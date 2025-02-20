/*----------------ESTE ARCHIVO DE JAVASCRIPT ES PARA LA FUNCIONALIDADES DEL GESTOR EN UN NUEVO EVENTO  Y CONTIENE LAS FUNCIONALIDADES DE LAS TABLAS-------------*/
let zona = document.getElementById('zona');
let vector = document.getElementById('vector');
var vectores;
var TablaErrorhecho = document.getElementById('tabla_hecho_error')
var TablaError = document.getElementById('tabla_error')
delito = document.getElementById('delitos_principales')
otro = document.getElementById('otrofg')
otrovalorinput = document.getElementById('delitos_otro')
// PRE CARGA DE CATALOGOS NECESARIOS 
const getAllVectores = async () => {
    try{
        const response = await fetch(base_url_js + 'GestorCasos/getAllVector', {
            method: 'POST',
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
//-------------Funcion de Inicializacion para vista
document.addEventListener('DOMContentLoaded',async() => {
    vectores = await getAllVectores();
    delito.addEventListener('keyup', esOtro);
    fechahora_captura = document.getElementById('fechahora_captura_principales');
    fechahora_captura.value = getFechaActual();
    fechahora_captura.disabled= true;
    captura=document.getElementById('captura_principales');
    captura.disabled= true;
    document.getElementById('cons').setAttribute('value', 'NA');
});
//-------------Funcion que dispara el cambio en el selector de vector cuando se cambia la opcion de la zona
zona.addEventListener('change', () => {
    vector.innerHTML="";
    if(zona.value.includes('ZONA')){
        zonaValue = zona.value.split(' ');
        vectoresFiltrados = vectores.filter((vector) => vector.Zona == zonaValue[1]);
    }else {
        zonaValue = 'CH';
        vectoresFiltrados = vectores.filter((vector) => vector.Zona == zonaValue);
    }
    if(zona.value == "NA"){
        vectoresFiltrados = [{Id_vector_Interno : 'SELECCIONE', Zona: 0, Region: 'LA ZONA'}]
    }
    vectoresFiltrados.forEach(vectorE => {
        vector.innerHTML += `<option value="${vectorE.Id_vector_Interno}">${vectorE.Id_vector_Interno} - ${vectorE.Region}</option>`
    })
});

/*------------- FUNCION AUTOCOMPLETE DE PROBABLE DELITO O FALTA ----------------------- */

const inputDelitos = document.getElementById('delitos_principales');
const myFormData =  new FormData();
inputDelitos.addEventListener('input', () => { 
    myFormData.append('termino', inputDelitos.value)//REALIZA UN FETCH PARA TRAER EL CATALOGO DELITOS PARA QUE SERA COMPARADO CON LO QUE SE ESTA INGRESANDO ADEMAS DE SE OCUPARA PARA LA FUNCION DEL AUTOCOMPLETE
    fetch(base_url_js + 'GestorCasos/getDelitos', {
            method: 'POST',
            body: myFormData
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `${r.Descripcion}`, value: `${r.Descripcion}`}))
        autocomplete({
            input: delitos_principales,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {
                delitos_principales.value = item.label;
            }
        }); 
    })
    .catch(err => alert(`Ha ocurrido un error al obtener los Delitos.\nCódigo de error: ${ err }`))
});
/*------------------FUNCIONES DE TODAS LAS TABLAS DE PARA LA CAPTURA DE LOS DATOS PRINCIPALES -------------- */
/*------------------FUNCIONES DE LA TABLA DE FALTAS DELITOS-------------- */

let selectedRowOtros = null;
const esOtro = () => {//FUNCION ESPECIAL PARA CUANDO LLENE CON EL CAMPO DE OTRO 
    if(delito.value.toLowerCase() == 'otro'){
        otro.classList.remove('mi_hide')
    }else{
        otro.classList.add('mi_hide')
    }
}

const onFormOtroSubmit = async() => {//SE LANZA LA FUNCION CON EL BOTON DE + DE LA TABLA DELITOS
    const campos = ['delitos_principales','tipo_delito'];
    bandera= await validateFormOtro(campos)//VERIFICA SI EL DELITO ES PARTE DEL CATALOGO EN CASO DE QUE NO INFORMA AL USUARIO QUE INGRESE UN DELITO VALIDO
    if (bandera === true) {
        TablaError.innerText = ''
        let formData = readFormOtros(campos);
        if (selectedRowOtros === null){
            if(formData.delitos_principales.toLowerCase() != 'otro'){
                    insertNewRowOtro(formData);//INSERTA LOS DATOS EN LA TABLA CONTENEDORA
                    resetFormOtros(campos);//LIMPIA LOS CAMPOS DE LA VISTA EN DONDE SE CAPTURAN LOS DELITOS
                }
            if(formData.delitos_principales.toLowerCase() == 'otro' && otrovalorinput.value != ''){
                formData.delitos_principales = otrovalorinput.value
                insertNewRowOtro(formData);//INSERTA LOS DATOS EN LA TABLA CONTENEDORA
                resetFormOtros(campos);//LIMPIA LOS CAMPOS DE LA VISTA EN DONDE SE CAPTURAN LOS DELITOS
            }
        }else{
            if(formData.delitos_principales.toLowerCase() != 'otro'){
                updateRowOtro(formData);//ACTUALIZA LOS DATOS EN LA TABLA CONTENEDORA
                resetFormOtros(campos);//LIMPIA LOS CAMPOS DE LA VISTA EN DONDE SE CAPTURAN LOS DELITOS
            }

            if(formData.delitos_principales.toLowerCase() == 'otro' && otrovalorinput.value != ''){
                formData.delitos_principales = otrovalorinput.value
                updateRowOtro(formData);//ACTUALIZA LOS DATOS EN LA TABLA CONTENEDORA
                resetFormOtros(campos);//LIMPIA LOS CAMPOS DE LA VISTA EN DONDE SE CAPTURAN LOS DELITOS
            }
        }
    }
}

const insertNewRowOtro = ({ delitos_principales, tipo_delito}, type) => {//Funcion para cuando ingrese "delito" 
    const table = document.getElementById('faltasDelitosTable').getElementsByTagName('tbody')[0];//SELECCIONA LA TABLA
    let newRow = table.insertRow(table.length);//INSERTA EL NUEVO ROW 
    if(delitos_principales.toLowerCase() == 'otro' ){//EN EL CASO ESPECIAL DE QUE EL DELITO AGREGADO SEA 'OTRO' SE ABRE EL PANEL DE ESPECIFICACION Y SE AGREGA EL DELITO ESPECIFICADO
        delitos_principales = otrovalorinput.value;
    }
    newRow.insertCell(0).innerHTML = delitos_principales.toUpperCase();
    if(tipo_delito==''){

        newRow.insertCell(1).innerHTML = 'SD';
    }else{
        newRow.insertCell(1).innerHTML = tipo_delito.toUpperCase();
    }
    
    if (type === undefined) {//AL NO TENER ESPECICACION ACERCA DEL TIPO DE VISTA SE INFIERE QUE ES UNA TABA EN LA QUE SE PUEDE OCUPAR FUNCIONES DE ACTUALIZACION Y ELIMINACION POR LO QUE SE DA PASO A MOSTRAR LOS BOTONES
        newRow.insertCell(2).innerHTML = `<button type="button" class="btn btn-ssc" value="-" onclick="deleteRow(this,faltasDelitosTable)">
                                            <i class="material-icons">delete</i>
                                        </button>`;
    }
}

const editOtro = (obj) => {//FUNCION EN LA QUE SE EDITA EL DELITO TOMANDO LOS DATOS DE LA ROW SELECCIONADA Y ACOMODANDO LOS DATOS EN LA VISTA PARA SU EDICION
    const campos = ['descripcionOtros'];
    document.getElementById('alertEditObjeto').style.display = 'block';
    selectedRowOtros = obj.parentElement.parentElement;
    for (let i = 0; i < campos.length; i++) {
        document.getElementById(campos[i]).value = selectedRowOtros.cells[i].innerHTML;
    }
}

const updateRowOtro = (data) => {//FUNCION CON LA QUE ACTUALIZA LOS DATOS DE LA TABLA 
    for (dataKey in data) {
        let i = Object.keys(data).indexOf(dataKey);
        selectedRowOtros.cells[i].innerHTML = data[dataKey].toUpperCase();
    }
    document.getElementById('alertEditObjeto').style.display = 'none';
}

const readFormOtros = (campos) => {//FUNCION QUE LEE LOS DATOS DE LA VISTA QUE SE INGRESARAN A LA TABLA
    let formData = {}
    for (let i = 0; i < campos.length; i++) {
        formData[campos[i]] = document.getElementById(campos[i]).value;
    }
    return formData;
}

const resetFormOtros = (campos) => {//FUNCION QUE LIMPIA LOS CAMPOS DE LA VISTA
    for (let i = 0; i < campos.length; i++) {
        document.getElementById(campos[i]).value = '';
    }
    selectedRowOtros = null;
    otrovalorinput.value = '';
    otro.classList.add('mi_hide')
}

const validateFormOtro = async(campos) => {//FUNCION QUE VALIDA SI LOS CAMPOS DE LA VISTA INGRESADOS SON CORRECTOS ANTES DE SER INGRESADOS A LA TABLA
    let isValid = true;
    for (i = 0; i < campos.length; i++) {
        if(campos[i]!='tipo_delito'){
            if (document.getElementById(campos[i]).value === "") {
                isValid = false;
                document.getElementById(campos[i] + '-invalid').style.display = 'block';
            } else {
                document.getElementById(campos[i] + '-invalid').style.display = 'none';
                delitoValido = await validateDelito(document.getElementById(campos[i]).value.toUpperCase());
                if(delitoValido==""){
                    isValid = true
                    document.getElementById('delito_principales_error').innerText = '';
                }else{
                    isValid = false
                    document.getElementById('delito_principales_error').innerText =delitoValido;
                }
            }

        }

    }
    return isValid;
}
const validateDelito = async (delito_buscar)=> {//FUNCION QUE VALIDA SI EL DELITO INGRESADO ESTA EN EL CATALOGO
    var DelitoValido = "";
    if(delito_buscar.length > 0){
        Delitos = await getAllDelito();
        const result = Delitos.find(element => element.Descripcion.toUpperCase() == delito_buscar);
        if (result){
            DelitoValido = true
        }
        if(DelitoValido==false){
            DelitoValido="Ingrese un Delito valido"
        }else{
            DelitoValido=""
        }  
    }
    if(delito_buscar.toUpperCase()=='OTRO'){
        DelitoValido=""
    }
    return DelitoValido;
}
const getAllDelito = async () => {//FUNCION PIDE TODOS LOS DELITOS DEL CATALOGO
    try {
        const response = await fetch(base_url_js + 'GestorCasos/getDelitos', {//REALIZA UN FETCH DE PETICION DE DATOS EN ESTE CASO LOS DELITOS
            method: 'POST'
        });
        const data = await response.json();
        return data;   
    } catch (error) {
        console.log(error);
    }
}

/*--------------Funciones de la tabla de hechos */
let selectedRowHechos = null//VARIABLE QUE DEFINE SI SE TRATA DE UNA ACTUALIZACION DE UN REGISTRO YA CREADO O DE UNA NUEVA INSERCION
const onFormHechosSubmit = () => {//SE LANZA LA FUNCION CON EL BOTON DE + DE LA TABLA HECHOS
    if(document.getElementById('descripcion_hecho').value!=""){//VALIDAD SI EXISTE UNA DESCRIPCION DEL HECHO
        TablaErrorhecho.innerText = ''
        if (selectedRowHechos === null){
            InsertHecho();//INSERTA NUEVA FILA EN LA TABLA DE HECHOS
        }else{
            updateRowHecho();//ACTUALIZA LA FILA SELECCIONADA EN LA TABLA DE HECHOS
        }
        resetFormHechos();//LIMPIA LA VISTA 
    }else{
        document.getElementById('descripcion_error').innerText = 'Debe de especificar el hecho';
    }
}
const InsertHecho = async() => {//INSERTA LOS DATOS CAPTURADOS EN LA VISTA EN LA TABLA DE HECHOS
    const table = document.getElementById('HechosTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    let emojis = /(?:[\u2700-\u27bf]|(?:\ud83c[\udde6-\uddff]){2}|[\ud800-\udbff][\udc00-\udfff]|[\u0023-\u0039]\ufe0f?\u20e3|\u3299|\u3297|\u303d|\u3030|\u24c2|\ud83c[\udd70-\udd71]|\ud83c[\udd7e-\udd7f]|\ud83c\udd8e|\ud83c[\udd91-\udd9a]|\ud83c[\udde6-\uddff]|\ud83c[\ude01-\ude02]|\ud83c\ude1a|\ud83c\ude2f|\ud83c[\ude32-\ude3a]|\ud83c[\ude50-\ude51]|\u203c|\u2049|[\u25aa-\u25ab]|\u25b6|\u25c0|[\u25fb-\u25fe]|\u00a9|\u00ae|\u2122|\u2139|\ud83c\udc04|[\u2600-\u26FF]|\u2b05|\u2b06|\u2b07|\u2b1b|\u2b1c|\u2b50|\u2b55|\u231a|\u231b|\u2328|\u23cf|[\u23e9-\u23f3]|[\u23f8-\u23fa]|\ud83c\udccf|\u2934|\u2935|[\u2190-\u21ff])/g;
    let limpia= document.getElementById('descripcion_hecho').value.toUpperCase();
    limpia=limpia.replace(emojis , '');
    newRow.insertCell(0).innerHTML = limpia;
    newRow.insertCell(1).innerHTML = document.getElementById('fecha_recepcion_hechos').value;
    newRow.insertCell(2).innerHTML = document.getElementById('hora_recepcion_hechos').value;
    newRow.insertCell(3).innerHTML =`<button type="button" class="btn btn-add" onclick="editHecho(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc" value="-" onclick="deleteRow(this,HechosTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
}
const editHecho = (obj) => {//FUNCION QUE EDITA LA TABLA DE HECHOS TOMANDO LOS DATOS DE LA ROW SELECCIONADA Y ACOMODANDO LOS DATOS EN LA VISTA PARA SU EDICION
    document.getElementById('alertaEdithecho').style.display = 'block';
    selectedRowHechos = obj.parentElement.parentElement;
    document.getElementById('descripcion_hecho').value=selectedRowHechos.cells[0].innerHTML;
    document.getElementById('fecha_recepcion_hechos').value=selectedRowHechos.cells[1].innerHTML;
    document.getElementById('hora_recepcion_hechos').value=selectedRowHechos.cells[2].innerHTML;

}
const updateRowHecho = async() => {//FUNCION PARA ACTUALIZAR LOS DATOS DE LA TABLA DE HECHOS
    let emojis = /(?:[\u2700-\u27bf]|(?:\ud83c[\udde6-\uddff]){2}|[\ud800-\udbff][\udc00-\udfff]|[\u0023-\u0039]\ufe0f?\u20e3|\u3299|\u3297|\u303d|\u3030|\u24c2|\ud83c[\udd70-\udd71]|\ud83c[\udd7e-\udd7f]|\ud83c\udd8e|\ud83c[\udd91-\udd9a]|\ud83c[\udde6-\uddff]|\ud83c[\ude01-\ude02]|\ud83c\ude1a|\ud83c\ude2f|\ud83c[\ude32-\ude3a]|\ud83c[\ude50-\ude51]|\u203c|\u2049|[\u25aa-\u25ab]|\u25b6|\u25c0|[\u25fb-\u25fe]|\u00a9|\u00ae|\u2122|\u2139|\ud83c\udc04|[\u2600-\u26FF]|\u2b05|\u2b06|\u2b07|\u2b1b|\u2b1c|\u2b50|\u2b55|\u231a|\u231b|\u2328|\u23cf|[\u23e9-\u23f3]|[\u23f8-\u23fa]|\ud83c\udccf|\u2934|\u2935|[\u2190-\u21ff])/g;
    let limpia= document.getElementById('descripcion_hecho').value.toUpperCase();
    limpia=limpia.replace(emojis , '');
    selectedRowHechos.cells[0].innerHTML = limpia;
    selectedRowHechos.cells[1].innerHTML = document.getElementById('fecha_recepcion_hechos').value;
    selectedRowHechos.cells[2].innerHTML = document.getElementById('hora_recepcion_hechos').value;
    document.getElementById('alertaEdithecho').style.display = 'none';
    selectedRowHechos= null;
}

const resetFormHechos = () => {//FUNCION QUE LIMPIA LOS CAMPOS ASOCIADOS A LA TABLA DE HECHOS
    document.getElementById('descripcion_hecho').value="";
    document.getElementById('fecha_recepcion_hechos').value = getFecha();
    document.getElementById('hora_recepcion_hechos').value = getHora();
}


/*--------------Funciones de la tabla de vehiculos involucrados */
let selectedRowVehiculos = null
const onFormVehiculoSubmit = async () => {//SE LANZA LA FUNCION CON EL BOTON DE + DE LA TABLA VEHICULOS
    if(document.getElementById('Tipo_Vehiculo').value!="NA" && document.getElementById('Tipo_Veh_Involucrado').value!="SD"){
        document.getElementById('Tipo_Vehiculo_principales_error').innerText ='';
        document.getElementById('Tipo_Veh_Involucrado_error').innerText = ''
        if(document.getElementById('Marca').value==""){//VALIDACION QUE LA MARCA SI ES VACIA NO EVALUAR CON EL CATALOGO
            if(document.getElementById('Submarca').value==""){//VALIDACION QUE LA SUBMARS SI ES VACIA NO EVALUAR CON EL CATALOGO
                document.getElementById('Marca_principales_error').innerText ="";
                document.getElementById('Submarca_principales_error').innerText ="";
                if (selectedRowVehiculos === null){
                    InsertVehiculos();//INSERTA NUEVA FILA EN LA TABLA DE VEHICULOS
                    resetFormVehiculos();//LIMPIA LA VISTA DE LOS CAMPOS ASOCIADOS DE LA TABLA DE VEHICULOS
                }else{
                    updateRowVehiculos();//ACTUALIZA LA FILA SELECCIONADA EN LA TABLA DE VEHICULOS
                    resetFormVehiculos();//LIMPIA LA VISTA DE LOS CAMPOS ASOCIADOS DE LA TABLA DE VEHICULOS
                }
            }else{
                submarcaValida = await validateSubmarca(document.getElementById('Submarca').value.toUpperCase());//VALIDACION QUE LA SUBMARCA INGRESADA ESTE EN EL CATALOGO
                if(submarcaValida==""){
                    document.getElementById('Marca_principales_error').innerText ="";
                    document.getElementById('Submarca_principales_error').innerText ="";
                    if (selectedRowVehiculos === null){
                        InsertVehiculos();//INSERTA NUEVA FILA EN LA TABLA DE VEHICULOS
                        resetFormVehiculos();//LIMPIA LA VISTA DE LOS CAMPOS ASOCIADOS DE LA TABLA DE VEHICULOS
                    }else{
                        updateRowVehiculos();//ACTUALIZA LA FILA SELECCIONADA EN LA TABLA DE VEHICULOS
                        resetFormVehiculos();//LIMPIA LA VISTA DE LOS CAMPOS ASOCIADOS DE LA TABLA DE VEHICULOS
                
                    }
                }else{
                    document.getElementById('Submarca_principales_error').innerText = submarcaValida;//SI LA SUBMARCA INGRESADA NO PERTENECE AL CATALOGO INFORMA EN LA VISTA
                }

            }
        }else{
             marcaValida = await validateMarca(document.getElementById('Marca').value.toUpperCase());//VALIDACION QUE LA MARCA INGRESADA ESTE EN EL CATALOGO
            if(marcaValida==""){

                if(document.getElementById('Submarca').value==""){
                    document.getElementById('Marca_principales_error').innerText ="";//LIMPIA LOS MENSAJES DE AVISO EN LOS CAMPOS
                    document.getElementById('Submarca_principales_error').innerText ="";//LIMPIA LOS MENSAJES DE AVISO EN LOS CAMPOS
                    if (selectedRowVehiculos === null){
                        InsertVehiculos();//INSERTA NUEVA FILA EN LA TABLA DE VEHICULOS
                        resetFormVehiculos();//LIMPIA LA VISTA DE LOS CAMPOS ASOCIADOS DE LA TABLA DE VEHICULOS
                    }else{
                        updateRowVehiculos();//ACTUALIZA LA FILA SELECCIONADA EN LA TABLA DE VEHICULOS
                        resetFormVehiculos();//LIMPIA LA VISTA DE LOS CAMPOS ASOCIADOS DE LA TABLA DE VEHICULOS
                
                    }
                }else{
                    submarcaValida = await validateSubmarca(document.getElementById('Submarca').value.toUpperCase());//VALIDACION QUE LA SUBMARCA INGRESADA ESTE EN EL CATALOGO
                    document.getElementById('Marca_principales_error').innerText ="";//LIMPIA LOS MENSAJES DE AVISO EN LOS CAMPOS
                    if(submarcaValida==""){
                        document.getElementById('Marca_principales_error').innerText ="";//LIMPIA LOS MENSAJES DE AVISO EN LOS CAMPOS
                        document.getElementById('Submarca_principales_error').innerText ="";//LIMPIA LOS MENSAJES DE AVISO EN LOS CAMPOS
                        if (selectedRowVehiculos === null){
                            InsertVehiculos();//INSERTA NUEVA FILA EN LA TABLA DE VEHICULOS
                            resetFormVehiculos();//LIMPIA LA VISTA DE LOS CAMPOS ASOCIADOS DE LA TABLA DE VEHICULOS
                        }else{
                            updateRowVehiculos();//ACTUALIZA LA FILA SELECCIONADA EN LA TABLA DE VEHICULOS
                            resetFormVehiculos();//LIMPIA LA VISTA DE LOS CAMPOS ASOCIADOS DE LA TABLA DE VEHICULOS
                    
                        }
                    }else{
                        document.getElementById('Marca_principales_error').innerText ="";//LIMPIA LOS MENSAJES DE AVISO EN LOS CAMPOS
                        document.getElementById('Submarca_principales_error').innerText = submarcaValida;//SI LA SUBMARCA INGRESADA NO PERTENECE AL CATALOGO INFORMA EN LA VISTA
                    }
                }
            }else{
                document.getElementById('Submarca_principales_error').innerText = "";//LIMPIA LOS MENSAJES DE AVISO EN LOS CAMPOS
                document.getElementById('Marca_principales_error').innerText = marcaValida;//SI LA MARCA INGRESADA NO PERTENECE AL CATALOGO INFORMA EN LA VISTA
            }


        }

    }else{
        if(document.getElementById('Tipo_Vehiculo').value=="NA"){
            document.getElementById('Tipo_Vehiculo_principales_error').innerText = 'Debe de especificar el tipo de vehiculo';//EN CASO DE QUE FALTE LLENAR EL CAMPO INFORMA EN LA VISTA
        }
        if(document.getElementById('Tipo_Veh_Involucrado').value=="SD"){
            document.getElementById('Tipo_Veh_Involucrado_error').innerText = 'Debe de especificar el tipo de vehiculo involucrado';//EN CASO DE QUE FALTE LLENAR EL CAMPO INFORMA EN LA VISTA
        }  
    }
}
const InsertVehiculos = async() => {//INSERTA LOS DATOS CAPTURADOS EN LA VISTA EN LA TABLA DE VEHICULOS
    const table = document.getElementById('VehiculoTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    newRow.insertCell(0).innerHTML = document.getElementById('Tipo_Vehiculo').value;
    if(document.getElementById('Marca').value!=""){
        newRow.insertCell(1).innerHTML = document.getElementById('Marca').value.toUpperCase();
         

    }else{
        newRow.insertCell(1).innerHTML = "SD"
    }

    if(document.getElementById('Submarca').value!=""){
        newRow.insertCell(2).innerHTML = document.getElementById('Submarca').value.toUpperCase();
         

    }else{
        newRow.insertCell(2).innerHTML = "SD"
    }
    
    newRow.insertCell(3).innerHTML = document.getElementById('Modelo').value;
    
    if(document.getElementById('Placa_Vehiculo').value!=""){//FUNCION ESPECIAL PARA EL CAMPO PLACA QUE SOLO SE INSERTEN DATOS DE A-Z,a-z Y 0-9
        str=document.getElementById('Placa_Vehiculo').value;
        str=str.replace(/[^a-zA-Z0-9 ]+/g,'');
        newRow.insertCell(4).innerHTML = str.toUpperCase();  

    }else{
        newRow.insertCell(4).innerHTML = "SD"
    }

    if(document.getElementById('Color').value!=""){//FUNCION ESPECIAL PARA QUE EL CAMPO COLOR QUE SOLO SE INSERTEN DATOS DE A-Z Y a-z
        Color=document.getElementById('Color').value;
        Color=Color.replace(/[^a-zA-Z ]+/g,'');
        newRow.insertCell(5).innerHTML = Color.toUpperCase();
    }else{
        newRow.insertCell(5).innerHTML = "SD"
    }

    if(document.getElementById('Descripcion_gral').value!=""){ 
        let emojis = /(?:[\u2700-\u27bf]|(?:\ud83c[\udde6-\uddff]){2}|[\ud800-\udbff][\udc00-\udfff]|[\u0023-\u0039]\ufe0f?\u20e3|\u3299|\u3297|\u303d|\u3030|\u24c2|\ud83c[\udd70-\udd71]|\ud83c[\udd7e-\udd7f]|\ud83c\udd8e|\ud83c[\udd91-\udd9a]|\ud83c[\udde6-\uddff]|\ud83c[\ude01-\ude02]|\ud83c\ude1a|\ud83c\ude2f|\ud83c[\ude32-\ude3a]|\ud83c[\ude50-\ude51]|\u203c|\u2049|[\u25aa-\u25ab]|\u25b6|\u25c0|[\u25fb-\u25fe]|\u00a9|\u00ae|\u2122|\u2139|\ud83c\udc04|[\u2600-\u26FF]|\u2b05|\u2b06|\u2b07|\u2b1b|\u2b1c|\u2b50|\u2b55|\u231a|\u231b|\u2328|\u23cf|[\u23e9-\u23f3]|[\u23f8-\u23fa]|\ud83c\udccf|\u2934|\u2935|[\u2190-\u21ff])/g;
        let limpia= document.getElementById('Descripcion_gral').value.toUpperCase();
        limpia=limpia.replace(emojis , '');
        newRow.insertCell(6).innerHTML =limpia; 
    }else{
        newRow.insertCell(6).innerHTML = "SD"
    }
    newRow.insertCell(7).innerHTML =    `<div class="d-flex justify-content-around" id="uploadFileFotos${newRow.rowIndex}">
                                            <div class="form-group">
                                                <input type="file" name="FotoVehiculo_row${newRow.rowIndex}" accept="image/*" id="fileFotoV_row${newRow.rowIndex}" class="inputfile uploadFileFotosV" onchange="uploadFile(event)" data-toggle="tooltip" data-placement="bottom">
                                                <label for="fileFotoV_row${newRow.rowIndex}"></label>
                                            </div>
                                        </div>
                                        <div id="imageContentV_row${newRow.rowIndex}"></div>`;
    newRow.insertCell(8).innerHTML = document.getElementById('Tipo_Veh_Involucrado').value.toUpperCase();
    newRow.insertCell(9).innerHTML = document.getElementById('Estado_Veh').value.toUpperCase();
    newRow.insertCell(10).innerHTML = document.getElementById('actualizaVP').value.toUpperCase();
    newRow.insertCell(11).innerHTML = `<button type="button" class="btn btn-add" onclick="editVehiculos(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc" value="-" onclick="deleteRow(this,VehiculoTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
  
}
const editVehiculos = (obj) => {//FUNCION QUE EDITA LA TABLA DE VEHICULOS TOMANDO LOS DATOS DE LA ROW SELECCIONADA Y ACOMODANDO LOS DATOS EN LA VISTA PARA SU EDICION
    document.getElementById('alertaEditVehiculos').style.display = 'block';
    selectedRowVehiculos = obj.parentElement.parentElement;
    document.getElementById('Tipo_Vehiculo').value=selectedRowVehiculos.cells[0].innerHTML;
    if(selectedRowVehiculos.cells[1].innerHTML !='SD'){
        document.getElementById('Marca').value=selectedRowVehiculos.cells[1].innerHTML;
    }else{
        document.getElementById('Marca').value="";
    }
    if(selectedRowVehiculos.cells[2].innerHTML !='SD'){
        document.getElementById('Submarca').value=selectedRowVehiculos.cells[2].innerHTML;
    }else{
        document.getElementById('Submarca').value="";
    }
    document.getElementById('Modelo').value=selectedRowVehiculos.cells[3].innerHTML;
    document.getElementById('Placa_Vehiculo').value=selectedRowVehiculos.cells[4].innerHTML;    
    document.getElementById('Color').value=selectedRowVehiculos.cells[5].innerHTML;
    document.getElementById('Descripcion_gral').value=selectedRowVehiculos.cells[6].innerHTML;
    document.getElementById('Tipo_Veh_Involucrado').value=selectedRowVehiculos.cells[8].innerHTML;
    document.getElementById('Estado_Veh').value=selectedRowVehiculos.cells[9].innerHTML;
}
const updateRowVehiculos = async() => {//FUNCION PARA ACTUALIZAR LOS DATOS DE LA TABLA DE VEHICULOS
    selectedRowVehiculos.cells[0].innerHTML = document.getElementById('Tipo_Vehiculo').value;
    if(document.getElementById('Marca').value !=""){
        selectedRowVehiculos.cells[1].innerHTML = document.getElementById('Marca').value;

    }else{
        selectedRowVehiculos.cells[1].innerHTML = 'SD';
    }
    if(document.getElementById('Submarca').value !=""){
        selectedRowVehiculos.cells[2].innerHTML = document.getElementById('Submarca').value;

    }else{
        selectedRowVehiculos.cells[2].innerHTML = 'SD';
    }
    selectedRowVehiculos.cells[3].innerHTML = document.getElementById('Modelo').value;
    if(document.getElementById('Placa_Vehiculo').value!=""){
        str=document.getElementById('Placa_Vehiculo').value;
        str=str.replace(/[^a-zA-Z0-9 ]+/g,'');
        selectedRowVehiculos.cells[4].innerHTML = str.toUpperCase();
        
    }else{
        selectedRowVehiculos.cells[4].innerHTML ='SD';
    }
    if(document.getElementById('Color').value!=""){//FUNCION ESPECIAL PARA QUE EL CAMPO COLOR QUE SOLO SE INSERTEN DATOS DE A-Z Y a-z
        Color=document.getElementById('Color').value;
        Color=Color.replace(/[^a-zA-Z ]+/g,'');
        selectedRowVehiculos.cells[5].innerHTML = Color.toUpperCase();
    }else{
        selectedRowVehiculos.cells[5].innerHTML ='SD';
    }
    if(document.getElementById('Descripcion_gral').value!=""){ 
        let emojis = /(?:[\u2700-\u27bf]|(?:\ud83c[\udde6-\uddff]){2}|[\ud800-\udbff][\udc00-\udfff]|[\u0023-\u0039]\ufe0f?\u20e3|\u3299|\u3297|\u303d|\u3030|\u24c2|\ud83c[\udd70-\udd71]|\ud83c[\udd7e-\udd7f]|\ud83c\udd8e|\ud83c[\udd91-\udd9a]|\ud83c[\udde6-\uddff]|\ud83c[\ude01-\ude02]|\ud83c\ude1a|\ud83c\ude2f|\ud83c[\ude32-\ude3a]|\ud83c[\ude50-\ude51]|\u203c|\u2049|[\u25aa-\u25ab]|\u25b6|\u25c0|[\u25fb-\u25fe]|\u00a9|\u00ae|\u2122|\u2139|\ud83c\udc04|[\u2600-\u26FF]|\u2b05|\u2b06|\u2b07|\u2b1b|\u2b1c|\u2b50|\u2b55|\u231a|\u231b|\u2328|\u23cf|[\u23e9-\u23f3]|[\u23f8-\u23fa]|\ud83c\udccf|\u2934|\u2935|[\u2190-\u21ff])/g;
        let limpia= document.getElementById('Descripcion_gral').value.toUpperCase();
        limpia=limpia.replace(emojis , '');
        selectedRowVehiculos.cells[6].innerHTML =  limpia;
    }else{
        selectedRowVehiculos.cells[6].innerHTML = 'SD';
    }

    selectedRowVehiculos.cells[8].innerHTML =  document.getElementById('Tipo_Veh_Involucrado').value.toUpperCase();
    selectedRowVehiculos.cells[9].innerHTML =  document.getElementById('Estado_Veh').value.toUpperCase();
    selectedRowVehiculos.cells[10].innerHTML =  document.getElementById('actualizaVP').value.toUpperCase();
    document.getElementById('alertaEditVehiculos').style.display = 'none';
    selectedRowVehiculos= null;
}

const resetFormVehiculos = () => {//FUNCION QUE LIMPIA LOS CAMPOS ASOCIADOS A LA TABLA DE VEHICULO
    document.getElementById('Tipo_Vehiculo').value="NA";
    document.getElementById('Marca').value="";
    document.getElementById('Submarca').value="";
    document.getElementById('Modelo').value="SD";
    document.getElementById('Placa_Vehiculo').value="";    
    document.getElementById('Color').value="";
    document.getElementById('Descripcion_gral').value="";
    document.getElementById('Tipo_Veh_Involucrado').value="SD";
    document.getElementById('Estado_Veh').value="NO CORROBORADO";
}
/*--------------Funciones de autocomplete para tabla de vehiculos responsables */
/*--------------Funciones de autocomplete marca */
const inputMarca = document.getElementById('Marca');
inputMarca.addEventListener('input', () => {
    myFormData.append('termino', inputMarca.value)
    fetch(base_url_js + 'GestorCasos/getMarcas', {//REALIZA UN FETCH DE PETICION DE DATOS EN ESTE CASO LAS MARCAS PARA EL AUTOCOMPLETE
            method: 'POST',
            body: myFormData
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `${r.Marca}`, value: `${r.Marca}`}))
        autocomplete({
            input: Marca,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {
                Marca.value = item.label.toUpperCase();
            }
        }); 
    })
    .catch(err => alert(`Ha ocurrido un error al obtener las Marcas.\nCódigo de error: ${ err }`))
});
const validateMarca = async (marca_buscar)=> {//FUNCION QUE VALIDA LA MARCA INGRESADA EN LA VISTA 
    var MarcaValida = "";
    if(marca_buscar.length > 0){
        Marcas = await getAllMarca();
        const result = Marcas.find(element => element.Marca.toUpperCase() == marca_buscar);
        if (result){
            MarcaValida = true
        }
        if(MarcaValida==false){
            MarcaValida="Ingrese una Marca valida"
        }else{
            MarcaValida=""
        }  
    }
    return MarcaValida;
}
const getAllMarca = async () => {//FUNCION PARA SACAR TODAS LAS MARCAS DEL CATALOGO
    try {
        const response = await fetch(base_url_js + 'GestorCasos/getMarcas', {
            method: 'POST'
        });
        const data = await response.json();
        return data;
        
    } catch (error) {
        console.log(error);
    }
}
/*--------------Funciones de autocomplete submarca */
const inputSubmarca = document.getElementById('Submarca');
inputSubmarca.addEventListener('input', () => {
    myFormData.append('termino', inputSubmarca.value)
    fetch(base_url_js + 'GestorCasos/getSubmarcas', {//REALIZA UN FETCH DE PETICION DE DATOS EN ESTE CASO LAS SUBMARCAS PARA EL AUTOCOMPLETE
            method: 'POST',
            body: myFormData
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `${r.Submarca}`, value: `${r.Submarca}`}))
        autocomplete({
            input: Submarca,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {
                Submarca.value = item.label.toUpperCase();
            }
        }); 
    })
    .catch(err => alert(`Ha ocurrido un error al obtener las Submarca.\nCódigo de error: ${ err }`))
});
const validateSubmarca = async (submarca_buscar)=> {//FUNCION QUE VALIDA LA SUBMARCA INGRESADA EN LA VISTA 
    var SubmarcaValida = "";
    if(submarca_buscar.length > 0){
        Submarcas = await getAllSubmarca();
        const result = Submarcas.find(element => element.Submarca.toUpperCase() == submarca_buscar);
        if (result){
            SubmarcaValida = true
        }
        if(SubmarcaValida==false){
            SubmarcaValida="Ingrese una Submarca valida"
        }else{
            SubmarcaValida=""
        }  
    }
    return SubmarcaValida;
}
const getAllSubmarca = async () => {//FUNCION PARA SACAR TODAS LAS SUBMARCAS DEL CATALOGO
    try {
        const response = await fetch(base_url_js + 'GestorCasos/getSubmarcas', {
            method: 'POST'
        });
        const data = await response.json();
        return data; 
    } catch (error) {
        console.log(error);
    }
}

