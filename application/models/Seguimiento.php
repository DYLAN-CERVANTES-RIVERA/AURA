<?php
class Seguimiento{
    public $db; //Variable para instanciar el objeto PDO
    public $db2;
    public function __construct(){
        $this->db = new Base(); //Se instancia el objeto con los métodos de PDO
        $this->db2 = new Base2(); //Se instancia el objeto con los métodos de solo consulta
    }
    //genera la consulta where dependiendo del filtro
    public function generateFromWhereSentence($cadena = "", $filtro = '1',$cadena2 = ""){
        $from_where_sentence = "";
        switch ($filtro) {
            case '1':   //general
                $palabras = explode(",", strtolower($cadena));//Se obtiene la cadena con la cual se quiere buscar
                $articulos = array('el', 'la', 'los', 'las', 'un', 'de', 'en', 'unos', 'una', 'unas', 'a', 'con', 'y', 'o', 'u');//arreglo con las palabras a ignorar en la busqueda, normalmete artículos
                $palabras = array_diff($palabras, $articulos);
                $from_where_sentence .= " FROM gc_seguimiento_filtro_1 WHERE Id_Seguimiento > 0";
                foreach($palabras as $palabra){
                    $palabra=ltrim($palabra, " ");
                    $palabra=rtrim($palabra, " ");
                    $from_where_sentence.= " AND    (    Id_Seguimiento LIKE '%" . $palabra . "%' OR 
                                                        Elemento_Captura LIKE '%" . $palabra . "%' OR 
                                                        FechaHora_Creacion LIKE '%" . $palabra . "%' OR 
                                                        Nombre_grupo_delictivo LIKE '%" . $palabra . "%' OR 
                                                        Modus_operandi LIKE '%" . $palabra . "%' OR 
                                                        Peligrosidad LIKE '%" . $palabra . "%' OR 
                                                        Observaciones LIKE '%" . $palabra . "%' OR 
                                                        Zonas LIKE '%" . $palabra . "%' OR
                                                        Folios_infra LIKE '%" . $palabra . "%'OR
                                                        vehiculos_del_seguimiento LIKE '%" . $palabra . "%'OR
                                                        personas_del_seguimiento LIKE '%" . $palabra . "%'
                                                    ) ";          
                }
                if($_SESSION['userdataSIC']->Modo_Admin != 1){
                    if($_SESSION['userdataSIC']->Red[0] == 1){
                        $from_where_sentence.= " AND ( Alto_Impacto = 1) ";   
                    }else{
                        $from_where_sentence.= " AND ( Alto_Impacto = 0) ";  
                    }
                }
                $from_where_sentence .= $this->getFechaCondition();
                $from_where_sentence .= " ORDER BY Id_Seguimiento DESC";
            break;
            case '2':   //personas
                $palabras = explode(",", strtolower($cadena));//Se obtiene la cadena con la cual se quiere buscar
                $articulos = array('el', 'la', 'los', 'las', 'un', 'de', 'en', 'unos', 'una', 'unas', 'a', 'con', 'y', 'o', 'u');//arreglo con las palabras a ignorar en la busqueda, normalmete artículos
                $palabras = array_diff($palabras, $articulos);
                $from_where_sentence .= " FROM gc_seguimiento_filtro_2 WHERE Id_Seguimiento > 0";
                foreach($palabras as $palabra){
                    $palabra=ltrim($palabra, " ");
                    $palabra=rtrim($palabra, " ");
                    $from_where_sentence.= " AND    (   Id_Persona LIKE '%" . $palabra . "%' OR 
                                                        Id_Seguimiento LIKE '%" . $palabra . "%' OR 
                                                        Nombre_grupo_delictivo LIKE '%" . $palabra . "%' OR 
                                                        Capturo LIKE '%" . $palabra . "%' OR 
                                                        Nombre_completo LIKE '%" . $palabra . "%' OR 
                                                        Curp LIKE '%" . $palabra . "%' OR 
                                                        Telefono LIKE '%" . $palabra . "%' OR 
                                                        Edad LIKE '%" . $palabra . "%' OR
                                                        Alias LIKE '%" . $palabra . "%'OR
                                                        FechaHora_Creacion LIKE '%" . $palabra . "%'
                                                    ) ";          
                }
                if($_SESSION['userdataSIC']->Modo_Admin != 1){
                    if($_SESSION['userdataSIC']->Red[0] == 1){
                        $from_where_sentence.= " AND ( Alto_Impacto = 1) ";   
                    }else{
                        $from_where_sentence.= " AND ( Alto_Impacto = 0) ";  
                    }
                }
                $from_where_sentence .= $this->getFechaCondition();
                $from_where_sentence .= " ORDER BY Id_Seguimiento DESC";
            break;
            case '3':   //vehiculos
                $palabras = explode(",", strtolower($cadena));//Se obtiene la cadena con la cual se quiere buscar
                $articulos = array('el', 'la', 'los', 'las', 'un', 'de', 'en', 'unos', 'una', 'unas', 'a', 'con', 'y', 'o', 'u');//arreglo con las palabras a ignorar en la busqueda, normalmete artículos
                $palabras = array_diff($palabras, $articulos);
                $from_where_sentence .= " FROM gc_seguimiento_filtro_3 WHERE Id_Seguimiento > 0";
                foreach($palabras as $palabra){
                    $palabra=ltrim($palabra, " ");
                    $palabra=rtrim($palabra, " ");
                    $from_where_sentence.= " AND    (   Id_Vehiculo LIKE '%" . $palabra . "%' OR 
                                                        Id_Seguimiento LIKE '%" . $palabra . "%' OR 
                                                        Nombre_grupo_delictivo LIKE '%" . $palabra . "%' OR 
                                                        InfoVehiculo LIKE '%" . $palabra . "%' OR 
                                                        InfoPlaca LIKE '%" . $palabra . "%' OR 
                                                        Placas LIKE '%" . $palabra . "%' OR 
                                                        Nivs LIKE '%" . $palabra . "%' OR
                                                        Capturo LIKE '%" . $palabra . "%'OR
                                                        Nombre_Propietario LIKE '%" . $palabra . "%'OR
                                                        FechaHora_Creacion LIKE '%" . $palabra . "%'
                                                    ) ";          
                }
                if($_SESSION['userdataSIC']->Modo_Admin != 1){
                    if($_SESSION['userdataSIC']->Red[0] == 1){
                        $from_where_sentence.= " AND ( Alto_Impacto = 1) ";   
                    }else{
                        $from_where_sentence.= " AND ( Alto_Impacto = 0) ";  
                    }
                }
                $from_where_sentence .= $this->getFechaCondition();
                $from_where_sentence .= " ORDER BY Id_Seguimiento DESC";
            break;
        }
        return $from_where_sentence;
    }
    /*--------------------------------FUNCIÓN PARA LA VISUALIZACION-----------------------------------*/
    public function permisoVisualizacion($user){
        $sql = " SELECT  
                            permisos.Visualizacion
                    FROM usuario
                    LEFT JOIN permisos ON permisos.Id_Permisos = usuario.Id_Permisos
                    WHERE User_Name='".$user."'";

        $this->db->query($sql);
        return $this->db->register();
    }
    /*--------------------------------FUNCIÓN PARA FILTRAR POR UN RANGO DE FECHAS ESPECÍFICADO POR EL USUARIO-----------------------------------*/
    public function getFechaCondition(){
        $cad_fechas = "";
        if (isset($_SESSION['userdataSIC']->rango_inicio_sg) && isset($_SESSION['userdataSIC']->rango_fin_sg)) { //si no ingresa una fecha se seleciona el día de hoy como máximo
            $rango_inicio = $_SESSION['userdataSIC']->rango_inicio_sg;
            $rango_fin = $_SESSION['userdataSIC']->rango_fin_sg;
            $cad_fechas = " AND 
                            ((FechaHora_Creacion >= '" . $rango_inicio . " 00:00:00'  AND 
                            FechaHora_Creacion <= '" . $rango_fin . " 23:59:59' )OR
                            (FechaHora_Creacion >= CAST('" . $rango_inicio . " 00:00:00' AS date) AND
                            FechaHora_Creacion <= CAST('" . $rango_fin . " 23:59:59' AS date)))
                            ";
        }
        return $cad_fechas;
    }
    /*-----------------------FUNCION PARA INSERTAR UN NUEVO SEGUIMIENTO--------------------------------------- */
    public function insertNuevoSeguimiento($post){
        //Valores iniciales de retorno
        $data['status'] = true;
        $date = date("Ymdhis");
        $data['Id_Seguimiento'] = -1;
        $data['sqlEjecutados'] = "";
        $sqlEjecutados="";
        try{
            $this->db->beginTransaction();  //Se inicializa la transaccion para tener un punto de retorno en caso de fallo
            $nombre_grupo=$this->remplazoCadena($post['nombre_grupo']);
            $modus_operandi=$this->remplazoCadena($post['MO']);
            $observaciones=$this->remplazoCadena($post['observaciones']);
            $alto_impacto=($_SESSION['userdataSIC']->Red[0] == 1)? 1 : 0;
            $sql = "INSERT
                    INTO seguimiento_gabinete(
                        Elemento_Captura,
                        FechaHora_Creacion,
                        Nombre_grupo_delictivo,
                        Modus_operandi,
                        Peligrosidad,
                        Alto_Impacto,
                        Observaciones
                    )
                    VALUES(
                        '".$post['captura_principales']."',
                        '".$post['fechahora_captura_principales']."',
                        '".$nombre_grupo."',
                        '".$modus_operandi."',
                        '".$post['peligrosidad']."',
                        ".$alto_impacto.",
                        '".$observaciones."'
                    )";
            $this->db->query($sql);
            $this->db->execute();
            $this->db->query("SELECT LAST_INSERT_ID() as Id_Seguimiento"); //Se recupera el Id_Seguimiento que se a creado recientemente
            $Id_Seguimiento = $this->db->register()->Id_Seguimiento;
            $sqlEjecutados=$sql;
            if (isset($post['Eventos'])) {// Si existen datos de los eventos los asocia
                $EventosArray = json_decode($post['Eventos']);
                foreach ($EventosArray as $Evento) {
                    $sql = "UPDATE evento SET Id_Seguimiento =".$Id_Seguimiento. " WHERE Folio_infra= ".$Evento->row->Folio_infra;
                    $this->db->query($sql);
                    $this->db->execute();
                    $sqlEjecutados=$sqlEjecutados.' '.$sql;
                }    
            }
            if (isset($post['TableDelitos'])) {// Si existen datos de los delitos los escribe en la tabla delitos_asociados_evento
                $DelitosArray = json_decode($post['TableDelitos']);
                foreach ($DelitosArray as $delito) {
                    $sql = "INSERT
                        INTO delitos_asociados_seguimiento(
                            Id_Seguimiento,
                            Delito
                        )
                        VALUES(
                            ".$Id_Seguimiento.",
                            '".$delito->row->Delito."'
                        )";
                    $this->db->query($sql);
                    $this->db->execute();
                    $sqlEjecutados=$sqlEjecutados.' '.$sql;
                }    
            }
            $data['Id_Seguimiento'] = $Id_Seguimiento; 
            $data['sqlEjecutados'] = $sqlEjecutados;
            $this->db->commit(); //Si no hubo fallos en ninguna insercion asegura los cambios
        }catch(Exception $e){
            $data['status'] = false;
            $data['error_message'] = $e;
            $data['error_sql'] = $sql;
            $this->db->rollBack();//Si hubiese fallo en alguna insercion regresa  al estado en el que estaba la tabla al momento que se declaro el inicio de la transaccion  
        }
        return $data;
    }
    /*-----------------------FUNCIONES DE UPDATE--------------------------------------- */
    public function UpdateSeguimientoPrincipales($post){
        $response['status'] = true;
        $response['sqlEjecutados'] = "";
        $sqlEjecutados="";
        $date = date("Ymdhis");
        $Id_Seguimiento=$post['id_seguimiento'];
        try {
            $this->db->beginTransaction();  //Se inicializa la transaccion para tener un punto de retorno en caso de fallo
            $nombre_grupo=$this->remplazoCadena($post['nombre_grupo']);
            $modus_operandi=$this->remplazoCadena($post['MO']);
            $observaciones=$this->remplazoCadena($post['observaciones']);
            if($post['BanderaFoto']==0){
                $sql = "UPDATE seguimiento_gabinete SET
                        Nombre_grupo_delictivo = '".$nombre_grupo."',
                        Modus_operandi = '".$modus_operandi."',
                        Peligrosidad = '".$post['peligrosidad']."',
                        Observaciones = '".$observaciones."',
                        Nombre_PDF = '".$post['nombre_pdf']."'
                        WHERE Id_Seguimiento = ".$Id_Seguimiento;//Actualizamos el seguimiento
                $this->db->query($sql);
                $this->db->execute();
                $sqlEjecutados=$sqlEjecutados.' '.$sql;//guarda los movimientos ejecutados de sql

            }else{
                $sql = "UPDATE seguimiento_gabinete SET
                        Nombre_grupo_delictivo = '".$nombre_grupo."',
                        Modus_operandi = '".$modus_operandi."',
                        Peligrosidad = '".$post['peligrosidad']."',
                        Observaciones = '".$observaciones."',
                        Foto_grupo_delictivo = '".$post['Foto_grupo_delictivo']."',
                        Img_64 = '".$post['Img_64']."',
                        Nombre_PDF = '".$post['nombre_pdf']."'
                        WHERE Id_Seguimiento = ".$Id_Seguimiento;//Actualizamos el seguimiento
                $this->db->query($sql);
                $this->db->execute();
                $sqlrecortado=explode(";base64", strtolower($sql));
                $sqlEjecutados=$sqlEjecutados.' '.$sqlrecortado[0];//guarda los movimientos ejecutados de sql
            }
            $sql = "UPDATE evento SET Id_Seguimiento = null WHERE Id_Seguimiento= ".$Id_Seguimiento;//reseteamos
            $this->db->query($sql);
            $this->db->execute();
            $sqlEjecutados=$sqlEjecutados.' '.$sql; //guarda los movimientos ejecutados de sql
            if (isset($post['Eventos'])) {// Si existen datos de los eventos los escribe en la tabla delitos_asociados_evento
                $EventosArray = json_decode($post['Eventos']);
                foreach ($EventosArray as $Evento) {
                    $sql = "UPDATE evento SET Id_Seguimiento =".$Id_Seguimiento. " WHERE Folio_infra= ".$Evento->row->Folio_infra;
                    $this->db->query($sql);
                    $this->db->execute();
                    $sqlEjecutados=$sqlEjecutados.' '.$sql;
                }    
            }
            if (isset($post['TableDelitos'])) {// Si existen datos de los delitos los escribe en la tabla delitos_asociados_evento
                $sql = "DELETE FROM delitos_asociados_seguimiento WHERE Id_Seguimiento = " .$Id_Seguimiento ;
                $this->db->query($sql);
                $this->db->execute();
                $DelitosArray = json_decode($post['TableDelitos']);
                foreach ($DelitosArray as $delito) {
                    $sql = "INSERT
                        INTO delitos_asociados_seguimiento(
                            Id_Seguimiento,
                            Delito
                        )
                        VALUES(
                            ".$Id_Seguimiento.",
                            '".$delito->row->Delito."'
                        )";
                    $this->db->query($sql);
                    $this->db->execute();
                    $sqlEjecutados=$sqlEjecutados.' '.$sql;
                }    
            }else{
                $sql = "DELETE FROM delitos_asociados_seguimiento WHERE Id_Seguimiento = " .$Id_Seguimiento ;
                $this->db->query($sql);
                $this->db->execute();
            }
            $response['Id_Seguimiento'] = $Id_Seguimiento; 
            $response['sqlEjecutados'] = $sqlEjecutados;
            $this->db->commit();
        } catch (Exception $e) {
            $response['status'] = false;
            $response['error_message'] = 'Hubo un error en la Base de datos.';
            $response['error_sql'] = $sql;
            $this->db->rollBack();
        }
        $response['sqlEjecutados'] = $sqlEjecutados;
        return $response;
    }
    public function UpdatePersonasFetch($post){
        $response['status'] = true;
        $response['sqlEjecutados'] = "";
        $sqlEjecutados="";
        $Id_Seguimiento=$post['id_seguimiento'];
        try {
            $this->db->beginTransaction();  //Se inicializa la transaccion para tener un punto de retorno en caso de fallo
            if (isset($post['Personas_table'])) {
                $PersonasArray = json_decode($post['Personas_table']);
                foreach ($PersonasArray as $Persona) {
                    if($Persona->row->Id_Persona=='SD'){
                        //logica de insert
                        $Alias=$this->remplazoCadena($Persona->row->Alias);
                        if($Persona->row->nameImage!='null'){
                            $Nombre_Foto=$Persona->row->nameImage.'.png';

                        }else{
                            $Nombre_Foto='SD';
                        }
                        if($Persona->row->imagebase64!='null'){
                            $imagebase64=$Persona->row->imagebase64;

                        }else{
                            $imagebase64='SD';
                        }
                        $sql="INSERT
                        INTO persona_gabinete(
                            Id_Seguimiento,
                            Nombre,
                            Ap_Paterno,
                            Ap_Materno,
                            Genero,
                            Edad,
                            Fecha_Nacimiento,
                            Telefono,
                            Alias,
                            Curp,
                            Remisiones,
                            Rol,
                            Capturo,
                            Foto,
                            Img_64
                        )VALUES(
                            ".$Id_Seguimiento.",
                            '".$Persona->row->Nombre."',
                            '".$Persona->row->Ap_Paterno."',
                            '".$Persona->row->Ap_Materno."',
                            '".$Persona->row->Genero."',
                            '".$Persona->row->Edad."',
                            '".$Persona->row->Fecha_Nacimiento."',
                            '".$Persona->row->Telefono."',
                            '".$Persona->row->Alias."',
                            '".$Persona->row->Curp."',
                            '".$Persona->row->Remisiones."',
                            '".$Persona->row->Rol."',
                            '".$Persona->row->Capturo."',
                            '".$Nombre_Foto. "',
                            '".$imagebase64."'
                        )";
                        $this->db->query($sql);
                        $this->db->execute();
                        $this->db->query("SELECT LAST_INSERT_ID() as Id_Persona"); 
                        $Id_Persona = $this->db->register()->Id_Persona;
                        $sqlEjecutados.=" SE INSERTO PERSONA A RED DE VINCULO ".$Id_Persona.' '.$Persona->row->Nombre.' '.$Persona->row->Ap_Paterno;

                    }else{
                        $Alias=$this->remplazoCadena($Persona->row->Alias);
                        if($Persona->row->nameImage!='null'){
                            $Nombre_Foto=$Persona->row->nameImage.'.png';

                        }else{
                            $Nombre_Foto='SD';
                        }
                        if($Persona->row->imagebase64!='null'){
                            $imagebase64=$Persona->row->imagebase64;

                        }else{
                            $imagebase64='SD';
                        }
                        $sql=" UPDATE persona_gabinete
                                SET Nombre = '" . $Persona->row->Nombre  ."',
                                    Ap_Paterno = '" . $Persona->row->Ap_Paterno ."',
                                    Ap_Materno = '" . $Persona->row->Ap_Materno ."',
                                    Genero = '" . $Persona->row->Genero ."',
                                    Edad = '" . $Persona->row->Edad ."',
                                    Fecha_Nacimiento = '" . $Persona->row->Fecha_Nacimiento ."',
                                    Telefono = '" . $Persona->row->Telefono ."',
                                    Alias = '" . $Alias."',
                                    Curp = '" . $Persona->row->Curp ."',
                                    Remisiones = '" . $Persona->row->Remisiones ."',
                                    Rol = '" . $Persona->row->Rol ."',
                                    Capturo = '" . $Persona->row->Capturo ."',
                                    Foto = '" . $Nombre_Foto."',
                                    Img_64 = '" . $imagebase64 ."'
                                    WHERE Id_Persona=".$Persona->row->Id_Persona."
                                    AND (
                                        Nombre != '" . $Persona->row->Nombre . "'
                                        OR Ap_Paterno != '" . $Persona->row->Ap_Paterno . "'
                                        OR Ap_Materno != '" . $Persona->row->Ap_Materno . "'
                                        OR Genero != '" . $Persona->row->Genero . "'
                                        OR Edad != '" . $Persona->row->Edad . "'
                                        OR Fecha_Nacimiento != '" . $Persona->row->Fecha_Nacimiento . "'
                                        OR Telefono != '" . $Persona->row->Telefono . "'
                                        OR Alias != '" . $Alias . "'
                                        OR Curp != '" . $Persona->row->Curp . "'
                                        OR Remisiones != '" . $Persona->row->Remisiones . "'
                                        OR Rol != '" . $Persona->row->Rol . "'
                                        OR Capturo != '" . $Persona->row->Capturo . "'
                                        OR Foto != '" . $Nombre_Foto . "'
                                        OR Img_64 != '" . $imagebase64 . "'
                                    )";
                        $this->db->query($sql);
                        $this->db->execute();

                        if ($this->db->rowCount() > 0) {// Si se realizó una actualización
                            $sqlEjecutados.=" SE ACTUALIZO PERSONA DE RED DE VINCULO ".$Persona->row->Id_Persona.' '.$Persona->row->Nombre.' '.$Persona->row->Ap_Paterno;
                        }
                    }

                }

            }
            $this->db->commit(); //Si no hubo fallos en ninguna insercion asegura los cambios
       

        } catch (Exception $e) {
            $response['status'] = false;
            $response['error_message'] = 'Hubo un error en la Base de datos.';
            $response['error_sql'] = $sql;
            //echo ('ubo un error');
            $this->db->rollBack();
        }
        $response['id_seguimiento'] = $Id_Seguimiento; 
        $response['sqlEjecutados'] = $sqlEjecutados;
        return $response;
    }

    public function UpdateVehiculosFetch($post){
        $response['status'] = true;
        $response['sqlEjecutados'] = "";
        $sqlEjecutados="";
        $Id_Seguimiento=$post['id_seguimiento'];
        try {
            $this->db->beginTransaction();  //Se inicializa la transaccion para tener un punto de retorno en caso de fallo
            if (isset($post['Vehiculos_table'])) {
                $VehiculoArray = json_decode($post['Vehiculos_table']);
                foreach ($VehiculoArray as $Vehiculo) {
                    if($Vehiculo->row->Id_Vehiculo=='SD'){
                        //logica de insert
                        if($Vehiculo->row->nameImage!='null'){
                            $Nombre_Foto=$Vehiculo->row->nameImage.'.png';

                        }else{
                            $Nombre_Foto='SD';
                        }
                        if($Vehiculo->row->imagebase64!='null'){
                            $imagebase64=$Vehiculo->row->imagebase64;

                        }else{
                            $imagebase64='SD';
                        }
                        $sql="INSERT
                        INTO vehiculos_gabinete(
                            Id_Seguimiento,
                            Placas,
                            Nivs,
                            Marca,
                            Submarca,
                            Modelo,
                            Color,
                            Nombre_Propietario,
                            InfoPlaca,
                            Capturo,
                            Foto,
                            Img_64
                        )VALUES(
                            ".$Id_Seguimiento.",
                            '".$Vehiculo->row->Placas."',
                            '".$Vehiculo->row->Nivs."',
                            '".$Vehiculo->row->Marca."',
                            '".$Vehiculo->row->Submarca."',
                            '".$Vehiculo->row->Modelo."',
                            '".$Vehiculo->row->Color."',
                            '".$Vehiculo->row->Nombre_Propietario."',
                            '".$Vehiculo->row->InfoPlaca."',
                            '".$Vehiculo->row->Capturo."',
                            '".$Nombre_Foto. "',
                            '".$imagebase64."'
                        )";
                        $this->db->query($sql);
                        $this->db->execute();

                        $this->db->query("SELECT LAST_INSERT_ID() as Id_Vehiculo"); 
                        $Id_Vehiculo = $this->db->register()->Id_Vehiculo;
                        $sqlEjecutados.=" SE INSERTO UN VEHICULO A LA RED DE VINCULO ".$Id_Vehiculo.' '.$Vehiculo->row->Placas;

                    }else{
                        if($Vehiculo->row->nameImage!='null'){
                            $Nombre_Foto=$Vehiculo->row->nameImage.'.png';

                        }else{
                            $Nombre_Foto='SD';
                        }
                        if($Vehiculo->row->imagebase64!='null'){
                            $imagebase64=$Vehiculo->row->imagebase64;

                        }else{
                            $imagebase64='SD';
                        }
                        $sql=" UPDATE vehiculos_gabinete
                                SET Placas = '" . $Vehiculo->row->Placas  ."',
                                    Nivs = '" . $Vehiculo->row->Nivs ."',
                                    Marca = '" . $Vehiculo->row->Marca ."',
                                    Submarca = '" . $Vehiculo->row->Submarca ."',
                                    Modelo = '" . $Vehiculo->row->Modelo ."',
                                    Color = '" . $Vehiculo->row->Color ."',
                                    Nombre_Propietario = '" . $Vehiculo->row->Nombre_Propietario ."',
                                    InfoPlaca= '" . $Vehiculo->row->InfoPlaca ."',
                                    Foto = '" . $Nombre_Foto."',
                                    Img_64 = '" . $imagebase64 ."'
                                    WHERE Id_Vehiculo=".$Vehiculo->row->Id_Vehiculo."
                                    AND (
                                        Placas != '" . $Vehiculo->row->Placas . "'
                                        OR Nivs != '" . $Vehiculo->row->Nivs . "'
                                        OR Marca != '" . $Vehiculo->row->Marca . "'
                                        OR Submarca != '" . $Vehiculo->row->Submarca . "'
                                        OR Modelo != '" . $Vehiculo->row->Modelo . "'
                                        OR Color != '" . $Vehiculo->row->Color . "'
                                        OR Nombre_Propietario != '" . $Vehiculo->row->Nombre_Propietario . "'
                                        OR InfoPlaca != '" . $Vehiculo->row->InfoPlaca . "'
                                        OR Foto != '" . $Nombre_Foto . "'
                                        OR Img_64 != '" . $imagebase64 . "'
                                    )";
                        $this->db->query($sql);
                        $this->db->execute();
                        if ($this->db->rowCount() > 0) {// Si se realizó una actualización
                            $sqlEjecutados.=" SE ACTUALIZO VEHICULO A LA RED DE VINCULO ".$Vehiculo->row->Id_Vehiculo.' '.$Vehiculo->row->Placas;
                        }
                    }

                }

            }
            $this->db->commit(); //Si no hubo fallos en ninguna insercion asegura los cambios
       

        } catch (Exception $e) {
            $response['status'] = false;
            $response['error_message'] = 'Hubo un error en la Base de datos.';
            $response['error_sql'] = $sql;
            $this->db->rollBack();
        }
        $response['id_seguimiento'] = $Id_Seguimiento; 
        $response['sqlEjecutados'] = $sqlEjecutados;
        return $response;
    }
    public function UpdateDomiciliosFetch($post){
        $response['status'] = true;
        $response['sqlEjecutados'] = "";
        $sqlEjecutados="";
        try {
            $this->db->beginTransaction();  //Se inicializa la transaccion para tener un punto de retorno en caso de fallo
            if (isset($post['Domicilios_table'])) {
                $DomicilioArray = json_decode($post['Domicilios_table']);
                foreach ($DomicilioArray as $Domicilio) {
                    if($Domicilio->row->Id_Domicilio=='SD'){
                        //logica de insert
                        $sql="INSERT
                        INTO domicilios(
                            Id_Dato,
                            Tipo_Entidad,
                            Estatus,
                            Colonia,
                            Calle,
                            Calle2,
                            NumExt,
                            NumInt,
                            CP,
                            CoordX,
                            CoordY,
                            Capturo,
                            Observaciones_Ubicacion,
                            Estado,
                            Municipio,
                            Foraneo
                        )VALUES(
                            ".$Domicilio->row->Id_Dato.",
                            '".$Domicilio->row->Tipo_Entidad."',
                            '".$Domicilio->row->Estatus."',
                            '".$Domicilio->row->Colonia."',
                            '".$Domicilio->row->Calle."',
                            '".$Domicilio->row->Calle2."',
                            '".$Domicilio->row->NumExt."',
                            '".$Domicilio->row->NumInt."',
                            '".$Domicilio->row->CP."',
                            '".$Domicilio->row->CoordX."',
                            '".$Domicilio->row->CoordY."',
                            '".$Domicilio->row->Capturo. "',
                            '".$Domicilio->row->Observaciones_Ubicacion. "',
                            '".$Domicilio->row->Estado. "',
                            '".$Domicilio->row->Municipio. "',
                            '".$Domicilio->row->Foraneo. "'
                        )";
                        $this->db->query($sql);
                        $this->db->execute();

                        $this->db->query("SELECT LAST_INSERT_ID() as Id_Domicilio"); 
                        $Id_Domicilio = $this->db->register()->Id_Domicilio;
                        $sqlEjecutados.=" SE INSERTO UN DOMICILIO A LA RED DE VINCULO ".$Id_Domicilio;

                    }else{     
                        $sql=" UPDATE domicilios
                                SET Id_Dato = " .$Domicilio->row->Id_Dato  .",
                                    Tipo_Entidad = '" .$Domicilio->row->Tipo_Entidad."',
                                    Estatus = '" .$Domicilio->row->Estatus ."',
                                    Colonia = '" .$Domicilio->row->Colonia ."',
                                    Calle = '" .$Domicilio->row->Calle ."',
                                    Calle2 = '" .$Domicilio->row->Calle2 ."',
                                    NumExt = '" .$Domicilio->row->NumExt ."',
                                    NumInt= '" .$Domicilio->row->NumInt ."',
                                    CP= '" .$Domicilio->row->CP ."',
                                    CoordX = '" .$Domicilio->row->CoordX."',
                                    CoordY = '" . $Domicilio->row->CoordY."',
                                    Observaciones_Ubicacion = '" . $Domicilio->row->Observaciones_Ubicacion."',
                                    Estado = '" . $Domicilio->row->Estado."',
                                    Municipio = '" . $Domicilio->row->Municipio."',
                                    Foraneo = '" . $Domicilio->row->Foraneo."'
                                    WHERE Id_Domicilio =".$Domicilio->row->Id_Domicilio."
                                    AND (
                                        Id_Dato != " . $Domicilio->row->Id_Dato . "
                                        OR Tipo_Entidad != '" . $Domicilio->row->Tipo_Entidad . "'
                                        OR Estatus != '" . $Domicilio->row->Estatus . "'
                                        OR Colonia != '" . $Domicilio->row->Colonia . "'
                                        OR Calle != '" . $Domicilio->row->Calle . "'
                                        OR Calle2 != '" . $Domicilio->row->Calle2 . "'
                                        OR NumExt != '" . $Domicilio->row->NumExt . "'
                                        OR NumInt != '" . $Domicilio->row->NumInt . "'
                                        OR CP != '" . $Domicilio->row->CP . "'
                                        OR CoordX != '" . $Domicilio->row->CoordX . "'
                                        OR CoordY != '" . $Domicilio->row->CoordY . "'
                                        OR Observaciones_Ubicacion != '" . $Domicilio->row->Observaciones_Ubicacion . "'
                                        OR Estado != '" . $Domicilio->row->Estado . "'
                                        OR Municipio != '" . $Domicilio->row->Municipio . "'
                                        OR Foraneo != '" . $Domicilio->row->Foraneo . "'
                                    )";
                        $this->db->query($sql);
                        $this->db->execute();
                        if ($this->db->rowCount() > 0) {// Si se realizó una actualización
                            $sqlEjecutados.=" SE ACTUALIZO UN DOMICILIO DE LA RED DE VINCULO ".$Domicilio->row->Id_Domicilio;
                        }
                    }

                }
            }
            $this->db->commit(); //Si no hubo fallos en ninguna insercion asegura los cambios
        } catch (Exception $e) {
            $response['status'] = false;
            $response['error_message'] = 'Hubo un error en la Base de datos.';
            $response['error_sql'] = $sql;
            $this->db->rollBack();
        }
        $response['sqlEjecutados'] = $sqlEjecutados;
        return $response;
    }
    public function UpdateAntecendentesFetch($post){
        $response['status'] = true;
        $response['sqlEjecutados'] = "";
        $sqlEjecutados="";
        ini_set('memory_limit', '10240M');
        try {
            $this->db->beginTransaction();  //Se inicializa la transaccion para tener un punto de retorno en caso de fallo
            if (isset($post['AntecendentesTable'])) {
                $AntecendenteArray = json_decode($post['AntecendentesTable']);
                foreach ($AntecendenteArray as $Antecendente) {
                    if($Antecendente->row->Id_Antecedente=='SD'){
                        //logica de insert
                        $Descripcion=$this->remplazoCadena($Antecendente->row->Descripcion_Antecedente);
                        $sql="INSERT
                        INTO antecedentes_gabinete(
                            Id_Dato,
                            Tipo_Entidad,
                            Descripcion_Antecedente,
                            Fecha_Antecedente,
                            Capturo
                        )VALUES(
                            ".$Antecendente->row->Id_Dato.",
                            '".$Antecendente->row->Tipo_Entidad."',
                            '".$Descripcion."',
                            '".$Antecendente->row->Fecha_Antecedente."',
                            '".$Antecendente->row->Capturo. "'
                        )";
                        $this->db->query($sql);
                        $this->db->execute();
                        $this->db->query("SELECT LAST_INSERT_ID() as Id_Antecedente"); 
                        $Id_Antecedente = $this->db->register()->Id_Antecedente;
                        $sqlEjecutados.=" SE INSERTO UN ANTECEDENTE A LA RED DE VINCULO ".$Id_Antecedente." RELACIONADO A ".$Antecendente->row->Tipo_Entidad." ".$Antecendente->row->Id_Dato;

                    }else{
                        $Descripcion=$this->remplazoCadena($Antecendente->row->Descripcion_Antecedente);
                        $sql=" UPDATE antecedentes_gabinete
                                SET Id_Dato = " .$Antecendente->row->Id_Dato.",
                                    Tipo_Entidad = '" .$Antecendente->row->Tipo_Entidad."',
                                    Descripcion_Antecedente = '" .$Descripcion."',
                                    Fecha_Antecedente = '" .$Antecendente->row->Fecha_Antecedente."'
                                    WHERE Id_Antecedente =".$Antecendente->row->Id_Antecedente."
                                    AND (
                                        Id_Dato != " . $Antecendente->row->Id_Dato . "
                                        OR Tipo_Entidad != '" . $Antecendente->row->Tipo_Entidad . "'
                                        OR Descripcion_Antecedente != '" . $Descripcion . "'
                                        OR Fecha_Antecedente != '" . $Antecendente->row->Fecha_Antecedente . "'
                                    )";
                        $this->db->query($sql);
                        $this->db->execute();
                        if ($this->db->rowCount() > 0) {// Si se realizó una actualización
                            $sqlEjecutados.=" SE ACTUALIZO UN ANTECEDENTE DE LA RED DE VINCULO ".$Antecendente->row->Id_Antecedente." RELACIONADO A ".$Antecendente->row->Tipo_Entidad." ".$Antecendente->row->Id_Dato;
                        }
                    }
                }
            }
            $this->db->commit(); //Si no hubo fallos en ninguna insercion asegura los cambios
        } catch (Exception $e) {
            $response['status'] = false;
            $response['error_message'] = 'Hubo un error en la Base de datos.';
            $response['error_sql'] = $sql;
            $this->db->rollBack();
        }
        $response['sqlEjecutados'] = $sqlEjecutados;
        return $response;
    }
    public function UpdateForenciasFetch($post){
        $response['status'] = true;
        $response['sqlEjecutados'] = "";
        $sqlEjecutados="";
        try {
            $this->db->beginTransaction();  //Se inicializa la transaccion para tener un punto de retorno en caso de fallo
            if (isset($post['Forenciastable'])) {
                $ForenciaArray = json_decode($post['Forenciastable']);
                foreach ($ForenciaArray as $Forencia) {
                    if($Forencia->row->Id_Forencia=='SD'){
                        //logica de insert
                        if($Forencia->row->nameImage!='null'){
                            $Nombre_Foto=$Forencia->row->nameImage.'.png';
                        }else{
                            $Nombre_Foto='SD';
                        }
                        if($Forencia->row->imagebase64!='null'){
                            $imagebase64=$Forencia->row->imagebase64;
                        }else{
                            $imagebase64='SD';
                        }
                        $Descripcion=$this->remplazoCadena($Forencia->row->Descripcion_Forencia);
                        $sql="INSERT
                        INTO forencias_gabinete(
                            Id_Persona ,
                            Descripcion_Forencia,
                            Capturo,
                            Foto_Nombre,
                            Img_64
                        )VALUES(
                            '".$Forencia->row->Id_Persona ."',
                            '".$Descripcion."',
                            '".$Forencia->row->Capturo. "',
                            '".$Nombre_Foto. "',
                            '".$imagebase64. "'
                        )";
                        $this->db->query($sql);
                        $this->db->execute();
                        
                        $this->db->query("SELECT LAST_INSERT_ID() as Id_Forencia"); 
                        $Id_Forencia = $this->db->register()->Id_Forencia;
                        $sqlEjecutados.=" SE INSERTO UN DATO A LA RED DE VINCULO ".$Id_Forencia." RELACIONADO A LA PERSONA ".$Forencia->row->Id_Persona;
                 

                    }else{
                        if($Forencia->row->nameImage!='null'){
                            $Nombre_Foto=$Forencia->row->nameImage.'.png';
                        }else{
                            $Nombre_Foto='SD';
                        }
                        if($Forencia->row->imagebase64!='null'){
                            $imagebase64=$Forencia->row->imagebase64;
                        }else{
                            $imagebase64='SD';
                        }
                        $Descripcion=$this->remplazoCadena($Forencia->row->Descripcion_Forencia);
                        $sql=" UPDATE forencias_gabinete
                                SET Id_Persona = " .$Forencia->row->Id_Persona.",
                                    Descripcion_Forencia = '" .$Descripcion."',
                                    Foto_Nombre = '" .$Nombre_Foto."',
                                    Img_64 = '" .$imagebase64."'
                                    WHERE Id_Forencia =".$Forencia->row->Id_Forencia."
                                    AND (
                                        Id_Persona != " . $Forencia->row->Id_Persona . "
                                        OR Descripcion_Forencia != '" . $Descripcion . "'
                                        OR Foto_Nombre != '" . $Nombre_Foto . "'
                                        OR Img_64 != '" . $imagebase64 . "'
                                    )";
                        $this->db->query($sql);
                        $this->db->execute();
                        
                        if ($this->db->rowCount() > 0) {// Si se realizó una actualización
                            $sqlEjecutados.=" SE ACTUALIZO UN DATO DE LA RED DE VINCULO ".$Forencia->row->Id_Forencia." RELACIONADO A LA PERSONA ".$Forencia->row->Id_Persona;
                        }
                    }
                }
            }
            $this->db->commit(); //Si no hubo fallos en ninguna insercion asegura los cambios
        } catch (Exception $e) {
            $response['status'] = false;
            $response['error_message'] = 'Hubo un error en la Base de datos.';
            $response['error_sql'] = $sql;
            $this->db->rollBack();
        }
        $response['sqlEjecutados'] = $sqlEjecutados;
        return $response;
    }
    public function UpdateRedesSocialesFetch($post){
        $response['status'] = true;
        $response['sqlEjecutados'] = "";
        $sqlEjecutados="";
        try {
            $this->db->beginTransaction();  //Se inicializa la transaccion para tener un punto de retorno en caso de fallo
            if (isset($post['RedesSociales_table'])) {
                $RedesSocialesArray = json_decode($post['RedesSociales_table']);
                foreach ($RedesSocialesArray as $RedSocial) {
                    if($RedSocial->row->Id_Registro=='SD'){
                        //logica de insert
                        if($RedSocial->row->nameImage!='null'){
                            $Nombre_Foto=$RedSocial->row->nameImage.'.png';
                        }else{
                            $Nombre_Foto='SD';
                        }
                        if($RedSocial->row->imagebase64!='null'){
                            $imagebase64=$RedSocial->row->imagebase64;
                        }else{
                            $imagebase64='SD';
                        }
                        $Usuario=$this->remplazoCadena($RedSocial->row->Usuario);
                        $Enlace=$this->remplazoCadena($RedSocial->row->Enlace);
                        $Observacion_Enlace=$this->remplazoCadena($RedSocial->row->Observacion_Enlace);
                        $sql="INSERT
                        INTO redes_sociales_gabinete(
                            Id_Persona ,
                            Usuario ,
                            Tipo_Enlace ,
                            Enlace,
                            Observacion_Enlace,
                            Capturo,
                            Foto_Nombre,
                            Img_64
                        )VALUES(
                            '".$RedSocial->row->Id_Persona ."',
                            '".$Usuario."',
                            '".$RedSocial->row->Tipo_Enlace. "',
                            '".$Enlace. "',
                            '".$Observacion_Enlace. "',
                            '".$RedSocial->row->Capturo. "',
                            '".$Nombre_Foto. "',
                            '".$imagebase64. "'
                        )";
                        $this->db->query($sql);
                        $this->db->execute();
                        
                        $this->db->query("SELECT LAST_INSERT_ID() as Id_Registro"); 
                        $Id_Registro = $this->db->register()->Id_Registro;
                        $sqlEjecutados.=" SE INSERTO UN DATO DE REDES SOCIALES A LA RED DE VINCULO ".$Id_Registro." RELACIONADO A LA PERSONA ".$RedSocial->row->Id_Persona;

                    }else{
                        if($RedSocial->row->nameImage!='null'){
                            $Nombre_Foto=$RedSocial->row->nameImage.'.png';
                        }else{
                            $Nombre_Foto='SD';
                        }
                        if($RedSocial->row->imagebase64!='null'){
                            $imagebase64=$RedSocial->row->imagebase64;
                        }else{
                            $imagebase64='SD';
                        }
                        $Usuario=$this->remplazoCadena($RedSocial->row->Usuario);
                        $Enlace=$this->remplazoCadena($RedSocial->row->Enlace);
                        $Observacion_Enlace=$this->remplazoCadena($RedSocial->row->Observacion_Enlace);
                        $sql=" UPDATE redes_sociales_gabinete
                                SET Id_Persona = " .$RedSocial->row->Id_Persona.",
                                    Usuario = '" .$Usuario."',
                                    Tipo_Enlace = '" .$RedSocial->row->Tipo_Enlace."',
                                    Enlace = '" .$Enlace."',
                                    Observacion_Enlace = '" .$Observacion_Enlace."',
                                    Capturo = '" .$RedSocial->row->Capturo."',
                                    Foto_Nombre = '" .$Nombre_Foto."',
                                    Img_64 = '" .$imagebase64."'
                                    WHERE Id_Registro  =".$RedSocial->row->Id_Registro ."
                                    AND (
                                        Id_Persona != " . $RedSocial->row->Id_Persona . "
                                        OR Usuario != '" . $Usuario . "'
                                        OR Tipo_Enlace != '" . $RedSocial->row->Tipo_Enlace . "'
                                        OR Enlace != '" . $Enlace . "'
                                        OR Observacion_Enlace != '" . $Observacion_Enlace . "'
                                        OR Capturo != '" . $RedSocial->row->Capturo . "'
                                        OR Foto_Nombre != '" . $Nombre_Foto . "'
                                        OR Img_64 != '" . $imagebase64 . "'
                                    )";
                        $this->db->query($sql);
                        $this->db->execute();
                        
                        if ($this->db->rowCount() > 0) {// Si se realizó una actualización
                            $sqlEjecutados.=" SE ACTUALIZO UN DATO DE REDES SOCIALES A LA RED DE VINCULO ".$RedSocial->row->Id_Registro." RELACIONADO A LA PERSONA ".$RedSocial->row->Id_Persona;
                        }
                    }
                }
            }
            $this->db->commit(); //Si no hubo fallos en ninguna insercion asegura los cambios
        } catch (Exception $e) {
            $response['status'] = false;
            $response['error_message'] = 'Hubo un error en la Base de datos.';
            $response['error_sql'] = $sql;
            $this->db->rollBack();
        }
        $response['sqlEjecutados'] = $sqlEjecutados;
        return $response;
    }
    /*------------------FUNCIONES PARA FILTRADO Y BÚSQUEDA------------------*/
    //obtener el total de páginas y de registros de la consulta
    public function getTotalPages($no_of_records_per_page, $from_where_sentence = ""){
        //quitamos todo aquello que este fuera de los parámetros para solo obtener el substring desde FROM
        $from_where_sentence = strstr($from_where_sentence, 'FROM');
        $sql_total_pages = "SELECT COUNT(*) as Num_Pages " . $from_where_sentence; //total registros
        $this->db->query($sql_total_pages);      //prepararando query
        $total_rows = $this->db->register()->Num_Pages; //ejecutando query y recuperando el valor obtenido
        $total_pages = ceil($total_rows / $no_of_records_per_page); //calculando el total de paginations
        $data['total_rows'] = $total_rows;
        $data['total_pages'] = $total_pages;
        return $data;
    }
   //obtener los registros de la pagina actual
    public function getDataCurrentPage($offset, $no_of_records_per_page, $from_where_sentence = ""){
        $sql = " SELECT * ". $from_where_sentence . " LIMIT $offset,$no_of_records_per_page";
        $this->db->query($sql);
        return $this->db->registers(); 
    }
    /*-----------------------FUNCION PARA INGRESAR EL MOVIMIENTO AL HISTORIAL------------------------- */
    public function historial($user, $ip, $movimiento, $descripcion){// para ecribir los movimientos hechos en el gestor 
        $band = true;
        try {
            $this->db->beginTransaction();
            $sql = " INSERT
                    INTO historial(
                        Id_Usuario,
                        Ip_Acceso,
                        Movimiento,
                        Descripcion
                    )
                    VALUES(
                        trim($user),
                        '" . trim($ip) . "',
                        trim($movimiento),
                        '" . trim($descripcion) . "'
                    )
            ";
            $this->db->query($sql);
            $this->db->execute();
            $this->db->commit();
        } catch (Exception $e) {
            $band = false;
            $this->db->rollBack();
        }
        return $band;
    }
    public function getEventoDByCadena($cadena, $filtro ){
        //CONSULTA COINCIDENCIAS DE CADENA PARA EVENTOS 
        $cadena=$this->eliminar_acentos($cadena);
        //sentencia from_where para hacer la busqueda por la cadena ingresada
        $from_where_sentence = $this->generateFromWhereSentence($cadena, $filtro);
        $numPage = 1;
        $no_of_records_per_page = NUM_MAX_REG_PAGE; //total de registros por pagination
        $offset = ($numPage - 1) * $no_of_records_per_page; // desplazamiento conforme a la pagina
        $results = $this->getTotalPages($no_of_records_per_page, $from_where_sentence);  //total de páginas conforme a la busqueda
        //info de retorno para la creacion de los links conforme a la cadena ingresada
        $data['rows_Rems'] = $this->getDataCurrentPage($offset, $no_of_records_per_page, $from_where_sentence);   //se obtiene la información de la página actual
        $data['numPage'] = $numPage; //numero pag actual para la pagination footer
        $data['total_pages'] = $results['total_pages']; //total pages para la pagination
        $data['total_rows'] = $results['total_rows'];   //total de registro hallados
        return $data;
    }
    /*-------------------------------------- FUNCIONES PARA TRATAR POSIBLES ERRORES DE ENTRADA------------------------------------------ */
    public function eliminar_acentos($cadena){//esta funcion es para la cadena de busqueda ya que el like no permite mezcla diferentes collations solo aplica en bases locales 
		//Reemplazamos la A y a
		$cadena = str_replace(
		array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
		array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
		$cadena
		);

		//Reemplazamos la E y e
		$cadena = str_replace(
		array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
		array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
		$cadena );

		//Reemplazamos la I y i
		$cadena = str_replace(
		array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
		array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
		$cadena );

		//Reemplazamos la O y o
		$cadena = str_replace(
		array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
		array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
		$cadena );

		//Reemplazamos la U y u
		$cadena = str_replace(
		array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
		array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
		$cadena );

		//Reemplazamos la N, n, C y c
		$cadena = str_replace(
		array('Ñ', 'ñ', 'Ç', 'ç'),
		array('N', 'n', 'C', 'c'),
		$cadena
		);
		return $cadena;
	}
    public function remplazoCadena($entrada){// trata las cadenas para que no halla error en los querys
        $text=$entrada;
        $repla=['"','“','”'];
        $repla2=["'",'‘','’'];
        $text = str_replace($repla, "\"", $text);  
        $text = str_replace($repla2, "\'", $text); 
        return $text;
    }
    public function getAllInfoSeguimientoByCadena($from_where_sentence = ""){ 
    	$sqlAux = "SELECT * "
    				.$from_where_sentence."
                    ";  //query a la DB
        $this->db->query($sqlAux);          //se prepara el query mediante PDO
        return $this->db->registers();      //retorna todos los registros devueltos por la consulta
    }
    /*-------------------------------------- GET DE INFORMACION DEL SEGUIMIENTO------------------------------------------ */
    public function getPrincipales($Id_Seguimiento){
        $sql = "SELECT 	*
        FROM seguimiento_gabinete
        WHERE seguimiento_gabinete.Id_Seguimiento = " . $Id_Seguimiento;
        $this->db->query($sql);
        return $this->db->register();
    }

    public function getGrupoDelictivo($Id_Seguimiento){
        $sql = "SELECT 	Nombre_grupo_delictivo
        FROM seguimiento_gabinete
        WHERE seguimiento_gabinete.Id_Seguimiento = " . $Id_Seguimiento;
        $this->db->query($sql);
        return $this->db->register();
    }

    public function getAllPrincipales($Id_Seguimiento){
        $sql = "SELECT 	*
        FROM seguimiento_gabinete
        WHERE seguimiento_gabinete.Id_Seguimiento = " . $Id_Seguimiento;
        $this->db->query($sql);
        $data_principales= $this->db->register();
        
        $sql = "SELECT 	*
        FROM gc_evento_filtro_3
        WHERE gc_evento_filtro_3.Id_Seguimiento = " . $Id_Seguimiento;
        $this->db->query($sql);
        $data_eventos= $this->db->registers();

        $sql = "SELECT 	*
        FROM delitos_asociados_seguimiento
        WHERE delitos_asociados_seguimiento.Id_Seguimiento = " . $Id_Seguimiento;
        $this->db->query($sql);
        $data_delitos= $this->db->registers();

        $sql = "SELECT 	*
        FROM gc_seguimiento_filtro_1
        WHERE gc_seguimiento_filtro_1.Id_Seguimiento = " . $Id_Seguimiento;
        $this->db->query($sql);
        $data_filtro= $this->db->register();

        $data = [
            'principal'=> $data_principales,
            'eventos'   => $data_eventos,
            'delitos' => $data_delitos,
            'filtro' => $data_filtro
            ]; 
        return $data;
    }
    public function getEventos(){
        $sql = "SELECT * FROM gc_evento_filtro_2";
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getRemisiones(){
        $sql = "SELECT * FROM casos_consulta_detenido";
        $this->db2->query($sql);
        return $this->db2->registers();
    }
    public function getVehiculosSarai(){
        $sql = "SELECT * FROM casos_consulta_vehiculos";
        $this->db2->query($sql);
        return $this->db2->registers();
    }
    public function getVehiculosSic(){
        $sql = "SELECT * FROM consulta_vehiculos";
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getInfoVehiculoSarai($ID){
        $sql = "SELECT 	*
        FROM casos_consulta_vehiculos
        WHERE casos_consulta_vehiculos.ID = " . $ID;
        $this->db2->query($sql);
        return $this->db2->register();
    }
    public function getInfoVehiculoSic($ID){
        $sql = "SELECT 	*
        FROM consulta_vehiculos
        WHERE consulta_vehiculos.ID = " . $ID;
        $this->db->query($sql);
        return $this->db->register();
    }
    public function getInfoRemision($No_Remision){
        $sql = "SELECT 	*
        FROM casos_consulta_detenido
        WHERE casos_consulta_detenido.No_Remision = " . $No_Remision;
        $this->db2->query($sql);
        return $this->db2->register();
    }
    public function getInfoEvento($Folio_infra){
        $sql = "SELECT 	*
        FROM gc_evento_filtro_2
        WHERE gc_evento_filtro_2.Folio_infra = " . $Folio_infra;
        $this->db->query($sql);
        return $this->db->register();
    }
    public function getEventosRelacionados($Id_Seguimiento){
        $sql = "SELECT 	*
        FROM gc_evento_filtro_3
        WHERE gc_evento_filtro_3.Id_Seguimiento = " . $Id_Seguimiento;
        $this->db->query($sql);
        return $this->db->registers();
    }
    
    public function getDelitosRelacionados($Id_Seguimiento){
        $sql = "SELECT 	*
        FROM delitos_asociados_seguimiento
        WHERE delitos_asociados_seguimiento.Id_Seguimiento = " . $Id_Seguimiento;
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getPersonas($Id_Seguimiento){
        $sql = "SELECT 	*
        FROM persona_gabinete
        WHERE persona_gabinete.Id_Seguimiento = ". $Id_Seguimiento;
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getPersona($Id_Persona){
        $sql = "SELECT 	*
        FROM persona_gabinete
        WHERE persona_gabinete.Id_Persona = " . $Id_Persona;
        $this->db->query($sql);
        return $this->db->register();
    }
    public function getEntrevistasNo($Id_Seguimiento){
        $sql = "SELECT 	*
        FROM persona_entrevista
        WHERE persona_entrevista.Id_Seguimiento = ". $Id_Seguimiento." AND persona_entrevista.Capturado_Seguimiento = 'NO'";
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getEntrevistasSi($Id_Seguimiento){
        $sql = "SELECT 	*
        FROM persona_entrevista
        WHERE persona_entrevista.Id_Seguimiento = ". $Id_Seguimiento." AND persona_entrevista.Capturado_Seguimiento = 'SI'";
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getPrincipalesEntrevista($Id_Persona_Entrevista){
        $sql = "SELECT 	*
        FROM persona_entrevista
        WHERE persona_entrevista.Id_Persona_Entrevista = " . $Id_Persona_Entrevista;
        $this->db->query($sql);
        return $this->db->register();
    }
    public function getEntrevistas($Id_Persona_Entrevista){
        $sql = "SELECT 	*
        FROM entrevista_detenido
        WHERE entrevista_detenido.Id_Persona_Entrevista = " . $Id_Persona_Entrevista;
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getForensias($Id_Persona_Entrevista){
        $sql = "SELECT 	*
        FROM  forensias_detenido
        WHERE  forensias_detenido.Id_Persona_Entrevista = " . $Id_Persona_Entrevista;
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getUbicaciones($Id_Persona_Entrevista){
        $sql = "SELECT 	*
        FROM  ubicaciones_detenido
        WHERE  ubicaciones_detenido.Id_Persona_Entrevista = " . $Id_Persona_Entrevista;
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getDomicilios($Ids_Datos,$Tipo_Entidad){
        $data = []; 
        $i=0;
        $IdsArray = json_decode($Ids_Datos);
        foreach($IdsArray as $Id_dato){
            $sql = "SELECT 	*
            FROM domicilios
            WHERE domicilios.Id_Dato = " . $Id_dato." AND domicilios.Tipo_Entidad ='".$Tipo_Entidad."'";
            $this->db->query($sql);
            $data[$i]= $this->db->registers();
            $i++;
        }
        return $data;
    }
    public function getDomiciliosOneRegister($Id_dato,$Tipo_Entidad){
        $sql = "SELECT 	*
        FROM domicilios
        WHERE domicilios.Id_Dato = " . $Id_dato." AND domicilios.Tipo_Entidad ='".$Tipo_Entidad."'";
        $this->db->query($sql);
        $this->db->registers();
        return $this->db->registers();;
    }
    public function getDomiciliosConfirmados($Id_dato,$Tipo_Entidad){
        $sql = "SELECT 	*
        FROM domicilios
        WHERE domicilios.Id_Dato = " . $Id_dato." AND domicilios.Tipo_Entidad ='".$Tipo_Entidad."' AND domicilios.Estatus='CONFIRMADO'";
        $this->db->query($sql);
        $this->db->registers();
        return $this->db->registers();;
    }
    public function getDomiciliosPresuntos($Id_dato,$Tipo_Entidad){
        $sql = "SELECT 	*
        FROM domicilios
        WHERE domicilios.Id_Dato = " . $Id_dato." AND domicilios.Tipo_Entidad ='".$Tipo_Entidad."' AND domicilios.Estatus='PRESUNTO'";
        $this->db->query($sql);
        $this->db->registers();
        return $this->db->registers();;
    }
    public function getRedesSocialPerfiles($Id_dato){
        $sql = "SELECT 	*
        FROM redes_sociales_gabinete
        WHERE redes_sociales_gabinete.Id_Persona  = " . $Id_dato." AND redes_sociales_gabinete.Tipo_Enlace ='PERFIL'";
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getAntecedentes($Ids_Datos,$Tipo_Entidad){
        $data = []; 
        $i=0;
        $IdsArray = json_decode($Ids_Datos);
        foreach($IdsArray as $Id_dato){
            $sql = "SELECT 	*
            FROM antecedentes_gabinete
            WHERE antecedentes_gabinete.Id_Dato = " . $Id_dato." AND antecedentes_gabinete.Tipo_Entidad ='".$Tipo_Entidad."'";
            $this->db->query($sql);
            $data[$i]= $this->db->registers();
            $i++;
        }
        return $data;
    }
    public function getAntecedentesOneRegister($Id_dato,$Tipo_Entidad){
        $sql = "SELECT 	*
        FROM antecedentes_gabinete
        WHERE antecedentes_gabinete.Id_Dato = " . $Id_dato." AND antecedentes_gabinete.Tipo_Entidad ='".$Tipo_Entidad."'";
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getForencias($Ids_Datos){
        $data = []; 
        $i=0;
        $IdsArray = json_decode($Ids_Datos);
        foreach($IdsArray as $Id_dato){
            $sql = "SELECT 	*
            FROM forencias_gabinete
            WHERE forencias_gabinete.Id_Persona  = " . $Id_dato;
            $this->db->query($sql);
            $data[$i]= $this->db->registers();
            $i++;
        }
        return $data;
    }
    public function getRedesSociales($Ids_Datos){
        $data = []; 
        $i=0;
        $IdsArray = json_decode($Ids_Datos);
        foreach($IdsArray as $Id_dato){
            $sql = "SELECT 	*
            FROM redes_sociales_gabinete
            WHERE redes_sociales_gabinete.Id_Persona  = " . $Id_dato;
            $this->db->query($sql);
            $data[$i]= $this->db->registers();
            $i++;
        }
        return $data;
    }
    public function getForenciasOneRegister($Id_dato){
        $sql = "SELECT 	*
        FROM forencias_gabinete
        WHERE forencias_gabinete.Id_Persona  = " . $Id_dato;
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getRedesSocialesOneRegister($Id_dato){
        $sql = "SELECT 	*
        FROM redes_sociales_gabinete
        WHERE redes_sociales_gabinete.Id_Persona  = " . $Id_dato;
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getVehiculos($Id_Seguimiento){
        $sql = "SELECT 	*
        FROM vehiculos_gabinete
        WHERE vehiculos_gabinete.Id_Seguimiento = " . $Id_Seguimiento;
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getVehiculo($Id_Vehiculo){
        $sql = "SELECT 	*
        FROM vehiculos_gabinete
        WHERE vehiculos_gabinete.Id_Vehiculo  = " . $Id_Vehiculo;
        $this->db->query($sql);
        return $this->db->register();
    }
    public function DesAsociaPersona($Id_Persona){
       
        $response['status']=true;
        $response['Id_Persona']=$Id_Persona;
        try {
            $this->db->beginTransaction(); 
            $sql = "UPDATE persona_gabinete SET Id_Seguimiento =NULL WHERE Id_Persona = ".$Id_Persona;
            $this->db->query($sql);
            $this->db->execute();
            $this->db->commit(); //Si no hubo fallos en ninguna insercion asegura los cambios
            $sqlEjecutados=$sql;

        } catch (Exception $e) {
            $response['status'] = false;
            $response['error_message'] = 'Hubo un error en la Base de datos.';
            $response['error_sql'] = $sql;
            $this->db->rollBack();
        }
        $response['sqlEjecutados'] = $sqlEjecutados;
        return  $response;
    }
    public function DesasociaVehiculo($Id_Vehiculo){
       
        $response['status']=true;
        $response['Id_Vehiculo']=$Id_Vehiculo;
        try {
            $this->db->beginTransaction(); 
            $sql = "UPDATE vehiculos_gabinete SET Id_Seguimiento =NULL WHERE Id_Vehiculo = ".$Id_Vehiculo;
            $this->db->query($sql);
            $this->db->execute();
            $this->db->commit(); //Si no hubo fallos en ninguna insercion asegura los cambios
            $sqlEjecutados=$sql;

        } catch (Exception $e) {
            $response['status'] = false;
            $response['error_message'] = 'Hubo un error en la Base de datos.';
            $response['error_sql'] = $sql;
            $this->db->rollBack();
        }
        $response['sqlEjecutados'] = $sqlEjecutados;
        return  $response;
    }
    public function DesasociaDomicilio($Id_Domicilio){
       
        $response['status']=true;
        $response['Id_Domicilio']=$Id_Domicilio;
        try {
            $this->db->beginTransaction(); 
            $sql = "UPDATE domicilios SET Id_Dato =NULL WHERE Id_Domicilio = ".$Id_Domicilio;
            $this->db->query($sql);
            $this->db->execute();
            $this->db->commit(); //Si no hubo fallos en ninguna insercion asegura los cambios
            $sqlEjecutados=$sql;

        } catch (Exception $e) {
            $response['status'] = false;
            $response['error_message'] = 'Hubo un error en la Base de datos.';
            $response['error_sql'] = $sql;
            $this->db->rollBack();
        }
        $response['sqlEjecutados'] = $sqlEjecutados;
        return  $response;
    }
    public function DesasociaAntecedente($Id_Antecedente){
       
        $response['status']=true;
        $response['Id_Antecedente']=$Id_Antecedente;
        try {
            $this->db->beginTransaction(); 
            $sql = "UPDATE antecedentes_gabinete SET Id_Dato =NULL WHERE Id_Antecedente = ".$Id_Antecedente;
            $this->db->query($sql);
            $this->db->execute();
            $this->db->commit(); //Si no hubo fallos en ninguna insercion asegura los cambios
            $sqlEjecutados=$sql;

        } catch (Exception $e) {
            $response['status'] = false;
            $response['error_message'] = 'Hubo un error en la Base de datos.';
            $response['error_sql'] = $sql;
            $this->db->rollBack();
        }
        $response['sqlEjecutados'] = $sqlEjecutados;
        return  $response;
    }
    public function DesasociaForencia($Id_Forencia){
       
        $response['status']=true;
        $response['Id_Forencia']=$Id_Forencia;
        try {
            $this->db->beginTransaction(); 
            $sql = "UPDATE forencias_gabinete SET Id_Persona =NULL WHERE Id_Forencia = ".$Id_Forencia;
            $this->db->query($sql);
            $this->db->execute();
            $this->db->commit(); //Si no hubo fallos en ninguna insercion asegura los cambios
            $sqlEjecutados=$sql;

        } catch (Exception $e) {
            $response['status'] = false;
            $response['error_message'] = 'Hubo un error en la Base de datos.';
            $response['error_sql'] = $sql;
            $this->db->rollBack();
        }
        $response['sqlEjecutados'] = $sqlEjecutados;
        return  $response;
    }
    public function DesasociaRedSocial($Id_Registro){
       
        $response['status']=true;
        $response['Id_Registro']=$Id_Registro;
        try {
            $this->db->beginTransaction(); 
            $sql = "UPDATE redes_sociales_gabinete SET Id_Persona =NULL WHERE Id_Registro = ".$Id_Registro;
            $this->db->query($sql);
            $this->db->execute();
            $this->db->commit(); //Si no hubo fallos en ninguna insercion asegura los cambios
            $sqlEjecutados=$sql;

        } catch (Exception $e) {
            $response['status'] = false;
            $response['error_message'] = 'Hubo un error en la Base de datos.';
            $response['error_sql'] = $sql;
            $this->db->rollBack();
        }
        $response['sqlEjecutados'] = $sqlEjecutados;
        return  $response;
    }

    public function UpdateCambioAltoImpacto($Id_Seguimiento,$Alto_Impacto){
       
        $response['status']=true;
        try {
            $this->db->beginTransaction(); 
            $sql = "UPDATE seguimiento_gabinete SET Alto_Impacto = ".$Alto_Impacto." WHERE Id_Seguimiento  = ".$Id_Seguimiento;
            $this->db->query($sql);
            $this->db->execute();
            $this->db->commit(); //Si no hubo fallos en ninguna insercion asegura los cambios
            $sqlEjecutados=$sql;

        } catch (Exception $e) {
            $response['status'] = false;
            $response['error_message'] = 'Hubo un error en la Base de datos.';
            $response['error_sql'] = $sql;
            $this->db->rollBack();
        }
        $response['sqlEjecutados'] = $sqlEjecutados;
        return  $response;
    }

    /*-------------------------------GET INFORMACION COMPLETA DE LOS EVENTOS ASOCIADOS AL SEGUIMIENTO PARA PDF -----------------------------------*/
    public function getPrincipalesEventoAll($Folio_infra){
        $sql = "SELECT 	*
        FROM evento
        WHERE evento.Folio_infra = " . $Folio_infra;
        $this->db->query($sql);
        return $this->db->register();
    }
    public function getInfoDetencion($Folio_infra){
        $sql = "SELECT 	*
        FROM ubicaciones_detencion_evento
        WHERE ubicaciones_detencion_evento.Folio_infra = " . $Folio_infra;
        $this->db->query($sql);
        return $this->db->register();
    }
    public function getDelitosC($Folio_infra){
        $sql = "SELECT 	*
        FROM delitos_asociados_evento
        WHERE delitos_asociados_evento.Folio_infra = " . $Folio_infra;

        $this->db->query($sql);
        return $this->db->registers();
    }

    public function getHechosC($Folio_infra){
        $sql = "SELECT 	*
        FROM historico_descripcion_hecho
        WHERE historico_descripcion_hecho.Folio_infra = " . $Folio_infra;

        $this->db->query($sql);
        return $this->db->registers();

    }

    public function getEntrevistasEvento($Folio_infra){
        $sql = "SELECT 	*
        FROM entrevistas_seguimiento
        WHERE entrevistas_seguimiento.Folio_infra = " . $Folio_infra;

        $this->db->query($sql);
        return $this->db->registers();

    }
    public function getResponsablesC($Folio_infra){
        $sql = "SELECT 	*
        FROM persona_p
        WHERE persona_p.Folio_infra = " . $Folio_infra;

        $this->db->query($sql);
        return $this->db->registers();

    }
    public function getVehiculosC($Folio_infra){
        $sql = "SELECT 	*
        FROM vehiculo_p
        WHERE vehiculo_p.Folio_infra = " . $Folio_infra;

        $this->db->query($sql);
        return $this->db->registers();

    }

    public function getFotos($Folio_infra){
        $sql = "SELECT 	*
        FROM fotos_seguimiento
        WHERE fotos_seguimiento.Folio_infra = " . $Folio_infra;

        $this->db->query($sql);
        return $this->db->registers();

    }

}
?>