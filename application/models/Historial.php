<?php

class Historial
{
    public $db;
    public function __construct()
    {
        $this->db = new Base();    
    }
    /*NOMENCLATURA DE MOVIMIENTOS EN EL HISTORIAL 
    1.-INICIO DE SESION 
    2.-VER EVENTO 
    3.-INSERCION DE EVENTO 
    4.-ACTUALIZACION DE ENTREVISTA 
    5.-ACTUALIZACION DE FOTOS 
    6.-ACTUALIZACION DE EVENTO 
    7.-CONSULTA PANEL BUSQUEDA 
    8.-EXPORTACION DE INFORMACION GESTOR DE CASOS
    9.-TERMINAR SEGUIMIENTO
    10.-CAMBIAR CONTRASEÑA
    11.-CAMBIAR FOTO DE USUARIO
    12.-CREAR USUARIO
    13.-ACTUALIZAR INFORMACION DE USUARIO
    14.-VER  INFORMACION DE USUARIO
    15.-BUSQUEDA EN EL MODULO DE USUARIO
    16.-EXPORTAR DE INFORMACION USUARIOS
    17.-ELIMINAR REGISTRO CATALOGO 
    18.-CREAR REGISTRO CATALOGO
    19.-VER REGISTRO CATALOGO
    20.-ACTUALIZAR REGISTRO CATALOGO
    21.-CONSULTAR REGISTRO CATALOGO 
    22.-EXPORTACION DE ARCHIVOS DE CATALOGOS
    */

