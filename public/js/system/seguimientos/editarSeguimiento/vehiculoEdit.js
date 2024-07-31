function validePlaca(evt) {//FUNCION QUE VALIDA LA INSERCION DE SOLO NUMEROS 
    var code = (evt.which) ? evt.which : evt.keyCode;
    let codigos = [8,32]//TECLA DELETE
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
function validePlacas(evt) {//FUNCION QUE VALIDA LA INSERCION DE LETRAS NUMEROS COMAS  
    var code = (evt.which) ? evt.which : evt.keyCode;
    let codigos = [8,32,44]//TECLA DELETE
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
function validaModelo(evt) {//FUNCION QUE VALIDA LA INSERCION DE NUMEROS, LETRAS Y VOCALES CON ASCENTOS
    var code = (evt.which) ? evt.which : evt.keyCode;
    let codigos = [8,32,193,201,205,211,218,225,233,237,243,250]//TECLAS DELETE,ESPACIO Y LETRAS ACENTUADAS
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
function validaOnlyLetras(evt) {//FUNCION QUE VALIDA LA INSERCION DE SOLO LETRAS Y VOCALES CON ASCENTOS
    var code = (evt.which) ? evt.which : evt.keyCode;
    let codigos = [8,32,193,201,205,211,218,225,233,237,243,250]//TECLAS DELETE,ESPACIO Y LETRAS ACENTUADAS
    if ((code >= 65 && code <= 90)||(code >= 97 && code <= 122)) { //TECLAS DE LETRAS
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
/*---------------------------FUNCIONES PARA LA BUSQUEDA DE LA PLACA EN SISTEMA SARAI--------------------------------------*/
const inputVehiculo = document.getElementById('id_vehiculo_sarai');
const error_vehiculo= document.getElementById('error_vehiculo_sarai');
inputVehiculo.addEventListener('input', () => { 
    myFormData.append('termino', inputVehiculo.value)//REALIZA UN FETCH PARA TRAER EL CATALOGO VEHICULOS PARA QUE SERA COMPARADO CON LO QUE SE ESTA INGRESANDO ADEMAS DE SE OCUPARA PARA LA FUNCION DEL AUTOCOMPLETE
    fetch(base_url_js + 'Seguimientos/getVehiculosSarai', {
            method: 'POST',
            body: myFormData
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `PLACA:${r.Placas} ${r.Marca} ${r.Submarca} ${r.Color} REMISIONES:${r.Remision} ID EN SARAI:${r.ID_VEHICULO}` , value: `${r.ID}`}))//muestra un autocomplete de la placa
        autocomplete({
            input: id_vehiculo_sarai,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {//al dar click en la opcion selecciona el valor y pone los datos el formulario
                id_vehiculo_sarai.value = item.value;
                onFormVehiculoSaraiSubmit()
            }
        }); 
    })
    .catch(err => alert(`Ha ocurrido un error al obtener los Vehiculos.\nCódigo de error: ${ err }`))
});
/*FUNCION PARA OBTENER LA INFORMACION DEL VEHICULO INGRESADO CON ID */
const onFormVehiculoSaraiSubmit = async() => {
    let entrada =parseInt(inputVehiculo.value) ;//FUNCION QUE VALIDA SI LA ENTRADA ES UN ID VALIDO
    if( Number.isInteger(entrada)){
        await resetFormVehiculo();
        let InfoVehiculo= await getInfoVehiculoSarai(inputVehiculo.value)
        error_vehiculo.innerHTML='';
        if(InfoVehiculo!=''){
            llenadatosVehiculosarai(InfoVehiculo);
            inputVehiculo.value='';
        }else{
            error_vehiculo.innerHTML="NO EXISTE ESE NUMERO ID DE VEHICULO INGRESE OTRO"
        }
  
    }else{
        error_vehiculo.innerHTML="INGRESE SOLO EL NUMERO ID"
    }
}
const getInfoVehiculoSarai = async (ID_VEHICULO) => {//FUNCION PARA OBTENER LA INFORMACION DEL VEHICULO 
    try {
        myFormData.append('ID',ID_VEHICULO);
        const response = await fetch(base_url_js + 'Seguimientos/getInfoVehiculoSarai', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const llenadatosVehiculosarai = async ( data ) => {//FUNCION QUE LLENA EN EL FORMULARIO DE LOS DATOS SACADOS DE SARAI 
    let placas=document.getElementById('placas');
    let marca = document.getElementById('marca');
    let submarca = document.getElementById('submarca');
    let color = document.getElementById('color');
    let NIVS = document.getElementById('NIVS');
    let remisionesVeh = document.getElementById('remisiones_FoliosVeh');
    let modelo = document.getElementById('modelo');
    placas.value=(data.Placas!='SD')?data.Placas:'';
    marca.value=(data.Marca!='SD')?data.Marca:'';
    submarca.value=(data.Submarca!='SD')?data.Submarca:'';
    color.value=(data.Color!='SD')?data.Color:'';
    NIVS.value=(data.NIVS!='SD')?data.NIVS:'';
    remisionesVeh.value=(data.Remision!='SD')?'REMISIONES: '+data.Remision+', ID DE VEHICULO EN SARAI: '+data.ID_VEHICULO:'ID DE VEHICULO EN SARAI: '+data.ID_VEHICULO;
    modelo.value=(data.Modelo!='SD')?data.Modelo:'';
}
/*--------------------------FUNCIONES DE CONSULTA DE VEHICULO SIC (SOLO RESPONSABLES)---------------------------- */
const inputVehiculo2 = document.getElementById('id_vehiculo_sic');
const error_vehiculo2= document.getElementById('error_vehiculo_sic');
inputVehiculo2.addEventListener('input', () => { 
    myFormData.append('termino', inputVehiculo2.value)//REALIZA UN FETCH PARA TRAER EL CATALOGO DE VEHICULOS PARA QUE SERA COMPARADO CON LO QUE SE ESTA INGRESANDO ADEMAS DE SE OCUPARA PARA LA FUNCION DEL AUTOCOMPLETE
    fetch(base_url_js + 'Seguimientos/getVehiculosSic', {
            method: 'POST',
            body: myFormData
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `PLACA:${r.Placas} ${r.Marca} ${r.Submarca} ${r.Color} FOLIO ASOCIADO ${r.Folio_infra}` , value: `${r.ID}`}))
        autocomplete({
            input: id_vehiculo_sic,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {
                id_vehiculo_sic.value = item.value;
                onFormVehiculoSicSubmit()
            }
        }); 
    })
    .catch(err => alert(`Ha ocurrido un error al obtener los Vehiculos.\nCódigo de error: ${ err }`))
});
/*FUNCION PARA OBTENER LA INFORMACION DEL VEHICULO INGRESADO CON ID */
const onFormVehiculoSicSubmit = async() => {
    let entrada =parseInt(inputVehiculo2.value) ;
    if( Number.isInteger(entrada)){
        await resetFormVehiculo();
        let InfoVehiculo= await getInfoVehiculoSic(inputVehiculo2.value)//OBTIENE DEL CATALOGO DE LOS VEHICULOS EN EL SISTEMA SIC
        error_vehiculo2.innerHTML='';
        if(InfoVehiculo!=''){
            llenadatosVehiculoSic(InfoVehiculo);
        }else{
            error_vehiculo2.innerHTML="NO EXISTE ESE NUMERO ID DE VEHICULO INGRESE OTRO"
        }
        inputVehiculo2.value='';
    }else{
        error_vehiculo2.innerHTML="INGRESE SOLO EL NUMERO ID"
    }
}
const getInfoVehiculoSic = async (ID_VEHICULO) => {//FUNCION PARA OBTENER LA INFORMACION DEL VEHICULO 
    try {
        myFormData.append('ID',ID_VEHICULO);
        const response = await fetch(base_url_js + 'Seguimientos/getInfoVehiculoSic', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const llenadatosVehiculoSic = async ( data ) => {//FUNCION QUE LLENA EN EL FORMULARIO DE LOS DATOS SACADOS DE SIC
    let placas=document.getElementById('placas');
    let marca = document.getElementById('marca');
    let submarca = document.getElementById('submarca');
    let color = document.getElementById('color');
    let remisionesVeh = document.getElementById('remisiones_FoliosVeh');
    let modelo = document.getElementById('modelo');
    placas.value=(data.Placas!='SD')?data.Placas:'';
    marca.value=(data.Marca!='SD')?data.Marca:'';
    submarca.value=(data.Submarca!='SD')?data.Submarca:'';
    color.value=(data.Color!='SD')?data.Color:'';
    modelo.value=(data.Modelo!='SD')?data.Modelo:'';
    remisionesVeh.value='FOLIO ASOCIADO DEL EVENTO '+data.Folio_infra;
}
/*--------------------------SELECIONA LA OPCION DE BUSQUEDA DE PLACA-------------------- */
const changeProcedenciaBusqueda = () =>{//FUNCION PARA HABILITAR BUSQUEDA DESDE SARAI O SIC
    let radioHabilitado = document.getElementsByName('SaraioSic');
    if(radioHabilitado[1].checked){//SARAI
        document.getElementById('id_vehiculo_panel').classList.add('mi_hide');
        document.getElementById('id_vehiculo_panel2').classList.remove('mi_hide');
        error_vehiculo2.innerHTML='';
        inputVehiculo2.value='';
    }else if(radioHabilitado[0].checked){//SIC
        document.getElementById('id_vehiculo_panel2').classList.add('mi_hide');
        document.getElementById('id_vehiculo_panel').classList.remove('mi_hide');
        error_vehiculo.innerHTML='';
        inputVehiculo.value='';
    }
}
const resetFormVehiculo = async ()=>{//FUNCION QUE LIMPIA LA VISTA
    let placas=document.getElementById('placas');
    let marca = document.getElementById('marca');
    let submarca = document.getElementById('submarca');
    let color = document.getElementById('color');
    let NIVS = document.getElementById('NIVS');
    let remisionesVeh = document.getElementById('remisiones_FoliosVeh');
    let modelo = document.getElementById('modelo');
    document.getElementById('Id_Vehiculo').value='SD';
    document.getElementById('NombrePropietario').value='';
    placas.value='';   
    marca.value=''; 
    submarca.value='';
    color.value='';  
    NIVS.value='';
    remisionesVeh.value='';
    data.Remision='';
    modelo.value='';
}
/*----------------------FUNCIONES DE LA TABLA DE PERSONAS----------------- */
let selectedRowVehiculo = null//VARIABLE QUE DEFINE SI SE TRATA DE UNA ACTUALIZACION DE UN REGISTRO YA CREADO O DE UNA NUEVA INSERCION
let errorPlacas = document.getElementById('placas_error');
const onFormVehiculoSubmit = async() => {
    if(await ValidatableVehiculo()){
        if (selectedRowVehiculo === null){
            InsertVehiculo();//INSERTA NUEVA FILA EN LA TABLA DE PersonaS
            await verificaInfoVehiculoRed();
        }else{
            updateRowVehiculo();//ACTUALIZA LA FILA SELECCIONADA EN LA TABLA DE PersonaS
        
        }
        resetFormVehiculo();//LIMPIA LA VISTA 
    }
}
const ValidatableVehiculo = async() => {//VALIDA LOS DATOS PARA SER INGRESADOR A LA TABLA
    let respuesta=true;
    if(document.getElementById('placas').value.trim()==''|| document.getElementById('placas').value.length<3){
        errorPlacas.innerHTML='Ingrese Correctamente la placa del vehiculo'
        respuesta=false;
    }else{
        errorPlacas.innerHTML=''
    }
    return respuesta;
}
const InsertVehiculo= async()=>{//INSERTA LOS DATOS A LA TABLA DE VEHICULOS
    let table = document.getElementById('VehiculoTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    newRow.insertCell(0).innerHTML =document.getElementById('Id_Vehiculo').value;
    newRow.insertCell(1).innerHTML =document.getElementById('placas').value.toUpperCase();
    newRow.insertCell(2).innerHTML =(document.getElementById('marca').value!='')?document.getElementById('marca').value.toUpperCase():'SD';
    newRow.insertCell(3).innerHTML =(document.getElementById('submarca').value!='')?document.getElementById('submarca').value.toUpperCase():'SD';
    newRow.insertCell(4).innerHTML =(document.getElementById('color').value!='')?document.getElementById('color').value.toUpperCase():'SD';
    newRow.insertCell(5).innerHTML =(document.getElementById('modelo').value!='')?document.getElementById('modelo').value.toUpperCase():'SD';
    newRow.insertCell(6).innerHTML =(document.getElementById('NombrePropietario').value!='')?document.getElementById('NombrePropietario').value.toUpperCase():'SD';
    newRow.insertCell(7).innerHTML =(document.getElementById('NIVS').value.trim()!='')?document.getElementById('NIVS').value.toUpperCase():'SD';
    newRow.insertCell(8).innerHTML =(document.getElementById('remisiones_FoliosVeh').value.trim()!='')?document.getElementById('remisiones_FoliosVeh').value.toUpperCase():'SD';
    newRow.insertCell(9).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoV${newRow.rowIndex}">
                                        <div class="form-group">
                                            <input type="file" name="FotoVehiculo_row${newRow.rowIndex}" accept="image/*" id="fileFotoVehiculo_row${newRow.rowIndex}" class="inputfile uploadFileFotoV" onchange="uploadFileV(event)" data-toggle="tooltip" data-placement="bottom">
                                            <label for="fileFotoVehiculo_row${newRow.rowIndex}"></label>
                                            <h3 class="uploadFotoVehiculoCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                        </div>
                                    </div>
                                    <div id="imageContentV_row${newRow.rowIndex}"></div>`;
    newRow.insertCell(10).innerHTML =document.getElementById('captura_dato_vehiculo').value.toUpperCase();
    newRow.insertCell(11).innerHTML = `<button type="button" class="btn btn-add" onclick="editVehiculo(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc" value="-" onclick="deleteRowVehiculo(this,VehiculoTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
    newRow.cells[0].style.display = "none";
}
const editVehiculo = (obj) => {//FUNCION QUE EDITA LA TABLA DE VEHICULOS TOMANDO LOS DATOS DE LA ROW SELECCIONADA Y ACOMODANDO LOS DATOS EN LA VISTA PARA SU EDICION
    document.getElementById('alertaEditVehiculo').style.display = 'block';
    selectedRowVehiculo = obj.parentElement.parentElement;

    document.getElementById('Id_Vehiculo').value=selectedRowVehiculo.cells[0].innerHTML;
    document.getElementById('placas').value=selectedRowVehiculo.cells[1].innerHTML;
    document.getElementById('marca').value=(selectedRowVehiculo.cells[2].innerHTML!='SD')?selectedRowVehiculo.cells[2].innerHTML:'';
    document.getElementById('submarca').value=(selectedRowVehiculo.cells[3].innerHTML!='SD')?selectedRowVehiculo.cells[3].innerHTML:'';
    document.getElementById('color').value=(selectedRowVehiculo.cells[4].innerHTML!='SD')?selectedRowVehiculo.cells[4].innerHTML:'';
    document.getElementById('modelo').value=(selectedRowVehiculo.cells[5].innerHTML!='SD')?selectedRowVehiculo.cells[5].innerHTML:'';
    document.getElementById('NombrePropietario').value=(selectedRowVehiculo.cells[6].innerHTML!='SD')?selectedRowVehiculo.cells[6].innerHTML:'';
    document.getElementById('NIVS').value=(selectedRowVehiculo.cells[7].innerHTML!='SD')?selectedRowVehiculo.cells[7].innerHTML:'';
    document.getElementById('remisiones_FoliosVeh').value=(selectedRowVehiculo.cells[8].innerHTML!='SD')?selectedRowVehiculo.cells[8].innerHTML:'';

    window.scroll({
        top: 0,
        left: 100,
        behavior: 'smooth'
    });
}
const updateRowVehiculo=()=>{//FUNCION QUE ACTUALIZA LOS DATOS EN LA TABLA TOMANDO EL FORMULARIO
    selectedRowVehiculo.cells[0].innerHTML=document.getElementById('Id_Vehiculo').value;
    selectedRowVehiculo.cells[1].innerHTML=document.getElementById('placas').value.toUpperCase();
    selectedRowVehiculo.cells[2].innerHTML=(document.getElementById('marca').value!='')?document.getElementById('marca').value.toUpperCase():'SD';
    selectedRowVehiculo.cells[3].innerHTML=(document.getElementById('submarca').value!='')?document.getElementById('submarca').value.toUpperCase():'SD';
    selectedRowVehiculo.cells[4].innerHTML=(document.getElementById('color').value!='')?document.getElementById('color').value.toUpperCase():'SD';
    selectedRowVehiculo.cells[5].innerHTML=(document.getElementById('modelo').value!='')?document.getElementById('modelo').value.toUpperCase():'SD';
    selectedRowVehiculo.cells[6].innerHTML=(document.getElementById('NombrePropietario').value!='')?document.getElementById('NombrePropietario').value.toUpperCase():'SD';
    selectedRowVehiculo.cells[7].innerHTML=(document.getElementById('NIVS').value.trim()!='')?document.getElementById('NIVS').value.toUpperCase():'SD';
    selectedRowVehiculo.cells[8].innerHTML=(document.getElementById('remisiones_FoliosVeh').value.trim()!='')?document.getElementById('remisiones_FoliosVeh').value.toUpperCase():'SD';
    document.getElementById('alertaEditVehiculo').style.display = 'none';
    selectedRowVehiculo= null;
}
function uploadFileV(event, type) {//FUNCION PARA ACTUALIZAR LAS FOTOS DE LA TABLA DE VEHICULOS
    let file;
    if (type) {
        file = 'Photo';
    } else {
        file = 'File';
    }
    if (event.currentTarget.classList.contains('uploadFileFotoV')) {//FUNCION PARA LA TABLA DE VEHICULOS QUE CARGAR LAS FOTOS
        if (validateImage(event.target)) {
            const src = URL.createObjectURL(event.target.files[0]);
            const row = event.currentTarget;
            const index = row.parentNode.parentNode.parentNode.parentNode.rowIndex;
            createElementFotoVehiculo(src, index, 'File');
        } else {
            msg_VehiculosError.innerHTML = '<div class="alert alert-warning text-center" role="alert">Verificar el archivo cargado.<br>Posibles errores: <br> - Archivo muy pesado (Máximo 8 megas). <br> -Extensión no aceptada (Extensiones aceptadas: jpeg, png, jpg, PNG).</div>';
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });
        }
    }
}
function createElementFotoVehiculo(src, index, type, view) {//GENERA LA VISUALIZACION DE LA FOTO EN LA TABLA
    const div = document.getElementById('imageContentV_row' + index);
    if (view === undefined) {
        div.innerHTML = `<div class="d-flex justify-content-end">
                            <span onclick="deleteImageFotoV(${index})" class="deleteFile">X</span>
                        </div>
                        <img name="nor" src="${src}" id="imagesV_row_${index}" width="400px" data-toggle="modal" data-target="#ModalCenterVehiculo${index}">
                        <input type="hidden" class="${index} ${type}"/>
                        <div class="modal fade " id="ModalCenterVehiculo${index}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
function deleteImageFotoV(index) {//ELIMINA LA VISUALIZACION DE LA FOTO EN LA TABLA Y VACIA EL NOMBRE DEL FILE CARGADO 
    const div = document.getElementById('imageContentV_row' + index);
    document.getElementById('fileFotoVehiculo_row' + index).value = '';
    div.innerHTML = '';
}
const deleteRowVehiculo = async(obj, tableId) => {//funcion para eliminar una fila en la tabla de vehiculos ademas de funcion especial
    if (confirm('¿Desea eliminar este elemento?')) {
        let row = obj.parentElement.parentElement;
        aux=row.rowIndex;
        if(row.cells[0].innerHTML!='SD'){
            await DesasociaVehiculo(row.cells[0].innerHTML);
        }
        document.getElementById(tableId.id).deleteRow(row.rowIndex);
        j=aux+1;
        if(tableId.id=='VehiculoTable'){//Si es la tabla de Vehiculos hace una eliminacion especial debido a la foto 
            table = document.getElementById('VehiculoTable')
            j=aux+1;
            for(let i=aux;i<table.rows.length;i++){
                let contenedorImg =table.rows[i].cells[9].children[1];
                contenedorImg.setAttribute('id', 'imageContentV_row'+i);
                if(contenedorImg.childNodes.length>1){
                    if(contenedorImg.childNodes[1].childNodes[1]!=undefined){

                        contenedorImg.childNodes[1].childNodes[1].setAttribute('onclick', "deleteImageFotoV("+i+")");
                        contenedorImg.childNodes[3].setAttribute('id', 'imagesV_row_'+i);
                        contenedorImg.childNodes[3].setAttribute('data-target', '#ModalCenterVehiculo'+i);
                        if(contenedorImg.childNodes[5].getAttribute('class')==j+' Photo'){
                            contenedorImg.childNodes[5].setAttribute('class', i+' Photo');
                        }else{
                            contenedorImg.childNodes[5].setAttribute('class', i+' File');
                        }
                        contenedorImg.childNodes[7].setAttribute('id', 'ModalCenterVehiculo'+i);
                    }else{

                        contenedorImg.childNodes[0].childNodes[1].setAttribute('onclick', "deleteImageFotoV("+i+")");
                        contenedorImg.childNodes[2].setAttribute('id', 'imagesV_row_'+i);
                        contenedorImg.childNodes[2].setAttribute('data-target', '#ModalCenterVehiculo'+i);
    
                        if(contenedorImg.childNodes[4].getAttribute('class')==j+' Photo'){
                            contenedorImg.childNodes[4].setAttribute('class', i+' Photo');
                        }else{
                            contenedorImg.childNodes[4].setAttribute('class', i+' File');
                        }
                        contenedorImg.childNodes[6].setAttribute('id', 'ModalCenterVehiculo'+i);
                    }  
                }
                let contenedorInput =table.rows[i].cells[9].children[0];
                contenedorInput.setAttribute('id', 'uploadFileFotoV'+i);
                contenedorInput.childNodes[1].childNodes[1].setAttribute('id', 'fileFotoVehiculo_row'+i);
                contenedorInput.childNodes[1].childNodes[1].setAttribute('name', 'FotoVehiculo_row'+i);
                contenedorInput.childNodes[1].childNodes[3].setAttribute('for', 'fileFotoVehiculo_row'+i);
                j++;
            }
        }
        await RecargaDatosVehiculos();//FUNCION QUE RECARGA LOS DATOS DE LOS VEHICULOS
    }
}
const DesasociaVehiculo= async(Id_Vehiculo)=>{//FUNCION PARA DESVINCULAR LOS DATOS DEL SEGUIMIENTO  
    try {
        myFormData.append('Id_Vehiculo',Id_Vehiculo)
        const response = await fetch(base_url_js + 'Seguimientos/DesasociaVehiculo', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
//FUNCIONES PARA GUARDAR INFORMACION EN LA BASE DE DATOS Y REFRESCAR LA INFORMACION DE LA VISTA DE LA TABLA
const readTableVehiculos = async() => {//lee los datos de la tabla vehiculos y genera una estructura deacuerdo a los datos contenido es la tabla
    let table = document.getElementById('VehiculoTable');
    let objetos = [];
    for (let i = 1; i < table.rows.length; i++) {
        let input = table.rows[i].cells[9].children[1].children[1];
        if (input != undefined) {//si en el contenedor existe alguna imagen verifica el tipo
            let type = table.rows[i].cells[9].children[1].children[2].classList[1]
            let base64 = document.getElementById('imagesV_row_' + i);
            nameImage = 'FotoVehiculo_row' + i;
            isPNG = base64.src.split('.');
            if (type != 'File') {//funcion para leer las fotos
                if (isPNG[1] != undefined) {
                    await toDataURL(base64.src)//esto es para tipo photo osea que ya se encuentran en el servidor un archivo fisico con extension diferente a la png
                        .then(myBase64 => {
                            objetos.push({
                                ['row']: {
                                    Id_Vehiculo : table.rows[i].cells[0].innerHTML,
                                    Placas: table.rows[i].cells[1].innerHTML,
                                    Marca: table.rows[i].cells[2].innerHTML,
                                    Submarca: table.rows[i].cells[3].innerHTML,
                                    Color: table.rows[i].cells[4].innerHTML,
                                    Modelo: table.rows[i].cells[5].innerHTML,
                                    Nombre_Propietario: table.rows[i].cells[6].innerHTML,
                                    Nivs: table.rows[i].cells[7].innerHTML,
                                    InfoPlaca: table.rows[i].cells[8].innerHTML,
                                    Capturo: table.rows[i].cells[10].innerHTML,
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
                            Id_Vehiculo : table.rows[i].cells[0].innerHTML,
                            Placas: table.rows[i].cells[1].innerHTML,
                            Marca: table.rows[i].cells[2].innerHTML,
                            Submarca: table.rows[i].cells[3].innerHTML,
                            Color: table.rows[i].cells[4].innerHTML,
                            Modelo: table.rows[i].cells[5].innerHTML,
                            Nombre_Propietario: table.rows[i].cells[6].innerHTML,
                            Nivs: table.rows[i].cells[7].innerHTML,
                            InfoPlaca: table.rows[i].cells[8].innerHTML,
                            Capturo: table.rows[i].cells[10].innerHTML,
                            typeImage: type,
                            nameImage: nameImage,
                            image:  base64.src,
                            imagebase64:base64.src
                        }
                    });
                }
            }else{// si es tipo file guarda los datos de la imagen
                let aux=document.getElementById('fileFotoVehiculo_row'+i)
                let base64URL = await encodeFileAsBase64URL(aux.files[0]);
                objetos.push({
                    ['row']: {
                        Id_Vehiculo : table.rows[i].cells[0].innerHTML,
                        Placas: table.rows[i].cells[1].innerHTML,
                        Marca: table.rows[i].cells[2].innerHTML,
                        Submarca: table.rows[i].cells[3].innerHTML,
                        Color: table.rows[i].cells[4].innerHTML,
                        Modelo: table.rows[i].cells[5].innerHTML,
                        Nombre_Propietario: table.rows[i].cells[6].innerHTML,
                        Nivs: table.rows[i].cells[7].innerHTML,
                        InfoPlaca: table.rows[i].cells[8].innerHTML,
                        Capturo: table.rows[i].cells[10].innerHTML,
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
                    Id_Vehiculo : table.rows[i].cells[0].innerHTML,
                    Placas: table.rows[i].cells[1].innerHTML,
                    Marca: table.rows[i].cells[2].innerHTML,
                    Submarca: table.rows[i].cells[3].innerHTML,
                    Color: table.rows[i].cells[4].innerHTML,
                    Modelo: table.rows[i].cells[5].innerHTML,
                    Nombre_Propietario: table.rows[i].cells[6].innerHTML,
                    Nivs: table.rows[i].cells[7].innerHTML,
                    InfoPlaca: table.rows[i].cells[8].innerHTML,
                    Capturo: table.rows[i].cells[10].innerHTML,
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
/*--------------------------FUNCIONES PARA EL ALMACENAMIENTO DE LOS DATOS CONTENIDOS EN EL FRAME  --------------- */
var msg_VehiculosError = document.getElementById('msg_principales_vehiculos');
var datosVehiculos = document.getElementById('datos_vehiculos')
document.getElementById('btn_vehiculos').addEventListener('click', async function(e) {
    let myFormDataVehiculos = new FormData(datosVehiculos)//RECUERDA SIEMPRE ENVIAR LOS DATOS DEL FRAME Y AUN MAS IMPORTANTE POR LAS IMAGENES LAS DETECTE EN EL POST
    var Vehiculos =  await readTableVehiculos();//LEEMOS EL CONTENIDO DE LA TABLA DE Vehiculos 
    myFormDataVehiculos.append('Vehiculos_table', JSON.stringify(Vehiculos)); //CODIFICAMOS LOS DATOS PARA QUE EL CONTROLADOR LOS OCUPE 
    myFormDataVehiculos.append('id_seguimiento',document.getElementById('id_seguimiento_principales').value)
    let button = document.getElementById('btn_vehiculos')
    button.innerHTML = `
        Guardando
        <div class="spinner-grow spinner-grow-sm" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    `;
    button.classList.add('disabled-link');
    $('#ModalCenterPrincipalVehiculo').modal('show');
    fetch(base_url_js + 'Seguimientos/UpdateVehiculosFetch', {//realiza el fetch para actualizar los datos
        method: 'POST',
        body: myFormDataVehiculos
    })

    .then(res => res.json())

    .then(data => {//obtine  respuesta del modelo
        button.innerHTML = `Guardar`;
        button.classList.remove('disabled-link');//se vuelve activar la funcion del boton 
        $('#ModalCenterPrincipalVehiculo').modal('hide');//se quita la imagen 
        banderafotosVP=true;
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
            msg_VehiculosError.innerHTML = messageError
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });

        } else {//si todo salio bien
            alertaVehiculo()//Si todo salio bien actualiza los datos arroja un mensaje satisfactorio
        }
    })
})
const alertaVehiculo = async()=>{// FUNCION QUE MUESTRA QUE LOS DATOS FUERON ACTUALIZADOS CORRECTAMENTE 
    await RecargaDatosVehiculos();
    msg_VehiculosError.innerHTML = `<div class="alert alert-success text-center" role="success">Datos de Vehiculos Actualizados correctamente.
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
/*---------------------------------------FUNCIONES DE AUTOCOMPLETE EN EL FORMULARIO------------------------------*/
document.getElementById('marca').addEventListener('input', () => { 
    myFormData.append('termino', document.getElementById('marca').value)//REALIZA UN FETCH PARA TRAER EL CATALOGO VEHICULOS PARA QUE SERA COMPARADO CON LO QUE SE ESTA INGRESANDO ADEMAS DE SE OCUPARA PARA LA FUNCION DEL AUTOCOMPLETE
    fetch(base_url_js + 'Seguimientos/getMarcas', {
            method: 'POST',
            body: myFormData
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `${r.Marca}` , value: `${r.Marca}`}))
        autocomplete({
            input: marca,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {
                marca.value = item.value;
            }
        }); 
    })
    .catch(err => alert(`Ha ocurrido un error al obtener los Vehiculos.\nCódigo de error: ${ err }`))
});

document.getElementById('submarca').addEventListener('input', () => { 
    myFormData.append('termino', document.getElementById('marca').value)//REALIZA UN FETCH PARA TRAER EL CATALOGO VEHICULOS PARA QUE SERA COMPARADO CON LO QUE SE ESTA INGRESANDO ADEMAS DE SE OCUPARA PARA LA FUNCION DEL AUTOCOMPLETE
    fetch(base_url_js + 'Seguimientos/getSubmarcas', {
            method: 'POST',
            body: myFormData
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `${r.Submarca}` , value: `${r.Submarca}`}))
        autocomplete({
            input: submarca,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {
                submarca.value = item.value;
            }
        }); 
    })
    .catch(err => alert(`Ha ocurrido un error al obtener los Vehiculos.\nCódigo de error: ${ err }`))
});

