<?php
/*
    Catálogos:
    1  - Delitos / Faltas admin
    2  - Armas
    3 - Tipo de violencia
    4 - Zonas / sectores
    5 - Vectores
    6 - Marcas vehículos
	7 - Tipos de vehiculo
	8 - Submarcas de vehiculos
	9 - Colonias
	10 - Calles
	11 - Codigos postales
	12 - Nombres claves
	13 - Procedencia de informacion
	14 - Areas
*/
class Catalogo
{
	
	public $db; //variable para instanciar el objeto PDO
    public function __construct(){
        $this->db = new Base(); //se instancia el objeto con los métodos de PDO
    }

    //Obtener la info del catálogo conforme a la cadena de búsqueda y al catálogo en sí
    public function getCatalogoByCadena($cadena,$catalogo = '1'){
        //CONSULTA COINCIDENCIAS DE CADENA CONFORME AL CATALOGO SELECCIONADO

        if (!is_numeric($catalogo) || !($catalogo>=MIN_CATALOGO) || !($catalogo<=MAX_CATALOGO))
        	$catalogo = 1;
        
        //sentencia from_where para hacer la busqueda por la cadena ingresada
        $from_where_sentence = $this->generateFromWhereSentence($catalogo,$cadena);
        $numPage = 1;
        $no_of_records_per_page = NUM_MAX_REG_PAGE; //total de registros por pagination
        $offset = ($numPage-1) * $no_of_records_per_page; // desplazamiento conforme a la pagina

        $results = $this->getTotalPages($no_of_records_per_page,$from_where_sentence);  //total de páginas conforme a la busqueda
        //info de retorno para la creacion de los links conforme a la cadena ingresada
        $data['cat_rows'] = $this->getDataCurrentPage($offset,$no_of_records_per_page,$from_where_sentence);   //se obtiene la información de la página actual
        $data['numPage'] = $numPage; //numero pag actual para la pagination footer
        $data['total_pages'] = $results['total_pages']; //total pages para la pagination
        $data['total_rows'] = $results['total_rows'];   //total de registro hallados
        
        return $data;
    }
    //esta funcion retorna tanto el número total de paginas para los links como el total de registros contados conforme a la busqueda
    public function getTotalPages($no_of_records_per_page,$from_where_sentence = ""){ 
        $sql_total_pages = "SELECT COUNT(*) as Num_Pages ".$from_where_sentence; //total registros
        $this->db->query($sql_total_pages);      //prepararando query
        $total_rows = $this->db->register()->Num_Pages; //ejecutando query y recuperando el valor obtenido
        $total_pages = ceil($total_rows / $no_of_records_per_page); //calculando el total de paginations

        $data['total_rows'] = $total_rows;
        $data['total_pages'] = $total_pages;
        return $data;
    }

    public function getDataCurrentPage($offset,$no_of_records_per_page,$from_where_sentence = ""){

        $sql = "
                SELECT * "
                .$from_where_sentence." 
                LIMIT $offset,$no_of_records_per_page
                ";

        $this->db->query($sql);
        return $this->db->registers();
    }

