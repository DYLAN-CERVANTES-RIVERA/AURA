<div class= "content">
    <div class="cabecera_modulo" ><br></div>
    <div class="container">
        <!--vista para las tabs para edicion de los eventos  -->
        <?php if(!isset($data['titulo_1'])){ ?>
            <div class="paragraph-title d-flex justify-content-between mt-2 mb-1">
                <h5> <a href="<?= base_url; ?>Entrevistas">ENTREVISTAS DETENIDOS</a> <span>/ VER</span></h5>
            </div>
        <?php }else{ ?>
            <div class="paragraph-title d-flex justify-content-between mt-2 mb-1">
                <h5> <a href="<?= base_url; ?>Entrevistas">ENTREVISTAS DETENIDOS</a> <span>/ <?=$data['titulo_1']?></span></h5>
            </div>
        <?php } ?>
        <hr>
    </div>
    <div class="container">
        <!--vista para generar un nuevo evento -->
        <form id='datos_detenido_entrevistado' onsubmit="event.preventDefault()">
            <div class="row mt-2">
                <div class="col-lg-3 col-sm-6 ">
                    <span class="span_rem">Id de Persona Entrevistada: </h5>
                    <input  style="font-size: 15px;color: #0F2145; text-align:center;"type="text" name="id_persona_entrevista" id="id_persona_entrevista"  value=""class="form-control custom-input_dt text-uppercase" disabled="true">
                    <span class="span_error" id="id_persona_entrevista_error"></span>
                </div>

                <div class="col-lg-4 col-sm-6 ">           
                    <span class="span_rem ">Fecha/Hora de Creación de Entrevista:</h5>
                    <input style="font-size: 15px;color: #0F2145; text-align:center;" type="text" name="fechahora_captura_principales" id="fechahora_captura_principales" name="fechahora_captura_principales" class="form-control custom-input_dt" disabled="true">
                    <span class="span_error" id="fechaP_error"></span>
                </div>
            </div>
            <div class="form-row mt-3">
                <h5 class="titulo-azul col-lg-12 mt-3">Datos del Entrevistado</h5>
                <div class="form-group col-lg-12 col-sm-6">
                    <span class="span_rem col-lg-12 mt-3">Foto del Entrevistado:</span>
                    <div style="text-align:center;" id="imageContentDetenido"></div>
                </div>
                
            
                <div class="form-group col-lg-4 col-sm-6">
                    <span for="nombre" class="span_rem">Nombre del Entrevistado:</span>
                    <input type="text" class="form-control form-control-sm text-uppercase"  id="nombre" name="nombre" style="font-size: 15px;color: #0F2145; text-align:center;" disabled="true">
                </div>
                <div class="form-group col-lg-4 col-sm-6">
                    <span for="ap_paterno" class="span_rem">Apellido Paterno del Entrevistado:</span>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="30" id="ap_paterno" name="ap_paterno" style="font-size: 15px;color: #0F2145; text-align:center;" disabled="true">
                    <span class="span_error" id="ap_paterno_error"></span>
                </div>
                <div class="form-group col-lg-4 col-sm-6">
                    <span for="ap_materno" class="span_rem">Apellido Materno del Entrevistado:</span>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="30" id="ap_materno" name="ap_materno" style="font-size: 15px;color: #0F2145; text-align:center;" disabled="true" >
                    <span class="span_error" id="ap_materno_error"></span>
                </div>
                <div class="form-group col-lg-4 col-sm-6">
                    <span for="curp" class="span_rem">CURP:</span>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="19"id="curp" name="curp" style="font-size: 15px;color: #0F2145; text-align:center;" disabled="true">
                    <span class="span_error" id="curp_error"></span>
                </div>
                <div class="form-group col-lg-4 col-sm-6">
                    <span for="num_tel" class="span_rem">Número Telefónico:</span>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="11"id="num_tel" name="num_tel" style="font-size: 15px;color: #0F2145; text-align:center;" disabled="true">
                    <span class="span_error" id="num_tel_error"></span>
                </div>
                <div class="form-group col-lg-2 col-sm-6">
                    <span for="Fecha_n" class="span_rem">Fecha de Nacimiento:</span>
                    <input type="text" class="form-control form-control-sm" id="FechaNacimiento_principales" name="FechaNacimiento_principales" style="font-size: 15px;color: #0F2145; text-align:center;" disabled="true">
                    <span class="span_error" id="FechaNacimiento_principales_error"></span>
                </div>
                <div class="form-group col-lg-2 col-sm-6">
                    <span for="Edad" class="span_rem">Edad:</span>
                    <input type="text" class="form-control form-control-sm" maxlength="2" id="edad_principales" name="edad_principales" style="font-size: 15px;color: #0F2145; text-align:center;" disabled="true">
                    <span class="span_error" id="edad_principales_error"></span>
                </div>
                <div class="form-group col-lg-4 col-sm-6">
                    <span for="zona" class="span_rem">Zona:</span>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="45"id="zona" name="zona" style="font-size: 15px;color: #0F2145; text-align:center;" disabled="true" >
                    
                </div>
                <div class="form-group col-lg-8 col-sm-6">
                    <span for="banda" class="span_rem">Banda:</span>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="45"id="banda" name="banda" style="font-size: 15px;color: #0F2145; text-align:center;" disabled="true" >
                    <span class="span_error" id="banda_error"></span>
                </div>
                <div class="form-group col-lg-12">
                    <span for="detenido_por" class="span-form span_rem">Delitos del Entrevistado:</span>
                    <textarea name="detenido_por" id="detenido_por" cols="45" rows="3" class="form-control form-control-sm text-uppercase" style="font-size: 15px;color: #0F2145;" disabled="true"></textarea>
                    <span class="span_error" id="detenido_por_error"> </span>
                </div>
                <div class="form-group col-lg-12">
                    <span for="asociado_a" class="span-form span_rem">Antecendentes Asociados del Entrevistado:</span>
                    <textarea name="asociado_a" id="asociado_a" cols="45" rows="3" class="form-control form-control-sm text-uppercase" style="font-size: 15px;color: #0F2145;" disabled="true"></textarea>
                    <span class="span_error" id="asociado_a_error"> </span>
                </div>
                <div class="form-group col-lg-12">
                    <span for="alias" class="span-form span_rem">Alias del Entrevistado:</span>
                    <textarea name="alias" id="alias" cols="45" rows="3" class="form-control form-control-sm text-uppercase" style="font-size: 15px;color: #0F2145;" disabled="true"></textarea>
                    <span class="span_error" id="alias_error"> </span>
                </div>
                <div class="form-group col-lg-12">
                    <span for="remisiones" class="span-form span_rem">Remisiones del Entrevistado:</span>
                    <textarea name="remisiones" id="remisiones" cols="45" rows="3" class="form-control form-control-sm text-uppercase" style="font-size: 15px;color: #0F2145;" disabled="true"></textarea>
                    <span class="span_error" id="remisiones_error"> </span>
                </div>
            </div>
            <h5 class="titulo-azul mt-3">Domicilio del Entrevistado</h5>
            <div class="form-row mt-3">
                <div class="form-group col-lg-12 col-sm-10">
                    <span for="colonia_dom" class="span_rem">Colonia:</span>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="100" id="colonia_dom" name="colonia_dom" style="font-size: 15px;color: #0F2145; text-align:center;" disabled="true">
                    <span class="span_error" id="colonia_dom_error"></span>
                </div>
                <div class="form-group col-lg-6 col-sm-6">
                    <span for="calle_dom" class="span_rem">Calle:</span>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="100" id="calle_dom" name="calle_dom" style="font-size: 15px;color: #0F2145; text-align:center;" disabled="true">
                    <span class="span_error" id="calle_dom_error"></span>
                </div>
                <div class="form-group col-lg-6 col-sm-6">
                    <span for="calle2_dom" class="span_rem">Calle 2:</span>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="100" id="calle2_dom" name="calle2_dom" style="font-size: 15px;color: #0F2145; text-align:center;" disabled="true">
                    <span class="span_error" id="calle2_dom_error"></span>
                </div>
                <div class="form-group col-lg-4 col-sm-3">
                    <span for="numExt_dom" class="span_rem">No.Ext.:</span>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="6" id="numExt_dom" name="numExt_dom" style="font-size: 15px;color: #0F2145; text-align:center;" disabled="true">
                    <span class="span_error" id="numExt_dom_error"></span>
                </div>
                <div class="form-group col-lg-4 col-sm-3">
                    <span for="numInt_dom" class="span_rem">No.Int.:</span>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="6" id="numInt_dom" name="numInt_dom" style="font-size: 15px;color: #0F2145; text-align:center;" disabled="true">
                    <span class="span_error" id="numInt_dom_error"></span>
                </div>
            </div>
            <h5 class="titulo-azul mt-3">Lugar de la Detencion</h5> 
            <div class="form-row mt-3">
                <div class="form-group col-lg-12 col-sm-10">
                    <span for="colonia_detencion" class="span_rem">Colonia:</span>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="100" id="colonia_detencion" name="colonia_detencion" style="font-size: 15px;color: #0F2145; text-align:center;" disabled="true">
                    <span class="span_error" id="colonia_detencion_error"></span>
                </div>
                <div class="form-group col-lg-6 col-sm-6">
                    <span for="calle_detencion" class="span_rem">Calle:</span>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="100" id="calle_detencion" name="calle_detencion" style="font-size: 15px;color: #0F2145; text-align:center;" disabled="true">
                    <span class="span_error" id="calle_detencion_error"></span>
                </div>
                <div class="form-group col-lg-6 col-sm-6">
                    <span for="calle2_detencion" class="span_rem">Calle 2:</span>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="100" id="calle2_detencion" name="calle2_detencion" style="font-size: 15px;color: #0F2145; text-align:center;" disabled="true">
                    <span class="span_error" id="calle2_detencion_error"></span>
                </div>
                <div class="form-group col-lg-4 col-sm-3">
                    <span for="numExt_detencion" class="span_rem">No.Ext.:</span>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="6" id="numExt_detencion" name="numExt_detencion" style="font-size: 15px;color: #0F2145; text-align:center;" disabled="true">
                    <span class="span_error" id="numExt_detencion_error"></span>
                </div>
                <div class="form-group col-lg-4 col-sm-3">
                    <span for="numInt_detencion" class="span_rem">No.Int.:</span>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="6" id="numInt_detencion" name="numInt_detencion" style="font-size: 15px;color: #0F2145; text-align:center;" disabled="true">
                    <span class="span_error" id="numInt_detencion_error"></span>
                </div>
            </div>
            <h5 class="titulo-azul mt-3">Asignación</h5>
            <div class="form-group col-lg-8 col-sm-3">
                <label for="Asignado" class="span_rem">Asinado a:</label>
                <input type="text" class="form-control form-control-sm text-uppercase" maxlength="100" id="Asignado" name="Asignado" style="font-size: 15px;color: #0F2145; text-align:center;" disabled="true">
                <span class="span_error" id="Asignado_error"></span>
            </div>
        </form>
        <div class="row mt-5 mb-5">
            <div class="d-flex justify-content-end col-sm-12" id="id_p">
                <a class="btn btn-ssc  mr-3" href="<?= base_url; ?>Entrevistas"><i class="material-icons v-a-middle">arrow_back_ios</i>Volver al inicio</a>
            </div>
        </div>
    </div>
</div>