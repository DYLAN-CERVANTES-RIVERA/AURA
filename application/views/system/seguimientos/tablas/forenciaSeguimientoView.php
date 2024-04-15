<div class="container-fluid">
    <!--vista para generar datos de domicilios en el seguimiento -->
    <form id='datos_forencias' onsubmit="event.preventDefault()">
        <div class="container">
            <!--PARA MOSTRAR SI SE CARGARON LOS DATOS O UBO UN ERROR-->
            <div class="col-12 my-4" id="msg_principales_forencias"></div>
            <h5 class="titulo-azul">Datos de Personas Asociadas</h5>
            <div class="alert fondo-azul" role="event" style="display:none" id="alertaEditforencias">
                Se está realizando la edición de un dato.
            </div>
            <div class="row mt-2 mi_hide">
                <div class="col-lg-6 col-sm-6">
                    <h5 class="subtitulo-rosa">Id de Seguimiento Asignado:</h5>
                    <input type="text" name="id_seguimiento_forencias" id="id_seguimiento_forencias" class="form-control custom-input_dt">
                    <span class="span_error" id="id_seguimiento_forencias_error"></span> 
                </div>
                <div class="col-lg-6 col-sm-6 ">
                    <h5  class="subtitulo-rosa">Elemento que Captura dato de persona: </h5>
                    <input  style="font-size: 15px;color: #0F2145;"type="text" name="captura_dato_forencias" id="captura_dato_forencias" value="<?php echo $_SESSION['userdataSIC']->User_Name?>" class="form-control custom-input_dt text-uppercase" disabled="true">
                    <span class="span_error" id="captura_dato_forencias_error"></span>
                </div>
            </div>
            <div class="row mt-3">
                <div class="form-group col-lg-5" >
                    <h5  class="subtitulo-rosa">Selecciona la persona a la cual se asociará el dato:</h5>
                </div>
                <div class="form-group col-lg-6"  id="Persona_Select_Forencias">
                    <select class="custom-select custom-select-sm" id="PersonaSelectForencias">
                    </select>
                    <span class="span_error" id="PersonaSelect_Forencias_error"></span>
                </div>
            </div>
           
            <hr style="height:5px;border:none;color:#29295f;background-color:#29295f;"\>
            <div class="row mt-3">
                <div class="form-group col-lg-1 mi_hide" >
                    <input type="text" class="form-control form-control-sm " id="Id_Forencia" name="Id_Forencia" value="SD" disabled>
                </div>
                <div class="form-group col-lg-9">
                    <label for="forencia" class="subtitulo-rosa">Descripcion:</label>
                    <textarea name="forencia_descripcion" id="forencia_descripcion" cols="45" rows="7" class="form-control form-control-sm text-uppercase"placeholder="Ingrese la descripcion del dato"onkeypress="return valideMultiples(event);"></textarea>   
                    <span class="span_error" id="forencia_error"></span>
                </div>
                <div class="form-group col-lg-3">
                    <br>
                    <br>
                    <br>
                    <button type="button" class="btn btn-add button-movil-plus" onclick="onFormForenciasubmit()">Agrega Dato</button>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="form-row row"> 
                <div class="form-group col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="ForenciasTable" style="text-align:center">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Nombre de la Persona Asociado</th>
                                    <th scope="col" style="display:none;">Id Dato</th>
                                    <th scope="col" style="display:none;">Id Persona</th>
                                    <th scope="col">Descripcion de Dato</th>
                                    <th scope="col">Foto</th>
                                    <th scope="col">Capturo</th>
                                    <th scope="col">Editar/Eliminar</th>
                                </tr>
                            </thead>
                            <tbody id="contarforencias">
                            </tbody>
                        </table>
                    </div><br>
                    <span class="span_error" id="tabla_forencias_error"></span>
                </div>
            </div>
        </div>

    

        <div class="modal fade" id="ModalCenterPrincipalforencias" tabindex="-1" role="dialog" aria-labelledby="myModalExito" aria-hidden="true" >
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <img name="nor" src="<?= base_url; ?>/public/media/images/logo6.png"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
            </div>
        </div>
        <div class="row mt-5 mb-5">
            <div class="d-flex justify-content-end col-sm-12" id="id_p">
                <a class="btn btn-ssc  mr-3" href="<?= base_url; ?>Seguimientos"><i class="material-icons v-a-middle">arrow_back_ios</i>Volver al inicio</a>
                <a class="btn btn-ssc " id="btn_forencias" value='1'>Guardar</a>
            </div>
        </div>
    </form>
</div>