    public function generateFromWhereSentence($catalogo,$cadena=""){
        $from_where_sentence = "";
        switch ($catalogo) {
        	case '1': $from_where_sentence.= "FROM catalogo_delictivo WHERE Descripcion LIKE '%".$cadena."%' OR Tipo_actividad LIKE '%".$cadena."%'"; break;
        	case '2': $from_where_sentence.= "FROM catalogo_tipos_armas WHERE Tipo_Arma LIKE '%".$cadena."%'"; break;
            case '3': $from_where_sentence.= "FROM catalogo_tipo_violencia WHERE Tipo_Violencia LIKE '%".$cadena."%'"; break;
            case '4': $from_where_sentence.= "FROM catalogo_zonas_sectores WHERE Tipo_Grupo LIKE '%".$cadena."%' OR Zona_Sector LIKE '%".$cadena."%'"; break;
            case '5': $from_where_sentence.= "FROM catalogo_vectores WHERE Vector LIKE '%".$cadena."%' OR Id_Vector_Interno LIKE '%".$cadena."%'"; break;
            case '6': $from_where_sentence.= "FROM catalogo_marca_vehiculos_io WHERE Marca LIKE '%".$cadena."%'"; break;
            case '7': $from_where_sentence.= "FROM catalogo_tipos_vehiculos WHERE Tipo LIKE '%".$cadena."%'"; break;
            case '8': $from_where_sentence.= "FROM catalogo_submarcas_vehiculos WHERE Submarca LIKE '%".$cadena."%'"; break;
        	case '9': $from_where_sentence.= "FROM catalogo_colonias WHERE Tipo LIKE '%".$cadena."%' OR Colonia LIKE '%".$cadena."%'"; break;
            case '10': $from_where_sentence.= "FROM catalogo_calle WHERE Calle LIKE '%".$cadena."%'"; break;
            case '11': $from_where_sentence.= "FROM catalogo_codigos_postales WHERE Id_cp LIKE '%".$cadena."%' OR Codigo_postal LIKE '%".$cadena."%' OR Nombre LIKE '%".$cadena."%'"; break;
            case '12': $from_where_sentence.= "FROM catalogo_nombres_clave WHERE id LIKE '%".$cadena."%' OR nombre LIKE '%".$cadena."%' OR APpaterno LIKE '%".$cadena."%' OR APmaterno LIKE '%".$cadena."%' OR clave LIKE '%".$cadena."%'"; break;
            case '13': $from_where_sentence.= "FROM catalogo_fuente_casos WHERE id_fuente LIKE '%".$cadena."%' OR fuente LIKE '%".$cadena."%' "; break;
            case '14': $from_where_sentence.= "FROM catalogo_area WHERE id_area LIKE '%".$cadena."%' OR Area LIKE '%".$cadena."%' "; break;
            case '15': $from_where_sentence.= "FROM catalogo_tipo_sinviolencia WHERE Tipo_SViolencia LIKE '%".$cadena."%'"; break;
            case '16': $from_where_sentence.= "FROM catalogo_indicativo_entrevistador WHERE Indicativo LIKE '%".$cadena."%'"; break;
            case '17': $from_where_sentence.= "FROM catalogo_tipo_dato_entrevista WHERE Tipo LIKE '%".$cadena."%'"; break;
            case '18': $from_where_sentence.= "FROM catalogo_ubicaciones_camaras WHERE Calle LIKE '%".$cadena."%'"; break;
            default:
        		case '1': $from_where_sentence.= "FROM catalogo_delictivo WHERE Descripcion LIKE '%".$cadena."%' OR Tipo_actividad LIKE '%".$cadena."%'"; break;
        	break;
        }
        return $from_where_sentence;
    }
    public function getAllInfoCatalogoByCadena($from_where_sentence = ""){
    	$sqlAux = "SELECT *"
    				.$from_where_sentence."
                    ";  //query a la DB
        $this->db->query($sqlAux);          //se prepara el query mediante PDO
        return $this->db->registers();      //retorna todos los registros devueltos por la consulta
    }

    public function getModalidadDetencion($post)
    {
        $modalidad = $post['modalidad'];
        $sql = "SELECT * FROM catalogo_forma_detencion WHERE Forma_Detencion = '".$modalidad."'";
        $this->db->query($sql);
        return $this->db->registers();
    }

