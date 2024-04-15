<div class= "content">
    <div class="cabecera_modulo" ><br></div>
    <div class="container">
        <!--vista para las tabs para edicion de los eventos  -->
        <?php if(!isset($data['titulo_1'])){ ?>
            <div class="paragraph-title d-flex justify-content-between mt-2 mb-1">
                <h5> <a href="<?= base_url; ?>Seguimientos">REDES DE VINCULOS</a> <span>/ NUEVO</span></h5>
            </div>
        <?php }else{ ?>
            <div class="paragraph-title d-flex justify-content-between mt-2 mb-1">
                <h5> <a href="<?= base_url; ?>Seguimientos">REDES DE VINCULOS</a> <span>/ <?=$data['titulo_1']?></span></h5>
            </div>
        <?php } ?>
        <hr>
    </div>
    <div class="container">
        <!--vista para generar un nuevo evento -->
        <form id='datos_principales' onsubmit="event.preventDefault()">
            <!--PARA MOSTRAR SI SE CARGARON LOS DATOS O UBO UN ERROR-->
            <div class="col-12 my-4" id="msg_principales"></div>
            
            <div class="row mt-3">
                <div class="col-lg-7 col-sm-6 d-flex">
                    <h5  class="subtitulo-rosa">Elemento que Captura: </h5>
                    <input  style="font-size: 15px;color: #0F2145;"type="text" name="captura_principales" id="captura_principales" value="<?php echo strtoupper($_SESSION['userdataSIC']->User_Name)?>" class="form-control custom-input_dt">
                    <span class="span_error" id="captura_principales_error"></span>
                </div>
                <div class="col-lg-5 col-sm-6 d-flex">             
                    <h5 class="subtitulo-rosa">Fecha/Hora de Captura</h5>
                    <div class="col-lg-10 col-sm-6 d-inline-flex align-items-center">
                        <span class="span_error" id="fechaP_error"></span>
                        <input type="text" name="fechahora_captura_principales" id="fechahora_captura_principales" class="form-control custom-input_dt">
                    </div>
                </div>
            </div><br>

            <h5 class="titulo-azul" >Datos del Seguimiento</h5>
            <div class="row mt-3">
                <div class="form-group col-lg-8 col-sm-6">
                    <label for="nombre_grupo" class="subtitulo-rosa">Nombre del Grupo Delictivo:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" id="nombre_grupo" name="nombre_grupo" placeholder="Ingrese nombre del grupo delictivo" onkeypress="return validaConAcentos(event);" >
                    <span class="span_error" id="nombre_grupo_error"></span>
                </div>
                <div class="form-group col-lg-3">
                    <label for="peligrosidad" class="subtitulo-rosa">Peligrosidad:</label>
                    <select class="custom-select custom-select-sm" id="peligrosidad" name="peligrosidad">
                        <option value="SD" selected>SELECCIONA UNA OPCION</option>
                        <option value="BAJA" >BAJA</option>
                        <option value="MEDIA">MEDIA</option>
                        <option value="ALTA">ALTA</option>
                    </select>
                    <span class="span_error" id="peligrosidad_error"></span>
                </div>
                <div class="form-group col-lg-9">
                    <label for="principal_actividad" class="subtitulo-rosa">Principal Actividad Delictiva:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" id="principal_actividad" name="principal_actividad" placeholder="Ingrese el principal delito">
                    <span class="span_error" id="principal_actividad_error"></span>
                </div>
                <div class="form-group col-lg-3">
                    <br>
                    <button type="button" class="btn btn-add button-movil-plus" onclick="onFormDelitoSubmit()">Agregar Delito</button>
                </div>
            </div>
            <div class="table-responsive ">
                    <table class="table table-bordered" id="DelitosTable">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Delitos Principales</th>
                                <th scope="col">Eliminar</th>
                            </tr>
                        </thead>
                        <tbody id="contardelitos">
                        </tbody>
                    </table>
            </div>
            <div class="row mt-3">
                <div class="form-group col-lg-6">
                    <label for="MO" class="subtitulo-rosa">Modus Operandi:</label>
                    <textarea rows="10" class="form-control form-control-sm text-uppercase" id="MO" name="MO" placeholder="Ingrese el Modus Operandi del grupo delictivo"onkeypress="return valideMultiples(event);"></textarea>
                </div>
                <div class="form-group col-lg-6">
                    <label for="observaciones" class="subtitulo-rosa">Observaciones:</label>
                    <textarea rows="10" class="form-control form-control-sm text-uppercase" id="observaciones" name="observaciones" placeholder="Ingrese las observaciones del grupo delictivo" onkeypress="return valideMultiples(event);" ></textarea>
                </div>
            </div>
            <h5 class="titulo-azul" >Asociacion de Eventos</h5>
            <div class="row mt-3">
                <div class="form-group col-lg-10">
                    <label for="id_evento" class="subtitulo-rosa">Busque Evento Asociado:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" placeholder="Ingrese el número Folio Infra del sistema"  id="id_evento" name="id_evento"  onkeypress="return valideKey(event);" >
                    <input type="text" class="mi_hide" id="id_evento_value">   
                    <span class="span_error" id="evento_error"></span>
                </div>
                <div class="form-group col-lg-2">
                    <br>
                    <button type="button" class="btn btn-add" onclick="onFormEventoSubmit()">Asociar Evento</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="EventoTable">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Folio Infra</th>
                            <th scope="col">Folio 911</th>
                            <th scope="col">Ubicacion</th>
                            <th scope="col">Delitos</th>
                            <th scope="col">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody id="contarEventos">
                    </tbody>
                </table>
            </div>
            <span class="span_error" id="tabla_eventos_error"></span>
            <div class="modal fade" id="ModalCenterFoto" tabindex="-1" role="dialog" aria-labelledby="myModalExito" aria-hidden="true" >
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <img name="nor" src="<?= base_url; ?>/public/media/images/logo6.png"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                </div>
            </div>
            <div class="row mt-5 mb-5">
                <div class="d-flex justify-content-end col-sm-12" id="id_p">
                    <a class="btn btn-ssc  mr-3" href="<?= base_url; ?>Seguimientos"><i class="material-icons v-a-middle">arrow_back_ios</i>Volver al inicio</a>
                    <a class="btn btn-ssc " id="btn_principal" value='1'>Guardar</a>
                </div>
            </div>
            
        </form>
    </div>
</div>
<br><br><br>