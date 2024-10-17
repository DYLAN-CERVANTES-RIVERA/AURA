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
    <input class='mi_hide' id='ALTO_IMPACTO' value= <?php echo ($_SESSION['userdataSIC']->Red[0]);?>></input>
    <input class='mi_hide' id='ADMIN' value= <?php echo ($_SESSION['userdataSIC']->Modo_Admin);?>></input>
    <div class="container-fluid" id="contenedor_red">
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
                    Vehiculos
                </a>
            </li> 
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="principales0" role="tabpanel" aria-labelledby="datos_p">
                <?php include 'readOnly/PrincipalesReadOnly.php'; ?>
            </div>
            <div class="tab-pane fade show" id="Personas0" role="tabpanel" aria-labelledby="Personas0">
                <?php include 'readOnly/PersonasReadOnly.php'; ?>
            </div>
            <div class="tab-pane fade show " id="vehiculos0" role="tabpanel" aria-labelledby="vehiculos0">
                <?php include 'readOnly/VehiculosReadOnly.php'; ?>
            </div>
        </div>
    </div>
</div>
<br><br><br>