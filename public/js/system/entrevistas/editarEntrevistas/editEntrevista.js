/* ----- ----- ----- Funcionalidades de tabla de Entrevistas ----- ----- ----- */
let selectedRowEntrevistas = null;

const onFormEntrevistasSubmit = ()=>{

    const campos = ['id_entrevista','id_persona_entrevista','indicativo_entrevistador','alias_referidos','relevancia','entrevista','fecha_entrevista','hora_entrevista','captura_entrevistas'];
    
    if(validateFormEntrevista()){
        let formData = readFormDataEntrevistas(campos);
        if(selectedRowEntrevistas === null){
            insertNewRowEntrevistas(formData);
        }else{
            updateRowEntrevistas(formData);
        }    
        resetFormEntrevistas(campos);
    }
}

const readFormDataEntrevistas = (campos)=>{
    let formData = {};
    for(let i=0; i<campos.length;i++){ 
        formData[campos[i]] = document.getElementById(campos[i]).value;
    }
    return formData;
}
/*--------------FUNCIONES PARA LAS FOTOS DE LA TABLA DE FORENSIAS--------------------- */
function uploadFileEntrevista(event, type) {//Funcion para actualizar las imagenes de la tabla de personas
    let file;
    if (type) {
        file = 'Photo';
    } else {
        file = 'File';
    }
    if (event.currentTarget.classList.contains('uploadFileFotoEntrevista')) {//TABLA DE FORENSIAS
        if (validateImage(event.target)) {
            const src = URL.createObjectURL(event.target.files[0]);
            const row = event.currentTarget;
            const index = row.parentNode.parentNode.parentNode.parentNode.rowIndex;
            createElementFotoEntrevista(src, index, 'File');
        } else {
            msg_principales_entrevistas.innerHTML = '<div class="alert alert-warning text-center" role="alert">Verificar el archivo cargado.<br>Posibles errores: <br> - Archivo muy pesado (Máximo 8 megas). <br> -Extensión no aceptada (Extensiones aceptadas: jpeg, png, jpg, PNG).</div>';
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });
        }
    }
}
function createElementFotoEntrevista(src, index, type, view) {//FUNCION PARA LA VISUALIZACION DE IMAGENES EN LA TABLA DE FORENSIAS
    const div = document.getElementById('imageContentEntrevista_row' + index);
    if (view === undefined) {
        div.innerHTML = `<div class="d-flex justify-content-end">
                            <span onclick="deleteImageFotoEntrevista(${index})" class="deleteFile">X</span>
                        </div>
                        <img name="nor" src="${src}" id="imagesEntrevista_row_${index}" width="400px" data-toggle="modal" data-target="#ModalCenterEntrevista${index}">
                        <input type="hidden" class="${index} ${type}"/>
                        <div class="modal fade " id="ModalCenterEntrevista${index}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
function deleteImageFotoEntrevista(index) {//FUNCION QUE ELIMINA LA VISUALIZACION Y EL CONTENIDO DE LAS IMAGENES
    const div = document.getElementById('imageContentEntrevista_row' + index);
    document.getElementById('fileFotoEntrevista_row' + index).value = '';
    div.innerHTML = '';
}

const insertNewRowEntrevistas = ({id_entrevista,id_persona_entrevista,indicativo_entrevistador,alias_referidos,relevancia,entrevista,fecha_entrevista,hora_entrevista,captura_entrevistas})=>{//Funcion para insertar una nueva entrevista a la tabla
    const table = document.getElementById('entrevistasTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    newRow.insertCell(0).innerHTML = id_entrevista;
    newRow.insertCell(1).innerHTML = id_persona_entrevista;
    //newRow.insertCell(2).innerHTML = Id_persona_seguimiento;
    newRow.insertCell(2).innerHTML = indicativo_entrevistador;
    newRow.insertCell(3).innerHTML = (alias_referidos.trim()=='')?'SD':alias_referidos.toUpperCase();
    newRow.insertCell(4).innerHTML = relevancia;
    newRow.insertCell(5).innerHTML = entrevista.toUpperCase();
    newRow.insertCell(6).innerHTML = fecha_entrevista;
    newRow.insertCell(7).innerHTML = hora_entrevista;
    newRow.insertCell(8).innerHTML = `<div class="d-flex justify-content-around" id="uploadFileFotoEntrevista${newRow.rowIndex}">
                                        <div class="form-group">
                                            <input type="file" name="FotoEntrevista_row${newRow.rowIndex}" accept="image/*" id="fileFotoEntrevista_row${newRow.rowIndex}" class="inputfile uploadFileFotoEntrevista" onchange="uploadFileEntrevista(event)" data-toggle="tooltip" data-placement="bottom">
                                            <label for="fileFotoEntrevista_row${newRow.rowIndex}"></label>
                                        </div>
                                    </div>
                                    <div id="imageContentEntrevista_row${newRow.rowIndex}"></div>`;
    newRow.insertCell(9).innerHTML = captura_entrevistas;
    newRow.insertCell(10).innerHTML = `<button type="button" class="btn btn-add d-flex" onclick="editEntrevistas(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button><br>
                                    <button type="button" class="btn btn-ssc d-flex" value="-" onclick="deleteEntrevista(this)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
    //newRow.cells[0].style.display = "none";
    newRow.cells[1].style.display = "none";
}

const editEntrevistas = (obj)=>{//Funcion para editar entrevista de la tabla
    selectedRowEntrevistas = obj.parentElement.parentElement;
    document.getElementById('alertEditEntrevista').style.display = 'block';
    document.getElementById('id_entrevista').value = selectedRowEntrevistas.cells[0].innerHTML;;
    document.getElementById('id_persona_entrevista').value = selectedRowEntrevistas.cells[1].innerHTML;
    //document.getElementById('Id_persona_seguimiento').value = selectedRowEntrevistas.cells[2].innerHTML;
    document.getElementById('indicativo_entrevistador').value = selectedRowEntrevistas.cells[2].innerHTML;
    document.getElementById('alias_referidos').value = (selectedRowEntrevistas.cells[3].innerHTML=='SD')?'':selectedRowEntrevistas.cells[3].innerHTML;
    document.getElementById('relevancia').value = selectedRowEntrevistas.cells[4].innerHTML;
    document.getElementById('entrevista').value = selectedRowEntrevistas.cells[5].innerHTML;
    document.getElementById('fecha_entrevista').value = selectedRowEntrevistas.cells[6].innerHTML;
    document.getElementById('hora_entrevista').value = selectedRowEntrevistas.cells[7].innerHTML;
    
}

const updateRowEntrevistas = ({id_entrevista,id_persona_entrevista,indicativo_entrevistador,alias_referidos,relevancia,entrevista,fecha_entrevista,hora_entrevista,captura_entrevistas})=>{//Funcion para actualizar una entrevista de la tabla
    selectedRowEntrevistas.cells[0].innerHTML = id_entrevista;
    selectedRowEntrevistas.cells[1].innerHTML = id_persona_entrevista;
    ///selectedRowEntrevistas.cells[2].innerHTML = Id_persona_seguimiento;
    selectedRowEntrevistas.cells[2].innerHTML = indicativo_entrevistador;
    selectedRowEntrevistas.cells[3].innerHTML = (alias_referidos.trim()=='')?'SD':alias_referidos.toUpperCase();
    selectedRowEntrevistas.cells[4].innerHTML = relevancia;
    selectedRowEntrevistas.cells[5].innerHTML = entrevista.toUpperCase();
    selectedRowEntrevistas.cells[6].innerHTML = fecha_entrevista;
    selectedRowEntrevistas.cells[7].innerHTML = hora_entrevista;
    document.getElementById('alertEditEntrevista').style.display = 'none';
}

const resetFormEntrevistas = (campos)=>{//Funcion para resetear contenedor vista

    for(let i=0;i<campos.length;i++){
        if((campos[i]!='captura_entrevistas')&&(campos[i]!='id_persona_entrevista'))
        document.getElementById(campos[i]).value='';
    }
    document.getElementById('id_entrevista').value = 'SD';
    //document.getElementById('Id_persona_seguimiento').value = 'SD';
    document.getElementById('indicativo_entrevistador').value = 'SD';
    document.getElementById('relevancia').value = 'SD';
    document.getElementById('fecha_entrevista').value = getFecha();
    document.getElementById('hora_entrevista').value = getHora();
    selectedRowEntrevistas = null;

}

const deleteEntrevista = async(obj)=>{//Funcion para eliminar una entrevista
    if(confirm('¿Desea eliminar este elemento?')){

        const row = obj.parentElement.parentElement;
        if(row.cells[0].innerHTML!='SD'){
            await DesasociaEntrevista(row.cells[0].innerHTML,document.getElementById('id_persona_entrevista').value);
        }
        table = document.getElementById('entrevistasTable');
        table.deleteRow(row.rowIndex);
        await RecargaDatosEntrevistas();
    }
}
const DesasociaEntrevista= async(Id_Entrevista,Id_Persona_Entrevista)=>{//FUNCION QUE ELIMINA LOS DATOS DE LAS TABLAS DE ENTREVISTAS
    try {
        myFormData.append('Id_Entrevista',Id_Entrevista)
        myFormData.append('Id_Persona_Entrevista',Id_Persona_Entrevista )
        const response = await fetch(base_url_js + 'Entrevistas/DesasociaEntrevista', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
    
}

const validateFormEntrevista = () =>{//Funcion para validar la entrevista antes que se meta a la tabla 
    let isValid = true;
    if(document.getElementById('indicativo_entrevistador').value === "SD"){
        isValid = false;
        document.getElementById('indicativo_entrevistador_error').innerText = 'Debe de especificar el indicativo del entrevistador';
    }else{
        document.getElementById('indicativo_entrevistador_error').innerText = '';
    }
    if(document.getElementById('relevancia').value === "SD"){
        isValid = false;
        document.getElementById('relevancia_error').innerText = 'Debe de especificar la relevancia';
    }else{
        document.getElementById('relevancia_error').innerText = '';
    }
    if(document.getElementById('entrevista').value.trim() == ""){
        isValid = false;
        
        document.getElementById('entrevista_error').innerText = 'Debe ingresar la entrevista';
    }else{
        document.getElementById('entrevista_error').innerText = '';
    }
    return isValid;
}
/*--------------- FUNCIONES PARA VALIDAR Y GUARDAR -------------------------*/
var dataEntrevistas = document.getElementById('datos_Entrevistas')
var msg_principales_entrevistas = document.getElementById('msg_principales_entrevistas')

document.getElementById('btn_principalEntrevistas').addEventListener('click', async function(e) {// funcion se activa en dar guardar entrevistas tab
    e.preventDefault()
    var myFormDataEntrevistas = new FormData(dataEntrevistas)
    button = document.getElementById('btn_principalEntrevistas')
    button.innerHTML = `Guardando
        <div class="spinner-grow spinner-grow-sm" role="status">
            <span class="sr-only">Loading...</span>
        </div>`;
    button.classList.add('disabled-link');//se desactiva el boton de guardar hasta que halla una respuesta del controlador
    $('#ModalCenterEntrevista').modal('show');//Mostramos una imagen completa en pantalla hasta que halla una respuesta del controlador
    myFormDataEntrevistas.append('id_persona_entrevista',document.getElementById('id_persona_entrevista').value);
    var Entrevistas =  await readTableEntrevistas();//LEEMOS EL CONTENIDO DE LA TABLA DE Forencias 
    myFormDataEntrevistas.append('entrevistas_table', JSON.stringify(Entrevistas));
    fetch(base_url_js + 'Entrevistas/updateEntrevistasFetch', {//REALIZA FETCH PARA LA ACTUALIZACION DE LA TAB DE LAS ENTREVISTAS
            method: 'POST',
            body: myFormDataEntrevistas
    })
    .then(res => res.json())
    .then(data => {//Espera una respuesta he informa el usuario el estado de la transaccion
        button.innerHTML = `Guardar entrevistas`;
        button.classList.remove('disabled-link');//se vuelve activar la funcion del boton
        $('#ModalCenterEntrevista').modal('hide');//se quita la imagen  
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
                messageError = '<div class="alert alert-danger text-center" role="alert">Por favor, revisa nuevamente el formulario ya que hubo un error </div>';
                
            }
            console.log(data.error_sql)
            msg_principales_entrevistas.innerHTML = messageError
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });
        }else {//si todo salio bien
            alertaUpdateEntrevista()//Si todo se valido bien y se inserto correctamente se arroja un mensaje satisfactorio y redirige a la pestaña principal del gestor de casos
        }
    })
        /*for (var pair of myFormData.entries()) {
        console.log(pair[0] + ', ' + pair[1]);
    }*/
})
async function alertaUpdateEntrevista()  {//FUNCION PARA AVISAR QUE TODO SALIO BIEN 
    const BtGuardar =document.getElementById("btn_principalEntrevistas");
    BtGuardar.setAttribute('disabled', '');
    msg_principales_entrevistas.innerHTML = `<div class="alert alert-success text-center" role="success">Los datos de la entrevista actualizados correctamente 
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>`;
    window.scroll({
        top: 0,
        left: 100,
        behavior: 'smooth'
    });
    await RecargaDatosEntrevistas();
}

