/*-----------------------------------------------ESTE ARCHIVO DE JAVASCRIPT ES PARA LA FUNCIONALIDADES DE NUEVO EVENTO-------------------------------------------------------------------------*/
var data = document.getElementById('datos_principales')
var Folio911Error = document.getElementById('911_principalesError')
var RecepcionError = document.getElementById('recepcion_error')
var ZonaError = document.getElementById('zona_error')
var VectorError = document.getElementById('vector_error')
var ColoniaError = document.getElementById('Colonia_principales_error')
var CalleError = document.getElementById('Calle_principales_error')
var Calle2Error = document.getElementById('Calle2_principales_error')
var NoExtError = document.getElementById('NoExt_principales_error')
var CPError = document.getElementById('CP_principales_error')
var cordYError = document.getElementById('cordY_principales_error')
var cordXError = document.getElementById('cordX_principales_error')
var ActivavionError = document.getElementById('activacion_error')
var TablaError = document.getElementById('tabla_error')
var TablaErrorhecho = document.getElementById('tabla_hecho_error')
var inputotro = document.getElementById('delitos_otro')
var inputdel = document.getElementById('delitos_principales')
var fuenteError = document.getElementById('fuente_principales_error')
var msg_principalesError = document.getElementById('msg_principales')
var OpcionViolenciaError = document.getElementById('violencia_principales1_error')
var ViolenciaError = document.getElementById('violencia_principales_error')
var SViolenciaError = document.getElementById('sviolencia_principales_error')
var EstatusEventoError = document.getElementById('Estatus_Evento_principales_error')
var banderaHabilitacion = false;

