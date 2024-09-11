<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?= $data['titulo'] ?></title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="<?php echo base_url ?>public/css/bootstrap/bootstrap.css">
    <!-- ----- ----- ----- Root CSS ----- ----- ----- -->
    <link rel="stylesheet" href="<?php echo base_url ?>public/css/general/root.css">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="<?php echo base_url ?>public/css/template/style.css">
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="<?php echo base_url ?>public/css/template/header/customScrollbar.min.css">
    <!--Material Icons-->
    <link rel="stylesheet" href="<?= base_url ?>public/media/icons/material_design_icons/material-icons.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	  <meta name="theme-color" content="#6D0725" />
    <link rel='shortcut icon' type='image/ico' href='<?php echo base_url ?>public/media/icons/logo.ico'/>

    <script src= '<?php echo base_url ?>public/js/libraries/autocomplete.js'></script>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url ?>public/css/libraries/autocomplete.js'>

      <!-- Incluir SweetAlert desde la CDN -->
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Font Awesome JS -->
    <script defer src="<?php echo base_url ?>public/js/template/header/solid.js"></script>
    <script defer src="<?php echo base_url ?>public/js/template/header/fontawesome.js"></script>

    <?php if (isset($data['extra_css']))  echo $data['extra_css'] ?>
    <?php if (isset($data['extra'])) echo $data['extra'] ?>
  </head>
  <body>
    <div id="navbarhead">
      <nav class="navbar" id="sidenav_p">
        <div class="col-lg-4">
          <img class="box" src="<?php echo base_url; ?>public/media/images/logo2.png">
          <img class="box2" src="<?php echo base_url; ?>public/media/images/logo22.png">
        </div>
        <div class="col-lg-4 content-center mt-2">
          <h5 class="titulo_header">SECRETARÍA DE SEGURIDAD CIUDADANA</h5>
          <h6 class="subtitulo_header">DIRECCION DE INTELIGENCÍA Y POLÍTICA CRIMINAL</h6>
        </div>
        <div class="col-lg-4">
          <div class="row col-lg-12">
            <div class="col-lg-4">
              <a href="<?= base_url; ?>Login/logOut">
                <img class="box3" src="<?php echo base_url; ?>public/media/images/cerrar.png">
                <h6 class="subtitulo_header">Cerrar sesion</h6>
              </a>
            </div>
            <div class="col-lg-6 mt-1">
              <nav id = "nav_letrero" >
                <h6 class="text_bienvenido"><?= mb_strtoupper($_SESSION['userdataSIC']->Nombre.' '.$_SESSION['userdataSIC']->Ap_Paterno);?></h6> 
                <h6 class="text_bienvenido">BIENVENIDO(A)</h6>
              </nav>
            </div>
            <div class="col-lg-2">
              <a href="<?= base_url; ?>Cuenta">
                <img class="box4" src="<?php echo base_url; ?>public/media/images/default.png">
                <h6 class="subtitulo_header">Perfil</h6>
              </a>
            </div>
          </div>
        </div>
      </nav>
      <div id = "linea2"></div>
      <div id = "linea"></div>
      <?php
        $miHideGlobal =  ($_SESSION['userdataSIC']->Modo_Admin == 1  || $_SESSION['userdataSIC']->Seguimientos[2] == 1)  ? '':'mi_hide';
      ?>
      <div class="<?= "row mt-1 justify-content-end ".$miHideGlobal; ?>">
          <a id="button-global" class="btn btn-primary btn-asignadas-global mb-4" data-toggle="tooltip">Reporte Completados en Campo</a>    
      </div>
    </div>
    
  </body>
</html>