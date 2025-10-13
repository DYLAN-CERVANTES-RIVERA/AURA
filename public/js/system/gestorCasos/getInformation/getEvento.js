/*-------------ESTE ARCHIVO DE JAVASCRIPT ES PARA OBTENER LOS DATOS DEL EVENTO DE LA TAB PRINCIPAL PARA LA EDICION DEL EVENTO Y ADEMAS DE FUNCIONALIDADES DE LAS TABLAS-------E-----*/
const myFormData =  new FormData();
document.addEventListener('DOMContentLoaded', async () => {//Funcion para el llenado de elementos del evento
    select.addEventListener('change',showViolencia)
    select2.addEventListener('change',showArmas)
    
    let Estados = await getAllEstados();
    var selectE = document.getElementById("Estado");
    for (let i = 0; i < Estados.length; i++) {
        option = document.createElement("option");
        option.text = Estados[i]['Estado'];
        option.value = Estados[i]['Estado'];
        selectE.add(option);
    }

    evento = getEventotoSearch();
    data = await getEventoP(evento)
    vectores = await getAllVectores();;
    llenarEvento(data);
    InfoDetencion = await getInfoDetencion(data.Folio_infra);
    
    if(data.Ubo_Detencion==1 || InfoDetencion!= false){
        llenarUbicacion(InfoDetencion);
    }
    delito.addEventListener('keyup', esOtro)  
    delitos = await getDelitosP(evento)
    hechos = await getHechosP(evento)
    responsables = await getResponsablesP(evento)
    vehiculos = await getVehiculosP(evento)
    for (let i = 0; i < hechos.length; i++) {
        separadas = hechos[i].Fecha_Hora_Hecho.split(' ') 
        let formData = {
            descripcionHechos: hechos[i].Descripcion,
            fechaHechos: separadas[0],
            horaHechos: separadas[1]
        }
        insertNewRowHecho(formData); //Inserta todos los hechos del evento
    }
    for (let i = 0; i < delitos.length; i++) {
        let formData = {
            descripcionDelitos: delitos[i].Descripcion,
            tipoDelito:delitos[i].Giro
        }
        insertNewRowDelito(formData);//Inserta todos los delitos del evento
    }
    if(vehiculos.length>0){
        document.getElementsByName('Identificacion_VI')[0].checked = true
        document.getElementsByName('Identificacion_VI')[1].checked = false
        changeIdentificacionVI()
        for (let i = 0; i < vehiculos.length; i++){
            let formDataVeh = {
                Id_Vehiculo : vehiculos[i].Id_Vehiculo,
                Folio_infra: vehiculos[i].Folio_infra,
                tipoVehiculo: vehiculos[i].Tipo_Vehiculo,
                marcaVehiculo: vehiculos[i].Marca,
                submarcaVehiculo: vehiculos[i].Submarca,
                modeloVehiculo: vehiculos[i].Modelo,
                placasVehiculo: vehiculos[i].Placas_Vehiculo,
                colorVehiculo: vehiculos[i].Color,
                descripcionVehiculo: vehiculos[i].Descripcion_gral,
                Path_Imagen: vehiculos[i].Path_Imagen,
                Tipo_veh_invo: vehiculos[i].Tipo_veh_invo,
                img_64: vehiculos[i].img_64,
                Estado_Veh:vehiculos[i].Estado_Veh,
                Ultima_Actualizacion:vehiculos[i].Ultima_Actualizacion,
                Capturo:vehiculos[i].Capturo
            }
           await insertNewRowVehiculo(formDataVeh);//Inserta todos los vehiculos del evento
        }
    }

    if(responsables.length>0){
        document.getElementsByName('Identificacion_I')[0].checked = true
        document.getElementsByName('Identificacion_I')[1].checked = false
        changeIdentificacionI()
        for await(responsable of responsables){
            let formDataRes = {
                Folio_infra: responsable.Folio_infra,
                Id_Responsable : responsable.Id_Responsable ,
                sexoResponsable: responsable.Sexo,
                complexionResponsable: responsable.Complexion,
                rangoEdad: responsable.Rango_Edad,
                descripcionResponsable: responsable.Descripcion_Responsable,
                Path_Imagen: responsable.Path_Imagen,
                tipo_arma: responsable.Tipo_arma,
                img_64: responsable.img_64,
                Estado_Res:responsable.Estado_Res,
                Ultima_Actualizacion:responsable.Ultima_Actualizacion,
                Capturo:responsable.Capturo
            }
           await insertNewRowResponsable(formDataRes);//Inserta todos los involucrados del evento
        
        }
    }

});

