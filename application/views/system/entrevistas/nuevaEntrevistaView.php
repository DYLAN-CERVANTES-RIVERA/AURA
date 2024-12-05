<div class= "content">
    <div class="cabecera_modulo" ><br></div>
    <div class="container">
        <!--vista para las tabs para edicion de los eventos  -->
        <?php if(!isset($data['titulo_1'])){ ?>
            <div class="paragraph-title d-flex justify-content-between mt-2 mb-1">
                <h5> <a href="<?= base_url; ?>Entrevistas">ENTREVISTAS DETENIDOS</a> <span>/ NUEVA</span></h5>
            </div>
        <?php }else{ ?>
            <div class="paragraph-title d-flex justify-content-between mt-2 mb-1">
                <h5> <a href="<?= base_url; ?>Entrevistas">ENTREVISTAS DETENIDOS</a> <span>/ <?=$data['titulo_1']?></span></h5>
            </div>
        <?php } ?>
        <hr>
    </div>
    <div class="container">
        <!--vista para generar un nueva entrevista -->
        <form id='datos_principales_Detenido_Entrevistado' onsubmit="event.preventDefault()">
        <div class="col-12 my-4" id="msg_principales"></div>
            <div class="row mt-2">
                <div class="col-lg-6 col-sm-6 ">           
                    <span class="span_rem">Fecha/Hora de Creación de Entrevista:</h5>
                    <input style="font-size: 15px;color: #0F2145;" type="text" name="fechahora_captura_principales" id="fechahora_captura_principales" name="fechahora_captura_principales" class="form-control custom-input_dt">
                    <span class="span_error" id="fechaP_error"></span>
                </div>
                <div class="col-lg-6 col-sm-6 ">
                    <span class="span_rem ">Elemento que Captura Dato de Detenido Entrevista: </h5>
                    <input  style="font-size: 15px;color: #0F2145;"type="text" name="captura_dato_entrevista" id="captura_dato_entrevista" name="captura_dato_entrevista" value="<?php echo $_SESSION['userdataSIC']->User_Name?>" class="form-control custom-input_dt text-uppercase" disabled="true">
                    <span class="span_error" id="captura_dato_entrevista_error"></span>
                </div>
            </div>
            <h5 class="titulo-azul col-lg-12 mt-3">Remision</h5>
            <div class="row mt-2">
                <div class="col-6">
                    <span class="span_rem">¿Tiene alguna remisión capturada en SARAI la persona?</h5>
                </div>
                <div class="col-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="Remision_Si_No" id="id_rem_1" value="1" onchange="return changeRemision(event)" >
                        <label class="form-check-label" for="id_rem_1">Si</label>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="Remision_Si_No" id="id_rem_2" checked value="0" onchange="return changeRemision(event)" >
                        <label class="form-check-label" for="id_rem_2">No</label>
                    </div>
                </div>
                <div class="col-12 text-center">
                    <small id="error_remision_si_no" class="form-text text-danger"></small>
                </div>
            </div>
            <div class="row mt-3 mi_hide" id="id_Remision_panel">
                <div class="col-5">   
                    <h5  class="subtitulo-rosa">Ingrese el numero de remisión: </h5>            
                </div>
                <div class="col-7">
                    <input type="text" class="form-control form-control-sm " placeholder="Buscar"  id="id_remision" name="id_remision"  onkeypress="return valideKey(event);" >
                </div>
                <div class="col-12 text-center">
                    <small id="error_remision" class="form-text text-danger"></small>
                </div>
            </div>
            <div class="form-row mt-3">
                <h5 class="titulo-azul col-lg-12 mt-3">Grupos Delictivos Capturados en Sistema</h5>
                <div class="form-group col-lg-6">
                    <span class="span_rem">Seleccione un grupo delictivo para asociar a la Persona entrevistada (opcional):</span>
                    <select class="custom-select custom-select-sm" id="Id_Banda_Seguimiento" name="Id_Banda_Seguimiento">
                        <option value="SD" selected>SELECCIONE UNA OPCION</option>
                    </select>
                    <span class="span_error" id="Id_Banda_Seguimiento_error"></span>
                </div>  
                <div class="form-group col-lg-6 mi_hide" id="captura_sic">
                    <span class="span_rem">¿La persona ya esta capturada en el grupo delictivo?</h5>
                    <div class="col-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="Capturada_Si_No" id="id_sic_1" value="1">
                            <label class="form-check-label" for="id_sic_1">Si</label>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="Capturada_Si_No" id="id_sic_2" checked value="0">
                            <label class="form-check-label" for="id_sic_2">No</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row mt-3">
                
                <h5 class="titulo-azul col-lg-12 mt-3">Datos del Entrevistado</h5>
                <div class="form-group col-lg-4 col-sm-6">
                    <label for="nombre" class="subtitulo-rosa">Nombre Entrevistado:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="30" id="nombre" name="nombre" placeholder="Ingrese Nombre" onkeypress="return valida(event);">
                    <span class="span_error" id="nombre_error"></span>
                </div>
                <div class="form-group col-lg-4 col-sm-6">
                    <label for="ap_paterno" class="subtitulo-rosa">Apellido Paterno Entrevistado:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="30" id="ap_paterno" name="ap_paterno" placeholder="Ingrese Apellido Paterno" onkeypress="return valida(event);">
                    <span class="span_error" id="ap_paterno_error"></span>
                </div>
                <div class="form-group col-lg-4 col-sm-6">
                    <label for="ap_materno" class="subtitulo-rosa">Apellido Materno Entrevistado:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="30" id="ap_materno" name="ap_materno" placeholder="Ingrese Apellido Materno" onkeypress="return valida(event);">
                    <span class="span_error" id="ap_materno_error"></span>
                </div>
                <div class="form-group col-lg-4 col-sm-6">
                    <label for="curp" class="subtitulo-rosa">CURP:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="19"id="curp" name="curp" placeholder="Ingrese Curp"onkeypress="return validaCurp(event);" >
                    <span class="span_error" id="curp_error"></span>
                </div>
                <div class="form-group col-lg-4 col-sm-6">
                    <label for="num_tel" class="subtitulo-rosa">Número Telefónico:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="50"id="num_tel" name="num_tel" placeholder="Ingrese número telefónico" >
                    <span class="span_error" id="num_tel_error"></span>
                </div>
                <div class="form-group col-lg-2 col-sm-6">
                    <label for="Fecha_n" class="subtitulo-rosa">Fecha de Nacimiento:</label>
                    <input type="date" class="form-control form-control-sm" id="FechaNacimiento_principales" name="FechaNacimiento_principales">
                    <span class="span_error" id="FechaNacimiento_principales_error"></span>
                </div>
                <div class="form-group col-lg-2 col-sm-6">
                    <label for="Edad" class="subtitulo-rosa">Edad:</label>
                    <input type="text" class="form-control form-control-sm" maxlength="2" id="edad_principales" name="edad_principales" onkeypress="return validePanelRemisiones(event);">
                    <span class="span_error" id="edad_principales_error"></span>
                </div>
                <div class="form-group col-lg-4" id="zonaContent">
                    <label for="zona" class="label-form subtitulo-rosa">Zona</label>
                    <select class="custom-select custom-select-sm" id="zona" name="zona">
                        <option value="SD">SELECCIONE ZONA DE OPERACION</option>
                        <?php foreach ($data['datos_cat']['zonas'] as $item) : ?>
                                <option value="<?php echo $item->Zona_Sector; ?>"><?php echo $item->Zona_Sector; ?></option>
                            <?php endforeach ?>
                        </select>
                </div>
                <div class="form-group col-lg-8 col-sm-6">
                    <label for="banda" class="subtitulo-rosa">Banda:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="45"id="banda" name="banda" placeholder="Ingrese la banda"onkeypress="return valida(event);" >
                    <span class="span_error" id="banda_error"></span>
                </div>
                <div class="form-group col-lg-12">
                    <label for="detenido_por" class="label-form subtitulo-rosa">Delitos del Detenido Entrevistado:</label>
                    <textarea name="detenido_por" id="detenido_por" cols="45" rows="3" class="form-control form-control-sm text-uppercase"placeholder="Ingrese los delitos por los que fue detenida la personas entrevistada separados por ','" onkeypress="return valideMultiplesDatos(event);"></textarea>
                    <span class="span_error" id="detenido_por_error"> </span>
                </div>
                <div class="form-group col-lg-12">
                    <label for="asociado_a" class="label-form subtitulo-rosa">Antecendentes Asociados del Detenido Entrevistado:</label>
                    <textarea name="asociado_a" id="asociado_a" cols="45" rows="3" class="form-control form-control-sm text-uppercase"placeholder="Ingrese los antecendentes asociados a la personas entevistada separados por ','" onkeypress="return valideMultiplesDatos(event);"></textarea>
                    <span class="span_error" id="asociado_a_error"> </span>
                </div>
                <div class="form-group col-lg-12">
                    <label for="alias" class="label-form subtitulo-rosa">Alias del Detenido Entrevistado:</label>
                    <textarea name="alias" id="alias" cols="45" rows="3" class="form-control form-control-sm text-uppercase"placeholder="Ingrese alias de la personas separados por ','" onkeypress="return valideMultiplesDatos(event);"></textarea>
                    <span class="span_error" id="alias_error"> </span>
                </div>
                <div class="form-group col-lg-12">
                    <label for="remisiones" class="label-form subtitulo-rosa">Remisiones del Detenido Entrevistado:</label>
                    <textarea name="remisiones" id="remisiones" cols="45" rows="3" class="form-control form-control-sm text-uppercase"placeholder="Ingrese remisiones que tenga la persona separadas por ','"onkeypress="return validePanelRemisiones(event);"></textarea>
                    <span class="span_error" id="remisiones_error"> </span>
                </div>
            </div>
            <h5 class="titulo-azul mt-3">Domicilio del detenido</h5>
            <div class="form-row mt-3">
                <div class="form-group col-lg-12 col-sm-10">
                    <label for="colonia_dom" class="subtitulo-rosa">Colonia:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="100" id="colonia_dom" name="colonia_dom" placeholder="Ingrese Colonia del domiclio del detenido">
                    <span class="span_error" id="colonia_dom_error"></span>
                </div>
                <div class="form-group col-lg-6 col-sm-6">
                    <label for="calle_dom" class="subtitulo-rosa">Calle:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="100" id="calle_dom" name="calle_dom" placeholder="Ingrese Calle del domiclio del detenido">
                    <span class="span_error" id="calle_dom_error"></span>
                </div>
                <div class="form-group col-lg-6 col-sm-6">
                    <label for="calle2_dom" class="subtitulo-rosa">Calle 2:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="100" id="calle2_dom" name="calle2_dom" placeholder="Ingrese Calle 2 del domiclio del detenido">
                    <span class="span_error" id="calle2_dom_error"></span>
                </div>
                <div class="form-group col-lg-4 col-sm-3">
                    <label for="numExt_dom" class="subtitulo-rosa">No.Ext.:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="20" id="numExt_dom" name="numExt_dom" placeholder="Ingrese Num.Ext. del domiclio del detenido" onkeypress="return valideMultiplesDatos(event);">
                    <span class="span_error" id="numExt_dom_error"></span>
                </div>
                <div class="form-group col-lg-4 col-sm-3">
                    <label for="numInt_dom" class="subtitulo-rosa">No.Int.:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="20" id="numInt_dom" name="numInt_dom" placeholder="Ingrese Num.Int. del domiclio del detenido" onkeypress="return valideMultiplesDatos(event);">
                    <span class="span_error" id="numInt_dom_error"></span>
                </div>
            </div>
            <h5 class="titulo-azul mt-3">Lugar de la detencion</h5> 
            <div class="form-row mt-3">
                <div class="form-group col-lg-12 col-sm-10">
                    <label for="colonia_detencion" class="subtitulo-rosa">Colonia:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="100" id="colonia_detencion" name="colonia_detencion" placeholder="Ingrese Colonia de la detencion">
                    <span class="span_error" id="colonia_detencion_error"></span>
                </div>
                <div class="form-group col-lg-6 col-sm-6">
                    <label for="calle_detencion" class="subtitulo-rosa">Calle:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="100" id="calle_detencion" name="calle_detencion" placeholder="Ingrese Calle de la detencion">
                    <span class="span_error" id="calle_detencion_error"></span>
                </div>
                <div class="form-group col-lg-6 col-sm-6">
                    <label for="calle2_detencion" class="subtitulo-rosa">Calle 2:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="100" id="calle2_detencion" name="calle2_detencion" placeholder="Ingrese Calle2 de la detencion">
                    <span class="span_error" id="calle2_detencion_error"></span>
                </div>
                <div class="form-group col-lg-4 col-sm-3">
                    <label for="numExt_detencion" class="subtitulo-rosa">No.Ext.:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="20" id="numExt_detencion" name="numExt_detencion" placeholder="Ingrese Num.Ext. de la detencion" onkeypress="return valideMultiplesDatos(event);">
                    <span class="span_error" id="numExt_detencion_error"></span>
                </div>
                <div class="form-group col-lg-4 col-sm-3">
                    <label for="numInt_detencion" class="subtitulo-rosa">No.Int.:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="20" id="numInt_detencion" name="numInt_detencion" placeholder="Ingrese Num.Int. de la detencion" onkeypress="return valideMultiplesDatos(event);">
                    <span class="span_error" id="numInt_detencion_error"></span>
                </div>
            </div>

            <div class="modal fade" id="ModalCenterPrincipalAntecedentes" tabindex="-1" role="dialog" aria-labelledby="myModalExito" aria-hidden="true" >
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <img name="nor" src="<?= base_url; ?>/public/media/images/logo6.png"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                </div>
            </div>
            <div class="row mt-5 mb-5">
                <div class="d-flex justify-content-end col-sm-12" id="id_p">
                    <a class="btn btn-ssc  mr-3" href="<?= base_url; ?>Entrevistas"><i class="material-icons v-a-middle">arrow_back_ios</i>Volver al inicio</a>
                    <a class="btn btn-ssc " id="btn_detenido_entrevista_principal" value='1'>Guardar</a>
                </div>
            </div>
        </form>
    </div>
</div>
<br><br><br>