document.getElementById('btn_principal').addEventListener('click', async function(e) {//Funcion que valida lo ingresado 
    e.preventDefault()
    var myFormData = new FormData(data)
    var band = []
    var FV = new FormValidator()
    var i = 0

    if (myFormData.get('fuente_principales') == 'LLAMADA AL 911'){//VALIDA SI ES FUENTE ES UN LLAMADO AL 911 Y HACE QUE SEA OBLIAGARIO ADEMAS DE QUE DEBE SER LO INGRESADO SOLO NUMERO
        band[i++] = Folio911Error.innerText = FV.validate(myFormData.get('911_principales'), 'required')
        fuenteError.innerText = '';
    }else{
        Folio911Error.innerText = '';
        if (myFormData.get('fuente_principales') == 'NA'){
            band[i++] = fuenteError.innerText = FV.validate(myFormData.get('fuente_principales'), 'required')
        }else{
            fuenteError.innerText = '';
        }
    }
    if(myFormData.get('911_principales').length>0){//VALIDA QUE EL FOLIO 911 INGRESADO NO ESTE YA REGISTRADO ANTES
        valido911= await validateFolio911(myFormData.get('911_principales'))
        if(valido911!=""){
            band[i++] = Folio911Error.innerText = valido911
        }else{
            Folio911Error.innerText = '';
        }
    }
    if (myFormData.get('violencia_principales1') == 'NA'){//VALIDA SI NO HA SELECCIONADO UN TIPO DE VIOLENCIA
        band[i++] = OpcionViolenciaError.innerText = FV.validate(myFormData.get('violencia_principales1'), 'required')
    }else{
        if (myFormData.get('violencia_principales1') == 'CON VIOLENCIA'){//SI EL TIPO DE VIOLENCIA ES CON VALIDA QUE SE SELECIONE ALGUNA OPCION DEL CATALOGO QUE SE MUESTRA
            OpcionViolenciaError.innerText = '';
            if (myFormData.get('violencia_principales') == 'NA'){
                band[i++] = ViolenciaError.innerText = FV.validate(myFormData.get('violencia_principales'), 'required')
            }else{
                ViolenciaError.innerText = '';

            }
        }else{
            if (myFormData.get('violencia_principales1') == 'SIN VIOLENCIA'){//SI EL TIPO DE VIOLENCIA ES SIN VALIDA QUE SE SELECIONE ALGUNA OPCION DEL CATALOGO QUE SE MUESTRA
                OpcionViolenciaError.innerText = '';
                if (myFormData.get('sviolencia_principales') == 'NA'){
                    band[i++] = SViolenciaError.innerText = FV.validate(myFormData.get('sviolencia_principales'), 'required')
                }else{
                    SViolenciaError.innerText = '';
                }
            }
        }
    }
    if(document.getElementById('Estatus_Evento').value!="FUERA DE JURISDICCION"){// SI LA OPCION EN EL ESTATUS DEL EVENTO ES FUERA DE JURISDICCION NO SE VELIDA LA UBICACION
        band[i++] = ZonaError.innerText = FV.validate(myFormData.get('zona'), 'required ')
        band[i++] = VectorError.innerText = FV.validate(myFormData.get('vector'), 'required ')
        band[i++] = ColoniaError.innerText = FV.validate(myFormData.get('Colonia'), 'required ')
        band[i++] = CalleError.innerText = FV.validate(myFormData.get('Calle'), 'required ')
        band[i++] = cordYError.innerText = await ValidaCoordY(document.getElementById('cordY').value);
        band[i++] = cordXError.innerText =  await ValidaCoordX(document.getElementById('cordX').value);
    }
    if(inputdel.value == 'otro' && inputotro.value == '')//VALIDA SI EN LA FUNCION ESPECIAL DE OTRO DELITO EXISTE ALGO SI NO AVISA
        band[i++] = TablaError.innerText = 'Debe de especificar el delito'

    //SE EVALUA SI EXISTEN DATOS EN LAS TABLAS DE DELITOS Y HECHOS POR LO MENOS UN DATO DEBE DE EXISTIR 
    rowsTableFaltas = document.getElementById('contardelitos').rows.length;//ALMACENA EL NUMERO DE RENGLONES DE LA TABLA DELITOS
    if(rowsTableFaltas == 0){
        band[i++] = TablaError.innerText = 'Debe de seleccionar al menos una falta o delito'
    }else {
        band[i++] = TablaError.innerText = ''
        var delitosForm = readTableDelitos();
        myFormData.append('delitos_table', JSON.stringify(delitosForm));
    }
    rowsTablehechos = document.getElementById('contarhechos').rows.length;//ALMACENA EL NUMERO DE RENGLONES DE LA TABLA HECHOS
    if(rowsTablehechos == 0){
        band[i++] = TablaErrorhecho.innerText = 'Debe agregar por lo menos un hecho'
    }else {
        band[i++] = TablaErrorhecho.innerText = ''
        var hechosForm = readTableHechos();
        myFormData.append('hechos_table', JSON.stringify(hechosForm));
    }
    //SE EVALUA SI EXISTEN DATOS EN LAS TABLAS DE PRESUNTOS VEHICULOS Y PRESUNTOS RESPONSABLES
    rowsTableVehiculos = document.getElementById('contarVehiculos').rows.length;//Selecciona el conteo de las tablas de los vehiculos
    if(rowsTableVehiculos > 0){
        var vehiculosForm = await readTableVehiculos();
        myFormData.append('vehiculos_table', JSON.stringify(vehiculosForm));
    }

    rowsTableRes = document.getElementById('contarRes').rows.length;//Selecciona el conteo de las tablas de las personas involucradas
    if(rowsTableRes > 0){
        var ResponsableForm = await readTableRes();
        myFormData.append('responsables_table', JSON.stringify(ResponsableForm));
    }

    //se comprueban todas las validaciones
    var success = true
    band.forEach(element => {//recorre todas la banderas si todas son vacias procede a guardar los datos ya que no existe ninguna restriccion 
        success &= (element == '') ? true : false
    })

    if (success) { //si todo es correcto se envía form

        button2 = document.getElementById('btn_principal')
        button2.innerHTML = `
            Guardando
            <div class="spinner-grow spinner-grow-sm" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        `;
        button2.classList.add('disabled-link');//se desactiva el boton de guardar hasta que halla una respuesta del controlador
        $('#ModalCenterFoto').modal('show');//Mostramos una imagen completa en pantalla hasta que halla una respuesta del controlador

        if(document.getElementById('Estatus_Evento').value=="POR CONFIRMAR"){//Funcion para guardar preguntara si el evento esta pro confirmar
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });
            EstatusEventoError = 'Desea guardarlo con este estatus'
            var opcion = await confirm("El evento se encuentra con el estatus \"POR CONFIRMAR\" ¿Desea guardarlo con este estatus?");

        }else{
            opcion = true;
        }
        if (opcion == true) {
            //Agregamos los campos proporcionados  que se almacenaran
            EstatusEventoError = ''
            myFormData.append('Estatus_Evento',document.getElementById('Estatus_Evento').value)
            if(document.getElementById('Semana').value==""){
                myFormData.append('Semana',0)
            }
            if(document.getElementById('Habilitado_question1').checked){
                myFormData.append('Habilitado','HABILITADO')
                myFormData.append('FechaHora_Activacion',document.getElementById('fechahora_activacion_principales').value)
                myFormData.append('Quien_Habilito',document.getElementById('quienhabilito').value)
                banderaHabilitacion = true;
            }else{
                myFormData.append('Habilitado','DESHABILITADO')
                myFormData.append('FechaHora_Activacion','')
                myFormData.append('Quien_Habilito','')
            }
            myFormData.append('captura_principales',document.getElementById('captura_principales').value)

            myFormData.append('fechahora_captura_principales',document.getElementById('fechahora_captura_principales').value)
            myFormData.append('CSviolencia', document.getElementById('violencia_principales1').value)
            if(document.getElementById('violencia_principales1').value=="SIN VIOLENCIA"){
                myFormData.append('violencia_principales', document.getElementById('sviolencia_principales').value)

            }else{
                myFormData.append('violencia_principales', document.getElementById('violencia_principales').value)
            }
            
            myFormData.append('boton_principales', document.getElementById('btn_principal').value)

            fetch(base_url_js + 'GestorCasos/insertEventoFetch', {//realiza el fetch para insertar los datos
                method: 'POST',
                body: myFormData
            })
            .then(res => res.json())

            .then(data => {//obtine respuesta del controlador
                button2.innerHTML = `Guardar`;
                button2.classList.remove('disabled-link');//se vuelve activar la funcion del boton 
                $('#ModalCenterFoto').modal('hide');//se quita la imagen 
                console.log(data)
                if (!data.status) {//si ubo error informa que clase de error se genero 
                    let messageError;
                    if ('error_message' in data) {
                        if (data.error_message != 'Render Index') {
                            if (typeof(data.error_message) != 'string') {
                                messageError = `<div class="alert alert-danger text-center" role="alert">Sucedio un error en el servidor: ${data.error_message.errorInfo[2]}</div>`;
                            } else {
                                messageError = `<div class="alert alert-danger text-center" role="alert">Sucedio un error en el servidor: ${data.error_message}</div>`;
                            }
                        } else {
                            messageError = `<div class="alert alert-danger text-center alert-session-create" role="alert">
                                                <p>Sucedio un error, su sesión caduco o no tiene los permisos necesarios. Por favor vuelva a iniciar sesión.</p>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalLogin">
                                                    Iniciar sesión
                                                </button>
                                            </div>`;
                        }
                    } else {
                        messageError = '<div class="alert alert-danger text-center" role="alert">Por favor, revisa nuevamente el formulario ya que hubo un error </div>';
                        
                    }
                    console.log(data.error_sql)
                    msg_principalesError.innerHTML = messageError
                    window.scroll({
                        top: 0,
                        left: 100,
                        behavior: 'smooth'
                    });

                } else {//si todo salio bien
                    button2.classList.add('disabled-link');//se desactiva el boton para que el usuario no pueda guardar la misma informacion

                    Folio911Error.innerText = ''
                    RecepcionError.innerText = ''
                    ZonaError.innerText = ''
                    VectorError.innerText = ''
                    ColoniaError.innerText = ''
                    CalleError.innerText = ''
                    Calle2Error.innerText = ''
                    NoExtError.innerText = ''
                    CPError.innerText = ''
                    cordYError.innerText = ''
                    cordXError.innerText = ''
                    TablaError.innerText = ''
                    fuenteError.innerText = '';

                    window.scroll({
                        top: 0,
                        left: 100,
                        behavior: 'smooth'
                    });//mueve la vista hasta arriba de la pagina
                    document.getElementById('datos_principales').reset()
                    if(banderaHabilitacion){
                        let myFormData2= new FormData();
                        myFormData2.append('folio_aura', data.Folio_infra);
                        fetch('http://172.18.10.71:8080/api/asignador/tarea-guardia', {//realiza el fetch para insertar los datos
                            method: 'POST',
                            body: myFormData2
                        })
                    }
                    alerta()//Si todo se valido bien y se inserto correctamente se arroja un mensaje satisfactorio y redirige a la pestaña principal del gestor de casos
                }
            })
        }

    } else { //si no, se muestran errores en pantalla

        msg_principalesError.innerHTML = `<div class="alert alert-danger text-center" role="alert">Por favor, revisa nuevamente el formulario hay campos requeridos
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>`;
        console.log(myFormData)
        window.scroll({
            top: 0,
            left: 100,
            behavior: 'smooth'
        });
    }

    /*for (var pair of myFormData.entries()) {
        console.log(pair[0] + ', ' + pair[1]);
    }*/
})

