<?php
class Entrevista{
    public $db; //Variable para instanciar el objeto PDO
    public $db2;
    public function __construct(){
        $this->db = new Base(); //Se instancia el objeto con los métodos de PDO
        $this->db2 = new Base2(); //Se instancia el objeto con los métodos de solo consulta
    }


    //genera la consulta where dependiendo del filtro
    public function generateFromWhereSentence($cadena = "", $filtro = '1'){
        $from_where_sentence = "";
        $cadena=$this->eliminar_acentos($cadena);
        switch ($filtro) {
            case '1':   //general
                $palabras = explode(",", strtolower($cadena));//Se obtiene la cadena con la cual se quiere buscar
                $articulos = array('el', 'la', 'los', 'las', 'un', 'de', 'en', 'unos', 'una', 'unas', 'a', 'con', 'y', 'o', 'u');//arreglo con las palabras a ignorar en la busqueda, normalmete artículos
                $palabras = array_diff($palabras, $articulos);
                $from_where_sentence .= " FROM gc_entrevistas_filtro_1 WHERE Id_Persona_Entrevista > 0";
                foreach($palabras as $palabra){
                    $palabra=ltrim($palabra, " ");
                    $palabra=rtrim($palabra, " ");
                    $from_where_sentence.= " AND    (   Id_Persona_Entrevista LIKE '%" . $palabra . "%' OR 
                                                        Nombre_completo_Entrevistado LIKE '%" . $palabra . "%' OR 
                                                        Alias LIKE '%" . $palabra . "%' OR 
                                                        Remisiones LIKE '%" . $palabra . "%' OR 
                                                        Capturo LIKE '%" . $palabra . "%' OR 
                                                        Ubicacion_detencion LIKE '%" . $palabra . "%' OR 
                                                        Detenido_por LIKE '%" . $palabra . "%' OR
                                                        Banda LIKE '%" . $palabra . "%' OR
                                                        Alias_Referidos LIKE '%" . $palabra . "%' OR
                                                        Entrevistas_concat LIKE '%" . $palabra . "%' OR
                                                        Ubicaciones_concat LIKE '%" . $palabra . "%' OR
                                                        Descripcion_Forensia_concat LIKE '%" . $palabra . "%'
                                                    ) ";          
                }
                $from_where_sentence .= $this->getFechaCondition();
                $from_where_sentence .= " ORDER BY Id_Persona_Entrevista DESC";
            break;
            case '2':   //general
                $palabras = explode(",", strtolower($cadena));//Se obtiene la cadena con la cual se quiere buscar
                $articulos = array('el', 'la', 'los', 'las', 'un', 'de', 'en', 'unos', 'una', 'unas', 'a', 'con', 'y', 'o', 'u');//arreglo con las palabras a ignorar en la busqueda, normalmete artículos
                $palabras = array_diff($palabras, $articulos);
                $from_where_sentence .= " FROM gc_entrevistas_filtro_2 WHERE Id_Persona_Entrevista > 0";
                foreach($palabras as $palabra){
                    $palabra=ltrim($palabra, " ");
                    $palabra=rtrim($palabra, " ");
                    $from_where_sentence.= " AND    (   Id_Persona_Entrevista LIKE '%" . $palabra . "%' OR 
                                                        Id_Ubicaciones_Entrevista LIKE '%" . $palabra . "%' OR 
                                                        FechaHora_Creacion LIKE '%" . $palabra . "%' OR 
                                                        Nombre_completo_Entrevistado LIKE '%" . $palabra . "%' OR 
                                                        Remisiones LIKE '%" . $palabra . "%' OR 
                                                        Detenido_por LIKE '%" . $palabra . "%' OR
                                                        Banda LIKE '%" . $palabra . "%' OR
                                                        Ubicacion LIKE '%" . $palabra . "%' OR
                                                        CoordY LIKE '%" . $palabra . "%' OR
                                                        CoordX LIKE '%" . $palabra . "%' OR
                                                        Observaciones LIKE '%" . $palabra . "%'
                                                    ) ";          
                }
                $from_where_sentence .= $this->getFechaCondition();
            break;
            case '3':   //general
                $palabras = explode(",", strtolower($cadena));//Se obtiene la cadena con la cual se quiere buscar
                $articulos = array('el', 'la', 'los', 'las', 'un', 'de', 'en', 'unos', 'una', 'unas', 'a', 'con', 'y', 'o', 'u');//arreglo con las palabras a ignorar en la busqueda, normalmete artículos
                $palabras = array_diff($palabras, $articulos);
                $from_where_sentence .= " FROM gc_entrevistas_filtro_3 WHERE Id_Persona_Entrevista > 0";
                foreach($palabras as $palabra){
                    $palabra=ltrim($palabra, " ");
                    $palabra=rtrim($palabra, " ");
                    $from_where_sentence.= " AND    (   Id_Persona_Entrevista LIKE '%" . $palabra . "%' OR 
                                                        NOMBRES LIKE '%" . $palabra . "%' OR 
                                                        PRIM_APELLIDO LIKE '%" . $palabra . "%' OR 
                                                        SEG_APELLIDO LIKE '%" . $palabra . "%' OR 
                                                        ALIAS LIKE '%" . $palabra . "%' OR 
                                                        BANDA LIKE '%" . $palabra . "%' OR
                                                        DELITOS_ASOCIADOS LIKE '%" . $palabra . "%' OR
                                                        CAPTURA LIKE '%" . $palabra . "%'
                                                    ) ";          
                }
                $from_where_sentence .= $this->getFechaCondition();
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
    public function getAllInfoEntrevistaByCadena($from_where_sentence = ""){ 
    	$sqlAux = "SELECT * "
    				.$from_where_sentence."
                    ";  //query a la DB
        $this->db->query($sqlAux);          //se prepara el query mediante PDO
        return $this->db->registers();      //retorna todos los registros devueltos por la consulta
    }
    public function getEntrevistasByCadena($cadena, $filtro ){
        //CONSULTA COINCIDENCIAS DE CADENA PARA EVENTOS 
        $cadena=$this->eliminar_acentos($cadena);
        //sentencia from_where para hacer la busqueda por la cadena ingresada
        $from_where_sentence = $this->generateFromWhereSentence($cadena, $filtro);
        $numPage = 1;
        $no_of_records_per_page = NUM_MAX_REG_PAGE; //total de registros por pagination
        $offset = ($numPage - 1) * $no_of_records_per_page; // desplazamiento conforme a la pagina
        $results = $this->getTotalPages($no_of_records_per_page, $from_where_sentence);  //total de páginas conforme a la busqueda
        //info de retorno para la creacion de los links conforme a la cadena ingresada
        $data['rows_Entre'] = $this->getDataCurrentPage($offset, $no_of_records_per_page, $from_where_sentence);   //se obtiene la información de la página actual
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
    /*--------------------------------FUNCIÓN PARA FILTRAR POR UN RANGO DE FECHAS ESPECÍFICADO POR EL USUARIO-----------------------------------*/
    public function getFechaCondition(){
        $cad_fechas = "";
        if (isset($_SESSION['userdataSIC']->rango_inicio_es) && isset($_SESSION['userdataSIC']->rango_fin_es)) { //si no ingresa una fecha se seleciona el día de hoy como máximo
            $rango_inicio = $_SESSION['userdataSIC']->rango_inicio_es;
            $rango_fin = $_SESSION['userdataSIC']->rango_fin_es;
            $cad_fechas = " AND 
                            ((FechaHora_Creacion >= '" . $rango_inicio . " 00:00:00'  AND 
                            FechaHora_Creacion <= '" . $rango_fin . " 23:59:59' )OR
                            (FechaHora_Creacion >= CAST('" . $rango_inicio . " 00:00:00' AS date) AND
                            FechaHora_Creacion <= CAST('" . $rango_fin . " 23:59:59' AS date)))
                            ";
        }
        return $cad_fechas;
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
    /*------------------------Funcion para sacar los datos de consulta en sarai -----------------------------*/
    public function getInfoRemision($No_Remision){//sacamos la informacion de la remision
        $sql = "SELECT 	*
        FROM casos_consulta_detenido_entrevistas
        WHERE casos_consulta_detenido_entrevistas.No_Remision = " . $No_Remision;
        $this->db2->query($sql);
        return $this->db2->register();
    }
    public function getRemisiones(){
        $sql = "SELECT No_Remision,Nombre_completo,Detenido_por FROM casos_consulta_detenido_entrevistas ORDER BY (ID)";
        $this->db2->query($sql);
        return $this->db2->registers();
    }
    /*-----------------------FUNCION PARA INSERTAR UNA NUEVA ENTREVISTA--------------------------------------- */
    public function insertNuevaPersonaEntrevistada($post){
        //Valores iniciales de retorno
        $data['status'] = true;
        $date = date("Ymdhis");
        $data['Id_Persona_Entrevista'] = -1;
        $data['sqlEjecutados'] = "";
        $sqlEjecutados="";
        try{
            $this->db->beginTransaction();  //Se inicializa la transaccion para tener un punto de retorno en caso de fallo
            if($post['Id_Seguimiento']=='SD'){
                $sql = "INSERT
                INTO  persona_entrevista(
                    FechaHora_Creacion,
                    Nombre,
                    Ap_Paterno,
                    Ap_Materno,
                    Alias,
                    Fecha_Nacimiento,
                    CURP,
                    Telefono,
                    Edad,
                    Colonia_Domicilio,
                    Calle_Domicilio,
                    Calle2_Domicilio,
                    No_Exterior_Domicilio,
                    No_Interior_Domicilio,
                    Colonia_Detencion,
                    Calle_Detencion,
                    Calle2_Detencion,
                    No_Exterior_Detencion,
                    No_Interior_Detencion,
                    Detenido_por,
                    Asociado_A,
                    Banda,
                    Remisiones,
                    Capturo
                )
                VALUES(
                    '".$post['fechahora_captura_principales']."',
                    '".strtoupper($post['nombre'])."',
                    '".strtoupper($post['ap_paterno'])."',
                    '".strtoupper($post['ap_materno'])."',
                    '".strtoupper($post['alias'])."',
                    '".$post['FechaNacimiento_principales']."',
                    '".strtoupper($post['curp'])."',
                    '".$post['num_tel']."',
                    '".$post['edad_principales']."',
                    '".strtoupper($post['colonia_dom'])."',
                    '".strtoupper($post['calle_dom'])."',
                    '".strtoupper($post['calle2_dom'])."',
                    '".$post['numExt_dom']."',
                    '".$post['numInt_dom']."',
                    '".strtoupper($post['colonia_detencion'])."',
                    '".strtoupper($post['calle_detencion'])."',
                    '".strtoupper($post['calle2_detencion'])."',
                    '".$post['numExt_detencion']."',
                    '".$post['numInt_detencion']."',
                    '".strtoupper($post['detenido_por'])."',
                    '".strtoupper($post['asociado_a'])."',
                    '".strtoupper($post['banda'])."',
                    '".$post['remisiones']."',
                    '".$post['captura_dato_entrevista']."'
                )";

            }else{
                $sql = "INSERT
                INTO  persona_entrevista(
                    Id_Seguimiento,
                    Capturado_Seguimiento,
                    FechaHora_Creacion,
                    Nombre,
                    Ap_Paterno,
                    Ap_Materno,
                    Alias,
                    Fecha_Nacimiento,
                    CURP,
                    Telefono,
                    Edad,
                    Colonia_Domicilio,
                    Calle_Domicilio,
                    Calle2_Domicilio,
                    No_Exterior_Domicilio,
                    No_Interior_Domicilio,
                    Colonia_Detencion,
                    Calle_Detencion,
                    Calle2_Detencion,
                    No_Exterior_Detencion,
                    No_Interior_Detencion,
                    Detenido_por,
                    Asociado_A,
                    Banda,
                    Zona,
                    Remisiones,
                    Capturo
                )
                VALUES(
                    ".$post['Id_Seguimiento'].",
                    '".$post['Capturado_Seguimiento']."',
                    '".$post['fechahora_captura_principales']."',
                    '".strtoupper($post['nombre'])."',
                    '".strtoupper($post['ap_paterno'])."',
                    '".strtoupper($post['ap_materno'])."',
                    '".strtoupper($post['alias'])."',
                    '".$post['FechaNacimiento_principales']."',
                    '".strtoupper($post['curp'])."',
                    '".$post['num_tel']."',
                    '".$post['edad_principales']."',
                    '".strtoupper($post['colonia_dom'])."',
                    '".strtoupper($post['calle_dom'])."',
                    '".strtoupper($post['calle2_dom'])."',
                    '".$post['numExt_dom']."',
                    '".$post['numInt_dom']."',
                    '".strtoupper($post['colonia_detencion'])."',
                    '".strtoupper($post['calle_detencion'])."',
                    '".strtoupper($post['calle2_detencion'])."',
                    '".$post['numExt_detencion']."',
                    '".$post['numInt_detencion']."',
                    '".strtoupper($post['detenido_por'])."',
                    '".strtoupper($post['asociado_a'])."',
                    '".strtoupper($post['banda'])."',
                    '".$post['zona']."',
                    '".$post['remisiones']."',
                    '".$post['captura_dato_entrevista']."'
                )";
            }
            $this->db->query($sql);
            $this->db->execute();
            $this->db->query("SELECT LAST_INSERT_ID() as Id_Persona_Entrevista"); //Se recupera el Id_Persona_Entrevista que se a creado recientemente
            $Id_Persona_Entrevista = $this->db->register()->Id_Persona_Entrevista;
            $sqlEjecutados=$sql;

            $data['Id_Persona_Entrevista'] = $Id_Persona_Entrevista; 
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
    public function UpdateEntrevistaPrincipales($post){
        $response['status'] = true;
        $response['sqlEjecutados'] = "";
        $sqlEjecutados="";
        try {
            $this->db->beginTransaction(); //transaction para evitar errores de inserción
                if($post['Id_Seguimiento']=='SD'){
                    $Id_Seguimiento='null';
                }else{
                    $Id_Seguimiento=$post['Id_Seguimiento'];
                }
                $sql = "    UPDATE persona_entrevista
                SET  Id_Seguimiento = " . $Id_Seguimiento  .", 
                    Capturado_Seguimiento = '" . trim($post['Capturado_Seguimiento'])  ."',
                    Nombre = '" . trim(strtoupper($post['nombre']))  ."',
                    Ap_Paterno = '" . trim(strtoupper($post['ap_paterno'])) . "',
                    Ap_Materno = '" . trim(strtoupper($post['ap_materno'])) . "',
                    Alias = '" . trim(strtoupper($post['alias'])) . "',
                    Fecha_Nacimiento = '" . trim($post['FechaNacimiento_principales']) . "',
                    CURP = '" . trim(strtoupper($post['curp'])) . "',
                    Telefono = '" . trim($post['num_tel']) . "',
                    Edad = '" . trim($post['edad_principales']) . "',
                    Colonia_Domicilio = '" . trim(strtoupper($post['colonia_dom'])) . "',
                    Calle_Domicilio = '" . trim(strtoupper($post['calle_dom'])) . "',
                    Calle2_Domicilio = '" . trim(strtoupper($post['calle2_dom'])) . "',
                    No_Exterior_Domicilio = '" . trim($post['numExt_dom']) . "',
                    No_Interior_Domicilio = '" . trim($post['numInt_dom']) . "',
                    Colonia_Detencion = '" . trim(strtoupper($post['colonia_detencion'])) . "',
                    Calle_Detencion = '" . trim(strtoupper($post['calle_detencion'])) . "',
                    Calle2_Detencion = '" . trim(strtoupper($post['calle2_detencion'])) . "',
                    No_Exterior_Detencion = '" . trim($post['numExt_detencion']) . "',
                    No_Interior_Detencion ='". trim($post['numInt_detencion']) . "',
                    Detenido_por ='". trim(strtoupper($post['detenido_por'])) . "',
                    Asociado_A ='". trim(strtoupper($post['asociado_a'])) . "',
                    Banda ='". trim(strtoupper($post['banda'])) . "',
                    Remisiones ='". trim($post['remisiones']) . "',
                    Asignado_a ='". trim(strtoupper($post['Asignado'])) . "',
                    Relevancia ='". $post['Relevancia'] . "',
                    Zona ='". $post['zona'] . "',
                    Foto ='". $post['Foto'] . "',
                    Img_64 ='". $post['Img_64'] . "'
                    WHERE Id_Persona_Entrevista = " . trim($post['id_persona_entrevista']) . "
                ";
            $this->db->query($sql);
            $this->db->execute();
            $sqlEjecutados=$sql;
            $this->db->commit(); //Si no hubo fallos en ninguna insercion asegura los cambios

        } catch (Exception $e) {
            $response['status'] = false;
            $response['error_message'] = $e;
            $response['error_sql'] = $sql;
            $this->db->rollBack();//Si hubise fallo en alguna insercion regresa al estado en el que estaba la tabla al momento que se declaro el inicio de la transaccion  
        }
        $response['sqlEjecutados'] = $sqlEjecutados;
        return $response;
    }
    public function UpdateEntrevistas($post){
        $response['status'] = true;
        $response['sqlEjecutados'] = "";
        $sqlEjecutados="";
        try {
            $this->db->beginTransaction(); //transaction para evitar errores de inserción
            if (isset($post['entrevistas_table'])) {
                $EntrevistasArray = json_decode($post['entrevistas_table']);
                foreach ($EntrevistasArray as $Entrevista) {
                    if($Entrevista->row->Id_Entrevista=='SD'){
                        //logica de insert
                        if($Entrevista->row->nameImage!='null'){
                            $Nombre_Foto=$Entrevista->row->nameImage.'.png';
                        }else{
                            $Nombre_Foto='SD';
                        }
                        if($Entrevista->row->imagebase64!='null'){
                            $imagebase64=$Entrevista->row->imagebase64;
                        }else{
                            $imagebase64='SD';
                        }
                        $Descripcion=$this->remplazoCadena($Entrevista->row->Entrevista);
                        $sql = "  INSERT
                        INTO entrevista_detenido(
                            Id_Persona_Entrevista  ,
                            Indicativo_Entrevistador ,
                            Relevancia,
                            Alias_Referidos,
                            Entrevista,
                            Fecha_Entrevista,
                            Hora_Entrevista,
                            Capturo,
                            Foto,
                            Img_64
                        )VALUES(
                            '".$Entrevista->row->Id_Persona_Entrevista."',
                            '".$Entrevista->row->Indicativo_Entrevistador. "',
                            '".$Entrevista->row->Relevancia. "',
                            '".$Entrevista->row->Alias_Referidos. "',
                            '".$Descripcion. "',
                            '".$Entrevista->row->Fecha_Entrevista. "',
                            '".$Entrevista->row->Hora_Entrevista. "',
                            '".$Entrevista->row->Capturo. "',
                            '".$Nombre_Foto. "',
                            '".$imagebase64. "'
                        )";
                        $this->db->query($sql);
                        $this->db->execute();
                        $sqlrecortado=explode(";base64", strtolower($sql));
                        $sqlEjecutados.=$sqlrecortado[0];
                       
                    }else{
                        //logica de update 
                        if($Entrevista->row->nameImage!='null'){
                            $Nombre_Foto=$Entrevista->row->nameImage.'.png';
                        }else{
                            $Nombre_Foto='SD';
                        }
                        if($Entrevista->row->imagebase64!='null'){
                            $imagebase64=$Entrevista->row->imagebase64;
                        }else{
                            $imagebase64='SD';
                        }
                        $Descripcion=$this->remplazoCadena($Entrevista->row->Entrevista);
                        $sql = "    UPDATE entrevista_detenido
                        SET 
                            Indicativo_Entrevistador = '" .$Entrevista->row->Indicativo_Entrevistador. "',
                            Relevancia = '" . $Entrevista->row->Relevancia . "',
                            Alias_Referidos = '" . $Entrevista->row->Alias_Referidos . "',
                            Entrevista = '" . $Descripcion. "',
                            Fecha_Entrevista = '" . $Entrevista->row->Fecha_Entrevista . "',
                            Hora_Entrevista ='". $Entrevista->row->Hora_Entrevista . "',
                            Foto ='". $Nombre_Foto . "',
                            Img_64 ='". $imagebase64 . "'
                            WHERE Id_Entrevista  = " . $Entrevista->row->Id_Entrevista. "
                        ";
                        $this->db->query($sql);
                        $this->db->execute();
                        //$sqlEjecutados.=$sql;
                    }
                }
             $this->db->commit(); //Si no hubo fallos en ninguna insercion asegura los cambios           
            }
        } catch (Exception $e) {
            $response['status'] = false;
            $response['error_message'] = $e;
            $response['error_sql'] = $sql;
            $this->db->rollBack();//Si hubise fallo en alguna insercion regresa al estado en el que estaba la tabla al momento que se declaro el inicio de la transaccion  
        }
        $response['sqlEjecutados'] = $sqlEjecutados;
        return $response;
    }
    public function UpdateForensiasFetch($post){
        $response['status'] = true;
        $response['sqlEjecutados'] = "";
        $sqlEjecutados="";
        try {
            $this->db->beginTransaction();  //Se inicializa la transaccion para tener un punto de retorno en caso de fallo
            if (isset($post['Forensiastable'])) {
                $ForensiaArray = json_decode($post['Forensiastable']);
                foreach ($ForensiaArray as $Forensia) {
                    if($Forensia->row->Id_Forensia_Entrevista=='SD'){
                        //logica de insert
                        if($Forensia->row->nameImage!='null'){
                            $Nombre_Foto=$Forensia->row->nameImage.'.png';
                        }else{
                            $Nombre_Foto='SD';
                        }
                        if($Forensia->row->imagebase64!='null'){
                            $imagebase64=$Forensia->row->imagebase64;
                        }else{
                            $imagebase64='SD';
                        }
                        $Descripcion = $this->remplazoCadena($Forensia->row->Descripcion_Forensia);
                        $Dato_Relevante = $this->remplazoCadena($Forensia->row->Dato_Relevante);
                        $sql="INSERT
                        INTO forensias_detenido(
                            Id_Persona_Entrevista,
                            Id_Dato,
                            Descripcion_Forensia,
                            Capturo,
                            Foto,
                            Img_64,
                            Tipo_Relacion,
                            Tipo_Dato,
                            Dato_Relevante
                        )VALUES(
                            '".$Forensia->row->Id_Persona_Entrevista."',
                            ".$Forensia->row->Id_Dato.",
                            '".strtoupper($Descripcion)."',
                            '".$Forensia->row->Capturo. "',
                            '".$Nombre_Foto. "',
                            '".$imagebase64. "',
                            '".$Forensia->row->Tipo_Relacion. "',
                            '".$Forensia->row->Tipo_Dato. "',
                            '".strtoupper($Dato_Relevante). "'
                        )";
                        $this->db->query($sql);
                        $this->db->execute();
                        
                        $sqlrecortado=explode(";base64", strtolower($sql));
                        $sqlEjecutados.=$sqlEjecutados.' '.$sqlrecortado[0];

                    }else{
                        //logica de update
                        if($Forensia->row->nameImage!='null'){
                            $Nombre_Foto=$Forensia->row->nameImage.'.png';
                        }else{
                            $Nombre_Foto='SD';
                        }
                        if($Forensia->row->imagebase64!='null'){
                            $imagebase64=$Forensia->row->imagebase64;
                        }else{
                            $imagebase64='SD';
                        }
                        $Descripcion=$this->remplazoCadena($Forensia->row->Descripcion_Forensia);
                        $Dato_Relevante = $this->remplazoCadena($Forensia->row->Dato_Relevante);
                        $sql=" UPDATE forensias_detenido
                                SET 
                                    Id_Dato = " .$Forensia->row->Id_Dato.",
                                    Tipo_Relacion = '" .$Forensia->row->Tipo_Relacion."',
                                    Descripcion_Forensia = '" .strtoupper($Descripcion)."',
                                    Tipo_Dato = '" .$Forensia->row->Tipo_Dato."',
                                    Dato_Relevante = '" .strtoupper($Dato_Relevante)."',
                                    Foto = '" .$Nombre_Foto."',
                                    Img_64 = '" .$imagebase64."'
                                    WHERE Id_Forensia_Entrevista =".$Forensia->row->Id_Forensia_Entrevista."
                                ";
                     
                        $this->db->query($sql);
                        $this->db->execute();
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
    public function UpdateUbicacionesFetch($post){
        $response['status'] = true;
        $response['sqlEjecutados'] = "";
        $sqlEjecutados="";
        try {
            $this->db->beginTransaction();  //Se inicializa la transaccion para tener un punto de retorno en caso de fallo
            if (isset($post['Ubicacionestable'])) {
                $UbicacionArray = json_decode($post['Ubicacionestable']);
                foreach ($UbicacionArray as $Ubicacion) {
                    if($Ubicacion->row->Id_Ubicaciones_Entrevista=='SD'){
                        //logica de insert
                        if($Ubicacion->row->nameImage!='null'){
                            $Nombre_Foto=$Ubicacion->row->nameImage.'.png';
                        }else{
                            $Nombre_Foto='SD';
                        }
                        if($Ubicacion->row->imagebase64!='null'){
                            $imagebase64=$Ubicacion->row->imagebase64;
                        }else{
                            $imagebase64='SD';
                        }
                        $Observaciones_Ubicacion=$this->remplazoCadena($Ubicacion->row->Observaciones_Ubicacion);
                            $sql="INSERT
                            INTO ubicaciones_detenido(
                                Id_Persona_Entrevista,
                                Id_Dato,
                                Colonia,
                                Calle,
                                Calle2,
                                NumExt,
                                NumInt,
                                CP,
                                CoordX,
                                CoordY,
                                Observaciones_Ubicacion,
                                Link_Ubicacion,
                                Estado,
                                Municipio,
                                Foraneo,
                                Capturo,
                                Foto,
                                Img_64,
                                Tipo_Relacion
                            )VALUES(
                                '".$Ubicacion->row->Id_Persona_Entrevista."',
                                ".$Ubicacion->row->Id_Dato.",
                                '".$Ubicacion->row->Colonia."',
                                '".$Ubicacion->row->Calle."',
                                '".$Ubicacion->row->Calle2."',
                                '".$Ubicacion->row->NumExt."',
                                '".$Ubicacion->row->NumInt."',
                                '".$Ubicacion->row->CP."',
                                '".$Ubicacion->row->CoordX."',
                                '".$Ubicacion->row->CoordY."',
                                '".$Observaciones_Ubicacion."',
                                '".$Ubicacion->row->Link_Ubicacion."',
                                '".$Ubicacion->row->Estado."',
                                '".$Ubicacion->row->Municipio."',
                                '".$Ubicacion->row->Foraneo."',
                                '".$Ubicacion->row->Capturo. "',
                                '".$Nombre_Foto. "',
                                '".$imagebase64. "',
                                '".$Ubicacion->row->Tipo_Relacion."'
                            )";
                        $this->db->query($sql);
                        $this->db->execute();
                        $sqlrecortado=explode(";base64", strtolower($sql));
                        $sqlEjecutados.=$sqlEjecutados.' '.$sqlrecortado[0];
                    }else{
                        //logica de update
                        if($Ubicacion->row->nameImage!='null'){
                            $Nombre_Foto=$Ubicacion->row->nameImage.'.png';
                        }else{
                            $Nombre_Foto='SD';
                        }
                        if($Ubicacion->row->imagebase64!='null'){
                            $imagebase64=$Ubicacion->row->imagebase64;
                        }else{
                            $imagebase64='SD';
                        }
                        $Observaciones_Ubicacion=$this->remplazoCadena($Ubicacion->row->Observaciones_Ubicacion);
                        $sql=" UPDATE ubicaciones_detenido
                                SET 
                                    Id_Dato = '" .$Ubicacion->row->Id_Dato."',
                                    Tipo_Relacion = '" .$Ubicacion->row->Tipo_Relacion."',
                                    Colonia = '" .$Ubicacion->row->Colonia."',
                                    Calle = '" .$Ubicacion->row->Calle."',
                                    Calle2 = '" .$Ubicacion->row->Calle2."',
                                    NumExt = '" .$Ubicacion->row->NumExt."',
                                    NumInt = '" .$Ubicacion->row->NumInt."',
                                    CP = '" .$Ubicacion->row->CP."',
                                    CoordX = '" .$Ubicacion->row->CoordX."',
                                    CoordY = '" .$Ubicacion->row->CoordY."',
                                    Observaciones_Ubicacion = '" .$Observaciones_Ubicacion."',
                                    Link_Ubicacion = '" .$Ubicacion->row->Link_Ubicacion."',
                                    Estado = '" .$Ubicacion->row->Estado."',
                                    Municipio = '" .$Ubicacion->row->Municipio."',
                                    Foraneo = '" .$Ubicacion->row->Foraneo."',
                                    Foto = '" .$Nombre_Foto."',
                                    Img_64 = '" .$imagebase64."'
                                    WHERE Id_Ubicaciones_Entrevista  =".$Ubicacion->row->Id_Ubicaciones_Entrevista ;
                        $this->db->query($sql);
                        $this->db->execute();
                        //$sqlrecortado=explode(";base64", strtolower($sql));
                        //$sqlEjecutados.=$sqlEjecutados.' '.$sqlrecortado[0];
                        
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
                        INTO redes_sociales_detenido(
                            Id_Persona_Entrevista ,
                            Id_Dato,
                            Usuario ,
                            Tipo_Enlace ,
                            Enlace,
                            Observacion_Enlace,
                            Capturo,
                            Foto_Nombre,
                            Img_64,
                            Tipo_Relacion
                        )VALUES(
                            '".$RedSocial->row->Id_Persona_Entrevista ."',
                            '".$RedSocial->row->Id_Dato ."',
                            '".$Usuario."',
                            '".$RedSocial->row->Tipo_Enlace. "',
                            '".$Enlace. "',
                            '".$Observacion_Enlace. "',
                            '".$RedSocial->row->Capturo. "',
                            '".$Nombre_Foto. "',
                            '".$imagebase64. "',
                            '".$RedSocial->row->Tipo_Relacion."'
                        )";
                        $this->db->query($sql);
                        $this->db->execute();
                        
                        $sqlrecortado=explode(";base64", strtolower($sql));
                        $sqlEjecutados.=$sqlEjecutados.' '.$sqlrecortado[0];

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
                        $sql=" UPDATE redes_sociales_detenido
                                SET 
                                    Id_Dato = '" .$RedSocial->row->Id_Dato."',
                                    Tipo_Relacion = '" .$RedSocial->row->Tipo_Relacion ."',
                                    Usuario = '" .$Usuario."',
                                    Tipo_Enlace = '" .$RedSocial->row->Tipo_Enlace."',
                                    Enlace = '" .$Enlace."',
                                    Observacion_Enlace = '" .$Observacion_Enlace."',
                                    Capturo = '" .$RedSocial->row->Capturo."',
                                    Foto_Nombre = '" .$Nombre_Foto."',
                                    Img_64 = '" .$imagebase64."'
                                    WHERE Id_Registro  =".$RedSocial->row->Id_Registro ."
                                ";
                        $this->db->query($sql);
                        $this->db->execute();
                        
                        //$sqlrecortado=explode(";base64", strtolower($sql));
                        //$sqlEjecutados.=$sqlEjecutados.' '.$sqlrecortado[0];
                        
                        //logica de update
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
    public function remplazoCadena($entrada){// trata las cadenas para que no halla error en los querys
        $text=$entrada;
        $repla=['"','“','”'];
        $repla2=["'",'‘','’'];
        $text = str_replace($repla, "\"", $text);  
        $text = str_replace($repla2, "\'", $text); 
        return $text;
    }
    /*--------FUNCIONES PARA DESASOCIAR ELEMENTOS DE LAS TABLAS------ */
    public function DesasociaEntrevista($Id_Entrevista){
        $response['status']=true;
        try {
            $this->db->beginTransaction(); 
            $sql = "UPDATE entrevista_detenido SET Id_Persona_Entrevista =NULL WHERE Id_Entrevista  = ".$Id_Entrevista;
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
    public function DesasociaForensia($Id_Forensia_Entrevista){
        $response['status']=true;
        try {
            $this->db->beginTransaction(); 
            $sql = "UPDATE forensias_detenido SET Id_Persona_Entrevista =NULL WHERE Id_Forensia_Entrevista  = ".$Id_Forensia_Entrevista;
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
    public function DesasociaUbicacion($Id_Ubicacion){
        $response['status']=true;
        try {
            $this->db->beginTransaction(); 
            $sql = "UPDATE ubicaciones_detenido SET Id_Persona_Entrevista =NULL WHERE Id_Ubicaciones_Entrevista   = ".$Id_Ubicacion;
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
        try {
            $this->db->beginTransaction(); 
            $sql = "UPDATE redes_sociales_detenido SET Id_Persona_Entrevista = NULL WHERE Id_Registro   = ".$Id_Registro;
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
    /*-------------------------------------- GET DE INFORMACION DE LA PERSONA ENTREVISTADA------------------------------------------ */
    public function getPrincipales($Id_Persona_Entrevista){
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
    public function getForensiasSelect($Id_Persona_Entrevista){
        $sql = "SELECT 	*
        FROM  forensias_detenido
        WHERE  forensias_detenido.Id_Persona_Entrevista = " . $Id_Persona_Entrevista." AND forensias_detenido.Id_Entrevista = -1";
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
    public function getRedesSociales($Id_Persona_Entrevista){
        $sql = "SELECT 	*
        FROM  redes_sociales_detenido
        WHERE  redes_sociales_detenido.Id_Persona_Entrevista = " . $Id_Persona_Entrevista;
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getForensiasPdf($Id_Persona_Entrevista, $Id_Entrevista){
        $sql = "SELECT 	*
        FROM  forensias_detenido
        WHERE  forensias_detenido.Id_Persona_Entrevista = " . $Id_Persona_Entrevista." AND Id_Entrevista = ".$Id_Entrevista;
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getUbicacionesPdf($Id_Persona_Entrevista, $Id_Dato, $Tipo){
        $sql = "SELECT 	*
        FROM  ubicaciones_detenido
        WHERE  ubicaciones_detenido.Id_Persona_Entrevista = " . $Id_Persona_Entrevista." AND Id_Dato = ".$Id_Dato." AND Tipo_Relacion = '".$Tipo."'";
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getRedesSocialesPdf($Id_Persona_Entrevista, $Id_Dato, $Tipo){
        $sql = "SELECT 	*
        FROM  redes_sociales_detenido
        WHERE  redes_sociales_detenido.Id_Persona_Entrevista = " . $Id_Persona_Entrevista." AND Id_Dato = ".$Id_Dato." AND Tipo_Relacion = '".$Tipo."'";
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getPersonaSeguimientoOneRegister($Id_Persona){
        $sql = "SELECT 	*
        FROM catalogo_persona_seguimiento
        WHERE catalogo_persona_seguimiento.Id_Persona = " . $Id_Persona;
        $this->db->query($sql);
        return $this->db->register();
    }
    public function getPersonaSeguimiento(){
        $sql = "SELECT 	*
        FROM catalogo_persona_seguimiento
        WHERE catalogo_persona_seguimiento.Id_Persona > 0 ";
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getGrupoDelictivoSeguimiento(){
        $sql = "SELECT 	*
        FROM catalogo_grupo_delictivo_seguimiento
        WHERE catalogo_grupo_delictivo_seguimiento.Id_Seguimiento > 0 ";
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getIndicativos(){
        $sql = "SELECT 	*
        FROM catalogo_indicativo_entrevistador
        WHERE catalogo_indicativo_entrevistador.Id_Dato  > 0 ORDER BY (catalogo_indicativo_entrevistador.Indicativo) ASC";
        $this->db->query($sql);
        return $this->db->registers();
    }
}
?>