    public function InsertOrUpdateCatalogo($post){
        $catalogo = $post['catalogo'];
        $action   = $post['action'];
        $response = "Error";

        //switch de catalogo
        try{
            $this->db->beginTransaction(); //inicio de transaction
                switch ($catalogo) {
                    case '1':
                        switch ($action) { //switch de action 1-insertar  2-actualizar
                            case '1':
                                $sql = "INSERT INTO catalogo_delictivo (Id_delito,Descripcion,Id_actividad,Tipo_actividad) 
                                        VALUES ('".$post['Id_delito']."','".$post['Descripcion']."',".$post['Id_actividad'].",'".$post['Tipo_actividad']."')";
                            break;
                            case '2':
                                $sql = "UPDATE catalogo_delictivo
                                        SET Id_delito     = '".$post['Id_delito']."',
                                            Descripcion    = '".$post['Descripcion']."',
                                            Id_actividad          = ".$post['Id_actividad'].",
                                            Tipo_actividad     = '".$post['Tipo_actividad']."'
                                        WHERE Id_dato = ".$post['Id_dato']."
                                       ";
                            break;
                        }
                    break;
                    case '2':
                        switch ($action) { //switch de action 1-insertar  2-actualizar
                            case '1':
                                $sql = "INSERT INTO catalogo_tipos_armas (Tipo_Arma) 
                                        VALUES ('".$post['Tipo_Arma']."')";
                            break;
                            case '2':
                                $sql = "UPDATE catalogo_tipos_armas 
                                        SET Tipo_Arma    = '".$post['Tipo_Arma']."' 
                                        WHERE Id_Tipo_Arma = ".$post['Id_Tipo_Arma']."
                                       ";
                            break;
                        }
                    break;
                    case '3':
                        switch ($action) { //switch de action 1-insertar  2-actualizar
                            case '1':
                                $sql = "INSERT INTO catalogo_tipo_violencia (Tipo_Violencia) 
                                        VALUES ('".$post['Tipo_Violencia']."')";
                            break;
                            case '2':
                                $sql = "UPDATE catalogo_tipo_violencia 
                                        SET Tipo_Violencia    = '".$post['Tipo_Violencia']."' 
                                        WHERE Id_Tipo_Violencia = ".$post['Id_Tipo_Violencia']."
                                       ";
                            break;
                        }
                    break;
                    case '4':
                        switch ($action) { //switch de action 1-insertar  2-actualizar
                            case '1':
                                $sql = "INSERT INTO catalogo_zonas_sectores (Tipo_Grupo,Zona_Sector) 
                                        VALUES ('".$post['Tipo_Grupo']."','".$post['Zona_Sector']."')";
                            break;
                            case '2':
                                $sql = "UPDATE catalogo_zonas_sectores 
                                        SET Tipo_Grupo    = '".$post['Tipo_Grupo']."',
                                            Zona_Sector     = '".$post['Zona_Sector']."' 
                                        WHERE Id_Zona_Sector = ".$post['Id_Zona_Sector']."
                                       ";
                            break;
                        }
                    break;
                    case '5':
                        switch ($action) { //switch de action 1-insertar  2-actualizar
                            case '1':
                                $sql = "INSERT INTO catalogo_vectores (Id_Vector_Interno,Zona,Vector,Region) 
                                        VALUES ('".$post['Id_Vector_Interno']."','".$post['Zona']."','".$post['Vector']."','".$post['Region']."')";
                            break;
                            case '2':
                                $sql = "UPDATE catalogo_vectores 
                                        SET Id_Vector_Interno     = '".$post['Id_Vector_Interno']."',
                                            Zona    = '".$post['Zona']."',
                                            Vector          = '".$post['Vector']."',
                                            Region     = '".$post['Region']."'
                                        WHERE Id_Vector = ".$post['Id_Vector']."
                                       ";
                            break;
                        }
                    break;
                    case '6':
                        switch ($action) { //switch de action 1-insertar  2-actualizar
                            case '1':
                                $sql = "INSERT INTO catalogo_marca_vehiculos_io (Marca) 
                                        VALUES ('".$post['Marca']."')";
                            break;
                            case '2':
                                $sql = "UPDATE catalogo_marca_vehiculos_io 
                                        SET Marca = '".$post['Marca']."'
                                        WHERE Id_Marca_Io = ".$post['Id_Marca_Io']."
                                       ";
                            break;
                        }
                    break;
                    case '7':
                        switch ($action) { //switch de action 1-insertar  2-actualizar
                            case '1':
                                $sql = "INSERT INTO catalogo_tipos_vehiculos (Tipo) 
                                        VALUES ('".$post['Valor_Tipo']."')";
                            break;
                            case '2':
                                $sql = "UPDATE catalogo_tipos_vehiculos 
                                        SET Tipo    = '".$post['Valor_Tipo']."' 
                                        WHERE Id_Tipo_veh = ".$post['Id_Tipo_Vehiculo']."
                                       ";
                            break;
                        }
                    break;
                    case '8':
                        switch ($action) { //switch de action 1-insertar  2-actualizar
                            case '1':
                                $sql = "INSERT INTO catalogo_submarcas_vehiculos (Submarca) 
                                        VALUES ('".$post['Valor_Submarca']."')";
                            break;
                            case '2':
                                $sql = "UPDATE catalogo_submarcas_vehiculos 
                                        SET Submarca    = '".$post['Valor_Submarca']."' 
                                        WHERE Id_Submarca_veh = ".$post['Id_Submarca_Vehiculo']."
                                        ";
                            break;
                        }
                    break;
                    case '9':
                        switch ($action) { //switch de action 1-insertar  2-actualizar
                            case '1':
                                $sql = "INSERT INTO catalogo_colonias (Tipo,Colonia) 
                                        VALUES ('".$post['tipo']."','".$post['colonia']."')";
                            break;
                            case '2':
                                $sql = "UPDATE catalogo_colonias 
                                        SET Tipo            = '".$post['tipo']."',
                                            Colonia         = '".$post['colonia']."' 
                                        WHERE Id_colonia = ".$post['Id_colonia']."
                                       ";
                            break;
                        }
                    break;
                    case '10':
                        switch ($action) { //switch de action 1-insertar  2-actualizar
                            case '1':
                                $sql = "INSERT INTO catalogo_calle (Calle) 
                                        VALUES ('".$post['Id_calle_desc']."')";
                            break;
                            case '2':
                                $sql = "UPDATE catalogo_calle 
                                        SET Calle    = '".$post['Id_calle_desc']."' 
                                        WHERE Id_Calle = ".$post['Id_calle']."
                                        ";
                            break;
                        }
                    break;
                    case '11':
                        switch ($action) { //switch de action 1-insertar  2-actualizar
                            case '1':
                                $sql = "INSERT INTO catalogo_codigos_postales (Codigo_postal,Nombre) 
                                        VALUES ('".$post['Codigo_postal']."','".$post['Nombre']."')";
                            break;
                            case '2':
                                $sql = "UPDATE catalogo_codigos_postales 
                                        SET Codigo_postal            = '".$post['Codigo_postal']."',
                                            Nombre         = '".$post['Nombre']."'
                                        WHERE Id_cp = ".$post['Id_cp']."
                                        ";
                            break;
                        }
                    break;
                    case '12':
                        switch ($action) { //switch de action 1-insertar  2-actualizar
                            case '1':
                                $sql = "INSERT INTO catalogo_nombres_clave (nombre,APpaterno,APmaterno,clave) 
                                VALUES ('".$post['Nombre_clave']."','".$post['Ap_paterno_clave']."','".$post['Ap_materno_clave']."','".$post['Clave']."')";
                            break;
                            case '2':
                                $sql = "UPDATE catalogo_nombres_clave
                                        SET nombre    = '".$post['Nombre_clave']."',
                                            APpaterno = '".$post['Ap_paterno_clave']."',
                                            APmaterno = '".$post['Ap_materno_clave']."',
                                            clave = '".$post['Clave']."'
                                        WHERE id = ".$post['Id_clave']."
                                        ";
                            break;
                        }
                    break;
                    case '13':
                        switch ($action) { //switch de action 1-insertar  2-actualizar
                            case '1':
                                $sql = "INSERT INTO catalogo_fuente_casos (fuente) 
                                VALUES ('".$post['fuente']."')";
                            break;
                            case '2':
                                $sql = "UPDATE catalogo_fuente_casos
                                        SET fuente    = '".$post['fuente']."'
                                        WHERE id_fuente = ".$post['id_fuente']."
                                        ";
                            break;
                        }
                    break;
                    case '14':
                        switch ($action) { //switch de action 1-insertar  2-actualizar
                            case '1':
                                $sql = "INSERT INTO catalogo_area (Area) 
                                VALUES ('".$post['area']."')";
                            break;
                            case '2':
                                $sql = "UPDATE catalogo_area
                                        SET Area   = '".$post['area']."'
                                        WHERE id_area = ".$post['id_area']."
                                        ";
                            break;
                        }
                    break;
                    case '15':
                        switch ($action) { //switch de action 1-insertar  2-actualizar
                            case '1':
                                $sql = "INSERT INTO catalogo_tipo_sinviolencia (Tipo_SViolencia) 
                                        VALUES ('".$post['Tipo_Violencia']."')";
                            break;
                            case '2':
                                $sql = "UPDATE catalogo_tipo_sinviolencia 
                                        SET Tipo_SViolencia    = '".$post['Tipo_Violencia']."' 
                                        WHERE Id_Tipo_SViolencia = ".$post['Id_Tipo_Violencia']."
                                       ";
                            break;
                        }
                    break;
                    case '16':
                        switch ($action) { //switch de action 1-insertar  2-actualizar
                            case '1':
                                $sql = "INSERT INTO catalogo_indicativo_entrevistador (Indicativo) 
                                        VALUES ('".$post['Indicativo']."')";
                            break;
                            case '2':
                                $sql = "UPDATE catalogo_indicativo_entrevistador 
                                        SET Indicativo    = '".$post['Indicativo']."' 
                                        WHERE Id_Dato  = ".$post['Id_Dato']."
                                       ";
                            break;
                        }
                    break;
                    case '17':
                        switch ($action) { //switch de action 1-insertar  2-actualizar
                            case '1':
                                $sql = "INSERT INTO catalogo_tipo_dato_entrevista (Tipo) 
                                        VALUES ('".$post['Tipo']."')";
                            break;
                            case '2':
                                $sql = "UPDATE catalogo_tipo_dato_entrevista 
                                        SET Tipo    = '".$post['Tipo']."' 
                                        WHERE Id_Dato  = ".$post['Id_Dato_Tipo']."
                                       ";
                            break;
                        }
                    break;
                    case '18':
                        switch ($action) { //switch de action 1-insertar  2-actualizar
                            case '1':
                                $sql = "INSERT INTO catalogo_ubicaciones_camaras (Calle,Calle2,Info_Adicional,CoordX,CoordY) 
                                        VALUES ('".$post['Calle']."',
                                                '".$post['Calle2']."',
                                                '".$post['Info_Adicional']."',
                                                '".$post['CoordX']."',
                                                '".$post['CoordY']."'
                                        )";
                            break;
                            case '2':
                                $sql = "UPDATE catalogo_ubicaciones_camaras 
                                        SET 
                                        Calle    = '".$post['Calle']."', 
                                        Calle2    = '".$post['Calle2']."', 
                                        Info_Adicional    = '".$post['Info_Adicional']."', 
                                        CoordX    = '".$post['CoordX']."', 
                                        CoordY    = '".$post['CoordY']."'
                                        WHERE Id_Dato  = ".$post['Id_Dato']."
                                       ";
                            break;
                        }
                    break;
                }
            $this->db->query($sql); //se prepara query
            $this->db->execute();   //se ejecuta el query
            $this->db->commit();  //si todo sale bien, la transaction realiza commit de los queries
            $response = "Success";
        }
        catch (Exception $e) {
            $this->db->rollBack();    //si algo falla realiza el rollBack por seguridad
            $response = "Fatal Error: ".$e->getMessage();
        }
            

        return $response;
    }

