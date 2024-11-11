<?php

class Seguimientos extends Controller
{
    /*controlador del modulo Seguimientos
    NOMENCLATURA DE MOVIMIENTOS EN EL HISTORIAL 
    23.-INSERCION DE UN NUEVO SEGUIMIENTO
    24.-CONSULTA EN EL MODULO DE SEGUIMIENTO
    25.-VER SEGUIMIENTO
    26.-ACTUALIZACION DE SEGUIMIENTO TAB PRINCIPAL
    27.-ACTUALIZACION DE SEGUIMIENTO TABS SECUNDARIAS
    28.-ELIMINACION DE DATO EN LAS TABS DEL SEGUIMIENTOS
    29.-EXPORTACION DE INFORMACION DEL MODULO DE SEGUIMIENTOS
    */
    public function __construct(){
        $this->Catalogo = $this->model('Catalogo');//para ocupar las funciones del modelo del catalogo
        $this->Seguimiento = $this->model('Seguimiento');//para ocupar las funciones del modelo del seguimiento
        $this->numColumnsSG = [7,8,7,7,6,6];  //se inicializa el número de columns por cada filtro
        $this->FV = new FormValidator();
    }

    public function index(){
        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }
        $data = [
            'titulo'    => 'AURA | Seguimientos',
            'extra_css' => '<link rel="stylesheet" href="' . base_url . 'public/css/system/seguimientos/index.css">',
            'extra_js'  => '<script src="' . base_url . 'public/js/system/seguimientos/index.js"></script>'
        ];
        //PROCESO DE FILTRADO DE SEGUIMIENTO REDES
        if (isset($_GET['filtro']) && is_numeric($_GET['filtro']) && $_GET['filtro'] >= MIN_FILTRO_SG && $_GET['filtro'] <= MAX_FILTRO_SG) { //numero de filtro
            $filtro = $_GET['filtro'];
        } else {
            if($_SESSION['userdataSIC']->Red[0] == 0){
                $filtro = 5;
            }else{
                $filtro = 1;
            }
        }
        //PROCESAMIENTO DE LAS COLUMNAS 
        $this->setColumnsSession($filtro);
        $data['columns_SG'] = $_SESSION['userdataSIC']->columns_SG;

        //PROCESAMIENTO DE RANGO DE FOLIOS
        if (isset($_POST['rango_inicio']) && isset($_POST['rango_fin'])) {
            $_SESSION['userdataSIC']->rango_inicio_sg = $_POST['rango_inicio'];
            $_SESSION['userdataSIC']->rango_fin_sg = $_POST['rango_fin'];
        }
        //PROCESO DE PAGINATION
        if (isset($_GET['numPage'])) { //numero de pagination
            $numPage = $_GET['numPage'];
            if (!(is_numeric($numPage))) //seguridad si se ingresa parámetro inválido
                $numPage = 1;
        } else {
            $numPage = 1;
        }
        //cadena auxiliar por si se trata de una paginacion conforme a una busqueda dada anteriormente
        $cadena = "";
        if (isset($_GET['cadena'])) { //numero de pagination
            $cadena = $_GET['cadena'];
            $data['cadena'] = $cadena;
        }

        $where_sentence = $this->Seguimiento->generateFromWhereSentence($cadena, $filtro,"");
        $extra_cad = ($cadena != "") ? ("&cadena=" . $cadena) : ""; //para links conforme a búsqueda
        $no_of_records_per_page = NUM_MAX_REG_PAGE; //total de registros por pagination
        $offset = ($numPage - 1) * $no_of_records_per_page; // desplazamiento conforme a la pagina
        $results_rows_pages = $this->Seguimiento->getTotalPages($no_of_records_per_page, $where_sentence);   //total de páginas de acuerdo a la info de la DB
        $total_pages = $results_rows_pages['total_pages'];
        if ($numPage > $total_pages) {
            $numPage = 1;
            $offset = ($numPage - 1) * $no_of_records_per_page;
        } //seguridad si ocurre un error por url     

        $rows_Seguimientos = $this->Seguimiento->getDataCurrentPage($offset, $no_of_records_per_page, $where_sentence);    //se obtiene la información de la página actual

