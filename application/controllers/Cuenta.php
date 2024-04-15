<?php

/**
 * 
 */
class Cuenta extends Controller
{
	public $Usuario;
	public $Historial;
	public function __construct(){
		$this->Historial = $this->model('Historial');
		$this->Usuario = $this->model('Usuario');
	}

	public function index(){

		if (!isset($_SESSION['userdataSIC'])) {
            header("Location: ".base_url."GestorCasos");
            exit();
        }

		$data = [
					'titulo' 		=> 'Mi cuenta',
					'extra_css' => '<link rel="stylesheet" href="'. base_url . 'public/css/system/cuenta/index.css">',
            		'extra_js'  => '<script src="'. base_url . 'public/js/system/cuenta/index.js"></script>'
				];
		

        $id_user = $_SESSION['userdataSIC']->Id_Usuario;
        if (!(is_numeric($id_user))) //seguridad si se ingresa parámetro inválido
        	header("Location: ".base_url."GestorCasos");



        /*PROCESO DE ACTUALIZAR INFORMACIÓN DE USUARIO SI HUBO POST*/
        if(isset($_POST['editarInfo'])){	//post para editar los cambios en la info del usuario
        	
	    	//validación del password   
    		$validation = isset($_POST['Password']) & (trim($_POST['Password']) != "");
	        //comprueba si todos los campos requeridos existen en el post
	        if ($validation) {
	        	$success = $this->Usuario->updateUserPassword($_POST);

	        	switch ($success['success']) {
	        		case '-2':  //error en la base de datos
	        			$data['resultStatus'] = '<div class="alert alert-danger text-center" role="alert">Error en la base de datos, intenta de nuevo</div>';
	        			break;
	        		case '-1':  //erro en el formulario
	        			$data['resultStatus'] = '<div class="alert alert-danger text-center" role="alert"> Error en formulario</div>';
						$data['errorForm'] = $success['errorForm'];
	        			break;
	        		case '0':	//sin cambios en la informacion obtenida
	        			$foto_name = $this->updateImageUser($_FILES,$_SESSION['userdataSIC']->Id_Usuario);
	        			if ($foto_name) {
	        				$this->Usuario->updateImgNameUser($foto_name,$_SESSION['userdataSIC']->Id_Usuario);
	        				$data['resultStatus'] = '<div class="alert alert-success text-center" role="success">Se Actualizo Correctamente la Foto
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">    
										<span aria-hidden="true">&times;</span>
									</button>
								</div>';
							//se actualiza la variable de session
							$descripcion = 'CAMBIO FOTO USUARIO: ' .$_SESSION['userdataSIC']->User_Name;
							$this->Historial->insertHistorial(11,$descripcion,$_SESSION['userdataSIC']->Id_Usuario);
							$_SESSION['userdataSIC'] = $this->Usuario->getUserById($_SESSION['userdataSIC']->Id_Usuario);
	        			}else{
	        				$data['resultStatus'] = '<div class="alert alert-danger text-center" role="alert"> Información sin cambios</div>';
	        			}
	        			
	        			
	        			break;
	        		case '1':	//actualizacion correcta
	        			$foto_name = $this->updateImageUser($_FILES,$_SESSION['userdataSIC']->Id_Usuario);
	        			if ($foto_name) {
	        				$this->Usuario->updateImgNameUser($foto_name,$_SESSION['userdataSIC']->Id_Usuario);
							$descripcion = 'CAMBIO FOTO USUARIO: ' .$_SESSION['userdataSIC']->User_Name;
							$this->Historial->insertHistorial(11,$descripcion,$_SESSION['userdataSIC']->Id_Usuario);
	        			}
	        			$data['resultStatus'] = '<div class="alert alert-success text-center" role="success">Se Actualizo correctamente la contraseña
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">    
									<span aria-hidden="true">&times;</span>
								</button>
							</div>';
						$descripcion = 'CAMBIO CONTRASEÑA DEL USUARIO: ' .$_SESSION['userdataSIC']->User_Name;
						$this->Historial->insertHistorial(10,$descripcion,$_SESSION['userdataSIC']->Id_Usuario);
						//se actualiza la variable de session
						$_SESSION['userdataSIC'] = $this->Usuario->getUserById($_SESSION['userdataSIC']->Id_Usuario);
	        			break;
	        		
	        	}
	        }
	        else{
	        	$data['resultStatus'] = '<div class="alert alert-danger text-center" role="alert"> Error en formulario, intenta de nuevo</div>';
	        }
	    }
        /*FIN POST*/
       	$infoUser = $this->Usuario->getUserById($id_user);
	    if (!$infoUser) {
	    	header("Location: ".base_url."GestorCasos");
	    }
	    else{
	    	
	    	$infoUser->Fecha_Format = $this->formatearFecha($infoUser->Fecha_Registro_Usuario);
	    	$data['infoUser'] = $infoUser;
	    	$this->view("templates/header",$data);
            $this->view("system/cuenta/cuentaView",$data);
            $this->view("templates/footer",$data);
	    }
	    

	}
	//funcion para editar la info del usuario (solo foto y/o contraseña)
	public function editarInfo(){

	}
	//función para darle un formato a la fecha y hora exacta de creación del usuario en cestión
	public function formatearFecha($fecha = "1997-01-04 13:30:00"){
		//$fecha = "2020-01-20 15:30:00";
		//$date = new DateTime($fecha);
		//se asigna hora local en México
		setlocale(LC_TIME, 'es_CO.UTF-8');
		$DIA_INGLES=strftime("%A", strtotime($fecha));
        switch ($DIA_INGLES) {
            case 'Monday':
                $day_of_the_week = 'Lunes';
                break;
            case 'Tuesday':
                $day_of_the_week = 'Martes';
                break;
            case 'Wednesday':
                $day_of_the_week = 'Miercoles';
                break;
            case 'Thursday':
                $day_of_the_week = 'Jueves';
                break;
            case 'Friday':
                $day_of_the_week = 'Viernes';
                break;
            case 'Saturday':
                $day_of_the_week = 'Sabado';
                break;
            case 'Sunday':
                $day_of_the_week = 'Domingo';
                break;
        }
		$MESNUM=strftime("%m", strtotime($fecha));
		$mes="";
		switch ($MESNUM) {
            case '1':
                $mes="Enero";
                break;
            case '2':
                $mes="Febrero";
                break;
            case '3':
                $mes="Marzo";
                break;
            case '4':
				$mes="Abril";
                break;
            case '5':
                $mes="Mayo";
                break;
            case '6':
                $mes="Junio";
                break;
            case '7':
                $mes="Julio";
                break;
			case '8':
				$mes="Agosto";
				break;
			case '9':
				$mes="Septiembre";
				break;
			case '10':
				$mes="Octubre";
				break;			
			case '11':
				$mes="Noviembre";
				break;
			case '12':
				$mes="Diciembre";
				break;
        }
		$results =$day_of_the_week.strftime(", %d  de ", strtotime($fecha)).$mes. strftime(" del %G", strtotime($fecha))." a las ".date('g:i a', strtotime($fecha));;
		return $results;
	}

