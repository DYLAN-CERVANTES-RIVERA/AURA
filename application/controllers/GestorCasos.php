<?php

class GestorCasos extends Controller
{
    /*
    CRUD CREATE[3] , READ[2], UPDATE[1], DELETE[0]
    controlador del modulo gestor de casos
    NOMENCLATURA DE MOVIMIENTOS EN EL HISTORIAL 
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
    public $Catalogo;
    public $GestorCaso;
    public $numColumnsGC; //número de columnas por cada filtro
    public $FV;

    public function __construct(){
        $this->Catalogo = $this->model('Catalogo');
        $this->GestorCaso = $this->model('GestorCaso');
        $this->numColumnsGC = [10, 9, 8, 9, 10];  //se inicializa el número de columns por cada filtro
        $this->FV = new FormValidator();
    }

    public function index(){
        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }
        $data = [
            'titulo'    => 'AURA | Gestor de Eventos',
            'extra_css' => '<link rel="stylesheet" href="' . base_url . 'public/css/system/gestorCasos/index.css">',
            'extra_js'  => '<script src="' . base_url . 'public/js/system/gestorCasos/index2.js"></script>'.
                            '<script src="https://cdn.jsdelivr.net/npm/chart.js@3.3.2/dist/chart.min.js"></script>'
        ];
         //PROCESO DE FILTRADO DE EVENTOS
        if (isset($_GET['filtro']) && is_numeric($_GET['filtro']) && $_GET['filtro'] >= MIN_FILTRO_GC && $_GET['filtro'] <= MAX_FILTRO_GC) { //numero de filtro
            $filtro = $_GET['filtro'];
        } else {
            $filtro = 1;
        }
        //PROCESAMIENTO DE LAS COLUMNAS 
        $this->setColumnsSession($filtro);
        $data['columns_GC'] = $_SESSION['userdataSIC']->columns_GC;

        //PROCESAMIENTO DE RANGO DE FOLIOS
        if (isset($_POST['rango_inicio']) && isset($_POST['rango_fin'])) {
            $_SESSION['userdataSIC']->rango_inicio_gc = $_POST['rango_inicio'];
            $_SESSION['userdataSIC']->rango_fin_gc = $_POST['rango_fin'];
        }

        //PROCESO DE PAGINATION
        if (isset($_GET['numPage'])) { //numero de pagination
            $numPage = $_GET['numPage'];
            if (!(is_numeric($numPage))) //seguridad si se ingresa parámetro inválido
                $numPage = 1;
        } else {
            $numPage = 1;
        }
        //cadena auxiliar por si se trata de una paginacion conforme a una busqueda dada anteriormente
        $cadena = "";
        if (isset($_GET['cadena'])) { //numero de pagination
            $cadena = $_GET['cadena'];
            $data['cadena'] = $cadena;
        }
        $where_sentence = $this->GestorCaso->generateFromWhereSentence($cadena, $filtro,"");
        $extra_cad = ($cadena != "") ? ("&cadena=" . $cadena) : ""; //para links conforme a búsqueda
        $no_of_records_per_page = NUM_MAX_REG_PAGE; //total de registros por pagination
        $offset = ($numPage - 1) * $no_of_records_per_page; // desplazamiento conforme a la pagina
        $results_rows_pages = $this->GestorCaso->getTotalPages($no_of_records_per_page, $where_sentence);   //total de páginas de acuerdo a la info de la DB
        $total_pages = $results_rows_pages['total_pages'];
        if ($numPage > $total_pages) {
            $numPage = 1;
            $offset = ($numPage - 1) * $no_of_records_per_page;
        } //seguridad si ocurre un error por url     

        $rows_GestorCasos = $this->GestorCaso->getDataCurrentPage($offset, $no_of_records_per_page, $where_sentence);    //se obtiene la información de la página actual
        //$rows_GestorCasos=array_reverse($rows_GestorCasos);
        //guardamos la tabulacion de la información para la vista
        $data['infoTable'] = $this->generarInfoTable($rows_GestorCasos, $filtro);
        //guardamos los links en data para la vista
        $data['links'] = $this->generarLinks($numPage, $total_pages, $extra_cad, $filtro);
        //número total de registros encontrados
        $data['total_rows'] = $results_rows_pages['total_rows'];
        //filtro actual para Fetch javascript
        $data['filtroActual'] = $filtro;
        $data['dropdownColumns'] = $this->generateDropdownColumns($filtro);

        switch ($filtro) {
            case '1':
                $data['filtroNombre'] = "Todos los Eventos";
                break;
            case '2':
                $data['filtroNombre'] = "Eventos Habilitados";
                break;
            case '3':
                $data['filtroNombre'] = "Eventos Deshabilitados";
                break;
            case '4':
                $data['filtroNombre'] = "Busqueda a Quien se Asigno Evento";
                break;
            case '5':
                $data['filtroNombre'] = "Busqueda Solo por Folio infra Evento";
                break;
        }

        $this->view('templates/header', $data);
        $this->view('system/gestorCasos/gestorCasoView', $data);
        $this->view('templates/footer', $data);
    }
    /* ----------------------------------------FUNCION PARA GENERAR NUEVOS EVENTOS------------------------------------- */
    public function nuevoEvento(){
        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }
        //comprobar los permisos para dejar pasar al módulo
        if($_SESSION['userdataSIC']->Modo_Admin != 1 && $_SESSION['userdataSIC']->Seguimientos[3] != 1 && $_SESSION['userdataSIC']->Evento_D[3]!= 1){
            header("Location: " . base_url . "Estadisticas");
            exit();
        }
        //Cargar archivos y datos de los catalogos para le intefaz del nuevo evento
        $datos_prim = [
            'zonas' => $this->getZona(),
            'violencia' => $this->getViolencia(),
            'sinviolencia' => $this->getSViolencia(),
            'armas' => $this->getArmas(),
            'claves' => $this->getClave(),
            'fuentes'=> $this->getFuente(),
            'tipo_vehiculos'=> $this->getTipoVehiculos(),
            'edades'=> $this->getRangoEdades()
        ];
        $data = [
            'titulo'     => 'AURA | Nuevo Evento',
            'extra_css'  => '<link rel="stylesheet" href="' . base_url . 'public/css/system/gestorCasos/fullview.css">',
            'extra_js'   => '<script src="'.base_url.'public/js/system/gestorCasos/index.js"></script>'.
                            '<script src="https://api.mapbox.com/mapbox-gl-js/v2.8.1/mapbox-gl.js"></script>'.
                            '<link href="https://api.mapbox.com/mapbox-gl-js/v2.8.1/mapbox-gl.css" rel="stylesheet" />'.
                            '<script src="'.base_url.'public/js/system/gestorCasos/principales_mapbox.js"></script>'.
                            '<script src="'.base_url.'public/js/system/gestorCasos/validacion.js"></script>'.
                            '<script src="'.base_url.'public/js/system/gestorCasos/valida_nuevo.js"></script>',
            'datos_prim' => $datos_prim
        ];
        
