<div class= "content">
    <div class="cabecera_modulo" ><br></div>
    <div class="container">
        <!--vista para las tabs para edicion de los eventos  -->
        <?php if(!isset($data['titulo_1'])){ ?>
            <div class="paragraph-title d-flex justify-content-between mt-2 mb-1">
                <h5> <a href="<?= base_url; ?>GestorCasos">GESTOR DE EVENTOS </a> <span>/ EDITAR</span></h5>
            </div>
        <?php }else{ ?>
            <div class="paragraph-title d-flex justify-content-between mt-2 mb-1">
                <h5> <a href="<?= base_url; ?>GestorCasos">GESTOR DE EVENTOS </a> <span>/ <?=$data['titulo_1']?></span></h5>
            </div>
        <?php } ?>
    </div>
    <?php
        $miHide =  ($_SESSION['userdataSIC']->Modo_Admin == 1  || $_SESSION['userdataSIC']->Seguimientos[1] == 1)  ? '':'mi_hide';
    ?>
    <div class=" <?= "tareas_asignadas_status ".$miHide; ?>">
        <a id="button-asignacion" class="btn btn-primary btn-asignadas" data-toggle="tooltip" title="REPORTE ZEN"></a>
        <div class="asignacion-content" id="asignacion-content">
            <h3 class="titulo-azul-grueso">REPORTE ZEN</h3>
        </div>
    </div>

    <div class="container-fluid" >
        <ul class="nav nav-tabs d-flex justify-content-center" id="tab_gestor" role="tablist">
            <li class="nav-item repetido" id="li-principales" role="presentation">
                <a class="nav-link d-flex align-items-center active" id="principales" data-toggle="tab" href="#principales0" role="tab" aria-controls="Principales" aria-selected="true" selected>
                    Datos Principales del Evento
                </a>
            </li>
            <li class="nav-item repetido" id="li-fotos" role="presentation">
                <a class="nav-link d-flex align-items-center" id="fotos" data-toggle="tab" href="#fotos0" role="tab" aria-controls="Fotos" aria-selected="">
                    Imagenes de Video - Fotos
                </a>
            </li>
            <li class="<?= "nav-item repetido ".$miHide; ?>" id="li-entrevistas" role="presentation">
                <a class="nav-link d-flex align-items-center" id="entrevistas" data-toggle="tab" href="#entrevistas0" role="tab" aria-controls="Entrevistas" aria-selected="">
                    Entrevistas - Culminacion de seguimiento del Evento
                </a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="principales0" role="tabpanel" aria-labelledby="datos_p">
                <?php include 'tabs/editarEventoView.php'; ?>
            </div>
            <div class="tab-pane fade show " id="fotos0" role="tabpanel" aria-labelledby="fotos0">
                <?php include 'tabs/imagenes.php'; ?>
            </div>
            <div class="<?= "tab-pane fade show ".$miHide; ?>" id="entrevistas0" role="tabpanel" aria-labelledby="entrevistas0">
                <?php include 'tabs/entrevistas.php'; ?>
            </div>
        </div>
    </div>
</div>
<br><br><br>