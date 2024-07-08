

const asignacion_content = document.getElementById('asignacion-content');
var cont = 0;

const GetStatusTareas = async (Folio_infra) =>{
    let myFormData = new FormData;
    
    myFormData.append('Folio_infra', Folio_infra);

    fetch(base_url_js + 'GestorCasos/getTareas', {
        method: 'POST',
        body: myFormData
    })
    .then(res => res.json())
    .then(data => {
       
        if(Object.keys(data).length>0){
            cad = "";
            cont = 0;
            data.forEach(element => {
                if(Object.keys(element.Principales).length>0){
                    let actualizaciones = element.Principales
                    actualizaciones.forEach(dato => {
                        //console.log(dato);
                        insertaInfo(dato,element.Tipo);
                        cont++;
                    });
                }
            });
           
        }
        createElement('div', {id: "ATRAS", className: 'form-group'}, [],asignacion_content);
        document.getElementById("ATRAS").innerHTML=`<div class="row-md-12 mt-5 mb-5">
                <div class="d-flex justify-content-center col-sm-12">
                    <a class="btn btn-sm btn-ssc" href="${base_url_js}GestorCasos"><i class="material-icons v-a-middle">arrow_back_ios</i>Volver al inicio</a>
                </div>
            </div>`
    });
}

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

