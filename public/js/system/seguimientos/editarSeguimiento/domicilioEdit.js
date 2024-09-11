const MostrarTabDomicilio=async()=>{//FUNCION QUE OCULTA O MUESTRA LA TAB DE DOMICILIO
    NumeroPersonas=document.getElementById('contarRes').rows.length;
    NumeroVehiculos=document.getElementById('contarVeh').rows.length;
    if(NumeroPersonas==0 && NumeroVehiculos==0){//EN CASO DE NINGUN DATO CARGADO
        document.getElementById('li-Domicilio').classList.add('mi_hide');
        document.getElementById('Domicilio0').classList.add('mi_hide');
    }else{
        document.getElementById('li-Domicilio').classList.remove('mi_hide');
        document.getElementById('Domicilio0').classList.remove('mi_hide');
    }
    RecargaSelect() 
    RecargaDatosDomicilios();
    document.getElementById('Estado').setAttribute('disabled', '');
    document.getElementById('Estado').value='PUEBLA';
    document.getElementById('Municipio').value='PUEBLA';
    document.getElementById('Municipio').setAttribute('disabled', '');
    
}
async function  RecargaSelect() {//REFRESCA EL SELECTOR DEL DOMICILIO CON LOS DATOS DE PERSONAS Y VEHICULOS GUARDADOS EN EL SEGUIMIENTO  
    if(document.getElementsByName('tipo_dato')[0].checked){
        // Obtener referencia al elemento select
        var select = document.getElementById("PersonaSelect");
        while (select.options.length > 0) {//ACTUALIZACION DE SELECT POR SI HAY MODIFICACION EN LAS TABLAS
            select.remove(0);
        }
        let Personas = PersonasSelect;
        //console.log(PersonasSelect)
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
    if(document.getElementsByName('tipo_dato')[1].checked){
        // Obtener referencia al elemento select
        var select = document.getElementById("VehiculoSelect");
        while (select.options.length > 0) {//ACTUALIZACION DE SELECT POR SI HAY MODIFICACION EN LAS TABLAS
            select.remove(0);
        }
        let Vehiculos = VehiculosSelect;
        //console.log(VehiculosSelect)
        // Datos para las opciones
        var option = document.createElement("option");
        option.text = "SELECCIONE VEHICULO";
        option.value='SD';
        select.add(option);
        // Generar las opciones del select
        for (var i = 0; i < Vehiculos.length; i++) {
            option = document.createElement("option");
            option.text = Vehiculos[i]['Placas'] +" "+Vehiculos[i]['Marca']+" "+Vehiculos[i]['Color'];
            option.value = Vehiculos[i]['Id_Vehiculo'];
            select.add(option);
        }
    }
    
}
document.getElementById('puebla_domicilio').addEventListener('change',()=>{
    showUbicacionForanea();
});
document.getElementById('foraneo_domicilio').addEventListener('change',()=>{
    showUbicacionForanea();
});
const showUbicacionForanea = () =>{//Funcion de habilitado para la edicion del estado
    radio = document.getElementsByName('ubicacion_puebla_domicilio')
    
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
const changeTipo=()=>{//FUNCION QUE CAMBIA EL TIPO DE DATO AL QUE SE LE ASIGNARA EL DOMICILIO
    let radioTipo = document.getElementsByName('tipo_dato');
    RecargaSelect()
    if(radioTipo[0].checked){//PERSONA
        document.getElementById('PersonaSelect_error').innerHTML='';
        document.getElementById('Persona_Select').classList.remove('mi_hide');
        document.getElementById('Vehiculo_Select').classList.add('mi_hide');
    }else if(radioTipo[1].checked){//VEHICULO
        document.getElementById('Persona_Select').classList.add('mi_hide');
        document.getElementById('Vehiculo_Select').classList.remove('mi_hide');
        document.getElementById('VehiculoSelect_error').innerHTML=''
    }
}
/*----------------------FUNCIONES DE LA TABLA DE DOMICILIOS----------------- */
let selectedRowDomicilios = null//VARIABLE QUE DEFINE SI SE TRATA DE UNA ACTUALIZACION DE UN REGISTRO YA CREADO O DE UNA NUEVA INSERCION

const onFormdomiciliosubmit=async()=>{
    if(await ValidatableDomicilio()){
        if (selectedRowDomicilios === null){
            InsertDomicilio();//INSERTA NUEVA FILA EN LA TABLA DE DOMICILIOS
            ResetFormDomicilio();//LIMPIA LA VISTA 
        }else{
            UpdateRowDomicilio();//ACTUALIZA LA FILA SELECCIONADA EN LA TABLA DE DOMICILIOS
            ResetFormDomicilio();//LIMPIA LA VISTA
        }
    }
}

const ValidatableDomicilio = async() => {//FUNCION QUE VALIDA LAS ENTRADAS DEL FORMULARIO DE DOMICILIO PARA QUE SE INGRESE EN LA TABLA
    let FV = new FormValidator()
    let myFormData = new FormData(document.getElementById('datos_domicilios'))
    let band = []
    let respuesta=true;
    let i = 0;
    if(document.getElementsByName('tipo_dato')[0].checked && document.getElementById('PersonaSelect').value=='SD'){
        respuesta=false;
        document.getElementById('PersonaSelect_error').innerHTML='Seleccione una persona'
    }else{
        document.getElementById('PersonaSelect_error').innerHTML=''
    }
    if(document.getElementsByName('tipo_dato')[1].checked && document.getElementById('VehiculoSelect').value=='SD'){
        respuesta=false;
        document.getElementById('VehiculoSelect_error').innerHTML='Seleccione un vehiculo'
    }else{
        document.getElementById('VehiculoSelect_error').innerHTML=''
    }
    if(document.getElementById('Status_Domicilio').value=='SD'){
        respuesta=false;
        document.getElementById('Status_Domicilio_error').innerHTML='Seleccione un status'
    }else{
        document.getElementById('Status_Domicilio_error').innerHTML=''
    }
    band[i++]=document.getElementById('Colonia_principales_error').innerHTML= FV.validate(myFormData.get('Colonia'), 'required')
    band[i++]=document.getElementById('Calle_principales_error').innerHTML= FV.validate(myFormData.get('Calle'), 'required')
    band[i++]=document.getElementById('cordY_principales_error').innerHTML= FV.validate(myFormData.get('cordY'), 'required | numeric')
    band[i++]=document.getElementById('cordX_principales_error').innerHTML= FV.validate(myFormData.get('cordX'), 'required | numeric')
    let regexY = /^\d*\.\d*$/;//coordenada positiva osea la Y
    let regexX = /^-\d*\.\d+$/ //coordenada negativa osea la X
    let regexF = /^-?\d*\.\d+$/ //para foraneo 
    band.forEach(element => {//recorre todas la banderas si todas son vacias procede a guardar los datos ya que no existe ninguna restriccion 
        respuesta &= (element == '') ? true : false
    })
    radio = document.getElementsByName('ubicacion_puebla_domicilio')
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
const createObjectColonia = (colonia) => {//FUNCION QUE CONVIERTE LA CADENA INGRESADA EN EL CAMPO COLONIA A DOS ELEMENTOS PARA LA COMPARACION EN EL CATALOGO
    separado = colonia.split(' ');
    objetoColonia = {
        Tipo: '',
        Colonia: ''
    }
    if(separado){
        objetoColonia.Tipo = separado[0];
        for(let i = 1; i<separado.length; i++){
            objetoColonia.Colonia += separado[i]+' ';
        }
    }
    objetoColonia.Colonia = objetoColonia.Colonia.trim();
    return objetoColonia
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
const InsertDomicilio= async()=>{//FUNCION QUE INSERTA LOS DATOS EN LA TABLA DE DOMICILIO
    let table = document.getElementById('DomiciliosTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    if(document.getElementsByName('tipo_dato')[0].checked){
        newRow.insertCell(0).innerHTML =  document.getElementById("PersonaSelect").options[document.getElementById("PersonaSelect").selectedIndex].text;
    }else{
        newRow.insertCell(0).innerHTML =  document.getElementById("VehiculoSelect").options[document.getElementById("VehiculoSelect").selectedIndex].text;
    }
    newRow.insertCell(1).innerHTML =document.getElementById('Id_Domicilio').value;
    if(document.getElementsByName('tipo_dato')[0].checked){
        newRow.insertCell(2).innerHTML =document.getElementById('PersonaSelect').value;
        newRow.insertCell(3).innerHTML ='PERSONA';
    }else{
        newRow.insertCell(2).innerHTML =document.getElementById('VehiculoSelect').value;
        newRow.insertCell(3).innerHTML ='VEHICULO';
    }
    newRow.insertCell(4).innerHTML =document.getElementById('Status_Domicilio').value;
    newRow.insertCell(5).innerHTML =document.getElementById('Colonia').value.toUpperCase();
    newRow.insertCell(6).innerHTML =document.getElementById('Calle').value.toUpperCase();
    newRow.insertCell(7).innerHTML =(document.getElementById('Calle2').value!='')?document.getElementById('Calle2').value.toUpperCase():'SD';
    newRow.insertCell(8).innerHTML =(document.getElementById('no_Ext').value!='')?document.getElementById('no_Ext').value.toUpperCase():'SD';
    newRow.insertCell(9).innerHTML =(document.getElementById('no_Int').value!='')?document.getElementById('no_Int').value.toUpperCase():'SD';
    newRow.insertCell(10).innerHTML =(document.getElementById('CP').value!='')?document.getElementById('CP').value.toUpperCase():'SD';
    newRow.insertCell(11).innerHTML =document.getElementById('cordY').value
    newRow.insertCell(12).innerHTML =document.getElementById('cordX').value
    radio = document.getElementsByName('ubicacion_puebla_domicilio')
    
    if(radio[0].checked){
        newRow.insertCell(13).innerHTML='PUEBLA';
        newRow.insertCell(14).innerHTML='PUEBLA';
        newRow.insertCell(15).innerHTML='NO';
    }else if(radio[1].checked){
        newRow.insertCell(13).innerHTML=document.getElementById('Estado').value
        newRow.insertCell(14).innerHTML=(document.getElementById('Municipio').value.trim()=='')?'SD':document.getElementById('Municipio').value.toUpperCase();
        newRow.insertCell(15).innerHTML='SI';
    }
    newRow.insertCell(16).innerHTML =(document.getElementById('Observacion_Ubicacion_descripcion').value!='')?document.getElementById('Observacion_Ubicacion_descripcion').value.toUpperCase():'SD'
    newRow.insertCell(17).innerHTML =document.getElementById('captura_dato_domicilio').value.toUpperCase();
    newRow.insertCell(18).innerHTML =`<button type="button" class="btn btn-add" onclick="editDomicilio(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc" value="-" onclick="deleteRowDomicilio(this,DomiciliosTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
    newRow.cells[1].style.display = "none";
    newRow.cells[2].style.display = "none";
}
const ResetFormDomicilio = async ()=>{//FUNCION QUE LIMPIA LA VISTA
    radio = document.getElementsByName('ubicacion_puebla_domicilio')
    radio[0].checked=true;
    showUbicacionForanea();
    document.getElementById('Id_Domicilio').value='SD';
    document.getElementById('PersonaSelect').value='SD';
    document.getElementById('VehiculoSelect').value='SD';
    document.getElementById('Status_Domicilio').value='SD';
    document.getElementById('Colonia').value='';
    document.getElementById('Calle').value='';
    document.getElementById('Calle2').value='';
    document.getElementById('no_Ext').value='';
    document.getElementById('no_Int').value='';
    document.getElementById('CP').value='';
    document.getElementById('cordY').value='';
    document.getElementById('cordX').value='';
    document.getElementById('Observacion_Ubicacion_descripcion').value='';
    map.flyTo({
        center: [-98.20868494860592, 19.040296987811555],
        zoom: 11,
        essential: true
        });
        marker.setLngLat([-98.20868494860592, 19.040296987811555])
}
const editDomicilio = async(obj) => {//FUNCION QUE EDITA LA TABLA DE DOMICILIOS TOMANDO LOS DATOS DE LA ROW SELECCIONADA Y ACOMODANDO LOS DATOS EN LA VISTA PARA SU EDICION
    document.getElementById('alertaEditDomicilio').style.display = 'block';
    selectedRowDomicilios = obj.parentElement.parentElement;
    document.getElementById('Id_Domicilio').value=selectedRowDomicilios.cells[1].innerHTML;
    if(selectedRowDomicilios.cells[3].innerHTML=='PERSONA'){
        document.getElementsByName('tipo_dato')[0].checked=true;
        document.getElementsByName('tipo_dato')[1].checked=false;
        document.getElementById('PersonaSelect').value=selectedRowDomicilios.cells[2].innerHTML;
        document.getElementById('PersonaSelect_error').innerHTML='';
        document.getElementById('Persona_Select').classList.remove('mi_hide');
        document.getElementById('Vehiculo_Select').classList.add('mi_hide');
    }else{
        document.getElementsByName('tipo_dato')[1].checked=true;
        document.getElementsByName('tipo_dato')[0].checked=false;
        document.getElementById('VehiculoSelect').value=selectedRowDomicilios.cells[2].innerHTML;
        document.getElementById('Persona_Select').classList.add('mi_hide');
        document.getElementById('Vehiculo_Select').classList.remove('mi_hide');
        document.getElementById('VehiculoSelect_error').innerHTML=''
    }
    document.getElementById('cordY').value=selectedRowDomicilios.cells[11].innerHTML
    document.getElementById('cordX').value=selectedRowDomicilios.cells[12].innerHTML
    await getColoniasCalles();

    document.getElementById('Status_Domicilio').value=selectedRowDomicilios.cells[4].innerHTML
    document.getElementById('Colonia').value=selectedRowDomicilios.cells[5].innerHTML
    document.getElementById('Calle').value=selectedRowDomicilios.cells[6].innerHTML
    document.getElementById('Calle2').value=(selectedRowDomicilios.cells[7].innerHTML!='SD')?selectedRowDomicilios.cells[7].innerHTML:'';
    document.getElementById('no_Ext').value=(selectedRowDomicilios.cells[8].innerHTML!='SD')?selectedRowDomicilios.cells[8].innerHTML:'';
    document.getElementById('no_Int').value=(selectedRowDomicilios.cells[9].innerHTML!='SD')?selectedRowDomicilios.cells[9].innerHTML:'';
    document.getElementById('CP').value=(selectedRowDomicilios.cells[10].innerHTML!='SD')?selectedRowDomicilios.cells[10].innerHTML:'';
    
    radio = document.getElementsByName('ubicacion_puebla_domicilio')
    
    if(selectedRowDomicilios.cells[15].innerHTML=='NO'){
        radio[0].checked=true;
        radio[1].checked=false;
        showUbicacionForanea();
    }else{
        radio[1].checked=true;
        radio[0].checked=false;
        showUbicacionForanea();
        document.getElementById('Estado').value=selectedRowDomicilios.cells[13].innerHTML;
        document.getElementById('Municipio').value=(selectedRowDomicilios.cells[14].innerHTML!='SD')?selectedRowDomicilios.cells[14].innerHTML:'';

    }
    document.getElementById('Observacion_Ubicacion_descripcion').value=(selectedRowDomicilios.cells[16].innerHTML!='SD')?selectedRowDomicilios.cells[16].innerHTML:'';

    window.scroll({
        top: 0,
        left: 100,
        behavior: 'smooth'
    });
}
const UpdateRowDomicilio=()=>{
    if(document.getElementsByName('tipo_dato')[0].checked){
        selectedRowDomicilios.cells[0].innerHTML =  document.getElementById("PersonaSelect").options[document.getElementById("PersonaSelect").selectedIndex].text;
    }else{
        selectedRowDomicilios.cells[0].innerHTML =  document.getElementById("VehiculoSelect").options[document.getElementById("VehiculoSelect").selectedIndex].text;
    }
    selectedRowDomicilios.cells[1].innerHTML =document.getElementById('Id_Domicilio').value;
    if(document.getElementsByName('tipo_dato')[0].checked){
        selectedRowDomicilios.cells[2].innerHTML =document.getElementById('PersonaSelect').value;
        selectedRowDomicilios.cells[3].innerHTML ='PERSONA';
    }else{
        selectedRowDomicilios.cells[2].innerHTML =document.getElementById('VehiculoSelect').value;
        selectedRowDomicilios.cells[3].innerHTML ='VEHICULO';
    }
    selectedRowDomicilios.cells[4].innerHTML =document.getElementById('Status_Domicilio').value;
    selectedRowDomicilios.cells[5].innerHTML =document.getElementById('Colonia').value.toUpperCase();
    selectedRowDomicilios.cells[6].innerHTML =document.getElementById('Calle').value.toUpperCase();
    selectedRowDomicilios.cells[7].innerHTML =(document.getElementById('Calle2').value!='')?document.getElementById('Calle2').value.toUpperCase():'SD';
    selectedRowDomicilios.cells[8].innerHTML =(document.getElementById('no_Ext').value!='')?document.getElementById('no_Ext').value.toUpperCase():'SD';
    selectedRowDomicilios.cells[9].innerHTML =(document.getElementById('no_Int').value!='')?document.getElementById('no_Int').value.toUpperCase():'SD';
    selectedRowDomicilios.cells[10].innerHTML =(document.getElementById('CP').value!='')?document.getElementById('CP').value.toUpperCase():'SD';
    selectedRowDomicilios.cells[11].innerHTML =document.getElementById('cordY').value
    selectedRowDomicilios.cells[12].innerHTML =document.getElementById('cordX').value

    radio = document.getElementsByName('ubicacion_puebla_domicilio')
    
    if(radio[0].checked){
         selectedRowDomicilios.cells[13].innerHTML='PUEBLA';
         selectedRowDomicilios.cells[14].innerHTML='PUEBLA';
         selectedRowDomicilios.cells[15].innerHTML='NO';
    }else if(radio[1].checked){
         selectedRowDomicilios.cells[13].innerHTML=document.getElementById('Estado').value
         selectedRowDomicilios.cells[14].innerHTML=(document.getElementById('Municipio').value.trim()=='')?'SD':document.getElementById('Municipio').value.toUpperCase();
         selectedRowDomicilios.cells[15].innerHTML='SI';
    }

    selectedRowDomicilios.cells[16].innerHTML =(document.getElementById('Observacion_Ubicacion_descripcion').value!='')?document.getElementById('Observacion_Ubicacion_descripcion').value.toUpperCase():'SD';
    document.getElementById('alertaEditDomicilio').style.display = 'none';
    selectedRowDomicilios= null;
}
const deleteRowDomicilio = async(obj, tableId) => {//FUNCION PARA ELIMINAR UNA FILA DE LA TABLA DE DOMICILIO 
    if (confirm('¿Desea eliminar este elemento?')) {
        let row = obj.parentElement.parentElement;
        aux=row.rowIndex;
        if(row.cells[1].innerHTML!='SD'){
            await DesasociaDomicilio(row.cells[1].innerHTML);
        }
        document.getElementById(tableId.id).deleteRow(row.rowIndex);
        RecargaDatosDomicilios();
    }
}
const DesasociaDomicilio= async(Id_Domicilio)=>{//FUNCION QUE ELIMINA LOS DATOS DE LA TABLA DE DOMICILIOS
    try {
        myFormData.append('Id_Domicilio',Id_Domicilio)
        const response = await fetch(base_url_js + 'Seguimientos/DesasociaDomicilio', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
/*--------------------------FUNCIONES PARA EL ALMACENAMIENTO DE LOS DATOS CONTENIDOS EN EL FRAME  --------------- */
var msg_DomicilioError = document.getElementById('msg_principales_domicilios');
var datosDomicilio = document.getElementById('datos_domicilios')
document.getElementById('btn_domicilios').addEventListener('click', async function(e) {
    let myFormDataDomicilios = new FormData(datosDomicilio)//RECUERDA SIEMPRE ENVIAR LOS DATOS DEL FRAME Y AUN MAS IMPORTANTE POR LAS IMAGENES LAS DETECTE EN EL POST
    var Domicilios =  await readTableDomicilios();//LEEMOS EL CONTENIDO DE LA TABLA DE Domicilios 
    myFormDataDomicilios.append('Domicilios_table', JSON.stringify(Domicilios));
    myFormDataDomicilios.append('id_seguimiento',document.getElementById('id_seguimiento_principales').value)
    let button = document.getElementById('btn_domicilios')
    button.innerHTML = `
        Guardando
        <div class="spinner-grow spinner-grow-sm" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    `;
    button.classList.add('disabled-link');
    $('#ModalCenterPrincipalDomicilio').modal('show');
    fetch(base_url_js + 'Seguimientos/UpdateDomiciliosFetch', {//realiza el fetch para actualizar los datos
        method: 'POST',
        body: myFormDataDomicilios
    })

    .then(res => res.json())

    .then(data => {//obtine  respuesta del modelo
        button.innerHTML = `Guardar`;
        button.classList.remove('disabled-link');//se vuelve activar la funcion del boton 
        $('#ModalCenterPrincipalDomicilio').modal('hide');//se quita la imagen 
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
            msg_DomicilioError.innerHTML = messageError
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });
        } else {//si todo salio bien
            alertaDomicilio()//Si todo salio bien actualiza los datos arroja un mensaje satisfactorio
        }
    })
})
const alertaDomicilio = async()=>{//FUNCION QUE AVISA QUE LOS DATOS HAN SIDO ACTUALIZADOS CORRECTAMENTE
    await RecargaDatosDomicilios();
    msg_DomicilioError.innerHTML = `<div class="alert alert-success text-center" role="success">Datos de Domicilios Actualizados correctamente.
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
/*-----------------------------------FUNCIONES PARA LEER LOS DATOS DE LAS TABLAS-----------------------------------*/
const readTableDomicilios = () => {//lee los datos de la tabla delitos y genera una estructura deacuerdo a los datos contenido es la tabla
    let table = document.getElementById('DomiciliosTable');
    let objetos = [];
    for (let i = 1; i < table.rows.length; i++) {
        objetos.push({
            ['row']: {
                Id_Domicilio: table.rows[i].cells[1].innerHTML,
                Id_Dato: table.rows[i].cells[2].innerHTML,
                Tipo_Entidad: table.rows[i].cells[3].innerHTML,
                Estatus: table.rows[i].cells[4].innerHTML,
                Colonia: table.rows[i].cells[5].innerHTML,
                Calle: table.rows[i].cells[6].innerHTML,
                Calle2: table.rows[i].cells[7].innerHTML,
                NumExt: table.rows[i].cells[8].innerHTML,
                NumInt: table.rows[i].cells[9].innerHTML,
                CP: table.rows[i].cells[10].innerHTML,
                CoordY: table.rows[i].cells[11].innerHTML,
                CoordX: table.rows[i].cells[12].innerHTML,
                Estado: table.rows[i].cells[13].innerHTML,
                Municipio: table.rows[i].cells[14].innerHTML,
                Foraneo: table.rows[i].cells[15].innerHTML,
                Observaciones_Ubicacion: table.rows[i].cells[16].innerHTML,
                Capturo: table.rows[i].cells[17].innerHTML
            }
        });
    }
    return objetos;
}