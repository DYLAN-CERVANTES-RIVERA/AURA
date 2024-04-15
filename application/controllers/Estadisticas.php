<?php
class Estadisticas extends Controller
{
        /*controlador del modulo Entrevistas
        NOMENCLATURA DE MOVIMIENTOS EN EL HISTORIAL 
        37.-CONSULTA BUSQUEDA EN MODULO ESTADISTICAS
    */
    public function __construct(){
        $this->Estadistica = $this->model('Estadistica');
    }
    public function index(){
        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }
        //PROCESAMIENTO DE RANGO DE FOLIOS
        if (isset($_POST['rango_inicio']) && isset($_POST['rango_fin'])) {
            $_SESSION['userdataSIC']->rango_inicio_esta = $_POST['rango_inicio'];
            $_SESSION['userdataSIC']->rango_fin_esta = $_POST['rango_fin'];
        }
        $data = [
            'titulo'    => 'AURA | Estadisticas',
            'extra_css' => '<link rel="stylesheet" href="' . base_url . 'public/css/system/estadistica/index.css">',
            'extra_js'  => '<script src="' . base_url . 'public/js/system/estadistica/index.js"></script>'.
                            '<script src="https://cdn.jsdelivr.net/npm/chart.js@3.3.2/dist/chart.min.js"></script>'.
                            '<script src="' . base_url . 'public/js/system/estadistica/graficas.js"></script>'
        ];
     

        $this->view('templates/header', $data);
        $this->view('system/estadistica/EstadisticaView', $data);
        $this->view('templates/footer', $data);
    }
    //funcion para borrar variable sesión para filtro de rangos de fechas
    public function removeRangosFechasSesion(){
       
        unset($_SESSION['userdataSIC']->rango_inicio_esta);
        unset($_SESSION['userdataSIC']->rango_fin_esta);

        header("Location: " . base_url . "Estadisticas/index/");
        exit();
       
    }

    public function obtenerIp(){//Obtiene la ip de la computadora para historial principalmente
        $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $hosts = gethostbynamel($hostname);
        if (is_array($hosts)) {
            foreach ($hosts as $ip) {
                return $ip;
            }
        } else {
            return $ip = '0.0.0.0';
        }
    }

    /* ----- ----- ----- Funcion para generar las graficas  ----- ----- ----- */
    public function getDatagraficas(){//Genera las graficas conforme la busqueda
        if (isset($_POST['cadena'])) {//Comprueba si existe una cadena para buscar
            $cadena = trim($_POST['cadena']);
            $exacta = $_POST['exacta'];
            if (strlen($cadena) > 0) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $ip = $this->obtenerIp();
                $descripcion = 'CONSULTA EN MODULO DE ESTADISTICA: ' . $cadena .' '.$_SESSION['userdataSIC']->User_Name;
                $this->Estadistica->historial($user, $ip, 37, $descripcion);//Escribe en el historial el movimiento
            }
            echo json_encode($this->Estadistica->getDatosGraficas($cadena,$exacta));
        }
        
    }
}
?>