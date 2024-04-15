/*----------------------------FUNCIONES DE ACTUALIZACION DE FOTOS -------------------------------------*/
function uploadFileDetenido(event) {//FUNCION PARA ACTUALIZAR LA IMAGEN 
    document.getElementById('msg_principales').innerHTML = '';
    if (event.currentTarget.classList.contains('uploadFileFotoDetenido')) {//FOTO DEL GRUPO DELICTIVO
        if (validateImage(event.target)) {
            const src = URL.createObjectURL(event.target.files[0]);
            createElementEntrevistaDetenido(src, 'File');
        } else {
            document.getElementById('msg_principales').innerHTML = '<div class="alert alert-warning text-center" role="alert">Verificar el archivo cargado.<br>Posibles errores: <br> - Archivo muy pesado (Máximo 8 megas). <br> -Extensión no aceptada (Extensiones aceptadas: jpeg, png, jpg, PNG).</div>';
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });
        }
    }
}
const validateImage = (image) => {//Valida la nueva imagen cargada con una extension y peso indicado  
    const size = image.files[0].size,
        allowedExtensions = /(.jpg|.jpeg|.png|.PNG)$/i;
    if (!allowedExtensions.exec(image.value)) {
        return false;
    }
    return true;
}
function createElementEntrevistaDetenido(src, type) {//FUNCION PARA VIZUALIZAR Y CONTENER LA IMAGEN CARGADA
    let div = document.getElementById('imageContentDetenido');
    div.innerHTML = `<div class="d-flex justify-content-end">
                        <span onclick="deleteImageFotoDetenido()" class="deleteFile">X</span>
                    </div>
                    <img name="nor" src="${src}" id="imagesDetenido" width="300px" data-toggle="modal" data-target="#ModalCenterDetenidoEntrevista">
                    <input type="hidden" class=" ${type}"/>
                    <div class="modal fade " id="ModalCenterDetenidoEntrevista" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <img name="nor" src="${src}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                        </div>
                    </div>`;
}
function deleteImageFotoDetenido() {//FUNCION PARA ELIMINAR LA IMAGEN DEL GRUPO DELICTIVO
    let div = document.getElementById('imageContentDetenido');
    document.getElementById('fileFotoDetenido').value = '';
    div.innerHTML = '';
}
async function encodeFileAsBase64URL(file) {//FUNCION PARA CODIFICAR EN BASE 64 LA IMAGEN CARGADA 
    return new Promise((resolve1) => {
        let reader2 = new FileReader();
        reader2.addEventListener('loadend', () => {
            resolve1(reader2.result);
        });
        reader2.readAsDataURL(file);
    });
};
const imageExists= async(imgUrl)=> {//FUNCION QUE VALIDA SI EXISTE LA IMAGEN EN EL SERVIDOR
    if (!imgUrl) {
        return false;
    }
    return new Promise(res => {
        const image = new Image();
        image.onload = () => res(true);
        image.onerror = () => res(false);
        image.src = imgUrl;
    });
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
function valideMultiples(evt) {//FUNCION QUE VALIDA LA INSERCION DE SOLO NUMEROS 
    var code = (evt.which) ? evt.which : evt.keyCode;
    let codigos = [8,32,34,39,40,41,44,45,46,47,58,59,63,193,201,205,211,218,225,233,237,243,250,209,241]//TECLA DELETE
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

/*--------------- FUNCIONES PARA VALIDAR Y ACTUALIZAR LA PERSONA DETENIDA ENTREVISTADA NUEVA-------------------------*/
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
        myFormData.append('id_persona_entrevista',document.getElementById('id_persona_entrevista').value);
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
        let radioRelevancia = document.getElementsByName('Relevancia_Si_No');
        if(radioRelevancia[0].checked){//ES DE RELEVANCIA
            myFormData.append('Relevancia','1');
        }else if(radioRelevancia[1].checked){//NO ES DE RELEVANCIA
            myFormData.append('Relevancia','0');   
        }
        if(document.getElementById('imageContentDetenido').children[1]==undefined){
            myFormData.append('Foto','SD')
            myFormData.append('Img_64','SD')
            console.log('entro a sd')
        }else{
            if(document.getElementById('imageContentDetenido').children[2].classList[0]=='File'){
                let base64URL = await encodeFileAsBase64URL(document.getElementById('fileFotoDetenido').files[0]);
                myFormData.append('Foto','FotoDetenido'+document.getElementById('id_persona_entrevista').value+'.png');
                myFormData.append('Img_64',base64URL);
                let type = document.getElementById('imageContentDetenido').children[2].classList[0];
                myFormData.append('typeImage',type);
            }else{
                let base64 = document.getElementById('imagesDetenido');
                await toDataURL(base64.src)//esto es para tipo photo osea que ya se encuentran en el servidor un archivo fisico con extension diferente a la png
                .then(myBase64 => {
                    myFormData.append('Img_64',myBase64);
                    myFormData.append('Foto','FotoDetenido'+document.getElementById('id_persona_entrevista').value+'.png');
                    let type = document.getElementById('imageContentDetenido').children[2].classList[0];
                    myFormData.append('typeImage',type);
                })

            }
        }
        button2 = document.getElementById('btn_detenido_entrevista_principal')
        button2.innerHTML = `Guardando
                            <div class="spinner-grow spinner-grow-sm" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>`;
        button2.classList.add('disabled-link');//se desactiva el boton de guardar hasta que halla una respuesta del controlador
        $('#ModalCenterPrincipal').modal('show');//Mostramos una imagen completa en pantalla hasta que halla una respuesta del controlador
        //Agregamos los campos proporcionados  que se almacenaran

        fetch(base_url_js + 'Entrevistas/UpdateEntrevistasPrincipalesFetch', {//realiza el fetch para insertar los datos
            method: 'POST',
            body: myFormData
        })
        .then(res => res.json())

        .then(data => {//obtine respuesta del controlador
            button2.innerHTML = `Guardar`;
            button2.classList.remove('disabled-link');//se vuelve activar la funcion del boton 
            $('#ModalCenterPrincipal').modal('hide');//se quita la imagen 
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
                alertaUpdatePrincipales()//Si todo se valido bien y se inserto correctamente se arroja un mensaje satisfactorio y redirige a la pestaña principal del gestor de casos
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

function alertaUpdatePrincipales() {//FUNCION PARA AVISAR QUE TODO SALIO BIEN 
    const BtGuardar =document.getElementById("btn_detenido_entrevista_principal");
    BtGuardar.setAttribute('disabled', '');
    msg_principalesError.innerHTML = `<div class="alert alert-success text-center" role="success">Los datos del detenido han sido actualizados corectamente 
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
    console.log(file)
    return new Promise((resolve1) => {
        let reader2 = new FileReader();
        reader2.addEventListener('loadend', () => {
            resolve1(reader2.result);
        });
        reader2.readAsDataURL(file);
    });
};

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