const insertaInfo = async(data,tipo)=>{

    switch(tipo){
        case 'BARRIDO':
            createElement('div', {id: "BARRIDO"+data.id_tarea_barrido +tipo, className: 'form-group'}, [],asignacion_content);
            document.getElementById("BARRIDO"+data.id_tarea_barrido +tipo).innerHTML=`
            <a class="subtitulo-azul-grueso">REPORTE BARRIDO.</a>
            <div class="row">
                
                    <div class="col-lg-6">
                        <span class="span_rem">COORD X: </span>
                        <span class="span_rem_ans" >${data.coordenada_x}</span>
                    </div>
                    <div class="col-lg-6">
                        <span class="span_rem">COORD Y : </span>
                        <span class="span_rem_ans" >${data.coordenada_y}</span>
                    </div>
                    <div class="col-lg-12">
                        <span class="span_rem">DESCRIPCION: </span>
                        <span class="span_rem_ans" style="word-wrap: break-word;">${data.descripcion.toUpperCase()}</span>
                    </div>
                    <div class="col-lg-12">
                        <span class="span_rem">NUMERO DE CAMARAS: </span>
                        <span class="span_rem_ans" >${data.camaras}</span>
                    </div>
            </div>
            <hr>
        `;
        break;

        case 'BUSQUEDA':
            createElement('div', {id: "BUSQUEDA"+data.id_tarea_busqueda +tipo, className: 'form-group'}, [],asignacion_content);
            document.getElementById("BUSQUEDA"+data.id_tarea_busqueda +tipo).innerHTML=`
            <a class="subtitulo-azul-grueso">REPORTE BUSQUEDA.</a>
                <div class="row">
                    <div class="col-lg-12">
                        <span class="span_rem">DESCRIPCION: </span>
                        <span class="span_rem_ans" >${data.descripcion.toUpperCase()}</span>
                    </div>
                </div>`; 
            if(data.img!= null){
                
                let BUSQUEDA =document.getElementById("BUSQUEDA"+data.id_tarea_busqueda+tipo)
                let ruta = await obtenerIpImages(tipo,data.img);
                createElement('div', {id:"imageContent"+tipo+data.id_tarea_busqueda , className: 'center-all'}, [],BUSQUEDA);
                document.getElementById("imageContent"+tipo+data.id_tarea_busqueda ).innerHTML=`
                          <img name="nor" src="${ruta}" id="imagesBUSQUEDA_row_${data.id_tarea_busqueda }" width="350px" height="450px" data-toggle="modal" data-target="#ModalCenterBUSQUEDA${data.id_tarea_busqueda }"> 
                          <div class="modal fade" id="ModalCenterBUSQUEDA${data.id_tarea_busqueda }" tabindex="-1" aria-labelledby="exampleModalCenterTitle" style="display: none;" aria-hidden="true" >
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <img name="nor" src="${ruta}" style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block" data-toggle="modal" data-target="#exampleModalCenter">
                                </div>
                            </div>
                          <hr>`;
            }else{
                document.getElementById("BUSQUEDA"+data.id_tarea_busqueda+tipo).innerHTML=document.getElementById("BUSQUEDA"+data.id_tarea_busqueda+tipo).innerHTML+`<hr>`;
            }
        break;

        case 'ENTREVISTA':
            createElement('div', {id: "ENTREVISTA"+data.id_tarea_entrevista  +tipo, className: 'form-group'}, [],asignacion_content);
            document.getElementById("ENTREVISTA"+data.id_tarea_entrevista  +tipo).innerHTML=`
            <a class="subtitulo-azul-grueso">REPORTE ENTREVISTA.</a>
            <div class="row">
                <div class="col-lg-12">
                    <span class="span_rem">NOMBRE: </span>
                    <span class="span_rem_ans" >${data.nombre_entrevistado.toUpperCase()}</span>
                </div>
                <div class="col-lg-12">
                    <span class="span_rem">TELEFONO: </span>
                    <span class="span_rem_ans" >${data.telefono_entrevistado}</span>
                </div>
                <div class="col-lg-12">
                    <span class="span_rem">RELACION EN EL EVENTO: </span>
                    <span class="span_rem_ans" style="word-wrap: break-word;">${data.tipo_entrevistado.toUpperCase()}</span>
                </div>
                <div class="col-lg-12">
                    <span class="span_rem">ENTREVISTA: </span>
                    <span class="span_rem_ans" >${data.entrevista.toUpperCase()}</span>
                </div>
            </div>
            <hr>
        `;   
        break;

        case 'OTRA':
            createElement('div', {id: "OTRA"+data.id_tarea_otra +tipo, className: 'form-group'}, [],asignacion_content);
            document.getElementById("OTRA"+data.id_tarea_otra +tipo).innerHTML=`
            <a class="subtitulo-azul-grueso">REPORTE OTRA TAREA.</a>
            <div class="row">
                <div class="col-lg-12">
                    <span class="span_rem">DESCRIPCION: </span>
                    <span class="span_rem_ans" >${data.descripcion.toUpperCase()}</span>
                </div>
            </div>`;
        if(data.img!= null){
            let OTRA =document.getElementById("OTRA"+data.id_tarea_otra  +tipo)
            let ruta = await obtenerIpImages(tipo,data.img);
            createElement('div', {id:"imageContent"+tipo+data.id_tarea_otra , className: ''}, [],OTRA);
            document.getElementById("imageContent"+tipo+data.id_tarea_otra ).innerHTML=`
                      <img name="nor" src="${ruta}" id="imagesOTRA_row_${data.id_tarea_otra }" width="350px" height="450px" data-toggle="modal" data-target="#ModalCenterOTRA${data.id_tarea_otra }"> 
                          <div class="modal fade" id="ModalCenterOTRA${data.id_tarea_otra }" tabindex="-1" aria-labelledby="exampleModalCenterTitle" style="display: none;" aria-hidden="true" >
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <img name="nor" src="${ruta}" style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block" data-toggle="modal" data-target="#exampleModalCenter">
                                </div>
                            </div>
                          <hr>`;
        }else{
            document.getElementById("OTRA"+data.id_tarea_otra+tipo).innerHTML=document.getElementById("OTRA"+data.id_tarea_otra+tipo).innerHTML+`<hr>`;
        } 
        break;

        case 'VIGILANCIA':
            createElement('div', {id: "VIGILANCIA"+data.id_tarea_vigilancia +tipo, className: 'form-group'}, [],asignacion_content);
            document.getElementById("VIGILANCIA"+data.id_tarea_vigilancia +tipo).innerHTML=`
            <a class="subtitulo-azul-grueso">REPORTE VIGILANCIA.</a>
                <div class="row">
                    <div class="col-lg-12">
                        <span class="span_rem">DESCRIPCION: </span>
                        <span class="span_rem_ans" >${data.descripcion.toUpperCase()}</span>
                    </div>
                </div>
            `;
            if(data.img!= null){
                let vigilancia =document.getElementById("VIGILANCIA"+data.id_tarea_vigilancia +tipo)
                let ruta = await obtenerIpImages(tipo,data.img);
                createElement('div', {id:"imageContent"+tipo+data.id_tarea_vigilancia, className: ''}, [],vigilancia);
                document.getElementById("imageContent"+tipo+data.id_tarea_vigilancia).innerHTML=`
                          <img name="nor" src="${ruta}" id="imagesVIGILANCIA_row_${data.id_tarea_vigilancia}" width="350px" height="450px" data-toggle="modal" data-target="#ModalCenterVIGILANCIA${data.id_tarea_vigilancia }"> 
                          <div class="modal fade" id="ModalCenterVIGILANCIA${data.id_tarea_vigilancia }" tabindex="-1" aria-labelledby="exampleModalCenterTitle" style="display: none;" aria-hidden="true" >
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <img name="nor" src="${ruta}" style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block" data-toggle="modal" data-target="#exampleModalCenter">
                                </div>
                            </div>
                          <hr>`;
            }else{
                document.getElementById("VIGILANCIA"+data.id_tarea_vigilancia +tipo).innerHTML=document.getElementById("VIGILANCIA"+data.id_tarea_vigilancia +tipo).innerHTML+`<hr>`;
            }       
        break;
    }
}

async function obtenerIpImages(tipo,imagen) {

    const url = window.location.href;
    if (url.includes('172.18.0.25')) {
       return 'http://172.18.110.90:9090/api/images/'+tipo.toLowerCase()+"/"+imagen;
    } else{
       return 'http://187.216.250.252:9090/api/images/'+tipo.toLowerCase()+"/"+imagen;
    }
    return '';
}