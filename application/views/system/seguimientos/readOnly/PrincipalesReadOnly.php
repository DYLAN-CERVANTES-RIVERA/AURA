<div class="container">
    <!--vista para ver seguimiento -->
    <form id='datos_principales'>
        <!--PARA MOSTRAR SI SE CARGARON LOS DATOS O UBO UN ERROR-->
        <div class="col-12 my-4" id="msg_principales"></div>
        <h5 class="titulo-azul" >Datos del Seguimiento</h5>
        <div class="row mt-3">
            <div class="col-lg-3 col-sm-6">
                <h5 class="subtitulo-rosa">Id de Seguimiento Asignado:</h5>
                <input type="text" name="id_seguimiento_principales" id="id_seguimiento_principales" class="form-control custom-input_dt">
                <span class="span_error" id="id_seguimiento_principales_error"></span> 
            </div>
            <div class="col-lg-5 col-sm-6 ">
                <h5  class="subtitulo-rosa">Elemento Que Creo Seguimiento: </h5>
                <input  style="font-size: 15px;color: #0F2145;"type="text" name="captura_principales" id="captura_principales" class="form-control custom-input_dt">
                <span class="span_error" id="captura_principales_error"></span>
            </div>
            <div class="col-lg-4 col-sm-6 ">             
                <h5 class="subtitulo-rosa">Fecha/Hora de Creacion del Seguimiento:</h5>
                <input type="text" name="fechahora_captura_principales" id="fechahora_captura_principales" class="form-control custom-input_dt">
                <span class="span_error" id="fechaP_error"></span>
            </div>
        </div><br>
        <div class="row mt-3">
            <div class="form-group col-lg-6 col-sm-6">
                <label for="nombre_grupo" class="subtitulo-rosa">Nombre del Grupo Delictivo:</label>
                <input type="text" class="form-control form-control-sm text-uppercase" id="nombre_grupo" name="nombre_grupo" disabled='true'>
                <span class="span_error" id="nombre_grupo_error"></span>
            </div>
            <div class="form-group col-lg-2">
                <label for="peligrosidad" class="subtitulo-rosa">Peligrosidad:</label>
                <select class="custom-select custom-select-sm" id="peligrosidad" name="peligrosidad" disabled='true'>
                    <option value="SD" selected>SELECCIONA UNA OPCION</option>
                    <option value="BAJA" >BAJA</option>
                    <option value="MEDIA">MEDIA</option>
                    <option value="ALTA">ALTA</option>
                </select>
                <span class="span_error" id="peligrosidad_error"></span>
            </div>
            <div class="form-group col-lg-4 col-sm-6">
                <label for="nombre_grupo" class="subtitulo-rosa">Foto del Grupo Delictivo:</label>
                <div id="imageContentGD"></div>
            </div>
           
        </div>
        <div class="table-responsive ">
            <table class="table table-bordered" id="DelitosTable" style="text-align:center">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Delitos Principales</th>
                    </tr>
                </thead>
                <tbody id="contardelitos">
                </tbody>
            </table>
        </div>
        <div class="row mt-3">
            <div class="form-group col-lg-6">
                <label for="MO" class="subtitulo-rosa">Modus Operandi:</label>
                <textarea rows="10" class="form-control form-control-sm text-uppercase" id="MO" name="MO" disabled='true'></textarea>
            </div>
            <div class="form-group col-lg-6">
                <label for="observaciones" class="subtitulo-rosa">Observaciones:</label>
                <textarea rows="10" class="form-control form-control-sm text-uppercase" id="observaciones" name="observaciones" disabled='true'></textarea>
            </div>
        </div>
        <h5 class="titulo-azul" >Asociacion de Eventos</h5>
        <div class="table-responsive">
            <table class="table table-bordered" id="EventoTable">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Folio Infra</th>
                        <th scope="col">Folio 911</th>
                        <th scope="col">Ubicacion</th>
                        <th scope="col">Delitos</th>
                    </tr>
                </thead>
                <tbody id="contarEventos">
                </tbody>
            </table>
        </div>
        <span class="span_error" id="tabla_eventos_error"></span>

       
        <div class="row mt-2 ">
            <div class="form-group col-sm-12 mi hide" id="viewPDF">
            </div>
        </div>

        <div class="row mt-5 mb-5">
            <div class="d-flex justify-content-end col-sm-12" id="id_p">
                <a class="btn btn-ssc  mr-3" href="<?= base_url; ?>Seguimientos"><i class="material-icons v-a-middle">arrow_back_ios</i>Volver al inicio</a>
            </div>
        </div>
        
    </form>
</div>