    public function generateWhereSentence($cadena="",$filtro='1')
    {
        $where_sentence = "";
        switch($filtro){
            case '1': //TODOS LOS MOVIMIENTOS
                $where_sentence.= " FROM historial
                    LEFT JOIN usuario
                    ON historial.Id_Usuario = usuario.Id_Usuario
                    WHERE id_dato > 0 
                ";
                if($cadena!=''){
                    $palabras = explode(",", strtolower($cadena));//Se obtiene la cadena con la cual se quiere buscar
                    $articulos = array('el', 'la', 'los', 'las', 'un', 'de', 'en', 'unos', 'una', 'unas', 'a', 'con', 'y', 'o', 'u');//arreglo con las palabras a ignorar en la busqueda, normalmete artículos
                    $palabras = array_diff($palabras, $articulos);
                    foreach($palabras as $palabra){
                        $palabra=ltrim($palabra, " ");
                        $palabra=rtrim($palabra, " ");
                        $where_sentence .= "
                                        AND  (     
                                                    Fecha_Hora LIKE '%" . $palabra . "%' OR 
                                                    Ip_Acceso LIKE '%" . $palabra . "%' OR 
                                                    Movimiento LIKE '%" . $palabra . "%' OR 
                                                    Descripcion LIKE '%" . $palabra . "%') 
                                            ";
                                        
                    }
                }
            break;
            case '2': //INICIO DE SESION 
                $where_sentence.= " FROM historial
                    INNER JOIN usuario
                    ON historial.Id_Usuario = usuario.Id_Usuario
                    WHERE  historial.Movimiento = 1
                ";
                if($cadena!=''){
                    $palabras = explode(",", strtolower($cadena));//Se obtiene la cadena con la cual se quiere buscar
                    $articulos = array('el', 'la', 'los', 'las', 'un', 'de', 'en', 'unos', 'una', 'unas', 'a', 'con', 'y', 'o', 'u');//arreglo con las palabras a ignorar en la busqueda, normalmete artículos
                    $palabras = array_diff($palabras, $articulos);
                    foreach($palabras as $palabra){
                        $palabra=ltrim($palabra, " ");
                        $palabra=rtrim($palabra, " ");
                        $where_sentence .= "
                                        AND  (     
                                                    Fecha_Hora LIKE '%" . $palabra . "%' OR 
                                                    Ip_Acceso LIKE '%" . $palabra . "%' OR 
                                                    Movimiento LIKE '%" . $palabra . "%' OR 
                                                    Descripcion LIKE '%" . $palabra . "%') 
                                            ";
                                        
                    }
                }
            break;
            case '3': //VER EVENTO
                $where_sentence.= " FROM historial
                    INNER JOIN usuario
                    ON historial.Id_Usuario = usuario.Id_Usuario
                    WHERE historial.Movimiento = 2
                ";
                if($cadena!=''){
                    $palabras = explode(",", strtolower($cadena));//Se obtiene la cadena con la cual se quiere buscar
                    $articulos = array('el', 'la', 'los', 'las', 'un', 'de', 'en', 'unos', 'una', 'unas', 'a', 'con', 'y', 'o', 'u');//arreglo con las palabras a ignorar en la busqueda, normalmete artículos
                    $palabras = array_diff($palabras, $articulos);
                    foreach($palabras as $palabra){
                        $palabra=ltrim($palabra, " ");
                        $palabra=rtrim($palabra, " ");
                        $where_sentence .= "
                                        AND  (     
                                                    Fecha_Hora LIKE '%" . $palabra . "%' OR 
                                                    Ip_Acceso LIKE '%" . $palabra . "%' OR 
                                                    Movimiento LIKE '%" . $palabra . "%' OR 
                                                    Descripcion LIKE '%" . $palabra . "%') 
                                            ";
                                        
                    }
                }
            break;
            case '4': //INSERCION DE EVENTO 
                $where_sentence.= " FROM historial
                    INNER JOIN usuario
                    ON historial.Id_Usuario = usuario.Id_Usuario
                    WHERE historial.Movimiento = 3
                ";
                if($cadena!=''){
                    $palabras = explode(",", strtolower($cadena));//Se obtiene la cadena con la cual se quiere buscar
                    $articulos = array('el', 'la', 'los', 'las', 'un', 'de', 'en', 'unos', 'una', 'unas', 'a', 'con', 'y', 'o', 'u');//arreglo con las palabras a ignorar en la busqueda, normalmete artículos
                    $palabras = array_diff($palabras, $articulos);
                    foreach($palabras as $palabra){
                        $palabra=ltrim($palabra, " ");
                        $palabra=rtrim($palabra, " ");
                        $where_sentence .= "
                                        AND  (     
                                                    Fecha_Hora LIKE '%" . $palabra . "%' OR 
                                                    Ip_Acceso LIKE '%" . $palabra . "%' OR 
                                                    Movimiento LIKE '%" . $palabra . "%' OR 
                                                    Descripcion LIKE '%" . $palabra . "%') 
                                            ";
                                        
                    }
                }
            break;
            case '5': //ACTUALIZACION DE ENTREVISTA 
                $where_sentence.= " FROM historial
                    INNER JOIN usuario
                    ON historial.Id_Usuario = usuario.Id_Usuario
                    WHERE  historial.Movimiento = 4
                ";
                if($cadena!=''){
                    $palabras = explode(",", strtolower($cadena));//Se obtiene la cadena con la cual se quiere buscar
                    $articulos = array('el', 'la', 'los', 'las', 'un', 'de', 'en', 'unos', 'una', 'unas', 'a', 'con', 'y', 'o', 'u');//arreglo con las palabras a ignorar en la busqueda, normalmete artículos
                    $palabras = array_diff($palabras, $articulos);
                    foreach($palabras as $palabra){
                        $palabra=ltrim($palabra, " ");
                        $palabra=rtrim($palabra, " ");
                        $where_sentence .= "
                                        AND  (     
                                                    Fecha_Hora LIKE '%" . $palabra . "%' OR 
                                                    Ip_Acceso LIKE '%" . $palabra . "%' OR 
                                                    Movimiento LIKE '%" . $palabra . "%' OR 
                                                    Descripcion LIKE '%" . $palabra . "%') 
                                            ";
                                        
                    }
                }
            break;
            case '6': //ACTUALIZACION DE FOTOS 
                $where_sentence.= " FROM historial
                    INNER JOIN usuario
                    ON historial.Id_Usuario = usuario.Id_Usuario
                    WHERE historial.Movimiento = 5
                ";
                if($cadena!=''){
                    $palabras = explode(",", strtolower($cadena));//Se obtiene la cadena con la cual se quiere buscar
                    $articulos = array('el', 'la', 'los', 'las', 'un', 'de', 'en', 'unos', 'una', 'unas', 'a', 'con', 'y', 'o', 'u');//arreglo con las palabras a ignorar en la busqueda, normalmete artículos
                    $palabras = array_diff($palabras, $articulos);
                    foreach($palabras as $palabra){
                        $palabra=ltrim($palabra, " ");
                        $palabra=rtrim($palabra, " ");
                        $where_sentence .= "
                                        AND  (     
                                                    Fecha_Hora LIKE '%" . $palabra . "%' OR 
                                                    Ip_Acceso LIKE '%" . $palabra . "%' OR 
                                                    Movimiento LIKE '%" . $palabra . "%' OR 
                                                    Descripcion LIKE '%" . $palabra . "%') 
                                            ";
                                        
                    }
                }
            break;
            case '7': //ACTUALIZACION DE EVENTO 
                $where_sentence.= " FROM historial
                    INNER JOIN usuario
                    ON historial.Id_Usuario = usuario.Id_Usuario
                    WHERE historial.Movimiento = 6
                ";
                if($cadena!=''){
                    $palabras = explode(",", strtolower($cadena));//Se obtiene la cadena con la cual se quiere buscar
                    $articulos = array('el', 'la', 'los', 'las', 'un', 'de', 'en', 'unos', 'una', 'unas', 'a', 'con', 'y', 'o', 'u');//arreglo con las palabras a ignorar en la busqueda, normalmete artículos
                    $palabras = array_diff($palabras, $articulos);
                    foreach($palabras as $palabra){
                        $palabra=ltrim($palabra, " ");
                        $palabra=rtrim($palabra, " ");
                        $where_sentence .= "
                                        AND  (     
                                                    Fecha_Hora LIKE '%" . $palabra . "%' OR 
                                                    Ip_Acceso LIKE '%" . $palabra . "%' OR 
                                                    Movimiento LIKE '%" . $palabra . "%' OR 
                                                    Descripcion LIKE '%" . $palabra . "%') 
                                            ";
                                        
                    }
                }
            break;
            case '8': //CONSULTA PANEL BUSQUEDA
                $where_sentence.= " FROM historial
                    INNER JOIN usuario
                    ON historial.Id_Usuario = usuario.Id_Usuario
                    WHERE historial.Movimiento = 7
                ";
                if($cadena!=''){
                    $palabras = explode(",", strtolower($cadena));//Se obtiene la cadena con la cual se quiere buscar
                    $articulos = array('el', 'la', 'los', 'las', 'un', 'de', 'en', 'unos', 'una', 'unas', 'a', 'con', 'y', 'o', 'u');//arreglo con las palabras a ignorar en la busqueda, normalmete artículos
                    $palabras = array_diff($palabras, $articulos);
                    foreach($palabras as $palabra){
                        $palabra=ltrim($palabra, " ");
                        $palabra=rtrim($palabra, " ");
                        $where_sentence .= "
                                        AND  (     
                                                    Fecha_Hora LIKE '%" . $palabra . "%' OR 
                                                    Ip_Acceso LIKE '%" . $palabra . "%' OR 
                                                    Movimiento LIKE '%" . $palabra . "%' OR 
                                                    Descripcion LIKE '%" . $palabra . "%') 
                                            ";
                                        
                    }
                }
            break;
            case '9': //EXPORTACION DE INFO
                $where_sentence.= " FROM historial
                    INNER JOIN usuario
                    ON historial.Id_Usuario = usuario.Id_Usuario
                    WHERE historial.Movimiento = 8
                ";
                if($cadena!=''){
                    $palabras = explode(",", strtolower($cadena));//Se obtiene la cadena con la cual se quiere buscar
                    $articulos = array('el', 'la', 'los', 'las', 'un', 'de', 'en', 'unos', 'una', 'unas', 'a', 'con', 'y', 'o', 'u');//arreglo con las palabras a ignorar en la busqueda, normalmete artículos
                    $palabras = array_diff($palabras, $articulos);
                    foreach($palabras as $palabra){
                        $palabra=ltrim($palabra, " ");
                        $palabra=rtrim($palabra, " ");
                        $where_sentence .= "
                                        AND  (     
                                                    Fecha_Hora LIKE '%" . $palabra . "%' OR 
                                                    Ip_Acceso LIKE '%" . $palabra . "%' OR 
                                                    Movimiento LIKE '%" . $palabra . "%' OR 
                                                    Descripcion LIKE '%" . $palabra . "%') 
                                            ";
                                        
                    }
                }
            break;
           
        }

        $where_sentence.= $this->getFechaCondition();
        $where_sentence.=" ORDER by (Fecha_Hora) DESC ";
        return $where_sentence;
    }

