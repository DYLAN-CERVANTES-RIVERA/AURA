<div class="container-fluid">
    <!--vista del evento  para la edicion de la tab de entrevistas  -->
    <form id="datos_Entrevistas">
        <div class="container">
            <div class="col-lg-12 mt-2" id="msg_principales_entrevistas"></div>
            <h5 class="titulo-azul">Datos de Entrevistas</h5>
            
            <div class="alert fondo-azul" role="event" style="display:none" id="alertEditEntrevista" >
                Se está realizando la edición de un hecho.
            </div>
            
            <div class="form-row mt-3">
                <div class="form-group col-lg-4">
                    <label for="procedencia" class="label-form subtitulo-rosa">Tipo de procedencia</label><br>
                    <select class="custom-select custom-select-sm" id="procedencia" name="procedencia">
                        <option value="NA" selected>SELECCIONE PROCEDENCIA DE INFORMACIÓN</option>
                        <option value="ENTREVISTA PRESENCIAL">ENTREVISTA PRESENCIAL</option>
                        <option value="LLAMADA TELEFONICA">LLAMADA TELEFONICA</option>
                        <option value="WHATSAPP">WHATSAPP</option>
                    </select>
                    <span class="span_error" id="procedencia_error"></span>
                </div>
                <div class="form-group col-lg-3">
                    <label for="clave_entrevistador" class="label-form subtitulo-rosa">Clave del entrevistador</label><br>
                    <select class="custom-select custom-select-sm" id="clave_entrevistador" name="clave_entrevistador">
                        <option value="NA" selected>SELECCIONE UNA OPCION</option>
                        <?php foreach ($data['datos_prim']['claves'] as $item) : ?>
                                <option value="<?php echo $item->clave; ?>"><?php echo $item->clave; ?></option>
                        <?php endforeach ?>
                    </select>
                    <span class="span_error" id="clave_entrevistador_error"></span>
                </div>
                <div class="form-group col-lg-5">
                    <label for="nombre_Entrevistado" class="label-form subtitulo-rosa">Nombre del Entrevistado</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" id="nombre_Entrevistado" name="nombre_Entrevistado" placeholder="Ingrese el nombre de la persona que se entrevisto">
                    <span class="span_error" id="nombre_Entrevistado_error"></span>
                </div>
                <div class="form-group col-lg-8">
                    <label for="entrevista" class="label-form subtitulo-rosa">Coloque la entrevista</label>
                    <textarea class="form-control form-control-sm text-uppercase" id="entrevista"  name="entrevista" rows="6" maxlength="5000" placeholder="Ingrese la entrevista"></textarea>
                    <span class="span_error" id="entrevista_error"></span>
                </div>
                <div class="form-group col-lg-4">
                    <div class="form-group col-lg-12 col-sm-6">
                        <div class="col-lg-12 ">
                            <span class="label-form subtitulo-rosa">Fecha/Hora de la Entrevista</span>
                        </div>
                        <div class="col-lg-12 d-flex justify-content-end" >
                            <span class="subtitulo-azul col-lg-6 col-sm-6">Ingrese Fecha: </span>
                            <input type="date" name="fecha_entrevista" id="fecha_entrevista" class="form-control custom-input_dt fecha parrafo-azul fondo-azul" value="<?php echo date('Y-m-d') ?>">
                        </div><br>
                        <div class="col-lg-12 d-flex justify-content-end">
                            <span class="subtitulo-azul col-lg-5 col-sm-5">Ingrese Hora:</span>
                            <input type="time" name="hora_entrevista" id="hora_entrevista" class="form-control custom-input_dt hora parrafo-azul fondo-azul col-sm-4" value="<?php echo date('H:i') ?>">
                        </div>
                        <div class="row-lg-12">
                            <span class="span_error" id="fechahora_entrevista_error"> </span>
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-3" >
                    <label for="edad_Entrevistado" class="label-form subtitulo-rosa" >Edad del Entrevistado</label>
                    <input  type="text" minlength="1" maxlength="2" class="form-control form-control-sm" id="edad_Entrevistado" name="edad_Entrevistado" placeholder="Ingrese la edad">
                </div>
                <div class="col-lg-2" ></div>
                <div class="form-group col-lg-3 " >
                    <label for="Telefono_Entrevistado" class="label-form subtitulo-rosa">Telefono del Entrevistado</label>
                    <input type="text" minlength="1" maxlength="10"class="form-control form-control-sm" id="Telefono_Entrevistado" name="Telefono_Entrevistado" placeholder="Ingrese telefono">
                </div>
                <div class="col-lg-2" ></div>
                <div class=" form-group col-lg-2 d-flex align-items-end">
                    <button type="button" class="btn btn-add button-movil-plus" onclick="onFormEntrevistasSubmit()">Agregar Entrevista</button>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="form-row row"> 
                <div class="form-group col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="entrevistasTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Procedencia de Información</th>
                                    <th scope="col">Entrevista</th>
                                    <th scope="col">Nombre del Entrevistado</th>
                                    <th scope="col">Clave del Entrevistador</th>
                                    <th scope="col">Edad del Entrevistado</th>
                                    <th scope="col">Numero del Entrevistado</th>
                                    <th scope="col">Fecha de Entrevista</th>
                                    <th scope="col">Hora de Entrevista</th>
                                    <th scope="col">Capturo</th>
                                    <th scope="col">Editar/Eliminar</th>
                                </tr>
                            </thead>
                            <tbody id="countEntrevistas">
                            </tbody>
                        </table>
                    </div>
                    <span class="span_error" id="tablaEntrevistas_error"></span>
                </div>
            </div>
        </div>
        <div class="modal fade" id="ModalCenterEntrevistas" tabindex="-1" role="dialog" aria-labelledby="myModalExito" aria-hidden="true" >
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <img name="nor" src="<?= base_url; ?>/public/media/images/logo6.png"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
            </div>
        </div>

        <div class="row mt-5 mb-5">
            <div class="d-flex justify-content-start col-sm-6" id="id_seguimientofotos">
                <a class="btn btn-sm btn-ssc mr-3" href="<?= base_url; ?>GestorCasos/verResumenEvento/?Folio_infra=<?php $separadas=explode("=", $_SERVER["REQUEST_URI"]); $Folio_infra=$separadas[1];echo ($Folio_infra);?>">Ver Resumen</a>
                 <button type="button" class="btn btn-add" onclick="onFormSeguimientoTermSubmit()">Terminar Seguimiento de Evento</button>
            </div>
            <div class="d-flex justify-content-end col-sm-12" id="id_p">
                <a class="btn btn-sm btn-ssc mr-3" href="<?= base_url; ?>GestorCasos"><i class="material-icons v-a-middle">arrow_back_ios</i>Volver al inicio</a>
                <a class="btn btn-sm btn-ssc" id="btn_principalEntrevistas" value='1'>Guardar entrevista</a>
            </div>
        </div>

    </form>
</div>