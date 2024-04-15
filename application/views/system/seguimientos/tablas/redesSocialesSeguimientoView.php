<div class="container-fluid">
    <!--vista para generar datos de domicilios en el seguimiento -->
    <form id='datos_Redsocial' onsubmit="event.preventDefault()">
        <div class="container">
            <!--PARA MOSTRAR SI SE CARGARON LOS DATOS O UBO UN ERROR-->
            <div class="col-12 my-4" id="msg_principales_Redsocial"></div>
            <h5 class="titulo-azul">Datos de Redes Sociales Personas Asociadas</h5>
            <div class="alert fondo-azul" role="event" style="display:none" id="alertaEditRedsocial">
                Se está realizando la edición a un registro de Red social.
            </div>
            <div class="row mt-2 mi_hide">
                <div class="col-lg-6 col-sm-6">
                    <h5 class="subtitulo-rosa">Id de Seguimiento Asignado:</h5>
                    <input type="text" name="id_seguimiento_Redsocial" id="id_seguimiento_Redsocial" class="form-control custom-input_dt">
                    <span class="span_error" id="id_seguimiento_Redsocial_error"></span> 
                </div>
                <div class="col-lg-6 col-sm-6 ">
                    <h5  class="subtitulo-rosa">Elemento que Captura dato de persona: </h5>
                    <input  style="font-size: 15px;color: #0F2145;"type="text" name="captura_dato_Redsocial" id="captura_dato_Redsocial" value="<?php echo $_SESSION['userdataSIC']->User_Name?>" class="form-control custom-input_dt text-uppercase" disabled="true">
                    <span class="span_error" id="captura_dato_Redsocial_error"></span>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="form-group col-lg-6" >
                    <h5  class="subtitulo-rosa">Selecciona la persona a la cual se asociará el dato de la red social:</h5>
                </div>
                <div class="form-group col-lg-6"  id="Persona_Select_Redsocial">
                    <select class="custom-select custom-select-sm" id="PersonaSelectRedsocial">
                    </select>
                    <span class="span_error" id="PersonaSelect_Redsocial_error"></span>
                </div>
            </div>
            <hr style="height:5px;border:none;color:#29295f;background-color:#29295f;"\>
            <div class="row mt-3">
                <div class="form-group col-lg-1 mi_hide" >
                    <input type="text" class="form-control form-control-sm " id="Id_Registro" name="Id_Registro" value="SD" disabled>
                </div>
                <div class="form-group col-lg-8">
                    <label for="nombre_perfil" class="subtitulo-rosa"> Nombre de usuario (Perfil):</label>
                    <input type="text" class="form-control form-control-sm " id="nombre_perfil" name="nombre_perfil" placeholder="INGRESE EL NOMBRE DE USUARIO" >
                    <span class="span_error" id="nombre_perfil_error"></span>
                </div>
                <div class="form-group col-lg-9">
                    <label for="enlace" class="subtitulo-rosa">Enlace (URL):</label>
                    <input type="text" class="form-control form-control-sm " id="enlace" name="enlace" placeholder="INGRESE EL URL DE ENLACE" >
                    <span class="span_error" id="enlace_error"></span>
                </div>
                <div class="form-group col-lg-3"  id="Select_Redsocial_tipo_url">
                    <label for="url" class="subtitulo-rosa"> Tipo de URL:</label>
                    <select class="custom-select custom-select-sm" id="Redsocial_tipo_url">
                        <option value="SD" selected>SELECCIONA UNA OPCION</option>
                        <option value="PERFIL" >PERFIL</option>
                        <option value="PUBLICACION">PUBLICACION</option>
                    </select>
                    <span class="span_error" id="Redsocial_tipo_url_error"></span>
                </div>
                <div class="form-group col-lg-12">
                    <label for="Redsocial" class="subtitulo-rosa">Observacion de dato de red social:</label>
                    <textarea name="Redsocial_Observacion" id="Redsocial_Observacion" cols="45" rows="7" class="form-control form-control-sm text-uppercase"placeholder="Ingrese Observacion de dato de red social"onkeypress="return valideMultiples(event);"></textarea>   
                    <span class="span_error" id="Redsocial_Observacion_error"></span>
                </div>
                <div class="form-group col-lg-9"></div>
                <div class="form-group col-lg-3">
                    <button type="button" class="btn btn-add button-movil-plus" onclick="onFormRedsocialsubmit()">Agrega dato de red social</button>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="form-row row"> 
                <div class="form-group col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="RedsocialTable" style="text-align:center">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Nombre de la Persona Asociado</th>
                                    <th scope="col" style="display:none;">Id Registro</th>
                                    <th scope="col" style="display:none;">Id Persona</th>
                                    <th scope="col">Nombre Usuario</th>
                                    <th scope="col">Enlace(url)</th>
                                    <th scope="col">Tipo de Enlace</th>
                                    <th scope="col">Observacion</th>
                                    <th scope="col">Foto</th>
                                    <th scope="col">Capturo</th>
                                    <th scope="col">Editar/Eliminar</th>
                                </tr>
                            </thead>
                            <tbody id="contarRedsocial">
                            </tbody>
                        </table>
                    </div><br>
                    <span class="span_error" id="Table_redsocial_error"></span>
                </div>
            </div>
        </div>
        <div class="modal fade" id="ModalCenterPrincipalredSocial" tabindex="-1" role="dialog" aria-labelledby="myModalExito" aria-hidden="true" >
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <img name="nor" src="<?= base_url; ?>/public/media/images/logo6.png"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
            </div>
        </div>
        <div class="row mt-5 mb-5">
            <div class="d-flex justify-content-end col-sm-12" id="id_p">
                <a class="btn btn-ssc  mr-3" href="<?= base_url; ?>Seguimientos"><i class="material-icons v-a-middle">arrow_back_ios</i>Volver al inicio</a>
                <a class="btn btn-ssc " id="btn_redSocial" value='1'>Guardar</a>
            </div>
        </div>
        
    </form>
</div>