<div class="container-fluid">
    <!--vista para generar datos de domicilios en el seguimiento -->
    <form id='datos_forencias_entrevistas' onsubmit="event.preventDefault()">
        <div class="container">
            <!--PARA MOSTRAR SI SE CARGARON LOS DATOS O UBO UN ERROR-->
            <div class="col-12 my-4" id="msg_principales_forencias"></div>
            <h5 class="titulo-azul">Datos</h5>
            <div class="alert fondo-azul" role="event" style="display:none" id="alertaEditforencias">
                Se está realizando la edición de un dato.
            </div>

            <div class="row mt-3">
                <div class="form-group col-lg-9" >
                    <div class="row mt-3">
                        <div class="col-6">
                            <h5  class="subtitulo-rosa">¿Tipo al que se relacionara el dato?</h5>
                        </div>
                        <div class="col-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_dato_dato" id="id_tipo_1" value="ENTREVISTA" onchange="return changeTipoDato(event)" >
                                <label class="form-check-label" for="id_tipo_1">Entrevista</label>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_dato_dato" id="id_tipo_2"  value="DATO" onchange="return changeTipoDato(event)" >
                                <label class="form-check-label" for="id_tipo_2">Dato</label>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_dato_dato" id="id_tipo_3" checked  value="SD" onchange="return changeTipoDato(event)" >
                                <label class="form-check-label" for="id_tipo_3">Ninguno</label>
                            </div>
                        </div>
                    </div>
                    <h5  class="subtitulo-rosa">Selecciona el id  a la que se asocia el dato:</h5>
                    <select class="custom-select custom-select-sm" id="DatoSelect">
                    </select>
                    <span class="span_error" id="select_forencia_error"></span>
                </div>
                <div class="form-group col-lg-3">
                    <span class="span_rem">Captura: </span>
                    <input style="font-size: 15px;color: #0F2145; text-align:center;" type="input" name="captura_dato_forencias" id="captura_dato_forencias" class="form-control custom-input_dt" value="<?php echo strtoupper($_SESSION['userdataSIC']->User_Name)?>"  readOnly>
                </div>
            </div>
            <div class="row mt-3">
                <div class="form-group col-lg-1 mi_hide" >
                    <input type="text" class="form-control form-control-sm " id="Id_Forencia" name="Id_Forencia" value="SD" disabled>
                </div>
                <div class="form-group col-lg-10">
                    <label for="forencia" class="subtitulo-rosa">Descripcion:</label>
                    <textarea name="forencia_descripcion" id="forencia_descripcion" cols="45" rows="7" class="form-control form-control-sm text-uppercase"placeholder="Ingrese el dato"></textarea>   
                    <span class="span_error" id="forencia_error"></span>
                </div>
                <div class="form-group col-lg-2 mt-3">
                    <br>
                    <button type="button" class="btn btn-add button-movil-plus" onclick="onFormForenciasubmit()">Guardar Dato</button>
                </div>

            </div>
            <div class="row mt-3">
                <div class="form-group col-lg-10 mi_hide">
                    <label for="dato_relevante" class="subtitulo-rosa">Dato Relevante</label>
                    <input type="text" class="form-control form-control-sm" id="dato_relevante" name="dato_relevante" disabled onkeypress="return EvaluaEntrada(event)">
                    <span class="span_error" id="dato_relevante_error"></span>
                </div>
                <div class="form-group col-lg-3 mt-3 mi_hide">
                    <label for="tipo_dato" class="subtitulo-rosa"> Tipo de Dato:</label>
                    <select class="custom-select custom-select-sm" id="tipo_dato">
                    </select>
                    <span class="span_error" id="tipo_dato_error"></span>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="form-row row"> 
                <div class="form-group col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="ForenciasTable" style="text-align:center">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Id Dato</th>
                                    <th scope="col">Id Dato al que esta Relacionado</th>
                                    <th scope="col">Tipo de Relacion</th>
                                    <th scope="col">Foto</th>
                                    <th scope="col">Descripcion de Dato</th>
                                    <th scope="col" style="display:none">Tipo de Dato</th>
                                    <th scope="col" style="display:none">Dato Relevante</th>
                                    <th scope="col">Capturo</th>
                                    <th scope="col">Editar/Eliminar</th>
                                </tr>
                            </thead>
                            <tbody id="contarforencias">
                            </tbody>
                        </table>
                    </div><br>
                    <span class="span_error" id="tabla_forencias_error"></span>
                </div>
            </div>
        </div>
        <div class="modal fade" id="ModalCenterPrincipalforencias" tabindex="-1" role="dialog" aria-labelledby="myModalExito" aria-hidden="true" >
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <img name="nor" src="<?= base_url; ?>/public/media/images/logo6.png"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
            </div>
        </div>
    </form>
    <!--vista para generar datos especificos -->
    <form id='datos_especificos' onsubmit="event.preventDefault()">
        <div class="container">
            <h5 class="titulo-azul">Datos Especificos Encontrados</h5>
            <div class="col-12 my-4" id="msg_datos"></div>
            <div class="container">
                <h5 class="subtitulo-rosa">Selecciona un id al que se le desea especificar datos:</h5>
                <select class="custom-select custom-select-sm" id="Dato_select_especifico">
                </select>
                <br>
                <span class="span_error" id="Dato_select_especifico_error"></span>
                <br>

                <select class="custom-select custom-select-sm" id="Tipo_Dato_Especifico">
                    <option value="0">SELECCIONE UNA OPCIÓN</option>
                    <option value="1">NUMERO DE TELEFONO</option>
                    <option value="2">CURP / RFC</option>
                    <option value="3">NUMERO DE TARJETA</option>
                    <option value="4">OTRO</option>
                    <option value="5">PLACA / NIV</option>
                    <option value="6">ZONA DE OPERACION</option>
                    <option value="7">BANDA ASOCIADA</option>
                    <option value="8">NOMBRE</option>
                    <option value="9">ALIAS</option>
                </select>
                <div id=panelTabla></div>
            </div>
        </div>
    </form>
    <div class="row mt-5 mb-5">
        <div class="d-flex justify-content-end col-sm-12" id="id_p">
            <a class="btn btn-ssc  mr-3" href="<?= base_url; ?>Entrevistas"><i class="material-icons v-a-middle">arrow_back_ios</i>Volver al inicio</a>
                <!--<a class="btn btn-ssc " id="btn_forencias_entrevistas" value='1'>Guardar</a>-->
        </div>
    </div>

</div>