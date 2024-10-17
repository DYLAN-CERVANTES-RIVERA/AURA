<div class= "content">
    <div class="cabecera_modulo" ><br></div>
    <div class="container">
        <!--vista para las tabs para edicion de los eventos  -->
        <?php if(!isset($data['titulo_1'])){ ?>
            <div class="paragraph-title d-flex justify-content-between mt-2 mb-1">
                <h5> <a href="<?= base_url; ?>Seguimientos">REDES DE VÍNCULOS</a> <span>/ EDITAR</span></h5>
            </div>
        <?php }else{ ?>
            <div class="paragraph-title d-flex justify-content-between mt-2 mb-1">
                <h5> <a href="<?= base_url; ?>Seguimientos">REDES DE VÍNCULOS</a> <span>/ <?=$data['titulo_1']?></span></h5>
            </div>
        <?php } ?>
    </div>
    <input class='mi_hide' id='visual' value= <?php echo (isset($_SESSION['userdataSIC']->Visualizacion)&&$_SESSION['userdataSIC']->Visualizacion==1)?'1':'0';?>></input>
    <input class='mi_hide' id='ALTO_IMPACTO' value= <?php echo ($_SESSION['userdataSIC']->Red[0]);?>></input>
    <input class='mi_hide' id='ADMIN' value= <?php echo ($_SESSION['userdataSIC']->Modo_Admin);?>></input>
    <div class="container-fluid" id="contenedor_red" >
        <ul class="nav nav-tabs d-flex justify-content-center" id="tab_gestor" role="tablist">
            <li class="nav-item repetido" id="li-principales" role="presentation">
                <a class="nav-link d-flex align-items-center active" id="principales" data-toggle="tab" href="#principales0" role="tab" aria-controls="Principales" aria-selected="true" selected>
                    Seguimiento 
                </a>
            </li>

            <li class="nav-item repetido" id="li-Personas" role="presentation">
                <a class="nav-link d-flex align-items-center" id="Personas" data-toggle="tab" href="#Personas0" role="tab" aria-controls="Personas" aria-selected="">
                    Personas
                </a>
            </li>

            <li class="nav-item repetido" id="li-vehiculos" role="presentation">
                <a class="nav-link d-flex align-items-center" id="vehiculos" data-toggle="tab" href="#vehiculos0" role="tab" aria-controls="vehiculos" aria-selected="">
                    Vehículos
                </a>
            </li> 
            <li class="nav-item repetido mi_hide" id="li-Domicilio" role="presentation">
                <a class="nav-link d-flex align-items-center" id="Domicilio" data-toggle="tab" href="#Domicilio0" role="tab" aria-controls="Domicilio" aria-selected="">
                    Domicilios
                </a>
            </li> 
            <li class="nav-item repetido mi_hide" id="li-Antecedente" role="presentation">
                <a class="nav-link d-flex align-items-center" id="Antecedente" data-toggle="tab" href="#Antecedente0" role="tab" aria-controls="Antecedente" aria-selected="">
                    Antecedentes
                </a>
            </li> 
            <li class="nav-item repetido mi_hide" id="li-Forencia" role="presentation">
                <a class="nav-link d-flex align-items-center" id="Forencia" data-toggle="tab" href="#Forencia0" role="tab" aria-controls="Forencia" aria-selected="">
                    Datos
                </a>
            </li>
            <li class="nav-item repetido mi_hide" id="li-RedesSociales" role="presentation">
                <a class="nav-link d-flex align-items-center" id="RedesSociales" data-toggle="tab" href="#RedesSociales0" role="tab" aria-controls="RedesSociales" aria-selected="">
                    Redes Sociales
                </a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="principales0" role="tabpanel" aria-labelledby="datos_p">
                <?php include 'tablas/editarSeguimientoView.php'; ?>
            </div>
            <div class="tab-pane fade show" id="Personas0" role="tabpanel" aria-labelledby="Personas0">
                <?php include 'tablas/personaSeguimientoView.php'; ?>
            </div>
            <div class="tab-pane fade show " id="vehiculos0" role="tabpanel" aria-labelledby="vehiculos0">
                <?php include 'tablas/vehiculoSeguimientoView.php'; ?>
            </div> 
            <div class="tab-pane fade show mi_hide" id="Domicilio0" role="tabpanel" aria-labelledby="Domicilio0">
                <?php include 'tablas/domicilioSeguimientoView.php'; ?>
            </div> 
            <div class="tab-pane fade show mi_hide" id="Antecedente0" role="tabpanel" aria-labelledby="Antecedente0">
                <?php include 'tablas/antecedenteSeguimientoView.php'; ?>
            </div> 
            <div class="tab-pane fade show mi_hide" id="Forencia0" role="tabpanel" aria-labelledby="Forencia0">
                <?php include 'tablas/forenciaSeguimientoView.php'; ?>
            </div> 
            <div class="tab-pane fade show mi_hide" id="RedesSociales0" role="tabpanel" aria-labelledby="RedesSociales0">
                <?php include 'tablas/redesSocialesSeguimientoView.php'; ?>
            </div> 
        </div>
    </div>
</div>
<br><br><br>