<div class="container-fluid">
    <!--vista para generar datos de vehiculos en el seguimiento -->
    <form id='datos_vehiculos' onsubmit="event.preventDefault()">
        <div class="container">
            <!--PARA MOSTRAR SI SE CARGARON LOS DATOS O UBO UN ERROR-->
            <div class="col-12 my-4" id="msg_principales_vehiculos"></div>
            <h5 class="titulo-azul">Vehiculos Identificados Asociados</h5>
            <div class="alert fondo-azul" role="event" style="display:none" id="alertaEditVehiculo">
                Se está realizando la edición a un vehiculo.
            </div>
            <div class="row mt-2 mi_hide">
                <div class="col-lg-6 col-sm-6">
                    <h5 class="subtitulo-rosa">Id de Seguimiento Asignado:</h5>
                    <input type="text" name="id_seguimiento_vehiculo" id="id_seguimiento_vehiculo" class="form-control custom-input_dt">
                    <span class="span_error" id="id_seguimiento_vehiculo_error"></span> 
                </div>
                <div class="col-lg-6 col-sm-6 ">
                    <h5  class="subtitulo-rosa">Elemento que Captura dato Vehiculo: </h5>
                    <input  style="font-size: 15px;color: #0F2145;"type="text" name="captura_dato_vehiculo" id="captura_dato_vehiculo" value="<?php echo $_SESSION['userdataSIC']->User_Name?>" class="form-control custom-input_dt text-uppercase" disabled="true">
                    <span class="span_error" id="captura_dato_vehiculo_error"></span>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-6">
                    <h5  class="subtitulo-rosa">Busqueda de Placa</h5>
                </div>
                <div class="col-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="SaraioSic" id="id_dato_1" checked value="1" onchange="return changeProcedenciaBusqueda(event)" >
                        <label class="form-check-label" for="id_dato_1">SARAI</label>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="SaraioSic" id="id_dato_2"  value="0" onchange="return changeProcedenciaBusqueda(event)" >
                        <label class="form-check-label" for="id_dato_2">AURA</label>
                    </div>
                </div>
                <div class="col-12 text-center">
                    <small id="error_SaraioSic" class="form-text text-danger"></small>
                </div>
            </div>
            <div class="row mt-3" id="id_vehiculo_panel">
                <div class="col-4">   
                    <h5  class="subtitulo-rosa">Ingrese la placa y seleccione id del vehiculo :</h5>            
                </div>
                <div class="col-8">
                    <input type="text" class="form-control form-control-sm text-uppercase" placeholder="Buscar Placa en Sarai"  id="id_vehiculo_sarai" name="id_vehiculo_sarai"  onkeypress="return validePlaca(event);" >
                </div>
                <div class="col-12 text-center">
                    <small id="error_vehiculo_sarai" class="form-text text-danger"></small>
                </div>
            </div>
            <div class="row mt-3 mi_hide" id="id_vehiculo_panel2">
                <div class="col-4">   
                    <h5  class="subtitulo-rosa">Ingrese la placa y seleccione id del vehiculo :</h5>            
                </div>
                <div class="col-8">
                    <input type="text" class="form-control form-control-sm text-uppercase" placeholder="Buscar Placa en AURA"  id="id_vehiculo_sic" name="id_vehiculo_sic"  onkeypress="return validePlaca(event);" >
                </div>
                <div class="col-12 text-center">
                    <small id="error_vehiculo_sic" class="form-text text-danger"></small>
                </div>
            </div>
            <div class="form-row mt-3">
                <div class="form-group col-lg-1 mi_hide" >
                    <input type="text" class="form-control form-control-sm " id="Id_Vehiculo" name="Id_Vehiculo" value="SD" disabled>
                </div>
                <div class="form-group col-lg-12 col-sm-6">
                    <label for="placas" class="subtitulo-rosa">Placas:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" id="placas" name="placas" placeholder="Ingrese placas separadas por ','" onkeypress="return validePlacas(event);">
                    <span class="span_error" id="placas_error"></span>
                </div>
                <div class="form-group col-lg-6 col-sm-6">
                    <label for="marca" class="subtitulo-rosa">Marca:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="50" id="marca" name="marca" placeholder="Ingrese Marca" onkeypress="return valideMultiplesDatos(event);">
                    <span class="span_error" id="marca_error"></span>
                </div>
                <div class="form-group col-lg-6 col-sm-6">
                    <label for="submarca" class="subtitulo-rosa">Submarca:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="50" id="submarca" name="submarca" placeholder="Ingrese Submarca" onkeypress="return valideMultiplesDatos(event);">
                    <span class="span_error" id="submarca_error"></span>
                </div>
                <div class="form-group col-lg-6 col-sm-6">
                    <label for="color" class="subtitulo-rosa">Color:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="30" id="color" name="color" placeholder="Ingrese Color"onkeypress="return validaOnlyLetras(event);" >
                    <span class="span_error" id="color_error"></span>
                </div>
                <div class="form-group col-lg-6 col-sm-6">
                    <label for="modelo" class="subtitulo-rosa">Modelo:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="30" id="modelo" name="modelo" placeholder="Ingrese el modelo del vehiculo"onkeypress="return validaModelo(event);">
                    <span class="span_error" id="modelo_error"></span>
                </div>
                <div class="form-group col-lg-12 col-sm-6">
                    <label for="NombrePropietario" class="subtitulo-rosa">Nombre del Propietario:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="80" id="NombrePropietario" name="NombrePropietario" placeholder="Ingrese Nombre del Propietario"onkeypress="return validaOnlyLetras(event);" >
                    <span class="span_error" id="NombrePropietario_error"></span>
                </div>
                <div class="form-group col-lg-12">
                    <label for="NIVS" class="label-form subtitulo-rosa">NIVS del Vehiculo:</label>
                    <textarea name="NIVS" id="NIVS" cols="45" rows="3" class="form-control form-control-sm text-uppercase"placeholder="Ingrese NIVS que tenga el Vehiculo separadas por ','" onkeypress="return valideMultiplesDatos(event);"></textarea>
                    <span class="span_error" id="NIVS_error"> </span>
                </div>               
                <div class="form-group col-lg-9">
                    <label for="remisiones_FoliosVeh" class="label-form subtitulo-rosa">Informacion de las Placas :</label>
                    <textarea name="remisiones_FoliosVeh" id="remisiones_FoliosVeh" cols="45" rows="3" class="form-control form-control-sm text-uppercase"placeholder="Ingrese Informacion de las Placas"onkeypress="return valideMultiplesDatos(event);"></textarea>
                    <span class="span_error" id="remisiones_FoliosVeh_error"> </span>
                </div>
                <div class="form-group col-lg-3" align="right">
                    <br>
                    <button type="button" class="btn btn-add button-movil-plus" onclick="onFormVehiculoSubmit()">Agrega Vehiculo</button>
                </div>
            </div>
        </div>

    
        <div class="container-fluid">
            <div class="form-row row"> 
                <div class="form-group col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="VehiculoTable" style="text-align:center">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" style="display:none;">Id_Vehiculo</th>
                                    <th scope="col">Placas</th>
                                    <th scope="col">Marca</th>
                                    <th scope="col">Submarca</th>
                                    <th scope="col">Color</th>
                                    <th scope="col">Modelo</th>
                                    <th scope="col">Nombre del Propietario</th>
                                    <th scope="col">Nivs</th>
                                    <th scope="col">Informacion adicional</th>
                                    <th scope="col">Foto</th>
                                    <th scope="col">Capturo</th>
                                    <th scope="col">Editar/Eliminar</th>
                                </tr>
                            </thead>
                            <tbody id="contarVeh">
                            </tbody>
                        </table>
                    </div><br>
                    <span class="span_error" id="tabla_vehiculo_error"></span>
                </div>
            </div>
        </div>
        <div class="modal fade" id="ModalCenterPrincipalVehiculo" tabindex="-1" role="dialog" aria-labelledby="myModalExito" aria-hidden="true" >
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <img name="nor" src="<?= base_url; ?>/public/media/images/logo6.png"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
            </div>
        </div>
        <div class="row mt-5 mb-5">
            <div class="d-flex justify-content-end col-sm-12" id="id_p">
                <a class="btn btn-ssc  mr-3" href="<?= base_url; ?>Seguimientos"><i class="material-icons v-a-middle">arrow_back_ios</i>Volver al inicio</a>
                <a class="btn btn-ssc " id="btn_vehiculos" value='1'>Guardar</a>
            </div>
        </div>
    </form>
</div>