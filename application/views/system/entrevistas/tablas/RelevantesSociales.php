<div class="container-fluid">
    <!--vista para redes de detenido  -->
    <form id='datos_Redsocial' onsubmit="event.preventDefault()">
        <div class="container">
            <!--PARA MOSTRAR SI SE CARGARON LOS DATOS O UBO UN ERROR-->
            <div class="col-12 my-4" id="msg_principales_Redsocial"></div>
            <h5 class="titulo-azul">Datos de Redes Sociales Personas Asociadas</h5>
            <div class="alert fondo-azul" role="event" style="display:none" id="alertaEditRedsocial">
                Se está realizando la edición a un registro de Red social.
            </div>
            <div class="row">
                <div class="form-group col-lg-9">
                    <div class="row mt-3">
                        <div class="col-6">
                            <h5  class="subtitulo-rosa">¿Tipo de dato al cual se le asociara la red social?</h5>
                        </div>
                        <div class="col-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_dato_red_social" id="id_tipo_1" value="ENTREVISTA" onchange="return changeTipoRedSocial(event)" >
                                <label class="form-check-label" for="id_tipo_1">Entrevista</label>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_dato_red_social" id="id_tipo_2"  value="DATO" onchange="return changeTipoRedSocial(event)" >
                                <label class="form-check-label" for="id_tipo_2">Dato</label>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_dato_red_social" id="id_tipo_3" checked  value="SD" onchange="return changeTipoRedSocial(event)" >
                                <label class="form-check-label" for="id_tipo_3">Ninguno</label>
                            </div>
                        </div>
                    </div>
                    <h5  class="subtitulo-rosa">Selecciona el id  a la que se asocia la red social:</h5>
                    <select class="custom-select custom-select-sm" id="RedSocialSelect" disabled>
                    </select>
                </div>
                <div class="form-group col-lg-3">
                    <span class="span_rem">Captura: </span>
                    <input style="font-size: 15px;color: #0F2145; text-align:center;" type="input" name="captura_dato_Redsocial" id="captura_dato_Redsocial" class="form-control custom-input_dt" value="<?php echo strtoupper($_SESSION['userdataSIC']->User_Name)?>"  readOnly>
                </div>
            </div>
            
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
                                    
                                    <th scope="col">Id al que esta Relacionado</th>
                                    <th scope="col">Tipo de Relacion</th>
                                    <th scope="col">Nombre Usuario</th>
                                    <th scope="col">Enlace(url)</th>
                                    <th scope="col">Tipo de Enlace</th>
                                    <th scope="col">Observacion</th>
                                    <th scope="col">Foto</th>
                                    <th scope="col">Id Registro</th>
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
                <a class="btn btn-ssc  mr-3" href="<?= base_url; ?>Entrevistas"><i class="material-icons v-a-middle">arrow_back_ios</i>Volver al inicio</a>
                <a class="btn btn-ssc " id="btn_redSocial_entrevistas" value='1'>Guardar</a>
            </div>
        </div>
        
    </form>
</div>