async function refrescarDOM() {

    await RecargaTablasEvento()
    select.addEventListener('change',showViolencia)
    select2.addEventListener('change',showArmas)
    evento = getEventotoSearch();
    data = await getEventoP(evento)
    vectores = await getAllVectores();;
    llenarEvento(data);
    InfoDetencion = await getInfoDetencion(data.Folio_infra);
    if(data.Ubo_Detencion==1 || InfoDetencion!= false){
        
        llenarUbicacion(InfoDetencion);
    }
    delito.addEventListener('keyup', esOtro)  
    delitos = await getDelitosP(evento)
    hechos = await getHechosP(evento)
    responsables = await getResponsablesP(evento)
    vehiculos = await getVehiculosP(evento)
    for (let i = 0; i < hechos.length; i++) {
        separadas = hechos[i].Fecha_Hora_Hecho.split(' ') 
        let formData = {
            descripcionHechos: hechos[i].Descripcion,
            fechaHechos: separadas[0],
            horaHechos: separadas[1]
        }
        insertNewRowHecho(formData); //Inserta todos los hechos del evento
    }
    for (let i = 0; i < delitos.length; i++) {
        let formData = {
            descripcionDelitos: delitos[i].Descripcion,
            tipoDelito:delitos[i].Giro
        }
        insertNewRowDelito(formData);//Inserta todos los delitos del evento
    }
    if(vehiculos.length>0){
        document.getElementsByName('Identificacion_VI')[0].checked = true
        document.getElementsByName('Identificacion_VI')[1].checked = false
        changeIdentificacionVI()
        for (let i = 0; i < vehiculos.length; i++){
            let formDataVeh = {
                Id_Vehiculo : vehiculos[i].Id_Vehiculo,
                Folio_infra: vehiculos[i].Folio_infra,
                tipoVehiculo: vehiculos[i].Tipo_Vehiculo,
                marcaVehiculo: vehiculos[i].Marca,
                submarcaVehiculo: vehiculos[i].Submarca,
                modeloVehiculo: vehiculos[i].Modelo,
                placasVehiculo: vehiculos[i].Placas_Vehiculo,
                colorVehiculo: vehiculos[i].Color,
                descripcionVehiculo: vehiculos[i].Descripcion_gral,
                Path_Imagen: vehiculos[i].Path_Imagen,
                Tipo_veh_invo: vehiculos[i].Tipo_veh_invo,
                img_64: vehiculos[i].img_64,
                Estado_Veh:vehiculos[i].Estado_Veh,
                Ultima_Actualizacion:vehiculos[i].Ultima_Actualizacion,
                Capturo:vehiculos[i].Capturo
            }
           await insertNewRowVehiculo(formDataVeh);//Inserta todos los vehiculos del evento
        }
    }else{
        document.getElementsByName('Identificacion_VI')[0].checked = false
        document.getElementsByName('Identificacion_VI')[1].checked = true
        changeIdentificacionVI()
    }

    if(responsables.length>0){
        document.getElementsByName('Identificacion_I')[0].checked = true
        document.getElementsByName('Identificacion_I')[1].checked = false
        changeIdentificacionI()
        for await(responsable of responsables){
            let formDataRes = {
                Folio_infra: responsable.Folio_infra,
                Id_Responsable : responsable.Id_Responsable ,
                sexoResponsable: responsable.Sexo,
                complexionResponsable: responsable.Complexion,
                rangoEdad: responsable.Rango_Edad,
                descripcionResponsable: responsable.Descripcion_Responsable,
                Path_Imagen: responsable.Path_Imagen,
                tipo_arma: responsable.Tipo_arma,
                img_64: responsable.img_64,
                Estado_Res:responsable.Estado_Res,
                Ultima_Actualizacion:responsable.Ultima_Actualizacion,
                Capturo:responsable.Capturo
            }
        await insertNewRowResponsable(formDataRes);//Inserta todos los involucrados del evento
        }
    }else{
        document.getElementsByName('Identificacion_I')[0].checked = false
        document.getElementsByName('Identificacion_I')[1].checked = true
        changeIdentificacionI()
    }

}

async function RecargaTablasEvento(){
    
    let aux = document.getElementById('contarRes').rows.length+1
    for(let i = 1; i < aux; i++){
        document.getElementById('PersonaTable').deleteRow(1);
    }

    aux = document.getElementById('contarVehiculos').rows.length+1
    console.log(aux)
    for(let i = 1; i < aux; i++){
        console.log(i)
        document.getElementById('VehiculoTable').deleteRow(1);
    }

    aux = document.getElementById('contardelitos').rows.length+1
    for(let i = 1; i < aux; i++){
        document.getElementById('faltasDelitosTable').deleteRow(1);
    }

    aux = document.getElementById('contarhechos').rows.length+1
    for(let i = 1; i < aux; i++){
        document.getElementById('HechosTable').deleteRow(1);
    }
}
/*------------------------------------FUNCIONALIDADES DE LAS TABLAS------------------------- */

