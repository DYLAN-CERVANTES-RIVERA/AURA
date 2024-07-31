/*----------------------FUNCIONES DE LA TABLA DE FORENSIAS----------------- */
async function CatalogoTipo(){
    Tipos = await getCatalogoTipo()
    let select = document.getElementById("tipo_dato");
    while (select.options.length > 0) {
        select.remove(0);
    }
    option = document.createElement("option");
    option.text = "SELECCIONE EL TIPO DE DATO";
    option.value = "SD";
    select.add(option);

    for (let i = 0; i < Tipos.length; i++) {
        option = document.createElement("option");
        option.text = Tipos[i].Tipo;
        option.value = Tipos[i].Tipo;
        select.add(option);
    }
}
const tipo_dato = document.getElementById('tipo_dato');
tipo_dato.addEventListener('input', () => {
    opcion = document.getElementById("tipo_dato").value;
    document.getElementById("dato_relevante").value = "";
    document.getElementById('dato_relevante').disabled = false
    switch(opcion){
        case 'SD': 
            document.getElementsByName('dato_relevante')[0].placeholder ='';   
            document.getElementById('dato_relevante').disabled = true
        break
        case 'NÚMERO TELEFÓNICO': 
            document.getElementById("dato_relevante").maxLength = 10;
            document.getElementsByName('dato_relevante')[0].placeholder='INGRESE SOLO LOS 10 NUMEROS';   
        break
        case 'CURP':
            document.getElementById("dato_relevante").maxLength = 19;
            document.getElementsByName('dato_relevante')[0].placeholder='INGRESE CURP';   
        break
        case 'RFC':
            document.getElementById("dato_relevante").maxLength = 13;
            document.getElementsByName('dato_relevante')[0].placeholder='INGRESE RFC';   
        break
        case 'ALIAS':
            document.getElementById("dato_relevante").maxLength = 20;
            document.getElementsByName('dato_relevante')[0].placeholder='INGRESE ALIAS';   
        break
        case 'NÚMERO DE TARJETA':
            document.getElementById("dato_relevante").maxLength = 16;
            document.getElementsByName('dato_relevante')[0].placeholder='INGRESE NUMERO DE TARJETA';   
        break
        default:
            document.getElementById("dato_relevante").maxLength = 450;
            document.getElementsByName('dato_relevante')[0].placeholder=' INGRESE DATO DE RELEVANTE '+opcion; 
            break;
    }
});
document.getElementById("dato_relevante").addEventListener("input", function(event) {/// Para el pegado
    var valor = event.target.value;
    var nuevoValor = "";
    for (var i = 0; i < valor.length; i++) {
        var char = valor[i];       
        if (/[a-zA-Z0-9\-+\/*%().\sáéíóúÁÉÍÓÚ]/.test(char)) {
            nuevoValor += char;
        }
    }
    event.target.value = nuevoValor;
});
function EvaluaEntrada (evt) {
    opcion = document.getElementById("tipo_dato").value;
    let code = (evt.which) ? evt.which : evt.keyCode;
    let codigo_valido
    switch(opcion){
        case 'NÚMERO TELEFÓNICO':  
        case 'NÚMERO DE TARJETA': 
            codigos = [8,48,49,50,51,52,53,54,55,56,57]//TECLAS DELETE,NUMEROS
            codigo_valido = codigos.find(element=>element==code);
            if(codigo_valido){
               bandera = true
            }else{
                bandera= false
            }
        break;
        
        case 'ALIAS': 
        case 'RFC':
        case 'CURP':
            codigos = [8,48,49,50,51,52,53,54,55,56,57,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122];
            codigo_valido = codigos.find(element=>element==code);
            if(codigo_valido){
               bandera = true
            }else{
                bandera= false
            }
        break;
        
        case 'ACTIVIDAD DELICTIVA': 
        case 'ZONAS DE OPERACIÓN':
            // Obtener el texto ingresado en el campo
            var texto = document.getElementById("dato_relevante").value;
            
            // Dividir el texto en palabras usando un espacio como separador
            var palabras = texto.split(" ");
            
            // Contar la cantidad de palabras
            var cantidadPalabras = palabras.length;
            
            // Si la cantidad de palabras supera 20, prevenir la entrada de más texto
            if (cantidadPalabras >= 20) {
                event.preventDefault();
            }
        break;
        case 'COPARTICIPES': 
            codigos = [8,44,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,193,201,205,211,218,225,233,237,243,250,209,241,244];
            codigo_valido = codigos.find(element=>element==code);
            if(codigo_valido){
                bandera = true
            }else{
                bandera= false
            }
        break;
        default:
            bandera= true
        break;
    }
    return bandera;
}
let selectedRowForencias = null//VARIABLE QUE DEFINE SI SE TRATA DE UNA ACTUALIZACION DE UN REGISTRO YA CREADO O DE UNA NUEVA INSERCION
let radioTipoDato = document.getElementsByName('tipo_dato_dato');
const onFormForenciasubmit=async()=>{
    if(await ValidatableForencias()){
        if (selectedRowForencias === null){
            InsertForencias();//INSERTA NUEVA FILA EN LA TABLA DE FORENSIAS
            ResetFormForencias();//LIMPIA LA VISTA 
        }else{
            UpdateRowForencias();//ACTUALIZA LA FILA SELECCIONADA EN LA TABLA DE FORENSIAS
            ResetFormForencias();//LIMPIA LA VISTA
        }
    }
}

const ValidatableForencias = async() => {//FUNCION QUE VALIDA LAS ENTRADAS DEL FORMULARIO DE ANTECEDENTES PARA QUE SE INGRESE EN LA TABLA
    let respuesta=true;
    if(document.getElementById('forencia_descripcion').value.trim()==''){
        respuesta=false;
        document.getElementById('forencia_error').innerHTML='Ingrese la descripcion del dato'
    }else{
        document.getElementById('forencia_error').innerHTML=''
    }

    if(radioTipoDato[1].checked && document.getElementById('DatoSelect').value!= -1){
            if(document.getElementById('DatoSelect').value== document.getElementById("Id_Forencia").value){
                respuesta = false;
                document.getElementById('select_forencia_error').innerHTML='No puedes asociar el dato asi mismo';
            }else{
                document.getElementById('select_forencia_error').innerHTML='';
            }
    }else{
        document.getElementById('select_forencia_error').innerHTML='';
    }
    opcion = document.getElementById("tipo_dato").value;
    document.getElementById('dato_relevante_error').innerHTML='';
    switch(opcion){
        case 'NÚMERO TELEFÓNICO': 
            if(document.getElementById('dato_relevante').value.length < 10){
                document.getElementById('dato_relevante_error').innerHTML='Ingrese el numero telefonico con la cantidad de digitos adecuada';
                respuesta = false;
            }
        break;
        case 'NÚMERO DE TARJETA':
            if(document.getElementById('dato_relevante').value.length < 16){
                document.getElementById('dato_relevante_error').innerHTML='Ingrese el numero de tarjeta con la cantidad de digitos adecuada';
                respuesta = false;
            }
        break;
        case 'RFC':
            if(document.getElementById('dato_relevante').value.length < 10){
                document.getElementById('dato_relevante_error').innerHTML='Ingrese el RFC con la cantidad de digitos adecuada';
                respuesta = false;
            }
        break;
        case 'CURP':
            if(document.getElementById('dato_relevante').value.length < 18){
                document.getElementById('dato_relevante_error').innerHTML='Ingrese el Curp con la cantidad de digitos adecuada';
                respuesta = false;
            }
        break;
    }
    
    return respuesta;
}
const InsertForencias= async()=>{//FUNCION QUE INSERTA LOS DATOS EN LA TABLA DE FORENSIA
    let table = document.getElementById('ForenciasTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    newRow.insertCell(0).innerHTML = document.getElementById("Id_Forencia").value;
    newRow.insertCell(1).innerHTML = document.getElementById('DatoSelect').value;
    if(radioTipoDato[0].checked && document.getElementById('DatoSelect').value!= -1){
        tipo = 'ENTREVISTA';
    }else if(radioTipoDato[1].checked && document.getElementById('DatoSelect').value!= -1){
        tipo = 'DATO';
    }else{
        tipo = 'SD';
    }
    newRow.insertCell(2).innerHTML = tipo;
    newRow.insertCell(3).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoForencia${newRow.rowIndex}">
                                        <div class="form-group">
                                            <input type="file" name="FotoForencia_row${newRow.rowIndex}" accept="image/*" id="fileFotoForencia_row${newRow.rowIndex}" class="inputfile uploadFileFotoForencia" onchange="uploadFileForencia(event)" data-toggle="tooltip" data-placement="bottom">
                                            <label for="fileFotoForencia_row${newRow.rowIndex}"></label>
                                            <h3 class="uploadFotoForenciaCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                        </div>
                                    </div>
                                    <div id="imageContentForencia_row${newRow.rowIndex}"></div>`;
    newRow.insertCell(4).innerHTML = document.getElementById('forencia_descripcion').value.toUpperCase();
    newRow.insertCell(5).innerHTML = document.getElementById('tipo_dato').value;
    newRow.insertCell(6).innerHTML = (document.getElementById('dato_relevante').value.trim()=='')?'SD':document.getElementById('dato_relevante').value.toUpperCase();
    newRow.insertCell(7).innerHTML = document.getElementById('captura_dato_forencias').value.toUpperCase();
    newRow.insertCell(8).innerHTML =`<button type="button" class="btn btn-add" onclick="editForencia(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc" value="-" onclick="deleteRowForencia(this,ForenciasTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;

    //newRow.cells[0].style.display = "none";
    //newRow.cells[1].style.display = "none";
}
const ResetFormForencias= async()=>{//FUNCION QUE LIMPIA LA VISTA DE FORENSIA
    document.getElementById('Id_Forencia').value='SD';
    document.getElementById('DatoSelect').value=-1;
    document.getElementById('forencia_descripcion').value='';
    document.getElementById('DatoSelect').value = -1;
    document.getElementById('tipo_dato').value='SD';
    document.getElementById('dato_relevante').value='';
    document.getElementById("dato_relevante").maxLength = 450;
    document.getElementsByName('dato_relevante')[0].placeholder=''; 
    document.getElementById('dato_relevante').disabled = true
    radioTipoDato[2].checked = true;
    await changeTipoDato();
}
const editForencia = async (obj) => {//FUNCION QUE EDITA LA TABLA DE FORENSIA TOMANDO LOS DATOS DE LA ROW SELECCIONADA Y ACOMODANDO LOS DATOS EN LA VISTA PARA SU EDICION
    document.getElementById('alertaEditforencias').style.display = 'block';
    selectedRowForencias = obj.parentElement.parentElement;
    if(selectedRowForencias.cells[2].innerHTML =='ENTREVISTA' ){
        radioTipoDato[0].checked = true;
    }else if(selectedRowForencias.cells[2].innerHTML =='DATO'){
        radioTipoDato[1].checked = true;
    }else{
        radioTipoDato[2].checked = true;
    }
    await changeTipoDato();
    document.getElementById('Id_Forencia').value = selectedRowForencias.cells[0].innerHTML;
    document.getElementById('DatoSelect').value = selectedRowForencias.cells[1].innerHTML;
    document.getElementById('forencia_descripcion').value = selectedRowForencias.cells[4].innerHTML;
    document.getElementById('tipo_dato').value = selectedRowForencias.cells[5].innerHTML;
    document.getElementById('dato_relevante').value = (selectedRowForencias.cells[6].innerHTML=='SD')?'':selectedRowForencias.cells[6].innerHTML;
    if( selectedRowForencias.cells[5].innerHTML!='SD'){
        document.getElementById('dato_relevante').disabled = false
        opcion = selectedRowForencias.cells[5].innerHTML;
        switch(opcion){
            case 'SD': 
                document.getElementsByName('dato_relevante')[0].placeholder ='';   
                document.getElementById('dato_relevante').disabled = true
            break
            case 'NÚMERO TELEFÓNICO': 
                document.getElementById("dato_relevante").maxLength = 10;
                document.getElementsByName('dato_relevante')[0].placeholder='INGRESE SOLO LOS 10 NUMEROS';   
            break
            case 'CURP':
                document.getElementById("dato_relevante").maxLength = 19;
                document.getElementsByName('dato_relevante')[0].placeholder='INGRESE CURP';   
            break
            case 'RFC':
                document.getElementById("dato_relevante").maxLength = 13;
                document.getElementsByName('dato_relevante')[0].placeholder='INGRESE RFC';   
            break
            case 'ALIAS':
                document.getElementById("dato_relevante").maxLength = 20;
                document.getElementsByName('dato_relevante')[0].placeholder='INGRESE ALIAS';   
            break
            case 'NÚMERO DE TARJETA':
                document.getElementById("dato_relevante").maxLength = 16;
                document.getElementsByName('dato_relevante')[0].placeholder='INGRESE NUMERO DE TARJETA';   
            break
            default:
                document.getElementById("dato_relevante").maxLength = 450;
                document.getElementsByName('dato_relevante')[0].placeholder=' INGRESE DATO DE RELEVANTE '+opcion; 
                break;
        }
    }
    window.scroll({
        top: 0,
        left: 100,
        behavior: 'smooth'
    });
}
const UpdateRowForencias=()=>{//FUNCION QUE ACTUALIZA LOS DATOS EN LA TABLA DE FORENSIA
    selectedRowForencias.cells[0].innerHTML=document.getElementById('Id_Forencia').value;
    selectedRowForencias.cells[1].innerHTML=document.getElementById('DatoSelect').value;
    
    if(radioTipoDato[0].checked && document.getElementById('DatoSelect').value!= -1){
        tipo = 'ENTREVISTA';
    }else if(radioTipoDato[1].checked && document.getElementById('DatoSelect').value!= -1){
        tipo = 'DATO';
    }else{
        tipo = 'SD';
    }
    selectedRowForencias.cells[2].innerHTML = tipo;

    selectedRowForencias.cells[4].innerHTML = document.getElementById('forencia_descripcion').value.toUpperCase();
    selectedRowForencias.cells[5].innerHTML = document.getElementById('tipo_dato').value;
    selectedRowForencias.cells[6].innerHTML = (document.getElementById('dato_relevante').value.trim()=='')?'SD':document.getElementById('dato_relevante').value.toUpperCase();
    document.getElementById('alertaEditforencias').style.display = 'none';
    selectedRowForencias= null;
    window.scroll({
        top: 0,
        left: 10,
        behavior: 'smooth'
    });
}
const deleteRowForencia = async(obj, tableId) => {//funcion para eliminar una fila en tablas ademas de funcion especial de eliminacion para las tablas personas
    if (confirm('¿Desea eliminar este elemento?')) {
        let row = obj.parentElement.parentElement;
        aux=row.rowIndex;
        if(row.cells[0].innerHTML!='SD'){
            await DesasociaForensia(row.cells[0].innerHTML,document.getElementById('id_persona_entrevista').value);
        }
        document.getElementById(tableId.id).deleteRow(row.rowIndex);
        j=aux+1;
        if(tableId.id=='ForenciasTable'){//Si es la tabla de personas hace una eliminacion especial debido a las fotos
            table = document.getElementById('ForenciasTable')
            j=aux+1;
            for(let i=aux;i<table.rows.length;i++){
                let contenedorImg =table.rows[i].cells[3].children[1];
                contenedorImg.setAttribute('id', 'imageContentForencia_row'+i);
                if(contenedorImg.childNodes.length>1){
                    if(contenedorImg.childNodes[1].childNodes[1]!=undefined){
                        contenedorImg.childNodes[1].childNodes[1].setAttribute('onclick', "deleteImageFotoForencia("+i+")");
                        contenedorImg.childNodes[3].setAttribute('id', 'imagesforencia_row_'+i);
                        contenedorImg.childNodes[3].setAttribute('data-target', '#ModalCenterForencia'+i);
                        if(contenedorImg.childNodes[5].getAttribute('class')==j+' Photo'){
                            contenedorImg.childNodes[5].setAttribute('class', i+' Photo');
                        }else{
                            contenedorImg.childNodes[5].setAttribute('class', i+' File');
                        }
                        contenedorImg.childNodes[7].setAttribute('id', 'ModalCenterForencia'+i);
                    }else{
                        contenedorImg.childNodes[0].childNodes[1].setAttribute('onclick', "deleteImageFotoForencia("+i+")");
                        contenedorImg.childNodes[2].setAttribute('id', 'imagesforencia_row_'+i);
                        contenedorImg.childNodes[2].setAttribute('data-target', '#ModalCenterForencia'+i);
    
                        if(contenedorImg.childNodes[4].getAttribute('class')==j+' Photo'){
                            contenedorImg.childNodes[4].setAttribute('class', i+' Photo');
                        }else{
                            contenedorImg.childNodes[4].setAttribute('class', i+' File');
                        }
                        contenedorImg.childNodes[6].setAttribute('id', 'ModalCenterForencia'+i);
                    }  
                }
                let contenedorInput =table.rows[i].cells[3].children[0];
                contenedorInput.setAttribute('id', 'uploadFileFotoForencia'+i);
                contenedorInput.childNodes[1].childNodes[1].setAttribute('id', 'fileFotoForencia_row'+i);
                contenedorInput.childNodes[1].childNodes[1].setAttribute('name', 'FotoForencia_row'+i);
                contenedorInput.childNodes[1].childNodes[3].setAttribute('for', 'fileFotoForencia_row'+i);
                j++;
            }
        }
        await RecargaDatosForensia();
    }
}
const DesasociaForensia= async(Id_Forensia_Entrevista,Id_Persona_Entrevista)=>{//FUNCION QUE ELIMINA LOS DATOS DE LAS TABLAS DE FORENSIA
    try {
        myFormData.append('Id_Forensia_Entrevista',Id_Forensia_Entrevista)
        myFormData.append('Id_Persona_Entrevista',Id_Persona_Entrevista )
        const response = await fetch(base_url_js + 'Entrevistas/DesasociaForensia', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
/*--------------FUNCIONES PARA LAS FOTOS DE LA TABLA DE FORENSIAS--------------------- */
function uploadFileForencia(event, type) {//Funcion para actualizar las imagenes de la tabla de personas
    let file;
    if (type) {
        file = 'Photo';
    } else {
        file = 'File';
    }
    if (event.currentTarget.classList.contains('uploadFileFotoForencia')) {//TABLA DE FORENSIAS
        if (validateImage(event.target)) {
            const src = URL.createObjectURL(event.target.files[0]);
            const row = event.currentTarget;
            const index = row.parentNode.parentNode.parentNode.parentNode.rowIndex;
            createElementFotoForencia(src, index, 'File');
        } else {
            msg_forenciasError.innerHTML = '<div class="alert alert-warning text-center" role="alert">Verificar el archivo cargado.<br>Posibles errores: <br> - Archivo muy pesado (Máximo 8 megas). <br> -Extensión no aceptada (Extensiones aceptadas: jpeg, png, jpg, PNG).</div>';
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });
        }
    }
}
function createElementFotoForencia(src, index, type, view) {//FUNCION PARA LA VISUALIZACION DE IMAGENES EN LA TABLA DE FORENSIAS
    const div = document.getElementById('imageContentForencia_row' + index);
    if (view === undefined) {
        div.innerHTML = `<div class="d-flex justify-content-end">
                            <span onclick="deleteImageFotoForencia(${index})" class="deleteFile">X</span>
                        </div>
                        <img name="nor" src="${src}" id="imagesforencia_row_${index}" width="200px" data-toggle="modal" data-target="#ModalCenterForencia${index}">
                        <input type="hidden" class="${index} ${type}"/>
                        <div class="modal fade " id="ModalCenterForencia${index}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <img name="nor" src="${src}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                            </div>
                        </div>`;
    } else {
        div.innerHTML = `<div>
                            <img2 src="${src}">
                            <input type="hidden" class="${index} ${type}"/>
                        </div>`;
    }
}
function deleteImageFotoForencia(index) {//FUNCION QUE ELIMINA LA VISUALIZACION Y EL CONTENIDO DE LAS IMAGENES
    const div = document.getElementById('imageContentForencia_row' + index);
    document.getElementById('fileFotoForencia_row' + index).value = '';
    div.innerHTML = '';
}
/*--------------------------FUNCIONES PARA EL ALMACENAMIENTO DE LOS DATOS CONTENIDOS EN EL FRAME  --------------- */
var msg_forenciasError = document.getElementById('msg_principales_forencias');
var datosForencias = document.getElementById('datos_forencias_entrevistas')
document.getElementById('btn_forencias_entrevistas').addEventListener('click', async function(e) {
    let myFormDataForencias = new FormData(datosForencias)//RECUERDA SIEMPRE ENVIAR LOS DATOS DEL FRAME Y AUN MAS IMPORTANTE POR LAS IMAGENES LAS DETECTE EN EL POST
    var Forencias =  await readTableForencias();//LEEMOS EL CONTENIDO DE LA TABLA DE Forencias 
    myFormDataForencias.append('Forensiastable', JSON.stringify(Forencias)); //CODIFICAMOS LOS DATOS PARA QUE EL CONTROLADOR LOS OCUPE 
    myFormDataForencias.append('id_persona_entrevista',document.getElementById('id_persona_entrevista').value)
    
    let button = document.getElementById('btn_forencias_entrevistas')
    button.innerHTML = `
        Guardando
        <div class="spinner-grow spinner-grow-sm" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    `;
    button.classList.add('disabled-link');
    $('#ModalCenterPrincipalforencias').modal('show');
    fetch(base_url_js + 'Entrevistas/UpdateForensiasFetch', {//realiza el fetch para actualizar los datos
        method: 'POST',
        body: myFormDataForencias
    })

    .then(res => res.json())

    .then(data => {//obtine  respuesta del modelo
        button.innerHTML = `Guardar`;
        button.classList.remove('disabled-link');//se vuelve activar la funcion del boton 
        $('#ModalCenterPrincipalforencias').modal('hide');//se quita la imagen 
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
            msg_forenciasError.innerHTML = messageError
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });
        } else {//si todo salio bien
            alertaForencia()//Si todo salio bien actualiza los datos arroja un mensaje satisfactorio
        }
    })
})
const alertaForencia = async()=>{/// todo bien en la edicion
    
    msg_forenciasError.innerHTML = `<div class="alert alert-success text-center" role="success">Datos de Actualizados correctamente.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
            <span aria-hidden="true">&times;</span>
        </button>
    </div>`;
    window.scroll({
        top: 0,
        left: 100,
        behavior: 'smooth'
    });

    await RecargaDatosForensia();
}
//FUNCIONES PARA GUARDAR INFORMACION EN LA BASE DE DATOS Y REFRESCAR LA INFORMACION DE LA VISTA DE LA TABLA
const readTableForencias = async() => {//lee los datos de la tabla personas y genera una estructura deacuerdo a los datos contenido es la tabla
    let table = document.getElementById('ForenciasTable');
    let objetos = [];
    for (let i = 1; i < table.rows.length; i++) {
        let input = table.rows[i].cells[3].children[1].children[1];
        if (input != undefined) {//si en el contenedor existe alguna imagen verifica el tipo
            let type = table.rows[i].cells[3].children[1].children[2].classList[1]
            let base64 = document.getElementById('imagesforencia_row_' + i);
            nameImage = 'FotoForencia_row' + i;
            isPNG = base64.src.split('.');
            if (type != 'File') {//funcion para leer las fotos
                if (isPNG[1] != undefined) {
                    await toDataURL(base64.src)//esto es para tipo photo osea que ya se encuentran en el servidor un archivo fisico con extension diferente a la png
                        .then(myBase64 => {
                            objetos.push({
                                ['row']: {
                                    Id_Forensia_Entrevista : table.rows[i].cells[0].innerHTML,
                                    Id_Persona_Entrevista  : document.getElementById('id_persona_entrevista').value,
                                    Id_Dato: table.rows[i].cells[1].innerHTML,
                                    Tipo_Relacion: table.rows[i].cells[2].innerHTML,
                                    Descripcion_Forensia: table.rows[i].cells[4].innerHTML,
                                    Tipo_Dato: table.rows[i].cells[5].innerHTML,
                                    Dato_Relevante: table.rows[i].cells[6].innerHTML,
                                    Capturo: table.rows[i].cells[7].innerHTML,
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
                            Id_Forensia_Entrevista : table.rows[i].cells[0].innerHTML,
                            Id_Persona_Entrevista  : document.getElementById('id_persona_entrevista').value,
                            Id_Dato: table.rows[i].cells[1].innerHTML,
                            Tipo_Relacion: table.rows[i].cells[2].innerHTML,
                            Descripcion_Forensia: table.rows[i].cells[4].innerHTML,
                            Tipo_Dato: table.rows[i].cells[5].innerHTML,
                            Dato_Relevante: table.rows[i].cells[6].innerHTML,
                            Capturo: table.rows[i].cells[7].innerHTML,
                            typeImage: type,
                            nameImage: nameImage,
                            image:  base64.src,
                            imagebase64:base64.src
                        }
                    });
                }
            }else{// si es tipo file guarda los datos de la imagen
                let aux=document.getElementById('fileFotoForencia_row'+i)
                let base64URL = await encodeFileAsBase64URL(aux.files[0]);
                objetos.push({
                    ['row']: {
                        Id_Forensia_Entrevista : table.rows[i].cells[0].innerHTML,
                        Id_Persona_Entrevista  : document.getElementById('id_persona_entrevista').value,
                        Id_Dato: table.rows[i].cells[1].innerHTML,
                        Tipo_Relacion: table.rows[i].cells[2].innerHTML,
                        Descripcion_Forensia: table.rows[i].cells[4].innerHTML,
                        Tipo_Dato: table.rows[i].cells[5].innerHTML,
                        Dato_Relevante: table.rows[i].cells[6].innerHTML,
                        Capturo: table.rows[i].cells[7].innerHTML,
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
                    Id_Forensia_Entrevista : table.rows[i].cells[0].innerHTML,
                    Id_Persona_Entrevista  : document.getElementById('id_persona_entrevista').value,
                    Id_Dato: table.rows[i].cells[1].innerHTML,
                    Tipo_Relacion: table.rows[i].cells[2].innerHTML,
                    Descripcion_Forensia: table.rows[i].cells[4].innerHTML,
                    Tipo_Dato: table.rows[i].cells[5].innerHTML,
                    Dato_Relevante: table.rows[i].cells[6].innerHTML,
                    Capturo: table.rows[i].cells[7].innerHTML,
                    typeImage: "null",
                    nameImage: "null",
                    image: "null",
                    imagebase64:"null"
                }
            });
        }
    }
    console.log(objetos)
    return objetos;
}