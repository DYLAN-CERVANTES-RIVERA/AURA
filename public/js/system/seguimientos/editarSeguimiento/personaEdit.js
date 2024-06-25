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
    myFormData.append('termino', inputRemisiones.value)//REALIZA UN FETCH PARA TRAER EL CATALOGO DE REMISIONES PARA QUE SEA COMPARADO CON LO QUE SE ESTA INGRESANDO ADEMAS DE SE OCUPARA PARA LA FUNCION DEL AUTOCOMPLETE
    fetch(base_url_js + 'Seguimientos/getRemisiones', {
            method: 'POST',
            body: myFormData
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `REMISION:${r.No_Remision} NOMBRE DETENIDO:${r.Nombre_completo} DELITOS:${r.Faltas_Delitos_Detenido}` , value: `${r.No_Remision}`}))
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
    })
    .catch(err => alert(`Ha ocurrido un error al obtener las Remisiones.\nCódigo de error: ${ err }`))
});
const onFormRemisionSubmit = async() => {//FUINCION QUE EVALUA SI LA ENTRADA DE NUMERO DE REMISION EXISTE EN EL CATALOGO
    InfoRemision= await getInfoRemision(inputRemisiones.value)
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
        const response = await fetch(base_url_js + 'Seguimientos/getInfoRemision', {
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
    let FechaNacimiento_principales = document.getElementById('FechaNacimiento_principales');
    let Genero = document.getElementById('Genero');
    let telefono = document.getElementById('telefono');
    let alias = document.getElementById('alias');
    let remisiones = document.getElementById('remisiones');

    nombre.value=data.Nombre;
    ap_paterno.value=data.Ap_Paterno;
    ap_materno.value=data.Ap_Materno;
    curp.value=(data.CURP!='SD')?data.CURP:'';
    FechaNacimiento_principales.value=(data.Fecha_Nacimiento!='')?data.Fecha_Nacimiento:'';
    Genero.value=data.Genero;
    telefono.value=(data.Telefono!='SD')?data.Telefono:'';
    alias.value=(data.Alias!='SD')?data.Alias:'';
    remisiones.value=data.No_Remision;
}
/*----------------------FUNCIONES DE LA TABLA DE PERSONAS----------------- */
let selectedRowPersonas = null//VARIABLE QUE DEFINE SI SE TRATA DE UNA ACTUALIZACION DE UN REGISTRO YA CREADO O DE UNA NUEVA INSERCION
let errorNombre = document.getElementById('nombre_error');
let errorAp_paterno= document.getElementById('ap_paterno_error');
let errorAp_materno= document.getElementById('ap_materno_error');
let errorGenero= document.getElementById('genero_error');
const onFormPersonaSubmit = async() => {
    if(await ValidatablePersona()){
        if (selectedRowPersonas === null){
            InsertPersona();//INSERTA NUEVA FILA EN LA TABLA DE PersonaS
            await verificaInfo(); 
        }else{
            updateRowPersona();//ACTUALIZA LA FILA SELECCIONADA EN LA TABLA DE PersonaS
        }
        resetFormPersona();//LIMPIA LA VISTA 
    }
}
const ValidatablePersona = async() => {//FUNCION QUE VALIDA LAS ENTRADAS DEL FORMULARIO DE PERSONAS PARA QUE SE INGRESE EN LA TABLA
    let respuesta=true;
    if(document.getElementById('nombre').value.trim()==''|| document.getElementById('nombre').value.length<3){
        errorNombre.innerHTML='Ingrese Correctamente el Nombre'
        respuesta=false;
    }else{
        errorNombre.innerHTML=''
    }
    if(document.getElementById('ap_paterno').value.trim()==''|| document.getElementById('ap_paterno').value.length<3){
        errorAp_paterno.innerHTML='Ingrese Correctamente el Apellido Paterno'
        respuesta=false;
    }else{
        errorAp_paterno.innerHTML=''
    }
    if(document.getElementById('ap_materno').value.trim()==''|| document.getElementById('ap_materno').value.length<3){
        errorAp_materno.innerHTML='Ingrese Correctamente el Apellido Materno'
        respuesta=false;
    }else{
        errorAp_materno.innerHTML=''
    }
    if(document.getElementById('Genero').value=='SD'){
        errorGenero.innerHTML='Ingrese el Genero'
        respuesta=false;
    }else{
        errorGenero.innerHTML=''
    }
    return respuesta;
}
const resetFormPersona=async()=>{//FUNCION QUE LIMPA LOS DATOS DEL FORMULARIO DE PERSONAS
    document.getElementById('Id_Personas').value='SD';
    document.getElementById('nombre').value='';
    document.getElementById('ap_paterno').value='';
    document.getElementById('ap_materno').value='';
    document.getElementById('curp').value='';
    document.getElementById('FechaNacimiento_principales').value='';
    document.getElementById('Genero').value='SD';
    document.getElementById('telefono').value='';
    document.getElementById('alias').value='';
    document.getElementById('remisiones').value=''; 
    document.getElementById('Rol').value='INTEGRANTE'
}
const InsertPersona= async()=>{//FUNCION QUE INSERTA LOS DATOS EN LA TABLA DE PERSONAS
    let table = document.getElementById('PersonaTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    let genero = document.getElementById('Genero').value;//SE UTILIZA PARA CONTENER EL DATO YA QUE CON LA FUNCION ASINCRONA SE PIERDE LA INFROMACION DEL FORM
    let telefono = document.getElementById('telefono').value;
    let alias = document.getElementById('alias').value;
    let remisiones = document.getElementById('remisiones').value; 
    newRow.insertCell(0).innerHTML =document.getElementById('Id_Personas').value;
    newRow.insertCell(1).innerHTML =document.getElementById('nombre').value.toUpperCase();
    newRow.insertCell(2).innerHTML =document.getElementById('ap_paterno').value.toUpperCase();
    newRow.insertCell(3).innerHTML =document.getElementById('ap_materno').value.toUpperCase();
    newRow.insertCell(4).innerHTML =(document.getElementById('curp').value!='')?document.getElementById('curp').value:'SD';
    newRow.insertCell(5).innerHTML =(document.getElementById('FechaNacimiento_principales').value!='')?document.getElementById('FechaNacimiento_principales').value:'SD';
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
        newRow.insertCell(6).innerHTML = diferenciaAnios; 
    }else{
        newRow.insertCell(6).innerHTML='SD';
    }
    newRow.insertCell(7).innerHTML = genero
    newRow.insertCell(8).innerHTML =(telefono!='')?telefono:'SD';
    newRow.insertCell(9).innerHTML =(alias.trim()!=''&& alias.toUpperCase()!='NO'&& alias.toUpperCase()!='NINGUNO')?alias.toUpperCase():'SD';
    newRow.insertCell(10).innerHTML =(remisiones!='')?remisiones:'SD';
    newRow.insertCell(11).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoP${newRow.rowIndex}">
                                        <div class="form-group">
                                            <input type="file" name="FotoPersona_row${newRow.rowIndex}" accept="image/*" id="fileFotoPersona_row${newRow.rowIndex}" class="inputfile uploadFileFotoP" onchange="uploadFileP(event)" data-toggle="tooltip" data-placement="bottom">
                                            <label for="fileFotoPersona_row${newRow.rowIndex}"></label>
                                        </div>
                                    </div>
                                    <div id="imageContentP_row${newRow.rowIndex}"></div>`;
    newRow.insertCell(12).innerHTML =document.getElementById('Rol').value.toUpperCase();
    newRow.insertCell(13).innerHTML =document.getElementById('captura_dato_persona').value.toUpperCase();
    newRow.insertCell(14).innerHTML = `<button type="button" class="btn btn-add" onclick="editPersona(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc" value="-" onclick="deleteRowPersona(this,PersonaTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
    newRow.cells[0].style.display = "none";
}
const editPersona = (obj) => {//FUNCION QUE EDITA LA TABLA DE PERSONAS TOMANDO LOS DATOS DE LA ROW SELECCIONADA Y ACOMODANDO LOS DATOS EN LA VISTA PARA SU EDICION
    document.getElementById('alertaEditPersona').style.display = 'block';
    selectedRowPersonas = obj.parentElement.parentElement;
    document.getElementById('Id_Personas').value=selectedRowPersonas.cells[0].innerHTML;
    document.getElementById('nombre').value=selectedRowPersonas.cells[1].innerHTML;
    document.getElementById('ap_paterno').value=selectedRowPersonas.cells[2].innerHTML;
    document.getElementById('ap_materno').value=selectedRowPersonas.cells[3].innerHTML;
    document.getElementById('curp').value=(selectedRowPersonas.cells[4].innerHTML!='SD')?selectedRowPersonas.cells[4].innerHTML:'';
    document.getElementById('FechaNacimiento_principales').value=(selectedRowPersonas.cells[5].innerHTML!='SD')?selectedRowPersonas.cells[5].innerHTML:'';
    document.getElementById('Genero').value=selectedRowPersonas.cells[7].innerHTML;
    document.getElementById('telefono').value=(selectedRowPersonas.cells[8].innerHTML!='SD')?selectedRowPersonas.cells[8].innerHTML:'';
    document.getElementById('alias').value=(selectedRowPersonas.cells[9].innerHTML!='SD')?selectedRowPersonas.cells[9].innerHTML:'';
    document.getElementById('remisiones').value=(selectedRowPersonas.cells[10].innerHTML!='SD')?selectedRowPersonas.cells[10].innerHTML:'';
    document.getElementById('Rol').value=selectedRowPersonas.cells[12].innerHTML;

    window.scroll({
        top: 0,
        left: 100,
        behavior: 'smooth'
    });
}
const updateRowPersona= async()=>{//FUNCION QUE ACTUALIZA LOS DATOS EN LA TABLA DE PERSONAS
    selectedRowPersonas.cells[0].innerHTML=document.getElementById('Id_Personas').value;
    selectedRowPersonas.cells[1].innerHTML=document.getElementById('nombre').value.toUpperCase();
    selectedRowPersonas.cells[2].innerHTML=document.getElementById('ap_paterno').value.toUpperCase();
    selectedRowPersonas.cells[3].innerHTML=document.getElementById('ap_materno').value.toUpperCase();
    selectedRowPersonas.cells[4].innerHTML=(document.getElementById('curp').value!='')?document.getElementById('curp').value:'SD';
    selectedRowPersonas.cells[5].innerHTML=(document.getElementById('FechaNacimiento_principales').value!='')?document.getElementById('FechaNacimiento_principales').value:'SD';
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
        selectedRowPersonas.cells[6].innerHTML = diferenciaAnios; 
    }else{
        selectedRowPersonas.cells[6].innerHTML='SD';
    }

    selectedRowPersonas.cells[7].innerHTML=document.getElementById('Genero').value;
    selectedRowPersonas.cells[8].innerHTML=(document.getElementById('telefono').value!='')?document.getElementById('telefono').value:'SD';
    selectedRowPersonas.cells[9].innerHTML=(document.getElementById('alias').value.trim()!='')?document.getElementById('alias').value.toUpperCase():'SD';
    selectedRowPersonas.cells[10].innerHTML=(document.getElementById('remisiones').value!='')?document.getElementById('remisiones').value:'SD';
    selectedRowPersonas.cells[12].innerHTML=document.getElementById('Rol').value;
    document.getElementById('alertaEditPersona').style.display = 'none';
    selectedRowPersonas= null;
}
const deleteRowPersona = async(obj, tableId) => {//funcion para eliminar una fila en tablas ademas de funcion especial de eliminacion para las tablas personas
    if (confirm('¿Desea eliminar este elemento?')) {
        let row = obj.parentElement.parentElement;
        aux=row.rowIndex;
        if(row.cells[0].innerHTML!='SD'){
            await Desasocia(row.cells[0].innerHTML);
        }
        document.getElementById(tableId.id).deleteRow(row.rowIndex);
        j=aux+1;
        if(tableId.id=='PersonaTable'){//Si es la tabla de personas hace una eliminacion especial debido a las fotos
            table = document.getElementById('PersonaTable')
            j=aux+1;
            for(let i=aux;i<table.rows.length;i++){
                let contenedorImg =table.rows[i].cells[11].children[1];
                contenedorImg.setAttribute('id', 'imageContentP_row'+i);
                if(contenedorImg.childNodes.length>1){
                    if(contenedorImg.childNodes[1].childNodes[1]!=undefined){
                        contenedorImg.childNodes[1].childNodes[1].setAttribute('onclick', "deleteImageFotoP("+i+")");
                        contenedorImg.childNodes[3].setAttribute('id', 'imagesP_row_'+i);
                        contenedorImg.childNodes[3].setAttribute('data-target', '#ModalCenterInvolucrado'+i);
                        if(contenedorImg.childNodes[5].getAttribute('class')==j+' Photo'){
                            contenedorImg.childNodes[5].setAttribute('class', i+' Photo');
                        }else{
                            contenedorImg.childNodes[5].setAttribute('class', i+' File');
                        }
                        contenedorImg.childNodes[7].setAttribute('id', 'ModalCenterInvolucrado'+i);
                    }else{
                        contenedorImg.childNodes[0].childNodes[1].setAttribute('onclick', "deleteImageFotoP("+i+")");
                        contenedorImg.childNodes[2].setAttribute('id', 'imagesP_row_'+i);
                        contenedorImg.childNodes[2].setAttribute('data-target', '#ModalCenterInvolucrado'+i);
    
                        if(contenedorImg.childNodes[4].getAttribute('class')==j+' Photo'){
                            contenedorImg.childNodes[4].setAttribute('class', i+' Photo');
                        }else{
                            contenedorImg.childNodes[4].setAttribute('class', i+' File');
                        }
                        contenedorImg.childNodes[6].setAttribute('id', 'ModalCenterInvolucrado'+i);
                    }  
                }
                let contenedorInput =table.rows[i].cells[11].children[0];
                contenedorInput.setAttribute('id', 'uploadFileFotoP'+i);
                contenedorInput.childNodes[1].childNodes[1].setAttribute('id', 'fileFotoPersona_row'+i);
                contenedorInput.childNodes[1].childNodes[1].setAttribute('name', 'FotoPersona_row'+i);
                contenedorInput.childNodes[1].childNodes[3].setAttribute('for', 'fileFotoPersona_row'+i);
                j++;
            }
        }
        await RecargaDatosPersonas();
    }
}
const Desasocia= async(Id_Persona)=>{//FUNCION QUE ELIMINA LOS DATOS DE LAS TABLAS DE PERSONAS
    try {
        myFormData.append('Id_Persona',Id_Persona)
        const response = await fetch(base_url_js + 'Seguimientos/DesAsociaPersona', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
    
}
function uploadFileP(event, type) {//Funcion para actualizar las imagenes de la tabla de personas
    let file;
    if (type) {
        file = 'Photo';
    } else {
        file = 'File';
    }
    if (event.currentTarget.classList.contains('uploadFileFotoP')) {//TABLA DE PERSONAS
        if (validateImage(event.target)) {
            const src = URL.createObjectURL(event.target.files[0]);
            const row = event.currentTarget;
            const index = row.parentNode.parentNode.parentNode.parentNode.rowIndex;
            createElementFotoInvolucrado(src, index, 'File');
        } else {
            msg_personasError.innerHTML = '<div class="alert alert-warning text-center" role="alert">Verificar el archivo cargado.<br>Posibles errores: <br> - Archivo muy pesado (Máximo 8 megas). <br> -Extensión no aceptada (Extensiones aceptadas: jpeg, png, jpg, PNG).</div>';
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });
        }
    }
}
function createElementFotoInvolucrado(src, index, type, view) {//FUNCION PARA LA VISUALIZACION DE IMAGENES EN LA TABAL DE PERSONAS
    const div = document.getElementById('imageContentP_row' + index);
    if (view === undefined) {
        div.innerHTML = `<div class="d-flex justify-content-end">
                            <span onclick="deleteImageFotoP(${index})" class="deleteFile">X</span>
                        </div>
                        <img name="nor" src="${src}" id="imagesP_row_${index}" width="400px" data-toggle="modal" data-target="#ModalCenterInvolucrado${index}">
                        <input type="hidden" class="${index} ${type}"/>
                        <div class="modal fade " id="ModalCenterInvolucrado${index}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
function deleteImageFotoP(index) {//FUNCION QUE ELIMINA LA VISUALIZACION Y EL CONTENIDO DE LAS IMAGENES
    const div = document.getElementById('imageContentP_row' + index);
    document.getElementById('fileFotoPersona_row' + index).value = '';
    div.innerHTML = '';
}
/*------------------------- FUNCIONES QUE VALIDAN LAS ENTRADAS DE INFORMACION DEL FORMULARIO----------------------------------*/
function valida(evt) {//FUNCION QUE VALIDA LA INSERCION DE SOLO LETRAS Y VOCALES CON ASCENTOS
    var code = (evt.which) ? evt.which : evt.keyCode;
    let codigos = [8,32,193,201,205,211,218,225,233,237,243,250,209,241]//TECLAS DELETE,ESPACIO Y LETRAS ACENTUADAS
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
const getEdad=async(FechaNacimiento)=>{//Funcion que obtiene la edad deacuerdo al campo de fecha de nacimiento
    let fechaActual = new Date();
    // Obtiene los componentes de la fecha
    let dia = fechaActual.getDate();
    let mes = fechaActual.getMonth() + 1; // Los meses comienzan en 0, por lo que se suma 1
    let anio = fechaActual.getFullYear();
    // Formatea la fecha en el formato deseado (opcional)
    let fechaFormateada = anio + '-' + mes + '-' + dia;
    let fecha1 = new Date(FechaNacimiento);
    let fecha2 = new Date(fechaFormateada);
    let diferenciaMs = fecha2.getTime() - fecha1.getTime();
    // Convierte la diferencia en años
    let diferenciaAnios = Math.floor(diferenciaMs / 31536000000);
    return diferenciaAnios;
}
//FUNCIONES PARA GUARDAR INFORMACION EN LA BASE DE DATOS Y REFRESCAR LA INFORMACION DE LA VISTA DE LA TABLA
const readTablePersonas = async() => {//lee los datos de la tabla personas y genera una estructura deacuerdo a los datos contenido es la tabla
    let table = document.getElementById('PersonaTable');
    let objetos = [];
    for (let i = 1; i < table.rows.length; i++) {
        let input = table.rows[i].cells[11].children[1].children[1];
        if (input != undefined) {//si en el contenedor existe alguna imagen verifica el tipo
            let type = table.rows[i].cells[11].children[1].children[2].classList[1]
            let base64 = document.getElementById('imagesP_row_' + i);
            nameImage = 'FotoPersona_row' + i;
            isPNG = base64.src.split('.');
            if (type != 'File') {//funcion para leer las fotos
                if (isPNG[1] != undefined) {
                    await toDataURL(base64.src)//esto es para tipo photo osea que ya se encuentran en el servidor un archivo fisico con extension diferente a la png
                        .then(myBase64 => {
                            objetos.push({
                                ['row']: {
                                    Id_Persona : table.rows[i].cells[0].innerHTML,
                                    Nombre: table.rows[i].cells[1].innerHTML,
                                    Ap_Paterno: table.rows[i].cells[2].innerHTML,
                                    Ap_Materno: table.rows[i].cells[3].innerHTML,
                                    Curp: table.rows[i].cells[4].innerHTML,
                                    Fecha_Nacimiento: table.rows[i].cells[5].innerHTML,
                                    Edad: table.rows[i].cells[6].innerHTML,
                                    Genero: table.rows[i].cells[7].innerHTML,
                                    Telefono: table.rows[i].cells[8].innerHTML,
                                    Alias: table.rows[i].cells[9].innerHTML,
                                    Remisiones: table.rows[i].cells[10].innerHTML,
                                    Rol: table.rows[i].cells[12].innerHTML,
                                    Capturo: table.rows[i].cells[13].innerHTML,
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
                            Id_Persona : table.rows[i].cells[0].innerHTML,
                            Nombre: table.rows[i].cells[1].innerHTML,
                            Ap_Paterno: table.rows[i].cells[2].innerHTML,
                            Ap_Materno: table.rows[i].cells[3].innerHTML,
                            Curp: table.rows[i].cells[4].innerHTML,
                            Fecha_Nacimiento: table.rows[i].cells[5].innerHTML,
                            Edad: table.rows[i].cells[6].innerHTML,
                            Genero: table.rows[i].cells[7].innerHTML,
                            Telefono: table.rows[i].cells[8].innerHTML,
                            Alias: table.rows[i].cells[9].innerHTML,
                            Remisiones: table.rows[i].cells[10].innerHTML,
                            Rol: table.rows[i].cells[12].innerHTML,
                            Capturo: table.rows[i].cells[13].innerHTML,
                            typeImage: type,
                            nameImage: nameImage,
                            image:  base64.src,
                            imagebase64:base64.src
                        }
                    });
                }
            }else{// si es tipo file guarda los datos de la imagen
                let aux=document.getElementById('fileFotoPersona_row'+i)
                let base64URL = await encodeFileAsBase64URL(aux.files[0]);
                objetos.push({
                    ['row']: {
                        Id_Persona : table.rows[i].cells[0].innerHTML,
                        Nombre: table.rows[i].cells[1].innerHTML,
                        Ap_Paterno: table.rows[i].cells[2].innerHTML,
                        Ap_Materno: table.rows[i].cells[3].innerHTML,
                        Curp: table.rows[i].cells[4].innerHTML,
                        Fecha_Nacimiento: table.rows[i].cells[5].innerHTML,
                        Edad: table.rows[i].cells[6].innerHTML,
                        Genero: table.rows[i].cells[7].innerHTML,
                        Telefono: table.rows[i].cells[8].innerHTML,
                        Alias: table.rows[i].cells[9].innerHTML,
                        Remisiones: table.rows[i].cells[10].innerHTML,
                        Rol: table.rows[i].cells[12].innerHTML,
                        Capturo: table.rows[i].cells[13].innerHTML,
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
                    Id_Persona : table.rows[i].cells[0].innerHTML,
                    Nombre: table.rows[i].cells[1].innerHTML,
                    Ap_Paterno: table.rows[i].cells[2].innerHTML,
                    Ap_Materno: table.rows[i].cells[3].innerHTML,
                    Curp: table.rows[i].cells[4].innerHTML,
                    Fecha_Nacimiento: table.rows[i].cells[5].innerHTML,
                    Edad: table.rows[i].cells[6].innerHTML,
                    Genero: table.rows[i].cells[7].innerHTML,
                    Telefono: table.rows[i].cells[8].innerHTML,
                    Alias: table.rows[i].cells[9].innerHTML,
                    Remisiones: table.rows[i].cells[10].innerHTML,
                    Rol: table.rows[i].cells[12].innerHTML,
                    Capturo: table.rows[i].cells[13].innerHTML,
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
/*--------------------------FUNCIONES PARA EL ALMACENAMIENTO DE LOS DATOS CONTENIDOS EN EL FRAME  --------------- */
var msg_personasError = document.getElementById('msg_principales_personas');
var datosPersonas = document.getElementById('datos_personas')
document.getElementById('btn_personas').addEventListener('click', async function(e) {
    let myFormDataPersonas = new FormData(datosPersonas)//RECUERDA SIEMPRE ENVIAR LOS DATOS DEL FRAME Y AUN MAS IMPORTANTE POR LAS IMAGENES LAS DETECTE EN EL POST
    var Personas =  await readTablePersonas();//LEEMOS EL CONTENIDO DE LA TABLA DE PERSONAS 
    myFormDataPersonas.append('Personas_table', JSON.stringify(Personas)); //CODIFICAMOS LOS DATOS PARA QUE EL CONTROLADOR LOS OCUPE 
    myFormDataPersonas.append('id_seguimiento',document.getElementById('id_seguimiento_principales').value)
    let button = document.getElementById('btn_personas')
    button.innerHTML = `
        Guardando
        <div class="spinner-grow spinner-grow-sm" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    `;
    button.classList.add('disabled-link');
    $('#ModalCenterFoto2').modal('show');
    fetch(base_url_js + 'Seguimientos/UpdatePersonasFetch', {//realiza el fetch para actualizar los datos
        method: 'POST',
        body: myFormDataPersonas
    })

    .then(res => res.json())

    .then(data => {//obtine  respuesta del modelo
        button.innerHTML = `Guardar`;
        button.classList.remove('disabled-link');//se vuelve activar la funcion del boton 
        $('#ModalCenterFoto2').modal('hide');//se quita la imagen 
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
            msg_personasError.innerHTML = messageError
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });
        } else {//si todo salio bien
            alertaPersona()//Si todo salio bien actualiza los datos arroja un mensaje satisfactorio
        }
    })
})
const alertaPersona = async()=>{/// todo bien en la edicion
    await RecargaDatosPersonas();
    msg_personasError.innerHTML = `<div class="alert alert-success text-center" role="success">Datos de Personas Actualizados correctamente.
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
const verificaInfo = async() =>{
    let myFormDataConsulta = new FormData();//LEEMOS EL CONTENIDO DE LA TABLA DE PERSONAS 
    let nombre = document.getElementById('nombre').value.toUpperCase();
    let ap_paterno = document.getElementById('ap_paterno').value.toUpperCase();
    let ap_materno = document.getElementById('ap_materno').value.toUpperCase();
    
    myFormDataConsulta.append('Nombre',nombre.trim());
    myFormDataConsulta.append('Ap_paterno',ap_paterno.trim());
    myFormDataConsulta.append('Ap_materno',ap_materno.trim());

    fetch(base_url_js + 'Seguimientos/ConsultaPersonaFetch', {//realiza el fetch para consultar
        method: 'POST',
        body: myFormDataConsulta
    })

    .then(res => res.json())

    .then(data => {//obtiene respuesta del modelo
        if(Object.keys(data).length>0){
            cad = ""
            console.log(Object.keys(data).length)
            data.forEach(element => {
                let alto_impacto =(element.Alto_Impacto==1)?'UNA RED DE ALTO IMPACTO FOLIO: ':'UNA RED DE GABINETE FOLIO: ';
                cad += alto_impacto+element.Id_Seguimiento+", "+element.Nombre_grupo_delictivo+", "+element.Nombre+" "+element.Ap_Paterno+" "+element.Ap_Materno;  
            });
            
            Swal.fire({
                title: "LA PERSONA TIENE UNA COINCIDENCIA EN "+cad+" FAVOR DE REVISAR",
                icon: 'info',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'custom-confirm-btn'  // Clase CSS personalizada para el botón de confirmación
                },
                buttonsStyling: false
            });
            //alert("LA PERSONA TIENE UNA COINCIDENCIA EN "+cad+" FAVOR DE REVISAR")
        }
    })
}