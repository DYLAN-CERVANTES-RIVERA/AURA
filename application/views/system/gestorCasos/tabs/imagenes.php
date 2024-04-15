<div class="container-fluid">
    <!--vista del evento  para la edicion de la tab de fotos y videos  -->
    <form id='datos_fotos' onsubmit="event.preventDefault()">
        <div class="container">
            <div class="col-lg-12 my-4" id="msg_fotos"></div>
            <h5 class="titulo-azul">Datos de Imagenes</h5>
            <div class="alert fondo-azul" role="event" style="display:none" id="alertEditFoto">
                Se está realizando la edición de una foto.
            </div>
            
            <div class="row mt-3">
                <div class="form-group col-lg-12">
                    <label for="id_puerta" class="label-form subtitulo-rosa">CATALOGO DE CAMARAS</label>
                    <input type="text" class="form-control form-control-sm " placeholder="Buscar"  id="id_puerta" name="id_puerta">
                </div>
                <div class="form-group col-lg-6">
                    <label for="id_ubicacion" class="label-form subtitulo-rosa">SELECCIONE UN ID DE UBICACION</label>
                    <select class="custom-select custom-select-sm" id="id_ubicacion" name="id_ubicacion">
                        <option value="NA">SELCCIONE UN ID UBICACION</option>    
                        <option value="1">UBICACION 1</option>
                        <option value="2">UBICACION 2</option>
                        <option value="3">UBICACION 3</option>
                        <option value="4">UBICACION 4</option>
                        <option value="5">UBICACION 5</option>
                        <option value="6">UBICACION 6</option>
                        <option value="7">UBICACION 7</option>
                        <option value="8">UBICACION 8</option>
                        <option value="9">UBICACION 9</option>
                        <option value="10">UBICACION 10</option>
                    </select>
                    <span class="span_error" id="id_ubicacion_error"></span>
                </div>
                <div class="form-group col-lg-6">
                    <label for="id_camara" class="label-form subtitulo-rosa">SELECCIONE UN ID DE CAMARA</label>
                    <select class="custom-select custom-select-sm" id="id_camara" name="id_camara">
                        <option value="NA">SELCCIONE UN ID CAMARA</option>    
                        <option value="1">CAMARA 1</option>
                        <option value="2">CAMARA 2</option>
                        <option value="3">CAMARA 3</option>
                        <option value="4">CAMARA 4</option>
                        <option value="5">CAMARA 5</option>
                        <option value="6">CAMARA 6</option>
                        <option value="7">CAMARA 7</option>
                        <option value="8">CAMARA 8</option>
                        <option value="9">CAMARA 9</option>
                        <option value="10">CAMARA 10</option>
                    </select>
                    <span class="span_error" id="id_camara_error"></span>
                </div>
            </div>
            <h5 class="titulo-azul">Ubicacion de la Imagen</h5>
            <div class="row mt-3">
                <div class="col-lg-6">
                    <div class="form-row mt-3">
                        <p class="label-form ml-2 parrafo-azul"> Buscar por: </p>

                        <div class="form-group col-lg-12">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="porDireccion_fotos" name="busqueda" class="custom-control-input" value="0">
                                <label class="custom-control-label label-form parrafo-azul" for="porDireccion_fotos">Dirección</label>
                            </div>

                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="porCoordenadas_fotos" name="busqueda" class="custom-control-input" value="1">
                                <label class="custom-control-label label-form parrafo-azul" for="porCoordenadas_fotos">Coordenadas</label>
                            </div>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="ColoniaF" class="label-form subtitulo-rosa">Colonia</label>
                            <input type="text" class="form-control form-control-sm text-uppercase" id="ColoniaF" name="ColoniaF"  placeholder = "Ingrese la Colonia Camara" onkeypress="return valideMultiples(event);">
                            <span class="span_error" id="Colonia_fotos_error"></span>

                        </div>

                        <div class="form-group col-lg-6">
                            <label for="CalleF" class="label-form subtitulo-rosa">Calle</label>
                            <input type="text" class="form-control form-control-sm text-uppercase" id="CalleF" name="CalleF" placeholder="Ingrese la Calle 1 Camara" onkeypress="return valideMultiples(event);">
                            <span class="span_error" id="Calle_fotos_error"></span>

                        </div>

                        <div class="form-group col-lg-6">
                            <label for="Calle2F" class="label-form subtitulo-rosa">Calle 2</label>
                            <input type="text" class="form-control form-control-sm text-uppercase" id="Calle2F" name="Calle2F" placeholder="Ingrese la Calle 2 Camara" onkeypress="return valideMultiples(event);">
                            <span class="span_error" id="Calle2_fotos_error"></span>
                        </div>

                        <div class="form-group col-lg-3">
                            <label for="no_ExtF" class="label-form subtitulo-rosa">No. Ext.</label>
                            <input type="text" class="form-control form-control-sm" id="no_ExtF" name="no_ExtF" placeholder="Ingrese No Ext" onkeypress="return valideMultiples(event);">
                            <span class="span_error" id="NoExt_fotos_error"></span>
                        </div>

                        <div class="form-group col-lg-3">
                            <label for="CPF" class="label-form subtitulo-rosa">CP</label>
                            <input type="text" class="form-control form-control-sm" id="CPF" name="CPF" placeholder="Ingrese Codigo Postal">
                            <span class="span_error" id="CP_fotos_error"></span>
                        </div>


                        <div class="form-group col-lg-4">
                            <label for="cordYF" class="label-form subtitulo-rosa">Coordenada Y</label>
                            <input type="text" class="form-control form-control-sm" placeholder="Ingrese Cooordena +Y" id="cordYF" name="cordYF" >
                            <span class="span_error" id="cordY_fotos_error"></span>
                        </div>

                        <div class="form-group col-lg-4">
                            <label for="cordXF" class="label-form subtitulo-rosa">Coordenada X</label>
                            <input type="text" class="form-control form-control-sm" placeholder="Ingrese Cooordena -X" id="cordXF" name="cordXF" >
                            <span class="span_error" id="cordX_fotos_error"></span>
                        </div>
                        <div class="form-group col-12 col-lg-3">
                            <button type="button" class="btn btn-ssc mt-3 mi_hide" id="buscar_coordenadas_fotos">Buscar</button>
                            <button type="button" class="btn btn-ssc mt-3 mi_hide" id="buscar_direccion_fotos">Buscar</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" id="map_mapbox2"></div>
            </div>

            <div class="row mt-3">
                <div class="form-group col-lg-8">
                    <label for="descripcionFoto" class="label-form subtitulo-rosa">Describa la Imagen:</label>
                    <textarea class="form-control form-control-sm text-uppercase" id="descripcionFoto"  name="descripcionFoto" rows="5" placeholder="Ingrese Descripcion de la Imagen"></textarea>

                    <span class="span_error" id="descripcionFoto_error"></span>
                </div>
                <div class="form-group col-lg-4 col-sm-8">
                <span class="label-form subtitulo-rosa">Fecha y Hora de la Imagen Capturada </span>
                    <div class="row">
                        <span class="subtitulo-azul col-lg-5 col-sm-4">Ingrese Fecha: </span>
                        <input type="date" name="fecha_captura_foto" id="fecha_captura_foto" class="form-control custom-input_dt fecha parrafo-azul fondo-azul" value="<?php echo date('Y-m-d') ?>">
                    </div><br>    
                    <div class="row">
                        <span class="subtitulo-azul col-lg-5 col-sm-5">Ingrese Hora:</span>
                        <input type="time" name="hora_captura_foto" id="hora_captura_foto" class="form-control custom-input_dt hora parrafo-azul fondo-azul col-sm-4" value="<?php echo date('H:i') ?>">
                    </div>
                    <span class="span_error" id="fechahora_captura_foto_error"> </span>
                </div>
                <div class="col-lg-10 col-sm-6"></div>
                <button type="button" class="btn btn-add" onclick="onFormFotosSubmit()">Agregar la Imagen </button>
            </div><br>
        </div>
        <div class="container-fluid">
            <div class="form-row row"> 
                <div class="form-group col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="fotosTable" style="text-align:center">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Ubicacion Id</th>
                                    <th scope="col">Camara Id</th>
                                    <th scope="col">Imagen</th>
                                    <th scope="col">Descripción de Imagen</th>
                                    <th scope="col">Colonia</th>
                                    <th scope="col">Calle</th>
                                    <th scope="col">Calle2</th>
                                    <th scope="col">NoExt</th>
                                    <th scope="col">CP</th>
                                    <th scope="col">Coordenada Y</th>
                                    <th scope="col">Coordenada X</th>
                                    <th scope="col">Fecha de Captura</th>
                                    <th scope="col">Hora de Captura</th>
                                    <th scope="col">Fecha y Hora de Captura en Sistema</th>
                                    <th scope="col">Capturo</th>
                                    <th scope="col">Editar/Eliminar</th>
                                </tr>
                            </thead>
                            <tbody class="smallfont" id="contarImagenes">
                            </tbody>
                        </table>
                    </div>
                    <span class="span_error" id="tablaEntrevistas_error"></span>
                </div>
            </div>
        </div>
        <div class="modal fade" id="ModalCenterFotosVideos" tabindex="-1" role="dialog" aria-labelledby="myModalExito" aria-hidden="true" >
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <img name="nor" src="<?= base_url; ?>/public/media/images/logo6.png"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
            </div>
        </div>
        
        <div class="row mt-5 mb-5">

            <div class="d-flex justify-content-end col-sm-6 col-lg-12" id="id_p">
                <a class="btn btn-sm btn-ssc mr-3" href="<?= base_url; ?>GestorCasos"><i class="material-icons v-a-middle">arrow_back_ios</i>Volver al inicio</a>
                <a class="btn btn-sm btn-ssc" id="btn_principalFotos" value='1'>Guardar Imagenes y Fotos</a>
            </div>
        </div>
        <div class="row mi_hide" id="form_contenedor2">
            <input type="text" name="actualizaFotos" id="actualizaFotos" class="form-control custom-input_dt"  value="<?php echo $_SESSION['userdataSIC']->User_Name?>" readOnly>
        </div>
    </form>
    
</div>