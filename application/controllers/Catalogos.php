<?php
/*
    Catálogos:
    1  - Delitos / Faltas admin
    2  - Armas
    3 - Tipo de violencia
    4 - Zonas / sectores
    5 - Vectores
    6 - Marcas vehículos
	7 - Tipos de vehiculo
	8 - Submarcas de vehiculos
	9 - Colonias
	10 - Calles
	11 - Codigos postales
	12 - Nombres claves
	13 - Procedencia de informacion
	14 - Areas
	15 - Tipo de violencia SV
	16 - Indicativo Entrevistador
	17 - Tipo de datos Entrevistas
	18 - Ubicaciones de Camara Ciudad

*/
	/*
		Movimientos historial
	17	- ELIMINAR REGISTRO CATALOGO 
    18  - CREAR REGISTRO CATALOGO
    19  - VER REGISTRO CATALOGO
    20  - ACTUALIZAR REGISTRO CATALOGO
    21  - CONSULTAR REGISTRO CATALOGO 
    22  - EXPORTACION DE ARCHIVOS
	*/
class Catalogos extends Controller
{
	public $Catalogo;
	public $Historial;

	public function __construct()
	{
		$this->Catalogo = $this->model("Catalogo");
		$this->Historial = $this->model('Historial');
	}

	public function index(){

		if (!isset($_SESSION['userdataSIC']) || $_SESSION['userdataSIC']->Modo_Admin!=1) {
			if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA

				header("Location: " . base_url . "Login");
				exit();
			}
			header("Location: ". base_url ."GestorCasos");
			exit();
        }

        //Titulo de la pagina y archivos css y js necesarios
		$data = [
            'titulo'    => 'AURA | Catálogos',
            'extra_css' => '<link rel="stylesheet" href="'. base_url . 'public/css/system/catalogos/index.css">',
            'extra_js'  => '<script src="'. base_url . 'public/js/system/catalogos/index.js"></script>'
        ];


