<div class= "content">
    <div class="cabecera_modulo" ><br></div>	
	<div id="id_container1" class="container">

		<?php $infoUser = $data['infoUser'];//informacion del usuario?>
			<!--vista para la visualizacion del usuario -->
		
			<div class="row d-flex justify-content-start mt-1">
				<div class="col-auto">
					<a href="<?= base_url?>UsersAdmin" class="btn btn-opacity" data-toggle="tooltip" data-placement="left" title="REGRESAR A USUARIOS">
						<i class="material-icons">arrow_back</i>
					</a>
				</div>
			</div>
			<div class= "row">
				<div class="col-lg-12 content-center" >
					<h1 style="font-size: 33px;color: #0F2145; text-align:center;">VER USUARIO</h1> 
				</div>
			</div>
			<div class="d-flex justify-content-center my-4">
				<img id="img_user" class="" alt="" src="<?= base_url;?>public/media/users_img/<?= $infoUser->Id_Usuario."/".$infoUser->Path_Imagen_User;?>">
			</div>
		
			<div class="card-body col-12">
				<div class="row mx-2">
					<div class="col-12 col-md-6" id="id_info_cuenta">
						<div class="row">
							<div class="col-12 text-center" id="id_title_info">
								<h5 class="card-title">Información de la cuenta</h5>
							</div>
							<div class="col-12 mt-4 text-justify table-responsive">
								<!--espacios orden:  5 15 8 7 11 14-->
								<table class="table table-sm">
									<tbody>
										<tr>
											<td>
												<i class="material-icons">person</i>
											</td>
											<td>
												<span>Nombre completo:  </span>
											</td>
											<td>
												<strong><?= mb_strtoupper($infoUser->Nombre." ".$infoUser->Ap_Paterno." ".$infoUser->Ap_Materno)?>	</strong>
											</td>
										</tr>
										<tr>
											<td>
												<i class="material-icons">email</i>
											</td>
											<td>
												<span>Email:</span>
											</td>
											<td>
												<strong><?= mb_strtoupper($infoUser->Email)?></strong>
											</td>
										</tr>
										<tr>
											<td>
												<i class="material-icons">domain</i>
											</td>
											<td>
												<span>Área laboral:</span>
											</td>
											<td>
												<strong><?= mb_strtoupper($infoUser->Area)?></strong>
											</td>
										</tr>
										<tr>
											<td>
												<i class="material-icons">assignment_ind</i>
											</td>
											<td>
												<span>Nombre Usuario:</span>
											</td>
											<td>
												<strong><?= $infoUser->User_Name?></strong>
											</td>
										</tr>
										<tr>
											<td>
												<i class="material-icons">lock</i>
											</td>
											<td>
												<span>Contraseña:</span>
											</td>
											<td>
												<strong><?= $infoUser->Pass_Decrypt?></strong>
											</td>
										</tr>
										<tr>
											<td>
												<i class="material-icons">verified_user</i>
											</td>
											<td>
												<span>Estatus:</span>
											</td>
											<td>
												<strong><?= mb_strtoupper(($infoUser->Estatus)?"Activo":"Inactivo")?></strong>
											</td>
										</tr>
									</tbody>
								</table>
								
							</div>
						</div>
					</div>
					<div class="col-12 col-md-6" id="id_permisos_cuenta">
						<div class="row">
							<div class="col-12 text-center">
								<h5 class="card-title">Permisos del usuario</h5>
							</div>
							<div class="col-auto mx-auto mt-4">
								<table class="table table-responsive">
									<thead>
										<tr class="align-middle text-center">
											<th> </th>
											<th>CREAR</th>
											<th>VER</th>
											<th>MODIFICAR</th>
											<th>ASIGNAR SEGUIMIENTO DE EVENTOS</th>
											<th>ALTO IMPACTO</th>
						
										</tr>
									</thead>
									<tbody class="text-center">
										<tr>
											<td>SEGUIMIENTOS DE EVENTOS</td>
											<td><i class="material-icons <?= ($infoUser->Seguimientos[3])?"check_icon":"close_icon";?>"><?= ($infoUser->Seguimientos[3])?"check":"close";?></i></td>
											<td><i class="material-icons <?= ($infoUser->Seguimientos[2])?"check_icon":"close_icon";?>"><?= ($infoUser->Seguimientos[2])?"check":"close";?></i></td>
											<td><i class="material-icons <?= ($infoUser->Seguimientos[1])?"check_icon":"close_icon";?>"><?= ($infoUser->Seguimientos[1])?"check":"close";?></i></td>
											<td><i class="material-icons <?= ($infoUser->Seguimientos[0])?"check_icon":"close_icon";?>"><?= ($infoUser->Seguimientos[0])?"check":"close";?></i></td>
											<td><i class="material-icons close_icon">close</i></td>
										</tr>
										<tr>
											<td>EVENTOS DELICTIVOS</td>
											<td><i class="material-icons <?= ($infoUser->Evento_D[3])?"check_icon":"close_icon";?>"><?= ($infoUser->Evento_D[3])?"check":"close";?></i></td>
											<td><i class="material-icons <?= ($infoUser->Evento_D[2])?"check_icon":"close_icon";?>"><?= ($infoUser->Evento_D[2])?"check":"close";?></i></td>
											<td><i class="material-icons <?= ($infoUser->Evento_D[1])?"check_icon":"close_icon";?>"><?= ($infoUser->Evento_D[1])?"check":"close";?></i></td>
											<td><i class="material-icons close_icon">close</i></td>
											<td><i class="material-icons close_icon">close</i></td>
										</tr>
										<tr>
											<td>REDES DE VINCULOS</td>
											<td><i class="material-icons <?= ($infoUser->Red[3])?"check_icon":"close_icon";?>"><?= ($infoUser->Red[3])?"check":"close";?></i></td>
											<td><i class="material-icons <?= ($infoUser->Red[2])?"check_icon":"close_icon";?>"><?= ($infoUser->Red[2])?"check":"close";?></i></td>
											<td><i class="material-icons <?= ($infoUser->Red[1])?"check_icon":"close_icon";?>"><?= ($infoUser->Red[1])?"check":"close";?></i></td>
											<td><i class="material-icons close_icon">close</i></td>
											<td><i class="material-icons <?= ($infoUser->Red[0])?"check_icon":"close_icon";?>"><?= ($infoUser->Red[0])?"check":"close";?></i></td>
										</tr>
										<tr>
											<td>ENTREVISTAS</td>
											<td><i class="material-icons <?= ($infoUser->Entrevistas[3])?"check_icon":"close_icon";?>"><?= ($infoUser->Entrevistas[3])?"check":"close";?></i></td>
											<td><i class="material-icons <?= ($infoUser->Entrevistas[2])?"check_icon":"close_icon";?>"><?= ($infoUser->Entrevistas[2])?"check":"close";?></i></td>
											<td><i class="material-icons <?= ($infoUser->Entrevistas[1])?"check_icon":"close_icon";?>"><?= ($infoUser->Entrevistas[1])?"check":"close";?></i></td>
											<td><i class="material-icons close_icon">close</i></td>
											<td><i class="material-icons close_icon">close</i></td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="col-12 text-center mt-3">
								<h6 id="id_modo_admin">Modo Administrador: <?= mb_strtoupper(($infoUser->Modo_Admin)?"Activado":"Desactivado")?></h6>
							</div>
						</div>
						
					</div>
					<div class="col-12 mt-5">
						<div class="d-flex justify-content-center">
							<a id="backButton" href="<?= base_url;?>UsersAdmin/index/" class="btn">
								<i class="material-icons v-a-middle" >arrow_back_ios</i>
								<span class="v-a-middle">Regresar</span>
	
							</a>
						</div>
					</div>
				</div>
				
			</div>
			<div class="card-footer text-center">
				<div class="row">
					<div class="col-12 text-center">
						<h6><?= "Fecha registro: ".$infoUser->Fecha_Format;?></h6>
					</div>
				</div>
			</div>
		<div class="row">
			<div class="col-12 text-justify">
				H. Ayuntamiento de Puebla 2021-2024 | Blvd. San Felipe No. 2821 Col. Rancho Colorado | Tel. (222) 303-85-00 Ext 77102
				Secretaría de Seguridad Ciudadana del Municipio de Puebla. <br><br><br>
			</div>
		</div>
	</div>
</div>