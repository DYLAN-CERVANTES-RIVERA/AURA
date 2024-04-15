<div class="container-fluid">
    <!--vista del evento solo para lectura tab datos asociados  -->
    <h5 class="titulo-azul mt-3">Vehiculos Involucrados reportados</h5>
    <div class="table-responsive">
        <table class="table table-bordered" id="VehiculoTable">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Tipo de vehiculo</th>
                    <th scope="col">Marca</th>
                    <th scope="col">Submarca</th>
                    <th scope="col">Modelo</th>
                    <th scope="col">Placas</th>
                    <th scope="col">Color</th>
                    <th scope="col">Descripción del Vehiculo</th>
                    <th scope="col">Foto</th>
                    <th scope="col">Tipo de Vehiculo Involucrado</th>
                    <th scope="col">Estado de Vehiculo</th>
                    <th scope="col">Capturo</th>
                </tr>
            </thead>
            <tbody id="contarVehiculos">
            </tbody>
        </table>
    </div>
    <h5 class="titulo-azul mt-3">Involucrados reportados</h5>
    <div class="table-responsive">
        <table class="table table-bordered" id="PersonaTable">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Sexo</th>
                    <th scope="col">Rango de edad</th>
                    <th scope="col">Complexion</th>
                    <th scope="col">Descripción del Responsable</th>
                    <th scope="col">Foto</th>
                    <th scope="col">Tipo de arma</th>
                    <th scope="col">Estado de Involucrado</th>
                    <th scope="col">Capturo</th>
                </tr>
            </thead>
            <tbody id="contarRes">
            </tbody>
        </table>
    </div>

    <div class="row-md-12 mt-5 mb-5">
        <div class="d-flex justify-content-center col-sm-12">
            <a class="btn btn-sm btn-ssc" href="<?= base_url;?>GestorCasos"><i class="material-icons v-a-middle">arrow_back_ios</i>Volver al inicio</a>
        </div>
    </div>


</div>