    public function deleteCatalogoRow($post){
        $catalogo = $post['catalogo'];
        $id_reg   = $post['Id_Reg'];
        $response = "Error";

        //switch de catalogo
        try{
            $this->db->beginTransaction(); //inicio de transaction
                switch ($catalogo) {
                    case '1': $sql = "DELETE FROM catalogo_delictivo WHERE Id_dato = ".$id_reg; break;
                    case '2': $sql = "DELETE FROM catalogo_tipos_armas WHERE Id_Tipo_Arma = ".$id_reg; break;
                    case '3': $sql = "DELETE FROM catalogo_tipo_violencia WHERE Id_Tipo_Violencia = ".$id_reg; break;
                    case '4': $sql = "DELETE FROM catalogo_zonas_sectores WHERE Id_Zona_Sector = ".$id_reg; break;
                    case '5': $sql = "DELETE FROM catalogo_vectores WHERE Id_Vector = ".$id_reg; break;
                    case '6': $sql = "DELETE FROM catalogo_marca_vehiculos_io WHERE Id_Marca_Io = ".$id_reg; break;
                    case '7': $sql = "DELETE FROM catalogo_tipos_vehiculos WHERE Id_Tipo_veh = ".$id_reg; break;
                    case '8': $sql = "DELETE FROM catalogo_submarcas_vehiculos WHERE Id_Submarca_veh = ".$id_reg; break;
                    case '9': $sql = "DELETE FROM catalogo_colonias WHERE Id_colonia = ".$id_reg; break;
                    case '10': $sql = "DELETE FROM catalogo_calle WHERE Id_Calle = ".$id_reg; break;
                    case '11': $sql = "DELETE FROM catalogo_codigos_postales WHERE Id_cp = ".$id_reg; break;
                    case '12': $sql = "DELETE FROM catalogo_nombres_clave WHERE id = ".$id_reg; break;
                    case '13': $sql = "DELETE FROM catalogo_fuente_casos WHERE id_fuente = ".$id_reg; break;
                    case '14': $sql = "DELETE FROM catalogo_area WHERE id_area = ".$id_reg; break;
                    case '15': $sql = "DELETE FROM catalogo_tipo_sinviolencia WHERE Id_Tipo_SViolencia = ".$id_reg; break;
                    case '16': $sql = "DELETE FROM catalogo_indicativo_entrevistador WHERE Id_Dato= ".$id_reg; break;
                    case '17': $sql = "DELETE FROM catalogo_tipo_dato_entrevista WHERE Id_Dato= ".$id_reg; break;
                    case '18': $sql = "DELETE FROM catalogo_ubicaciones_camaras WHERE Id_Dato= ".$id_reg; break;
                }
            $this->db->query($sql); //se prepara query
            $this->db->execute();   //se ejecuta el query
            $this->db->commit();  //si todo sale bien, la transaction realiza commit de los queries
            $response = "Success";
        }
        catch (Exception $e) {
            $this->db->rollBack();    //si algo falla realiza el rollBack por seguridad
            $response = "Fatal Error: ".$e->getMessage();
        }
            

        return $response;
    }


