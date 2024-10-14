<!--vista del modulo de gestor de casos -->
<div class= "content">
    <div class="cabecera_modulo" ><br></div>
    <h1 class="cabecera_modulo text-center"><strong>GESTOR DE EVENTOS DELICTIVOS</strong></h1> 
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
                    if ($_SESSION['userdataSIC']->Modo_Admin == 1  || $_SESSION['userdataSIC']->Red[2] == 1) {
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
                            <a class="btn btn-opacity" title="PUNTOS ALTO IMPACTO" data-toggle="tooltip" href="<?= base_url;?>Puntos"><div><img class="circular--square" src="<?php echo base_url; ?>public/media/icons/puntos.png" width="50%" ></div></a>
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
            <!--Barra de operaciones de filtrado y búsqueda-->
            <div class="row">
                <div class="col-6 col-lg-2 mr-lg-auto my-2 my-lg-auto  d-flex justify-content-center">
                    
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-filtro" data-toggle="dropdown" id="id_filtros">
                        <i class="material-icons md-30 v-a-middle" >filter_alt</i>
                        <span class="v-a-middle" >Filtros</span>
                    </button>
                    <!--Dropdown filter content-->
                    <div class="dropdown-menu" aria-labelledby="id_filtros">
                        
                        <a class="dropdown-item <?= ($data['filtroActual']==1)?'active':'';?>" href="<?= base_url;?>GestorCasos/index/?filtro=1">Todos los Eventos</a>
                        <a class="dropdown-item <?= ($data['filtroActual']==1)?>" href="<?= base_url;?>GestorCasos/index/?filtro=2">Vista de Eventos Habilitados</a>
                        <a class="dropdown-item <?= ($data['filtroActual']==1)?>" href="<?= base_url;?>GestorCasos/index/?filtro=3">Vista de Eventos Deshabilitados</a>
                        <a class="dropdown-item <?= ($data['filtroActual']==1)?>" href="<?= base_url;?>GestorCasos/index/?filtro=4">Busqueda a Quien se Asigno Evento</a>
                        <a class="dropdown-item <?= ($data['filtroActual']==1)?>" href="<?= base_url;?>GestorCasos/index/?filtro=5">Busqueda Solo por Folio AURA Evento</a>
                        <a class="dropdown-item <?= ($data['filtroActual']==1)?>" href="<?= base_url;?>GestorCasos/index/?filtro=6">Eventos Consultados</a>
                        <a class="dropdown-item <?= ($data['filtroActual']==1)?>" href="<?= base_url;?>GestorCasos/index/?filtro=7">Eventos No Consultados</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" class="btn btn-filtro" data-toggle="modal" data-target="#filtro_rangos">
                            <span class="v-a-middle" >Por rango de fechas</span>
                        </a>
                    </div>
                </div>
                <div class="col-6 col-lg-2 mr-lg-auto my-2 my-lg-auto  d-flex justify-content-center">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-filtro" data-toggle="dropdown" id="columnas_filtro">
                        <i class="material-icons md-30 v-a-middle" >table_chart</i>
                        <span class="v-a-middle" >Columnas</span>
                    </button>
                    <!--Dropdown filter content-->
                    <div id="id_dropdownColumns" class="dropdown-menu" aria-labelledby="columnas_filtro">
                        <?= $data['dropdownColumns'];?>
                    </div>
                </div>

                <div class="col-6 col-lg-2 mr-lg-auto my-auto  d-flex justify-content-center" id="id_total_rows">
                    Total registros: <?= (isset($data['total_rows']))?$data['total_rows']:"---";?>
                </div>
                <div class="col-6 col-lg-1 mr-lg-auto my-2 my-lg-auto justify-content-center">
                    <div class="row col-12">
                        <div id="buttonsExport" class="col-12">
                            <?php 
                                $cadenaExport = (isset($data['cadena'])) ? ("&cadena=" . $data['cadena']) : "";
                                $filtroActual = "&filtroActual=".$data['filtroActual'];
                            ?>

                            <a id="id_link_excel" href="<?= base_url ?>GestorCasos/exportarInfo/?tipo_export=<?= "EXCEL".$cadenaExport.$filtroActual; ?>" class="btn" data-toggle="tooltip" data-placement="bottom" title="Exportar a Excel">
                                <i class="material-icons ssc md-36">description</i>
                                <!--img src="<?= base_url ?>public/media/icons/excelIcon.png" width="40px"--!-->
                            </a>
                            <a id="id_link_pdf" href="<?= base_url ?>GestorCasos/exportarInfo/?tipo_export=<?= "PDF".$cadenaExport.$filtroActual; ?>" target="_blank" class="btn mi_hide" data-toggle="tooltip" data-placement="bottom" title="Exportar a PDF">
                                <i class="material-icons ssc md-36">picture_as_pdf</i>
                                <!--img src="<?= base_url ?>public/media/icons/pdfIcon.png" width="40px"--!-->
                            </a>
                        </div>

                    </div>

                </div>
                <div class="col-6 col-lg-2 mr-lg-auto my-2 my-lg-auto">
                
                    <a class="btn btn-opacity <?= ($_SESSION['userdataSIC']->Evento_D[3]||$_SESSION['userdataSIC']->Seguimientos[3])?'':'mi_hide';?>" href="<?= base_url;?>GestorCasos/nuevoEvento" ><img src="<?php echo base_url; ?>public/media/icons/iconnuevo.png" width="15%">Nuevo Evento</a>

                </div>
            </div>
        
            <div class="row col-lg-auto mt-3">
                <?php $cadena = (isset($data['cadena'])) ? $data['cadena'] : ""; ?>
                <div class="input-group">
                    <input id="id_search" type="search" name="busqueda" value="<?= $cadena; ?>" id="busqueda" class="form-control py-2 border-right-0 border" placeholder="Buscar" required="required" aria-describedby="button-addon2" onkeyup="return checarCadena(event)" onchange="return checarCadena(event)">
                    <span class="input-group-append">
                        <div id="search_button" class="input-group-text bg-transparent"><i class="material-icons md-18 ssc search" id="filtro">search</i></div>
                    </span>
                </div>
            </div>

            <div class="row d-flex justify-content-center mt-3">
                <div class="col-auto">
                    <span>Filtro: </span>
                    <div class="chip-azul">
                        <span class="v-a-middle" >
                            <?php
                                if (isset($_SESSION['userdataSIC']->rango_inicio_gc)) {
                                    $r_inicio = $_SESSION['userdataSIC']->rango_inicio_gc;
                                    $r_fin = $_SESSION['userdataSIC']->rango_fin_gc;
                                    echo (isset($data['filtroNombre']))?$data['filtroNombre']." | Rangos de (".$r_inicio.") a (".$r_fin.")":"Vista general";
                                    
                                }
                                else{
                                    echo (isset($data['filtroNombre']))?$data['filtroNombre']:"Vista general";
                                }
                                
                            ?>
                        </span>
                    </div>
                    
                </div>
                <?php
                    if (isset($_SESSION['userdataSIC']->rango_inicio_gc)) {
                        ?>
                            <a class="btn btn-opacity" href="<?= base_url;?>GestorCasos/removeRangosFechasSesion/?filtroActual=<?= $data['filtroActual'];?>">mostrar todo</a>
                        <?php
                    }
                ?>
            </div>  
            
            <!--Tabla con la información-->
            <div class="row col-lg-auto mt-2">
                <div class="col-auto table-responsive">
                    <table class="table table-striped">
                        <thead class="thead-myTable text-center">
                            <tr id="id_thead" >
                                <?php
                                    //se imprimen los encabezados conforme al catálogo seleccionado 
                                    echo (isset($data['infoTable']['header']))?$data['infoTable']['header']:"";
                                ?>
                            </tr>
                        </thead>
                        <tbody id="id_tbody" class="text-justify">
                            <?php
                                //se imprime todos los registros tabulados de la consulta
                                echo (isset($data['infoTable']['body']))?$data['infoTable']['body']:"";
                            ?>
                        </tbody>
                    </table>
                </div>      
            </div>

            <!--Despliegue de Links de Pagination-->
            <div class="container mt-3 mb-5">
                <div class="row d-flex justify-content-center">
                    <div class="col-auto">
                        <nav aria-label="Page navigation example ">
                            <ul id="id_pagination" class="pagination">
                                <?php
                                    echo (isset($data['links']))?$data['links']:"";
                                ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
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
                    <form id="form_rangos" class="row filter-content mb-3" method="post" action="<?= base_url;?>GestorCasos/index/?filtro=<?= $data['filtroActual']?>">
                        <div class="col-3">
                            <h6>Rango de folios</h6>
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

<!--Input de filtro para Fetch busqueda por cadena-->
<input value="<?= $data['filtroActual']?>" id="filtroActual" type="hidden" >
<!-- MODAL DE GRÁFICAS -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-12 text-center">
                        <h5 class="modal-title text-center">Graficas SIC</h5>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-auto mx-auto my-auto">
                            <div class="chip">
                                <span class="v-a-middle" >
                                    <?php
                                        if (isset($_SESSION['userdataSIC']->rango_inicio_gc)) {
                                            $r_inicio = $_SESSION['userdataSIC']->rango_inicio_gc;
                                            $r_fin = $_SESSION['userdataSIC']->rango_fin_gc;
                                            echo "Eventos | Rangos de (".$r_inicio.") a (".$r_fin.")";
                                            
                                        }
                                        else{
                                            echo "Eventos";
                                        }    
                                    ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-12 text-center mt-3">
                            <h6>Total registros: <strong id="id_total_grafica"></strong></h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center my-3 mi_hide" id="id_sin_results_grafica" >
                            <h6>No existen registros en este rango de fechas. Pruebe ingresando otro rango</h6>
                        </div>
                        <div class="col-12">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="id_por_zonas" data-toggle="tab" href="#por_zonas" role="tab" aria-controls="home" aria-selected="true">Zonas</a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link" id="id_Vectores" data-toggle="tab" href="#Vectores" role="tab" aria-controls="profile" aria-selected="false">Vectores</a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link" id="id_CSViolencia" data-toggle="tab" href="#CSViolencia" role="tab" aria-controls="profile" aria-selected="false">Con/Sin Violencia</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="id_Delito" data-toggle="tab" href="#Delito" role="tab" aria-controls="profile" aria-selected="false">Tipo de Delito</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="id_dia" data-toggle="tab" href="#Dia" role="tab" aria-controls="profile" aria-selected="false">Dias de la Semana</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="id_hora" data-toggle="tab" href="#Hora" role="tab" aria-controls="profile" aria-selected="false">Horas en el Dia</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="por_zonas" role="tabpanel">
                                    <canvas id="id_grafica_1" width="400" height="400"></canvas>
                                </div>
                                <div class="tab-pane fade" id="Vectores" role="tabpanel">
                                    <canvas id="id_grafica_6" width="400" height="400"></canvas>
                                </div>
                                <div class="tab-pane fade" id="CSViolencia" role="tabpanel" >
                                    <canvas id="id_grafica_2" width="400" height="400"></canvas>
                                </div>
                                <div class="tab-pane fade" id="Delito" role="tabpanel" >
                                    <canvas id="id_grafica_3" width="400" height="400"></canvas>
                                </div>
                                <div class="tab-pane fade" id="Dia" role="tabpanel" >
                                    <canvas id="id_grafica_4" width="400" height="400"></canvas>
                                </div>
                                <div class="tab-pane fade" id="Hora" role="tabpanel" >
                                    <canvas id="id_grafica_5" width="400" height="400"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>