/*-------------FUNCIONES DE INICIALIZACION PARA VISTA---------*/
var datosRemisiones;
document.addEventListener('DOMContentLoaded',async() => {
    fechahora_captura = document.getElementById('fechahora_captura_principales');
    fechahora_captura.value = await getFechaActual();
    await getRemisiones();
    fechahora_captura.disabled= true;
    captura=document.getElementById('captura_dato_entrevista');
    captura.disabled= true;
    await RecargaGrupoDelictivoSeguimiento();
    //console.log(datosRemisiones)
});
async function  RecargaGrupoDelictivoSeguimiento() {//REFRESCA EL SELECTOR DEL CON EL GRUPO DELICTIVO   
    // Obtener referencia al elemento select
    var select = document.getElementById("Id_Banda_Seguimiento");
    let GruposDelicitivos = await getGrupoDelictivoSeguimiento();
    // Generar las opciones del select
    for (var i = 0; i < GruposDelicitivos.length; i++) {
        option = document.createElement("option");
        option.text = GruposDelicitivos[i]['Nombre_grupo_delictivo'];
        option.value = GruposDelicitivos[i]['Id_Seguimiento'];
        select.add(option);
    }
}
const getGrupoDelictivoSeguimiento = async () => {//FUNCION QUE OBTINE LOS DATOS PRINCIPALES DEL SEGUIMIENTO
    try {
        const response = await fetch(base_url_js + 'Entrevistas/getGrupoDelictivoSeguimiento', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
} 
const getFechaActual =async() => { //Funcion para Obtener la fecha en el formato sql admitido
    const str = new Date().toLocaleString('es-MX', );
    auxhoy=str
    separadas = auxhoy.split(',')
    aux=separadas[1] 
    aux=aux.substring(1, 6)
    unir=getFecha();
    unir=unir+' '+aux;
    return unir;
} 
const getFecha= () => { //Funcion para Obtener la fecha actual en el formato para el html
    var options = {//Formato DD/MM/YYYY
        year: "numeric",
        month: "2-digit",
        day: "2-digit"
    };
    const str = new Date().toLocaleString('es-MX', options );
    invertir=str.split('/')
    invertir= invertir.reverse();
    unir=invertir.join('-')
    return unir;
}

const myFormData =  new FormData();

const changeRemision = () =>{//FUNCION QUE HABILITA LA BUSQUEDA DEL NUMERO REMISION
    let radioHabilitado = document.getElementsByName('Remision_Si_No');
    if(radioHabilitado[0].checked){//SI TIENE REMISION
        document.getElementById('id_Remision_panel').classList.remove('mi_hide');
    }else if(radioHabilitado[1].checked){//NO TIENE REMISION
        document.getElementById('id_Remision_panel').classList.add('mi_hide');
    }
}
const inputRemisiones = document.getElementById('id_remision');
const error_remision= document.getElementById('error_remision');
inputRemisiones.addEventListener('input', async() => { 
    const arr = datosRemisiones.map( r => ({ label: `REMISION:${r.No_Remision} NOMBRE DETENIDO:${r.Nombre_completo} DETENIDO POR:${r.Detenido_por}` , value: `${r.No_Remision}`}))
    autocomplete({
        input: id_remision,
        fetch: function(text, update) {
            text = text.toLowerCase();
            const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
            update(suggestions);
        },
        onSelect: function(item) {
            id_remision.value = item.value;
            onFormRemisionSubmit()
        }
    }); 
});
const getRemisiones=async()=>{
    fetch(base_url_js + 'Entrevistas/getRemisiones', {
            method: 'POST',
            body: myFormData
    })
    .then(res => res.json())
    .then(data => {

        datosRemisiones=data;
    })
    .catch(err => alert(`Ha ocurrido un error al obtener las Remisiones.\nCódigo de error: ${ err }`))
}
const onFormRemisionSubmit = async() => {//FUNCION QUE EVALUA SI LA ENTRADA DE NUMERO DE REMISION EXISTE EN EL CATALOGO
    InfoRemision= await getInfoRemision(inputRemisiones.value)
    //console.log(InfoRemision);
    error_remision.innerHTML='';
    if(InfoRemision!=''){
        llenadatosRemision(InfoRemision);
        document.getElementById('id_remision').value='';
    }else{
        error_remision.innerHTML="NO EXISTE ESE NUMERO DE REMISION INGRESE OTRO"

    }
}
const getInfoRemision = async (No_Remision) => {//FUNCION PARA OBTENER LA INFORMACION DE LA REMISION SELECCIONADA
    try {
        myFormData.append('No_Remision',No_Remision);
        const response = await fetch(base_url_js + 'Entrevistas/getInfoRemision', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const llenadatosRemision = async ( data ) => {//FUNCION QUE LLENA LOS DATOS DE LA REMISION DEL FORMULARIO
    let nombre=document.getElementById('nombre');
    let ap_paterno = document.getElementById('ap_paterno');
    let ap_materno = document.getElementById('ap_materno');
    let curp = document.getElementById('curp');
    let num_tel = document.getElementById('num_tel');
    let FechaNacimiento_principales = document.getElementById('FechaNacimiento_principales');
    let edad = document.getElementById('edad_principales');
    let detenido_por = document.getElementById('detenido_por');
    let asociado_a = document.getElementById('asociado_a');
    let alias = document.getElementById('alias');
    let remisiones = document.getElementById('remisiones');
    let banda = document.getElementById('banda');

    let colonia_dom = document.getElementById('colonia_dom');
    let calle_dom = document.getElementById('calle_dom');
    let numExt_dom = document.getElementById('numExt_dom');
    let numInt_dom = document.getElementById('numInt_dom');

    let colonia_detencion = document.getElementById('colonia_detencion');
    let calle_detencion = document.getElementById('calle_detencion');
    let calle2_detencion = document.getElementById('calle2_detencion');
    let numExt_detencion = document.getElementById('numExt_detencion');

    nombre.value=data.Nombre;
    ap_paterno.value=data.Ap_Paterno;
    ap_materno.value=data.Ap_Materno;
    curp.value=(data.CURP!='SD')?data.CURP:'';
    num_tel.value = (data.Telefono!='SD')?data.Telefono:'';
    FechaNacimiento_principales.value=(data.Fecha_Nacimiento!='')?data.Fecha_Nacimiento:'';

    if(document.getElementById('FechaNacimiento_principales').value!=''){
        let fechaActual = new Date();
        // Obtiene los componentes de la fecha
        let dia = fechaActual.getDate();
        let mes = fechaActual.getMonth() + 1; // Los meses comienzan en 0, por lo que se suma 1
        let anio = fechaActual.getFullYear();
        // Formatea la fecha en el formato deseado (opcional)
        let fechaFormateada = anio + '-' + mes + '-' + dia;
        let fecha1 = new Date(document.getElementById('FechaNacimiento_principales').value);
        let fecha2 = new Date(fechaFormateada);
        let diferenciaMs = fecha2.getTime() - fecha1.getTime();
        // Convierte la diferencia en años
        let diferenciaAnios = Math.floor(diferenciaMs / 31536000000);
        edad.value = diferenciaAnios; 
    }

    banda.value=(data.Banda!='SD')?data.Banda:'';
    detenido_por.value=(data.Detenido_por!='SD')?data.Detenido_por:'';
    asociado_a.value=(data.Asociado_A!='SD')?data.Asociado_A:'';
    alias.value=(data.Alias!='SD')?data.Alias:'';
    remisiones.value=data.No_Remision;

    colonia_dom.value=(data.Colonia_Domicilio!='SD')?data.Colonia_Domicilio:'';
    calle_dom.value=(data.Calle_Domicilio!='SD')?data.Calle_Domicilio:'';
    numExt_dom.value=(data.No_Exterior_Domicilio!='SD')?data.No_Exterior_Domicilio:'';
    numInt_dom.value=(data.No_Interior_Domicilio!='SD')?data.No_Interior_Domicilio:'';

    colonia_detencion.value=(data.Colonia_Detencion!='SD')?data.Colonia_Detencion:'';
    calle_detencion.value=(data.Calle_1_Detencion!='SD')?data.Calle_1_Detencion:'';
    calle2_detencion.value=(data.Calle_2_Detencion!='SD')?data.Calle_2_Detencion:'';
    numExt_detencion.value=(data.No_Ext_Detencion!='SD')?data.No_Ext_Detencion:'';
}
/*------------------------- FUNCIONES QUE VALIDAN LAS ENTRADAS DE INFORMACION DEL FORMULARIO----------------------------------*/
function valida(evt) {//FUNCION QUE VALIDA LA INSERCION DE SOLO LETRAS Y VOCALES CON ASCENTOS
    var code = (evt.which) ? evt.which : evt.keyCode;
    let codigos = [8,32,193,201,205,211,218,225,233,237,243,250,209,241]//TECLAS DELETE,ESPACIO , LETRAS ACENTUADAS Y Ñ
    if ((code >= 65 && code <= 90)||(code >= 97 && code <= 122)) { //TECLAS DE NUMEROS
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
function validePanelRemisiones(evt) {//FUNCION QUE VALIDA LA INSERCION DE SOLO NUMEROS y comas
    var code = (evt.which) ? evt.which : evt.keyCode;
    if (code == 8 || code == 44) { //TECLA DELETE
        return true;
    } else if (code >= 48 && code <= 57) { //TECLAS DE NUMEROS
        return true;
    } else { // OTRAS TECLAS.
        return false;
    }
}
function validaCurp(evt) {//FUNCION QUE VALIDA LA INSERCION DE SOLO NUMEROS 
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
function valideKey(evt) {//FUNCION QUE VALIDA LA INSERCION DE SOLO NUMEROS 
    var code = (evt.which) ? evt.which : evt.keyCode;
    if (code == 8) { //TECLA DELETE
        return true;
    } else if (code >= 48 && code <= 57) { //TECLAS DE NUMEROS
        return true;
    } else { // OTRAS TECLAS.
        return false;
    }
}
function valideMultiplesDatos(evt) {//FUNCION QUE VALIDA LA INSERCION DE SOLO NUMEROS 
    var code = (evt.which) ? evt.which : evt.keyCode;
    let codigos = [8,32,44,45,47,209,241]//TECLA DELETE
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

/*FUNCIONES DE AUTOCOMPLETE */
const getAllColonias = async () => {//FUNCION QUE OBTIENE TODAS COLONIAS
    try {
        const response = await fetch(base_url_js + 'Catalogos/getColonias', {
            method: 'POST'
        });
        const data = await response.json();
        return data;
        
    } catch (error) {
        console.log(error);
    }
}

const getAllCalles = async () => {//FUNCION QUE OBTIENE TODAS LAS CALLES
    try {
        const response = await fetch(base_url_js + 'Catalogos/getAllCalles', {
            method: 'POST'
        });
        const data = await response.json();
        return data;
        
    } catch (error) {
        console.log(error);
    }
}

const inputCalle = document.getElementById('calle_dom');
const myFormData_calle =  new FormData();
inputCalle.addEventListener('input', () => {//FUNCION AUTOCOMPLETE DE LA CALLE
    myFormData_calle.append('termino', inputCalle.value)
    fetch(base_url_js + 'Catalogos/getCalles', {
            method: 'POST',
            body: myFormData_calle
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `${r.Calle}`, value: `${r.Calle}` }))
        autocomplete({
            input: calle_dom,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {
                calle_dom.value = item.label;
            }
        }); 
    })
    .catch(err => alert(`Ha ocurrido un error al obtener las colonias.\nCódigo de error: ${ err }`))
});

const inputCalle2 = document.getElementById('calle2_dom');
const myFormData_calle2 =  new FormData();
inputCalle2.addEventListener('input', () => {//FUNCION AUTOCOMPLETE DE LA CALLE 2
    myFormData_calle2.append('termino', inputCalle2.value)
    fetch(base_url_js + 'Catalogos/getCalles', {
            method: 'POST',
            body: myFormData_calle2
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `${r.Calle}`, value: `${r.Calle}` }))
        autocomplete({
            input: calle2_dom,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {
                calle2_dom.value = item.label;
            }
        }); 
    })
    .catch(err => alert(`Ha ocurrido un error al obtener las colonias.\nCódigo de error: ${ err }`))
});
const inputColonia = document.getElementById('colonia_dom');
const myFormDataM =  new FormData();
inputColonia.addEventListener('input', () => {//FUNCION AUTOCOMPLETE DE LA COLONIA
    myFormDataM.append('termino', inputColonia.value)
    fetch(base_url_js + 'Catalogos/getColonia', {
            method: 'POST',
            body: myFormDataM
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `${r.Tipo} ${r.Colonia}`, value: `${r.Colonia}`, tipo: r.Tipo }))
        autocomplete({
            input: colonia_dom,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {
                colonia_dom.value = item.label;
            }
        }); 
    })
    .catch(err => console.log(`Ha ocurrido un error al obtener las colonias.\nCódigo de error: ${ err }`))
});