        $this->view('templates/header', $data);
        $this->view('system/gestorCasos/nuevoEventoView', $data);
        $this->view('templates/footer', $data);
    }
    /* ----------------------------------------INSERCION DE EVENTOS ------------------------------------- */
    public function insertEventoFetch(){
          //comprobar los permisos para dejar pasar al módulo
        if($_SESSION['userdataSIC']->Modo_Admin != 1 && $_SESSION['userdataSIC']->Seguimientos[3] != 1 && $_SESSION['userdataSIC']->Evento_D[3] != 1){
            $data_p['status'] = false;
            $data_p['error_message'] = 'Render Index';
            echo json_encode($data_p);
        }
        
        $success = true;
        if ($success) {
            $success_2 = $this->GestorCaso->insertNuevoEvento($_POST);//Inserta la informacion
            if ($success_2['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $data_p['status'] =  true;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $success_2['sqlEjecutados']);
                $descripcion = 'INSERCION DEL EVENTO: '.$success_2['Folio_infra'].' '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $success_3=$this->GestorCaso->historial($user, $ip, 3, $descripcion);//Guarda en el historial el movimiento
                if($success_3){
                    $this->GuardarFotosVP($success_2['Folio_infra']);//Envia a guardar los archivos
                }
            } else {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $ip = $this->obtenerIp();
                $data_p['status'] =  false;
                $data_p['error_message'] = $success_2['error_message'];
                $data_p['error_sql'] = $success_2['error_sql'];
                $descripcion= $data_p['error_message'].'|||'.$data_p['error_sql'];
                $this->Escribelogtxt($user, $ip, $descripcion);//Si ubo un error guarda la informacion
                $this->GestorCaso->logs($user, $ip, $descripcion);//Si ubo un error guarda la informacion
            }
        } else {
            $data_p['status'] =  false;
        }
        echo json_encode($data_p);
    }
    public function Escribelogtxt($user, $ip, $descripcion){
        $path_carpeta = BASE_PATH . "public/";
        $archivo = fopen($path_carpeta.'logs.txt','a') or die ("Error al crear archivo log");
        $cad = $user.' ||| '.$ip.' ||| '.$descripcion;
        fwrite($archivo,$cad);
        fwrite($archivo,"\r\n");
        fclose($archivo);
    }
    /* ----------------------------------------GUARDAR FOTOS DE INVOLUCRADOS Y VEHICULOS ------------------------------------- */
    public function GuardarFotosVP($Folio_infra){
        $path_carpeta = BASE_PATH . "public/files/GestorCasos/" . $Folio_infra . "/Evento/";
        $path_carpeta2 = BASE_PATH . "public/files/GestorCasos/" . $Folio_infra . "/Respaldo/";
        if(isset($_POST['vehiculos_table'])){
            $vehiculos = json_decode($_POST['vehiculos_table']);//Saca los datos de los vehiculos
        }
        if(isset($_POST['responsables_table'])){
            $involucrados = json_decode($_POST['responsables_table']);//Saca los datos de los involucrados
        }
        foreach (glob($path_carpeta . "/*") as $archivos_carpeta) {
            if (is_dir($archivos_carpeta)) {
                rmDir_rf($archivos_carpeta);
            } else {
                unlink($archivos_carpeta);
            }
        }
        if(isset($vehiculos)){
            foreach ($vehiculos as $vehiculo) {
                if($vehiculo->row->nameImage != 'null'){
                    if ($vehiculo->row->typeImage == 'File') {
                        $type = $_FILES[$vehiculo->row->nameImage]['type'];
                        $extension = explode("/", $type);
                        $hoy = date("Y-m-d H:i:s");
                        $quitar = array(":", "/");
                        $hoy =str_replace($quitar, "-", $hoy);
                        $result = $this->uploadImageFileGC($vehiculo->row->nameImage, $_FILES, $Folio_infra, $path_carpeta, $vehiculo->row->nameImage . ".png");//Escritura de fotos en la carpeta
                        $result = $this->uploadImageFileGC($vehiculo->row->nameImage, $_FILES, $Folio_infra, $path_carpeta2,$hoy. $vehiculo->row->nameImage .".png");//Escritura de fotos en el respaldo
                    }
                    if ($vehiculo->row->typeImage == 'Photo') {
                        $result = $this->uploadImagePhotoGC($vehiculo->row->image, $Folio_infra, $path_carpeta, $path_carpeta . $vehiculo->row->nameImage . ".png");//Escritura de fotos en la carpeta
                        if( $_POST['banderafotosVP']!='true'){//Esta bandera es para cuando no existe un archivo fisico si es falsa genera un respaldo 
                            $hoy = date("Y-m-d H:i:s");
                            $quitar = array(":", "/");
                            $hoy =str_replace($quitar, "-", $hoy);
                            $result = $this->uploadImagePhotoGC($vehiculo->row->image, $Folio_infra, $path_carpeta2, $path_carpeta2. $hoy . $vehiculo->row->nameImage . ".png");//Escritura de fotos en el respaldo
                        }
                    }
                }
            }
        }
        if(isset($involucrados)){
            foreach ($involucrados as $involucrado) {
                if($involucrado->row->nameImage != 'null'){
                    if ($involucrado->row->typeImage == 'File') {
                        $type = $_FILES[$involucrado->row->nameImage]['type'];
                        $extension = explode("/", $type);
                        $hoy = date("Y-m-d H:i:s");
                        $quitar = array(":", "/");
                        $hoy =str_replace($quitar, "-", $hoy);
                        $result = $this->uploadImageFileGC($involucrado->row->nameImage, $_FILES, $Folio_infra, $path_carpeta, $involucrado->row->nameImage . ".png");//Escritura de fotos en la carpeta
                        $result = $this->uploadImageFileGC($involucrado->row->nameImage, $_FILES, $Folio_infra, $path_carpeta2,$hoy. $involucrado->row->nameImage .".png");//Escritura de fotos en el respaldo
                    }
                    if ($involucrado->row->typeImage == 'Photo') {
                        $result = $this->uploadImagePhotoGC($involucrado->row->image, $Folio_infra, $path_carpeta, $path_carpeta . $involucrado->row->nameImage . ".png");//Escritura de fotos en la carpeta
                        if( $_POST['banderafotosVP']!='true'){
                            $hoy = date("Y-m-d H:i:s");
                            $quitar = array(":", "/");
                            $hoy =str_replace($quitar, "-", $hoy);
                            $result = $this->uploadImagePhotoGC($involucrado->row->image, $Folio_infra, $path_carpeta2, $path_carpeta2. $hoy . $involucrado->row->nameImage . ".png");//Escritura de fotos en el respaldo
                        }
                    }
                }
            }
        }
    }
    /* -----------------------------------------EDICION DE CASOS ---------------------------------------- */
    public function editarEvento(){
        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }
        //comprobar los permisos para dejar pasar al módulo
        if($_SESSION['userdataSIC']->Modo_Admin != 1 && $_SESSION['userdataSIC']->Seguimientos[1] != 1 && $_SESSION['userdataSIC']->Evento_D[1] != 1){
            header("Location: " . base_url . "Estadisticas");
            exit();
        }
         //Cargar archivos y datos de los catalogos para la intefaz de la edicion del evento
        $datos_prim = [
            'zonas' => $this->getZona(),
            'violencia' => $this->getViolencia(),
            'sinviolencia' => $this->getSViolencia(),
            'armas' => $this->getArmas(),
            'claves' => $this->getClave(),
            'fuentes'=> $this->getFuente(),
            'tipo_vehiculos'=> $this->getTipoVehiculos(),
            'edades'=> $this->getRangoEdades()
        ];
    
        $data = [
            'titulo'     => 'AURA | Edicion de Evento',
            'extra_css'  => '<link rel="stylesheet" href="' . base_url . 'public/css/system/gestorCasos/fullview.css">',
            'extra_js'   => '<script src="https://api.mapbox.com/mapbox-gl-js/v2.8.1/mapbox-gl.js"></script>'.
                            '<link href="https://api.mapbox.com/mapbox-gl-js/v2.8.1/mapbox-gl.css" rel="stylesheet" />'.
                            '<script src="'.base_url.'public/js/system/gestorCasos/principales_mapbox.js"></script>'.
                            '<script src="'.base_url.'public/js/system/gestorCasos/fotos_mapbox.js"></script>'.
                            '<script src="'.base_url.'public/js/system/gestorCasos/getInformation/getFotosVideos.js"></script>'.
                            '<script src="'.base_url.'public/js/system/gestorCasos/getInformation/getEntrevista.js"></script>'.
                            '<script src="'.base_url.'public/js/system/gestorCasos/getInformation/getEvento.js"></script>'.
                            '<script src="'.base_url.'public/js/system/gestorCasos/editarEvento/validaEditarEvento.js"></script>'.
                            '<script src="'.base_url.'public/js/system/gestorCasos/editarEvento/entrevistas.js"></script>'.
                            '<script src="'.base_url.'public/js/system/gestorCasos/editarEvento/fotostables.js"></script>'.
                            '<script src="'.base_url.'public/js/system/gestorCasos/editarEvento/imagenes.js"></script>'.
                            '<script src="'.base_url.'public/js/system/gestorCasos/editarEvento/indexedit.js"></script>'.
                            '<script src="'.base_url.'public/js/system/gestorCasos/editarEvento/involucrados.js"></script>'.
                            '<script src="'.base_url.'public/js/system/gestorCasos/editarEvento/involucradosVeh.js"></script>'.
                            '<script src="'.base_url.'public/js/system/gestorCasos/editarEvento/ubicacion_detencion.js"></script>',
            'datos_prim' => $datos_prim
        ];

        
        $this->view('templates/header', $data);
        $this->view('system/gestorCasos/nuevoEventoFullView', $data);
        $this->view('templates/footer', $data);
    }
    /*----------------FUNCIONES PARA DESASOCIAR DATOS DE TABLAS------------------------ */
    public function DesasociaInvolucrado(){//FUNCION PARA ELIMINAR UNA PERSONA DE LA TABLA CON ID EN BASE DE DATOS
        if (isset($_POST['Id_Registro']) && isset($_POST['Folio_infra'])) {
            $Id_Registro = $_POST['Id_Registro'];
            $Folio_infra= $_POST['Folio_infra'];
            $data = $this->GestorCaso->DesasociaInvolucrado($Id_Registro);
            if ($data['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $data['sqlEjecutados']);
                $descripcion = 'SE ELIMINO EL INVOLUCRADO: '.$Id_Registro.' DEL EVENTO CON FOLIO INFRA: '.$Folio_infra.' EL USUARIO '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $this->GestorCaso->historial($user, $ip, 6, $descripcion);//Guarda en el historial el movimiento
            } 

            echo json_encode($data);
        } else {
            header("Location: " . base_url . "GestorCasos");
            exit();
        }
    }
    public function DesasociaVehInvolucrado(){//FUNCION PARA ELIMINAR UNA PERSONA DE LA TABLA CON ID EN BASE DE DATOS
        if (isset($_POST['Id_Registro']) && isset($_POST['Folio_infra'])) {
            $Id_Registro = $_POST['Id_Registro'];
            $Folio_infra= $_POST['Folio_infra'];
            $data = $this->GestorCaso->DesasociaVehInvolucrado($Id_Registro);
            if ($data['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $data['sqlEjecutados']);
                $descripcion = 'SE ELIMINO EL VEHICULO INVOLUCRADO: '.$Id_Registro.' DEL EVENTO CON FOLIO INFRA: '.$Folio_infra.' EL USUARIO '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $this->GestorCaso->historial($user, $ip, 6, $descripcion);//Guarda en el historial el movimiento
            } 

            echo json_encode($data);
        } else {
            header("Location: " . base_url . "GestorCasos");
            exit();
        }
    }
    public function getCamaras(){//FUNCION QUE OBTINE EL CATALOGO DE LAS REMISIONES
        $data = $this->GestorCaso->getCamaras();
        echo json_encode($data);
    }
    public function updateEvento(){
        //comprobar los permisos para dejar pasar al módulo
        if($_SESSION['userdataSIC']->Modo_Admin != 1 && $_SESSION['userdataSIC']->Seguimientos[1] != 1 && $_SESSION['userdataSIC']->Evento_D[1] != 1 ){
            $data_p['status'] = false;
            $data_p['error_message'] = 'Render Index';
            echo json_encode($data_p);
        }
        if(isset($_POST['Folio_infra'])){
            $success = true;
            if ($success) {
                $success_2 = $this->GestorCaso->updatePrincipales($_POST);
                if ($success_2['status']) {
                    $user = $_SESSION['userdataSIC']->Id_Usuario;
                    $data_p['status'] =  true;
                    $ip = $this->obtenerIp();
                    $quitar = array("'", "\"");
                    $auxsql =str_replace($quitar, "-", $success_2['sqlEjecutados']);
                    $descripcion = 'ACTUALIZACION DE EVENTO: '.$_POST['Folio_infra'].' '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                    $success_3=$this->GestorCaso->historial($user, $ip, 6, $descripcion);//Guarda en el historial el movimiento
                    if($success_3){
                        $this->GuardarFotosVP($_POST['Folio_infra']);//Envia a guardar los archivos
                    }
                    if (isset($_FILES['file_pdf'])) {
                        $path_carpeta_PDF= BASE_PATH . "public/files/GestorCasos/" . $_POST['Folio_infra']."/" ;
                        $path_file_PDF = BASE_PATH .  "public/files/GestorCasos/" . $_POST['Folio_infra']."/Evento" . $_POST['Folio_infra'] . ".pdf";
                        $name = 'file_pdf';
                        $result = $this->uploadPDFFileEvento($name, $_FILES, $path_carpeta_PDF, $path_file_PDF);
                        $data_p['file'] = $result;
                    } 
                } else {
                    $data_p['status'] =  false;
                    $data_p['error_message'] = $success_2['error_message'];
                    $data_p['error_sql'] = $success_2['error_sql'];
                }
            } else {
                $data_p['status'] =  false;
            }
            echo json_encode($data_p);
        }else{
            $data_p['status'] = false;
            $data_p['error_message'] = 'No Existe Folio Infra';
            echo json_encode($data_p);
        }     
    }
    public function uploadPDFFileEvento($name, $file, $carpeta, $ruta){//ACTUALIZA LOS ARCHIVOS FISICOS PDF EN EL SERVIDOR DEL SEGUIMIENTO
        $allowed_mime_type_arr = array('pdf');
        $arrayAux = explode('.', $file[$name]['name']);
        $mime = end($arrayAux);

        if ((isset($file[$name]['name'])) && ($file[$name]['name'] != "")) {
            if (in_array($mime, $allowed_mime_type_arr)) {
                $band = true;
            } else {
                $band = false;
            }
        } else {
            $band = false;
        }

        /* ----- ----- ----- Existe la carpeta ----- ----- ----- */
        if (!file_exists($carpeta))
            mkdir($carpeta, 0777, true);

        if ($band) {
            move_uploaded_file($file[$name]['tmp_name'], $ruta);
        }

        return $band;
    }
    public function UpdateSeguimientoTerminado(){// Funcion que cambia el estatus del seguimiento del evento
        if(!isset($_SESSION['userdataSIC']) || ($_SESSION['userdataSIC']->Modo_Admin != 1 && $_SESSION['userdataSIC']->Seguimientos[1] != 1 ) ){
                $data_p['status'] = false;
                $data_p['error_message'] = 'Render Index';
                echo json_encode($data_p);
            
        }

        if(isset($_POST['FolioInfra'])){
            $success=$this->GestorCaso->UpdateSeguimientoTerminados($_POST);

            if ($success['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $data_p['status'] =  $success['status'];
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $success['sqlEjecutados']);
                $descripcion = 'ACTUALIZACION DE EVENTO TERMINO SEGUIMIENTO: '.$_POST['FolioInfra'].' '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $success_2=$this->GestorCaso->historial($user, $ip, 9, $descripcion);//Guarda en el historial el movimiento
                
            }else{
                $data_p['status'] = false;
                $data_p['error_message'] = 'ubo error en la actualizacion';
            }

        }
        echo json_encode($data_p);


    }

    /* -----------------------------------------VER EVENTO---------------------------------------- */
    
    public function verEvento(){
        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }
        //comprobar los permisos para dejar pasar al módulo
        if($_SESSION['userdataSIC']->Modo_Admin != 1 && $_SESSION['userdataSIC']->Seguimientos[2] != 1 && $_SESSION['userdataSIC']->Evento_D[2] != 1 ){
            header("Location: " . base_url . "Estadisticas");
            exit(); 
        }
          //Cargar archivos para la intefaz ver el evento
       
            $data = [
                'titulo'     => 'AURA | Ver Evento',
                'extra_css'  => '<link rel="stylesheet" href="' . base_url . 'public/css/system/gestorCasos/fullview.css">',
                'extra_js'   => 
                                '<script src="'.base_url.'public/js/system/gestorCasos/getInformation/getEvento-ro.js"></script>'.
                                '<script src="'.base_url.'public/js/system/gestorCasos/getInformation/getSeguimiento-ro.js"></script>',
            ];
       

        $this->view('templates/header', $data);
        $this->view('system/gestorCasos/EventoFullView-readOnly', $data);
        $this->view('templates/footer', $data);
    }
    /* -----------------------------------------VER RESUMEN DEL EVENTO---------------------------------------- */
    public function verResumenEvento(){
        //comprobar los permisos para dejar pasar al módulo
        if(!isset($_SESSION['userdataSIC']) || ($_SESSION['userdataSIC']->Modo_Admin != 1  &&  $_SESSION['userdataSIC']->Seguimientos[2] != '1')){
           
                header("Location: " . base_url . "GestorCasos");
                exit();
        }
        //Cargar archivos para la intefaz ver el resumen del evento
        $data = [
            'titulo'     => 'AURA | Ver Evento',
            'extra_css'  => '<link rel="stylesheet" href="' . base_url . 'public/css/system/gestorCasos/fullview.css">',
            'extra_js'   => 
                            '<script src="'.base_url.'public/js/system/gestorCasos/getInformation/getEventoResumen.js"></script>',
        ];

        $this->view('templates/header', $data);
        $this->view('system/gestorCasos/VerResumen/datosResumen', $data);
        $this->view('templates/footer', $data);
    }

    /* ----------------------------------------FUNCIONES DE FILTROS ------------------------------------- */
    //función para checar los cambios de filtro y poder asignar los valores correspondientes de las columnas a la session
    public function setColumnsSession($filtroActual = 1){
        //si el filtro existe y esta dentro de los parámetros continua
        if (isset($_SESSION['userdataSIC']->filtro_GC) && $_SESSION['userdataSIC']->filtro_GC >= MIN_FILTRO_GC && $_SESSION['userdataSIC']->filtro_GC <= MAX_FILTRO_GC) {
            //si cambia el filtro se procde a cambiar los valores de las columnas que contiene el filtro seleccionado
            if ($_SESSION['userdataSIC']->filtro_GC != $filtroActual) {
                $_SESSION['userdataSIC']->filtro_GC = $filtroActual;
                unset($_SESSION['userdataSIC']->columns_GC); 
                for ($i = 0; $i < $this->numColumnsGC[$_SESSION['userdataSIC']->filtro_GC - 1]; $i++)
                    $_SESSION['userdataSIC']->columns_GC['column' . ($i + 1)] = 'show';
            }
        } else { //si no existe el filtro entonces se inicializa con el primero por default
            $_SESSION['userdataSIC']->filtro_GC = $filtroActual;
            unset($_SESSION['userdataSIC']->columns_GC);
            for ($i = 0; $i < $this->numColumnsGC[$_SESSION['userdataSIC']->filtro_GC - 1]; $i++)
                $_SESSION['userdataSIC']->columns_GC['column' . ($i + 1)] = 'show';
        }
    }

    //función fetch que actualiza los valores de las columnas para la session
    public function setColumnFetch(){
        if (isset($_POST['columName']) && isset($_POST['valueColumn'])) {
            $_SESSION['userdataSIC']->columns_GC[$_POST['columName']] = $_POST['valueColumn'];
            echo json_encode("ok");
        } else {
            header("Location: " . base_url . "GestorCasos");
            exit();
        }
    }
        
    
    public function generarLinks($numPage, $total_pages, $extra_cad = "", $filtro = 1){
        //$extra_cad sirve para determinar la paginacion conforme a si se realizó una busqueda
        //Creación de links para la paginacion
        $links = "";

        //FLECHA IZQ (PREV PAGINATION)
        if ($numPage > 1) {
            $links .= '<li class="page-item">
                            <a class="page-link" href=" ' . base_url . 'GestorCasos/index/?numPage=1' . $extra_cad . '&filtro=' . $filtro . ' " data-toggle="tooltip" data-placement="top" title="Primera página">
                                <i class="material-icons">first_page</i>
                            </a>
                        </li>';
            $links .= '<li class="page-item">
                            <a class="page-link" href=" ' . base_url . 'GestorCasos/index/?numPage=' . ($numPage - 1) . $extra_cad . '&filtro=' . $filtro . ' " data-toggle="tooltip" data-placement="top" title="Página anterior">
                                <i class="material-icons">navigate_before</i>
                            </a>
                        </li>';
        }

        //DESPLIEGUE DE PAGES NUMBER
        $LINKS_EXTREMOS = GLOBAL_LINKS_EXTREMOS; //numero máximo de links a la izquierda y a la derecha
        for ($ind = ($numPage - $LINKS_EXTREMOS); $ind <= ($numPage + $LINKS_EXTREMOS); $ind++) {
            if (($ind >= 1) && ($ind <= $total_pages)) {

                $activeLink = ($ind == $numPage) ? 'active' : '';

                $links .= '<li class="page-item ' . $activeLink . ' ">
                                <a class="page-link" href=" ' . base_url . 'GestorCasos/index/?numPage=' . ($ind) . $extra_cad . '&filtro=' . $filtro . ' ">
                                    ' . ($ind) . '
                                </a>
                            </li>';
            }
        }

        //FLECHA DERECHA (NEXT PAGINATION)
        if ($numPage < $total_pages) {

            $links .= '<li class="page-item">
                            <a class="page-link" href=" ' . base_url . 'GestorCasos/index/?numPage=' . ($numPage + 1) . $extra_cad . '&filtro=' . $filtro . ' " data-toggle="tooltip" data-placement="top" title="Siguiente página">
                            <i class="material-icons">navigate_next</i>
                            </a>
                        </li>';
            $links .= '<li class="page-item">
                            <a class="page-link" href=" ' . base_url . 'GestorCasos/index/?numPage=' . ($total_pages) . $extra_cad . '&filtro=' . $filtro . ' " data-toggle="tooltip" data-placement="top" title="Última página">
                            <i class="material-icons">last_page</i>
                            </a>
                        </li>';
        }

        return $links;
    }
    public function buscarPorCadena(){//Funcion para buscar lo que le escribes en el panel de texto buscar

        if (isset($_POST['cadena'])) {//Comprueba si existe una cadena para buscar
            $cadena = trim($_POST['cadena']);
            $filtroActual = trim($_POST['filtroActual']);

            $results = $this->GestorCaso->getEventoDByCadena($cadena, $filtroActual);//Devuelve los datos de la cadena consultada
            if (strlen($cadena) > 0) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $ip = $this->obtenerIp();
                $descripcion = 'CONSULTA: ' . $cadena .' '.$_SESSION['userdataSIC']->User_Name;
                $this->GestorCaso->historial($user, $ip, 7, $descripcion);//Escribe en el historial el movimiento
            }
            $extra_cad = ($cadena != "") ? ("&cadena=" . $cadena) : ""; //para links conforme a búsqueda

            $dataReturn['infoTable'] = $this->generarInfoTable($results['rows_Rems'], $filtroActual);
            $dataReturn['links'] = $this->generarLinks($results['numPage'], $results['total_pages'], $extra_cad, $filtroActual);
            $dataReturn['export_links'] = $this->generarExportLinks($extra_cad, $filtroActual);
            $dataReturn['total_rows'] = "Total registros: " . $results['total_rows'];
            $dataReturn['dropdownColumns'] = $this->generateDropdownColumns($filtroActual);

            
            echo json_encode($dataReturn);
        } else {
            header("Location: " . base_url . "GestorCasos");
            exit();
        }
    }
    public function obtenerIp(){//Obtiene la ip de la computadora para historial principalmente
        $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $hosts = gethostbynamel($hostname);
        if (is_array($hosts)) {
            foreach ($hosts as $ip) {
                return $ip;
            }
        } else {
            return $ip = '0.0.0.0';
        }
    }
    public function generarExportLinks($extra_cad = "", $filtro = 1){//Funcion para exportar la informacion 
        if ($extra_cad != "") {
            $dataReturn['csv'] =  base_url . 'GestorCasos/exportarInfo/?tipo_export=CSV' . $extra_cad . '&filtroActual=' . $filtro;
            $dataReturn['excel'] =  base_url . 'GestorCasos/exportarInfo/?tipo_export=EXCEL' . $extra_cad . '&filtroActual=' . $filtro;
            $dataReturn['pdf'] =  base_url . 'GestorCasos/exportarInfo/?tipo_export=PDF' . $extra_cad . '&filtroActual=' . $filtro;
        } else {
            $dataReturn['csv'] =  base_url . 'GestorCasos/exportarInfo/?tipo_export=CSV' . $extra_cad . '&filtroActual=' . $filtro;
            $dataReturn['excel'] =  base_url . 'GestorCasos/exportarInfo/?tipo_export=EXCEL' . $extra_cad . '&filtroActual=' . $filtro;
            $dataReturn['pdf'] =  base_url . 'GestorCasos/exportarInfo/?tipo_export=PDF' . $extra_cad . '&filtroActual=' . $filtro;
        }
        return $dataReturn;
    }
    
    //función para generar la información de la tabla de forma dinámica
    public function generarInfoTable($rows, $filtro = 1){
        $permisos_Editar = ($_SESSION['userdataSIC']->Seguimientos[1] == 1||$_SESSION['userdataSIC']->Evento_D[1] == 1 || $_SESSION['userdataSIC']->Modo_Admin == 1) ? '' : 'mi_hide';//desaparece los botones de acuerdo a los permisos
        $permisos_Ver = ($_SESSION['userdataSIC']->Seguimientos[2] == 1||$_SESSION['userdataSIC']->Evento_D[2] == 1 || $_SESSION['userdataSIC']->Modo_Admin == 1) ? '' : 'mi_hide';
        $permisos_VerResumen= ($_SESSION['userdataSIC']->Seguimientos[2] == 1|| $_SESSION['userdataSIC']->Modo_Admin == 1) ? '' : 'mi_hide';
        $permisos_VerRed= ($_SESSION['userdataSIC']->Red[2] == 1|| $_SESSION['userdataSIC']->Modo_Admin == 1) ? '' : 'mi_hide';
        //se genera la tabulacion de la informacion por backend
        $infoTable['header'] = "";
        $infoTable['body'] = "";


        switch ($filtro) {
            case '1': //General de todos los casos
                $infoTable['header'] .= '
                        <th class="column1">Folio Infra</th>
                        <th class="column2">Elemento Asignado para Seguimiento</th>
                        <th class="column3">Folio 911</th>
                        <th class="column4">Fecha de Recepcion (AAAA-MM-DD)</th>
                        <th class="column5">Estatus del Seguimiento</th>
                        <th class="column6">Motivo</th>
                        <th class="column7">Con/Sin Violencia</th>
                        <th class="column8">Colonia</th>
                        <th class="column9">Calle</th>
                        <th class="column10">Zona y Vector</th>
                        
                    ';
                foreach ($rows as $row) {
                    if($row->ClaveSeguimiento!=''){
                        $infoTable['body'] .= '<tr id="tr' . $row->Folio_infra . '">';
                        $infoTable['body'] .= '  <td class="column1">' . $row->Folio_infra . '</td>
                                                <td class="column2">' . mb_strtoupper($row->ClaveSeguimiento) . '</td>
                                                <td class="column3">' . $row->Folio_911 . '</td>
                                                <td class="column4">' . mb_strtoupper($row->FechaHora_Recepcion). '</td>
                                                <td class="column5">' . mb_strtoupper($row->Status) . '</td>
                                                <td class="column6">' . mb_strtoupper($row->delitos_concat)  . '</td>
                                                <td class="column7">' . mb_strtoupper($row->CSviolencia) . '</td>
                                                <td class="column8">' . mb_strtoupper($row->Colonia) . '</td>
                                                <td class="column9">' . mb_strtoupper($row->Calle) . '</td>
                                                <td class="column10">' . mb_strtoupper($row->Zona).' '.mb_strtoupper($row->Vector) . '</td>
                                                
                            ';
                    }else{
                        $infoTable['body'] .= '<tr id="tr' . $row->Folio_infra . '">';
                        $infoTable['body'] .= '  <td class="column1">' . $row->Folio_infra . '</td>
                                                <td class="column2">'.'SIN ASIGNAR'.'</td>
                                                <td class="column3">' . $row->Folio_911 . '</td>
                                                <td class="column4">' . mb_strtoupper($row->FechaHora_Recepcion). '</td>
                                                <td class="column5">' . mb_strtoupper($row->Status) . '</td>
                                                <td class="column6">' . mb_strtoupper($row->delitos_concat)  . '</td>
                                                <td class="column7">' . mb_strtoupper($row->CSviolencia) . '</td>
                                                <td class="column8">' . mb_strtoupper($row->Colonia) . '</td>
                                                <td class="column9">' . mb_strtoupper($row->Calle) . '</td>
                                                <td class="column10">' . mb_strtoupper($row->Zona).' '.mb_strtoupper($row->Vector) . '</td>
                            ';

                    }
                    if ($row->FechaHora_Captura != '') {
                        if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Seguimientos[1] == '1'|| $_SESSION['userdataSIC']->Evento_D[1]==1) { //validacion de tabs validados completamente y/o permisos de validacion o modo admin
                            $infoTable['body'] .= '<td class="d-flex">
                                                    <a class="myLinks' . $permisos_Editar . '" data-toggle="tooltip" data-placement="right" title="Editar registro" href="' . base_url . 'GestorCasos/editarEvento/?Folio_infra=' . $row->Folio_infra . '">
                                                        <i class="material-icons">edit</i>
                                                    </a>';
                        } else {
                            $infoTable['body'] .= '<td class="d-flex">';
                        }
                        if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Seguimientos[2] == '1'|| $_SESSION['userdataSIC']->Evento_D[2]==1) { //validacion de tabs validados completamente y/o permisos de validacion o modo admin
                           
                            $infoTable['body'] .= '
                                                    <a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Ver registro" href="' . base_url . 'GestorCasos/verEvento/?Folio_infra=' . $row->Folio_infra . '">
                                                        <i class="material-icons">visibility</i>
                                                    </a>';
                        }
                        if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Seguimientos[2] == '1'){
                                $infoTable['body'] .= '
                                                        <a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Generar PDF Ficha del Evento" href="' . base_url . 'GestorCasos/GeneraFichaEventoPDF/?Folio_infra=' . $row->Folio_infra . '">
                                                            <i class="material-icons">file_present</i>
                                                        </a>
                                                        <a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Generar PDF Involucrados" href="' . base_url . 'GestorCasos/GeneraFichaInvolucradoPDF/?Folio_infra=' . $row->Folio_infra . '">
                                                            <i class="material-icons">person</i>
                                                        </a>
                                                        <a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Generar PDF Vehiculos Involucrados" href="' . base_url . 'GestorCasos/GeneraFichaVehInvolucradoPDF/?Folio_infra=' . $row->Folio_infra . '">
                                                            <i class="material-icons">time_to_leave</i>
                                                        </a>
                                                        <a target="_blank" class="myLinks' . $permisos_VerResumen . '" data-toggle="tooltip" data-placement="right" title="Ver Resumen de Evento" href="' . base_url . 'GestorCasos/verResumenEvento/?Folio_infra=' . $row->Folio_infra . '">
                                                            <i class="material-icons">book</i>
                                                        </a>
                                                    ';
                            if($row->Id_Seguimiento!=NULL){
                                $infoTable['body'] .= '<a target="_blank" class="myLinks' . $permisos_VerRed . '" data-toggle="tooltip" data-placement="right" title="Generar PDF Completo del Seguimiento" href="' . base_url . 'GestorCasos/GeneraPDF/?Id_seguimiento=' .$row->Id_Seguimiento. '">
                                                            <i class="material-icons">assignment</i>
                                                        </a>
                                                    </td>';

                            }else{
                                $infoTable['body'] .= '</td>';
                            }
                        }else{
                            $infoTable['body'] .= '</td>';
                        }
                            
                    }
                    $infoTable['body'] .= '</tr>';
                }
                break;
                case '2': //Eventos Habilitados
                    $infoTable['header'] .= '
                            <th class="column1">Folio Infra</th>
                            <th class="column2">Elemento Asignado para Seguimiento</th>
                            <th class="column3">Folio 911</th>
                            <th class="column4">Fecha de Captura  (AAAA-MM-DD) </th>
                            <th class="column5">Fecha de Recepcion (AAAA-MM-DD)</th>
                            <th class="column6">Estatus del Seguimiento</th>
                            <th class="column7">Colonia</th>
                            <th class="column8">Calle</th>
                            <th class="column9">Fecha de Activacion  (AAAA-MM-DD)</th>
                            
                            
                        ';
                    foreach ($rows as $row) {
                        if($row->ClaveSeguimiento!=''){
                            $infoTable['body'] .= '<tr id="tr' . $row->Folio_infra . '">';
                            $infoTable['body'] .= '  <td class="column1">' . $row->Folio_infra . '</td>
                                                    <td class="column2">' . mb_strtoupper($row->ClaveSeguimiento) . '</td>
                                                    <td class="column3">' . $row->Folio_911 . '</td>
                                                    <td class="column4">' . mb_strtoupper($row->FechaHora_Captura) . '</td>
                                                    <td class="column5">' . mb_strtoupper($row->FechaHora_Recepcion) . '</td>
                                                    <td class="column6">' . mb_strtoupper($row->Status) . '</td>
                                                    <td class="column7">' . mb_strtoupper($row->Colonia) . '</td>
                                                    <td class="column8">' . mb_strtoupper($row->Calle) . '</td>
                                                    <td class="column9">' . mb_strtoupper($row->FechaHora_Activacion) . '</td>
                                                   
    
                            ';
                        }else{
                            $infoTable['body'] .= '<tr id="tr' . $row->Folio_infra . '">';
                            $infoTable['body'] .= '  <td class="column1">' . $row->Folio_infra . '</td>
                                                    <td class="column2">' . 'SIN ASIGNAR' . '</td>
                                                    <td class="column3">' . $row->Folio_911 . '</td>
                                                    <td class="column4">' . mb_strtoupper($row->FechaHora_Captura) . '</td>
                                                    <td class="column5">' . mb_strtoupper($row->FechaHora_Recepcion) . '</td>
                                                    <td class="column6">' . mb_strtoupper($row->Status) . '</td>
                                                    <td class="column7">' . mb_strtoupper($row->Colonia) . '</td>
                                                    <td class="column8">' . mb_strtoupper($row->Calle) . '</td>
                                                    <td class="column9">' . mb_strtoupper($row->FechaHora_Activacion) . '</td>
                                                    
    
                            ';
                        }
                        if ($row->FechaHora_Captura != '') {
                            if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Seguimientos[1] == '1'|| $_SESSION['userdataSIC']->Evento_D[1]==1) { //validacion de tabs validados completamente y/o permisos de validacion o modo admin
                                $infoTable['body'] .= '<td class="d-flex">
                                                        <a class="myLinks' . $permisos_Editar . '" data-toggle="tooltip" data-placement="right" title="Editar registro" href="' . base_url . 'GestorCasos/editarEvento/?Folio_infra=' . $row->Folio_infra . '">
                                                            <i class="material-icons">edit</i>
                                                        </a>';
                            } else {
                                $infoTable['body'] .= '<td class="d-flex">';
                            }
                            if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Seguimientos[2] == '1'|| $_SESSION['userdataSIC']->Evento_D[2]==1) { //validacion de tabs validados completamente y/o permisos de validacion o modo admin
                               
                                $infoTable['body'] .= '
                                                        <a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Ver registro" href="' . base_url . 'GestorCasos/verEvento/?Folio_infra=' . $row->Folio_infra . '">
                                                            <i class="material-icons">visibility</i>
                                                        </a>';
                            }
                            if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Seguimientos[2] == '1'){
                                    $infoTable['body'] .= '
                                                            <a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Generar PDF Ficha del Evento" href="' . base_url . 'GestorCasos/GeneraFichaEventoPDF/?Folio_infra=' . $row->Folio_infra . '">
                                                                <i class="material-icons">file_present</i>
                                                            </a>
                                                            <a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Generar PDF Involucrados" href="' . base_url . 'GestorCasos/GeneraFichaInvolucradoPDF/?Folio_infra=' . $row->Folio_infra . '">
                                                                <i class="material-icons">person</i>
                                                            </a>
                                                            <a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Generar PDF Vehiculos Involucrados" href="' . base_url . 'GestorCasos/GeneraFichaVehInvolucradoPDF/?Folio_infra=' . $row->Folio_infra . '">
                                                                <i class="material-icons">time_to_leave</i>
                                                            </a>
                                                            <a target="_blank" class="myLinks' . $permisos_VerResumen . '" data-toggle="tooltip" data-placement="right" title="Ver Resumen de Evento" href="' . base_url . 'GestorCasos/verResumenEvento/?Folio_infra=' . $row->Folio_infra . '">
                                                                <i class="material-icons">book</i>
                                                            </a>
                                                        </td>';
                            }else{
                                $infoTable['body'] .= '</td>';
                            }
                                
                        }
                        $infoTable['body'] .= '</tr>';
                    }
                    break;
                case '3': //Eventos Deshabilitados
                    $infoTable['header'] .= '
                            <th class="column1">Folio Infra</th>
                            <th class="column2">Folio 911</th>
                            <th class="column3">Fecha de Captura  (AAAA-MM-DD) </th>
                            <th class="column4">Fecha de Recepcion (AAAA-MM-DD)</th>
                            <th class="column5">Estatus del Seguimiento</th>
                            <th class="column6">Colonia</th>
                            <th class="column7">Calle</th>
                            <th class="column8">Zona</th>
                        ';
                    foreach ($rows as $row) {
                        $infoTable['body'] .= '<tr id="tr' . $row->Folio_infra . '">';
                        $infoTable['body'] .= '  <td class="column1">' . $row->Folio_infra . '</td>
                                                <td class="column2">' . $row->Folio_911 . '</td>
                                                <td class="column3">' . mb_strtoupper($row->FechaHora_Captura) . '</td>
                                                <td class="column4">' . mb_strtoupper($row->FechaHora_Recepcion) . '</td>
                                                <td class="column5">' . mb_strtoupper($row->Status) . '</td>
                                                <td class="column6">' . mb_strtoupper($row->Colonia) . '</td>
                                                <td class="column7">' . mb_strtoupper($row->Calle) . '</td>
                                                <td class="column8">' . mb_strtoupper($row->Zona) . '</td>
    
                            ';
                        if ($row->FechaHora_Captura != '') {
                            if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Seguimientos[1] == '1'|| $_SESSION['userdataSIC']->Evento_D[1]==1) { //validacion de tabs validados completamente y/o permisos de validacion o modo admin
                                $infoTable['body'] .= '<td class="d-flex">
                                                        <a class="myLinks' . $permisos_Editar . '" data-toggle="tooltip" data-placement="right" title="Editar registro" href="' . base_url . 'GestorCasos/editarEvento/?Folio_infra=' . $row->Folio_infra . '">
                                                            <i class="material-icons">edit</i>
                                                        </a>';
                            } else {
                                $infoTable['body'] .= '<td class="d-flex">';
                            }
                            if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Seguimientos[2] == '1'|| $_SESSION['userdataSIC']->Evento_D[2]==1) { //validacion de tabs validados completamente y/o permisos de validacion o modo admin
                               
                                $infoTable['body'] .= '
                                                        <a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Ver registro" href="' . base_url . 'GestorCasos/verEvento/?Folio_infra=' . $row->Folio_infra . '">
                                                            <i class="material-icons">visibility</i>
                                                        </a>';
                            }
                            if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Seguimientos[2] == '1'){
                                    $infoTable['body'] .= '
                                                            <a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Generar PDF Ficha del Evento" href="' . base_url . 'GestorCasos/GeneraFichaEventoPDF/?Folio_infra=' . $row->Folio_infra . '">
                                                                <i class="material-icons">file_present</i>
                                                            </a>
                                                            <a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Generar PDF Involucrados" href="' . base_url . 'GestorCasos/GeneraFichaInvolucradoPDF/?Folio_infra=' . $row->Folio_infra . '">
                                                                <i class="material-icons">person</i>
                                                            </a>
                                                            <a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Generar PDF Vehiculos Involucrados" href="' . base_url . 'GestorCasos/GeneraFichaVehInvolucradoPDF/?Folio_infra=' . $row->Folio_infra . '">
                                                                <i class="material-icons">time_to_leave</i>
                                                            </a>
                                                            <a target="_blank" class="myLinks' . $permisos_VerResumen . '" data-toggle="tooltip" data-placement="right" title="Ver Resumen de Evento" href="' . base_url . 'GestorCasos/verResumenEvento/?Folio_infra=' . $row->Folio_infra . '">
                                                                <i class="material-icons">book</i>
                                                            </a>
                                                        </td>';
                            }else{
                                $infoTable['body'] .= '</td>';
                            }
                                
                        }
                        $infoTable['body'] .= '</tr>';
                    }
                    break;
                case '4': //Asinacion de Eventos
                        $infoTable['header'] .= '
                                <th class="column1">Folio Infra</th>
                                <th class="column2">Elemento Asignado para Seguimiento</th>
                                <th class="column3">Folio 911</th>
                                <th class="column4">Fecha de Captura  (AAAA-MM-DD) </th>
                                <th class="column5">Fecha de Recepcion (AAAA-MM-DD)</th>
                                <th class="column6">Estatus del Seguimiento</th>
                                <th class="column7">Colonia</th>
                                <th class="column8">Calle</th>
                                <th class="column9">Zona</th>
                                
                            ';
                        foreach ($rows as $row) {
                            if($row->ClaveSeguimiento!=''){
                                $infoTable['body'] .= '<tr id="tr' . $row->Folio_infra . '">';
                                $infoTable['body'] .= '  <td class="column1">' . $row->Folio_infra . '</td>
                                                        <td class="column2">' . mb_strtoupper($row->ClaveSeguimiento) . '</td>
                                                        <td class="column3">' . $row->Folio_911 . '</td>
                                                        <td class="column4">' . mb_strtoupper($row->FechaHora_Captura) . '</td>
                                                        <td class="column5">' . mb_strtoupper($row->FechaHora_Recepcion) . '</td>
                                                        <td class="column6">' . mb_strtoupper($row->Status) . '</td>
                                                        <td class="column7">' . mb_strtoupper($row->Colonia) . '</td>
                                                        <td class="column8">' . mb_strtoupper($row->Calle) . '</td>
                                                        <td class="column9">' . mb_strtoupper($row->Zona) . '</td>
            
                                    ';
                            }else{
                                $infoTable['body'] .= '<tr id="tr' . $row->Folio_infra . '">';
                                $infoTable['body'] .= '  <td class="column1">' . $row->Folio_infra . '</td>     
                                                        <td class="column2">' . 'SIN ASIGNAR' . '</td>
                                                        <td class="column3">' . $row->Folio_911 . '</td>
                                                        <td class="column4">' . mb_strtoupper($row->FechaHora_Captura) . '</td>
                                                        <td class="column5">' . mb_strtoupper($row->FechaHora_Recepcion) . '</td>
                                                        <td class="column6">' . mb_strtoupper($row->Status) . '</td>
                                                        <td class="column7">' . mb_strtoupper($row->Colonia) . '</td>
                                                        <td class="column8">' . mb_strtoupper($row->Calle) . '</td>
                                                        <td class="column9">' . mb_strtoupper($row->Zona) . '</td>
            
                                    ';
                            }
                            if ($row->FechaHora_Captura != '') {
                                if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Seguimientos[1] == '1'|| $_SESSION['userdataSIC']->Evento_D[1]==1) { //validacion de tabs validados completamente y/o permisos de validacion o modo admin
                                    $infoTable['body'] .= '<td class="d-flex">
                                                            <a class="myLinks' . $permisos_Editar . '" data-toggle="tooltip" data-placement="right" title="Editar registro" href="' . base_url . 'GestorCasos/editarEvento/?Folio_infra=' . $row->Folio_infra . '">
                                                                <i class="material-icons">edit</i>
                                                            </a>';
                                } else {
                                    $infoTable['body'] .= '<td class="d-flex">';
                                }
                                if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Seguimientos[2] == '1'|| $_SESSION['userdataSIC']->Evento_D[2]==1) { //validacion de tabs validados completamente y/o permisos de validacion o modo admin
                                   
                                    $infoTable['body'] .= '
                                                            <a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Ver registro" href="' . base_url . 'GestorCasos/verEvento/?Folio_infra=' . $row->Folio_infra . '">
                                                                <i class="material-icons">visibility</i>
                                                            </a>';
                                }
                                if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Seguimientos[2] == '1'){
                                        $infoTable['body'] .= '
                                                                <a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Generar PDF Ficha del Evento" href="' . base_url . 'GestorCasos/GeneraFichaEventoPDF/?Folio_infra=' . $row->Folio_infra . '">
                                                                    <i class="material-icons">file_present</i>
                                                                </a>
                                                                <a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Generar PDF Involucrados" href="' . base_url . 'GestorCasos/GeneraFichaInvolucradoPDF/?Folio_infra=' . $row->Folio_infra . '">
                                                                    <i class="material-icons">person</i>
                                                                </a>
                                                                <a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Generar PDF Vehiculos Involucrados" href="' . base_url . 'GestorCasos/GeneraFichaVehInvolucradoPDF/?Folio_infra=' . $row->Folio_infra . '">
                                                                    <i class="material-icons">time_to_leave</i>
                                                                </a>
                                                                <a target="_blank" class="myLinks' . $permisos_VerResumen . '" data-toggle="tooltip" data-placement="right" title="Ver Resumen de Evento" href="' . base_url . 'GestorCasos/verResumenEvento/?Folio_infra=' . $row->Folio_infra . '">
                                                                    <i class="material-icons">book</i>
                                                                </a>
                                                            </td>';
                                }else{
                                    $infoTable['body'] .= '</td>';
                                }
                                    
                            }
                            $infoTable['body'] .= '</tr>';
                        }
                break;
                case '5': //General de todos los casos
                    $infoTable['header'] .= '
                            <th class="column1">Folio Infra</th>
                            <th class="column2">Elemento Asignado para Seguimiento</th>
                            <th class="column3">Folio 911</th>
                            <th class="column4">Fecha de Recepcion (AAAA-MM-DD)</th>
                            <th class="column5">Estatus del Seguimiento</th>
                            <th class="column6">Motivo</th>
                            <th class="column7">Con/Sin Violencia</th>
                            <th class="column8">Colonia</th>
                            <th class="column9">Calle</th>
                            <th class="column10">Zona y Vector</th>
                        ';
                    foreach ($rows as $row) {
                        if($row->ClaveSeguimiento!=''){
                            $infoTable['body'] .= '<tr id="tr' . $row->Folio_infra . '">';
                            $infoTable['body'] .= '  <td class="column1">' . $row->Folio_infra . '</td>
                                                    <td class="column2">' . mb_strtoupper($row->ClaveSeguimiento) . '</td>
                                                    <td class="column3">' . $row->Folio_911 . '</td>
                                                    <td class="column4">' . mb_strtoupper($row->FechaHora_Recepcion). '</td>
                                                    <td class="column5">' . mb_strtoupper($row->Status) . '</td>
                                                    <td class="column6">' . mb_strtoupper($row->delitos_concat)  . '</td>
                                                    <td class="column7">' . mb_strtoupper($row->CSviolencia) . '</td>
                                                    <td class="column8">' . mb_strtoupper($row->Colonia) . '</td>
                                                    <td class="column9">' . mb_strtoupper($row->Calle) . '</td>
                                                    <td class="column10">' . mb_strtoupper($row->Zona).' '.mb_strtoupper($row->Vector) . '</td>
                                ';
                        }else{
                            $infoTable['body'] .= '<tr id="tr' . $row->Folio_infra . '">';
                            $infoTable['body'] .= '  <td class="column1">' . $row->Folio_infra . '</td>
                                                    <td class="column2">'.'SIN ASIGNAR'.'</td>
                                                    <td class="column3">' . $row->Folio_911 . '</td>
                                                    <td class="column4">' . mb_strtoupper($row->FechaHora_Recepcion). '</td>
                                                    <td class="column5">' . mb_strtoupper($row->Status) . '</td>
                                                    <td class="column6">' . mb_strtoupper($row->delitos_concat)  . '</td>
                                                    <td class="column7">' . mb_strtoupper($row->CSviolencia) . '</td>
                                                    <td class="column8">' . mb_strtoupper($row->Colonia) . '</td>
                                                    <td class="column9">' . mb_strtoupper($row->Calle) . '</td>
                                                    <td class="column10">' . mb_strtoupper($row->Zona).' '.mb_strtoupper($row->Vector) . '</td>
                                ';
    
                        }
                        if ($row->FechaHora_Captura != '') {
                            if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Seguimientos[1] == '1'|| $_SESSION['userdataSIC']->Evento_D[1]==1) { //validacion de tabs validados completamente y/o permisos de validacion o modo admin
                                $infoTable['body'] .= '<td class="d-flex">
                                                        <a class="myLinks' . $permisos_Editar . '" data-toggle="tooltip" data-placement="right" title="Editar registro" href="' . base_url . 'GestorCasos/editarEvento/?Folio_infra=' . $row->Folio_infra . '">
                                                            <i class="material-icons">edit</i>
                                                        </a>';
                            } else {
                                $infoTable['body'] .= '<td class="d-flex">';
                            }
                            if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Seguimientos[2] == '1'|| $_SESSION['userdataSIC']->Evento_D[2]==1) { //validacion de tabs validados completamente y/o permisos de validacion o modo admin
                               
                                $infoTable['body'] .= '
                                                        <a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Ver registro" href="' . base_url . 'GestorCasos/verEvento/?Folio_infra=' . $row->Folio_infra . '">
                                                            <i class="material-icons">visibility</i>
                                                        </a>';
                            }
                            if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Seguimientos[2] == '1'){
                                    $infoTable['body'] .= '
                                                            <a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Generar PDF Ficha del Evento" href="' . base_url . 'GestorCasos/GeneraFichaEventoPDF/?Folio_infra=' . $row->Folio_infra . '">
                                                                <i class="material-icons">file_present</i>
                                                            </a>
                                                            <a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Generar PDF Involucrados" href="' . base_url . 'GestorCasos/GeneraFichaInvolucradoPDF/?Folio_infra=' . $row->Folio_infra . '">
                                                                <i class="material-icons">person</i>
                                                            </a>
                                                            <a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Generar PDF Vehiculos Involucrados" href="' . base_url . 'GestorCasos/GeneraFichaVehInvolucradoPDF/?Folio_infra=' . $row->Folio_infra . '">
                                                                <i class="material-icons">time_to_leave</i>
                                                            </a>
                                                            <a target="_blank" class="myLinks' . $permisos_VerResumen . '" data-toggle="tooltip" data-placement="right" title="Ver Resumen de Evento" href="' . base_url . 'GestorCasos/verResumenEvento/?Folio_infra=' . $row->Folio_infra . '">
                                                                <i class="material-icons">book</i>
                                                            </a>
                                                        ';
                                if($row->Id_Seguimiento!=NULL){
                                    $infoTable['body'] .= '<a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Generar PDF Completo del Seguimiento" href="' . base_url . 'GestorCasos/GeneraPDF/?Id_seguimiento=' .$row->Id_Seguimiento. '">
                                                                <i class="material-icons">assignment</i>
                                                            </a>
                                                        </td>';
    
                                }else{
                                    $infoTable['body'] .= '</td>';
                                }
                            }else{
                                $infoTable['body'] .= '</td>';
                            }
                                
                        }
                        $infoTable['body'] .= '</tr>';
                    }
                    break;
      
        }
        $infoTable['header'] .= '<th >Operaciones</th>';
        return $infoTable;
    }

	public function exportarInfo(){//Funcion para Exportar informacion en excel

		if (!isset($_REQUEST['tipo_export'])) {
            header("Location: " . base_url . "GestorCasos");
            exit();
		}
		$from_where_sentence = "";
        $filtroActual =  $_REQUEST['filtroActual'];

        if (isset($_REQUEST['cadena'])){//Verifica si existe una Cadena para consulta
            $from_where_sentence = $this->GestorCaso->generateFromWhereSentence($_REQUEST['cadena'], $filtroActual,"EXCEL");//excel con consulta
        }else{
            $from_where_sentence = $this->GestorCaso->generateFromWhereSentence("",$filtroActual,"EXCEL");//Excel sin consulta
        }
		$tipo_export = $_REQUEST['tipo_export'];;
		if ($tipo_export == 'EXCEL') {
			//se realiza exportacion de usuarios a EXCEL
			$cat_rows = $this->GestorCaso->getAllInfoAlertaByCadena($from_where_sentence);
			switch ($filtroActual) {
				case '1':
                    //Genera nombre de archivo junto con los datos y los encabezasdos 
					$filename = "Vista_General_Eventos";
					$csv_data="Folio infra,Folio 911,Fecha de Recepcion,Hora de Recepcion,Fecha de Captura,Hora de Captura,Delitos,Giro,Con/Sin Violencia,Tipo de Violencia,Conteo de Masculinos,Conteo de Femeninas,Conteo de Vehiculos,Tipos de Vehiculos,Vehiculos Involucrados,Tipo de Armas,Zona,Vector,Colonia,Calle1,Calle2,Numero,Coordenada Y,Coordenada X,Descripcion de hechos,Entrevistas Realizadas Evento,Estatus Seguimiento,Vehiculos,Involucrados,Entrevistas,Fotos\n";
					foreach ($cat_rows as $row) {
                        $partes2 = explode(" ", $row->FechaHora_Recepcion);
                        $partes = explode(" ", $row->FechaHora_Captura);
                        $partesM = explode(" ", $row->Conteo_Masculinos);
                        $partesF = explode(" ", $row->Conteo_Femeninas);
                        $partesV = explode(" ", $row->Conteo_Vehiculos);
                        if($partesM[0]=="UN"){//Convertir el conteo de palabras a numeros masculinos
                            $conteoM=1;

                        }else if($partesM[0]=="SIN"||$partesM[0]==""||$partesM[0]==null){
                            $conteoM=0;
                        }else{
                            $conteoM=$partesM[0];
                        }

                        if($partesF[0]=="UNA"){//Convertir el conteo de palabras a numeros feneninas
                            $conteoF=1;

                        }else if($partesF[0]=="SIN"||$partesF[0]==""||$partesF[0]==null){
                            $conteoF=0;
                        }else{
                            $conteoF=$partesF[0];
                        }

                        if($partesV[0]=="UN"){
                            $conteoV=1;

                        }else if($partesV[0]=="SIN"||$partesV[0]==""||$partesV[0]==null){//Convertir el conteo de palabras a numeros vehiculos
                            $conteoV=0;
                        }else{
                            $conteoV=$partesV[0];
                        }
                        if($row->vehiculos_concat!=''){//verifica si existen datos de vehiculos
                            $banVehiculos='C';
                        }else{
                            $banVehiculos='X';
                        }
                        if($row->responsables_concat!=''){//verifica si existen datos de personas
                            $banResponsables='C';
                        }else{
                            $banResponsables='X';
                        }
                        if($row->entrevistas_seguimiento_concat!=''){//verifica si existen Entrevistas
                            $banEntrevistas='C';
                        }else{
                            $banEntrevistas='X';
                        }
                        
                        if($row->fotos_seguimiento_concat!='' && $row->SeguimientoTerminado=='1'){
                            $banFotos='C';
                        }else{
                            if($row->fotos_seguimiento_concat!='' ){
                                $banFotos='P';
                            }else{
                                $banFotos='X';
                            }   
                        }
                        $auxHechos=$this->tratamiento($row->hechos_concat);
                        $auxEntrevistas=$this->tratamiento($row->entrevistas_seguimiento_concat);
						$csv_data.= mb_strtoupper($row->Folio_infra).",\"".
                                    mb_strtoupper($row->Folio_911)."\",\"".
                                    mb_strtoupper($partes2[0])."\",\"".
                                    mb_strtoupper($partes2[1])."\",\"".
                                    mb_strtoupper($partes[0])."\",\"".
                                    mb_strtoupper($partes[1])."\",\"".
                                    mb_strtoupper($row->delitos_concat)."\",\"".
                                    mb_strtoupper($row->delito_giro)."\",\"".
                                    mb_strtoupper($row->CSviolencia)."\",\"".
                                    mb_strtoupper($row->Tipo_Violencia)."\",\"".
                                    mb_strtoupper($conteoM)."\",\"".
                                    mb_strtoupper($conteoF)."\",\"".
                                    mb_strtoupper($conteoV)."\",\"".
                                    mb_strtoupper($row->Tipos_Vehiculos)."\",\"".
                                    mb_strtoupper($row->vehiculos_involucrados)."\",\"".
                                    mb_strtoupper($row->Tipoarma_concat)."\",\"".
                                    mb_strtoupper($row->Zona)."\",\"".
                                    mb_strtoupper($row->Vector)."\",\"".
                                    mb_strtoupper($row->Colonia)."\",\"".
                                    mb_strtoupper($row->Calle)."\",\"".
                                    mb_strtoupper($row->Calle2)."\",\"".
                                    mb_strtoupper($row->NoExt)."\",\"".
                                    mb_strtoupper($row->CoordY)."\",\"".
                                    mb_strtoupper($row->CoordX)."\",\"".
                                    mb_strtoupper($auxHechos)."\",\"".
                                    mb_strtoupper($auxEntrevistas)."\",\"".
                                    mb_strtoupper($row->Status)."\",\"".
                                    mb_strtoupper($banVehiculos)."\",\"".
                                    mb_strtoupper($banResponsables)."\",\"".
                                    mb_strtoupper($banEntrevistas)."\",\"".
									mb_strtoupper($banFotos)."\"\n";
					}
					break;
                    case '2':
                        $filename = "Eventos_Habilitados";
                        $csv_data="Folio infra,Folio 911,Fecha de Captura,Hora de Captura,Fecha de Recepcion,Hora de Recepcion,Delitos,Con/Sin Violencia,Tipo de Violencia,Conteo de Masculinos,Conteo de Femeninas,Conteo de Vehiculos,Vehiculos Involucrados,Tipo de Armas,Zona,Vector,Colonia,Calle1,Calle2,Numero,Coordenada Y,Coordenada X,Descripcion de hechos,Estatus Seguimiento\n";
                        foreach ($cat_rows as $row) {
                            $partes2 = explode(" ", $row->FechaHora_Recepcion);
                            $partes = explode(" ", $row->FechaHora_Captura);
                            $partesM = explode(" ", $row->Conteo_Masculinos);
                            $partesF = explode(" ", $row->Conteo_Femeninas);
                            $partesV = explode(" ", $row->Conteo_Vehiculos);
                            if($partesM[0]=="UN"){
                                $conteoM=1;
    
                            }else if($partesM[0]=="SIN"||$partesM[0]==""||$partesM[0]==null){//Convertir el conteo de palabras a numeros masculinos
                                $conteoM=0;
                            }else{
                                $conteoM=$partesM[0];
                            }
    
                            if($partesF[0]=="UNA"){
                                $conteoF=1;
    
                            }else if($partesF[0]=="SIN"||$partesF[0]==""||$partesF[0]==null){//Convertir el conteo de palabras a numeros femeninas
                                $conteoF=0;
                            }else{
                                $conteoF=$partesF[0];
                            }
    
                            if($partesV[0]=="UN"){
                                $conteoV=1;
    
                            }else if($partesV[0]=="SIN"||$partesV[0]==""||$partesV[0]==null){//Convertir el conteo de palabras a numeros vehiculos
                                $conteoV=0;
                            }else{
                                $conteoV=$partesV[0];
                            }
                            $auxHechos=$this->tratamiento($row->hechos_concat);
                            $csv_data.= mb_strtoupper($row->Folio_infra).",\"".
                                        mb_strtoupper($row->Folio_911)."\",\"".
                                        mb_strtoupper($partes[0])."\",\"".
                                        mb_strtoupper($partes[1])."\",\"".
                                        mb_strtoupper($partes2[0])."\",\"".
                                        mb_strtoupper($partes2[1])."\",\"".
                                        mb_strtoupper($row->delitos_concat)."\",\"".
                                        mb_strtoupper($row->CSviolencia)."\",\"".
                                        mb_strtoupper($row->Tipo_Violencia)."\",\"".
                                        mb_strtoupper($conteoM)."\",\"".
                                        mb_strtoupper($conteoF)."\",\"".
                                        mb_strtoupper($conteoV)."\",\"".
                                        mb_strtoupper($row->vehiculos_involucrados)."\",\"".
                                        mb_strtoupper($row->Tipoarma_concat)."\",\"".
                                        mb_strtoupper($row->Zona)."\",\"".
                                        mb_strtoupper($row->Vector)."\",\"".
                                        mb_strtoupper($row->Colonia)."\",\"".
                                        mb_strtoupper($row->Calle)."\",\"".
                                        mb_strtoupper($row->Calle2)."\",\"".
                                        mb_strtoupper($row->NoExt)."\",\"".
                                        mb_strtoupper($row->CoordY)."\",\"".
                                        mb_strtoupper($row->CoordX)."\",\"".
                                        mb_strtoupper($auxHechos)."\",\"".
                                        mb_strtoupper($row->Status)."\"\n";
                        }
                        break;
                    case '3':
                        $filename = "Eventos_Deshabilitados";
                        $csv_data="Folio infra,Folio 911,Fecha de Captura,Hora de Captura,Fecha de Recepcion,Hora de Recepcion,Delitos,Con/Sin Violencia,Tipo de Violencia,Conteo de Masculinos,Conteo de Femeninas,Conteo de Vehiculos,Vehiculos Involucrados,Tipo de Armas,Zona,Vector,Colonia,Calle1,Calle2,Numero,Coordenada Y,Coordenada X,Descripcion de hechos,Estatus Seguimiento\n";
                        foreach ($cat_rows as $row) {
                            $partes2 = explode(" ", $row->FechaHora_Recepcion);
                            $partes = explode(" ", $row->FechaHora_Captura);
                            $partesM = explode(" ", $row->Conteo_Masculinos);
                            $partesF = explode(" ", $row->Conteo_Femeninas);
                            $partesV = explode(" ", $row->Conteo_Vehiculos);
                            if($partesM[0]=="UN"){
                                $conteoM=1;
    
                            }else if($partesM[0]=="SIN"||$partesM[0]==""||$partesM[0]==null){//Convertir el conteo de palabras a numeros masculinos
                                $conteoM=0;
                            }else{
                                $conteoM=$partesM[0];
                            }
    
                            if($partesF[0]=="UNA"){
                                $conteoF=1;
    
                            }else if($partesF[0]=="SIN"||$partesF[0]==""||$partesF[0]==null){//Convertir el conteo de palabras a numeros femeninas
                                $conteoF=0;
                            }else{
                                $conteoF=$partesF[0];
                            }
    
                            if($partesV[0]=="UN"){
                                $conteoV=1;
    
                            }else if($partesV[0]=="SIN"||$partesV[0]==""||$partesV[0]==null){//Convertir el conteo de palabras a numeros vehiculos
                                $conteoV=0;
                            }else{
                                $conteoV=$partesV[0];
                            }
                            $auxHechos=$this->tratamiento($row->hechos_concat);
                            $csv_data.= mb_strtoupper($row->Folio_infra).",\"".
                                        mb_strtoupper($row->Folio_911)."\",\"".
                                        mb_strtoupper($partes[0])."\",\"".
                                        mb_strtoupper($partes[1])."\",\"".
                                        mb_strtoupper($partes2[0])."\",\"".
                                        mb_strtoupper($partes2[1])."\",\"".
                                        mb_strtoupper($row->delitos_concat)."\",\"".
                                        mb_strtoupper($row->CSviolencia)."\",\"".
                                        mb_strtoupper($row->Tipo_Violencia)."\",\"".
                                        mb_strtoupper($conteoM)."\",\"".
                                        mb_strtoupper($conteoF)."\",\"".
                                        mb_strtoupper($conteoV)."\",\"".
                                        mb_strtoupper($row->vehiculos_involucrados)."\",\"".
                                        mb_strtoupper($row->Tipoarma_concat)."\",\"".
                                        mb_strtoupper($row->Zona)."\",\"".
                                        mb_strtoupper($row->Vector)."\",\"".
                                        mb_strtoupper($row->Colonia)."\",\"".
                                        mb_strtoupper($row->Calle)."\",\"".
                                        mb_strtoupper($row->Calle2)."\",\"".
                                        mb_strtoupper($row->NoExt)."\",\"".
                                        mb_strtoupper($row->CoordY)."\",\"".
                                        mb_strtoupper($row->CoordX)."\",\"".
                                        mb_strtoupper($auxHechos)."\",\"".
                                        mb_strtoupper($row->Status)."\"\n";
                        }
                        break;
                        case '4':
                            $filename = "Asignacion de Eventos";
                            $csv_data="Folio infra,Folio 911,Fecha y Hora de Caputra,Fecha y Hora de Recepcion,Zona,Vector,Fuente,Colonia,Calle,Con y Sin violencia,Tipo de violencia,Estado,Elemento Asignado para Seguimiento\n";
                            foreach ($cat_rows as $row) {
                                $csv_data.= mb_strtoupper($row->Folio_infra).",\"".
                                            mb_strtoupper($row->Folio_911)."\",\"".
                                            mb_strtoupper($row->FechaHora_Captura)."\",\"".
                                            mb_strtoupper($row->FechaHora_Recepcion)."\",\"".
                                            mb_strtoupper($row->Zona)."\",\"".
                                            mb_strtoupper($row->Vector)."\",\"".
                                            mb_strtoupper($row->Fuente)."\",\"".
                                            mb_strtoupper($row->Colonia)."\",\"".
                                            mb_strtoupper($row->Calle)."\",\"".
                                            mb_strtoupper($row->CSviolencia)."\",\"".
                                            mb_strtoupper($row->Tipo_Violencia)."\",\"".
                                            mb_strtoupper($row->Status)."\",\"".
                                            mb_strtoupper($row->ClaveSeguimiento)."\"\n";
                            }
                        break;
                        case '5':
                            //Genera nombre de archivo junto con los datos y los encabezasdos 
                            $filename = "Vista_General_Eventos_por_Folio";
                            $csv_data="Folio infra,Folio 911,Fecha de Recepcion,Hora de Recepcion,Fecha de Captura,Hora de Captura,Delitos,Giro,Con/Sin Violencia,Tipo de Violencia,Conteo de Masculinos,Conteo de Femeninas,Conteo de Vehiculos,Tipos de Vehiculos,Vehiculos Involucrados,Tipo de Armas,Zona,Vector,Colonia,Calle1,Calle2,Numero,Coordenada Y,Coordenada X,Descripcion de hechos,Estatus Seguimiento,Vehiculos,Involucrados,Entrevistas,Fotos\n";
                            foreach ($cat_rows as $row) {
                                $partes2 = explode(" ", $row->FechaHora_Recepcion);
                                $partes = explode(" ", $row->FechaHora_Captura);
                                $partesM = explode(" ", $row->Conteo_Masculinos);
                                $partesF = explode(" ", $row->Conteo_Femeninas);
                                $partesV = explode(" ", $row->Conteo_Vehiculos);
                                if($partesM[0]=="UN"){//Convertir el conteo de palabras a numeros masculinos
                                    $conteoM=1;
        
                                }else if($partesM[0]=="SIN"||$partesM[0]==""||$partesM[0]==null){
                                    $conteoM=0;
                                }else{
                                    $conteoM=$partesM[0];
                                }
        
                                if($partesF[0]=="UNA"){//Convertir el conteo de palabras a numeros feneninas
                                    $conteoF=1;
        
                                }else if($partesF[0]=="SIN"||$partesF[0]==""||$partesF[0]==null){
                                    $conteoF=0;
                                }else{
                                    $conteoF=$partesF[0];
                                }
        
                                if($partesV[0]=="UN"){
                                    $conteoV=1;
        
                                }else if($partesV[0]=="SIN"||$partesV[0]==""||$partesV[0]==null){//Convertir el conteo de palabras a numeros vehiculos
                                    $conteoV=0;
                                }else{
                                    $conteoV=$partesV[0];
                                }
                                if($row->vehiculos_concat!=''){//verifica si existen datos de vehiculos
                                    $banVehiculos='C';
                                }else{
                                    $banVehiculos='X';
                                }
                                if($row->responsables_concat!=''){//verifica si existen datos de personas
                                    $banResponsables='C';
                                }else{
                                    $banResponsables='X';
                                }
                                if($row->entrevistas_seguimiento_concat!=''){//verifica si existen Entrevistas
                                    $banEntrevistas='C';
                                }else{
                                    $banEntrevistas='X';
                                }
                                
                                if($row->fotos_seguimiento_concat!='' && $row->SeguimientoTerminado=='1'){
                                    $banFotos='C';
                                }else{
                                    if($row->fotos_seguimiento_concat!='' ){
                                        $banFotos='P';
                                    }else{
                                        $banFotos='X';
                                    }   
                                }
                                $auxHechos=$this->tratamiento($row->hechos_concat);
                                $csv_data.= mb_strtoupper($row->Folio_infra).",\"".
                                            mb_strtoupper($row->Folio_911)."\",\"".
                                            mb_strtoupper($partes2[0])."\",\"".
                                            mb_strtoupper($partes2[1])."\",\"".
                                            mb_strtoupper($partes[0])."\",\"".
                                            mb_strtoupper($partes[1])."\",\"".
                                            mb_strtoupper($row->delitos_concat)."\",\"".
                                            mb_strtoupper($row->delito_giro)."\",\"".
                                            mb_strtoupper($row->CSviolencia)."\",\"".
                                            mb_strtoupper($row->Tipo_Violencia)."\",\"".
                                            mb_strtoupper($conteoM)."\",\"".
                                            mb_strtoupper($conteoF)."\",\"".
                                            mb_strtoupper($conteoV)."\",\"".
                                            mb_strtoupper($row->Tipos_Vehiculos)."\",\"".
                                            mb_strtoupper($row->vehiculos_involucrados)."\",\"".
                                            mb_strtoupper($row->Tipoarma_concat)."\",\"".
                                            mb_strtoupper($row->Zona)."\",\"".
                                            mb_strtoupper($row->Vector)."\",\"".
                                            mb_strtoupper($row->Colonia)."\",\"".
                                            mb_strtoupper($row->Calle)."\",\"".
                                            mb_strtoupper($row->Calle2)."\",\"".
                                            mb_strtoupper($row->NoExt)."\",\"".
                                            mb_strtoupper($row->CoordY)."\",\"".
                                            mb_strtoupper($row->CoordX)."\",\"".
                                            mb_strtoupper($auxHechos)."\",\"".
                                            mb_strtoupper($row->Status)."\",\"".
                                            mb_strtoupper($banVehiculos)."\",\"".
                                            mb_strtoupper($banResponsables)."\",\"".
                                            mb_strtoupper($banEntrevistas)."\",\"".
                                            mb_strtoupper($banFotos)."\"\n";
                            }
                            break;
            }
			//se genera el archivo csv o excel
			$csv_data = utf8_decode($csv_data); //escribir información con formato utf8 por algún acento
			header("Content-Description: File Transfer");
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=".$filename.".csv");
			echo $csv_data;
            $user = $_SESSION['userdataSIC']->Id_Usuario;
            $ip = $this->obtenerIp();
            $descripcion = 'EXPORTACION DE EXCEL: ' . $filename .' '.$_SESSION['userdataSIC']->User_Name;
            $this->GestorCaso->historial($user, $ip, 8, $descripcion);//GUarda movimiento en historial

		}else {
			header("Location: ".base_url."GestorCasos");
		}
	}

    //función que filtra las columnas deseadas por el usuario
    public function generateDropdownColumns($filtro = 1){
        $dropDownColumn = '';
        //generación de dropdown dependiendo del filtro
        switch ($filtro) {
            case '1':
                $campos = ['Folio Infra','Elemento Asignado', 'Folio 911', 'Fecha de Recepcion', 'Estatus del Seguimiento','Motivo','Con/Sin Violencia', 'Colonia', 'Calle','Zona y Vector'];
                break;
            case '2':
                $campos = ['Folio Infra','Elemento Asignado', 'Folio 911', 'Fecha de Captura', 'Fecha de Recepcion', 'Descripcion', 'Colonia', 'Calle','Fecha Activacion'];
                break;
            case '3':
                $campos = ['Folio Infra', 'Folio 911', 'Fecha de Captura', 'Fecha de Recepcion', 'Descripcion', 'Colonia', 'Calle','Zona'];
                break;
            case '4':
                $campos = ['Folio Infra','Elemento Asignado', 'Folio 911', 'Fecha de Captura', 'Fecha de Recepcion', 'Descripcion', 'Colonia', 'Calle','Zona'];
                break;
            case '5':
                $campos = ['Folio Infra','Elemento Asignado', 'Folio 911', 'Fecha de Recepcion', 'Estatus del Seguimiento','Motivo','Con/Sin Violencia', 'Colonia', 'Calle','Zona y Vector'];
                break;

        }
        //gestión de cada columna
        $ind = 1;
        foreach ($campos as $campo) {
            $checked = ($_SESSION['userdataSIC']->columns_GC['column' . $ind] == 'show') ? 'checked' : '';
            $dropDownColumn .= ' <div class="form-check">
                                    <input class="form-check-input checkColumns" type="checkbox" value="' . $_SESSION['userdataSIC']->columns_GC['column' . $ind] . '" onchange="hideShowColumn(this.id);" id="column' . $ind . '" ' . $checked . '>
                                    <label class="form-check-label" for="column' . $ind . '">
                                        ' . $campo . '
                                    </label>
                                </div>';
            $ind++;
        }
        $dropDownColumn .= '     <div class="dropdown-divider">
                                </div>
                                <div class="form-check">
                                    <input id="checkAll" class="form-check-input" type="checkbox" value="hide" onchange="hideShowAll(this.id);" id="column' . $ind . '" checked>
                                    <label class="form-check-label" for="column' . $ind . '">
                                        Todo
                                    </label>
                                </div>';
        return $dropDownColumn;
    }

    //funcion para borrar variable sesión para filtro de rangos de fechas
    public function removeRangosFechasSesion(){
        if (isset($_REQUEST['filtroActual'])) {
            unset($_SESSION['userdataSIC']->rango_inicio_gc);
            unset($_SESSION['userdataSIC']->rango_fin_gc);

            header("Location: " . base_url . "GestorCasos/index/?filtro=" . $_REQUEST['filtroActual']);
            exit();
        }
    }

    /* ----------------------------------------FUNCIONES DE PRECARGA Y OBTENCION DE INFORMACION DE CATALOGOS -------------------------------------*/
    // DATOS DE CATALOGOS
    public function getZona(){
        $data = $this->Catalogo->getZonaSector("POLICIA");
        return $data;
    }
    public function getClave(){
        $data = $this->Catalogo->getNombresClave();
        return $data;
    }

    public function getViolencia(){
        $data = $this->Catalogo->getAllTipoViolencia();
        return $data;
    }
    public function getSViolencia(){
        $data = $this->Catalogo->getAllTipoSViolencia();
        return $data;
    }

    public function getArmas(){
        $data = $this->Catalogo->getAllArma();
        return $data;
    }
    /*Recordatorio: si son return son impresion directa de la vista php si son echo son disparables y manipulables desde JS*/
    public function getAllVector(){
        $data =  $this->Catalogo->getAllVector();
        echo json_encode($data);
    }

    public function getDelitos(){
        $data = $this->Catalogo->getAllFaltaDelito();
        echo json_encode($data);
    }
    public function getFuente(){
        $data = $this->Catalogo->getAllFuente();
        return $data;
    }
    public function getTipoVehiculos(){
        $data = $this->Catalogo->getTipoVehiculo();
        return $data;
    }
    /*PARA FUNCION DE AUTOCOMPLETE DE MARCAS DE VEHICULOS */
    public function getMarcas(){
        $data = $this->Catalogo->getMarca();
        echo json_encode($data);
    }
   /*PARA FUNCION DE AUTOCOMPLETE DE SUBMARCAS DE VEHICULOS */
    public function getSubmarcas(){
        $data = $this->Catalogo->getSubmarca();
        echo json_encode($data);
    }
    public function getRangoEdades(){
        $data = $this->Catalogo->getRangoEdad();
        return $data;
    }
    public function getVector(){
        $zona = $_POST['zona'];
        
        $data = $this->Catalogo->getVector($zona);
        echo json_encode($data);
    }
    //DATOS DE GESTOR
    public function getTodos911(){
        $data = $this->GestorCaso->getTodo911();
        echo json_encode($data);
    }

    public function getAllEventos(){
        $data = $this->GestorCaso->getAllEvento($_POST['termino']);
        echo json_encode($data);
    }
    public function getTodosEventos(){
        $data = $this->GestorCaso->getTodosEvento();
        echo json_encode($data);
    }

    /*-----------------------------------------GET DE INFORMACION -----------------------------------------*/
    public function getPrincipalesAll(){

        if (isset($_POST['Folio_infra'])) {
            $Folio_infra = $_POST['Folio_infra'];
            $data = $this->GestorCaso->getPrincipalesAll($Folio_infra);
            $ip = $this->obtenerIp();
            $user = $_SESSION['userdataSIC']->Id_Usuario;
            $descripcion = 'VER EVENTO: '.$_SESSION['userdataSIC']->User_Name.' '.$Folio_infra;
            $success_3=$this->GestorCaso->historial($user, $ip, 2, $descripcion);//GUARDA EL MOVIMIENTO EN EL HISTORIAL
    
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "GestorCasos");
            exit();
        }

    }
    public function getInfoDetencion(){

        if (isset($_POST['Folio_infra'])) {
            $Folio_infra = $_POST['Folio_infra'];
            $data = $this->GestorCaso->getInfoDetencion($Folio_infra);
          
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "GestorCasos");
            exit();
        }

    }
    public function getPrincipales(){

        if (isset($_POST['Folio_infra'])) {
            $Folio_infra = $_POST['Folio_infra'];
            $data = $this->GestorCaso->getPrincipales($Folio_infra);
            $ip = $this->obtenerIp();
            $user = $_SESSION['userdataSIC']->Id_Usuario;
            $descripcion = 'VER EVENTO: '.$_SESSION['userdataSIC']->User_Name.' '.$Folio_infra;
            $success_3=$this->GestorCaso->historial($user, $ip, 2, $descripcion);//GUARDA EL MOVIMIENTO EN EL HISTORIAL
    
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "GestorCasos");
            exit();
        }

    }
    public function getResumen(){

        if (isset($_POST['Folio_infra'])) {
            $Folio_infra = $_POST['Folio_infra'];
            $data = $this->GestorCaso->getResumen($Folio_infra);
            $ip = $this->obtenerIp();
            $user = $_SESSION['userdataSIC']->Id_Usuario;
            $descripcion = 'VER RESUMEN DEL EVENTO: '.$_SESSION['userdataSIC']->User_Name.' '.$Folio_infra;
            $success_3=$this->GestorCaso->historial($user, $ip, 2, $descripcion);//GUARDA EL MOVIMIENTO EN EL HISTORIAL
    
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "GestorCasos");
            exit();
        }

    }

    public function getDelitosC(){

        if (isset($_POST['Folio_infra'])) {
            $Folio_infra = $_POST['Folio_infra'];
            $data = $this->GestorCaso->getDelitosC($Folio_infra);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "GestorCasos");
            exit();
        }

    }
    public function getHechosC(){

        if (isset($_POST['Folio_infra'])) {
            $Folio_infra = $_POST['Folio_infra'];
            $data = $this->GestorCaso->getHechosC($Folio_infra);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "GestorCasos");
            exit();
        }

    }
    public function getResponsablesC(){

        if (isset($_POST['Folio_infra'])) {
            $Folio_infra = $_POST['Folio_infra'];
            $data = $this->GestorCaso->getResponsablesC($Folio_infra);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "GestorCasos");
            exit();
        }

    }
    public function getVehiculosC(){

        if (isset($_POST['Folio_infra'])) {
            $Folio_infra = $_POST['Folio_infra'];
            $data = $this->GestorCaso->getVehiculosC($Folio_infra);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "GestorCasos");
            exit();
        }

    }
    public function getFotos(){

        if (isset($_POST['Folio_infra'])) {
            $Folio_infra = $_POST['Folio_infra'];
            $data = $this->GestorCaso->getFotos($Folio_infra);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "GestorCasos");
            exit();
        }

    }
    public function getEntrevistas(){

        if (isset($_POST['Folio_infra'])) {
            $Folio_infra = $_POST['Folio_infra'];
            $data = $this->GestorCaso->getEntrevistas($Folio_infra);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "GestorCasos");
            exit();
        }

    }

 

