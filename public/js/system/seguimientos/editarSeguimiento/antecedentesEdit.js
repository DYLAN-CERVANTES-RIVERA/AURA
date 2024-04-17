const MostrarTabAntecedentes=async()=>{//FUNCION QUE OCULTA O MUESTRA LA TAB DE ANTECEDENTES
    NumeroPersonas=document.getElementById('contarRes').rows.length;
    NumeroVehiculos=document.getElementById('contarVeh').rows.length;
    if(NumeroPersonas==0 && NumeroVehiculos==0){//EN CASO DE NINGUN DATO CARGADO
        document.getElementById('li-Antecedente').classList.add('mi_hide');
        document.getElementById('Antecedente0').classList.add('mi_hide');
    }else{
        document.getElementById('li-Antecedente').classList.remove('mi_hide');
        document.getElementById('Antecedente0').classList.remove('mi_hide');
    }
    RecargaSelectAntecedente() 
    await RecargaDatosAntecedentes();
}
async function  RecargaSelectAntecedente() {//REFRESCA EL SELECTOR DEL ANTECEDENTE CON LOS DATOS DE PERSONAS Y VEHICULOS GUARDADOS EN EL SEGUIMIENTO  
    if(document.getElementsByName('tipo_dato_antecendente')[0].checked){
        // Obtener referencia al elemento select
        var select = document.getElementById("PersonaSelectAntecedente");
        while (select.options.length > 0) {//ACTUALIZACION DE SELECT POR SI HAY MODIFICACION EN LAS TABLAS
            select.remove(0);
        }
        let Personas = await getPersonas(Seguimiento);
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
    if(document.getElementsByName('tipo_dato_antecendente')[1].checked){
        // Obtener referencia al elemento select
        var select = document.getElementById("VehiculoSelectAntecedente");
        while (select.options.length > 0) {//ACTUALIZACION DE SELECT POR SI HAY MODIFICACION EN LAS TABLAS
            select.remove(0);
        }
        let Vehiculos = await getVehiculos(Seguimiento);
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
const changeTipoAntecedente=()=>{//FUNCION QUE CAMBIA EL TIPO DE DATO AL QUE SE LE ASIGNARA EL ANTECEDENTE
    let radioTipo = document.getElementsByName('tipo_dato_antecendente');
    RecargaSelectAntecedente()
    if(radioTipo[0].checked){//PERSONA
        document.getElementById('PersonaSelect_Antecedente_error').innerHTML='';
        document.getElementById('Persona_Select_Antecedente').classList.remove('mi_hide');
        document.getElementById('Vehiculo_Select_Antecedente').classList.add('mi_hide');
    }else if(radioTipo[1].checked){//VEHICULO
        document.getElementById('Persona_Select_Antecedente').classList.add('mi_hide');
        document.getElementById('Vehiculo_Select_Antecedente').classList.remove('mi_hide');
        document.getElementById('VehiculoSelect_Antecedente_error').innerHTML=''
    }
}
/*----------------------FUNCIONES DE LA TABLA DE ANTECEDENTES----------------- */
let selectedRowAntecedentes = null//VARIABLE QUE DEFINE SI SE TRATA DE UNA ACTUALIZACION DE UN REGISTRO YA CREADO O DE UNA NUEVA INSERCION

const onFormAntecedentesubmit=async()=>{
    if(await ValidatableAntecedentes()){
        if (selectedRowAntecedentes === null){
            InsertAntecedentes();//INSERTA NUEVA FILA EN LA TABLA DE ANTECEDENTES
            ResetFormAntecedentes();//LIMPIA LA VISTA 
        }else{
            UpdateRowAntecedentes();//ACTUALIZA LA FILA SELECCIONADA EN LA TABLA DE ANTECEDENTES
            ResetFormAntecedentes();//LIMPIA LA VISTA
        }
    }
}
const ValidatableAntecedentes = async() => {//FUNCION QUE VALIDA LAS ENTRADAS DEL FORMULARIO DE ANTECEDENTES PARA QUE SE INGRESE EN LA TABLA
    let respuesta=true;
    if(document.getElementsByName('tipo_dato_antecendente')[0].checked && document.getElementById('PersonaSelectAntecedente').value=='SD'){
        respuesta=false;
        document.getElementById('PersonaSelect_Antecedente_error').innerHTML='Seleccione una persona'
    }else{
        document.getElementById('PersonaSelect_Antecedente_error').innerHTML=''
    }
    if(document.getElementsByName('tipo_dato_antecendente')[1].checked && document.getElementById('VehiculoSelectAntecedente').value=='SD'){
        respuesta=false;
        document.getElementById('VehiculoSelect_Antecedente_error').innerHTML='Seleccione un vehiculo'
    }else{
        document.getElementById('VehiculoSelect_Antecedente_error').innerHTML=''
    }

    if(document.getElementById('Antecedente_descripcion').value.trim()==''){
        respuesta=false;
        document.getElementById('Antecedente_error').innerHTML='Ingrese el antecedente'
    }else{
        document.getElementById('Antecedente_error').innerHTML=''
    }
    return respuesta;
}

const InsertAntecedentes= async()=>{//FUNCION QUE INSERTA LOS DATOS EN LA TABLA DE ANTECEDENTE
    let table = document.getElementById('AntecendentesTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    if(document.getElementsByName('tipo_dato_antecendente')[0].checked){
        newRow.insertCell(0).innerHTML =  document.getElementById("PersonaSelectAntecedente").options[document.getElementById("PersonaSelectAntecedente").selectedIndex].text;
    }else{
        newRow.insertCell(0).innerHTML =  document.getElementById("VehiculoSelectAntecedente").options[document.getElementById("VehiculoSelectAntecedente").selectedIndex].text;
    }
    newRow.insertCell(1).innerHTML =document.getElementById('Id_Antecedente').value;
    if(document.getElementsByName('tipo_dato_antecendente')[0].checked){
        newRow.insertCell(2).innerHTML =document.getElementById('PersonaSelectAntecedente').value;
        newRow.insertCell(3).innerHTML ='PERSONA';
    }else{
        newRow.insertCell(2).innerHTML =document.getElementById('VehiculoSelectAntecedente').value;
        newRow.insertCell(3).innerHTML ='VEHICULO';
    }
    newRow.insertCell(4).innerHTML =document.getElementById('Antecedente_descripcion').value.toUpperCase();
    newRow.insertCell(5).innerHTML =(document.getElementById('fecha').value!='')?document.getElementById('fecha').value:'SD';
    newRow.insertCell(6).innerHTML =document.getElementById('captura_dato_antecedentes').value.toUpperCase();
    newRow.insertCell(7).innerHTML =`<button type="button" class="btn btn-add" onclick="editAntecedente(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc" value="-" onclick="deleteRowAntecedente(this,AntecendentesTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
    newRow.cells[1].style.display = "none";
    newRow.cells[2].style.display = "none";
}
const ResetFormAntecedentes = async ()=>{//FUNCION QUE LIMPIA LA VISTA
    document.getElementById('Id_Antecedente').value='SD';
    document.getElementById('PersonaSelectAntecedente').value='SD';
    document.getElementById('VehiculoSelectAntecedente').value='SD';
    document.getElementById('Antecedente_descripcion').value='';
    document.getElementById('fecha').value='';
}
const editAntecedente = (obj) => {//FUNCION QUE EDITA LA TABLA DE ANTECEDENTES TOMANDO LOS DATOS DE LA ROW SELECCIONADA Y ACOMODANDO LOS DATOS EN LA VISTA PARA SU EDICION
    document.getElementById('alertaEditAntecedentes').style.display = 'block';
    selectedRowAntecedentes = obj.parentElement.parentElement;
    document.getElementById('Id_Antecedente').value=selectedRowAntecedentes.cells[1].innerHTML;
    if(selectedRowAntecedentes.cells[3].innerHTML=='PERSONA'){
        document.getElementsByName('tipo_dato_antecendente')[0].checked=true;
        document.getElementsByName('tipo_dato_antecendente')[1].checked=false;
        document.getElementById('PersonaSelectAntecedente').value=selectedRowAntecedentes.cells[2].innerHTML;
        document.getElementById('Persona_Select_Antecedente').classList.remove('mi_hide');
        document.getElementById('Vehiculo_Select_Antecedente').classList.add('mi_hide');
        document.getElementById('PersonaSelect_Antecedente_error').innerHTML='';
    }else{
        document.getElementsByName('tipo_dato_antecendente')[1].checked=true;
        document.getElementsByName('tipo_dato_antecendente')[0].checked=false;
        document.getElementById('VehiculoSelectAntecedente').value=selectedRowAntecedentes.cells[2].innerHTML;
        document.getElementById('Persona_Select_Antecedente').classList.add('mi_hide');
        document.getElementById('Vehiculo_Select_Antecedente').classList.remove('mi_hide');
        document.getElementById('VehiculoSelect_Antecedente_error').innerHTML='';
    }
    document.getElementById('Antecedente_descripcion').value=selectedRowAntecedentes.cells[4].innerHTML
    document.getElementById('fecha').value=(selectedRowAntecedentes.cells[5].innerHTML!='SD')?selectedRowAntecedentes.cells[5].innerHTML:'';

    window.scroll({
        top: 0,
        left: 100,
        behavior: 'smooth'
    });
}
const UpdateRowAntecedentes=()=>{
    if(document.getElementsByName('tipo_dato_antecendente')[0].checked){
        selectedRowAntecedentes.cells[0].innerHTML =  document.getElementById("PersonaSelectAntecedente").options[document.getElementById("PersonaSelectAntecedente").selectedIndex].text;
    }else{
        selectedRowAntecedentes.cells[0].innerHTML =  document.getElementById("VehiculoSelectAntecedente").options[document.getElementById("VehiculoSelectAntecedente").selectedIndex].text;
    }
    selectedRowAntecedentes.cells[1].innerHTML =document.getElementById('Id_Antecedente').value;
    if(document.getElementsByName('tipo_dato_antecendente')[0].checked){
        selectedRowAntecedentes.cells[2].innerHTML =document.getElementById('PersonaSelectAntecedente').value;
        selectedRowAntecedentes.cells[3].innerHTML ='PERSONA';
    }else{
        selectedRowAntecedentes.cells[2].innerHTML =document.getElementById('VehiculoSelectAntecedente').value;
        selectedRowAntecedentes.cells[3].innerHTML ='VEHICULO';
    }
    selectedRowAntecedentes.cells[4].innerHTML =document.getElementById('Antecedente_descripcion').value.toUpperCase();
    selectedRowAntecedentes.cells[5].innerHTML =(document.getElementById('fecha').value!='')?document.getElementById('fecha').value:'SD';
    document.getElementById('alertaEditAntecedentes').style.display = 'none';
    selectedRowAntecedentes= null;
}
const deleteRowAntecedente = async(obj, tableId) => {//FUNCION PARA ELIMINAR UNA FILA DE LA TABLA DE ANTECEDENTE 
    if (confirm('¿Desea eliminar este elemento?')) {
        let row = obj.parentElement.parentElement;
        aux=row.rowIndex;
        if(row.cells[1].innerHTML!='SD'){
            await DesasociaAntecedente(row.cells[1].innerHTML);
        }
        document.getElementById(tableId.id).deleteRow(row.rowIndex);
        RecargaDatosAntecedentes();
    }
}
const DesasociaAntecedente= async(Id_Antecedente)=>{//FUNCION QUE ELIMINA LOS DATOS DE LA TABLA DE ANTECEDENTES
    try {
        myFormData.append('Id_Antecedente',Id_Antecedente)
        const response = await fetch(base_url_js + 'Seguimientos/DesasociaAntecedente', {
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
var msg_AntecedenteError = document.getElementById('msg_principales_antecedentes');
var datosAntecedentes = document.getElementById('datos_antecedentes')
document.getElementById('btn_antecedentes').addEventListener('click', async function(e) {
    let myFormDataAntecedentes = new FormData(datosAntecedentes)//RECUERDA SIEMPRE ENVIAR LOS DATOS DEL FRAME Y AUN MAS IMPORTANTE POR LAS IMAGENES LAS DETECTE EN EL POST
    var Antecendentes =  await readTableAntecendentes();//LEEMOS EL CONTENIDO DE LA TABLA DE Domicilios 
    myFormDataAntecedentes.append('AntecendentesTable', JSON.stringify(Antecendentes));
    myFormDataAntecedentes.append('id_seguimiento',document.getElementById('id_seguimiento_principales').value)
    let button = document.getElementById('btn_antecedentes')
    button.innerHTML = `
        Guardando
        <div class="spinner-grow spinner-grow-sm" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    `;
    button.classList.add('disabled-link');
    $('#ModalCenterPrincipalAntecendentes').modal('show');
    fetch(base_url_js + 'Seguimientos/UpdateAntecendentesFetch', {//realiza el fetch para actualizar los datos
        method: 'POST',
        body: myFormDataAntecedentes
    })

    .then(res => res.json())

    .then(data => {//obtine  respuesta del modelo
        button.innerHTML = `Guardar`;
        button.classList.remove('disabled-link');//se vuelve activar la funcion del boton 
        $('#ModalCenterPrincipalAntecendentes').modal('hide');//se quita la imagen 
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
            msg_AntecedenteError.innerHTML = messageError
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });
        } else {//si todo salio bien
            alertaAntecendentes()//Si todo salio bien actualiza los datos arroja un mensaje satisfactorio
        }
    })
})
const alertaAntecendentes = async()=>{//FUNCION QUE AVISA QUE LOS DATOS HAN SIDO ACTUALIZADOS CORRECTAMENTE
    await RecargaDatosAntecedentes();
    msg_AntecedenteError.innerHTML = `<div class="alert alert-success text-center" role="success">Datos de Antecendentes Actualizados correctamente.
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
const readTableAntecendentes = () => {//lee los datos de la tabla delitos y genera una estructura deacuerdo a los datos contenido es la tabla
    let table = document.getElementById('AntecendentesTable');
    let objetos = [];
    for (let i = 1; i < table.rows.length; i++) {
        objetos.push({
            ['row']: {
                Id_Antecedente: table.rows[i].cells[1].innerHTML,
                Id_Dato: table.rows[i].cells[2].innerHTML,
                Tipo_Entidad: table.rows[i].cells[3].innerHTML,
                Descripcion_Antecedente: table.rows[i].cells[4].innerHTML,
                Fecha_Antecedente: table.rows[i].cells[5].innerHTML,
                Capturo: table.rows[i].cells[6].innerHTML
            }
        });
    }
    return objetos;
}