const inputCalleLugarDetencion = document.getElementById('calle_detencion');
inputCalleLugarDetencion.addEventListener('input', () => {//FUNCION AUTOCOMPLETE DE LA CALLE
    myFormData_calle.append('termino', inputCalleLugarDetencion.value)
    fetch(base_url_js + 'Catalogos/getCalles', {
            method: 'POST',
            body: myFormData_calle
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `${r.Calle}`, value: `${r.Calle}` }))
        autocomplete({
            input: calle_detencion,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {
                calle_detencion.value = item.label;
            }
        }); 
    })
    .catch(err => alert(`Ha ocurrido un error al obtener las colonias.\nCódigo de error: ${ err }`))
});

const inputCalle2LugarDetencion = document.getElementById('calle2_detencion');
inputCalle2LugarDetencion.addEventListener('input', () => {//FUNCION AUTOCOMPLETE DE LA CALLE 2
    myFormData_calle2.append('termino', inputCalle2LugarDetencion.value)
    fetch(base_url_js + 'Catalogos/getCalles', {
            method: 'POST',
            body: myFormData_calle2
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `${r.Calle}`, value: `${r.Calle}` }))
        autocomplete({
            input: calle2_detencion,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {
                calle2_detencion.value = item.label;
            }
        }); 
    })
    .catch(err => alert(`Ha ocurrido un error al obtener las colonias.\nCódigo de error: ${ err }`))
});

