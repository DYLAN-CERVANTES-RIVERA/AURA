<div class= "content">
    <div class="cabecera_modulo" ><br></div>
	<!--vista para la creacion usuario-->
	<div class="row d-flex justify-content-start">
		<div class="col-auto">
			<a href="<?= base_url?>UsersAdmin" class="btn btn-opacity" data-toggle="tooltip" data-placement="left" title="REGRESAR A USUARIOS">
				<i class="material-icons">arrow_back</i>
			</a>
		</div>
	</div>
	<div class= "row">
		<div class="col-lg-12 content-center" >
			<h1 style="font-size: 33px;color: #0F2145; text-align:center;">NUEVO USUARIO</h1> 
		</div>
	</div>

	<?php echo (isset($data['resultStatus']))?$data['resultStatus']:""; //status del post (con exito o sin exito)?>
	<div class="row mt-2 mx-auto">
		<form id="id_form" class="col-12" method="post" action="<?= base_url;?>UsersAdmin/crearUser" enctype="multipart/form-data" accept-charset="utf-8">
			<div class="row">
				<div class="col-12 text-center my-3">
					<h5>Información general</h5>
				</div>
				<div class="col-12 text-center my-2">
					<p class="indicaciones_p">Llene los campos correspondientes a la información requerida</p>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-12 col-md-4">
					<label for="Nombre">Nombre</label>
				    <input type="text" class="form-control" name="Nombre" id="Nombre" placeholder="Nombre" required value="<?php echo (isset($_POST['crearUser']))?$_POST['Nombre']:"";?>">
				    <small class="form-text text-muted"></small>
				</div>
				<div class="form-group col-12 col-md-4">
					<label for="Ap_Paterno">Apellido paterno</label>
				    <input type="text" class="form-control" name="Ap_Paterno" id="Ap_Paterno" placeholder="Apellido Paterno" required value="<?php echo (isset($_POST['crearUser']))?$_POST['Ap_Paterno']:"";?>">
				</div>
				<div class="form-group col-12 col-md-4">
					<label for="Ap_Materno">Apellido materno</label>
				    <input type="text" class="form-control" name="Ap_Materno" id="Ap_Materno" placeholder="Apellido Materno" required value="<?php echo (isset($_POST['crearUser']))?$_POST['Ap_Materno']:"";?>">
				 
				</div>
			</div>
			<div class="row">
				<div class="form-group col-12 col-md-4">
					<label for="Email">Email</label>
				    <input type="email" class="form-control" name="Email" id="Email" placeholder="example@gmail.com" required value="<?php echo (isset($_POST['crearUser']))?$_POST['Email']:"";?>">
				    <small class="form-text text-muted"><?= (isset($data['errorForm']['Email']))?$data['errorForm']['Email']:"";?></small>
				</div>
				<div class="form-group col-12 col-md-4">
				    <label for="Area">Área</label>
				    <select class="form-control" id="Area" name="Area">
						<?php foreach ($data['data_catalogo']['Areas'] as $item) : ?>
								<option value="<?php echo $item->Area; ?>"><?php echo $item->Area; ?></option>
						<?php endforeach ?>
					</select>
				</div>
				<div class="form-group col-12 col-md-4">
				    <label for="Estatus">Estatus</label>
				    <select class="form-control" id="Estatus" name="Estatus">
				      <option value="1" <?php echo (isset($_POST['crearUser']) && $_POST['Estatus'] == "1")?"selected":"";?>>ACTIVO</option>
				      <option value="0" <?php echo (isset($_POST['crearUser']) && $_POST['Estatus'] == "0")?"selected":"";?>>INACTIVO</option>
				    </select>
				</div>
			</div>
			<div class="row">
				<div class="col-12 text-center mt-3 mb-3">
					<h5>Información de la sesión</h5>
				</div>
				<div class="col-12 text-center my-2">
					<p class="indicaciones_p">Ingrese el usuario y contraseña para el inicio de sesión del nuevo usuario</p>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-12 col-md-4 offset-md-2">
					<label for="User_Name">Nombre de Usuario</label>
				    <input type="text" class="form-control" name="User_Name" id="User_Name" placeholder="Ejemplo: ROKY" required value="<?php echo (isset($_POST['crearUser']))?$_POST['User_Name']:"";?>">
				    <small class="form-text text-muted"><?= (isset($data['errorForm']['User_Name']))?$data['errorForm']['User_Name']:"";?></small>
				</div>
				<div class="form-group col-12 col-md-4">
					<label for="id_pass">Contraseña</label>
					<div id="id_pass" class="input-group">
		                <input id="id_input_pass" type="password" name="Password" class="form-control py-2 border-right-0 border" placeholder="Contraseña" required value="<?php echo (isset($_POST['crearUser']))?$_POST['Password']:"";?>" aria-describedby="button-addon2" onkeypress="return valideMultiples(event);">
		                <span class="input-group-append">
		                    <div id="id_pass_button" class="input-group-text bg-transparent"><i class="material-icons md-18 ssc view-password">visibility</i></div>
		                </span>
		            </div>
		            <!--small class="form-text text-muted">Contraseña de usueario</small-->
				</div>
				
			</div>
			<div class="row">
				<div class="col-12 text-center mt-3 mb-3">
					<h5>Permisos</h5>
				</div>
				<div class="col-12 text-center my-2">
					<p class="indicaciones_p">Marque los permisos que tendrá el nuevo usuario conforme a los módulos del sistema. Si el usuario tedrá permisos de administrador, solo marque la correspondiente casilla</p>
				</div>
			</div>
			<div class="row d-flex justify-content-center mt-2 mb-3" >
				<div class="col-auto">
					<table class="table table-responsive">
					  <thead class="thead-myTable">
						    <tr>

							    <th >
							    	<div class="row d-flex justify-content-center">
							    		Seguimientos de Eventos
							    	</div>
							    	<div class="row d-flex justify-content-center">
							    		<input class="checkPermisos" type="checkbox" value="1" id="all_seguimientos">
							    	</div>
							    </th>
							    <th >
							    	<div class="row d-flex justify-content-center">
							    		Eventos Delictivos
							    	</div>
							    	<div class="row d-flex justify-content-center">
							    		<input class="checkPermisos" type="checkbox" value="1" id="all_eventos">
							    	</div>
							    </th>
								<th >
							    	<div class="row d-flex justify-content-center">
							    		Redes de vinculos
							    	</div>
							    	<div class="row d-flex justify-content-center">
							    		<input class="checkPermisos" type="checkbox" value="1" id="all_redes">
							    	</div>
							    </th>
								<th >
							    	<div class="row d-flex justify-content-center">
							    		Entrevistas
							    	</div>
							    	<div class="row d-flex justify-content-center">
							    		<input class="checkPermisos" type="checkbox" value="1" id="all_entrevistas">
							    	</div>
							    </th>
						    </tr>
					  </thead>
					  <tbody>
						  	<tr>

						  		<td>
						  			<div class="form-group form-check col-12">
									    <input type="checkbox" class="form-check-input checkPermisos" value="1" id="S_Create" name="S_Create" <?= (isset($_POST['crearUser']) && isset($_POST['S_Create']) )?"checked":"";?> >
									    <label class="form-check-label" for="S_Create">Crear</label>
									</div>
						  		</td>
						  		<td>
						  			<div class="form-group form-check col-12">
									    <input type="checkbox" class="form-check-input checkPermisos" value="1" id="E_Create" name="E_Create" <?= (isset($_POST['crearUser']) && isset($_POST['E_Create']) )?"checked":"";?> >
									    <label class="form-check-label" for="E_Create">Crear</label>
									</div>
						  		</td>
								<td>
						  			<div class="form-group form-check col-12">
									    <input type="checkbox" class="form-check-input checkPermisos" value="1" id="Red_Create" name="Red_Create" <?= (isset($_POST['crearUser']) && isset($_POST['Red_Create']) )?"checked":"";?> >
									    <label class="form-check-label" for="Red_Create">Crear</label>
									</div>
						  		</td>
								  <td>
						  			<div class="form-group form-check col-12">
									    <input type="checkbox" class="form-check-input checkPermisos" value="1" id="Entrevista_Create" name="Entrevista_Create" <?= (isset($_POST['crearUser']) && isset($_POST['Entrevista_Create']) )?"checked":"";?> >
									    <label class="form-check-label" for="Entrevista_Create">Crear</label>
									</div>
						  		</td>
						  	</tr>
						  	<tr>
							 
						  		<td>
						  			<div class="form-group form-check col-12">
									    <input type="checkbox" class="form-check-input checkPermisos" value="1" id="S_Read" name="S_Read" <?= (isset($_POST['crearUser']) && isset($_POST['S_Read']) )?"checked":"";?> >
									    <label class="form-check-label" for="S_Read">Consultar</label>
									</div>
						  		</td>
						  		<td>
						  			<div class="form-group form-check col-12">
									    <input type="checkbox" class="form-check-input checkPermisos" value="1" id="E_Read" name="E_Read" <?= (isset($_POST['crearUser']) && isset($_POST['E_Read']) )?"checked":"";?> >
									    <label class="form-check-label" for="E_Read">Consultar</label>
									</div>
						  		</td>
								<td>
									<div class="form-group form-check col-12">
										<input type="checkbox" class="form-check-input checkPermisos" value="1" id="Red_Read" name="Red_Read" <?= (isset($_POST['crearUser']) && isset($_POST['Red_Read']) )?"checked":"";?> >
										<label class="form-check-label" for="Red_Read">Consultar</label>
									</div>
						  		</td>
								<td>
									<div class="form-group form-check col-12">
										<input type="checkbox" class="form-check-input checkPermisos" value="1" id="Entrevista_Read" name="Entrevista_Read" <?= (isset($_POST['crearUser']) && isset($_POST['Entrevista_Read']) )?"checked":"";?> >
										<label class="form-check-label" for="Entrevista_Read">Consultar</label>
									</div>
						  		</td>
						  	</tr>
						  	<tr>
							
						  		<td>
						  			<div class="form-group form-check col-12">
									    <input type="checkbox" class="form-check-input checkPermisos" value="1" id="S_Update" name="S_Update" <?= (isset($_POST['crearUser']) && isset($_POST['S_Update']) )?"checked":"";?> >
									    <label class="form-check-label" for="S_Update">Modificar</label>
									</div>
						  		</td>
						  		<td>
						  			<div class="form-group form-check col-12">
									    <input type="checkbox" class="form-check-input checkPermisos" value="1" id="E_Update" name="E_Update" <?= (isset($_POST['crearUser']) && isset($_POST['E_Update']) )?"checked":"";?> >
									    <label class="form-check-label" for="E_Update">Modificar</label>
									</div>
						  		</td>
								<td>
						  			<div class="form-group form-check col-12">
									    <input type="checkbox" class="form-check-input checkPermisos" value="1" id="Red_Update" name="Red_Update" <?= (isset($_POST['crearUser']) && isset($_POST['Red_Update']) )?"checked":"";?> >
									    <label class="form-check-label" for="Red_Update">Modificar</label>
									</div>
						  		</td>
								<td>
						  			<div class="form-group form-check col-12">
									    <input type="checkbox" class="form-check-input checkPermisos" value="1" id="Entrevista_Update" name="Entrevista_Update" <?= (isset($_POST['crearUser']) && isset($_POST['Entrevista_Update']) )?"checked":"";?> >
									    <label class="form-check-label" for="Entrevista_Update">Modificar</label>
									</div>
						  		</td>
						  	</tr>
							<tr>

						  		<td>
						  			<div class="form-group form-check col-12">
									    <input type="checkbox" class="form-check-input checkPermisos" value="1" id="S_Delete" name="S_Delete" <?= (isset($_POST['crearUser']) && isset($_POST['S_Delete']) )?"checked":"";?>>
									    <label class="form-check-label" for="S_Delete">Asignar Seguimientos de eventos</label>
									</div>
						  		</td>
						  		<td>
						  			<div class="form-group form-check col-12 mi_hide">
									    <input type="checkbox" class="form-check-input checkPermisos" value="1" id="E_Delete" name="E_Delete" <?= (isset($_POST['crearUser']) && isset($_POST['E_Delete']) )?"checked":"";?>>
									    <label class="form-check-label" for="E_Delete">Borrar</label>
									</div>
						  		</td>
								<td>
						  			<div class="form-group form-check col-12">
									    <input type="checkbox" class="form-check-input checkPermisos" value="1" id="Red_Delete" name="Red_Delete" <?= (isset($_POST['crearUser']) && isset($_POST['Red_Delete']) )?"checked":"";?>>
									    <label class="form-check-label" for="Red_Delete">Usuario Alto Impacto</label>
									</div>
						  		</td>
								<td>
									<div class="form-group form-check col-12 ">
										<input type="checkbox" class="form-check-input checkPermisos" value="1" id="Entrevista_Delete" name="Entrevista_Delete" <?= (isset($_POST['crearUser']) && isset($_POST['Entrevista_Delete']) )?"checked":"";?>>
										<label class="form-check-label" for="Entrevista_Delete">Exportar Entrevistas</label>
									</div>
								</td>
						  	</tr>
						  	
					  </tbody>
					</table>
				</div>
			</div>
			<div class="row mt-2 mb-5" >
			
				<div class="col-12 form-group form-check text-center">
				    <input type="checkbox" class="form-check-input" value="1" id="Modo_Admin" onclick="disablePermisos()" name="Modo_Admin" <?= (isset($_POST['crearUser']) && isset($_POST['Modo_Admin']))?"checked":"";?>>
				    <label class="form-check-label" for="Modo_Admin" >Modo Administrador</label>
				</div>
			</div>
			<div class="row">
				<div class="col-12 text-center mt-3 mb-3">
					<h5>Imagen del usuario</h5>
				</div>
				<div class="col-12 text-center my-2">
					<p class="indicaciones_p">Suba una imagen de perfil.</p>
				</div>
			</div>
			<div class="row mt-3 mb-3">
				<div class="col-8 col-md-6 offset-2 offset-md-3 ">
					<label for="id_image">Imagen usuario</label>
					<div id="id_image" class="input-group">
						<div class="custom-file">
							<label id="label_foto_file" class="custom-file-label" for="id_foto_file" data-browse="Buscar">Subir imagen</label>
					    	<input type="file" class="custom-file-input" id="id_foto_file" name="foto_file">
					  	</div>
					</div>
					<small id="error_img1" class="form-text text-danger">Tamaño máximo 8MB, formatos: jpg/png</small>
				</div>
			</div>
			<div class="row mt-3 mb-5">
				<div class=" col-lg-4 col-sm-12 offset-lg-4 mt-sm-4 d-flex justify-content-center align-items-center">
                    <div id="preview_1" class="preview"></div>
                </div>
			</div>
			
			<div class="row mt-4 mb-5">
				<div class="col-12 col-md-3 offset-md-3 mb-4 mb-md-0">
					<div class="d-flex justify-content-center">
						<a id="backButton" href="<?= base_url;?>UsersAdmin/index/" class="btn">
							<i class="material-icons v-a-middle">arrow_back_ios</i>
        					<span class="v-a-middle">Regresar</span>

						</a>
					</div>
				</div>
				<div class="col-12 col-md-3 d-flex justify-content-center">
					<button type="submit" id="mySubmit" class="btn btm-ssc" name="crearUser">Crear usuario</button>
				</div>
			</div>
			
		</form>

	</div>
</div>