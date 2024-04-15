/*-----------------------------------------------ESTE ARCHIVO DE JAVASCRIPT ES PARA LA FUNCIONALIDADES DE LA EDICION DE EVENTOS TAB PRINCIPAL -------------------------------------------------------------------------*/
var data = document.getElementById('datos_principales')
var Folio911Error = document.getElementById('911_principalesError')
var alertError = document.getElementById('alert_error')
var RecepcionError = document.getElementById('recepcion_error')
var ZonaError = document.getElementById('zona_error')
var VectorError = document.getElementById('vector_error')
var ColoniaError = document.getElementById('Colonia_principales_error')
var CalleError = document.getElementById('Calle_principales_error')
var Calle2Error = document.getElementById('Calle2_principales_error')
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
var Folio911oculto = document.getElementById('911_principales_oculto')
var banderafotosVP=true;

document.getElementById('btn_principal').addEventListener('click', async function(e) {//Funcion que se dispara con el boton guardadr para guardar los datos principales
    e.preventDefault()
    var myFormData = new FormData(document.getElementById('datos_principales'))
    var band = []
    var FV = new FormValidator()
    var i = 0
    Folio_infra = document.getElementById('folio_infra_principales').value;
    Elemento_Captura=document.getElementById('captura_principales').value;
    myFormData.append('Folio_infra', Folio_infra );
    myFormData.append('captura_principales', Elemento_Captura );
    if (myFormData.get('fuente_principales') == 'LLAMADA AL 911'){//el folio 911 se requiere si fue una llamada al 911
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
   if(myFormData.get('911_principales').length>0){//valida que el folio no este asociado anteriormente
        valido911= await validateFolio911(myFormData.get('911_principales'))
        if(valido911!=""){
            if(myFormData.get('911_principales')==Folio911oculto.value){
                Folio911Error.innerText = '';
            }else{
                band[i++] = Folio911Error.innerText = valido911

            }
        }else{
            Folio911Error.innerText = '';
        }

    }
    if (myFormData.get('violencia_principales1') == 'NA'){//valida que se capturen los datos requeridos
        band[i++] = OpcionViolenciaError.innerText = FV.validate(myFormData.get('violencia_principales1'), 'required')
    }else{
        if (myFormData.get('violencia_principales1') == 'CON VIOLENCIA'){
            OpcionViolenciaError.innerText = '';
            if (myFormData.get('violencia_principales') == 'NA'){
                band[i++] = ViolenciaError.innerText = FV.validate(myFormData.get('violencia_principales'), 'required')
            }else{
                ViolenciaError.innerText = '';
            }
        }else{
            if (myFormData.get('violencia_principales1') == 'SIN VIOLENCIA'){
                OpcionViolenciaError.innerText = '';
                if (myFormData.get('sviolencia_principales') == 'NA'){
                    band[i++] = SViolenciaError.innerText = FV.validate(myFormData.get('sviolencia_principales'), 'required')
                }else{
                    SViolenciaError.innerText = '';
                }
            }
        }
    }
    if(document.getElementById('Estatus_Evento').value!="FUERA DE JURISDICCION"){//si en caso que el estado del evento se encuentre fuera de jurisdiccion no es requerido el domicilio
        band[i++] = ZonaError.innerText = FV.validate(myFormData.get('zona'), 'required ')
        band[i++] = VectorError.innerText = FV.validate(myFormData.get('vector'), 'required ')
        band[i++] = ColoniaError.innerText = FV.validate(myFormData.get('Colonia'), 'required ')
        band[i++] = CalleError.innerText = FV.validate(myFormData.get('Calle'), 'required ')
        band[i++] = CPError.innerText = FV.validate(myFormData.get('CP'), 'required | numeric')
        band[i++] = cordYError.innerText = FV.validate(myFormData.get('cordY'), 'required | numeric')
        band[i++] = cordXError.innerText = FV.validate(myFormData.get('cordX'), 'required | numeric')
    }

    
    if(inputdel.value == 'otro' && inputotro.value == '')
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


    let radiodetencion = document.getElementsByName('Detencion');
    if(radiodetencion[1].checked){
        myFormData.append('Ubo_Detencion', 0);

    }else{
        myFormData.append('Ubo_Detencion', 1);
        (document.getElementsByName('Detencion_Por_Info_Io')[0].checked)?myFormData.append('Detencion_Por_Info_Io', 1):myFormData.append('Detencion_Por_Info_Io', 0);
        if(document.getElementsByName('ubicacion_puebla')[0].checked){
            myFormData.append('Foraneo', 0)
            myFormData.append('Estado', 'PUEBLA')
            myFormData.append('Municipio', 'PUEBLA')
        }else{
            myFormData.append('Foraneo', 1)
        }

        band[i++] = document.getElementById('Colonia_Det_principales_error').innerText = FV.validate(myFormData.get('Colonia_Det'), 'required ')
        band[i++] = document.getElementById('Calle_Det_principales_error').innerText = FV.validate(myFormData.get('Calle_Det'), 'required ')
        band[i++] = document.getElementById('NoExt_Det_principales_error').innerText = FV.validate(myFormData.get('no_Ext_Det'), 'required | numeric')
        band[i++] = document.getElementById('CP_Det_principales_error').innerText = FV.validate(myFormData.get('CP_Det'), 'required | numeric')
        band[i++] = document.getElementById('cordY_Det_principales_error').innerText = FV.validate(myFormData.get('cordY_Det'), 'required | numeric')
        band[i++] = document.getElementById('cordX_Det_principales_error').innerText = FV.validate(myFormData.get('cordX_Det'), 'required | numeric')
    }

    //SE EVALUA SI EXISTEN DATOS EN LAS TABLAS DE PRESUNTOS VEHICULOS Y PRESUNTOS RESPONSABLES
    rowsTableVehiculos = document.getElementById('contarVehiculos').rows.length;
    if(rowsTableVehiculos > 0){
        var vehiculosForm = await readTableVehiculos();
        myFormData.append('vehiculos_table', JSON.stringify(vehiculosForm));
    }

    rowsTableRes = document.getElementById('contarRes').rows.length;
    if(rowsTableRes > 0){
        var ResponsableForm = await readTableRes();
        myFormData.append('responsables_table', JSON.stringify(ResponsableForm));
    }
    //se comprueban todas las validaciones
    var success = true
    band.forEach(element => {
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
        button2.classList.add('disabled-link');
        $('#ModalCenterFoto').modal('show');
        //Agregamos los campos proporcionados  que se almacenaran
        myFormData.append('Estatus_Evento',document.getElementById('Estatus_Evento').value)
        if(document.getElementById('Semana').value==""){
            myFormData.append('Semana',0)
        }
        
      
        if(document.getElementById('Habilitado_question1').checked){
            myFormData.append('Habilitado','HABILITADO')
            myFormData.append('FechaHora_Activacion',document.getElementById('fechahora_activacion_principales').value)
            myFormData.append('Quien_Habilito',document.getElementById('quienhabilito').value)
        }else{
            myFormData.append('Habilitado','DESHABILITADO')
            myFormData.append('FechaHora_Activacion','')
            myFormData.append('Quien_Habilito','')
        }
        myFormData.append('ClaveSeguimiento',document.getElementById('clave_asignacion_seguimiento').value)
        myFormData.append('CSviolencia', document.getElementById('violencia_principales1').value)
        if(document.getElementById('violencia_principales1').value=="SIN VIOLENCIA"){
            myFormData.append('violencia_principales', document.getElementById('sviolencia_principales').value)

        }else{
            myFormData.append('violencia_principales', document.getElementById('violencia_principales').value)
        }
        let file = document.getElementById('filePDFEvento');
        if (file.files[0] != undefined) {
            myFormData.append('file_pdf', file.files[0]);
            myFormData.append('nombre_pdf', "Evento"+document.getElementById('folio_infra_principales').value+".pdf");
        }else{
            myFormData.append('nombre_pdf', banderaPdf);
        }
        myFormData.append('banderafotosVP', banderafotosVP);//bandera para avisarle al controlador que si debe hacer respaldo
        fetch(base_url_js + 'GestorCasos/updateEvento', {//realiza el fetch para actualizar los datos
            method: 'POST',
            body: myFormData
        })

        .then(res => res.json())

        .then(data => {//obtine  respuesta del modelo
            button2.innerHTML = `Guardar Datos principales`;
            button2.classList.remove('disabled-link');//se vuelve activar la funcion del boton 
            $('#ModalCenterFoto').modal('hide');//se quita la imagen 
            banderafotosVP=true;
            console.log(data)
            if (!data.status) {
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
                    messageError = '<div class="alert alert-danger text-center" role="alert">Por favor, revisa nuevamente el formulario</div>'
                }
                console.log(data.error_sql)
                msg_principalesError.innerHTML = messageError
                window.scroll({
                    top: 0,
                    left: 100,
                    behavior: 'smooth'
                });

            } else {//si todo salio bien
                Folio911Error.innerText = ''
                alertError.innerText = ''
                RecepcionError.innerText = ''
                ZonaError.innerText = ''
                VectorError.innerText = ''
                ColoniaError.innerText = ''
                CalleError.innerText = ''
                Calle2Error.innerText = ''
                CPError.innerText = ''
                cordYError.innerText = ''
                cordXError.innerText = ''
                ActivavionError.innerText = ''
                TablaError.innerText = ''
                fuenteError.innerText = '';

                window.scroll({
                    top: 0,
                    left: 100,
                    behavior: 'smooth'
                });
                alertaM()//Si todo salio bien actualiza los datos arroja un mensaje satisfactorio
            }
        })
      

    } else { //si no, se muestran los campos requeridos en pantalla

        msg_principalesError.innerHTML = '<div class="alert alert-danger text-center" role="alert">Por favor, revisa nuevamente el formulario</div>'
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

function alertaM() {/// todo bien en la edicion
    
    msg_principalesError.innerHTML = `<div class="alert alert-success text-center" role="success">Datos Actualizados correctamente.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
            <span aria-hidden="true">&times;</span>
        </button>
    </div>`;
    window.scroll({
        top: 0,
        left: 100,
        behavior: 'smooth'
    });
    refrescarDOM()
}
///-----------------------------------funciones para leer los datos de las tablas 
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
//funciones para leer las fotos en base64 en las tablas
const toDataURL =async url => fetch(url)
    .then(res => res.blob())
    .then(blob => new Promise((resolve) => {
        let reader = new FileReader();
        reader.addEventListener('loadend', async() => {
            resolve(reader.result);
        });
        reader.readAsDataURL(blob);
    }))
//funcion para generar foto en base64 de la tabla
async function encodeFileAsBase64URL(file) {
    return new Promise((resolve1) => {
        let reader2 = new FileReader();
        reader2.addEventListener('loadend', () => {
            resolve1(reader2.result);
        });
        reader2.readAsDataURL(file);
    });
};

///Validaciones de campos 
function valideMultiples(evt) {//FUNCION QUE VALIDA LA INSERCION DE DATOS VALIDOS EN EL FORMULARIO
    var code = (evt.which) ? evt.which : evt.keyCode;
    let codigos = [8,32,44,193,201,205,211,218,225,233,237,243,250,209,241]//TECLAS DELETE,ESPACIO Y LETRAS ACENTUADAS
    if ((code >= 65 && code <= 90)||(code >= 97 && code <= 122)||(code >= 48 && code <= 57)) { //TECLAS DE NUMEROS
        return true;
    } else { // OTRAS TECLAS.
        let codigo_valido = codigos.find(element=>element==code);
        if(codigo_valido){
            return true;
        }else{
            return false;
        }
    }
}
function valideKey(evt) {//FUNCION QUE VALIDA LA INSERCION DE SOLO NUMEROS 
    var code = (evt.which) ? evt.which : evt.keyCode;
    let codigos = [8]//TECLA DELETE
    if ((code >= 65 && code <= 90)||(code >= 97 && code <= 122)||(code >= 48 && code <= 57)) { //TECLAS DE NUMEROS
        return true;
    } else { // OTRAS TECLAS.
        let codigo_valido = codigos.find(element=>element==code);
        if(codigo_valido){
            return true;
        }else{
            return false;
        }
    }
}

function filtraCoordNegativa(event) {/// Para el pegado
    var valor = event.target.value;
    var nuevoValor = "";
    for (var i = 0; i < valor.length; i++) {
        var char = valor[i];       
        if (/[0-9-.]/.test(char)) {
            nuevoValor += char;
        }
    }
    event.target.value = nuevoValor;
}
function filtraCoordPositiva(event) {/// Para el pegado
    var valor = event.target.value;
    var nuevoValor = "";
    for (var i = 0; i < valor.length; i++) {
        var char = valor[i];       
        if (/[0-9.]/.test(char)) {
            nuevoValor += char;
        }
    }
    event.target.value = nuevoValor;
}
function filtrarAlfaNumericos(event) {/// Para el pegado
    var valor = event.target.value;
    var nuevoValor = "";
    for (var i = 0; i < valor.length; i++) {
        var char = valor[i];       
        if (/[a-zA-Z0-9-\sáéíóúÁÉÍÓÚÑñ]/.test(char)) {
            nuevoValor += char;
        }
    }
    event.target.value = nuevoValor;
}
function filtrarSoloLetras(event) {/// Para el pegado
    var valor = event.target.value;
    var nuevoValor = "";
    for (var i = 0; i < valor.length; i++) {
        var char = valor[i];       
        if (/[a-zA-Z\sáéíóúÁÉÍÓÚÑñ]/.test(char)) {
            nuevoValor += char;
        }
    }
    event.target.value = nuevoValor;
}
function filtrarSoloNumeros(event) {
    var valor = event.target.value;
    var nuevoValor = "";
    for (var i = 0; i < valor.length; i++) {
        var char = valor[i];       
        if (/[0-9]/.test(char)) {
            nuevoValor += char;
        }
    }
    event.target.value = nuevoValor;
}
function filtrarLetrasComa(event) {/// Para el pegado
    var valor = event.target.value;
    var nuevoValor = "";
    for (var i = 0; i < valor.length; i++) {
        var char = valor[i];       
        if (/[a-zA-Z0-9,\sáéíóúÁÉÍÓÚÑñ]/.test(char)) {
            nuevoValor += char;
        }
    }
    event.target.value = nuevoValor;
}

