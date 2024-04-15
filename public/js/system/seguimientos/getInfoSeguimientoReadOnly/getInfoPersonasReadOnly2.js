const getPersonas = async (Id_Seguimiento) => { //Funcion que realizar peticion para obtener los datos de las entrevistas del evento
    try {
        myFormData.append('Id_Seguimiento',Id_Seguimiento)
        const response = await fetch(base_url_js + 'Seguimientos/getPersonas', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}


const InsertVistaPersona = async({Id_Persona,Id_Seguimiento,Nombre,Ap_Paterno,Ap_Materno,Genero,Edad,Fecha_Nacimiento,Telefono,Alias,Curp,Remisiones,Capturo,Foto,Img_64},Persona)=>{//FUNCION QUE INSERTA LOS DATOS DE LAS PERSONAS ASOCIADAS AL SEGUIMIENTO
    let pathImagesPersonas =base_url_js+'public/files/Seguimientos/'+Id_Seguimiento+'/Personas/';
    let scr='';
    if(Foto!='SD'){
        let ruta = pathImagesPersonas+Foto;
        let ban = await imageExists(ruta);
        ruta = ruta+'?nocache='+getRandomInt(50)
        if(ban==true){
            scr=ruta;
        }else{
            if(Img_64!='SD'){
                scr=Img_64;
            }
        }
    }
    let main = document.getElementById('datos_Personas');
    createElement('div', {id: "Persona"+Persona, className: 'form-group'}, [],main);
    document.getElementById("Persona"+Persona).innerHTML=`
        <h5 class="titulo-azul mt-3">Persona ${Persona}</h5>
        <div class="row">
            <div class=" row col-lg-9">
                <div class="col-lg-8">
                    <span class="span_rem">Nombre Completo: </span>
                    <span class="span_rem_ans" >${Nombre+' '+Ap_Paterno+' '+Ap_Materno}</span>
                </div>
                <div class="col-lg-4">
                    <span class="span_rem">Genero: </span>
                    <span class="span_rem_ans" >${Genero}</span>
                </div>
                <div class="col-lg-2">
                    <span class="span_rem">Edad: </span>
                    <span class="span_rem_ans" >${Edad}</span>
                </div>
                <div class="col-lg-4">
                    <span class="span_rem">Telefono: </span>
                    <span class="span_rem_ans" >${Telefono}</span>
                </div>
                <div class="col-lg-6">
                    <span class="span_rem">Curp: </span>
                    <span class="span_rem_ans" >${Curp}</span>
                </div>
                <div class="col-lg-6">
                    <span class="span_rem">Fecha de Nacimiento: </span>
                    <span class="span_rem_ans" >${Fecha_Nacimiento}</span>
                </div>
                <div class="col-lg-6">
                    <span class="span_rem">Alias: </span>
                    <span class="span_rem_ans" >${Alias}</span>
                </div>
                <div class="col-lg-6">
                    <span class="span_rem">Remisiones: </span>
                    <span class="span_rem_ans" >${Remisiones}</span>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="col-lg-12">
                    <span class="span_rem">Capturo: </span>
                    <span class="span_rem_ans" >${Capturo}</span>
                </div>
                <img name="nor" src="${scr}"  width="300"  data-toggle="modal" data-target="#ModalCenterPersona${Persona}">
                <div class="modal fade " id="ModalCenterPersona${Persona}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <img name="nor" src="${scr}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                    </div>
                </div>  
            </div>
        </div>
        <hr>
    `;
    let contenedorhtml=document.getElementById("Persona"+Persona)
    let Antecedentes = await getAntecedentesOneRegister(Id_Persona,'PERSONA');
    let Domicilios = await getDomiciliosOneRegister(Id_Persona,'PERSONA');
    let RedesSociales = await getRedesSocialesOneRegister(Id_Persona);
    
   
    for await(Domicilio of Domicilios){
        let formDataDomicilio = {
            Id_Domicilio : Domicilio.Id_Domicilio,
            Id_Dato: Domicilio.Id_Dato,
            Observaciones_Ubicacion : Domicilio.Observaciones_Ubicacion,
            Estatus : Domicilio.Estatus,
            Colonia : Domicilio.Colonia,
            Calle : Domicilio.Calle,
            Calle2 : Domicilio.Calle2,
            NumExt : Domicilio.NumExt,
            NumInt : Domicilio.NumInt,
            CP : Domicilio.CP,
            CoordY : Domicilio.CoordY,
            CoordX : Domicilio.CoordX,
            Capturo : Domicilio.Capturo
        }
        await InsertVistaDomicilio(formDataDomicilio,contenedorhtml);//Inserta todos los involucrados del evento
    }
    for await(let Antecedente of Antecedentes){
        let formDataAntecedente = {
            Id_Antecedente : Antecedente.Id_Antecedente,
            Id_Dato: Antecedente.Id_Dato,
            Tipo_Entidad : Antecedente.Tipo_Entidad,
            Descripcion_Antecedente : Antecedente.Descripcion_Antecedente,
            Fecha_Antecedente : Antecedente.Fecha_Antecedente,
            Capturo : Antecedente.Capturo
        }
        await InsertVistaAntecedente(formDataAntecedente,contenedorhtml);//Inserta todos domicilios de los vehiculos del seguimiento
    }
    for await(let RedSocial of RedesSociales){
        let formDataRedSocial = {
            Id_Registro : RedSocial.Id_Registro,
            Id_Persona : RedSocial.Id_Persona,
            Id_Seguimiento: Seguimiento,
            Usuario : RedSocial.Usuario,
            Tipo_Enlace : RedSocial.Tipo_Enlace,
            Enlace : RedSocial.Enlace,
            Observacion_Enlace : RedSocial.Observacion_Enlace,
            Capturo : RedSocial.Capturo,
            Foto_Nombre : RedSocial.Foto_Nombre,
            Img_64 : RedSocial.Img_64
        }
       await InsertVistaRedesSociales(formDataRedSocial,contenedorhtml);//Inserta todos los domicilios de las personas del seguimiento
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
const InsertVistaDomicilio= async({Id_Domicilio,Id_Dato,Observaciones_Ubicacion,Estatus,Colonia,Calle,Calle2,NumExt,NumInt,CP,CoordY,CoordX,Capturo},main)=>{
    createElement('div', {id: "Domicilio"+Id_Dato+Id_Domicilio, className: 'form-group'}, [],main);
    document.getElementById("Domicilio"+Id_Dato+Id_Domicilio).innerHTML=`
             <span class="subtitulo-azul-grueso">Domicilio asociado (${Estatus}).</span>
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
                <div class="col-lg-2">
                    <span class="span_rem">Capturo: </span>
                    <span class="span_rem_ans" >${Capturo}</span>
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
                    <span class="span_rem">Observacion del domicilio: </span>
                    <span class="span_rem_ans" >${Observaciones_Ubicacion}</span>
                </div> 
            </div> 
            <hr>
`;
}

const InsertVistaAntecedente= async({Id_Antecedente,Id_Dato,Tipo_Entidad,Descripcion_Antecedente,Fecha_Antecedente,Capturo},main)=>{
    createElement('div', {id: "Antecedente"+Id_Dato+Id_Antecedente, className: 'form-group'}, [],main);
    document.getElementById("Antecedente"+Id_Dato+Id_Antecedente).innerHTML=`
    <a class="subtitulo-azul-grueso">Antecedente asociado.</a>
    <div class="row">
       <div class="col-lg-6">
           <span class="span_rem">Descripcion antecedente: </span>
           <span class="span_rem_ans" >${Descripcion_Antecedente}</span>
       </div>
       <div class="col-lg-3">
           <span class="span_rem">Fecha del Antecedente: </span>
           <span class="span_rem_ans" >${Fecha_Antecedente}</span>
       </div>
       <div class="col-lg-3">
           <span class="span_rem">Capturo: </span>
           <span class="span_rem_ans" >${Capturo}</span>
       </div>
    </div>
    <hr>
`;
}
const InsertVistaRedesSociales= async({Id_Registro,Id_Persona,Id_Seguimiento,Usuario,Tipo_Enlace,Enlace,Observacion_Enlace,Capturo,Foto_Nombre,Img_64},main)=>{
    let pathImagesPersonas =base_url_js+'public/files/Seguimientos/'+Id_Seguimiento+'/Redes_Sociales/';
    let scr='';
    if(Foto_Nombre!='SD'){
        let ruta = pathImagesPersonas+Foto_Nombre;
        let ban = await imageExists(ruta);
        ruta = ruta+'?nocache='+getRandomInt(50)
        if(ban==true){
            scr=ruta;
        }else{
            if(Img_64!='SD'){
                scr=Img_64;
            }
        }
    }
    createElement('div', {id: "RedSocialPersona"+Id_Persona+Id_Registro, className: 'form-group'}, [],main);
    document.getElementById("RedSocialPersona"+Id_Persona+Id_Registro).innerHTML=`
    <a class="subtitulo-azul-grueso">Dato de Red Social.</a>
    <div class="row">
        <div class=" row col-lg-9">
            <div class="col-lg-9">
                <span class="span_rem">Nombre de Usuario(Perfil): </span>
                <span class="span_rem_ans" >${Usuario}</span>
            </div>
            <div class="col-lg-3">
                <span class="span_rem">Tipo de enlace: </span>
                <span class="span_rem_ans" >${Tipo_Enlace}</span>
            </div>
            <div class="col-lg-12">
                <span class="span_rem">Enlace: </span>
                <span class="span_rem_ans" style="word-wrap: break-word;">${Enlace}</span>
            </div>
            <div class="col-lg-12">
                <span class="span_rem">Observacion del enlace: </span>
                <span class="span_rem_ans" >${Observacion_Enlace}</span>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="col-lg-12">
                <span class="span_rem">Capturo: </span>
                <span class="span_rem_ans" >${Capturo}</span>
            </div>
            <img name="nor" src="${scr}"  width="300"  data-toggle="modal" data-target="#ModalCenterRedSocial${Id_Persona+Id_Registro}">
            <div class="modal fade " id="ModalCenterRedSocial${Id_Persona+Id_Registro}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <img name="nor" src="${scr}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                </div>
            </div>  
        </div>
    </div>
    <hr>
`;
}