const verificaInfoVehiculoRed = async() =>{
    let myFormDataConsulta = new FormData();//LEEMOS EL CONTENIDO 
    let placa = document.getElementById('placas').value.toUpperCase();
    let niv = document.getElementById('NIVS').value.toUpperCase();
    
    myFormDataConsulta.append('Placa',placa.trim());
    myFormDataConsulta.append('Niv',niv.trim());

    console.log(placa,niv)

    fetch(base_url_js + 'Seguimientos/ConsultaVehiculoFetch', {//realiza el fetch para consultar
        method: 'POST',
        body: myFormDataConsulta
    })

    .then(res => res.json())

    .then(data => {//obtiene respuesta del modelo
        if(Object.keys(data).length>0){
            cad = ""
            data.forEach(element => {
                let alto_impacto =(element.Alto_Impacto==1)?'UNA RED DE ALTO IMPACTO FOLIO: ':'UNA RED DE GABINETE FOLIO: ';
                cad += alto_impacto+element.Id_Seguimiento+" "+element.Nombre_grupo_delictivo+" PLACAS: "+element.Placas+" NIVS: "+element.Nivs;  
            });
            coincidencia = "El VEHICULO TIENE UNA COINCIDENCIA EN "+cad+" FAVOR DE REVISAR ";
            verificaInfoVehiculoEventos(coincidencia,placa);
           
        }else{
            verificaInfoVehiculoEventos("",placa);
        }
    })
}
const verificaInfoVehiculoEventos = async(coincidencia,placa2) =>{
    let myFormDataConsulta = new FormData();//LEEMOS EL CONTENIDO 
    let placa = placa2
    
    myFormDataConsulta.append('Placa',placa.trim());


    fetch(base_url_js + 'Seguimientos/ConsultaVehiculoEFetch', {//realiza el fetch para consultar
        method: 'POST',
        body: myFormDataConsulta
    })

    .then(res => res.json())

    .then(data => {//obtiene respuesta del modelo
        if(Object.keys(data).length>0){
            cad = ""
            data.forEach(element => {
                cad += "FOLIO DE EVENTO: "+element.Folio_infra+" FOLIO 911: "+element.Folio_911+" PLACA: "+element.Placas_Vehiculo+" MARCA: "+element.Marca+" SUBMARCA: "+element.Submarca+" COLOR: "+element.Color+" ";  
            });
            Swal.fire({
                title: coincidencia +"El VEHICULO TIENE UNA COINCIDENCIA EN "+cad+" FAVOR DE REVISAR",
                icon: 'info',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'custom-confirm-btn'  // Clase CSS personalizada para el botón de confirmación
                },
                buttonsStyling: false
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
                });
            }
        }
    })
}