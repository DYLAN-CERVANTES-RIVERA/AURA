<?php
class Punto{
    public $db; //Variable para instanciar el objeto PDO
    public $db2;
    public function __construct(){
        $this->db = new Base(); //Se instancia el objeto con los métodos de PDO
        $this->db2 = new Base2(); 
    }
    public function generateFromWhereSentence($cadena = "", $filtro = '1',$cadena2 = "")
    {
        $from_where_sentence = "";
        $cadena=$this->eliminar_acentos($cadena);
        switch ($filtro) {
            case '1':   //general
                $palabras = explode(",", strtolower($cadena));//Se obtiene la cadena con la cual se quiere buscar
                    $articulos = array('el', 'la', 'los', 'las', 'un', 'de', 'en', 'unos', 'una', 'unas', 'a', 'con', 'y', 'o', 'u');//arreglo con las palabras a ignorar en la busqueda, normalmete artículos
                    $palabras = array_diff($palabras, $articulos);
                    $from_where_sentence .= "
                                FROM gc_punto_filtro_1 WHERE  Id_Punto > 0 ";
                    foreach($palabras as $palabra){
                        $palabra = ltrim($palabra, " ");
                        $palabra = rtrim($palabra, " ");
                        $from_where_sentence.= "
                        AND  (      Id_Punto LIKE '%" . $palabra . "%' OR 
                                    Remision LIKE '%" . $palabra . "%' OR 
                                    Nombre_Detenido LIKE '%" . $palabra . "%' OR 
                                    Fuente_Info LIKE '%" . $palabra . "%' OR 
                                    Fecha_Punto LIKE '%" . $palabra . "%' OR 
                                    Identificador LIKE '%" . $palabra . "%' OR 
                                    Narrativa  LIKE '%" . $palabra . "%' OR 
                                    Estatus_Punto LIKE '%" . $palabra . "%' OR 
                                    Zona LIKE '%" . $palabra . "%' OR 
                                    Vector LIKE '%" . $palabra . "%' OR 
                                    Colonia LIKE '%" . $palabra . "%' OR 
                                    Calle LIKE '%" . $palabra . "%' OR 
                                    Calle2 LIKE '%" . $palabra . "%' OR 
                                    NoExt LIKE '%" . $palabra . "%' OR 
                                    CP LIKE '%" . $palabra . "%' OR 
                                    CoordX LIKE '%" . $palabra . "%' OR 
                                    CoordY LIKE '%" . $palabra . "%' OR 
                                    Distribuidor LIKE '%" . $palabra . "%' OR
                                    Grupo_OP LIKE '%" . $palabra . "%' OR
                                    Descripcion_Adicional LIKE '%" . $palabra . "%' OR
                                    Atendido_Por LIKE '%" . $palabra . "%' OR
                                    Fecha_Captura LIKE '%" . $palabra . "%'OR
                                    Capturo LIKE '%" . $palabra . "%'OR
                                    Descripcion_Datoconcat LIKE '%" . $palabra . "%') ";                                
                    }
                break;

        }

        //where complemento fechas (si existe)
        if($filtro==1){
            $from_where_sentence .= $this->getFechaCondition();
            //order by
            if($cadena2=='EXCEL'){
                $from_where_sentence .= " ORDER BY Id_Punto ASC";
            }else{
                $from_where_sentence .= " ORDER BY Id_Punto DESC";
            }
            

        }else{
            $from_where_sentence .= $this->getFechaCondition();
            $from_where_sentence .= " ORDER BY Id_Punto DESC";

        }

     
        return $from_where_sentence;
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
        $sql = "
                SELECT Id_Punto,Fuente_Info,Identificador,Fecha_Punto,Fecha_Captura,Colonia,Calle,Zona,Vector,Distribuidor,Remision,Nombre_Detenido,Narrativa"
            . $from_where_sentence . "  
                LIMIT $offset,$no_of_records_per_page
                ";

        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getAllInfoPuntoByCadena($from_where_sentence = ""){ 
    	$sqlAux = "SELECT * "
    				.$from_where_sentence."
                    ";  //query a la DB
        $this->db->query($sqlAux);          //se prepara el query mediante PDO
        return $this->db->registers();      //retorna todos los registros devueltos por la consulta
    }
    function eliminar_acentos($cadena){//esta funcion es para la cadena de busqueda ya que el like no permite mezcla diferentes collations solo aplica en bases locales 
		
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
    public function getFechaCondition(){
        $cad_fechas = "";
        if (isset($_SESSION['userdataSIC']->rango_inicio_pun) && isset($_SESSION['userdataSIC']->rango_fin_pun)) { //si no ingresa una fecha se seleciona el día de hoy como máximo
            $rango_inicio = $_SESSION['userdataSIC']->rango_inicio_pun;
            $rango_fin = $_SESSION['userdataSIC']->rango_fin_pun;
            $cad_fechas = " AND 
                            ((Fecha_Punto >= '" . $rango_inicio . " 00:00:00'  AND 
                            Fecha_Punto <= '" . $rango_fin . " 23:59:59' )OR
                            (Fecha_Punto >= CAST('" . $rango_inicio . " 00:00:00' AS date) AND
                            Fecha_Punto <= CAST('" . $rango_fin . " 23:59:59' AS date)))
                            ";
        }

        return $cad_fechas;
    }
    public function getRemisiones(){
        $sql = "SELECT No_Remision,Nombre_completo,Detenido_por FROM casos_consulta_detenido_entrevistas ORDER BY (ID)";
        $this->db2->query($sql);
        return $this->db2->registers();
    }
    public function getInfoRemision($No_Remision){//sacamos la informacion de la remision
        $sql = "SELECT 	*
        FROM casos_consulta_detenido_entrevistas
        WHERE casos_consulta_detenido_entrevistas.No_Remision = " . $No_Remision;
        $this->db2->query($sql);
        return $this->db2->register();
    }
    /*----------- FUNCION INSERT NUEVO PUNTO -------------------*/ 
    public function insertNuevoPunto($post){
        //Valores iniciales de retorno
        $data['status'] = true;
        $data['Id_Punto'] = -1;
        $data['sqlEjecutados'] = "";
        $sqlEjecutados = "";
        try{
            $this->db->beginTransaction();  //Se inicializa la transaccion para tener un punto de retorno en caso de fallo
            $sql = "INSERT
                    INTO puntos_alto_impacto(
                        Remision,
                        Nombre_Detenido,
                        Fuente_Info,
                        Fecha_Punto,
                        Identificador,
                        Narrativa,
                        Estatus_Punto,
                        Zona,
                        Vector,
                        Colonia,
                        Calle,
                        Calle2,
                        NoExt,
                        CP,
                        CoordX,
                        CoordY,
                        Distribuidor,
                        Enlace_Google,
                        Grupo_OP,
                        Descripcion_Adicional,
                        Atendido_Por,
                        Fecha_Captura,
                        Capturo
                    )
                    VALUES(
                        '".$post['id_remision']."',
                        '".$this->remplazoCadena($post['nombre'])."',
                        '".$post['Fuente_info']."',
                        '".$post['fecha_obtencion']."',
                        '".$post['Identificador']."',
                        '".$this->remplazoCadena($post['Narrativa'])."',
                        '".$post['Estatus_Punto']."',
                        '".$post['zona']."',
                        '".$post['vector']."',
                        '".$this->remplazoCadena($post['Colonia'])."',
                        '".$this->remplazoCadena($post['Calle'])."',
                        '".$this->remplazoCadena($post['Calle2'])."',
                        '".$post['no_Ext']."',
                        '".$post['CP']."',
                        '".$post['cordX']."',
                        '".$post['cordY']."',
                        '".$this->remplazoCadena($post['Distribuidor'])."',
                        '".$post['Enlace_Google']."',
                        '".$this->remplazoCadena($post['Grupo_OP'])."',
                        '".$this->remplazoCadena($post['Info_Adicional'])."',
                        '".$this->remplazoCadena($post['Atendido_Por'])."',
                        '".$post['fechahora_captura_principales']."',
                        '".$post['captura_principales']."'
                    )";
            $this->db->query($sql);
            $this->db->execute();
            $this->db->query("SELECT LAST_INSERT_ID() as Id_Punto"); //Se recupera el Id_Punto que se a creado recientemente
            $Id_Punto = $this->db->register()->Id_Punto;
            $sqlEjecutados=$sql;
            $data['Id_Punto'] = $Id_Punto; 
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

    public function UpdatePuntoFetch($post){
        $response['status'] = true;
        $response['sqlEjecutados'] = "";
        $response['aquino'] ="";
        $sqlEjecutados="";
        try {
            $this->db->beginTransaction();  //Se inicializa la transaccion para tener un punto de retorno en caso de fallo
            
            $sql=" UPDATE puntos_alto_impacto
            SET Remision = '" .$post['id_remision']."',
                Nombre_Detenido = '" .$this->remplazoCadena($post['nombre'])."',
                Fuente_Info = '" .$post['Fuente_info']."',
                Fecha_Punto = '" .$post['fecha_obtencion']."',
                Identificador = '" .$post['Identificador']."',
                Narrativa = '" .$this->remplazoCadena($post['Narrativa'])."',
                Estatus_Punto = '" .$post['Estatus_Punto']."',
                Zona = '" .$post['zona']."',
                Vector = '" .$post['vector']."',
                Colonia = '" .$this->remplazoCadena($post['Colonia'])."',
                Calle = '" .$this->remplazoCadena($post['Calle'])."',
                Calle2 = '" .$this->remplazoCadena($post['Calle2'])."',
                NoExt = '" .$post['no_Ext']."',
                CP = '" .$post['CP']."',
                CoordX = '" .$post['cordX']."',
                CoordY = '" .$post['cordY']."',
                Distribuidor = '" .$this->remplazoCadena($post['Distribuidor'])."',
                Enlace_Google = '" .$post['Enlace_Google']."',
                Grupo_OP = '" .$this->remplazoCadena($post['Grupo_OP'])."',
                Descripcion_Adicional = '" .$this->remplazoCadena($post['Info_Adicional'])."',
                Atendido_Por = '" .$this->remplazoCadena($post['Atendido_Por'])."',
                Img_64 = '" .$post['Img_64']."',
                Path_Img = '" .$post['Path_Img']."',
                Img_64_Google = '" .$post['Img_64_Google']."',
                Path_Img_Google = '" .$post['Path_Img_Google']."'
                WHERE Id_Punto   = ".$post['Id_Punto'] ;
            $this->db->query($sql);
            $this->db->execute();
            
            $sqlEjecutados .= " SE ACTUALIZO INFORMACION DE PUNTO ";
           

                $UbicacionArray = json_decode($post['DatosUbicacion_table']);
                
                foreach ($UbicacionArray as $Ubicacion) {
                    if($Ubicacion->row->nameImage!='null'){
                        $Nombre_Foto = $Ubicacion->row->nameImage.'.png';
                    }else{
                        $Nombre_Foto ='SD';
                    }
                    if($Ubicacion->row->imagebase64!='null'){
                        $imagebase64 = $Ubicacion->row->imagebase64;
                    }else{
                        $imagebase64 ='SD';
                    }
                    $Descripcion_Dato = $this->remplazoCadena($Ubicacion->row->Descripcion_Dato);
                    if($Ubicacion->row->Id_Dato_Punto == 'SD'){                        
                            $sql="INSERT
                            INTO datos_puntos(
                                Id_Punto,
                                Tipo_Dato,
                                Descripcion_Dato,
                                Img_64_Dato,
                                Path_Imagen_Dato,
                                Fecha_Captura,
                                Capturo
                            )VALUES(
                                '".$Ubicacion->row->Id_Punto."',
                                '".$Ubicacion->row->Tipo_Dato."',
                                '".$Descripcion_Dato."',
                                '".$imagebase64."',
                                '".$Nombre_Foto."',
                                '".$Ubicacion->row->Fecha_Captura. "',
                                '".$Ubicacion->row->Capturo."'
                            )";
                        $this->db->query($sql);
                        $this->db->execute();

                        $this->db->query("SELECT LAST_INSERT_ID() as Id_Dato_Punto"); 
                        $Id_Dato_Punto = $this->db->register()->Id_Dato_Punto;
                        $sqlEjecutados.=" SE INSERTO UN DATO DE PUNTO ".$Id_Dato_Punto;
                        //logica de insert
                        
                    }else{
                          $sql = "UPDATE datos_puntos SET 
                                    Tipo_Dato = '" .$Ubicacion->row->Tipo_Dato. "',
                                    Descripcion_Dato = '" . $Descripcion_Dato . "',
                                    Path_Imagen_Dato = '" . $Nombre_Foto . "',
                                    Img_64_Dato ='". $imagebase64 . "'
                                    WHERE Id_Dato_Punto   = " . $Ubicacion->row->Id_Dato_Punto;

                        $this->db->query($sql);
                        $this->db->execute();
                        if ($this->db->rowCount() > 0) {// Si se realizó una actualización
                            $sqlEjecutados.=" SE ACTUALIZO UN DATO DE PUNTO ".$Ubicacion->row->Id_Dato_Punto;
                        }
                        
                        //logica de update
                    }

                }

            
            $this->db->commit(); //Si no hubo fallos en ninguna insercion asegura los cambios
        } catch (Exception $e) {
            $response['status'] = false;
            $response['error_message'] = 'Hubo un error en la Base de datos.';
            $response['error_sql'] = $sql;
            //$this->db->rollBack();
            return $response;
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
        $text = mb_strtoupper($text);
        $text = trim($text);
        return $text;
    }
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
    public function logs($user, $ip, $descripcion){// para ecribir los movimientos de error en el gestor 
        try {
            $this->db->beginTransaction();
            $sql = " INSERT
                    INTO logs_error(
                        Id_Usuario,
                        Ip_Acceso,
                        Descripcion_error
                    )
                    VALUES(
                        trim($user),
                        '" . trim($ip) . "',
                        '" . trim($descripcion) . "'
                    )
            ";
            $this->db->query($sql);
            $this->db->execute();
            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
        }
    }
    public function getPuntosByCadena($cadena, $filtro ){
        //CONSULTA COINCIDENCIAS DE CADENA PARA EVENTOS 
        $cadena=$this->eliminar_acentos($cadena);
        //sentencia from_where para hacer la busqueda por la cadena ingresada
        $from_where_sentence = $this->generateFromWhereSentence($cadena, $filtro);
        $numPage = 1;
        $no_of_records_per_page = NUM_MAX_REG_PAGE; //total de registros por pagination
        $offset = ($numPage - 1) * $no_of_records_per_page; // desplazamiento conforme a la pagina
        $results = $this->getTotalPages($no_of_records_per_page, $from_where_sentence);  //total de páginas conforme a la busqueda
        //info de retorno para la creacion de los links conforme a la cadena ingresada
        $data['rows_Pun'] = $this->getDataCurrentPage($offset, $no_of_records_per_page, $from_where_sentence);   //se obtiene la información de la página actual
        $data['numPage'] = $numPage; //numero pag actual para la pagination footer
        $data['total_pages'] = $results['total_pages']; //total pages para la pagination
        $data['total_rows'] = $results['total_rows'];   //total de registro hallados
        return $data;
    }
    public function getInfoPunto($Id_Punto){
        $sql = "SELECT 	*
        FROM puntos_alto_impacto
        WHERE puntos_alto_impacto.Id_Punto = " . $Id_Punto;
        $this->db->query($sql);
        return $this->db->register();
    }
    public function getDatosPunto($Id_Punto){
        $sql = "SELECT 	*
        FROM datos_puntos
        WHERE datos_puntos.Id_Punto = " . $Id_Punto;
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function DesasociaDato($Id_Dato_Punto){
        $response['status']=true;
        try {
            $this->db->beginTransaction(); 
            $sql = "UPDATE datos_puntos SET Id_Punto = NULL WHERE Id_Dato_Punto  = ".$Id_Dato_Punto;
            $this->db->query($sql);
            $this->db->execute();
            $this->db->commit(); //Si no hubo fallos en ninguna insercion asegura los cambios
            $sqlEjecutados = $sql;

        } catch (Exception $e) {
            $response['status'] = false;
            $response['error_message'] = 'Hubo un error en la Base de datos.';
            $response['error_sql'] = $sql;
            $this->db->rollBack();
        }
        $response['sqlEjecutados'] = $sqlEjecutados;
        return  $response;
    }
}
?>