<div class="container">
    <h5 class="titulo-azul mt-3">Imagenes de Video y Fotos</h5>
    <div class="row">
        <div class="col-lg-6">
            <label for="id_ubicacion" class="label-form subtitulo-rosa">VER SOLO UBICACION</label>
            <select class="custom-select custom-select-sm" id="id_ubicacion" name="id_ubicacion">
                <option value="NA">TODAS LAS UBICACIONES</option>    
                <option value="1">VER SOLO UBICACION 1</option>
                <option value="2">VER SOLO UBICACION 2</option>
                <option value="3">VER SOLO UBICACION 3</option>
                <option value="4">VER SOLO UBICACION 4</option>
                <option value="5">VER SOLO UBICACION 5</option>
                <option value="6">VER SOLO UBICACION 6</option>
                <option value="7">VER SOLO UBICACION 7</option>
                <option value="8">VER SOLO UBICACION 8</option>
                <option value="9">VER SOLO UBICACION 9</option>
                <option value="10">VER SOLO UBICACION 10</option>
            </select>
            <span class="span_error" id="id_ubicacion_error"></span>
        </div>
        <div class="col-lg-6">
            <label for="id_camara" class="label-form subtitulo-rosa">VER SOLO CAMARA</label>
            <select class="custom-select custom-select-sm" id="id_camara" name="id_camara">
                <option value="NA">TODAS LAS CAMARAS</option>    
                <option value="1">VER SOLO CAMARA 1</option>
                <option value="2">VER SOLO CAMARA 2</option>
                <option value="3">VER SOLO CAMARA 3</option>
                <option value="4">VER SOLO CAMARA 4</option>
                <option value="5">VER SOLO CAMARA 5</option>
                <option value="6">VER SOLO CAMARA 6</option>
                <option value="7">VER SOLO CAMARA 7</option>
                <option value="8">VER SOLO CAMARA 8</option>
                <option value="9">VER SOLO CAMARA 9</option>
                <option value="10">VER SOLO CAMARA 10</option>
            </select>
            <span class="span_error" id="id_camara_error"></span>
        </div>
    </div>
</div>
    <br>
<div class="container-fluid">
    <div class="table-responsive">
        <table class="table table-bordered" id="fotosTable">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Ubicacion Id</th>
                    <th scope="col">Foto</th>
                    <th scope="col">Camara Id</th>
                    <th scope="col">Descripción de Foto</th>
                    <th scope="col">Colonia</th>
                    <th scope="col">Calle</th>
                    <th scope="col">Calle2</th>
                    <th scope="col">NoExt</th>
                    <th scope="col">CP</th>
                    <th scope="col">Coordenada Y</th>
                    <th scope="col">Coordenada X</th>
                    <th scope="col">Fecha de Captura</th>
                    <th scope="col">Hora de Captura</th>
                    <th scope="col">Fecha y Hora de Captura en Sistema</th>
                </tr>
            </thead>
            <tbody id="tablaEntrevistas-B">
            </tbody>
        </table>
    </div>
    <div class="row-md-12 mt-5 mb-5">
        <div class="d-flex justify-content-center col-sm-12">
            <a class="btn btn-sm btn-ssc" href="<?= base_url;?>GestorCasos"><i class="material-icons v-a-middle">arrow_back_ios</i>Volver al inicio</a>
        </div>
    </div>

</div>