        //guardamos la tabulacion de la información para la vista
        $data['infoTable'] = $this->generarInfoTable($rows_Seguimientos, $filtro);
        //guardamos los links en data para la vista
        $data['links'] = $this->generarLinks($numPage, $total_pages, $extra_cad, $filtro);
        //número total de registros encontrados
        $data['total_rows'] = $results_rows_pages['total_rows'];
        //filtro actual para Fetch javascript
        $data['filtroActual'] = $filtro;
        $data['dropdownColumns'] = $this->generateDropdownColumns($filtro);
        switch ($filtro) {
            case '1':
                $data['filtroNombre'] = ($_SESSION['userdataSIC']->Red[0] == 1 && $_SESSION['userdataSIC']->Modo_Admin != 1)? "Todos los Grupos (Alto Impacto)" : "Todos los Grupos";
                break;
            case '2':
                $data['filtroNombre'] = ($_SESSION['userdataSIC']->Red[0] == 1 && $_SESSION['userdataSIC']->Modo_Admin != 1)? "Personas (Alto Impacto)" : "Personas";
                break;
            case '3':
                $data['filtroNombre'] = ($_SESSION['userdataSIC']->Red[0] == 1 && $_SESSION['userdataSIC']->Modo_Admin != 1)? "Vehiculos (Alto Impacto)" : "Vehiculos";
                break;
            case '4':
                $data['filtroNombre'] = ( $_SESSION['userdataSIC']->Modo_Admin == 1)? "Alto Impacto":"";
                break;
            case '5':
                $data['filtroNombre'] =  "Eventos Delictivos Sin Asociar";
                break;
            case '6':
                $data['filtroNombre'] =  "Eventos Delictivos Asociados a un Grupo";
                break;
        }
        $this->view('templates/header', $data);
        $this->view('system/seguimientos/seguimientoView', $data);//muestra la vista principal del modulo de seguimientos
        $this->view('templates/footer', $data);
    }
    /* ----------------------------------------FUNCIONES DE FILTROS ------------------------------------- */
    //función para checar los cambios de filtro y poder asignar los valores correspondientes de las columnas a la session
    public function setColumnsSession($filtroActual = 1){
        //si el filtro existe y esta dentro de los parámetros continua
        if (isset($_SESSION['userdataSIC']->filtro_SG) && $_SESSION['userdataSIC']->filtro_SG >= MIN_FILTRO_SG && $_SESSION['userdataSIC']->filtro_SG <= MAX_FILTRO_SG) {
            //si cambia el filtro se procde a cambiar los valores de las columnas que contiene el filtro seleccionado
            if ($_SESSION['userdataSIC']->filtro_SG != $filtroActual) {
                $_SESSION['userdataSIC']->filtro_SG = $filtroActual;
                unset($_SESSION['userdataSIC']->columns_SG); 
                for ($i = 0; $i < $this->numColumnsSG[$_SESSION['userdataSIC']->filtro_SG - 1]; $i++)
                    $_SESSION['userdataSIC']->columns_SG['column' . ($i + 1)] = 'show';
            }
        } else { //si no existe el filtro entonces se inicializa con el primero por default
            $_SESSION['userdataSIC']->filtro_SG = $filtroActual;
            unset($_SESSION['userdataSIC']->columns_SG);
            for ($i = 0; $i < $this->numColumnsSG[$_SESSION['userdataSIC']->filtro_SG - 1]; $i++)
                $_SESSION['userdataSIC']->columns_SG['column' . ($i + 1)] = 'show';
        }
    }
    //función fetch que actualiza los valores de las columnas para la session
    public function setColumnFetch(){
        if (isset($_POST['columName']) && isset($_POST['valueColumn'])) {
            $_SESSION['userdataSIC']->columns_SG[$_POST['columName']] = $_POST['valueColumn'];
            echo json_encode("ok");
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }
    //función para generar la información de la tabla de forma dinámica
    public function generarInfoTable($rows, $filtro = 1){
        $permisos_Editar = ($_SESSION['userdataSIC']->Red[1] == 1 || $_SESSION['userdataSIC']->Modo_Admin == 1) ? '' : 'mi_hide';//desaparece los botones de acuerdo a los permisos
        $permisos_Ver = ($_SESSION['userdataSIC']->Red[2] == 1 || $_SESSION['userdataSIC']->Modo_Admin == 1) ? '' : 'mi_hide';
        //se genera la tabulacion de la informacion por backend
        $infoTable['header'] = "";
        $infoTable['body'] = "";
        switch ($filtro) {
            case '1': //General de todos los casos
                $infoTable['header'] .= '
                        <th class="column1">Folio Red</th>
                        <th class="column2">Nombre de Grupo Delictivo</th>
                        <th class="column3">Fecha de Creación (AAAA-MM-DD)</th>
                        <th class="column4">Folios AURA</th>
                        <th class="column5">Zonas</th>
                        <th class="column6">Peligrosidad</th>
                        <th class="column7">Elemento Capturante</th>
                    ';
                foreach ($rows as $row) {
                    $Folios = ($row->Folios_infra!=NULL && $row->Folios_infra!='') ? $this->ReconstruyeCad($row->Folios_infra):'';
                    $Zonas = ($row->Zonas!=NULL && $row->Zonas!='') ? $this->ReconstruyeCad($row->Zonas):'';
                    $infoTable['body'] .= '<tr id="tr' . $row->Id_Seguimiento . '">';
                    $infoTable['body'] .= ' <td class="column1">' . $row->Id_Seguimiento . '</td>
                                            <td class="column2">' . mb_strtoupper($row->Nombre_grupo_delictivo) . '</td>
                                            <td class="column3">' . mb_strtoupper($row->FechaHora_Creacion). '</td>
                                            <td class="column4">' . $Folios. '</td>
                                            <td class="column5">' . mb_strtoupper($Zonas). '</td>
                                            <td class="column6">' . mb_strtoupper($row->Peligrosidad)  . '</td>
                                            <td class="column7">' . $row->Elemento_Captura . '</td>
                        ';
                    if ($row->FechaHora_Creacion != '') {
                        if ($_SESSION['userdataSIC']->Modo_Admin == 1 || $_SESSION['userdataSIC']->Red[1] == 1) { //validacion de tabs validados completamente y/o permisos de validacion o modo admin
                            $infoTable['body'] .= '<td>
                                                    <a class="myLinks ' . $permisos_Editar . '" data-toggle="tooltip" data-placement="right" title="Editar registro" href="' . base_url . 'Seguimientos/editarSeguimiento/?Id_seguimiento=' . $row->Id_Seguimiento . '">
                                                        <i class="material-icons">edit</i>
                                                    </a>';
                        } else {
                            $infoTable['body'] .= '<td>';
                        }
                        if ($_SESSION['userdataSIC']->Modo_Admin == 1 || $_SESSION['userdataSIC']->Red[2] == 1) { //validacion de tabs validados completamente y/o permisos de validacion o modo admin
                           
                            $infoTable['body'] .= '
                                                    <a class="myLinks ' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Ver registro" href="' . base_url . 'Seguimientos/verSeguimiento/?Id_seguimiento=' . $row->Id_Seguimiento . '">
                                                        <i class="material-icons">visibility</i>
                                                    </a>
                                                    <a target="_blank" class="myLinks ' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Generar PDF de la Red de Vínculo Completa" href="' . base_url . 'Seguimientos/GeneraPDF/?Id_seguimiento=' .$row->Id_Seguimiento. '">
                                                        <i class="material-icons">assignment</i>
                                                    </a>
                                                    </td>';
                                                /*<a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Generar Ficha Tipo Atlas" href="' . base_url . 'Seguimientos/GenerarFichaAtlas/?Id_seguimiento=' .$row->Id_Seguimiento. '">
                                                    <i class="material-icons">picture_as_pdf</i>
                                                </a>*/

                        }else{
                            $infoTable['body'] .= '</td>';
                        }
                            
                    }
                    $infoTable['body'] .= '</tr>';
                }
                $infoTable['header'] .= '<th >Operaciones</th>';
            break;
            case '2': //Personas
                $infoTable['header'] .= '
                        <th class="column1">Folio Red</th>
                        <th class="column2">Nombre del grupo delictivo</th>
                        <th class="column3">Nombre completo</th>
                        <th class="column4">Curp</th>
                        <th class="column5">Edad</th>
                        <th class="column6">Telefono</th>
                        <th class="column7">Alias</th>
                        <th class="column8">Capturo</th>
                    ';
                foreach ($rows as $row) {
                    $infoTable['body'] .= '<tr id="tr' . $row->Id_Persona . '">';
                    $infoTable['body'] .= ' <td class="column1">' . $row->Id_Seguimiento . '</td>
                                            <td class="column2">' . $row->Nombre_grupo_delictivo . '</td>
                                            <td class="column3">' . $row->Nombre_completo . '</td>
                                            <td class="column4">' . $row->Curp . '</td>
                                            <td class="column5">' . $row->Edad . '</td>
                                            <td class="column6">' . $row->Telefono . '</td>
                                            <td class="column7">' . $row->Alias . '</td>
                                            <td class="column8">' . $row->Capturo . '</td>
                        ';
                    if ($row->FechaHora_Creacion != '') {

                        if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Red[2] == 1) { //validacion de tabs validados completamente y/o permisos de validacion o modo admin
                           
                            $infoTable['body'] .= '<td >
                                                    <a class="myLinks' . $permisos_Editar . '" data-toggle="tooltip" data-placement="right" title="Editar registro" href="' . base_url . 'Seguimientos/editarSeguimiento/?Id_seguimiento=' . $row->Id_Seguimiento . '">
                                                        <i class="material-icons">edit</i>
                                                    </a>
                                                    <a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Generar PDF de persona" href="' . base_url . 'Seguimientos/GeneraPersonaPDF/?Id_Persona=' .$row->Id_Persona. '">
                                                        <i class="material-icons">person</i>
                                                    </a>
                                                    </td>';
                        }
                    }
                    $infoTable['body'] .= '</tr>';
                }
                $infoTable['header'] .= '<th >Exportar Ficha</th>';
            break;
            case '3': //vehiculos 'Folio Red','Nombre del grupo delictivo', 'Placas', 'Informacion del vehiculo', 'Nivs','Nombre del propietario','Capturo'
                $infoTable['header'] .= '
                        <th class="column1">Folio Red</th>
                        <th class="column2">Nombre del grupo delictivo</th>
                        <th class="column3">Placas</th>
                        <th class="column4">Informacion del vehiculo</th>
                        <th class="column5">Nivs</th>
                        <th class="column6">Nombre del propietario</th>
                        <th class="column7">Capturo</th>
                    ';
                foreach ($rows as $row) {
                    $infoTable['body'] .= '<tr id="tr' . $row->Id_Vehiculo . '">';
                    $infoTable['body'] .= ' <td class="column1">' . $row->Id_Seguimiento . '</td>
                                            <td class="column2">' . $row->Nombre_grupo_delictivo . '</td>
                                            <td class="column3">' . $row->Placas . '</td>
                                            <td class="column4">' . $row->InfoVehiculo . '</td>
                                            <td class="column5">' . $row->Nivs . '</td>
                                            <td class="column6">' . $row->Nombre_Propietario . '</td>
                                            <td class="column7">' . $row->Capturo . '</td>
                        ';
                    if ($row->FechaHora_Creacion != '') {

                        if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Red[2] == 1) { //validacion de tabs validados completamente y/o permisos de validacion o modo admin
                            $infoTable['body'] .= '<td >
                                                    <a class="myLinks' . $permisos_Editar . '" data-toggle="tooltip" data-placement="right" title="Editar registro" href="' . base_url . 'Seguimientos/editarSeguimiento/?Id_seguimiento=' . $row->Id_Seguimiento . '">
                                                        <i class="material-icons">edit</i>
                                                    </a>
                                                    <a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Generar PDF de Vehiculos" href="' . base_url . 'Seguimientos/GeneraVehiculoPDF/?Id_Vehiculo=' .$row->Id_Vehiculo. '">
                                                        <i class="material-icons">time_to_leave</i>
                                                    </a>
                                                    </td>';
                        }
                    }
                    $infoTable['body'] .= '</tr>';
                }
                $infoTable['header'] .= '<th >Exportar Ficha</th>';
            break;
            case '4': //General de todos los casos
                $infoTable['header'] .= '
                        <th class="column1">Folio Red</th>
                        <th class="column2">Nombre de Grupo Delictivo</th>
                        <th class="column3">Fecha de Creación (AAAA-MM-DD)</th>
                        <th class="column4">Folios AURA</th>
                        <th class="column5">Zonas</th>
                        <th class="column6">Peligrosidad</th>
                        <th class="column7">Elemento Capturante</th>
                    ';
                foreach ($rows as $row) {
                    $Folios = ($row->Folios_infra!=NULL && $row->Folios_infra!='') ? $this->ReconstruyeCad($row->Folios_infra):'';
                    $Zonas = ($row->Zonas!=NULL && $row->Zonas!='') ? $this->ReconstruyeCad($row->Zonas):'';
                    $infoTable['body'] .= '<tr id="tr' . $row->Id_Seguimiento . '">';
                    $infoTable['body'] .= ' <td class="column1">' . $row->Id_Seguimiento . '</td>
                                            <td class="column2">' . mb_strtoupper($row->Nombre_grupo_delictivo) . '</td>
                                            <td class="column3">' . mb_strtoupper($row->FechaHora_Creacion). '</td>
                                            <td class="column4">' . $Folios. '</td>
                                            <td class="column5">' . mb_strtoupper($Zonas). '</td>
                                            <td class="column6">' . mb_strtoupper($row->Peligrosidad)  . '</td>
                                            <td class="column7">' . $row->Elemento_Captura . '</td>
                        ';
                    if ($row->FechaHora_Creacion != '') {
                        if ($_SESSION['userdataSIC']->Modo_Admin == 1 || $_SESSION['userdataSIC']->Red[1] == 1) { //validacion de tabs validados completamente y/o permisos de validacion o modo admin
                            $infoTable['body'] .= '<td>
                                                    <a class="myLinks ' . $permisos_Editar . '" data-toggle="tooltip" data-placement="right" title="Editar registro" href="' . base_url . 'Seguimientos/editarSeguimiento/?Id_seguimiento=' . $row->Id_Seguimiento . '">
                                                        <i class="material-icons">edit</i>
                                                    </a>';
                        } else {
                            $infoTable['body'] .= '<td>';
                        }
                        if ($_SESSION['userdataSIC']->Modo_Admin == 1 || $_SESSION['userdataSIC']->Red[2] == 1) { //validacion de tabs validados completamente y/o permisos de validacion o modo admin
                           
                            $infoTable['body'] .= '
                                                    <a class="myLinks ' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Ver registro" href="' . base_url . 'Seguimientos/verSeguimiento/?Id_seguimiento=' . $row->Id_Seguimiento . '">
                                                        <i class="material-icons">visibility</i>
                                                    </a>
                                                    <a target="_blank" class="myLinks ' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Generar PDF de la Red de Vínculo Completa" href="' . base_url . 'Seguimientos/GeneraPDF/?Id_seguimiento=' .$row->Id_Seguimiento. '">
                                                        <i class="material-icons">assignment</i>
                                                    </a>
                                                    </td>';
                                                /*<a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Generar Ficha Tipo Atlas" href="' . base_url . 'Seguimientos/GenerarFichaAtlas/?Id_seguimiento=' .$row->Id_Seguimiento. '">
                                                    <i class="material-icons">picture_as_pdf</i>
                                                </a>*/

                        }else{
                            $infoTable['body'] .= '</td>';
                        }
                            
                    }
                    $infoTable['body'] .= '</tr>';
                }
                $infoTable['header'] .= '<th >Operaciones</th>';
            break;
            case '5': //General de todos los casos
                $infoTable['header'] .= '
                        <th class="column1">Folio Red</th>
                        <th class="column2">Nombre de Grupo Delictivo</th>
                        <th class="column3">Fecha de Creación (AAAA-MM-DD)</th>
                        <th class="column4">Folios AURA</th>
                        <th class="column5">Zonas</th>
                        <th class="column6">Elemento Capturante</th>
                    ';
                foreach ($rows as $row) {
                    $Folios = ($row->Folios_infra!=NULL && $row->Folios_infra!='') ? $this->ReconstruyeCad($row->Folios_infra):'';
                    $Zonas = ($row->Zonas!=NULL && $row->Zonas!='') ? $this->ReconstruyeCad($row->Zonas):'';
                    $infoTable['body'] .= '<tr id="tr' . $row->Id_Seguimiento . '">';
                    $infoTable['body'] .= ' <td class="column1">' . $row->Id_Seguimiento . '</td>
                                            <td class="column2">' . mb_strtoupper($row->Nombre_grupo_delictivo) . '</td>
                                            <td class="column3">' . mb_strtoupper($row->FechaHora_Creacion). '</td>
                                            <td class="column4">' . $Folios. '</td>
                                            <td class="column5">' . mb_strtoupper($Zonas). '</td>
                                            <td class="column6">' . $row->Elemento_Captura . '</td>
                        ';
                    if ($row->FechaHora_Creacion != '') {
                        if ($_SESSION['userdataSIC']->Modo_Admin == 1 || $_SESSION['userdataSIC']->Red[1] == 1) { //validacion de tabs validados completamente y/o permisos de validacion o modo admin
                            $infoTable['body'] .= '<td class="d-flex">
                                                    <a class="myLinks ' . $permisos_Editar . '" data-toggle="tooltip" data-placement="right" title="Editar registro" href="' . base_url . 'Seguimientos/editarSeguimiento/?Id_seguimiento=' . $row->Id_Seguimiento . '">
                                                        <i class="material-icons">edit</i>
                                                    </a>';
                        } else {
                            $infoTable['body'] .= '<td> class="d-flex"';
                        }
                        if ($_SESSION['userdataSIC']->Modo_Admin == 1 || $_SESSION['userdataSIC']->Red[2] == 1) { //validacion de tabs validados completamente y/o permisos de validacion o modo admin
                           
                            $infoTable['body'] .= '
                                                    <a class="myLinks ' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Ver registro" href="' . base_url . 'Seguimientos/verSeguimiento/?Id_seguimiento=' . $row->Id_Seguimiento . '">
                                                        <i class="material-icons">visibility</i>
                                                    </a>
                                                    <a target="_blank" class="myLinks ' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Generar PDF de la Red de Vínculo Completa" href="' . base_url . 'Seguimientos/GeneraPDF/?Id_seguimiento=' .$row->Id_Seguimiento. '">
                                                        <i class="material-icons">assignment</i>
                                                    </a>
                                                    ';
                                                /*<a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Generar Ficha Tipo Atlas" href="' . base_url . 'Seguimientos/GenerarFichaAtlas/?Id_seguimiento=' .$row->Id_Seguimiento. '">
                                                    <i class="material-icons">picture_as_pdf</i>
                                                </a>*/
                            if($row->Consultado==1){
                                $infoTable['body'] .= '<div class="fondo2">
                                                        <div class="circulo" data-toggle="tooltip" data-placement="top" title="Evento Consultado"></div>
                                                        </div>
                                                    </td>';
                            }else{
                                $infoTable['body'] .= '<div class="fondo">
                                                            <div class="circulo" data-toggle="tooltip" data-placement="top" title="Evento No Consultado"></div>
                                                        </div>
                                                    </td>';
                            }
    
                        }else{
                            $infoTable['body'] .= '</td>';
                        }
                            
                    }
                    $infoTable['body'] .= '</tr>';
                }
                $infoTable['header'] .= '<th >Operaciones</th>';
            break;
            case '6': //General de todos los casos
                $infoTable['header'] .= '
                        <th class="column1">Folio Red</th>
                        <th class="column2">Nombre de Grupo Delictivo</th>
                        <th class="column3">Fecha de Creación (AAAA-MM-DD)</th>
                        <th class="column4">Folios AURA</th>
                        <th class="column5">Zonas</th>
                        <th class="column6">Elemento Capturante</th>
                    ';
                foreach ($rows as $row) {
                    $Folios = ($row->Folios_infra!=NULL && $row->Folios_infra!='') ? $this->ReconstruyeCad($row->Folios_infra):'';
                    $Zonas = ($row->Zonas!=NULL && $row->Zonas!='') ? $this->ReconstruyeCad($row->Zonas):'';
                    $infoTable['body'] .= '<tr id="tr' . $row->Id_Seguimiento . '">';
                    $infoTable['body'] .= ' <td class="column1">' . $row->Id_Seguimiento . '</td>
                                            <td class="column2">' . mb_strtoupper($row->Nombre_grupo_delictivo) . '</td>
                                            <td class="column3">' . mb_strtoupper($row->FechaHora_Creacion). '</td>
                                            <td class="column4">' . $Folios. '</td>
                                            <td class="column5">' . mb_strtoupper($Zonas). '</td>
                                            <td class="column6">' . $row->Elemento_Captura . '</td>
                        ';
                    if ($row->FechaHora_Creacion != '') {
                        if ($_SESSION['userdataSIC']->Modo_Admin == 1 || $_SESSION['userdataSIC']->Red[1] == 1) { //validacion de tabs validados completamente y/o permisos de validacion o modo admin
                            $infoTable['body'] .= '<td>
                                                    <a class="myLinks ' . $permisos_Editar . '" data-toggle="tooltip" data-placement="right" title="Editar registro" href="' . base_url . 'Seguimientos/editarSeguimiento/?Id_seguimiento=' . $row->Id_Seguimiento . '">
                                                        <i class="material-icons">edit</i>
                                                    </a>';
                        } else {
                            $infoTable['body'] .= '<td>';
                        }
                        if ($_SESSION['userdataSIC']->Modo_Admin == 1 || $_SESSION['userdataSIC']->Red[2] == 1) { //validacion de tabs validados completamente y/o permisos de validacion o modo admin
                           
                            $infoTable['body'] .= '
                                                    <a class="myLinks ' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Ver registro" href="' . base_url . 'Seguimientos/verSeguimiento/?Id_seguimiento=' . $row->Id_Seguimiento . '">
                                                        <i class="material-icons">visibility</i>
                                                    </a>
                                                    <a target="_blank" class="myLinks ' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Generar PDF de la Red de Vínculo Completa" href="' . base_url . 'Seguimientos/GeneraPDF/?Id_seguimiento=' .$row->Id_Seguimiento. '">
                                                        <i class="material-icons">assignment</i>
                                                    </a>
                                                    </td>';
                                                /*<a target="_blank" class="myLinks' . $permisos_Ver . '" data-toggle="tooltip" data-placement="right" title="Generar Ficha Tipo Atlas" href="' . base_url . 'Seguimientos/GenerarFichaAtlas/?Id_seguimiento=' .$row->Id_Seguimiento. '">
                                                    <i class="material-icons">picture_as_pdf</i>
                                                </a>*/
    
                        }else{
                            $infoTable['body'] .= '</td>';
                        }
                            
                    }
                    $infoTable['body'] .= '</tr>';
                }
                $infoTable['header'] .= '<th >Operaciones</th>';
            break;
        }
        return $infoTable;
    }
    public function ReconstruyeCad($Folios){
        $folios = explode(',', $Folios);
        $result = '';
        if(count($folios)>4){
            foreach ($folios as $index => $folio) {
                $result .= $folio;
                // Reemplazar cada segunda coma por un salto de línea
                if (($index+1)  %  5 == 0) {
                    $result .= "<br>"; // Salto de línea
                } else {
                    if(trim($folio)!='' && $index<count($folios)-1){
                        $result .= ', '; // Coma y espacio para los demás

                    }
                }
            }

        }else if(count($folios)>1){
            foreach ($folios as $index => $folio) {
                $result .= $folio;
                if(trim($folio)!='' && $index<count($folios)-1){
                    $result .= ', '; // Coma y espacio para los demás
                }
            }
            
        }else {
            $result = $Folios;

        }
        return strval($result);
    }
    public function generarLinks($numPage, $total_pages, $extra_cad = "", $filtro = 1){
        //$extra_cad sirve para determinar la paginacion conforme a si se realizó una busqueda
        //Creación de links para la paginacion
        $links = "";

        //FLECHA IZQ (PREV PAGINATION)
        if ($numPage > 1) {
            $links .= '<li class="page-item">
                            <a class="page-link" href=" ' . base_url . 'Seguimientos/index/?numPage=1' . $extra_cad . '&filtro=' . $filtro . ' " data-toggle="tooltip" data-placement="top" title="Primera página">
                                <i class="material-icons">first_page</i>
                            </a>
                        </li>';
            $links .= '<li class="page-item">
                            <a class="page-link" href=" ' . base_url . 'Seguimientos/index/?numPage=' . ($numPage - 1) . $extra_cad . '&filtro=' . $filtro . ' " data-toggle="tooltip" data-placement="top" title="Página anterior">
                                <i class="material-icons">navigate_before</i>
                            </a>
                        </li>';
        }

        //DESPLIEGUE DE PAGES NUMBER
        $LINKS_EXTREMOS = GLOBAL_LINKS_EXTREMOS; //numero máximo de links a la izquierda y a la derecha
        for ($ind = ($numPage - $LINKS_EXTREMOS); $ind <= ($numPage + $LINKS_EXTREMOS); $ind++) {
            if (($ind >= 1) && ($ind <= $total_pages)) {

                $activeLink = ($ind == $numPage) ? 'active' : '';

                $links .= '<li class="page-item ' . $activeLink . ' ">
                                <a class="page-link" href=" ' . base_url . 'Seguimientos/index/?numPage=' . ($ind) . $extra_cad . '&filtro=' . $filtro . ' ">
                                    ' . ($ind) . '
                                </a>
                            </li>';
            }
        }

        //FLECHA DERECHA (NEXT PAGINATION)
        if ($numPage < $total_pages) {

            $links .= '<li class="page-item">
                            <a class="page-link" href=" ' . base_url . 'Seguimientos/index/?numPage=' . ($numPage + 1) . $extra_cad . '&filtro=' . $filtro . ' " data-toggle="tooltip" data-placement="top" title="Siguiente página">
                            <i class="material-icons">navigate_next</i>
                            </a>
                        </li>';
            $links .= '<li class="page-item">
                            <a class="page-link" href=" ' . base_url . 'Seguimientos/index/?numPage=' . ($total_pages) . $extra_cad . '&filtro=' . $filtro . ' " data-toggle="tooltip" data-placement="top" title="Última página">
                            <i class="material-icons">last_page</i>
                            </a>
                        </li>';
        }

        return $links;
    }
    //función que filtra las columnas deseadas por el usuario
    public function generateDropdownColumns($filtro = 1){
        $dropDownColumn = '';
        //generación de dropdown dependiendo del filtro
        switch ($filtro) {
            case '1':
                $campos = ['Folio Red', 'Nombre de grupo delictivo', 'Fecha de creación', 'Folios AURA','Zonas','Peligrosidad','Elemento capturante'];
                break;
            case '2':
                $campos = ['Folio Red','Nombre del grupo delictivo', 'Nombre completo', 'Curp','Edad','Telefono','Alias', 'Capturo'];
                break;
            case '3':
                $campos = ['Folio Red','Nombre del grupo delictivo', 'Placas', 'Informacion del vehiculo', 'Nivs','Nombre del propietario','Capturo'];
                break;
            case '4':
                $campos = ['Folio Red', 'Nombre de grupo delictivo', 'Fecha de creación', 'Folios AURA','Zonas','Peligrosidad','Elemento capturante'];
                break;
            case '5':
                $campos = ['Folio Red', 'Nombre de grupo delictivo', 'Fecha de creación', 'Folios AURA','Zonas','Elemento capturante'];
                break;
            case '6':
                $campos = ['Folio Red', 'Nombre de grupo delictivo', 'Fecha de creación', 'Folios AURA','Zonas','Elemento capturante'];
                break;
        }
       

        //gestión de cada columna
        $ind = 1;
        foreach ($campos as $campo) {
            $checked = ($_SESSION['userdataSIC']->columns_SG['column' . $ind] == 'show') ? 'checked' : '';
            $dropDownColumn .= ' <div class="form-check">
                                    <input class="form-check-input checkColumns" type="checkbox" value="' . $_SESSION['userdataSIC']->columns_SG['column' . $ind] . '" onchange="hideShowColumn(this.id);" id="column' . $ind . '" ' . $checked . '>
                                    <label class="form-check-label" for="column' . $ind . '">
                                        ' . $campo . '
                                    </label>
                                </div>';
            $ind++;
        }
        $dropDownColumn .= '     <div class="dropdown-divider">
                                </div>
                                <div class="form-check">
                                    <input id="checkAll" class="form-check-input" type="checkbox" value="hide" onchange="hideShowAll(this.id);" id="column' . $ind . '" checked>
                                    <label class="form-check-label" for="column' . $ind . '">
                                        Todo
                                    </label>
                                </div>';
        return $dropDownColumn;
    }
    //funcion para borrar variable sesión para filtro de rangos de fechas
    public function removeRangosFechasSesion(){
        if (isset($_REQUEST['filtroActual'])) {
            unset($_SESSION['userdataSIC']->rango_inicio_sg);
            unset($_SESSION['userdataSIC']->rango_fin_sg);

            header("Location: " . base_url . "Seguimientos/index/?filtro=" . $_REQUEST['filtroActual']);
            exit();
        } 
    }
    public function buscarPorCadena(){//Funcion para buscar lo que le escribes en el panel de texto buscar

        if (isset($_POST['cadena'])) {//Comprueba si existe una cadena para buscar
            $cadena = trim($_POST['cadena']);
            $filtroActual = trim($_POST['filtroActual']);

            $results = $this->Seguimiento->getEventoDByCadena($cadena, $filtroActual);//Devuelve los datos de la cadena consultada
            if (strlen($cadena) > 0) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $ip = $this->obtenerIp();
                $descripcion = 'CONSULTA SEGUIMIENTO: ' . $cadena .' '.$_SESSION['userdataSIC']->User_Name;
                $this->Seguimiento->historial($user, $ip, 24, $descripcion);//Escribe en el historial el movimiento
            }
            $extra_cad = ($cadena != "") ? ("&cadena=" . $cadena) : ""; //para links conforme a búsqueda

            $dataReturn['infoTable'] = $this->generarInfoTable($results['rows_Rems'], $filtroActual);
            $dataReturn['links'] = $this->generarLinks($results['numPage'], $results['total_pages'], $extra_cad, $filtroActual);
            $dataReturn['export_links'] = $this->generarExportLinks($extra_cad, $filtroActual);
            $dataReturn['total_rows'] = "Total registros: " . $results['total_rows'];
            $dataReturn['dropdownColumns'] = $this->generateDropdownColumns($filtroActual);


            echo json_encode($dataReturn);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
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
    public function generarExportLinks($extra_cad = "", $filtro = 1){//Funcion para exportar la informacion 
        if ($extra_cad != "") {
            $dataReturn['csv'] =  base_url . 'Seguimientos/exportarInfo/?tipo_export=CSV' . $extra_cad . '&filtroActual=' . $filtro;
            $dataReturn['excel'] =  base_url . 'Seguimientos/exportarInfo/?tipo_export=EXCEL' . $extra_cad . '&filtroActual=' . $filtro;
            $dataReturn['pdf'] =  base_url . 'Seguimientos/exportarInfo/?tipo_export=PDF' . $extra_cad . '&filtroActual=' . $filtro;
        } else {
            $dataReturn['csv'] =  base_url . 'Seguimientos/exportarInfo/?tipo_export=CSV' . $extra_cad . '&filtroActual=' . $filtro;
            $dataReturn['excel'] =  base_url . 'Seguimientos/exportarInfo/?tipo_export=EXCEL' . $extra_cad . '&filtroActual=' . $filtro;
            $dataReturn['pdf'] =  base_url . 'Seguimientos/exportarInfo/?tipo_export=PDF' . $extra_cad . '&filtroActual=' . $filtro;
        }
        return $dataReturn;
    }
	public function exportarInfo(){//Funcion para Exportar informacion en excel

		if (!isset($_REQUEST['tipo_export'])) {
            header("Location: " . base_url . "Seguimientos");
            exit();
		}
		$from_where_sentence = "";
        $filtroActual =  $_REQUEST['filtroActual'];

        if (isset($_REQUEST['cadena'])){//Verifica si existe una Cadena para consulta
            $from_where_sentence = $this->Seguimiento->generateFromWhereSentence($_REQUEST['cadena'], $filtroActual,"EXCEL");//excel con consulta
        }else{
            $from_where_sentence = $this->Seguimiento->generateFromWhereSentence("",$filtroActual,"EXCEL");//Excel sin consulta
        }
		$tipo_export = $_REQUEST['tipo_export'];;
		if ($tipo_export == 'EXCEL') {
			//se realiza exportacion de usuarios a EXCEL
			$cat_rows = $this->Seguimiento->getAllInfoSeguimientoByCadena($from_where_sentence);
			switch ($filtroActual) {
				case '1':
                case '4':
                case '5':
                case '6':
                    //Genera nombre de archivo junto con los datos y los encabezasdos 
					$filename = "Vista_General_seguimientos";
					$csv_data="Folio Red, Nombre del grupo delictivo,Modus_operandi,Peligrosidad,Observaciones,Zonas,Folios AURA (Eventos),Vehiculos del seguimiento,Personas del seguimiento,Elemento_Captura ,Fecha de creación\n";
                    foreach ($cat_rows as $row) {
                        $partes = explode(" ", $row->FechaHora_Creacion);

						$csv_data.= mb_strtoupper($row->Id_Seguimiento).",\"".
                                    mb_strtoupper($row->Nombre_grupo_delictivo)."\",\"".
                                    mb_strtoupper($row->Modus_operandi)."\",\"".
                                    mb_strtoupper($row->Peligrosidad)."\",\"".
                                    mb_strtoupper($row->Observaciones)."\",\"".
                                    mb_strtoupper($row->Zonas)."\",\"".
                                    mb_strtoupper($row->Folios_infra)."\",\"".
                                    mb_strtoupper($row->vehiculos_del_seguimiento)."\",\"".
                                    mb_strtoupper($row->personas_del_seguimiento)."\",\"".
                                    mb_strtoupper($row->Elemento_Captura)."\",\"".
									mb_strtoupper($partes[0])."\"\n";
					}
					break;

            }
			//se genera el archivo csv o excel
			$csv_data = utf8_decode($csv_data); //escribir información con formato utf8 por algún acento
			header("Content-Description: File Transfer");
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=".$filename.".csv");
			echo $csv_data;
			//header("Location: ".base_url."UsersAdmin");
            $user = $_SESSION['userdataSIC']->Id_Usuario;
            $ip = $this->obtenerIp();
            $descripcion = 'EXPORTACION DE EXCEL: ' . $filename .' '.$_SESSION['userdataSIC']->User_Name;
            $this->Seguimiento->historial($user, $ip, 29, $descripcion);//GUarda movimiento en historial

		}else {
			header("Location: ".base_url."Estadisticas");
		}
	}
    /* ----------------------------------------FUNCION PARA GENERAR NUEVOS SEGUIMIENTOS------------------------------------- */
    public function nuevoSeguimiento(){
        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }
        //comprobar los permisos para dejar pasar al módulo
        if(($_SESSION['userdataSIC']->Modo_Admin != 1 && $_SESSION['userdataSIC']->Red[3] != 1)){
            header("Location: " . base_url . "Estadisticas");
            exit(); 
        }
        $data = [
            'titulo'     => 'AURA | Nuevo Seguimiento',
            'extra_css'  => '<link rel="stylesheet" href="' . base_url . 'public/css/system/seguimientos/fullview.css">',
            'extra_js'   => '<script src="'.base_url.'public/js/system/seguimientos/nuevoSeguimiento.js"></script>'
        ];
        $this->view('templates/header', $data);
        $this->view('system/seguimientos/nuevoSeguimientoView', $data);
        $this->view('templates/footer', $data);
    }
    /* ----------------------------------------INSERCION DE SEGUIMIENTO------------------------------------- */
    public function insertSeguimientoFetch(){///FUNCION QUE INSERTA LOS DATOS PARA LA GENERACION DE UN NUEVO REGISTRO DE SEGUIMIENTO
        //comprobar los permisos para dejar pasar al módulo
        if(($_SESSION['userdataSIC']->Modo_Admin != 1 &&  $_SESSION['userdataSIC']->Red[3] != 1) ){
                $data_p['status'] = false;
                $data_p['error_message'] = 'Render Index';
                echo json_encode($data_p);
        }
        $success_2 = $this->Seguimiento->insertNuevoSeguimiento($_POST);//Inserta la informacion
        if ($success_2['status']) {
            $user = $_SESSION['userdataSIC']->Id_Usuario;
            $data_p['status'] =  true;
            $ip = $this->obtenerIp();
            $quitar = array("'", "\"");
            $auxsql =str_replace($quitar, "-", $success_2['sqlEjecutados']);
            $descripcion = 'INSERCION DE SEGUIMIENTO: '.$success_2['Id_Seguimiento'].' '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
            $success_3=$this->Seguimiento->historial($user, $ip, 23, $descripcion);//Guarda en el historial el movimiento
            
        } else {
            $data_p['status'] =  false;
            $data_p['error_message'] = $success_2['error_message'];
            $data_p['error_sql'] = $success_2['error_sql'];
        }
        echo json_encode($data_p);
    }
    /*----------------------------------FUNCION PARA EDITAR TODO EL SEGUIMIENTO---------------------------------------------*/
    public function editarSeguimiento(){
        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }
        //comprobar los permisos para dejar pasar al módulo
        if($_SESSION['userdataSIC']->Modo_Admin != 1 && $_SESSION['userdataSIC']->Red[1] != 1){
            header("Location: " . base_url . "Estadisticas");
            exit();
        }else{
            $data=$this->Seguimiento->permisoVisualizacion($_SESSION['userdataSIC']->User_Name);
            $_SESSION['userdataSIC']->Visualizacion = $data->Visualizacion;
        }
        $datos_prim = [
            'estados' => $this->getEstadosMexico()
        ];
        $data = [
            'titulo'     => 'AURA | Edicion de Seguimiento',
            'extra_css'  => '<link rel="stylesheet" href="' . base_url . 'public/css/system/seguimientos/fullview.css">',
            'extra_js'   =>'<script src="https://api.mapbox.com/mapbox-gl-js/v2.8.1/mapbox-gl.js"></script>'.
                            '<link href="https://api.mapbox.com/mapbox-gl-js/v2.8.1/mapbox-gl.css" rel="stylesheet" />'.
                            '<script src="'.base_url.'public/js/system/seguimientos/editarSeguimiento/indexedit.js"></script>'.
                            '<script src="'.base_url.'public/js/system/seguimientos/getInfoSeguimiento/getSeguimiento.js"></script>'.
                            '<script src="'.base_url.'public/js/system/seguimientos/getInfoSeguimiento/getPersonas.js"></script>'.
                            '<script src="'.base_url.'public/js/system/seguimientos/getInfoSeguimiento/getVehiculos.js"></script>'.
                            '<script src="'.base_url.'public/js/system/seguimientos/getInfoSeguimiento/getDomicilio.js"></script>'.
                            '<script src="'.base_url.'public/js/system/seguimientos/getInfoSeguimiento/getAntecedentes.js"></script>'.
                            '<script src="'.base_url.'public/js/system/seguimientos/getInfoSeguimiento/getDatoForencias.js"></script>'.
                            '<script src="'.base_url.'public/js/system/seguimientos/getInfoSeguimiento/getRedesSociales.js"></script>'.
                            '<script src="'.base_url.'public/js/system/seguimientos/editarSeguimiento/antecedentesEdit.js"></script>'.
                            '<script src="'.base_url.'public/js/system/seguimientos/editarSeguimiento/datoforenciaEdit.js"></script>'.
                            '<script src="'.base_url.'public/js/system/seguimientos/editarSeguimiento/personaEdit.js"></script>'.
                            '<script src="'.base_url.'public/js/system/seguimientos/editarSeguimiento/vehiculoEdit.js"></script>'.
                            '<script src="'.base_url.'public/js/system/seguimientos/editarSeguimiento/domicilioEdit.js"></script>'.
                            '<script src="'.base_url.'public/js/system/seguimientos/editarSeguimiento/domicilio_mapbox.js"></script>'.
                            '<script src="'.base_url.'public/js/system/seguimientos/editarSeguimiento/redesSocialesEdit.js"></script>',
                            'datos_prim'            =>  $datos_prim
                        ];
        $this->view('templates/header', $data);
        $this->view('system/seguimientos/SeguimientoFullView', $data);
        $this->view('templates/footer', $data);
    }
    /*Funciones añadidas para estados y municipios */
    public function getEstadosMexico(){
        $data = $this->Catalogo->getSimpleCatalogoOrder("Estado", "catalogo_estados","Estado");
        return $data;
    }
    public function getMunicipios(){
        $data = $this->Catalogo->getMunicipiosEstados($_POST['termino'],$_POST['estado']);
        echo json_encode($data);
    }
    public function existeMunicipio(){
        $data = $this->Catalogo->existeMunicipio($_POST['estado'],$_POST['municipio']);
        echo json_encode($data);
    }

    /* ----------------------------------------ACTUALIZACION DE SEGUIMIENTO------------------------------------- */
    public function UpdateSeguimientoFetch(){//FUNCION PARA ACTUALIZAR EL SEGUIMIENTO
        //comprobar los permisos para dejar pasar al módulo
        if($_SESSION['userdataSIC']->Modo_Admin != 1 &&  $_SESSION['userdataSIC']->Red[1] != 1){
            $data_p['status'] = false;
            $data_p['error_message'] = 'Render Index';
            echo json_encode($data_p);
        }
        if(isset($_POST['id_seguimiento'])){
            $success = $this->Seguimiento->UpdateSeguimientoPrincipales($_POST);
            if ($success['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $data_p['status'] =  true;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $success['sqlEjecutados']);
                $descripcion = 'ACTUALIZACION DE SEGUIMIENTO: '.$_POST['id_seguimiento'].' '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $success_3=$this->Seguimiento->historial($user, $ip, 26, $descripcion);//Guarda en el historial el movimiento
                if($success_3){
                    if($_POST['BanderaFoto']==1){
                        $this->GuardarFotoGD($_POST['id_seguimiento']);//Envia a guardar los archivos
                    }
                    if($_POST['BanderaRecuperacion']==1){
                        $this->RecuperarFotoGD($_POST['id_seguimiento']);//Envia a guardar los archivos
                    }
                    if (isset($_FILES['file_pdf'])) {
                        $path_carpeta_PDF= BASE_PATH . "public/files/Seguimientos/" . $_POST['id_seguimiento']."/" ;
                        $path_file_PDF = BASE_PATH .  "public/files/Seguimientos/" . $_POST['id_seguimiento']."/Seguimiento" . $_POST['id_seguimiento'] . ".pdf";
                        $name = 'file_pdf';
                        $result = $this->uploadPDFFileSeguimientos($name, $_FILES, $path_carpeta_PDF, $path_file_PDF);
                        $data_p['file'] = $result;
                    }  
                }
            } else {
                $data_p['status'] =  false;
                $data_p['error_message'] = $success['error_message'];
                $data_p['error_sql'] = $success['error_sql'];
            }
          
            echo json_encode($data_p);
        }else{
            $data_p['status'] = false;
            $data_p['error_message'] = 'No Existe el Id de seguimiento';
            echo json_encode($data_p);
        }     
    }
    public function uploadPDFFileSeguimientos($name, $file, $carpeta, $ruta){//ACTUALIZA LOS ARCHIVOS FISICOS PDF EN EL SERVIDOR DEL SEGUIMIENTO
        $allowed_mime_type_arr = array('pdf');
        $arrayAux = explode('.', $file[$name]['name']);
        $mime = end($arrayAux);

        if ((isset($file[$name]['name'])) && ($file[$name]['name'] != "")) {
            if (in_array($mime, $allowed_mime_type_arr)) {
                $band = true;
            } else {
                $band = false;
            }
        } else {
            $band = false;
        }

        /* ----- ----- ----- Existe la carpeta ----- ----- ----- */
        if (!file_exists($carpeta))
            mkdir($carpeta, 0777, true);

        if ($band) {
            move_uploaded_file($file[$name]['tmp_name'], $ruta);
        }

        return $band;
    }
    /* ----------------------------------------GUARDAR FOTOS DEL GRUPO DELICTIVO------------------------------------- */
    public function GuardarFotoGD($Id_seguimiento){
        $path_carpeta = BASE_PATH . "public/files/Seguimientos/" . $Id_seguimiento."/" ;
       
        $result = $this->uploadImageFileGD('FotoGrupoDelictivo', $_FILES,$path_carpeta, $_POST['Foto_grupo_delictivo']);//Escritura de fotos en la carpeta

    }
    public function RecuperarFotoGD($Id_seguimiento){
        $path_carpeta = BASE_PATH . "public/files/Seguimientos/" . $Id_seguimiento."/" ;

        $result = $this->uploadImagePhotoGD($_POST['Img_64'],$path_carpeta,$path_carpeta.$_POST['Foto_grupo_delictivo']);//Escritura de fotos en la carpeta

    }
    /* ----- ----- ----- Funciones para guardar la imagenes en el servidor  ----- ----- ----- */
    public function uploadImageFileGD($name, $file, $carpeta, $fileName){
        $type = $file[$name]['type'];
        $extension = explode("/", $type);

        $imageUploadPath = $carpeta . $fileName;
        $allowed_mime_type_arr = array('jpeg', 'png', 'jpg', 'PNG');

        if (!file_exists($carpeta))//si no existe la carpeta se crea
            mkdir($carpeta, 0777, true);

        if (in_array($extension[1], $allowed_mime_type_arr)) {
            $img_temp = $file[$name]['tmp_name'];
            $compressedImg = $this->compressImage($img_temp, $imageUploadPath, 75);
            $band = true;
        } else {
            $band = false;
        }
        return $band;
    }
    public function uploadImagePhotoGD($img, $carpeta, $ruta){

        if (!file_exists($carpeta))//si no existe la carpeta se crea
            mkdir($carpeta, 0777, true);

        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);

        file_put_contents($ruta, $image_base64);

        return true;
    }
    
    public function compressImage($source, $destination, $quality){//Funcion para crear fotos en formato png y jpeg  
        $imgInfo = getimagesize($source);
        $mime = $imgInfo['mime'];
        error_reporting(E_ALL & ~E_WARNING );//ESTO ES PARA NO MUESTRE LOS WARNINGS CUANDO LAS FOTOS SUBIAS CONTENTGAN EL BIT DE INTERLANCIA PRENDIDO Y DEJE FLUIR EL FUNCIONAMIENTO DEL SISTEMA
        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($source);
                break;
            case 'image/png':
                $image = imagecreatefrompng($source);
                break;
            default:
                $image = imagecreatefromjpeg($source);
        }

        imagejpeg($image, $destination, $quality);

        return $imgInfo;
    }
    /* ----------------------------------------ACTUALIZACION DE PERSONAS SEGUIMIENTO------------------------------------- */
    public function UpdatePersonasFetch(){
        //comprobar los permisos para dejar pasar al módulo
        if(($_SESSION['userdataSIC']->Modo_Admin != 1 &&  $_SESSION['userdataSIC']->Red[1] != 1) ){
            $data_p['status'] = false;
            $data_p['error_message'] = 'Render Index';
            echo json_encode($data_p);
        }
        
        if(isset($_POST['Personas_table'])){
            $success = $this->Seguimiento->UpdatePersonasFetch($_POST);
            if ($success['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $data_p['status'] =  true;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $success['sqlEjecutados']);
                $descripcion = 'ACTUALIZACION DE PERSONAS DE SEGUIMIENTO: '.$_POST['id_seguimiento'].' '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $success_3=$this->Seguimiento->historial($user, $ip, 27, $descripcion);//Guarda en el historial el movimiento

                

                if(isset($_POST['Personas_table'])){
                    $personas = json_decode($_POST['Personas_table']);//Saca los datos de los personas
                }
                ini_set('memory_limit', '5120M');
                $Id_Seguimiento = 0;
                if(isset($personas)){
                    foreach ($personas as $persona) {
                        
                        if($Id_Seguimiento!=$persona->row->Id_Seguimiento ){
                            $Id_Seguimiento = $persona->row->Id_Seguimiento;
                            $path_carpeta = BASE_PATH . "public/files/Seguimientos/" . $Id_Seguimiento . "/Personas/";
                            $path_carpeta2 = BASE_PATH . "public/files/Seguimientos/" . $Id_Seguimiento . "/Respaldo/";
    
                            foreach (glob($path_carpeta . "/*") as $archivos_carpeta) {
                                if (is_dir($archivos_carpeta)) {
                                    rmDir_rf($archivos_carpeta);
                                } else {
                                    unlink($archivos_carpeta);
                                }
                            }
                        }
                        if($persona->row->nameImage != 'null'){
                            if ($persona->row->typeImage == 'File') {
                                $type = $_FILES[$persona->row->nameImage]['type'];
                                $extension = explode("/", $type);
                                $hoy = date("Y-m-d H:i:s");
                                $quitar = array(":", "/");
                                $hoy =str_replace($quitar, "-", $hoy);
                                $result = $this->uploadImageFilePersonas($persona->row->nameImage, $_FILES, $Id_Seguimiento, $path_carpeta, $persona->row->nameImage . ".png");//Escritura de fotos en la carpeta
                                $result = $this->uploadImageFilePersonas($persona->row->nameImage, $_FILES, $Id_Seguimiento, $path_carpeta2,$hoy. $persona->row->nameImage .".png");//Escritura de fotos en el respaldo
                            }
                            if ($persona->row->typeImage == 'Photo') {
                                $result = $this->uploadImagePhotoPersonas($persona->row->image, $Id_Seguimiento, $path_carpeta, $path_carpeta . $persona->row->nameImage . ".png");//Escritura de fotos en la carpeta
                            }
                        }
                    }
                }
                
            } else {
                $data_p['status'] =  false;
                $data_p['error_message'] = $success['error_message'];
                $data_p['error_sql'] = $success['error_sql'];
            }
            echo json_encode($data_p);
        }else{
            $data_p['status'] = false;
            $data_p['error_message'] = 'No existen datos de personas';
            echo json_encode($data_p);
        }     
    }
    /* ----- ----- ----- Funciones para guardar la imagenes en el servidor de personas ----- ----- ----- */
    public function uploadImageFilePersonas($name, $file, $alerta, $carpeta, $fileName){
        $type = $file[$name]['type'];
        $extension = explode("/", $type);

        $imageUploadPath = $carpeta . $fileName;
        $allowed_mime_type_arr = array('jpeg', 'png', 'jpg', 'PNG');

        if (!file_exists($carpeta))//si no existe la carpeta se crea
            mkdir($carpeta, 0777, true);

        if (in_array($extension[1], $allowed_mime_type_arr)) {
            $img_temp = $file[$name]['tmp_name'];
            $compressedImg = $this->compressImage($img_temp, $imageUploadPath, 75);
            $band = true;
        } else {
            $band = false;
        }
        return $band;
    }

    public function uploadImagePhotoPersonas($img, $ficha, $carpeta, $ruta){

        if (!file_exists($carpeta))//si no existe la carpeta se crea
            mkdir($carpeta, 0777, true);

        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);

        file_put_contents($ruta, $image_base64);

        return true;
    }
    /* ----------------------------------------ACTUALIZACION DE VEHICULOS SEGUIMIENTO------------------------------------- */
    public function UpdateVehiculosFetch(){
        //comprobar los permisos para dejar pasar al módulo
        if(($_SESSION['userdataSIC']->Modo_Admin != 1 &&  $_SESSION['userdataSIC']->Red[1] != 1) ){
            $data_p['status'] = false;
            $data_p['error_message'] = 'Render Index';
            echo json_encode($data_p);
        }
        ini_set('memory_limit', '5120M');
        if(isset($_POST['Vehiculos_table'])){
            $success = $this->Seguimiento->UpdateVehiculosFetch($_POST);
            if ($success['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $data_p['status'] =  true;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $success['sqlEjecutados']);
                $descripcion = 'ACTUALIZACION DE VEHICULOS DE SEGUIMIENTO: '.$_POST['id_seguimiento'].' '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $this->Seguimiento->historial($user, $ip, 27, $descripcion);//Guarda en el historial el movimiento
                $this->GuardarFotosVehiculos($success['id_seguimiento']);
                 
            } else {
                $data_p['status'] =  false;
                $data_p['error_message'] = $success['error_message'];
                $data_p['error_sql'] = $success['error_sql'];
            }
            echo json_encode($data_p);
        }else{
            $data_p['status'] = false;
            $data_p['error_message'] = 'No existen datos de vehiculos';
            echo json_encode($data_p);
        }     
    }
    public function GuardarFotosVehiculos($Id_Seguimiento_Padre){
        if(isset($_POST['Vehiculos_table'])){
            $Vehiculos = json_decode($_POST['Vehiculos_table']);//Saca los datos de los Vehiculos
        }
        ini_set('memory_limit', '5120M');
        $Id_Seguimiento = 0;
        if(isset($Vehiculos)){
            foreach ($Vehiculos as $vehiculo) {
                if($Id_Seguimiento!=$vehiculo->row->Id_Seguimiento){
                    $Id_Seguimiento = $vehiculo->row->Id_Seguimiento;
                    $path_carpeta = BASE_PATH . "public/files/Seguimientos/" . $vehiculo->row->Id_Seguimiento. "/Vehiculos/";
                    $path_carpeta2 = BASE_PATH . "public/files/Seguimientos/" . $vehiculo->row->Id_Seguimiento. "/Respaldo/";
                   
                    foreach (glob($path_carpeta . "/*") as $archivos_carpeta) {
                        if (is_dir($archivos_carpeta)) {
                            rmDir_rf($archivos_carpeta);
                        } else {
                            unlink($archivos_carpeta);
                        }
                    }
                }
                if($vehiculo->row->nameImage != 'null'){
                    if ($vehiculo->row->typeImage == 'File') {
                        $type = $_FILES[$vehiculo->row->nameImage]['type'];
                        $extension = explode("/", $type);
                        $hoy = date("Y-m-d H:i:s");
                        $quitar = array(":", "/");
                        $hoy =str_replace($quitar, "-", $hoy);
                        $result = $this->uploadImageFileVehiculos($vehiculo->row->nameImage, $_FILES, $Id_Seguimiento, $path_carpeta, $vehiculo->row->nameImage . ".png");//Escritura de fotos en la carpeta
                        $result = $this->uploadImageFileVehiculos($vehiculo->row->nameImage, $_FILES, $Id_Seguimiento, $path_carpeta2,$hoy. $vehiculo->row->nameImage .".png");//Escritura de fotos en el respaldo
                    }
                    if ($vehiculo->row->typeImage == 'Photo') {
                        $result = $this->uploadImagePhotoVehiculos($vehiculo->row->image, $Id_Seguimiento, $path_carpeta, $path_carpeta . $vehiculo->row->nameImage . ".png");//Escritura de fotos en la carpeta
                    }
                }
            }
        }
    }
    /* ----------------------------------------ACTUALIZACION DE DOMICILIOS SEGUIMIENTO------------------------------------- */
    public function UpdateDomiciliosFetch(){
        //comprobar los permisos para dejar pasar al módulo
        if(($_SESSION['userdataSIC']->Modo_Admin != 1 &&  $_SESSION['userdataSIC']->Red[1] != 1) ){
            $data_p['status'] = false;
            $data_p['error_message'] = 'Render Index';
            echo json_encode($data_p);
        }
        ini_set('memory_limit', '3072M');
        if(isset($_POST['Domicilios_table'])){
            $success = $this->Seguimiento->UpdateDomiciliosFetch($_POST);
            if ($success['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $data_p['status'] =  true;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $success['sqlEjecutados']);
                $descripcion = 'ACTUALIZACION DE DOMICILIOS DE SEGUIMIENTO: '.$_POST['id_seguimiento'].' '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $success_3=$this->Seguimiento->historial($user, $ip, 27, $descripcion);//Guarda en el historial el movimiento
            } else {
                $data_p['status'] =  false;
                $data_p['error_message'] = $success['error_message'];
                $data_p['error_sql'] = $success['error_sql'];
            }
            echo json_encode($data_p);
        }else{
            $data_p['status'] = false;
            $data_p['error_message'] = 'No existen datos de domicilios';
            echo json_encode($data_p);
        }     
    }
    /* ----------------------------------------ACTUALIZACION DE DOMICILIOS SEGUIMIENTO------------------------------------- */
    public function UpdateAntecendentesFetch(){
        //comprobar los permisos para dejar pasar al módulo
        if(($_SESSION['userdataSIC']->Modo_Admin != 1 &&  $_SESSION['userdataSIC']->Red[1] != 1) ){
            $data_p['status'] = false;
            $data_p['error_message'] = 'Render Index';
            echo json_encode($data_p);
        }       
        ini_set('memory_limit', '10240M');
        if(isset($_POST['AntecendentesTable'])){
            $success = $this->Seguimiento->UpdateAntecendentesFetch($_POST);
            if ($success['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $data_p['status'] =  true;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $success['sqlEjecutados']);
                $descripcion = 'ACTUALIZACION DE ANTECEDENTES DE SEGUIMIENTO: '.$_POST['id_seguimiento'].' '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $success_3=$this->Seguimiento->historial($user, $ip, 27, $descripcion);//Guarda en el historial el movimiento
            } else {
                $data_p['status'] =  false;
                $data_p['error_message'] = $success['error_message'];
                $data_p['error_sql'] = $success['error_sql'];
                //echo('ubo un error en el controlador');
            }
            echo json_encode($data_p);
        }else{
            $data_p['status'] = false;
            $data_p['error_message'] = 'No existen datos de antecedentes';
            echo json_encode($data_p);
        }     
    }
    /* ----------------------------------------ACTUALIZACION DE FORENSIAS SEGUIMIENTO------------------------------------- */
    public function UpdateForenciasFetch(){
        //comprobar los permisos para dejar pasar al módulo
        if(($_SESSION['userdataSIC']->Modo_Admin != 1 &&  $_SESSION['userdataSIC']->Red[1] != 1) ){
            $data_p['status'] = false;
            $data_p['error_message'] = 'Render Index';
            echo json_encode($data_p);
        }
        ini_set('memory_limit', '5120M');
        if(isset($_POST['Forenciastable'])){
            $success = $this->Seguimiento->UpdateForenciasFetch($_POST);
            if ($success['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $data_p['status'] =  true;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $success['sqlEjecutados']);
                $descripcion = 'ACTUALIZACION DE FORENSIAS DE SEGUIMIENTO: '.$_POST['id_seguimiento'].' '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $this->Seguimiento->historial($user, $ip, 27, $descripcion);//Guarda en el historial el movimiento
                $this->GuardarFotosForencias($_POST['id_seguimiento']);
                
            } else {
                $data_p['status'] =  false;
                $data_p['error_message'] = $success['error_message'];
                $data_p['error_sql'] = $success['error_sql'];
            }
            echo json_encode($data_p);
        }else{
            $data_p['status'] = false;
            $data_p['error_message'] = 'No existen datos de forencias';
            echo json_encode($data_p);
        }     
    }
    public function GuardarFotosForencias($Id_Seguimiento){
       if(isset($_POST['Forenciastable'])){
            $Forencias = json_decode($_POST['Forenciastable']);//Saca los datos de los Forencias
        }
        ini_set('memory_limit', '5120M');
        $Id_Seguimiento = 0;
      
        if(isset($Forencias)){
            foreach ($Forencias as $forencia) {
                if($Id_Seguimiento != $forencia->row->Id_Seguimiento){
                    $Id_Seguimiento = $forencia->row->Id_Seguimiento;
                    $path_carpeta = BASE_PATH . "public/files/Seguimientos/" . $Id_Seguimiento . "/Forencias/";
                    $path_carpeta2 = BASE_PATH . "public/files/Seguimientos/" . $Id_Seguimiento . "/Respaldo/";
                    foreach (glob($path_carpeta . "/*") as $archivos_carpeta) {
                        if (is_dir($archivos_carpeta)) {
                            rmDir_rf($archivos_carpeta);
                        } else {
                            unlink($archivos_carpeta);
                        }
                    }

                }

                if($forencia->row->nameImage != 'null'){
                    if ($forencia->row->typeImage == 'File') {
                        $type = $_FILES[$forencia->row->nameImage]['type'];
                        $extension = explode("/", $type);
                        $hoy = date("Y-m-d H:i:s");
                        $quitar = array(":", "/");
                        $hoy =str_replace($quitar, "-", $hoy);
                        $result = $this->uploadImageFileForencias($forencia->row->nameImage, $_FILES, $Id_Seguimiento, $path_carpeta, $forencia->row->nameImage . ".png");//Escritura de fotos en la carpeta
                        $result = $this->uploadImageFileForencias($forencia->row->nameImage, $_FILES, $Id_Seguimiento, $path_carpeta2,$hoy. $forencia->row->nameImage .".png");//Escritura de fotos en el respaldo
                    }
                    if ($forencia->row->typeImage == 'Photo') {
                        $result = $this->uploadImagePhotoForencias($forencia->row->image, $Id_Seguimiento, $path_carpeta, $path_carpeta . $forencia->row->nameImage . ".png");//Escritura de fotos en la carpeta
                    }
                }
            }
        }
    }
    /* ----------------------------------------ACTUALIZACION DE REDES SOCIALES SEGUIMIENTO------------------------------------- */
    public function UpdateRedesSocialesFetch(){
        //comprobar los permisos para dejar pasar al módulo
        if(($_SESSION['userdataSIC']->Modo_Admin != 1 &&  $_SESSION['userdataSIC']->Red[1] != 1) ){
            $data_p['status'] = false;
            $data_p['error_message'] = 'Render Index';
            echo json_encode($data_p);
        }
        ini_set('memory_limit', '5120M');
        if(isset($_POST['RedesSociales_table'])){
            $success = $this->Seguimiento->UpdateRedesSocialesFetch($_POST);
            if ($success['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $data_p['status'] =  true;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $success['sqlEjecutados']);
                $descripcion = 'ACTUALIZACION DE REDES SOCIALES DE SEGUIMIENTO: '.$_POST['id_seguimiento'].' '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $this->Seguimiento->historial($user, $ip, 27, $descripcion);//Guarda en el historial el movimiento
                $this->GuardarFotosRedesSociales($_POST['id_seguimiento']);
                
            } else {
                $data_p['status'] =  false;
                $data_p['error_message'] = $success['error_message'];
                $data_p['error_sql'] = $success['error_sql'];
            }
            echo json_encode($data_p);
        }else{
            $data_p['status'] = false;
            $data_p['error_message'] = 'No existen datos de forencias';
            echo json_encode($data_p);
        }     
    }
    public function GuardarFotosRedesSociales($Id_Seguimiento){
        if(isset($_POST['RedesSociales_table'])){
            $RedesSociales = json_decode($_POST['RedesSociales_table']);//Saca los datos de los RedesSociales
        }
        ini_set('memory_limit', '5120M');
        $Id_Seguimiento = 0;
        if(isset($RedesSociales)){
            foreach ($RedesSociales as $redSocial) {
                if($Id_Seguimiento!=$redSocial->row->Id_Seguimiento){
                    $Id_Seguimiento = $redSocial->row->Id_Seguimiento;
                    $path_carpeta = BASE_PATH . "public/files/Seguimientos/" . $redSocial->row->Id_Seguimiento. "/Redes_Sociales/";
                    $path_carpeta2 = BASE_PATH . "public/files/Seguimientos/" . $redSocial->row->Id_Seguimiento. "/Respaldo/";
                   
                    foreach (glob($path_carpeta . "/*") as $archivos_carpeta) {
                        if (is_dir($archivos_carpeta)) {
                            rmDir_rf($archivos_carpeta);
                        } else {
                            unlink($archivos_carpeta);
                        }
                    }
                }
                if($redSocial->row->nameImage != 'null'){
                    if ($redSocial->row->typeImage == 'File') {
                        $type = $_FILES[$redSocial->row->nameImage]['type'];
                        $extension = explode("/", $type);
                        $hoy = date("Y-m-d H:i:s");
                        $quitar = array(":", "/");
                        $hoy =str_replace($quitar, "-", $hoy);
                        $result = $this->uploadImageFileredSocial($redSocial->row->nameImage, $_FILES, $Id_Seguimiento, $path_carpeta, $redSocial->row->nameImage . ".png");//Escritura de fotos en la carpeta
                        $result = $this->uploadImageFileredSocial($redSocial->row->nameImage, $_FILES, $Id_Seguimiento, $path_carpeta2,$hoy. $redSocial->row->nameImage .".png");//Escritura de fotos en el respaldo
                    }
                    if ($redSocial->row->typeImage == 'Photo') {
                        $result = $this->uploadImagePhotoredSocial($redSocial->row->image, $Id_Seguimiento, $path_carpeta, $path_carpeta . $redSocial->row->nameImage . ".png");//Escritura de fotos en la carpeta
                    }
                }
            }
        }
    }
    /* ----- ----- ----- Funciones para guardar la imagenes en el servidor de las redes sociales ----- ----- ----- */
    public function uploadImageFileredSocial($name, $file, $alerta, $carpeta, $fileName){
        $type = $file[$name]['type'];
        $extension = explode("/", $type);
        $imageUploadPath = $carpeta . $fileName;
        $allowed_mime_type_arr = array('jpeg', 'png', 'jpg', 'PNG');

        if (!file_exists($carpeta))//si no existe la carpeta se crea
            mkdir($carpeta, 0777, true);

        if (in_array($extension[1], $allowed_mime_type_arr)) {
            $img_temp = $file[$name]['tmp_name'];
            $compressedImg = $this->compressImage($img_temp, $imageUploadPath, 75);
            $band = true;
        } else {
            $band = false;
        }
        return $band;
    }

    public function uploadImagePhotoredSocial($img, $ficha, $carpeta, $ruta){
        if (!file_exists($carpeta))//si no existe la carpeta se crea
            mkdir($carpeta, 0777, true);

        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        file_put_contents($ruta, $image_base64);
        return true;
    }
    /* ----- ----- ----- Funciones para guardar la imagenes en el servidor de las forencias asociadas  ----- ----- ----- */
    public function uploadImageFileForencias($name, $file, $alerta, $carpeta, $fileName){
        $type = $file[$name]['type'];
        $extension = explode("/", $type);

        $imageUploadPath = $carpeta . $fileName;
        $allowed_mime_type_arr = array('jpeg', 'png', 'jpg', 'PNG');

        if (!file_exists($carpeta))//si no existe la carpeta se crea
            mkdir($carpeta, 0777, true);

        if (in_array($extension[1], $allowed_mime_type_arr)) {
            $img_temp = $file[$name]['tmp_name'];
            $compressedImg = $this->compressImage($img_temp, $imageUploadPath, 75);
            $band = true;
        } else {
            $band = false;
        }

        return $band;
    }

    public function uploadImagePhotoForencias($img, $ficha, $carpeta, $ruta){

        if (!file_exists($carpeta))//si no existe la carpeta se crea
            mkdir($carpeta, 0777, true);

        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);

        file_put_contents($ruta, $image_base64);

        return true;
    }
    
    /* ----- ----- ----- Funciones para guardar la imagenes en el servidor de los vehiculos ----- ----- ----- */
    public function uploadImageFileVehiculos($name, $file, $alerta, $carpeta, $fileName){
        $type = $file[$name]['type'];
        $extension = explode("/", $type);

        $imageUploadPath = $carpeta . $fileName;
        $allowed_mime_type_arr = array('jpeg', 'png', 'jpg', 'PNG');

        if (!file_exists($carpeta))//si no existe la carpeta se crea
            mkdir($carpeta, 0777, true);

        if (in_array($extension[1], $allowed_mime_type_arr)) {
            $img_temp = $file[$name]['tmp_name'];
            $compressedImg = $this->compressImage($img_temp, $imageUploadPath, 75);
            $band = true;
        } else {
            $band = false;
        }

        return $band;
    }

    public function uploadImagePhotoVehiculos($img, $ficha, $carpeta, $ruta){

        if (!file_exists($carpeta))//si no existe la carpeta se crea
            mkdir($carpeta, 0777, true);

        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);

        file_put_contents($ruta, $image_base64);

        return true;
    }
    public function verSeguimiento(){//FUNCION PARA VISUALIZAR LOS DATOS DEL SEGUIMIENTO
        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }
        //comprobar los permisos para dejar pasar al módulo
        if(!isset($_SESSION['userdataSIC']) || ($_SESSION['userdataSIC']->Modo_Admin != 1 && $_SESSION['userdataSIC']->Red[2] != 1)){
            header("Location: " . base_url . "Estadisticas");
            exit();
        }else{
            $data=$this->Seguimiento->permisoVisualizacion($_SESSION['userdataSIC']->User_Name);
            $_SESSION['userdataSIC']->Visualizacion = $data->Visualizacion;
        }
        if($_SESSION['userdataSIC']->Visualizacion == 1){
            $data = [
                'titulo'     => 'AURA | Ver Seguimiento',
                'extra_css'  => '<link rel="stylesheet" href="' . base_url . 'public/css/system/seguimientos/fullview.css">',
                'extra_js'   =>'<script src="'.base_url.'public/js/system/seguimientos/getInfoSeguimientoReadOnly/getInfoSeguimientoReadOnly.js"></script>'.
                                '<script src="'.base_url.'public/js/system/seguimientos/getInfoSeguimientoReadOnly/getInfoPersonasReadOnly.js"></script>'.
                                '<script src="'.base_url.'public/js/system/seguimientos/getInfoSeguimientoReadOnly/getInfoVehiculosReadOnly.js"></script>'                
            ];
        }else{
            $data = [
                'titulo'     => 'AURA | Ver Seguimiento',
                'extra_css'  => '<link rel="stylesheet" href="' . base_url . 'public/css/system/seguimientos/fullview.css">',
                'extra_js'   =>'<script src="'.base_url.'public/js/system/seguimientos/getInfoSeguimientoReadOnly/getInfoSeguimientoReadOnly.js"></script>'.
                                '<script src="'.base_url.'public/js/system/seguimientos/getInfoSeguimientoReadOnly/getInfoPersonasReadOnly2.js"></script>'.
                                '<script src="'.base_url.'public/js/system/seguimientos/getInfoSeguimientoReadOnly/getInfoVehiculosReadOnly.js"></script>'                
            ];
        }
        $this->view('templates/header', $data);
        $this->view('system/seguimientos/SeguimientoFullViewReadOnly', $data);
        $this->view('templates/footer', $data);
    }

    /* ----------------------------------------FUNCIONES DE PRECARGA Y OBTENCION DE INFORMACION DE CATALOGOS -------------------------------------*/
    /*PARA FUNCION DE AUTOCOMPLETE DE MARCAS DE VEHICULOS */
    public function getMarcas(){
        $data = $this->Catalogo->getMarca();
        echo json_encode($data);
    }
    /*PARA FUNCION DE AUTOCOMPLETE DE SUBMARCAS DE VEHICULOS */
    public function getSubmarcas(){
        $data = $this->Catalogo->getSubmarca();
        echo json_encode($data);
    }
    public function getDelitos(){//FUNCION QUE OBTIENE EL CATALOGO DE DELITOS 
        $data = $this->Catalogo->getAllFaltaDelito();
        echo json_encode($data);
    }
    public function getEventos(){//FUNCION QUE OBTINE LOS EVENTOS DISPONIBLES PARA SEGUIMIENTO
        $data = $this->Seguimiento->getEventos();
        echo json_encode($data);
    }
    public function getRemisiones(){//FUNCION QUE OBTINE EL CATALOGO DE LAS REMISIONES
        $data = $this->Seguimiento->getRemisiones();
        echo json_encode($data);
    }
    public function getVehiculosSarai(){//FUNCION QUE OBTINE EL CATALOGO DE VEHICULOS INGRESADOS EN SARAI 
        $data = $this->Seguimiento->getVehiculosSarai();
        echo json_encode($data);
    }
    public function getVehiculosSic(){//FUNCION QUE OBTINE EL CATALOGO DE VEHICULOS INGRESADOS EN EL SIC
        $data = $this->Seguimiento->getVehiculosSic();
        echo json_encode($data);
    }
    public function getInfoVehiculoSarai(){//FUNCION QUE OBTIENE LA INFORMACION DEL VEHICULO CONSULTADO EN SARAI
        if (isset($_POST['ID'])) {
            $ID = $_POST['ID'];
            $data = $this->Seguimiento->getInfoVehiculoSarai($ID);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }
    public function getInfoVehiculoSic(){//FUNCION QUE OBTIENE LA INFORMACION DEL VEHICULO CONSULTADO EN SIC
        if (isset($_POST['ID'])) {
            $ID = $_POST['ID'];
            $data = $this->Seguimiento->getInfoVehiculoSic($ID);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }
    
    public function getInfoRedes(){//FUNCION QUE OBTIENE LA INFORMACION DEL EVENTO ESPECIFICO CONSULTADO
        $data = $this->Seguimiento->getInfoRedes();
        echo json_encode($data);
    }
    public function getInfoEvento(){//FUNCION QUE OBTIENE LA INFORMACION DEL EVENTO ESPECIFICO CONSULTADO
        if (isset($_POST['Folio_infra'])) {
            $Folio_infra = $_POST['Folio_infra'];
            $data = $this->Seguimiento->getInfoEvento($Folio_infra);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }
    public function getInfoRemision(){//FUNCION QUE OBTIENE LA INFORMACION DE LA REMISION CONSULTADO EN SARAI
        if (isset($_POST['No_Remision'])) {
            $No_Remision = $_POST['No_Remision'];
            $data = $this->Seguimiento->getInfoRemision($No_Remision);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }
    public function getPersonas(){//FUNCION QUE OBTIENE LA INFORMACION DE LAS PERSONAS DEL SEGUIMIENTO
        if (isset($_POST['Id_Seguimiento'])) {
            $Id_seguimiento = $_POST['Id_Seguimiento'];
            $data = $this->Seguimiento->getPersonas($Id_seguimiento);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }
    public function getVehiculos(){//FUNCION QUE OBTIENE LA INFORMACION DE LOS VEHICULOS DEL SEGUIMIENTO
        if (isset($_POST['Id_Seguimiento'])) {
            $Id_seguimiento = $_POST['Id_Seguimiento'];
            $data = $this->Seguimiento->getVehiculos($Id_seguimiento);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }
    public function getDomicilios(){//FUNCION QUE OBTIENE LA INFORMACION DE LOS DOMICILIOS DEL SEGUIMIENTO
        if (isset($_POST['Ids_Datos']) && isset($_POST['Tipo_Entidad'])) {
            $Ids_Datos = $_POST['Ids_Datos'];
            $Tipo_Entidad=$_POST['Tipo_Entidad'];
            $data = $this->Seguimiento->getDomicilios($Ids_Datos,$Tipo_Entidad);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }
    public function getDomiciliosOneRegister(){//FUNCION QUE OBTIENE LA INFORMACION DE LOS DOMICILIOS DE LA PERSONA O VEHICULO DEL SEGUIMIENTO
        if (isset($_POST['Id_Dato']) && isset($_POST['Tipo_Entidad'])) {
            $Id_Dato = $_POST['Id_Dato'];
            $Tipo_Entidad=$_POST['Tipo_Entidad'];
            $data = $this->Seguimiento->getDomiciliosOneRegister($Id_Dato,$Tipo_Entidad);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }
    public function getAntecedentes(){//FUNCION QUE OBTIENE LA INFORMACION DE LOS ANTECEDENTES DE LAS PERSONAS O VEHICULOS DEL SEGUIMIENTO
        if (isset($_POST['Ids_Datos']) && isset($_POST['Tipo_Entidad'])) {
            $Ids_Datos = $_POST['Ids_Datos'];
            $Tipo_Entidad=$_POST['Tipo_Entidad'];
            $data = $this->Seguimiento->getAntecedentes($Ids_Datos,$Tipo_Entidad);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }
    public function getAntecedentesOneRegister(){//FUNCION QUE OBTIENE LA INFORMACION DE LOS ANTECEDENTES DE LAS PERSONA DEL SEGUIMIENTO
        if (isset($_POST['Id_Dato']) && isset($_POST['Tipo_Entidad'])) {
            $Id_Dato = $_POST['Id_Dato'];
            $Tipo_Entidad=$_POST['Tipo_Entidad'];
            $data = $this->Seguimiento->getAntecedentesOneRegister($Id_Dato,$Tipo_Entidad);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }
    public function getForencias(){//FUNCION QUE OBTIENE LA INFORMACION DE LAS FORENCIAS DE LAS PERSONAS DEL SEGUIMIENTO
        if (isset($_POST['Ids_Persona']) ) {
            $Ids_Persona = $_POST['Ids_Persona'];
            $data = $this->Seguimiento->getForencias($Ids_Persona);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }
    public function getRedesSociales(){//FUNCION QUE OBTIENE LA INFORMACION DE LAS REDES SOCIALES DE LAS PERSONAS DEL SEGUIMIENTO
        if (isset($_POST['Ids_Persona']) ) {
            $Ids_Persona = $_POST['Ids_Persona'];
            $data = $this->Seguimiento->getRedesSociales($Ids_Persona);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }
    public function getForenciasOneRegister(){//FUNCION QUE OBTIENE LA INFORMACION DE LAS FORENCIAS DE LA PERSONA DEL SEGUIMIENTO
        if (isset($_POST['Id_Persona']) ) {
            $Id_Persona = $_POST['Id_Persona'];
            $data = $this->Seguimiento->getForenciasOneRegister($Id_Persona);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }
    public function getRedesSocialesOneRegister(){//FUNCION QUE OBTIENE LA INFORMACION DE LAS PERSONAS DEL SEGUIMIENTO
        if (isset($_POST['Id_Persona']) ) {
            $Id_Persona = $_POST['Id_Persona'];
            $data = $this->Seguimiento->getRedesSocialesOneRegister($Id_Persona);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }
    public function getPrincipales(){//FUNCION QUE OBTIENE LOS DATOS PRINCIPALES DEL SEGUIMIENTO
        if (isset($_POST['Id_seguimiento'])) {
            $Id_seguimiento = $_POST['Id_seguimiento'];
            $data = $this->Seguimiento->getPrincipales($Id_seguimiento);
            $ip = $this->obtenerIp();
            $user = $_SESSION['userdataSIC']->Id_Usuario;
            $descripcion = 'VER SEGUIMIENTO: '.$_SESSION['userdataSIC']->User_Name.' '.$Id_seguimiento;
            $success_3=$this->Seguimiento->historial($user, $ip, 25, $descripcion);//GUARDA EL MOVIMIENTO EN EL HISTORIAL
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }
    public function getEventosRelacionados(){//FUNCION QUE OBTIENE LOS DATOS DEL EVENTO RELACIONADOS AL SEGUIMIENTO
        if (isset($_POST['Id_seguimiento'])) {
            $Id_seguimiento = $_POST['Id_seguimiento'];
            $data = $this->Seguimiento->getEventosRelacionados($Id_seguimiento);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }
    public function getDelitosRelacionados(){//FUNCION QUE OBTIENE LOS DATOS DEL EVENTO RELACIONADOS AL SEGUIMIENTO
        if (isset($_POST['Id_seguimiento'])) {
            $Id_seguimiento = $_POST['Id_seguimiento'];
            $data = $this->Seguimiento->getDelitosRelacionados($Id_seguimiento);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }
    public function getHijosRed(){//FUNCION QUE OBTIENE LOS DATOS DEL EVENTO RELACIONADOS AL SEGUIMIENTO
        if (isset($_POST['Id_seguimiento'])) {
            $Id_seguimiento = $_POST['Id_seguimiento'];
            $data = $this->Seguimiento->getHijosRed($Id_seguimiento);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }
    public function DesAsociaPersona(){//FUNCION PARA ELIMINAR UNA PERSONA DE LA TABLA CON ID EN BASE DE DATOS
        if (isset($_POST['Id_Persona'])) {
            $Id_Persona = $_POST['Id_Persona'];
            $data = $this->Seguimiento->DesAsociaPersona($Id_Persona);
            if ($data['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $data['sqlEjecutados']);
                $descripcion = 'ELIMINO LA PERSONA: '.$Id_Persona.' DEL SEGUIMIENTO '.$_POST['Id_Seguimiento'].' EL USUARIO '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $success_3=$this->Seguimiento->historial($user, $ip, 28, $descripcion);//Guarda en el historial el movimiento
            } 

            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }

    public function DesasociaVehiculo(){//FUNCION PARA ELIMINAR UN VEHICULO DE LA TABLA CON ID EN BASE DE DATOS
        if (isset($_POST['Id_Vehiculo'])) {
            $Id_Vehiculo = $_POST['Id_Vehiculo'];
            $data = $this->Seguimiento->DesasociaVehiculo($Id_Vehiculo);
            if ($data['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $data['sqlEjecutados']);
                $descripcion = 'ELIMINO EL VEHICULO: '.$Id_Vehiculo.' DEL SEGUIMIENTO '.$_POST['Id_Seguimiento'].' EL USUARIO '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $success_3=$this->Seguimiento->historial($user, $ip, 28, $descripcion);//Guarda en el historial el movimiento
            } 

            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }
    public function DesasociaDomicilio(){//FUNCION PARA ELIMINAR UN DOMICILIO DE LA TABLA CON ID EN BASE DE DATOS
        if (isset($_POST['Id_Domicilio'])) {
            $Id_Domicilio = $_POST['Id_Domicilio'];
            $data = $this->Seguimiento->DesasociaDomicilio($Id_Domicilio);
            if ($data['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $data['sqlEjecutados']);
                $descripcion = 'ELIMINO EL DOMICILIO: '.$Id_Domicilio.' DEL SEGUIMIENTO '.$_POST['Id_Seguimiento'].' EL USUARIO '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $success_3=$this->Seguimiento->historial($user, $ip, 28, $descripcion);//Guarda en el historial el movimiento
            } 

            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }
    public function DesasociaAntecedente(){//FUNCION PARA ELIMINAR UN ANTECEDENTE DE LA TABLA CON ID EN BASE DE DATOS
        if (isset($_POST['Id_Antecedente'])) {
            $Id_Antecedente = $_POST['Id_Antecedente'];
            $data = $this->Seguimiento->DesasociaAntecedente($Id_Antecedente);
            if ($data['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $data['sqlEjecutados']);
                $descripcion = 'ELIMINO EL ANTECEDENTE: '.$Id_Antecedente.' DEL SEGUIMIENTO '.$_POST['Id_Seguimiento'].' EL USUARIO '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $success_3=$this->Seguimiento->historial($user, $ip, 28, $descripcion);//Guarda en el historial el movimiento
            } 

            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }
    public function DesasociaForencia(){//FUNCION PARA ELIMINAR UNA FORENSIA DE LA TABLA CON ID EN BASE DE DATOS
        if (isset($_POST['Id_Forencia'])) {
            $Id_Forencia = $_POST['Id_Forencia'];
            $data = $this->Seguimiento->DesasociaForencia($Id_Forencia);
            if ($data['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $data['sqlEjecutados']);
                $descripcion = 'ELIMINO LA FORENCIA: '.$Id_Forencia.' DEL SEGUIMIENTO '.$_POST['Id_Seguimiento'].' EL USUARIO '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $success_3=$this->Seguimiento->historial($user, $ip, 28, $descripcion);//Guarda en el historial el movimiento
            } 

            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }
    public function DesasociaRedSocial(){//FUNCION PARA ELIMINAR UN DATO DE RED SOCIAL DE LA TABLA CON ID EN BASE DE DATOS
        if (isset($_POST['Id_Registro'])) {
            $Id_Registro = $_POST['Id_Registro'];
            $data = $this->Seguimiento->DesasociaRedSocial($Id_Registro);
            if ($data['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $data['sqlEjecutados']);
                $descripcion = 'ELIMINO LA RED SOCIAL: '.$Id_Registro.' DEL SEGUIMIENTO '.$_POST['Id_Seguimiento'].' EL USUARIO '.$_SESSION['userdataSIC']->User_Name.' '.$auxsql;
                $success_3=$this->Seguimiento->historial($user, $ip, 28, $descripcion);//Guarda en el historial el movimiento
            } 

            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }

    public function UpdateCambioAltoImpacto(){//FUNCION PARA ELIMINAR UN DATO DE RED SOCIAL DE LA TABLA CON ID EN BASE DE DATOS
        if (isset($_POST['Id_Seguimiento']) && isset($_POST['Alto_Impacto'])) {
            $Id_Seguimiento = $_POST['Id_Seguimiento'];
            $Alto_Impacto = $_POST['Alto_Impacto'];
            $data = $this->Seguimiento->UpdateCambioAltoImpacto($Id_Seguimiento,$Alto_Impacto);
            if ($data['status']) {
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $ip = $this->obtenerIp();
                $quitar = array("'", "\"");
                $auxsql =str_replace($quitar, "-", $data['sqlEjecutados']);
                $descripcion = 'CAMBIO TIPO DE RED : '.$Id_Seguimiento.' EL USUARIO '.$_SESSION['userdataSIC']->User_Name.' ALTO IMPACTO:'.$Alto_Impacto .' '.$auxsql;
                $success_3=$this->Seguimiento->historial($user, $ip, 38, $descripcion);//Guarda en el historial el movimiento
            } 

            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }
    public function GeneraPDF(){//GENERA PDF CON LA INFORMACION DE TODO EL SEGUIMIENTO  VEHICULOS, PERSONAS ,DOMICILIOS, ANTECEDENTES, FORENSIAS Y REDES SOCIALES
        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }else{
            $data = $this->Seguimiento->permisoVisualizacion($_SESSION['userdataSIC']->User_Name);
            $_SESSION['userdataSIC']->Visualizacion = $data->Visualizacion;
        }
        if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Red[2] == '1'){
            if (isset($_GET['Id_seguimiento']) ){
                $Id_seguimiento= $_GET['Id_seguimiento'];
                $dataSeguimiento=$this->Seguimiento->getAllPrincipales($Id_seguimiento);
                $Personas=$this->Seguimiento->getPersonas($Id_seguimiento);
                $Vehiculos= $this->Seguimiento->getVehiculos($Id_seguimiento);
                $PersonasEntrevistasNo=$this->Seguimiento->getEntrevistasNo($Id_seguimiento);//Obtiene los datos de las personas entrevistadas que no estan en el seguimiento
                $PersonasEntrevistasSi=$this->Seguimiento->getEntrevistasSi($Id_seguimiento);//Obtiene los datos de las personas entrevistadas que si estan en el seguimiento
                $dataPersona=[];
                $i=0;
                foreach($Personas as $Persona){
                    $dataPersona[$i]= [
                    'datos_persona'=> $Persona,
                    'domicilios'   => $this->Seguimiento->getDomiciliosOneRegister($Persona->Id_Persona,'PERSONA'),
                    'antecedentes' => $this->Seguimiento->getAntecedentesOneRegister($Persona->Id_Persona,'PERSONA'),
                    'forencias'    => $this->Seguimiento->getForenciasOneRegister($Persona->Id_Persona),
                    'redes_sociales'=>$this->Seguimiento->getRedesSocialesOneRegister($Persona->Id_Persona)
                    ];
                    $i++;  
                }
                $conteoPersonas=$i;
                $i=0;
                $dataVehiculo=[];
                foreach($Vehiculos as $Vehiculo){
                    $dataVehiculo[$i]= [
                    'datos_Vehiculo'=> $Vehiculo,
                    'domicilios'   => $this->Seguimiento->getDomiciliosOneRegister($Vehiculo->Id_Vehiculo,'VEHICULO'),
                    'antecedentes' => $this->Seguimiento->getAntecedentesOneRegister($Vehiculo->Id_Vehiculo,'VEHICULO')
                    ];
                    $i++;  
                }
                $dataEventos=[];
                $i=0;
                if($dataSeguimiento['eventos']!=[]){
                    $Eventos=$dataSeguimiento['eventos'];
                    foreach($Eventos as $Evento){
                        $dataConsultaTareas = $this->Seguimiento->getTareasPrincipal($Evento->Folio_infra);
                        $dataTareas = [];
                        if($data != []){
                            $Tareas = $dataConsultaTareas;
                            $j=0;
                            foreach($Tareas as $Tarea){
                                $dataTareas[$j]= [
                                    'Tipo' =>$Tarea->tipo_tarea,
                                    'Principales'   =>$this->Seguimiento->getStatusTareaTipo($Tarea->id_tarea, $Tarea->tipo_tarea)
                                ];
                                $j++;
                            }
                        }
                        $dataEventos[$i]= [
                            'principales'   => $Evento,
                            'evento'   => $this->Seguimiento->getPrincipalesEventoAll($Evento->Folio_infra),
                            'detencion'   => $this->Seguimiento->getInfoDetencion($Evento->Folio_infra),
                            'delitos'       => $this->Seguimiento->getDelitosC($Evento->Folio_infra),
                            'hechos'        => $this->Seguimiento->getHechosC($Evento->Folio_infra),
                            'entrevistas'   => $this->Seguimiento->getEntrevistasEvento($Evento->Folio_infra),
                            'vehiculos'   => $this->Seguimiento->getVehiculosC($Evento->Folio_infra),
                            'personas'   => $this->Seguimiento->getResponsablesC($Evento->Folio_infra),
                            'fotos'   => $this->Seguimiento->getFotos($Evento->Folio_infra),
                            'usuarios'=>$this->Seguimiento->getUsuarios(),
                            'tareas' => $dataTareas
                        ];
                        $i++;
                    }
                }
                $dataPersonasEntrevistadasNo=[];
                $i=0;
                foreach($PersonasEntrevistasNo as $Persona){
                    $dataPersonasEntrevistadasNo[$i]= [
                        'Principales'=> $this->Seguimiento->getPrincipalesEntrevista($Persona->Id_Persona_Entrevista ),
                        'Ubicaciones'   => $this->Seguimiento->getUbicaciones($Persona->Id_Persona_Entrevista ),
                        'Entrevistas' => $this->Seguimiento->getEntrevistas($Persona->Id_Persona_Entrevista ),
                        'Forensias'    => $this->Seguimiento->getForensias($Persona->Id_Persona_Entrevista )
                    ];
                    $i++;  
                }
                $dataPersonasEntrevistadasSi=[];
                $i=0;
                foreach($PersonasEntrevistasSi as $Persona){
                    $dataPersonasEntrevistadasSi[$i]= [
                        'Principales'=> $this->Seguimiento->getPrincipalesEntrevista($Persona->Id_Persona_Entrevista ),
                        'Ubicaciones'   => $this->Seguimiento->getUbicaciones($Persona->Id_Persona_Entrevista ),
                        'Entrevistas' => $this->Seguimiento->getEntrevistas($Persona->Id_Persona_Entrevista ),
                        'Forensias'    => $this->Seguimiento->getForensias($Persona->Id_Persona_Entrevista )
                    ];
                    $i++;  
                }
                $info_hijos = [];
                if($dataSeguimiento['principal']->Tipo_Grupo=='GRUPO'){
                    
                    $hijos = $this->Seguimiento->getHijosRed($Id_seguimiento);
                    $j=0;
                    foreach($hijos as $hijo){
                        $PersonasH = $this->Seguimiento->getPersonas($hijo->Id_Seguimiento);
                        $VehiculosH = $this->Seguimiento->getVehiculos($hijo->Id_Seguimiento);
                        $dataSeguimientoH =$this->Seguimiento->getAllPrincipales($hijo->Id_Seguimiento);
                        $dataPersonaH=[];
                        $i=0;
                        foreach($PersonasH as $PersonaH){
                            $dataPersonaH[$i]= [
                            'datos_persona'=> $PersonaH,
                            'domicilios'   => $this->Seguimiento->getDomiciliosOneRegister($PersonaH->Id_Persona,'PERSONA'),
                            'antecedentes' => $this->Seguimiento->getAntecedentesOneRegister($PersonaH->Id_Persona,'PERSONA'),
                            'forencias'    => $this->Seguimiento->getForenciasOneRegister($PersonaH->Id_Persona),
                            'redes_sociales'=>$this->Seguimiento->getRedesSocialesOneRegister($PersonaH->Id_Persona)
                            ];
                            $i++;  
                        }
                        $conteoPersonas = $conteoPersonas + $i;
                        $i=0;
                        $dataVehiculoH=[];
                        foreach($VehiculosH as $VehiculoH){
                            $dataVehiculoH[$i]= [
                            'datos_Vehiculo'=> $VehiculoH,
                            'domicilios'   => $this->Seguimiento->getDomiciliosOneRegister($VehiculoH->Id_Vehiculo,'VEHICULO'),
                            'antecedentes' => $this->Seguimiento->getAntecedentesOneRegister($VehiculoH->Id_Vehiculo,'VEHICULO')
                            ];
                            $i++;  
                        }
                        $dataEventosH=[];
                        $i=0;
                        if($dataSeguimientoH['eventos']!=[]){
                            $EventosH=$dataSeguimientoH['eventos'];
                            foreach($EventosH as $EventoH){
                                
                                $dataConsultaTareas = $this->Seguimiento->getTareasPrincipal($Evento->Folio_infra);
                                $dataTareas = [];
                                if($data != []){
                                    $Tareas = $dataConsultaTareas;
                                    $j=0;
                                    foreach($Tareas as $Tarea){
                                        $dataTareas[$j]= [
                                            'Tipo' =>$Tarea->tipo_tarea,
                                            'Principales'   =>$this->Seguimiento->getStatusTareaTipo($Tarea->id_tarea, $Tarea->tipo_tarea)
                                        ];
                                        $j++;
                                    }
                                }
                                $dataEventosH[$i]= [
                                    'principales'   => $EventoH,
                                    'evento'   => $this->Seguimiento->getPrincipalesEventoAll($EventoH->Folio_infra),
                                    'detencion'   => $this->Seguimiento->getInfoDetencion($EventoH->Folio_infra),
                                    'delitos'       => $this->Seguimiento->getDelitosC($EventoH->Folio_infra),
                                    'hechos'        => $this->Seguimiento->getHechosC($EventoH->Folio_infra),
                                    'entrevistas'   => $this->Seguimiento->getEntrevistasEvento($EventoH->Folio_infra),
                                    'vehiculos'   => $this->Seguimiento->getVehiculosC($EventoH->Folio_infra),
                                    'personas'   => $this->Seguimiento->getResponsablesC($EventoH->Folio_infra),
                                    'fotos'   => $this->Seguimiento->getFotos($EventoH->Folio_infra),
                                    'usuarios'=>$this->Seguimiento->getUsuarios(),
                                    'tareas' => $dataTareas
                                ];
                                $i++;
                            }
                        }
                        $info_hijos[$j] = [
                            'dataSeguimientoH'   => $dataSeguimientoH,
                            'datos_vehiculos'   => $dataVehiculoH,
                            'datos_personas' => $dataPersonaH,
                            'datos_eventos' => $dataEventosH
                        ];
                        $j++;
                    }
                }

                $info_Seguimiento=[
                    'datos_seguimiento'=> $dataSeguimiento,
                    'ConteoPersonas'=> $conteoPersonas,
                    'datos_vehiculos' => $dataVehiculo,
                    'datos_personas' => $dataPersona,
                    'datos_eventos' => $dataEventos,
                    'datos_personas_entrevistadas_no'=> $dataPersonasEntrevistadasNo,
                    'datos_personas_entrevistadas_si'=> $dataPersonasEntrevistadasSi,
                    'data_hijos' => $info_hijos
                ];
            }else{
                header("Location: " . base_url . "Seguimientos");
                exit();
    
            }
            if($_SESSION['userdataSIC']->Red[0] == $dataSeguimiento["principal"]->Alto_Impacto || $_SESSION['userdataSIC']->Modo_Admin == '1'){
                $this->view('system/seguimientos/fichaseguimientoView', $info_Seguimiento);
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $ip = $this->obtenerIp();
                $descripcion = 'EXPORTACION DE FICHA DE SEGUIMIENTO PDF: '.$_SESSION['userdataSIC']->User_Name.' SEGUIMIENTO: '.$Id_Seguimiento;
                $this->Seguimiento->historial($user, $ip, 29, $descripcion);//GUARDA EL MOVIMIENTO EN EL HISTORIAL
            }

    
        }else{
            header("Location: " . base_url . "Estadisticas");
            exit();
    
        }
    }
    public function GenerarFichaAtlas(){
        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }
        if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Red[2] == '1'){
            if (isset($_GET['Id_seguimiento']) ){
                $Id_seguimiento = $_GET['Id_seguimiento'];
                $dataSeguimiento = $this->Seguimiento->getAllPrincipales($Id_seguimiento);
                $Personas = $this->Seguimiento->getPersonas($Id_seguimiento);
                $dataPersona=[];
                $i=0;
                foreach($Personas as $Persona){
                    $dataPersona[$i]= [
                    'datos_persona'=> $Persona,
                    'perfiles'=>$this->Seguimiento->getRedesSocialPerfiles($Persona->Id_Persona),
                    'domicilios_confirmados'   => $this->Seguimiento->getDomiciliosConfirmados($Persona->Id_Persona,'PERSONA'),
                    'domicilios_presuntos'   => $this->Seguimiento->getDomiciliosPresuntos($Persona->Id_Persona,'PERSONA'),
                    'antecedentes' => $this->Seguimiento->getAntecedentesOneRegister($Persona->Id_Persona,'PERSONA')
                    ];
                    $i++;  
                }
                $info_Seguimiento=[
                    'datos_seguimiento'=> $dataSeguimiento,
                    'datos_personas'=> $dataPersona
                ];
            }
            $this->view('system/seguimientos/fichaAtlasView', $info_Seguimiento);
            $user = $_SESSION['userdataSIC']->Id_Usuario;
            $ip = $this->obtenerIp();
            $descripcion = 'EXPORTACION DE FICHA TIPO ATLAS: '.$_SESSION['userdataSIC']->User_Name.' SEGUIMIENTO: '.$Id_seguimiento;
            $this->Seguimiento->historial($user, $ip, 29, $descripcion);//GUARDA EL MOVIMIENTO EN EL HISTORIAL
        }else{
            header("Location: " . base_url . "Estadisticas");
            exit();
        }

    }
    public function GeneraPersonaPDF(){//GENERA PDF CON LA INFORMACION DE LA PERSONA DOMICILIOS, ANTECEDENTES ,FORENSIAS Y REDES SOCIALES
        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }else{
            $data=$this->Seguimiento->permisoVisualizacion($_SESSION['userdataSIC']->User_Name);
            $_SESSION['userdataSIC']->Visualizacion = $data->Visualizacion;
        }
        if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Red[2] == '1'){
            if (isset($_GET['Id_Persona']) ){
                $Id_Persona= $_GET['Id_Persona'];
                $persona=$this->Seguimiento->getPersona($Id_Persona);
                $dataPersona= [
                'datos_persona'=> $persona,
                'domicilios'   => $this->Seguimiento->getDomiciliosOneRegister($Id_Persona,'PERSONA'),
                'antecedentes' => $this->Seguimiento->getAntecedentesOneRegister($Id_Persona,'PERSONA'),
                'forencias'    => $this->Seguimiento->getForenciasOneRegister($Id_Persona),
                'redes_sociales'=>$this->Seguimiento->getRedesSocialesOneRegister($Id_Persona),
                'grupo_delictivo'=>$this->Seguimiento->getGrupoDelictivo($persona->Id_Seguimiento)
                ];
                $alto=$this->Seguimiento->getALTOImpacto($persona->Id_Seguimiento);
            }else{
                header("Location: " . base_url . "Seguimientos");
                exit();
    
            }
            
            if($_SESSION['userdataSIC']->Red[0] == $alto->Alto_Impacto || $_SESSION['userdataSIC']->Modo_Admin == '1'){
                $this->view('system/seguimientos/fichaPersonaView', $dataPersona);
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $ip = $this->obtenerIp();
                $descripcion = 'EXPORTACION DE FICHA PERSONA PDF: '.$_SESSION['userdataSIC']->User_Name.' PERSONA: '.$Id_Persona;
                $this->Seguimiento->historial($user, $ip, 29, $descripcion);//GUARDA EL MOVIMIENTO EN EL HISTORIAL
            }
    
        }else{
            header("Location: " . base_url . "Estadisticas");
            exit();
    
        }
    }
    public function GeneraVehiculoPDF(){//GENERA PDF CON LA INFORMACION DEl VEHICULO DOMICILIOS Y ANTECEDENTES 
        if(!isset($_SESSION['userdataSIC'])){//SI NO EXISTE UNA SESION ACTIVA NO DEJA PASAR AL SISTEMA
            header("Location: " . base_url . "Login");
            exit();
        }
        if ($_SESSION['userdataSIC']->Modo_Admin == '1' || $_SESSION['userdataSIC']->Red[2] == '1'){
            if (isset($_GET['Id_Vehiculo']) ){
                $Id_Vehiculo= $_GET['Id_Vehiculo'];
                $Vehiculo=$this->Seguimiento->getVehiculo($Id_Vehiculo);
                $dataVehiculo= [
                'datos_Vehiculo'=> $Vehiculo,
                'domicilios'   => $this->Seguimiento->getDomiciliosOneRegister($Id_Vehiculo,'VEHICULO'),
                'antecedentes' => $this->Seguimiento->getAntecedentesOneRegister($Id_Vehiculo,'VEHICULO'),
                'grupo_delictivo'=>$this->Seguimiento->getGrupoDelictivo($Vehiculo->Id_Seguimiento)
                ];
                $alto=$this->Seguimiento->getALTOImpacto($persona->Id_Seguimiento);
            }else{
                header("Location: " . base_url . "Seguimientos");
                exit();
    
            }
            if($_SESSION['userdataSIC']->Red[0] == $alto->Alto_Impacto || $_SESSION['userdataSIC']->Modo_Admin == '1'){
                $this->view('system/seguimientos/fichaVehiculoView', $dataVehiculo);
                $user = $_SESSION['userdataSIC']->Id_Usuario;
                $ip = $this->obtenerIp();
                $descripcion = 'EXPORTACION DE FICHA VEHICULO PDF: '.$_SESSION['userdataSIC']->User_Name.' VEHICULO: '.$Id_Vehiculo;
                $this->Seguimiento->historial($user, $ip, 29, $descripcion);//GUARDA EL MOVIMIENTO EN EL HISTORIAL
            }
        }else{
            header("Location: " . base_url . "Estadisticas");
            exit();
    
        }
    }
    public function ConsultaPersonaFetch(){
        if (isset($_POST['Nombre'])) {
            $Nombre = $_POST['Nombre'];
            $Ap_paterno = $_POST['Ap_paterno'];
            $Ap_materno = $_POST['Ap_materno'];
            $data = $this->Seguimiento->ConsultaPersona($Nombre, $Ap_paterno, $Ap_materno);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }

    public function ConsultaVehiculoFetch(){
        if (isset($_POST['Placa'])||isset($_POST['Niv'])) {
            $Placa = $_POST['Placa'];
            $Niv = $_POST['Niv'];
            $data = $this->Seguimiento->ConsultaVehiculo($Placa, $Niv);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }
    public function ConsultaVehiculoEFetch(){
        if (isset($_POST['Placa'])) {
            $Placa = $_POST['Placa'];
            $data = $this->Seguimiento->ConsultaVehiculoEventos($Placa);
            echo json_encode($data);
        } else {
            header("Location: " . base_url . "Seguimientos");
            exit();
        }
    }
}

?>