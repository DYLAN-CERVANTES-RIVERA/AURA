<div class="container-fluid">
    <!--vista del evento  para la edicion de la tab datos principales  -->
    <form id='datos_principales' onsubmit="event.preventDefault()">
        <div class="container">
            <div class="col-12 my-4" id="msg_principales"></div>
            <div class="row mt-3">  
                <div class="form-group col-lg-4 col-sm-6">
                    <span class="subtitulo-rosa">Elemento que Capturo el Evento</span>
                    <input type="text" name="captura_principales" id="captura_principales"  class="form-control custom-input_dt">
                </div> 
                <div class="form-group col-lg-4 col-sm-6">
                    <span class="subtitulo-rosa">Folio AURA</span>
                    <input type="text" name="folio_infra_principales" id="folio_infra_principales" class="form-control custom-input_dt">
                </div>
                <div class="form-group col-lg-3 col-sm-6">
                    <h5 class="subtitulo-rosa">Fecha/Hora de Captura en Sistema</h5>
                    <input type="text" name="fechahora_captura_principales" id="fechahora_captura_principales" class="form-control custom-input_dt">
                </div>
            </div>
            <!--FECHAS Y HORAS -->

        
            <h5 class="titulo-azul" >Datos del Evento</h5>
            <div class="row mt-3">
                <div class="col-lg-5">
                    <label for="fuente_principales" class="subtitulo-rosa">Origen del evento</label>
                    <select class="custom-select custom-select-sm parrafo-azul" id="fuente_principales" name="fuente_principales">
                        <option value="NA">SELECCIONE EL ORIGEN DEL EVENTO</option>
                        <?php foreach ($data['datos_prim']['fuentes'] as $item) : ?>
                                <option value="<?php echo $item->fuente; ?>"><?php echo $item->fuente; ?></option>
                        <?php endforeach ?>
                    </select>
                    <span class="span_error" id="fuente_principales_error"></span>
                </div>
                <div class="col-lg-4">
                    <label class="subtitulo-rosa">Folio 911</label>
                    <input style="color: #002f6c;" type="text" name="911_principales" id="911_principales"  maxlength="50" class="form-control" placeholder="Ingrese el Folio 911" onkeypress="return valideKey(event);">
                    <input type="text"  id="911_principales_oculto"  class="form-control mi_hide" >
                    <span class="span_error" id="911_principalesError"></span>
                </div>
                <div class="col-lg-3">
                    <label class="subtitulo-rosa">Fecha/Hora de Recepción</label>
                    <br>
                    <div class="row justify-content-center">
                        <label class="subtitulo-azul">Ingrese Fecha</label>
                        <input type="date" name="fecha_evento_principales" id="fecha_evento_principales" class="form-control custom-input_dt fecha parrafo-azul fondo-azul" value="<?php echo date('Y-m-d') ?>">
                    </div>
                </div>
                <div class="col-lg-3">
                    <label for="Estatus_Evento" class="label-form subtitulo-rosa">Estatus del Evento</label>
                    <select class="custom-select custom-select-sm" id="Estatus_Evento" name="Estatus_Evento">
                        <option value="POR CONFIRMAR">POR CONFIRMAR</option>
                        <option value="CONFIRMADO">CONFIRMADO</option>
                        <option value="FUERA DE JURISDICCION">FUERA DE JURISDICCION</option>
                        <option value="IMPROCEDENTE">IMPROCEDENTE</option>
                    </select>
                    <span class="span_error" id="Estatus_Evento_principales_error"></span>
                </div>
                <div class="col-lg-6">
                    <label class="label-form subtitulo-rosa">CDI</label>
                    <input type="text" id="cdi" name="cdi" class="form-control form-control-sm" maxlength="50" placeholder="Ingrese el CDI del evento">
                </div>
                <div class="col-lg-3">
                    <br>
                    <div class="row justify-content-center">
                        <label class="subtitulo-azul">Ingrese Hora</label>
                        <input type="time" name="hora_evento_principales" id="hora_evento_principales" class="form-control custom-input_dt hora parrafo-azul fondo-azul col-sm-6" value="<?php echo date('H:i') ?>">
                    </div>
                </div>
            </div><br>
            <!--UBICACION -->

            <h5 class="titulo-azul">Ubicacion del Evento</h5>

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
                            <input type="text" class="form-control form-control-sm text-uppercase" id="Colonia" name="Colonia" placeholder="Ingrese la Colonia del Evento" >
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
                            <input type="text" class="form-control form-control-sm" id="CP" name="CP" maxlength="25" placeholder="Ingrese Codigo Postal" onkeypress="return valideMultiples(event);">
                            <span class="span_error" id="CP_principales_error"></span>
                        </div>


                        <div class="form-group col-lg-4">
                            <label for="cordY" class="label-form subtitulo-rosa">Coordenada Y</label>
                            <input type="text" class="form-control form-control-sm" placeholder="Ingrese Cooordena +Y" id="cordY" name="cordY" >
                            <span class="span_error" id="cordY_principales_error"></span>
                        </div>

                        <div class="form-group col-lg-4">
                            <label for="cordX" class="label-form subtitulo-rosa">Coordenada X</label>
                            <input type="text" class="form-control form-control-sm" placeholder="Ingrese Cooordena -X" id="cordX" name="cordX" >
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
            <!--HECHOS -->

            <h5 class="titulo-azul">Hechos Reportados</h5>
            <div class="alert fondo-azul" role="event" style="display:none" id="alertaEdithecho" >
                Se está realizando la edición de un hecho.
            </div>
            <div class="row mt-3">
                <div class="form-group col-lg-8">
                    <label for="descripcion_hecho" class="subtitulo-rosa">Descripción de los hechos reportados</label>
                    <textarea name="descripcion_hecho" id="descripcion_hecho" cols="45" rows="4" class="form-control form-control-sm text-uppercase" placeholder="Ingrese descripcion del hecho reportado"></textarea>
                    <span class="span_error" id="descripcion_error"> </span>
                </div>
                <div class="col-lg-4 col-sm-4">
                    <div class="col-lg-12 col-sm-12 d-flex justify-content-end">
                        <span class="subtitulo-rosa">Fecha/Hora de los hechos </span>
                    </div>
                    
                    <div class="col-lg-12 col-sm-12 d-flex justify-content-end">
                        <span class="subtitulo-azul col-lg-5 col-sm-4">Ingrese Fecha: </span>
                        <input type="date" name="fecha_recepcion_hechos" id="fecha_recepcion_hechos" class="form-control custom-input_dt fecha  parrafo-azul fondo-azul" value="<?php echo date('Y-m-d') ?>">
                    </div><br>

                    <div class="col-lg-12 col-sm-12 d-flex justify-content-end">
                        <span class="subtitulo-azul col-lg-5 col-sm-5">Ingrese Hora:</span>
                        <input type="time" name="hora_recepcion_hechos" id="hora_recepcion_hechos" class="form-control custom-input_dt hora fondo-azul parrafo-azul col-sm-4" value="<?php echo date('H:i') ?>">
                    </div>
                    <div class="row-lg-12">
                        <span class="span_error" id="recepcion_error"> </span>
                    </div>
                </div>

                <div class="col-lg-10 col-sm-6"> 
                </div>
                <button type="button" class="btn btn-add" onclick="onFormHechosSubmit()">Agregar Hecho</button>
            </div><br>
            <div class="table-responsive">
                <table  class="table table-bordered" id="HechosTable">
                    <thead class="thead-dark">
                        <tr >
                            <th  scope="col">Descripción</th>
                            <th  scope="col">Fecha</th>
                            <th  scope="col">Hora</th>
                            <th  scope="col">Editar/Eliminar</th>
                        </tr>
                    </thead>
                    <tbody id="contarhechos">
                    </tbody>
                </table>
            </div>
            <span class="span_error" id="tabla_hecho_error"></span><br><br><br>
            <!--DELITOS-->

            <h5 class="text-center titulo-azul">Delito o Falta</h5>

            <div class="form-row mt-3">
                <div class="form-group col-lg-6">
                    <label for="delitos_principales" class="label-form subtitulo-rosa">Búsque el Delito o Falta administrativa</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" id="delitos_principales" name="delitos_principales" placeholder="Ingrese el delito o la falta" onkeypress="return valideMultiples(event);">
                    <div class="invalid-feedback" id="delitos_principales-invalid">
                        La descripción es requerida.
                    </div>
                    <span class="span_error" id="delito_principales_error"></span>
                </div>
                <div class="form-group col-lg-4">
                    <label for="tipo_delito" class="label-form subtitulo-rosa">Giro</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" placeholder="Ingrese Giro" id="tipo_delito" name="tipo_delito" onkeypress="return valideMultiples(event);">
                    <span class="span_error" id="tipo_delito_principales_error"></span>
                </div>

                <div class="form-group col-lg-5 mi_hide" id="otrofg">
                    <label for="delitos_otro" class="label-form subtitulo-rosa">Especifíque</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" id="delitos_otro" name="delitos_otro">
                    <div class="invalid-feedback" id="delitos_otro-invalid">
                        La descripción es requerida.
                    </div>
                </div>
                <div class="col-lg-10 col-sm-6"></div>
                <button type="button" class="btn btn-add button-movil-plus" onclick="onFormOtroSubmit()">Agregar Delito</button>
                
            </div><br>

            <div class="table-responsive ">
                <table class="table table-bordered" id="faltasDelitosTable">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Descripción</th>
                            <th scope="col">Giro</th>
                            <th scope="col">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody id="contardelitos">
                    </tbody>
                </table>
            </div>
            <span class="span_error" id="tabla_error"></span>

            <!--VIOLENCIA -->

            <div class="row mt-3" id="ext_1">

                <div class="form-group col-lg-3">
                    <label for="violencia_principales1" class="label-form subtitulo-rosa">Con/Sin violencia:</label>
                    <select class="custom-select custom-select-sm" id="violencia_principales1" name="violencia_principales1">
                        <option value="NA">SELECCIONE OPCION</option>
                        <option value="CON VIOLENCIA">CON VIOLENCIA</option>
                        <option value="SIN VIOLENCIA">SIN VIOLENCIA</option>
                    </select>
                    <span class="span_error" id="violencia_principales1_error"></span>
                </div>

                <div class="row mi_hide" id="form_violencia">
                    <div class="form-group col-lg-9">
                        <label for="violencia_principales" class="label-form subtitulo-rosa">Tipo de violencia</label>
                        <select class="custom-select custom-select-sm" id="violencia_principales" name="violencia_principales">
                            <option value="NA">SELECCIONE EL TIPO DE VIOLENCIA</option>
                            <?php foreach ($data['datos_prim']['violencia'] as $item) : ?>
                                <option value="<?php echo $item->Tipo_Violencia; ?>"><?php echo $item->Tipo_Violencia; ?></option>
                            <?php endforeach ?>
                        </select>
                        <span class="span_error" id="violencia_principales_error"></span>
                    </div>
                </div>
                <div class="row mi_hide" id="form_sinviolencia">
                    <div class="form-group col-lg-9">
                        <label for="violencia_principales" class="label-form subtitulo-rosa">Tipo de violencia</label>
                        <select class="custom-select custom-select-sm" id="sviolencia_principales" name="sviolencia_principales">
                            <option value="NA">SELECCIONE EL TIPO DE VIOLENCIA</option>
                            <?php foreach ($data['datos_prim']['sinviolencia'] as $item) : ?>
                                <option value="<?php echo $item->Tipo_SViolencia; ?>"><?php echo $item->Tipo_SViolencia; ?></option>
                            <?php endforeach ?>
                        </select>
                        <span class="span_error" id="sviolencia_principales_error"></span>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="form-group col-lg-6">
                    <label for="Unidad_Primer_R" class="subtitulo-rosa">Unidad Primer Respondiente</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" id="Unidad_Primer_R" name="Unidad_Primer_R" placeholder="Ingrese Unidad Primer Respondiente">
                </div>
                <div class="form-group col-lg-6">
                    <label for="Informacion_Primer_R" class="subtitulo-rosa">Información Unidad Primer Respondiente</label>
                    <textarea name="Informacion_Primer_R" id="Informacion_Primer_R" cols="30" rows="3" class="form-control form-control-sm text-uppercase" placeholder="Ingrese Información Unidad Primer Respondiente(Elementos)" onkeypress="return valideMultiples(event);"></textarea>
                </div>
                <div class="form-group col-lg-12">
                    <label for="Acciones" class="subtitulo-rosa">Acciones IO</label>
                    <textarea   name="Acciones" id="Acciones" cols="45" rows="4" class="form-control form-control-sm text-uppercase" placeholder="Ingrese Acciones por Parte de IO" onkeypress="return valideMultiples(event);"></textarea>
                </div>
            </div>
            <h5 class="titulo-azul">Información del Turno (Inteligencía Operativa)</h5>
            <div class="row mt-3">
                <div class="form-group col-lg-4">
                    <label for="Turno" class="subtitulo-rosa">Turno (Inteligencía Operativa)</label>
                    <!--<input type="text" class="form-control form-control-sm text-uppercase" id="Turno" name="Turno" maxLength = 20 placeholder="Ingrese Turno" onkeypress="return valideMultiples(event);">-->
                    <select class="custom-select custom-select-sm parrafo-azul" id="Turno" name="Turno">
                        <option value="SD">SELECCIONE TURNO (IO)</option>
                        <option value="1">TURNO 1 DIURNO(IO)</option>
                        <option value="2">TURNO 2 NOCTURNO(IO)</option>
                        <option value="3">TURNO 3 DIURNO(IO)</option>
                        <option value="4">TURNO 4 NOCTURNO(IO)</option>
                    </select>
                     <!--catalogo-->
                </div>
                <div class="form-group col-lg-5">
                    <label for="Responsable_Turno" class="subtitulo-rosa">Responsable de Turno (Inteligencía Operativa)</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" id="Responsable_Turno" name="Responsable_Turno" maxLength = 30 placeholder="Ingrese el Responsable de Turno (Indicativo)" onkeypress="return valideMultiples(event);"> 
                </div>
                <div class="form-group col-lg-3">
                    <label for="Semana" class="subtitulo-rosa">Semana</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" id="Semana" name="Semana" maxLength = 3 placeholder="Ingrese Semana"> 
                </div>
            </div>
                              
            <!--VEHICULOS INVOLUCRADOS-->

            <h5 class="titulo-azul">Vehículos Involucrados</h5>
            <div class="row mt-3">
                <div class="form-group col-lg-4">
                    <label for="identificacionVI" class="label-form subtitulo-rosa">¿Se tiene Identificación de los Vehículos en el evento?</label>
                </div>  
                <div class="form-group col-lg-4">
                    <input type="radio" id="Identificacion_VI1" name="Identificacion_VI" value="1" onchange="return changeIdentificacionVI(event)">
                    <label for="id_Identificacion_VI">Si</label>
                </div>
                <div class="form-group col-lg-4">
                    <input type="radio" id="Identificacion_VI2" name="Identificacion_VI" value="0" onchange="return changeIdentificacionVI(event)" checked >
                    <label for="id_Identificacion_VI2">No</label>
                </div>
            </div>
        </div> 
        <div class="mi_hide" id="div_vehInvolucrados">
            <div class="container">
                <h5 class="titulo-rosa">Ingrese Información de los Vehículos Involucrados</h5>
                <div class="alert fondo-azul" role="event" style="display:none" id="alertaEditVehiculos" >
                    Se está realizando la edición a un Vehículo.
                </div>
                <div class="form-row mt-3">
                    <div class="col-lg-6 mi_hide">
                        <input type="text" name="Id_Vehiculo" id="Id_Vehiculo" value="SD">
                    </div>
                    <div class="form-group col-lg-4">
                        <label for="Tipo_Vehiculo" class="label-form  subtitulo-rosa">Tipo de Vehículo</label>
                        <select class="custom-select custom-select-sm parrafo-azul" id="Tipo_Vehiculo" name="Tipo_Vehiculo">
                            <option value="NA">SELECCIONE UN TIPO DE VEHÍCULO</option>
                            <?php foreach ($data['datos_prim']['tipo_vehiculos'] as $item) : ?>
                                <option value="<?php echo $item->Tipo; ?>"><?php echo $item->Tipo; ?></option>
                            <?php endforeach ?>
                        </select>
                        <span class="span_error" id="Tipo_Vehiculo_principales_error"></span>
                    </div>

                    <div class="form-group col-lg-4">
                        <label for="Marca" class="label-form subtitulo-rosa">Marca</label>
                        <input type="text" class="form-control form-control-sm text-uppercase" placeholder="Ingrese marca del vehiculo" id="Marca" name="Marca">
                        <span class="span_error" id="Marca_principales_error"></span>
                    </div>

                    <div class="form-group col-lg-4">
                        <label for="Submarca" class="label-form subtitulo-rosa">Submarca</label>
                        <input type="text" class="form-control form-control-sm text-uppercase" placeholder="Ingrese submarca del vehiculo" id="Submarca" name="Submarca">
                        <span class="span_error" id="Submarca_principales_error"></span>
                    </div>

                    <div class="form-group col-lg-3 col-sm-6">
                        <label for="Modelo" class="label-form subtitulo-rosa">Modelo</label>
                        <select class="custom-select custom-select-sm parrafo-azul" id="Modelo" name="Modelo">
                            <option value="SD">SELECCIONE TIPO DE MODELO</option>
                            <option value="NUEVO">NUEVO</option>
                            <option value="SEMINUEVO">SEMINUEVO</option>
                            <option value="VIEJO">VIEJO</option>
                        </select>
                        <span class="span_error" id="Modelo_principales_error"></span>
                    </div>
                    
                    <div class="form-group col-lg-9 col-sm-6">
                        <label for="Placa_Vehiculo" class="label-form subtitulo-rosa">Placas del Vehículo</label>
                        <input type="text" class="form-control form-control-sm text-uppercase" placeholder="Ingrese placas del vehiculo" id="Placa_Vehiculo" name="Placa_Vehiculo" onkeypress="return valideMultiples(event);">
                        <span class="span_error" id="Placa_Vehiculo_principales_error"></span>
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="Color" class="label-form subtitulo-rosa">Color</label>
                        <input type="text" class="form-control form-control-sm text-uppercase" placeholder="Ingrese color del vehiculo" id="Color" name="Color" onkeypress="return valideMultiples(event);">
                        <span class="span_error" id="Color_principales_error"></span><br>
                        
                    </div>

                    <div class="form-group col-lg-9">
                        <label for="Descripcion_gral" class="label-form subtitulo-rosa">Descripción del Vehículo</label>
                        <textarea name="Descripcion_gral" id="Descripcion_gral" cols="45" rows="3" placeholder="Ingrese descripcion del vehiculo" class="form-control form-control-sm text-uppercase" onkeypress="return valideMultiples(event);"></textarea>
                        <span class="span_error" id="Descripcion_gral_error"> </span>
                    </div>
                    <div class="col-lg-4 col-sm-6"> 
                        <label for="Tipo_Veh_Involucrado" class="label-form subtitulo-rosa">Tipo de Vehículo Involucrado</label>
                        <select class="custom-select custom-select-sm parrafo-azul" id="Tipo_Veh_Involucrado" name="Tipo_Veh_Involucrado">
                            <option value="SD">SELECCIONE TIPO DE VEHICULO INVOLUCRADO</option>
                            <option value="PARTE AFECTADA">PARTE AFECTADA</option>
                            <option value="RESPONSABLE">RESPONSABLE</option>
                        </select>
                        <span class="span_error" id="Tipo_Veh_Involucrado_error"></span>
                    </div>
                    <div class="col-lg-1 col-sm-6 "></div>
                    <div class="col-lg-4 col-sm-6 ">
                        <div class="col-lg-12 col-sm-6  <?= ($_SESSION['userdataSIC']->Seguimientos[1])?'':'mi_hide';?>">
                            <label for="Estado_Veh" class="label-form subtitulo-rosa">Estado en el Evento</label>
                            <select class="custom-select custom-select-sm parrafo-azul" id="Estado_Veh" name="Estado_Veh">
                                <option value="NO CORROBORADO">NO CORROBORADO</option>
                                <option value="CORROBORADO">CORROBORADO</option>
                                <option value="DESCARTADO">DESCARTADO</option>
                            </select>
                            <span class="span_error" id="Estado_Veh_error"></span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6" align="right">
                        <br>
                        <button type="button" class="btn btn-add" onclick="onFormVehiculoSubmit()"> Agregar Vehículo</button>
                    </div>
                </div><br>
            </div>
            <div class="container-fluid">
                <div class="form-row row"> 
                    <div class="form-group col-lg-12">                   
                        <div class="table-responsive">
                            <table class="table table-bordered" id="VehiculoTable" style="text-align:center">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Id de Vehículo</th>
                                        <th scope="col">Tipo de vehículo</th>
                                        <th scope="col">Marca</th>
                                        <th scope="col">Submarca</th>
                                        <th scope="col">Modelo</th>
                                        <th scope="col">Placas</th>
                                        <th scope="col">Color</th>
                                        <th scope="col">Descripción del Vehículo</th>
                                        <th scope="col">Foto</th>
                                        <th scope="col">Tipo de Vehículo Involucrado</th>
                                        <th scope="col">Estado de Vehículo</th>
                                        <th scope="col">Capturo</th>
                                        <th scope="col">Editar/Eliminar</th>
                                        <th scope="col" style="display:none;"></th>
                                    </tr>
                                </thead>
                                <tbody id="contarVehiculos">
                                </tbody>
                            </table>
                        </div><br>

                        <span class="span_error" id="tabla_Vehiculo_error"></span>
                    </div>
                </div>
            </div>
        </div>
        <!--INVOLUCRADOS-->
        <div class="container">
            <h5 class="titulo-azul">Personas Involucradas</h5>
            <div class="row mt-3">
                <div class="form-group col-lg-4">
                    <label for="identificacion" class="label-form subtitulo-rosa">¿Se tiene Identificación de Involucrados en el evento?</label>
                </div>  
                <div class="form-group col-lg-4">
                    <input type="radio" id="Identificacion_I1" name="Identificacion_I" value="1" onchange="return changeIdentificacionI(event)">
                    <label for="id_Identificacion_I">Si</label>
                </div>
                <div class="form-group col-lg-4">
                    <input type="radio" id="Identificacion_I2" name="Identificacion_I" value="0" onchange="return changeIdentificacionI(event)" checked >
                    <label for="id_Identificacion_I2">No</label>
                </div>
            </div>
        </div>    
        <div class="mi_hide" id="div_responsables">
            <div class="container">
                <h5 class="titulo-rosa">Ingrese Información de los involucrados</h5>
                <div class="alert fondo-azul" role="event" style="display:none" id="alertaEditProbales" >
                    Se está realizando la edición a una Persona Involucrada.
                </div>
                <div class="form-row mt-3">

                    <div class="col-lg-6 mi_hide">
                        <input type="text" name="Id_Responsable" id="Id_Responsable" value="SD" >
                    </div>
                    <div class="form-group col-lg-4">
                        <label for="Sexo" class="label-form subtitulo-rosa">Sexo</label>
                        <select class="custom-select custom-select-sm" id="Sexo" name="Sexo">
                            <option value="SD">SELECCIONE SEXO</option>
                            <option value="MASCULINO">MASCULINO</option>
                            <option value="FEMENINO">FEMENINO</option>
                        </select>
                        <span class="span_error" id="Sexo_principales_error"></span>
                    </div>

                    <div class="form-group col-lg-4">
                        <label for="Rango_Edad" class="label-form subtitulo-rosa">Rango de edad</label>
                        <select class="custom-select custom-select-sm" id="Rango_Edad" name="Rango_Edad">
                            <option value="SD">SELECCIONE UN RANGO DE EDAD</option>
                            <?php foreach ($data['datos_prim']['edades'] as $item) : ?>
                                <option value="<?php echo $item->Rango; ?>"><?php echo $item->Rango; ?></option>
                            <?php endforeach ?>
                        </select>
                        <span class="span_error" id="Rango_Edad_principales_error"></span>
                    </div>

                    <div class="form-group col-lg-4">
                        <label for="Complexion" class="label-form subtitulo-rosa">Complexion</label>
                        <select class="custom-select custom-select-sm" id="Complexion" name="Complexion">
                            <option value="SD">SELECCIONE UNA COMPLEXION</option>
                            <option value="DELGADA">DELGADA</option>
                            <option value="MEDIA">MEDIA</option>
                            <option value="ROBUSTA">ROBUSTA</option>
                            <option value="OBESA">OBESA</option>
                        </select>
                        <span class="span_error" id="Complexion_principales_error"></span>
                    </div>

                    <div class="form-group col-lg-12">
                        <label for="Descripcion_gral_per" class="label-form subtitulo-rosa">Descripción de la Persona Involucrada</label>
                        <textarea name="Descripcion_gral_per" id="Descripcion_gral_per" cols="45" rows="3" class="form-control form-control-sm text-uppercase" placeholder="Ingrese descripcion del involucrado" onkeypress="return valideMultiples(event);"></textarea>
                        <span class="span_error" id="Descripcion_gral_per_error"> </span>
                    </div>

                    <div class="form-group col-lg-4 ">
                        <label for="arma_principales_per" class="label-form subtitulo-rosa">Tipo de arma</label>
                        <select class="custom-select custom-select-sm" id="arma_principales_per" name="arma_principales_per">
                            <option id="cons" value=" ">SELECCIONE EL TIPO DE ARMA</option>
                            <?php foreach ($data['datos_prim']['armas'] as $item) : ?>
                                    <option value="<?php echo $item->Tipo_Arma; ?>"><?php echo $item->Tipo_Arma; ?></option>
                                <?php endforeach ?>
                        </select>
                        <span class="span_error" id="arma_principales_per_error"></span>
                    </div>
                    <div class="col-lg-4 col-sm-6 ">
                        <div class="col-lg-12 col-sm-6  <?= ($_SESSION['userdataSIC']->Seguimientos[1])?'':'mi_hide';?>">
                            <label for="Estado_Res" class="label-form subtitulo-rosa">Estado</label>
                            <select class="custom-select custom-select-sm parrafo-azul" id="Estado_Res" name="Estado_Res">
                                <option value="NO CORROBORADO">NO CORROBORADO</option>
                                <option value="CORROBORADO">CORROBORADO</option>
                                <option value="DESCARTADO">DESCARTADO</option>
                            </select>
                            <span class="span_error" id="Estado_Res_error"></span>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-8" align="right">
                        <br>
                        <button type="button" class="btn btn-add button-movil-plus" onclick="onFormPRSubmit()">Agregar Involucrado</button>
                    </div>
                </div><br>
            </div>
        
            <div class="container-fluid">
                <div class="form-row row"> 
                    <div class="form-group col-lg-12">

                        <div class="table-responsive">
                            <table class="table table-bordered" id="PersonaTable" style="text-align:center">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Id</th>
                                        <th scope="col">Sexo</th>
                                        <th scope="col">Rango de edad</th>
                                        <th scope="col">Complexion</th>
                                        <th scope="col">Descripción de la Persona Involucrada</th>
                                        <th scope="col">Foto de la Persona Involucrada</th>
                                        <th scope="col">Tipo de Arma</th>
                                        <th scope="col">Estado de Involucrado</th>
                                        <th scope="col">Capturo</th>
                                        <th scope="col">Editar/Eliminar</th>
                                        <th scope="col" style="display:none;"></th>
                                    </tr>
                                </thead>
                                <tbody id="contarRes">
                                </tbody>
                            </table>
                        </div><br>
                        <span class="span_error" id="tabla_probales_responsables_error"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <h5 class="titulo-azul">Detención del Evento</h5>
            <div class="row mt-3">
                <div class="form-group col-lg-4">
                    <label for="Detencion" class="label-form subtitulo-rosa">¿Se Realizo Alguna Detención del Evento? </label>
                </div>  
                <div class="form-group col-lg-4">
                    <input type="radio" id="Detencion1" name="Detencion" value="1" onchange="return changeDetencion(event)">
                    <label for="Detencion1">Si</label>
                </div>
                <div class="form-group col-lg-4">
                    <input type="radio" id="Detencion2" name="Detencion" value="0" onchange="return changeDetencion(event)" checked >
                    <label for="Detencion2">No</label>
                </div>
            </div>   
            <div class="mi_hide"  id="div_detencion">

                <h5 class="titulo-rosa">Ingrese Información de la Detención</h5>
                <div class="row mt-3">
                    <div class="form-group col-lg-4">
                        <label for="Detencion_Por_Info_Io" class="label-form subtitulo-rosa">¿Detención por Información de IO? </label>
                    </div>  
                    <div class="form-group col-lg-2">
                        <input type="radio" id="Detencion_Por_Info_Io1" name="Detencion_Por_Info_Io" value="1" >
                        <label for="Detencion_Por_Info_Io1">Si</label>
                    </div>
                    <div class="form-group col-lg-2">
                        <input type="radio" id="Detencion_Por_Info_Io2" name="Detencion_Por_Info_Io" value="0"  checked >
                        <label for="Detencion_Por_Info_Io2">No</label>
                    </div>
                </div> 
                <div class="row mt-3 ">
                    <div class="form-group col-lg-8 col-sm-6">
                        <h5 class="subtitulo-rosa">Compañia</h5>
                        <input type="text" class="form-control form-control-sm text-uppercase" id="Compañia" name="Compañia" placeholder="Ingrese Compañia a cargo de la Detencion" onkeypress="return valideMultiples(event);">
                    </div>
                    <div class="form-group col-lg-4 col-sm-6">
                        <h5 class="subtitulo-rosa">Fecha de Detención</h5>
                        <input type="date" name="Fecha_Detencion" id="Fecha_Detencion" class="form-control custom-input_dt fecha parrafo-azul fondo-azul" value="<?php echo date('Y-m-d') ?>">
                    </div>
                    <div class="form-group col-lg-12 col-sm-6">
                        <h5 class="subtitulo-rosa">Elementos que Realizaron la Detención</h5>
                        <textarea name="Elementos_Realizan_D" id="Elementos_Realizan_D" rows="2" class="form-control form-control-sm text-uppercase" placeholder="Ingrese Elementos que realizaron la detencion separados por ','" ></textarea>  
                    </div>
                    <div class="form-group col-lg-12 col-sm-6">
                        <h5 class="subtitulo-rosa">Nombre de los Detenidos</h5>
                        <textarea name="Nombres_Detenidos" id="Nombres_Detenidos" cols="55" rows="2" class="form-control form-control-sm text-uppercase" placeholder="Ingrese los Nombres de los Detenidos separados por ','" ></textarea>  
                    </div>
                </div> 
                <div class="row mt-3">
                    
                    <div class="form-group col-lg-4">
                        <p class="label-form ml-2 parrafo-azul"> Ubicación de Detención en: </p>  
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="puebla_ubicacion" name="ubicacion_puebla" class="custom-control-input" value="PUEBLA" checked>
                            <label class="custom-control-label label-form parrafo-azul" for="puebla_ubicacion">Puebla</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="foraneo_ubicacion" name="ubicacion_puebla" class="custom-control-input" value="FORANEO">
                            <label class="custom-control-label label-form parrafo-azul" for="foraneo_ubicacion">Foraneo</label>
                        </div>
                    </div>
                    <div class="row form-group col-lg-8 mi_hide" id="Es_Foraneo">
                        <div class="form-group col-lg-6">
                            <label for="Estado" class="label-form parrafo-azul">Estado:</label>
                            <select class="custom-select custom-select-sm" id="Estado" name="Estado">
                            <option value="SD" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            </select>
                            <span class="span_error" id="Estado_error"></span>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="Municipio" class="label-form parrafo-azul">Municipio:</label>
                            <input type="text" class="form-control form-control-sm text-uppercase" id="Municipio" name="Municipio" >
                            <span class="span_error" id="Municipio_error"></span>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="form-group col-lg-1 mi_hide" >
                        <input type="text" class="form-control form-control-sm " id="Id_Ubicacion_Detencion" name="Id_Ubicacion_Detencion" value="SD">
                    </div>
                    <div class="col-lg-6">
                        <div class="form-row mt-3">
                            <p class="label-form ml-2 parrafo-azul"> Buscar por: </p>

                            <div class="form-group col-lg-12">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="porDireccion_Det" name="busqueda" class="custom-control-input" value="0">
                                    <label class="custom-control-label label-form parrafo-azul" for="porDireccion_Det">Dirección</label>
                                </div>

                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="porCoordenadas_Det" name="busqueda" class="custom-control-input" value="1">
                                    <label class="custom-control-label label-form parrafo-azul" for="porCoordenadas_Det">Coordenadas</label>
                                </div>
                            </div>

                            <div class="form-group col-lg-6">
                                <label for="Colonia_Det" class="label-form subtitulo-rosa">Colonia</label>
                                <input type="text" class="form-control form-control-sm text-uppercase" id="Colonia_Det" name="Colonia_Det" placeholder = "Ingrese la Colonia Detencion" onkeypress="return valideMultiples(event);">
                                <span class="span_error" id="Colonia_Det_principales_error"></span>
                            </div>

                            <div class="form-group col-lg-6">
                                <label for="Calle_Det" class="label-form subtitulo-rosa">Calle</label>
                                <input type="text" class="form-control form-control-sm text-uppercase" id="Calle_Det" name="Calle_Det" placeholder="Ingrese la Calle 1 Detencion" onkeypress="return valideMultiples(event);">
                                <span class="span_error" id="Calle_Det_principales_error"></span>

                            </div>

                            <div class="form-group col-lg-6">
                                <label for="Calle_Det2" class="label-form subtitulo-rosa">Calle 2</label>
                                <input type="text" class="form-control form-control-sm text-uppercase" id="Calle_Det2" name="Calle_Det2" placeholder="Ingrese la Calle 2 Detencion" onkeypress="return valideMultiples(event);">
                                <span class="span_error" id="Calle_Det2_principales_error"></span>
                            </div>

                            <div class="form-group col-lg-3">
                                <label for="no_Ext_Det" class="label-form subtitulo-rosa">No. Ext.</label>
                                <input type="text" class="form-control form-control-sm" id="no_Ext_Det" name="no_Ext_Det" placeholder="Ingrese No Ext" onkeypress="return valideMultiples(event);">
                                <span class="span_error" id="NoExt_Det_principales_error"></span>
                            </div>

                            <div class="form-group col-lg-3">
                                <label for="no_Int_Det" class="label-form subtitulo-rosa">No. Int.</label>
                                <input type="text" class="form-control form-control-sm" id="no_Int_Det" name="no_Int_Det" placeholder="Ingrese No Int" onkeypress="return valideMultiples(event);">
                                <span class="span_error" id="NoInt_Det_principales_error"></span>
                            </div>

                            <div class="form-group col-lg-3">
                                <label for="CP_Det" class="label-form subtitulo-rosa">CP</label>
                                <input type="text" class="form-control form-control-sm" id="CP_Det" name="CP_Det" placeholder="Ingrese Codigo Postal">
                                <span class="span_error" id="CP_Det_principales_error"></span>
                            </div>


                            <div class="form-group col-lg-4">
                                <label for="cordY_Det" class="label-form subtitulo-rosa">Coordenada Y</label>
                                <input type="text" class="form-control form-control-sm" placeholder="Ingrese Cooordena +Y" id="cordY_Det" name="cordY_Det" >
                                <span class="span_error" id="cordY_Det_principales_error"></span>
                            </div>

                            <div class="form-group col-lg-4">
                                <label for="cordX_Det" class="label-form subtitulo-rosa">Coordenada X</label>
                                <input type="text" class="form-control form-control-sm" placeholder="Ingrese Cooordena -X" id="cordX_Det" name="cordX_Det" >
                                <span class="span_error" id="cordX_Det_principales_error"></span>
                            </div>
                            <div class="form-group col-12 col-lg-3">
                                <button type="button" class="btn btn-ssc mt-3 mi_hide" id="buscar_coordenadas_Det">Buscar</button>
                                <button type="button" class="btn btn-ssc mt-3 mi_hide" id="buscar_direccion_Det">Buscar</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6" id="map_mapbox_Det"></div>
                </div>
                <div class="form-row mt-3">
                    <div class="form-group col-lg-12">
                        <label for="Observacion_Ubicacion_Det" class="subtitulo-rosa">Observación de la Ubicación:</label>
                        <textarea name="Observacion_Ubicacion_Det" id="Observacion_Ubicacion_Det" cols="45" rows="7" class="form-control form-control-sm text-uppercase" placeholder="Ingrese Observacion de la Ubicacion"onkeypress="return valideMultiples(event);"></textarea>  
                        <span class="span_error" id="Observacion_Ubicacion_Det_error"></span>
                    </div>
                    <div class="form-group col-lg-12 col-sm-6">
                        <label for="Link_Ubicacion_Det" class="subtitulo-rosa">Link de Ubicación:</label>
                        <input type="text" class="form-control form-control-sm" maxlength="100"id="Link_Ubicacion_Det" name="Link_Ubicacion_Det" placeholder="Ingrese el link de la ubicacion">
                        <span class="span_error" id="Link_Ubicacion_Det_error"></span>
                    </div>
                </div>
            </div>
            <!--SEGUIMIENTO -->
            <h5 class="titulo-azul">Seguimiento del Evento</h5>
            <div class="row m-3" >
                <div class="form-row mt-3">
                    <div class="form-group col-lg-12" >
                        <span class="span_rem mt-2">Estatus del Seguimiento del Evento</span>
                    </div>
                    <div class="form-group col-lg-12" >
                        <input type="radio" id="Habilitado_question1" name="Habilitado_question" value="siHabilitado">
                        <label for="Si">HABILITADO</label>
                        <input type="radio" id="Habilitado_question2" name="Habilitado_question" value="noHabilitado">
                        <label for="No">DESHABILITADO</label>
                    </div>
                </div>
                <div class="row mi_hide" id="form_activacion">
                    <div class="form-row mt-3">
                        <div class="form-group col-lg-12">
                            <span class="span_rem ">Fecha y Hora de la Activación del seguimiento del Evento</span>
                        </div>
                        <div class="form-group col-lg-12" >
                            <input type="text" name="fechahora_activacion_principales" id="fechahora_activacion_principales" class="form-control custom-input_dt">
                            <br>
                            <input type="text" name="quienhabilito" id="quienhabilito" class="form-control custom-input_dt"  readOnly>
                            <input type="text" name="statusAntes" id="statusAntes" class="form-control custom-input_dt mi_hide">
                        </div>
                    </div>
                
                    <div class="row-lg-12">
                        <span class="span_error" id="activacion_error"></span>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-5 <?= ($_SESSION['userdataSIC']->Seguimientos[0])?'':'mi_hide';?>">
                <div class="form-group col-lg-12">
                    <span class="span_rem mt-2">Asignación de elemento para seguimiento: </span>
                </div>
                <div class="form-group col-lg-12">
                    <select class="custom-select custom-select-sm" id="clave_asignacion_seguimiento" name="clave_asignacion_seguimiento">
                            <option value="SIN ASIGNAR" selected>SELECCIONE UNA OPCION</option>
                            <?php foreach ($data['datos_prim']['claves'] as $item) : ?>
                                    <option value="<?php echo $item->clave; ?>"><?php echo $item->clave; ?></option>
                            <?php endforeach ?>
                        </select>
                        <span class="span_error" id="clave_asignacion_seguimiento_error"></span>
                    </div>
            </div>
            
            <div class="row mt-2 mi_hide">
            <h5 class="titulo-azul">Información recopilada por IO del Evento</h5>
                <div class="form-group col-lg-12">
                    <div class="input-group col-sm-12 mt-4 mb-5">
                        <div class="custom-file">
                            <input type="file" name="filePDFEvento" onchange="uploadFilePDF(event)"class="bs-custom-file-input" id="filePDFEvento" accept=".pdf">
                            <label class="custom-file-label" for="filePDFEvento">Agrega archivo PDF</label>
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
            <div class="modal fade" id="ModalCenterFoto" tabindex="-1" role="dialog" aria-labelledby="myModalExito" aria-hidden="true" >
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <img name="nor" src="<?= base_url; ?>/public/media/images/logo6.png"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                </div>
            </div>
            <div class="row mt-5 mb-5">
                <div class="d-flex justify-content-end col-sm-12" id="id_p">
                    <a class="btn btn-sm btn-ssc mr-3" href="<?= base_url; ?>GestorCasos"><i class="material-icons v-a-middle">arrow_back_ios</i>Volver al inicio</a>
                    <a class="btn btn-sm btn-ssc" id="btn_principal" value='1'>Guardar Datos principales</a>
                </div>
            </div>
            <div class="row mi_hide" id="form_contenedor">
                <input type="text" name="actualizahabilito" id="actualizahabilito" class="form-control custom-input_dt"  value="<?php echo strtoupper($_SESSION['userdataSIC']->User_Name)?>" readOnly>
                <input type="text" name="actualizaVP" id="actualizaVP" class="form-control custom-input_dt"  value="<?php echo $_SESSION['userdataSIC']->User_Name?>" readOnly>
                <input type="text" name="Area" id="Area" class="form-control custom-input_dt"  value="<?php echo $_SESSION['userdataSIC']->Area?>" readOnly>
                <input type="text" name="TipoUsuario" id="TipoUsuario" class="form-control custom-input_dt"  value="<?php echo $_SESSION['userdataSIC']->Seguimientos[1]?>" readOnly>
            </div>
            
            <h5 class="titulo-azul" >Evento Activo o Inactivo</h5>
            <div class="form-row mt-3 justify-content-center">
                <div class="custom-control custom-control-inline">
                    <input type="checkbox" id="cancelar_evento" name="cancelar_evento" class="custom-control-input">
                    <label class="custom-control-label label-form parrafo-azul" for="cancelar_evento">Evento Activo</label>
                </div>
                <div class="form-group col-lg-3">
                    <button type="button" class="btn btn-primary button-movil-plus" onclick="changeStatus()">Guarda Estatus</button>
                </div>
            </div> 
        </div>
                               
    </form>

</div>