function alerta() {/// todo bien en la insercion
    const BtGuardar = document.getElementById("btn_principal");
    BtGuardar.setAttribute('disabled', '');
    msg_principalesError.innerHTML = `<div class="alert alert-success text-center" role="success">Datos Insertados Correctamente. En breve será redirigido a la página Principal de Gestor de Casos
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
            <span aria-hidden="true">&times;</span>
        </button>
    </div>`;
    window.scroll({
        top: 0,
        left: 100,
        behavior: 'smooth'
    });
    //console.log(base_url_js);
    setTimeout(function() { window.location = base_url_js+"GestorCasos"; }, 2000);//funcion para redirigir a la pagina principal del gestor de casos
}
/*-----------------------------------FUNCIONES PARA LEER LOS DATOS DE LAS TABLAS-----------------------------------*/
const readTableDelitos = () => {//lee los datos de la tabla delitos y genera una estructura deacuerdo a los datos contenido es la tabla
    const table = document.getElementById('faltasDelitosTable');
    let objetos = [];

    for (let i = 1; i < table.rows.length; i++) {
        objetos.push({
            ['row']: {
                descripcion: table.rows[i].cells[0].innerHTML,
                tipo_delito: table.rows[i].cells[1].innerHTML
            }
        });
    }

    return objetos;
}
const readTableHechos = () => {//lee los datos de la tabla hechos y genera una estructura deacuerdo a los datos contenido es la tabla
    const table = document.getElementById('HechosTable');
    let objetos = [];

    for (let i = 1; i < table.rows.length; i++) {
        objetos.push({
            ['row']: {
                descripcion: table.rows[i].cells[0].innerHTML,
                Fecha: table.rows[i].cells[1].innerHTML,
                Hora: table.rows[i].cells[2].innerHTML
            }
        });
    }

    return objetos;
}
const readTableVehiculos = async() => {//lee los datos de la tabla vehiculos y genera una estructura deacuerdo a los datos contenido es la tabla
    const table = document.getElementById('VehiculoTable');
    let objetos = [];
    for (let i = 1; i < table.rows.length; i++) {
        const input = table.rows[i].cells[7].children[1].children[2];
      
        if (input != undefined) {//si en el contenedor existe alguna imagen verifica el tipo 
            const type = table.rows[i].cells[7].children[1].children[3].classList[1]
            base64 = document.getElementById('imagesV_row_' + i);
            nameImage = 'FotoVehiculo_row' + i;
            isPNG = base64.src.split('.');
            if (type != 'File') {//funcion para leer las fotos
                if (isPNG[1] != undefined) {
                    await toDataURL(base64.src)//esto es para tipo photo osea que ya se encuentran en el servidor un archivo fisico con extension diferente a la png
                        .then(myBase64 => {
                            objetos.push({
                                ['row']: {
                                    tipo_vehiculo: table.rows[i].cells[0].innerHTML,
                                    marca: table.rows[i].cells[1].innerHTML,
                                    submarca: table.rows[i].cells[2].innerHTML,
                                    modelo: table.rows[i].cells[3].innerHTML,
                                    placas: table.rows[i].cells[4].innerHTML,
                                    color: table.rows[i].cells[5].innerHTML,
                                    descripcionV: table.rows[i].cells[6].innerHTML,
                                    tipo_vehiculo_involucrado: table.rows[i].cells[8].innerHTML,
                                    estado_veh: table.rows[i].cells[9].innerHTML,
                                    capturo: table.rows[i].cells[10].innerHTML,
                                    Ultima_Actualizacion: table.rows[i].cells[10].innerHTML,
                                    typeImage: type,
                                    nameImage: nameImage,
                                    image: myBase64,
                                    imagebase64:myBase64
                                }
                            });
                        })
                } else {//esto es para tipo photo osea que ya se encuentran en el servidor un archivo fisico con extension png
                    objetos.push({
                        ['row']: {
                            tipo_vehiculo: table.rows[i].cells[0].innerHTML,
                            marca: table.rows[i].cells[1].innerHTML,
                            submarca: table.rows[i].cells[2].innerHTML,
                            modelo: table.rows[i].cells[3].innerHTML,
                            placas: table.rows[i].cells[4].innerHTML,
                            color: table.rows[i].cells[5].innerHTML,
                            descripcionV: table.rows[i].cells[6].innerHTML,
                            tipo_vehiculo_involucrado: table.rows[i].cells[8].innerHTML,
                            estado_veh: table.rows[i].cells[9].innerHTML,
                            capturo: table.rows[i].cells[10].innerHTML,
                            Ultima_Actualizacion: table.rows[i].cells[10].innerHTML,
                            typeImage: type,
                            nameImage: nameImage,
                            image:  base64.src,
                            imagebase64:base64.src
                        }
                    });
                }
            }else{// si es tipo file guarda los datos de la imagen
                aux=document.getElementById('fileFotoV_row'+i)
                const base64URL = await encodeFileAsBase64URL(aux.files[0]);//genera la codificacion de la en base64 de la imagen
                objetos.push({
                    ['row']: {
                        tipo_vehiculo: table.rows[i].cells[0].innerHTML,
                        marca: table.rows[i].cells[1].innerHTML,
                        submarca: table.rows[i].cells[2].innerHTML,
                        modelo: table.rows[i].cells[3].innerHTML,
                        placas: table.rows[i].cells[4].innerHTML,
                        color: table.rows[i].cells[5].innerHTML,
                        descripcionV: table.rows[i].cells[6].innerHTML,
                        tipo_vehiculo_involucrado: table.rows[i].cells[8].innerHTML,
                        estado_veh: table.rows[i].cells[9].innerHTML,
                        capturo: table.rows[i].cells[10].innerHTML,
                        Ultima_Actualizacion: table.rows[i].cells[10].innerHTML,
                        typeImage: type,
                        nameImage: nameImage,
                        image: "null",
                        imagebase64:base64URL
                    }
                });

            }
 
        } else {//si no hay imagen solo almacena los datos el texto 
            objetos.push({
                ['row']: {
                    tipo_vehiculo: table.rows[i].cells[0].innerHTML,
                    marca: table.rows[i].cells[1].innerHTML,
                    submarca: table.rows[i].cells[2].innerHTML,
                    modelo: table.rows[i].cells[3].innerHTML,
                    placas: table.rows[i].cells[4].innerHTML,
                    color: table.rows[i].cells[5].innerHTML,
                    descripcionV: table.rows[i].cells[6].innerHTML,
                    tipo_vehiculo_involucrado: table.rows[i].cells[8].innerHTML,
                    estado_veh: table.rows[i].cells[9].innerHTML,
                    capturo: table.rows[i].cells[10].innerHTML,
                    Ultima_Actualizacion: table.rows[i].cells[10].innerHTML,
                    typeImage: "null",
                    nameImage: "null",
                    image: "null",
                    imagebase64:"null"
                }
            });
        }

    }

    return objetos;
}
const readTableRes = async() => {//lee los datos de la tabla personas y genera una estructura deacuerdo a los datos contenido es la tabla
    const table = document.getElementById('PersonaTable');
    let objetos = [];
    for (let i = 1; i < table.rows.length; i++) {
        const input = table.rows[i].cells[4].children[1].children[2];
        if (input != undefined) {//si en el contenedor existe alguna imagen verifica el tipo 
            const type = table.rows[i].cells[4].children[1].children[3].classList[1]
            base64 = document.getElementById('imagesP_row_' + i);
            nameImage = 'FotoInvolucrado_row' + i;
            isPNG = base64.src.split('.');
            if (type != 'File') {//funcion para leer las fotos
                if (isPNG[1] != undefined) {
                    await toDataURL(base64.src)//esto es para tipo photo osea que ya se encuentran en el servidor un archivo fisico con extension diferente a la png
                        .then(myBase64 => {
                            objetos.push({
                                ['row']: {
                                    sexo: table.rows[i].cells[0].innerHTML,
                                    rango_edad: table.rows[i].cells[1].innerHTML,
                                    complexion: table.rows[i].cells[2].innerHTML,
                                    descripcionR: table.rows[i].cells[3].innerHTML,
                                    tipo_arma: table.rows[i].cells[5].innerHTML,
                                    estado_res: table.rows[i].cells[6].innerHTML,
                                    capturo: table.rows[i].cells[7].innerHTML,
                                    Ultima_Actualizacion: table.rows[i].cells[7].innerHTML,
                                    typeImage: type,
                                    nameImage: nameImage,
                                    image: myBase64,
                                    imagebase64:myBase64
                                }
                            });
                        })
                } else {//esto es para tipo photo osea que ya se encuentran en el servidor un archivo fisico con extension png
                    objetos.push({
                        ['row']: {
                            sexo: table.rows[i].cells[0].innerHTML,
                            rango_edad: table.rows[i].cells[1].innerHTML,
                            complexion: table.rows[i].cells[2].innerHTML,
                            descripcionR: table.rows[i].cells[3].innerHTML,
                            tipo_arma: table.rows[i].cells[5].innerHTML,
                            estado_res: table.rows[i].cells[6].innerHTML,
                            capturo: table.rows[i].cells[7].innerHTML,
                            Ultima_Actualizacion: table.rows[i].cells[7].innerHTML,
                            typeImage: type,
                            nameImage: nameImage,
                            image:  base64.src,
                            imagebase64:base64.src
                        }
                    });
                }
            }else{// si es tipo file guarda los datos de la imagen
                aux=document.getElementById('fileFotoP_row'+i)
                const base64URL = await encodeFileAsBase64URL(aux.files[0]);//genera la codificacion de la en base64 de la imagen
                objetos.push({
                    ['row']: {
                        sexo: table.rows[i].cells[0].innerHTML,
                        rango_edad: table.rows[i].cells[1].innerHTML,
                        complexion: table.rows[i].cells[2].innerHTML,
                        descripcionR: table.rows[i].cells[3].innerHTML,
                        tipo_arma: table.rows[i].cells[5].innerHTML,
                        estado_res: table.rows[i].cells[6].innerHTML,
                        capturo: table.rows[i].cells[7].innerHTML,
                        Ultima_Actualizacion: table.rows[i].cells[7].innerHTML,
                        typeImage: type,
                        nameImage: nameImage,
                        image: "null",
                        imagebase64:base64URL
                    }
                });

            }
 
        } else {//si no hay imagen solo almacena los datos el texto 
            objetos.push({
                ['row']: {
                    sexo: table.rows[i].cells[0].innerHTML,
                    rango_edad: table.rows[i].cells[1].innerHTML,
                    complexion: table.rows[i].cells[2].innerHTML,
                    descripcionR: table.rows[i].cells[3].innerHTML,
                    tipo_arma: table.rows[i].cells[5].innerHTML,
                    estado_res: table.rows[i].cells[6].innerHTML,
                    capturo: table.rows[i].cells[7].innerHTML,
                    Ultima_Actualizacion: table.rows[i].cells[7].innerHTML,
                    typeImage: "null",
                    nameImage: "null",
                    image: "null",
                    imagebase64:"null"
                }
            });
        }

    }

    return objetos;
}
//funciones para validar si el folio 911 nuevo ingresado ya se encuentra en la base de datos o no
const validateFolio911 = async (folio911_buscar)=> {
    var Valido911 = "ñ";
    if(folio911_buscar.length > 0){
    
        TodosFolios = await getAllFolio911();
        const result = TodosFolios.find(element => element.Folio_911 == folio911_buscar);
        
        if (result){
            
            Valido911="El Folio 911 ya se capturo"
        }else{
            Valido911=""
        }
    }
    return Valido911;
}
const getAllFolio911 = async () => {
    try {
        const response = await fetch(base_url_js + 'GestorCasos/getTodos911', {
            method: 'POST'
        });
        const data = await response.json();
        return data;
        
    } catch (error) {
        console.log(error);
    }
}
//funcion para generar foto en base64 de la tabla
async function encodeFileAsBase64URL(file) {
    if (file.size > 8 * 1024 * 1024) { // 8 MB en bytes
        throw new Error('El archivo excede el tamaño máximo de 8 MB.');
    }
    return new Promise((resolve) => {
        const reader = new FileReader();
        reader.addEventListener('loadend', () => {
            resolve(reader.result);
        });
        reader.readAsDataURL(file);
    });
};
function changeIdentificacionVI(){
    let radioHabilitado = document.getElementsByName('Identificacion_VI');
    if(radioHabilitado[0].checked){//si tiene involucrados
        document.getElementById('div_vehInvolucrados').classList.remove('mi_hide');
    }else if(radioHabilitado[1].checked){//no tiene involucrados
        document.getElementById('div_vehInvolucrados').classList.add('mi_hide');
    }
}
function changeIdentificacionI(){
    let radioHabilitado = document.getElementsByName('Identificacion_I');
    if(radioHabilitado[0].checked){//si tiene involucrados
        document.getElementById('div_responsables').classList.remove('mi_hide');
    }else if(radioHabilitado[1].checked){//no tiene involucrados
        document.getElementById('div_responsables').classList.add('mi_hide');
    }
}
const ValidaCoordY = async (valor)  =>{/// Para la coordenada Y
    let bandera =""
    if(valor.length>0){
        let contador=0;
        for (let i = 0; i < valor.length; i++) {
            if(valor[i]=='.'){
                contador++;
            }
        }

        switch(contador){
            case 1: bandera='';break;
            case 0: bandera='Falta el punto decimal';break;
            default: bandera='El Numero de puntos decimales es mayor a 1';
        }
        if(valor[2]!='.'){bandera = bandera + ' Error en el punto decimal ';}
    }else{
         bandera = 'Coordenada Y requerida'
    }

    return bandera.trim();
}

const ValidaCoordX = async (valor)  =>{/// Para la coordenada Y
    
    let bandera =""
    if(valor.length>0){
        let contador=0;
        let contadornegativo=0;
        for (let i = 0; i < valor.length; i++) {
            if(valor[i]=='.'){
                contador++;
            }
        }
        switch(contador){
            case 1: bandera='';break;
            case 0: bandera='Falta el punto decimal';break;
            default: bandera='El Numero de puntos decimales es mayor a 1';
        }
        for (let i = 0; i < valor.length; i++) {
            if(valor[i]=='-'){
                contadornegativo++;
            }
        }
        switch(contadornegativo){
            case 1: bandera = bandera+'';break;
            case 0: bandera = bandera +' Falta el simbolo de negativo';break;
            default: bandera= bandera + ' El Numero de simbolos negativos es mayor a uno';
        }
        if(valor[0]!='-'){bandera = bandera + ' Ingrese el simbolo negativo al principio de la coordenada Y';}
        if(valor[3]!='.'){bandera = bandera + ' Error en el punto decimal ';}
       
    }else{
        bandera = 'Coordenada X requerida'
    }
    return bandera.trim();
}