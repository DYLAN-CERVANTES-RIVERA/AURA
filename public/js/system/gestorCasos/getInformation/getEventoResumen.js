/*----------------------ESTE ARCHIVO DE JAVASCRIPT ES PARA OBTENER LOS DATOS DEL EVENTO PARA LA VISTA DEL RESUMEN---------------------------------------*/
document.addEventListener('DOMContentLoaded', async () => {
    evento = getEventotoSearch();
    data = await getResumenEvento(evento)
    responsables = await getResponsablesP(evento);
    vehiculos = await getVehiculosP(evento);
    fotos =  await getFotos(evento);
    entrevistas = await getEntrevistas(evento);

    llenarResumenEvento(data,responsables,vehiculos,fotos,entrevistas);
    
});
/*--------------------------------OBTENCION DE LOS DATOS DEL EVENTO------------------------*/
const getEventotoSearch = () => {//OBTIENE EL FOLIO DEL EVENTO QUE SE ESTA VISUALIZARA EN RESUMEN
    const params = new Proxy(new URLSearchParams(window.location.search), {
        get: (searchParams, prop) => searchParams.get(prop),
      });
      // Get the value of "some_key" in eg "https://example.com/?some_key=some_value"
      let value = params.Folio_infra; // "some_value"
      return value;
}

const getResumenEvento = async (evento) => {//FUNCION QUE OBTINE LOS DATOS PRINCIPALES DEL EVENTO
    try {
        var myFormData = new FormData();
        myFormData.append('Folio_infra',evento)
        const response = await fetch(base_url_js + 'GestorCasos/getResumen', {
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
const getFotos = async (caso) => { //Funcion para realizar una peticion para la consulta a la tabla  
    try {
        var myFormData = new FormData();
        myFormData.append('Folio_infra',caso)
        const response = await fetch(base_url_js + 'GestorCasos/getFotos', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
} 
const getEntrevistas = async (caso) => { //Funcion para realizar una peticion para la consulta a la tabla  
    try {
        var myFormData = new FormData();
        myFormData.append('Folio_infra',caso)
        const response = await fetch(base_url_js + 'GestorCasos/getEntrevistas', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}

const llenarResumenEvento = async ( data,responsables,vehiculos,fotos,entrevistas ) => {//LLENA LOS DATOS EN LA PLANTILLA DE LA VISTA
   
    elemento_captura = document.getElementById('elemento_captura')
    folio_infra = document.getElementById('Folio_infra_principales')
    folio_911 = document.getElementById('911_principales')
    fuente= document.getElementById('Fuente_principales')
    statusp = document.getElementById('status_principales')
    fechahora_captura = document.getElementById('fechahora_captura_principales')
    fechahora_recepcion = document.getElementById('fechahora_recepcion_principales')
    cp = document.getElementById('cp')
    tipo_violencia = document.getElementById('violencia_principales')
    tipo_violenciaCS= document.getElementById('violenciaCS_principales')
    tipo_arma = document.getElementById('arma_principales')
    fechahora_activacion = document.getElementById('fechahora_activacion_principales')
    seguimiento = document.getElementById('Seguimiento_principales')
    EstatusEvento= document.getElementById('Estatus_Evento')
    ClaveSeguimiento= document.getElementById('ClaveAsignacion')
    //AQUI EMPIEZA A LLENAR LA VISTA 
    elemento_captura.textContent = data.Elemento_Captura
    folio_911.textContent = data.Folio_911
    folio_infra.textContent=data.Folio_infra
    fuente.textContent=data.Fuente
    statusp.textContent=data.Status_Seguimiento
    EstatusEvento.textContent=data.Status_Evento;
    fechaLimpia= await FormatoFecha(data.FechaHora_Captura);
    fechahora_captura.textContent = fechaLimpia ;
    fechaLimpia= await FormatoFecha(data.FechaHora_Recepcion);
    fechahora_recepcion.textContent = fechaLimpia ;
    tipo_violenciaCS.textContent = data.CSviolencia
    if(data.Status_Seguimiento=="HABILITADO"){
        fechaLimpia= await FormatoFecha(data.FechaHora_Activacion);
        fechahora_activacion.textContent =fechaLimpia

    }else{
        fechahora_activacion.textContent ='EL EVENTO ESTA DESHABILITADO';
    }

    if(data.ClaveSeguimiento!='' && data.ClaveSeguimiento!=null){
        ClaveSeguimiento.textContent=data.ClaveSeguimiento
    }else{
        ClaveSeguimiento.textContent='SIN ASIGNAR'
    }
    const table = document.getElementById('ResumenTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    //Empieza generar la tabla de vizualizacion Resumen
    if(vehiculos.length>0){
        newRow.insertCell(0).innerHTML ='<td><i class="material-icons check_icon">check</i></td>';
    } else{

        newRow.insertCell(0).innerHTML ='<td><i class="material-icons close_icon">close</i></td>';
    }
    if(responsables.length>0){
        newRow.insertCell(1).innerHTML ='<td><i class="material-icons check_icon">check</i></td>';
    } else{
        newRow.insertCell(1).innerHTML ='<td><i class="material-icons close_icon">close</i></td>';
    }
    if(entrevistas.length>0){
        newRow.insertCell(2).innerHTML ='<td><i class="material-icons check_icon">check</i></td>';
    } else{
        newRow.insertCell(2).innerHTML ='<td><i class="material-icons close_icon">close</i></td>';
    }
    if(fotos.length>0 && data.SeguimientoTerminado=='1'){
        newRow.insertCell(3).innerHTML ='<td><i class="material-icons check_icon">check</i></td>';
    } else{
        if(fotos.length>0){
            newRow.insertCell(3).innerHTML ='<td><i class="material-icons close_icon">P</i></td>';
        }else{
            newRow.insertCell(3).innerHTML ='<td><i class="material-icons close_icon">close</i></td>';
        }
    }  
}
const FormatoFecha =async ($fecha)=>{//FUNCION PARA SACAR FORMATO DE FECHA
    let cad,cad2
    let dateCaptura = new Date($fecha), options = {weekday: 'long', month: 'long', day: 'numeric', year: 'numeric',hour:'numeric',minute:'numeric',hour12: false};
    cad=dateCaptura.toLocaleString('es-ES', options)
    cad=cad.toUpperCase();
    let separadas = cad.split(',')
    cad2=separadas[0]+separadas[1]+" A LAS"+separadas[2];

    return cad2;
}