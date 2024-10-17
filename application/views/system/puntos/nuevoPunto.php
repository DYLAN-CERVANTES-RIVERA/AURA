<div class= "content">
    <div class="cabecera_modulo" ><br></div>
    <div class="container">
        <!--vista para las tabs para edicion de los eventos  -->
        <?php if(!isset($data['titulo_1'])){ ?>
            <div class="paragraph-title d-flex justify-content-between mt-2 mb-1">
                <h5> <a href="<?= base_url; ?>Puntos">PUNTOS IDENTIFICADOS </a> <span>/ NUEVO PUNTO</span></h5>
            </div>
        <?php }else{ ?>
            <div class="paragraph-title d-flex justify-content-between mt-2 mb-1">
                <h5> <a href="<?= base_url; ?>Puntos">PUNTOS IDENTIFICADOS </a> <span>/ <?=$data['titulo_1']?></span></h5>
            </div>
        <?php } ?>
        <hr>
    </div>
    <div class="container">
        <!--vista para generar un nuevo evento -->
        <form id='datos_principales_puntos' onsubmit="event.preventDefault()">
            <!--PARA MOSTRAR SI SE CARGARON LOS DATOS O UBO UN ERROR-->
            <div class="col-12 my-4" id="msg_principales_puntos"></div>
            <div class="row mt-3">
                <div class="col-lg-5 col-sm-6">
                    <h5  class="subtitulo-rosa">Captura </h5>
                    <input style="text-align:center;font-size: 15px;color: #0F2145;" type="text" name="captura_principales" id="captura_principales" value="<?php echo strtoupper($_SESSION['userdataSIC']->User_Name)?>" class="form-control custom-input_dt">
                </div>
                <div class="col-lg-2 col-sm-6"></div>
                <div class="col-lg-5 col-sm-6">             
                    <h5 class="subtitulo-rosa">Fecha/Hora de Captura</h5>
                    <div class="col-lg-8 col-sm-6 d-inline-flex align-items-center">
                        <input type="text" name="fechahora_captura_principales" id="fechahora_captura_principales" class="form-control custom-input_dt text-center">
                    </div>
                </div>
            </div><br>
            <h5 class="titulo-azul">Ubicacion Identificada</h5>

            <div class="row mt-3">
                <div class="col-lg-6">
                    <div class="form-row mt-3">
                        <p class="label-form ml-2 parrafo-azul"> Buscar por: </p>

                        <div class="form-group col-lg-12">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="porDireccion_alertas" name="busqueda" class="custom-control-input" value="0">
                                <label class="custom-control-label label-form parrafo-azul" for="porDireccion_alertas">Dirección</label>
                            </div>

                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="porCoordenadas_alertas" name="busqueda" class="custom-control-input" value="1">
                                <label class="custom-control-label label-form parrafo-azul" for="porCoordenadas_alertas">Coordenadas</label>
                            </div>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="Colonia" class="label-form subtitulo-rosa">Colonia</label>
                            <input type="text" class="form-control form-control-sm text-uppercase" id="Colonia" name="Colonia" placeholder="Ingrese la Colonia del Evento">
                            <span class="span_error" id="Colonia_principales_error"></span>

                        </div>

                        <div class="form-group col-lg-6">
                            <label for="Calle" class="label-form subtitulo-rosa">Calle</label>
                            <input type="text" class="form-control form-control-sm text-uppercase" id="Calle" name="Calle" placeholder="Ingrese la Calle 1 del Evento" onkeypress="return valideMultiples(event);">
                            <span class="span_error" id="Calle_principales_error"></span>

                        </div>

                        <div class="form-group col-lg-6">
                            <label for="Calle2" class="label-form subtitulo-rosa">Calle 2</label>
                            <input type="text" class="form-control form-control-sm text-uppercase" id="Calle2" name="Calle2" placeholder="Ingrese la Calle 2 del Evento" onkeypress="return valideMultiples(event);">
                            <span class="span_error" id="Calle2_principales_error"></span>
                        </div>

                        <div class="form-group col-lg-3">
                            <label for="no_Ext" class="label-form subtitulo-rosa">No. Ext.</label>
                            <input type="text" class="form-control form-control-sm" id="no_Ext" name="no_Ext" maxlength="25" placeholder="Ingrese No Ext" onkeypress="return valideMultiples(event);">
                            <span class="span_error" id="NoExt_principales_error"></span>
                        </div>

                        <div class="form-group col-lg-3">
                            <label for="CP" class="label-form subtitulo-rosa">CP</label>
                            <input type="text" class="form-control form-control-sm" id="CP" name="CP" maxlength="25" placeholder="Ingrese Codigo Postal">
                            <span class="span_error" id="CP_principales_error"></span>
                        </div>


                        <div class="form-group col-lg-4">
                            <label for="cordY" class="label-form subtitulo-rosa">Coordenada Y</label>
                            <input type="text" class="form-control form-control-sm" placeholder="Ingrese Cooordena +Y" id="cordY" name="coordY" >
                            <span class="span_error" id="cordY_principales_error"></span>
                        </div>

                        <div class="form-group col-lg-4">
                            <label for="cordX" class="label-form subtitulo-rosa">Coordenada X</label>
                            <input type="text" class="form-control form-control-sm" placeholder="Ingrese Cooordena -X"  id="cordX" name="coordX" >
                            <span class="span_error" id="cordX_principales_error"></span>
                        </div>
                        <div class="form-group col-12 col-lg-3">
                            <button type="button" class="btn btn-ssc mt-3 mi_hide" id="buscar_coordenadas_ins">Buscar</button>
                            <button type="button" class="btn btn-ssc mt-3 mi_hide" id="buscar_direccion_ins">Buscar</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" id="map_mapbox"></div>
            </div>
            <div class="form-row mt-5">
                <div class="form-group col-lg-3" id="zonaContent">
                    <label for="zona" class="label-form subtitulo-rosa">Zona</label>
                    <select class="custom-select custom-select-sm" id="zona" name="zona">
                        <option value="NA">SELECCIONE UNA ZONA</option>
                        <?php foreach ($data['datos_prim']['zonas'] as $item) : ?>
                                <option value="<?php echo $item->Zona_Sector; ?>"><?php echo $item->Zona_Sector; ?></option>
                            <?php endforeach ?>
                        </select>
                    <span class="span_error" id="zona_error"></span>
                </div>
                <div class="form-group col-lg-3" id="vectorContent">
                    <label for="vector" class="label-form subtitulo-rosa">Vector</label>
                    <select class="custom-select custom-select-sm" id="vector" name="vector">
                        <option value="NA">SELECCIONE UNA ZONA PRIMERO</option>
                    </select>
                    <span class="span_error" id="vector_error"></span>
                </div>  
            </div>
            <h5 class="titulo-azul">Fuente de Información</h5>
            <div class = 'row'>
                <div class="col-lg-4">
                    <label for="Fuente_info" class="label-form subtitulo-rosa">Fuente de Información</label>
                    <select class="custom-select custom-select-sm" id="Fuente_info" name="Fuente_info">
                        <option value="NA">SELECCIONE UNA FUENTE DE INFORMACIÓN</option>
                        <option value="DETENIDO">DETENIDO</option>
                        <option value="FACEBOOK">FACEBOOK</option>
                        <option value="NUMERO TELEFONICO">NÚMERO TELEFÓNICO</option>
                        <option value="ANONIMO">ANONIMO</option>
                    </select>
                    <span class="span_error" id="Fuente_info_error"></span>
                </div>
                <div class="col-lg-3">
                    <label for="Estatus_Punto" class="label-form subtitulo-rosa">Estatus del Punto</label>
                    <select class="custom-select custom-select-sm" id="Estatus_Punto" name="Estatus_Punto">
                        <option value="NO CONFIRMADO">NO CONFIRMADO</option>
                        <option value="POR CONFIRMAR">POR CONFIRMAR</option>
                        <option value="CONFIRMADO">CONFIRMADO</option>
                        <option value="FUERA DE JURISDICCION">FUERA DE JURISDICCION</option>
                    </select>
                    <span class="span_error" id="Estatus_Punto_principales_error"></span>
                </div>

                <div class="col-lg-5">
                    <label for="Identificador" class="label-form subtitulo-rosa">Identificador</label>
                    <select class="custom-select custom-select-sm" id="Identificador" name="Identificador">
                        <option value="NA">SELECCIONE UN IDENTIFICADOR DEL PUNTO </option>
                        <?php foreach ($data['datos_prim']['identificadores'] as $item) : ?>
                                <option value="<?php echo $item->Identificador; ?>"><?php echo $item->Identificador; ?></option>
                            <?php endforeach ?>
                        </select>
                    <span class="span_error" id="Identificador_error"></span>
                </div>

            </div>

            <div class = "mi_hide" id="Info_detenido">
                <div class="row mt-3">
                    <div class="col-lg-6">
                        <label for="remision" class="label-form subtitulo-rosa">¿El Detenido Tiene alguna remisión capturada en SARAI?</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="Remision_Si_No" id="id_rem_1" value="1" onchange="return changeRemision(event)" >
                            <label class="form-check-label" for="id_rem_1">Si</label>
                        </div>
                    
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="Remision_Si_No" id="id_rem_2" checked value="0" onchange="return changeRemision(event)" >
                            <label class="form-check-label" for="id_rem_2">No</label>
                        </div>
                    </div>
                    <div class="row col-lg-6 mi_hide" id="id_Remision_panel">
                        <div class="col-5">   
                            <h5  class="subtitulo-rosa">Ingrese el numero de remisión: </h5>            
                        </div>
                        <div class="col-7">
                            <input type="text" class="form-control form-control-sm " placeholder="Buscar"  id="id_remision" name="id_remision">
                        </div>
                        <div class="col-12 text-center">
                            <small id="error_remision" class="form-text text-danger"></small>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="form-group col-lg-5">
                        <label for="nombre" class="subtitulo-rosa">Nombre Completo y Alias (Detenido):</label>
                        <input type="text" class="form-control form-control-sm text-uppercase" maxlength="450" id="nombre" name="nombre" placeholder="Ingrese Nombre" onkeypress="return valida(event);">
                    </div>
                    <div class="form-group col-lg-7">
                        <label for="Narrativa" class="label-form subtitulo-rosa">Narrativa del Detenido</label>
                        <textarea name="Narrativa" id="Narrativa" cols="45" rows="3" class="form-control form-control-sm text-uppercase" placeholder="Ingrese Narrativa Del Detenido"></textarea>
                        <span class="span_error" id="Narrativa_error"> </span>
                    </div>
                </div>
            </div>
            <h5 class="titulo-azul mt-3">Información de la Ubicación</h5>
            <div class="row mt-3">
                <div class="form-group col-lg-3">
                    <label for="fecha" class="label-form subtitulo-rosa">Ingrese fecha de obtencion de Información</label>
                    <input type="date" name="fecha_obtencion" id="fecha_obtencion" class="form-control custom-input_dt fecha parrafo-azul fondo-azul" value="<?php echo date('Y-m-d') ?>">
                </div>
                <div class="form-group col-lg-9">
                    <label for="Info_Adicional" class="label-form subtitulo-rosa">Información Adicional</label>
                    <textarea name="Info_Adicional" id="Info_Adicional" cols="45" rows="3" class="form-control form-control-sm text-uppercase" placeholder="Ingrese Información Adicional"></textarea>
                    <span class="span_error" id="Info_Adicional_error"> </span>
                </div>
                <div class="form-group col-lg-12">
                    <label for="Distribuidor" class="subtitulo-rosa">Nombre o Alias del Distribuidor:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="450" id="Distribuidor" name="Distribuidor" placeholder="Ingrese Información del Distribuidor" onkeypress="return valida(event);">
                </div>   
                <div class="form-group col-lg-6">
                    <label for="Grupo_OP" class="subtitulo-rosa">Grupo Delictivo:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="450" id="Grupo_OP" name="Grupo_OP" placeholder="Ingrese Información de Grupo Delictivo" onkeypress="return valida(event);">
                </div> 
                <div class="form-group col-lg-6">
                    <label for="Atendido_Por" class="subtitulo-rosa">Información Atendida por:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="450" id="Atendido_Por" name="Atendido_Por" placeholder="Ingrese Quien Atendio la Información" onkeypress="return valida(event);">
                </div> 
                <div class="form-group col-lg-12">
                    <label for="Enlace_Google" class="subtitulo-rosa">Enlace de Ubicacion:</label>
                    <input type="text" class="form-control form-control-sm" maxlength="450"id="Enlace_Google" name="Enlace_Google" placeholder="INGRESE EL ENLACE DE LA UBICACION" onkeypress="return valida(event);" >
                    <span class="span_error" id="Enlace_Google_error"></span>
                </div>
            </div>
            <div class="modal fade" id="ModalCenterPunto" tabindex="-1" role="dialog" aria-labelledby="myModalExito" aria-hidden="true" >
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <img name="nor" src="<?= base_url; ?>/public/media/images/logo6.png"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                </div>
            </div>
            <div class="row mt-5 mb-5">
                <div class="d-flex justify-content-end col-sm-12" id="id_p">
                    <a class="btn btn-ssc  mr-3" href="<?= base_url; ?>Puntos"><i class="material-icons v-a-middle">arrow_back_ios</i>Volver al inicio</a>
                    <a class="btn btn-ssc " id="btn_nuevo_punto" value='1'>Guardar</a>
                </div>
            </div>
        </form>

    </div>
</div>
<br><br><br>