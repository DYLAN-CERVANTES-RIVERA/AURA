<?php

    /*controlador del modulo de historial
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
    23.-INSERCION DE UN NUEVO SEGUIMIENTO
    24.-CONSULTA EN EL MODULO DE SEGUIMIENTO
    25.-VER SEGUIMIENTO
    26.-ACTUALIZACION DE SEGUIMIENTO TAB PRINCIPAL
    27.-ACTUALIZACION DE SEGUIMIENTO TABS SECUNDARIAS
    28.-ELIMINACION DE DATO EN LAS TABS DEL SEGUIMIENTOS
    29.-EXPORTACION DE INFORMACION DEL MODULO DE SEGUIMIENTOS
	30.-INSERCION DE UNA NUEVA PERSONA QUE SE ENTREVISTO 
	31.-VER INFORMACION DE LA ENTREVISTA
	32.-ACTUALIZACION DE LA TAB PRINCIPAL DE PERSONA ENTREVISTADA 
	33.-ACTUALIZACION DE TAB SECUNDARIA DE ENTREVISTA 
	34.-ELIMINACION DE ELEMENTO DE ALGUNA TABLA 
	35.-CONSULTA BUSQUEDA EN MODULO ENTREVISTAS
	36.-EXPORTACION DE INFORMACION MODULO ENTREVISTAS
	37.-CONSULTA BUSQUEDA EN MODULO ESTADISTICAS
	38.-CAMBIO DE TIPO DE RED ALTO IMPACTO 
	39.-INSERCION DE UN NUEVO PUNTO
	40.-VER INFORMACION DE UN PUNTO
	41.-ACTUALIZACION DE INFORMACION PUNTO
	42.-CONSULTA BUSQUEDA EN MODULO PUNTOS
	43.-EXPORTACION DE INFORMACION MODULO PUNTOS
    */
	class Historiales extends Controller
	{
		public $Historial;

		public function __construct()
		{
			$this->Historial = $this->model('Historial');
			$this->numColumnsHIS = [5,5,5,5,5,5,5,5,5];
		}

		public function index()
		{
			if(!isset($_SESSION['userdataSIC']) || ($_SESSION['userdataSIC']->Modo_Admin !=1)){
				if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA

					header("Location: " . base_url . "Login");
					exit();
				}
				header("Location: ". base_url ."GestorCasos");
				exit();
			}

			$data = [
				'titulo'    => 'AURA | Historial',
				'extra_css' => '<link rel="stylesheet" href="'. base_url . 'public/css/admin/index.css">',
				'extra_js'  => '<script src="'. base_url . 'public/js/system/historial/index.js"></script>'
			];

			if(isset($_GET['filtro']) && is_numeric($_GET['filtro']) && $_GET['filtro'] >= MIN_FILTRO_HIS && $_GET['filtro'] <= MAX_FILTRO_HIS){
				$filtro = $_GET['filtro'];
			}else{
				$filtro = 1;
			}

			$this->setColumnsSession($filtro);
			$data['columns_HIS'] = $_SESSION['userdataSIC']->columns_HIS;
			if(isset($_POST['rango_inicio']) && isset($_POST['rango_fin'])){//verifica si existe un filtro de fechas en el modulo 
				$_SESSION['userdataSIC']->rango_inicio_his = $_POST['rango_inicio'];
            	$_SESSION['userdataSIC']->rango_fin_his = $_POST['rango_fin'];
			}

			if(isset($_GET['numPage'])){
				$numPage = $_GET['numPage'];
				if(!(is_numeric($numPage))){
					$numPage = 1;
				}
			}else{
				$numPage = 1;
			}

			$cadena = "";
			if(isset($_GET['cadena'])){
				$cadena = $_GET['cadena'];
				$data['cadena'] = $cadena;
			}

			$where_sentence = $this->Historial->generateWhereSentence($cadena,$filtro);//GENERA EL QUERY PARA LA INFORMACION
			$extra_cad = ($cadena != "") ? ("&cadena=".$cadena) : "";

			$no_of_records_per_page = NUM_MAX_REG_PAGE;
			$offset = ($numPage -1 ) * $no_of_records_per_page;

			$results_rows_pages = $this->Historial->getTotalPages($no_of_records_per_page,$where_sentence);
			$total_pages = $results_rows_pages['total_pages'];

			if($numPage > $total_pages){
				$numPage = 1;
				$offset = ($numPage -1) * $no_of_records_per_page;
			}

			$rows_his = $this->Historial->getDataCurrentPage($offset, $no_of_records_per_page,$where_sentence);

			$data['infoTable'] = $this->generateInfoTable($rows_his,$filtro);
			$data['links'] = $this->generateLinks($numPage, $total_pages, $extra_cad, $filtro);
			$data['total_rows'] = $results_rows_pages['total_rows'];
			$data['filtroActual'] = $filtro;
			$data['dropdownColumns'] = $this->generateDropdownColumns($filtro);

			switch($filtro){//Filtros del modulo
				case '1':
					$data['filtroNombre'] = "TODOS LOS MOVIMIENTOS";
				break;
				case '2':
					$data['filtroNombre'] = "INICIO DE SESION";
				break;
				case '3':
					$data['filtroNombre'] = "VER EVENTO";
				break;
				case '4':
					$data['filtroNombre'] = "INSERCION DE EVENTO";
				break;
				case '5':
					$data['filtroNombre'] = "ACTUALIZACION DE ENTREVISTA";
				break;
				case '6':
					$data['filtroNombre'] = "ACTUALIZACION DE FOTOS";
				break;
				case '7':
					$data['filtroNombre'] = "ACTUALIZACION DE EVENTO";
				break;
				case '8':
					$data['filtroNombre'] = "CONSULTA EN EL MODULO DE GESTOR DE CASOS";
				break;
				case '9':
					$data['filtroNombre'] = "EXPORTACION DE INFO EN EL MODULO DE GESTOR DE CASOS";
				break;
				
			}


			$data['prueba'] = $rows_his;

			$this->view('templates/header', $data);
			$this->view('system/historial/historialView', $data);
			$this->view('templates/footer', $data);
		}

		public function generateInfoTable($rows,$filtro=1)//Genera la informacion de la tabla del modulo
		{
			$infoTable['header'] = "";
			$infoTable['body'] = "";
			$infoTable['header'].='
				<th class="column1">Usuario</th>
				<th class="column2">Fecha y hora</th>
				<th class="column3">Ip Acceso</th>
				<th class="column4">Movimiento</th>
				<th class="column5">Descripción</th>
			';
			//print_r($rows);
			foreach($rows as $row){
				switch($row->Movimiento){//le concatena una especificacion mas descriptiva del movimiento
					case '1':
						$movimiento = $row->Movimiento.'. INICIO DE SESION';
					break;
					case '2':
						$movimiento = $row->Movimiento.'. VER EVENTO';
					break;
					case '3':
						$movimiento = $row->Movimiento.'. INSERCION DE EVENTO';
					break;
					case '4':
						$movimiento = $row->Movimiento.'. ACTUALIZACION DE ENTREVISTA';
					break;
					case '5':
						$movimiento = $row->Movimiento.'. ACTUALIZACION DE FOTOS';
					break;
					case '6':
						$movimiento = $row->Movimiento.'. ACTUALIZACION DE EVENTO';
					break;
					case '7':
						$movimiento = $row->Movimiento.'. CONSULTA EN EL MODULO DE GESTOR DE CASOS';
					break;
					case '8':
						$movimiento = $row->Movimiento.'. EXPORTACION DE INFORMACION EN EL MODULO DE GESTOR DE CASOS';
					break;
					case '9':
						$movimiento = $row->Movimiento.'. TERMINO SEGUIMIENTO';
					break;
					case '10':
						$movimiento = $row->Movimiento.'. CAMBIO SU CONTRASEÑA';
					break;
					case '11':
						$movimiento = $row->Movimiento.'. CAMBIO SU FOTO';
					break;
					case '12':
						$movimiento = $row->Movimiento.'. CREO UN USUARIO';
					break;
					case '13':
						$movimiento = $row->Movimiento.'. ACTUALIZO INFORMACION DE UN USUARIO';
					break;
					case '14':
						$movimiento = $row->Movimiento.'. VIO INFORMACION DE USUARIO';
					break;
					case '15':
						$movimiento = $row->Movimiento.'. CONSULTO EN EL MODULO DE USUARIO';
					break;
					case '16':
						$movimiento = $row->Movimiento.'. EXPORTO INFORMACION DE USUARIOS';
					break;
					case '17':
						$movimiento = $row->Movimiento.'. ELIMINO REGISTRO CATALOGO ';
					break;
					case '18':
						$movimiento = $row->Movimiento.'. CREO REGISTRO CATALOGO';
					break;
					case '19':
						$movimiento = $row->Movimiento.'. VER REGISTRO CATALOGO';
					break;
					case '20':
						$movimiento = $row->Movimiento.'. ACTUALIZO REGISTRO CATALOGO';
					break;
					case '21':
						$movimiento = $row->Movimiento.'. CONSULTO REGISTRO CATALOGO';
					break;
					case '22':
						$movimiento = $row->Movimiento.'. EXPORTACION DE INFORMACION DE CATALOGOS ';
					break;
					case '23':
						$movimiento = $row->Movimiento.'. INSERCION DE UN NUEVO SEGUIMIENTO ';
					break;
					case '24':
						$movimiento = $row->Movimiento.'. CONSULTA EN EL MODULO DE SEGUIMIENTO ';
					break;
					case '25':
						$movimiento = $row->Movimiento.'. VER SEGUIMIENTO ';
					break;
					case '26':
						$movimiento = $row->Movimiento.'. ACTUALIZACION DE SEGUIMIENTO TAB PRINCIPAL ';
					break;
					case '27':
						$movimiento = $row->Movimiento.'. ACTUALIZACION DE SEGUIMIENTO TABS SECUNDARIAS ';
					break;
					case '28':
						$movimiento = $row->Movimiento.'. ELIMINACION DE DATO EN LAS TABS DEL SEGUIMIENTOS ';
					break;
					case '29':
						$movimiento = $row->Movimiento.'. EXPORTACION DE INFORMACION DEL MODULO DE SEGUIMIENTOS ';
					break;					
					case '30':
						$movimiento = $row->Movimiento.'. INSERCION DE UNA NUEVA PERSONA QUE SE ENTREVISTO ';
					break;
					case '31':
						$movimiento = $row->Movimiento.'. VER INFORMACION DE LA ENTREVISTA ';
					break;
					case '32':
						$movimiento = $row->Movimiento.'. ACTUALIZACION DE LA TAB PRINCIPAL DE PERSONA ENTREVISTADA ';
					break;
					case '33':
						$movimiento = $row->Movimiento.'. ACTUALIZACION DE TAB SECUNDARIA DE ENTREVISTA ';
					break;
					case '34':
						$movimiento = $row->Movimiento.'. ELIMINACION DE DATOS DE ALGUNA TABLA DE ENTREVISTA ';
					break;
					case '35':
						$movimiento = $row->Movimiento.'. CONSULTA BUSQUEDA EN MODULO ENTREVISTAS ';
					break;
					case '36':
						$movimiento = $row->Movimiento.'. EXPORTACION DE INFORMACION MODULO ENTREVISTAS ';
					break;
					case '37':
						$movimiento = $row->Movimiento.'. CONSULTA MODULO ESTADISTICAS ';
					break;
					case '38':
						$movimiento = $row->Movimiento.'. MODIFICACION DE TIPO DE RED DE ALTO IMPACTO ';
					break;
					case '39':
						$movimiento = $row->Movimiento.'. INSERCION DE UN NUEVO PUNTO ';
					break;
					case '40':
						$movimiento = $row->Movimiento.'. VER INFORMACION DE UN PUNTO ';
					break;
					case '41':
						$movimiento = $row->Movimiento.'. ACTUALIZACION DE INFORMACION PUNTO ';
					break;
					case '42':
						$movimiento = $row->Movimiento.'. CONSULTA BUSQUEDA EN MODULO PUNTOS ';
					break;
					case '43':
						$movimiento = $row->Movimiento.'. EXPORTACION DE INFORMACION MODULO PUNTOS';
					break;
					default:
						$movimiento = $row->Movimiento;
					break;
				}
				$infoTable['body'].='<tr>';
					$infoTable['body'].='
					<td class="column1">'.$row->User_Name.'</td>
					<td class="column2">'.$row->Fecha_Hora.'</td>
					<td class="column3">'.$row->Ip_Acceso.'</td>
					<td class="column4">'.$movimiento.'</td>
					<td class="column5">'.$row->Descripcion.'</td>
				';
				$infoTable['body'].='</tr>';
			}

			return $infoTable;
		}

		public function generateLinks($numPage, $total_pages, $extra_cad ="", $filtro = 1)
		{
			//$extra_cad sirve para determinar la paginacion conforme a si se realizó una busqueda
        	//Creación de links para la paginacion
			$links = "";

			if($numPage>1){
				$links.='<li>
							<a class="page-link" href="'.base_url.'Historiales/?numPage=1'.$extra_cad.'&filtro='.$filtro.'" data-toggle="tooltip" data-placement="top" title="Primera página">
								<i class="material-icons">first_page</i>
							</a>
						</li>';
				$links.='<li class="page-item">
							<a class="page-link" href=" '.base_url.'Historiales/?numPage='.($numPage-1).$extra_cad.'&filtro='.$filtro.' " data-toggle="tooltip" data-placement="top" title="Página anterior">
								<i class="material-icons">navigate_before</i>
							</a>
						</li>';
			}
			//DESPLIEGUE DE PAGES NUMBER
			$LINKS_EXTREMOS = GLOBAL_LINKS_EXTREMOS;//numero máximo de links a la izquierda y a la derecha
			for($ind=($numPage-$LINKS_EXTREMOS); $ind<=($numPage+$LINKS_EXTREMOS); $ind++){
				if(($ind>=1) && ($ind<= $total_pages)){
					$activeLink = ($ind == $numPage) ? 'active':'';

					$links.='<li class="page-item '.$activeLink.' ">
								<a class="page-link" href=" '.base_url.'Historiales/?numPage='.($ind).$extra_cad.'&filtro='.$filtro.' ">
									'.($ind).'
								</a>
							</li>';
				}
			}
			 //FLECHA DERECHA (NEXT PAGINATION)
			if($numPage<$total_pages){
				$links.= '<li class="page-item">
                            <a class="page-link" href=" '.base_url.'Historiales/?numPage='.($numPage+1).$extra_cad.'&filtro='.$filtro.' " data-toggle="tooltip" data-placement="top" title="Siguiente página">
                            	<i class="material-icons">navigate_next</i>
                            </a>
                        </li>';
                $links.= '<li class="page-item">
                            <a class="page-link" href=" '.base_url.'Historiales/?numPage='.($total_pages).$extra_cad.'&filtro='.$filtro.' " data-toggle="tooltip" data-placement="top" title="Última página">
                           		<i class="material-icons">last_page</i>
                            </a>
                        </li>';
			}

			return $links;
		}

		public function generateDropdownColumns($filtro=1)//función que filtra las columnas deseadas por el usuario
		{	
			$dropdownColumn = "";

			$campos = ['Usuario','Fecha y Hora','Ip Acceso','Movimiento', 'Descripción'];

			$ind = 1;
			foreach($campos as $campo){
				$checked = ($_SESSION['userdataSIC']->columns_HIS['column'.$ind] == 'show') ? 'checked':'';
				$dropdownColumn.=   '<div class="form-check">
										<input class="form-check-input checkColumns" type="checkbox" value="'.$_SESSION['userdataSIC']->columns_HIS['column'.$ind].'" onchange="hideShowColumn(this.id);" id="column'.$ind.'" '.$checked.'>
										<label class="form-check-label" for="column'.$ind.'">
											'.$campo.'
										</label>
									</div>';
				$ind++;
			}

			$dropdownColumn.= 	'<div class="dropdown-divider">
                            	</div>
                                <div class="form-check">
                                    <input id="checkAll" class="form-check-input" type="checkbox" value="hide" onchange="hideShowAll(this.id);" id="column'.$ind.'" checked>
                                    <label class="form-check-label" for="column'.$ind.'">
                                        Todo
                                    </label>
                                </div>';

			return $dropdownColumn;
		}
		/* ----------------------------------------FUNCIONES DE FILTROS ------------------------------------- */
    	//función para checar los cambios de filtro y poder asignar los valores correspondientes de las columnas a la session

		public function setColumnsSession($filtroActual=1)
		{
			if(isset($_SESSION['userdataSIC']->filtro_HIS) && $_SESSION['userdataSIC']->filtro_HIS >= MIN_FILTRO_HIS && $_SESSION['userdataSIC']->filtro_HIS <= MAX_FILTRO_HIS)	{
				if($_SESSION['userdataSIC']->filtro_HIS != $filtroActual){
					$_SESSION['userdataSIC']->filtro_HIS = $filtroActual;
					unset($_SESSION['userdataSIC']->columns_HIS);
					for($i=0;$i<$this->numColumnsHIS[$_SESSION['userdataSIC']->filtro_HIS - 1]; $i++){
						$_SESSION['userdataSIC']->columns_HIS['column'.($i+1)] = 'show';
					}
				}
			}else{
				$_SESSION['userdataSIC']->filtro_HIS = $filtroActual;
				unset($_SESSION['userdataSIC']->columns_HIS);
				for($i=0;$i<$this->numColumnsHIS[$_SESSION['userdataSIC']->filtro_HIS - 1]; $i++){
					$_SESSION['userdataSIC']->columns_HIS['column'.($i+1)] = 'show';
				}
			}
		}
		//función fetch que actualiza los valores de las columnas para la session
		public function setColumnFetch(){
			if(isset($_POST['columnName']) && isset($_POST['valueColumn'])){
				$_SESSION['userdataSIC']->columns_HIS[$_POST['columnName']] = $_POST['valueColumn'];
				echo json_encode('ok');
			}
		}
		//funcion para borrar variable sesión para filtro de rangos de fechas
		public function removeRangosFechasSesion()
		{
			if(isset($_REQUEST['filtroActual'])){
				unset($_SESSION['userdataSIC']->rango_inicio_his);
				unset($_SESSION['userdataSIC']->rango_fin_his);

				header("Location: ".base_url."Historiales/?filtro=".$_REQUEST['filtroActual']);
				exit();
			}
		}
		//Funcion para buscar lo que le escribes en el panel de texto buscar
		public function buscarPorCadena()
		{
			if(isset($_POST['cadena'])){
				$cadena = trim($_POST['cadena']);
				$filtroActual = trim($_POST['filtroActual']);

				$results = $this->Historial->getHistorialByCadena($cadena,$filtroActual);
				$extra_cad = ($cadena != "")?("&cadena=".$cadena):"";

				$dataReturn['infoTable'] = $this->generateInfoTable($results['rows_Hisroriales'],$filtroActual);
				$dataReturn['links'] = $this->generateLinks($results['numPage'],$results['total_pages'],$extra_cad,$filtroActual);

				$dataReturn['export_links'] = $this->generateExportLinks($extra_cad,$filtroActual);
				$dataReturn['total_rows'] = "Total registros: ".$results['total_rows'];
				$dataReturn['dropdownColumns'] = $this->generateDropdownColumns($filtroActual);

				echo json_encode($dataReturn);
			}
		}

		public function generateExportLinks($extra_cad = "",$filtro = 1)//Funcion para exportar la informacion 
		{
			if($extra_cad != ""){
				$dataReturn['csv']   =  base_url.'Historiales/exportarInfo/?tipo_export=CSV'.$extra_cad.'&filtroActual='.$filtro;
				$dataReturn['excel'] =  base_url.'Historiales/exportarInfo/?tipo_export=EXCEL'.$extra_cad.'&filtroActual='.$filtro;
				$dataReturn['pdf']   =  base_url.'Historiales/exportarInfo/?tipo_export=PDF'.$extra_cad.'&filtroActual='.$filtro;
			}else{
				$dataReturn['csv']   =  base_url.'Historiales/exportarInfo/?tipo_export=CSV'.$extra_cad.'&filtroActual='.$filtro;
				$dataReturn['excel'] =  base_url.'Historiales/exportarInfo/?tipo_export=EXCEL'.$extra_cad.'&filtroActual='.$filtro;
				$dataReturn['pdf']   =  base_url.'Historiales/exportarInfo/?tipo_export=PDF'.$extra_cad.'&filtroActual='.$filtro;
			}

			return $dataReturn;
		}

		public function exportarInfo()//Funcion para Exportar informacion en excel
		{
			ini_set('memory_limit', '3072M');//Funcion para poder exportar un archivo de gran tamaño
			if(!isset($_REQUEST['tipo_export'])){
				header("Location: ".base_url."Estadisticas");
				exit();
			}

			if(!isset($_REQUEST['filtroActual']) || !is_numeric($_REQUEST['filtroActual']) || !($_REQUEST['filtroActual']>=MIN_FILTRO_HIS) || !($_REQUEST['filtroActual']<=MAX_FILTRO_HIS)){
				$filtroActual = 1;
			}else{
				$filtroActual = $_REQUEST['filtroActual'];
			}

			$from_where_sentence = "";

			if(isset($_REQUEST['cadena'])){//Verifica si existe una Cadena para consulta
				$from_where_sentence = $this->Historial->generateWhereSentence($_REQUEST['cadena'],$filtroActual);//excel con consulta
			}else{
				$from_where_sentence = $this->Historial->generateWhereSentence("",$filtroActual);//Excel sin consulta
			}

			$tipo_export = $_REQUEST['tipo_export'];

			if($tipo_export == 'EXCEL'){
				$rows_HIS = $this->Historial->getAllInfoHistorialByCadena($from_where_sentence);
				$filename = 'HIS_general';
				$csv_data = "Usuario,Fecha y Hora,Ip Acceso,Movimiento,Descripción\n";
				foreach ($rows_HIS as $row) {
					switch($row->Movimiento){// le concatena mas informacion acerca del movimiento que se exporatara en excel
						case '1':
							$movimiento = $row->Movimiento.'. INICIO DE SESION';
						break;
						case '2':
							$movimiento = $row->Movimiento.'. VER EVENTO';
						break;
						case '3':
							$movimiento = $row->Movimiento.'. INSERCION DE EVENTO';
						break;
						case '4':
							$movimiento = $row->Movimiento.'. ACTUALIZACION DE ENTREVISTA';
						break;
						case '5':
							$movimiento = $row->Movimiento.'. ACTUALIZACION DE FOTOS ';
						break;
						case '6':
							$movimiento = $row->Movimiento.'. ACTUALIZACION DE EVENTO ';
						break;
						case '7':
							$movimiento = $row->Movimiento.'. CONSULTA EN EL MODULO DE GESTOR DE CASOS';
						break;
						case '8':
							$movimiento = $row->Movimiento.'. EXPORTACION DE INFORMACION EN EL MODULO DE GESTOR DE CASOS';
						break;
						case '9':
							$movimiento = $row->Movimiento.'. TERMINO SEGUIMIENTO';
						break;
						case '10':
							$movimiento = $row->Movimiento.'. CAMBIO SU CONTRASEÑA';
						break;
						case '11':
							$movimiento = $row->Movimiento.'. CAMBIO SU FOTO';
						break;
						case '12':
							$movimiento = $row->Movimiento.'. CREO UN USUARIO';
						break;
						case '13':
							$movimiento = $row->Movimiento.'. ACTUALIZO INFORMACION DE UN USUARIO';
						break;
						case '14':
							$movimiento = $row->Movimiento.'. VIO INFORMACION DE USUARIO';
						break;
						case '15':
							$movimiento = $row->Movimiento.'. CONSULTO EN EL MODULO DE USUARIO';
						break;
						case '16':
							$movimiento = $row->Movimiento.'. EXPORTO INFORMACION DE USUARIOS';
						break;
						case '17':
							$movimiento = $row->Movimiento.'. ELIMINO REGISTRO CATALOGO ';
						break;
						case '18':
							$movimiento = $row->Movimiento.'. CREO REGISTRO CATALOGO';
						break;
						case '19':
							$movimiento = $row->Movimiento.'. VER REGISTRO CATALOGO';
						break;
						case '20':
							$movimiento = $row->Movimiento.'. ACTUALIZO REGISTRO CATALOGO';
						break;
						case '21':
							$movimiento = $row->Movimiento.'. CONSULTO REGISTRO CATALOGO';
						break;
						case '22':
							$movimiento = $row->Movimiento.'. EXPORTACION DE INFORMACION DE CATALOGOS ';
						break;
						case '23':
							$movimiento = $row->Movimiento.'. INSERCION DE UN NUEVO SEGUIMIENTO ';
						break;
						case '24':
							$movimiento = $row->Movimiento.'. CONSULTA EN EL MODULO DE SEGUIMIENTO ';
						break;
						case '25':
							$movimiento = $row->Movimiento.'. VER SEGUIMIENTO ';
						break;
						case '26':
							$movimiento = $row->Movimiento.'. ACTUALIZACION DE SEGUIMIENTO TAB PRINCIPAL ';
						break;
						case '27':
							$movimiento = $row->Movimiento.'. ACTUALIZACION DE SEGUIMIENTO TABS SECUNDARIAS ';
						break;
						case '28':
							$movimiento = $row->Movimiento.'. ELIMINACION DE DATO EN LAS TABS DEL SEGUIMIENTOS ';
						break;
						case '29':
							$movimiento = $row->Movimiento.'. EXPORTACION DE INFORMACION DEL MODULO DE SEGUIMIENTOS ';
						break;
						case '30':
							$movimiento = $row->Movimiento.'. INSERCION DE UNA NUEVA PERSONA QUE SE ENTREVISTO ';
						break;
						case '31':
							$movimiento = $row->Movimiento.'. VER INFORMACION DE LA ENTREVISTA ';
						break;
						case '32':
							$movimiento = $row->Movimiento.'. ACTUALIZACION DE LA TAB PRINCIPAL DE PERSONA ENTREVISTADA ';
						break;
						case '33':
							$movimiento = $row->Movimiento.'. ACTUALIZACION DE TAB SECUNDARIA DE ENTREVISTA ';
						break;
						case '34':
							$movimiento = $row->Movimiento.'. ELIMINACION DE DATOS DE ALGUNA TABLA DE ENTREVISTA ';
						break;
						case '35':
							$movimiento = $row->Movimiento.'. CONSULTA BUSQUEDA EN MODULO ENTREVISTAS ';
						break;
						case '36':
							$movimiento = $row->Movimiento.'. EXPORTACION DE INFORMACION MODULO ENTREVISTAS ';
						break;
						case '37':
							$movimiento = $row->Movimiento.'. CONSULTA MODULO ESTADISTICAS ';
						break;
						case '38':
							$movimiento = $row->Movimiento.'. MODIFICACION DE TIPO DE RED DE ALTO IMPACTO ';
						break;
						case '39':
							$movimiento = $row->Movimiento.'. INSERCION DE UN NUEVO PUNTO ';
						break;
						case '40':
							$movimiento = $row->Movimiento.'. VER INFORMACION DE UN PUNTO ';
						break;
						case '41':
							$movimiento = $row->Movimiento.'. ACTUALIZACION DE INFORMACION PUNTO ';
						break;
						case '42':
							$movimiento = $row->Movimiento.'. CONSULTA BUSQUEDA EN MODULO PUNTOS ';
						break;
						case '43':
							$movimiento = $row->Movimiento.'. EXPORTACION DE INFORMACION MODULO PUNTOS';
						break;
						default:
							$movimiento = $row->Movimiento;
						break;
					}
					$Descripcion=$this->tratamiento($row->Descripcion);
					$csv_data.= $row->User_Name.",\"".
								$row->Fecha_Hora.",\",\"".
								$row->Ip_Acceso."\",\"".
								$movimiento."\",\"".
								$Descripcion."\"\n";
				}
				
				$csv_data = utf8_decode($csv_data);

				header("Content-Description: File Transfer");
				header("Content-Type: application/force-download");
				header("Content-Disposition: attachment; filename=".$filename."historiales.csv");
				echo $csv_data;
			}elseif($tipo_export == 'PDF'){
				$data = [
					'titulo'    => 'Historial',
				];

				$rows_HIS = $this->Historial->getAllInfoHistorialByCadena($from_where_sentence);
				$data['infoTable'] = $this->generateInfoTable($rows_HIS,$filtroActual);

				$this->view('system/historial/His_general_view',$data);
			}else{
				header("Location: ".base_url."Historiales");
				exit();
			}
		}
		public function tratamiento($entrada){
			$text=$entrada;
			$quitar = array("'", "\"","\\","/","´",",","\n");
			$text = str_replace($quitar, '', $text);
			$sinSaltos = preg_replace('/\r\n|\r|\n/', '', $text);
			$espaciosReducidos = preg_replace('/\s+/', ' ', $sinSaltos);
			return $espaciosReducidos;
		}
	}
?>