	//función para actualizar la imagen del usuario
	public function updateImageUser($files = null, $id_user = null){

		//validación del File
	    $MAX_SIZE = 8000000;
	    $allowed_mime_type_arr = array('jpeg','png','jpg');
	    //Nota: En fetch no funciona get_mime_by_extension()
	    
	    //se checa si se cumplen todas las condiciones para un file correcto
	    if((isset($files['foto_file']['name'])) && ($files['foto_file']['name']!="") && ($files['foto_file']['size']<=$MAX_SIZE)){
	    	$arrayAux = explode('.', $files['foto_file']['name']);
	    	$mime = end($arrayAux); //obtiene la extensión del file

	        if(in_array($mime, $allowed_mime_type_arr)){
	            $band = true;
	        }else{
	            $band = false;
	        }
	    }else{
	        $band = false;
	    }

	    if ($band) {//se sube la foto original
	    	//se crea la carpeta si aun no existe del nuevo usuario
		    $carpeta = BASE_PATH."public/media/users_img/".$id_user;
			if (!file_exists($carpeta)) 
			    mkdir($carpeta, 0777, true);
			else
				$this->removeOnlyFilesDir($carpeta,true);

	    	$img_name = $files['foto_file']['name'];
	    	$ruta = BASE_PATH."public/media/users_img/".$id_user."/".$img_name;
			copy($files['foto_file']['tmp_name'],$ruta); //se guarda la imagen en la carpeta
	    }
	    else{//se sube la foto por default
	    	$img_name = false;	
	    }

	    return $img_name;
	}
	//Función para borrar contenido de carpeta de fotos de usuario
    public function removeOnlyFilesDir($dir,$ind) { //si ind == 1 no borra el directorio original, caso contrario, si lo borra
           $files = array_diff(scandir($dir), array('.','..'));
            foreach ($files as $file) {
              (is_dir("$dir/$file")) ? $this->removeOnlyFilesDir("$dir/$file",false) : unlink("$dir/$file");
            }

            if ($ind) return;
            else return rmdir($dir);
    }
}

?>