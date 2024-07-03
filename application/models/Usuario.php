<?php
/*
    VIEWS DEL SISTEMA:
    usuario_permisos para busquedas más eficientes

    ___________________________________________
    permisos: CRUD -- Create-Read-Update-Delete -> posiciones en array:  3-2-1-0
*/
class Usuario
{
    public $db; //variable para instanciar el objeto PDO
    public function __construct(){
        $this->db = new Base(); //se instancia el objeto con los métodos de PDO
    }

    public function getUserById($id_user = null){ //consulta datos sirve también para el inicio la asignación de permisos del usuario
        if ($id_user != null) {
            $sql = "
                    SELECT  usuario.*,
                            EXPORT_SET(permisos.Seguimientos,'1','0','',4) AS Seguimientos,
                            EXPORT_SET(permisos.Evento_D,'1','0','',4) AS Evento_D,
                            EXPORT_SET(permisos.Entrevistas,'1','0','',4) AS Entrevistas,
                            EXPORT_SET(permisos.Redes_V,'1','0','',4) AS Red,
                            permisos.Visualizacion,
                            permisos.Modo_Admin,
                            AES_DECRYPT(usuario.Password,'".CRYPTO_KEY."') as Pass_Decrypt
                    FROM usuario
                    LEFT JOIN permisos ON permisos.Id_Permisos = usuario.Id_Permisos
                    WHERE usuario.Id_Usuario = $id_user
                    ";
            $this->db->query($sql);
            return $this->db->register();
        }
        else return false;
    }

    public function getUsers(){   //funcion creada para obtener los registros de los usuarios
        $sqlAux = "SELECT usuario.*,permisos.* 
                    FROM usuario
                    LEFT JOIN permisos ON permisos.Id_Permisos = usuario.Id_Permisos";  //query a la DB
        $this->db->query($sqlAux);          //se prepara el query mediante PDO
        return $this->db->registers();      //retorna todos los registros devueltos por la consulta
    }