const inputColoniaLugarDetencion = document.getElementById('colonia_detencion');
inputColoniaLugarDetencion.addEventListener('input', () => {//FUNCION AUTOCOMPLETE DE LA COLONIA
    myFormDataM.append('termino', inputColoniaLugarDetencion.value)
    fetch(base_url_js + 'Catalogos/getColonia', {
            method: 'POST',
            body: myFormDataM
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `${r.Tipo} ${r.Colonia}`, value: `${r.Colonia}`, tipo: r.Tipo }))
        autocomplete({
            input: colonia_detencion,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {
                colonia_detencion.value = item.label;
            }
        }); 
    })
    .catch(err => console.log(`Ha ocurrido un error al obtener las colonias.\nCódigo de error: ${ err }`))
});

const FechaNacimiento_principales = document.getElementById('FechaNacimiento_principales');
FechaNacimiento_principales.addEventListener('input', () => {//Funcion para sacar la edad
    let edad = document.getElementById('edad_principales');
    if(document.getElementById('FechaNacimiento_principales').value!=''){
        let fechaActual = new Date();
        // Obtiene los componentes de la fecha
        let dia = fechaActual.getDate();
        let mes = fechaActual.getMonth() + 1; // Los meses comienzan en 0, por lo que se suma 1
        let anio = fechaActual.getFullYear();
        // Formatea la fecha en el formato deseado (opcional)
        let fechaFormateada = anio + '-' + mes + '-' + dia;
        let fecha1 = new Date(document.getElementById('FechaNacimiento_principales').value);
        let fecha2 = new Date(fechaFormateada);
        let diferenciaMs = fecha2.getTime() - fecha1.getTime();
        // Convierte la diferencia en años
        let diferenciaAnios = Math.floor(diferenciaMs / 31536000000);
        edad.value = diferenciaAnios; 
    }
});

