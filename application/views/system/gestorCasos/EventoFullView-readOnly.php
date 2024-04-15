<div class= "content">
    <div class="cabecera_modulo" ><br></div>
    <div class="container">
        <!--vista para las tabs para edicion de los eventos  -->
        <?php if(!isset($data['titulo_1'])){ ?>
            <div class="paragraph-title d-flex justify-content-between mt-2 mb-1">
                <h5> <a href="<?= base_url; ?>GestorCasos">GESTOR DE EVENTOS </a> <span>/ VER</span></h5>
            </div>
        <?php }else{ ?>
            <div class="paragraph-title d-flex justify-content-between mt-2 mb-1">
                <h5> <a href="<?= base_url; ?>GestorCasos">GESTOR DE EVENTOS </a> <span>/ <?=$data['titulo_1']?></span></h5>
            </div>
        <?php } ?>
    </div>

    <div class="container-fluid" >
        <ul class="nav nav-tabs d-flex justify-content-center" id="tab_Casos" role="tablist">
            
            <li class="nav-item" role="presentation">
                <a class="nav-link d-flex align-items-center active" id="principales" data-toggle="tab" href="#principales0" role="tab" aria-controls="Principales" aria-selected="true" selected>
                    Datos Principales
                </a>
            </li>


            <li class="nav-item repetido" id="li-tablas" role="presentation">
                <a class="nav-link d-flex align-items-center" id="tablas" data-toggle="tab" href="#tablas0" role="tab" aria-controls="tablas" aria-selected="">
                    Datos Asociados al Evento
                </a>
            </li>
            <li class="nav-item repetido" id="li-imagenes" role="presentation">
                    <a class="nav-link d-flex align-items-center" id="imagenes" data-toggle="tab" href="#imagenes0" role="tab" aria-controls="imagenes" aria-selected="">
                        Imagenes del Evento
                    </a>
                </li>
            <?php
            if ($_SESSION['userdataSIC']->Modo_Admin == 1  || $_SESSION['userdataSIC']->Seguimientos[2] == 1) {
            ?>
                <li class="nav-item repetido" id="li-seguimiento" role="presentation">
                    <a class="nav-link d-flex align-items-center" id="seguimiento" data-toggle="tab" href="#seguimiento0" role="tab" aria-controls="seguimiento" aria-selected="">
                        Entrevistas del Evento
                    </a>
                </li>
            <?php
            }
            ?>

        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="principales0" role="tabpanel" aria-labelledby="datos_p">
                <?php include 'readOnly/datosPrincipales.php'; ?>
            </div>
            <div class="tab-pane fade" id="imagenes0" role="tabpanel" aria-labelledby="imagenes0">
                <?php include 'readOnly/Imagenes.php'; ?>
            </div>
            <?php
            if ($_SESSION['userdataSIC']->Modo_Admin == 1  || $_SESSION['userdataSIC']->Seguimientos[2] == 1) {
            ?>
                <div class="tab-pane fade" id="seguimiento0" role="tabpanel" aria-labelledby="seguimiento0">
                    <?php include 'readOnly/seguimiento.php'; ?>
                </div>
            <?php
            }
            ?>
            <div class="tab-pane fade" id="tablas0" role="tabpanel" aria-labelledby="tablas0">
                <?php include 'readOnly/tablasAsociadas.php'; ?>
            </div>
        </div>
    </div>
</div>
<br><br><br>