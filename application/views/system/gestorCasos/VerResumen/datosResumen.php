<div class= "content">
    <div class="cabecera_modulo" ><br></div>
    <div class="container">
        <!--vista para las tabs para edicion de los eventos  -->
        <?php if(!isset($data['titulo_1'])){ ?>
            <div class="paragraph-title d-flex justify-content-between mt-2 mb-1">
                <h5> <a href="<?= base_url; ?>GestorCasos">GESTOR DE EVENTOS </a> <span>/ RESUMEN</span></h5>
            </div>
        <?php }else{ ?>
            <div class="paragraph-title d-flex justify-content-between mt-2 mb-1">
                <h5> <a href="<?= base_url; ?>GestorCasos">GESTOR DE EVENTOS </a> <span>/ <?=$data['titulo_1']?></span></h5>
            </div>
        <?php } ?>
        <hr>
    </div>
    <div class="container center-all">
   <!--vista para el resumen evento -->
    <div class="row mt-5">
        <div class="col-lg-3 col-sm-12 ">
            <span class="span_rem">Folio Infra: </span>
            <span class="span_error" id="Folio_infra_principalesError"></span>
            <span class="span_rem_ans" name="Folio_infra_principales" id="Folio_infra_principales"></span>
        </div>   
        <div class="col-lg-5 col-sm-12  ">
            <span class="span_rem">Elemento Capturante: </span>
            <span class="span_rem_ans" name="elemento_captura" id="elemento_captura"></span>
        </div>
        
        <div class="col-lg-3 col-sm-12  ">
            <span class="span_rem">Fuente: </span>
            <span class="span_error" id="Fuente_principalesError"></span>
            <span class="span_rem_ans" name="Fuente_principales" id="Fuente_principales"></span>
        </div>
        <div class="col-lg-3 col-sm-12  ">
            <span class="span_rem">Folio 911: </span>
            <span class="span_error" id="911_principalesError"></span>
            <span class="span_rem_ans" name="911_principales" id="911_principales"></span>
        </div>
        <div class="col-lg-5 col-sm-12  ">
            <span class="span_rem">Estatus de Seguimiento: </span>
            <span class="span_error" id="status_principalesError"></span>
            <span class="span_rem_ans" name="status_principales" id="status_principales"></span>
        </div>
        <div class="col-lg-3 col-sm-12  ">
            <span class="span_rem">Con/Sin violencia: </span>
            <span class="span_error" id="violenciaCS_principalesError"></span>
            <span class="span_rem_ans" name="violenciaCS_principales" id="violenciaCS_principales"></span>
        </div>
    </div>
    <div class="row ">
        <div class="col-lg-5 col-sm-12  ">
            <span class="span_rem">Estatus del Evento: </span>
            <span class="span_error" id="Estatus_EventoError"></span>
            <span class="span_rem_ans" name="Estatus_Evento" id="Estatus_Evento"></span>
        </div>
    </div>
    
    <div class="row mt-5">
        <div class="form-group col-lg-6 mt-4 text-center">
            <h5 class="text-center" >Fecha y Hora de Captura en Sistema: </h5>
            <span class="span_rem_ans" name="fechahora_captura_principales" id="fechahora_captura_principales"></span>  
        </div>
        <div class="form-group col-lg-6 mt-4 text-center  ">
            <h5 class="text-center" >Fecha y Hora de Recepcion : </h5>
            <span class="span_rem_ans" name="fechahora_recepcion_principales" id="fechahora_recepcion_principales"></span>
        </div>
    </div>

    <div class="row mt-5">
        <div class="form-group col-lg-6 mt-4 text-center">
            <h5 class="text-center" >Fecha y Hora de Activacion de Seguimiento: </h5>
            <span class="span_rem_ans" name="fechahora_activacion_principales" id="fechahora_activacion_principales"></span>
        </div>
        <div class="form-group col-lg-6 mt-4 text-center">
            <h5 class="text-center" >Elemento Asignado para el Seguimiento: </h5>
            <span class="span_rem_ans" name="ClaveAsignacion" id="ClaveAsignacion"></span>
        </div>
    </div>
    <br>
    <div class="col-12 col-md-12" id="id_permisos_cuenta">
        <div class="row mt-5">
            <div class="col-12 text-center">
                <h5 class="card-title">Resumen Seguimiento del Evento</h5>
            </div>

            <div class="col-auto mx-auto mt-4">
                <table class="table table-responsive" id="ResumenTable">
                    <thead>
                        <tr class="align-middle text-center">
                            <th>VEHICULOS</th>
                            <th>INVOLUCRADOS</th>
                            <th>ENTREVISTAS</th>
                            <th>FOTOS Y VIDEOS</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                    </tbody>
                </table>
            </div>

            <div class="row col-lg-3" >
                <div class="row col-lg-12">
                    <i class="material-icons close_icon">close</i>
                    <h5 >= Sin datos</h5>
                </div>
                
                <div class="row col-lg-12">
                    <i class="material-icons close_icon">check</i> 
                    <h5 >= Completado</h5>
                </div>
                <div class="row col-lg-12">
                    <i class="material-icons close_icon">P </i> 
                    <h5 > = Por Completar</h5>
                </div>
            </div>
        </div>
        
    </div>
    
    <div class="row mt-5 mb-5">
        <div class="d-flex  col-sm-6" >
                 <a class="btn btn-sm btn-ssc mr-3" href="<?= base_url; ?>GestorCasos/editarEvento/?Folio_infra=<?php $separadas=explode("=", $_SERVER["REQUEST_URI"]); $Folio_infra=$separadas[1];echo ($Folio_infra);?>">Editar Evento</a>
        </div>
        <div class="d-flex  col-sm-6">
            <a class="btn btn-sm btn-ssc" href="<?= base_url;?>GestorCasos"><i class="material-icons v-a-middle">arrow_back_ios</i>Volver al inicio</a>
        </div>
    </div>


    </div>
</div>