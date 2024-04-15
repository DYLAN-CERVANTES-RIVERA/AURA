<div class="container-fluid">
    <!--vista para generar datos de ubicaciones de las entrevistas -->
    <form id='datos_ubicaciones' onsubmit="event.preventDefault()">
        <div class="container">
            <!--PARA MOSTRAR SI SE CARGARON LOS DATOS O UBO UN ERROR-->
            <div class="col-12 my-4" id="msg_principales_ubicaciones"></div>
            <h5 class="titulo-azul">Ubicaciones Asociados</h5>
            <div class="alert fondo-azul" role="event" style="display:none" id="alertaEditUbicacion">
                Se está realizando la edición a un Domicilio.
            </div>
            <div class="row">
                <div class="form-group col-lg-9">
                    <div class="row mt-3">
                        <div class="col-6">
                            <h5  class="subtitulo-rosa">¿Tipo de dato al cual se le asociara la Ubicacion?</h5>
                        </div>
                        <div class="col-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_dato_ubicacion" id="id_tipo_ubicacion1" value="ENTREVISTA" onchange="return changeTipoUbicacion(event)" >
                                <label class="form-check-label" for="id_tipo_ubicacion1">Entrevista</label>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_dato_ubicacion" id="id_tipo_ubicacion2"  value="DATO" onchange="return changeTipoUbicacion(event)" >
                                <label class="form-check-label" for="id_tipo_ubicacion2">Dato</label>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_dato_ubicacion" id="id_tipo_ubicacion3" checked  value="SD" onchange="return changeTipoUbicacion(event)" >
                                <label class="form-check-label" for="id_tipo_ubicacion3">Ninguno</label>
                            </div>
                        </div>
                    </div>
                    <h5  class="subtitulo-rosa">Selecciona el id a la que se asocia la ubicacion:</h5>
                    <select class="custom-select custom-select-sm" id="UbicacionSelect" disabled>
                    </select>
                </div>
                <div class="form-group col-lg-3">
                    <span class="span_rem">Captura: </span>
                    <input style="font-size: 15px;color: #0F2145; text-align:center;" type="input" name="captura_dato_ubicaciones" id="captura_dato_ubicaciones" class="form-control custom-input_dt" value="<?php echo strtoupper($_SESSION['userdataSIC']->User_Name)?>"  readOnly>
                </div>
            </div>
            <div class="row mt-3">
                <p class="label-form ml-2 parrafo-azul"> Ubicacion en: </p>   
                <div class="form-group col-lg-12">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="puebla_ubicacion" data-id="peticionario" name="ubicacion_puebla" class="custom-control-input" value="PUEBLA" checked>
                        <label class="custom-control-label label-form parrafo-azul" for="puebla_ubicacion">Puebla</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="foraneo_ubicacion" data-id="peticionario" name="ubicacion_puebla" class="custom-control-input" value="FORANEO">
                        <label class="custom-control-label label-form parrafo-azul" for="foraneo_ubicacion">Foraneo</label>
                    </div>
                </div>
                <div class="row mt-3 mi_hide" id="Es_Foraneo">
                    <div class="form-group col-lg-6">
                        <label for="Estado" class="label-form parrafo-azul">Estado:</label>
                        <select class="custom-select custom-select-sm" id="Estado" name="Estado">
                        <option value="SD" selected disabled>SELECCIONE UNA OPCIÓN</option>
                        </select>
                        <span class="span_error" id="Estado_error"></span>
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="Municipio" class="label-form parrafo-azul">Municipio:</label>
                        <input type="text" class="form-control form-control-sm text-uppercase" id="Municipio" name="Municipio" >
                        <span class="span_error" id="Municipio_error"></span>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="form-group col-lg-1 mi_hide" >
                    <input type="text" class="form-control form-control-sm " id="Id_Ubicacion" name="Id_Ubicacion" value="SD" disabled>
                </div>
                <div class="col-lg-6">
                    <div class="form-row mt-3">
                        <p class="label-form ml-2 parrafo-azul"> Buscar por: </p>

                        <div class="form-group col-lg-12">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="porDireccion_alertas" name="busqueda" class="custom-control-input" value="0">
                                <label class="custom-control-label label-form parrafo-azul" for="porDireccion_alertas">Dirección</label>
                            </div>

                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="porCoordenadas_alertas" name="busqueda" class="custom-control-input" value="1">
                                <label class="custom-control-label label-form parrafo-azul" for="porCoordenadas_alertas">Coordenadas</label>
                            </div>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="Colonia" class="label-form subtitulo-rosa">Colonia</label>
                            <input type="text" class="form-control form-control-sm text-uppercase" id="Colonia" name="Colonia">
                            <span class="span_error" id="Colonia_principales_error"></span>

                        </div>

                        <div class="form-group col-lg-6">
                            <label for="Calle" class="label-form subtitulo-rosa">Calle</label>
                            <input type="text" class="form-control form-control-sm text-uppercase" id="Calle" name="Calle">
                            <span class="span_error" id="Calle_principales_error"></span>

                        </div>

                        <div class="form-group col-lg-6">
                            <label for="Calle2" class="label-form subtitulo-rosa">Calle 2</label>
                            <input type="text" class="form-control form-control-sm" id="Calle2" name="Calle2">
                            <span class="span_error" id="Calle2_principales_error"></span>
                        </div>

                        <div class="form-group col-lg-3">
                            <label for="no_Ext" class="label-form subtitulo-rosa">No. Ext.</label>
                            <input type="text" class="form-control form-control-sm" id="no_Ext" name="no_Ext">
                            <span class="span_error" id="NoExt_principales_error"></span>
                        </div>

                        <div class="form-group col-lg-3">
                            <label for="no_Int" class="label-form subtitulo-rosa">No. Int.</label>
                            <input type="text" class="form-control form-control-sm" id="no_Int" name="no_Int">
                            <span class="span_error" id="NoExt_principales_error"></span>
                        </div>

                        <div class="form-group col-lg-3">
                            <label for="CP" class="label-form subtitulo-rosa">CP</label>
                            <input type="text" class="form-control form-control-sm" id="CP" name="CP">
                            <span class="span_error" id="CP_principales_error"></span>
                        </div>


                        <div class="form-group col-lg-4">
                            <label for="cordY" class="label-form subtitulo-rosa">Coordenada Y</label>
                            <input type="text" class="form-control form-control-sm" id="cordY" name="cordY" >
                            <span class="span_error" id="cordY_principales_error"></span>
                        </div>

                        <div class="form-group col-lg-4">
                            <label for="cordX" class="label-form subtitulo-rosa">Coordenada X</label>
                            <input type="text" class="form-control form-control-sm" id="cordX" name="cordX" >
                            <span class="span_error" id="cordX_principales_error"></span>
                        </div>
                        <div class="form-group col-12 col-lg-3">
                            <button type="button" class="btn btn-ssc mt-3 mi_hide" id="buscar_coordenadas_ins">Buscar</button>
                            <button type="button" class="btn btn-ssc mt-3 mi_hide" id="buscar_direccion_ins">Buscar</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" id="map_mapbox"></div>
            </div>
            <div class="form-row mt-3">
                <div class="form-group col-lg-12">
                    <label for="Observacion_Ubicacion" class="subtitulo-rosa">Observacion de la Ubicacion:</label>
                    <textarea name="Observacion_Ubicacion_descripcion" id="Observacion_Ubicacion_descripcion" cols="45" rows="7" class="form-control form-control-sm text-uppercase"placeholder="Ingrese Observacion de la Ubicacion"onkeypress="return valideMultiples(event);"></textarea>  
                    <span class="span_error" id="Observacion_Ubicacion_error"></span>
                </div>
                <div class="form-group col-lg-12 col-sm-6">
                    <label for="Link_Ubicacion" class="subtitulo-rosa">Link de Ubicacion:</label>
                    <input type="text" class="form-control form-control-sm" maxlength="100"id="Link_Ubicacion" name="Link_Ubicacion" placeholder="INGRESE EL LINK DE LA UBICACION"onkeypress="return valida(event);" >
                    <span class="span_error" id="Link_Ubicacion_error"></span>
                </div>
                <div class="form-group col-lg-12" align="right">
                    <button type="button" class="btn btn-add button-movil-plus" onclick="onFormUbicacionsubmit()">Agrega Ubicacion</button>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="form-row row"> 
                <div class="form-group col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="UbicacionTable" style="text-align:center">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Id Ubicacion</th>
                                    <th scope="col">Id Relacionado</th>
                                    <th scope="col">Colonia</th>
                                    <th scope="col">Calle</th>
                                    <th scope="col">Calle2</th>
                                    <th scope="col">No.Ext</th>
                                    <th scope="col">No.Int</th>
                                    <th scope="col">Cp</th>
                                    <th scope="col">CoordY</th>
                                    <th scope="col">CoordX</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Municipio</th>
                                    <th scope="col">Foraneo</th>
                                    <th scope="col">Observaciones de Ubicacion</th>
                                    <th scope="col">Link</th>
                                    <th scope="col">Foto Ubicacion</th>
                                    <th scope="col">Capturo</th>
                                    <th scope="col">Editar/Eliminar</th>
                                    <th scope="col">Tipo</th>
                                </tr>
                            </thead>
                            <tbody id="contarUbicacion">
                            </tbody>
                        </table>
                    </div><br>
                    <span class="span_error" id="tabla_Ubicacion_error"></span>
                </div>
            </div>
        </div>
        <div class="modal fade" id="ModalCenterPrincipalUbicacion" tabindex="-1" role="dialog" aria-labelledby="myModalExito" aria-hidden="true" >
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <img name="nor" src="<?= base_url; ?>/public/media/images/logo6.png"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
            </div>
        </div>
        <div class="row mt-5 mb-5">
            <div class="d-flex justify-content-end col-sm-12" id="id_p">
                <a class="btn btn-ssc  mr-3" href="<?= base_url; ?>Entrevistas"><i class="material-icons v-a-middle">arrow_back_ios</i>Volver al inicio</a>
                <a class="btn btn-ssc " id="btn_Ubicacion_Entrevistas" value='1'>Guardar Ubicaciones</a>
            </div>
        </div>
    </form>
</div>