<div class="container-fluid">
    <!--vista del evento  para la edicion de la tab de entrevistas  -->
    <form id="datos_Entrevistas">
        <div class="container">
            <div class="col-12 my-4" id="msg_principales_entrevistas"></div>
            <h5 class="titulo-azul">Datos de Entrevistas</h5>
            
            <div class="alert fondo-azul" role="event" style="display:none" id="alertEditEntrevista" >
                Se está realizando la edición de una entrevista.
            </div>
            <div class="row mt-3" >
                <div class="col-lg-3 col-sm-6 mi_hide">
                    <input  style="font-size: 15px;color: #0F2145; text-align:center;"type="text" id="id_entrevista" name="id_entrevista" value="SD" disabled="true">
                </div> 
                <div class="form-group col-lg-9"></div>    
                <div class="form-group col-lg-3">
                    <span class="span_rem">Captura: </span>
                    <input style="font-size: 15px;color: #0F2145; text-align:center;" type="input" name="captura_entrevistas" id="captura_entrevistas" class="form-control custom-input_dt" value="<?php echo strtoupper($_SESSION['userdataSIC']->User_Name)?>"  readOnly>
                </div>
            </div>
            <div class="form-row mt-3">
                <div class="form-group col-lg-7">
                    <label for="indicativo_entrevistador" class="label-form subtitulo-rosa">Indicativo del Entrevistador:</label><br>
                    <select class="custom-select custom-select-sm" id="indicativo_entrevistador" name="indicativo_entrevistador">
                        <option value="SD" selected>SELECCIONE UNA OPCION</option>
                    </select>
                    <span class="span_error" id="indicativo_entrevistador_error"></span>
                </div>
                <div class="form-group col-lg-5">
                    <div class="form-group col-lg-12 col-sm-6">
                        <div class="col-lg-12 ">
                            <span class="label-form subtitulo-rosa">Fecha/Hora de la Entrevista</span>
                        </div>
                        <div class="col-lg-12 d-flex justify-content-end" >
                            <span class="subtitulo-azul col-lg-5 col-sm-4">Ingrese Fecha: </span>
                            <input type="date" name="fecha_entrevista" id="fecha_entrevista" class="form-control custom-input_dt fecha parrafo-azul fondo-azul" value="<?php echo date('Y-m-d') ?>">
                        </div><br>
                        <div class="col-lg-12 d-flex justify-content-end">
                            <span class="subtitulo-azul col-lg-5 col-sm-5">Ingrese Hora:</span>
                            <input type="time" name="hora_entrevista" id="hora_entrevista" class="form-control custom-input_dt hora parrafo-azul fondo-azul col-sm-4" value="<?php echo date('H:i') ?>">
                        </div>
                    </div>
                </div>


                <div class="form-group col-lg-8">
                    <label for="alias_referidos" class="label-form subtitulo-rosa">Alias Referidos:</label>
                    <textarea name="alias_referidos" id="alias_referidos" cols="45" rows="3" class="form-control form-control-sm text-uppercase"placeholder="Ingrese los alias referidos en la entrevista separados por ','" onkeypress="return valideMultiplesDatos(event);"></textarea>
                    <span class="span_error" id="alias_referidos_error"> </span>
                </div>
                <div class="form-group col-lg-4">
                    <label for="relevancia" class="label-form subtitulo-rosa">Relevancia:</label><br>
                    <select class="custom-select custom-select-sm" id="relevancia" name="relevancia">
                        <option value="SD" selected>SELECCIONE UNA OPCION</option>
                        <option value="ALTA">ALTA</option>
                        <option value="MEDIA">MEDIA</option>
                        <option value="BAJA">BAJA</option>
                    </select>
                    <span class="span_error" id="relevancia_error"></span>
                </div>
                <div class="form-group col-lg-10">
                    <label for="entrevista" class="label-form subtitulo-rosa">Entrevista:</label>
                    <textarea class="form-control form-control-sm text-uppercase" id="entrevista"  name="entrevista" rows="10" maxlength="10000" placeholder="Ingrese la entrevista"></textarea>
                    <span class="span_error" id="entrevista_error"></span>
                </div>
                <div class=" form-group col-lg-2 d-flex align-items-end">
                    <button type="button" class="btn btn-add button-movil-plus" onclick="onFormEntrevistasSubmit()">Agregar Entrevista</button>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="form-row row"> 
                <div class="form-group col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="entrevistasTable" style="text-align:center">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Id Entrevista</th>
                                    <th scope="col" style="display:none;">Id Persona Entrevistada</th>
                                    <th scope="col">Indicativo del Entrevistador</th>
                                    <th scope="col">Alias Referidos</th>
                                    <th scope="col">Relevancia</th>
                                    <th scope="col">Entrevista</th>
                                    <th scope="col">Fecha de Entrevista</th>
                                    <th scope="col">Hora de Entrevista</th>
                                    <th scope="col">Foto</th>
                                    <th scope="col">Capturo</th>
                                    <th scope="col">Editar/Eliminar</th>
                                </tr>
                            </thead>
                            <tbody id="tablaEntrevistasCount">
                            </tbody>
                        </table>
                    </div><br>
                    <span class="span_error" id="tablaEntrevistas_error"></span>
                </div>
            </div>
        </div>


        <div class="modal fade" id="ModalCenterEntrevista" tabindex="-1" role="dialog" aria-labelledby="myModalExito" aria-hidden="true" >
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <img name="nor" src="<?= base_url; ?>/public/media/images/logo6.png"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
            </div>
        </div>
        <div class="row mt-5 mb-5">
            <div class="d-flex justify-content-end col-sm-12" id="id_p">
                <a class="btn btn-ssc  mr-3" href="<?= base_url; ?>Entrevistas"><i class="material-icons v-a-middle">arrow_back_ios</i>Volver al inicio</a>
                <a class="btn btn-sm btn-ssc" id="btn_principalEntrevistas" value='1'>Guardar entrevista</a>
            </div>
        </div>

    </form>
</div>