const MostrarTabRedesSociales=async()=>{//FUNCION QUE OCULTA O MUESTRA LA TAB DE DOMICILIO
    NumeroPersonas=document.getElementById('contarRes').rows.length;
    if(NumeroPersonas==0){//EN CASO DE NINGUN DATO CARGADO
        document.getElementById('li-RedesSociales').classList.add('mi_hide');
        document.getElementById('RedesSociales0').classList.add('mi_hide');
    }else{
        document.getElementById('li-RedesSociales').classList.remove('mi_hide');
        document.getElementById('RedesSociales0').classList.remove('mi_hide');
    }
    RecargaSelectRedSocial()
    RecargaDatosRedesSociales();
}
async function  RecargaSelectRedSocial() {//REFRESCA EL SELECTOR DEL DOMICILIO CON LOS DATOS DE PERSONAS Y VEHICULOS GUARDADOS EN EL SEGUIMIENTO  
    // Obtener referencia al elemento select
    var select = document.getElementById("PersonaSelectRedsocial");
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
/*----------------------FUNCIONES DE LA TABLA DE Redsocial----------------- */
let selectedRowRedsocial = null//VARIABLE QUE DEFINE SI SE TRATA DE UNA ACTUALIZACION DE UN REGISTRO YA CREADO O DE UNA NUEVA INSERCION

const onFormRedsocialsubmit=async()=>{
    if(await ValidatableRedsocial()){
        if (selectedRowRedsocial === null){
            InsertRedsocial();//INSERTA NUEVA FILA EN LA TABLA DE Redsocial
            ResetFormRedsocial();//LIMPIA LA VISTA 
        }else{
            UpdateRowRedsocial();//ACTUALIZA LA FILA SELECCIONADA EN LA TABLA DE Redsocial
            ResetFormRedsocial();//LIMPIA LA VISTA
        }
    }
}

const ValidatableRedsocial = async() => {//FUNCION QUE VALIDA LAS ENTRADAS DEL FORMULARIO DE ANTECEDENTES PARA QUE SE INGRESE EN LA TABLA
    let respuesta=true;
    let FV = new FormValidator()
    let myFormData = new FormData(document.getElementById('datos_Redsocial'))
    let band = [];
    let i = 0;
    if(document.getElementById('PersonaSelectRedsocial').value=='SD'){
        respuesta=false;
        document.getElementById('PersonaSelect_Redsocial_error').innerHTML='Seleccione una persona'
    }else{
        document.getElementById('PersonaSelect_Redsocial_error').innerHTML=''
    }
    if(document.getElementById('Redsocial_tipo_url').value=='SD'){
        respuesta=false;
        document.getElementById('Redsocial_tipo_url_error').innerHTML='Seleccione una persona'
    }else{
        document.getElementById('Redsocial_tipo_url_error').innerHTML=''
    }
    band[i++]=document.getElementById('nombre_perfil_error').innerHTML= FV.validate(myFormData.get('nombre_perfil'), 'required')
    band[i++]=document.getElementById('enlace_error').innerHTML= FV.validate(myFormData.get('enlace'), 'required')
    band[i++]=document.getElementById('Redsocial_Observacion_error').innerHTML= FV.validate(myFormData.get('Redsocial_Observacion'), 'required')
    band.forEach(element => {//recorre todas la banderas si todas son vacias procede a guardar los datos ya que no existe ninguna restriccion 
        respuesta &= (element == '') ? true : false
    })


    return respuesta;
}
const InsertRedsocial= async()=>{//FUNCION QUE INSERTA LOS DATOS EN LA TABLA DE RED SOCIAL
    let table = document.getElementById('RedsocialTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    newRow.insertCell(0).innerHTML=document.getElementById("PersonaSelectRedsocial").options[document.getElementById("PersonaSelectRedsocial").selectedIndex].text;
    newRow.insertCell(1).innerHTML =document.getElementById('Id_Registro').value;
    newRow.insertCell(2).innerHTML =document.getElementById('PersonaSelectRedsocial').value;
    newRow.insertCell(3).innerHTML =document.getElementById('nombre_perfil').value;
    newRow.insertCell(4).innerHTML =document.getElementById('enlace').value;
    newRow.insertCell(5).innerHTML =document.getElementById('Redsocial_tipo_url').value;
    newRow.insertCell(6).innerHTML =document.getElementById('Redsocial_Observacion').value.toUpperCase();
    
    newRow.insertCell(7).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoRedsocial${newRow.rowIndex}">
                                        <div class="form-group">
                                            <input type="file" name="FotoRedsocial_row${newRow.rowIndex}" accept="image/*" id="fileFotoRedsocial_row${newRow.rowIndex}" class="inputfile uploadFileFotoRedsocial" onchange="uploadFileRedsocial(event)" data-toggle="tooltip" data-placement="bottom">
                                            <label for="fileFotoRedsocial_row${newRow.rowIndex}"></label>
                                            <h3 class="uploadFotoRedsocialCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                        </div>
                                    </div>
                                    <div id="imageContentRedsocial_row${newRow.rowIndex}"></div>`;
    newRow.insertCell(8).innerHTML =document.getElementById('captura_dato_Redsocial').value.toUpperCase();
    newRow.insertCell(9).innerHTML =`<button type="button" class="btn btn-add" onclick="editRedsocial(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc" value="-" onclick="deleteRowRedsocial(this,RedsocialTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
    newRow.insertCell(10).innerHTML = document.getElementById('Id_Seg_Redes').value;
    newRow.cells[1].style.display = "none";
    newRow.cells[2].style.display = "none";
    newRow.cells[10].style.display = "none";
}
const ResetFormRedsocial= async()=>{//FUNCION QUE LIMPIA LA VISTA DE RED SOCIAL
    document.getElementById('Id_Registro').value='SD';
    document.getElementById('PersonaSelectRedsocial').value='SD';
    document.getElementById('nombre_perfil').value='';
    document.getElementById('enlace').value='';
    document.getElementById('Redsocial_tipo_url').value='SD';
    document.getElementById('Redsocial_Observacion').value='';
}
const editRedsocial = (obj) => {//FUNCION QUE EDITA LA TABLA DE RED SOCIAL TOMANDO LOS DATOS DE LA ROW SELECCIONADA Y ACOMODANDO LOS DATOS EN LA VISTA PARA SU EDICION
    document.getElementById('alertaEditRedsocial').style.display = 'block';
    selectedRowRedsocial = obj.parentElement.parentElement;
    document.getElementById('Id_Registro').value=selectedRowRedsocial.cells[1].innerHTML;
    document.getElementById('PersonaSelectRedsocial').value=selectedRowRedsocial.cells[2].innerHTML;
    document.getElementById('nombre_perfil').value=selectedRowRedsocial.cells[3].innerHTML;
    document.getElementById('enlace').value=selectedRowRedsocial.cells[4].innerHTML;
    document.getElementById('Redsocial_tipo_url').value=selectedRowRedsocial.cells[5].innerHTML;
    document.getElementById('Redsocial_Observacion').value=selectedRowRedsocial.cells[6].innerHTML;
    document.getElementById('Id_Seg_Redes').value=selectedRowRedsocial.cells[10].innerHTML;

    window.scroll({
        top: 0,
        left: 100,
        behavior: 'smooth'
    });
}
const UpdateRowRedsocial=()=>{//FUNCION QUE ACTUALIZA LOS DATOS EN LA TABLA DE RED SOCIAL
    selectedRowRedsocial.cells[0].innerHTML=document.getElementById("PersonaSelectRedsocial").options[document.getElementById("PersonaSelectRedsocial").selectedIndex].text;
    selectedRowRedsocial.cells[1].innerHTML=document.getElementById('Id_Registro').value;
    selectedRowRedsocial.cells[2].innerHTML=document.getElementById('PersonaSelectRedsocial').value;
    selectedRowRedsocial.cells[3].innerHTML=document.getElementById('nombre_perfil').value;
    selectedRowRedsocial.cells[4].innerHTML=document.getElementById('enlace').value;
    selectedRowRedsocial.cells[5].innerHTML=document.getElementById('Redsocial_tipo_url').value;
    selectedRowRedsocial.cells[6].innerHTML=document.getElementById('Redsocial_Observacion').value.toUpperCase();
    selectedRowRedsocial.cells[10].innerHTML=document.getElementById('Id_Seg_Redes').value;
    document.getElementById('alertaEditRedsocial').style.display = 'none';
    selectedRowRedsocial= null;
}
/*--------------------------FUNCIONES PARA EL ALMACENAMIENTO DE LOS DATOS CONTENIDOS EN EL FRAME  --------------- */
var msg_RedSocialError = document.getElementById('msg_principales_Redsocial');
var datosRedSocial = document.getElementById('datos_Redsocial')
document.getElementById('btn_redSocial').addEventListener('click', async function(e) {
    let myFormDataRedSocial = new FormData(datosRedSocial)//RECUERDA SIEMPRE ENVIAR LOS DATOS DEL FRAME Y AUN MAS IMPORTANTE POR LAS IMAGENES LAS DETECTE EN EL POST
    var RedesSociales =  await readTableRedSocial();//LEEMOS EL CONTENIDO DE LA TABLA DE Domicilios 
    myFormDataRedSocial.append('RedesSociales_table', JSON.stringify(RedesSociales));
    myFormDataRedSocial.append('id_seguimiento',document.getElementById('id_seguimiento_principales').value)
    let button = document.getElementById('btn_redSocial')
    button.innerHTML = `
        Guardando
        <div class="spinner-grow spinner-grow-sm" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    `;
    button.classList.add('disabled-link');
    $('#ModalCenterPrincipalredSocial').modal('show');
    fetch(base_url_js + 'Seguimientos/UpdateRedesSocialesFetch', {//realiza el fetch para actualizar los datos
        method: 'POST',
        body: myFormDataRedSocial
    })

    .then(res => res.json())

    .then(data => {//obtine  respuesta del modelo
        button.innerHTML = `Guardar`;
        button.classList.remove('disabled-link');//se vuelve activar la funcion del boton 
        $('#ModalCenterPrincipalredSocial').modal('hide');//se quita la imagen 
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
            msg_RedSocialError.innerHTML = messageError
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });
        } else {//si todo salio bien
            alertaRedesSociales()//Si todo salio bien actualiza los datos arroja un mensaje satisfactorio
        }
    })
})
const alertaRedesSociales = async()=>{//FUNCION QUE AVISA QUE LOS DATOS HAN SIDO ACTUALIZADOS CORRECTAMENTE
    await RecargaDatosRedesSociales();
   msg_RedSocialError.innerHTML = `<div class="alert alert-success text-center" role="success">Datos de Redes Sociales Actualizados correctamente.
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
const readTableRedSocial = async() => {//lee los datos de la tabla personas y genera una estructura deacuerdo a los datos contenido es la tabla
    let table = document.getElementById('RedsocialTable');
    let objetos = [];
    for (let i = 1; i < table.rows.length; i++) {
        let input = table.rows[i].cells[7].children[1].children[1];
        if (input != undefined) {//si en el contenedor existe alguna imagen verifica el tipo
            let type = table.rows[i].cells[7].children[1].children[2].classList[1]
            let base64 = document.getElementById('imagesRedSocial_row_' + i);
            nameImage = 'FotoRedsocial_row' + i;
            isPNG = base64.src.split('.');
            if (type != 'File') {//funcion para leer las fotos
                if (isPNG[1] != undefined) {
                    await toDataURL(base64.src)//esto es para tipo photo osea que ya se encuentran en el servidor un archivo fisico con extension diferente a la png
                        .then(myBase64 => {
                            objetos.push({
                                ['row']: {
                                    Id_Registro: table.rows[i].cells[1].innerHTML,
                                    Id_Persona : table.rows[i].cells[2].innerHTML,
                                    Usuario: table.rows[i].cells[3].innerHTML,
                                    Enlace: table.rows[i].cells[4].innerHTML,
                                    Tipo_Enlace: table.rows[i].cells[5].innerHTML,
                                    Observacion_Enlace: table.rows[i].cells[6].innerHTML,
                                    Capturo: table.rows[i].cells[8].innerHTML,
                                    typeImage: type,
                                    nameImage: nameImage,
                                    image: myBase64,
                                    imagebase64:myBase64,
                                    Id_Seguimiento: table.rows[i].cells[10].innerHTML
                                }
                            });
                        })
                } else {//esto es para tipo photo osea que ya se encuentran en el servidor un archivo fisico con extension png
                    objetos.push({
                        ['row']: {
                            Id_Registro: table.rows[i].cells[1].innerHTML,
                            Id_Persona : table.rows[i].cells[2].innerHTML,
                            Usuario: table.rows[i].cells[3].innerHTML,
                            Enlace: table.rows[i].cells[4].innerHTML,
                            Tipo_Enlace: table.rows[i].cells[5].innerHTML,
                            Observacion_Enlace: table.rows[i].cells[6].innerHTML,
                            Capturo: table.rows[i].cells[8].innerHTML,
                            typeImage: type,
                            nameImage: nameImage,
                            image:  base64.src,
                            imagebase64:base64.src,
                            Id_Seguimiento: table.rows[i].cells[10].innerHTML
                        }
                    });
                }
            }else{// si es tipo file guarda los datos de la imagen
                let aux=document.getElementById('fileFotoRedsocial_row'+i)
                let base64URL = await encodeFileAsBase64URL(aux.files[0]);
                objetos.push({
                    ['row']: {
                        Id_Registro: table.rows[i].cells[1].innerHTML,
                        Id_Persona : table.rows[i].cells[2].innerHTML,
                        Usuario: table.rows[i].cells[3].innerHTML,
                        Enlace: table.rows[i].cells[4].innerHTML,
                        Tipo_Enlace: table.rows[i].cells[5].innerHTML,
                        Observacion_Enlace: table.rows[i].cells[6].innerHTML,
                        Capturo: table.rows[i].cells[8].innerHTML,
                        typeImage: type,
                        nameImage: nameImage,
                        image: "null",
                        imagebase64:base64URL,
                        Id_Seguimiento: table.rows[i].cells[10].innerHTML
                    }
                });
            }
        } else {//si no hay imagen solo almacena los datos el texto 
            objetos.push({
                ['row']: {
                    Id_Registro: table.rows[i].cells[1].innerHTML,
                    Id_Persona : table.rows[i].cells[2].innerHTML,
                    Usuario: table.rows[i].cells[3].innerHTML,
                    Enlace: table.rows[i].cells[4].innerHTML,
                    Tipo_Enlace: table.rows[i].cells[5].innerHTML,
                    Observacion_Enlace: table.rows[i].cells[6].innerHTML,
                    Capturo: table.rows[i].cells[8].innerHTML,
                    typeImage: "null",
                    nameImage: "null",
                    image: "null",
                    imagebase64:"null",
                    Id_Seguimiento: table.rows[i].cells[10].innerHTML
                }
            });
        }
    }
    console.log(objetos)
    return objetos;
}
const deleteRowRedsocial = async(obj, tableId) => {//funcion para eliminar una fila en tablas ademas de funcion especial de eliminacion para las tablas personas
    if (confirm('¿Desea eliminar este elemento?')) {
        let row = obj.parentElement.parentElement;
        aux=row.rowIndex;
        if(row.cells[1].innerHTML!='SD'){
           await DesasociaRedSocial(row.cells[1].innerHTML);
        }
        document.getElementById(tableId.id).deleteRow(row.rowIndex);
        j=aux+1;
        if(tableId.id=='RedsocialTable'){//Si es la tabla de personas hace una eliminacion especial debido a las fotos
            table = document.getElementById('RedsocialTable')
            j=aux+1;
            for(let i=aux;i<table.rows.length;i++){
                let contenedorImg =table.rows[i].cells[7].children[1];
                contenedorImg.setAttribute('id', 'imageContentRedsocial_row'+i);
                if(contenedorImg.childNodes.length>1){
                    if(contenedorImg.childNodes[1].childNodes[1]!=undefined){
                        contenedorImg.childNodes[1].childNodes[1].setAttribute('onclick', "deleteImageFotoRedSocial("+i+")");
                        contenedorImg.childNodes[3].setAttribute('id', 'imagesRedSocial_row_'+i);
                        contenedorImg.childNodes[3].setAttribute('data-target', '#ModalCenterRedSocial'+i);
                        if(contenedorImg.childNodes[5].getAttribute('class')==j+' Photo'){
                            contenedorImg.childNodes[5].setAttribute('class', i+' Photo');
                        }else{
                            contenedorImg.childNodes[5].setAttribute('class', i+' File');
                        }
                        contenedorImg.childNodes[7].setAttribute('id', 'ModalCenterRedSocial'+i);
                    }else{
                        contenedorImg.childNodes[0].childNodes[1].setAttribute('onclick', "deleteImageFotoRedSocial("+i+")");
                        contenedorImg.childNodes[2].setAttribute('id', 'imagesRedSocial_row_'+i);
                        contenedorImg.childNodes[2].setAttribute('data-target', '#ModalCenterRedSocial'+i);
    
                        if(contenedorImg.childNodes[4].getAttribute('class')==j+' Photo'){
                            contenedorImg.childNodes[4].setAttribute('class', i+' Photo');
                        }else{
                            contenedorImg.childNodes[4].setAttribute('class', i+' File');
                        }
                        contenedorImg.childNodes[6].setAttribute('id', 'ModalCenterRedSocial'+i);
                    }  
                }
                let contenedorInput =table.rows[i].cells[7].children[0];
                contenedorInput.setAttribute('id', 'uploadFileFotoRedsocial'+i);
                contenedorInput.childNodes[1].childNodes[1].setAttribute('id', 'fileFotoRedsocial_row'+i);
                contenedorInput.childNodes[1].childNodes[1].setAttribute('name', 'FotoRedsocial_row'+i);
                contenedorInput.childNodes[1].childNodes[3].setAttribute('for', 'fileFotoRedsocial_row'+i);
                j++;
            }
        }
        await RecargaDatosRedesSociales();
    }
}
//--------------FUNCIONES PARA LAS FOTOS DE LA TABLA DE RED SOCIAL--------------------- 
function uploadFileRedsocial(event, type) {//Funcion para actualizar las imagenes de la tabla de personas
    let file;
    if (type) {
        file = 'Photo';
    } else {
        file = 'File';
    }
    if (event.currentTarget.classList.contains('uploadFileFotoRedsocial')) {//TABLA DE Redsocial
        if (validateImage(event.target)) {
            const src = URL.createObjectURL(event.target.files[0]);
            const row = event.currentTarget;
            const index = row.parentNode.parentNode.parentNode.parentNode.rowIndex;
            createElementFotoRedSocial(src, index, 'File');
        } else {
            msg_RedSocialError.innerHTML = '<div class="alert alert-warning text-center" role="alert">Verificar el archivo cargado.<br>Posibles errores: <br> - Archivo muy pesado (Máximo 8 megas). <br> -Extensión no aceptada (Extensiones aceptadas: jpeg, png, jpg, PNG).</div>';
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });
        }
    }
}
function createElementFotoRedSocial(src, index, type, view) {//FUNCION PARA LA VISUALIZACION DE IMAGENES EN LA TABLA DE Redsocial
    const div = document.getElementById('imageContentRedsocial_row' + index);
    if (view === undefined) {
        div.innerHTML = `<div class="d-flex justify-content-end">
                            <span onclick="deleteImageFotoRedSocial(${index})" class="deleteFile">X</span>
                        </div>
                        <img name="nor" src="${src}" id="imagesRedSocial_row_${index}" width="400px" data-toggle="modal" data-target="#ModalCenterRedSocial${index}">
                        <input type="hidden" class="${index} ${type}"/>
                        <div class="modal fade " id="ModalCenterRedSocial${index}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
function deleteImageFotoRedSocial(index) {//FUNCION QUE ELIMINA LA VISUALIZACION Y EL CONTENIDO DE LAS IMAGENES
    const div = document.getElementById('imageContentRedsocial_row' + index);
    document.getElementById('fileFotoRedsocial_row' + index).value = '';
    div.innerHTML = '';
}
const DesasociaRedSocial= async(Id_Registro)=>{//FUNCION QUE ELIMINA LOS DATOS DE LAS TABLAS DE PERSONAS
    try {
        myFormData.append('Id_Registro',Id_Registro)
        const response = await fetch(base_url_js + 'Seguimientos/DesasociaRedSocial', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const cambioID_SeguimientoRedes = async() =>{
    let buscar = document.getElementById('PersonaSelectRedsocial').value;
    let person = await findPersonById(buscar);
    document.getElementById('Id_Seg_Redes').value = person.Id_Seguimiento;
 }

document.getElementById('PersonaSelectRedsocial').addEventListener('change',cambioID_SeguimientoRedes);