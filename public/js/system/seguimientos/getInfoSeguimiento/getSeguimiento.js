/*-------------ESTE ARCHIVO DE JAVASCRIPT ES PARA OBTENER LOS DATOS DEL SEGUIMIENTO ------------*/
const myFormData =  new FormData();
var banderaPdf;
var Seguimiento;
var PersonasSelect = [];
var VehiculosSelect = [];
document.addEventListener('DOMContentLoaded', async () => {//FUNCION PARA EL LLENADO DE ELEMENTOS DEL SEGUIMIENTO
    radioHabilitado = document.getElementsByName('Question');
    radioHabilitado[0].addEventListener('change',showHabilitado);
    radioHabilitado[1].addEventListener('change',showHabilitado); 
    radioHabilitado[2].addEventListener('change',showHabilitado); 
    await getInfoRedes()
    await getRemisiones();
    await getInfoEventos()
    Seguimiento = getSeguimientotoSearch();
    data = await getSeguimiento(Seguimiento);
    
    if(data.Alto_Impacto!=document.getElementById("ALTO_IMPACTO").value && document.getElementById("ADMIN").value!= 1){
        document.getElementById('contenedor_red').classList.add('mi_hide');
    }
    let Eventos = await getEventosRelacionados(Seguimiento);
    let Delitos = await getDelitosRelacionados(Seguimiento);
    llenarSeguimiento(data);
    let Personas = await getPersonas(Seguimiento);
    let Vehiculos = await getVehiculos(Seguimiento);
    for (let i = 0; i < Eventos.length; i++) {
        let formData = {
            Folio_infra: Eventos[i].Folio_infra,
            Folio_911: Eventos[i].Folio_911,
            delitos_concat: Eventos[i].delitos_concat,
            Ubicacion: Eventos[i].Ubicacion,
            Id_Seguimiento: Eventos[i].Id_Seguimiento
        }
        insertRowEvento(formData); //INSERTA EN LA VISTA TODOS LOS EVENTOS RELACIONADOS AL SEGUIMIENTO
    }
    for (let i = 0; i < Delitos.length; i++) {
        let formData = {
            Delito: Delitos[i].Delito
        }
        insertRowDelito(formData); //INSERTA EN LA VISTA TODOS LOS DELITOS RELACIONADOS AL SEGUIMIENTO
    }
    let i=0,j=0;
    let consultaPersonas=[];
    let consultaVehiculos=[];

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
            Rol : Persona.Rol,
            Capturo : Persona.Capturo,
            Foto : Persona.Foto,
            Img_64 : Persona.Img_64
        }
        consultaPersonas[i]=Persona.Id_Persona;
        i++;
        await InsertgetPersona(formDataPersona);//Inserta todas las personas del seguimiento
    }
   
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
        consultaVehiculos[j]=Vehiculo.Id_Vehiculo;
        j++;
       await InsertgetVehiculos(formDataVehiculo);//Inserta todos los vehiculos del seguimiento
    }

    if(document.getElementById('Question2').checked){
        let hijos = await buscaHijos(data.Id_Seguimiento);   
        if(hijos.length>0){
            //console.log(hijos)
            //Panel tiene folios de eventos delictivos 
            for (let i = 0; i < hijos.length; i++) {
                await llenarInfoHijo(hijos[i].Id_Seguimiento);
            }

        }
    }
    await readPersonasVista();
    await readVehiculosVista();
  
    MostrarTabDomicilio();//oculta o muestra la tab de domicilios 
    MostrarTabAntecedentes();
    MostrarTabForencias();
    MostrarTabRedesSociales();
   
    
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
    let id_seguimiento_persona = document.getElementById('id_seguimiento_persona');
    let id_seguimiento_vehiculo = document.getElementById('id_seguimiento_vehiculo');
    let id_seguimiento_domicilio = document.getElementById('id_seguimiento_domicilio');
    let radioAltoImpacto = document.getElementsByName('Alto_Imp_Si_No');
    
    //EMPIEZA A LLENAR LA INFORMACION EN LA VISTA
    id_seguimiento_persona.value=data.Id_Seguimiento;//VISTA DE PERSONA 
    id_seguimiento_persona.disabled = true;

    id_seguimiento_vehiculo.value=data.Id_Seguimiento;//VISTA DE VEHICULO 
    id_seguimiento_vehiculo.disabled = true;

    id_seguimiento_domicilio.value=data.Id_Seguimiento;//VISTA DE DOMICILIO 
    id_seguimiento_domicilio.disabled = true;

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
            div.innerHTML = `<div class="d-flex justify-content-end">
                                <span onclick="deleteImageFotoGD()" class="deleteFile">X</span>
                            </div>
                            <img name="nor" src="${ruta}" id="imagesGD" width="300px" data-toggle="modal" data-target="#ModalCenterGrupoDelictivo">
                            <input type="hidden" class="Photo"/>
                            <div class="modal fade " id="ModalCenterGrupoDelictivo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <img name="nor" src="${ruta}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                </div>
                            </div>`;
    
        }else{
            div.innerHTML = `<div class="d-flex justify-content-end">
                                <span onclick="deleteImageFotoGD()" class="deleteFile">X</span>
                            </div>
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
        banderaPdf =data.Nombre_PDF
    }else{
        banderaPdf = "SD"
    }
    
    if(data.Alto_Impacto == 1){
        radioAltoImpacto[0].checked = true;
        radioAltoImpacto[1].checked = false;
    }else{
        radioAltoImpacto[0].checked = false;
        radioAltoImpacto[1].checked = true;
    }
    if(data.Tipo_Grupo == "PERSONA"){
        document.getElementById('Question1').checked = true;
        showHabilitado();
    }else{
        if(data.Tipo_Grupo == "EVENTO DELICTIVO"){
            document.getElementById('Question3').checked = true;
            showHabilitado();
            document.getElementById('Id_red').value = data.Llave_Padre;
            document.getElementById('consulta').checked = (data.Consultado==1)?true:false;
        }else{
            document.getElementById('Question2').checked = true;
            showHabilitado();
        }
        
    }
}

const readPersonasVista = async() => {//lee los datos de la tabla personas y genera una estructura deacuerdo a los datos contenido es la tabla
    let table = document.getElementById('PersonaTable');
    PersonasSelect = [];
    for (let i = 1; i < table.rows.length; i++) {
        PersonasSelect.push({
                Id_Persona : table.rows[i].cells[0].innerHTML,
                Nombre: table.rows[i].cells[1].innerHTML,
                Ap_Paterno: table.rows[i].cells[2].innerHTML,
                Ap_Materno: table.rows[i].cells[3].innerHTML,
                Id_Seguimiento: table.rows[i].cells[15].innerHTML
           
        });
    }
    ///console.log(PersonasSelect)
}
const readVehiculosVista = async() => {//lee los datos de la tabla personas y genera una estructura deacuerdo a los datos contenido es la tabla
    let table = document.getElementById('VehiculoTable');
    VehiculosSelect = [];
    for (let i = 1; i < table.rows.length; i++) {
        VehiculosSelect.push({
            Id_Vehiculo : table.rows[i].cells[0].innerHTML,
            Placas: table.rows[i].cells[1].innerHTML,
            Marca: table.rows[i].cells[2].innerHTML,
            Submarca: table.rows[i].cells[3].innerHTML,
            Color: table.rows[i].cells[4].innerHTML
           
        });
    }
    ///console.log(VehiculosSelect)
}
const buscaHijos = async (seguimiento) => {//FUNCION QUE OBTIENE LOS EVENTOS RELACIONADOS AL SEGUIMIENTO
    try {
        var myFormData = new FormData();
        myFormData.append('Id_seguimiento',seguimiento);
        const response = await fetch(base_url_js + 'Seguimientos/getHijosRed', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const llenarInfoHijo = async (Seguimiento,caso) =>{
    let Eventos, Personas, Vehiculos
    switch(caso){
        case "PERSONAS":
            Personas = await getPersonas(Seguimiento);
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
                    Rol : Persona.Rol,
                    Capturo : Persona.Capturo,
                    Foto : Persona.Foto,
                    Img_64 : Persona.Img_64
                }
                await InsertgetPersona(formDataPersona);//Inserta todas las personas del seguimiento
            }     
        
        break;
        case "VEHICULOS": 
            Vehiculos = await getVehiculos(Seguimiento);
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
        
            await InsertgetVehiculos(formDataVehiculo);//Inserta todos los vehiculos del seguimiento
            }
        break;
        default: 
            Eventos = await getEventosRelacionados(Seguimiento);
            Personas = await getPersonas(Seguimiento);
            Vehiculos = await getVehiculos(Seguimiento);
            for (let i = 0; i < Eventos.length; i++) {
                let formData = {
                    Folio_infra: Eventos[i].Folio_infra,
                    Folio_911: Eventos[i].Folio_911,
                    delitos_concat: Eventos[i].delitos_concat,
                    Ubicacion: Eventos[i].Ubicacion,
                    Id_Seguimiento: Eventos[i].Id_Seguimiento
                }
                insertRowEvento(formData); //INSERTA EN LA VISTA TODOS LOS EVENTOS RELACIONADOS AL SEGUIMIENTO
            }
        
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
        
            await InsertgetVehiculos(formDataVehiculo);//Inserta todos los vehiculos del seguimiento
            }

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
                    Rol : Persona.Rol,
                    Capturo : Persona.Capturo,
                    Foto : Persona.Foto,
                    Img_64 : Persona.Img_64
                }
                await InsertgetPersona(formDataPersona);//Inserta todas las personas del seguimiento
            }
        break;
    }
    
}

/*-----------------------------------FUNCIONES DE PARA LLENAR LOS DATOS DE LA TABLA DE SEGUIMIENTO------------------ */
const insertRowEvento = ({Folio_infra,Folio_911,delitos_concat,Ubicacion,Id_Seguimiento}) => {//Funcion para llenar tabla eventos relacionados de seguimiento
    const table = document.getElementById('EventoTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    newRow.insertCell(0).innerHTML = Folio_infra;
    newRow.insertCell(1).innerHTML = Folio_911;
    newRow.insertCell(2).innerHTML = Ubicacion;
    newRow.insertCell(3).innerHTML = delitos_concat;
    newRow.insertCell(4).innerHTML = `<button type="button" class="btn btn-ssc" value="-" onclick="deleteRowEvento(this,EventoTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
    newRow.insertCell(5).innerHTML = Id_Seguimiento;
    newRow.cells[5].style.display = "none";
}

const insertRowDelito = ({Delito}) => {//Funcion para llenar tabla de delitos asociados al seguimiento
    let table = document.getElementById('DelitosTable').getElementsByTagName('tbody')[0];//SELECCIONA LA TABLA
    let newRow = table.insertRow(table.length);//INSERTA EL NUEVO ROW 
    newRow.insertCell(0).innerHTML = Delito;
    newRow.insertCell(1).innerHTML = `<button type="button" class="btn btn-ssc" value="-" onclick="deleteRow(this,DelitosTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
}

const RecargaPrincipales=async()=>{
    document.getElementById('filePDF').innerText='';
    data = await getSeguimiento(Seguimiento);
    llenarSeguimiento(data);
}
