<?php

class Puntos extends Controller{

    /*controlador del modulo Puntos
    NOMENCLATURA DE MOVIMIENTOS EN EL HISTORIAL 
        39.-INSERCION DE UN NUEVO PUNTO
        40.-VER INFORMACION DE UN PUNTO
        41.-ACTUALIZACION DE INFORMACION PUNTO
        42.-CONSULTA BUSQUEDA EN MODULO PUNTOS
        43.-EXPORTACION DE INFORMACION MODULO PUNTOS
    */
    public $Catalogo;
    public $Punto;
    public $numColumnsPUN; //número de columnas por cada filtro
    public $FV;

    public function __construct(){
        $this->Catalogo = $this->model('Catalogo');
        $this->Punto = $this->model('Punto');
        $this->numColumnsPUN = [10, 9, 8];  //se inicializa el número de columns por cada filtro
        $this->FV = new FormValidator();
    }

    public function index(){
        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }
  
        $data = [
            'titulo'    => 'AURA | Puntos Identificados',
            'extra_css' => '<link rel="stylesheet" href="' . base_url . 'public/css/system/puntos/index.css">',
            'extra_js'  => '<script src="' . base_url . 'public/js/system/puntos/index.js"></script>'.
                            '<script src="https://cdn.jsdelivr.net/npm/chart.js@3.3.2/dist/chart.min.js"></script>'
        ];

        if (isset($_GET['filtro']) && is_numeric($_GET['filtro']) && $_GET['filtro'] >= MIN_FILTRO_PUN && $_GET['filtro'] <= MAX_FILTRO_PUN) { //numero de filtro
            $filtro = $_GET['filtro'];
        } else {
            $filtro = 1;
        }
        //PROCESAMIENTO DE LAS COLUMNAS 
        $this->setColumnsSession($filtro);
        $data['columns_PUN'] = $_SESSION['userdataSIC']->columns_PUN;

