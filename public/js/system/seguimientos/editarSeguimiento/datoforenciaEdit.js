const MostrarTabForencias=async()=>{//FUNCION QUE OCULTA O MUESTRA LA TAB DE FORENSIA
    NumeroPersonas=document.getElementById('contarRes').rows.length;
    if(NumeroPersonas==0||document.getElementById('visual').value==0){//EN CASO DE NINGUN DATO CARGADO
        document.getElementById('li-Forencia').classList.add('mi_hide');
        document.getElementById('Forencia0').classList.add('mi_hide');
    }else{
        document.getElementById('li-Forencia').classList.remove('mi_hide');
        document.getElementById('Forencia0').classList.remove('mi_hide');
    }
    RecargaSelectForencia() 
    RecargaDatosForencia();
}
async function  RecargaSelectForencia() {//REFRESCA EL SELECTOR DEL FORENSIA CON LOS DATOS DE PERSONAS Y VEHICULOS GUARDADOS EN EL SEGUIMIENTO  
    // Obtener referencia al elemento select
    var select = document.getElementById("PersonaSelectForencias");
    while (select.options.length > 0) {//ACTUALIZACION DE SELECT POR SI HAY MODIFICACION EN LAS TABLAS
        select.remove(0);
    }
    let Personas = PersonasSelect;
    // Datos para las opciones
    var option = document.createElement("option");
    option.text = "SELECCIONE PERSONA";
    option.value='SD';
    select.add(option);
    // Generar las opciones del select
    for (var i = 0; i < Personas.length; i++) {
        option = document.createElement("option");
        option.text = Personas[i]['Nombre'] +" "+Personas[i]['Ap_Paterno']+" "+Personas[i]['Ap_Materno'];
        option.value = Personas[i]['Id_Persona'];
        select.add(option);
    }
}
/*----------------------FUNCIONES DE LA TABLA DE FORENSIAS----------------- */
let selectedRowForencias = null//VARIABLE QUE DEFINE SI SE TRATA DE UNA ACTUALIZACION DE UN REGISTRO YA CREADO O DE UNA NUEVA INSERCION

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
    if(document.getElementById('PersonaSelectForencias').value=='SD'){
        respuesta=false;
        document.getElementById('PersonaSelect_Forencias_error').innerHTML='Seleccione una persona'
    }else{
        document.getElementById('PersonaSelect_Forencias_error').innerHTML=''
    }

    if(document.getElementById('forencia_descripcion').value.trim()==''){
        respuesta=false;
        document.getElementById('forencia_error').innerHTML='Ingrese la descripcion de la forencia'
    }else{
        document.getElementById('forencia_error').innerHTML=''
    }
    return respuesta;
}
const InsertForencias= async()=>{//FUNCION QUE INSERTA LOS DATOS EN LA TABLA DE FORENSIA
    let table = document.getElementById('ForenciasTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    newRow.insertCell(0).innerHTML=document.getElementById("PersonaSelectForencias").options[document.getElementById("PersonaSelectForencias").selectedIndex].text;
    newRow.insertCell(1).innerHTML =document.getElementById('Id_Forencia').value;
    newRow.insertCell(2).innerHTML =document.getElementById('PersonaSelectForencias').value;
    newRow.insertCell(3).innerHTML =document.getElementById('forencia_descripcion').value.toUpperCase();
    newRow.insertCell(4).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoForencia${newRow.rowIndex}">
                                        <div class="form-group">
                                            <input type="file" name="FotoForencia_row${newRow.rowIndex}" accept="image/*" id="fileFotoForencia_row${newRow.rowIndex}" class="inputfile uploadFileFotoForencia" onchange="uploadFileForencia(event)" data-toggle="tooltip" data-placement="bottom">
                                            <label for="fileFotoForencia_row${newRow.rowIndex}"></label>
                                            <h3 class="uploadFotoDatoCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                        </div>
                                    </div>
                                    <div id="imageContentForencia_row${newRow.rowIndex}"></div>`;
    newRow.insertCell(5).innerHTML =document.getElementById('captura_dato_forencias').value.toUpperCase();
    newRow.insertCell(6).innerHTML =`<button type="button" class="btn btn-add" onclick="editForencia(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc" value="-" onclick="deleteRowForencia(this,ForenciasTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
    newRow.insertCell(7).innerHTML = document.getElementById('Id_Seg_Dato').value;
    newRow.cells[1].style.display = "none";
    newRow.cells[2].style.display = "none";
    newRow.cells[7].style.display = "none";
}
const ResetFormForencias= async()=>{//FUNCION QUE LIMPIA LA VISTA DE FORENSIA
    document.getElementById('Id_Forencia').value='SD';
    document.getElementById('PersonaSelectForencias').value='SD';
    document.getElementById('forencia_descripcion').value='';
}
const editForencia = (obj) => {//FUNCION QUE EDITA LA TABLA DE FORENSIA TOMANDO LOS DATOS DE LA ROW SELECCIONADA Y ACOMODANDO LOS DATOS EN LA VISTA PARA SU EDICION
    document.getElementById('alertaEditforencias').style.display = 'block';
    selectedRowForencias = obj.parentElement.parentElement;
    document.getElementById('Id_Forencia').value=selectedRowForencias.cells[1].innerHTML;
    document.getElementById('PersonaSelectForencias').value=selectedRowForencias.cells[2].innerHTML;
    document.getElementById('forencia_descripcion').value=selectedRowForencias.cells[3].innerHTML;
    document.getElementById('Id_Seg_Dato').value=selectedRowForencias.cells[7].innerHTML;
    window.scroll({
        top: 0,
        left: 100,
        behavior: 'smooth'
    });
}
const UpdateRowForencias=()=>{//FUNCION QUE ACTUALIZA LOS DATOS EN LA TABLA DE FORENSIA
    selectedRowForencias.cells[0].innerHTML=document.getElementById("PersonaSelectForencias").options[document.getElementById("PersonaSelectForencias").selectedIndex].text;
    selectedRowForencias.cells[1].innerHTML=document.getElementById('Id_Forencia').value;
    selectedRowForencias.cells[2].innerHTML=document.getElementById('PersonaSelectForencias').value;
    selectedRowForencias.cells[3].innerHTML=document.getElementById('forencia_descripcion').value.toUpperCase();
    selectedRowForencias.cells[7].innerHTML=document.getElementById('Id_Seg_Dato').value
    document.getElementById('alertaEditforencias').style.display = 'none';
    selectedRowForencias= null;
}


const deleteRowForencia = async(obj, tableId) => {//funcion para eliminar una fila en tablas ademas de funcion especial de eliminacion para las tablas personas
    if (confirm('¿Desea eliminar este elemento?')) {
        let row = obj.parentElement.parentElement;
        aux=row.rowIndex;
        if(row.cells[1].innerHTML!='SD'){
            await DesasociaForencia(row.cells[1].innerHTML);
        }
        document.getElementById(tableId.id).deleteRow(row.rowIndex);
        j=aux+1;
        if(tableId.id=='ForenciasTable'){//Si es la tabla de personas hace una eliminacion especial debido a las fotos
            table = document.getElementById('ForenciasTable')
            j=aux+1;
            for(let i=aux;i<table.rows.length;i++){
                let contenedorImg =table.rows[i].cells[4].children[1];
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
                let contenedorInput =table.rows[i].cells[4].children[0];
                contenedorInput.setAttribute('id', 'uploadFileFotoForencia'+i);
                contenedorInput.childNodes[1].childNodes[1].setAttribute('id', 'fileFotoForencia_row'+i);
                contenedorInput.childNodes[1].childNodes[1].setAttribute('name', 'FotoForencia_row'+i);
                contenedorInput.childNodes[1].childNodes[3].setAttribute('for', 'fileFotoForencia_row'+i);
                j++;
            }
        }
        await RecargaDatosForencia();
    }
}
const DesasociaForencia= async(Id_Forencia)=>{//FUNCION QUE ELIMINA LOS DATOS DE LAS TABLAS DE FORENSIA
    try {
        myFormData.append('Id_Forencia',Id_Forencia)
        const response = await fetch(base_url_js + 'Seguimientos/DesasociaForencia', {
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
                        <img name="nor" src="${src}" id="imagesforencia_row_${index}" width="400px" data-toggle="modal" data-target="#ModalCenterForencia${index}">
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
var datosForencias = document.getElementById('datos_forencias')
document.getElementById('btn_forencias').addEventListener('click', async function(e) {
    let myFormDataForencias = new FormData(datosForencias)//RECUERDA SIEMPRE ENVIAR LOS DATOS DEL FRAME Y AUN MAS IMPORTANTE POR LAS IMAGENES LAS DETECTE EN EL POST
    var Forencias =  await readTableForencias();//LEEMOS EL CONTENIDO DE LA TABLA DE Forencias 

    let OrdenadasDatos = Forencias.sort((a, b) => a.row.Id_Seguimiento - b.row.Id_Seguimiento);

    myFormDataForencias.append('Forenciastable', JSON.stringify(OrdenadasDatos)); //CODIFICAMOS LOS DATOS PARA QUE EL CONTROLADOR LOS OCUPE 
    myFormDataForencias.append('id_seguimiento',document.getElementById('id_seguimiento_principales').value)
    
    let button = document.getElementById('btn_forencias')
    button.innerHTML = `
        Guardando
        <div class="spinner-grow spinner-grow-sm" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    `;
    button.classList.add('disabled-link');
    $('#ModalCenterPrincipalforencias').modal('show');
    fetch(base_url_js + 'Seguimientos/UpdateForenciasFetch', {//realiza el fetch para actualizar los datos
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
    await RecargaDatosForencia();
    msg_forenciasError.innerHTML = `<div class="alert alert-success text-center" role="success">Datos Actualizados correctamente.
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
//FUNCIONES PARA GUARDAR INFORMACION EN LA BASE DE DATOS Y REFRESCAR LA INFORMACION DE LA VISTA DE LA TABLA
const readTableForencias = async() => {//lee los datos de la tabla personas y genera una estructura deacuerdo a los datos contenido es la tabla
    let table = document.getElementById('ForenciasTable');
    let objetos = [];
    for (let i = 1; i < table.rows.length; i++) {
        let input = table.rows[i].cells[4].children[1].children[1];
        if (input != undefined) {//si en el contenedor existe alguna imagen verifica el tipo
            let type = table.rows[i].cells[4].children[1].children[2].classList[1]
            let base64 = document.getElementById('imagesforencia_row_' + i);
            nameImage = 'FotoForencia_row' + i;
            isPNG = base64.src.split('.');
            if (type != 'File') {//funcion para leer las fotos
                if (isPNG[1] != undefined) {
                    await toDataURL(base64.src)//esto es para tipo photo osea que ya se encuentran en el servidor un archivo fisico con extension diferente a la png
                        .then(myBase64 => {
                            objetos.push({
                                ['row']: {
                                    Id_Forencia: table.rows[i].cells[1].innerHTML,
                                    Id_Persona : table.rows[i].cells[2].innerHTML,
                                    Descripcion_Forencia: table.rows[i].cells[3].innerHTML,
                                    Capturo: table.rows[i].cells[5].innerHTML,
                                    typeImage: type,
                                    nameImage: nameImage,
                                    image: myBase64,
                                    imagebase64:myBase64,
                                    Id_Seguimiento: table.rows[i].cells[7].innerHTML
                                }
                            });
                        })
                } else {//esto es para tipo photo osea que ya se encuentran en el servidor un archivo fisico con extension png
                    objetos.push({
                        ['row']: {
                            Id_Forencia: table.rows[i].cells[1].innerHTML,
                            Id_Persona : table.rows[i].cells[2].innerHTML,
                            Descripcion_Forencia: table.rows[i].cells[3].innerHTML,
                            Capturo: table.rows[i].cells[5].innerHTML,
                            typeImage: type,
                            nameImage: nameImage,
                            image:  base64.src,
                            imagebase64:base64.src,
                            Id_Seguimiento: table.rows[i].cells[7].innerHTML
                        }
                    });
                }
            }else{// si es tipo file guarda los datos de la imagen
                let aux=document.getElementById('fileFotoForencia_row'+i)
                let base64URL = await encodeFileAsBase64URL(aux.files[0]);
                objetos.push({
                    ['row']: {
                        Id_Forencia: table.rows[i].cells[1].innerHTML,
                        Id_Persona : table.rows[i].cells[2].innerHTML,
                        Descripcion_Forencia: table.rows[i].cells[3].innerHTML,
                        Capturo: table.rows[i].cells[5].innerHTML,
                        typeImage: type,
                        nameImage: nameImage,
                        image: "null",
                        imagebase64:base64URL,
                        Id_Seguimiento: table.rows[i].cells[7].innerHTML
                    }
                });
            }
        } else {//si no hay imagen solo almacena los datos el texto 
            objetos.push({
                ['row']: {
                    Id_Forencia: table.rows[i].cells[1].innerHTML,
                    Id_Persona : table.rows[i].cells[2].innerHTML,
                    Descripcion_Forencia: table.rows[i].cells[3].innerHTML,
                    Capturo: table.rows[i].cells[5].innerHTML,
                    typeImage: "null",
                    nameImage: "null",
                    image: "null",
                    imagebase64:"null",
                    Id_Seguimiento: table.rows[i].cells[7].innerHTML
                }
            });
        }
    }
    console.log(objetos)
    return objetos;
}

const findPersonById = (id) => {
    return PersonasSelect.find(person => person.Id_Persona === id);
};

const cambioID_Seguimiento = async() =>{
    let buscar = document.getElementById('PersonaSelectForencias').value;
    //console.log(PersonasSelect)
    //console.log(buscar)
    let person = await findPersonById(buscar);
    //console.log(person.Id_Seguimiento)
    document.getElementById('Id_Seg_Dato').value = person.Id_Seguimiento;
    //console.log(document.getElementById('Id_Seg_Dato').value)
 }

document.getElementById('PersonaSelectForencias').addEventListener('change',cambioID_Seguimiento);