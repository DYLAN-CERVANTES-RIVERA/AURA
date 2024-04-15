<?php
class Estadistica{
    public $db; //Variable para instanciar el objeto PDO
    public function __construct(){
        $this->db = new Base(); //Se instancia el objeto con los métodos de PDO
    }
    public function remplazoCadena($entrada){// trata las cadenas para que no halla error en los querys
        $text=$entrada;
        $repla=['"','“','”'];
        $repla2=["'",'‘','’'];
        $text = str_replace($repla, "\"", $text);  
        $text = str_replace($repla2, "\'", $text); 
        $text = mb_strtoupper($text);
        $text = trim($text);
        return $text;
    }
    //función auxiliar para filtrar por un rango de fechas específicado por el usuario
    public function getFechaCondition(){
        $cad_fechas = "";
        if (isset($_SESSION['userdataSIC']->rango_inicio_esta) && isset($_SESSION['userdataSIC']->rango_fin_esta)) { //si no ingresa una fecha se seleciona el día de hoy como máximo
            $rango_inicio = $_SESSION['userdataSIC']->rango_inicio_esta;
            $rango_fin = $_SESSION['userdataSIC']->rango_fin_esta;
            $cad_fechas = " AND 
                            ((FechaHora_Recepcion >= '" . $rango_inicio . " 00:00:00'  AND 
                            FechaHora_Recepcion <= '" . $rango_fin . " 23:59:59' )OR
                            (FechaHora_Recepcion >= CAST('" . $rango_inicio . " 00:00:00' AS date) AND
                            FechaHora_Recepcion <= CAST('" . $rango_fin . " 23:59:59' AS date)))
                            ";
        }

        return $cad_fechas;
    }
    public function getDatosGraficas($cadena,$exacta){//Obtener datos de graficas
        $data['Zonas'] = [];
        $data['Delitos'] = [];
        $data['CSviolencias'] = [];
        $data['Dias'] = [];
        $data['Vectores'] = [];
        $cadena=$this->eliminar_acentos($cadena);
        $condicion=$this->getCondicionGraficas($cadena,$exacta);
        $rango_fecha=$this->getFechaCondition();
        $sql ="SELECT gc_evento_filtro_1.Zona, COUNT(gc_evento_filtro_1.Zona) as ZonaTotal FROM `gc_evento_filtro_1` WHERE gc_evento_filtro_1.Folio_infra>0 ".$rango_fecha." ".$condicion." GROUP BY gc_evento_filtro_1.Zona ORDER BY ZonaTotal DESC";
        $this->db->query($sql);
        $data['Zonas'] = $this->db->registers();

        $sql ="SELECT gc_evento_filtro_1.delitos_concat as Delito, COUNT(gc_evento_filtro_1.delitos_concat) as DelitosTotal FROM `gc_evento_filtro_1` WHERE gc_evento_filtro_1.Folio_infra>0".$rango_fecha." ".$condicion." GROUP BY gc_evento_filtro_1.delitos_concat ORDER BY DelitosTotal DESC";
        $this->db->query($sql);
        $data['Delitos'] = $this->db->registers();

        $sql ="SELECT gc_evento_filtro_1.CSviolencia, COUNT(gc_evento_filtro_1.CSviolencia) as CSviolenciaTotal FROM `gc_evento_filtro_1` WHERE gc_evento_filtro_1.Folio_infra>0".$rango_fecha." ".$condicion." GROUP BY gc_evento_filtro_1.CSviolencia ORDER BY CSviolenciaTotal DESC";
        $this->db->query($sql);
        $data['CSviolencias'] = $this->db->registers();


        $sql ="SELECT gc_evento_filtro_1.Dia_semana , COUNT(gc_evento_filtro_1.Dia_semana) as DiaTotal FROM `gc_evento_filtro_1` WHERE gc_evento_filtro_1.Folio_infra>0 ".$rango_fecha." ".$condicion." GROUP BY gc_evento_filtro_1.Dia_semana ORDER by  FIELD(gc_evento_filtro_1.Dia_semana ,'LUNES','MARTES','MIERCOLES','JUEVES' ,'VIERNES','SABADO','DOMINGO') ASC ";

        $this->db->query($sql);
        $data['Dias'] = $this->db->registers();

        $sql ="SELECT gc_evento_filtro_1.Hora_trunca, COUNT(gc_evento_filtro_1.Hora_trunca) as HoraTotal FROM `gc_evento_filtro_1` WHERE gc_evento_filtro_1.Folio_infra>0".$rango_fecha." ".$condicion." GROUP BY gc_evento_filtro_1.Hora_trunca ORDER BY gc_evento_filtro_1.Hora_trunca2 ASC";
        $this->db->query($sql);
        $data['Horas'] = $this->db->registers();

        $sql ="SELECT gc_evento_filtro_1.Vector, COUNT(gc_evento_filtro_1.Vector) as VectorTotal FROM `gc_evento_filtro_1` WHERE gc_evento_filtro_1.Folio_infra>0 ".$rango_fecha." ".$condicion." GROUP BY gc_evento_filtro_1.Vector ORDER BY gc_evento_filtro_1.Vector ASC";
        $this->db->query($sql);
        $data['Vectores'] = $this->db->registers();
        return $data;
    }
    public function getCondicionGraficas($cadena,$exacta){//conforme a los filtros y las busquedas realizadas generar la informacion para las graficas
        $condicion='';
        $dias = array ('lunes', 'martes', 'miercoles','jueves','viernes','sabado','domingo','LUNES', 'MARTES', 'MIERCOLES','JUEVES','VIERNES','SABADO','DOMINGO');//Funcion para un dia en especifico
        if($cadena!=''){
            if($exacta==0){
                $palabras = explode(",", strtolower($cadena));//Se obtiene la cadena con la cual se quiere buscar
                $articulos = array('el', 'la', 'los', 'las', 'un', 'de', 'en', 'unos', 'una', 'unas', 'a', 'con', 'y', 'o', 'u');//arreglo con las palabras a ignorar en la busqueda, normalmete artículos
                $palabras = array_diff($palabras, $articulos);
                foreach($palabras as $palabra){
                    $palabra=ltrim($palabra, " ");
                    $palabra=rtrim($palabra, " ");
                    if(in_array ($palabra,$dias)){
                        $condicion.= "
                        AND Dia_semana='". $palabra . "' ";
                    }else{
                        $condicion.= "
                        AND  (       Folio_infra LIKE '%" . $palabra . "%' OR 
                                    Elemento_Captura LIKE '%" . $palabra . "%' OR 
                                    Folio_911 LIKE '%" . $palabra . "%' OR 
                                    FechaHora_Captura LIKE '%" . $palabra . "%' OR 
                                    FechaHora_Recepcion LIKE '%" . $palabra . "%' OR 
                                    Zona LIKE '%" . $palabra . "%' OR 
                                    Vector LIKE '%" . $palabra . "%' OR 
                                    Colonia LIKE '%" . $palabra . "%' OR 
                                    Calle LIKE '%" . $palabra . "%' OR 
                                    Calle2 LIKE '%" . $palabra . "%' OR 
                                    CSviolencia  LIKE '%" . $palabra . "%' OR 
                                    Tipo_Violencia LIKE '%" . $palabra . "%' OR 
                                    Tipoarma_concat LIKE '%" . $palabra . "%' OR 
                                    FechaHora_Activacion LIKE '%" . $palabra . "%' OR 
                                    Fuente LIKE '%" . $palabra . "%' OR 
                                    Status LIKE '%" . $palabra . "%' OR 
                                    Quien_Habilito LIKE '%" . $palabra . "%' OR 
                                    Conteo_Masculinos LIKE '%" . $palabra . "%' OR
                                    Conteo_Femeninas LIKE '%" . $palabra . "%' OR
                                    Conteo_Vehiculos LIKE '%" . $palabra . "%' OR
                                    ClaveSeguimiento LIKE '%" . $palabra . "%' OR
                                    hechos_concat LIKE '%" . $palabra . "%' OR
                                    delitos_concat LIKE '%" . $palabra . "%' OR
                                    responsables_concat LIKE '%" . $palabra . "%' OR
                                    vehiculos_concat LIKE '%" . $palabra . "%'OR
                                    entrevistas_seguimiento_concat LIKE '%" . $palabra . "%' OR
                                    fotos_seguimiento_concat LIKE '%" . $palabra . "%'OR
                                    Hora_trunca LIKE '%" . $palabra . "%'OR
                                    delito_giro LIKE '%" . $palabra . "%') 
                            ";
                    }               
                }
            }else{
                $palabras = explode(",", strtolower($cadena));//Se obtiene la cadena con la cual se quiere buscar
                $articulos = array('el', 'la', 'los', 'las', 'un', 'de', 'en', 'unos', 'una', 'unas', 'a', 'con', 'y', 'o', 'u');//arreglo con las palabras a ignorar en la busqueda, normalmete artículos
                $palabras = array_diff($palabras, $articulos);
                foreach($palabras as $palabra){
                    $Sinespacios = strtoupper(trim($palabra));
                    //se busca asi en los delitos concat por la generacion de la vista a la que se esta consultando separa los delitos por una , 
                    $condicion.= "
                    AND  (       
                            Zona = '" . $Sinespacios . "' OR 
                            Vector = '" . $Sinespacios . "' OR
                            CSviolencia  = '" . $Sinespacios . "' OR 
                            delitos_concat LIKE '" . $Sinespacios . "' OR
                            delitos_concat LIKE '" . $Sinespacios . ",%' OR
                            delitos_concat LIKE '%," . $Sinespacios . "' OR
                            delitos_concat LIKE '%," . $Sinespacios . ",%' OR
                            Dia_semana = '" . $Sinespacios . "' OR
                            Hora_trunca = '" . $Sinespacios . "') 
                        ";    
                }
      
            }
        }
        return $condicion;
    }
    function eliminar_acentos($cadena){//esta funcion es para la cadena de busqueda ya que el like no permite mezcla diferentes collations solo aplica en bases locales 
		
		//Reemplazamos la A y a
		$cadena = str_replace(
		array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
		array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
		$cadena
		);

		//Reemplazamos la E y e
		$cadena = str_replace(
		array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
		array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
		$cadena );

		//Reemplazamos la I y i
		$cadena = str_replace(
		array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
		array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
		$cadena );

		//Reemplazamos la O y o
		$cadena = str_replace(
		array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
		array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
		$cadena );

		//Reemplazamos la U y u
		$cadena = str_replace(
		array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
		array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
		$cadena );

		//Reemplazamos la N, n, C y c
		$cadena = str_replace(
		array('Ñ', 'ñ', 'Ç', 'ç'),
		array('N', 'n', 'C', 'c'),
		$cadena
		);
		
		return $cadena;
	}
    public function historial($user, $ip, $movimiento, $descripcion){// para ecribir los movimientos hechos en el gestor 
        $band = true;
        try {
            $this->db->beginTransaction();
            $sql = " INSERT
                    INTO historial(
                        Id_Usuario,
                        Ip_Acceso,
                        Movimiento,
                        Descripcion
                    )
                    VALUES(
                        trim($user),
                        '" . trim($ip) . "',
                        trim($movimiento),
                        '" . trim($descripcion) . "'
                    )
            ";
            $this->db->query($sql);
            $this->db->execute();
            $this->db->commit();
        } catch (Exception $e) {
            $band = false;
            $this->db->rollBack();
        }
        return $band;
    }
}