    public function getCatalogforDropdown($post){
        //SELECT Valor_MF FROM catalogo_media_filiacion WHERE Tipo_MF = 'COMPLEXIÓN'
        $sql = "SELECT Valor_MF FROM catalogo_media_filiacion WHERE Tipo_MF ="."'".$post."'"."ORDER BY Id_MF";
        $this->db->query($sql);
        $resultado = $this->db->registers();
        return $resultado;
    }

    public function getSimpleCatalogo($campo, $tabla){
        $sql = "SELECT DISTINCT $campo FROM $tabla";
        $this->db->query($sql);
        $resultado = $this->db->registers();
        return $resultado;

    }

    /* ----- ----- ----- Función zona o sector ----- ----- ----- */
    public function getZonaSector($tipo){
        $sql = "SELECT Zona_Sector FROM catalogo_zonas_sectores WHERE Tipo_Grupo = "."'".$tipo."'";
        $this->db->query($sql);
        $resultado = $this->db->registers();
        return $resultado;
    }

    /*---------------Función para obtener los vectores solicitados */

    public function getVector($tipo){
        $aux = (is_int($tipo) ? "WHERE Zona = " .  $tipo   : "WHERE Zona = " .  "'" . $tipo ."'" );
        $sql = "SELECT Id_vector_Interno, Region FROM catalogo_vectores " . $aux . " ORDER BY Zona ASC, Id_Vector_Interno ASC";
        $this->db->query($sql);
        return $this->db->registers();
    }

