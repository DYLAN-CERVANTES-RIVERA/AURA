<?php
class GestorCaso{
    public $db; //Variable para instanciar el objeto PDO comentario de prueba aura
    public function __construct(){
        $this->db = new Base(); //Se instancia el objeto con los métodos de PDO
        $this->db2 = new BaseTareas();
    }
    /*----------- FUNCION INSERT NUEVO EVENTO -------------------*/ 
    public function insertNuevoEvento($post){
        //Valores iniciales de retorno
        $data['status'] = true;
        $date = date("Ymdhis");
        $data['Folio_infra'] = -1;
        $data['sqlEjecutados'] = "";
        $sqlEjecutados="";
        try{

            $this->db->beginTransaction();  //Se inicializa la transaccion para tener un punto de retorno en caso de fallo
            $sql = "INSERT
                    INTO evento(
                        Elemento_Captura,
                        Folio_911,
                        FechaHora_Recepcion,
                        FechaHora_Captura,
                        Cdi,
                        Zona,
                        Vector,
                        Colonia,
                        Calle,
                        Calle2,
                        NoExt,
                        CP,
                        CoordX,
                        CoordY,
                        CSviolencia,
                        Tipo_Violencia,
                        FechaHora_Activacion,
                        Fuente,
                        Status_Seguimiento,
                        Quien_Habilito,
                        Status_Evento,
                        Unidad_Primer_R,
                        Informacion_Primer_R,
                        Acciones,
                        Turno,
                        Responsable_Turno,
                        Semana   
                    )
                    VALUES(
                        '".$post['captura_principales']."',
                        '".$post['911_principales']."',
                        '".$post['fecha_evento_principales'].' '.$post['hora_evento_principales']."',
                        '".$post['fechahora_captura_principales']."',
                        '".$post['cdi']."',
                        '".$post['zona']."',
                        '".$post['vector']."',
                        '".$this->remplazoCadena($post['Colonia'])."',
                        '".$this->remplazoCadena($post['Calle'])."',
                        '".$this->remplazoCadena($post['Calle2'])."',
                        '".$post['no_Ext']."',
                        '".$post['CP']."',
                        '".$post['cordX']."',
                        '".$post['cordY']."',
                        '".$post['CSviolencia']."',
                        '".$post['violencia_principales']."',
                        '".$post['FechaHora_Activacion']."',
                        '".$post['fuente_principales']."',
                        '".$post['Habilitado']."',
                        '".$post['Quien_Habilito']."',
                        '".$post['Estatus_Evento']."',
                        '".$this->remplazoCadena($post['Unidad_Primer_R'])."',
                        '".$this->remplazoCadena($post['Informacion_Primer_R'])."',
                        '".$this->remplazoCadena($post['Acciones'])."',
                        '".$this->remplazoCadena($post['Turno'])."',
                        '".$this->remplazoCadena($post['Responsable_Turno'])."',
                        ".$post['Semana']."
                    )";
            $this->db->query($sql);
            $this->db->execute();
            $this->db->query("SELECT LAST_INSERT_ID() as Folio_infra"); //Se recupera el Folio_infra que se a creado recientemente
            $Folio_infra = $this->db->register()->Folio_infra;
            $sqlEjecutados=$sql;
            

            if (isset($post['delitos_table'])) {// Si existen datos de los delitos los escribe en la tabla delitos_asociados_evento
                $delitosArray = json_decode($post['delitos_table']);
                $sqlEjecutados.=" DELITOS DEL EVENTO: ";
                foreach ($delitosArray as $delito) {
                    $sql = "INSERT
                    INTO delitos_asociados_evento(
                        Folio_infra,
                        Descripcion,
                        Giro 
                    )
                    VALUES(
                        ".$Folio_infra.",
                        '".$delito->row->descripcion."',
                        '".$this->remplazoCadena($delito->row->tipo_delito)."'
                    )
                    ";
                    $this->db->query($sql);
                    $this->db->execute();
                    $sqlEjecutados.=$delito->row->descripcion.' ';
                }    
            }

            if (isset($post['hechos_table'])) {// Si existen datos de los hechos los escribe en la tabla historico_descripcion_hecho
                $hechosArray = json_decode($post['hechos_table']);
                $sqlEjecutados.=" HECHOS REPORTADOS DEL EVENTO: ";
                foreach ($hechosArray as $hecho) {
                    $descripcion=$this->remplazoCadena($hecho->row->descripcion);
                    $sql = "INSERT
                    INTO historico_descripcion_hecho(
                        Folio_infra,
                        Descripcion,
                        Fecha_Hora_Hecho
                    )
                    VALUES(
                        ".$Folio_infra.",
                        '".$descripcion."',
                        '".$hecho->row->Fecha.' '.$hecho->row->Hora."'
                    )
                    ";
                    $this->db->query($sql);
                    $this->db->execute();
                    $sqlEjecutados.=$descripcion.' ';
                }
            }
            
            if (isset($post['vehiculos_table'])) {//Si existen datos de los vehiculos responsables los escribe en la tabla de vehiculosp
                $vehiculosArray = json_decode($post['vehiculos_table']);
                $ContVehiculos=0;
                foreach ($vehiculosArray as $vehiculo) {
                    $name = '';
                    if($vehiculo->row->typeImage!='null'){
                        if ($vehiculo->row->typeImage == 'File') {
                            $type = $_FILES[$vehiculo->row->nameImage]['type'];
                            $extension = explode("/", $type);
                            $name = $vehiculo->row->nameImage . ".png?v="  . $date;
                        } else {
                            $name = $vehiculo->row->nameImage . ".png?v=" . $date;
                        }
                    }
                    $sql = "INSERT
                    INTO vehiculo_p(
                        Folio_infra,
                        Tipo_Vehiculo,
                        Marca,
                        Submarca,
                        Modelo,
                        Placas_Vehiculo,
                        Color,
                        Descripcion_gral,
                        Tipo_veh_invo,
                        Path_Imagen,
                        img_64,
                        Estado_Veh,
                        Capturo,
                        Ultima_Actualizacion
                    )
                    VALUES(
                        ".$Folio_infra.",
                        '".$vehiculo->row->tipo_vehiculo."',
                        '".$vehiculo->row->marca."',
                        '".$vehiculo->row->submarca."',
                        '".$vehiculo->row->modelo."',
                        '".$vehiculo->row->placas."',
                        '".$this->remplazoCadena($vehiculo->row->color)."',
                        '".$this->remplazoCadena($vehiculo->row->descripcionV)."',
                        '".$vehiculo->row->tipo_vehiculo_involucrado."',
                        '".$name."',
                        '" . $vehiculo->row->imagebase64 . "',
                        '" . $vehiculo->row->estado_veh. "',
                        '" . $vehiculo->row->capturo. "',
                        '" . $vehiculo->row->Ultima_Actualizacion. "'
                    )";
                    $this->db->query($sql);
                    $this->db->execute();
                    $this->db->query("SELECT LAST_INSERT_ID() as Id_Veh"); 
                    $Id_Veh = $this->db->register()->Id_Veh;
                    $sqlEjecutados.=" SE INSERTO UN VEHICULO ".$Id_Veh;               
                    if($vehiculo->row->tipo_vehiculo_involucrado == 'RESPONSABLE'){
                        $ContVehiculos++;
                    }
                }
                if($ContVehiculos == 0){
                    $sql = "UPDATE evento SET Conteo_Vehiculos = 'SIN VEHICULOS' WHERE Folio_infra = ".$Folio_infra;//Se actualiza un el conteo de vehiculos responsables 
                }else{
                    if($ContVehiculos == 1){
                        $sql = "UPDATE evento SET Conteo_Vehiculos = 'UN VEHICULO' WHERE Folio_infra = ".$Folio_infra;
                    }else{
                        $sql = "UPDATE evento SET Conteo_Vehiculos = '".$ContVehiculos." VEHICULOS' WHERE Folio_infra = ".$Folio_infra;
                    }
                }
                $this->db->query($sql);
                $this->db->execute();

            }else{
                $sql = "UPDATE evento SET Conteo_Vehiculos = 'SIN VEHICULOS' WHERE Folio_infra = ".$Folio_infra;
                $this->db->query($sql);
                $this->db->execute(); 
            }

            if (isset($post['responsables_table'])) {//Si existen datos de los probables responsables los escribe en la tabla de personasp
                $responsablesArray = json_decode($post['responsables_table']);
                $ContMasculinos=0;
                $ContFemeninas=0;
                foreach ($responsablesArray as $responsable) {
                    $name = '';
                    if($responsable->row->typeImage!='null'){
                        if ($responsable->row->typeImage == 'File') {
                            $type = $_FILES[$responsable->row->nameImage]['type'];
                            $extension = explode("/", $type);
                            $name = $responsable->row->nameImage . ".png?v=" . $date;
                        } else {
                            $name = $responsable->row->nameImage . ".png?v=" . $date;
                        }
                    }
                    $sql = "INSERT
                    INTO persona_p(
                        Folio_infra,
                        Sexo,
                        Rango_Edad,
                        Complexion,
                        Descripcion_Responsable,
                        Path_Imagen,
                        Tipo_arma,
                        img_64,
                        Estado_Res,
                        Capturo,
                        Ultima_Actualizacion
                    )
                    VALUES(
                        ".$Folio_infra.",
                        '".$responsable->row->sexo."',
                        '".$responsable->row->rango_edad."',
                        '".$responsable->row->complexion."',
                        '".$this->remplazoCadena($responsable->row->descripcionR)."',
                        '".$name."',
                        '".$responsable->row->tipo_arma."',
                        '" . $responsable->row->imagebase64 . "',
                        '" . $responsable->row->estado_res . "',
                        '" . $responsable->row->capturo . "',
                        '" . $responsable->row->Ultima_Actualizacion . "'
                    )
                    ";
                    $this->db->query($sql);
                    $this->db->execute();

                    $this->db->query("SELECT LAST_INSERT_ID() as Id_Per"); 
                    $Id_Per = $this->db->register()->Id_Per;
                    $sqlEjecutados.=" SE INSERTO UN INVOLUCRADO ".$Id_Per;

                    if($responsable->row->sexo=="MASCULINO"){
                        $ContMasculinos++;
                    }
                    if($responsable->row->sexo=="FEMENINO"){
                        $ContFemeninas++;
                    }
                }
                if($ContMasculinos==0){
                    $sql = "UPDATE evento SET Conteo_Masculinos= 'SIN MASCULINOS' WHERE Folio_infra= ".$Folio_infra;//Se actualiza el conteo de responsables involucrados de ambos sexos 
                }else{
                    if($ContMasculinos==1){
                        $sql = "UPDATE evento SET Conteo_Masculinos= 'UN MASCULINO' WHERE Folio_infra= ".$Folio_infra;
                    }else{
                        $sql = "UPDATE evento SET Conteo_Masculinos='".$ContMasculinos." MASCULINOS' WHERE Folio_infra= ".$Folio_infra;
                    } 
                }
                $this->db->query($sql);
                $this->db->execute();
                if($ContFemeninas==0){
                    $sql = "UPDATE evento SET Conteo_Femeninas= 'SIN FEMENINAS' WHERE Folio_infra= ".$Folio_infra;
                }else{
                    if($ContFemeninas==1){
                        $sql = "UPDATE evento SET Conteo_Femeninas= 'UNA FEMENINA' WHERE Folio_infra= ".$Folio_infra;
                    }else{
                        $sql = "UPDATE evento SET Conteo_Femeninas='".$ContFemeninas." FEMENINAS' WHERE Folio_infra= ".$Folio_infra;
                    }
                }
                $this->db->query($sql);
                $this->db->execute();

            }else{
                $sql = "UPDATE evento SET Conteo_Masculinos= 'SIN MASCULINOS',Conteo_Femeninas = 'SIN FEMENINAS' WHERE Folio_infra = ".$Folio_infra;
                $this->db->query($sql);
                $this->db->execute(); 
            }

            $data['Folio_infra'] = $Folio_infra; 
            $data['sqlEjecutados'] = $sqlEjecutados;
            $this->db->commit(); //Si no hubo fallos en ninguna insercion asegura los cambios
        }catch(Exception $e){
            $data['status'] = false;
            $data['error_message'] = $e;
            $data['error_sql'] = $sql;
            $this->db->rollBack();//Si hubiese fallo en alguna insercion regresa  al estado en el que estaba la tabla al momento que se declaro el inicio de la transaccion  
        }
        return $data;
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

    //genera la consulta where dependiendo del filtro
    public function generateFromWhereSentence($cadena = "", $filtro = '1',$cadena2 = "")
    {
        $from_where_sentence = "";
        $cadena=$this->eliminar_acentos($cadena);
        switch ($filtro) {
            case '1':   //general
                $palabras = explode(",", strtolower($cadena));//Se obtiene la cadena con la cual se quiere buscar
                    $articulos = array('el', 'la', 'los', 'las', 'un', 'de', 'en', 'unos', 'una', 'unas', 'a', 'con', 'y', 'o', 'u');//arreglo con las palabras a ignorar en la busqueda, normalmete artículos
                    $palabras = array_diff($palabras, $articulos);
                    $from_where_sentence .= "
                                FROM gc_evento_filtro_1
    
                                            WHERE  Folio_infra > 0 
                                                ";
                    foreach($palabras as $palabra){
                        $palabra=ltrim($palabra, " ");
                        $palabra=rtrim($palabra, " ");
                        $dias = array ('lunes', 'martes', 'miercoles','jueves','viernes','sabado','domingo');
                        if(in_array ($palabra,$dias)){
                            $from_where_sentence.= "
                                            AND Dia_semana='". $palabra . "' ";
                        }else{
                            $from_where_sentence.= "
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
                break;
                case '2':   //EVENTOS HABILITADOS
                    $palabras = explode(",", strtolower($cadena));//Se obtiene la cadena con la cual se quiere buscar
                        $articulos = array('el', 'la', 'los', 'las', 'un', 'de', 'en', 'unos', 'una', 'unas', 'a', 'con', 'y', 'o', 'u');//arreglo con las palabras a ignorar en la busqueda, normalmete artículos
                        $palabras = array_diff($palabras, $articulos);
                        $from_where_sentence .= "
                                    FROM gc_evento_filtro_1
        
                                                WHERE  Folio_infra > 0 
                                                    ";
                        foreach($palabras as $palabra){
                            $palabra=ltrim($palabra, " ");
                            $palabra=rtrim($palabra, " ");
                            $dias = array ('lunes', 'martes', 'miercoles','jueves','viernes','sabado','domingo');
                            if(in_array ($palabra,$dias)){
                                $from_where_sentence.= "
                                                AND Dia_semana='". $palabra . "' ";
                            }else{
                                $from_where_sentence.= "
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
                        $from_where_sentence.= "
                                                AND  Status ='HABILITADO'       
                                                ";

                    break;
                case '3':   //EVENTOS  DESAHABILITADOS
                    $palabras = explode(",", strtolower($cadena));//Se obtiene la cadena con la cual se quiere buscar
                        $articulos = array('el', 'la', 'los', 'las', 'un', 'de', 'en', 'unos', 'una', 'unas', 'a', 'con', 'y', 'o', 'u');//arreglo con las palabras a ignorar en la busqueda, normalmete artículos
                        $palabras = array_diff($palabras, $articulos);
                        $from_where_sentence .= "
                                    FROM gc_evento_filtro_1
        
                                                WHERE  Folio_infra > 0 
                                                    ";
                        foreach($palabras as $palabra){
                            $palabra=ltrim($palabra, " ");
                            $palabra=rtrim($palabra, " ");
                            $dias = array ('lunes', 'martes', 'miercoles','jueves','viernes','sabado','domingo');
                            if(in_array ($palabra,$dias)){
                                $from_where_sentence.= "
                                                AND Dia_semana='". $palabra . "' ";
                            }else{
                                $from_where_sentence.= "
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
                        $from_where_sentence.= "
                                                AND  Status ='DESHABILITADO'       
                                                ";
                    break;
                    case '4':   //general
                        $palabras = explode(",", strtolower($cadena));//Se obtiene la cadena con la cual se quiere buscar
                            $articulos = array('el', 'la', 'los', 'las', 'un', 'de', 'en', 'unos', 'una', 'unas', 'a', 'con', 'y', 'o', 'u');//arreglo con las palabras a ignorar en la busqueda, normalmete artículos
                            $palabras = array_diff($palabras, $articulos);
                            $from_where_sentence .= "
                                        FROM gc_evento_filtro_1
            
                                                    WHERE  Folio_infra > 0 
                                                        ";
                            foreach($palabras as $palabra){
                                $palabra=ltrim($palabra, " ");
                                $palabra=rtrim($palabra, " ");
                                $from_where_sentence .= "
                                                AND  (      Folio_infra LIKE '%" . $palabra . "%' OR 
                                                            Elemento_Captura LIKE '%" . $palabra . "%' OR 
                                                            Folio_911 LIKE '%" . $palabra . "%' OR 
                                                            FechaHora_Captura LIKE '%" . $palabra . "%' OR 
                                                            FechaHora_Recepcion LIKE '%" . $palabra . "%' OR 
                                                            Zona LIKE '%" . $palabra . "%' OR 
                                                            Vector LIKE '%" . $palabra . "%' OR
                                                            Colonia LIKE '%" . $palabra . "%' OR 
                                                            Calle LIKE '%" . $palabra . "%' OR 
                                                            Fuente LIKE '%" . $palabra . "%' OR 
                                                            Status LIKE '%" . $palabra . "%' OR 
                                                            Calle2 LIKE '%" . $palabra . "%' OR 
                                                            ClaveSeguimiento LIKE '%" . $palabra . "%') 
                                                    ";
                                                
                            }
                            $from_where_sentence.= "
                                                    AND  Status ='HABILITADO'       
                                                    ";

                    break;
                    case '5':   //general
                        $palabras = explode(",", strtolower($cadena));//Se obtiene la cadena con la cual se quiere buscar
                            $articulos = array('el', 'la', 'los', 'las', 'un', 'de', 'en', 'unos', 'una', 'unas', 'a', 'con', 'y', 'o', 'u');//arreglo con las palabras a ignorar en la busqueda, normalmete artículos
                            $palabras = array_diff($palabras, $articulos);
                            $from_where_sentence .= "
                                        FROM gc_evento_filtro_1
            
                                                    WHERE  Folio_infra > 0 
                                                        ";
                            if($cadena!=''){
                                foreach($palabras as $palabra){
                                    $palabra=ltrim($palabra, " ");
                                    $palabra=rtrim($palabra, " ");
                                    $from_where_sentence .= "
                                                    AND  (  Folio_infra = '" . $palabra . "' ) 
                                                        ";              
                                }
                            }

                    break;

        }

        //where complemento fechas (si existe)
        if($filtro==1){
            $from_where_sentence .= $this->getFechaCondition();
            //order by
            if($cadena2=='EXCEL'){
                $from_where_sentence .= " ORDER BY Folio_infra ASC";
            }else{
                $from_where_sentence .= " ORDER BY Folio_infra DESC";
            }
            

        }else{
            $from_where_sentence .= $this->getFechaCondition();
            $from_where_sentence .= " ORDER BY Folio_infra DESC";

        }

     
        return $from_where_sentence;
    }
/*--------FUNCIONES PARA DESASOCIAR ELEMENTOS DE LAS TABLAS------ */
    public function DesasociaInvolucrado($Id_Responsable){
        $response['status']=true;
        try {
            $this->db->beginTransaction(); 
            $sql = "UPDATE persona_p SET Folio_infra = NULL WHERE Id_Responsable = ".$Id_Responsable;
            $this->db->query($sql);
            $this->db->execute();
            $this->db->commit(); //Si no hubo fallos en ninguna insercion asegura los cambios
            $sqlEjecutados=$sql;

        } catch (Exception $e) {
            $response['status'] = false;
            $response['error_message'] = 'Hubo un error en la Base de datos.';
            $response['error_sql'] = $sql;
            $this->db->rollBack();
        }
        $response['sqlEjecutados'] = $sqlEjecutados;
        return  $response;
    }

    public function DesasociaVehInvolucrado($Id_Vehiculo){
        $response['status']=true;
        try {
            $this->db->beginTransaction(); 
            $sql = "UPDATE vehiculo_p SET Folio_infra = NULL WHERE Id_Vehiculo = ".$Id_Vehiculo;
            $this->db->query($sql);
            $this->db->execute();
            $this->db->commit(); //Si no hubo fallos en ninguna insercion asegura los cambios
            $sqlEjecutados=$sql;

        } catch (Exception $e) {
            $response['status'] = false;
            $response['error_message'] = 'Hubo un error en la Base de datos.';
            $response['error_sql'] = $sql;
            $this->db->rollBack();
        }
        $response['sqlEjecutados'] = $sqlEjecutados;
        return  $response;
    }

/*-----------------------FUNCIONES DE UPDATE--------------------------------------- */

    public function updatePrincipales($post)
    {
        $response['status'] = true;
        $response['sqlEjecutados'] = "";
        $sqlEjecutados="";
        $date = date("Ymdhis");
        try {
            
            $this->db->beginTransaction(); //transaction para evitar errores de inserción
            if($post['Folio_infra']!=''){
                $sql = "    UPDATE evento
                SET Folio_911 = '" . trim($post['911_principales'])  ."',
                    FechaHora_Recepcion = '".$post['fecha_evento_principales'].' '.$post['hora_evento_principales']. "',
                    Zona = '" . trim($post['zona']) . "',
                    Vector = '" . trim($post['vector']) . "',
                    Colonia = '" . $this->remplazoCadena($post['Colonia']) . "',
                    Calle = '" . $this->remplazoCadena($post['Calle']) . "',
                    Calle2 = '" . $this->remplazoCadena($post['Calle2']) . "',
                    NoExt = '" . trim($post['no_Ext']) . "',
                    CP = '" . trim($post['CP']) . "',
                    CoordX = '" . trim($post['cordX']) . "',
                    CoordY = '" . trim($post['cordY']) . "',
                    CSviolencia = '" . trim($post['CSviolencia']) . "',
                    Tipo_Violencia = '" . trim($post['violencia_principales']) . "',
                    FechaHora_Activacion = '" . trim($post['FechaHora_Activacion']) . "',
                    Fuente = '" . trim($post['fuente_principales']) . "',
                    Status_Seguimiento = '" . $post['Habilitado'] . "',
                    Quien_Habilito = '" . trim($post['Quien_Habilito']) . "',
                    Status_Evento = '" . trim($post['Estatus_Evento']) . "',
                    Unidad_Primer_R = '" .  $this->remplazoCadena($post['Unidad_Primer_R']) . "',
                    Informacion_Primer_R = '" .  $this->remplazoCadena($post['Informacion_Primer_R']) . "',
                    Acciones = '" . $this->remplazoCadena($post['Acciones']) . "',
                    Turno = '" .  $this->remplazoCadena($post['Turno']) . "',
                    Responsable_Turno = '" . $this->remplazoCadena($post['Responsable_Turno']) . "',
                    Semana = " . $post['Semana'] . ",
                    Ubo_Detencion = " . $post['Ubo_Detencion'] . ",
                    Path_Pdf = '" . $post['nombre_pdf'] . "',
                    ClaveSeguimiento ='". trim($post['ClaveSeguimiento']) . "',
                    Cdi ='". trim($post['cdi']) . "'
                    WHERE Folio_infra = " . trim($post['Folio_infra']) . "
                    AND (
                        Folio_911 != '" . trim($post['911_principales']) . "'
                        OR FechaHora_Recepcion != '" . $post['fecha_evento_principales'] . ' ' . $post['hora_evento_principales'] . "'
                        OR Zona != '" . trim($post['zona']) . "'
                        OR Vector != '" . trim($post['vector']) . "'
                        OR Colonia != '" . $this->remplazoCadena($post['Colonia']) . "'
                        OR Calle != '" . $this->remplazoCadena($post['Calle']) . "'
                        OR Calle2 != '" . $this->remplazoCadena($post['Calle2']) . "'
                        OR NoExt != '" . trim($post['no_Ext']) . "'
                        OR CP != '" . trim($post['CP']) . "'
                        OR CoordX != '" . trim($post['cordX']) . "'
                        OR CoordY != '" . trim($post['cordY']) . "'
                        OR CSviolencia != '" . trim($post['CSviolencia']) . "'
                        OR Tipo_Violencia != '" . trim($post['violencia_principales']) . "'
                        OR FechaHora_Activacion != '" . trim($post['FechaHora_Activacion']) . "'
                        OR Fuente != '" . trim($post['fuente_principales']) . "'
                        OR Status_Seguimiento != '" . $post['Habilitado'] . "'
                        OR Quien_Habilito != '" . trim($post['Quien_Habilito']) . "'
                        OR Status_Evento != '" . trim($post['Estatus_Evento']) . "'
                        OR Unidad_Primer_R != '" . $this->remplazoCadena($post['Unidad_Primer_R']) . "'
                        OR Informacion_Primer_R != '" . $this->remplazoCadena($post['Informacion_Primer_R']) . "'
                        OR Acciones != '" . $this->remplazoCadena($post['Acciones']) . "'
                        OR Turno != '" . $this->remplazoCadena($post['Turno']) . "'
                        OR Responsable_Turno != '" . $this->remplazoCadena($post['Responsable_Turno']) . "'
                        OR Semana != " . $post['Semana'] . "
                        OR Ubo_Detencion != " . $post['Ubo_Detencion'] . "
                        OR Path_Pdf != '" . $post['nombre_pdf'] . "'
                        OR ClaveSeguimiento != '". trim($post['ClaveSeguimiento']) . "'
                        OR Cdi != '". trim($post['cdi']) . "'
                    )";
                $this->db->query($sql);
                $this->db->execute();
                if ($this->db->rowCount() > 0) {// Si se realizó una actualización
                    $sqlEjecutados.=" SE ACTUALIZO LA EVENTO EN DATOS PRINCIPALES ".$sql;
                }

            }else{
                $response['folio'] = "No hay folio infra";
            }


            if (isset($post['delitos_table'])) {// pregunta si existen datos de la tabla delitos 

                $sql = "DELETE FROM delitos_asociados_evento WHERE Folio_infra = " . trim($post['Folio_infra']) . "";
                $this->db->query($sql);
                $this->db->execute();
                $delitosArray = json_decode($post['delitos_table']);
                $sqlEjecutados.=" DELITOS DEL EVENTO: ";
                $cad=" ";
                foreach ($delitosArray as $delito) {
                    //se inserta delito asociado al evento
                    $sql = "INSERT
                    INTO delitos_asociados_evento(
                        Folio_infra,
                        Descripcion,
                        Giro
                    )
                    VALUES(
                        ".trim($post['Folio_infra']).",
                        '".$delito->row->descripcion."',
                        '".$this->remplazoCadena($delito->row->tipo_delito)."'
                    )
                    ";
                    $this->db->query($sql);
                    $this->db->execute();
                    $sqlEjecutados.=$delito->row->descripcion.' ';
                }
           
            }

            if (isset($post['hechos_table'])) {// pregunta si existen datos de la tabla hechos
                $sql = "DELETE FROM historico_descripcion_hecho WHERE Folio_infra = " . trim($post['Folio_infra']) . "";
                $this->db->query($sql);
                $this->db->execute();
                $hechosArray = json_decode($post['hechos_table']);
                $sqlEjecutados.=" HECHOS REPORTADOS DEL EVENTO: ";
                foreach ($hechosArray as $hecho) {
                     //se inserta el hecho asociado al evento
                     $descripcionH=$this->remplazoCadena($hecho->row->descripcion);
                    $sql = "INSERT
                    INTO historico_descripcion_hecho(
                        Folio_infra,
                        Descripcion,
                        Fecha_Hora_Hecho
                    )
                    VALUES(
                        ".$post['Folio_infra'].",
                        '". $descripcionH."',
                        '".$hecho->row->Fecha.' '.$hecho->row->Hora."'
                    )
                    ";
                    $this->db->query($sql);
                    $this->db->execute();
                    $sqlEjecutados.=$descripcionH.' ';
                }
            }
            if (isset($post['vehiculos_table'])) {// Si existen datos de la tabla vehiculos
                $vehiculosArray = json_decode($post['vehiculos_table']);
                $ContVehiculos = 0;
                foreach ($vehiculosArray as $vehiculo) {
                    $name = '';
                    if($vehiculo->row->typeImage!='null'){//Se genera el nombre de la foto en caso de exista
                        if ($vehiculo->row->typeImage == 'File') {
                            $type = $_FILES[$vehiculo->row->nameImage]['type'];
                            $extension = explode("/", $type);
                            $name = $vehiculo->row->nameImage . ".png?v=" . $date;
                        } else {
                            $name = $vehiculo->row->nameImage . ".png?v=" . $date;
                        }
                    }
                    if($vehiculo->row->Id_Vehiculo =='SD'){//se inserta el vehiculo asociado al evento
                        $sql = "INSERT
                        INTO vehiculo_p(
                            Folio_infra,
                            Tipo_Vehiculo,
                            Marca,
                            Submarca,
                            Modelo,
                            Placas_Vehiculo,
                            Color,
                            Descripcion_gral,
                            Tipo_veh_invo,
                            Path_Imagen,
                            img_64,
                            Estado_Veh,
                            Capturo,
                            Ultima_Actualizacion
                        )
                        VALUES(
                            ".$post['Folio_infra'].",
                            '".$vehiculo->row->tipo_vehiculo."',
                            '".$vehiculo->row->marca."',
                            '".$vehiculo->row->submarca."',
                            '".$vehiculo->row->modelo."',
                            '".$vehiculo->row->placas."',
                            '".$this->remplazoCadena($vehiculo->row->color)."',
                            '".$this->remplazoCadena($vehiculo->row->descripcionV)."',
                            '".$vehiculo->row->tipo_vehiculo_involucrado."',
                            '".$name."',
                            '" . $vehiculo->row->imagebase64 . "',
                            '" . $vehiculo->row->estado_veh. "',
                            '" . $vehiculo->row->capturo. "',
                            '" . $vehiculo->row->Ultima_Actualizacion. "'
                        )
                        ";
                        $this->db->query($sql);
                        $this->db->execute();
                        $this->db->query("SELECT LAST_INSERT_ID() as Id_Veh"); 
                        $Id_Veh = $this->db->register()->Id_Veh;
                        $sqlEjecutados.=" SE INSERTO UN VEHICULO ".$Id_Veh;
                    }else{
                        $sql ="UPDATE vehiculo_p SET
                            Tipo_Vehiculo = '" . $vehiculo->row->tipo_vehiculo."',
                            Marca = '" .$vehiculo->row->marca ."',
                            Submarca = '" . $vehiculo->row->submarca."',
                            Modelo = '" . $vehiculo->row->modelo."',
                            Placas_Vehiculo = '" . $vehiculo->row->placas."',
                            Color = '" . $this->remplazoCadena($vehiculo->row->color)."',
                            Descripcion_gral = '" . $this->remplazoCadena($vehiculo->row->descripcionV)."',
                            Tipo_veh_invo = '" . $vehiculo->row->tipo_vehiculo_involucrado."',
                            Ultima_Actualizacion = '" . $vehiculo->row->Ultima_Actualizacion."',
                            Path_Imagen = '" . $name."',
                            img_64 = '" . $vehiculo->row->imagebase64."',
                            Estado_Veh = '" . $vehiculo->row->estado_veh."'
                            WHERE Id_Vehiculo = ".$vehiculo->row->Id_Vehiculo. " 
                            AND (
                                Tipo_Vehiculo != '" . $vehiculo->row->tipo_vehiculo . "'
                                OR Marca != '" . $vehiculo->row->marca . "'
                                OR Submarca != '" . $vehiculo->row->submarca . "'
                                OR Modelo != '" . $vehiculo->row->modelo . "'
                                OR Placas_Vehiculo != '" . $vehiculo->row->placas . "'
                                OR Color != '" . $this->remplazoCadena($vehiculo->row->color) . "'
                                OR Descripcion_gral != '" . $this->remplazoCadena($vehiculo->row->descripcionV) . "'
                                OR Tipo_veh_invo != '" . $vehiculo->row->tipo_vehiculo_involucrado . "'
                                OR Ultima_Actualizacion != '" . $vehiculo->row->Ultima_Actualizacion . "'
                                OR img_64 != '" . $vehiculo->row->imagebase64 . "'
                                OR Estado_Veh != '" . $vehiculo->row->estado_veh . "'
                            )";
                        $this->db->query($sql);
                        $this->db->execute();
                        if ($this->db->rowCount() > 0) {// Si se realizó una actualización
                            $sqlEjecutados.=" SE ACTUALIZO VEHICULO ".$vehiculo->row->Id_Vehiculo;
                        }
                    }
                    
                    if($vehiculo->row->tipo_vehiculo_involucrado == 'RESPONSABLE'){
                        $ContVehiculos++;
                    }
                }
                if($ContVehiculos==0){// actualiza el conteo de los vehiculos 
                    $sql = "UPDATE evento SET Conteo_Vehiculos = 'SIN VEHICULOS' WHERE Folio_infra = ".$post['Folio_infra'];
                }else{
                    if($ContVehiculos==1){
                        $sql = "UPDATE evento SET Conteo_Vehiculos = 'UN VEHICULO' WHERE Folio_infra = ".$post['Folio_infra'];

                    }else{
                        $sql = "UPDATE evento SET Conteo_Vehiculos = '".$ContVehiculos." VEHICULOS' WHERE Folio_infra = ".$post['Folio_infra'];
                    }
                }
                $this->db->query($sql);
                $this->db->execute(); 
                
            }else{//en caso de que no exista ningun vehiculo actualiza sin vehiculos
                $sql = "UPDATE evento SET Conteo_Vehiculos = 'SIN VEHICULOS' WHERE Folio_infra= ".$post['Folio_infra'];
                $this->db->query($sql);
                $this->db->execute();
            }

            if (isset($post['responsables_table'])) {// pregunta si existen datos de la tabla de involucrados
                
                $responsablesArray = json_decode($post['responsables_table']);
                $ContMasculinos=0;
                $ContFemeninas=0;
                foreach ($responsablesArray as $responsable) {
                    //se inserta el involucrado asociado al evento
                    $name = '';
                    if($responsable->row->typeImage!='null'){//Se genera el nombre de la foto en caso de exista
                        if ($responsable->row->typeImage == 'File') {
                            $type = $_FILES[$responsable->row->nameImage]['type'];
                            $extension = explode("/", $type);
                            $name = $responsable->row->nameImage . ".png?v=" . $date;
                        } else {
                            $name = $responsable->row->nameImage . ".png?v=" . $date;
                        }
                    }
                    if($responsable->row->Id_Responsable=='SD'){
                        $sql = "INSERT
                        INTO persona_p(
                            Folio_infra,
                            Sexo,
                            Rango_Edad,
                            Complexion,
                            Descripcion_Responsable,
                            Path_Imagen,
                            Tipo_arma,
                            img_64 ,
                            Estado_Res,
                            Capturo,
                            Ultima_Actualizacion
                        )
                        VALUES(
                            ".$post['Folio_infra'].",
                            '".$responsable->row->sexo."',
                            '".$responsable->row->rango_edad."',
                            '".$responsable->row->complexion."',
                            '".$this->remplazoCadena($responsable->row->descripcionR)."',
                            '".$name."',
                            '".$responsable->row->tipo_arma."',
                            '" . $responsable->row->imagebase64 . "',
                            '" . $responsable->row->estado_res . "',
                            '" . $responsable->row->capturo . "',
                            '" . $responsable->row->Ultima_Actualizacion . "'
                        )";
                        $this->db->query($sql);
                        $this->db->execute();
                        $this->db->query("SELECT LAST_INSERT_ID() as Id_Per"); 
                        $Id_Per = $this->db->register()->Id_Per;
                        $sqlEjecutados.=" SE INSERTO UN INVOLUCRADO ".$Id_Per;
                    }else{
                        $sql=" UPDATE persona_p
                        SET Sexo = '" . $responsable->row->sexo  ."',
                            Rango_Edad = '" . $responsable->row->rango_edad ."',
                            Complexion = '" . $responsable->row->complexion ."',
                            Descripcion_Responsable = '" . $this->remplazoCadena($responsable->row->descripcionR)."',
                            Path_Imagen = '" . $name ."',
                            Tipo_arma = '" . $responsable->row->tipo_arma ."',
                            Estado_Res = '" . $responsable->row->estado_res ."',
                            Ultima_Actualizacion = '" . $responsable->row->Ultima_Actualizacion ."',
                            img_64 = '" . $responsable->row->imagebase64  ."'
                            WHERE Id_Responsable = ".$responsable->row->Id_Responsable."
                            AND (
                                Sexo != '" . $responsable->row->sexo . "'
                                OR Rango_Edad != '" . $responsable->row->rango_edad . "'
                                OR Complexion != '" . $responsable->row->complexion . "'
                                OR Descripcion_Responsable != '" . $this->remplazoCadena($responsable->row->descripcionR) . "'
                                OR Tipo_arma != '" . $responsable->row->tipo_arma . "'
                                OR Estado_Res != '" . $responsable->row->estado_res . "'
                                OR Ultima_Actualizacion != '" . $responsable->row->Ultima_Actualizacion . "'
                                OR img_64 != '" . $responsable->row->imagebase64  . "'
                        )";
                        $this->db->query($sql);
                        $this->db->execute();
                        if ($this->db->rowCount() > 0) {// Si se realizó una actualización
                            $sqlEjecutados.=" SE ACTUALIZO EL INVOLUCRADO ".$responsable->row->Id_Responsable;
                        }
                    }

                    if($responsable->row->sexo=="MASCULINO"){//hace un conteo de masculinos 
                        $ContMasculinos++;
                    }
                    if($responsable->row->sexo=="FEMENINO"){//hace un conteo de femeninas 
                        $ContFemeninas++;
                    }
                }
                if($ContMasculinos==0){// actualiza el conteo de los masculinos
                    $sql = "UPDATE evento SET Conteo_Masculinos= 'SIN MASCULINOS' WHERE Folio_infra= ".$post['Folio_infra'];
                }else{
                    if($ContMasculinos==1){
                        $sql = "UPDATE evento SET Conteo_Masculinos= 'UN MASCULINO' WHERE Folio_infra= ".$post['Folio_infra'];

                    }else{
                        $sql = "UPDATE evento SET Conteo_Masculinos='".$ContMasculinos." MASCULINOS' WHERE Folio_infra= ".$post['Folio_infra'];
                    }
                }
                $this->db->query($sql);
                $this->db->execute();

                if($ContFemeninas==0){// actualiza el conteo de los femeninas
                    $sql = "UPDATE evento SET Conteo_Femeninas= 'SIN FEMENINAS' WHERE Folio_infra= ".$post['Folio_infra'];
                }else{
                    if($ContFemeninas==1){
                        $sql = "UPDATE evento SET Conteo_Femeninas= 'UNA FEMENINA' WHERE Folio_infra= ".$post['Folio_infra'];

                    }else{
                        $sql = "UPDATE evento SET Conteo_Femeninas='".$ContFemeninas." FEMENINAS' WHERE Folio_infra= ".$post['Folio_infra'];
                    }
                    
                }
                
                $this->db->query($sql);
                $this->db->execute();
                
            }else{
                $sql = "UPDATE evento SET Conteo_Masculinos= 'SIN MASCULINOS',Conteo_Femeninas = 'SIN FEMENINAS' WHERE Folio_infra= ".$post['Folio_infra'];
                $this->db->query($sql);
                $this->db->execute();
            }

            if($post['Ubo_Detencion']==1){
                if($post['Id_Ubicacion_Detencion']=='SD'){
                    //logica de insert
                    $sql = "INSERT
                        INTO ubicaciones_detencion_evento(
                            Folio_infra,
                            Detencion_Por_Info_Io,
                            Fecha_Detencion,
                            Compañia,
                            Elementos_Realizan_D,
                            Nombres_Detenidos,
                            Colonia,
                            Calle ,
                            Calle2,
                            NumExt,
                            NumInt,
                            CP,
                            CoordX,
                            CoordY,
                            Estado,
                            Municipio,
                            Foraneo,
                            Link_Ubicacion,
                            Observaciones_Detencion
                        )
                        VALUES(
                            ".$post['Folio_infra'].",
                            ".$post['Detencion_Por_Info_Io'].",
                            '".$post['Fecha_Detencion']."',
                            '".$this->remplazoCadena($post['Compañia'])."',
                            '".$this->remplazoCadena($post['Elementos_Realizan_D'])."',
                            '".$this->remplazoCadena($post['Nombres_Detenidos'])."',
                            '".$post['Colonia_Det']."',
                            '".$post['Calle_Det']."',
                            '".$post['Calle_Det2']."',
                            '".$post['no_Ext_Det']."',
                            '".$post['no_Int_Det']."',
                            '".$post['CP_Det']."',
                            '".$post['cordX_Det']."',
                            '".$post['cordY_Det']."',
                            '".$post['Estado']."',
                            '".$post['Municipio']."',
                            '".$post['Foraneo']."',
                            '".$post['Link_Ubicacion_Det']."',
                            '" .$this->remplazoCadena($post['Observacion_Ubicacion_Det']). "'
                        )";
                        $this->db->query($sql);
                        $this->db->execute();
                        $this->db->query("SELECT LAST_INSERT_ID() as Id_Ubicacion_Detencion"); 
                        $Id_Ubicacion_Detencion = $this->db->register()->Id_Ubicacion_Detencion;
                        $sqlEjecutados.=" SE INSERTO LA UBICACION DE DETENCION  ".$Id_Ubicacion_Detencion;
                }else{
                    //logica de update
                    $sql=" UPDATE ubicaciones_detencion_evento
                            SET Detencion_Por_Info_Io = '" . $post['Detencion_Por_Info_Io']."',
                                Fecha_Detencion = '" . $post['Fecha_Detencion'] ."',
                                Compañia = '" . $this->remplazoCadena($post['Compañia']) ."',
                                Elementos_Realizan_D = '" . $this->remplazoCadena($post['Elementos_Realizan_D'])."',
                                Nombres_Detenidos = '" . $this->remplazoCadena($post['Nombres_Detenidos']) ."',
                                Colonia = '" . $post['Colonia_Det'] ."',
                                Calle = '" . $post['Calle_Det'] ."',
                                Calle2 = '" . $post['Calle_Det2'] ."',
                                NumExt = '" . $post['no_Ext_Det'] ."',
                                NumInt = '" . $post['no_Int_Det'] ."',
                                CP = '" . $post['CP_Det'] ."',
                                CoordY = '" . $post['cordY_Det'] ."',
                                CoordX = '" . $post['cordX_Det'] ."',
                                Estado = '" . $post['Estado'] ."',
                                Municipio = '" . $post['Municipio'] ."',
                                Foraneo = '" . $post['Foraneo'] ."',
                                Link_Ubicacion = '" . $post['Link_Ubicacion_Det'] ."',
                                Observaciones_Detencion = '" . $this->remplazoCadena($post['Observacion_Ubicacion_Det']) ."'
                                WHERE Id_Ubicacion_Detencion  = ".$post['Id_Ubicacion_Detencion']."
                                AND (
                                    Detencion_Por_Info_Io != '" . $post['Detencion_Por_Info_Io'] . "'
                                    OR Fecha_Detencion != '" . $post['Fecha_Detencion'] . "'
                                    OR Compañia != '" . $this->remplazoCadena($post['Compañia']) . "'
                                    OR Elementos_Realizan_D != '" . $this->remplazoCadena($post['Elementos_Realizan_D']) . "'
                                    OR Nombres_Detenidos != '" . $this->remplazoCadena($post['Nombres_Detenidos']) . "'
                                    OR Colonia != '" . $post['Colonia_Det'] . "'
                                    OR Calle != '" . $post['Calle_Det'] . "'
                                    OR Calle2 != '" . $post['Calle_Det2'] . "'
                                    OR NumExt != '" . $post['no_Ext_Det'] . "'
                                    OR NumInt != '" . $post['no_Int_Det'] . "'
                                    OR CP != '" . $post['CP_Det'] . "'
                                    OR CoordY != '" . $post['cordY_Det'] . "'
                                    OR CoordX != '" . $post['cordX_Det'] . "'
                                    OR Estado != '" . $post['Estado'] . "'
                                    OR Municipio != '" . $post['Municipio'] . "'
                                    OR Foraneo != '" . $post['Foraneo'] . "'
                                    OR Link_Ubicacion != '" . $post['Link_Ubicacion_Det'] . "'
                                    OR Observaciones_Detencion != '" . $this->remplazoCadena($post['Observacion_Ubicacion_Det']) . "'
                                )";
                    $this->db->query($sql);
                    $this->db->execute();
                    if ($this->db->rowCount() > 0) {// Si se realizó una actualización
                        $sqlEjecutados.=" SE ACTUALIZO UBICACION DETENCION EVENTO ".$post['Id_Ubicacion_Detencion'];
                    }
                }
                
            }else{
                if($post['Id_Ubicacion_Detencion']!='SD'){
                    $sql=" UPDATE ubicaciones_detencion_evento SET Folio_infra = NULL WHERE Id_Ubicacion_Detencion  = ".$post['Id_Ubicacion_Detencion'];
                    $this->db->query($sql);
                    $this->db->execute();
                }
            }
            $this->db->commit(); //si todo sale bien se realiza los commits

        } catch (Exception $e) {
            $response['status'] = false;
            $response['error_message'] = $e;
            $response['error_sql'] = $sql;
            $this->db->rollBack();//Si hubise fallo en alguna insercion regresa al estado en el que estaba la tabla al momento que se declaro el inicio de la transaccion  
        }
        $response['sqlEjecutados'] = $sqlEjecutados;
        return $response;
    }

    public function updateEntrevistas($post)///actualiza entrevistas
    {
        $response['status'] = true;
        $response['sqlEjecutados'] = "";//guarda los movimientos ejecutados de sql
        $sqlEjecutados="";
        try {
            $this->db->beginTransaction();
            if(isset($post['entrevistas_table'])){
                $sql = "DELETE FROM entrevistas_seguimiento WHERE Folio_infra =" . $post['Folio_infra'];
                $this->db->query($sql);
                $this->db->execute();
                $sqlEjecutados=$sqlEjecutados.' '.$sql;
                $entrevistas = json_decode($post['entrevistas_table']);
                foreach ($entrevistas as $entrevista) {
                    //inserta la entrevista
                    $descripcionEntre=$this->remplazoCadena($entrevista->row->entrevista);
                    $sql = " INSERT
                            INTO entrevistas_seguimiento(
                                Folio_infra,
                                procedencia,
                                entrevista,
                                entrevistado,
                                entrevistador,
                                edad_entrevistado,
                                telefono_entrevistado,
                                fecha_entrevista,
                                hora_entrevista,
                                Capturo,
                                Ultima_Actualizacion
                            )
                            VALUES(
                                '" . $post['Folio_infra'] ."',
                                '" . trim($entrevista->row->procedencia) . "',
                                '" . trim($descripcionEntre) . "',
                                '" . $this->remplazoCadena($entrevista->row->nombre_entrevistado) . "',
                                '" . trim($entrevista->row->clave_entrevistador) ."',
                                '" . trim($entrevista->row->edad_entrevistado) ."',
                                '" . trim($entrevista->row->telefono_entrevistado) ."',
                                '" . trim($entrevista->row->fecha_entrevista) ."',
                                '" . trim($entrevista->row->hora_entrevista) ."',
                                '" . trim($entrevista->row->Capturo) ."',
                                '" . trim($entrevista->row->Ultima_Actualizacion) ."'
                            )
                    ";
                    $this->db->query($sql);
                    $this->db->execute();
                    $sqlEjecutados=$sqlEjecutados.' '.$sql;
                    }
            }else{
                $sql = "DELETE FROM entrevistas_seguimiento WHERE Folio_infra =" . $post['Folio_infra'];
                $this->db->query($sql);
                $this->db->execute();
                $sqlEjecutados=$sqlEjecutados.' '.$sql;//guarda los movimientos ejecutados de sql
            }
            $this->db->commit();
        } catch (Exception $e) {
            $response['status'] = false;
            $response['error_message'] = 'Hubo un error en la Base de datos.';
            $this->db->rollBack();
        }
        $response['sqlEjecutados'] = $sqlEjecutados;
        return $response;
    }
          
    public function UpdateSeguimientoTerminados($post)//actualiza el campo en la tabla evento el estado del seguimiento
    {
       
        try {
           
            $this->db->beginTransaction();
            if(isset($post['SeguimientoTerminado'])){
                $sql = "UPDATE evento SET SeguimientoTerminado = '1' WHERE Folio_infra= ".$post['FolioInfra'];
                $this->db->query($sql);
                $this->db->execute();
                $response['status'] = true;
                $response['sqlEjecutados'] = $sql;
            }else{

                $response['status'] = false;
                $response['error_message'] = "no hay estatus";
            }
            
        } catch (Exception $e) {
            $response['status'] = false;
            $response['error_message'] = $e;
            $response['error_sql'] = $sql;
            $this->db->rollBack();
        }
        $this->db->commit();
        return $response;
    }

    public function updateFotos($post)//actualiza la tab de fotos
    {
        $response['status'] = true;
        $response['sqlEjecutados'] = "";
        $sqlEjecutados="";
        $date = date("Ymdhis");
        try {
           
            $this->db->beginTransaction();

                if(isset($post['fotos_table'])){

                $sql = "DELETE FROM fotos_seguimiento WHERE Folio_infra =" . $post['Folio_infra'];
                $this->db->query($sql);
                $this->db->execute();
                $sqlEjecutados=$sqlEjecutados.' '.$sql; 
                $fotos = json_decode($post['fotos_table']);
                foreach ($fotos as $foto) {
                    //inserta la foto
                    $name = '';
                    if ($foto->row->typeImage == 'File') {//genera el nombre de la foto
                        $type = $_FILES[$foto->row->nameImage]['type'];
                        $extension = explode("/", $type);
                        $name = $foto->row->nameImage . ".png?v=" . $date;
                    } else {
                        $name = $foto->row->nameImage . ".png?v=" . $date;
                    }
                    $descripcionF=$this->remplazoCadena($foto->row->descripcion);
                    $sql = " INSERT
                            INTO fotos_seguimiento(
                                Folio_infra,
                                Descripcion,
                                Path_Imagen,
                                id_ubicacion,
                                ColoniaF,
                                CalleF,
                                Calle2F,
                                no_ExtF,
                                CPF,
                                cordYF,
                                cordXF,
                                id_camara,
                                fecha_captura_foto,
                                hora_captura_foto,
                                fecha_hora_captura_sistema,
                                img_64,
                                Capturo,
                                Ultima_Actualizacion
                            )
                            VALUES(
                                '" . trim($post['Folio_infra']) ."',
                                '" . trim($descripcionF) . "',
                                '" . trim($name) . "',
                                '" . trim($foto->row->id_ubicacion) . "',
                                '" . trim($foto->row->ColoniaF) . "',
                                '" . trim($foto->row->CalleF) . "',
                                '" . trim($foto->row->Calle2F) . "',
                                '" . trim($foto->row->no_ExtF) . "',
                                '" . trim($foto->row->CPF) . "',
                                '" . trim($foto->row->cordYF) . "',
                                '" . trim($foto->row->cordXF) . "',
                                '" . trim($foto->row->id_camara) . "',
                                '" . trim($foto->row->fecha_captura_foto) . "',
                                '" . trim($foto->row->hora_captura_foto) . "',
                                '" . trim($foto->row->fecha_hora_captura_sistema) . "',
                                '" . $foto->row->imagebase64 . "',
                                '" . trim($foto->row->capturo). "',
                                '" . trim($foto->row->Ultima_Actualizacion). "'
                            )
                    ";
                    $this->db->query($sql);
                    $this->db->execute();
                    $sqlrecortado=explode(";base64", strtolower($sql));
                    $sqlEjecutados=$sqlEjecutados.' '.$sqlrecortado[0];
                    }
                }else {//si no hay datos vacia los datos relacionados a nivel de base datos
                    $sql = "DELETE FROM fotos_seguimiento WHERE Folio_infra =" . $post['Folio_infra'];
                    $this->db->query($sql);
                    $this->db->execute();
                    $sqlEjecutados=$sqlEjecutados.' '.$sql;
                }
                $this->db->commit();
          
              
              

            } catch (Exception $e) {
                $response['status'] = false;
                $response['error_message'] = $e;
                $response['error_sql'] = $sql;
                $this->db->rollBack();
            }
            $response['sqlEjecutados'] = $sqlEjecutados;
            return $response;
    }
  /*------------------FUNCIONES PARA FILTRADO Y BÚSQUEDA------------------*/
    //obtener el total de páginas y de registros de la consulta
    public function getTotalPages($no_of_records_per_page, $from_where_sentence = ""){
        //quitamos todo aquello que este fuera de los parámetros para solo obtener el substring desde FROM
        $from_where_sentence = strstr($from_where_sentence, 'FROM');

        $sql_total_pages = "SELECT COUNT(*) as Num_Pages " . $from_where_sentence; //total registros
        $this->db->query($sql_total_pages);      //prepararando query
        $total_rows = $this->db->register()->Num_Pages; //ejecutando query y recuperando el valor obtenido
        $total_pages = ceil($total_rows / $no_of_records_per_page); //calculando el total de paginations

        $data['total_rows'] = $total_rows;
        $data['total_pages'] = $total_pages;
        return $data;
    }

    //obtener los registros de la pagina actual
    public function getDataCurrentPage($offset, $no_of_records_per_page, $from_where_sentence = ""){
        $sql = "
                SELECT Folio_infra,Folio_911,ClaveSeguimiento,FechaHora_Recepcion,Status,delitos_concat,CSviolencia,Colonia,Calle,Zona,Vector,FechaHora_Activacion,FechaHora_Captura,Id_Seguimiento "
            . $from_where_sentence . "  
                LIMIT $offset,$no_of_records_per_page
                ";

        $this->db->query($sql);
        return $this->db->registers();
    }

    //función auxiliar para filtrar por un rango de fechas específicado por el usuario
    public function getFechaCondition(){
        $cad_fechas = "";
        if (isset($_SESSION['userdataSIC']->rango_inicio_gc) && isset($_SESSION['userdataSIC']->rango_fin_gc)) { //si no ingresa una fecha se seleciona el día de hoy como máximo
            $rango_inicio = $_SESSION['userdataSIC']->rango_inicio_gc;
            $rango_fin = $_SESSION['userdataSIC']->rango_fin_gc;
            $cad_fechas = " AND 
                            ((FechaHora_Recepcion >= '" . $rango_inicio . " 00:00:00'  AND 
                            FechaHora_Recepcion <= '" . $rango_fin . " 23:59:59' )OR
                            (FechaHora_Recepcion >= CAST('" . $rango_inicio . " 00:00:00' AS date) AND
                            FechaHora_Recepcion <= CAST('" . $rango_fin . " 23:59:59' AS date)))
                            ";
        }

        return $cad_fechas;
    }
    
/*-------------------------------------- Get de informacion de Casos------------------------------------------ */
    public function getUsuarios(){
        $sql = "SELECT 	User_Name
        FROM usuario
        WHERE usuario.Area = 'DEPARTAMENTO DE INFORMACION' " ;
        $this->db->query($sql);
        return $this->db->registers();
    }

    public function getPrincipalesAll($Folio_infra){
        $sql = "SELECT 	*
        FROM evento
        WHERE evento.Folio_infra = " . $Folio_infra;
        $this->db->query($sql);
        return $this->db->register();
    }
    public function getInfoDetencion($Folio_infra){
        $sql = "SELECT 	*
        FROM ubicaciones_detencion_evento
        WHERE ubicaciones_detencion_evento.Folio_infra = " . $Folio_infra;
        $this->db->query($sql);
        return $this->db->register();
    }
    public function getPrincipales($Folio_infra){
        $sql = "SELECT 	*
        FROM gc_evento_filtro_1
        WHERE gc_evento_filtro_1.Folio_infra = " . $Folio_infra;

        $this->db->query($sql);
        return $this->db->register();

    }

    public function getResumen($Folio_infra){
        $sql = "SELECT 	*
        FROM evento
        WHERE evento.Folio_infra = " . $Folio_infra;

        $this->db->query($sql);
        return $this->db->register();

    }
    public function getDelitosC($Folio_infra){
        $sql = "SELECT 	*
        FROM delitos_asociados_evento
        WHERE delitos_asociados_evento.Folio_infra = " . $Folio_infra;

        $this->db->query($sql);
        return $this->db->registers();

    }
    
    public function getHechosC($Folio_infra){
        $sql = "SELECT 	*
        FROM historico_descripcion_hecho
        WHERE historico_descripcion_hecho.Folio_infra = " . $Folio_infra;

        $this->db->query($sql);
        return $this->db->registers();

    }
    public function getResponsablesC($Folio_infra){
        $sql = "SELECT 	*
        FROM persona_p
        WHERE persona_p.Folio_infra = " . $Folio_infra;

        $this->db->query($sql);
        return $this->db->registers();

    }
    public function getVehiculosC($Folio_infra){
        $sql = "SELECT 	*
        FROM vehiculo_p
        WHERE vehiculo_p.Folio_infra = " . $Folio_infra;

        $this->db->query($sql);
        return $this->db->registers();

    }
    public function getVehiculosCorroborado($Folio_infra){
        $sql = "SELECT 	*
        FROM vehiculo_p
        WHERE vehiculo_p.Folio_infra = " . $Folio_infra ." AND vehiculo_p.Estado_Veh='CORROBORADO'" ;

        $this->db->query($sql);
        return $this->db->registers();

    }
    public function getResponsablesCorroborado($Folio_infra){
        $sql = "SELECT 	*
        FROM persona_p
        WHERE persona_p.Folio_infra = " . $Folio_infra." AND persona_p.Estado_Res='CORROBORADO'" ;

        $this->db->query($sql);
        return $this->db->registers();

    }

    public function getFotos($Folio_infra){
        $sql = "SELECT 	*
        FROM fotos_seguimiento
        WHERE fotos_seguimiento.Folio_infra = " . $Folio_infra;

        $this->db->query($sql);
        return $this->db->registers();

    }

    public function getEntrevistas($Folio_infra){
        $sql = "SELECT 	*
        FROM entrevistas_seguimiento
        WHERE entrevistas_seguimiento.Folio_infra = " . $Folio_infra;

        $this->db->query($sql);
        return $this->db->registers();

    }

    public function GetInfo_Evento($Folio_infra){
        $sql = "SELECT Folio_infra, Folio_911, hechos_concat, delitos_concat  FROM gc_evento_filtro_1 WHERE Folio_infra = " . $Folio_infra;
        $this->db->query($sql);
        return $this->db->registers();
    }

    public function getTodo911(){
        $sql = "SELECT Folio_911 FROM evento" ;
        $this->db->query($sql);
        return $this->db->registers();

    }

    public function getTareasPrincipal($Folio_infra){
        $sql = "SELECT 	id_tarea,tipo_tarea
        FROM tareas
        WHERE tareas.folio_sic = " . $Folio_infra;

        $this->db2->query($sql);
        return $this->db2->registers();
    }
    public function getStatusTareaTipo($id_tarea, $tipo_tarea){
        switch($tipo_tarea){
            case 'BARRIDO':
                $sql = "SELECT * FROM tareas_barrido WHERE tareas_barrido.id_tarea = " . $id_tarea;
            break;
            case 'BUSQUEDA':
                $sql = "SELECT * FROM tareas_busqueda WHERE tareas_busqueda.id_tarea = " . $id_tarea;
            break;
            case 'ENTREVISTA':
                $sql = "SELECT * FROM tareas_entrevista WHERE tareas_entrevista.id_tarea = " . $id_tarea;
            break;
            case 'OTRA':
                $sql = "SELECT * FROM tareas_otra WHERE tareas_otra.id_tarea = " . $id_tarea;
            break;
            case 'VIGILANCIA':
                $sql = "SELECT * FROM tareas_vigilancia WHERE tareas_vigilancia.id_tarea = " . $id_tarea;
            break;
        }
        $this->db2->query($sql);
        return $this->db2->registers();
    }
    public function getReporteZen($tabla,$id){
        $sql = "SELECT p.*,r.folio_sic AS Folio_AURA, r.tipo_tarea AS Tipo
                FROM ". $tabla." p
                JOIN tareas r ON p.id_tarea  = r.id_tarea 
                WHERE r.folio_sic != 0 GROUP BY Folio_AURA ORDER BY ".$id." DESC
                LIMIT 3";

        $this->db2->query($sql);
        return $this->db2->registers();
    }

    public function getAllInfoAlertaByCadena($from_where_sentence = ""){ 
    	$sqlAux = "SELECT * "
    				.$from_where_sentence."
                    ";  //query a la DB
        $this->db->query($sqlAux);          //se prepara el query mediante PDO
        return $this->db->registers();      //retorna todos los registros devueltos por la consulta
    }
    public function getEventoDByCadena($cadena, $filtro ){
        //CONSULTA COINCIDENCIAS DE CADENA PARA EVENTOS 
        $cadena=$this->eliminar_acentos($cadena);
        //sentencia from_where para hacer la busqueda por la cadena ingresada
        $from_where_sentence = $this->generateFromWhereSentence($cadena, $filtro);
        $numPage = 1;
        $no_of_records_per_page = NUM_MAX_REG_PAGE; //total de registros por pagination
        $offset = ($numPage - 1) * $no_of_records_per_page; // desplazamiento conforme a la pagina

        $results = $this->getTotalPages($no_of_records_per_page, $from_where_sentence);  //total de páginas conforme a la busqueda
        //info de retorno para la creacion de los links conforme a la cadena ingresada
        $data['rows_Rems'] = $this->getDataCurrentPage($offset, $no_of_records_per_page, $from_where_sentence);   //se obtiene la información de la página actual
        $data['numPage'] = $numPage; //numero pag actual para la pagination footer
        $data['total_pages'] = $results['total_pages']; //total pages para la pagination
        $data['total_rows'] = $results['total_rows'];   //total de registro hallados

        return $data;
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
    /*------------------METODOS PARA TRAER LA INFOMACION DE RED DE VINCULO----------------------*/
    public function getCamaras(){
        $sql = "SELECT * FROM catalogo_ubicaciones_camaras ORDER BY (Id_Dato)";
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getAllPrincipales($Id_Seguimiento){
        $sql = "SELECT 	*
        FROM seguimiento_gabinete
        WHERE seguimiento_gabinete.Id_Seguimiento = " . $Id_Seguimiento;
        $this->db->query($sql);
        $data_principales= $this->db->register();
        
        $sql = "SELECT 	*
        FROM gc_evento_filtro_3
        WHERE gc_evento_filtro_3.Id_Seguimiento = " . $Id_Seguimiento;
        $this->db->query($sql);
        $data_eventos= $this->db->registers();

        $sql = "SELECT 	*
        FROM delitos_asociados_seguimiento
        WHERE delitos_asociados_seguimiento.Id_Seguimiento = " . $Id_Seguimiento;
        $this->db->query($sql);
        $data_delitos= $this->db->registers();
        $data = [
            'principal'=> $data_principales,
            'eventos'   => $data_eventos,
            'delitos' => $data_delitos
            ]; 
        return $data;
    }
    public function getPersonas($Id_Seguimiento){
        $sql = "SELECT 	*
        FROM persona_gabinete
        WHERE persona_gabinete.Id_Seguimiento = ". $Id_Seguimiento;
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getVehiculos($Id_Seguimiento){
        $sql = "SELECT 	*
        FROM vehiculos_gabinete
        WHERE vehiculos_gabinete.Id_Seguimiento = " . $Id_Seguimiento;
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getDomiciliosOneRegister($Id_dato,$Tipo_Entidad){
        $sql = "SELECT 	*
        FROM domicilios
        WHERE domicilios.Id_Dato = " . $Id_dato." AND domicilios.Tipo_Entidad ='".$Tipo_Entidad."'";
        $this->db->query($sql);
        $this->db->registers();
        return $this->db->registers();;
    }
    public function getAntecedentesOneRegister($Id_dato,$Tipo_Entidad){
        $sql = "SELECT 	*
        FROM antecedentes_gabinete
        WHERE antecedentes_gabinete.Id_Dato = " . $Id_dato." AND antecedentes_gabinete.Tipo_Entidad ='".$Tipo_Entidad."'";
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getForenciasOneRegister($Id_dato){
        $sql = "SELECT 	*
        FROM forencias_gabinete
        WHERE forencias_gabinete.Id_Persona  = " . $Id_dato;
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getRedesSocialesOneRegister($Id_dato){
        $sql = "SELECT 	*
        FROM redes_sociales_gabinete
        WHERE redes_sociales_gabinete.Id_Persona  = " . $Id_dato;
        $this->db->query($sql);
        return $this->db->registers();
    }
    /*--------------------------------FUNCIÓN PARA LA VISUALIZACION-----------------------------------*/
    public function permisoVisualizacion($user){
        $sql = " SELECT  
                            permisos.Visualizacion
                    FROM usuario
                    LEFT JOIN permisos ON permisos.Id_Permisos = usuario.Id_Permisos
                    WHERE User_Name='".$user."'";

        $this->db->query($sql);
        return $this->db->register();
    }
    public function logs($user, $ip, $descripcion){// para ecribir los movimientos de error en el gestor 
        try {
            $this->db->beginTransaction();
            $sql = " INSERT
                    INTO logs_error(
                        Id_Usuario,
                        Ip_Acceso,
                        Descripcion_error
                    )
                    VALUES(
                        trim($user),
                        '" . trim($ip) . "',
                        '" . trim($descripcion) . "'
                    )
            ";
            $this->db->query($sql);
            $this->db->execute();
            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
        }
    }
}