<?php
    
    define('app_path', dirname(dirname(__FILE__)));     //Ruta de la app
    define('BASE_PATH', realpath(dirname(__FILE__) . '/../..').'/'); //BASE_PATH del proyecto
    define('base_url', 'http://172.18.9.145/AURA/'); //Ruta de la url
    define('site_name', 'Sistema de Investigación');       //Nombre del sitio

    //Configuración de acceso a la base de datos
    define ('DB_HOST', 'localhost');
    define ('DB_USER', 'root');
    define ('DB_PASSWORD', '');
    define ('DB_NAME', 'casos');
    define ('DB_NAME2', 'planeacion');
    define ('DB_TAREA', 'asignador_tareas');

    //key de encryptación de información
    define ('CRYPTO_KEY', 'planeacion_xdlol123');
    //valores globales del número máximo de registros por Pagination
    define ('NUM_MAX_REG_PAGE', 7);
    define ('GLOBAL_LINKS_EXTREMOS', 4);
    //globals GESTOS DE CASOS
    define('MIN_FILTRO_GC', 1);
    define('MAX_FILTRO_GC', 15);
    //globals SEGUIMIENTOS
    define('MIN_FILTRO_SG', 1);
    define('MAX_FILTRO_SG', 6);
    //globals ENTREVISTAS
    define('MIN_FILTRO_ES', 1);
    define('MAX_FILTRO_ES', 3);
    //globals PUNTOS
    define('MIN_FILTRO_PUN', 1);
    define('MAX_FILTRO_PUN', 3);
    //globales USUARIOS
    define('MIN_FILTRO_USER', 1);
    define('MAX_FILTRO_USER', 3);
    // min/max catálogos hasta ahora
    define('MIN_CATALOGO', 1);
    define('MAX_CATALOGO', 18);

    //globales HISTORIAL
    define('MIN_FILTRO_HIS',1);
    define('MAX_FILTRO_HIS',11);

    //API KEY FOR GOOGLE MAPS
    define('API_KEY', 'AIzaSyCnSkqEtab01WtYXIqnL39sGyKkK2EdEV4');

    //Zona horaria
    date_default_timezone_set ('America/Mexico_City');
    
    $timeout = 36000;

    //Establecer el maxlifetime de la sesión

    ini_set ( "session.gc_maxlifetime" , $timeout ) ;

    //Establecer la duración de la cookie de la sesión

    ini_set ( "session.cookie_lifetime" , $timeout ) ;
    session_start();//configuracion de sesiones

    //Establecer el nombre de sesión predeterminado

   /* $s_name = session_name ( ) ;


    //Comprueba si la sesión existe o no

    if ( isset ( $_COOKIE [ $s_name ] ) ) {
        setcookie ( $s_name , $_COOKIE [ $s_name ] , time ( ) + $timeout , '/' ) ;
    }
    else{
        
        session_destroy ( ) ;
        ///header("Location: ".base_url."Login");
        //echo "La sesión ha expirado.<br/>" ;
    }*/
    
?>