//FUNCIONES PARA GUARDAR INFORMACION EN LA BASE DE DATOS Y REFRESCAR LA INFORMACION DE LA VISTA DE LA TABLA
const readTableEntrevistas = async() => {//lee los datos de la tabla personas y genera una estructura deacuerdo a los datos contenido es la tabla
    let table = document.getElementById('entrevistasTable');
    let objetos = [];
    for (let i = 1; i < table.rows.length; i++) {
        let input = table.rows[i].cells[8].children[1].children[1];
        if (input != undefined) {//si en el contenedor existe alguna imagen verifica el tipo
            let type = table.rows[i].cells[8].children[1].children[2].classList[1]
            let base64 = document.getElementById('imagesEntrevista_row_' + i);
            nameImage = 'FotoEntrevista_row' + i;
            isPNG = base64.src.split('.');
            if (type != 'File') {//funcion para leer las fotos
                if (isPNG[1] != undefined) {
                    await toDataURL(base64.src)//esto es para tipo photo osea que ya se encuentran en el servidor un archivo fisico con extension diferente a la png
                        .then(myBase64 => {
                            objetos.push({
                                ['row']: {
                                    Id_Entrevista: table.rows[i].cells[0].innerHTML,
                                    Id_Persona_Entrevista  : table.rows[i].cells[1].innerHTML,
                                    Indicativo_Entrevistador: table.rows[i].cells[2].innerHTML,
                                    Alias_Referidos: table.rows[i].cells[3].innerHTML,
                                    Relevancia: table.rows[i].cells[4].innerHTML,
                                    Entrevista: table.rows[i].cells[5].innerHTML,
                                    Fecha_Entrevista: table.rows[i].cells[6].innerHTML,
                                    Hora_Entrevista: table.rows[i].cells[7].innerHTML,
                                    Capturo: table.rows[i].cells[9].innerHTML,
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
                            Id_Entrevista: table.rows[i].cells[0].innerHTML,
                            Id_Persona_Entrevista  : table.rows[i].cells[1].innerHTML,
                            Indicativo_Entrevistador: table.rows[i].cells[2].innerHTML,
                            Alias_Referidos: table.rows[i].cells[3].innerHTML,
                            Relevancia: table.rows[i].cells[4].innerHTML,
                            Entrevista: table.rows[i].cells[5].innerHTML,
                            Fecha_Entrevista: table.rows[i].cells[6].innerHTML,
                            Hora_Entrevista: table.rows[i].cells[7].innerHTML,
                            Capturo: table.rows[i].cells[9].innerHTML,
                            typeImage: type,
                            nameImage: nameImage,
                            image:  base64.src,
                            imagebase64:base64.src
                        }
                    });
                }
            }else{// si es tipo file guarda los datos de la imagen
                let aux=document.getElementById('fileFotoEntrevista_row'+i)
                let base64URL = await encodeFileAsBase64URL(aux.files[0]);
                objetos.push({
                    ['row']: {
                        Id_Entrevista: table.rows[i].cells[0].innerHTML,
                        Id_Persona_Entrevista  : table.rows[i].cells[1].innerHTML,
                        Indicativo_Entrevistador: table.rows[i].cells[2].innerHTML,
                        Alias_Referidos: table.rows[i].cells[3].innerHTML,
                        Relevancia: table.rows[i].cells[4].innerHTML,
                        Entrevista: table.rows[i].cells[5].innerHTML,
                        Fecha_Entrevista: table.rows[i].cells[6].innerHTML,
                        Hora_Entrevista: table.rows[i].cells[7].innerHTML,
                        Capturo: table.rows[i].cells[9].innerHTML,
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
                    Id_Entrevista: table.rows[i].cells[0].innerHTML,
                    Id_Persona_Entrevista  : table.rows[i].cells[1].innerHTML,
                    Indicativo_Entrevistador: table.rows[i].cells[2].innerHTML,
                    Alias_Referidos: table.rows[i].cells[3].innerHTML,
                    Relevancia: table.rows[i].cells[4].innerHTML,
                    Entrevista: table.rows[i].cells[5].innerHTML,
                    Fecha_Entrevista: table.rows[i].cells[6].innerHTML,
                    Hora_Entrevista: table.rows[i].cells[7].innerHTML,
                    Capturo: table.rows[i].cells[9].innerHTML,
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