/*--------------Funciones de la tabla de probables responsables */
let selectedRowPR = null
const onFormPRSubmit = () => {//SE LANZA LA FUNCION CON EL BOTON DE + DE LA TABLA PERSONAS
    if(document.getElementById('Sexo').value!="SD"){
        document.getElementById('Sexo_principales_error').innerText =''
        if (selectedRowPR === null){
            InsertPR();
            resetFormPR();
        }else{
            updateRowPR();
            resetFormPR();
        }
    }else{
        document.getElementById('Sexo_principales_error').innerText = 'Debe de especificar por lo menos el sexo'
    }

}
const InsertPR = () => {//INSERTA LOS DATOS CAPTURADOS EN LA VISTA EN LA TABLA DE PERSONAS
    const table = document.getElementById('PersonaTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    newRow.insertCell(0).innerHTML = document.getElementById('Sexo').value;
    newRow.insertCell(1).innerHTML = document.getElementById('Rango_Edad').value;
    newRow.insertCell(2).innerHTML = document.getElementById('Complexion').value;
    
    if(document.getElementById('Descripcion_gral_per').value!=""){
        let emojis = /(?:[\u2700-\u27bf]|(?:\ud83c[\udde6-\uddff]){2}|[\ud800-\udbff][\udc00-\udfff]|[\u0023-\u0039]\ufe0f?\u20e3|\u3299|\u3297|\u303d|\u3030|\u24c2|\ud83c[\udd70-\udd71]|\ud83c[\udd7e-\udd7f]|\ud83c\udd8e|\ud83c[\udd91-\udd9a]|\ud83c[\udde6-\uddff]|\ud83c[\ude01-\ude02]|\ud83c\ude1a|\ud83c\ude2f|\ud83c[\ude32-\ude3a]|\ud83c[\ude50-\ude51]|\u203c|\u2049|[\u25aa-\u25ab]|\u25b6|\u25c0|[\u25fb-\u25fe]|\u00a9|\u00ae|\u2122|\u2139|\ud83c\udc04|[\u2600-\u26FF]|\u2b05|\u2b06|\u2b07|\u2b1b|\u2b1c|\u2b50|\u2b55|\u231a|\u231b|\u2328|\u23cf|[\u23e9-\u23f3]|[\u23f8-\u23fa]|\ud83c\udccf|\u2934|\u2935|[\u2190-\u21ff])/g;
        let limpia= document.getElementById('Descripcion_gral_per').value.toUpperCase();
        limpia=limpia.replace(emojis , '');
        newRow.insertCell(3).innerHTML =limpia;

    }else{

        newRow.insertCell(3).innerHTML = "SD"
    }
    newRow.insertCell(4).innerHTML =  `<div class="d-flex justify-content-around" id="uploadFileFotoP${newRow.rowIndex}">
                                        <div class="form-group">
                                            <input type="file" name="FotoInvolucrado_row${newRow.rowIndex}" accept="image/*" id="fileFotoP_row${newRow.rowIndex}" class="inputfile uploadFileFotoP" onchange="uploadFile(event)" data-toggle="tooltip" data-placement="bottom">
                                            <label for="fileFotoP_row${newRow.rowIndex}"></label>
                                        </div>
                                    </div>
                                    <div id="imageContentP_row${newRow.rowIndex}"></div>`;
    newRow.insertCell(5).innerHTML = document.getElementById('arma_principales_per').value; 
    newRow.insertCell(6).innerHTML = document.getElementById('Estado_Res').value.toUpperCase();
    newRow.insertCell(7).innerHTML = document.getElementById('actualizaVP').value.toUpperCase();             
    newRow.insertCell(8).innerHTML = `<button type="button" class="btn btn-add" onclick="editPR(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc" value="-" onclick="deleteRow(this,PersonaTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
    
}
const editPR = (obj) => {//FUNCION QUE EDITA LA TABLA DE PERSONAS TOMANDO LOS DATOS DE LA ROW SELECCIONADA Y ACOMODANDO LOS DATOS EN LA VISTA PARA SU EDICION
    document.getElementById('alertaEditProbales').style.display = 'block';
    selectedRowPR = obj.parentElement.parentElement;
    document.getElementById('Sexo').value=selectedRowPR.cells[0].innerHTML;
    document.getElementById('Rango_Edad').value=selectedRowPR.cells[1].innerHTML;
    document.getElementById('Complexion').value=selectedRowPR.cells[2].innerHTML;
    document.getElementById('Descripcion_gral_per').value=selectedRowPR.cells[3].innerHTML;
    document.getElementById('arma_principales_per').value=selectedRowPR.cells[5].innerHTML;
    document.getElementById('Estado_Res').value=selectedRowPR.cells[6].innerHTML;
}
const updateRowPR = () => {//FUNCION PARA ACTUALIZAR LOS DATOS DE LA TABLA DE PERSONAS
    selectedRowPR.cells[0].innerHTML = document.getElementById('Sexo').value;
    selectedRowPR.cells[1].innerHTML = document.getElementById('Rango_Edad').value;
    selectedRowPR.cells[2].innerHTML = document.getElementById('Complexion').value;
    if(document.getElementById('Descripcion_gral_per').value!=""){
        let emojis = /(?:[\u2700-\u27bf]|(?:\ud83c[\udde6-\uddff]){2}|[\ud800-\udbff][\udc00-\udfff]|[\u0023-\u0039]\ufe0f?\u20e3|\u3299|\u3297|\u303d|\u3030|\u24c2|\ud83c[\udd70-\udd71]|\ud83c[\udd7e-\udd7f]|\ud83c\udd8e|\ud83c[\udd91-\udd9a]|\ud83c[\udde6-\uddff]|\ud83c[\ude01-\ude02]|\ud83c\ude1a|\ud83c\ude2f|\ud83c[\ude32-\ude3a]|\ud83c[\ude50-\ude51]|\u203c|\u2049|[\u25aa-\u25ab]|\u25b6|\u25c0|[\u25fb-\u25fe]|\u00a9|\u00ae|\u2122|\u2139|\ud83c\udc04|[\u2600-\u26FF]|\u2b05|\u2b06|\u2b07|\u2b1b|\u2b1c|\u2b50|\u2b55|\u231a|\u231b|\u2328|\u23cf|[\u23e9-\u23f3]|[\u23f8-\u23fa]|\ud83c\udccf|\u2934|\u2935|[\u2190-\u21ff])/g;
        let limpia= document.getElementById('Descripcion_gral_per').value.toUpperCase();
        limpia=limpia.replace(emojis , '');
        selectedRowPR.cells[3].innerHTML =limpia;

    }else{

        selectedRowPR.cells[3].innerHTML = "SD"
    }
    selectedRowPR.cells[5].innerHTML = document.getElementById('arma_principales_per').value;
    selectedRowPR.cells[6].innerHTML = document.getElementById('Estado_Res').value.toUpperCase();
    selectedRowPR.cells[7].innerHTML = document.getElementById('actualizaVP').value.toUpperCase(); 
    document.getElementById('alertaEditProbales').style.display = 'none';
    selectedRowPR= null;
}

const resetFormPR = () => {//FUNCION QUE LIMPIA LOS CAMPOS ASOCIADOS A LA TABLA DE PERSONAS
    document.getElementById('Sexo').value="SD";
    document.getElementById('Rango_Edad').value="SD";
    document.getElementById('Complexion').value="SD";
    document.getElementById('Descripcion_gral_per').value="";
    
    if(document.getElementById('violencia_principales').value=="ARMA DE FUEGO"){
        document.getElementById('cons').value="SD";
        console.log('SD')
    }else{
        document.getElementById('cons').value="NA";
        console.log('NA')
    }
    document.getElementById('Estado_Res').value="NO CORROBORADO";
}

/*--------------------------FUNCIONES DEL EVENTO*/
const showHabilitado = () =>{//FUNCION PARA CUANDO SE HABILITA EL EVENTO
    radioHabilitado = document.getElementsByName('Habilitado_question');
    if(radioHabilitado[0].checked){
        document.getElementById('form_activacion').classList.remove('mi_hide');
        fechahora_activacion = document.getElementById('fechahora_activacion_principales');
        fechahora_activacion.value= getFechaActual();
        fechahora_activacion.disabled= true;
    }else if(radioHabilitado[1].checked){
        document.getElementById('form_activacion').classList.add('mi_hide');
    }
}

document.addEventListener('DOMContentLoaded', () =>{//Funciones de la view
    radioHabilitado = document.getElementsByName('Habilitado_question');
    radioHabilitado[0].addEventListener('change',showHabilitado);
    radioHabilitado[1].addEventListener('change',showHabilitado); 
    select.addEventListener('change',showViolencia);
    select2.addEventListener('change',showArmas);
})

const select = document.querySelector("#violencia_principales1");

const showViolencia = () =>{//FUNCIONES PARA LOS SELECTS CON Y SIN VIOLENCIA
    cuestionViolencia= document.getElementById('violencia_principales1').value;
    if(cuestionViolencia=="CON VIOLENCIA"){
        document.getElementById('form_violencia').classList.remove('mi_hide')
        document.getElementById('form_sinviolencia').classList.add('mi_hide')
        document.getElementById('sviolencia_principales').value="NA"
        document.getElementById('sviolencia_principales_error').innerText = '';
        document.getElementById('violencia_principales1_error').innerText = '';
    }else if(cuestionViolencia=="SIN VIOLENCIA"){
        document.getElementById('form_sinviolencia').classList.remove('mi_hide')
        document.getElementById('form_violencia').classList.add('mi_hide')
        
        document.getElementById('violencia_principales').value="NA"
        
        document.getElementById('violencia_principales_error').innerText = '';
        document.getElementById('violencia_principales1_error').innerText = '';
        document.getElementById('cons').setAttribute('value', 'NA');
    }else if(cuestionViolencia=="NA"){
        document.getElementById('form_violencia').classList.add('mi_hide')
        
        document.getElementById('violencia_principales').value="NA"
        
        document.getElementById('form_sinviolencia').classList.add('mi_hide')
        document.getElementById('sviolencia_principales').value="NA"
        document.getElementById('cons').setAttribute('value', 'NA');
    }
}

const select2 = document.querySelector("#violencia_principales");

const showArmas = () =>{//FUNCION PARA CUADO EL TIPO DE VIOLENCIA CAMBIA A 'ARMA DE FUEGO'
    cuestionArmas= document.getElementById('violencia_principales').value;
    document.getElementById('violencia_principales_error').innerText = '';
    if(cuestionArmas=="ARMA DE FUEGO"){
        document.getElementById('cons').setAttribute('value', 'SD');
    }else{
        document.getElementById('cons').setAttribute('value', 'NA');
    }
}


/*  FUNCIONALIDADES DE LAS IMAGENES DE LA  TABLA DE VEHICULOS E INVOLUCRADOS  */

function createElementFotoVehiculo(src, index, type, view) {//PARA CREAR UNA IMAGEN EN LA ROW SELECCIONADA EN LA TABLA DE VEHICULOS
    const div = document.getElementById('imageContentV_row' + index);

    if (view === undefined) {
        div.innerHTML = `<div class="d-flex justify-content-end">
                            <span onclick="deleteImageFotoV(${index})" class="deleteFile">X</span>
                        </div><br>
                        <img name="nor" src="${src}" id="imagesV_row_${index}" width="400px" data-toggle="modal" data-target="#ModalCenterVehiculo${index}">
                        <input type="hidden" class="${index} ${type}"/>
                        <div class="modal fade " id="ModalCenterVehiculo${index}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <img name="nor" src="${src}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                            </div>
                        </div>`;
    } else {
        div.innerHTML = `
            <div>
                <img2 src="${src}">
                <input type="hidden" class="${index} ${type}"/>
            </div>
        `;
    }
}

function uploadFile(event, type) {//CARGAR LA IMAGEN EN LA TABLA
    let file;
    if (type) {
        file = 'Photo';
    } else {
        file = 'File';
    }
    if (event.currentTarget.classList.contains('uploadFileFotosV')) {//CARGA LA IMAGEN EN TABLA DE VEHICULOS
        if (validateImage(event.target)) {//FUNCION QUE VALIDA LA IMAGEN QUE SE SUBIO
            const src = URL.createObjectURL(event.target.files[0]);
            const row = event.currentTarget;
            const index = row.parentNode.parentNode.parentNode.parentNode.rowIndex;
            createElementFotoVehiculo(src, index, 'File');
        } else {
            document.getElementById('msg_fotosParticulares').innerHTML = '<div class="alert alert-warning text-center" role="alert">Verificar el archivo cargado.<br>Posibles errores: <br> - Archivo muy pesado (Máximo 8 megas). <br> -Extensión no aceptada (Extensiones aceptadas: jpeg, png, jpg, PNG).</div>';
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });
        }
    }
    if (event.currentTarget.classList.contains('uploadFileFotoP')) {//CARGA LA IMAGEN EN TABLA DE PERSONAS
        if (validateImage(event.target)) {//FUNCION QUE VALIDA LA IMAGEN QUE SE SUBIO
            const src = URL.createObjectURL(event.target.files[0]);
            const row = event.currentTarget;
            const index = row.parentNode.parentNode.parentNode.parentNode.rowIndex;
            createElementFotoInvolucrado(src, index, 'File');
        } else {
            document.getElementById('msg_fotosParticulares').innerHTML = '<div class="alert alert-warning text-center" role="alert">Verificar el archivo cargado.<br>Posibles errores: <br> - Archivo muy pesado (Máximo 8 megas). <br> -Extensión no aceptada (Extensiones aceptadas: jpeg, png, jpg, PNG).</div>';
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });
        }
    }
};

const validateImage = (image) => {//FUNCION QUE VALIDA LA EXTENSION DEL ARCHIVO QUE SE SUBIO 
    const size = image.files[0].size,
        allowedExtensions = /(.jpg|.jpeg|.png|.PNG)$/i;
    if (!allowedExtensions.exec(image.value)) {
        return false;
    }
    if(size > 8 * 1024 * 1024) { // 8 MB en bytes
        return false;
    }
    return true;
}

function deleteImageFotoV(index) {//ELIMINA LA IMAGEN DE LA TABLA VEHICULOS
    const div = document.getElementById('imageContentV_row' + index);
    document.getElementById('fileFotoV_row' + index).value = '';
    div.innerHTML = '';
}

/*  FUNCIONALIDADES DE LA TABLA DE PERSONAS */

function createElementFotoInvolucrado(src, index, type, view) {//PARA CREAR UNA IMAGEN EN LA ROW SELECCIONADA EN LA TABLA DE PERSONAS
    const div = document.getElementById('imageContentP_row' + index);
    if (view === undefined) {
        div.innerHTML = `<div class="d-flex justify-content-end">
                            <span onclick="deleteImageFotoP(${index})" class="deleteFile">X</span>
                        </div><br>
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

function deleteImageFotoP(index) {//ELIMINA LA IMAGEN DE LA TABLA PERSONAS
    const div = document.getElementById('imageContentP_row' + index);
    document.getElementById('fileFotoP_row' + index).value = '';
    div.innerHTML = '';
}

//FUNCIONES ESPECIALES DE ELIMINACION Y REACOMODO DE LAS TABLAS VEHICULOS Y PERSONAS
const deleteRow = (obj, tableId) => {
    if (confirm('¿Desea eliminar este elemento?')) {
        const row = obj.parentElement.parentElement;
        aux=row.rowIndex;
        document.getElementById(tableId.id).deleteRow(row.rowIndex);
        if(tableId.id=='VehiculoTable'){
            table = document.getElementById('VehiculoTable');
            for(let i=aux;i<table.rows.length;i++){
                let contenedorImg =table.rows[i].cells[7].children[1];
                contenedorImg.setAttribute('id', 'imageContentV_row'+i);
                console.log(contenedorImg)
                if(contenedorImg.childNodes.length>0){                 
                    contenedorImg.childNodes[0].childNodes[1].setAttribute('onclick', "deleteImageFotoV("+i+")");
                    contenedorImg.childNodes[3].setAttribute('id', 'imagesV_row_'+i);
                    contenedorImg.childNodes[3].setAttribute('data-target', '#ModalCenterVehiculo'+i);
                    contenedorImg.childNodes[5].setAttribute('class', i+' File');
                    contenedorImg.childNodes[7].setAttribute('id', 'ModalCenterVehiculo'+i);
                }
                let contenedorInput =table.rows[i].cells[7].children[0];
                contenedorInput.setAttribute('id', 'uploadFileFotos'+i);
                contenedorInput.childNodes[1].childNodes[1].setAttribute('id', 'fileFotoV_row'+i);
                contenedorInput.childNodes[1].childNodes[1].setAttribute('name', 'FotoVehiculo_row'+i);
                contenedorInput.childNodes[1].childNodes[3].setAttribute('for', 'fileFotoV_row'+i);
            }
        }
        if(tableId.id=='PersonaTable'){
            table = document.getElementById('PersonaTable');
            for(let i=aux;i<table.rows.length;i++){
                let contenedorImg =table.rows[i].cells[4].children[1];
                contenedorImg.setAttribute('id', 'imageContentP_row'+i);
                if(contenedorImg.childNodes.length>0){
                    contenedorImg.childNodes[0].childNodes[1].setAttribute('onclick', "deleteImageFotoP("+i+")"); 
                    contenedorImg.childNodes[3].setAttribute('id', 'imagesP_row_'+i);
                    contenedorImg.childNodes[3].setAttribute('data-target', '#ModalCenterInvolucrado'+i);
                    contenedorImg.childNodes[5].setAttribute('class', i+' File');
                    contenedorImg.childNodes[7].setAttribute('id', 'ModalCenterInvolucrado'+i);
                }
                let contenedorInput =table.rows[i].cells[4].children[0];
                contenedorInput.setAttribute('id', 'uploadFileFotoP'+i);
                contenedorInput.childNodes[1].childNodes[1].setAttribute('id', 'fileFotoP_row'+i);
                contenedorInput.childNodes[1].childNodes[1].setAttribute('name', 'FotoInvolucrado_row'+i);
                contenedorInput.childNodes[1].childNodes[3].setAttribute('for', 'fileFotoP_row'+i);
            }
        }
    }
}

const getFechaActual = () => {
    const now = new Date();

    // Obtener la fecha en formato yyyy-mm-dd
    const fecha = now.toISOString().split('T')[0];
    // Obtener la hora en formato de 24 horas
    const options = { hour: '2-digit', minute: '2-digit', hour12: false };
    const hora = new Intl.DateTimeFormat('es-MX', options).format(now);

    // Unir fecha y hora
    const fechaHora = `${fecha} ${hora}`;
    return fechaHora;
}

const getFecha = () => { //Funcion para Obtener la fecha actual en el formato para el html
    let now = new Date();
    let fecha = now.toISOString().split('T')[0];
    return fecha;
} 
const getHora = () => { //Funcion para Obtener la hora actual en el formato para el html
    let now = new Date();
    let options = { hour: '2-digit', minute: '2-digit', hour12: false };
    let hora = new Intl.DateTimeFormat('es-MX', options).format(now);
    return hora;
} 