        //PROCESAMIENTO DE RANGO DE FOLIOS
        if (isset($_POST['rango_inicio']) && isset($_POST['rango_fin'])) {
            $_SESSION['userdataSIC']->rango_inicio_pun = $_POST['rango_inicio'];
            $_SESSION['userdataSIC']->rango_fin_pun = $_POST['rango_fin'];
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

        $where_sentence = $this->Punto->generateFromWhereSentence($cadena, $filtro,"");
        $extra_cad = ($cadena != "") ? ("&cadena=" . $cadena) : ""; //para links conforme a búsqueda
        $no_of_records_per_page = NUM_MAX_REG_PAGE; //total de registros por pagination
        $offset = ($numPage - 1) * $no_of_records_per_page; // desplazamiento conforme a la pagina
        $results_rows_pages = $this->Punto->getTotalPages($no_of_records_per_page, $where_sentence);   //total de páginas de acuerdo a la info de la DB
        $total_pages = $results_rows_pages['total_pages'];
        if ($numPage > $total_pages) {
            $numPage = 1;
            $offset = ($numPage - 1) * $no_of_records_per_page;
        } //seguridad si ocurre un error por url     
        $rows_Puntos = $this->Punto->getDataCurrentPage($offset, $no_of_records_per_page, $where_sentence);    //se obtiene la información de la página actual
        //guardamos la tabulacion de la información para la vista

        $data['infoTable'] = $this->generarInfoTable($rows_Puntos, $filtro);
        $data['links'] = $this->generarLinks($numPage, $total_pages, $extra_cad, $filtro);
        $data['total_rows'] = $results_rows_pages['total_rows'];
        $data['filtroActual'] = $filtro;
        $data['dropdownColumns'] = $this->generateDropdownColumns($filtro);

        $data['filtroActual'] = $filtro;

        switch ($filtro) {
            case '1':
                $data['filtroNombre'] = "Puntos";
                break;
        }
        $this->view('templates/header', $data);
        $this->view('system/puntos/puntosView', $data);
        $this->view('templates/footer', $data);
    }
    public function generarInfoTable($rows, $filtro = 1){
        $permisos_Ver = ($_SESSION['userdataSIC']->Puntos[2] == 1|| $_SESSION['userdataSIC']->Modo_Admin == 1) ? '' : 'mi_hide';
        $permisos_Editar = ($_SESSION['userdataSIC']->Puntos[1] == 1 || $_SESSION['userdataSIC']->Modo_Admin == 1) ? '' : 'mi_hide';//desaparece los botones de acuerdo a los permisos
        $permisos_Exportar= ($_SESSION['userdataSIC']->Puntos[0] == 1|| $_SESSION['userdataSIC']->Modo_Admin == 1) ? '' : 'mi_hide';
        //se genera la tabulacion de la informacion por backend
        $infoTable['header'] = "";
        $infoTable['body'] = "";
        switch ($filtro) {
            case '1': //General de todos los casos
                $infoTable['header'] .= '
                        <th class="column1">Folio Punto</th>
                        <th class="column2">Fuente de Información</th>
                        <th class="column3">Identificador</th>
                        <th class="column4">Alias del Distribuidor</th>
                        <th class="column5">Remision Asociada</th>
                        <th class="column6">Nombre del Detenido</th>
                        <th class="column7">Narrativa del Detenido</th>
                        <th class="column8">Colonia</th>
                        <th class="column9">Calle</th>
                        <th class="column10">Zona y Vector</th> ';
                foreach ($rows as $row) {

                        $infoTable['body'] .= '<tr id="tr' . $row->Id_Punto . '">';
                        $infoTable['body'] .= ' <td class="column1">' . $row->Id_Punto . '</td>
                                                <td class="column2">' . $row->Fuente_Info .'</td>
                                                <td class="column3">' . $row->Identificador . '</td>
                                                <td class="column4">' . $row->Distribuidor. '</td>
                                                <td class="column5">' . $row->Remision . '</td>
                                                <td class="column6">' . $row->Nombre_Detenido  . '</td>
                                                <td class="column7">' . $row->Narrativa . '</td>
                                                <td class="column8">' . mb_strtoupper($row->Colonia) . '</td>
                                                <td class="column9">' . mb_strtoupper($row->Calle) . '</td>
                                                <td class="column10">' . mb_strtoupper($row->Zona).' '.mb_strtoupper($row->Vector) . '</td>
                            ';

                    
                    if ($row->Fecha_Punto != '') {                     
                        if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Red[0] == '1'){
                           
                            $infoTable['body'] .= '<td class="d-flex">
                                                <a class="myLinks ' . $permisos_Editar . '"  data-toggle="tooltip" data-placement="right" title="Editar registro" href="' . base_url . 'Puntos/editarPunto/?Id_Punto=' . $row->Id_Punto . '">
                                                    <i class="material-icons">edit</i>
                                                </a>
                                                <a target="_blank" class="myLinks ' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Ver registro" href="' . base_url . 'Puntos/verPunto/?Id_Punto=' . $row->Id_Punto . '">
                                                    <i class="material-icons">visibility</i>
                                                </a>
                                                <a target="_blank" class="myLinks ' . $permisos_Exportar . '" data-toggle="tooltip" data-placement="right" title="Generar PDF Ficha del Punto" href="' . base_url . 'Puntos/GeneraFichaPunto/?Id_Punto=' . $row->Id_Punto . '">
                                                    <i class="material-icons">file_present</i>
                                                </a></td>';
                        }else{
                            $infoTable['body'] .= '<td class="d-flex"></td>';
                        }
                            
                    }
                    $infoTable['body'] .= '</tr>';
                }
                break;
      
        }
        $infoTable['header'] .= '<th >Operaciones</th>';
        return $infoTable;
    }
    //función que filtra las columnas deseadas por el usuario
    public function generateDropdownColumns($filtro = 1){
        $dropDownColumn = '';
        //generación de dropdown dependiendo del filtro
        switch ($filtro) {
            case '1':
                $campos = ['Folio Punto','Fuente de Información', 'Identificador', 'Alias del Distribuidor', 'Remision Asociada','Nombre del Detenido','Narrativa del Detenido', 'Colonia', 'Calle','Zona y Vector'];
                break;
        }
        //gestión de cada columna
        $ind = 1;
        foreach ($campos as $campo) {
            $checked = ($_SESSION['userdataSIC']->columns_PUN['column' . $ind] == 'show') ? 'checked' : '';
            $dropDownColumn .= ' <div class="form-check">
                                    <input class="form-check-input checkColumns" type="checkbox" value="' . $_SESSION['userdataSIC']->columns_PUN['column' . $ind] . '" onchange="hideShowColumn(this.id);" id="column' . $ind . '" ' . $checked . '>
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
    public function generarLinks($numPage, $total_pages, $extra_cad = "", $filtro = 1){
        //$extra_cad sirve para determinar la paginacion conforme a si se realizó una busqueda
        //Creación de links para la paginacion
        $links = "";

        //FLECHA IZQ (PREV PAGINATION)
        if ($numPage > 1) {
            $links .= '<li class="page-item">
                            <a class="page-link" href=" ' . base_url . 'Puntos/index/?numPage=1' . $extra_cad . '&filtro=' . $filtro . ' " data-toggle="tooltip" data-placement="top" title="Primera página">
                                <i class="material-icons">first_page</i>
                            </a>
                        </li>';
            $links .= '<li class="page-item">
                            <a class="page-link" href=" ' . base_url . 'Puntos/index/?numPage=' . ($numPage - 1) . $extra_cad . '&filtro=' . $filtro . ' " data-toggle="tooltip" data-placement="top" title="Página anterior">
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
                                <a class="page-link" href=" ' . base_url . 'Puntos/index/?numPage=' . ($ind) . $extra_cad . '&filtro=' . $filtro . ' ">
                                    ' . ($ind) . '
                                </a>
                            </li>';
            }
        }

        //FLECHA DERECHA (NEXT PAGINATION)
        if ($numPage < $total_pages) {

            $links .= '<li class="page-item">
                            <a class="page-link" href=" ' . base_url . 'Puntos/index/?numPage=' . ($numPage + 1) . $extra_cad . '&filtro=' . $filtro . ' " data-toggle="tooltip" data-placement="top" title="Siguiente página">
                            <i class="material-icons">navigate_next</i>
                            </a>
                        </li>';
            $links .= '<li class="page-item">
                            <a class="page-link" href=" ' . base_url . 'Puntos/index/?numPage=' . ($total_pages) . $extra_cad . '&filtro=' . $filtro . ' " data-toggle="tooltip" data-placement="top" title="Última página">
                            <i class="material-icons">last_page</i>
                            </a>
                        </li>';
        }

        return $links;
    }
    /* ----------------------------------------FUNCIONES DE FILTROS ------------------------------------- */
    //función para checar los cambios de filtro y poder asignar los valores correspondientes de las columnas a la session
    public function setColumnsSession($filtroActual = 1){
        //si el filtro existe y esta dentro de los parámetros continua
        if (isset($_SESSION['userdataSIC']->filtro_PUN) && $_SESSION['userdataSIC']->filtro_PUN >= MIN_FILTRO_PUN && $_SESSION['userdataSIC']->filtro_PUN <= MAX_FILTRO_PUN) {
            //si cambia el filtro se procde a cambiar los valores de las columnas que contiene el filtro seleccionado
            if ($_SESSION['userdataSIC']->filtro_PUN != $filtroActual) {
                $_SESSION['userdataSIC']->filtro_PUN = $filtroActual;
                unset($_SESSION['userdataSIC']->columns_PUN); 
                for ($i = 0; $i < $this->numColumnsPUN[$_SESSION['userdataSIC']->filtro_PUN - 1]; $i++)
                    $_SESSION['userdataSIC']->columns_PUN['column' . ($i + 1)] = 'show';
            }
        } else { //si no existe el filtro entonces se inicializa con el primero por default
            $_SESSION['userdataSIC']->filtro_PUN = $filtroActual;
            unset($_SESSION['userdataSIC']->columns_PUN);
            for ($i = 0; $i < $this->numColumnsPUN[$_SESSION['userdataSIC']->filtro_PUN - 1]; $i++)
                $_SESSION['userdataSIC']->columns_PUN['column' . ($i + 1)] = 'show';
        }
    }
    //función fetch que actualiza los valores de las columnas para la session
    public function setColumnFetch(){
        if (isset($_POST['columName']) && isset($_POST['valueColumn'])) {
            $_SESSION['userdataSIC']->columns_PUN[$_POST['columName']] = $_POST['valueColumn'];
            echo json_encode("ok");
        } else {
            header("Location: " . base_url . "Puntos");
            exit();
        }
    }

    public function nuevopunto(){
        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }
        $datos_prim = [
            'zonas' => $this->getZona(),
            'identificadores'=> $this->getIdentificador()
        ];
  
        $data = [
            'titulo'    => 'AURA | Punto Nuevo',
            'extra_css' => '<link rel="stylesheet" href="' . base_url . 'public/css/system/puntos/index.css">',
            'extra_js'  => '<script src="' . base_url . 'public/js/system/puntos/nuevopunto.js"></script>'.
                            '<script src="https://api.mapbox.com/mapbox-gl-js/v2.8.1/mapbox-gl.js"></script>'.
                            '<link href="https://api.mapbox.com/mapbox-gl-js/v2.8.1/mapbox-gl.css" rel="stylesheet" />'.
                            '<script src="'.base_url.'public/js/system/puntos/principales_mapbox.js"></script>'.
                            '<script src="https://cdn.jsdelivr.net/npm/chart.js@3.3.2/dist/chart.min.js"></script>',
            'datos_prim' => $datos_prim
        ];
        $this->view('templates/header', $data);
        $this->view('system/puntos/nuevoPunto', $data);
        $this->view('templates/footer', $data);

        
    }
    public function editarPunto(){
        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }
        $datos_prim = [
            'zonas' => $this->getZona(),
            'identificadores'=> $this->getIdentificador()
        ];
  
        $data = [
            'titulo'    => 'AURA | Edicion de Punto',
            'extra_css' => '<link rel="stylesheet" href="' . base_url . 'public/css/system/puntos/index.css">',
            'extra_js'  => '<script src="' . base_url . 'public/js/system/puntos/getInformacion/getPunto.js"></script>'.
                            '<script src="' . base_url . 'public/js/system/puntos/editInformacion/editarPunto.js"></script>'.
                            '<script src="https://api.mapbox.com/mapbox-gl-js/v2.8.1/mapbox-gl.js"></script>'.
                            '<link href="https://api.mapbox.com/mapbox-gl-js/v2.8.1/mapbox-gl.css" rel="stylesheet" />'.
                            '<script src="'.base_url.'public/js/system/puntos/principales_mapbox.js"></script>'.
                            '<script src="https://cdn.jsdelivr.net/npm/chart.js@3.3.2/dist/chart.min.js"></script>',
            'datos_prim' => $datos_prim
        ];

        //'<script src="' . base_url . 'public/js/system/puntos/nuevopunto.js"></script>'.
        $this->view('templates/header', $data);
        $this->view('system/puntos/editpunto', $data);
        $this->view('templates/footer', $data);        
    }

    public function verPunto(){
        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }
  
        $data = [
            'titulo'    => 'AURA | Ver Punto',
            'extra_css' => '<link rel="stylesheet" href="' . base_url . 'public/css/system/puntos/index.css">',
            'extra_js'  => '<script src="' . base_url . 'public/js/system/puntos/getInformacion/getPuntoOnlyRead.js"></script>'
        ];

        //'<script src="' . base_url . 'public/js/system/puntos/nuevopunto.js"></script>'.
        $this->view('templates/header', $data);
        $this->view('system/puntos/verpunto', $data);
        $this->view('templates/footer', $data);        
    }

    public function buscarPorCadena(){//Funcion para buscar lo que le escribes en el panel de texto buscar

        if (isset($_POST['cadena'])) {//Comprueba si existe una cadena para buscar
            $cadena = trim($_POST['cadena']);
            $filtroActual = trim($_POST['filtroActual']);

            $results = $this->Punto->getPuntosByCadena($cadena, $filtroActual);//Devuelve los datos de la cadena consultada
            if (strlen($cadena) > 0) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $ip = $this->obtenerIp();
                $descripcion = 'CONSULTA PUNTOS: ' . $cadena .' '.$_SESSION['userdataSIC']->User_Name;
                $this->Punto->historial($user, $ip, 42, $descripcion);//Escribe en el historial el movimiento
            }
            $extra_cad = ($cadena != "") ? ("&cadena=" . $cadena) : ""; //para links conforme a búsqueda

            $dataReturn['infoTable'] = $this->generarInfoTable($results['rows_Pun'], $filtroActual);
            $dataReturn['links'] = $this->generarLinks($results['numPage'], $results['total_pages'], $extra_cad, $filtroActual);
            $dataReturn['export_links'] = $this->generarExportLinks($extra_cad, $filtroActual);
            $dataReturn['total_rows'] = "Total registros: " . $results['total_rows'];
            $dataReturn['dropdownColumns'] = $this->generateDropdownColumns($filtroActual);


            echo json_encode($dataReturn);
        } else {
            header("Location: " . base_url . "Puntos");
            exit();
        }
    }
    public function generarExportLinks($extra_cad = "", $filtro = 1){//Funcion para exportar la informacion 
        if ($extra_cad != "") {
            $dataReturn['csv'] =  base_url . 'Puntos/exportarInfo/?tipo_export=CSV' . $extra_cad . '&filtroActual=' . $filtro;
            $dataReturn['excel'] =  base_url . 'Puntos/exportarInfo/?tipo_export=EXCEL' . $extra_cad . '&filtroActual=' . $filtro;
            $dataReturn['pdf'] =  base_url . 'Puntos/exportarInfo/?tipo_export=PDF' . $extra_cad . '&filtroActual=' . $filtro;
        } else {
            $dataReturn['csv'] =  base_url . 'Puntos/exportarInfo/?tipo_export=CSV' . $extra_cad . '&filtroActual=' . $filtro;
            $dataReturn['excel'] =  base_url . 'Puntos/exportarInfo/?tipo_export=EXCEL' . $extra_cad . '&filtroActual=' . $filtro;
            $dataReturn['pdf'] =  base_url . 'Puntos/exportarInfo/?tipo_export=PDF' . $extra_cad . '&filtroActual=' . $filtro;
        }
        return $dataReturn;
    }
    public function exportarInfo(){//Funcion para Exportar informacion en excel

		if (!isset($_REQUEST['tipo_export'])) {
            header("Location: " . base_url . "Puntos");
            exit();
		}

		$from_where_sentence = "";
        $filtroActual =  $_REQUEST['filtroActual'];

        if (isset($_REQUEST['cadena'])){//Verifica si existe una Cadena para consulta
            $from_where_sentence = $this->Punto->generateFromWhereSentence($_REQUEST['cadena'], $filtroActual);//excel con consulta
        }else{
            $from_where_sentence = $this->Punto->generateFromWhereSentence("",$filtroActual);//Excel sin consulta
        }
		$tipo_export = $_REQUEST['tipo_export'];;
		if ($tipo_export == 'EXCEL') {
			//se realiza exportacion de usuarios a EXCEL
			$cat_rows = $this->Punto->getAllInfoPuntoByCadena($from_where_sentence);
			switch ($filtroActual) {
				case '1':
                    //Genera nombre de archivo junto con los datos y los encabezasdos 
					$filename = "Vista_General_Puntos";
					$csv_data="ID DE PUNTO AURA ,REMISION ,FUENTE DE INFORMACION ,NOMBRE DEL DETENIDO ,FECHA DE RECEPCION DE INFORMACION ,IDENTIFICADOR ,NARRATIVA DEL DETENIDO ,DATOS DEL PUNTO,ESTATUS DEL PUNTO ,COORDENADA Y ,COORDENADA X ,ZONA ,VECTOR ,DESCRIPCION ADICIONAL ,COLONIA ,UBICACION COMPLETA ,ATENDIDO POR ,DISTRIBUIDOR ,GRUPO DELICITVO EN EL QUE OPERA\n";
					foreach ($cat_rows as $row) {

						$csv_data.= mb_strtoupper($row->Id_Punto).",\"".
                                    mb_strtoupper($row->Remision)."\",\"".
                                    mb_strtoupper($row->Fuente_Info)."\",\"".
                                    $this->tratamiento(mb_strtoupper($row->Nombre_Detenido))."\",\"".
                                    mb_strtoupper($row->Fecha_Punto)."\",\"".
                                    mb_strtoupper($row->Identificador)."\",\"".
                                    $this->tratamiento(mb_strtoupper($row->Narrativa))."\",\"".
                                    $this->tratamiento(mb_strtoupper($row->Descripcion_Datoconcat))."\",\"".
                                    mb_strtoupper($row->Estatus_Punto)."\",\"".
                                    $row->CoordY."\",\"".
                                    $row->CoordX."\",\"".
                                    mb_strtoupper($row->Zona)."\",\"".
                                    mb_strtoupper($row->Vector)."\",\"".
                                    mb_strtoupper($row->Descripcion_Adicional)."\",\"".
                                    mb_strtoupper($row->Colonia)."\",\"".
                                    mb_strtoupper($row->Colonia.' '.$row->Calle.' '.$row->Calle2.' '.$row->NoExt.' '.$row->CP)."\",\"".
                                    $this->tratamiento(mb_strtoupper($row->Atendido_Por))."\",\"".
                                    $this->tratamiento(mb_strtoupper($row->Distribuidor))."\",\"".
									$this->tratamiento(mb_strtoupper($row->Grupo_OP))."\"\n";
					}
				break;
               
            }
			//se genera el archivo csv o excel
			$csv_data = utf8_decode($csv_data); //escribir información con formato utf8 por algún acento
			header("Content-Description: File Transfer");
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=".$filename.".csv");
			echo $csv_data;
			//header("Location: ".base_url."UsersAdmin");
            $user = $_SESSION['userdataSIC']->Id_Usuario;
            $ip = $this->obtenerIp();
            $descripcion = 'EXPORTACION DE EXCEL Puntos: ' . $filename .' '.$_SESSION['userdataSIC']->User_Name;
            $this->Punto->historial($user, $ip, 43, $descripcion);//GUarda movimiento en historial

		}else {
			header("Location: ".base_url."Puntos");
		}
	}
    public function GeneraFichaPunto(){

        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }

        if ($_SESSION['userdataSIC']->Modo_Admin == 1 || $_SESSION['userdataSIC']->Red[0] == 1){
            if (isset($_GET['Id_Punto']) ){
                $Id_Punto = $_GET['Id_Punto'];
                $data = [
                    'principales'   => $this->Punto->getInfoPunto($Id_Punto),
                    'datos'   => $this->Punto->getDatosPunto($Id_Punto)
                ];


                $this->view('system/puntos/fichaPdfPuntos', $data);
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $ip = $this->obtenerIp();
                $descripcion = 'EXPORTACION DE FICHA DE PUNTO PDF: '.$_SESSION['userdataSIC']->User_Name.' FOLIO PUNTO AURA: '.$Id_Punto;
                $this->Punto->historial($user, $ip, 43, $descripcion);//GUARDA EL MOVIMIENTO EN EL HISTORIAL
    
            }else{
                header("Location: " . base_url . "Puntos");
                exit();
    
            }
        }
    }
    public function tratamiento($entrada){
        $text=$entrada;
        $quitar = array("'", "\"","\\","/","´",);
        $text = str_replace($quitar, ' ', $text);
        return $text;
    }
    public function exportarInfoImagenes(){//Funcion para Exportar informacion en excel

		if (!isset($_REQUEST['tipo_export'])) {
            header("Location: " . base_url . "Puntos");
            exit();
		}

		$from_where_sentence = "";
        $filtroActual =  $_REQUEST['filtroActual'];

        if (isset($_REQUEST['cadena'])){//Verifica si existe una Cadena para consulta
            $from_where_sentence = $this->Punto->generateFromWhereSentence($_REQUEST['cadena'], $filtroActual);//excel con consulta
        }else{
            $from_where_sentence = $this->Punto->generateFromWhereSentence("",$filtroActual);//Excel sin consulta
        }
		$tipo_export = $_REQUEST['tipo_export'];;
		if ($tipo_export == 'EXCEL') {
			//se realiza exportacion de usuarios a EXCEL
			$cat_rows = $this->Punto->getAllInfoPuntoByCadena($from_where_sentence);
			switch ($filtroActual) {
				case '1':
                    header("Content-Description: File Transfer");
                    header("Content-Type: application/vnd.ms-excel"); // Tipo MIME para Excel
                    header("Content-Disposition: attachment; filename=Vista_General_Puntos.xls"); // Usa .xls
                    header("Cache-Control: must-revalidate");
                    header("Pragma: public");
                    header("Expires: 0");
                    
                    $output = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
                    <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                        <style>
                            table { border-collapse: collapse; }
                            th, td { border: 1px solid black; padding: 5px; }
                        </style>
                    </head>
                    <body>
                        <table>
                            <tr>
                                <th>ID DE PUNTO AURA</th>
                                <th>REMISION</th>
                                <th>FUENTE DE INFORMACION</th>
                                <th>NOMBRE DEL DETENIDO</th>
                                <th>FECHA DE RECEPCION DE INFORMACION</th>
                                <th>IDENTIFICADOR</th>
                                <th>NARRATIVA DEL DETENIDO</th>
                                <th>DATOS DEL PUNTO</th>
                                <th>ESTATUS DEL PUNTO</th>
                                <th>COORDENADA Y</th>
                                <th>COORDENADA X</th>
                                <th>ZONA</th>
                                <th>VECTOR</th>
                                <th>DESCRIPCION ADICIONAL</th>
                                <th>COLONIA</th>
                                <th>UBICACION COMPLETA</th>
                                <th>ATENDIDO POR</th>
                                <th>DISTRIBUIDOR</th>
                                <th>GRUPO DELICITVO EN EL QUE OPERA</th>
                                <th>IMAGEN</th>
                            </tr>';
                    
                    foreach ($cat_rows as $row) {
                        

                        $imagePath = 'ruta/a/tu/imagen.jpg'; // Cambia esto por la ruta correcta de la imagen

                        $imageUrl =  base_url."public/files/Puntos/" . $row->Id_Punto . "/FotoUbi".$row->Id_Punto.".png";
                            if ($this->imageExists($imageUrl)) {
                               
                                $imagePath = $imageUrl;
                            } else {
                                $imagePath = '';
                            }
                        $output .= '<tr>
                            <td>' . mb_strtoupper($row->Id_Punto) . '</td>
                            <td>' . mb_strtoupper($row->Remision) . '</td>
                            <td>' . mb_strtoupper($row->Fuente_Info) . '</td>
                            <td>' . mb_strtoupper($row->Nombre_Detenido) . '</td>
                            <td>' . mb_strtoupper($row->Fecha_Punto) . '</td>
                            <td>' . mb_strtoupper($row->Identificador) . '</td>
                            <td>' . mb_strtoupper($row->Narrativa) . '</td>
                            <td>' . mb_strtoupper($row->Descripcion_Datoconcat) . '</td>
                            <td>' . mb_strtoupper($row->Estatus_Punto) . '</td>
                            <td>' . $row->CoordY . '</td>
                            <td>' . $row->CoordX . '</td>
                            <td>' . mb_strtoupper($row->Zona) . '</td>
                            <td>' . mb_strtoupper($row->Vector) . '</td>
                            <td>' . mb_strtoupper($row->Descripcion_Adicional) . '</td>
                            <td>' . mb_strtoupper($row->Colonia) . '</td>
                            <td>' . mb_strtoupper($row->Colonia . ' ' . $row->Calle . ' ' . $row->Calle2 . ' ' . $row->NoExt . ' ' . $row->CP) . '</td>
                            <td>' . mb_strtoupper($row->Atendido_Por) . '</td>
                            <td>' . mb_strtoupper($row->Distribuidor) . '</td>
                            <td>' . mb_strtoupper($row->Grupo_OP) . '</td>
                            <td><img src="'.$imagePath.'" width="50" height="50" /></td> 
                        </tr>';
                    }
                    
                    $output .= '</table>
                    </body>
                    </html>';
                    
                    echo $output;
                    $user = $_SESSION['userdataSIC']->Id_Usuario;
                    $ip = $this->obtenerIp();
                    $descripcion = 'EXPORTACION DE EXCEL PUNTOS: Vista_General_Puntos '.$_SESSION['userdataSIC']->User_Name;
                    $this->Punto->historial($user, $ip, 43, $descripcion);//GUarda movimiento en historial
				break;
               
            }



		}else {
			header("Location: ".base_url."Puntos");
		}
	}
    public function imageExists($imgUrl) {
        // Verifica que la URL no esté vacía
        if (empty($imgUrl)) {
            return false;
        }
    
        // Verifica si la URL es accesible
        $headers = get_headers($imgUrl);
        
        // Comprueba si la respuesta es 200 (OK)
        if (strpos($headers[0], '200') !== false) {
            return true;
        } else {
            return false;
        }
    }

    public function getInfoPunto(){//FUNCION QUE OBTIENE LOS DATOS PRINCIPALES DEL PUNTO
        if (isset($_POST['Id_Punto'])) {
            $Id_Punto = $_POST['Id_Punto'];
            $data = $this->Punto->getInfoPunto($Id_Punto);
            $ip = $this->obtenerIp();
            $user = $_SESSION['userdataSIC']->Id_Usuario;
            $descripcion = 'VER INFORMACION DEL PUNTO: '.$_SESSION['userdataSIC']->User_Name.' '.$Id_Punto;
            $success_3=$this->Punto->historial($user, $ip, 40, $descripcion);//GUARDA EL MOVIMIENTO EN EL HISTORIAL
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Puntos");
            exit();
        }
    }
    public function getDatosPunto(){//FUNCION QUE OBTIENE LOS DATOS PRINCIPALES DEL PUNTO
        if (isset($_POST['Id_Punto'])) {
            $Id_Punto = $_POST['Id_Punto'];
            $data = $this->Punto->getDatosPunto($Id_Punto);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Puntos");
            exit();
        }
    }

    public function getAllVector(){
        $data =  $this->Catalogo->getAllVector();
        echo json_encode($data);
    }
    /* ----------------------------------------FUNCIONES DE PRECARGA Y OBTENCION DE INFORMACION DE CATALOGOS -------------------------------------*/
    // DATOS DE CATALOGOS
    public function getZona(){
        $data = $this->Catalogo->getZonaSector("POLICIA");
        return $data;
    }
    public function getIdentificador(){
        $data = $this->Catalogo->getSimpleCatalogo('Identificador','catalogo_identificador_puntos');
        return $data;
    }
    public function getRemisiones(){//FUNCION QUE OBTINE EL CATALOGO DE LAS REMISIONES
        $data = $this->Punto->getRemisiones();
        echo json_encode($data);
    }

    public function getInfoRemision(){//FUNCION QUE OBTIENE LA INFORMACION DE LA REMISION CONSULTADO EN SARAI
        if (isset($_POST['No_Remision'])) {
            $No_Remision = $_POST['No_Remision'];
            $data = $this->Punto->getInfoRemision($No_Remision);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Puntos");
            exit();
        }
    }
    //funcion para borrar variable sesión para filtro de rangos de fechas
    public function removeRangosFechasSesion(){
        if (isset($_REQUEST['filtroActual'])) {
            unset($_SESSION['userdataSIC']->rango_inicio_pun);
            unset($_SESSION['userdataSIC']->rango_fin_pun);

            header("Location: " . base_url . "Puntos/index/?filtro=" . $_REQUEST['filtroActual']);
            exit();
        }
    }

    /* ----------------------------------------INSERCION DE PUNTOS ------------------------------------- */
    public function insertPuntoFetch(){
        //comprobar los permisos para dejar pasar al módulo
        if($_SESSION['userdataSIC']->Modo_Admin != 1 && $_SESSION['userdataSIC']->Red[0] != 1 ){
            $data_p['status'] = false;
            $data_p['error_message'] = 'Render Index';
            echo json_encode($data_p);
        }
        
        $success_2 = $this->Punto->insertNuevoPunto($_POST);//Inserta la informacion
        if ($success_2['status']) {
            $user = $_SESSION['userdataSIC']->Id_Usuario;
            $data_p['status'] =  true;
            $data_p['Id_Punto'] =  $success_2['Id_Punto'];
            $ip = $this->obtenerIp();
            $quitar = array("'", "\"");
            $auxsql = str_replace($quitar, "-", $success_2['sqlEjecutados']);
            $descripcion = 'INSERCION DEL PUNTO: '.$success_2['Id_Punto'].' '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
            $success_3 = $this->Punto->historial($user, $ip, 39, $descripcion);//Guarda en el historial el movimiento
   
        } else {
            $user = $_SESSION['userdataSIC']->Id_Usuario;
            $ip = $this->obtenerIp();
            $data_p['status'] =  false;
            $data_p['error_message'] = $success_2['error_message'];
            $data_p['error_sql'] = $success_2['error_sql'];
            $descripcion = $data_p['error_message'].'|||'.$data_p['error_sql'];
            $this->Escribelogtxt($user, $ip, $descripcion);//Si ubo un error guarda la informacion
            $this->Punto->logs($user, $ip, $descripcion);//Si ubo un error guarda la informacion
        }
       
        echo json_encode($data_p);
    }
    /* ----------------------------------------ACTUALIZACION DE FORENSIAS SEGUIMIENTO------------------------------------- */
    public function UpdatePuntoFetch(){
        //comprobar los permisos para dejar pasar al módulo
        if(($_SESSION['userdataSIC']->Modo_Admin != 1 &&  $_SESSION['userdataSIC']->Red[1] != 1) ){
            $data_p['status'] = false;
            $data_p['error_message'] = 'Render Index';
            echo json_encode($data_p);
        }

        $success = $this->Punto->UpdatePuntoFetch($_POST);
        if ($success['status']) {
            $user = $_SESSION['userdataSIC']->Id_Usuario;
            $data_p['status'] =  true;
            $ip = $this->obtenerIp();
            $quitar = array("'", "\"");
            $auxsql =str_replace($quitar, "-", $success['sqlEjecutados']);
            $descripcion = 'ACTUALIZACION DE PUNTO: '.$_POST['Id_Punto'].' '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
            $this->Punto->historial($user, $ip, 41, $descripcion);//Guarda en el historial el movimiento
            if(isset($_POST['DatosUbicacion_table'])){
                $this->GuardarFotosUbicaciones($_POST['Id_Punto']);
            }
            $this->GuardarFotosDatos($_POST['Id_Punto']);
            
            
        } else {
            $data_p['status'] =  false;
            $data_p['error_message'] = $success['error_message'];
            $data_p['error_sql'] = $success['error_sql'];
        }
        echo json_encode($data_p);
          
    }

    public function GuardarFotosDatos($Id_Punto){

        $path_carpetaRaiz = BASE_PATH . "public/files/Puntos/" . $Id_Punto . "/";
        if($_POST['Path_Img_Google']!='SD'){
            if($_POST['typeImageGoogle']=='File'){
                $result = $this->uploadImageFile('FotoMaps', $_FILES,$path_carpetaRaiz, $_POST['Path_Img_Google']);//Escritura de fotos en la carpeta
            }else{
                $result = $this->uploadImagePhoto($_POST['Img_64_Google'],$path_carpetaRaiz,$path_carpetaRaiz.$_POST['Path_Img_Google']);//Escritura de fotos en la carpeta
            }
        }
        if($_POST['Path_Img']!='SD'){
            if($_POST['typeImage']=='File'){
                $result = $this->uploadImageFile('FotoUbi', $_FILES,$path_carpetaRaiz, $_POST['Path_Img']);//Escritura de fotos en la carpeta
            }else{
                $result = $this->uploadImagePhoto($_POST['Img_64'],$path_carpetaRaiz,$path_carpetaRaiz.$_POST['Path_Img']);//Escritura de fotos en la carpeta
            }
        }
    }

    public function GuardarFotosUbicaciones($Id_Punto){
        $path_carpeta = BASE_PATH . "public/files/Puntos/" . $Id_Punto . "/Datos/";
        $path_carpeta2 = BASE_PATH . "public/files/Puntos/" . $Id_Punto . "/Respaldo/";
        if(isset($_POST['DatosUbicacion_table'])){
            $Ubicaciones = json_decode($_POST['DatosUbicacion_table']);//Saca los datos de los Forencias
        }
        ini_set('memory_limit', '5120M');
        foreach (glob($path_carpeta . "/*") as $archivos_carpeta) {
            if (is_dir($archivos_carpeta)) {
                rmDir_rf($archivos_carpeta);
            } else {
                unlink($archivos_carpeta);
            }
        }
        if(isset($Ubicaciones)){
            foreach ($Ubicaciones as $Ubicacion) {
                if($Ubicacion->row->nameImage != 'null'){
                    if ($Ubicacion->row->typeImage == 'File') {
                        $type = $_FILES[$Ubicacion->row->nameImage]['type'];
                        $extension = explode("/", $type);
                        $hoy = date("Y-m-d H:i:s");
                        $quitar = array(":", "/");
                        $hoy =str_replace($quitar, "-", $hoy);
                        $result = $this->uploadImageFileUbicaciones($Ubicacion->row->nameImage, $_FILES, $Id_Punto, $path_carpeta, $Ubicacion->row->nameImage . ".png");//Escritura de fotos en la carpeta
                        $result = $this->uploadImageFileUbicaciones($Ubicacion->row->nameImage, $_FILES, $Id_Punto, $path_carpeta2,$hoy. $Ubicacion->row->nameImage .".png");//Escritura de fotos en el respaldo
                    }
                    if ($Ubicacion->row->typeImage == 'Photo') {
                        $result = $this->uploadImagePhotoUbicaciones($Ubicacion->row->image, $Id_Punto, $path_carpeta, $path_carpeta . $Ubicacion->row->nameImage . ".png");//Escritura de fotos en la carpeta
                    }
                }
            }
        }
    }
    /* ----- ----- ----- Funciones para guardar la imagenes en el servidor de las forencias asociadas  ----- ----- ----- */
    public function uploadImageFileUbicaciones($name, $file, $alerta, $carpeta, $fileName){
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

    public function uploadImagePhotoUbicaciones($img, $ficha, $carpeta, $ruta){
        if (!file_exists($carpeta)){//si no existe la carpeta se crea
            mkdir($carpeta, 0777, true);
        }
        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        file_put_contents($ruta, $image_base64);
        return true;
    }
    /* ----- ----- ----- Funciones para guardar la imagenes en el servidor  ----- ----- ----- */
    public function uploadImageFile($name, $file, $carpeta, $fileName){
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
    public function uploadImagePhoto($img, $carpeta, $ruta){

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
        error_reporting(E_ALL & ~E_WARNING );//ESTO ES PARA NO MUESTRE LOS WARNINGS CUANDO LAS FOTOS SUBIAS CONTENTGAN EL BIT DE INTERLANCIA PRENDIDO Y DEJE FLUIR EL FUNCIONAMIENTO DEL SISTEMA
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

    public function Escribelogtxt($user, $ip, $descripcion){
        $path_carpeta = BASE_PATH . "public/";
        $archivo = fopen($path_carpeta.'logs.txt','a') or die ("Error al crear archivo log");
        $cad = $user.' ||| '.$ip.' ||| '.$descripcion;
        fwrite($archivo,$cad);
        fwrite($archivo,"\r\n");
        fclose($archivo);
    } 

    public function DesasociaDato(){//FUNCION PARA ELIMINAR UNA PERSONA DE LA TABLA CON ID EN BASE DE DATOS
        if (isset($_POST['Id_Dato_Punto']) && isset($_POST['Id_Punto'])) {
            $Id_Dato_Punto = $_POST['Id_Dato_Punto'];
            $Id_Punto= $_POST['Id_Punto'];
            $data = $this->Punto->DesasociaDato($Id_Dato_Punto);
            if ($data['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $data['sqlEjecutados']);
                $descripcion = 'ELIMINO DATO : '.$Id_Dato_Punto.' DEL PUNTO '.$Id_Punto.' EL USUARIO '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $this->Punto->historial($user, $ip, 41, $descripcion);//Guarda en el historial el movimiento
            } 

            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Puntos");
            exit();
        }
    }

}
?>