const deleteRow = async (obj, tableId) => {//funcion para eliminar uanfila en tablas ademas de funcion especial de eliminacion para las tablas vehiculos y personas
    if (confirm('¿Desea eliminar este elemento?')) {
        const row = obj.parentElement.parentElement;
        aux=row.rowIndex;
        if(tableId.id=='PersonaTable'){
            if(row.cells[0].innerHTML!='SD'){
                await DesasociaInvolucrado(row.cells[0].innerHTML);
                
            }
        }
        if(tableId.id=='VehiculoTable'){
            if(row.cells[0].innerHTML!='SD'){
                await DesasociaVehInvolucrado(row.cells[0].innerHTML);
            }
        }
        document.getElementById(tableId.id).deleteRow(row.rowIndex);
        j=aux+1;
        if(tableId.id=='VehiculoTable'){//Si es la tabla de vehiculos hace una eliminacion especial
            table = document.getElementById('VehiculoTable')
            for(let i=aux;i<table.rows.length;i++){
 
                let contenedorImg =table.rows[i].cells[8].children[1];
                console.log(contenedorImg)
                contenedorImg.setAttribute('id', 'imageContentV_row'+i);
                
                if(contenedorImg.childNodes.length>1){
                    if(contenedorImg.childNodes[1].childNodes[1]!=undefined){

                        contenedorImg.childNodes[1].childNodes[1].setAttribute('onclick', "deleteImageFotoV("+i+")");
                        contenedorImg.childNodes[3].setAttribute('id', 'imagesV_row_'+i);
                        contenedorImg.childNodes[3].setAttribute('data-target', '#ModalCenterVehiculo'+i);
                        console.log(contenedorImg.childNodes[5].getAttribute('class'))
    
                        if(contenedorImg.childNodes[5].getAttribute('class')==j+' Photo'){
                            contenedorImg.childNodes[5].setAttribute('class', i+' Photo');
                            console.log("foto")
                        }else{
                            contenedorImg.childNodes[5].setAttribute('class', i+' File');
                            console.log("file")
                        }
                        contenedorImg.childNodes[7].setAttribute('id', 'ModalCenterVehiculo'+i);
                    }else{

                        contenedorImg.childNodes[0].childNodes[1].setAttribute('onclick', "deleteImageFotoV("+i+")");
                        contenedorImg.childNodes[2].setAttribute('id', 'imagesV_row_'+i);
                        contenedorImg.childNodes[2].setAttribute('data-target', '#ModalCenterVehiculo'+i);
                        console.log(contenedorImg.childNodes[4])
    
                        if(contenedorImg.childNodes[4].getAttribute('class')==j+' Photo'){
                            contenedorImg.childNodes[4].setAttribute('class', i+' Photo');
                            console.log("foto")
                        }else{
                            contenedorImg.childNodes[4].setAttribute('class', i+' File');
                            console.log("file")
                        }
                        contenedorImg.childNodes[6].setAttribute('id', 'ModalCenterVehiculo'+i);
                    }  
                }
                let contenedorInput =table.rows[i].cells[8].children[0];
                contenedorInput.setAttribute('id', 'uploadFileFotos'+i);
                contenedorInput.childNodes[1].childNodes[1].setAttribute('id', 'fileFotoV_row'+i);
                contenedorInput.childNodes[1].childNodes[1].setAttribute('name', 'FotoVehiculo_row'+i);
                contenedorInput.childNodes[1].childNodes[3].setAttribute('for', 'fileFotoV_row'+i);
                j++;
            }
            refrescarDOM()
        }

        if(tableId.id=='PersonaTable'){//Si es la tabla de involucrados hace una eliminacion especial
            table = document.getElementById('PersonaTable')
            j=aux+1;
            for(let i=aux;i<table.rows.length;i++){
                let contenedorImg =table.rows[i].cells[5].children[1];
                console.log(contenedorImg)
                contenedorImg.setAttribute('id', 'imageContentP_row'+i);
                if(contenedorImg.childNodes.length>1){
                    if(contenedorImg.childNodes[1].childNodes[1]!=undefined){

                        contenedorImg.childNodes[1].childNodes[1].setAttribute('onclick', "deleteImageFotoP("+i+")");
                        contenedorImg.childNodes[3].setAttribute('id', 'imagesP_row_'+i);
                        contenedorImg.childNodes[3].setAttribute('data-target', '#ModalCenterInvolucrado'+i);
                        console.log(contenedorImg.childNodes[5])
                        console.log(contenedorImg.childNodes[5].getAttribute('class'))
                        if(contenedorImg.childNodes[5].getAttribute('class')==j+' Photo'){
                            contenedorImg.childNodes[5].setAttribute('class', i+' Photo');
                            console.log("foto")
                        }else{
                            contenedorImg.childNodes[5].setAttribute('class', i+' File');
                            console.log("file")
                        }
                        contenedorImg.childNodes[7].setAttribute('id', 'ModalCenterInvolucrado'+i);
                    }else{

                        contenedorImg.childNodes[0].childNodes[1].setAttribute('onclick', "deleteImageFotoP("+i+")");
                        contenedorImg.childNodes[2].setAttribute('id', 'imagesP_row_'+i);
                        contenedorImg.childNodes[2].setAttribute('data-target', '#ModalCenterInvolucrado'+i);
                        console.log(contenedorImg.childNodes[4])
    
                        if(contenedorImg.childNodes[4].getAttribute('class')==j+' Photo'){
                            contenedorImg.childNodes[4].setAttribute('class', i+' Photo');
                            console.log("foto")
                        }else{
                            contenedorImg.childNodes[4].setAttribute('class', i+' File');
                            console.log("file")
                        }
                        contenedorImg.childNodes[6].setAttribute('id', 'ModalCenterInvolucrado'+i);
                    }  
                }
 
                let contenedorInput =table.rows[i].cells[5].children[0];
                contenedorInput.setAttribute('id', 'uploadFileFotoP'+i);
                contenedorInput.childNodes[1].childNodes[1].setAttribute('id', 'fileFotoP_row'+i);
                contenedorInput.childNodes[1].childNodes[1].setAttribute('name', 'FotoInvolucrado_row'+i);
                contenedorInput.childNodes[1].childNodes[3].setAttribute('for', 'fileFotoP_row'+i);
                j++;
            }
            refrescarDOM()
        }
    }
}

