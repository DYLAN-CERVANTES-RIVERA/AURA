<!--vista del modulo catalogos -->
<div class= "content">
    <div class="cabecera_modulo" ><br></div>
    <h1 class="cabecera_modulo text-center"><strong>CATALOGOS DEL SISTEMA</strong></h1> 
    <div class="row">
        <div class="container col-lg-1  justify-content-center text-center" id="navbarnavegacion">
            <br><br><br>
            <nav id = "nav_template">
                <br><br>
                <ul class="list-unstyled justify-content-center">
                    <li >
                        <a class="btn btn-opacity" data-toggle="tooltip" title="ESTADISTICAS" href="<?= base_url;?>Estadisticas" ><img class="circular--square" src="<?php echo base_url; ?>public/media/icons/estadistica.png" width="50%"></a>
                    </li>
                    <?php
                    if ($_SESSION['userdataSIC']->Modo_Admin == 1  || $_SESSION['userdataSIC']->Evento_D[2]==1|| $_SESSION['userdataSIC']->Seguimientos[2] == 1) {
                    ?>
                        <li >
                        <a class="btn btn-opacity" data-toggle="tooltip" title="GESTOR DE EVENTOS DELICTIVOS" href="<?= base_url;?>GestorCasos" ><img class="circular--square" src="<?php echo base_url; ?>public/media/icons/eventologo.png" width="50%"></a>
                        </li>
                    <?php
                    }
                    ?>
                    <?php
                    if ($_SESSION['userdataSIC']->Modo_Admin == 1  ||  $_SESSION['userdataSIC']->Red[2] == 1) {
                    ?>
                        <li>
                            <a class="btn btn-opacity" title="REDES DE VINCULO" data-toggle="tooltip" href="<?= base_url;?>Seguimientos"><img class="circular--square" src="<?php echo base_url; ?>public/media/icons/red.png" width="50%"></a>
                        </li>
                    <?php
                    }
                    ?>
                    <?php
                    if ($_SESSION['userdataSIC']->Modo_Admin == 1  || $_SESSION['userdataSIC']->Entrevistas[2] == 1) {
                    ?>
                        <li>
                            <a class="btn btn-opacity" title="ENTREVISTAS DE DETENIDOS" data-toggle="tooltip" href="<?= base_url;?>Entrevistas"><div><img class="circular--square" src="<?php echo base_url; ?>public/media/icons/entrevista.png" width="50%"></div></a>
                        </li>
                    <?php
                    }
                    ?>
                    <?php
                        if ($_SESSION['userdataSIC']->Modo_Admin == 1  || $_SESSION['userdataSIC']->Red[0] == 1) {
                    ?>
                        <li>
                            <a class="btn btn-opacity" title="PUNTOS ALTO IMPACTO" data-toggle="tooltip" href="<?= base_url;?>Puntos"><div><img class="circular--square" src="<?php echo base_url; ?>public/media/icons/ubicacion.png" width="55%" ></div></a>
                        </li>
                    <?php
                    }
                    ?>

                    <?php
                    if ($_SESSION['userdataSIC']->Modo_Admin == 1) {
                    ?>
                        <br><br>
                        <li class="container mt-3">
                            <hr style="color: #fafafa; background-color: #fafafa;">
                        </li>
                        <br><br>
                        <li class="text-center">
                            <a class="btn btn-opacity" title="CATALOGOS DE SISTEMA" data-toggle="tooltip"  href="<?= base_url; ?>Catalogos"><h5 class="text_nav">Catálogos</h5></a>
                        </li>
                        <li class="text-center">
                            <a class="btn btn-opacity" title="USUARIOS REGISTRADOS" data-toggle="tooltip" href="<?= base_url; ?>UsersAdmin/"><h5 class="text_nav">Usuarios</h5></a>
                        </li>
                        <li class="text-center">
                            <a class="btn btn-opacity" title="HISTORIAL DE MOVIMIENTOS" data-toggle="tooltip" href="<?= base_url; ?>Historiales"><h5 class="text_nav">Historial</h5></a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
                <br>
            </nav>
        </div>
        <div class="container col-lg-10 mt-1 mb-1">
            <div class="row">
                <!--vista de la cards para los catalogos-->
                <a href="<?= base_url?>Catalogos/crudCatalogo/?catalogoActual=1" class="col-12 col-md-6 col-lg-3 mb-4 btn btn-catalogo px-5 px-lg-4">
                    <div class="card shadow-sm">
                        <img src="<?= base_url?>public/media/icons/catalogos/crime.png" class="d-block my-width p-5">

                        
                        <div class="card-body text-center">
                            <h4 class="card-title">Delitos</h4> 
                        </div>
                    </div>	
                </a>
                <a href="<?= base_url?>Catalogos/crudCatalogo/?catalogoActual=2" class="col-12 col-md-6 col-lg-3 mb-4 btn btn-catalogo px-5 px-lg-4">
                    <div class="card shadow-sm">
                        <img src="<?= base_url?>public/media/icons/catalogos/gun.png" class="d-block my-width p-5">

                        
                        <div class="card-body text-center">
                            <h4 class="card-title">Armas</h4> 
                        </div>
                    </div>	
                </a>
                <a href="<?= base_url?>Catalogos/crudCatalogo/?catalogoActual=3" class="col-12 col-md-6 col-lg-3 mb-4 btn btn-catalogo px-5 px-lg-4">
                    <div class="card shadow-sm">
                        <img src="<?= base_url?>public/media/icons/catalogos/violencia.png" class="d-block my-width p-5">
                        <div class="card-body text-center">
                            <h4 class="card-title">Tipo Violencia(Con Violencia)</h4> 
                        </div>
                    </div>  
                </a>
                <a href="<?= base_url?>Catalogos/crudCatalogo/?catalogoActual=15" class="col-12 col-md-6 col-lg-3 mb-4 btn btn-catalogo px-5 px-lg-4">
                    <div class="card shadow-sm">
                        <img src="<?= base_url?>public/media/icons/catalogos/snviolencia.png" class="d-block my-width p-5">
                        <div class="card-body text-center">
                            <h4 class="card-title">Tipo Violencia(Sin Violencia)</h4> 
                        </div>
                    </div>  
                </a>
                <a href="<?= base_url?>Catalogos/crudCatalogo/?catalogoActual=4" class="col-12 col-md-6 col-lg-3 mb-4 btn btn-catalogo px-5 px-lg-4">
                    <div class="card shadow-sm">
                        <img src="<?= base_url?>public/media/icons/catalogos/zonas1.png" class="d-block my-width p-5">
                        <div class="card-body text-center">
                            <h4 class="card-title">Zonas</h4> 
                        </div>
                    </div>  
                </a>
                <a href="<?= base_url?>Catalogos/crudCatalogo/?catalogoActual=5" class="col-12 col-md-6 col-lg-3 mb-4 btn btn-catalogo px-5 px-lg-4">
                    <div class="card shadow-sm">
                        <img src="<?= base_url?>public/media/icons/catalogos/zvector.png" class="d-block my-width p-5">
                        <div class="card-body text-center">
                            <h4 class="card-title">Vectores</h4> 
                        </div>
                    </div>  
                </a>
                <a href="<?= base_url?>Catalogos/crudCatalogo/?catalogoActual=6" class="col-12 col-md-6 col-lg-3 mb-4 btn btn-catalogo px-5 px-lg-4">
                    <div class="card shadow-sm">
                        <img src="<?= base_url?>public/media/icons/catalogos/marcaio.png" class="d-block my-width p-5">
                        <div class="card-body text-center">
                            <h4 class="card-title">Marcas del vehículo</h4>
                        </div>
                    </div>  
                </a>
                <a href="<?= base_url?>Catalogos/crudCatalogo/?catalogoActual=7" class="col-12 col-md-6 col-lg-3 mb-4 btn btn-catalogo px-5 px-lg-4">
                    <div class="card shadow-sm">
                        <img src="<?= base_url?>public/media/icons/catalogos/tipos-vehiculos.png" class="d-block my-width p-5">
                        <div class="card-body text-center">
                            <h4 class="card-title">Tipos de Vehículos </h4> 
                        </div>
                    </div>  
                </a>
                <a href="<?= base_url?>Catalogos/crudCatalogo/?catalogoActual=8" class="col-12 col-md-6 col-lg-3 mb-4 btn btn-catalogo px-5 px-lg-4">
                    <div class="card shadow-sm">
                        <img src="<?= base_url?>public/media/icons/catalogos/sub-marcas-coche.png" class="d-block my-width p-5">
                        <div class="card-body text-center">
                            <h4 class="card-title">Submarcas de Vehículos </h4> 
                        </div>
                    </div>  
                </a>


                <a href="<?= base_url?>Catalogos/crudCatalogo/?catalogoActual=9" class="col-12 col-md-6 col-lg-3 mb-4 btn btn-catalogo px-5 px-lg-4">
                    <div class="card shadow-sm">
                        <img src="<?= base_url?>public/media/icons/catalogos/catalogo_colonias.png" class="d-block my-width p-5">
                        <div class="card-body text-center">
                            <h4 class="card-title">Colonias</h4> 
                        </div>
                    </div>  
                </a>
                <a href="<?= base_url?>Catalogos/crudCatalogo/?catalogoActual=10" class="col-12 col-md-6 col-lg-3 mb-4 btn btn-catalogo px-5 px-lg-4">
                    <div class="card shadow-sm">
                        <img src="<?= base_url?>public/media/icons/catalogos/catalogo_calles.png" class="d-block my-width p-5">
                        <div class="card-body text-center">
                            <h4 class="card-title">Calles</h4> 
                        </div>
                    </div>  
                </a>

                <a href="<?= base_url?>Catalogos/crudCatalogo/?catalogoActual=11" class="col-12 col-md-6 col-lg-3 mb-4 btn btn-catalogo px-5 px-lg-4">
                    <div class="card shadow-sm">
                        <img src="<?= base_url?>public/media/icons/catalogos/catalogo_codigo_postal.png" class="d-block my-width p-5">
                        <div class="card-body text-center">
                            <h4 class="card-title">Codigos Postales</h4> 
                        </div>
                    </div>  
                </a>
                
                <a href="<?= base_url?>Catalogos/crudCatalogo/?catalogoActual=12" class="col-12 col-md-6 col-lg-3 mb-4 btn btn-catalogo px-5 px-lg-4">
                    <div class="card shadow-sm">
                        <img src="<?= base_url?>public/media/icons/catalogos/nombres_clave.png" class="d-block my-width p-5">
                        <div class="card-body text-center">
                            <h4 class="card-title">Nombres Claves</h4>
                        </div>
                    </div>  
                </a>
                <a href="<?= base_url?>Catalogos/crudCatalogo/?catalogoActual=13" class="col-12 col-md-6 col-lg-3 mb-4 btn btn-catalogo px-5 px-lg-4">
                    <div class="card shadow-sm">
                        <img src="<?= base_url?>public/media/icons/catalogos/Fuentes.jpg" class="d-block my-width p-5">
                        <div class="card-body text-center">
                            <h4 class="card-title">Procendencia de informacion</h4>
                        </div>
                    </div>  
                </a>
                <a href="<?= base_url?>Catalogos/crudCatalogo/?catalogoActual=14" class="col-12 col-md-6 col-lg-3 mb-4 btn btn-catalogo px-5 px-lg-4">
                    <div class="card shadow-sm">
                        <img src="<?= base_url?>public/media/icons/catalogos/areas.jpg" class="d-block my-width p-5">
                        <div class="card-body text-center">
                            <h4 class="card-title">Areas</h4>
                        </div>
                    </div>  
                </a>
                <a href="<?= base_url?>Catalogos/crudCatalogo/?catalogoActual=16" class="col-12 col-md-6 col-lg-3 mb-4 btn btn-catalogo px-5 px-lg-4">
                    <div class="card shadow-sm">
                        <img src="<?= base_url?>public/media/icons/catalogos/Entrevistador.png" width="50%" class="d-block my-width p-5">
                        <div class="card-body text-center">
                            <h4 class="card-title">Indicativos Entrevistador</h4>
                        </div>
                    </div>  
                </a>
                <a href="<?= base_url?>Catalogos/crudCatalogo/?catalogoActual=17" class="col-12 col-md-6 col-lg-3 mb-4 btn btn-catalogo px-5 px-lg-4">
                    <div class="card shadow-sm">
                        <img src="<?= base_url?>public/media/icons/catalogos/TiposEntrevistas.png" width="50%" class="d-block my-width p-5">
                        <div class="card-body text-center">
                            <h4 class="card-title">Tipos de Datos Entrevista</h4>
                        </div>
                    </div>  
                </a>
                <a href="<?= base_url?>Catalogos/crudCatalogo/?catalogoActual=18" class="col-12 col-md-6 col-lg-3 mb-4 btn btn-catalogo px-5 px-lg-4">
                    <div class="card shadow-sm">
                        <img src="<?= base_url?>public/media/icons/catalogos/camaras.jpg" width="50%" class="d-block my-width p-5">
                        <div class="card-body text-center">
                            <h4 class="card-title">Catalogo de Camaras</h4>
                        </div>
                    </div>  
                </a>
            </div>
        </div>
    </div>
</div>