/*--------------------------------------FUNCIONES UPDATE TABS -------------------------------------- */

    public function updateFotos(){//Almacena las fotos en el servidor

        if (isset($_POST['Folio_infra'])) {//Comprueba que los datos esten correctos para generar laa carpeta correspondiente
            $Folio_infra = $_POST['Folio_infra'];

            $path_carpeta = BASE_PATH . "public/files/GestorCasos/" . $Folio_infra . "/Seguimiento/";
            $path_carpeta2 = BASE_PATH . "public/files/GestorCasos/" . $Folio_infra . "/Respaldo/";
            if(isset($_POST['fotos_table'])){
                $fotos = json_decode($_POST['fotos_table']);
            }
            
            $success = true;
            if ($success) {
                $success_2 = $this->GestorCaso->updateFotos($_POST);
                if ($success_2) {//$success_2['status']
                    foreach (glob($path_carpeta . "/*") as $archivos_carpeta) {
                        if (is_dir($archivos_carpeta)) {
                            rmDir_rf($archivos_carpeta);
                        } else {
                            unlink($archivos_carpeta);
                        }
                    }
                    $user = $_SESSION['userdataSIC']->Id_Usuario;
                    $ip = $this->obtenerIp();
                    $quitar = array("'", "\"");
                    $auxsql =str_replace($quitar, "-", $success_2['sqlEjecutados']);//Limpia la cadena del sql para la insercion de los datos en el historial
                    $descripcion = 'ACTUALIZACION DE FOTOS: ' . $Folio_infra .' '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;//Guarda el moviento en el historial
                    $this->GestorCaso->historial($user, $ip, 5, $descripcion);
                    if(isset($fotos)){
                        foreach ($fotos as $foto) {
                            if ($foto->row->typeImage == 'File') {
                                $type = $_FILES[$foto->row->nameImage]['type'];
                                $extension = explode("/", $type);
                                $hoy = date("Y-m-d H:i:s");
                                $quitar = array(":", "/");
                                $hoy =str_replace($quitar, "-", $hoy);
                                $result = $this->uploadImageFileGC($foto->row->nameImage, $_FILES, $Folio_infra, $path_carpeta, $foto->row->nameImage . ".png");//Escritura de fotos en la carpeta
                                $result = $this->uploadImageFileGC($foto->row->nameImage, $_FILES, $Folio_infra, $path_carpeta2,$hoy. $foto->row->nameImage .".png");//Escritura de fotos en el respaldo
                            }   
                            if ($foto->row->typeImage == 'Photo') {
                                $result = $this->uploadImagePhotoGC($foto->row->image, $Folio_infra, $path_carpeta, $path_carpeta . $foto->row->nameImage . ".png");//Escritura de fotos en la carpeta
                                
                                if( $_POST['banderafotos']!='true'){
                                    $hoy = date("Y-m-d H:i:s");
                                    $quitar = array(":", "/");
                                    $hoy =str_replace($quitar, "-", $hoy);
                                    $result = $this->uploadImagePhotoGC($foto->row->image, $Folio_infra, $path_carpeta2, $path_carpeta2. $hoy . $foto->row->nameImage . ".png");//Escritura de fotos en el respaldo
                                }
                            }
                        }
                    }
                    $data_p['status'] =  true;


                } else {
                    $data_p['status'] =  false;
                    $data_p['error_message'] = $success_2['error_message'];
                }
            } else {
                $data_p['status'] =  false;
            }

            echo json_encode($data_p);
        } else {
            $data_p['status'] = false;
            $data_p['error_message'] = 'Petición mal realizada, favor de verificar campos.';
            echo json_encode($data_p);
        }
        
    }

    public function updateEntrevistas(){//Funcion para guardar las entrevistas 
        //comprobar los permisos para dejar pasar al módulo
        if ($_SESSION['userdataSIC']->Seguimientos[1] != 1 && $_SESSION['userdataSIC']->Modo_Admin != 1) {
            $data_p['status'] = false;
            $data_p['error_message'] = 'Render Index';
    
            echo json_encode($data_p);
        }
        
        if (isset($_POST['Folio_infra'])) {

            $Folio_infra = $_POST['Folio_infra'];
            $success = true;
            if ($success) {
                $success_2 = $this->GestorCaso->updateEntrevistas($_POST);//Actualiza entrevistas
                    
                if ($success_2){
                    $data_p['status'] =  true;
                    $user = $_SESSION['userdataSIC']->Id_Usuario;
                    $ip = $this->obtenerIp();
                    $quitar = array("'", "\"");
                    $auxsql =str_replace($quitar, "-", $success_2['sqlEjecutados']);
                    $descripcion = 'ACTUALIZACION DE ENTREVISTA: ' . $Folio_infra .' '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                    $this->GestorCaso->historial($user, $ip, 4, $descripcion);//Guarda movimiento en historial
                }else{
                    $data_p['status'] =  false;
                }
            } else {
                    $data_p['status'] =  false;
                    $data_p['error_message'] = $success_2['error_message'];
            }
        } else {
                $data_p['status'] =  false;
            }
        echo json_encode($data_p);    
    }
  /* ----- ----- ----- Funciones para guardar la imagenes en el servidor  ----- ----- ----- */
    public function uploadImageFileGC($name, $file, $alerta, $carpeta, $fileName){
        $type = $file[$name]['type'];
        $extension = explode("/", $type);

        $imageUploadPath = $carpeta . $fileName;
        $allowed_mime_type_arr = array('jpeg', 'png', 'jpg', 'PNG');

        if (!file_exists($carpeta))//si no existe la carpeta se crea
            mkdir($carpeta, 0777, true);

        if (in_array($extension[1], $allowed_mime_type_arr)) {
            $img_temp = $file[$name]['tmp_name'];
            $compressedImg = $this->compressImage($img_temp, $imageUploadPath, 75);
            $band = true;
        } else {
            $band = false;
        }

        return $band;
    }

    public function uploadImagePhotoGC($img, $ficha, $carpeta, $ruta){

        if (!file_exists($carpeta))//si no existe la carpeta se crea
            mkdir($carpeta, 0777, true);

        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);

        file_put_contents($ruta, $image_base64);

        return true;
    }

    public function compressImage($source, $destination, $quality){//Funcion para crear fotos en formato png y jpeg  
        $imgInfo = getimagesize($source);
        $mime = $imgInfo['mime'];

        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($source);
                break;
            case 'image/png':
                $image = imagecreatefrompng($source);
                break;
            default:
                $image = imagecreatefromjpeg($source);
        }

        imagejpeg($image, $destination, $quality);

        return $imgInfo;
    }
    public function tratamiento($entrada){
        $text=$entrada;
        $quitar = array("'", "\"","\\","/","´",);
        $text = str_replace($quitar, ' ', $text);
        return $text;
    }
    /* ----- ----- ----- Funciones para Generar los pdf  ----- ----- ----- */
    public function GeneraFichaEventoPDF(){//GENERA PDF CON LA INFORMACION DE TODO EL EVENTO VEHICULOS CORROBORADOS Y PERSONAS CORROBORADAS ADEMAS DE LAS INFORMACION DE LAS FOTOS
        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }
        if ($_SESSION['userdataSIC']->Modo_Admin == 1 || $_SESSION['userdataSIC']->Seguimientos[2] == 1){
            if (isset($_GET['Folio_infra']) ){
                $Folio_infra= $_GET['Folio_infra'];
                $data = [
                    'principales'   => $this->GestorCaso->getPrincipales($Folio_infra),
                    'evento'   => $this->GestorCaso->getPrincipalesAll($Folio_infra),
                    'detencion'   => $this->GestorCaso->getInfoDetencion($Folio_infra),
                    'delitos'       => $this->GestorCaso->getDelitosC($Folio_infra),
                    'hechos'        => $this->GestorCaso->getHechosC($Folio_infra),
                    'entrevistas'   => $this->GestorCaso->getEntrevistas($Folio_infra),
                    'vehiculos'   => $this->GestorCaso->getVehiculosC($Folio_infra),
                    'personas'   => $this->GestorCaso->getResponsablesC($Folio_infra),
                    'fotos'   => $this->GestorCaso->getFotos($Folio_infra),
                    'usuarios'=>$this->GestorCaso->getUsuarios()
                ];

            }else{
                header("Location: " . base_url . "GestorCasos");
                exit();

            }
            $this->view('system/gestorCasos/fichaEventoView', $data);
            $user = $_SESSION['userdataSIC']->Id_Usuario;
            $ip = $this->obtenerIp();
            $descripcion = 'EXPORTACION DE FICHA DE EVENTO PDF: '.$_SESSION['userdataSIC']->User_Name.' FOLIO INFRA: '.$Folio_infra;
            $this->GestorCaso->historial($user, $ip, 8, $descripcion);//GUARDA EL MOVIMIENTO EN EL HISTORIAL

        }else{
            header("Location: " . base_url . "GestorCasos");
            exit();

        }
    }
    public function GeneraFichaInvolucradoPDF(){//GENERA PDF CON LA INFORMACION DE SOLO LAS PERSONAS CORROBORADAS
        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }
        if ($_SESSION['userdataSIC']->Modo_Admin == 1 || $_SESSION['userdataSIC']->Seguimientos[2] == 1){
            if (isset($_GET['Folio_infra']) ){
                $Folio_infra= $_GET['Folio_infra'];
                $data = [
                    'principales'   => [$Folio_infra],
                    'personas'   => $this->GestorCaso->getResponsablesC($Folio_infra),
                ];

            }else{
                header("Location: " . base_url . "GestorCasos");
                exit();

            }
            $this->view('system/gestorCasos/fichaInvolucradosView', $data);
            $user = $_SESSION['userdataSIC']->Id_Usuario;
            $ip = $this->obtenerIp();
            $descripcion = 'EXPORTACION DE FICHA DE INVOLUCRADOS PDF: '.$_SESSION['userdataSIC']->User_Name.' FOLIO INFRA: '.$Folio_infra;
            $this->GestorCaso->historial($user, $ip, 8, $descripcion);//GUARDA EL MOVIMIENTO EN EL HISTORIAL

        }else{
            header("Location: " . base_url . "GestorCasos");
            exit();

        }
    }
    public function GeneraFichaVehInvolucradoPDF(){//GENERA PDF CON LA INFORMACION DE SOLO LOS VEHICULOS CORROBORADOS
        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }
        if ($_SESSION['userdataSIC']->Modo_Admin == 1 || $_SESSION['userdataSIC']->Seguimientos[2] == 1){
            if (isset($_GET['Folio_infra']) ){
                $Folio_infra= $_GET['Folio_infra'];
                $data = [
                    'principales'   => [$Folio_infra],
                    'vehiculos'   => $this->GestorCaso->getVehiculosC($Folio_infra),
                ];

            }else{
                header("Location: " . base_url . "GestorCasos");
                exit();

            }
            $this->view('system/gestorCasos/fichaVehInvolucradoView', $data);
            $user = $_SESSION['userdataSIC']->Id_Usuario;
            $ip = $this->obtenerIp();
            $descripcion = 'EXPORTACION DE FICHA DE VEHICULOS INVOLUCRADOS PDF: '.$_SESSION['userdataSIC']->User_Name.' FOLIO INFRA: '.$Folio_infra;
            $this->GestorCaso->historial($user, $ip, 8, $descripcion);//GUARDA EL MOVIMIENTO EN EL HISTORIAL

        }else{
            header("Location: " . base_url . "GestorCasos");
            exit();

        }
    }
    /* ----- ----- ----- Funcion para generar las graficas  ----- ----- ----- */
    public function getDatagraficas(){//Genera las graficas conforme la busqueda y los filtros
        if(isset($_REQUEST['filtroActual'])){
            echo json_encode($this->GestorCaso->getDatosGraficas($_REQUEST['cadena'], $_REQUEST['filtroActual']));
        }else{

            echo json_encode($this->GestorCaso->getDatosGraficas($_REQUEST['cadena'], 1));
        }
        
    }
    public function GeneraPDF(){//GENERA PDF CON LA INFORMACION DE TODO EL SEGUIMIENTO  VEHICULOS, PERSONAS ,DOMICILIOS, ANTECEDENTES, FORENSIAS Y REDES SOCIALES
        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }else{
            $data=$this->GestorCaso->permisoVisualizacion($_SESSION['userdataSIC']->User_Name);
            $_SESSION['userdataSIC']->Visualizacion = $data->Visualizacion;
        }
        if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Seguimientos[2] == '1'){
            if (isset($_GET['Id_seguimiento']) ){
                $Id_seguimiento= $_GET['Id_seguimiento'];
                $dataSeguimiento=$this->GestorCaso->getAllPrincipales($Id_seguimiento);
                $Personas=$this->GestorCaso->getPersonas($Id_seguimiento);
                $Vehiculos= $this->GestorCaso->getVehiculos($Id_seguimiento);
                $dataPersona=[];
                $i=0;
                foreach($Personas as $Persona){
                    $dataPersona[$i]= [
                    'datos_persona'=> $Persona,
                    'domicilios'   => $this->GestorCaso->getDomiciliosOneRegister($Persona->Id_Persona,'PERSONA'),
                    'antecedentes' => $this->GestorCaso->getAntecedentesOneRegister($Persona->Id_Persona,'PERSONA'),
                    'forencias'    => $this->GestorCaso->getForenciasOneRegister($Persona->Id_Persona),
                    'redes_sociales'=>$this->GestorCaso->getRedesSocialesOneRegister($Persona->Id_Persona)

                    ];
                    $i++;  
                }
                $conteoPersonas=$i;
                $i=0;
                $dataVehiculo=[];
                foreach($Vehiculos as $Vehiculo){
                    $dataVehiculo[$i]= [
                    'datos_Vehiculo'=> $Vehiculo,
                    'domicilios'   => $this->GestorCaso->getDomiciliosOneRegister($Vehiculo->Id_Vehiculo,'VEHICULO'),
                    'antecedentes' => $this->GestorCaso->getAntecedentesOneRegister($Vehiculo->Id_Vehiculo,'VEHICULO')
                    ];
                    $i++;  
                }
                $dataEventos=[];
                $i=0;
                if($dataSeguimiento['eventos']!=[]){
                    $Eventos=$dataSeguimiento['eventos'];
                    foreach($Eventos as $Evento){
                        $dataEventos[$i]= [
                            'principales'   => $Evento,
                            'evento'   => $this->GestorCaso->getPrincipalesAll($Evento->Folio_infra),
                            'detencion'   => $this->GestorCaso->getInfoDetencion($Evento->Folio_infra),
                            'delitos'       => $this->GestorCaso->getDelitosC($Evento->Folio_infra),
                            'hechos'        => $this->GestorCaso->getHechosC($Evento->Folio_infra),
                            'entrevistas'   => $this->GestorCaso->getEntrevistas($Evento->Folio_infra),
                            'vehiculos'   => $this->GestorCaso->getVehiculosCorroborado($Evento->Folio_infra),
                            'personas'   => $this->GestorCaso->getResponsablesCorroborado($Evento->Folio_infra),
                            'fotos'   => $this->GestorCaso->getFotos($Evento->Folio_infra)
                        ];
                        $i++;
                    }

                }

                $info_Seguimiento=[
                    'datos_seguimiento'=> $dataSeguimiento,
                    'ConteoPersonas'=>$conteoPersonas,
                    'datos_vehiculos'   => $dataVehiculo,
                    'datos_personas' => $dataPersona,
                    'datos_eventos' => $dataEventos,
                    'datos_personas_entrevistadas_no'=> [],
                    'datos_personas_entrevistadas_si'=> []
                ];
            }else{
                header("Location: " . base_url . "GestorCasos");
                exit();
    
            }
            $this->view('system/seguimientos/fichaseguimientoView', $info_Seguimiento);
            $user = $_SESSION['userdataSIC']->Id_Usuario;
            $ip = $this->obtenerIp();
            $descripcion = 'EXPORTACION DE FICHA DE SEGUIMIENTO PDF: '.$_SESSION['userdataSIC']->User_Name.' SEGUIMIENTO: '.$Id_Seguimiento;
            $this->GestorCaso->historial($user, $ip, 29, $descripcion);//GUARDA EL MOVIMIENTO EN EL HISTORIAL
    
        }else{
            header("Location: " . base_url . "GestorCasos");
            exit();
    
        }
    }
}
?>