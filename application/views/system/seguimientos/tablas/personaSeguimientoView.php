<div class="container-fluid">
    <!--vista para generar datos de personas en el seguimiento -->
    <form id='datos_personas' onsubmit="event.preventDefault()">
        <div class="container">
            <!--PARA MOSTRAR SI SE CARGARON LOS DATOS O UBO UN ERROR-->
            <div class="col-12 my-4" id="msg_principales_personas"></div>
            <h5 class="titulo-azul mt-3">Personas Identificadas Asociadas</h5>
            <div class="alert fondo-azul" role="event" style="display:none" id="alertaEditPersona">
                Se está realizando la edición a una persona.
            </div>
            <div class="row mt-2 mi_hide">
                <div class="col-lg-6 col-sm-6">
                    <h5 class="subtitulo-rosa">Id de Seguimiento Asignado:</h5>
                    <input type="text" name="id_seguimiento_persona" id="id_seguimiento_persona" class="form-control custom-input_dt">
                    <span class="span_error" id="id_seguimiento_persona_error"></span> 
                </div>
                <div class="col-lg-6 col-sm-6 ">
                    <h5  class="subtitulo-rosa">Elemento que Captura dato de persona: </h5>
                    <input  style="font-size: 15px;color: #0F2145;"type="text" name="captura_dato_persona" id="captura_dato_persona" value="<?php echo $_SESSION['userdataSIC']->User_Name?>" class="form-control custom-input_dt text-uppercase" disabled="true">
                    <span class="span_error" id="captura_dato_persona_error"></span>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-6">
                    <h5  class="subtitulo-rosa">¿Tiene alguna remisión capturada en SARAI la persona?</h5>
                </div>
                <div class="col-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="Remision_Si_No" id="id_rem_1" value="1" onchange="return changeRemision(event)" >
                        <label class="form-check-label" for="id_rem_1">Si</label>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="Remision_Si_No" id="id_rem_2" checked value="0" onchange="return changeRemision(event)" >
                        <label class="form-check-label" for="id_rem_2">No</label>
                    </div>
                </div>
                <div class="col-12 text-center">
                    <small id="error_remision_si_no" class="form-text text-danger"></small>
                </div>
            </div>
            <div class="row mt-5 mi_hide" id="id_Remision_panel">
                <div class="col-5">   
                    <h5  class="subtitulo-rosa">Ingrese el numero de remisión: </h5>            
                </div>
                <div class="col-7">
                    <input type="text" class="form-control form-control-sm " placeholder="Buscar"  id="id_remision" name="id_remision"  onkeypress="return valideKey(event);" >
                </div>
                <div class="col-12 text-center">
                    <small id="error_remision" class="form-text text-danger"></small>
                </div>
            </div>

            <div class="form-row mt-3">
                <div class="form-group col-lg-1 mi_hide" >
                    <input type="text" class="form-control form-control-sm " id="Id_Personas" name="Id_Personas" value="SD" disabled>
                </div>
                <div class="form-group col-lg-6 col-sm-6">
                    <label for="nombre" class="subtitulo-rosa">Nombre:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="30" id="nombre" name="nombre" placeholder="Ingrese Nombre" onkeypress="return valida(event);">
                    <span class="span_error" id="nombre_error"></span>
                </div>
                <div class="form-group col-lg-3 col-sm-6">
                    <label for="ap_paterno" class="subtitulo-rosa">Apellido Paterno:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="30" id="ap_paterno" name="ap_paterno" placeholder="Ingrese Apellido Paterno" onkeypress="return valida(event);">
                    <span class="span_error" id="ap_paterno_error"></span>
                </div>
                <div class="form-group col-lg-3 col-sm-6">
                    <label for="ap_materno" class="subtitulo-rosa">Apellido Materno:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="30" id="ap_materno" name="ap_materno" placeholder="Ingrese Apellido Materno" onkeypress="return valida(event);">
                    <span class="span_error" id="ap_materno_error"></span>
                </div>
                <div class="form-group col-lg-6 col-sm-6">
                    <label for="curp" class="subtitulo-rosa">CURP:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="19"id="curp" name="curp" placeholder="Ingrese Curp"onkeypress="return validaCurp(event);" >
                    <span class="span_error" id="curp_error"></span>
                </div>

                <div class="form-group col-lg-3 col-sm-6">
                    <label for="Fecha_n" class="subtitulo-rosa">Fecha de Nacimiento:</label>
                    <input type="date" class="form-control form-control-sm" id="FechaNacimiento_principales" name="FechaNacimiento_principales">
                    <span class="span_error" id="FechaNacimiento_principales_error"></span>
                </div>
                <div class="form-group col-lg-3">
                    <label for="Genero" class="label-form subtitulo-rosa">Genero:</label>
                    <select class="custom-select custom-select-sm" id="Genero" name="Genero">
                        <option value="SD">SELECCIONE GENERO</option>
                        <option value="MASCULINO">MASCULINO</option>
                        <option value="FEMENINO">FEMENINO</option>
                    </select>
                    <span class="span_error" id="genero_error"></span>
                </div>
                <div class="form-group col-lg-3">
                    <label for="Rol" class="label-form subtitulo-rosa">Rol:</label>
                    <select class="custom-select custom-select-sm" id="Rol" name="Rol">
                        <option value="INTEGRANTE">INTEGRANTE</option>
                        <option value="LIDER">LIDER</option>
                        <option value="COLIDER">COLIDER</option>
                        <option value="CONSULTA">CONSULTA DE INFORMACION</option>
                    </select>
                    <span class="span_error" id="rol_error"></span>
                </div>
                <div class="form-group col-lg-3 col-sm-6">
                    <label for="telefono" class="subtitulo-rosa">Telefono:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="50" id="telefono" name="telefono" placeholder="Ingrese Telefono" >
                    <span class="span_error" id="telefono_error"></span>
                </div>
                <div class="form-group col-lg-6">
                    <label for="alias" class="label-form subtitulo-rosa">Alias de la Persona:</label>
                    <textarea name="alias" id="alias" cols="45" rows="3" class="form-control form-control-sm text-uppercase"placeholder="Ingrese alias de la personas separados por ','" onkeypress="return valideMultiplesDatos(event);"></textarea>
                    <span class="span_error" id="alias_error"> </span>
                </div>
                <div class="form-group col-lg-9">
                    <label for="remisiones" class="label-form subtitulo-rosa">Remisiones de la Persona:</label>
                    <textarea name="remisiones" id="remisiones" cols="45" rows="3" class="form-control form-control-sm text-uppercase"placeholder="Ingrese remisiones que tenga la persona separadas por ','"onkeypress="return validePanelRemisiones(event);"></textarea>
                    <span class="span_error" id="remisiones_error"> </span>
                </div>
                <div class="form-group col-lg-3" align="right">
                    <br>
                    <button type="button" class="btn btn-add button-movil-plus" onclick="onFormPersonaSubmit()">Agrega Persona</button>
                </div>
            </div>
        </div>    
        <div class="container-fluid">
            <div class="form-row row"> 
                <div class="form-group col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="PersonaTable" style="text-align:center">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" style="display:none;">Id_Persona</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Apellido Paterno</th>
                                    <th scope="col">Apellido Materno</th>
                                    <th scope="col">Curp</th>
                                    <th scope="col">Fecha de Nacimiento</th>
                                    <th scope="col">Edad</th>
                                    <th scope="col">Genero</th>
                                    <th scope="col">Telefono</th>
                                    <th scope="col">Alias</th>
                                    <th scope="col">Remisiones</th>
                                    <th scope="col">Foto</th>
                                    <th scope="col">Rol</th>
                                    <th scope="col">Capturo</th>
                                    <th scope="col">Editar/Eliminar</th>
                                </tr>
                            </thead>
                            <tbody id="contarRes">
                            </tbody>
                        </table>
                    </div><br>
                    <span class="span_error" id="tabla_personas_error"></span>
                </div>
            </div>
        </div>
        <div class="modal fade" id="ModalCenterFoto2" tabindex="-1" role="dialog" aria-labelledby="myModalExito" aria-hidden="true" >
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <img name="nor" src="<?= base_url; ?>/public/media/images/logo6.png"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
            </div>
        </div>
        <div class="row mt-5 mb-5">
            <div class="d-flex justify-content-end col-sm-12" id="id_p">
                <a class="btn btn-ssc  mr-3" href="<?= base_url; ?>Seguimientos"><i class="material-icons v-a-middle">arrow_back_ios</i>Volver al inicio</a>
                <a class="btn btn-ssc " id="btn_personas" value='1'>Guardar</a>
            </div>
        </div>
    </form>
</div>