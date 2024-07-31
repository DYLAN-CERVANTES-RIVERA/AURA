document.getElementById('puebla_ubicacion').addEventListener('change',()=>{
    showUbicacionForanea();
});
document.getElementById('foraneo_ubicacion').addEventListener('change',()=>{
    showUbicacionForanea();
});
const showUbicacionForanea = () =>{//Funcion de habilitado para la edicion del estado
    radio = document.getElementsByName('ubicacion_puebla')
    if(radio[0].checked){
        document.getElementById('Estado').setAttribute('disabled', '');
        document.getElementById('Estado').value='PUEBLA';
        document.getElementById('Municipio').value='PUEBLA';
        document.getElementById('Municipio').setAttribute('disabled', '');
        document.getElementById('Es_Foraneo').classList.add('mi_hide');
        document.getElementById('Estado_error').innerHTML='';
    }else if(radio[1].checked){
        document.getElementById('Estado').removeAttribute("disabled");
        document.getElementById('Estado').value='SD';
        document.getElementById('Municipio').value='';
        document.getElementById('Municipio').removeAttribute("disabled");
        document.getElementById('Es_Foraneo').classList.remove('mi_hide');
        document.getElementById('Estado_error').innerHTML='';
    }
}
/*----------------------FUNCIONES DE LA TABLA DE DOMICILIOS----------------- */
let selectedRowUbicaciones = null//VARIABLE QUE DEFINE SI SE TRATA DE UNA ACTUALIZACION DE UN REGISTRO YA CREADO O DE UNA NUEVA INSERCION
let radioTipo = document.getElementsByName('tipo_dato_ubicacion');
const onFormUbicacionsubmit=async()=>{
    if(await ValidatableUbicacion()){
        if (selectedRowUbicaciones === null){
            InsertUbicacion();//INSERTA NUEVA FILA EN LA TABLA DE DOMICILIOS
            ResetFormUbicacion();//LIMPIA LA VISTA 
        }else{
            UpdateRowUbicacion();//ACTUALIZA LA FILA SELECCIONADA EN LA TABLA DE DOMICILIOS
            ResetFormUbicacion();//LIMPIA LA VISTA
        }
    }
}

