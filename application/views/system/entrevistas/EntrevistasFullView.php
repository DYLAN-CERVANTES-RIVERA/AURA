<div class= "content">
    <div class="cabecera_modulo" ><br></div>
    <div class="container">
        <!--vista para las tabs para edicion de los eventos  -->
        <?php if(!isset($data['titulo_1'])){ ?>
            <div class="paragraph-title d-flex justify-content-between mt-2 mb-1">
                <h5> <a href="<?= base_url; ?>Entrevistas">ENTREVISTAS DETENIDOS</a> <span>/ EDITAR</span></h5>
            </div>
        <?php }else{ ?>
            <div class="paragraph-title d-flex justify-content-between mt-2 mb-1">
                <h5> <a href="<?= base_url; ?>Entrevistas">ENTREVISTAS DETENIDOS</a> <span>/ <?=$data['titulo_1']?></span></h5>
            </div>
        <?php } ?>
    </div>
    <input class='mi_hide' id='visual' value= <?php echo (isset($_SESSION['userdataSIC']->Visualizacion)&&$_SESSION['userdataSIC']->Visualizacion==1)?'1':'0';?>></input>
    <div class="container-fluid" >
        <ul class="nav nav-tabs d-flex justify-content-center" id="tab_gestor" role="tablist">
            <li class="nav-item repetido" id="li-personas" role="presentation">
                <a class="nav-link d-flex align-items-center active" id="personas" data-toggle="tab" href="#personas0" role="tab" aria-controls="Principales" aria-selected="true" selected>
                    Persona Entrevistada  
                </a>
            </li>
            <li class="nav-item repetido" id="li-Entrevista" role="presentation">
                <a class="nav-link d-flex align-items-center" id="Entrevista" data-toggle="tab" href="#Entrevista0" role="tab" aria-controls="Entrevista" aria-selected="">
                    Entrevistas
                </a>
            </li>
            <li class="nav-item repetido" id="li-Forensia" role="presentation">
                <a class="nav-link d-flex align-items-center" id="Forensia" data-toggle="tab" href="#Forensia0" role="tab" aria-controls="Forensia" aria-selected="">
                    Datos Relevantes Entrevista
                </a>
            </li>
            <li class="nav-item repetido" id="li-Ubicacion" role="presentation">
                <a class="nav-link d-flex align-items-center" id="Ubicacion" data-toggle="tab" href="#Ubicacion0" role="tab" aria-controls="Ubicacion" aria-selected="">
                    Ubicaciones Relevantes Entrevista
                </a>
            </li>
            <li class="nav-item repetido" id="li-Social" role="presentation">
                <a class="nav-link d-flex align-items-center" id="Social" data-toggle="tab" href="#Social0" role="tab" aria-controls="Social" aria-selected="">
                    Redes Sociales Entrevista
                </a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="personas0" role="tabpanel" aria-labelledby="datos_p">
                <?php include 'tablas/PersonaEntrevistada.php'; ?>
            </div>
            <div class="tab-pane fade show" id="Entrevista0" role="tabpanel" aria-labelledby="Entrevista0">
                <?php include 'tablas/EntrevistaDetenido.php'; ?>
            </div>
            <div class="tab-pane fade show " id="Forensia0" role="tabpanel" aria-labelledby="Forensia0">
                <?php include 'tablas/RelevantesForensia.php'; ?>
            </div> 
            <div class="tab-pane fade show " id="Ubicacion0" role="tabpanel" aria-labelledby="Ubicacion0">
                <?php include 'tablas/RelevantesUbicacion.php'; ?>
            </div>
            <div class="tab-pane fade show " id="Social0" role="tabpanel" aria-labelledby="Social0">
                <?php include 'tablas/RelevantesSociales.php'; ?>
            </div>  
        </div>
    </div>
</div>
<br><br><br>