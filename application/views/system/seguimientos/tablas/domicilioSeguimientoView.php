<div class="container-fluid">
    <!--vista para generar datos de domicilios en el seguimiento -->
    <form id='datos_domicilios' onsubmit="event.preventDefault()">
        <div class="container">
            <!--PARA MOSTRAR SI SE CARGARON LOS DATOS O UBO UN ERROR-->
            <div class="col-12 my-4" id="msg_principales_domicilios"></div>
            <h5 class="titulo-azul">Domicilios Identificados Asociados</h5>
            <div class="alert fondo-azul" role="event" style="display:none" id="alertaEditDomicilio">
                Se está realizando la edición a un Domicilio.
            </div>
            <div class="row mt-2 mi_hide">
                <div class="col-lg-6 col-sm-6">
                    <h5 class="subtitulo-rosa">Id de Seguimiento Asignado:</h5>
                    <input type="text" name="id_seguimiento_domicilio" id="id_seguimiento_domicilio" class="form-control custom-input_dt">
                    <span class="span_error" id="id_seguimiento_domicilio_error"></span> 
                </div>
                <div class="col-lg-6 col-sm-6 ">
                    <h5  class="subtitulo-rosa">Elemento que Captura dato de persona: </h5>
                    <input  style="font-size: 15px;color: #0F2145;"type="text" name="captura_dato_domicilio" id="captura_dato_domicilio" value="<?php echo $_SESSION['userdataSIC']->User_Name?>" class="form-control custom-input_dt text-uppercase" disabled="true">
                    <span class="span_error" id="captura_dato_domicilio_error"></span>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-6">
                    <h5  class="subtitulo-rosa">¿Tipo de dato al cual se le asociara el domicilio?</h5>
                </div>
                <div class="col-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_dato" id="id_tipo_1" checked value="PERSONA" onchange="return changeTipo(event)" >
                        <label class="form-check-label" for="id_tipo_1">Persona</label>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_dato" id="id_tipo_2"  value="VEHICULO" onchange="return changeTipo(event)" >
                        <label class="form-check-label" for="id_tipo_2">Vehiculo</label>
                    </div>
                </div>
                <div class="col-12 text-center">
                    <small id="error_tipo_dato" class="form-text text-danger"></small>
                </div>
            </div>
            <div class="row mt-3">
                <div class="form-group col-lg-6"  >
                    <select class="custom-select custom-select-sm" id="Status_Domicilio">
                        <option value="SD">SELECCIONA UN STATUS DEL DOMICILIO</option>
                        <option value="PRESUNTO">PRESUNTO</option>
                        <option value="CONFIRMADO">CONFIRMADO</option>
                    </select>
                    <span class="span_error" id="Status_Domicilio_error"></span>
                </div>
                <div class="form-group col-lg-6"  id="Persona_Select">
                    <select class="custom-select custom-select-sm" id="PersonaSelect">
                    </select>
                    <span class="span_error" id="PersonaSelect_error"></span>
                </div>
                <div class="form-group col-lg-6 mi_hide" id="Vehiculo_Select">
                    <select class="custom-select custom-select-sm" id="VehiculoSelect">
                    </select>
                    <span class="span_error" id="VehiculoSelect_error"></span>
                </div>
            </div>
            <hr style="height:5px;border:none;color:#29295f;background-color:#29295f;"\>
            <p class="label-form ml-2 parrafo-azul"> Ubicacion del domicilio en: </p>   
            <div class="form-group col-lg-12">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="puebla_domicilio" data-id="peticionario" name="ubicacion_puebla_domicilio" class="custom-control-input" value="PUEBLA" checked>
                    <label class="custom-control-label label-form parrafo-azul" for="puebla_domicilio">Puebla</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="foraneo_domicilio" data-id="peticionario" name="ubicacion_puebla_domicilio" class="custom-control-input" value="FORANEO">
                    <label class="custom-control-label label-form parrafo-azul" for="foraneo_domicilio">Foraneo</label>
                </div>
            </div>
            <div class="row mt-3 mi_hide" id="Es_Foraneo">
                <div class="form-group col-lg-6">
                    <label for="Estado" class="label-form parrafo-azul">Estado:</label>
                    <select class="custom-select custom-select-sm" id="Estado" name="Estado">
                    <option value="SD" selected disabled>SELECCIONE UNA OPCIÓN</option>
                        <?php foreach ($data['datos_prim']['estados'] as $item) : ?>
                            <option value="<?php echo $item->Estado ?>"><?php echo $item->Estado ?></option>
                        <?php endforeach ?>
                    </select>
                    <span class="span_error" id="Estado_error"></span>
                </div>
                <div class="form-group col-lg-6">
                    <label for="Municipio" class="label-form parrafo-azul">Municipio:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" id="Municipio" name="Municipio" >
                    <span class="span_error" id="Municipio_error"></span>
                </div>
            </div>

            <div class="row mt-3">
                <div class="form-group col-lg-1 mi_hide" >
                    <input type="text" class="form-control form-control-sm " id="Id_Domicilio" name="Id_Domicilio" value="SD" disabled>
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
                <div class="form-group col-lg-9">
                        <label for="Observacion_Ubicacion" class="subtitulo-rosa">Observacion del Domicilio:</label>
                        <textarea name="Observacion_Ubicacion_descripcion" id="Observacion_Ubicacion_descripcion" cols="45" rows="7" class="form-control form-control-sm text-uppercase"placeholder="Ingrese Observacion del domicilio"onkeypress="return valideMultiples(event);"></textarea>
                        
                        <span class="span_error" id="Observacion_Ubicacion_error"></span>
                </div>

                <div class="form-group col-lg-3" align="right">
                    <br>
                    <button type="button" class="btn btn-add button-movil-plus" onclick="onFormdomiciliosubmit()">Agrega Domicilio</button>
                </div>

            </div>

        </div>

    
        <div class="container-fluid">
            <div class="form-row row"> 
                <div class="form-group col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="DomiciliosTable" style="text-align:center">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Nombre del Dato Asociado</th>
                                    <th scope="col" style="display:none;">Id Domicilio</th>
                                    <th scope="col" style="display:none;">Id Dato</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Status</th>
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
                                    <th scope="col">Observaciones</th>
                                    <th scope="col">Capturo</th>
                                    <th scope="col">Editar/Eliminar</th>
                                </tr>
                            </thead>
                            <tbody id="contarDomicilios">
                            </tbody>
                        </table>
                    </div><br>
                    <span class="span_error" id="tabla_domicilios_error"></span>
                </div>
            </div>
        </div>
        <div class="modal fade" id="ModalCenterPrincipalDomicilio" tabindex="-1" role="dialog" aria-labelledby="myModalExito" aria-hidden="true" >
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <img name="nor" src="<?= base_url; ?>/public/media/images/logo6.png"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
            </div>
        </div>
        <div class="row mt-5 mb-5">
            <div class="d-flex justify-content-end col-sm-12" id="id_p">
                <a class="btn btn-ssc  mr-3" href="<?= base_url; ?>Seguimientos"><i class="material-icons v-a-middle">arrow_back_ios</i>Volver al inicio</a>
                <a class="btn btn-ssc " id="btn_domicilios" value='1'>Guardar</a>
            </div>
        </div>
    </form>
</div>