    public function loginUser($post){ //consulta datos para el login
        $sqlAux = " SELECT  usuario.*, 
                            EXPORT_SET(permisos.Seguimientos,'1','0','',4) AS Seguimientos,
                            EXPORT_SET(permisos.Evento_D,'1','0','',4) AS Evento_D,
                            EXPORT_SET(permisos.Entrevistas,'1','0','',4) AS Entrevistas,
                            EXPORT_SET(permisos.Redes_V,'1','0','',4) AS Red,
                            permisos.Visualizacion,
                            permisos.Modo_Admin
                    FROM usuario
                    LEFT JOIN permisos ON permisos.Id_Permisos = usuario.Id_Permisos
                    WHERE Estatus=1 AND User_Name='".$post['User_Name']."' AND Password = AES_ENCRYPT('".$post['Password']."','".CRYPTO_KEY."')";

        $this->db->query($sqlAux);
        return $this->db->register();
    }
    public function visualizacion($valor){ //sirve para ocultar los datos de entrevistas y redes de vinculo
        $response['status'] = true;
        try {
            $this->db->beginTransaction();
            $sql = "UPDATE permisos SET Visualizacion = ".$valor;
            $this->db->query($sql);
            $this->db->execute();
            $this->db->commit();
        } catch (Exception $e) {
            $response['status'] = false;
            $response['error_message'] = 'Hubo un error en la Base de datos.';
            $response['error_sql'] = $sql;
            $this->db->rollBack();
        } 
        return $response;
    }
    public function insertNewUser($post,$file_name = null){ //inserta un nuevo usuario al sistema
        if ($file_name == null) {
            $file_name = "default.png";
        }
        $dataReturn['success'] = '0';
        $dataReturn['errorForm'] = null;

        $sinErrorForm = true;   //bandera que marca error en el formulario
        try {  
            $this->db->beginTransaction(); //inicio de transaction
            //se comprueba si existe un usuario con mismo email
            $this->db->query("SELECT Id_Usuario FROM usuario WHERE Email = '".(trim($post['Email']))."'");
            if ($this->db->register()) {
                $sinErrorForm = false;
                $dataReturn['errorForm']['Email'] = '<p class="text-danger">Este email ya ha sido registrado</p>';
            }
        
            //se comprueba si existe un usuario con mismo user_name
            $this->db->query("SELECT Id_Usuario FROM usuario WHERE User_Name = '".(trim($post['User_Name']))."'");
            if ($this->db->register()) {
                $sinErrorForm = false;
                $dataReturn['errorForm']['User_Name'] = '<p class="text-danger">Este nombre de usuario ya existe</p>';
            }
            //si el username y email son distintos entonces se procede a armar los permisos y asignar un id_permisos o crearlos (si es necesario)
                if ($sinErrorForm){

                    $id_permisos_new_user = 1; //permisos temporal

                    if (isset($post['Modo_Admin'])) { //permisos de Admin
                        $this->db->query("SELECT Id_Permisos FROM permisos WHERE Modo_Admin = 1");
                        $Permisos_Dios = $this->db->register();
                        
                        if ($Permisos_Dios) {
                            $id_permisos_new_user = $Permisos_Dios->Id_Permisos;
                        }
                        else{//permiso dios no existe, entonces se crea un permisos nuevo con modo Dios activado
                            //se crea un nuevo permiso de modo Dios
                            $this->db->query("INSERT INTO permisos (Modo_Admin) VALUES (1)");
                            $this->db->execute();
                            $this->db->query("SELECT LAST_INSERT_ID() as Id_Permisos"); //se recupera el id de permisos creado recientemente
                            $id_permisos_new_user = $this->db->register()->Id_Permisos;
                        }
                    }else{   //permisos conforme a los check dados en la matriz de permisos
                        //nuevos permisos
                        $Seguimientos="";$Eventos="";$Entrevistas="";$Redes="";

                        $Seguimientos.= (isset($post['S_Create']))?'1':'0'; 
                        $Seguimientos.= (isset($post['S_Read']))?'1':'0'; 
                        $Seguimientos.= (isset($post['S_Update']))?'1':'0'; 
                        $Seguimientos.= (isset($post['S_Delete']))?'1':'0';

                        $Eventos.= (isset($post['E_Create']))?'1':'0'; 
                        $Eventos.= (isset($post['E_Read']))?'1':'0'; 
                        $Eventos.= (isset($post['E_Update']))?'1':'0'; 
                        $Eventos.= (isset($post['E_Delete']))?'1':'0';

                        $Redes.= (isset($post['Red_Create']))?'1':'0'; 
                        $Redes.= (isset($post['Red_Read']))?'1':'0'; 
                        $Redes.= (isset($post['Red_Update']))?'1':'0'; 
                        $Redes.= (isset($post['Red_Delete']))?'1':'0';

                        $Entrevistas.= (isset($post['Entrevista_Create']))?'1':'0'; 
                        $Entrevistas.= (isset($post['Entrevista_Read']))?'1':'0'; 
                        $Entrevistas.= (isset($post['Entrevista_Update']))?'1':'0'; 
                        $Entrevistas.= (isset($post['Entrevista_Delete']))?'1':'0';

                        $this->db->query("  SELECT  Id_Permisos 
                                            FROM    permisos 
                                            WHERE  
                                                    Seguimientos = b'".$Seguimientos."' AND 
                                                    Evento_D = b'".$Eventos."' AND
                                                    Entrevistas = b'".$Entrevistas."' AND  
                                                    Redes_V = b'".$Redes."' AND  
                                                    Modo_Admin = 0");
                        $Permisos = $this->db->register();
                        if ($Permisos) {
                            //se obtine el id de los permisos que coinciden con las marcas dadas
                            $id_permisos_new_user = $Permisos->Id_Permisos;
                        }
                        else{//permiso dios no existe, entonces se crea un permisos nuevo con modo Dios activado
                            //se crea un nuevo permiso de modo Dios
                            $this->db->query("INSERT INTO   permisos 
                                                            (Seguimientos,Evento_D,Entrevistas,Redes_V) 
                                                            VALUES (    
                                                                        b'".$Seguimientos."',
                                                                        b'".$Eventos."',
                                                                        b'".$Entrevistas."',
                                                                        b'".$Redes."'
                                                                    )");
                            $this->db->execute();
                            $this->db->query("SELECT LAST_INSERT_ID() as Id_Permisos"); //se recupera el id de permisos creado recientemente
                            $id_permisos_new_user = $this->db->register()->Id_Permisos;
                        }
                    }

                    $auxNivelUser = (isset($post['Nivel_User']))?1:0;
                    $sqlAux1 = "
                                INSERT INTO usuario (Nombre,Ap_Paterno,Ap_Materno,Area,Email,User_Name,Password,Estatus,Id_Permisos,Path_Imagen_User)
                                VALUES  ('".trim($post['Nombre'])."', 
                                        '".trim($post['Ap_Paterno'])."',
                                        '".trim($post['Ap_Materno'])."',
                                        '".trim($post['Area'])."',
                                        '".trim($post['Email'])."',
                                        '".trim($post['User_Name'])."',
                                        '".trim($post['Password'])."',
                                         ".trim($post['Estatus'])." ,
                                         ".$id_permisos_new_user.",
                                         '".$file_name."'
                                         )
                                ";
                    $this->db->query($sqlAux1);
                    $this->db->execute();
                    //recuperar el Id del último usuario registrado
                    $this->db->query("SELECT LAST_INSERT_ID() as Id_Usuario"); //se recupera el id de permisos creado recientemente
                    $dataReturn['id_new_user'] = $this->db->register()->Id_Usuario;
                }
                    
            $this->db->commit();  //si todo sale bien, la transaction realiza commit de los queries
          
            //se comprueban las banderas para comprobar los resultados y dar respuesta al controlador
            if (!$sinErrorForm) {   //error en formulario
                //echo "caí en sinErrorForm";
                $dataReturn['success'] = '-1';
            }
            else{   //sin cambios en la información
                //echo "caí en sin cambios";
                $dataReturn['success'] = '1';
            }
        }catch (Exception $e) {
            $this->db->rollBack();    //si algo falla realiza el rollBack por seguridad
            echo "Fallo en DB: " . $e->getMessage();
            $dataReturn['success'] = '-2';
            $dataReturn['errorForm'] = null;
        }
        return $dataReturn;
    }

    public function updateUserInfo($post){ //actualización de información de un usuario
        $dataReturn['success'] = '0';
        $dataReturn['errorForm'] = null;

        $sinErrorForm = true;   //bandera que marca error en el formulario
        $cambiosUserInfo = false;   //bandera para comprobar si se esta actualizando algo direfente al anterior o no
        $cambiosPermisos = false;

        $sqlUserInfo = "SELECT  usuario.*,
                                EXPORT_SET(permisos.Seguimientos,'1','0','',4) AS Seguimientos,
                                EXPORT_SET(permisos.Evento_D,'1','0','',4) AS Evento_D,
                                EXPORT_SET(permisos.Entrevistas,'1','0','',4) AS Entrevistas,
                                EXPORT_SET(permisos.Redes_V,'1','0','',4) AS Red,
                                permisos.Modo_Admin,  
                                AES_DECRYPT(Password,'".CRYPTO_KEY."') as Pass_Decrypt FROM usuario 
                                LEFT JOIN permisos ON permisos.Id_Permisos = usuario.Id_Permisos 
                                WHERE Id_Usuario = ".$post['Id_Usuario']."";
        $this->db->query($sqlUserInfo);
        $dataBefore = $this->db->register();
        //convert stdClass to array
        $dataBefore = json_decode(json_encode($dataBefore), true);
        $post['Nivel_User'] = (isset($post['Nivel_User']))?1:0; //se modifica para ver si hubo cambio en nivel de user (Validaciones remisiones)

        if ($dataBefore) { //existe el usuario con el id del post?
            try {  
                $this->db->beginTransaction(); //inicio de transaction
                    //buscando diferencias en los valores nuevos y viejos de la info de User
                
                    //buscando diferencias en el formulario
                    if (($post['Nombre'] != $dataBefore['Nombre']) || ($post['Ap_Paterno'] != $dataBefore['Ap_Paterno']) || ($post['Ap_Materno'] != $dataBefore['Ap_Materno']) || ($post['Email'] != $dataBefore['Email']) || ($post['Area'] != $dataBefore['Area']) || ($post['User_Name'] != $dataBefore['User_Name']) || ($post['Password'] != $dataBefore['Pass_Decrypt']) || ($post['Estatus'] != $dataBefore['Estatus']) || ($post['Nivel_User'] != $dataBefore['Nivel_User'])) {
                        
                        if ($post['Email'] != $dataBefore['Email']) {
                            //se comprueba si existe un usuario con mismo email
                            $this->db->query("SELECT Id_Usuario FROM usuario WHERE Email = '".(trim($post['Email']))."'");
                            if ($this->db->register()) {
                                $sinErrorForm = false;
                                $dataReturn['errorForm']['Email'] = '<p class="text-danger">Este email ya ha sido registrado</p>';
                            }
                        }
                        
                        if ($post['User_Name'] != $dataBefore['User_Name']) {
                            //se comprueba si existe un usuario con mismo user_name
                            $this->db->query("SELECT Id_Usuario FROM usuario WHERE User_Name = '".(trim($post['User_Name']))."'");
                            if ($this->db->register()) {
                                $sinErrorForm = false;
                                $dataReturn['errorForm']['User_Name'] = '<p class="text-danger">Este nombre de usuario ya existe</p>';
                            }
                        }
                        
                        if ($sinErrorForm) {
                            $cambiosUserInfo = true;
                            $sqlAux1 = "
                                        UPDATE  usuario 
                                        SET     Nombre = '".trim($post['Nombre'])."', 
                                                Ap_Paterno = '".trim($post['Ap_Paterno'])."',
                                                Ap_Materno = '".trim($post['Ap_Materno'])."',
                                                Area = '".trim($post['Area'])."',
                                                Email = '".trim($post['Email'])."',
                                                User_Name = '".trim($post['User_Name'])."',
                                                usuario.Password = '".trim($post['Password'])."',
                                                Estatus = ".trim($post['Estatus'])." 
                                        WHERE   Id_Usuario = ".$post['Id_Usuario']."
                                        ";
                            $this->db->query($sqlAux1);
                            $this->db->execute();
                        }
                        
                    }
                    //comprobacion de cambios en Permisos de usuario
                    //modo Dios
                    if ($sinErrorForm) {
                        $Modo_Admin_Now = (isset($post['Modo_Admin']))?1:0;

                        //cambio a permisos de Dios por lo que solo se busca un permiso que coincida o si no se crea uno (ya no se toman n cuenta los demas permisos)
                        if (($Modo_Admin_Now != $dataBefore['Modo_Admin']) && ($Modo_Admin_Now == 1)) {
                            $cambiosPermisos = true;
                            $this->db->query("SELECT Id_Permisos FROM permisos WHERE Modo_Admin = 1");
                            $Permisos_Dios = $this->db->register();
                            if ($Permisos_Dios) {
                                //actualiza el id de permisos al usuario en cuestion
                                $sqlAux2 = "UPDATE usuario SET Id_Permisos = $Permisos_Dios->Id_Permisos WHERE Id_Usuario = ".$post['Id_Usuario']."";
                                $this->db->query($sqlAux2);
                                $this->db->execute();
                            }
                            else{//permiso dios no existe, entonces se crea un permisos nuevo con modo Dios activado
                                //se crea un nuevo permiso de modo Dios
                                $this->db->query("INSERT INTO permisos (Modo_Admin) VALUES (1)");
                                $this->db->execute();
                                $this->db->query("SELECT LAST_INSERT_ID() as Id_Permisos"); //se recupera el id de permisos creado recientemente
                                $id_new_permisos = $this->db->register()->Id_Permisos;
                                //se actualiza el id permisos al usuario en cuestion
                                $sqlAux2 = "UPDATE usuario SET Id_Permisos = $id_new_permisos WHERE Id_Usuario = ".$post['Id_Usuario']."";
                                $this->db->query($sqlAux2);
                                $this->db->execute();
                            }
                        }
                        elseif (($Modo_Admin_Now != $dataBefore['Modo_Admin']) && ($Modo_Admin_Now == 0)) { //se cambian los otros permisos
                            $cambiosPermisos = true;
                            //nuevos permisos
                            $Seguimientos="";$Eventos="";$Entrevistas="";$Redes="";

                            $Seguimientos.= (isset($post['S_Create']))?'1':'0'; 
                            $Seguimientos.= (isset($post['S_Read']))?'1':'0'; 
                            $Seguimientos.= (isset($post['S_Update']))?'1':'0'; 
                            $Seguimientos.= (isset($post['S_Delete']))?'1':'0';

                            $Eventos.= (isset($post['E_Create']))?'1':'0'; 
                            $Eventos.= (isset($post['E_Read']))?'1':'0'; 
                            $Eventos.= (isset($post['E_Update']))?'1':'0'; 
                            $Eventos.= (isset($post['E_Delete']))?'1':'0';

                            $Redes.= (isset($post['Red_Create']))?'1':'0'; 
                            $Redes.= (isset($post['Red_Read']))?'1':'0'; 
                            $Redes.= (isset($post['Red_Update']))?'1':'0'; 
                            $Redes.= (isset($post['Red_Delete']))?'1':'0';

                            $Entrevistas.= (isset($post['Entrevista_Create']))?'1':'0'; 
                            $Entrevistas.= (isset($post['Entrevista_Read']))?'1':'0'; 
                            $Entrevistas.= (isset($post['Entrevista_Update']))?'1':'0'; 
                            $Entrevistas.= (isset($post['Entrevista_Delete']))?'1':'0';

                            $this->db->query("  SELECT  Id_Permisos 
                                                FROM    permisos 
                                                WHERE   Seguimientos = b'".$Seguimientos."' AND 
                                                        Evento_D = b'".$Eventos."' AND 
                                                        Entrevistas = b'".$Entrevistas."' AND
                                                        Redes_V = b'".$Redes."' AND  
                                                        Modo_Admin = 0");
                            $Permisos = $this->db->register();
                            if ($Permisos) {
                                //actualiza el id de permisos al usuario en cuestion
                                $sqlAux2 = "UPDATE usuario SET Id_Permisos = $Permisos->Id_Permisos WHERE Id_Usuario = ".$post['Id_Usuario']."";
                                $this->db->query($sqlAux2);
                                $this->db->execute();
                            }
                            else{//permiso dios no existe, entonces se crea un permisos nuevo con modo Dios activado
                                //se crea un nuevo permiso de modo Dios
                                $this->db->query("INSERT INTO   permisos 
                                                                (Seguimientos,Evento_D,Entrevistas,Redes_V) 
                                                                VALUES (    
                                                                            b'".$Seguimientos."',
                                                                            b'".$Eventos."',
                                                                            b'".$Entrevistas."',
                                                                            b'".$Redes."'
                                                                        )");
                                $this->db->execute();
                                $this->db->query("SELECT LAST_INSERT_ID() as Id_Permisos"); //se recupera el id de permisos creado recientemente
                                $id_new_permisos = $this->db->register()->Id_Permisos;
                                //se actualiza el id permisos al usuario en cuestion
                                $sqlAux2 = "UPDATE usuario SET Id_Permisos = $id_new_permisos WHERE Id_Usuario = ".$post['Id_Usuario']."";
                                $this->db->query($sqlAux2);
                                $this->db->execute();
                            }
                            
                        }
                        elseif(($Modo_Admin_Now == $dataBefore['Modo_Admin']) && ($Modo_Admin_Now == 0)){ //No cambio el modo Dios pero puede que si los deás permisos
                            //nuevos permisos
                            $Seguimientos="";$Eventos="";$Entrevistas="";$Redes="";

                            $Seguimientos.= (isset($post['S_Create']))?'1':'0'; 
                            $Seguimientos.= (isset($post['S_Read']))?'1':'0'; 
                            $Seguimientos.= (isset($post['S_Update']))?'1':'0'; 
                            $Seguimientos.= (isset($post['S_Delete']))?'1':'0';

                            $Eventos.= (isset($post['E_Create']))?'1':'0'; 
                            $Eventos.= (isset($post['E_Read']))?'1':'0'; 
                            $Eventos.= (isset($post['E_Update']))?'1':'0'; 
                            $Eventos.= (isset($post['E_Delete']))?'1':'0';

                            $Redes.= (isset($post['Red_Create']))?'1':'0'; 
                            $Redes.= (isset($post['Red_Read']))?'1':'0'; 
                            $Redes.= (isset($post['Red_Update']))?'1':'0'; 
                            $Redes.= (isset($post['Red_Delete']))?'1':'0';

                            $Entrevistas.= (isset($post['Entrevista_Create']))?'1':'0'; 
                            $Entrevistas.= (isset($post['Entrevista_Read']))?'1':'0'; 
                            $Entrevistas.= (isset($post['Entrevista_Update']))?'1':'0'; 
                            $Entrevistas.= (isset($post['Entrevista_Delete']))?'1':'0';


                            if (($Seguimientos != $dataBefore['Seguimientos']) || 
                                ($Eventos != $dataBefore['Evento_D']) || ($Entrevistas != $dataBefore['Entrevistas'])|| ($Redes != $dataBefore['Redes_V'])){

                                $cambiosPermisos = true;

                                $this->db->query(" SELECT  Id_Permisos 
                                                    FROM    permisos 
                                                    WHERE   Seguimientos = b'".$Seguimientos."' AND 
                                                            Evento_D = b'".$Eventos."' AND 
                                                            Entrevistas = b'".$Entrevistas."' AND 
                                                            Redes_V = b'".$Redes."' AND  
                                                            Modo_Admin = 0");

                                $Permisos = $this->db->register();
                                if ($Permisos) {
                                    //actualiza el id de permisos al usuario en cuestion
                                    $sqlAux2 = "UPDATE usuario SET Id_Permisos = $Permisos->Id_Permisos WHERE Id_Usuario = ".$post['Id_Usuario']."";
                                    $this->db->query($sqlAux2);
                                    $this->db->execute();
                                }
                                else{//permiso dios no existe, entonces se crea un permisos nuevo con modo Dios DESACTIVADO
                                    $this->db->query("INSERT INTO   permisos 
                                                                    (Seguimientos,Evento_D,Entrevistas,Redes_V) 
                                                                    VALUES (    
                                                                                b'".$Seguimientos."',
                                                                                b'".$Eventos."',
                                                                                b'".$Entrevistas."',
                                                                                b'".$Redes."'
                                                                            )");
                                    $this->db->execute();
                                    $this->db->query("SELECT LAST_INSERT_ID() as Id_Permisos"); //se recupera el id de permisos creado recientemente
                                    $id_new_permisos = $this->db->register()->Id_Permisos;
                                    //se actualiza el id permisos al usuario en cuestion
                                    $sqlAux2 = "UPDATE usuario SET Id_Permisos = $id_new_permisos WHERE Id_Usuario = ".$post['Id_Usuario']."";
                                    $this->db->query($sqlAux2);
                                    $this->db->execute();
                                }
                            }
                        }
                    }
                        
                $this->db->commit();  //si todo sale bien, la transaction realiza commit de los queries
              
                //se comprueban las banderas para comprobar los resultados y dar respuesta al controlador
                if (!$sinErrorForm) {   //error en formulario
                    //echo "caí en sinErrorForm";
                    $dataReturn['success'] = '-1';
                }
                elseif ($cambiosUserInfo || $cambiosPermisos) { //actualizacion correcta
                    //echo "caí en correcto";
                    $dataReturn['success'] = '1';
                }
                else{   //sin cambios en la información
                    //echo "caí en sin cambios";
                    $dataReturn['success'] = '0';
                }
            }catch (Exception $e) {
                $this->db->rollBack();    //si algo falla realiza el rollBack por seguridad
                echo "Fallo en DB: " . $e->getMessage();
                $dataReturn['success'] = '-2';
                $dataReturn['errorForm'] = null;
            }
        }

        return $dataReturn;
    }

    public function updateImgNameUser($foto_name,$id_user){
        //actualizar el nombre de la imágen del usuario
        $sqlAux1 = "
                    UPDATE usuario 
                    SET Path_Imagen_User = '".$foto_name."'
                    WHERE Id_Usuario = ".$id_user."
                    ";
        $this->db->query($sqlAux1);
        $this->db->execute();
    }

    public function generateWhereSentence($cadena){ //consulta datos de usuarios
        $where_sentence = "";
        if ($cadena != "") {
            $where_sentence = "
                        WHERE   User_Name LIKE '%".$cadena."%' OR
                                Nombre_Completo LIKE '%".$cadena."%' OR
                                Email LIKE '%".$cadena."%' OR
                                Area LIKE '%".$cadena."%'  OR
                                Refe1 LIKE '%".$cadena."%'  OR
                                Refe_Temp LIKE '%".$cadena."%'  
                        ";
        }
        
        return $where_sentence;
    }

    public function decToBin($numDec){ //decimal a binario
            $dataReturn = decbin($numDec);

            while (strlen($dataReturn) < 4) {   //se agregan ceros a la izquierda para siempre tener un string de 4 bits
                $dataReturn = "0".$dataReturn;
            }
            return $dataReturn;
    }

    //función para actualizar la contraseña del usuario en módulo de Cuenta
    public function updateUserPassword($post){

        $dataReturn['success'] = '0';
        $dataReturn['errorForm'] = null;

        $sinErrorForm = true;   //bandera que marca error en el formulario
        $cambiosUserInfo = false;   //bandera para comprobar si se esta actualizando algo direfente al anterior o no
        

        $sqlUserInfo = "SELECT usuario.*, AES_DECRYPT(Password,'".CRYPTO_KEY."') as Pass_Decrypt FROM usuario LEFT JOIN permisos ON permisos.Id_Permisos = usuario.Id_Permisos WHERE Id_Usuario = ".$_SESSION['userdataSIC']->Id_Usuario."";
        $this->db->query($sqlUserInfo);
        $dataBefore = $this->db->register();
        //convert stdClass to array
        $dataBefore = json_decode(json_encode($dataBefore), true);

        if ($dataBefore) { //existe el usuario con el id del post?
            try {  
                $this->db->beginTransaction(); //inicio de transaction                
                    //buscando diferencias en el formulario
                    if ($post['Password'] != $dataBefore['Pass_Decrypt']) {
                        $cambiosUserInfo = true;
                        $sqlAux1 = "
                                    UPDATE  usuario 
                                    SET     usuario.Password = '".trim($post['Password'])."'
                                    WHERE   Id_Usuario = ".$_SESSION['userdataSIC']->Id_Usuario."
                                    ";
                        $this->db->query($sqlAux1);
                        $this->db->execute();
                    }
                    //comprobacion de cambios en Permisos de usuario
                    //modo Dios
                      
                $this->db->commit();  //si todo sale bien, la transaction realiza commit de los queries
              
                //se comprueban las banderas para comprobar los resultados y dar respuesta al controlador
                if (!$sinErrorForm) {   //error en formulario
                    //echo "caí en sinErrorForm";
                    $dataReturn['success'] = '-1';
                }
                elseif ($cambiosUserInfo) { //actualizacion correcta
                    //echo "caí en correcto";
                    $dataReturn['success'] = '1';
                    

                }
                else{   //sin cambios en la información
                    //echo "caí en sin cambios";
                    $dataReturn['success'] = '0';
                }
            }catch (Exception $e) {
                $this->db->rollBack();    //si algo falla realiza el rollBack por seguridad
                echo "Fallo en DB: " . $e->getMessage();
                $dataReturn['success'] = '-2';
                $dataReturn['errorForm'] = null;
            }
        }

        return $dataReturn;
    }


    /*-----FUNCIONES PARA FILTRADO Y BUSQUEDA ACTUALIZADOS (WHERE SENTENCE)-----*/
    //obtener el total de páginas y de registros de la consulta
    public function getTotalPages($no_of_records_per_page,$from_where_sentence = ""){
        //quitamos todo aquello que este fuera de los parámetros para solo obtener el substring desde FROM
        $from_where_sentence = strstr($from_where_sentence, 'FROM');

        $sql_total_pages = "SELECT COUNT(*) as Num_Pages ".$from_where_sentence; //total registros
        $this->db->query($sql_total_pages);      //prepararando query
        $total_rows = $this->db->register()->Num_Pages; //ejecutando query y recuperando el valor obtenido
        $total_pages = ceil($total_rows / $no_of_records_per_page); //calculando el total de paginations

        $data['total_rows'] = $total_rows;
        $data['total_pages'] = $total_pages;
        return $data;
    }

    //obtener los registros de la pagina actual
    public function getDataCurrentPage($offset,$no_of_records_per_page,$from_where_sentence = ""){//realiza la consulta de datos conforme a la generacion dinámica de la consulta de datos

        $sql = "
                SELECT * "
                .$from_where_sentence." 
                LIMIT $offset,$no_of_records_per_page
                ";

        $this->db->query($sql);
        return $this->db->registers();
    }

    //genera la consulta where dependiendo del filtro
    public function generateFromWhereSentence($cadena="",$filtro='1'){

        $from_where_sentence = "";
        switch ($filtro) {
            case '1':   //todos
                
                $from_where_sentence.= " FROM usuario_permisos 
                                         WHERE      (User_Name LIKE '%".$cadena."%' OR
                                                    Nombre_Completo LIKE '%".$cadena."%' OR
                                                    Email LIKE '%".$cadena."%' OR
                                                    Area LIKE '%".$cadena."%'  OR
                                                    Refe1 LIKE '%".$cadena."%'  OR
                                                    Refe_Temp LIKE '%".$cadena."%')  
                                            ";
                
            break;
            case '2':   //administradores
                $from_where_sentence.= " FROM usuario_permisos 
                                         WHERE      (User_Name LIKE '%".$cadena."%' OR
                                                    Nombre_Completo LIKE '%".$cadena."%' OR
                                                    Email LIKE '%".$cadena."%' OR
                                                    Area LIKE '%".$cadena."%'  OR
                                                    Refe1 LIKE '%".$cadena."%'  OR
                                                    Refe_Temp LIKE '%".$cadena."%') 
                                                    AND Modo_Admin = 1 
                                            ";
                
            break;
            case '3':   //otros
                $from_where_sentence.= " FROM usuario_permisos 
                                         WHERE      (User_Name LIKE '%".$cadena."%' OR
                                                    Nombre_Completo LIKE '%".$cadena."%' OR
                                                    Email LIKE '%".$cadena."%' OR
                                                    Area LIKE '%".$cadena."%'  OR
                                                    Refe1 LIKE '%".$cadena."%'  OR
                                                    Refe_Temp LIKE '%".$cadena."%')
                                                    AND Modo_Admin = 0 
                                            ";
                
            break;

                /*
                    SELECT * FROM evento_delictivo_view WHERE Fecha >= '2020-01-01' AND Fecha <= '2020-03-30'
                */
        }

        //where complemento fechas (si existe)
        $from_where_sentence.= $this->getFechaCondition();
        //order by
        $from_where_sentence.= " ORDER BY Id_Usuario";   
        return $from_where_sentence;
    }

    public function getUsersByCadena($cadena,$filtro='1'){
        //CONSULTA COINCIDENCIAS DE CADENA PARA LOS USUARIOS
        if (!is_numeric($filtro) || !($filtro>=MIN_FILTRO_USER) || !($filtro<=MAX_FILTRO_USER))
            $filtro = 1;
        
        //sentencia from_where para hacer la busqueda por la cadena ingresada
        $from_where_sentence = $this->generateFromWhereSentence($cadena,$filtro);
        $numPage = 1;
        $no_of_records_per_page = NUM_MAX_REG_PAGE; //total de registros por pagination
        $offset = ($numPage-1) * $no_of_records_per_page; // desplazamiento conforme a la pagina

        $results = $this->getTotalPages($no_of_records_per_page,$from_where_sentence);  //total de páginas conforme a la busqueda
        //info de retorno para la creacion de los links conforme a la cadena ingresada
        $data['rows_Users'] = $this->getDataCurrentPage($offset,$no_of_records_per_page,$from_where_sentence);   //se obtiene la información de la página actual
        $data['numPage'] = $numPage; //numero pag actual para la pagination footer
        $data['total_pages'] = $results['total_pages']; //total pages para la pagination
        $data['total_rows'] = $results['total_rows'];   //total de registro hallados
        
        return $data;
    }
    
    //obtener todos los registros de un cierto filtro para su exportación
    public function getAllInfoUsersByCadena($from_where_sentence = ""){
        $sqlAux = "SELECT *"
                    .$from_where_sentence."
                    ";  //query a la DB
        $this->db->query($sqlAux);          //se prepara el query mediante PDO
        return $this->db->registers();      //retorna todos los registros devueltos por la consulta
    }

    //Se obtiene la IP del usuario para insertarla en la tabla historial
    public function obtenerIp()
    {
        $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $hosts = gethostbynamel($hostname);
        if (is_array($hosts)) 
        {
            //echo "Host ".$hostname." ip:<br><br>";
            foreach ($hosts as $ip) 
            {
                //echo "IP: ".$ip."<br>";
                return $ip;
            }
        }

        else 
        {
            return $ip="No se encontró IP";
        }

    }

    //Funcion que inserta los movimientos de los usuarios en la tabla historial
    public function historical($idusuario,$descripcion)
    {
        $ip = $this->obtenerIp();
        //print_r($_SESSION['userdataSIC']);
        $sql="INSERT INTO historial (Id_Usuario,Fecha_Hora,Ip_Acceso,Descripcion) VALUES('$idusuario',CURRENT_TIMESTAMP(),'$ip','$descripcion')";
        $this->db->query($sql);
        $this->db->execute();
    }

    //función auxiliar para filtrar por un rango de fechas específicado por el usuario
    public function getFechaCondition(){
        $cad_fechas = "";
        if (isset($_SESSION['userdataSIC']->rango_inicio_user) && isset($_SESSION['userdataSIC']->rango_fin_user)) { //si no ingresa una fecha se seleciona el día de hoy como máximo
            $rango_inicio = $_SESSION['userdataSIC']->rango_inicio_user;
            $rango_fin = $_SESSION['userdataSIC']->rango_fin_user;
            $cad_fechas = " AND 
                            Fecha_Registro_Usuario >= '".$rango_inicio." 00:00:00'  AND 
                            Fecha_Registro_Usuario <= '".$rango_fin." 23:59:59' 
                            ";
        }

        return $cad_fechas; 
    }
    public function getNumeros($Id_User, $FechaInicio = "", $FechaFin = ""){//consulta datos para la estadística del usuarios en el reporte pdf
        $data = [];
        try{
            $sql = "SELECT
                        `usuario`.`Id_Usuario` AS `Id_Usuario`,
                        ucase(`usuario`.`User_Name`) AS `User_Name`,
                        `usuario`.`Fecha_Registro_Usuario` AS `Fecha_Registro_Usuario`,
                        `usuario`.`Area` AS `Area`,
                        `usuario`.`Path_Imagen_User` AS `Path_Imagen_User`,
                        ucase(CONCAT_WS('',`usuario`.`Nombre`,' ',`usuario`.`Ap_Paterno`,' ',`usuario`.`Ap_Materno`)) AS `Nombre_Completo`
                    FROM `usuario`
                    WHERE `usuario`.`Id_Usuario` = ".$Id_User;
            
            $this->db->query($sql);
            $aux = $this->db->register();             
            $data['Id_Usuario'] = $aux->Id_Usuario;
            $data['User_Name'] = $aux->User_Name;
            $data['Fecha_Registro_Usuario'] = $aux->Fecha_Registro_Usuario;
            $data['Area'] = $aux->Area;
            $data['Path_Imagen_User'] = $aux->Path_Imagen_User;
            $data['Nombre_Completo'] = $aux->Nombre_Completo;
            if($FechaInicio != "" && $FechaFin != ""){
                $sqlFecha = " AND 
                        ((`historial`.`Fecha_Hora` >= '" . $FechaInicio . " 00:00:00'  AND 
                        `historial`.`Fecha_Hora` <= '" . $FechaFin . " 23:59:59' )OR
                        (`historial`.`Fecha_Hora` >= CAST('" . $FechaInicio . " 00:00:00' AS date) AND
                        `historial`.`Fecha_Hora` <= CAST('" . $FechaFin . " 23:59:59' AS date)))
                ";
            }else{
                $sqlFecha ='';
            }

            $sql = "SELECT 
                        COUNT(*) AS `Conteo_Eventos_capturados` 
                    FROM `evento` 
                    WHERE `evento`.`Elemento_Captura` = '" .$data['User_Name']."'";
            if($FechaInicio != "" && $FechaFin != ""){
                $sql .= " AND 
                        ((`evento`.`FechaHora_Captura` >= '" . $FechaInicio . " 00:00:00'  AND 
                        `evento`.`FechaHora_Captura` <= '" . $FechaFin . " 23:59:59' )OR
                        (`evento`.`FechaHora_Captura` >= CAST('" . $FechaInicio . " 00:00:00' AS date) AND
                        `evento`.`FechaHora_Captura` <= CAST('" . $FechaFin . " 23:59:59' AS date)))
                ";
                $data['FechaInicio'] = $FechaInicio;
                $data['FechaFin'] = $FechaFin;
            }
            $this->db->query($sql);
            $aux = $this->db->register();  
            $data[0]['Eventos Capturados'] = $aux->Conteo_Eventos_capturados;

            $sql ="SELECT 
                    COUNT(*) AS `Conteo_Eventos_Asignados` 
                    FROM `evento` 
                    WHERE `evento`.`ClaveSeguimiento` = '".$data['User_Name']."' AND FechaHora_Captura >= '2024-01-01 00:00:00'";
            $this->db->query($sql);  
            $aux = $this->db->register();  
            $data[1]['Eventos Asignados'] = $aux->Conteo_Eventos_Asignados;

            $sql ="SELECT 
                    COUNT(*) AS `Conteo_Seguimiento_Terminado` 
                FROM `evento` WHERE `evento`.`SeguimientoTerminado`= 1 
                AND `evento`.`ClaveSeguimiento` = '".$data['User_Name']."' AND FechaHora_Captura >= '2024-01-01 00:00:00'";
            $this->db->query($sql);
            $aux = $this->db->register();  
            $data[1]['Eventos con Seguimiento Terminado'] = $aux->Conteo_Seguimiento_Terminado;

            $sql ="SELECT 
                    COUNT(DISTINCT `fotos_seguimiento`.`Folio_infra`) AS `Conteo_Eventos_Fotos` 
                    FROM `fotos_seguimiento` 
                    WHERE `fotos_seguimiento`.`Capturo` = '".$data['User_Name']."'";
            if($FechaInicio != "" && $FechaFin != ""){
                $sql .= " AND 
                        ((`fotos_seguimiento`.`fecha_hora_captura_sistema` >= '" . $FechaInicio . " 00:00:00'  AND 
                        `fotos_seguimiento`.`fecha_hora_captura_sistema` <= '" . $FechaFin . " 23:59:59' )OR
                        (`fotos_seguimiento`.`fecha_hora_captura_sistema` >= CAST('" . $FechaInicio . " 00:00:00' AS date) AND
                        `fotos_seguimiento`.`fecha_hora_captura_sistema` <= CAST('" . $FechaFin . " 23:59:59' AS date)))
                ";
            }
            $this->db->query($sql);  
            $aux = $this->db->register();
            $data[0]['Captura de Fotos en Eventos'] = $aux->Conteo_Eventos_Fotos;

            $sql ="SELECT 
                COUNT(DISTINCT `entrevistas_seguimiento`.`Folio_infra`) AS `Conteo_Entrevistas_Realizadas_Eventos` 
                FROM `entrevistas_seguimiento` 
                WHERE `entrevistas_seguimiento`.`entrevistador` = '".$data['User_Name']."'";
            if($FechaInicio != "" && $FechaFin != ""){
                $sql .= " AND 
                            fecha_entrevista >= '".$FechaInicio."'  AND 
                            fecha_entrevista <= '".$FechaFin."' 
                            ";
            }
            $this->db->query($sql);  
            $aux = $this->db->register();
            $data[0]['Entrevistas Realizadas en los Eventos'] = $aux->Conteo_Entrevistas_Realizadas_Eventos;

            $sql ="SELECT 
                COUNT(*) AS `Conteo_Consultas_Eventos` 
                FROM `historial` 
                WHERE `historial`.`Movimiento` = 7 
                AND `historial`.`Id_Usuario` = ".$data['Id_Usuario'];
            $sql = $sql.$sqlFecha;
            $this->db->query($sql);  
            $aux = $this->db->register();
            $data[0]['Consultas en Modulo de Eventos'] = $aux->Conteo_Consultas_Eventos;

            $sql ="SELECT 
                COUNT(*) AS `Conteo_Actualizacion_Eventos` 
                FROM `historial` 
                WHERE (`historial`.`Movimiento` = 4 OR `historial`.`Movimiento` = 5 OR `historial`.`Movimiento` = 6) 
                AND `historial`.`Id_Usuario` = ".$data['Id_Usuario'];
            $sql = $sql.$sqlFecha;
            $this->db->query($sql);  
            $aux = $this->db->register();
            $data[0]['Actualizaciones en Modulo de Eventos'] = $aux->Conteo_Actualizacion_Eventos;

            $sql ="SELECT 
                COUNT(*) AS `Conteo_Exportacion_Eventos` 
                FROM `historial` 
                WHERE `historial`.`Movimiento` = 8 
                AND `historial`.`Id_Usuario` = ".$data['Id_Usuario'];
            $sql = $sql.$sqlFecha;
            $this->db->query($sql);  
            $aux = $this->db->register();
            $data[0]['Exportacion de Informacion Modulo de Eventos'] = $aux->Conteo_Exportacion_Eventos;



            $sql ="SELECT 
                COUNT(*) AS `Conteo_Seguimiento_Redes_Captura` 
                FROM `seguimiento_gabinete` 
                WHERE `seguimiento_gabinete`.`Elemento_Captura` = '".$data['User_Name']."'";
            if($FechaInicio != "" && $FechaFin != ""){
                $sql .= " AND 
                        ((`seguimiento_gabinete`.`FechaHora_Creacion` >= '" . $FechaInicio . " 00:00:00'  AND 
                        `seguimiento_gabinete`.`FechaHora_Creacion` <= '" . $FechaFin . " 23:59:59' )OR
                        (`seguimiento_gabinete`.`FechaHora_Creacion` >= CAST('" . $FechaInicio . " 00:00:00' AS date) AND
                        `seguimiento_gabinete`.`FechaHora_Creacion` <= CAST('" . $FechaFin . " 23:59:59' AS date)))
                ";
            }
            $this->db->query($sql);  
            $aux = $this->db->register();
            $data[0]['Captura de Red de Vinculo'] = $aux->Conteo_Seguimiento_Redes_Captura;

            $sql ="SELECT 
                COUNT(*) AS `Conteo_Consultas_Seguimientos_Redes` 
                FROM `historial` 
                WHERE `historial`.`Movimiento` = 24 
                AND `historial`.`Id_Usuario` = ".$data['Id_Usuario'];
            $sql = $sql.$sqlFecha;
            $this->db->query($sql);  
            $aux = $this->db->register();
            $data[0]['Consultas en Modulo de Redes de Vinculo'] = $aux->Conteo_Consultas_Seguimientos_Redes;

            $sql ="SELECT 
                COUNT(*) AS `Conteo_Actualizacion_Seguimiento_Redes` 
                FROM `historial` 
                WHERE (`historial`.`Movimiento` = 26 OR `historial`.`Movimiento` = 27) 
                AND `historial`.`Id_Usuario` = ".$data['Id_Usuario'];
            $sql = $sql.$sqlFecha;
            $this->db->query($sql);  
            $aux = $this->db->register();
            $data[0]['Actualizaciones en Modulo de Redes de Vinculo'] = $aux->Conteo_Actualizacion_Seguimiento_Redes;

            $sql ="SELECT 
                COUNT(*) AS `Conteo_Exportacion_Seguimientos_Redes` 
                FROM `historial` 
                WHERE `historial`.`Movimiento` = 29 
                AND `historial`.`Id_Usuario` = ".$data['Id_Usuario'];
            $sql = $sql.$sqlFecha;
            $this->db->query($sql);  
            $aux = $this->db->register();
            $data[0]['Exportacion de Informacion Modulo de Redes'] = $aux->Conteo_Exportacion_Seguimientos_Redes;

            $sql ="SELECT 
                    COUNT(*) AS `Conteo_Informacion_Detenido_Capturado` 
                    FROM `persona_entrevista` 
                    WHERE `persona_entrevista`.`Capturo` ='".$data['User_Name']."'";
            if($FechaInicio != "" && $FechaFin != ""){
                $sql .= " AND 
                        ((`persona_entrevista`.`FechaHora_Creacion` >= '" . $FechaInicio . " 00:00:00'  AND 
                        `persona_entrevista`.`FechaHora_Creacion` <= '" . $FechaFin . " 23:59:59' )OR
                        (`persona_entrevista`.`FechaHora_Creacion` >= CAST('" . $FechaInicio . " 00:00:00' AS date) AND
                        `persona_entrevista`.`FechaHora_Creacion` <= CAST('" . $FechaFin . " 23:59:59' AS date)))
                ";
            }
            $this->db->query($sql);  
            $aux = $this->db->register();
            $data[0]['Captura de Informacion Modulo de Entrevistas'] = $aux->Conteo_Informacion_Detenido_Capturado;

            $sql ="SELECT 
                    COUNT(DISTINCT `entrevista_detenido`.`Id_Entrevista`) AS `Conteo_Entrevistas_Realizadas_Detenidos` 
                    FROM `entrevista_detenido` 
                    WHERE `entrevista_detenido`.`Indicativo_Entrevistador` = '".$data['User_Name']."'";
            if($FechaInicio != "" && $FechaFin != ""){
                $sql .= " AND 
                        Fecha_Entrevista >= '".$FechaInicio."'  AND 
                        Fecha_Entrevista <= '".$FechaFin."' 
                ";
            }
            $this->db->query($sql);  
            $aux = $this->db->register();
            $data[0]['Entrevistas Realizadas'] = $aux->Conteo_Entrevistas_Realizadas_Detenidos;
            
            $sql ="SELECT 
                COUNT(*) AS `Conteo_Consultas_Entrevistas` 
                FROM `historial` 
                WHERE `historial`.`Movimiento` = 35 
                AND `historial`.`Id_Usuario` = ".$data['Id_Usuario'];
            $sql = $sql.$sqlFecha;
            $this->db->query($sql);  
            $aux = $this->db->register();
            $data[0]['Consultas en Modulo de Entrevistas'] = $aux->Conteo_Consultas_Entrevistas;


            $sql ="SELECT 
                COUNT(*) AS `Conteo_Actualizacion_Entrevistas` 
                FROM `historial` WHERE (`historial`.`Movimiento` = 32 OR `historial`.`Movimiento` = 33) 
                AND `historial`.`Id_Usuario` = ".$data['Id_Usuario'];
            $sql = $sql.$sqlFecha;
            $this->db->query($sql);  
            $aux = $this->db->register();
            $data[0]['Actualizaciones en Modulo de Entrevistas'] = $aux->Conteo_Actualizacion_Entrevistas;

            $sql ="SELECT 
                COUNT(*) AS `Conteo_Exportacion_Entrevistas` 
                FROM `historial` 
                WHERE `historial`.`Movimiento` = 36 
                AND `historial`.`Id_Usuario` = ".$data['Id_Usuario'];
            $sql = $sql.$sqlFecha;
            $this->db->query($sql);  
            $aux = $this->db->register();
            $data[0]['Exportacion de Informacion Modulo de Entrevistas'] = $aux->Conteo_Exportacion_Entrevistas;

            return $data;
        }catch(Exception $e){
            $data['success'] = false;
            $data['msg'] = 'Ubo un error en la consulta de la estadistica del usuario';
            $data['error'] = $e; 
            return $data;
        }
        
    }
}