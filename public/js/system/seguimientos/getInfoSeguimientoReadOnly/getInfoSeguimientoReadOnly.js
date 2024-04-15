/*-------------ESTE ARCHIVO DE JAVASCRIPT ES PARA OBTENER LOS DATOS DEL SEGUIMIENTO ------------*/
const myFormData =  new FormData();
var Seguimiento;
document.addEventListener('DOMContentLoaded', async () => {//FUNCION PARA EL LLENADO DE ELEMENTOS DEL EVENTO
    Seguimiento = getSeguimientotoSearch();
    data = await getSeguimiento(Seguimiento);
    llenarSeguimiento(data);
    let Eventos = await getEventosRelacionados(Seguimiento);
    let Delitos = await getDelitosRelacionados(Seguimiento);
    let Personas = await getPersonas(Seguimiento);
    let Vehiculos = await getVehiculos(Seguimiento);
    for (let i = 0; i < Eventos.length; i++) {
        let formData = {
            Folio_infra: Eventos[i].Folio_infra,
            Folio_911: Eventos[i].Folio_911,
            delitos_concat: Eventos[i].delitos_concat,
            Ubicacion: Eventos[i].Ubicacion
        }
        insertRowEvento(formData); //INSERTA EN LA VISTA TODOS LOS EVENTOS RELACIONADOS
    }
    for (let i = 0; i < Delitos.length; i++) {
        let formData = {
            Delito: Delitos[i].Delito
        }
        insertRowDelito(formData); //INSERTA EN LA VISTA TODOS LOS EVENTOS RELACIONADOS
    }
    i=0;
    j=0;
    if(Personas.length>=1){
        for await(let Persona of Personas){
            let formDataPersona = {
                Id_Persona : Persona.Id_Persona,
                Id_Seguimiento: Seguimiento,
                Nombre : Persona.Nombre,
                Ap_Paterno : Persona.Ap_Paterno,
                Ap_Materno : Persona.Ap_Materno,
                Genero : Persona.Genero,
                Edad : Persona.Edad,
                Fecha_Nacimiento : Persona.Fecha_Nacimiento,
                Telefono : Persona.Telefono,
                Alias : Persona.Alias,
                Curp : Persona.Curp,
                Remisiones : Persona.Remisiones,
                Capturo : Persona.Capturo,
                Foto : Persona.Foto,
                Img_64 : Persona.Img_64
            }
            i++;
            await InsertVistaPersona(formDataPersona,i);//Inserta todas las personas del seguimiento
            
        }
    }else{
        document.getElementById('li-Personas').classList.add('mi_hide');
        document.getElementById('Personas0').classList.add('mi_hide');
    }
   
   if(Vehiculos.length>=1){
    for await(let Vehiculo of Vehiculos){
        let formDataVehiculo = {
            Id_Vehiculo : Vehiculo.Id_Vehiculo,
            Id_Seguimiento: Seguimiento,
            Placas : Vehiculo.Placas,
            Marca : Vehiculo.Marca,
            Submarca : Vehiculo.Submarca,
            Color : Vehiculo.Color,
            Modelo : Vehiculo.Modelo,
            Nombre_Propietario : Vehiculo.Nombre_Propietario,
            Nivs : Vehiculo.Nivs,
            InfoPlaca : Vehiculo.InfoPlaca,
            Capturo : Vehiculo.Capturo,
            Foto : Vehiculo.Foto,
            Img_64 : Vehiculo.Img_64
        }
        j++;
       await InsertVistaVehiculos(formDataVehiculo,j);//Inserta todos los vehiculos del seguimiento
    }
   }else{
        document.getElementById('li-vehiculos').classList.add('mi_hide');
        document.getElementById('vehiculos0').classList.add('mi_hide');
   }
   
    
});
/*---------------------------FUNCIONES PARA LA OBTENCION DE LA INFORMACION DEL SEGUIMIENTO PRINCIPAL------------------- */
const getSeguimientotoSearch = () => {//OBTIENE EL ID DEL SEGUIMIENTO QUE SE ESTA EDITARA
    const params = new Proxy(new URLSearchParams(window.location.search),
     {
        get: (searchParams,
             prop) => searchParams.get(prop),
      });
      // Get the value of "some_key" in eg "https://example.com/?some_key=some_value"
      let value = params.Id_seguimiento; // "some_value"
      return value;
}
const getSeguimiento = async (seguimiento) => {//FUNCION QUE OBTINE LOS DATOS PRINCIPALES DEL SEGUIMIENTO
    try {
        var myFormData = new FormData();
        myFormData.append('Id_seguimiento',seguimiento);
        const response = await fetch(base_url_js + 'Seguimientos/getPrincipales', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
} 
const getEventosRelacionados = async (seguimiento) => {//FUNCION QUE OBTIENE LOS EVENTOS RELACIONADOS AL SEGUIMIENTO
    try {
        var myFormData = new FormData();
        myFormData.append('Id_seguimiento',seguimiento);
        const response = await fetch(base_url_js + 'Seguimientos/getEventosRelacionados', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}

const getDelitosRelacionados = async (seguimiento) => {//FUNCION QUE OBTIENE LOS EVENTOS RELACIONADOS AL SEGUIMIENTO
    try {
        var myFormData = new FormData();
        myFormData.append('Id_seguimiento',seguimiento);
        const response = await fetch(base_url_js + 'Seguimientos/getDelitosRelacionados', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
/*-----------------------------------FUNCIONES DE PARA LLENAR LOS DATOS DEL SEGUIMIENTO------------------ */
const llenarSeguimiento = async ( data ) => {//LLENA LOS DATOS EN LA PLANTILLA DE LA VISTA DE DATOS PRINCIPALES DEL SEGUIMIENTO
    let id_seguimiento_principales=document.getElementById('id_seguimiento_principales');
    let elemento_captura = document.getElementById('captura_principales');
    let fechahora_captura_principales = document.getElementById('fechahora_captura_principales');
    let nombre_grupo = document.getElementById('nombre_grupo');
    let peligrosidad = document.getElementById('peligrosidad');
    let modus_operandi = document.getElementById('MO');
    let observaciones = document.getElementById('observaciones');
    

    id_seguimiento_principales.value=data.Id_Seguimiento;
    elemento_captura.value=data.Elemento_Captura;
    fechahora_captura_principales.value=data.FechaHora_Creacion;
    elemento_captura.disabled = true;
    fechahora_captura_principales.disabled = true;
    id_seguimiento_principales.disabled = true;
    nombre_grupo.value=data.Nombre_grupo_delictivo;

    peligrosidad.value=data.Peligrosidad;
    modus_operandi.value=data.Modus_operandi;
    observaciones.value=data.Observaciones;
    if(data.Foto_grupo_delictivo!='SD'){
        let ruta = `${base_url_js}public/files/Seguimientos/${data.Id_Seguimiento}/${data.Foto_grupo_delictivo}`;
        let ban = await imageExists(ruta)
        let div = document.getElementById('imageContentGD');
        if(ban==true){
            div.innerHTML = `
                            <img name="nor" src="${ruta}" id="imagesGD" width="300px" data-toggle="modal" data-target="#ModalCenterGrupoDelictivo">
                            <input type="hidden" class="Photo"/>
                            <div class="modal fade " id="ModalCenterGrupoDelictivo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <img name="nor" src="${ruta}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                </div>
                            </div>`;
    
        }else{
            div.innerHTML = `
                            <img name="nor" src="${data.Img_64}" id="imagesGD" width="300px" data-toggle="modal" data-target="#ModalCenterGrupoDelictivo">
                            <input type="hidden" class="Photo"/>
                            <div class="modal fade " id="ModalCenterGrupoDelictivo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <img name="nor" src="${data.Img_64}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                </div>
                            </div>`;
            BanderaRecuperacion=true
        }
    }
    if(data.Nombre_PDF!='SD'){
        let rutaPDf = `${base_url_js}public/files/Seguimientos/${data.Id_Seguimiento}/${data.Nombre_PDF}`+'?nocache='+getRandomInt(50);
        document.getElementById('viewPDF').classList.remove('mi_hide');
        document.getElementById('viewPDF').innerHTML = `
        <embed src="${rutaPDf}" width="100%" height="600"  type="application/pdf">
        `;
    }

}

/*-----------------------------------FUNCIONES DE PARA LLENAR LOS DATOS DE LA TABLA DE SEGUIMIENTO------------------ */
const insertRowEvento = ({Folio_infra,Folio_911,delitos_concat,Ubicacion}) => {//Funcion para llenar tabla de hechos
    const table = document.getElementById('EventoTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    newRow.insertCell(0).innerHTML = Folio_infra;
    newRow.insertCell(1).innerHTML = Folio_911;
    newRow.insertCell(2).innerHTML = Ubicacion;
    newRow.insertCell(3).innerHTML = delitos_concat;
}

const insertRowDelito = ({Delito}) => {//Funcion para llenar tabla de hechos
    let table = document.getElementById('DelitosTable').getElementsByTagName('tbody')[0];//SELECCIONA LA TABLA
    let newRow = table.insertRow(table.length);//INSERTA EL NUEVO ROW 
    newRow.insertCell(0).innerHTML = Delito;
}

const RecargaPrincipales=async()=>{
    document.getElementById('filePDF').innerText='';
    data = await getSeguimiento(Seguimiento);
    llenarSeguimiento(data);
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
function getRandomInt(max) {//FUNCION QUE RETORNA UN NUMERO ENTERO RANDOM
    return Math.floor(Math.random() * max);
}

const getDomiciliosOneRegister = async (Id_Dato,Tipo_Entidad) => { //Funcion que realizar peticion para obtener los datos de las entrevistas del evento
    try {
        myFormData.append('Id_Dato',Id_Dato)
        myFormData.append('Tipo_Entidad',Tipo_Entidad)
        const response = await fetch(base_url_js + 'Seguimientos/getDomiciliosOneRegister', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const getAntecedentesOneRegister = async (Id_Dato,Tipo_Entidad) => { //Funcion que realizar peticion para obtener los datos de las entrevistas del evento
    try {
        myFormData.append('Id_Dato',Id_Dato)
        myFormData.append('Tipo_Entidad',Tipo_Entidad)
        const response = await fetch(base_url_js + 'Seguimientos/getAntecedentesOneRegister', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const getForenciasOneRegister = async (Id_Persona) => { //Funcion que realizar peticion para obtener los datos de las entrevistas del evento
    try {
        myFormData.append('Id_Persona',Id_Persona)
        const response = await fetch(base_url_js + 'Seguimientos/getForenciasOneRegister', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}

const getRedesSocialesOneRegister = async (Id_Persona) => { //Funcion que realizar peticion para obtener los datos de las entrevistas del evento
    try {
        myFormData.append('Id_Persona',Id_Persona)
        const response = await fetch(base_url_js + 'Seguimientos/getRedesSocialesOneRegister', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}