        $this->view("templates/header", $data);
        $this->view("system/catalogos/catalogosView", $data);
        $this->view("templates/footer", $data);
    }

    public function crudCatalogo(){
    	if (!isset($_SESSION['userdataSIC']) || $_SESSION['userdataSIC']->Modo_Admin!=1) {
                header("Location: ".base_url."Login");
                exit();
            }

            //Titulo de la pagina y archivos css y js necesarios
			$data = [
                'titulo'    => 'AURA | Catálogos',
                'extra_css' => '<link rel="stylesheet" href="'. base_url . 'public/css/system/catalogos/index.css">
                				<link rel="stylesheet" href="'. base_url . 'public/css/system/catalogos/crud.css">',
                'extra_js'  => '<script src="'. base_url . 'public/js/system/catalogos/crud.js"></script>'
            ];

            
            //PROCESO DE FILTRADO DE CATALOGO
            if (isset($_GET['catalogoActual']) && is_numeric($_GET['catalogoActual']) && $_GET['catalogoActual']>=MIN_CATALOGO && $_GET['catalogoActual']<=MAX_CATALOGO) { //numero de catálogo
		        $catalogoActual = $_GET['catalogoActual'];
		    } 
		    else {
		        $catalogoActual = 1;
		    }
            //PROCESO DE PAGINATION
			if (isset($_GET['numPage'])) { //numero de pagination
		        $numPage = $_GET['numPage'];
		        if (!(is_numeric($numPage))) //seguridad si se ingresa parámetro inválido
		        	$numPage = 1;
		    } 
		    else {
		        $numPage = 1;
		    }
		    //cadena auxiliar por si se trata de una paginacion conforme a una busqueda dada anteriormente
		    $cadena = "";
		    if (isset($_GET['cadena'])) { //numero de pagination
		        $cadena = $_GET['cadena'];
		        $data['cadena'] = $cadena;
		    }

		    $from_where_sentence = $this->Catalogo->generateFromWhereSentence($catalogoActual,$cadena);
		    $extra_cad = ($cadena != "")?("&cadena=".$cadena):""; //para links conforme a búsqueda

		    $no_of_records_per_page = NUM_MAX_REG_PAGE; //total de registros por pagination
		    $offset = ($numPage-1) * $no_of_records_per_page; // desplazamiento conforme a la pagina

		    $results_rows_pages = $this->Catalogo->getTotalPages($no_of_records_per_page,$from_where_sentence);	//total de páginas de acuerdo a la info de la DB
		    $total_pages = $results_rows_pages['total_pages'];

		    if ($numPage>$total_pages) {$numPage = 1; $offset = ($numPage-1) * $no_of_records_per_page;} //seguridad si ocurre un error por url 	
		    
		    $cat_rows = $this->Catalogo->getDataCurrentPage($offset,$no_of_records_per_page,$from_where_sentence);	//se obtiene la información de la página actual

		    //guardamos la tabulacion de la información para la vista
		    $data['infoTable'] = $this->generarInfoTable($cat_rows,$catalogoActual);
			//guardamos los links en data para la vista
			$data['links'] = $this->generarLinks($numPage,$total_pages,$extra_cad,$catalogoActual);
			//número total de registros encontrados
			$data['total_rows'] = $results_rows_pages['total_rows'];
			$data['catalogoActual'] = $catalogoActual;


            $this->view("templates/header", $data);
            $this->view("system/catalogos/catalogosCrudView", $data);
            $this->view("templates/footer", $data);
    }

    public function buscarPorCadena(){
		if (!isset($_SESSION['userdataSIC']) || ($_SESSION['userdataSIC']->Modo_Admin != 1)) {
			header("Location: ".base_url."Inicio");
			exit();
		}

		if (isset($_POST['cadena']) && isset($_POST['catalogoActual'])) {
			$cadena = trim($_POST['cadena']); 
			$catalogoActual = trim($_POST['catalogoActual']);

			$results = $this->Catalogo->getCatalogoByCadena($cadena,$catalogoActual);
			$extra_cad = ($cadena != "")?("&cadena=".$cadena):""; //para links conforme a búsqueda

			if(strlen($cadena)>0){
				$nombreCatalogo= $this->getNombreCatalogo($catalogoActual);
                $this->Historial->insertHistorial(21,'CONSULTA EN CATALOGO: '.$nombreCatalogo.' SE CONSULTO: '.$cadena,$_SESSION['userdataSIC']->Id_Usuario);
            }
			//$dataReturn = "jeje";

			$dataReturn['infoTable'] = $this->generarInfoTable($results['cat_rows'],$catalogoActual);
			$dataReturn['links'] = $this->generarLinks($results['numPage'],$results['total_pages'],$extra_cad,$catalogoActual);
			$dataReturn['export_links'] = $this->generarExportLinks($extra_cad,$catalogoActual);
			$dataReturn['total_rows'] = "Total registros: ".$results['total_rows'];

			
			echo json_encode($dataReturn);
		}
		else{
			header("Location: ".base_url."Inicio");
		}
	}

	public function generarExportLinks($extra_cad = "",$catalogoActual = 1){//Sirve para generar informacion para exportar a excel o pdf
		if ($extra_cad != "") {
			$dataReturn['csv'] =  base_url.'Catalogos/exportarInfo/?tipo_export=CSV'.$extra_cad.'&catalogoActual='.$catalogoActual;
			$dataReturn['excel'] =  base_url.'Catalogos/exportarInfo/?tipo_export=EXCEL'.$extra_cad.'&catalogoActual='.$catalogoActual;
			$dataReturn['pdf'] =  base_url.'Catalogos/exportarInfo/?tipo_export=PDF'.$extra_cad.'&catalogoActual='.$catalogoActual;
			//return $dataReturn;
		}
		else{
			$dataReturn['csv'] =  base_url.'Catalogos/exportarInfo/?tipo_export=CSV'.$extra_cad.'&catalogoActual='.$catalogoActual;
			$dataReturn['excel'] =  base_url.'Catalogos/exportarInfo/?tipo_export=EXCEL'.$extra_cad.'&catalogoActual='.$catalogoActual;
			$dataReturn['pdf'] =  base_url.'Catalogos/exportarInfo/?tipo_export=PDF'.$extra_cad.'&catalogoActual='.$catalogoActual;
		}
		return $dataReturn;
	}


    public function generarLinks($numPage,$total_pages,$extra_cad = "",$catalogoActual = 1){
			//$extra_cad sirve para determinar la paginacion conforme a si se realizó una busqueda
			//Creación de links para el pagination
			$links = "";

			//FLECHA IZQ (PREV PAGINATION)
			if ($numPage>1) {
				$links.= '<li class="page-item">
							<a class="page-link" href=" '.base_url.'Catalogos/crudCatalogo/?numPage=1'.$extra_cad.'&catalogoActual='.$catalogoActual.' " data-toggle="tooltip" data-placement="top" title="Primera página">
								<i class="material-icons">first_page</i>
							</a>
						</li>';
				$links.= '<li class="page-item">
							<a class="page-link" href=" '.base_url.'Catalogos/crudCatalogo/?numPage='.($numPage-1).$extra_cad.'&catalogoActual='.$catalogoActual.' " data-toggle="tooltip" data-placement="top" title="Página anterior">
								<i class="material-icons">navigate_before</i>
							</a>
						</li>';
			}

			//DESPLIEGUE DE PAGES NUMBER
			$LINKS_EXTREMOS = GLOBAL_LINKS_EXTREMOS; //numero máximo de links a la izquierda y a la derecha
			for ($ind=($numPage-$LINKS_EXTREMOS); $ind<=($numPage+$LINKS_EXTREMOS); $ind++) {
				if(($ind>=1) && ($ind <= $total_pages)){

					$activeLink = ($ind == $numPage)? 'active':'';

					$links.= '<li class="page-item '.$activeLink.' ">
								<a class="page-link" href=" '.base_url.'Catalogos/crudCatalogo/?numPage='.($ind).$extra_cad.'&catalogoActual='.$catalogoActual.' ">
									'.($ind).'
								</a>
							</li>';
				}
			}

			//FLECHA DERECHA (NEXT PAGINATION)
			if ($numPage<$total_pages) {

				$links.= '<li class="page-item">
							<a class="page-link" href=" '.base_url.'Catalogos/crudCatalogo/?numPage='.($numPage+1).$extra_cad.'&catalogoActual='.$catalogoActual.' " data-toggle="tooltip" data-placement="top" title="Siguiente página">
							<i class="material-icons">navigate_next</i>
							</a>
						</li>';
				$links.= '<li class="page-item">
							<a class="page-link" href=" '.base_url.'Catalogos/crudCatalogo/?numPage='.($total_pages).$extra_cad.'&catalogoActual='.$catalogoActual.' " data-toggle="tooltip" data-placement="top" title="Última página">
							<i class="material-icons">last_page</i>
							</a>
						</li>';
			}

			return $links;
	}

	public function generarInfoTable($catalogoRows,$catalogoActual = 1){
			//se genera la tabulacion de la informacion por backend
			$infoTable['header'] = "";
			$infoTable['body'] = "";
	  		$infoTable['formBody'] = $this->generateFormCatalogo($catalogoActual);

	  			
  			switch ($catalogoActual) {
				case '1':
					$infoTable['header'] .= '
							<th >Id_dato</th>
						  <th >Id_delito</th>
							<th >Descripción</th>
							<th >Id_actividad</th>
							<th >Tipo_actividad</th>
						';
					foreach ($catalogoRows as $row) {
						$infoTable['body'].= '<tr id="tr'.$row->Id_dato.'">';
						$infoTable['body'].= '	<td >'.$row->Id_dato.'</td>
											  <td >'.$row->Id_delito.'</td>
											  <td >'.$row->Descripcion.'</td>
											  <td >'.$row->Id_actividad.'</td>
											  <td >'.$row->Tipo_actividad.'</td>
						  ';
					  $infoTable['body'].= '	<td >
												  <div class="d-flex justify-content-center" id="operaciones">
													  <button data-toggle="tooltip" data-placement="top" title="Editar registro" class="btn btn-icon btn-edit mr-1 edit-icon" onclick="editAction('.$catalogoActual.','.$row->Id_dato.')"><i class="material-icons">edit</i></button>
													  <button data-toggle="tooltip" data-placement="top" title="Eliminar registro" class="btn btn-icon btn-delete delete-icon" onclick="deleteAction('.$catalogoActual.','.$row->Id_dato.')"><i class="material-icons">delete</i></button>
												  </div>
											  </td>';
					  $infoTable['body'].= '</tr>';
					}
					
				break;
				case '2':
					$infoTable['header'] .= '
							<th >Id</th>
							<th >Tipo de arma</th>
						';
					foreach ($catalogoRows as $row) {
						$infoTable['body'].= '<tr id="tr'.$row->Id_Tipo_Arma.'">';
					  $infoTable['body'].= '	<td >'.$row->Id_Tipo_Arma.'</td>
											  <td >'.$row->Tipo_Arma.'</td>
						  ';
						 $infoTable['body'].= '	<td >
												  <div class="d-flex justify-content-center" id="operaciones">
													  <button data-toggle="tooltip" data-placement="top" title="Editar registro" class="btn btn-icon btn-edit mr-1 edit-icon" onclick="editAction('.$catalogoActual.','.$row->Id_Tipo_Arma.')"><i class="material-icons">edit</i></button>
													  <button data-toggle="tooltip" data-placement="top" title="Eliminar registro" class="btn btn-icon btn-delete delete-icon" onclick="deleteAction('.$catalogoActual.','.$row->Id_Tipo_Arma.')"><i class="material-icons">delete</i></button>
												  </div>
											  </td>';
					  $infoTable['body'].= '</tr>';
					}
					
				break;
				case '3':
					$infoTable['header'] .= '
							<th >Id</th>
							<th >Tipo de Violencia</th>
						';
					foreach ($catalogoRows as $row) {
						$infoTable['body'].= '<tr id="tr'.$row->Id_Tipo_Violencia.'">';
						$infoTable['body'].= '	<td >'.$row->Id_Tipo_Violencia.'</td>
											  <td >'.$row->Tipo_Violencia.'</td>
						  ';
					  $infoTable['body'].= '	<td >
												  <div class="d-flex justify-content-center" id="operaciones">
													  <button data-toggle="tooltip" data-placement="top" title="Editar registro" class="btn btn-icon btn-edit mr-1 edit-icon" onclick="editAction('.$catalogoActual.','.$row->Id_Tipo_Violencia.')"><i class="material-icons">edit</i></button>
													  <button data-toggle="tooltip" data-placement="top" title="Eliminar registro" class="btn btn-icon btn-delete delete-icon" onclick="deleteAction('.$catalogoActual.','.$row->Id_Tipo_Violencia.')"><i class="material-icons">delete</i></button>
												  </div>
											  </td>';
					  $infoTable['body'].= '</tr>';
					}
					
					break;
				case '4':
					$infoTable['header'] .= '
							<th >Id</th>
							<th >Tipo de grupo</th>
							<th >Zona / Sector</th>
						';
					foreach ($catalogoRows as $row) {
						$infoTable['body'].= '<tr id="tr'.$row->Id_Zona_Sector.'">';
						$infoTable['body'].= '	<td >'.$row->Id_Zona_Sector.'</td>
												<td >'.$row->Tipo_Grupo.'</td>
												<td >'.$row->Zona_Sector.'</td>
							';
						$infoTable['body'].= '	<td >
													<div class="d-flex justify-content-center" id="operaciones">
														<button data-toggle="tooltip" data-placement="top" title="Editar registro" class="btn btn-icon btn-edit mr-1 edit-icon" onclick="editAction('.$catalogoActual.','.$row->Id_Zona_Sector.')"><i class="material-icons">edit</i></button>
														<button data-toggle="tooltip" data-placement="top" title="Eliminar registro" class="btn btn-icon btn-delete delete-icon" onclick="deleteAction('.$catalogoActual.','.$row->Id_Zona_Sector.')"><i class="material-icons">delete</i></button>
													</div>
												</td>';
						$infoTable['body'].= '</tr>';
					}
					
				break;
				case '5':
					$infoTable['header'] .= '
							<th >Id</th>
							<th >Id vector interno</th>
							<th >Zona</th>
							<th >Vector</th>
							<th >Región</th>
						';
					foreach ($catalogoRows as $row) {
						$infoTable['body'].= '<tr id="tr'.$row->Id_Vector.'">';
						$infoTable['body'].= '	<td >'.$row->Id_Vector.'</td>
											  <td >'.$row->Id_Vector_Interno.'</td>
											  <td >'.$row->Zona.'</td>
											  <td >'.$row->Vector.'</td>
											  <td >'.$row->Region.'</td>
						  ';
					  $infoTable['body'].= '	<td >
												  <div class="d-flex justify-content-center" id="operaciones">
													  <button data-toggle="tooltip" data-placement="top" title="Editar registro" class="btn btn-icon btn-edit mr-1 edit-icon" onclick="editAction('.$catalogoActual.','.$row->Id_Vector.')"><i class="material-icons">edit</i></button>
													  <button data-toggle="tooltip" data-placement="top" title="Eliminar registro" class="btn btn-icon btn-delete delete-icon" onclick="deleteAction('.$catalogoActual.','.$row->Id_Vector.')"><i class="material-icons">delete</i></button>
												  </div>
											  </td>';
					  $infoTable['body'].= '</tr>';
					}
					
				break;
				case '6':
					$infoTable['header'] .= '
							<th >Id</th>
							<th >Marca del vehículo</th>
						';
					foreach ($catalogoRows as $row) {
						$infoTable['body'].= '<tr id="tr'.$row->Id_Marca_Io.'">';
						$infoTable['body'].= '	<td >'.$row->Id_Marca_Io.'</td>
												<td >'.$row->Marca.'</td>	  
						  ';
					  $infoTable['body'].= '	<td >
												  <div class="d-flex justify-content-center" id="operaciones">
													  <button data-toggle="tooltip" data-placement="top" title="Editar registro" class="btn btn-icon btn-edit mr-1 edit-icon" onclick="editAction('.$catalogoActual.','.$row->Id_Marca_Io.')"><i class="material-icons">edit</i></button>
													  <button data-toggle="tooltip" data-placement="top" title="Eliminar registro" class="btn btn-icon btn-delete delete-icon" onclick="deleteAction('.$catalogoActual.','.$row->Id_Marca_Io.')"><i class="material-icons">delete</i></button>
												  </div>
											  </td>';
					  $infoTable['body'].= '</tr>';
					}
					
				break;
				case '7':
					$infoTable['header'] .= '
							<th >Id_Tipo</th>
							<th >Tipo</th>
						';
					foreach ($catalogoRows as $row) {
						$infoTable['body'].= '<tr id="tr'.$row->Id_Tipo_veh.'">';
						
						$infoTable['body'].= '	<td >'.$row->Id_Tipo_veh.'</td>  
												<td >'.$row->Tipo.'</td>  
							';
						$infoTable['body'].= '	<td >
													<div class="d-flex justify-content-center" id="operaciones">
														<button data-toggle="tooltip" data-placement="top" title="Editar registro" class="btn btn-icon btn-edit mr-1 edit-icon" onclick="editAction('.$catalogoActual.','.$row->Id_Tipo_veh.')"><i class="material-icons">edit</i></button>
														<button data-toggle="tooltip" data-placement="top" title="Eliminar registro" class="btn btn-icon btn-delete delete-icon" onclick="deleteAction('.$catalogoActual.','.$row->Id_Tipo_veh.')"><i class="material-icons">delete</i></button>
													</div>
												</td>';
						$infoTable['body'].= '</tr>';
					}
				
				break;
				case '8':
					$infoTable['header'] .= '
							<th >Id_Submarca</th>
							<th >Submarca</th>
						';
					foreach ($catalogoRows as $row) {
						$infoTable['body'].= '<tr id="tr'.$row->Id_Submarca_veh.'">';
						$infoTable['body'].= '	<td >'.$row->Id_Submarca_veh.'</td>
												<td >'.$row->Submarca.'</td>  
							';
						$infoTable['body'].= '	<td >
													<div class="d-flex justify-content-center" id="operaciones">
														<button data-toggle="tooltip" data-placement="top" title="Editar registro" class="btn btn-icon btn-edit mr-1 edit-icon" onclick="editAction('.$catalogoActual.','.$row->Id_Submarca_veh.')"><i class="material-icons">edit</i></button>
														<button data-toggle="tooltip" data-placement="top" title="Eliminar registro" class="btn btn-icon btn-delete delete-icon" onclick="deleteAction('.$catalogoActual.','.$row->Id_Submarca_veh.')"><i class="material-icons">delete</i></button>
													</div>
												</td>';
						$infoTable['body'].= '</tr>';
					}
				
				break;
				case '9':
					$infoTable['header'] .= '
							<th >Id_colonia</th>
							<th >Tipo</th>
							<th >Colonia</th>
						';
					foreach ($catalogoRows as $row) {
						$infoTable['body'].= '<tr id="tr'.$row->Id_colonia.'">';
						
						$infoTable['body'].= '	<td >'.$row->Id_colonia.'</td>  
												<td >'.$row->Tipo.'</td>  
												<td >'.$row->Colonia.'</td>  
							';
						$infoTable['body'].= '	<td >
													<div class="d-flex justify-content-center" id="operaciones">
														<button data-toggle="tooltip" data-placement="top" title="Editar registro" class="btn btn-icon btn-edit mr-1 edit-icon" onclick="editAction('.$catalogoActual.','.$row->Id_colonia.')"><i class="material-icons">edit</i></button>
														<button data-toggle="tooltip" data-placement="top" title="Eliminar registro" class="btn btn-icon btn-delete delete-icon" onclick="deleteAction('.$catalogoActual.','.$row->Id_colonia.')"><i class="material-icons">delete</i></button>
													</div>
												</td>';
						$infoTable['body'].= '</tr>';
					}
				
				break;
				case '10':
					$infoTable['header'] .= '
							<th >Id_calle</th>
							<th >Id_calle_desc</th>
						';
					foreach ($catalogoRows as $row) {
						$infoTable['body'].= '<tr id="tr'.$row->Id_Calle.'">';
						
						$infoTable['body'].= '	<td >'.$row->Id_Calle.'</td>  
												<td >'.$row->Calle.'</td>  
							';
						$infoTable['body'].= '	<td >
													<div class="d-flex justify-content-center" id="operaciones">
														<button data-toggle="tooltip" data-placement="top" title="Editar registro" class="btn btn-icon btn-edit mr-1 edit-icon" onclick="editAction('.$catalogoActual.','.$row->Id_Calle.')"><i class="material-icons">edit</i></button>
														<button data-toggle="tooltip" data-placement="top" title="Eliminar registro" class="btn btn-icon btn-delete delete-icon" onclick="deleteAction('.$catalogoActual.','.$row->Id_Calle.')"><i class="material-icons">delete</i></button>
													</div>
												</td>';
						$infoTable['body'].= '</tr>';
					}
				
				break;
				case '11':
					$infoTable['header'] .= '
							<th >Id_cp</th>
							<th >Codigo_postal</th>
							<th >Nombre</th>
						';
					foreach ($catalogoRows as $row) {
						$infoTable['body'].= '<tr id="tr'.$row->Id_cp.'">';
						
						$infoTable['body'].= '	<td >'.$row->Id_cp.'</td>  
												<td >'.$row->Codigo_postal.'</td>  
												<td >'.$row->Nombre.'</td>  
							';
						$infoTable['body'].= '	<td >
													<div class="d-flex justify-content-center" id="operaciones">
														<button data-toggle="tooltip" data-placement="top" title="Editar registro" class="btn btn-icon btn-edit mr-1 edit-icon" onclick="editAction('.$catalogoActual.','.$row->Id_cp.')"><i class="material-icons">edit</i></button>
														<button data-toggle="tooltip" data-placement="top" title="Eliminar registro" class="btn btn-icon btn-delete delete-icon" onclick="deleteAction('.$catalogoActual.','.$row->Id_cp.')"><i class="material-icons">delete</i></button>
													</div>
												</td>';
						$infoTable['body'].= '</tr>';
					}
				break;
				case '12':
					$infoTable['header'] .= '
									<th>Id Dato</th>
									<th>Nombre</th>
									<th>Apellido Paterno</th>
									<th>Apellido Materno</th>
									<th>Nombre Clave</th>
						';
					foreach ($catalogoRows as $row) {
						$infoTable['body'].= '<tr id="tr'.$row->id.'">';
						

						$infoTable['body'].= '	<td >'.$row->id.'</td>  
												<td >'.$row->nombre.'</td>  
												<td >'.$row->APpaterno.'</td>  
												<td >'.$row->APmaterno.'</td>  
												<td >'.$row->clave.'</td> 
							';
						$infoTable['body'].= '	<td >
													<div class="d-flex justify-content-center" id="operaciones">
														<button data-toggle="tooltip" data-placement="top" title="Editar registro" class="btn btn-icon btn-edit mr-1 edit-icon" onclick="editAction('.$catalogoActual.','.$row->id.')"><i class="material-icons">edit</i></button>
														<button data-toggle="tooltip" data-placement="top" title="Eliminar registro" class="btn btn-icon btn-delete delete-icon" onclick="deleteAction('.$catalogoActual.','.$row->id.')"><i class="material-icons">delete</i></button>
													</div>
												</td>';
						$infoTable['body'].= '</tr>';
					}
				break;
				case '13':
					$infoTable['header'] .= '
							<th >Id</th>
							<th >Fuente</th>
						';
					foreach ($catalogoRows as $row) {
						$infoTable['body'].= '<tr id="tr'.$row->id_fuente.'">';
						
						$infoTable['body'].= '	<td >'.$row->id_fuente.'</td>  
												<td >'.$row->fuente.'</td> 
							';
						$infoTable['body'].= '	<td >
													<div class="d-flex justify-content-center" id="operaciones">
														<button data-toggle="tooltip" data-placement="top" title="Editar registro" class="btn btn-icon btn-edit mr-1 edit-icon" onclick="editAction('.$catalogoActual.','.$row->id_fuente.')"><i class="material-icons">edit</i></button>
														<button data-toggle="tooltip" data-placement="top" title="Eliminar registro" class="btn btn-icon btn-delete delete-icon" onclick="deleteAction('.$catalogoActual.','.$row->id_fuente.')"><i class="material-icons">delete</i></button>
													</div>
												</td>';
						$infoTable['body'].= '</tr>';
					}
				break;
				case '14':
					$infoTable['header'] .= '
							<th >Id</th>
							<th >Area</th>
						';
					foreach ($catalogoRows as $row) {
						$infoTable['body'].= '<tr id="tr'.$row->id_area.'">';
						
						$infoTable['body'].= '	<td >'.$row->id_area.'</td>  
												<td >'.$row->Area.'</td> 
							';
						$infoTable['body'].= '	<td >
													<div class="d-flex justify-content-center" id="operaciones">
														<button data-toggle="tooltip" data-placement="top" title="Editar registro" class="btn btn-icon btn-edit mr-1 edit-icon" onclick="editAction('.$catalogoActual.','.$row->id_area.')"><i class="material-icons">edit</i></button>
														<button data-toggle="tooltip" data-placement="top" title="Eliminar registro" class="btn btn-icon btn-delete delete-icon" onclick="deleteAction('.$catalogoActual.','.$row->id_area.')"><i class="material-icons">delete</i></button>
													</div>
												</td>';
						$infoTable['body'].= '</tr>';
					}
				break;	
				case '15':
					$infoTable['header'] .= '
							<th >Id</th>
							<th >Tipo de Violencia</th>
						';
					foreach ($catalogoRows as $row) {
						$infoTable['body'].= '<tr id="tr'.$row->Id_Tipo_SViolencia.'">';
						$infoTable['body'].= '	<td >'.$row->Id_Tipo_SViolencia.'</td>
											  <td >'.$row->Tipo_SViolencia.'</td>
						  ';
					  $infoTable['body'].= '	<td >
												  <div class="d-flex justify-content-center" id="operaciones">
													  <button data-toggle="tooltip" data-placement="top" title="Editar registro" class="btn btn-icon btn-edit mr-1 edit-icon" onclick="editAction('.$catalogoActual.','.$row->Id_Tipo_SViolencia.')"><i class="material-icons">edit</i></button>
													  <button data-toggle="tooltip" data-placement="top" title="Eliminar registro" class="btn btn-icon btn-delete delete-icon" onclick="deleteAction('.$catalogoActual.','.$row->Id_Tipo_SViolencia.')"><i class="material-icons">delete</i></button>
												  </div>
											  </td>';
					  $infoTable['body'].= '</tr>';
					}
					
				break;
				case '16':
					$infoTable['header'] .= '
							<th >Id</th>
							<th >Indicativo</th>
						';
					foreach ($catalogoRows as $row) {
						$infoTable['body'].= '<tr id="tr'.$row->Id_Dato .'">';
						$infoTable['body'].= '	<td >'.$row->Id_Dato .'</td>
											  <td >'.$row->Indicativo.'</td>
						  ';
					  $infoTable['body'].= '	<td >
												  <div class="d-flex justify-content-center" id="operaciones">
													  <button data-toggle="tooltip" data-placement="top" title="Editar registro" class="btn btn-icon btn-edit mr-1 edit-icon" onclick="editAction('.$catalogoActual.','.$row->Id_Dato.')"><i class="material-icons">edit</i></button>
													  <button data-toggle="tooltip" data-placement="top" title="Eliminar registro" class="btn btn-icon btn-delete delete-icon" onclick="deleteAction('.$catalogoActual.','.$row->Id_Dato.')"><i class="material-icons">delete</i></button>
												  </div>
											  </td>';
					  $infoTable['body'].= '</tr>';
					}
					
				break;
				case '17':
					$infoTable['header'] .= '
							<th >Id</th>
							<th >Tipo</th>
						';
					foreach ($catalogoRows as $row) {
						$infoTable['body'].= '<tr id="tr'.$row->Id_Dato .'">';
						$infoTable['body'].= '	<td >'.$row->Id_Dato .'</td>
											  <td >'.$row->Tipo.'</td>
						  ';
					  $infoTable['body'].= '	<td >
												  <div class="d-flex justify-content-center" id="operaciones">
													  <button data-toggle="tooltip" data-placement="top" title="Editar registro" class="btn btn-icon btn-edit mr-1 edit-icon" onclick="editAction('.$catalogoActual.','.$row->Id_Dato.')"><i class="material-icons">edit</i></button>
													  <button data-toggle="tooltip" data-placement="top" title="Eliminar registro" class="btn btn-icon btn-delete delete-icon" onclick="deleteAction('.$catalogoActual.','.$row->Id_Dato.')"><i class="material-icons">delete</i></button>
												  </div>
											  </td>';
					  $infoTable['body'].= '</tr>';
					}	
				break;	
				case '18':
					$infoTable['header'] .= '
							<th >Id</th>
							<th >Calle</th>
							<th >Calle 2</th>
							<th >Información Adicional</th>
							<th >CoordX</th>
							<th >CoordY</th>
						';
					foreach ($catalogoRows as $row) {
						$infoTable['body'].= '<tr id="tr'.$row->Id_Dato .'">';
						$infoTable['body'].= '	<td >'.$row->Id_Dato .'</td>
											  <td >'.$row->Calle.'</td>
											  <td >'.$row->Calle2.'</td>
											  <td >'.$row->Info_Adicional.'</td>
											  <td >'.$row->CoordX.'</td>
											  <td >'.$row->CoordY.'</td>
						  ';
					  $infoTable['body'].= '	<td >
												  <div class="d-flex justify-content-center" id="operaciones">
													  <button data-toggle="tooltip" data-placement="top" title="Editar registro" class="btn btn-icon btn-edit mr-1 edit-icon" onclick="editAction('.$catalogoActual.','.$row->Id_Dato.')"><i class="material-icons">edit</i></button>
													  <button data-toggle="tooltip" data-placement="top" title="Eliminar registro" class="btn btn-icon btn-delete delete-icon" onclick="deleteAction('.$catalogoActual.','.$row->Id_Dato.')"><i class="material-icons">delete</i></button>
												  </div>
											  </td>';
					  $infoTable['body'].= '</tr>';
					}	
				break;
  			}
			$infoTable['header'].='<th >Operaciones</th>';
			$nombreCatalogo= $this->getNombreCatalogo($catalogoActual);
			$this->Historial->insertHistorial(19,'VER CATALOGO: '.$nombreCatalogo,$_SESSION['userdataSIC']->Id_Usuario);
	  		return $infoTable;
	}

	public function exportarInfo(){
		if (!isset($_SESSION['userdataSIC']) || ($_SESSION['userdataSIC']->Modo_Admin != 1)) {
			header("Location: ".base_url."Inicio");
		}

		if (!isset($_REQUEST['tipo_export'])) {
			header("Location: ".base_url."UsersAdmin");
		}
		//se recupera el catalogo actual para poder consultar conforme al mismo
		if (!is_numeric($_REQUEST['catalogoActual']) || !($_REQUEST['catalogoActual']>=MIN_CATALOGO) || !($_REQUEST['catalogoActual']<=MAX_CATALOGO)) 
				$catalogoActual = 1;
			else
				$catalogoActual = $_REQUEST['catalogoActual'];

		$from_where_sentence = "";
		//se genera la sentencia from where para realizar la correspondiente consulta
		if (isset($_REQUEST['cadena'])) 
			$from_where_sentence = $this->Catalogo->generateFromWhereSentence($catalogoActual,$_REQUEST['cadena']);
		else
			$from_where_sentence = $this->Catalogo->generateFromWhereSentence($catalogoActual,"");

		
		
		//var_dump($_REQUEST);
		$tipo_export = $_REQUEST['tipo_export'];

		if ($tipo_export == 'EXCEL') {
			//se realiza exportacion de usuarios a EXCEL
			$cat_rows = $this->Catalogo->getAllInfoCatalogoByCadena($from_where_sentence);
			switch ($catalogoActual) {
				case '1':
					$filename = "catalogo_faltas_delitos";
					$csv_data="Id_dato,Id_delito,Descripción,Id_actividad,Tipo_actividad\n";
					foreach ($cat_rows as $row) {
						$csv_data.= mb_strtoupper($row->Id_dato).",\"".
									mb_strtoupper($row->Id_delito)."\",\"".
									mb_strtoupper($row->Descripcion)."\",\"".
									mb_strtoupper($row->Id_actividad)."\",\"".
									mb_strtoupper($row->Tipo_actividad)."\"\n";
					}
				break;
				case '2':
					$filename = "catalogo_tipos_armas";
					$csv_data="Id,Tipo de Arma\n";
					foreach ($cat_rows as $row) {
						$csv_data.= mb_strtoupper($row->Id_Tipo_Arma).",\"".
									mb_strtoupper($row->Tipo_Arma)."\"\n";
					}
				break;
				case '3':
					$filename = "catalogo_tipo_violencia";
					$csv_data="Id,Tipo_Violencia\n";
					foreach ($cat_rows as $row) {
						$csv_data.= mb_strtoupper($row->Id_Tipo_Violencia).",\"".
									mb_strtoupper($row->Tipo_Violencia)."\"\n";
					}
				break;
				case '4':
					$filename = "catalogo_zonas_sectores";
					$csv_data="Id,Tipo_Grupo,Zona/Sector\n";
					foreach ($cat_rows as $row) {
						$csv_data.= mb_strtoupper($row->Id_Zona_Sector).",\"".
									mb_strtoupper($row->Tipo_Grupo)."\",\"".
									mb_strtoupper($row->Zona_Sector)."\"\n";
					}
				break;
				case '5':
					$filename = "catalogo_vectores";
					$csv_data="Id,Id Vector Interno,Zona,Vector,Región\n";
					foreach ($cat_rows as $row) {
						$csv_data.= mb_strtoupper($row->Id_Vector).",\"".
									mb_strtoupper($row->Id_Vector_Interno)."\",\"".
									mb_strtoupper($row->Zona)."\",\"".
									mb_strtoupper($row->Vector)."\",\"".
									mb_strtoupper($row->Region)."\"\n";
					}
				break;
				case '6':
					$filename = "catalogo_marca_vehiculos_io";
					$csv_data="Id,Marca del Vehículo\n";
					foreach ($cat_rows as $row) {
						$csv_data.= mb_strtoupper($row->Id_Marca_Io).",\"".
									mb_strtoupper($row->Marca)."\"\n";
					}
				break;
				case '7':
					$filename = "catalogo_tipos_vehiculos";
					$csv_data="Id,Tipo\n";
					foreach ($cat_rows as $row) {
						$csv_data.= mb_strtoupper($row->Id_Tipo_veh).",\"".
									mb_strtoupper($row->Tipo)."\"\n";
					}
				break;
				case '8':
					$filename = "catalogo_submarcas_vehiculos";
					$csv_data="Id,Submarca\n";
					foreach ($cat_rows as $row) {
						$csv_data.= mb_strtoupper($row->Id_Submarca_veh).",\"".
									mb_strtoupper($row->Submarca)."\"\n";
					}
				break;
				case '9':
					$filename = "catalogo_colonias";
					$csv_data="Id,tipo,colonia\n";
					foreach ($cat_rows as $row) {
						$csv_data.= mb_strtoupper($row->Id_colonia).",\"".
									mb_strtoupper($row->Tipo)."\",\"".
									mb_strtoupper($row->Colonia)."\"\n";
					}
				break;
				case '10':
					$filename = "catalogo_calles";
					$csv_data="Id,calle\n";
					foreach ($cat_rows as $row) {
						$csv_data.= mb_strtoupper($row->Id_calle).",\"".
									mb_strtoupper($row->Calle)."\"\n";
					}
				break;
				case '11':
					$filename = "catalogo_codigos_postales";
					$csv_data="Id,Codigo postal,Nombre\n";
					foreach ($cat_rows as $row) {
						$csv_data.= mb_strtoupper($row->Id_cp).",\"".
									mb_strtoupper($row->Codigo_postal)."\",\"".
									mb_strtoupper($row->Nombre)."\"\n";
					}
					break;
				case '12':
					$filename = "catalogo_nombres_clave";
					$csv_data="Id,Nombre,Apellido_paterno,Apellido_materno,Clave\n";
					foreach ($cat_rows as $row) {
						$csv_data.= mb_strtoupper($row->id).",\"".
									mb_strtoupper($row->nombre)."\",\"".
									mb_strtoupper($row->APpaterno)."\",\"".
									mb_strtoupper($row->APmaterno)."\",\"".
									mb_strtoupper($row->clave)."\"\n";
					}
					break;
				case '13':
					$filename = "catalogo_fuentes";
					$csv_data="Id_Fuente,Fuente\n";
					foreach ($cat_rows as $row) {
						$csv_data.= mb_strtoupper($row->id_fuente).",\"".
									mb_strtoupper($row->fuente)."\"\n";
					}
					break;
				case '14':
					$filename = "catalogo_area";
					$csv_data="Id_Area,Area\n";
					foreach ($cat_rows as $row) {
						$csv_data.= mb_strtoupper($row->id_area).",\"".
									mb_strtoupper($row->Area)."\"\n";
					}
					break;
				case '15':
					$filename = "catalogo_tipo_violencia";
					$csv_data="Id,Tipo_Violencia\n";
					foreach ($cat_rows as $row) {
						$csv_data.= mb_strtoupper($row->Id_Tipo_SViolencia).",\"".
									mb_strtoupper($row->Tipo_SViolencia)."\"\n";
					}
				break;
				case '16':
					$filename = "catalogo_indicativo_entrevistador";
					$csv_data="Id_Dato,Indicativo\n";
					foreach ($cat_rows as $row) {
						$csv_data.= mb_strtoupper($row->Id_Dato).",\"".
									mb_strtoupper($row->Indicativo)."\"\n";
					}
				break;
				case '17':
					$filename = "catalogo_tipo_dato_entrevista";
					$csv_data="Id_Dato,Tipo\n";
					foreach ($cat_rows as $row) {
						$csv_data.= mb_strtoupper($row->Id_Dato).",\"".
									mb_strtoupper($row->Tipo)."\"\n";
					}
				break;
				case '18':
					$filename = "catalogo_ubicaciones_camaras";
					$csv_data="Id_Dato,Calle,Calle2,Información Adicional,CoordX, CoordY\n";
					foreach ($cat_rows as $row) {
						$csv_data.= mb_strtoupper($row->Id_Dato).",\"".
									mb_strtoupper($row->Calle)."\",\"".
									mb_strtoupper($row->Calle2)."\",\"".
									mb_strtoupper($row->Info_Adicional)."\",\"".
									mb_strtoupper($row->CoordX)."\",\"".
									mb_strtoupper($row->CoordY)."\"\n";
					}
				break;
			}
			//se genera el archivo csv o excel
			$csv_data = utf8_decode($csv_data); //escribir información con formato utf8 por algún acento
			header("Content-Description: File Transfer");
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=".$filename.".csv");
			echo $csv_data;
			$nombreCatalogo= $this->getNombreCatalogo($catalogoActual);
			$this->Historial->insertHistorial(22,'EXPORTACION DE EXCEL CATALOGO: '.$nombreCatalogo,$_SESSION['userdataSIC']->Id_Usuario);
			//header("Location: ".base_url."UsersAdmin");

		}
		elseif($tipo_export == 'PDF'){
			$cat_rows = $this->Catalogo->getAllInfoCatalogoByCadena($from_where_sentence);
			

			header("Content-type: application/pdf");
			header("Content-Disposition: inline; filename=usuarios.pdf");
			$nombreCatalogo= $this->getNombreCatalogo($catalogoActual);
			$this->Historial->insertHistorial(22,'EXPORTACION DE PDF CATALOGO: '.$nombreCatalogo,$_SESSION['userdataSIC']->Id_Usuario);
			echo $this->generarPDF($cat_rows,$_REQUEST['cadena'],$catalogoActual);
		}
		else{
			header("Location: ".base_url."Inicio");
		}
	}

	public function generarPDF($cat_rows,$cadena = "",$catalogoActual = '1'){
		//require('../libraries/PDF library/fpdf16/fpdf.php');
		switch ($catalogoActual) {
			case '1': $filename="Delitos";break;
			case '2': $filename="Tipos de Armas";break;
			case '3': $filename="Tipos de Violencia(Con Violencia)";break;
			case '4': $filename="Zonas";break;
			case '5': $filename="Vectores";break;
			case '6': $filename="Marca del Vehículo";break;
			case '7': $filename="Tipos de Vehículo";break;
			case '8': $filename="Submarcas del Vehículo";break;
			case '9': $filename="Colonias";break;
			case '10': $filename="Calles";break;
			case '11': $filename="Codigos postales";break;
			case '12': $filename="Nombres Clave";break;
			case '13': $filename="Fuentes";break;
			case '14': $filename="Areas";break;
			case '15': $filename="Tipos de Violencia(Sin Violencia)";break;
			case '16': $filename="Indicativos";break;
			case '17': $filename="Tipo de Dato Entrevista";break;
			case '18': $filename="Ubicaciones de Camaras";break;

		}

		$data['subtitulo']      = 'Catálogo: '.$filename;

		if ($cadena != "") {
			$data['msg'] = 'todos los registros con filtro: '.$cadena.'';
		}
		else{
			$data['msg'] = 'todos los registros del catálogo';
		}


		//---Aquí va la info según sea el catálogo seleccionado
		switch ($catalogoActual) {
			case '1':
				$data['columns'] =  [
	                            'Id_dato',
	                            'Id_delito',
	                            'Descripción',
	                            'Id_actividad',
	                            'Tipo_actividad'
                            ];  
       	 		$data['field_names'] = [
								'Id_dato',
								'Id_delito',
								'Descripción',
								'Id_actividad',
								'Tipo_actividad'
                            ];
				
				foreach ($cat_rows as $key => $row){
					$cat_rows[$key]->statusAux = ($row->Status)?"Activo":"Inactivo";
				}
				
			break;
			case '2':
				$data['columns'] =  [
	                            'Id',
	                            'Tipo Arma'
                            ];  
       	 		$data['field_names'] = [
	                            'Id_Tipo_Arma',
	                            'Tipo_Arma'
                            ];
			break;
			case '3':
				$data['columns'] =  [
	                            'Id tipo',
	                            'Tipo_Violencia'
                            ];  
       	 		$data['field_names'] = [
	                            'Id_Tipo_Violencia',
	                            'Tipo_Violencia'
                            ];
			break;
			case '4':
				$data['columns'] =  [
	                            'Id',
	                            'Tipo grupo',
	                            'Zona Sector'
                            ];  
       	 		$data['field_names'] = [
	                            'Id_Zona_Sector',
	                            'Tipo_Grupo',
	                            'Zona_Sector'
                            ]; 
			break;
			case '5':
				$data['columns'] =  [
	                            'Id',
	                            'Id Vector Interno',
	                            'Zona',
	                            'Vector',
	                            'Región'
                            ];  
       	 		$data['field_names'] = [
	                            'Id_Vector',
	                            'Id_Vector_Interno',
	                            'Zona',
	                            'Vector',
	                            'Region'
                            ];
			break;
			case '6': 
				$data['columns'] =  [
	                            'Id',
	                            'Marca del vehículo'
                            ];  
       	 		$data['field_names'] = [
	                            'Id_Marca_Io',
	                            'Marca'
                            ];
			break;
			case '7': 
				$data['columns'] =  [
	                            'Id',
	                            'Tipo del vehículo'
                            ];  
       	 		$data['field_names'] = [
	                            'Id_Tipo_veh',
	                            'Tipo'
                            ];
			break;
			case '8': 
				$data['columns'] =  [
	                            'Id',
	                            'Submarca del vehículo'
                            ];  
       	 		$data['field_names'] = [
	                            'Id_Submarca_veh',
	                            'Submarca'
                            ];
			break;
			case '9': 
				$data['columns'] =  [
	                            'Id',
	                            'Tipo',
								'Nombre colonia'
                            ];  
       	 		$data['field_names'] = [
	                            'Id_colonia',
	                            'Tipo',
	                            'Colonia'
                            ];
			break;
			case '10': 
				$data['columns'] =  [
	                            'Id',
	                            'Nombre calle'
                            ];  
       	 		$data['field_names'] = [
	                            'Id_Calle',
	                            'Calle'
                            ];
			break;
			case '11': 
				$data['columns'] =  [
	                            'Id Dato',
	                            'Cp',
								'Nombre'
                            ];  
       	 		$data['field_names'] = [
	                            'Id_cp',
	                            'Codigo_postal',
	                            'Nombre'
                            ];
			break;
			case '12': 
				$data['columns'] =  [
	                            'Id Dato',
	                            'Nombre',
								'Apellido Paterno',
								'Apellido Materno',
								'Nombre Clave'
                            ];  
       	 		$data['field_names'] = [
	                            'id',
	                            'nombre',
	                            'APpaterno',
								'APmaterno',
								'clave'
                            ];
			break;
			case '13': 
				$data['columns'] =  [
	                            'Id Fuente',
	                            'Fuente'
                            ];  
       	 		$data['field_names'] = [
	                            'id',
	                            'Fuente'
                            ];
			break;
			case '14': 
				$data['columns'] =  [
	                            'Id Area',
	                            'Area'
                            ];  
       	 		$data['field_names'] = [
	                            'id_area',
	                            'Area'
                            ];
			break;
			case '15':
				$data['columns'] =  [
	                            'Id tipo',
	                            'Tipo_Violencia'
                            ];  
       	 		$data['field_names'] = [
	                            'Id_Tipo_SViolencia',
	                            'Tipo_SViolencia'
                            ];
			break;
			case '16':
				$data['columns'] =  [
	                            'Id Indicativo',
	                            'Indicativo'
                            ];  
       	 		$data['field_names'] = [
	                            'Id_Dato',
	                            'Indicativo'
                            ];
			break;
			case '17':
				$data['columns'] =  [
	                            'Id Tipo',
	                            'Tipo'
                            ];  
       	 		$data['field_names'] = [
	                            'Id_Dato',
	                            'Tipo'
                            ];
			break;
			case '18':
				$data['columns'] =  [
	                            'Id Dato',
	                            'Calle',
	                            'Calle2',
	                            'Info_Adicional',
	                            'CoordX',
	                            'CoordY'
                            ];  
       	 		$data['field_names'] = [
	                            'Id_Dato',
	                            'Calle',
	                            'Calle2',
	                            'Info_Adicional',
	                            'CoordX',
	                            'CoordY'
                            ];
			break;
		}

		$data['rows'] = $cat_rows;
		//se carga toda la plantilla con la información enviada por parámetro
        $plantilla = MY_PDF::getPlantilla($data);
        //se carga el css de la plantilla
        $css = file_get_contents(base_url.'public/css/template/pdf_style.css');
        // Create an instance of the class:
        $mpdf = new \Mpdf\Mpdf([]);
        // se inserta el css y html cargado
        $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($plantilla, \Mpdf\HTMLParserMode::HTML_BODY);
        // se muestra en pantalla
        $mpdf->Output();
	}

	//función para generar los campos para adición o edición de registros del catálogo
	public function generateFormCatalogo($catalogoActual = 1){
		$formBody = "";
		switch ($catalogoActual) {
			case '1':
				$formBody.='
							<div class="col-12 col-md-2 form-group">
							    <label for="id_dato_d">Id dato:</label>
							    <input type="text" class="form-control" id="id_dato_d" value="1" readonly>
							</div>
							<div class="col-12 col-md-2 form-group">
							    <label for="id_delito">Id delito:</label>
							    <input type="text" class="form-control" id="id_delito" placeholder="Ingrese id de delito">
							</div>
							<div class="col-12 col-md-8 form-group">
								<label for="id_descripcion">Descripción:</label>
								<textarea class="form-control" id="id_descripcion" rows="2"></textarea>
							</div>
							<div class="col-12 col-md-3 form-group">
							    <label for="id_actividad">Id actividad:</label>
								<input type="text" class="form-control" id="id_actividad" placeholder="Ingrese id de actividad">
							</div>
							<div class="col-12 col-md-3 form-group">
							    <label for="Tipo_actividad">Tipo de Actividad:</label>
								<input type="text" class="form-control" id="Tipo_actividad" placeholder="Ingrese tipo de actividad">
							</div>
							';
			break;
			case '2':
				$formBody.='
							<div class="col-12 col-md-2 form-group">
							    <label for="id_tipo_arma">Id:</label>
							    <input type="text" class="form-control" id="id_tipo_arma" value="1" readonly>
							</div>
							<div class="col-12 col-md-6 form-group">
							    <label for="id_tipo_arma_nombre">Tipo de arma:</label>
							    <input type="text" class="form-control" id="id_tipo_arma_nombre" placeholder="Ingrese el tipo de arma">
							</div>
							';
			break;
			case '3':
				$formBody.='
							<div class="col-12 col-md-2 form-group">
								<label for="id_tipo_violencia">Id:</label>
								<input type="text" class="form-control" id="id_tipo_violencia" value="1" readonly>
							</div>
							<div class="col-12 col-md-6 form-group">
								<label for="id_tipo_violencia_valor">Tipo de Violencia:</label>
								<input type="text" class="form-control" id="id_tipo_violencia_valor" placeholder="Ingrese tipo de violencia">
							</div>
							';
			break;
			case '4':
				$formBody.='
							<div class="col-12 col-md-1 form-group">
							    <label for="id_zona_sector">Id:</label>
							    <input type="text" class="form-control" id="id_zona_sector" value="1" readonly>
							</div>
							<div class="col-12 col-md-3 form-group">
							    <label for="id_tipo_grupo">Tipo grupo:</label>
							    <input type="text" class="form-control" id="id_tipo_grupo" placeholder="Ingrese el tipo de grupo">
							</div>
							<div class="col-12 col-md-4 form-group">
							    <label for="id_zona_sector_valor">Zona/Sector:</label>
							    <textarea class="form-control" id="id_zona_sector_valor" rows="2"></textarea>
							</div>
							';
			break;
			case '5':
				$formBody.='
							<div class="col-12 col-md-2 form-group">
							    <label for="id_grupo">Id:</label>
							    <input type="text" class="form-control" id="id_vector" value="1" readonly>
							</div>
							<div class="col-12 col-md-4 form-group">
							    <label for="id_nombre_g">Id Vector Interno:</label>
							    <input type="text" class="form-control" id="id_vector_i" placeholder="Id del Vector Interno">
							</div>
							<div class="col-12 col-md-3 form-group">
							    <label for="id_modus_operandi">Zona:</label>
							    <input type="text" class="form-control" id="id_zona" placeholder="Ingrese la zona (1 - 9 ó CH para centro histórico)">
							</div>
							<div class="col-12 col-md-3 form-group">
							    <label for="id_modo_fuga">Vector:</label>
							    <input type="text" class="form-control" id="id_vector_numero" placeholder="Ingrese el número del vector">
							</div>
							<div class="col-12 col-md-8 form-group">
							    <label for="id_descripcion">Región:</label>
							    <input type="text" class="form-control" id="id_region" placeholder="Ingrese la región (Norte, Centro, Sur, CH)">
							</div>
							';
			break;
			case '6':
				$formBody.='
							<div class="col-12 col-md-1 form-group">
							    <label for="id_origen_evento">Id:</label>
							    <input type="text" class="form-control" id="id_marca_vehiculo" value="1" readonly>
							</div>
							<div class="col-12 col-md-3 form-group">
							    <label for="id_origen">Marca del Vehículo:</label>
							    <input type="text" class="form-control" id="id_marca" placeholder="Ingrese la marca del vehículo">
							</div>
							';
			break;
			case '7':
				$formBody.='
							<div class="col-12 col-md-1 form-group">
								<label for="id_tipo_veh">Id:</label>
								<input type="text" class="form-control" id="id_tipo_veh" value="1" readonly>
							</div>
							<div class="col-12 col-md-3 form-group">
								<label for="id_tipo_veh_desc">Tipo de vehiculo:</label>
								<input type="text" class="form-control" id="id_tipo_veh_desc" placeholder="Ingrese el tipo de vehiculo">
							</div>
							';
			break;
			case '8':
				$formBody.='
							<div class="col-12 col-md-1 form-group">
								<label for="id_submarca_veh">Id:</label>
								<input type="text" class="form-control" id="id_submarca_veh" value="1" readonly>
							</div>
							<div class="col-12 col-md-3 form-group">
								<label for="id_submarca_desc">Submarca de vehiculo:</label>
								<input type="text" class="form-control" id="id_submarca_desc" placeholder="Ingrese la submarca de vehiculo">
							</div>
							';
			break;
			case '9':
				$formBody.='
							<div class="col-12 col-md-1 form-group">
								<label for="Id_colonia">Id:</label>
								<input type="text" class="form-control" id="Id_colonia" value="1" readonly>
							</div>
							<div class="col-12 col-md-3 form-group">
								<label for="tipo">Tipo de colonia:</label>
								<input type="text" class="form-control" id="tipo" placeholder="Ingrese el tipo de colonia">
							</div>
							<div class="col-12 col-md-4 form-group">
								<label for="colonia">Nombre de la colonia:</label>
								<input type="text" class="form-control" id="colonia" placeholder="Ingrese el nombre de la colonia">
							</div>
							';
			break;
			case '10':
				$formBody.='
							<div class="col-12 col-md-1 form-group">
								<label for="Id_calle">Id:</label>
								<input type="text" class="form-control" id="Id_calle" value="1" readonly>
							</div>
							<div class="col-12 col-md-3 form-group">
								<label for="Id_calle_desc">Nombre de la calle/niv:</label>
								<input type="text" class="form-control" id="Id_calle_desc" placeholder="Ingrese el nombre de la calle">
							</div>
							';
			break;
			case '11':
				$formBody.='
							<div class="col-12 col-md-1 form-group">
								<label for="Id_cp">Id:</label>
								<input type="text" class="form-control" id="Id_cp" value="1" readonly>
							</div>
							<div class="col-12 col-md-3 form-group">
								<label for="Codigo_postal">Codigo postal</label>
								<input type="text" class="form-control" id="Codigo_postal" placeholder="Ingrese el codigo postal">
							</div>
							<div class="col-12 col-md-3 form-group">
								<label for="Nombre">Nombre:</label>
								<input type="text" class="form-control" id="Nombre" placeholder="Ingrese el nombre">
							</div>
							';
				break;
			case '12':
				$formBody.='
							<div class="col-12 col-md-1 form-group">
								<label for="Id_categoria">Id:</label>
								<input type="text" class="form-control" id="id_clave" value="1" readonly>
							</div>
							<div class="col-12 col-md-3 form-group">
								<label for="Categoria">Nombre:</label>
								<input type="text" class="form-control" id="nombre_clave" placeholder="Ingrese nombre">
								<span class="span_error" id="errorcombinado1"></span>
							</div>
							<div class="col-12 col-md-3 form-group">
								<label for="Categoria">Apellido paterno:</label>
								<input type="text" class="form-control" id="ap_paterno_clave" placeholder="Ingrese apellido paterno">
								<span class="span_error" id="errorcombinado2"></span>
							</div>
							<div class="col-12 col-md-3 form-group">
								<label for="Categoria">Apellido Materno:</label>
								<input type="text" class="form-control" id="ap_materno_clave" placeholder="Ingrese apellido materno">
								<span class="span_error" id="errorcombinado3"></span>
							</div>
							<div class="col-12 col-md-3 form-group">
								<label for="Categoria">Nombre clave:</label>
								<input type="text" class="form-control" id="clave" placeholder="Ingrese nombre clave">
								<span class="span_error" id="errorcombinado4"></span>
							</div>
							';
			break;
			case '13':
				$formBody.='
							<div class="col-12 col-md-1 form-group">
								<label for="Id_Fuente">Id :</label>
								<input type="text" class="form-control" id="id_fuente" value="1" readonly>
							</div>
							<div class="col-12 col-md-3 form-group">
								<label for="Categoria">Fuente:</label>
								<input type="text" class="form-control" id="fuente" placeholder="Ingrese fuente">
								<span class="span_error" id="errorcombinado1"></span>
							</div>
							';
			break;
			case '14':
				$formBody.='
							<div class="col-12 col-md-1 form-group">
								<label for="Id_Area">Id :</label>
								<input type="text" class="form-control" id="id_area" value="1" readonly>
							</div>
							<div class="col-12 col-md-3 form-group">
								<label for="Categoria">Area:</label>
								<input type="text" class="form-control" id="area" placeholder="Ingrese area">
								<span class="span_error" id="errorcombinado1"></span>
							</div>
							';
			break;
			case '15':
				$formBody.='
							<div class="col-12 col-md-2 form-group">
								<label for="id_tipo_violencia">Id:</label>
								<input type="text" class="form-control" id="id_tipo_violencia" value="1" readonly>
							</div>
							<div class="col-12 col-md-6 form-group">
								<label for="id_tipo_violencia_valor">Tipo de Violencia:</label>
								<input type="text" class="form-control" id="id_tipo_violencia_valor" placeholder="Ingrese tipo de violencia">
							</div>
							';
			break;
			case '16':
				$formBody.='
							<div class="col-12 col-md-2 form-group">
								<label for="Id_Dato_Indicativo">Id:</label>
								<input type="text" class="form-control" id="Id_Dato_Indicativo" value="1" readonly>
							</div>
							<div class="col-12 col-md-6 form-group">
								<label for="Indicativo">Indicativo:</label>
								<input type="text" class="form-control" id="Indicativo" placeholder="Ingrese el indicativo">
							</div>
							';
			break;
			case '17':
				$formBody.='
							<div class="col-12 col-md-2 form-group">
								<label for="Id_Dato_Tipo">Id:</label>
								<input type="text" class="form-control" id="Id_Dato_Tipo" value="1" readonly>
							</div>
							<div class="col-12 col-md-6 form-group">
								<label for="Tipo">Tipo:</label>
								<input type="text" class="form-control" id="Tipo" placeholder="Ingrese el Tipo">
							</div>
							';
			break;
			case '18':
				$formBody.='
							<div class="col-12 col-md-2 form-group">
								<label for="Id_Dato">Id:</label>
								<input type="text" class="form-control" id="Id_Dato" value="1" readonly>
							</div>
							<div class="col-6 col-md-6 form-group">
								<label for="Calle">Calle:</label>
								<input type="text" class="form-control" id="Calle" placeholder="Ingrese la Calle">
							</div>
							<div class="col-6 col-md-6 form-group">
								<label for="Calle2">Calle2:</label>
								<input type="text" class="form-control" id="Calle2" placeholder="Ingrese la Calle 2">
							</div>
							<div class="col-12 col-md-6 form-group">
								<label for="Info_Adicional">Información Adicional:</label>
								<input type="text" class="form-control" id="Info_Adicional" placeholder="Ingrese Información Adiccional">
							</div>
							<div class="col-6 col-md-6 form-group">
								<label for="CoordX">Coordenada X:</label>
								<input type="text" class="form-control" id="CoordX" placeholder="Ingrese Coordenada X">
							</div>
							<div class="col-6 col-md-6 form-group">
								<label for="CoordY">Coordenada Y:</label>
								<input type="text" class="form-control" id="CoordY" placeholder="Ingrese Coordenada Y">
							</div>

							';
			break;
		}
		return $formBody;
	}

	//función Fetch para crear o actualizar en catálogo seleccionado
	public function sendFormFetch(){
		if (!isset($_SESSION['userdataSIC']) || $_SESSION['userdataSIC']->Modo_Admin!=1) {
            header("Location: ".base_url."Login");
            exit();
        }

        if (!isset($_POST['postForm'])) {
        	header("Location: ".base_url."Catalogos");
        }
        
        //variable de respuesta al insertar o actualizar
        $response = $this->Catalogo->InsertOrUpdateCatalogo($_POST); //se manda el POST y todo el desmadre se realiza en el modelo
		if($response == "Success"){
			$catalogo = $_POST['catalogo'];
			$action   = $_POST['action'];
			switch ($action) { //switch de action 1-insertar  2-actualizar
				case '1':
						$nombreCatalogo= $this->getNombreCatalogo($catalogo);
						$this->Historial->insertHistorial(18,'SE CREO UN REGISTRO DEL CATALOGO: '.$nombreCatalogo,$_SESSION['userdataSIC']->Id_Usuario);
				break;
				case '2':
					$nombreCatalogo= $this->getNombreCatalogo($catalogo);
					$this->Historial->insertHistorial(20,'SE ACTUALIZO UN REGISTRO DEL CATALOGO: '.$nombreCatalogo,$_SESSION['userdataSIC']->Id_Usuario);
				break;
			}
		}

        echo json_encode($response);
	}

	//función Fetch para crear o actualizar en catálogo seleccionado
	public function deleteFormFetch(){
		if (!isset($_SESSION['userdataSIC']) || $_SESSION['userdataSIC']->Modo_Admin!=1) {
            header("Location: ".base_url."Login");
            exit();
        }

        if (!isset($_POST['deletePostForm'])) {
        	header("Location: ".base_url."Catalogos");
        }
        
        //variable de respuesta al insertar o actualizar
        $response = $this->Catalogo->deleteCatalogoRow($_POST); //se manda el POST y todo el desmadre se realiza en el modelo
		if($response == "Success"){
			$catalogo = $_POST['catalogo'];
			$nombreCatalogo= $this->getNombreCatalogo($catalogo);
			$this->Historial->insertHistorial(17,'SE ELIMINO EL REGISTRO: '.$_POST['Id_Reg'] .'  DEL CATALOGO: '.$nombreCatalogo,$_SESSION['userdataSIC']->Id_Usuario);

		}

        echo json_encode($response);
	}
	/*Se añaden funciones para catalogo de colonias y calles*/
	public function getColonias()
    {
        $data = $this->Catalogo->getColonias();
        echo json_encode($data);
    }
    public function getAllCalles()
    {
        $data = $this->Catalogo->getCalles();
        echo json_encode($data);
    }
	/*Funciones para colonias y calles de catalogo y mapas con MAP BOX*/
    public function getColonia()
    {
        $data = $this->Catalogo->getColoniaCatalogo($_POST['termino']);
        echo json_encode($data);
    }
    public function getCalles()
    {
        $data = $this->Catalogo->getCallesCatalogo($_POST['termino']);
        echo json_encode($data);
    }
    public function getCP()
    {
        $data = $this->Catalogo->getCPCatalogo($_POST['cp']);
        echo json_encode($data);
    }
	public function getIncidenciasCuervosPersonas(){

        $response = $this->Catalogo->getIncidenciasCuervosPersonas(); 

        echo json_encode($response);
	}

	public function getIncidenciasCuervosVehiculos(){

        $response = $this->Catalogo->getIncidenciasCuervosVehiculos(); 

        echo json_encode($response);
	}

	public function getCatalogoPlacaNip(){

        $response = $this->Catalogo->getCatalogoPlacaNip(); 

        echo json_encode($response);
	}
	public function getCatalogoPersonas(){

        $response = $this->Catalogo->getCatalogoPersonas(); 

        echo json_encode($response);
	}
	
	public function getSubmarcasTermino(){
		$data = $this->Catalogo->getSubmarcaCatalogo($_POST['termino']);
        echo json_encode($data);
	}
	public function getMarcasTermino(){
		$data = $this->Catalogo->getMarcaCatalogo($_POST['termino']);
        echo json_encode($data);
	}
	public function getAMarcas()
    {
        $data = $this->Catalogo->getAMarcas();
        echo json_encode($data);
    }
	public function getSMarcas()
    {
        $data = $this->Catalogo->getSMarcas();
        echo json_encode($data);
    }
	public function getTipoDatos()
    {
        $data = $this->Catalogo->getTipoDatos();
        echo json_encode($data);
    }
	public function getNombreCatalogo($catalogoActual)//Para sacar el nombre del catalogo para el historial 
    {	
		$nombreCatalogo="";
		switch ($catalogoActual) {
			case '1':
				$nombreCatalogo="Catálogo de Delitos";
			break;
			case '2':
				$nombreCatalogo="Catálogo de Armas (tipos)";
			break;
			case '3':
				$nombreCatalogo="Catálogo de Tipos de Violencia(Con violencia)";
			break;
			case '4':
				$nombreCatalogo="Catálogo de zonas y sectores";
			break;
			case '5':
				$nombreCatalogo="Catálogo de vectores";
			break;
			case '6':
				$nombreCatalogo="Catálogo de marca del vehículo";
			break;
			case '7':
				$nombreCatalogo="Catálogo de tipos de vehiculos";
			break;
			case '8':
				$nombreCatalogo="Catálogo de submarcas de vehiculos";
			break;
			case '9':
				$nombreCatalogo="Catálogo de colonias";
			break;
			case '10':
				$nombreCatalogo="Catálogo de calles";
			break;
			case '11':
				$nombreCatalogo="Catálogo Codigos Postales";
			break;
			case '12':
				$nombreCatalogo="Catálogo de nombres clave";
			break;
			case '13':
				$nombreCatalogo="Catálogo de procendencia información";
			break;
			case '14':
				$nombreCatalogo="Catálogo de areas";
			break;
			case '15':
				$nombreCatalogo="Catálogo de Tipos de Violencia(Sin violencia)";
			break;
			case '16':
				$nombreCatalogo="Catálogo de Indicativo";
			break;
			case '17':
				$nombreCatalogo="Catálogo de Tipo de Datos entrevistas";
			break;
			case '18':
				$nombreCatalogo="Catálogo de Ubicaciones de Camaras";
			break;
		}
		return $nombreCatalogo;
    }

}

?>