<?php

class Entrevistas extends Controller
{
    /*controlador del modulo Entrevistas
    NOMENCLATURA DE MOVIMIENTOS EN EL HISTORIAL 
        30.-INSERCION DE UNA NUEVA PERSONA QUE SE ENTREVISTO 
        31.-VER INFORMACION DE LA ENTREVISTA
        32.-ACTUALIZACION DE LA TAB PRINCIPAL DE PERSONA ENTREVISTADA 
        33.-ACTUALIZACION DE TAB SECUNDARIA DE ENTREVISTA 
        34.-ELIMINACION DE ELEMENTO DE ALGUNA TABLA 
        35.-CONSULTA BUSQUEDA EN MODULO ENTREVISTAS
        36.-EXPORTACION DE INFORMACION MODULO ENTREVISTAS
    */
    public function __construct(){
        $this->Catalogo = $this->model('Catalogo');//para ocupar las funciones del modelo del catalogo
        $this->Entrevista = $this->model('Entrevista');//para ocupar las funciones del modelo del entrevista
        $this->numColumnsSG = [8,8,7];  //se inicializa el número de columns por cada filtro
        $this->FV = new FormValidator();
    }

    public function index(){
        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }
        $data = [
            'titulo'    => 'AURA | Entrevistas',
            'extra_css' => '<link rel="stylesheet" href="' . base_url . 'public/css/system/entrevistas/index.css">',
            'extra_js'  => '<script src="' . base_url . 'public/js/system/entrevistas/index.js"></script>'
        ];
        //PROCESO DE FILTRADO DE EVENTOS
        if (isset($_GET['filtro']) && is_numeric($_GET['filtro']) && $_GET['filtro'] >= MIN_FILTRO_ES && $_GET['filtro'] <= MAX_FILTRO_ES) { //numero de filtro
            $filtro = $_GET['filtro'];
        } else {
            $filtro = 1;
        }
        //PROCESAMIENTO DE LAS COLUMNAS 
        $this->setColumnsSession($filtro);
        $data['columns_ES'] = $_SESSION['userdataSIC']->columns_ES;