    /*Funciones para Eventos delictivos*/
    public function getCatalogoGruposPolicia(){
        $sql = "SELECT CONCAT(Tipo_Grupo,' - ',Valor_Grupo) AS Grupo 
                FROM catalogo_grupos
                ORDER BY Tipo_Grupo,Valor_Grupo;    
                ";
        $this->db->query($sql);
        return $this->db->registers();
    }
    /*fin Eventos delictivos*/

    /*Funciones inspecciones*/
    public function getGruposZonasSectores(){
        $sql = "SELECT DISTINCT Tipo_Grupo FROM catalogo_grupos_inspecciones";
        $this->db->query($sql);
        $grupos = $this->db->registers();
        return $grupos;
    }
    /*fin inspecciones*/

    /*Obteniendo los Eventos para "Eventos delictivos"*/
    public function getEventos( $termino ){
        $sql = "SELECT descripcion FROM catalogo_911 WHERE descripcion LIKE " ."'". $termino."%' OR descripcion LIKE " . "'%" .$termino . "%' OR descripcion LIKE " . "'" . $termino . "%'" ;
        $this->db->query($sql);
        return $this->db->registers();
    }

    public function getColonia( $termino ){
        $sql = "SELECT Tipo_Colonia, Colonia  FROM catalogo_colonia WHERE Colonia LIKE " ."'". $termino."%' OR Colonia LIKE " . "'%" .$termino . "%' OR Colonia LIKE " . "'" . $termino . "%'" ;
        $this->db->query( $sql );
        return $this->db->registers();
    }
    /*Funciones añadidas para catalogo de colonias, calles, estados y municipios*/
    public function getColoniaCatalogo( $termino ){
        $sql = "SELECT Tipo, Colonia  FROM catalogo_colonias WHERE Colonia LIKE " ."'". $termino."%' OR Colonia LIKE " . "'%" .$termino . "%' OR Colonia LIKE " . "'" . $termino . "%'" ;
        $this->db->query( $sql );
        return $this->db->registers();
    }
    public function getCallesCatalogo( $termino ){
        $sql = "SELECT Calle  FROM catalogo_calle WHERE Calle LIKE " ."'". $termino."%' OR Calle LIKE " . "'%" .$termino . "%' OR Calle LIKE " . "'" . $termino . "%'" ;
        $this->db->query( $sql );
        return $this->db->registers();
    }
    public function getCPCatalogo($termino ){
        //$termino=str_replace([" DE "," de "," DEL "," del "]," ",$termino);
        $sql = "SELECT *  FROM catalogo_codigos_postales WHERE Nombre LIKE " ."'%". $termino."%'" ;
        $this->db->query( $sql );
        return $this->db->registers();
    }
    public function getSimpleCatalogoOrder($campo, $tabla,$order){
        $sql = "SELECT DISTINCT $campo FROM $tabla Order By $order";
        $this->db->query($sql);
        $resultado = $this->db->registers();
        return $resultado;
    }
    public function getMunicipiosEstados( $termino,$estado ){
        $sql = "SELECT Municipio  FROM catalogo_estados_municipios WHERE (Municipio LIKE " ."'". $termino."%' OR Municipio LIKE " . "'%" .$termino . "%' OR Municipio LIKE " . "'" . $termino . "%') AND Estado = "."'".$estado."'";
        $this->db->query( $sql );
        return $this->db->registers();
    }
    public function existeMunicipio( $estado,$municipio ){
        $sql = "SELECT COUNT(Municipio) AS CONTADOR from catalogo_estados_municipios where Estado=" ."'". $estado."' AND Municipio="."'".$municipio."'";
        $this->db->query( $sql );
        return $this->db->registers();
    }
    /*Funciones añadidad para colonias y calles de catalogo*/
    public function getColonias()
    {
        $sql = "SELECT Tipo, Colonia FROM catalogo_colonias";
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getCalles()
    {
        $sql = "SELECT Calle FROM catalogo_calle";
        $this->db->query($sql);
        return $this->db->registers();
    }
    /*Funciones añadidas para catalogo de cuervos*/
    public function getIncidenciasCuervosPersonas(){
        $sql = "SELECT * FROM registros_match_lista_negra";
        $this->db->query($sql);
        return $this->db->registers();
    }

    public function getIncidenciasCuervosVehiculos(){
        $sql = "SELECT * FROM registros_vehiculos_lista";
        $this->db->query($sql);
        return $this->db->registers();
    }

    public function getCatalogoPersonas(){
        $sql = "SELECT * FROM catalogo_lista_negra";
        $this->db->query($sql);
        return $this->db->registers();
    }

    public function getCatalogoPlacaNip(){
        $sql = "SELECT * FROM catalogo_lista_vehiculos";
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getSubmarcaCatalogo($termino){
        $sql = "SELECT Submarca  FROM catalogo_submarcas_vehiculos WHERE Submarca LIKE " ."'". $termino."%' OR Submarca LIKE " . "'%" .$termino . "%' OR Submarca LIKE " . "'" . $termino . "%'" ;
        $this->db->query( $sql );
        return $this->db->registers();
    }
    public function getMarcaCatalogo($termino){
        $sql = "SELECT Marca  FROM catalogo_marca_vehiculos_io WHERE Marca LIKE " ."'". $termino."%' OR Marca LIKE " . "'%" .$termino . "%' OR Marca LIKE " . "'" . $termino . "%'" ;
        $this->db->query( $sql );
        return $this->db->registers();
    }
    public function getAMarcas()
    {
        $sql = "SELECT Marca FROM catalogo_marca_vehiculos_io";
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getSMarcas()
    {
        $sql = "SELECT Submarca FROM catalogo_submarcas_vehiculos";
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getTipoDatos()
    {
        $sql = "SELECT Tipo FROM catalogo_tipo_dato_entrevista";
        $this->db->query($sql);
        return $this->db->registers();
    }
    /*---------------Función para obtener todos los vectores */

    public function getAllVector(){
        $sql = "SELECT Id_vector_Interno, Zona, Region FROM catalogo_vectores ORDER BY Zona ASC, Id_Vector_Interno ASC";
        $this->db->query($sql);
        return $this->db->registers();
    }

    public function getAllFormaDetencion(){
        $sql = "SELECT * FROM catalogo_forma_detencion";
        $this->db->query($sql);
        return $this->db->registers();
    }

    public function getAllArma(){
        $sql = "SELECT * FROM catalogo_tipos_armas";
        $this->db->query($sql);
        return $this->db->registers();
    }

    public function getAllMedidasDroga(){
        $sql = "SELECT * FROM catalogo_medidas_droga";
        $this->db->query($sql);
        return $this->db->registers();
    }

    public function getAllAdicciones(){
        $sql = "SELECT * FROM catalogo_adicciones";
        $this->db->query($sql);
        return $this->db->registers();
    }

    public function getAllTipoAnimal(){
        $sql = "SELECT * FROM catalogo_animales_asegurados";
        $this->db->query($sql);
        return $this->db->registers();
    }

    public function getTipoAseguramiento($id_forma){
        $sql = "SELECT * FROM catalogo_forma_detencion WHERE Id_Forma_Detencion = $id_forma";
        $this->db->query($sql);
        return $this->db->registers();
    }
    
    public function getCargadores() {
        $sql = "SELECT * FROM `catalogo_tipos_armas` WHERE `Id_Tipo_Arma` > 8";
        $this->db->query($sql);
        return $this->db->registers();
    }

    public function getElementosLocales($termino){
        $sql = "SELECT `Id_Empleado`,`No_ControlMunicipio`, CONCAT(`Paterno`,' ',`Materno`,' ',`Nombre`) AS 'NombreCompleto',`Paterno`,`Materno`,`Nombre`,`TipoEmpleado`,`No_PlacaPolicia`
        FROM catalogo_elemento_participante 
        WHERE `No_ControlMunicipio` = '". $termino ."'  OR
        CONCAT(`Paterno`,' ',`Materno`,' ',`Nombre`) LIKE '%". $termino . "%' OR
        `No_PlacaPolicia` = '". $termino . "'
        ";
        $this->db->query($sql);
        return $this->db->registers();
    }
     /*---------------------FUNCIONES QUE SON NUEVAS PARA EL MODULO DE GESTOR DE CASOS--------------*/
     public function getAllTipoViolencia(){
        $sql = "SELECT * FROM catalogo_tipo_violencia";
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getAllTipoSViolencia(){
        $sql = "SELECT * FROM catalogo_tipo_sinviolencia";
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getIndicativo(){
        $sql = "SELECT * FROM catalogo_indicativo_entrevistador";
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getAllFaltaDelito(){
        $sql = "SELECT * FROM catalogo_delictivo";
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getAllFuente(){
        $sql = "SELECT fuente FROM catalogo_fuente_casos";
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getTipoVehiculo(){
        $sql = "SELECT Tipo FROM catalogo_tipos_vehiculos";
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getMarca(){
        $sql = "SELECT Marca FROM catalogo_marca_vehiculos_io";
        $this->db->query($sql);
        return $this->db->registers();
    }
    
    public function getSubmarca(){
        $sql = "SELECT Submarca FROM catalogo_submarcas_vehiculos";
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getRangoEdad(){
        $sql = "SELECT Rango FROM catalogo_rango_edades";
        $this->db->query($sql);
        return $this->db->registers();
    }
    public function getNombresClave(){
        $sql = "SELECT clave FROM catalogo_nombres_clave";
        $this->db->query($sql);
        $resultado = $this->db->registers();
        return $resultado;
    }
    
    public function getArea(){
        $sql = "SELECT Area FROM catalogo_area";
        $this->db->query($sql);
        $resultado = $this->db->registers();
        return $resultado;
    }


}


?>