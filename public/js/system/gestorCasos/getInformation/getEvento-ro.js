/*-----------------------------------------------ESTE ARCHIVO DE JAVASCRIPT ES PARA OBTENER LOS DATOS DEL EVENTO PARA LA VISTA DE SOLO LECTURA------------------------------------------------------------------------*/
document.addEventListener('DOMContentLoaded', async () => {//Funcion de carga para llenado de elementos del evento
    evento = getEventotoSearch();
    data = await getEventoP(evento)
    llenarEvento(data);
    delitos = await getDelitosP(evento);
    hechos = await getHechosP(evento);
    responsables = await getResponsablesP(evento);
    vehiculos = await getVehiculosP(evento);
    if(data.Ubo_Detencion==1){
        InfoDetencion = await getInfoDetencion(data.Folio_infra);
        llenarUbicacion(InfoDetencion);
    }
    for (let i = 0; i < hechos.length; i++) {
        separadas = hechos[i].Fecha_Hora_Hecho.split(' ') 
        let formData = {
            descripcionHechos: hechos[i].Descripcion,
            fechaHechos: separadas[0],
            horaHechos: separadas[1]
        }
        insertNewRowHecho(formData);//Inserta todos los hechos del evento
    }
    for (let i = 0; i < delitos.length; i++) {
        let formData = {
            descripcionDelitos: delitos[i].Descripcion,
            Giro:delitos[i].Giro
        }
        insertNewRowDelito(formData);//Inserta todos los delitos del evento
    }
    for (let i = 0; i < vehiculos.length; i++) {
        let formData = {
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
            Estado_Veh: vehiculos[i].Estado_Veh,
            Capturo: vehiculos[i].Capturo
        }
       await insertNewRowVehiculo(formData);//Inserta todos los vehiculos del evento
    }
    for (let i = 0; i < responsables.length; i++) {
        let formData = {
            Folio_infra: responsables[i].Folio_infra,
            sexoResponsable: responsables[i].Sexo,
            complexionResponsable: responsables[i].Complexion,
            rangoEdad: responsables[i].Rango_Edad,
            descripcionResponsable: responsables[i].Descripcion_Responsable,
            Path_Imagen: responsables[i].Path_Imagen,
            tipo_arma: responsables[i].Tipo_arma,
            img_64: responsables[i].img_64,
            Estado_Res: responsables[i].Estado_Res,
            Capturo: responsables[i].Capturo
        }
        await insertNewRowResponsable(formData);//Inserta todos los involucrados del evento
    }
});
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
const insertNewRowDelito = ({  descripcionDelitos, Giro}) => {//Funcion para la insercion tabla delitos

    const table = document.getElementById('faltasDelitosTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    newRow.insertCell(0).innerHTML = descripcionDelitos;
    if(Giro==""||Giro==null){
        newRow.insertCell(1).innerHTML ="SD";

    }else{
        newRow.insertCell(1).innerHTML = Giro;
    }

}
const insertNewRowHecho = ({descripcionHechos,fechaHechos,horaHechos}) => {//Funcion para la insercion tabla hechos
    const table = document.getElementById('HechosTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    newRow.insertCell(0).innerHTML = descripcionHechos;
    newRow.insertCell(1).innerHTML = fechaHechos;
    newRow.insertCell(2).innerHTML = horaHechos;
}
const insertNewRowVehiculo= async ({Folio_infra,tipoVehiculo,marcaVehiculo,submarcaVehiculo,modeloVehiculo,placasVehiculo,colorVehiculo,descripcionVehiculo,Path_Imagen,Tipo_veh_invo,img_64,Estado_Veh,Capturo}) => {//Funcion para llenar los datos de la tabla vehiculo
    pathImagesFotos = `${base_url_js}public/files/GestorCasos/${Folio_infra}/Evento/`;
    const table = document.getElementById('VehiculoTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    newRow.insertCell(0).innerHTML = tipoVehiculo;
    newRow.insertCell(1).innerHTML = marcaVehiculo;
    newRow.insertCell(2).innerHTML = submarcaVehiculo;
    newRow.insertCell(3).innerHTML = modeloVehiculo;
    newRow.insertCell(4).innerHTML = placasVehiculo;
    newRow.insertCell(5).innerHTML = colorVehiculo;
    newRow.insertCell(6).innerHTML = descripcionVehiculo;
    if((Path_Imagen!='') && (Path_Imagen!=null)){
        srcImage = Path_Imagen;
        srcImage = srcImage.split('?')
        ruta=pathImagesFotos + srcImage[0]
        ban = await imageExists(ruta)
        if(ban==true){
        newRow.insertCell(7).innerHTML =`
                                        <div id="imageContentV_row${newRow.rowIndex}">
                                            <img name="nor" src="${pathImagesFotos + srcImage[0]}" id="imagesV_row_${newRow.rowIndex}" width="300px" data-toggle="modal" data-target="#ModalCenterVehiculo${newRow.rowIndex}">
                                            <input type="hidden" class="${newRow.rowIndex} Photo"/>
                                            <div class="modal fade " id="ModalCenterVehiculo${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <img name="nor" src="${pathImagesFotos + srcImage[0]}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                                </div>
                                            </div>
                                        </div>`;
        }else{

            if(img_64!='SD'&&img_64!=' '&&img_64!=''){
                newRow.insertCell(7).innerHTML =`
                                                <div id="imageContentV_row${newRow.rowIndex}">
                                                    <img name="nor" src="${img_64}" id="imagesV_row_${newRow.rowIndex}" width="300px" data-toggle="modal" data-target="#ModalCenterVehiculo${newRow.rowIndex}">
                                                    <input type="hidden" class="${newRow.rowIndex} Photo"/>
                                                    <div class="modal fade " id="ModalCenterVehiculo${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                            <img name="nor" src="${img_64}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                                        </div>
                                                    </div>
                                                </div>`;

            }else{
                newRow.insertCell(7).innerHTML =`
                                                <div id="imageContentV_row${newRow.rowIndex}">
                                                    <label>NO EXISTE FOTO VERIFICAR RESPALDO</label>
                                                </div>`;
            }

        }
    }else{
        newRow.insertCell(7).innerHTML =`
                                        <div id="imageContentV_row${newRow.rowIndex}">
                                        </div>`;

    }
    newRow.insertCell(8).innerHTML=Tipo_veh_invo;
    newRow.insertCell(9).innerHTML = Estado_Veh;
    newRow.insertCell(10).innerHTML = Capturo;
}
const insertNewRowResponsable =async ({Folio_infra,sexoResponsable,complexionResponsable,rangoEdad,descripcionResponsable,Path_Imagen,tipo_arma,img_64,Estado_Res,Capturo}) => {//Funcion para llenar los datos de la tabla personas
    pathImagesFotos = `${base_url_js}public/files/GestorCasos/${Folio_infra}/Evento/`;
    const table = document.getElementById('PersonaTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    newRow.insertCell(0).innerHTML = sexoResponsable;
    newRow.insertCell(1).innerHTML = rangoEdad;
    newRow.insertCell(2).innerHTML = complexionResponsable;
    newRow.insertCell(3).innerHTML = descripcionResponsable;
    if((Path_Imagen!='') && (Path_Imagen!=null)){
        srcImage = Path_Imagen;
        srcImage = srcImage.split('?')
        ruta=pathImagesFotos + srcImage[0]
        ban = await imageExists(ruta)
        
        if(ban==true){
            newRow.insertCell(4).innerHTML = `<div id="imageContentP_row${newRow.rowIndex}">
                                                <img name="nor" src="${pathImagesFotos + srcImage[0]}" id="imagesP_row_${newRow.rowIndex}" width="200px" data-toggle="modal" data-target="#ModalCenterInvolucrado${newRow.rowIndex}">
                                                <input type="hidden" class="${newRow.rowIndex} Photo"/>
                                                <div class="modal fade " id="ModalCenterInvolucrado${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                        <img name="nor" src="${pathImagesFotos + srcImage[0]}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                                    </div>
                                                </div>
                                            </div>`; 

        }else{

            if(img_64!='SD'&&img_64!=' '&&img_64!=''){
                newRow.insertCell(4).innerHTML = `<div id="imageContentP_row${newRow.rowIndex}">
                                                    <img name="nor" src="${img_64}" id="imagesP_row_${newRow.rowIndex}" width="200px" data-toggle="modal" data-target="#ModalCenterInvolucrado${newRow.rowIndex}">
                                                    <input type="hidden" class="${newRow.rowIndex} Photo"/>
                                                    <div class="modal fade " id="ModalCenterInvolucrado${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                            <img name="nor" src="${img_64}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                                        </div>
                                                    </div>
                                                </div>`; 

            }else{
                newRow.insertCell(4).innerHTML = `<div id="imageContentP_row${newRow.rowIndex}">
                                                    <label>NO EXISTE FOTO VERIFICAR RESPALDO</label>
                                                </div>`; 
            }
        }

    }else{
        newRow.insertCell(4).innerHTML = `<div id="imageContentP_row${newRow.rowIndex}"></div>`;
    }
    newRow.insertCell(5).innerHTML = tipo_arma;
    
    newRow.insertCell(6).innerHTML = Estado_Res;
    newRow.insertCell(7).innerHTML = Capturo;
 
}
/*------------------FUNCIONES PARA LA CONSULTA DE LOS DATOS ASOCIADOS AL EVENTO -------------------------------- */
const getEventotoSearch = () => {//OBTIENE EL FOLIO DEL EVENTO QUE SE ESTA VISUALIZARA
    const params = new Proxy(new URLSearchParams(window.location.search), {
        get: (searchParams, prop) => searchParams.get(prop),
      });
      // Get the value of "some_key" in eg "https://example.com/?some_key=some_value"
      let value = params.Folio_infra; // "some_value"
      return value;
}

const getEventoP = async (evento) => {//FUNCION QUE OBTINE LOS DATOS PRINCIPALES DEL EVENTO
    try {
        var myFormData = new FormData();
        myFormData.append('Folio_infra',evento)
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
const llenarEvento = async ( data ) => {//LLENA LOS DATOS EN LA PLANTILLA DE LA VISTA DE DATOS PRINCIPALES
   
    elemento_captura = document.getElementById('elemento_captura')
    folio_infra = document.getElementById('Folio_infra_principales')
    folio_911 = document.getElementById('911_principales')
    fuente= document.getElementById('Fuente_principales')
    fecha_captura = document.getElementById('fecha_captura_principales')
    fecha_recepcion = document.getElementById('fecha_recepcion_principales')
    statusp = document.getElementById('status_principales')
    zona = document.getElementById('zona_principales')
    vector = document.getElementById('vector_principales')
    colonia = document.getElementById('Colonia')
    calle = document.getElementById('Calle')
    calle2 = document.getElementById('Calle2')
    cordy = document.getElementById('cordY')
    cordx = document.getElementById('cordX')
    noext = document.getElementById('noExterior')
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
    fecha_captura.textContent = fechaLimpia
  
    fechaLimpia= await FormatoFecha(data.FechaHora_Recepcion);
    fecha_recepcion.textContent = fechaLimpia;  

    if(data.Status_Evento!="FUERA DE JURISDICCION"){
        zona.textContent = data.Zona
        vector.textContent = data.Vector
        colonia.textContent = data.Colonia
        calle.textContent = data.Calle
        calle2.textContent = (data.Calle2 == '' ? 'SD' : data.Calle2) 
        cordy.textContent = data.CoordY
        cordx.textContent = data.CoordX
        noext.textContent = (data.NoExt== '' ? 'SD' : data.NoExt) 
        cp.textContent = data.CP
    }

 
    tipo_violencia.textContent = data.Tipo_Violencia
    tipo_violenciaCS.textContent = data.CSviolencia
    if(data.Status_Seguimiento=="HABILITADO"){
        fechaLimpia= await FormatoFecha(data.FechaHora_Activacion);
        fechahora_activacion.textContent =fechaLimpia;

    }
    if(data.ClaveSeguimiento!='' && data.ClaveSeguimiento!=null){
        ClaveSeguimiento.textContent=data.ClaveSeguimiento
    }else{
        ClaveSeguimiento.textContent='SIN ASIGNAR'
    }

    document.getElementById('Responsable_Turno').textContent = (data.Responsable_Turno!=null && data.Responsable_Turno!='')?data.Responsable_Turno:"SD"; 
    document.getElementById('Turno').textContent = (data.Turno!=null && data.Turno!='')?data.Turno:"SD"; 
    document.getElementById('Semana').textContent = (data.Semana!=null && data.Semana!='')?data.Semana:"SD"; 
    document.getElementById('Unidad_Primer_R').textContent = (data.Unidad_Primer_R!=null && data.Unidad_Primer_R!='')?data.Unidad_Primer_R:"SD"; 
    document.getElementById('Informacion_Primer_R').textContent = (data.Informacion_Primer_R!=null && data.Informacion_Primer_R!='')?data.Informacion_Primer_R:"SD"; 
    document.getElementById('Acciones').textContent = (data.Acciones!=null && data.Acciones!='')?data.Acciones:"SD"; 

}
const llenarUbicacion = async(data)=>{
    document.getElementById('Ubodetencion').classList.remove('mi_hide')
    fechaLimpia= await FormatoFecha(data.Fecha_Detencion);
    document.getElementById('Fecha_Detencion').textContent =fechaLimpia;


    if(data.Detencion_Por_Info_Io==1){
        document.getElementById('Detencion_Por_Info_Io').textContent = "SI";
    }else{
        document.getElementById('Detencion_Por_Info_Io').textContent = "NO";
    }
    


    document.getElementById('Compañia').textContent = (data.Compañia!=null && data.Compañia!='')?data.Compañia:"SD";
    document.getElementById('Elementos_Realizan_D').textContent = (data.Elementos_Realizan_D!=null && data.Elementos_Realizan_D!='')?data.Elementos_Realizan_D:"SD"; 
    document.getElementById('Nombres_Detenidos').textContent = (data.Nombres_Detenidos!=null && data.Nombres_Detenidos!='')?data.Nombres_Detenidos:"SD";



    document.getElementById('Estado').textContent = (data.Estado!=null && data.Estado!='')?data.Estado:"SD"; 
    document.getElementById('Municipio').textContent = (data.Municipio!=null && data.Municipio!='')?data.Municipio:"SD"; 
    document.getElementById('Colonia_Det').textContent = (data.Colonia!=null && data.Colonia!='')?data.Colonia:"SD"; 
    document.getElementById('Calle_Det').textContent = (data.Calle!=null && data.Calle!='')?data.Calle:"SD"; 
    document.getElementById('Calle_Det2').textContent = (data.Calle2!=null && data.Calle2!='')?data.Calle2:"SD"; 
    document.getElementById('CP_Det').textContent = (data.CP!=null && data.CP!='')?data.CP:"SD"; 
    document.getElementById('no_Ext_Det').textContent = (data.NumExt!=null && data.NumExt!='')?data.NumExt:"SD"; 
    document.getElementById('no_Int_Det').textContent = (data.NumInt!=null && data.NumInt!='')?data.NumInt:"SD"; 
    document.getElementById('cordX_Det').textContent = (data.CoordX!=null && data.CoordX!='')?data.CoordX:"SD"; 
    document.getElementById('cordY_Det').textContent = (data.CoordY!=null && data.CoordY!='')?data.CoordY:"SD"; 
    document.getElementById('Link_Ubicacion_Det').textContent = (data.Link_Ubicacion!=null && data.Link_Ubicacion!='')?data.Link_Ubicacion:"SD"; 
    document.getElementById('Observacion_Ubicacion_Det').textContent = (data.Observaciones_Detencion!=null && data.Observaciones_Detencion!='')?data.Observaciones_Detencion:"SD";



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
const FormatoFecha =async ($fecha)=>{//FUNCION PARA SACAR FROMATO DE FECHA
    let cad,cad2
    let dateCaptura = new Date($fecha), options = {weekday: 'long', month: 'long', day: 'numeric', year: 'numeric',hour:'numeric',minute:'numeric',hour12: false};
    cad=dateCaptura.toLocaleString('es-ES', options)
    cad=cad.toUpperCase();
    let separadas = cad.split(',')
    cad2=separadas[0]+separadas[1]+" A LAS"+separadas[2];

    return cad2;
}