        //PROCESAMIENTO DE RANGO DE FOLIOS
        if (isset($_POST['rango_inicio']) && isset($_POST['rango_fin'])) {
            $_SESSION['userdataSIC']->rango_inicio_es = $_POST['rango_inicio'];
            $_SESSION['userdataSIC']->rango_fin_es = $_POST['rango_fin'];
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

        $where_sentence = $this->Entrevista->generateFromWhereSentence($cadena, $filtro,"");
        $extra_cad = ($cadena != "") ? ("&cadena=" . $cadena) : ""; //para links conforme a búsqueda
        $no_of_records_per_page = NUM_MAX_REG_PAGE; //total de registros por pagination
        $offset = ($numPage - 1) * $no_of_records_per_page; // desplazamiento conforme a la pagina
        $results_rows_pages = $this->Entrevista->getTotalPages($no_of_records_per_page, $where_sentence);   //total de páginas de acuerdo a la info de la DB
        $total_pages = $results_rows_pages['total_pages'];
        if ($numPage > $total_pages) {
            $numPage = 1;
            $offset = ($numPage - 1) * $no_of_records_per_page;
        } //seguridad si ocurre un error por url     

        $rows_Entrevistas = $this->Entrevista->getDataCurrentPage($offset, $no_of_records_per_page, $where_sentence);    //se obtiene la información de la página actual

        //guardamos la tabulacion de la información para la vista
        $data['infoTable'] = $this->generarInfoTable($rows_Entrevistas, $filtro);
        //guardamos los links en data para la vista
        $data['links'] = $this->generarLinks($numPage, $total_pages, $extra_cad, $filtro);
        //número total de registros encontrados
        $data['total_rows'] = $results_rows_pages['total_rows'];
        //filtro actual para Fetch javascript
        $data['filtroActual'] = $filtro;
        $data['dropdownColumns'] = $this->generateDropdownColumns($filtro);
        switch ($filtro) {
            case '1':
                $data['filtroNombre'] = "Todos las Entrevistas";
                break;
            case '2':
                $data['filtroNombre'] = "Todas las Ubicaciones";
                break;
            case '3':
                $data['filtroNombre'] = "Personas Alertas";
                break;
        }
        $this->view('templates/header', $data);
        $this->view('system/entrevistas/entrevistaView', $data);//muestra la vista principal del modulo de entrevistas
        $this->view('templates/footer', $data);
    }
    /* ----------------------------------------FUNCIONES DE FILTROS ------------------------------------- */
    //función para checar los cambios de filtro y poder asignar los valores correspondientes de las columnas a la session
    public function setColumnsSession($filtroActual = 1){
        //si el filtro existe y esta dentro de los parámetros continua
        if (isset($_SESSION['userdataSIC']->filtro_ES) && $_SESSION['userdataSIC']->filtro_ES >= MIN_FILTRO_ES && $_SESSION['userdataSIC']->filtro_ES <= MAX_FILTRO_ES) {
            //si cambia el filtro se procde a cambiar los valores de las columnas que contiene el filtro seleccionado
            if ($_SESSION['userdataSIC']->filtro_ES != $filtroActual) {
                $_SESSION['userdataSIC']->filtro_ES = $filtroActual;
                unset($_SESSION['userdataSIC']->columns_ES); 
                for ($i = 0; $i < $this->numColumnsSG[$_SESSION['userdataSIC']->filtro_ES - 1]; $i++)
                    $_SESSION['userdataSIC']->columns_ES['column' . ($i + 1)] = 'show';
            }
        } else { //si no existe el filtro entonces se inicializa con el primero por default
            $_SESSION['userdataSIC']->filtro_ES = $filtroActual;
            unset($_SESSION['userdataSIC']->columns_ES);
            for ($i = 0; $i < $this->numColumnsSG[$_SESSION['userdataSIC']->filtro_ES - 1]; $i++)
                $_SESSION['userdataSIC']->columns_ES['column' . ($i + 1)] = 'show';
        }
    }
    //función fetch que actualiza los valores de las columnas para la session
    public function setColumnFetch(){
        if (isset($_POST['columName']) && isset($_POST['valueColumn'])) {
            $_SESSION['userdataSIC']->columns_SG[$_POST['columName']] = $_POST['valueColumn'];
            echo json_encode("ok");
        } else {
            header("Location: " . base_url . "Entrevistas");
            exit();
        }
    }
    //función para generar la información de la tabla de forma dinámica
    public function generarInfoTable($rows, $filtro = 1){
        $permisos_Editar = ($_SESSION['userdataSIC']->Entrevistas[1] == 1 || $_SESSION['userdataSIC']->Modo_Admin == 1) ? '' : 'mi_hide';//desaparece los botones de acuerdo a los permisos
        $permisos_Ver = ($_SESSION['userdataSIC']->Entrevistas[2] == 1 || $_SESSION['userdataSIC']->Modo_Admin == 1) ? '' : 'mi_hide';
        $permisos_Exportacion= ($_SESSION['userdataSIC']->Entrevistas[0] == 1 || $_SESSION['userdataSIC']->Modo_Admin == 1) ? '' : 'mi_hide';
        //se genera la tabulacion de la informacion por backend
        $infoTable['header'] = "";
        $infoTable['body'] = "";
        switch ($filtro) {
            case '1': //General de todos las entrevistas
                $infoTable['header'] .= '
                        <th class="column1">Folio de Persona</th>
                        <th class="column2">Nombre del Detenido Entrevistado</th>
                        <th class="column3">Alias</th>
                        <th class="column4">Remisiones</th>
                        <th class="column5">Capturo</th>
                        <th class="column6">Entrevistas</th>
                        <th class="column7">Ubicaciones</th>
                    ';
                foreach ($rows as $row) {
                    $Entrevistas_concat = "";
                    $EntrevistasArray = explode('|||',$row->Entrevistas_concat);
                    foreach ($EntrevistasArray as $key => $ent) {
                        $Entrevistas_concat .= ($ent)? ($key+1).".- ".$ent."<br>":'';
                    }
                    $Forensia_concat = "";
                    $ForensiaArray = explode('|||',$row->Ubicaciones_concat);
                    foreach ($ForensiaArray as $key => $fore) {
                        $Forensia_concat .= ($fore)? ($key+1).".- ".$fore."<br>":'';
                    }
                    $infoTable['body'] .= '<tr id="tr' . $row->Id_Persona_Entrevista . '">';
                    $infoTable['body'] .= ' <td class="column1">' . $row->Id_Persona_Entrevista . '</td>
                                            <td class="column2">' . mb_strtoupper($row->Nombre_completo_Entrevistado) . '</td>
                                            <td class="column3">' . mb_strtoupper($row->Alias). '</td>
                                            <td class="column4">' . $row->Remisiones. '</td>
                                            <td class="column5">' . mb_strtoupper($row->Capturo). '</td>
                                            <td class="column6">' . mb_strtoupper($Entrevistas_concat)  . '</td>
                                            <td class="column7">' . mb_strtoupper($Forensia_concat) . '</td>
                        ';
                    if ($row->FechaHora_Creacion != '') {
                        if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Entrevistas[1] == '1') { //validacion de tabs validados completamente y/o permisos de validacion o modo admin
                            $infoTable['body'] .= '<td>
                                                    <a class="myLinks ' . $permisos_Editar . '" data-toggle="tooltip" data-placement="right" title="Editar registro" href="' . base_url . 'Entrevistas/editarPersonaEntrevistada/?Id_Persona_Entrevista=' . $row->Id_Persona_Entrevista . '">
                                                        <i class="material-icons">edit</i>
                                                    </a>';
                        } else {
                            $infoTable['body'] .= '<td>';
                        }
                        if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Entrevistas[2] == '1') { //validacion de tabs validados completamente y/o permisos de validacion o modo admin
                            $infoTable['body'] .= '
                                                    <a target="_blank" class="myLinks ' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Ver registro" href="' . base_url . 'Entrevistas/verEntrevista/?Id_Persona_Entrevista=' . $row->Id_Persona_Entrevista . '">
                                                        <i class="material-icons">visibility</i>
                                                    </a>';                        
                        
                        }
                        if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Entrevistas[0] == '1') {                         
                            $infoTable['body'] .= '
                                        <a target="_blank" class="myLinks ' . $permisos_Exportacion . '" data-toggle="tooltip" data-placement="right" title="Generar PDF" href="' . base_url . 'Entrevistas/GeneraPDF/?Id_Persona_Entrevista=' .$row->Id_Persona_Entrevista. '">
                                            <i class="material-icons">assignment</i>
                                        </a>
                                        </td>';
                        
                        }else{
                            $infoTable['body'] .= '</td>';
                        }
                            
                    }
                    $infoTable['body'] .= '</tr>';
                }
                $infoTable['header'] .= '<th >Operaciones</th>';
            break;
            case '2': //General de todos las entrevistas
                $infoTable['header'] .= '
                        <th class="column1">Remision</th>
                        <th class="column2">Nombre del Detenido Entrevistado</th>
                        <th class="column3">Banda</th>
                        <th class="column4">Domicilio</th>
                        <th class="column5">Coordenada Y</th>
                        <th class="column6">Coordenada X</th>
                        <th class="column7">Observacion</th>
                    ';
                foreach ($rows as $row) {
                    $arrayAux = explode(',',$row->Remisiones);
                    $infoTable['body'] .= '<tr id="tr' . $row->Id_Persona_Entrevista . '">';
                    $infoTable['body'] .= ' <td class="column1">' . $arrayAux[0] . '</td>
                                            <td class="column2">' . mb_strtoupper($row->Nombre_completo_Entrevistado) . '</td>
                                            <td class="column3">' . mb_strtoupper($row->Banda). '</td>
                                            <td class="column4">' . mb_strtoupper($row->Ubicacion). '</td>
                                            <td class="column5">' . mb_strtoupper($row->CoordY). '</td>
                                            <td class="column6">' . mb_strtoupper($row->CoordX)  . '</td>
                                            <td class="column7">' . mb_strtoupper($row->Observaciones) . '</td>
                        ';
                    if ($row->FechaHora_Creacion != '') {
                        if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Entrevistas[1] == '1') { //validacion de tabs validados completamente y/o permisos de validacion o modo admin
                            $infoTable['body'] .= '<td>
                                                    <a class="myLinks ' . $permisos_Editar . '" data-toggle="tooltip" data-placement="right" title="Editar registro" href="' . base_url . 'Entrevistas/editarPersonaEntrevistada/?Id_Persona_Entrevista=' . $row->Id_Persona_Entrevista . '">
                                                        <i class="material-icons">edit</i>
                                                    </a>';
                        } else {
                            $infoTable['body'] .= '<td>';
                        }
                        if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Entrevistas[2] == '1') { //validacion de tabs validados completamente y/o permisos de validacion o modo admin
                            $infoTable['body'] .= '
                                                    <a target="_blank" class="myLinks ' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Ver registro" href="' . base_url . 'Entrevistas/verEntrevista/?Id_Persona_Entrevista=' . $row->Id_Persona_Entrevista . '">
                                                        <i class="material-icons">visibility</i>
                                                    </a>';                        
                        
                        }
                        if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Entrevistas[0] == '1') {                         
                            $infoTable['body'] .= '
                                        <a target="_blank" class="myLinks ' . $permisos_Exportacion . '" data-toggle="tooltip" data-placement="right" title="Generar PDF" href="' . base_url . 'Entrevistas/GeneraPDF/?Id_Persona_Entrevista=' .$row->Id_Persona_Entrevista. '">
                                            <i class="material-icons">assignment</i>
                                        </a>
                                        </td>';
                        }else{
                            $infoTable['body'] .= '</td>';
                        }
                    }
                    $infoTable['body'] .= '</tr>';
                }
                $infoTable['header'] .= '<th >Operaciones</th>';
            break;
            case '3': //General de todos las entrevistas
                $infoTable['header'] .= '
                        <th class="column1">Nombres</th>
                        <th class="column2">Primer Apellido</th>
                        <th class="column3">Segundo Apellido</th>
                        <th class="column4">Alias</th>
                        <th class="column5">Banda</th>
                        <th class="column6">Asociado A</th>
                        <th class="column7">Capturo</th>
                    ';
                foreach ($rows as $row) {
                    $infoTable['body'] .= '<tr id="tr' . $row->Id_Persona_Entrevista . '">';
                    $infoTable['body'] .= ' <td class="column1">' . mb_strtoupper($row->NOMBRES). '</td>
                                            <td class="column2">' . mb_strtoupper($row->PRIM_APELLIDO) . '</td>
                                            <td class="column3">' . mb_strtoupper($row->SEG_APELLIDO). '</td>
                                            <td class="column4">' . mb_strtoupper($row->ALIAS). '</td>
                                            <td class="column5">' . mb_strtoupper($row->BANDA). '</td>
                                            <td class="column6">' . mb_strtoupper($row->DELITOS_ASOCIADOS)  . '</td>
                                            <td class="column7">' . mb_strtoupper($row->CAPTURA)  . '</td>
                        ';
                    if ($row->FechaHora_Creacion != '') {
                        if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Entrevistas[1] == '1') { //validacion de tabs validados completamente y/o permisos de validacion o modo admin
                            $infoTable['body'] .= '<td>
                                                    <a class="myLinks ' . $permisos_Editar . '" data-toggle="tooltip" data-placement="right" title="Editar registro" href="' . base_url . 'Entrevistas/editarPersonaEntrevistada/?Id_Persona_Entrevista=' . $row->Id_Persona_Entrevista . '">
                                                        <i class="material-icons">edit</i>
                                                    </a>';
                        } else {
                            $infoTable['body'] .= '<td>';
                        }
                        if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Entrevistas[2] == '1') { //validacion de tabs validados completamente y/o permisos de validacion o modo admin
                            $infoTable['body'] .= '
                                                    <a target="_blank" class="myLinks ' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Ver registro" href="' . base_url . 'Entrevistas/verEntrevista/?Id_Persona_Entrevista=' . $row->Id_Persona_Entrevista . '">
                                                        <i class="material-icons">visibility</i>
                                                    </a>';                        
                        
                        }
                        if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Entrevistas[0] == '1') {                         
                            $infoTable['body'] .= '
                                        <a target="_blank" class="myLinks ' . $permisos_Exportacion . '" data-toggle="tooltip" data-placement="right" title="Generar PDF" href="' . base_url . 'Entrevistas/GeneraPDF/?Id_Persona_Entrevista=' .$row->Id_Persona_Entrevista. '">
                                            <i class="material-icons">assignment</i>
                                        </a>
                                        </td>';
                        }else{
                            $infoTable['body'] .= '</td>';
                        }
                    }
                    $infoTable['body'] .= '</tr>';
                }
                $infoTable['header'] .= '<th >Operaciones</th>';
            break;
        }
       
        return $infoTable;
    }
	public function exportarInfo(){//Funcion para Exportar informacion en excel

		if (!isset($_REQUEST['tipo_export'])) {
            header("Location: " . base_url . "Entrevistas");
            exit();
		}else{
            $data=$this->Entrevista->permisoVisualizacion($_SESSION['userdataSIC']->User_Name);
            //print_r($data->Visualizacion);
            $_SESSION['userdataSIC']->Visualizacion = $data->Visualizacion;
        }
		$from_where_sentence = "";
        $filtroActual =  $_REQUEST['filtroActual'];

        if (isset($_REQUEST['cadena'])){//Verifica si existe una Cadena para consulta
            $from_where_sentence = $this->Entrevista->generateFromWhereSentence($_REQUEST['cadena'], $filtroActual);//excel con consulta
        }else{
            $from_where_sentence = $this->Entrevista->generateFromWhereSentence("",$filtroActual);//Excel sin consulta
        }
		$tipo_export = $_REQUEST['tipo_export'];;
		if ($tipo_export == 'EXCEL') {
			//se realiza exportacion de usuarios a EXCEL
			$cat_rows = $this->Entrevista->getAllInfoEntrevistaByCadena($from_where_sentence);
			switch ($filtroActual) {
				case '1':
                    //Genera nombre de archivo junto con los datos y los encabezasdos 
					$filename = "Vista_General_Entrevista";
					$csv_data="REMISION,NOMBRE DEL DETENIDO,ALIAS,REMITIDO POR,FECHA ENTREVISTA,CAPTURO,LUGAR DE LA DETENCION,BANDA O GRUPO PARA DELINQUIR,ENTREVISTA DETENIDO,ALIAS REFERIDOS,UBICACIONES REFERIDAS,DATOS DEL DOMICILIO,COORDENADAS,RESUMEN DE ENTREVISTA\n";
					foreach ($cat_rows as $row) {
                        $cadVisualizacion=($_SESSION['userdataSIC']->Visualizacion==1)?$row->Descripcion_Forensia_concat:'';
                        $arrayAux = explode(',', $row->Remisiones);
						$csv_data.= mb_strtoupper($arrayAux[0]).",\"".
                                    mb_strtoupper($row->Nombre_completo_Entrevistado)."\",\"".
                                    mb_strtoupper($row->Alias)."\",\"".
                                    mb_strtoupper($row->Detenido_por)."\",\"".
                                    mb_strtoupper($row->Fechas_entrevistas)."\",\"".
                                    mb_strtoupper($row->Capturo)."\",\"".
                                    mb_strtoupper($row->Ubicacion_detencion)."\",\"".
                                    mb_strtoupper($row->Banda)."\",\"".
                                    $this->tratamiento( mb_strtoupper($row->Entrevistas_concat))."\",\"".
                                    mb_strtoupper($row->Alias_Referidos)."\",\"".
                                    $this->tratamiento2(mb_strtoupper($row->Ubicaciones_concat))."\",\"".
                                    $this->tratamiento2(mb_strtoupper($row->Observaciones_domicilios))."\",\"".
                                    $row->Coordenadas."\",\"".
									$this->tratamiento(mb_strtoupper($cadVisualizacion))."\"\n";
					}
				break;
                case '2':
                    //Genera nombre de archivo junto con los datos y los encabezasdos 
					$filename = "Puntos_identificados_remisiones";
					$csv_data="FOLIO,FUENTE,DELITO ASOCIADO,NOMBRE DE LA BANDA,OBJETIVO,DOMICILIO,COORDENADAS Y,COORDENADAS X,VERIDICADO,FECHA,OBSERVACIONES\n";
					foreach ($cat_rows as $row) {
                        $arrayAux = explode(',', $row->Remisiones);
                        $arrayFecha = explode(' ', $row->FechaHora_Creacion);
						$csv_data.= mb_strtoupper($arrayAux[0]).",\"\",\"".
                                    mb_strtoupper($row->Detenido_por)."\",\"".
                                    mb_strtoupper($row->Banda)."\",\"\",\"".
                                    $this->tratamiento(mb_strtoupper($row->Ubicacion))."\",\"".
                                    mb_strtoupper($row->CoordY)."\",\"".
                                    mb_strtoupper($row->CoordX)."\",\"\",\"".
                                    $arrayFecha[0]."\",\"".
									$this->tratamiento(mb_strtoupper($row->Observaciones))."\"\n";
					}
				break;
                case '3':
                    //Genera nombre de archivo junto con los datos y los encabezasdos 
					$filename = "Personas_Alertas";
					$csv_data="NOMBRE, PRIMER APELLIDO, SEGUNDO APELLIDO, ALIAS, BANDA, DELITOS ASOCIADOS, FOLIOS, CAPTURA, OBSERVACIONES\n";
					foreach ($cat_rows as $row) {
						$csv_data.= mb_strtoupper($row->NOMBRES).",\"".
                                    mb_strtoupper($row->PRIM_APELLIDO)."\",\"".
                                    mb_strtoupper($row->SEG_APELLIDO)."\",\"".
                                    mb_strtoupper($row->ALIAS)."\",\"".
                                    mb_strtoupper($row->BANDA)."\",\"".
                                    mb_strtoupper($row->DELITOS_ASOCIADOS)."\",\"".
                                    mb_strtoupper('')."\",\"".
                                    mb_strtoupper($row->CAPTURA)."\",\"".
									mb_strtoupper('')."\"\n";
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
            $descripcion = 'EXPORTACION DE EXCEL ENTREVISTAS: ' . $filename .' '.$_SESSION['userdataSIC']->User_Name;
            $this->Entrevista->historial($user, $ip, 36, $descripcion);//GUarda movimiento en historial

		}else {
			header("Location: ".base_url."Entrevistas");
		}
	}
    public function tratamiento($entrada){
        $text=$entrada;
        $quitar = array("'", "\"","\\","/","´","|||");
        $text = str_replace($quitar, ' ', $text);
        return $text;
    }
    public function tratamiento2($entrada){
        $text=$entrada;
        $quitar = array("'", "\"","\\","/","´");
        $text = str_replace($quitar, ' ', $text);
        return $text;
    }
    public function buscarPorCadena(){//Funcion para buscar lo que le escribes en el panel de texto buscar

        if (isset($_POST['cadena'])) {//Comprueba si existe una cadena para buscar
            $cadena = trim($_POST['cadena']);
            $filtroActual = trim($_POST['filtroActual']);

            $results = $this->Entrevista->getEntrevistasByCadena($cadena, $filtroActual);//Devuelve los datos de la cadena consultada
            if (strlen($cadena) > 0) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $ip = $this->obtenerIp();
                $descripcion = 'CONSULTA ENTREVISTA: ' . $cadena .' '.$_SESSION['userdataSIC']->User_Name;
                $this->Entrevista->historial($user, $ip, 35, $descripcion);//Escribe en el historial el movimiento
            }
            $extra_cad = ($cadena != "") ? ("&cadena=" . $cadena) : ""; //para links conforme a búsqueda

            $dataReturn['infoTable'] = $this->generarInfoTable($results['rows_Entre'], $filtroActual);
            $dataReturn['links'] = $this->generarLinks($results['numPage'], $results['total_pages'], $extra_cad, $filtroActual);
            $dataReturn['export_links'] = $this->generarExportLinks($extra_cad, $filtroActual);
            $dataReturn['total_rows'] = "Total registros: " . $results['total_rows'];
            $dataReturn['dropdownColumns'] = $this->generateDropdownColumns($filtroActual);


            echo json_encode($dataReturn);
        } else {
            header("Location: " . base_url . "Entrevistas");
            exit();
        }
    }
    public function generarExportLinks($extra_cad = "", $filtro = 1){//Funcion para exportar la informacion 
        if ($extra_cad != "") {
            $dataReturn['csv'] =  base_url . 'Entrevistas/exportarInfo/?tipo_export=CSV' . $extra_cad . '&filtroActual=' . $filtro;
            $dataReturn['excel'] =  base_url . 'Entrevistas/exportarInfo/?tipo_export=EXCEL' . $extra_cad . '&filtroActual=' . $filtro;
            $dataReturn['pdf'] =  base_url . 'Entrevistas/exportarInfo/?tipo_export=PDF' . $extra_cad . '&filtroActual=' . $filtro;
        } else {
            $dataReturn['csv'] =  base_url . 'Entrevistas/exportarInfo/?tipo_export=CSV' . $extra_cad . '&filtroActual=' . $filtro;
            $dataReturn['excel'] =  base_url . 'Entrevistas/exportarInfo/?tipo_export=EXCEL' . $extra_cad . '&filtroActual=' . $filtro;
            $dataReturn['pdf'] =  base_url . 'Entrevistas/exportarInfo/?tipo_export=PDF' . $extra_cad . '&filtroActual=' . $filtro;
        }
        return $dataReturn;
    }
    public function generarLinks($numPage, $total_pages, $extra_cad = "", $filtro = 1){
        //$extra_cad sirve para determinar la paginacion conforme a si se realizó una busqueda
        //Creación de links para la paginacion
        $links = "";

        //FLECHA IZQ (PREV PAGINATION)
        if ($numPage > 1) {
            $links .= '<li class="page-item">
                            <a class="page-link" href=" ' . base_url . 'Entrevistas/index/?numPage=1' . $extra_cad . '&filtro=' . $filtro . ' " data-toggle="tooltip" data-placement="top" title="Primera página">
                                <i class="material-icons">first_page</i>
                            </a>
                        </li>';
            $links .= '<li class="page-item">
                            <a class="page-link" href=" ' . base_url . 'Entrevistas/index/?numPage=' . ($numPage - 1) . $extra_cad . '&filtro=' . $filtro . ' " data-toggle="tooltip" data-placement="top" title="Página anterior">
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
                                <a class="page-link" href=" ' . base_url . 'Entrevistas/index/?numPage=' . ($ind) . $extra_cad . '&filtro=' . $filtro . ' ">
                                    ' . ($ind) . '
                                </a>
                            </li>';
            }
        }

        //FLECHA DERECHA (NEXT PAGINATION)
        if ($numPage < $total_pages) {

            $links .= '<li class="page-item">
                            <a class="page-link" href=" ' . base_url . 'Entrevistas/index/?numPage=' . ($numPage + 1) . $extra_cad . '&filtro=' . $filtro . ' " data-toggle="tooltip" data-placement="top" title="Siguiente página">
                            <i class="material-icons">navigate_next</i>
                            </a>
                        </li>';
            $links .= '<li class="page-item">
                            <a class="page-link" href=" ' . base_url . 'Entrevistas/index/?numPage=' . ($total_pages) . $extra_cad . '&filtro=' . $filtro . ' " data-toggle="tooltip" data-placement="top" title="Última página">
                            <i class="material-icons">last_page</i>
                            </a>
                        </li>';
        }

        return $links;
    }
    //función que filtra las columnas deseadas por el usuario
    public function generateDropdownColumns($filtro = 1){
        $dropDownColumn = '';
        //generación de dropdown dependiendo del filtro
        switch ($filtro) {
            case '1':
                $campos = ['Folio Persona', 'Nombre del Detenido Entrevistado', 'Alias', 'Remisiones','Capturo','Entrevistas','Forencias'];
                break;
            case '2':
                $campos = ['Remision', 'Nombre del Detenido Entrevistado', 'Banda', 'Domicilio','Coordenada X','Coordenada Y','Observacion'];
                break;
            case '3':
                $campos = ['Nombre', 'Primer Apellido', 'Segundo Apellido', 'Alias','Banda','Delitos Asociados','Capturo'];
                break;
        }
        $ind = 1;
        foreach ($campos as $campo) {
            $checked = ($_SESSION['userdataSIC']->columns_ES['column' . $ind] == 'show') ? 'checked' : '';
            $dropDownColumn .= ' <div class="form-check">
                                    <input class="form-check-input checkColumns" type="checkbox" value="' . $_SESSION['userdataSIC']->columns_ES['column' . $ind] . '" onchange="hideShowColumn(this.id);" id="column' . $ind . '" ' . $checked . '>
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
    /* ----------------------------------------FUNCION PARA GENERAR NUEVAS ENTREVISTAS------------------------------------- */
    public function nuevaEntrevista(){
        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }
        //comprobar los permisos para dejar pasar al módulo
        if(($_SESSION['userdataSIC']->Modo_Admin != 1 && $_SESSION['userdataSIC']->Entrevistas[3] != 1)){
            header("Location: " . base_url . "Estadisticas");
            exit(); 
        }
        $datos_cat = [
            'zonas' => $this->Catalogo->getZonaSector("POLICIA")
        ];
        $data = [
            'titulo'     => 'AURA | Nueva Entrevista',
            'extra_css'  => '<link rel="stylesheet" href="' . base_url . 'public/css/system/entrevistas/fullview.css">',
            'extra_js'   => '<script src="'.base_url.'public/js/system/entrevistas/nuevaEntrevista.js"></script>',
            'datos_cat' => $datos_cat
        ];
        $this->view('templates/header', $data);
        $this->view('system/entrevistas/nuevaEntrevistaView', $data);
        $this->view('templates/footer', $data);
    }
    public function getInfoRemision(){//FUNCION QUE OBTIENE LA INFORMACION DE LA REMISION CONSULTADO EN SARAI
        if (isset($_POST['No_Remision'])) {
            $No_Remision = $_POST['No_Remision'];
            $data = $this->Entrevista->getInfoRemision($No_Remision);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Entrevistas");
            exit();
        }
    }
    /* ----------------------------------------INSERCION DE ENTREVISTAS------------------------------------- */
    public function insertEntrevistasFetch(){///FUNCION QUE INSERTA LOS DATOS PARA LA GENERACION DE UN NUEVO REGISTRO DE SEGUIMIENTO
        //comprobar los permisos para dejar pasar al módulo
        if(($_SESSION['userdataSIC']->Modo_Admin != 1 &&  $_SESSION['userdataSIC']->Entrevistas[3] != 1) ){
                $data_p['status'] = false;
                $data_p['error_message'] = 'Render Index';
                echo json_encode($data_p);
        }
        $success = $this->Entrevista->insertNuevaPersonaEntrevistada($_POST);//Inserta la informacion
        if ($success['status']) {
            $user = $_SESSION['userdataSIC']->Id_Usuario;
            $data_p['status'] =  true;
            $ip = $this->obtenerIp();
            $quitar = array("'", "\"");
            $auxsql =str_replace($quitar, "-", $success['sqlEjecutados']);
            $descripcion = 'INSERCION DE PERSONA DETENIDA ENTREVISTADA: '.$success['Id_Persona_Entrevista'].' '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
            $this->Entrevista->historial($user, $ip, 30, $descripcion);//Guarda en el historial el movimiento
            
        } else {
            $data_p['status'] =  false;
            $data_p['error_message'] = $success['error_message'];
            $data_p['error_sql'] = $success['error_sql'];
        }
        echo json_encode($data_p);
    }
    /*----------------------------------FUNCION PARA EDITAR PERSONAS ENTREVISTADAS---------------------------------------------*/
    public function editarPersonaEntrevistada(){
        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }
        //comprobar los permisos para dejar pasar al módulo
        if($_SESSION['userdataSIC']->Modo_Admin != 1 && $_SESSION['userdataSIC']->Entrevistas[1] != 1){
            header("Location: " . base_url . "Estadisticas");
            exit();
        }else{
            $data=$this->Entrevista->permisoVisualizacion($_SESSION['userdataSIC']->User_Name);
            $_SESSION['userdataSIC']->Visualizacion = $data->Visualizacion;
        }
        $datos_cat = [
            'zonas' => $this->Catalogo->getZonaSector("POLICIA")
        ];
        $data = [
            'titulo'     => 'AURA | Edicion de Entrevistas',
            'extra_css'  => '<link rel="stylesheet" href="' . base_url . 'public/css/system/entrevistas/fullview.css">',
            'extra_js'   => '<script src="https://api.mapbox.com/mapbox-gl-js/v2.8.1/mapbox-gl.js"></script>'.
                            '<link href="https://api.mapbox.com/mapbox-gl-js/v2.8.1/mapbox-gl.css" rel="stylesheet" />'.
                            '<script src="'.base_url.'public/js/system/entrevistas/editarEntrevistas/editPersona.js"></script>'.
                            '<script src="'.base_url.'public/js/system/entrevistas/editarEntrevistas/editForencia.js"></script>'.
                            '<script src="'.base_url.'public/js/system/entrevistas/editarEntrevistas/editEntrevista.js"></script>'.
                            '<script src="'.base_url.'public/js/system/entrevistas/editarEntrevistas/editUbicaciones.js"></script>'.
                            '<script src="'.base_url.'public/js/system/entrevistas/editarEntrevistas/ubicacion_mapbox.js"></script>'.
                            '<script src="'.base_url.'public/js/system/entrevistas/editarEntrevistas/editSocial.js"></script>'.
                            '<script src="'.base_url.'public/js/system/entrevistas/getInfoEntrevistas/getUbicacionesEntrevista.js"></script>'.
                            '<script src="'.base_url.'public/js/system/entrevistas/getInfoEntrevistas/getEntrevistaDetenido.js"></script>'.
                            '<script src="'.base_url.'public/js/system/entrevistas/getInfoEntrevistas/getPersonasEntrevista.js"></script>'.
                            '<script src="'.base_url.'public/js/system/entrevistas/getInfoEntrevistas/getForenciasEntrevista.js"></script>'.
                            '<script src="'.base_url.'public/js/system/entrevistas/getInfoEntrevistas/getSocialEntrevista.js"></script>',
                            'datos_cat' => $datos_cat
                        ];
        $this->view('templates/header', $data);
        $this->view('system/entrevistas/EntrevistasFullView', $data);
        $this->view('templates/footer', $data);
    }
    /* ----------------------------------------ACTUALIZACION DE DATOS PRINCIPALES PERSONA DETENIDA------------------------------------- */
    public function UpdateEntrevistasPrincipalesFetch(){//FUNCION PARA ACTUALIZAR 
        //comprobar los permisos para dejar pasar al módulo
        if(($_SESSION['userdataSIC']->Modo_Admin != 1 &&  $_SESSION['userdataSIC']->Entrevistas[1] != 1) ){
            $data_p['status'] = false;
            $data_p['error_message'] = 'Render Index';
            echo json_encode($data_p);
        }
        if(isset($_POST['id_persona_entrevista'])){
            $success = $this->Entrevista->UpdateEntrevistaPrincipales($_POST);
            if ($success['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $data_p['status'] =  true;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $success['sqlEjecutados']);
                $descripcion = 'ACTUALIZACION DE DATOS PRINCIPALES PERSONA DETENIDA: '.$_POST['id_persona_entrevista'].' '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $this->Entrevista->historial($user, $ip, 32, $descripcion);//Guarda en el historial el movimiento
                $this->GuardarFotoDetenido($_POST['id_persona_entrevista']);//Envia a guardar los archivos
            } else {
                $data_p['status'] =  false;
                $data_p['error_message'] = $success['error_message'];
                $data_p['error_sql'] = $success['error_sql'];
            }
            echo json_encode($data_p);
        }else{
            $data_p['status'] = false;
            $data_p['error_message'] = 'No Existe el id_persona_entrevista';
            echo json_encode($data_p);
        }     
    }
    public function updateEntrevistasFetch(){//FUNCION PARA ACTUALIZAR 
        //comprobar los permisos para dejar pasar al módulo
        if(($_SESSION['userdataSIC']->Modo_Admin != 1 &&  $_SESSION['userdataSIC']->Entrevistas[1] != 1) ){
            $data_p['status'] = false;
            $data_p['error_message'] = 'Render Index';
            echo json_encode($data_p);
        }
        if(isset($_POST['entrevistas_table']) && isset($_POST['id_persona_entrevista'])){
            $success = $this->Entrevista->UpdateEntrevistas($_POST);
            if ($success['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $data_p['status'] =  true;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $success['sqlEjecutados']);
                $descripcion = 'ACTUALIZACION DE DATOS DE ENTREVISTAS: '.$_POST['id_persona_entrevista'].' '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $this->Entrevista->historial($user, $ip, 33, $descripcion);//Guarda en el historial el movimiento
                $this->GuardarFotosEntrevistas($_POST['id_persona_entrevista']);
           } else {
                $data_p['status'] =  false;
                $data_p['error_message'] = $success['error_message'];
                $data_p['error_sql'] = $success['error_sql'];
            }
            echo json_encode($data_p);
        }else{
            $data_p['status'] = false;
            $data_p['error_message'] = 'No Existe el id_persona_entrevista';
            echo json_encode($data_p);
        }     
    }
    public function GuardarFotosEntrevistas($Id_Persona_Entrevista){
        $path_carpeta = BASE_PATH . "public/files/Entrevistas/" . $Id_Persona_Entrevista . "/FotosEntrevistas/";
        $path_carpeta2 = BASE_PATH . "public/files/Entrevistas/" . $Id_Persona_Entrevista . "/Respaldo/";
        if(isset($_POST['entrevistas_table'])){
            $Entrevistas = json_decode($_POST['entrevistas_table']);//Saca los datos de los Entrevistas
        }
        ini_set('memory_limit', '5120M');
        foreach (glob($path_carpeta . "/*") as $archivos_carpeta) {
            if (is_dir($archivos_carpeta)) {
                rmDir_rf($archivos_carpeta);
            } else {
                unlink($archivos_carpeta);
            }
        }
        if(isset($Entrevistas)){
            foreach ($Entrevistas as $Entrevista) {
                if($Entrevista->row->nameImage != 'null'){
                    if ($Entrevista->row->typeImage == 'File') {
                        $type = $_FILES[$Entrevista->row->nameImage]['type'];
                        $extension = explode("/", $type);
                        $hoy = date("Y-m-d H:i:s");
                        $quitar = array(":", "/");
                        $hoy =str_replace($quitar, "-", $hoy);
                        $result = $this->uploadImageFileEntrevistas($Entrevista->row->nameImage, $_FILES, $Id_Persona_Entrevista, $path_carpeta, $Entrevista->row->nameImage . ".png");//Escritura de fotos en la carpeta
                        $result = $this->uploadImageFileEntrevistas($Entrevista->row->nameImage, $_FILES, $Id_Persona_Entrevista, $path_carpeta2,$hoy. $Entrevista->row->nameImage .".png");//Escritura de fotos en el respaldo
                    }
                    if ($Entrevista->row->typeImage == 'Photo') {
                        $result = $this->uploadImagePhotoEntrevistas($Entrevista->row->image, $Id_Persona_Entrevista, $path_carpeta, $path_carpeta . $Entrevista->row->nameImage . ".png");//Escritura de fotos en la carpeta
                    }
                }
            }
        }
    }
    /* ----- ----- ----- Funciones para guardar la imagenes en el servidor de las Entrevistas asociadas  ----- ----- ----- */
    public function uploadImageFileEntrevistas($name, $file, $alerta, $carpeta, $fileName){
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

    public function uploadImagePhotoEntrevistas($img, $ficha, $carpeta, $ruta){
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
    /* ----------------------------------------ACTUALIZACION DE FORENSIAS SEGUIMIENTO------------------------------------- */
    public function UpdateForensiasFetch(){
        //comprobar los permisos para dejar pasar al módulo
        if(($_SESSION['userdataSIC']->Modo_Admin != 1 &&  $_SESSION['userdataSIC']->Entrevistas[1] != 1) ){
            $data_p['status'] = false;
            $data_p['error_message'] = 'Render Index';
            echo json_encode($data_p);
        }
        if(isset($_POST['Forensiastable'])){
            $success = $this->Entrevista->UpdateForensiasFetch($_POST);
            if ($success['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $data_p['status'] =  true;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $success['sqlEjecutados']);
                $descripcion = 'ACTUALIZACION DE FORENSIAS DE ENTREVISTAS: '.$_POST['id_persona_entrevista'].' '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $this->Entrevista->historial($user, $ip, 33, $descripcion);//Guarda en el historial el movimiento
                $this->GuardarFotosForensias($_POST['id_persona_entrevista']);
                
            } else {
                $data_p['status'] =  false;
                $data_p['error_message'] = $success['error_message'];
                $data_p['error_sql'] = $success['error_sql'];
            }
            echo json_encode($data_p);
        }else{
            $data_p['status'] = false;
            $data_p['error_message'] = 'No existen datos de forencias';
            echo json_encode($data_p);
        }     
    }
    public function GuardarFotosForensias($Id_Persona_Entrevista){
        $path_carpeta = BASE_PATH . "public/files/Entrevistas/" . $Id_Persona_Entrevista . "/ForensiasRelevantes/";
        $path_carpeta2 = BASE_PATH . "public/files/Entrevistas/" . $Id_Persona_Entrevista . "/Respaldo/";
        if(isset($_POST['Forensiastable'])){
            $Forencias = json_decode($_POST['Forensiastable']);//Saca los datos de los Forencias
        }
        ini_set('memory_limit', '5120M');
        foreach (glob($path_carpeta . "/*") as $archivos_carpeta) {
            if (is_dir($archivos_carpeta)) {
                rmDir_rf($archivos_carpeta);
            } else {
                unlink($archivos_carpeta);
            }
        }
        if(isset($Forencias)){
            foreach ($Forencias as $forencia) {
                if($forencia->row->nameImage != 'null'){
                    if ($forencia->row->typeImage == 'File') {
                        $type = $_FILES[$forencia->row->nameImage]['type'];
                        $extension = explode("/", $type);
                        $hoy = date("Y-m-d H:i:s");
                        $quitar = array(":", "/");
                        $hoy =str_replace($quitar, "-", $hoy);
                        $result = $this->uploadImageFileForencias($forencia->row->nameImage, $_FILES, $Id_Persona_Entrevista, $path_carpeta, $forencia->row->nameImage . ".png");//Escritura de fotos en la carpeta
                        $result = $this->uploadImageFileForencias($forencia->row->nameImage, $_FILES, $Id_Persona_Entrevista, $path_carpeta2,$hoy. $forencia->row->nameImage .".png");//Escritura de fotos en el respaldo
                    }
                    if ($forencia->row->typeImage == 'Photo') {
                        $result = $this->uploadImagePhotoForencias($forencia->row->image, $Id_Persona_Entrevista, $path_carpeta, $path_carpeta . $forencia->row->nameImage . ".png");//Escritura de fotos en la carpeta
                    }
                }
            }
        }
    }
    /* ----- ----- ----- Funciones para guardar la imagenes en el servidor de las forencias asociadas  ----- ----- ----- */
    public function uploadImageFileForencias($name, $file, $alerta, $carpeta, $fileName){
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

    public function uploadImagePhotoForencias($img, $ficha, $carpeta, $ruta){
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
 /* ----------------------------------------ACTUALIZACION DE FORENSIAS SEGUIMIENTO------------------------------------- */
    public function UpdateUbicacionesFetch(){
        //comprobar los permisos para dejar pasar al módulo
        if(($_SESSION['userdataSIC']->Modo_Admin != 1 &&  $_SESSION['userdataSIC']->Entrevistas[1] != 1) ){
            $data_p['status'] = false;
            $data_p['error_message'] = 'Render Index';
            echo json_encode($data_p);
        }
        if(isset($_POST['Ubicacionestable'])){
            $success = $this->Entrevista->UpdateUbicacionesFetch($_POST);
            if ($success['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $data_p['status'] =  true;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $success['sqlEjecutados']);
                $descripcion = 'ACTUALIZACION DE UBICACIONES DE ENTREVISTAS: '.$_POST['id_persona_entrevista'].' '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $this->Entrevista->historial($user, $ip, 33, $descripcion);//Guarda en el historial el movimiento
                $this->GuardarFotosUbicaciones($_POST['id_persona_entrevista']);
                
            } else {
                $data_p['status'] =  false;
                $data_p['error_message'] = $success['error_message'];
                $data_p['error_sql'] = $success['error_sql'];
            }
            echo json_encode($data_p);
        }else{
            $data_p['status'] = false;
            $data_p['error_message'] = 'No existen datos de forencias';
            echo json_encode($data_p);
        }     
    }
    public function GuardarFotosUbicaciones($Id_Persona_Entrevista){
        $path_carpeta = BASE_PATH . "public/files/Entrevistas/" . $Id_Persona_Entrevista . "/UbicacionesRelevantes/";
        $path_carpeta2 = BASE_PATH . "public/files/Entrevistas/" . $Id_Persona_Entrevista . "/Respaldo/";
        if(isset($_POST['Ubicacionestable'])){
            $Ubicaciones = json_decode($_POST['Ubicacionestable']);//Saca los datos de los Forencias
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
                        $result = $this->uploadImageFileUbicaciones($Ubicacion->row->nameImage, $_FILES, $Id_Persona_Entrevista, $path_carpeta, $Ubicacion->row->nameImage . ".png");//Escritura de fotos en la carpeta
                        $result = $this->uploadImageFileUbicaciones($Ubicacion->row->nameImage, $_FILES, $Id_Persona_Entrevista, $path_carpeta2,$hoy. $Ubicacion->row->nameImage .".png");//Escritura de fotos en el respaldo
                    }
                    if ($Ubicacion->row->typeImage == 'Photo') {
                        $result = $this->uploadImagePhotoUbicaciones($Ubicacion->row->image, $Id_Persona_Entrevista, $path_carpeta, $path_carpeta . $Ubicacion->row->nameImage . ".png");//Escritura de fotos en la carpeta
                    }
                }
            }
        }
    }
     /* ----------------------------------------ACTUALIZACION DE REDES SOCIALES SEGUIMIENTO------------------------------------- */
    public function UpdateRedesSocialesFetch(){
        //comprobar los permisos para dejar pasar al módulo
        if(($_SESSION['userdataSIC']->Modo_Admin != 1 &&  $_SESSION['userdataSIC']->Entrevistas[1] != 1) ){
            $data_p['status'] = false;
            $data_p['error_message'] = 'Render Index';
            echo json_encode($data_p);
        }
        if(isset($_POST['RedesSociales_table'])){
            $success = $this->Entrevista->UpdateRedesSocialesFetch($_POST);
            if ($success['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $data_p['status'] =  true;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $success['sqlEjecutados']);
                $descripcion = 'ACTUALIZACION DE REDES SOCIALES DE ENTREVISTAS: '.$_POST['id_persona_entrevista'].' '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $this->Entrevista->historial($user, $ip, 33, $descripcion);//Guarda en el historial el movimiento
                $this->GuardarFotoSociales($_POST['id_persona_entrevista']);
                
            } else {
                $data_p['status'] =  false;
                $data_p['error_message'] = $success['error_message'];
                $data_p['error_sql'] = $success['error_sql'];
            }
            echo json_encode($data_p);
        }else{
            $data_p['status'] = false;
            $data_p['error_message'] = 'No existen datos de redes';
            echo json_encode($data_p);
        }     
    }
    public function GuardarFotoSociales($Id_Persona_Entrevista){
        $path_carpeta = BASE_PATH . "public/files/Entrevistas/" . $Id_Persona_Entrevista . "/Redes_Sociales/";
        $path_carpeta2 = BASE_PATH . "public/files/Entrevistas/" . $Id_Persona_Entrevista . "/Respaldo/";
        if(isset($_POST['RedesSociales_table'])){
            $RedesSociales = json_decode($_POST['RedesSociales_table']);//Saca los datos de los Forencias
        }
        ini_set('memory_limit', '5120M');
        foreach (glob($path_carpeta . "/*") as $archivos_carpeta) {
            if (is_dir($archivos_carpeta)) {
                rmDir_rf($archivos_carpeta);
            } else {
                unlink($archivos_carpeta);
            }
        }
        if(isset($RedesSociales)){
            foreach ($RedesSociales as $RedSocial) {
                if($RedSocial->row->nameImage != 'null'){
                    if ($RedSocial->row->typeImage == 'File') {
                        $type = $_FILES[$RedSocial->row->nameImage]['type'];
                        $extension = explode("/", $type);
                        $hoy = date("Y-m-d H:i:s");
                        $quitar = array(":", "/");
                        $hoy =str_replace($quitar, "-", $hoy);
                        $result = $this->uploadImageFileSocial($RedSocial->row->nameImage, $_FILES, $Id_Persona_Entrevista, $path_carpeta, $RedSocial->row->nameImage . ".png");//Escritura de fotos en la carpeta
                        $result = $this->uploadImageFileSocial($RedSocial->row->nameImage, $_FILES, $Id_Persona_Entrevista, $path_carpeta2,$hoy. $RedSocial->row->nameImage .".png");//Escritura de fotos en el respaldo
                    }
                    if ($RedSocial->row->typeImage == 'Photo') {
                        $result = $this->uploadImagePhotoSocial($RedSocial->row->image, $Id_Persona_Entrevista, $path_carpeta, $path_carpeta . $RedSocial->row->nameImage . ".png");//Escritura de fotos en la carpeta
                    }
                }
            }
        }
    }
    /* ----- ----- ----- Funciones para guardar la imagenes en el servidor de las forencias asociadas  ----- ----- ----- */
    public function uploadImageFileSocial($name, $file, $alerta, $carpeta, $fileName){
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

    public function uploadImagePhotoSocial($img, $ficha, $carpeta, $ruta){
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
    public function VerEntrevista(){
        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }
        //comprobar los permisos para dejar pasar al módulo
        if($_SESSION['userdataSIC']->Modo_Admin != 1 && $_SESSION['userdataSIC']->Entrevistas[2] != 1){
            header("Location: " . base_url . "Estadisticas");
            exit();
        }else{
            $data=$this->Entrevista->permisoVisualizacion($_SESSION['userdataSIC']->User_Name);
            $_SESSION['userdataSIC']->Visualizacion = $data->Visualizacion;
        }
        if($_SESSION['userdataSIC']->Visualizacion == 1){
                $data = [
                    'titulo'     => 'AURA | Ver Informacion de Persona Entrevistada',
                    'extra_css'  => '<link rel="stylesheet" href="' . base_url . 'public/css/system/entrevistas/fullview.css">',
                    'extra_js'   => '<script src="'.base_url.'public/js/system/entrevistas/getInfoEntrevistasReadOnly/getPersonaReadOnly.js"></script>'
                                ];
        }else{
            $data = [
                'titulo'     => 'AURA | Ver Informacion de Persona Entrevistada',
                'extra_css'  => '<link rel="stylesheet" href="' . base_url . 'public/css/system/entrevistas/fullview.css">',
                'extra_js'   => '<script src="'.base_url.'public/js/system/entrevistas/getInfoEntrevistasReadOnly/getPersonaReadOnly2.js"></script>'
                            ];
        }
        $this->view('templates/header', $data);
        $this->view('system/entrevistas/EntrevistasReadOnly', $data);
        $this->view('templates/footer', $data);
    }
    public function getForensias(){//FUNCION QUE OBTIENE LA INFORMACION DE LAS FORENCIAS DE LAS PERSONAS DEL SEGUIMIENTO
        if(isset($_POST['Id_Persona_Entrevista'])){
            $Id_Persona_Entrevista=$_POST['Id_Persona_Entrevista'];
            $data = $this->Entrevista->getForensias($Id_Persona_Entrevista);
            echo json_encode($data);
        }
    }
    public function getUbicaciones(){//FUNCION QUE OBTIENE LA INFORMACION DE LAS UBICACIONES DE LAS ENTREVISTAS
        if(isset($_POST['Id_Persona_Entrevista'])){
            $Id_Persona_Entrevista=$_POST['Id_Persona_Entrevista'];
            $data = $this->Entrevista->getUbicaciones($Id_Persona_Entrevista);
            echo json_encode($data);
        }
    }
    public function getRedesSociales(){//FUNCION QUE OBTIENE LA INFORMACION DE LAS UBICACIONES DE LAS ENTREVISTAS
        if(isset($_POST['Id_Persona_Entrevista'])){
            $Id_Persona_Entrevista=$_POST['Id_Persona_Entrevista'];
            $data = $this->Entrevista->getRedesSociales($Id_Persona_Entrevista);
            echo json_encode($data);
        }
    }
    public function getPersonaSeguimientoOneRegister(){//FUNCION QUE OBTIENE LA INFORMACION DE LAS FORENCIAS DE LAS PERSONAS DEL SEGUIMIENTO
        if(isset($_POST['Id_Persona'])){
            $Id_Persona=$_POST['Id_Persona'];
            $data = $this->Entrevista->getPersonaSeguimientoOneRegister($Id_Persona);
            echo json_encode($data);
        }
    }
    /*Funciones añadidas para estados y municipios */
    public function getEstadosMexico(){
        $data = $this->Catalogo->getSimpleCatalogoOrder("Estado", "catalogo_estados","Estado");
        echo json_encode($data);
    }
    public function getMunicipios(){
        $data = $this->Catalogo->getMunicipiosEstados($_POST['termino'],$_POST['estado']);
        echo json_encode($data);
    }
    public function existeMunicipio(){
        $data = $this->Catalogo->existeMunicipio($_POST['estado'],$_POST['municipio']);
        echo json_encode($data);
    }
    public function getEntrevistas(){//FUNCION QUE OBTINE EL CATALOGO DE LAS REMISIONES
        if(isset($_POST['Id_Persona_Entrevista'])){
            $Id_Persona_Entrevista=$_POST['Id_Persona_Entrevista'];
            $data = $this->Entrevista->getEntrevistas($Id_Persona_Entrevista);
            echo json_encode($data);
        }
    }
    public function getRemisiones(){//FUNCION QUE OBTINE EL CATALOGO DE LAS REMISIONES
        $data = $this->Entrevista->getRemisiones();
        echo json_encode($data);
    }
    public function getPersonaSeguimiento(){//FUNCION QUE OBTIENE LA INFORMACION DE LAS PERSONAS DEL SEGUIMIENTO
        $data = $this->Entrevista->getPersonaSeguimiento();
        echo json_encode($data);
    }
    public function getGrupoDelictivoSeguimiento(){//FUNCION QUE OBTIENE LA INFORMACION DE LAS PERSONAS DEL SEGUIMIENTO
        $data = $this->Entrevista->getGrupoDelictivoSeguimiento();
        echo json_encode($data);
    }
    public function getIndicativos(){//FUNCION QUE OBTIENE LA INFORMACION DE LOS INDICATIVOS
        $data = $this->Entrevista->getIndicativos();
        echo json_encode($data);
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
    /*----------------FUNCIONES PARA DESASOCIAR DATOS DE TABLAS------------------------ */
    public function DesasociaEntrevista(){//FUNCION PARA ELIMINAR UNA PERSONA DE LA TABLA CON ID EN BASE DE DATOS
        if (isset($_POST['Id_Entrevista']) && isset($_POST['Id_Persona_Entrevista'])) {
            $Id_Entrevista = $_POST['Id_Entrevista'];
            $Id_Persona_Entrevista= $_POST['Id_Persona_Entrevista'];
            $data = $this->Entrevista->DesasociaEntrevista($Id_Entrevista);
            if ($data['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $data['sqlEjecutados']);
                $descripcion = 'ELIMINO LA ENTREVISTA: '.$Id_Entrevista.' DEL LA PERSONA '.$Id_Persona_Entrevista.' EL USUARIO '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $this->Entrevista->historial($user, $ip, 34, $descripcion);//Guarda en el historial el movimiento
            } 

            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Entrevistas");
            exit();
        }
    }
    public function DesasociaForensia(){//FUNCION PARA ELIMINAR UNA PERSONA DE LA TABLA CON ID EN BASE DE DATOS
        if (isset($_POST['Id_Forensia_Entrevista']) && isset($_POST['Id_Persona_Entrevista'])) {
            $Id_Forensia_Entrevista = $_POST['Id_Forensia_Entrevista'];
            $Id_Persona_Entrevista= $_POST['Id_Persona_Entrevista'];
            $data = $this->Entrevista->DesasociaForensia($Id_Forensia_Entrevista);
            if ($data['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $data['sqlEjecutados']);
                $descripcion = 'ELIMINO LA FORENSIA DE ENTREVISTA: '.$Id_Forensia_Entrevista.' DEL LA PERSONA '.$Id_Persona_Entrevista.' EL USUARIO '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $this->Entrevista->historial($user, $ip, 34, $descripcion);//Guarda en el historial el movimiento
            } 

            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Entrevistas");
            exit();
        }
    }
    public function DesasociaUbicacion(){//FUNCION PARA ELIMINAR UNA PERSONA DE LA TABLA CON ID EN BASE DE DATOS
        if (isset($_POST['Id_Ubicacion']) && isset($_POST['Id_Persona_Entrevista'])) {
            $Id_Ubicacion = $_POST['Id_Ubicacion'];
            $Id_Persona_Entrevista= $_POST['Id_Persona_Entrevista'];
            $data = $this->Entrevista->DesasociaUbicacion($Id_Ubicacion);
            if ($data['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $data['sqlEjecutados']);
                $descripcion = 'ELIMINO LA UBICACION DE ENTREVISTA: '.$Id_Ubicacion.' DEL LA PERSONA '.$Id_Persona_Entrevista.' EL USUARIO '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $this->Entrevista->historial($user, $ip, 34, $descripcion);//Guarda en el historial el movimiento
            } 

            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Entrevistas");
            exit();
        }
    }
    public function DesasociaRedSocial(){//FUNCION PARA ELIMINAR UNA PERSONA DE LA TABLA CON ID EN BASE DE DATOS
        if (isset($_POST['Id_Registro']) && isset($_POST['Id_Persona_Entrevista'])) {
            $Id_Registro = $_POST['Id_Registro'];
            $Id_Persona_Entrevista= $_POST['Id_Persona_Entrevista'];
            $data = $this->Entrevista->DesasociaRedSocial($Id_Registro);
            if ($data['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $data['sqlEjecutados']);
                $descripcion = 'ELIMINO DATO RED SOCIAL DE ENTREVISTA: '.$Id_Registro.' DEL LA PERSONA '.$Id_Persona_Entrevista.' EL USUARIO '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $this->Entrevista->historial($user, $ip, 34, $descripcion);//Guarda en el historial el movimiento
            } 

            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Entrevistas");
            exit();
        }
    }



    //funcion para borrar variable sesión para filtro de rangos de fechas
    public function removeRangosFechasSesion(){
        if (isset($_REQUEST['filtroActual'])) {
            unset($_SESSION['userdataSIC']->rango_inicio_es);
            unset($_SESSION['userdataSIC']->rango_fin_es);

            header("Location: " . base_url . "Entrevistas/index/?filtro=" . $_REQUEST['filtroActual']);
            exit();
        }
    }
    public function getPrincipales(){//FUNCION QUE OBTIENE LOS DATOS PRINCIPALES DE LA PERSONA ENTREVISTADA
        if (isset($_POST['Id_Persona_Entrevista'])) {
            $Id_Persona_Entrevista = $_POST['Id_Persona_Entrevista'];
            $data = $this->Entrevista->getPrincipales($Id_Persona_Entrevista);
            $ip = $this->obtenerIp();
            $user = $_SESSION['userdataSIC']->Id_Usuario;
            $descripcion = 'VER INFORMACION DE LA ENTREVISTA: '.$_SESSION['userdataSIC']->User_Name.' ID PERSONA ENTREVISTADA '.$Id_Persona_Entrevista;
            $success_3=$this->Entrevista->historial($user, $ip, 31, $descripcion);//GUARDA EL MOVIMIENTO EN EL HISTORIAL
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Entrevistas");
            exit();
        }
    }

    /* ----------------------------------------GUARDAR FOTO DETENIDO ENTREVISTADO------------------------------------ */
    public function GuardarFotoDetenido($id_persona_entrevista){
        $path_carpeta = BASE_PATH . "public/files/Entrevistas/" . $id_persona_entrevista."/" ;
        if($_POST['Foto']!='SD'){
            if($_POST['typeImage']=='File'){
                $result = $this->uploadImageFileDetenido('FotoDetenido', $_FILES,$path_carpeta, $_POST['Foto']);//Escritura de fotos en la carpeta
            }else{
                $result = $this->uploadImagePhotoDetenido($_POST['Img_64'],$path_carpeta,$path_carpeta.$_POST['Foto']);//Escritura de fotos en la carpeta
            }
        }
    }
    /* ----- ----- ----- Funciones para guardar la imagenes en el servidor  ----- ----- ----- */
    public function uploadImageFileDetenido($name, $file, $carpeta, $fileName){
        $type = $file[$name]['type'];
        $extension = explode("/", $type);

        $imageUploadPath = $carpeta . $fileName;
        $allowed_mime_type_arr = array('jpeg', 'png', 'jpg', 'PNG');

        if (!file_exists($carpeta)){//si no existe la carpeta se crea
            mkdir($carpeta, 0777, true);
        }
        if (in_array($extension[1], $allowed_mime_type_arr)) {
            $img_temp = $file[$name]['tmp_name'];
            $compressedImg = $this->compressImage($img_temp, $imageUploadPath, 75);
            $band = true;
        } else {
            $band = false;
        }
        return $band;
    }
    public function uploadImagePhotoDetenido($img, $carpeta, $ruta){

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
    public function GeneraPDF(){//GENERA PDF CON LA INFORMACION DE TODO LA INFORMACION DE LA PERSONA ENTREVISTADA
        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }else{
            $data=$this->Entrevista->permisoVisualizacion($_SESSION['userdataSIC']->User_Name);
            $_SESSION['userdataSIC']->Visualizacion = $data->Visualizacion;
        }
        if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Entrevistas[2] == '1'){
            if (isset($_GET['Id_Persona_Entrevista']) ){
                $Id_Persona_Entrevista= $_GET['Id_Persona_Entrevista'];

                $info_Persona=[
                    'Principales'=> $this->Entrevista->getPrincipales($Id_Persona_Entrevista),   
                    'Entrevistas'=> $this->Entrevista->getEntrevistas($Id_Persona_Entrevista),
                    'Forensias'=> $this->Entrevista->getForensias($Id_Persona_Entrevista),
                    'Ubicaciones'=> $this->Entrevista->getUbicaciones($Id_Persona_Entrevista),
                    'Redes_Sociales'=> $this->Entrevista->getRedesSociales($Id_Persona_Entrevista)
                ];
            }else{
                header("Location: " . base_url . "Entrevistas");
                exit();
    
            }
            $this->view('system/entrevistas/fichaEntrevistasView', $info_Persona);
            $user = $_SESSION['userdataSIC']->Id_Usuario;
            $ip = $this->obtenerIp();
            $descripcion = 'EXPORTACION DE FICHA DE ENTREVISTA PDF: '.$_SESSION['userdataSIC']->User_Name.' ENTREVISTA: '.$Id_Persona_Entrevista;
            $this->Entrevista->historial($user, $ip, 36, $descripcion);//GUARDA EL MOVIMIENTO EN EL HISTORIAL
    
        }else{
            header("Location: " . base_url . "Estadisticas");
            exit();
    
        }
    }
    public function getForensiasSelect(){//FUNCION QUE OBTIENE LA INFORMACION DE LAS FORENCIAS DE LAS PERSONAS DEL SEGUIMIENTO
        if(isset($_POST['Id_Persona_Entrevista'])){
            $Id_Persona_Entrevista=$_POST['Id_Persona_Entrevista'];
            $data = $this->Entrevista->getForensiasSelect($Id_Persona_Entrevista);
            echo json_encode($data);
        }
    }
}
?>