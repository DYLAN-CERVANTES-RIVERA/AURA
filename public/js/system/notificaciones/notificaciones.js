//console.log("Tabla monitoreo")
const botonReporteZen       = document.getElementById('button-global');
const asignacionglobal        = document.getElementById('asignacion-content-global');

const peticionTabla = async (tabla,id) =>{
    let myformdata = new FormData()
    myformdata.append("tabla", tabla)
    myformdata.append("id", id)
    try {
        const response = await fetch(base_url_js + 'GestorCasos/getReporteZenGlobal', {
            method: 'POST',
            body: myformdata
        });

        if (!response.ok) {
            throw new Error('Error en la solicitud');
        }

        const data = await response.json();
        return data; // Devuelve los datos obtenidos

    } catch (error) {
        console.error('Error en la solicitud:', error);
        throw error; // Puedes manejar el error o lanzarlo nuevamente según sea necesario
    }
}

const monitorizarTablas = async () => {
    let reporteBarrido = await peticionTabla("tareas_barrido","id_tarea_barrido");
    let reporteBusqueda = await peticionTabla("tareas_busqueda","id_tarea_busqueda");
    let reporteOtro = await peticionTabla("tareas_entrevista","id_tarea_entrevista");
    let reporteEntrevista = await peticionTabla("tareas_otra","id_tarea_otra");
    let reporteVigilancia = await peticionTabla("tareas_vigilancia","id_tarea_vigilancia");
    asignacionglobal.innerHTML="";
    let actualizaciones = "";
    if(Object.keys(reporteBarrido).length>0){
        actualizaciones = reporteBarrido
        actualizaciones.forEach(dato => {
            insertaInfoGRAL(dato,dato.Tipo);          
        });
    }
    if(Object.keys(reporteBusqueda).length>0){
        actualizaciones = reporteBusqueda
        actualizaciones.forEach(dato => {
            insertaInfoGRAL(dato,dato.Tipo);   
        });
    }
    if(Object.keys(reporteOtro).length>0){
        actualizaciones = reporteOtro
        actualizaciones.forEach(dato => {
            insertaInfoGRAL(dato,dato.Tipo);
        });
    }
    if(Object.keys(reporteEntrevista).length>0){
        actualizaciones = reporteEntrevista
        actualizaciones.forEach(dato => {
            insertaInfoGRAL(dato,dato.Tipo);   
        });
    }
    if(Object.keys(reporteVigilancia).length>0){
        actualizaciones = reporteVigilancia
        actualizaciones.forEach(dato => {
            insertaInfoGRAL(dato,dato.Tipo);  
        });
    } 
}


botonReporteZen.addEventListener('click', () => {
    if(asignacionglobal.style.right === '' || asignacionglobal.style.right === '-100%'){
        asignacionglobal.style.right = '-43%';
        asignacionglobal.style.top = '-350px';
        asignacionglobal.style.width = '200px';
        asignacionglobal.style.height = '600px';
        botonReporteZen.innerText = "Cerrar Reporte Completados en Campo";
        botonReporteZen.title="CERRAR REPORTE"
        monitorizarTablas();
    }else{
        if(asignacionglobal.style.right === '-43%'){
            asignacionglobal.style.right = '-100%';
            asignacionglobal.style.width = '50px';
            botonReporteZen.innerText = "Reporte Completados en Campo";
            botonReporteZen.title="ABRIR REPORTE"
        }
    }
});


setInterval(monitorizarTablas, 20000);//Cada 20 segundos se actualiza el panel


function createElement(el, options, listen = [], appendTo){
    let element = document.createElement(el);
    Object.keys(options).forEach(function (k){
       element[k] = options[k];
    });
    if(listen.length > 0){
        listen.forEach(function(l){
           element.addEventListener(l.event, l.f);
        });
    }
    appendTo.append(element);
}

const insertaInfoGRAL = async(data,tipo)=>{

    switch(tipo){
        case 'BARRIDO':
            createElement('div', {id: "BARRIDOGRAL"+data.id_tarea_barrido +tipo, className: 'form-group'}, [],asignacionglobal);
            document.getElementById("BARRIDOGRAL"+data.id_tarea_barrido +tipo).innerHTML=`
            <a class="subtitulo-azul"> FOLIO AURA : ${data.Folio_AURA} REPORTE BARRIDO.</a>
            <hr>`; 

        break;

        case 'BUSQUEDA':
            createElement('div', {id: "BUSQUEDAGRAL"+data.id_tarea_busqueda +tipo, className: 'form-group'}, [],asignacionglobal);
            document.getElementById("BUSQUEDAGRAL"+data.id_tarea_busqueda +tipo).innerHTML=`
            <a class="subtitulo-azul">FOLIO AURA : ${data.Folio_AURA} REPORTE BUSQUEDA.</a>
            <hr>`; 
        break;

        case 'ENTREVISTA':
            createElement('div', {id: "ENTREVISTAGRAL"+data.id_tarea_entrevista  +tipo, className: 'form-group'}, [],asignacionglobal);
            document.getElementById("ENTREVISTAGRAL"+data.id_tarea_entrevista  +tipo).innerHTML=`
            <a class="subtitulo-azul">FOLIO AURA : ${data.Folio_AURA} REPORTE ENTREVISTA.</a>
            <hr>`;   
        break;

        case 'OTRA':
            createElement('div', {id: "OTRAGRAL"+data.id_tarea_otra +tipo, className: 'form-group'}, [],asignacionglobal);
            document.getElementById("OTRAGRAL"+data.id_tarea_otra +tipo).innerHTML=`
            <a class="subtitulo-azul">FOLIO AURA : ${data.Folio_AURA} REPORTE OTRA TAREA.</a> 
            <hr>`;  
        break;

        case 'VIGILANCIA':
            createElement('div', {id: "VIGILANCIAGRAL"+data.id_tarea_vigilancia +tipo, className: 'form-group'}, [],asignacionglobal);
            document.getElementById("VIGILANCIAGRAL"+data.id_tarea_vigilancia +tipo).innerHTML=`
            <a class="subtitulo-azul">FOLIO AURA : ${data.Folio_AURA} REPORTE VIGILANCIA.</a> 
            <hr>`;   
        break;
    }
}