/*--------------- FUNCIONES PARA VALIDAR Y DE GUARDAR LA PERSONA DETENIDA ENTREVISTADA NUEVA -------------------------*/
var data = document.getElementById('datos_principales_Detenido_Entrevistado')
var msg_principalesError = document.getElementById('msg_principales')
var nombre_error = document.getElementById('nombre_error');
var ap_paterno_error = document.getElementById('ap_paterno_error');
var ap_materno_error = document.getElementById('ap_materno_error');
const ResetLetreros = async () =>{//FUNCION PARA RESETEAR LOS LETREROS DE LA VISTA
    nombre_error.innerText ="";
    ap_paterno_error.innerText ="";
    ap_materno_error.innerText ="";
}
document.getElementById('btn_detenido_entrevista_principal').addEventListener('click', async function(e) {//Funcion que valida lo ingresado 
    e.preventDefault()
    var myFormData = new FormData(data)
    var band = []
    var FV = new FormValidator()
    var i = 0
    await ResetLetreros();
    band[i++] = nombre_error.innerText = FV.validate(myFormData.get('nombre'), 'required ');
    band[i++] = ap_paterno_error.innerText = FV.validate(myFormData.get('ap_paterno'), 'required ');
    band[i++] = ap_materno_error.innerText = FV.validate(myFormData.get('ap_materno'), 'required ');

    //se comprueban todas las validaciones
    var success = true
    band.forEach(element => {//recorre todas la banderas si todas son vacias procede a guardar los datos ya que no existe ninguna restriccion 
        success &= (element == '') ? true : false
    })
    if (success) { //si todo es correcto se envía form
        myFormData.append('fechahora_captura_principales',document.getElementById('fechahora_captura_principales').value)
        myFormData.append('captura_dato_entrevista',document.getElementById('captura_dato_entrevista').value.toUpperCase())
        myFormData.append('Id_Seguimiento',document.getElementById('Id_Banda_Seguimiento').value);
        myFormData.append('zona',document.getElementById('zona').value);
        if(document.getElementById('Id_Banda_Seguimiento').value=='SD'){
            myFormData.append('Capturado_Seguimiento','NO');
        }else{
            let radioHabilitado = document.getElementsByName('Capturada_Si_No');
            if(radioHabilitado[0].checked){//YA ESTA CAPTURADO EN RED
                myFormData.append('Capturado_Seguimiento','SI');
            }else if(radioHabilitado[1].checked){//NO ESTA CAPTURADO EN RED
                myFormData.append('Capturado_Seguimiento','NO');
                
            }
        }
        button2 = document.getElementById('btn_detenido_entrevista_principal')
        button2.innerHTML = `Guardando
                            <div class="spinner-grow spinner-grow-sm" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>`;
        button2.classList.add('disabled-link');//se desactiva el boton de guardar hasta que halla una respuesta del controlador
        $('#ModalCenterFoto').modal('show');//Mostramos una imagen completa en pantalla hasta que halla una respuesta del controlador
        //Agregamos los campos proporcionados  que se almacenaran

        fetch(base_url_js + 'Entrevistas/insertEntrevistasFetch', {//realiza el fetch para insertar los datos
            method: 'POST',
            body: myFormData
        })
        .then(res => res.json())

        .then(data => {//obtine respuesta del controlador
            button2.innerHTML = `Guardar`;
            button2.classList.remove('disabled-link');//se vuelve activar la funcion del boton 
            $('#ModalCenterFoto').modal('hide');//se quita la imagen 
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

                window.scroll({
                    top: 0,
                    left: 100,
                    behavior: 'smooth'
                });//mueve la vista hasta arriba de la pagina
                
                alerta()//Si todo se valido bien y se inserto correctamente se arroja un mensaje satisfactorio y redirige a la pestaña principal del gestor de casos
            }
        })
        

    } else { //si no, se muestran errores en pantalla
        msg_principalesError.innerHTML = `<div class="alert alert-danger text-center" role="alert">POR FAVOR, REVISA NUEVAMENTE EL FORMULARIO HAY CAMPOS REQUERIDOS
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>`;
        window.scroll({
            top: 0,
            left: 100,
            behavior: 'smooth'
        });
    }

    for (var pair of myFormData.entries()) {
        console.log(pair[0] + ', ' + pair[1]);
    }
})