    public function getFechaCondition()//función auxiliar para filtrar por un rango de fechas específicado por el usuario
    {
        $cad_fechas="";
        if(isset($_SESSION['userdataSIC']->rango_inicio_his) && isset($_SESSION['userdataSIC']->rango_fin_his)){
            $rango_inicio = $_SESSION['userdataSIC']->rango_inicio_his;
            $rango_fin = $_SESSION['userdataSIC']->rango_fin_his;

            $cad_fechas = " AND 
                            ((Fecha_Hora >= '" . $rango_inicio . " 00:00:00'  AND 
                            Fecha_Hora <= '" . $rango_fin . " 23:59:59' )OR
                            (Fecha_Hora >= CAST('" . $rango_inicio . " 00:00:00' AS date) AND
                            Fecha_Hora <= CAST('" . $rango_fin . " 23:59:59' AS date)))
                            ";
        }

        return $cad_fechas;
    }

    public function getTotalPages($no_of_records_per_page,$where_sentence="") //obtener el total de páginas y de registros de la consulta
    {
        $where_sentence = strstr($where_sentence, 'FROM');

        $sql_total_pages = "SELECT COUNT(*) as Num_Pages ".$where_sentence;
        $this->db->query($sql_total_pages);
        $total_rows = $this->db->register()->Num_Pages;
        $total_pages = ceil($total_rows/$no_of_records_per_page);
        //print_r($total_rows);
        $data['total_rows'] = $total_rows;
        $data['total_pages'] = $total_pages;

        return $data;
    }

