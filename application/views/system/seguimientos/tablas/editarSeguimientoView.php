<div class="container">
    <!--vista para generar un nuevo evento -->
    <form id='datos_principales' onsubmit="event.preventDefault()">
        <!--PARA MOSTRAR SI SE CARGARON LOS DATOS O UBO UN ERROR-->
        <div class="col-12 my-4" id="msg_principales"></div>
        
        <div class="row mt-3">
            <div class="col-lg-3 col-sm-6">
                <h5 class="subtitulo-rosa">Id de Red Asignado:</h5>
                <input type="text" name="id_seguimiento_principales" id="id_seguimiento_principales" class="form-control custom-input_dt">
                <span class="span_error" id="id_seguimiento_principales_error"></span> 
            </div>
            <div class="col-lg-5 col-sm-6 ">
                <h5  class="subtitulo-rosa">Elemento que creo la Red: </h5>
                <input  style="font-size: 15px;color: #0F2145;"type="text" name="captura_principales" id="captura_principales" class="form-control custom-input_dt">
                <span class="span_error" id="captura_principales_error"></span>
            </div>
            <div class="col-lg-4 col-sm-6 ">             
                <h5 class="subtitulo-rosa">Fecha/Hora de Creación del Red:</h5>
                <input type="text" name="fechahora_captura_principales" id="fechahora_captura_principales" class="form-control custom-input_dt">
                <span class="span_error" id="fechaP_error"></span>
            </div>
        </div><br>

        <h5 class="titulo-azul" >Datos de la Red</h5>
        <?php
            $disabled =  ($_SESSION['userdataSIC']->Modo_Admin == 1 )  ? false:true;
        ?>
        <div class="row mi_hide" id = "Panel_Asociacion">
            <div class="col-5">   
                <h5  class="subtitulo-rosa">Ingrese el grupo al que se requiere asociar </h5>            
            </div>
            <div class="col-7">
                <input type="text" class="form-control form-control-sm " placeholder="Poner solo el id de la red busque y seleccione"  id="Id_red" name="Id_red" <?php echo $disabled ? 'disabled' : ''; ?>  >
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-lg-3" >
                <label class="subtitulo-rosa">Tipo de Red:</label>
            </div> 
            <div class="col-3">
                <input type="radio" id="Question3" name="Question" value="2" <?php echo $disabled ? 'disabled' : ''; ?>>
                <label>Evento Delictivo</label>
            </div> 
            <div class="col-3">
                <input type="radio" id="Question1" name="Question" value="1" <?php echo $disabled ? 'disabled' : ''; ?>>
                <label>Persona</label>
            </div> 
            <div class="col-3">
                <input type="radio" id="Question2" name="Question" value="0"checked <?php echo $disabled ? 'disabled' : ''; ?>>
                <label>Grupo</label>
            </div> 
        </div>
        <div class="row mt-3">
            <div class="row d-flex justify-content-center col-lg-12">
                <div class="form-group form-check col-2" id="Pregunta_Consu"> 
                    <input type="checkbox" class="form-check-input checkPermisos" value="0" id="consulta" name="consulta" >
                    <label class="form-check-label" for="consulta">Consulta Realizada</label>
                    <br>
                </div>
            </div>
            <div class="row col-lg-12" id="Panel_nombre">
                <div class="form-group col-lg-6 col-sm-6">
                    <label for="nombre_grupo" class="subtitulo-rosa">Nombre del Grupo Delictivo:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" id="nombre_grupo" name="nombre_grupo" placeholder="Ingrese nombre del grupo delictivo" onkeypress="return validaConAcentos(event);" >
                    <span class="span_error" id="nombre_grupo_error"></span>
                </div>
                <div class="form-group col-lg-6">
                    <label for="peligrosidad" class="subtitulo-rosa">Peligrosidad:</label>
                    <select class="custom-select custom-select-sm" id="peligrosidad" name="peligrosidad">
                        <option value="SD" selected>SELECCIONA UNA OPCION</option>
                        <option value="BAJA" >BAJA</option>
                        <option value="MEDIA">MEDIA</option>
                        <option value="ALTA">ALTA</option>
                    </select>
                    <span class="span_error" id="peligrosidad_error"></span>
                </div>

                <div class="form-group col-lg-4 col-sm-6">
                    <label for="nombre_grupo" class="subtitulo-rosa">Foto del Grupo Delictivo:</label>
                    <div class="d-flex justify-content-around" id="uploadFileFotoGD">
                        <div class="form-group">
                            <input type="file" name="FotoGrupoDelictivo" accept="image/*" id="fileFotoGD" class="inputfile uploadFileFotoGD" onchange="uploadFileGD(event)" data-toggle="tooltip" data-placement="bottom">
                            <label for="fileFotoGD"></label>
                        </div>
                    </div>
                    <div id="imageContentGD"></div>
                </div>
            </div>

            <div class="row col-lg-12">
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
        </div>
        <div class="table-responsive ">
            <table class="table table-bordered" id="DelitosTable" style="text-align:center">
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
                <textarea rows="10" class="form-control form-control-sm text-uppercase" id="MO" name="MO" placeholder="Ingrese el Modus Operandi del grupo delictivo" onkeypress="return valideMultiples(event);"></textarea>
            </div>
            <div class="form-group col-lg-6">
                <label for="observaciones" class="subtitulo-rosa">Observaciones:</label>
                <textarea rows="10" class="form-control form-control-sm text-uppercase" id="observaciones" name="observaciones" placeholder="Ingrese las observaciones del grupo delictivo" onkeypress="return valideMultiples(event);"></textarea>
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
                        <th scope="col">Folio AURA</th>
                        <th scope="col">Folio 911</th>
                        <th scope="col">Ubicacion</th>
                        <th scope="col">Delitos</th>
                        <th scope="col">Desasociar</th>
                    </tr>
                </thead>
                <tbody id="contarEventos">
                </tbody>
            </table>
        </div>
        <span class="span_error" id="tabla_eventos_error"></span>
        <div id="pdf_Segui">
            <div class="row mt-2" >
                <div class="form-group col-lg-12">
                    <label for="id_RED" class="subtitulo-rosa">Red de Vínculos:</label>
                    
                    <div class="input-group col-sm-12 mt-4 mb-5">
                        <div class="custom-file">
                        
                            <input type="file" name="filePDFSeguimiento" onchange="uploadFilePDF(event)"class="bs-custom-file-input" id="filePDFSeguimiento" accept=".pdf">
                            <label class="custom-file-label" for="filePDFSeguimiento">Agrega archivo PDF</label>
                        </div>
                    </div>
                </div> 
                <div class="filePDF col-sm-12 d-flex justify-content-center" id="filePDF">
                </div>
            </div>
            <div class="row mt-2 ">
                <div class="form-group col-sm-12 mi hide" id="viewPDF">
                </div>
            </div>
        </div>

        <?php $clase = ( $_SESSION['userdataSIC']->Modo_Admin == 1 ) ? '' : 'mi_hide';?>  
        <div class="row mt-2 mi_hide">
            <label class="subtitulo-rosa">Tipo Red de Vínculo:</label>
            <div class="col-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="Alto_Imp_Si_No" id="id_alto_1" value="1">
                    <label class="form-check-label" for="id_alto_1">Si es de Alto Impacto</label>
                </div>
            </div>
            <div class="col-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="Alto_Imp_Si_No" id="id_alto_2" checked value="0">
                    <label class="form-check-label" for="id_alto_2">No es de Alto Impacto</label>
                </div>
            </div>
            <a class="btn btn-ssc " id="btn_Alto_Impacto" value='1'> Cambiar </a>
        </div>

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