async function alerta() {//FUNCION PARA AVISAR QUE TODO SALIO BIEN 

    await ConsultaPersonaRed();
    
    
    const BtGuardar =document.getElementById("btn_detenido_entrevista_principal");
    BtGuardar.setAttribute('disabled', '');
    msg_principalesError.innerHTML = `<div class="alert alert-success text-center" role="success">DATOS INSERTADOS CORRECTAMENTE. EN BREVE SERÁ REDIRIGIDO A LA PÁGINA PRINCIPAL
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>`;
    window.scroll({
        top: 0,
        left: 100,
        behavior: 'smooth'
    });
   ///setInterval(function() { window.location = base_url_js+"Entrevistas"; }, 6000);//FUNCION PARA REDIRIGIR A LA PAGINA PRINCIPAL DE SEGUIMIENTO
}
const Id_Banda_Seguimiento = document.getElementById('Id_Banda_Seguimiento');
Id_Banda_Seguimiento.addEventListener('input', () => {
    if(Id_Banda_Seguimiento.value!='SD'){
        document.getElementById('captura_sic').classList.remove('mi_hide');
    }else{
        
        document.getElementById('captura_sic').classList.add('mi_hide');
        document.getElementById('id_sic_2').checked=true;
        document.getElementById('id_sic_1').checked=false;
    }
});

