<div class= "content">
    <div class="cabecera_modulo" ><br></div>
	<div class="container mt-1 mb-1 ">
		<div class="row d-flex justify-content-start">
			<div class="col-auto">
				<a href="<?= base_url?>Catalogos" class="btn btn-opacity" data-toggle="tooltip" data-placement="left" title="REGRESAR A LOS CATÁLOGOS">
					<i class="material-icons">arrow_back</i>
				</a>
			</div>
		</div>
		<div class="row">
			<div class="col-12 text-center">
				<h3 class="myDisplay" style="font-size: 33px;color: #0F2145; text-align:center;">
					<?php
						switch ($data['catalogoActual']) {
							case '1': echo "CATÁLOGO DE DELITOS"; break;
							case '2': echo "CATÁLOGO DE ARMAS (TIPOS)"; break;
							case '3': echo "CATÁLOGO DE TIPOS DE VIOLENCIA (Con violencia)"; break;
							case '4': echo "CATÁLOGO DE ZONAS"; break;
							case '5': echo "CATÁLOGO DE VECTORES"; break;
							case '6': echo "CATÁLOGO DE MARCAS DE VEHÍCULOS"; break;
							case '7': echo "CATÁLOGO DE TIPOS DE VEHÍCULOS"; break;
							case '8': echo "CATÁLOGO DE SUBMARCAS DE VEHÍCULOS"; break;
							case '9': echo "CATÁLOGO DE COLONIAS"; break;
							case '10': echo "CATÁLOGO DE CALLES"; break;
							case '11': echo "CATÁLOGO DE CODIGOS POSTALES"; break;
							case '12': echo "CATÁLOGO DE NOMBRE CLAVE"; break;
							case '13': echo "CATÁLOGO DE PROCENDENCIA DE INFORMACIÓN"; break;
							case '14': echo "CATÁLOGO DE AREAS"; break;
							case '15': echo "CATÁLOGO DE TIPOS DE VIOLENCIA (Sin violencia)"; break;
							case '16': echo "CATÁLOGO DE INDICATIVO ENTREVISTADOR"; break;
							case '17': echo "CATÁLOGO DE TIPOS DE DATO ENTREVISTA"; break;
							case '18': echo "CATÁLOGO DE CAMARAS"; break;
						}
					?>
					<input id="catalogoActual" type="hidden" value="<?= $data['catalogoActual']?>">
				</h3>
			</div>
		</div>
		


		<div class="container">
			<div class="row d-flex justify-content-center">

				<div class="col-12 pr-4 pl-4">
					<div class="row d-flex justify-content-start mt-3 mb-3">
						<div class="col-12 col-md-3 col-lg-2 my-auto ocultar">
							<button class="btn btn-opacity" data-toggle="tooltip" data-placement="left" title="nuevo registro" onclick="addAction(<?= $data['catalogoActual']?>)">
								<span class="v-a-middle">Nuevo </span>
								<i class="material-icons md-30 v-a-middle" >add</i>
								
							</button>
						</div>
						<?php if ($data['catalogoActual']==32 || $data['catalogoActual']==33){?>
							<div class="col-12 col-md-3 col-lg-2 my-auto">
							<button class="btn btn-opacity" data-toggle="tooltip" data-placement="left" title="Ver Registros de Incidencias" onclick="switchTable(<?= $data['catalogoActual']?>)">
								<span class="v-a-middle">Ver incidencias </span>
								<i class="material-icons md-30 v-a-middle" >repeat</i>	
							</button>
						</div>
						<?php } ?>
						<div class="col-12 col-md-9 col-lg-10" id="id_form_catalogo">
							<div class="row">
								<!--Se imprimen los input correspondientes a cada catálogo del sistema-->
								<?= $data['infoTable']['formBody'];?>

								<div class="col-6 col-md-2 my-auto d-flex justify-content-center">
									<button id="send_button" class="btn" onclick="sendFormAction(<?= $data['catalogoActual']?>)">
										<span class="v-a-middle">Guardar</span>
									</button>
								</div>
								<div class="col-6 col-md-2 my-auto d-flex justify-content-center">
									<button id="cancel_button" class="btn" onclick="hideForm()">
										<span class="v-a-middle">Cancelar</span>
									</button>
								</div>
							</div>
							
						</div>
					</div>

					<div class="row mt-2 mb-3 ocultar">
						<div class="col-12 col-md-3 mr-auto ">
							<div class="row">
								<div id="buttonsExport" class="col-12">
									<?php 
										$cadenaExport = (isset($data['cadena'])) ? ("&cadena=" . $data['cadena']) : "";
										$catalogoActualExport = "&catalogoActual=".$data['catalogoActual'];
									?>

									<a id="id_link_excel" href="<?= base_url ?>Catalogos/exportarInfo/?tipo_export=<?= "EXCEL" . $cadenaExport.$catalogoActualExport; ?>" class="btn" data-toggle="tooltip" data-placement="bottom" title="Exportar a Excel">
										<i class="material-icons ssc md-36">description</i>
										<!--img src="<?= base_url ?>public/media/icons/excelIcon.png" width="40px"--!-->
									</a>
									<a id="id_link_pdf" href="<?= base_url ?>Catalogos/exportarInfo/?tipo_export=<?= "PDF" . $cadenaExport.$catalogoActualExport; ?>" target="_blank" class="btn" data-toggle="tooltip" data-placement="bottom" title="Exportar a PDF">
										<i class="material-icons ssc md-36">picture_as_pdf</i>
										<!--img src="<?= base_url ?>public/media/icons/pdfIcon.png" width="40px"--!-->
									</a>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-9 my-auto ocultar">
							<div class="row d-flex justify-content-end">
								<div class="col-auto my-auto" id="id_total_rows">
									Total registros: <?= (isset($data['total_rows']))?$data['total_rows']:"null";?>
								</div>
								<div class="col-auto">
									<?php $cadena = (isset($data['cadena'])) ? $data['cadena'] : ""; ?>
									<div class="input-group">
										<input id="id_search" type="search" name="busqueda" value="<?= $cadena; ?>" id="busqueda" class="form-control py-2 border-right-0 border" placeholder="Buscar" required="required" aria-describedby="button-addon2" onkeyup="return checarCadena(event)" onchange="return checarCadena(event)">
										<span class="input-group-append">
											<div id="search_button" class="input-group-text bg-transparent"><i class="material-icons md-18 ssc search" id="filtro">search</i></div>
										</span>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="container ocultar">
			<div class="row d-flex justify-content-center">
				<div class="col-auto">
					<table class="table table-responsive table-striped">
						<thead class="thead-myTable text-center">
							<tr id="id_thead" >
								<?php
									//se imprimen los encabezados conforme al catálogo seleccionado 
									echo $data['infoTable']['header'];
								?>
							</tr>
						</thead>
						<tbody id="id_tbody" class="text-justify">
							<?php
								//se imprime todos los registros tabulados de la consulta
								echo $data['infoTable']['body'];
							?>
						</tbody>
					</table>
				</div>
					
			</div>
			
		</div>


		<!--Despliegue de Links de Pagination-->
		<div class="container mt-3 mb-5 ocultar">
			<div class="row d-flex justify-content-center">
				<div class="col-auto">
					<nav aria-label="Page navigation example ">
						<ul id="id_pagination" class="pagination">
							<?php
							echo $data['links'];
							?>
						</ul>
					</nav>
				</div>
			</div>
		</div>
		<!-- Nueva tabla solo de vista -->

		<div class="container">
			<div class="row d-flex justify-content-center">

				<div class="col-12 pr-4 pl-4">
					<div class="row mt-2 mb-3 ocultar mi_hide">
						<div id="buttonsExport" class="col-3">
							<a id="id_link_excel_incidencias" h class="btn" data-toggle="tooltip" data-placement="bottom" title="Exportar a Excel">
								<i class="material-icons ssc md-36">description</i>
							</a>
						</div>
						<div class="col-12 col-md-9 my-auto ocultar mi_hide" >
							<div class="row d-flex justify-content-end">
								<div class="col-auto my-auto" id="id_total_rows_incidencias">
									Total registros:
								</div>
								<div class="col-auto">
									<div class="input-group">
										<input id="id_search_incidencias" type="search" name="busqueda" id="busqueda_incidencias" class="form-control py-2 border-right-0 border" placeholder="Buscar" required="required">
										<span class="input-group-append">
											<div id="search_button_incidencias" class="input-group-text bg-transparent"><i class="material-icons md-18 ssc search" id="filtro_incidencias">search</i></div>
										</span>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="container ocultar mi_hide">
			<div class="row d-flex justify-content-center">
				<div class="col-auto">
					<table class="table table-responsive table-striped">
						<thead class="thead-myTable text-center">
							<tr id="id_thead_incidencias" >

							</tr>
						</thead>
						<tbody id="id_tbody_incidencias" class="text-justify">

						</tbody>
					</table>
				</div>

			</div>

		</div>


		<!--Despliegue de Links de Pagination-->
		<div class="container mt-3 mb-5 ocultar mi_hide">
			<div class="row d-flex justify-content-center">
				<div class="col-auto">
					<nav aria-label="Page navigation example ">
						<ul id="id_pagination_incidencias" class="pagination">
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</div>
</div>