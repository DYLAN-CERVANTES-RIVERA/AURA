<?php
     class Login extends Controller{
        public $Usuario;    //variable para instanciar el modelo de Usuario
        public $GestorCaso; 

        public function __construct(){
            $this->Usuario = $this->model('Usuario');   //se instancia model Usuario y ya puede ser ocupado en el controlador
            $this->GestorCaso = $this->model('GestorCaso');
        }

        public function index(){
            if (isset($_SESSION['userdataSIC']->User_Name)) {
                header("Location: ".base_url."Estadisticas");
            }
            /* Se añade el llamado al archivo extra que contiene la función para hacer visible la contraseña*/
            $data = [
                'titulo'    => ' AURA | Inicio de sesión',
                'extra_css' => '<link rel="stylesheet" href="'. base_url . 'public/css/login/style.css">',
                'extra_js'  => '<script src="' . base_url . 'public/js/login/principal.js"></script>'
            ];

            //$this->view('templates/header', $data);
            $this->view('login/loginView', $data);
            //$this->view('templates/footer', $data);
        }

        public function login(){
            if (isset($_SESSION['userdataSIC'])) {
                header("Location: ".base_url."Estadisticas");
            }

            if (isset($_POST['enviarLogin'])) { //comprobacion de post correcto
                $success = $this->Usuario->loginUser($_POST);

                if ($success) {
                    
                    $this->setDataSession($success);
                    

                    //Se definen las variables pará pasarlas al modelo e insertarlas en la tabla historial
                    $user = $_SESSION['userdataSIC']->Id_Usuario;
                    $ip = $this->obtenerIp();
                    $descripcion = 'INICIO DE SESION: '.$_SESSION['userdataSIC']->User_Name;
                    $this->GestorCaso->historial($user,$ip,1,$descripcion);
                    
                    header("Location: ".base_url."Estadistica");
                }
                else{
                    $data = [
                        'titulo'    => 'AURA | Inicio de sesión',
                        'extra_css' => '<link rel="stylesheet" href="'. base_url . 'public/css/login/style.css">',
                        'extra_js'  => '<script src="' . base_url . 'public/js/login/principal.js"></script>'
                    ];

                    $data['post'] = $_POST;
                    $data['ErrorMessage'] = "CONTRASEÑA INCORRECTA INTENTALO NUEVAMENTE";
                    $this->view('login/loginView', $data);
                }
            }
            else{
                header("Location: ".base_url."Login");
            }
            
        }

        public function setDataSession($data){
           // print_r($data);//igual a succes
            $_SESSION['userdataSIC'] = $data;

            if(isset($_SESSION['userdataSIC']->User_Name)){
                $hoy = date("Y-m-d");
                $_SESSION['userdataSIC']->rango_inicio_gc = $hoy;
                $_SESSION['userdataSIC']->rango_fin_gc = $hoy;
                $_SESSION['userdataSIC']->rango_inicio_sg = $hoy;
                $_SESSION['userdataSIC']->rango_fin_sg = $hoy;
                $_SESSION['userdataSIC']->rango_inicio_es = $hoy;
                $_SESSION['userdataSIC']->rango_fin_es = $hoy;
                $_SESSION['userdataSIC']->rango_inicio_esta = $hoy;
                $_SESSION['userdataSIC']->rango_fin_esta = $hoy;
                $_SESSION['userdataSIC']->rango_inicio_pun = $hoy;
                $_SESSION['userdataSIC']->rango_fin_pun = $hoy;
                $_SESSION['userdataSIC']->Fecha_inicio_reporte = "";
                $_SESSION['userdataSIC']->Fecha_fin_reporte = "";
            }
            return;
        }

        public function logOut(){//redireccion al cerrar sesion
            if (!isset($_SESSION['userdataSIC'])) {
                header("Location: ".base_url."Login");
            }

            /*$user = $_SESSION['userdataSIC']->Id_Usuario;
            $ip = $this->obtenerIp();
            $descripcion = 'CERRO DE SESION: '.$_SESSION['userdataSIC']->User_Name;
            $this->GestorCaso->historial($user,$ip,39,$descripcion);*/

            unset($_SESSION['userdataSIC']);
            header("Location: ".base_url."Login");
        }
        public function obtenerIp()//Obtiene la ip para fines de escritura en el historial
        {
            $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
            $hosts = gethostbynamel($hostname);
            if (is_array($hosts)) {
                foreach ($hosts as $ip) {
                    return $ip;
                }
            }else{
                return $ip = '0.0.0.0';
            }
        }

        /**
         * Login GET 
         */
        public function loginGet(){
            if (isset($_SESSION['userdataSIC'])) {
                header("Location: ".base_url."Estadisticas");
            }

            $success = $this->Usuario->loginUser($_GET);

            if ($success) {
                $this->setDataSession($success);

                //Se definen las variables pará pasarlas al modelo e insertarlas en la tabla historial
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $ip = $this->obtenerIp();
                $descripcion = 'INICIO DE SESION: '.$_SESSION['userdataSIC']->User_Name;
                $this->GestorCaso->historial($user,$ip,1,$descripcion);//Escribe el movimiento en el historial
                
                header("Location: ".base_url."Estadisticas");
            }
            else{
                $data = [
                    'titulo'    => 'AURA | Inicio de sesión',
                    'extra_css' => '<link rel="stylesheet" href="'. base_url . 'public/css/login/style.css">',
                    'extra_js'  => ''
                ];

                $data['post'] = $_GET;
                $data['ErrorMessage'] = "CONTRASEÑA INCORRECTA INTENTALO NUEVAMENTE";
                $this->view('login/loginView', $data);
            }
        }

        /* ----- ----- ----- Endpoint Fetch ----- ----- ----- */
        public function loginFetch(){
            if (isset($_SESSION['userdataSIC'])) {
                $data_p['status']     = true;
                $data_p['loginExist'] = true;
                echo json_encode($data_p);
            }else{
                $success = $this->Usuario->loginUser($_POST);

                if ($success) {
                    $this->setDataSession($success);
                    $user = $_SESSION['userdataSIC']->Id_Usuario;
                    $ip = $this->obtenerIp();
                    $descripcion = 'INICIO DE SESION: '.$_SESSION['userdataSIC']->User_Name;
                    $this->GestorCaso->historial($user,$ip,1,$descripcion);//Escribe el movimiento en el historial
                    
                    $data_p['status'] = true;
                    echo json_encode($data_p);
                }
                else{
                    $data_p['status'] = false;
                    $data_p['error_message'] = 'CONTRASEÑA INCORRECTA INTENTALO NUEVAMENTE';

                    echo json_encode($data_p);
                }
            }
        }
     }