const ConsultaPersonaRed = async() =>{
    let myFormDataConsulta = new FormData();//LEEMOS EL CONTENIDO DE LA TABLA DE PERSONAS 
    let nombre = document.getElementById('nombre').value.toUpperCase();
    let ap_paterno = document.getElementById('ap_paterno').value.toUpperCase();
    let ap_materno = document.getElementById('ap_materno').value.toUpperCase();
    
    myFormDataConsulta.append('Nombre',nombre.trim());
    myFormDataConsulta.append('Ap_paterno',ap_paterno.trim());
    myFormDataConsulta.append('Ap_materno',ap_materno.trim());

    fetch(base_url_js + 'Entrevistas/ConsultaPersonaFetch', {//realiza el fetch para consultar
        method: 'POST',
        body: myFormDataConsulta
    })

    .then(res => res.json())

    .then(data => {//obtiene respuesta del modelo
        if(Object.keys(data).length>0){
            cad = ""
            data.forEach(element => {
                let alto_impacto =(element.Alto_Impacto==1)?'UNA RED DE ALTO IMPACTO FOLIO: ':'UNA RED DE GABINETE FOLIO: ';
                cad += alto_impacto+element.Id_Seguimiento+", "+element.Nombre_grupo_delictivo+", "+element.Nombre+" "+element.Ap_Paterno+" "+element.Ap_Materno;  
            });
        
            coincidencia = "LA PERSONA TIENE UNA COINCIDENCIA EN "+cad+" FAVOR DE REVISAR ";
            ConsultaPersonaEntrevista(coincidencia);
        
        }else{
            ConsultaPersonaEntrevista("");
        }
    })
}

const ConsultaPersonaEntrevista = async(coincidencia) =>{
    console.log(coincidencia)
    let myFormDataConsulta = new FormData();//LEEMOS EL CONTENIDO DE LA TABLA DE PERSONAS 
    let nombre = document.getElementById('nombre').value.toUpperCase();
    let ap_paterno = document.getElementById('ap_paterno').value.toUpperCase();
    let ap_materno = document.getElementById('ap_materno').value.toUpperCase();
    let fecha = document.getElementById('fechahora_captura_principales').value;
    
    myFormDataConsulta.append('Nombre',nombre.trim());
    myFormDataConsulta.append('Ap_paterno',ap_paterno.trim());
    myFormDataConsulta.append('Ap_materno',ap_materno.trim());
    myFormDataConsulta.append('fecha',fecha);

    fetch(base_url_js + 'Entrevistas/ConsultaPersonaEFetch', {//realiza el fetch para consultar
        method: 'POST',
        body: myFormDataConsulta
    })

    .then(res => res.json())

    .then(data => {//obtiene respuesta del modelo
        if(Object.keys(data).length>0){
            cad = "";
            data.forEach(element => {
                cad += "UNA ENTREVISTA ANTERIOR CON EL FOLIO: "+element.Id_Persona_Entrevista+", "+element.Nombre+" "+element.Ap_Paterno+" "+element.Ap_Materno+" "+element.Alias;  
            });
            console.log(cad)
            Swal.fire({
                title: coincidencia + "LA PERSONA TIENE UNA COINCIDENCIA EN "+cad+" FAVOR DE REVISAR",
                icon: 'info',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'custom-confirm-btn'  // Clase CSS personalizada para el botón de confirmación
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed || result.isDismissed) {
                    // Llamar a la función que quieres ejecutar después de que el usuario presione "OK"
                    setTimeout(function() { window.location = base_url_js+"Entrevistas"; }, 2000);
                }
            }).catch(() => {
                // Llamar a la función personalizada si el usuario cierra la alerta
                setTimeout(function() { window.location = base_url_js+"Entrevistas"; }, 2000);
            });
        }else{
            if(coincidencia!=""){
                Swal.fire({
                    title: coincidencia,
                    icon: 'info',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'custom-confirm-btn'  // Clase CSS personalizada para el botón de confirmación
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed || result.isDismissed) {
                        // Llamar a la función que quieres ejecutar después de que el usuario presione "OK"
                    
                        setTimeout(function() { window.location = base_url_js+"Entrevistas"; }, 2000);
                    }
                }).catch(() => {
                    // Llamar a la función personalizada si el usuario cierra la alerta
                    setTimeout(function() { window.location = base_url_js+"Entrevistas"; }, 2000);
                });

            }else{
                setTimeout(function() { window.location = base_url_js+"Entrevistas"; }, 3000);

            }

        }
    })
    document.getElementById('datos_principales_Detenido_Entrevistado').reset()
}