<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo  base_url; ?>public/css/bootstrap/bootstrap.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url ?>public/css/general/root.css">
    <?php if (isset($data['extra_css']))  echo $data['extra_css'] ?>

    <title><?php echo $data['titulo'] ?></title>
</head>

<body class="body2" style="background-image:url('<?= base_url;?>public/media/images/BARRAS2.png');">
    <div class='row justify-content-center'>


        <div class="abs-center col-6">
            <div class="row vidrio1 d-flex justify-content-center"  >
                <div class="card-body p-5 text-center">
                  
                    <img src="<?php echo base_url; ?>public/media/images/logo2.png" width="370" height="230" class="mb-5">
                    <br>

                    <form action="<?= base_url;?>Login/login" method="POST" class="needs-validation" novalidate autocomplete="off" >
                        <div class="form-group">
                            <div class=" col-lg-12  mb-4">
                                <input name="User_Name" type="text" class="form-control form-control-lg" id="usuario" placeholder="Usuario" required value="<?php echo(isset($data['post']['User_Name']))? $data['post']['User_Name']:"";?>">
                                <div class="invalid-feedback">Este campo es obligatorio</div>
                            </div>
                            <div class="col-lg-12 mb-4">
                                <input name="Password" type="password" class="form-control form-control-lg" id="contrasena" placeholder="Contraseña" required>
                                <div class="invalid-feedback">Este campo es obligatorio</div>
                            </div>
                            <div class="mb-3" >
                                <span style="color: red;"><?php echo (isset($data['ErrorMessage']))?$data['ErrorMessage']:"";?></span>
                            </div>
                        </div>
                        <br><br><br>
                        <div class="form-check mt-n5" align="center">
                            <input class="form-check-input" type="checkbox" value="" id="check_pass">
                            <label class="subtitulo-azul-grueso " for="check_pass">
                                Mostrar contraseña
                            </label>
                           
                        </div>
                        <br>
                        <button class="btn btn-ssc btn-lg btn-block " type="submit" name="enviarLogin" >Entrar</button>

                    </form>
                    <br><br>
                 
                </div>        
                <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
                <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
                <script src="<?php echo base_url; ?>public/js/bootstrap/bootstrap.js" ></script>
                <?php if (isset($data['extra_js']))  echo $data['extra_js']; ?>
            </div>
        </div> 
    </div>
    <footer>
        <div class="footer-text text-center text-white">
            AURA
        </div>
    </footer>
       
</body>

</html>