//FUNCIONALIDADES DE LA TABLA DE DELITOS O FALTAS
delito = document.getElementById('delitos_principales')
otro = document.getElementById('otrofg')
otrovalorinput = document.getElementById('delitos_otro')
const insertNewRowDelito = ({  descripcionDelitos,tipoDelito}) => {//Funcion para la insercion tabla delitos
    const table = document.getElementById('faltasDelitosTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    newRow.insertCell(0).innerHTML = descripcionDelitos;
    if(tipoDelito==""||tipoDelito==null){
        newRow.insertCell(1).innerHTML ="SD";

    }else{
        newRow.insertCell(1).innerHTML = tipoDelito;
    }
    newRow.insertCell(2).innerHTML =`<button type="button" class="btn btn-ssc" value="-" onclick="deleteRow(this,faltasDelitosTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
}

let selectedRowOtros = null;

const onFormOtroSubmit = async() => {//SE LANZA LA FUNCION CON EL BOTON DE + DE LA TABLA DELITOS
    const campos = ['delitos_principales','tipo_delito'];
    bandera= await validateFormOtro(campos)//VERIFICA SI EL DELITO ES PARTE DEL CATALOGO EN CASO DE QUE NO INFORMA AL USUARIO QUE INGRESE UN DELITO VALIDO
    if (bandera === true) {
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

const insertNewRowOtro = ({ delitos_principales, tipo_delito}, type) => {//Funcion para cuando ingrese "otro delito" 
    const table = document.getElementById('faltasDelitosTable').getElementsByTagName('tbody')[0];
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
        const response = await fetch(base_url_js + 'GestorCasos/getDelitos', {
            method: 'POST'
        });
        const data = await response.json();
        return data;
        
    } catch (error) {
        console.log(error);
    }
}
/*------------- FUNCION AUTOCOMPLETE DE PROBABLE DELITO O FALTA ----------------------- */

const inputDelitos = document.getElementById('delitos_principales');
inputDelitos.addEventListener('input', () => { 
    myFormData.append('termino', inputDelitos.value)
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

const esOtro = () => {//FUNCION ESPECIAL PARA CUANDO LLENE CON EL CAMPO DE OTRO 
    if(delito.value.toLowerCase() == 'otro'){
        console.log('Es otro')
        otro.classList.remove('mi_hide')
    }else{
        otro.classList.add('mi_hide')
    }
}

//FUNCIONALIDADES DE LA TABLA DE HECHOS 

document.getElementById("descripcion_hecho").addEventListener("input", function(event) {/// Para el pegado
    var valor = event.target.value;
    var nuevoValor = "";
    for (var i = 0; i < valor.length; i++) {
        var char = valor[i];       
        if (/[a-zA-Z0-9\-+\/*%().,\sáéíóúÁÉÍÓÚÑñ'"?¿¡!$&=:;_]/.test(char)) {
            nuevoValor += char;
        }
    }
    event.target.value = nuevoValor;
});
let selectedRowHechos = null//VARIABLE QUE DEFINE SI SE TRATA DE UNA ACTUALIZACION DE UN REGISTRO YA CREADO O DE UNA NUEVA INSERCION
const onFormHechosSubmit = () => {//SE LANZA LA FUNCION CON EL BOTON DE + DE LA TABLA HECHOS
    if(document.getElementById('descripcion_hecho').value!=""){//VALIDAD SI EXISTE UNA DESCRIPCION DEL HECHO
        if (selectedRowHechos === null){
            InsertHecho();//INSERTA NUEVA FILA EN LA TABLA DE HECHOS
            resetFormHechos();//LIMPIA LA VISTA 
        }else{
            updateRowHecho();//ACTUALIZA LA FILA SELECCIONADA EN LA TABLA DE HECHOS
            resetFormHechos();//LIMPIA LA VISTA
        }
    }else{
        document.getElementById('descripcion_error').innerText = 'Debe de especificar el hecho';
    }
}

const insertNewRowHecho = ({descripcionHechos,fechaHechos,horaHechos}) => {//Funcion para llenar tabla de hechos
    const table = document.getElementById('HechosTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    newRow.insertCell(0).innerHTML = descripcionHechos;
    newRow.insertCell(1).innerHTML = fechaHechos;
    newRow.insertCell(2).innerHTML = horaHechos;
    newRow.insertCell(3).innerHTML = `<button type="button" class="btn btn-add" onclick="editHecho(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc" value="-" onclick="deleteRow(this,HechosTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
}

const InsertHecho = () => {//INSERTA LOS DATOS CAPTURADOS EN LA VISTA EN LA TABLA DE HECHOS
    const table = document.getElementById('HechosTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    let emojis = /(?:[\u2700-\u27bf]|(?:\ud83c[\udde6-\uddff]){2}|[\ud800-\udbff][\udc00-\udfff]|[\u0023-\u0039]\ufe0f?\u20e3|\u3299|\u3297|\u303d|\u3030|\u24c2|\ud83c[\udd70-\udd71]|\ud83c[\udd7e-\udd7f]|\ud83c\udd8e|\ud83c[\udd91-\udd9a]|\ud83c[\udde6-\uddff]|\ud83c[\ude01-\ude02]|\ud83c\ude1a|\ud83c\ude2f|\ud83c[\ude32-\ude3a]|\ud83c[\ude50-\ude51]|\u203c|\u2049|[\u25aa-\u25ab]|\u25b6|\u25c0|[\u25fb-\u25fe]|\u00a9|\u00ae|\u2122|\u2139|\ud83c\udc04|[\u2600-\u26FF]|\u2b05|\u2b06|\u2b07|\u2b1b|\u2b1c|\u2b50|\u2b55|\u231a|\u231b|\u2328|\u23cf|[\u23e9-\u23f3]|[\u23f8-\u23fa]|\ud83c\udccf|\u2934|\u2935|[\u2190-\u21ff])/g;
    let limpia= document.getElementById('descripcion_hecho').value.toUpperCase();
    limpia=limpia.replace(emojis , '');
    newRow.insertCell(0).innerHTML = limpia;
    newRow.insertCell(1).innerHTML = document.getElementById('fecha_recepcion_hechos').value;
    newRow.insertCell(2).innerHTML = document.getElementById('hora_recepcion_hechos').value;
    newRow.insertCell(3).innerHTML = `<button type="button" class="btn btn-add" onclick="editHecho(this)"> 
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

const updateRowHecho = () => {//FUNCION PARA ACTUALIZAR LOS DATOS DE LA TABLA DE HECHOS
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


/*-----------------------------------FUNCIONES DE PARA LA OBTENCION DE DATOS DEL EVENTO------------------- */

const getEventotoSearch = () => {//OBTIENE EL FOLIO DEL EVENTO QUE SE ESTA EDITARA
    const params = new Proxy(new URLSearchParams(window.location.search), {
        get: (searchParams, prop) => searchParams.get(prop),
      });
      // Get the value of "some_key" in eg "https://example.com/?some_key=some_value"
      let value = params.Folio_infra; // "some_value"
      return value;
}

const getInfoDetencion = async (Folio_infra) =>{
    try {
        var myFormData = new FormData();
        myFormData.append('Folio_infra',Folio_infra);
        const response = await fetch(base_url_js + 'GestorCasos/getInfoDetencion', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const getEventoP = async (evento) => {//FUNCION QUE OBTINE LOS DATOS PRINCIPALES DEL EVENTO
    try {
        var myFormData = new FormData();
        myFormData.append('Folio_infra',evento);
        const response = await fetch(base_url_js + 'GestorCasos/getPrincipalesAll', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
} 
const getDelitosP = async (evento) => {//FUNCION QUE OBTIENE LOS DELITOS DEL EVENTO
    try {
        var myFormData = new FormData();
        myFormData.append('Folio_infra',evento)
        const response = await fetch(base_url_js + 'GestorCasos/getDelitosC', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
} 
const getHechosP = async (evento) => {//FUNCION QUE OBTIENE LOS HECHOS DEL EVENTO
    try {
        var myFormData = new FormData();
        myFormData.append('Folio_infra',evento)
        const response = await fetch(base_url_js + 'GestorCasos/getHechosC', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
} 
const getResponsablesP = async (evento) => {//FUNCION QUE OBTIENE LAS PERSONAS INVOLUCRADAS DEL EVENTO
    try {
        var myFormData = new FormData();
        myFormData.append('Folio_infra',evento)
        const response = await fetch(base_url_js + 'GestorCasos/getResponsablesC', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
} 
const getVehiculosP = async (evento) => {//FUNCION QUE OBTIENE LOS VEHICULOS INVOLUCRADAS DEL EVENTO
    try {
        var myFormData = new FormData();
        myFormData.append('Folio_infra',evento)
        const response = await fetch(base_url_js + 'GestorCasos/getVehiculosC', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
} 
/*-----------------------------------FUNCIONES DE PARA LLENAR LOS DATOS DEL EVENTO------------------- */

const llenarUbicacion = async(data)=>{
    let radiodetencion = document.getElementsByName('Detencion');
    radiodetencion[0].checked = true
    changeDetencion()
    document.getElementById('Compañia').value = data.Compañia;
    document.getElementById('Elementos_Realizan_D').value = data.Elementos_Realizan_D;
    document.getElementById('Fecha_Detencion').value = data.Fecha_Detencion;
    document.getElementById('Nombres_Detenidos').value = data.Nombres_Detenidos;
    if(data.Detencion_Por_Info_Io==1){
        radioIo = document.getElementsByName('Detencion_Por_Info_Io')
        radioIo[0].checked = true;
    }
    
    if(data.Foraneo==1){
        radio = document.getElementsByName('ubicacion_puebla')
        radio[1].checked = true
        showUbicacionForanea()
        document.getElementById('Estado').value=data.Estado;
        document.getElementById('Municipio').value=data.Municipio;
    }else{
        document.getElementById('Estado').value='PUEBLA';
        document.getElementById('Municipio').value='PUEBLA';
    }

    if(data.CoordY!='' && data.CoordX!=''){
        let validaX = await ValidaCoordX(data.CoordX);
        let validaY = await ValidaCoordY(data.CoordY);

        if(validaX == "" && validaY == ""){
            document.getElementById('cordX_Det').value = data.CoordX;
            document.getElementById('cordY_Det').value = data.CoordY;
            await getColoniasCallesD();
        }else{
            document.getElementById('cordX_Det').value = data.CoordX;
            document.getElementById('cordY_Det').value = data.CoordY;
        }
    }
    document.getElementById('Colonia_Det').value = data.Colonia;
    document.getElementById('Calle_Det').value = data.Calle;
    document.getElementById('Calle_Det2').value = data.Calle2;
    document.getElementById('CP_Det').value = data.CP;
    document.getElementById('no_Ext_Det').value = data.NumExt;
    document.getElementById('no_Int_Det').value = data.NumInt;

    document.getElementById('Link_Ubicacion_Det').value = data.Link_Ubicacion;
    document.getElementById('Observacion_Ubicacion_Det').value = data.Observaciones_Detencion;
    document.getElementById('Id_Ubicacion_Detencion').value = data.Id_Ubicacion_Detencion;

}
const llenarEvento = async ( data ) => {//LLENA LOS DATOS EN LA PLANTILLA DE LA VISTA DE DATOS PRINCIPALES
    elemento_captura = document.getElementById('captura_principales')
    folio_infra = document.getElementById('folio_infra_principales')
    folio_911 = document.getElementById('911_principales')
    folio_911_oculto = document.getElementById('911_principales_oculto')
    fuente= document.getElementById('fuente_principales')
    fecha_evento = document.getElementById('fecha_evento_principales')
    hora_evento = document.getElementById('hora_evento_principales')
    fechahora_captura = document.getElementById('fechahora_captura_principales')
    statusp = document.getElementById('status_principales')
    zona = document.getElementById('zona')
    vector = document.getElementById('vector')
    colonia = document.getElementById('Colonia')
    calle = document.getElementById('Calle')
    calle2 = document.getElementById('Calle2')
    cordy = document.getElementById('cordY')
    cordx = document.getElementById('cordX')
    noext = document.getElementById('no_Ext')
    cp = document.getElementById('CP')
    stipo_violencia = document.getElementById('sviolencia_principales')
    tipo_violencia = document.getElementById('violencia_principales')
    tipo_violenciaCS= document.getElementById('violencia_principales1')
   
    fecha_activacion = document.getElementById('fecha_activacion_principales')
    hora_activacion = document.getElementById('hora_activacion_principales')
    habilitado = document.getElementById('Habilitado_question1')
    deshabilitado = document.getElementById('Habilitado_question2')
    seguimiento = document.getElementById('Seguimiento_principales');
    EstatusEvento = document.getElementById('Estatus_Evento')
    ClaveSeguimiento = document.getElementById('clave_asignacion_seguimiento')

    Unidad_Primer_R = document.getElementById('Unidad_Primer_R')
    Informacion_Primer_R = document.getElementById('Informacion_Primer_R')
    Acciones = document.getElementById('Acciones')
    Turno = document.getElementById('Turno')
    Responsable_Turno = document.getElementById('Responsable_Turno')
    Semana = document.getElementById('Semana')
    //AQUI EMPIEZA A LLENAR LA VISTA 
    
    elemento_captura.value = data.Elemento_Captura;
    elemento_captura.disabled = true;
    folio_infra.value=data.Folio_infra;
    folio_infra.disabled = true;
    separadas = data.FechaHora_Recepcion.split(' ');
    fecha_evento.value = separadas[0]; 
    hora_evento.value = separadas[1];
    fechahora_captura.value = data.FechaHora_Captura;
    fechahora_captura.disabled = true;
    EstatusEvento.value=(data.Status_Evento!='' && data.Status_Evento != null)?data.Status_Evento:'POR CONFIRMAR';
    if(data.Status_Evento!="FUERA DE JURISDICCION"){
        zona.value = data.Zona;
        zonaValue = zona.value.split(' ');
        if(zona.value.includes('ZONA')){
            zonaValue = zona.value.split(' ');
            vectoresFiltrados = vectores.filter((vector) => vector.Zona == zonaValue[1]);
            //console.log(vectoresFiltrados);
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
        vector.value = data.Vector
        if(data.CoordY!='' && data.CoordX!=''){
            let validaX = await ValidaCoordX(data.CoordX);
            let validaY = await ValidaCoordY(data.CoordY);
    
            if(validaX == "" && validaY == ""){
                cordy.value = data.CoordY;
                cordx.value = data.CoordX;
                await getColoniasCalles();
            }else{
                cordy.value = data.CoordY;
                cordx.value = data.CoordX;
            }
        }    
        noext.value = data.NoExt
        cp.value = data.CP
        colonia.value = data.Colonia
        calle.value = data.Calle
        calle2.value = (data.Calle2 == '' ? '' : data.Calle2) 
    }else{
        if(data.Zona!=null || data.Zona!=''){
            zona.value = data.Zona;
            zonaValue = zona.value.split(' ');
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
            vector.value = data.Vector
        }
        if(data.CoordY!='' && data.CoordX!=''){
            let validaX = await ValidaCoordX(data.CoordX);
            let validaY = await ValidaCoordY(data.CoordY);
    
            if(validaX == "" && validaY == ""){
                cordy.value = data.CoordY;
                cordx.value = data.CoordX;
                await getColoniasCalles();
            }else{
                cordy.value = data.CoordY;
                cordx.value = data.CoordX;
            }
        } 
        noext.value = data.NoExt
        cp.value = data.CP
        colonia.value = data.Colonia
        calle.value = data.Calle
        calle2.value = (data.Calle2 == '' ? '' : data.Calle2) 
    }
    folio_911.value = data.Folio_911
    folio_911_oculto.value = data.Folio_911
    fuente.value=(data.Fuente != '' && data.Fuente != null)?data.Fuente:'NA';

    document.getElementById('cons').setAttribute('value', 'NA');
    if(data.CSviolencia=='SIN VIOLENCIA'){
        stipo_violencia.value = data.Tipo_Violencia
        tipo_violenciaCS.value= 'SIN VIOLENCIA'
        document.getElementById('cons').setAttribute('value', 'NA');
        document.getElementById('form_sinviolencia').classList.remove('mi_hide')
    }else if(data.CSviolencia=='CON VIOLENCIA'){
        tipo_violencia.value = data.Tipo_Violencia
        tipo_violenciaCS.value = 'CON VIOLENCIA'
        document.getElementById('form_violencia').classList.remove('mi_hide')
        document.getElementById('cons').setAttribute('value', 'NA');

    }
    radio = document.getElementsByName('Habilitado_question')
    if(data.Status_Seguimiento.toUpperCase()=='HABILITADO'){
        habilitado.checked=true;
        document.getElementById('form_activacion').classList.remove('mi_hide')
        FechaHora_Activacion=document.getElementById('fechahora_activacion_principales')
        FechaHora_Activacion.value=data.FechaHora_Activacion;
        FechaHora_Activacion.disabled = true;
        QuienHabilito=document.getElementById('quienhabilito')
        QuienHabilito.value=data.Quien_Habilito;
        document.getElementById('fotos').disabled = false;
        document.getElementById('li-fotos').classList.remove('mi_hide')
        document.getElementById('fotos0').classList.remove('mi_hide')
        if(await getpermiso()){
            document.getElementById('entrevistas').disabled = false;
            document.getElementById('li-entrevistas').classList.remove('mi_hide')
            document.getElementById('entrevistas0').classList.remove('mi_hide')
        }

       
    }else{
        deshabilitado.checked=true;
        if(document.getElementById('TipoUsuario').value !=0){
            document.getElementById('fotos').disabled =true;
            document.getElementById('li-fotos').classList.add('mi_hide')
            document.getElementById('fotos0').classList.add('mi_hide')
            document.getElementById('entrevistas').disabled =true;
            document.getElementById('li-entrevistas').classList.add('mi_hide')
            document.getElementById('entrevistas0').classList.add('mi_hide')

        }

    }
    if(data.ClaveSeguimiento!='' && data.ClaveSeguimiento!=null){
        ClaveSeguimiento.value=data.ClaveSeguimiento
    }
    document.getElementById('statusAntes').value =data.Status_Seguimiento.toUpperCase();

    Unidad_Primer_R.value = data.Unidad_Primer_R
    Informacion_Primer_R.value = data.Informacion_Primer_R
    Acciones.value = data.Acciones
    Turno.value = (data.Turno != ''&& data.Turno != null)?data.Turno:'SD';
    Responsable_Turno.value = data.Responsable_Turno
    Semana.value = data.Semana
    document.getElementById('cancelar_evento').checked = (data.Activo == 1) ? true : false;
    cdi = document.getElementById('cdi');
    if(data.Cdi!=null){
        cdi.value = data.Cdi

    }
    if(data.Path_Pdf!="SD" && data.Path_Pdf!=null){
        let rutaPDf = `${base_url_js}public/files/GestorCasos/${data.Folio_infra}/${data.Path_Pdf}`+'?nocache='+getRandomInt(50);
        document.getElementById('viewPDF').classList.remove('mi_hide');
        document.getElementById('viewPDF').innerHTML = `
        <embed src="${rutaPDf}" width="100%" height="600"  type="application/pdf">
        `;
        banderaPdf =data.Path_Pdf
    }else{
        banderaPdf = "SD"
    }
    
    
}
const getpermiso= async () => {
    try{
        const response = await fetch(base_url_js + 'GestorCasos/getPermiso', {
            method: 'POST',
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
        return false;
    }
    
}
var banderaPdf;
/*-----------------------------------FUNCIONES LA EDICION DEL EVENTO------------------- */
// Funcion que dispara el cambio en el selector de vector cuando se cambia la opcion de la zona
zona.addEventListener('change', () => {
    vector.innerHTML="";

    console.log(zona.value);
        if(zona.value.includes('ZONA')){
            zonaValue = zona.value.split(' ');
            vectoresFiltrados = vectores.filter((vector) => vector.Zona == zonaValue[1]);
            console.log(vectoresFiltrados);
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

//FUNCIONES DEL TIPO DE VIOLENCIA
const select = document.querySelector("#violencia_principales1");

const showViolencia = () =>{
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
    }else if(cuestionViolencia=="NA"){
        document.getElementById('form_violencia').classList.add('mi_hide')
        
        document.getElementById('violencia_principales').value="NA"
        
        document.getElementById('form_sinviolencia').classList.add('mi_hide')
        document.getElementById('sviolencia_principales').value="NA"
    }
}

const select2 = document.querySelector("#violencia_principales");

const showArmas = () =>{
    cuestionArmas= document.getElementById('violencia_principales').value;
    document.getElementById('violencia_principales_error').innerText = '';
    if(cuestionArmas=="ARMA DE FUEGO"){
        document.getElementById('cons').setAttribute('value', 'SD');
    }else{
   
        document.getElementById('cons').setAttribute('value', 'NA');
    }
}

function getRandomInt(max) {//FUNCION QUE RETORNA UN NUMERO ENTERO RANDOM
    return Math.floor(Math.random() * max);
  }
const imageExists= async(imgUrl)=> {//FUNCION QUE VALIDA SI EXISTE LA IMAGEN EN EL SERVIDOR
    if (!imgUrl) {
        return false;
    }
    return new Promise(res => {
        const image = new Image();
        image.onload = () => res(true);
        image.onerror = () => res(false);
        image.src = imgUrl;
    });

}
async function ValidaCoordNegativa(valor) {/// Para el historico
   
    var nuevoValor = "";
    for (var i = 0; i < valor.length; i++) {
        var char = valor[i];       
        if (/[0-9-.]/.test(char)) {
            nuevoValor += char;
        }
    }
    return nuevoValor;
}

async function ValidaCoordPositiva(valor) {/// Para el historico
    var nuevoValor = "";
    for (var i = 0; i < valor.length; i++) {
        var char = valor[i];       
        if (/[0-9.]/.test(char)) {
            nuevoValor += char;
        }
    }
    return nuevoValor;
}