const ValidatableUbicacion = async() => {//FUNCION QUE VALIDA LAS ENTRADAS DEL FORMULARIO DE DOMICILIO PARA QUE SE INGRESE EN LA TABLA
    let FV = new FormValidator()
    let myFormData = new FormData(document.getElementById('datos_ubicaciones'))
    let band = []
    let respuesta=true;
    let i = 0;
    let regexY = /^\d*\.\d*$/;//coordenada positiva osea la Y
    let regexX = /^-\d*\.\d+$/ //coordenada negativa osea la X
    let regexF = /^-?\d*\.\d+$/ //para foraneo 
    band[i++]=document.getElementById('Colonia_principales_error').innerHTML= FV.validate(myFormData.get('Colonia'), 'required')
    band[i++]=document.getElementById('Calle_principales_error').innerHTML= FV.validate(myFormData.get('Calle'), 'required')
    band[i++]=document.getElementById('cordY_principales_error').innerHTML= FV.validate(myFormData.get('cordY'), 'required | numeric')
    band[i++]=document.getElementById('cordX_principales_error').innerHTML= FV.validate(myFormData.get('cordX'), 'required | numeric')
    band.forEach(element => {//recorre todas la banderas si todas son vacias procede a guardar los datos ya que no existe ninguna restriccion 
        respuesta &= (element == '') ? true : false
    })
    radio = document.getElementsByName('ubicacion_puebla')
    if(respuesta==1 && radio[0].checked){
        let coloniasCatalogo = await getAllColonias();
        let inputColoniaValue = createObjectColonia (document.getElementById('Colonia').value);
        let result = coloniasCatalogo.find( colonia => (colonia.Tipo == inputColoniaValue.Tipo && colonia.Colonia == inputColoniaValue.Colonia) )
        if(result){
            document.getElementById('Colonia_principales_error').innerHTML= ''
        }else{
            document.getElementById('Colonia_principales_error').innerHTML= 'Ingrese una Colonia Valida'
            respuesta=false;
        }
        let callesCatalogo = await getAllCalles();
        let result2 = callesCatalogo.find(element => element.Calle == document.getElementById('Calle').value);
        if (result2){
            document.getElementById('Calle_principales_error').innerHTML= ''
        }else{
            document.getElementById('Calle_principales_error').innerHTML= 'Ingresa una Calle Valida'
            respuesta=false;
        }
        if (!regexX.test(document.getElementById('cordX').value)) {
            console.log('error en la coordenada X');
            document.getElementById('cordX_principales_error').innerText = 'La coordenada X no es correcta';
            respuesta = false;
        }else{
            document.getElementById('cordX_principales_error').innerText = '';
        }
    
        if (!regexY.test(document.getElementById('cordY').value)) {
            console.log('error en la coordenada Y');
            document.getElementById('cordY_principales_error').innerText = 'La coordenada Y no es correcta';
            respuesta = false;
        }else{
            document.getElementById('cordY_principales_error').innerText = '';
        }   
    }else{
        if(radio[1].checked){
            document.getElementById('Estado').value=='SD'?document.getElementById('Estado_error').innerHTML='Seleccione un Estado':document.getElementById('Estado_error').innerHTML='';
            respuesta=(document.getElementById('Estado').value=='SD')?false:respuesta; 
        }
        if (!regexF.test(document.getElementById('cordX').value)) {
            console.log('error en la coordenada X');
            document.getElementById('cordX_principales_error').innerText = 'La coordenada X no es correcta';
            respuesta = false;
        }else{
            document.getElementById('cordX_principales_error').innerText = '';
        }
    
        if (!regexF.test(document.getElementById('cordY').value)) {
            console.log('error en la coordenada Y');
            document.getElementById('cordY_principales_error').innerText = 'La coordenada Y no es correcta';
            respuesta = false;
        }else{
            document.getElementById('cordY_principales_error').innerText = '';
        }
    }
    return respuesta;
}

