<div class= "content">
    <div class="cabecera_modulo" ><br></div>
    <div class="container">
        <!--vista para las tabs para edicion de los eventos  -->
        <?php if(!isset($data['titulo_1'])){ ?>
            <div class="paragraph-title d-flex justify-content-between mt-2 mb-1">
                <h5> <a href="<?= base_url; ?>Puntos">PUNTOS IDENTIFICADOS </a> <span>/ VER PUNTO</span></h5>
            </div>
        <?php }else{ ?>
            <div class="paragraph-title d-flex justify-content-between mt-2 mb-1">
                <h5> <a href="<?= base_url; ?>Puntos">PUNTOS IDENTIFICADOS </a> <span>/ <?=$data['titulo_1']?></span></h5>
            </div>
        <?php } ?>
        <hr>
    </div>
    <form id='datos_principales_puntos' onsubmit="event.preventDefault()" >
        <div class="container">
            <div class="row mt-3" disabled>
                <div class="col-lg-4 col-sm-6">
                    <h5 class="subtitulo-rosa">Id del Punto</h5>
                    <input type="text" name="Id_Punto" id="Id_Punto" class="form-control custom-input_dt text-center" disabled>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <h5  class="subtitulo-rosa">Capturo</h5>
                    <input type="text" name="Capturo" id="Capturo" class="form-control custom-input_dt text-center" disabled>
                </div>

                <div class="col-lg-4 col-sm-6">             
                    <h5 class="subtitulo-rosa">Fecha de Captura</h5>
                    <input type="text" name="Fecha_Captura" id="Fecha_Captura" class="form-control custom-input_dt text-center" disabled>
                </div>
            </div><br>

            <h5 class="titulo-azul">Ubicacion Identificada</h5>

            <div class="row mt-3">
                <div class="form-row mt-3">
                    <div class="form-group col-lg-6">
                        <label for="Colonia" class ="label-form subtitulo-rosa">Colonia</label>
                        <input type="text" class = "form-control form-control-sm text-uppercase" id="Colonia" name="Colonia" >
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="Calle" class="label-form subtitulo-rosa">Calle</label>
                        <input type="text" class="form-control form-control-sm text-uppercase" id="Calle" name="Calle">
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="Calle2" class="label-form subtitulo-rosa">Calle 2</label>
                        <input type="text" class="form-control form-control-sm text-uppercase" id="Calle2" name="Calle2">
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="no_Ext" class="label-form subtitulo-rosa">No. Ext.</label>
                        <input type="text" class="form-control form-control-sm" id="no_Ext" name="no_Ext">
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="CP" class="label-form subtitulo-rosa">CP</label>
                        <input type="text" class="form-control form-control-sm" id="CP" name="CP">
                    </div>
                    
                    <div class="form-group col-lg-4">
                        <label for="cordY" class="label-form subtitulo-rosa">Coordenada Y</label>
                        <input type="text" class="form-control form-control-sm" id="cordY" name="cordY">
                    </div>

                    <div class="form-group col-lg-4">
                        <label for="cordX" class="label-form subtitulo-rosa">Coordenada X</label>
                        <input type="text" class="form-control form-control-sm" id="cordX" name="cordX">
                    </div>

                    <div class="form-group col-lg-4">
                        <label for="zona" class="label-form subtitulo-rosa">Zona</label>
                        <input type="text" class="form-control form-control-sm" id="zona" name="zona">
                    </div>

                    <div class="form-group col-lg-4">
                        <label for="vector" class="label-form subtitulo-rosa">Vector</label>
                        <input type="text" class="form-control form-control-sm" id="vector" name="vector">
                    </div>
                </div>
            </div>
            <h5 class="titulo-azul">Fuente de Informacion</h5>
            <div class = 'row mt-3'>
                <div class="form-row mt-3">
                    <div class="form-group col-lg-4">
                        <label for="Fuente_info" class="label-form subtitulo-rosa">Fuente de Informacion</label>
                        <input type="text" class="form-control form-control-sm"  id="Fuente_info" name="Fuente_info">
                    </div>

                    <div class="form-group col-lg-4">
                        <label for="Estatus_Punto" class="label-form subtitulo-rosa">Estatus del Punto</label>
                        <input type="text" class="form-control form-control-sm" id="Estatus_Punto" name="Estatus_Punto">
                    </div>

                    <div class="form-group col-lg-4">
                        <label for="Identificador" class="label-form subtitulo-rosa">Identificador</label>
                        <input type="text" class="form-control form-control-sm"  id="Identificador" name="Identificador">
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="nombre" class="subtitulo-rosa">Remision:</label>
                        <input type="text" class="form-control form-control-sm " id="id_remision" name="id_remision">
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="nombre" class="subtitulo-rosa">Nombre Completo y Alias (Detenido):</label>
                        <input type="text" class="form-control form-control-sm text-uppercase"  id="nombre" name="nombre">
                    </div>
                    <div class="form-group col-lg-12">
                        <label for="Narrativa" class="label-form subtitulo-rosa">Narrativa del Detenido</label>
                        <textarea name="Narrativa" id="Narrativa" rows="5" class="form-control form-control-sm text-uppercase"></textarea>
                    </div>
                </div>
            </div>
            <h5 class="titulo-azul mt-3">Informacion de la Ubicacion</h5>
            <div class="row mt-3">
                <div class="form-group col-lg-3">
                    <label for="fecha" class="label-form subtitulo-rosa">Ingrese fecha de obtencion de Información</label>
                    <input type="date" name="fecha_obtencion" id="fecha_obtencion" class="form-control custom-input_dt fecha parrafo-azul fondo-azul">
                </div>
                <div class="form-group col-lg-9">
                    <label for="Info_Adicional" class="label-form subtitulo-rosa">Información Adicional</label>
                    <textarea name="Info_Adicional" id="Info_Adicional" rows="5" class="form-control form-control-sm text-uppercase" ></textarea>
                </div>
                <div class="form-group col-lg-12">
                    <label for="Distribuidor" class="subtitulo-rosa">Nombre o Alias del Distribuidor:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="450" id="Distribuidor" name="Distribuidor">
                </div>   
                <div class="form-group col-lg-6">
                    <label for="Grupo_OP" class="subtitulo-rosa">Grupo Delictivo:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="450" id="Grupo_OP" name="Grupo_OP">
                </div> 
                <div class="form-group col-lg-6">
                    <label for="Atendido_Por" class="subtitulo-rosa">Informacion Atendida por:</label>
                    <input type="text" class="form-control form-control-sm text-uppercase" maxlength="450" id="Atendido_Por" name="Atendido_Por">
                </div> 
                <div class="form-group col-lg-12">
                    <label for="Enlace_Google" class="subtitulo-rosa">Enlace de Ubicacion:</label>
                    <input type="text" class="form-control form-control-sm" maxlength="450"id="Enlace_Google" name="Enlace_Google">
                </div>
            </div>
            
            <div class="form-group col-lg-12 col-sm-6">
                <label for="fotoMaps" class="label-form subtitulo-rosa">Imagen de la Ubicacion desde el Enlace</label>
                <div class="d-flex justify-content-around">
                    <div id="imageContentMaps"></div>
                </div>
            </div>

            <h5 class="titulo-azul mt-3">Imagen de la Ubicacion Atendida</h5>  
            <div class="form-group col-lg-12 col-sm-6">
                <div class="d-flex justify-content-around">
                    <div id="imageContentUbi"></div>
                </div>
            </div>

            <h5 class="titulo-azul mt-3">Datos del punto</h5>
           
        </div>

        <div class="container-fluid">
            <div class="form-row row"> 
                <div class="form-group col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="DatosUbiTable" style="text-align:center">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Foto</th>
                                    <th scope="col">Descripcion de Dato</th>
                                    <th scope="col">Tipo de Dato</th>
                                    <th scope="col">Capturo</th>
                                </tr>
                            </thead>
                            <tbody id="contardatosUbi">
                            </tbody>
                        </table>
                    </div><br>
                    <span class="span_error" id="tabla_forencias_error"></span>
                </div>
            </div>
        </div>
        <div class="row mt-5 mb-5">
            <div class="d-flex justify-content-end col-sm-12" id="id_p">
                <a class="btn btn-ssc  mr-3" href="<?= base_url; ?>Puntos"><i class="material-icons v-a-middle">arrow_back_ios</i>Volver al inicio</a>
            </div>
        </div>
    </form>
</div>
<br><br><br>