    public function getDataCurrentPage($offset, $no_of_records_per_page,$where_sentence ="")//obtener los registros de la pagina actual
    {
        $sql = "
            SELECT * ".$where_sentence."
            LIMIT $offset , $no_of_records_per_page
        ";

        $this->db->query($sql);
        return $this->db->registers();
    }

    public function getHistorialByCadena($cadena,$filtro=1)
    {
        if(!is_numeric($filtro) || !($filtro>=MIN_FILTRO_HIS) || !($filtro<=MAX_FILTRO_HIS)){//verifica los datos globales para saber que el filtro existe en el modulo
            $filtro = 1;
        }

        $from_where_sentence = $this->generateWhereSentence($cadena,$filtro);//genera los datos conforme a la cadena ingresada dento del panel en el modulo del historial
        $numPage = 1;
        $no_of_records_per_page = NUM_MAX_REG_PAGE;
        $offset = ($numPage-1)*$no_of_records_per_page;

        $results = $this->getTotalPages($no_of_records_per_page,$from_where_sentence);

        $data['rows_Hisroriales'] = $this->getDataCurrentPage($offset,$no_of_records_per_page,$from_where_sentence);
        $data['numPage'] = $numPage;
        $data['total_pages'] = $results['total_pages'];
        $data['total_rows'] = $results['total_rows'];

        return $data;
    }

    public function getAllInfoHistorialByCadena($from_where_sentence="")//saca todo el historial de la base de datos
    {
        $sqlAux = "SELECT *"
                    .$from_where_sentence."
                    ";
        
        $this->db->query($sqlAux);
        return $this->db->registers();
    }

    public function insertHistorial($movimiento = null, $descripcion = null,$usuario = null){// insertar en el historial el mmovimiento hecho desde otro modulo catalogo y administracion de usuarios
        if( $movimiento == null || $descripcion == null){
            return false;
        }

        $ip = $this->obtenerIp();
        $sql = "INSERT INTO historial(Id_Usuario,Ip_Acceso,Movimiento,Descripcion) VALUES(".$usuario.",'".$ip."','".$movimiento."','".$descripcion."')";
        $this->db->query($sql);
        
        return $this->db->execute();
    }

    private function obtenerIp()//obtiene la ip para insertar el movimiento en el historial
    {
        $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $hosts = gethostbynamel($hostname);
        if (is_array($hosts)) {
            foreach ($hosts as $ip) {
                return $ip;
            }
        }else{
            return $ip = '0.0.0.0';
        }
    }

    
}
?>