const InsertUbicacion= async()=>{//FUNCION QUE INSERTA LOS DATOS EN LA TABLA DE DOMICILIO
    let table = document.getElementById('UbicacionTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    if(radioTipo[0].checked && document.getElementById('UbicacionSelect').value!= -1){
        tipo = 'ENTREVISTA';
    }else if(radioTipo[1].checked && document.getElementById('UbicacionSelect').value!= -1){
        tipo = 'DATO';
    }else{
        tipo = 'SD';
    }
   
    newRow.insertCell(0).innerHTML = document.getElementById('UbicacionSelect').value;
    newRow.insertCell(1).innerHTML = tipo;
    //newRow.insertCell(2).innerHTML = document.getElementById('PersonaSelectUbicaciones').value;

    newRow.insertCell(2).innerHTML = document.getElementById('Colonia').value.toUpperCase();
    newRow.insertCell(3).innerHTML = document.getElementById('Calle').value.toUpperCase();
    newRow.insertCell(4).innerHTML = (document.getElementById('Calle2').value!='')?document.getElementById('Calle2').value.toUpperCase():'SD';
    newRow.insertCell(5).innerHTML = (document.getElementById('no_Ext').value!='')?document.getElementById('no_Ext').value.toUpperCase():'SD';
    newRow.insertCell(6).innerHTML = (document.getElementById('no_Int').value!='')?document.getElementById('no_Int').value.toUpperCase():'SD';
    newRow.insertCell(7).innerHTML = (document.getElementById('CP').value!='')?document.getElementById('CP').value.toUpperCase():'SD';
    newRow.insertCell(8).innerHTML = document.getElementById('cordY').value
    newRow.insertCell(9).innerHTML = document.getElementById('cordX').value
    radio = document.getElementsByName('ubicacion_puebla')
    if(radio[0].checked){
        newRow.insertCell(10).innerHTML='PUEBLA';
        newRow.insertCell(11).innerHTML='PUEBLA';
        newRow.insertCell(12).innerHTML='NO';
    }else if(radio[1].checked){
        newRow.insertCell(10).innerHTML=document.getElementById('Estado').value
        newRow.insertCell(11).innerHTML=(document.getElementById('Municipio').value.trim()=='')?'SD':document.getElementById('Municipio').value.toUpperCase();
        newRow.insertCell(12).innerHTML='SI';
    }
    newRow.insertCell(13).innerHTML =(document.getElementById('Observacion_Ubicacion_descripcion').value!='')?document.getElementById('Observacion_Ubicacion_descripcion').value.toUpperCase():'SD';
    newRow.insertCell(14).innerHTML =(document.getElementById('Link_Ubicacion').value!='')?document.getElementById('Link_Ubicacion').value:'SD';
    newRow.insertCell(15).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoUbicacion${newRow.rowIndex}">
                                        <div class="form-group">
                                            <input type="file" name="FotoUbicacion_row${newRow.rowIndex}" accept="image/*" id="fileFotoUbicacion_row${newRow.rowIndex}" class="inputfile uploadFileFotoUbicacion" onchange="uploadFileUbicacion(event)" data-toggle="tooltip" data-placement="bottom">
                                            <label for="fileFotoUbicacion_row${newRow.rowIndex}"></label>
                                            <h3 class="uploadFotoUbicacionCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                        </div>
                                    </div>
                                    <div id="imageContentUbicacion_row${newRow.rowIndex}"></div>`;
    newRow.insertCell(16).innerHTML = document.getElementById('Id_Ubicacion').value;
    newRow.insertCell(17).innerHTML = document.getElementById('captura_dato_ubicaciones').value.toUpperCase();
    newRow.insertCell(18).innerHTML =`<button type="button" class="btn btn-add" onclick="editUbicacion(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc" value="-" onclick="deleteRowDomicilio(this,UbicacionTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
    

    
    //newRow.cells[0].style.display = "none";
    //newRow.cells[1].style.display = "none";
}
const ResetFormUbicacion = async ()=>{//FUNCION QUE LIMPIA LA VISTA
    document.getElementById('Id_Ubicacion').value='SD';
    radio[0].checked=true;
    showUbicacionForanea();
    document.getElementById('UbicacionSelect').value = -1;
    document.getElementById('Colonia').value='';
    document.getElementById('Calle').value='';
    document.getElementById('Calle2').value='';
    document.getElementById('no_Ext').value='';
    document.getElementById('no_Int').value='';
    document.getElementById('CP').value='';
    document.getElementById('cordY').value='';
    document.getElementById('cordX').value='';
    document.getElementById('Observacion_Ubicacion_descripcion').value='';
    document.getElementById('Link_Ubicacion').value='';
    radioTipo[2].checked = true;
    await changeTipoUbicacion();
    map.flyTo({
        center: [-98.20868494860592, 19.040296987811555],
        zoom: 11,
        essential: true
        });
        marker.setLngLat([-98.20868494860592, 19.040296987811555])
}
const editUbicacion = async (obj) => {//FUNCION QUE EDITA LA TABLA DE DOMICILIOS TOMANDO LOS DATOS DE LA ROW SELECCIONADA Y ACOMODANDO LOS DATOS EN LA VISTA PARA SU EDICION
    document.getElementById('alertaEditUbicacion').style.display = 'block';
    selectedRowUbicaciones = obj.parentElement.parentElement;
    if(selectedRowUbicaciones.cells[1].innerHTML =='ENTREVISTA' ){
        radioTipo[0].checked = true;
    }else if(selectedRowUbicaciones.cells[1].innerHTML =='DATO'){
        radioTipo[1].checked = true;
    }else{
        radioTipo[2].checked = true;
    }
    await changeTipoUbicacion();

    document.getElementById('cordY').value=selectedRowUbicaciones.cells[8].innerHTML
    document.getElementById('cordX').value=selectedRowUbicaciones.cells[9].innerHTML
    await getColoniasCalles();

    document.getElementById('Id_Ubicacion').value = selectedRowUbicaciones.cells[16].innerHTML;
    document.getElementById('UbicacionSelect').value = selectedRowUbicaciones.cells[0].innerHTML;
    document.getElementById('Colonia').value=selectedRowUbicaciones.cells[2].innerHTML
    document.getElementById('Calle').value=selectedRowUbicaciones.cells[3].innerHTML
    document.getElementById('Calle2').value=(selectedRowUbicaciones.cells[4].innerHTML!='SD')?selectedRowUbicaciones.cells[4].innerHTML:'';
    document.getElementById('no_Ext').value=(selectedRowUbicaciones.cells[5].innerHTML!='SD')?selectedRowUbicaciones.cells[5].innerHTML:'';
    document.getElementById('no_Int').value=(selectedRowUbicaciones.cells[6].innerHTML!='SD')?selectedRowUbicaciones.cells[6].innerHTML:'';
    document.getElementById('CP').value=(selectedRowUbicaciones.cells[7].innerHTML!='SD')?selectedRowUbicaciones.cells[7].innerHTML:'';
  
    radio = document.getElementsByName('ubicacion_puebla')
    if(selectedRowUbicaciones.cells[12].innerHTML=='NO'){
        radio[0].checked=true;
        radio[1].checked=false;
        showUbicacionForanea();
    }else{
        radio[1].checked=true;
        radio[0].checked=false;
        showUbicacionForanea();
        document.getElementById('Estado').value=selectedRowUbicaciones.cells[10].innerHTML;
        document.getElementById('Municipio').value=(selectedRowUbicaciones.cells[11].innerHTML!='SD')?selectedRowUbicaciones.cells[11].innerHTML:'';
    }
    document.getElementById('Observacion_Ubicacion_descripcion').value=(selectedRowUbicaciones.cells[13].innerHTML!='SD')?selectedRowUbicaciones.cells[13].innerHTML:'';
    document.getElementById('Link_Ubicacion').value=(selectedRowUbicaciones.cells[14].innerHTML!='SD')?selectedRowUbicaciones.cells[14].innerHTML:'';
    window.scroll({
        top: 0,
        left: 100,
        behavior: 'smooth'
    });
}
const UpdateRowUbicacion=()=>{
    if(radioTipo[0].checked  && document.getElementById('UbicacionSelect').value!= -1){
        tipo = 'ENTREVISTA';
    }else if(radioTipo[1].checked  && document.getElementById('UbicacionSelect').value!= -1){
        tipo = 'DATO';
    }else{
        tipo = 'SD';
    }
    
    
    selectedRowUbicaciones.cells[0].innerHTML = document.getElementById('UbicacionSelect').value; 
    selectedRowUbicaciones.cells[1].innerHTML = tipo;
    selectedRowUbicaciones.cells[2].innerHTML = document.getElementById('Colonia').value.toUpperCase();
    selectedRowUbicaciones.cells[3].innerHTML = document.getElementById('Calle').value.toUpperCase();
    selectedRowUbicaciones.cells[4].innerHTML = (document.getElementById('Calle2').value!='')?document.getElementById('Calle2').value.toUpperCase():'SD';
    selectedRowUbicaciones.cells[5].innerHTML = (document.getElementById('no_Ext').value!='')?document.getElementById('no_Ext').value.toUpperCase():'SD';
    selectedRowUbicaciones.cells[6].innerHTML = (document.getElementById('no_Int').value!='')?document.getElementById('no_Int').value.toUpperCase():'SD';
    selectedRowUbicaciones.cells[7].innerHTML = (document.getElementById('CP').value!='')?document.getElementById('CP').value.toUpperCase():'SD';
    selectedRowUbicaciones.cells[8].innerHTML = document.getElementById('cordY').value;
    selectedRowUbicaciones.cells[9].innerHTML = document.getElementById('cordX').value;
    radio = document.getElementsByName('ubicacion_puebla')
    
    if(radio[0].checked){
         selectedRowUbicaciones.cells[10].innerHTML='PUEBLA';
         selectedRowUbicaciones.cells[11].innerHTML='PUEBLA';
         selectedRowUbicaciones.cells[12].innerHTML='NO';
    }else if(radio[1].checked){
         selectedRowUbicaciones.cells[10].innerHTML=document.getElementById('Estado').value
         selectedRowUbicaciones.cells[11].innerHTML=(document.getElementById('Municipio').value.trim()=='')?'SD':document.getElementById('Municipio').value.toUpperCase();
         selectedRowUbicaciones.cells[12].innerHTML='SI';
    }
    selectedRowUbicaciones.cells[13].innerHTML =(document.getElementById('Observacion_Ubicacion_descripcion').value!='')?document.getElementById('Observacion_Ubicacion_descripcion').value.toUpperCase():'SD';
    selectedRowUbicaciones.cells[14].innerHTML =(document.getElementById('Link_Ubicacion').value.trim()!='')?document.getElementById('Link_Ubicacion').value:'SD';
    selectedRowUbicaciones.cells[16].innerHTML = document.getElementById('Id_Ubicacion').value;
    document.getElementById('alertaEditUbicacion').style.display = 'none';
    selectedRowUbicaciones= null;
}
const deleteRowDomicilio = async(obj, tableId) => {//FUNCION PARA ELIMINAR UNA FILA DE LA TABLA DE DOMICILIO 
    if (confirm('¿Desea eliminar este elemento?')) {
        let row = obj.parentElement.parentElement;
        aux=row.rowIndex;
        if(row.cells[0].innerHTML!='SD'){
            await DesasociaUbicacion(row.cells[0].innerHTML);
        }
        document.getElementById(tableId.id).deleteRow(row.rowIndex);
        j=aux+1;
        if(tableId.id=='UbicacionTable'){//Si es la tabla de personas hace una eliminacion especial debido a las fotos
            table = document.getElementById('UbicacionTable')
            j=aux+1;
            for(let i=aux;i<table.rows.length;i++){
                let contenedorImg =table.rows[i].cells[15].children[1];
                contenedorImg.setAttribute('id', 'imageContentUbicacion_row'+i);
                if(contenedorImg.childNodes.length>1){
                    if(contenedorImg.childNodes[1].childNodes[1]!=undefined){
                        contenedorImg.childNodes[1].childNodes[1].setAttribute('onclick', "deleteImageFotoUbicacion("+i+")");
                        contenedorImg.childNodes[3].setAttribute('id', 'imagesUbicacion_row_'+i);
                        contenedorImg.childNodes[3].setAttribute('data-target', '#ModalCenterUbicacion'+i);
                        if(contenedorImg.childNodes[5].getAttribute('class')==j+' Photo'){
                            contenedorImg.childNodes[5].setAttribute('class', i+' Photo');
                        }else{
                            contenedorImg.childNodes[5].setAttribute('class', i+' File');
                        }
                        contenedorImg.childNodes[7].setAttribute('id', 'ModalCenterUbicacion'+i);
                    }else{
                        contenedorImg.childNodes[0].childNodes[1].setAttribute('onclick', "deleteImageFotoUbicacion("+i+")");
                        contenedorImg.childNodes[2].setAttribute('id', 'imagesUbicacion_row_'+i);
                        contenedorImg.childNodes[2].setAttribute('data-target', '#ModalCenterUbicacion'+i);
    
                        if(contenedorImg.childNodes[4].getAttribute('class')==j+' Photo'){
                            contenedorImg.childNodes[4].setAttribute('class', i+' Photo');
                        }else{
                            contenedorImg.childNodes[4].setAttribute('class', i+' File');
                        }
                        contenedorImg.childNodes[6].setAttribute('id', 'ModalCenterUbicacion'+i);
                    }  
                }
                let contenedorInput =table.rows[i].cells[15].children[0];
                contenedorInput.setAttribute('id', 'uploadFileFotoUbicacion'+i);
                contenedorInput.childNodes[1].childNodes[1].setAttribute('id', 'fileFotoUbicacion_row'+i);
                contenedorInput.childNodes[1].childNodes[1].setAttribute('name', 'FotoUbicacion_row'+i);
                contenedorInput.childNodes[1].childNodes[3].setAttribute('for', 'fileFotoUbicacion_row'+i);
                j++;
            }
        }
        RecargaDatosUbicacion();
    }
}
const DesasociaUbicacion= async(Id_Ubicacion)=>{//FUNCION QUE ELIMINA LOS DATOS DE LA TABLA DE DOMICILIOS
    try {
        myFormData.append('Id_Ubicacion',Id_Ubicacion)
        myFormData.append('Id_Persona_Entrevista',document.getElementById('id_persona_entrevista').value )
        const response = await fetch(base_url_js + 'Entrevistas/DesasociaUbicacion', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
/*--------------FUNCIONES PARA LAS FOTOS DE LA TABLA DE UBICACION--------------------- */


function uploadFileUbicacion(event, type) {//Funcion para actualizar las imagenes de la tabla de ubicacion
    let file;
    if (type) {
        file = 'Photo';
    } else {
        file = 'File';
    }
    if (event.currentTarget.classList.contains('uploadFileFotoUbicacion')) {//TABLA DE UBICACION
        if (validateImage(event.target)) {
            const src = URL.createObjectURL(event.target.files[0]);
            const row = event.currentTarget;
            const index = row.parentNode.parentNode.parentNode.parentNode.rowIndex;
            createElementFotoUbicacion(src, index, 'File');
        } else {
            msg_ubicacionError.innerHTML = '<div class="alert alert-warning text-center" role="alert">Verificar el archivo cargado.<br>Posibles errores: <br> - Archivo muy pesado (Máximo 8 megas). <br> -Extensión no aceptada (Extensiones aceptadas: jpeg, png, jpg, PNG).</div>';
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });
        }
    }
}
function createElementFotoUbicacion(src, index, type, view) {//FUNCION PARA LA VISUALIZACION DE IMAGENES EN LA TABLA DE UBICACION
    const div = document.getElementById('imageContentUbicacion_row' + index);
    if (view === undefined) {
        div.innerHTML = `<div class="d-flex justify-content-end">
                            <span onclick="deleteImageFotoUbicacion(${index})" class="deleteFile">X</span>
                        </div>
                        <img name="nor" src="${src}" id="imagesUbicacion_row_${index}" width="250px" data-toggle="modal" data-target="#ModalCenterUbicacion${index}">
                        <input type="hidden" class="${index} ${type}"/>
                        <div class="modal fade " id="ModalCenterUbicacion${index}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
function deleteImageFotoUbicacion(index) {//FUNCION QUE ELIMINA LA VISUALIZACION Y EL CONTENIDO DE LAS IMAGENES
    const div = document.getElementById('imageContentUbicacion_row' + index);
    document.getElementById('fileFotoUbicacion_row' + index).value = '';
    div.innerHTML = '';
}
/*--------------------------FUNCIONES PARA EL ALMACENAMIENTO DE LOS DATOS CONTENIDOS EN EL FRAME  --------------- */
var msg_ubicacionError=document.getElementById('msg_principales_ubicaciones');
var datos_ubicaciones = document.getElementById('datos_ubicaciones')
document.getElementById('btn_Ubicacion_Entrevistas').addEventListener('click', async function(e) {
    let myFormDataUbicacion = new FormData(datos_ubicaciones)//RECUERDA SIEMPRE ENVIAR LOS DATOS DEL FRAME Y AUN MAS IMPORTANTE POR LAS IMAGENES LAS DETECTE EN EL POST
    var Ubicaciones =  await readTableUbicaciones();//LEEMOS EL CONTENIDO DE LA TABLA DE Forencias 
    myFormDataUbicacion.append('Ubicacionestable', JSON.stringify(Ubicaciones)); //CODIFICAMOS LOS DATOS PARA QUE EL CONTROLADOR LOS OCUPE 
    myFormDataUbicacion.append('id_persona_entrevista',document.getElementById('id_persona_entrevista').value)
    
    let button = document.getElementById('btn_Ubicacion_Entrevistas')
    button.innerHTML = `
        Guardando
        <div class="spinner-grow spinner-grow-sm" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    `;
    button.classList.add('disabled-link');
    $('#ModalCenterPrincipalUbicacion').modal('show');
    fetch(base_url_js + 'Entrevistas/UpdateUbicacionesFetch', {//realiza el fetch para actualizar los datos
        method: 'POST',
        body: myFormDataUbicacion
    })

    .then(res => res.json())

    .then(data => {//obtine  respuesta del modelo
        button.innerHTML = `Guardar Ubicaciones`;
        button.classList.remove('disabled-link');//se vuelve activar la funcion del boton 
        $('#ModalCenterPrincipalUbicacion').modal('hide');//se quita la imagen 
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
            msg_ubicacionError.innerHTML = messageError
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });
        } else {//si todo salio bien
            alertaUbicacion()//Si todo salio bien actualiza los datos arroja un mensaje satisfactorio
        }
    })
})
const alertaUbicacion = async()=>{/// todo bien en la edicion
    msg_ubicacionError.innerHTML = `<div class="alert alert-success text-center" role="success">Datos de Ubicaciones Actualizados correctamente.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
            <span aria-hidden="true">&times;</span>
        </button>
    </div>`;
    window.scroll({
        top: 0,
        left: 100,
        behavior: 'smooth'
    });

    await RecargaDatosUbicacion();
}
const readTableUbicaciones = async() => {//lee los datos de la tabla personas y genera una estructura deacuerdo a los datos contenido es la tabla
    let table = document.getElementById('UbicacionTable');
    let objetos = [];
    for (let i = 1; i < table.rows.length; i++) {
        let input = table.rows[i].cells[15].children[1].children[1];
        if (input != undefined) {//si en el contenedor existe alguna imagen verifica el tipo
            let type = table.rows[i].cells[15].children[1].children[2].classList[1]
            let base64 = document.getElementById('imagesUbicacion_row_' + i);
            nameImage = 'FotoUbicacion_row' + i;
            isPNG = base64.src.split('.');
            if (type != 'File') {//funcion para leer las fotos
                if (isPNG[1] != undefined) {
                    await toDataURL(base64.src)//esto es para tipo photo osea que ya se encuentran en el servidor un archivo fisico con extension diferente a la png
                        .then(myBase64 => {
                            objetos.push({
                                ['row']: {
                                    
                                    Id_Persona_Entrevista  : document.getElementById('id_persona_entrevista').value,
                                    Id_Dato: table.rows[i].cells[0].innerHTML,
                                    Tipo_Relacion: table.rows[i].cells[1].innerHTML,
                                    Colonia: table.rows[i].cells[2].innerHTML,
                                    Calle: table.rows[i].cells[3].innerHTML,
                                    Calle2: table.rows[i].cells[4].innerHTML,
                                    NumExt: table.rows[i].cells[5].innerHTML,
                                    NumInt: table.rows[i].cells[6].innerHTML,
                                    CP: table.rows[i].cells[7].innerHTML,
                                    CoordY: table.rows[i].cells[8].innerHTML,
                                    CoordX: table.rows[i].cells[9].innerHTML,
                                    Estado: table.rows[i].cells[10].innerHTML,
                                    Municipio: table.rows[i].cells[11].innerHTML,
                                    Foraneo: table.rows[i].cells[12].innerHTML,
                                    Observaciones_Ubicacion: table.rows[i].cells[13].innerHTML,
                                    Link_Ubicacion: table.rows[i].cells[14].innerHTML,
                                    Id_Ubicaciones_Entrevista : table.rows[i].cells[16].innerHTML,
                                    Capturo: table.rows[i].cells[17].innerHTML,
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
                            Id_Persona_Entrevista  : document.getElementById('id_persona_entrevista').value,
                            Id_Dato: table.rows[i].cells[0].innerHTML,
                            Tipo_Relacion: table.rows[i].cells[1].innerHTML,
                            Colonia: table.rows[i].cells[2].innerHTML,
                            Calle: table.rows[i].cells[3].innerHTML,
                            Calle2: table.rows[i].cells[4].innerHTML,
                            NumExt: table.rows[i].cells[5].innerHTML,
                            NumInt: table.rows[i].cells[6].innerHTML,
                            CP: table.rows[i].cells[7].innerHTML,
                            CoordY: table.rows[i].cells[8].innerHTML,
                            CoordX: table.rows[i].cells[9].innerHTML,
                            Estado: table.rows[i].cells[10].innerHTML,
                            Municipio: table.rows[i].cells[11].innerHTML,
                            Foraneo: table.rows[i].cells[12].innerHTML,
                            Observaciones_Ubicacion: table.rows[i].cells[13].innerHTML,
                            Link_Ubicacion: table.rows[i].cells[14].innerHTML,
                            Id_Ubicaciones_Entrevista : table.rows[i].cells[16].innerHTML,
                            Capturo: table.rows[i].cells[17].innerHTML,
                            typeImage: type,
                            nameImage: nameImage,
                            image:  base64.src,
                            imagebase64:base64.src
                        }
                    });
                }
            }else{// si es tipo file guarda los datos de la imagen
                let aux=document.getElementById('fileFotoUbicacion_row'+i)
                let base64URL = await encodeFileAsBase64URL(aux.files[0]);
                objetos.push({
                    ['row']: {
                        Id_Persona_Entrevista  : document.getElementById('id_persona_entrevista').value,
                        Id_Dato: table.rows[i].cells[0].innerHTML,
                        Tipo_Relacion: table.rows[i].cells[1].innerHTML,
                        Colonia: table.rows[i].cells[2].innerHTML,
                        Calle: table.rows[i].cells[3].innerHTML,
                        Calle2: table.rows[i].cells[4].innerHTML,
                        NumExt: table.rows[i].cells[5].innerHTML,
                        NumInt: table.rows[i].cells[6].innerHTML,
                        CP: table.rows[i].cells[7].innerHTML,
                        CoordY: table.rows[i].cells[8].innerHTML,
                        CoordX: table.rows[i].cells[9].innerHTML,
                        Estado: table.rows[i].cells[10].innerHTML,
                        Municipio: table.rows[i].cells[11].innerHTML,
                        Foraneo: table.rows[i].cells[12].innerHTML,
                        Observaciones_Ubicacion: table.rows[i].cells[13].innerHTML,
                        Link_Ubicacion: table.rows[i].cells[14].innerHTML,
                        Id_Ubicaciones_Entrevista : table.rows[i].cells[16].innerHTML,
                        Capturo: table.rows[i].cells[17].innerHTML,
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
                    Id_Persona_Entrevista  : document.getElementById('id_persona_entrevista').value,
                    Id_Dato: table.rows[i].cells[0].innerHTML,
                    Tipo_Relacion: table.rows[i].cells[1].innerHTML,
                    Colonia: table.rows[i].cells[2].innerHTML,
                    Calle: table.rows[i].cells[3].innerHTML,
                    Calle2: table.rows[i].cells[4].innerHTML,
                    NumExt: table.rows[i].cells[5].innerHTML,
                    NumInt: table.rows[i].cells[6].innerHTML,
                    CP: table.rows[i].cells[7].innerHTML,
                    CoordY: table.rows[i].cells[8].innerHTML,
                    CoordX: table.rows[i].cells[9].innerHTML,
                    Estado: table.rows[i].cells[10].innerHTML,
                    Municipio: table.rows[i].cells[11].innerHTML,
                    Foraneo: table.rows[i].cells[12].innerHTML,
                    Observaciones_Ubicacion: table.rows[i].cells[13].innerHTML,
                    Link_Ubicacion: table.rows[i].cells[14].innerHTML,
                    Id_Ubicaciones_Entrevista : table.rows[i].cells[16].innerHTML,
                    Capturo: table.rows[i].cells[17].innerHTML,
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
