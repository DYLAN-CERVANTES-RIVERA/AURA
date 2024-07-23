<div class="container">
   <!--vista del evento solo para lectura tab datos principales -->
   <h5 class="titulo-azul mt-3">Informacion del Turno</h5>
    <div class="row mt-1">
        <div class="col-lg-6 col-sm-12">
            <span class="span_rem">Elemento que Capturo la Informacion: </span>
            <span class="span_rem_ans" name="elemento_captura" id="elemento_captura"></span>
        </div>
        <div class="col-lg-6 col-sm-12">
            <span class="span_rem" for="Turno" class="subtitulo-rosa">Turno:</span>
            <span class="span_rem_ans" name="Turno" id="Turno"></span>
        </div>
        <div class="col-lg-6 col-sm-12">
            <span class="span_rem" for="Responsable_Turno" class="subtitulo-rosa">Responsable de Turno:</span>
            <span class="span_rem_ans" name="Responsable_Turno" id="Responsable_Turno"></span>
        </div>
        <div class="col-lg-6 col-sm-12">
            <span class="span_rem" for="Semana" class="subtitulo-rosa">Semana:</span>
            <span class="span_rem_ans" name="Semana" id="Semana"></span>
        </div>
        <div class="row col-lg-12 col-sm-12 justify-content-center mt-3">
            <span class="span_rem" for="Semana" class="subtitulo-rosa">Fecha y hora de captura del evento:</span>
            <span class="span_rem_ans valor-campo" name="fecha_captura_principales" id="fecha_captura_principales"></span>
        </div>
    </div>
    <h5 class="titulo-azul mt-3">Datos del Evento</h5>
    <div class="row mt-2">
        <div class="col-lg-3 col-sm-12 ">
            <span class="span_rem">Folio AURA: </span>
            <span class="span_error" id="Folio_infra_principalesError"></span>
            <span class="span_rem_ans" name="Folio_infra_principales" id="Folio_infra_principales"></span>
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
        <div class="col-lg-3 col-sm-12  ">
            <span class="span_rem">Estatus del Evento: </span>
            <span class="span_error" id="Estatus_EventoError"></span>
            <span class="span_rem_ans" name="Estatus_Evento" id="Estatus_Evento"></span>
        </div>
        <div class="col-lg-4 col-sm-12  ">
            <span class="span_rem">Seguimiento del Evento: </span>
            <span class="span_error" id="status_principalesError"></span>
            <span class="span_rem_ans" name="status_principales" id="status_principales"></span>
        </div>
        <div class="col-lg-4 col-sm-12 ">
            <span class="span_rem">Con/Sin violencia: </span>
            <span class="span_error" id="violenciaCS_principalesError"></span>
            <span class="span_rem_ans" name="violenciaCS_principales" id="violenciaCS_principales"></span>
        </div>
        <div class="col-lg-4 col-sm-12">
            <span class="span_rem">Tipo de Violencia:</span>
            <span class="span_rem_ans" id="violencia_principales" name="violencia_principales"></span>
        </div>

        <div class="col-lg-6 col-sm-12  mt-2">
            <span class="span_rem">CDI:</span>
            <span class="span_rem_ans" id="cdi" name="cdi"></span>
        </div>

        <div class="row col-lg-6 col-sm-12 justify-content-center mt-2">
            <span class="span_rem">Fecha y hora de recepcion del evento: </span>
            <span class="span_rem_ans valor-campo" name="fecha_recepcion_principales" id="fecha_recepcion_principales"></span>
        </div>
    </div>
    <h5 class="text-center mt-2">Hechos reportados</h5>
    <div class="table-responsive">
        <table class="table table-bordered" id="HechosTable">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Descripción</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Hora</th>
                </tr>
            </thead>
            <tbody id="contarhechos">
            </tbody>
        </table>
    </div>
    <h5 class="text-center mt-1">Delitos reportados</h5>
    <div class="table-responsive">
        <table class="table table-bordered" id="faltasDelitosTable">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Descripción</th>
                    <th scope="col">Giro</th>
                </tr>
            </thead>
            <tbody id="contardelitos">
            </tbody>
        </table>
    </div>

    <h5 class="titulo-azul mt-3">Ubicación del evento: </h5>
    <div class="row-md-12 mt-1">
        <div class="form-row">
            <div class="form-group col-lg-4 mt-4 text-center">
                <span class="span_rem">Colonia: </span>
                <span class="valor-campo" id="Colonia" name="Colonia"></span>
                <span class="span_error" id="Colonia_principales_error"></span>
            </div>

            <div class="form-group col-lg-4 mt-4 text-center">
                <span class="span_rem">Calle: </span>
                <span class="valor-campo" id="Calle" name="Calle"></span>
                <span class="span_error" id="Calle_principales_error"></span>
            </div>

            <div class="form-group col-lg-4 mt-4 text-center">
                <span class="span_rem">Calle 2: </span>
                <span class="valor-campo" id="Calle2" name="Calle2"></span>
                <span class="span_error" id="Calle2_principales_error"></span>
            </div>

            <div class="form-group col-lg-3 mt-4 text-center">
                <span class="span_rem">Núm. de Exterior: </span>
                <span class="valor-campo" id="noExterior" name="noExterior"></span>
                <span class="span_error" id="noExterior_principales_error"></span>

            </div>

            <div class="form-group col-lg-3 mt-4 text-center">
                <span class="span_rem">Coordenada Y:</span>
                <span class="valor-campo" id="cordY" name="cordY"></span>
                <span class="span_error" id="cordY_principales_error"></span>
            </div>

            <div class="form-group col-lg-3 mt-4 text-center">
                <span class="span_rem">Coordenada X:</span>
                <span class="valor-campo" id="cordX" name="cordX"></span>
                <span class="span_error" id="cordX_principales_error"></span>
            </div>

            <div class="form-group col-lg-3 mt-4 text-center">
                <span class="span_rem">CP:</span>
                <span class="valor-campo" id="cp" name="cp"></span>
                <span class="span_error" id="cp_principales_error"></span>
            </div>

        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <div class="form-group text-center">
                <span class="span_rem">Zona: </span>
                <span class="valor-campo" id="zona_principales" name="zona_principales"></span>
                <span class="span_error" id="zona_error"></span>
            </div>
        </div>

        <div class="col">
            <div class="form-group text-center">
                <span class="span_rem">Vector: </span>
                <span class="valor-campo" id="vector_principales" name="vector_principales"></span>
                <span class="span_error" id="vector_principales_error"></span>
            </div>
        </div>
    </div>
    <h5 class="titulo-azul mt-3"> Informacion del Primer Respondiente: </h5>
    <div class="row mt-2">
        <div class="col-lg-4 col-sm-12 ">
            <span class="span_rem">Unidad del Primer Respondiente:</span>
            <span class="span_rem_ans" name="Unidad_Primer_R" id="Unidad_Primer_R"></span>
        </div>   
        <div class="col-lg-8 col-sm-12  ">
            <span class="span_rem">Información del Primer Respondiente: </span>
            <span class="span_rem_ans" name="Informacion_Primer_R" id="Informacion_Primer_R"></span>
        </div>
        <div class="col-lg-12 col-sm-12  ">
            <span class="span_rem">Acciones: </span>
            <span class="span_rem_ans" name="Acciones" id="Acciones"></span>
        </div>
    </div>
    <div class="mi_hide" id="Ubodetencion">
        <h5 class="titulo-azul mt-3">Detencion del Evento: </h5>
        <div class="row-md-12 mt-1">
            <div class="form-row">
                <div class="col-lg-12 col-sm-12">
                    <span class="span_rem">Fecha de la Detencion: </span>
                    <span class="span_rem_ans" name="Fecha_Detencion" id="Fecha_Detencion"></span>
                </div>

                <div class="col-lg-7 col-sm-12">
                    <span class="span_rem">La detencion fue por la informacion proporcionada por IO: </span>
                    <span class="span_rem_ans" name="Detencion_Por_Info_Io" id="Detencion_Por_Info_Io"></span>
                </div>

                <div class="col-lg-5 col-sm-12">
                    <span class="span_rem">Compañia: </span>
                    <span class="span_rem_ans" name="Compañia" id="Compañia"></span>
                </div>
                <div class="col-lg-12 col-sm-12">
                    <span class="span_rem">Elementos que Realizaron la detencion: </span>
                    <span class="span_rem_ans" name="Elementos_Realizan_D" id="Elementos_Realizan_D"></span>
                </div>
                <div class="col-lg-12 col-sm-12">
                    <span class="span_rem">Nombres de los detenidos: </span>
                    <span class="span_rem_ans" name="Nombres_Detenidos" id="Nombres_Detenidos"></span>
                </div>

                <div class="col-lg-6 col-sm-12">
                    <span class="span_rem">Estado: </span>
                    <span class="valor-campo text-center" id="Estado" name="Estado"></span>
                </div>                
                <div class="col-lg-6 col-sm-12 ">
                    <span class="span_rem">Municipio: </span>
                    <span class="valor-campo text-center" id="Municipio" name="Municipio"></span>
                </div>


                <div class="col-lg-6 col-sm-12 ">
                    <span class="span_rem">Colonia: </span>
                    <span class="valor-campo text-center" id="Colonia_Det" name="Colonia_Det"></span>
                </div>

                <div class="col-lg-6 col-sm-12">
                    <span class="span_rem">Calle: </span>
                    <span class="valor-campo text-center" id="Calle_Det" name="Calle_Det"></span>
                </div>

                <div class="col-lg-6 col-sm-12">
                    <span class="span_rem">Calle 2: </span>
                    <span class="valor-campo text-center" id="Calle_Det2" name="Calle_Det2"></span>
                </div>

                <div class="col-lg-6 col-sm-12">
                    <span class="span_rem">CP:</span>
                    <span class="valor-campo text-center" id="CP_Det" name="CP_Det"></span>
                </div>

                <div class="col-lg-6 col-sm-12">
                    <span class="span_rem">Núm. de Exterior: </span>
                    <span class="valor-campo text-center" id="no_Ext_Det" name="no_Ext_Det"></span>
                </div>

                <div class="col-lg-6 col-sm-12">
                    <span class="span_rem">Núm. de Interior: </span>
                    <span class="valor-campo text-center" id="no_Int_Det" name="no_Int_Det"></span>
                </div>

                <div class="col-lg-6 col-sm-12">
                    <span class="span_rem">Coordenada Y:</span>
                    <span class="valor-campo text-center" id="cordY_Det" name="cordY_Det"></span>
                </div>

                <div class="col-lg-6 col-sm-12">
                    <span class="span_rem">Coordenada X:</span>
                    <span class="valor-campo text-center" id="cordX_Det" name="cordX_Det"></span>
                </div>
                <div class="col-lg-12 col-sm-12">
                    <span class="span_rem">Link de la Ubicacion:</span>
                    <span class="valor-campo text-center" id="Link_Ubicacion_Det" name="Link_Ubicacion_Det"></span>
                </div>
                <div class="col-lg-12 col-sm-12">
                    <span class="span_rem">Observaciones de la Ubicacion:</span>
                    <span class="valor-campo text-center" id="Observacion_Ubicacion_Det" name="Observacion_Ubicacion_Det"></span>
                </div>
            </div>
        </div>
    </div>
    
    
    <h5 class="titulo-azul mt-3"> Datos del Seguimiento del Evento: </h5>
    <div class="row-md-12">
        <div class="form-row">
            <div class="form-group col-lg-4 mt-2 text-center">
                <span class="span_rem">Elemento Asignado a el Seguimiento:</span>
                <span class="valor-campo" id="ClaveAsignacion" name="ClaveAsignacion"></span>
                <span class="span_error" id="ClaveAsignacion_error"></span>
            </div>
       
            <div class="form-group col-lg-8 mt-2 text-center">
                <span class="span_rem">Fecha y Hora de activacion de Seguimiento:</span>
                <span class="valor-campo" name="fechahora_activacion_principales" id="fechahora_activacion_principales"></span>
                <span class="span_error" id="fechaP_error"></span>
            </div>
        </div>
    </div><br>

    <div class="row-md-12 mt-5 mb-5">
        <div class="d-flex justify-content-center col-sm-12">
            <a class="btn btn-sm btn-ssc" href="<?= base_url;?>GestorCasos"><i class="material-icons v-a-middle">arrow_back_ios</i>Volver al inicio</a>
        </div>
    </div>

</div>