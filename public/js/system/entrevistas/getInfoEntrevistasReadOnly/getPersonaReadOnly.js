const getEntrevistaSearch = async() => {//OBTIENE EL ID DE LA PERSONA ENTREVISTADA QUE SE ESTA EDITARA
    const params = new Proxy(new URLSearchParams(window.location.search),
    {
    get: (searchParams,
            prop) => searchParams.get(prop),
    });
    // Get the value of "some_key" in eg "https://example.com/?some_key=some_value"
    let value = params.Id_Persona_Entrevista; // "some_value"
    return value;
}

const getDatosPersonaEntrevistas = async (Persona_Entrevista) => {//FUNCION QUE OBTINE LOS DATOS PRINCIPALES DEL SEGUIMIENTO
    try {
        var myFormData = new FormData();
        myFormData.append('Id_Persona_Entrevista',Persona_Entrevista);
        const response = await fetch(base_url_js + 'Entrevistas/getPrincipales', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
} 

const myFormData =  new FormData();
var Persona_Entrevista;
document.addEventListener('DOMContentLoaded', async () => {
    Persona_Entrevista = await getEntrevistaSearch();
    let data = await getDatosPersonaEntrevistas(Persona_Entrevista);
    llenarDatosPrincipales(data);

});
/*-----------------------------------FUNCIONES DE PARA LLENAR LOS DATOS DE LA VISTA------------------ */
const llenarDatosPrincipales = async ( data ) => {//LLENA LOS DATOS EN LA PLANTILLA DE LA VISTA DE DATOS PRINCIPALES DE LA ENTREVISTA
    let Id_Persona_Entrevista = document.getElementById('id_persona_entrevista');
    let FechaHora_Creacion = document.getElementById('fechahora_captura_principales');
    let Capturo = document.getElementById('captura_dato_entrevista');
    let Nombre = document.getElementById('nombre');
    let Ap_Paterno = document.getElementById('ap_paterno');
    let Ap_Materno = document.getElementById('ap_materno');
    let CURP = document.getElementById('curp');
    let Telefono = document.getElementById('num_tel');
    let Fecha_Nacimiento = document.getElementById('FechaNacimiento_principales');
    let Edad = document.getElementById('edad_principales');
    let Banda = document.getElementById('banda');
    let Detenido_por = document.getElementById('detenido_por');
    let Asociado_A = document.getElementById('asociado_a');
    let Alias = document.getElementById('alias');
    let Remisiones = document.getElementById('remisiones');
    let Asignado = document.getElementById('Asignado');
    let Colonia_Domicilio = document.getElementById('colonia_dom');
    let Calle_Domicilio = document.getElementById('calle_dom');
    let Calle2_Domicilio = document.getElementById('calle2_dom');
    let No_Exterior_Domicilio = document.getElementById('numExt_dom');
    let No_Interior_Domicilio = document.getElementById('numInt_dom');
    let Colonia_Detencion = document.getElementById('colonia_detencion');
    let Calle_Detencion = document.getElementById('calle_detencion');
    let Calle2_Detencion = document.getElementById('calle2_detencion');
    let No_Exterior_Detencion = document.getElementById('numExt_detencion');
    let No_Interior_Detencion = document.getElementById('numInt_detencion');
    let zona = document.getElementById('zona');
    Id_Persona_Entrevista.value = data.Id_Persona_Entrevista;
    FechaHora_Creacion.value = data.FechaHora_Creacion;
    //Capturo.value = data.Capturo;
    Nombre.value = data.Nombre;
    Ap_Paterno.value = data.Ap_Paterno;
    Ap_Materno.value = data.Ap_Materno;
    CURP.value = data.CURP;
    Telefono.value = data.Telefono;
    Fecha_Nacimiento.value = data.Fecha_Nacimiento;
    Edad.value = data.Edad;
    zona.value = data.Zona;
    Banda.value = data.Banda;
    Detenido_por.value = data.Detenido_por;
    Asociado_A.value = data.Asociado_A;
    Alias.value = data.Alias;
    Remisiones.value = data.Remisiones;
    Colonia_Domicilio.value = data.Colonia_Domicilio;
    Calle_Domicilio.value = data.Calle_Domicilio;
    Calle2_Domicilio.value = data.Calle2_Domicilio;
    No_Exterior_Domicilio.value = data.No_Exterior_Domicilio;
    No_Interior_Domicilio.value = data.No_Interior_Domicilio;
    Colonia_Detencion.value = data.Colonia_Detencion;
    Calle_Detencion.value = data.Calle_Detencion;
    Calle2_Detencion.value = data.Calle2_Detencion;
    No_Exterior_Detencion.value = data.No_Exterior_Detencion;
    No_Interior_Detencion.value = data.No_Interior_Detencion;
    Asignado.value = (data.Asignado_a=='SD'||data.Asignado_a.trim()=='')?'':data.Asignado_a;
    if(data.Foto!='SD'){
        let ruta = `${base_url_js}public/files/Entrevistas/${data.Id_Persona_Entrevista}/${data.Foto}`;
        let ban = await imageExists(ruta)
        let div = document.getElementById('imageContentDetenido');
        if(ban==true){
            div.innerHTML = `
                            <img name="nor" src="${ruta}" id="imagesDetenido" width="300px" data-toggle="modal" data-target="#ModalCenterDetenidoEntrevista">
                            <input type="hidden" class="Photo"/>
                            <div class="modal fade " id="ModalCenterDetenidoEntrevista" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <img name="nor" src="${ruta}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                </div>
                            </div>`;
    
        }else{
            div.innerHTML = `
                            <img name="nor" src="${data.Img_64}" id="imagesDetenido" width="300px" data-toggle="modal" data-target="#ModalCenterDetenidoEntrevista">
                            <input type="hidden" class="Photo"/>
                            <div class="modal fade " id="ModalCenterDetenidoEntrevista" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <img name="nor" src="${data.Img_64}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                </div>
                            </div>`;
            BanderaRecuperacion=true
        }
    }
    let main = document.getElementById('datos_detenido_entrevistado');
    let i=0;
    let Entrevistas = await getEntrevistas(Persona_Entrevista);
    if(Entrevistas!=undefined){
        for await(let Entrevista of Entrevistas){
            i++;
            let formDataEntrevista = {
                id_entrevista : Entrevista.Id_Entrevista ,
                id_persona_entrevista: Entrevista.Id_Persona_Entrevista,
                indicativo_entrevistador: Entrevista.Indicativo_Entrevistador,
                alias_referidos: Entrevista.Alias_Referidos,
                relevancia: Entrevista.Relevancia,
                entrevista: Entrevista.Entrevista,
                fecha_entrevista:Entrevista.Fecha_Entrevista,
                hora_entrevista:Entrevista.Hora_Entrevista,
                captura_entrevistas:Entrevista.Capturo,
                Foto:Entrevista.Foto,
                Img_64:Entrevista.Img_64
            }
            await InsertVistaEntrevista(formDataEntrevista,main,i);//Inserta todas las personas del seguimiento
        }
    }
    let Forensias=await getForensias(Persona_Entrevista);
    i=0;
    if(Forensias!=undefined){
        for await(let Forensia of Forensias){
            i++;
            let formDataForensia = {
                Id_Forensia_Entrevista: Forensia.Id_Forensia_Entrevista,
                Id_Persona_Entrevista : Forensia.Id_Persona_Entrevista ,
                Descripcion_Forensia : Forensia.Descripcion_Forensia,
                Capturo : Forensia.Capturo,
                Foto : Forensia.Foto,
                Img_64 : Forensia.Img_64
            }
        await InsertVistaForensia(formDataForensia,main,i);//Inserta todos las forensias de las personas del seguimiento
        }
    }
    let Ubicaciones=await getUbicaciones(Persona_Entrevista);
    i=0;
    if(Ubicaciones!=undefined){
        for await(let Ubicacion of Ubicaciones){
            i++;
            let formDataUbicacion = {
                Id_Ubicaciones_Entrevista: Ubicacion.Id_Ubicaciones_Entrevista ,
                Id_Persona_Entrevista: Ubicacion.Id_Persona_Entrevista,
                Id_Persona: Ubicacion.Id_Persona,
                Colonia: Ubicacion.Colonia,
                Calle: Ubicacion.Calle,
                Calle2: Ubicacion.Calle2,
                NumExt: Ubicacion.NumExt,
                NumInt: Ubicacion.NumInt,
                CP: Ubicacion.CP,
                CoordX: Ubicacion.CoordX,
                CoordY: Ubicacion.CoordY,
                Observaciones_Ubicacion: Ubicacion.Observaciones_Ubicacion,
                Link_Ubicacion: Ubicacion.Link_Ubicacion,
                Estado: Ubicacion.Estado,
                Municipio: Ubicacion.Municipio,
                Foraneo: Ubicacion.Foraneo,
                Capturo : Ubicacion.Capturo,
                Foto : Ubicacion.Foto,
                Img_64 : Ubicacion.Img_64
            }
            await InsertVistaUbicacion(formDataUbicacion,main,i);//Inserta todos las Ubicaciones de las personas del seguimiento
        }
    }
    let RedesSociales=await getRedesSociales(Persona_Entrevista);
    i=0;
    if(RedesSociales!=undefined){
        for await(let RedSocial of RedesSociales){
            i++;
            let formDataRedSocial = {
                Id_Registro: RedSocial.Id_Registro ,
                Id_Persona_Entrevista : RedSocial.Id_Persona_Entrevista ,
                Usuario: RedSocial.Usuario,
                Enlace: RedSocial.Enlace,
                Tipo_Enlace: RedSocial.Tipo_Enlace,
                Observacion_Enlace: RedSocial.Observacion_Enlace,
                Capturo: RedSocial.Capturo,
                Foto_Nombre: RedSocial.Foto_Nombre,
                Img_64: RedSocial.Img_64
            } 
        await InsertVistaSocial(formDataRedSocial,main,i);//Inserta todas las redes sociales de las personas del seguimiento
        }
    }
}
const getPersonaSeguimiento = async (Id_Persona) => { //Funcion que realizar peticion para obtener los datos de las personas del seguimiento
    try {
        myFormData.append('Id_Persona',Id_Persona)
        const response = await fetch(base_url_js + 'Entrevistas/getPersonaSeguimientoOneRegister', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const getForensias = async (Id_Persona_Entrevista) => { //Funcion que realizar peticion para obtener los datos de las forensias de las personas del seguimiento
    try {
        myFormData.append('Id_Persona_Entrevista',Id_Persona_Entrevista)
        const response = await fetch(base_url_js + 'Entrevistas/getForensias', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const getEntrevistas = async (Persona_Entrevista) => { //Funcion que realizar peticion para obtener los datos de las entrevistas del evento
    try {
        var myFormData = new FormData();
        myFormData.append('Id_Persona_Entrevista',Persona_Entrevista)
        const response = await fetch(base_url_js + 'Entrevistas/getEntrevistas', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const getUbicaciones = async (Id_Persona_Entrevista) => { //Funcion que realizar peticion para obtener los datos de las forensias de las personas del seguimiento
    try {
        myFormData.append('Id_Persona_Entrevista',Id_Persona_Entrevista)
        const response = await fetch(base_url_js + 'Entrevistas/getUbicaciones', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const getRedesSociales = async (Id_Persona_Entrevista) => { //Funcion que realizar peticion para obtener los datos de las redes sociales de las personas del seguimiento
    try {
        myFormData.append('Id_Persona_Entrevista',JSON.stringify(Id_Persona_Entrevista))
        const response = await fetch(base_url_js + 'Entrevistas/getRedesSociales', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
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
const InsertVistaForensia = async({Id_Forensia_Entrevista,Id_Persona_Entrevista,Descripcion_Forensia,Capturo,Foto,Img_64},main,i)=>{
    createElement('div', {id: "Forensia"+Id_Forensia_Entrevista+Id_Persona_Entrevista, className: 'form-group'}, [],main);
    if(Img_64!='SD'){
        document.getElementById("Forensia"+Id_Forensia_Entrevista+Id_Persona_Entrevista).innerHTML=`
        <h5 class="titulo-azul col-lg-12 mt-3">Dato ${i}</h5>
        <div class="form-row mt-3">  
            <div class="form-group col-lg-12 col-sm-6">
                <span class="span_rem col-lg-12 mt-3">Foto del dato:</span>
                <div style="text-align:center;" id="imageForensia${i}">
                    <img name="nor" src="${Img_64}" id="imagesForensia${i}" width="300px" data-toggle="modal" data-target="#ModalCenterForensiaEntrevista${i}">
                    <input type="hidden" class="Photo"/>
                    <div class="modal fade " id="ModalCenterForensiaEntrevista${i}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <img name="nor" src="${Img_64}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        <div class="row">
            <div class="col-lg-12">
                <span class="span_rem">Descripcion de Dato: </span>
                <span class="span_rem_ans">${Descripcion_Forensia}</span>
            </div> 
        </div> 
    `;
    }else{
        document.getElementById("Forensia"+Id_Forensia_Entrevista+Id_Persona_Entrevista).innerHTML=`
        <h5 class="titulo-azul col-lg-12 mt-3">Dato ${i}</h5>
        <div class="row">
            <div class="col-lg-12">
                <span class="span_rem">Descripcion de Dato: </span>
                <span class="span_rem_ans">${Descripcion_Forensia}</span>
            </div> 
        </div> 
    `;
    }
}
const InsertVistaEntrevista = async({id_entrevista,id_persona_entrevista,indicativo_entrevistador,alias_referidos,relevancia,entrevista,fecha_entrevista,hora_entrevista,captura_entrevistas,Foto,Img_64},main,i)=>{
    createElement('div', {id: "Entrevista"+id_entrevista+id_persona_entrevista, className: 'form-group'}, [],main);
    if(Img_64!='SD'){
        document.getElementById("Entrevista"+id_entrevista+id_persona_entrevista).innerHTML=`
            <h5 class="titulo-azul col-lg-12 mt-3">Entrevista ${i}</h5> 
            <div class="form-row mt-3">  
                <div class="form-group col-lg-12 col-sm-6">
                    <span class="span_rem col-lg-12 mt-3">Foto de la Entrevista:</span>
                    <div style="text-align:center;" id="imageEntre${i}">
                        <img name="nor" src="${Img_64}" id="imagesEntre${i}" width="300px" data-toggle="modal" data-target="#ModalCenterEntrevista${i}">
                        <input type="hidden" class="Photo"/>
                        <div class="modal fade " id="ModalCenterEntrevista${i}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <img name="nor" src="${Img_64}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                            </div>
                        </div>
                    </div>
                </div>
            </div>       
            <div class="row">
                <div class="col-lg-4">
                    <span class="span_rem">Entrevistador: </span>
                    <span class="span_rem_ans">${indicativo_entrevistador}</span>
                </div>
                <div class="col-lg-8">
                    <span class="span_rem">Alias Referidos: </span>
                    <span class="span_rem_ans">${alias_referidos}</span>
                </div> 
                <div class="col-lg-4">
                    <span class="span_rem">Relevancia: </span>
                    <span class="span_rem_ans">${relevancia}</span>
                </div> 
                <div class="col-lg-4">
                    <span class="span_rem">Fecha y Hora de la entrevista: </span>
                    <span class="span_rem_ans">${fecha_entrevista} ${hora_entrevista}</span>
                </div> 
                <div class="col-lg-12">
                    <span class="span_rem">Entrevista: </span>
                    <span class="span_rem_ans">${entrevista}</span>
                </div> 
            </div> 
        `;
    }else{
        document.getElementById("Entrevista"+id_entrevista+id_persona_entrevista).innerHTML=`
            <h5 class="titulo-azul col-lg-12 mt-3">Entrevista ${i}</h5>       
            <div class="row">
                <div class="col-lg-4">
                    <span class="span_rem">Entrevistador: </span>
                    <span class="span_rem_ans">${indicativo_entrevistador}</span>
                </div>
                <div class="col-lg-8">
                    <span class="span_rem">Alias Referidos: </span>
                    <span class="span_rem_ans">${alias_referidos}</span>
                </div> 
                <div class="col-lg-4">
                    <span class="span_rem">Relevancia: </span>
                    <span class="span_rem_ans">${relevancia}</span>
                </div> 
                <div class="col-lg-4">
                    <span class="span_rem">Fecha y Hora de la entrevista: </span>
                    <span class="span_rem_ans">${fecha_entrevista} ${hora_entrevista}</span>
                </div> 
                <div class="col-lg-12">
                    <span class="span_rem">Entrevista: </span>
                    <span class="span_rem_ans">${entrevista}</span>
                </div> 
            </div> 
        `;

    }
}
const InsertVistaUbicacion = async({Id_Ubicaciones_Entrevista ,Id_Persona_Entrevista,Id_Persona,Colonia,Calle,Calle2,NumExt,NumInt,CP,CoordX,CoordY,Observaciones_Ubicacion,Link_Ubicacion,Estado,Municipio,Foraneo,Capturo,Foto,Img_64},main,i)=>{
    createElement('div', {id: "Ubicacion"+Id_Ubicaciones_Entrevista+Id_Persona_Entrevista, className: 'form-group'}, [],main);
    if(Img_64!='SD'){
        document.getElementById("Ubicacion"+Id_Ubicaciones_Entrevista+Id_Persona_Entrevista).innerHTML=`
                <h5 class="titulo-azul col-lg-12 mt-3">Ubicacion Relevante ${i}</h5>
                <div class="form-row mt-3">  
                    <div class="form-group col-lg-12 col-sm-6">
                        <span class="span_rem col-lg-12 mt-3">Foto de la Ubicacion:</span>
                        <div style="text-align:center;" id="imageUbicacion${i}">
                            <img name="nor" src="${Img_64}" id="imagesUbicacion${i}" width="300px" data-toggle="modal" data-target="#ModalCenterUbicacionEntrevista${i}">
                            <input type="hidden" class="Photo"/>
                            <div class="modal fade " id="ModalCenterUbicacionEntrevista${i}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <img name="nor" src="${Img_64}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-6">
                        <span class="span_rem">Colonia: </span>
                        <span class="span_rem_ans" >${Colonia}</span>
                    </div>
                    <div class="col-lg-6">
                        <span class="span_rem">Calle: </span>
                        <span class="span_rem_ans" >${Calle}</span>
                    </div>
                    <div class="col-lg-4">
                        <span class="span_rem">Calle2: </span>
                        <span class="span_rem_ans" >${Calle2}</span>
                    </div>
                    <div class="col-lg-2">
                        <span class="span_rem">NumExt: </span>
                        <span class="span_rem_ans" >${NumExt}</span>
                    </div>
                    <div class="col-lg-2">
                        <span class="span_rem">NumInt: </span>
                        <span class="span_rem_ans" >${NumInt}</span>
                    </div>
                    <div class="col-lg-2">
                        <span class="span_rem">CP: </span>
                        <span class="span_rem_ans" >${CP}</span>
                    </div>
                    <div class="col-lg-6">
                        <span class="span_rem">CoordY: </span>
                        <span class="span_rem_ans" >${CoordY}</span>
                    </div>
                    <div class="col-lg-6">
                        <span class="span_rem">CoordX: </span>
                        <span class="span_rem_ans" >${CoordX}</span>
                    </div> 
                    <div class="col-lg-12">
                        <span class="span_rem">Observacion de la ubicacion: </span>
                        <span class="span_rem_ans" >${Observaciones_Ubicacion}</span>
                    </div> 
                    <div class="col-lg-12">
                        <span class="span_rem">Link de la ubicacion: </span>
                        <span class="span_rem_ans" >${Link_Ubicacion}</span>
                    </div> 
                </div> 
                `;
    }else{
        document.getElementById("Ubicacion"+Id_Ubicaciones_Entrevista+Id_Persona_Entrevista).innerHTML=`
        <h5 class="titulo-azul col-lg-12 mt-3">Ubicacion Relevante ${i}</h5>
        <div class="row">
            <div class="col-lg-6">
                <span class="span_rem">Colonia: </span>
                <span class="span_rem_ans" >${Colonia}</span>
            </div>
            <div class="col-lg-6">
                <span class="span_rem">Calle: </span>
                <span class="span_rem_ans" >${Calle}</span>
            </div>
            <div class="col-lg-4">
                <span class="span_rem">Calle2: </span>
                <span class="span_rem_ans" >${Calle2}</span>
            </div>
            <div class="col-lg-2">
                <span class="span_rem">NumExt: </span>
                <span class="span_rem_ans" >${NumExt}</span>
            </div>
            <div class="col-lg-2">
                <span class="span_rem">NumInt: </span>
                <span class="span_rem_ans" >${NumInt}</span>
            </div>
            <div class="col-lg-2">
                <span class="span_rem">CP: </span>
                <span class="span_rem_ans" >${CP}</span>
            </div>
            <div class="col-lg-6">
                <span class="span_rem">CoordY: </span>
                <span class="span_rem_ans" >${CoordY}</span>
            </div>
            <div class="col-lg-6">
                <span class="span_rem">CoordX: </span>
                <span class="span_rem_ans" >${CoordX}</span>
            </div> 
            <div class="col-lg-12">
                <span class="span_rem">Observacion de la ubicacion: </span>
                <span class="span_rem_ans" >${Observaciones_Ubicacion}</span>
            </div> 
            <div class="col-lg-12">
                <span class="span_rem">Link de la ubicacion: </span>
                <span class="span_rem_ans" >${Link_Ubicacion}</span>
            </div> 
        </div> 
        `;

    }
}
const InsertVistaSocial= async({Id_Registro,Id_Persona_Entrevista,Usuario,Enlace,Tipo_Enlace,Observacion_Enlace,Capturo,Foto_Nombre,Img_64},main,i)=>{
    createElement('div', {id: "Social"+Id_Registro+Id_Persona_Entrevista, className: 'form-group'}, [],main);
    if(Img_64!='SD'){
        document.getElementById("Social"+Id_Registro+Id_Persona_Entrevista).innerHTML=`
        <h5 class="titulo-azul col-lg-12 mt-3">Dato Red Social ${i}</h5>
        <div class="form-row mt-3">  
            <div class="form-group col-lg-12 col-sm-6">
                <span class="span_rem col-lg-12 mt-3">Foto del dato:</span>
                <div style="text-align:center;" id="imageSocial${i}">
                    <img name="nor" src="${Img_64}" id="imagesSocial${i}" width="300px" data-toggle="modal" data-target="#ModalCenterSocial${i}">
                    <input type="hidden" class="Photo"/>
                    <div class="modal fade " id="ModalCenterSocial${i}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <img name="nor" src="${Img_64}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        <div class="row">
            <div class="col-lg-8">
                <span class="span_rem">Usuario: </span>
                <span class="span_rem_ans">${Usuario}</span>
            </div>
            <div class="col-lg-4">
                <span class="span_rem">Tipo de Enlace: </span>
                <span class="span_rem_ans">${Tipo_Enlace}</span>
            </div> 
            <div class="col-lg-12">
                <span class="span_rem">Enlace: </span>
                <span class="span_rem_ans">${Enlace}</span>
            </div> 
            <div class="col-lg-12">
                <span class="span_rem">Observacion de Enlace: </span>
                <span class="span_rem_ans">${Observacion_Enlace}</span>
            </div> 
        </div> 
    `;
    }else{
        document.getElementById("Social"+Id_Registro+Id_Persona_Entrevista).innerHTML=`
        <h5 class="titulo-azul col-lg-12 mt-3">Dato Red Social ${i}</h5>
        <div class="row">
            <div class="col-lg-8">
                <span class="span_rem">Usuario: </span>
                <span class="span_rem_ans">${Usuario}</span>
            </div>
            <div class="col-lg-4">
                <span class="span_rem">Tipo de Enlace: </span>
                <span class="span_rem_ans">${Tipo_Enlace}</span>
            </div> 
            <div class="col-lg-12">
                <span class="span_rem">Enlace: </span>
                <span class="span_rem_ans">${Enlace}</span>
            </div> 
            <div class="col-lg-12">
                <span class="span_rem">Observacion de Enlace: </span>
                <span class="span_rem_ans">${Observacion_Enlace}</span>
            </div> 
        </div> 
    `;
    }

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