<div class= "content">
    <div class="cabecera_modulo " ><br></div>
    <h1 class=" cabecera_modulo text-center"><strong>ESTADISTICAS</strong></h1> 
    <div class="row">
        <div class="container col-lg-1  justify-content-center text-center " id="navbarnavegacion">
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
        <div class="container col-lg-10  mb-1">
            <div class="col-12 text-center mt-3">
                <div class="chip">
                    <span class="v-a-middle" >
                        <?php
                            if (isset($_SESSION['userdataSIC']->rango_inicio_esta)) {
                                $r_inicio = $_SESSION['userdataSIC']->rango_inicio_esta;
                                $r_fin = $_SESSION['userdataSIC']->rango_fin_esta;
                                echo "Eventos Recibidos | Rango de (".$r_inicio.") a (".$r_fin.")";
                                
                            }
                            else{
                                echo "Eventos Recibidos";
                            }    
                        ?>
                    </span>
                </div>
            </div>
            <div class="row col-lg-12 mt-3">
                <div class="col-4">
                    <h6>Total registros: <strong id="id_total_grafica"></strong></h6>
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-opacity" onclick="return checarCadena(event)" ><img src="<?php echo base_url; ?>public/media/icons/eye.png" width="15%" >Generar graficas</button>
                </div>
                <div class="col-2">
                    <?php
                        if (isset($_SESSION['userdataSIC']->rango_inicio_esta)) {
                    ?>
                        <a class="btn btn-opacity" href="<?= base_url;?>Estadisticas/removeRangosFechasSesion"><img src="<?php echo base_url; ?>public/media/icons/nocalendario.png" width="15%">Quitar rango de fechas</a>
                    <?php
                        }
                    ?>
                </div>
                <div class="col-2">
                    <a  href="#" class="btn btn-opacity" data-toggle="modal" data-target="#filtro_rangos">
                        <img src="<?php echo base_url; ?>public/media/icons/calendario.png" width="15%">Asignar rango de fechas
                    </a>
                </div>
                <div class="col-2 mt-2 text-center">
                        <input type="checkbox" class="form-check-input" value="1" id="exacta" name="exacta" >
                        <label class="form-check-label" for="exacta">Busqueda Exacta Solo campos de graficas</label>			
                </div>
            </div>
            <div class="row col-lg-auto mt-3">
                <?php $cadena = (isset($data['cadena'])) ? $data['cadena'] : ""; ?>
                <div class="input-group justify-content-center">
                    <input id="id_search" type="search" name="busqueda" value="<?= $cadena; ?>" id="busqueda" class="col-10 border-right-0 border" placeholder="Ingrese los filtros correspondientes, utilice (,)" required="required" aria-describedby="button-addon2" onkeyup="return checarCadena(event)" onchange="return checarCadena(event)">
                    <span class="input-group-append">
                        <div id="search_button" class="input-group-text bg-transparent"><i class="material-icons md-18 ssc search" id="filtro">search</i></div>
                    </span>
                </div>
            </div>
            <div class="col-12 text-center my-3 mi_hide" id="id_sin_results_grafica" >
                <h6>No existen registros en este rango de fechas. Pruebe ingresando otro rango</h6>
            </div>
            <div class="row col-lg-auto mt-3">
                <div class="col-lg-6" id="por_zonas">
                    <canvas id="id_grafica_1" width="100" height="100"></canvas>
                </div>
                <div class="col-lg-6" id="Vectores">
                    <canvas id="id_grafica_6" width="100" height="100"></canvas>
                </div>
            </div>
            <div class="row col-lg-auto mt-5">
                <div class="col-lg-4" id="CSViolencia" >
                    <canvas id="id_grafica_2" width="100" height="100"></canvas>
                </div>
                <div class="col-lg-4" id="Dia" >
                    <canvas id="id_grafica_4" width="100" height="100"></canvas>
                </div>
                <div class="col-lg-4" id="Hora" >
                    <canvas id="id_grafica_5" width="100" height="100"></canvas>
                </div>
            </div>
            <div class="row col-lg-auto mt-5 d-flex justify-content-center">
                <div class="col-lg-12" id="Delito" >
                    <canvas id="id_grafica_3" width="400" height="400"></canvas>
                </div>
            </div>
            <br><br><br>
        </div>
    <br><br><br>
    </div>
    <div class="modal fade" id="ModalCenterFoto" tabindex="-1" role="dialog" aria-labelledby="myModalExito" aria-hidden="true" >
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <img name="nor" src="<?= base_url; ?>/public/media/images/logo6.png"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
        </div>
    </div>
</div>
    <!-- Modals content for date range-->
    <div class="modal fade" id="filtro_rangos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="title-width" id="exampleModalLabel">Filtrar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="material-icons">close</i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <form id="form_rangos" class="row filter-content mb-3" method="post" action="<?= base_url;?>Estadisticas/index?>">
                            <div class="col-3">
                                <h6>Rango de fecha</h6>
                            </div>
                            <div class="col-9">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group input-group-sm">
                                            <input type="date" class="form-control" id="id_date_1" name="rango_inicio" aria-describedby="fecha_filtro_1" required>
                                            <small id="fecha_filtro_1" class="form-text text-muted">Fecha inicio</small>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group input-group-sm">
                                            <input type="date" class="form-control" id="id_date_2" name="rango_fin" aria-describedby="fecha_filtro_2" required>
                                            <small id="fecha_filtro_2" class="form-text text-muted">Fecha fin</small>
                                        </div>
                                    </div>
                                </div>       
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="return aplicarRangos()">Aplicar</button>
                </div>
            </div>
        </div>
    </div>