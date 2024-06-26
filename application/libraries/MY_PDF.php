<?php
/**
 //$array = json_decode(json_encode($booking), true);
 */
class MY_PDF
{
	
	public function getPlantilla($data=null){
		$plantilla = '';

		if ($data == null) {
			return "";
		}

		$subtitulo 	= $data['subtitulo'];
		$mensaje 	= $data['msg'];
		$theads 	= '';
		$field_names= $data['field_names'];
		$body		= ''; 

		//obteniendo y generando los heads de la tabla
		foreach ($data['columns'] as $column) {
			$theads.= '<th>'.$column.'</th>';
		}

		//obteniendo y enerando cada registro con sus respectivos valores
		foreach ($data['rows'] as $row) {
			$body.= '<tr>';
			foreach ($field_names as $f_name) {
				$body.= '<td>'.mb_strtoupper($row->$f_name).'</td>';
			}
			$body.= '</tr>';
		}

		$FontStyleTable = (count($data['columns']) >= 8)?'style="font-size: 11px;"':'';
		$plantilla.= '
			<body>
			    <div>
			    	<div class="row mb-1 no_border">
						<table class="table">
						  <tbody style="">
						    <tr style="">
						      <td style="">
						      	<img src="'.base_url.'public/media/images/logo2.png" height="80px" >
						      </td>
						      <td style="vertical-align: middle; text-align: center;">
						      	<h3>Administrador Unificado de Reportes y Analisis</h3>
						      	<hr>
						      	<span>Exportación de '.$subtitulo.'</span>
						      </td>
						    </tr>
						  </tbody>
						</table>
					</div>
					<div class="row mb-2" style="text-align: center;">
						<h5 style="color: #616161;">La siguiente tabla muestra '.$mensaje.'</h5>
					</div>
					<div class="form-row row">
						<div class="table-responsive">
							<table class="table table-bordered" >
								<thead class="text-center thead-dark">
									<tr id="id_thead" >
										'.$theads.'
									</tr>
								</thead>
								<tbody id="id_tbody" class="text-justify">
									'.$body.'
								</tbody>
							</table>
						</div>	
					</div>
				</div>
			</body>
		';

		return $plantilla;
	}

}

?>
				
