<div class="container-fluid">
    <!--vista para generar datos de domicilios en el seguimiento -->
    <form id='datos_antecedentes' onsubmit="event.preventDefault()">
        <div class="container">
            <!--PARA MOSTRAR SI SE CARGARON LOS DATOS O UBO UN ERROR-->
            <div class="col-12 my-4" id="msg_principales_antecedentes"></div>
            <h5 class="titulo-azul">Antecedentes Asociados</h5>
            <div class="alert fondo-azul" role="event" style="display:none" id="alertaEditAntecedentes">
                Se está realizando la edición a un Antecedentes.
            </div>
            <div class="row mt-2 mi_hide">
                <div class="col-lg-6 col-sm-6">
                    <h5 class="subtitulo-rosa">Id de Seguimiento Asignado:</h5>
                    <input type="text" name="id_seguimiento_antecedentes" id="id_seguimiento_antecedentes" class="form-control custom-input_dt">
                    <span class="span_error" id="id_seguimiento_antecedentes_error"></span> 
                </div>
                <div class="col-lg-6 col-sm-6 ">
                    <h5  class="subtitulo-rosa">Elemento que Captura dato de persona: </h5>
                    <input  style="font-size: 15px;color: #0F2145;"type="text" name="captura_dato_antecedentes" id="captura_dato_antecedentes" value="<?php echo $_SESSION['userdataSIC']->User_Name?>" class="form-control custom-input_dt text-uppercase" disabled="true">
                    <span class="span_error" id="captura_dato_antecedentes_error"></span>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-6">
                    <h5  class="subtitulo-rosa">¿Tipo de dato al cual se le asociara el antecendete?</h5>
                </div>
                <div class="col-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_dato_antecendente" id="id_tipo_antecedente_1" checked value="PERSONA" onchange="return changeTipoAntecedente(event)" >
                        <label class="form-check-label" for="id_tipo_antecedente_1">Persona</label>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_dato_antecendente" id="id_tipo_antecedente_2"  value="VEHICULO" onchange="return changeTipoAntecedente(event)" >
                        <label class="form-check-label" for="id_tipo_antecedente_2">Vehiculo</label>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="form-group col-lg-6">
                </div>
                <div class="form-group col-lg-6"  id="Persona_Select_Antecedente">
                    <select class="custom-select custom-select-sm" id="PersonaSelectAntecedente">
                    </select>
                    <span class="span_error" id="PersonaSelect_Antecedente_error"></span>
                </div>
                <div class="form-group col-lg-6 mi_hide" id="Vehiculo_Select_Antecedente">
                    <select class="custom-select custom-select-sm" id="VehiculoSelectAntecedente">
                    </select>
                    <span class="span_error" id="VehiculoSelect_Antecedente_error"></span>
                </div>
            </div>
            <hr style="height:5px;border:none;color:#29295f;background-color:#29295f;"\>
            <div class="row mt-3">
                <div class="form-group col-lg-1 mi_hide" >
                    <input type="text" class="form-control form-control-sm " id="Id_Antecedente" name="Id_Antecedente" value="SD" disabled>
                </div>
                <div class="form-group col-lg-9">
                    <label for="Antecedente" class="subtitulo-rosa">Descripcion Antecedente:</label>
                    <textarea name="Antecedente_descripcion" id="Antecedente_descripcion" cols="45" rows="7" class="form-control form-control-sm text-uppercase"placeholder="Ingrese el Antecedente"onkeypress="return valideMultiples(event);"></textarea>
                    
                    <span class="span_error" id="Antecedente_error"></span>
                </div>
                <div class="form-group col-lg-3">
                    <br>
                    <label for="fecha" class="subtitulo-rosa">Fecha del Antecedente:</label>
                    <input type="month" name="fecha" id="fecha" class="form-control" value="">
                    <br>
                    <br>
                    <button type="button" class="btn btn-add button-movil-plus" onclick="onFormAntecedentesubmit()">Agrega Antecedente</button>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="form-row row"> 
                <div class="form-group col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="AntecendentesTable" style="text-align:center">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Nombre del Dato Asociado</th>
                                    <th scope="col" style="display:none;">Id Antecedentes</th>
                                    <th scope="col" style="display:none;">Id Dato</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Descripcion de Antecedente</th>
                                    <th scope="col">Fecha de Antecedente</th>
                                    <th scope="col">Capturo</th>
                                    <th scope="col">Editar/Eliminar</th>
                                </tr>
                            </thead>
                            <tbody id="contarAntecedentes">
                            </tbody>
                        </table>
                    </div><br>
                    <span class="span_error" id="tabla_antecedentes_error"></span>
                </div>
            </div>
        </div>

    

        <div class="modal fade" id="ModalCenterPrincipalAntecedentes" tabindex="-1" role="dialog" aria-labelledby="myModalExito" aria-hidden="true" >
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <img name="nor" src="<?= base_url; ?>/public/media/images/logo6.png"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
            </div>
        </div>
        <div class="row mt-5 mb-5">
            <div class="d-flex justify-content-end col-sm-12" id="id_p">
                <a class="btn btn-ssc  mr-3" href="<?= base_url; ?>Seguimientos"><i class="material-icons v-a-middle">arrow_back_ios</i>Volver al inicio</a>
                <a class="btn btn-ssc " id="btn_antecedentes" value='1'>Guardar</a>
            </div>
        </div>
    </form>
</div>