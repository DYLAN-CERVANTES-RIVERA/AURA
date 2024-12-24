const myFormData =  new FormData();
var Persona_Entrevista;
document.addEventListener('DOMContentLoaded', async () => {
    await CatalogoTipo()
    Persona_Entrevista = await getEntrevistaSearch();
    let data = await getDatosPersonaEntrevistas(Persona_Entrevista);
    await GetStatusTareas(Persona_Entrevista)
    await RecargaGrupoDelictivoSeguimiento();
    await RecargaSelectIndicativo()
    llenarDatosPrincipales(data);

    let Entrevistas = await getEntrevistas(Persona_Entrevista);
    if(Entrevistas!=undefined){
        for await(let Entrevista of Entrevistas){
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
            await insertNewRowTablaEntrevista(formDataEntrevista);//Inserta todas las personas del seguimiento
        }
    }
    let Forensias=await getForensias(Persona_Entrevista);
    if(Forensias!=undefined){
        for await(let Forensia of Forensias){
            let formDataForensia = {
                Id_Forensia_Entrevista: Forensia.Id_Forensia_Entrevista,
                Id_Persona_Entrevista : Forensia.Id_Persona_Entrevista,
                Id_Dato: Forensia.Id_Dato,
                Tipo_Relacion: Forensia.Tipo_Relacion,
                Descripcion_Forensia : Forensia.Descripcion_Forensia,
                Tipo_Dato: Forensia.Tipo_Dato,
                Dato_Relevante: Forensia.Dato_Relevante,
                Capturo : Forensia.Capturo,
                Foto : Forensia.Foto,
                Img_64 : Forensia.Img_64
            }
        await InsertgetForensia(formDataForensia);//Inserta todos las forensias de las personas del seguimiento
        }
    }
    let Ubicaciones=await getUbicaciones(Persona_Entrevista);
    if(Ubicaciones!=undefined){
        for await(let Ubicacion of Ubicaciones){
            let formDataUbicacion = {
                Id_Ubicaciones_Entrevista: Ubicacion.Id_Ubicaciones_Entrevista ,
                Id_Persona_Entrevista: Ubicacion.Id_Persona_Entrevista,
                Id_Dato: Ubicacion.Id_Dato,
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
                Img_64 : Ubicacion.Img_64,
                Tipo_Relacion: Ubicacion.Tipo_Relacion
            }
        await InsertgetUbicacion(formDataUbicacion);//Inserta todos las Ubicaciones de las personas del seguimiento
        }
    }
    let RedesSociales=await getRedesSociales(Persona_Entrevista);
    for await(let RedSocial of RedesSociales){
        let formDataRedSocial = {
            Id_Registro: RedSocial.Id_Registro ,
            Id_Persona_Entrevista : RedSocial.Id_Persona_Entrevista,
            Id_Dato: RedSocial.Id_Dato,
            Usuario: RedSocial.Usuario,
            Enlace: RedSocial.Enlace,
            Tipo_Enlace: RedSocial.Tipo_Enlace,
            Observacion_Enlace: RedSocial.Observacion_Enlace,
            Capturo: RedSocial.Capturo,
            Foto_Nombre: RedSocial.Foto_Nombre,
            Img_64: RedSocial.Img_64,
            Tipo_Relacion: RedSocial.Tipo_Relacion
        }  
       await InsertgetRedSocial(formDataRedSocial);//Inserta todos los datos de redes sociales de las personas del seguimiento
    }
    MostrarTabForensias();
    let Estados = await getAllEstados();
    var select2 = document.getElementById("Estado");
    for (let i = 0; i < Estados.length; i++) {
        option = document.createElement("option");
        option.text = Estados[i]['Estado'];
        option.value = Estados[i]['Estado'];
        select2.add(option);
    }
    await RecargaSelects();
});
async function  RecargaSelects() {//REFRESCA EL SELECTOR DE TODAS LAS TABS
    await changeTipoDato();
    await changeTipoUbicacion();
    await changeTipoRedSocial();
    await changeDatoEspecifico();
}
const changeTipoDato = async()=>{//FUNCION QUE CAMBIA EL TIPO DE DATO AL QUE SE LE ASIGNARA EL DOMICILIO
    let radioTipo = document.getElementsByName('tipo_dato_dato');
    let select = document.getElementById("DatoSelect");
    while (select.options.length > 0) {//ACTUALIZACION DE SELECT POR SI HAY MODIFICACION EN LAS TABLAS
        select.remove(0);
    }
    if(radioTipo[0].checked){//ENTREVISTA
            select.disabled = false;
            let Entrevistas = await getEntrevistas(Persona_Entrevista);
            option = document.createElement("option");
            option.text = "SELECCIONE EL ID LA ENTREVISTA ASOCIADA";
            option.value = -1;
            select.add(option);

            for (let i = 0; i < Entrevistas.length; i++) {
                option = document.createElement("option");
                option.text = "ID: "+Entrevistas[i]['Id_Entrevista']+" EXTRACTO ENTREVISTA: "+Entrevistas[i]['Entrevista'].substr(0, 55)+"... ALIAS REFERIDOS: "+Entrevistas[i]['Alias_Referidos'];
                option.value = Entrevistas[i]['Id_Entrevista'];
                select.add(option);
            }
    } 
    
    if(radioTipo[1].checked){//DATO
        select.disabled = false;
        let Forensias = await getForensias(Persona_Entrevista);
        option = document.createElement("option");
        option.text = "SELECCIONE EL ID ASOCIADO";
        option.value = -1;
        select.add(option);

        for (let i = 0; i < Forensias.length; i++) {
            option = document.createElement("option");
            option.text = "ID: "+Forensias[i]['Id_Forensia_Entrevista']+" EXTRACTO DATO: "+Forensias[i]['Descripcion_Forensia'].substr(0, 55)+"... ";
            option.value = Forensias[i]['Id_Forensia_Entrevista'];
            select.add(option);
        }
    }
    if(radioTipo[2].checked){//NINGUNO
        select.disabled = true;
        option = document.createElement("option");
        option.text = "";
        option.value = -1;
        select.add(option);
    }

}
const changeTipoRedSocial = async()=>{//FUNCION QUE CAMBIA EL TIPO DE DATO AL QUE SE LE ASIGNARA EL DOMICILIO
    let radioTipo = document.getElementsByName('tipo_dato_red_social');
    let select = document.getElementById("RedSocialSelect");
    while (select.options.length > 0) {//ACTUALIZACION DE SELECT POR SI HAY MODIFICACION EN LAS TABLAS
        select.remove(0);
    }
    if(radioTipo[0].checked){//ENTREVISTA
            select.disabled = false;
            let Entrevistas = await getEntrevistas(Persona_Entrevista);
            option = document.createElement("option");
            option.text = "SELECCIONE EL ID LA ENTREVISTA ASOCIADA";
            option.value = -1;
            select.add(option);

            for (let i = 0; i < Entrevistas.length; i++) {
                option = document.createElement("option");
                option.text = "ID: "+Entrevistas[i]['Id_Entrevista']+" EXTRACTO ENTREVISTA: "+Entrevistas[i]['Entrevista'].substr(0, 55)+"... ALIAS REFERIDOS: "+Entrevistas[i]['Alias_Referidos'];
                option.value = Entrevistas[i]['Id_Entrevista'];
                select.add(option);
            }
    } 
    
    if(radioTipo[1].checked){//DATO
        select.disabled = false;
        let Forensias = await getForensias(Persona_Entrevista);
        option = document.createElement("option");
        option.text = "SELECCIONE EL ID ASOCIADO";
        option.value = -1;
        select.add(option);

        for (let i = 0; i < Forensias.length; i++) {
            option = document.createElement("option");
            option.text = "ID: "+Forensias[i]['Id_Forensia_Entrevista']+" EXTRACTO DATO: "+Forensias[i]['Descripcion_Forensia'].substr(0, 55)+"... ";
            option.value = Forensias[i]['Id_Forensia_Entrevista'];
            select.add(option);
        }
    }
    if(radioTipo[2].checked){//NINGUNO
        select.disabled = true;
        option = document.createElement("option");
        option.text = "";
        option.value = -1;
        select.add(option);
    }

}

const changeTipoUbicacion = async()=>{//FUNCION QUE CAMBIA EL TIPO DE DATO AL QUE SE LE ASIGNARA EL DOMICILIO
    let radioTipo = document.getElementsByName('tipo_dato_ubicacion');
    let select = document.getElementById("UbicacionSelect");
    while (select.options.length > 0) {//ACTUALIZACION DE SELECT POR SI HAY MODIFICACION EN LAS TABLAS
        select.remove(0);
    }
    if(radioTipo[0].checked){//ENTREVISTA
            select.disabled = false;
            let Entrevistas = await getEntrevistas(Persona_Entrevista);
            option = document.createElement("option");
            option.text = "SELECCIONE EL ID LA ENTREVISTA ASOCIADA";
            option.value = -1;
            select.add(option);

            for (let i = 0; i < Entrevistas.length; i++) {
                option = document.createElement("option");
                option.text = "ID: "+Entrevistas[i]['Id_Entrevista']+" EXTRACTO ENTREVISTA: "+Entrevistas[i]['Entrevista'].substr(0, 55)+"... ALIAS REFERIDOS: "+Entrevistas[i]['Alias_Referidos'];
                option.value = Entrevistas[i]['Id_Entrevista'];
                select.add(option);
            }
    } 
    
    if(radioTipo[1].checked){//DATO
        select.disabled = false;
        let Forensias = await getForensias(Persona_Entrevista);
        option = document.createElement("option");
        option.text = "SELECCIONE EL ID ASOCIADO";
        option.value = -1;
        select.add(option);

        for (let i = 0; i < Forensias.length; i++) {
            option = document.createElement("option");
            option.text = "ID: "+Forensias[i]['Id_Forensia_Entrevista']+" EXTRACTO DATO: "+Forensias[i]['Descripcion_Forensia'].substr(0, 55)+"... ";
            option.value = Forensias[i]['Id_Forensia_Entrevista'];
            select.add(option);
        }
    }
    if(radioTipo[2].checked){//NINGUNO
        select.disabled = true;
        option = document.createElement("option");
        option.text = "";
        option.value = -1;
        select.add(option);
    }

}
async function  RecargaGrupoDelictivoSeguimiento() {//REFRESCA EL SELECTOR DEL CON EL GRUPO DELICTIVO   
    // Obtener referencia al elemento select
    var select = document.getElementById("Id_Banda_Seguimiento");
    let GruposDelicitivos = await getGrupoDelictivoSeguimiento();
    // Generar las opciones del select
    for (var i = 0; i < GruposDelicitivos.length; i++) {
        option = document.createElement("option");
        option.text = GruposDelicitivos[i]['Nombre_grupo_delictivo'];
        option.value = GruposDelicitivos[i]['Id_Seguimiento'];
        select.add(option);
    }
}
const getGrupoDelictivoSeguimiento = async () => {//FUNCION QUE OBTINE LOS DATOS PRINCIPALES DEL SEGUIMIENTO
    try {
        const response = await fetch(base_url_js + 'Entrevistas/getGrupoDelictivoSeguimiento', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
} 

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
    let Id_Banda_Seguimiento = document.getElementById('Id_Banda_Seguimiento');
    let zona = document.getElementById('zona');

    if(data.Id_Seguimiento!=null&&data.Id_Seguimiento!=0){
        console.log(data.Id_Seguimiento)
        Id_Banda_Seguimiento.value=data.Id_Seguimiento;
        document.getElementById('captura_sic').classList.remove('mi_hide');
        if(data.Capturado_Seguimiento=='SI'){
            document.getElementById('id_sic_2').checked=false;
            document.getElementById('id_sic_1').checked=true;
        }else{
            document.getElementById('id_sic_2').checked=true;
            document.getElementById('id_sic_1').checked=false;
        }
    }

    Id_Persona_Entrevista.value = data.Id_Persona_Entrevista;
    FechaHora_Creacion.value = data.FechaHora_Creacion;
    Capturo.value = data.Capturo;
    Nombre.value = data.Nombre;
    Ap_Paterno.value = data.Ap_Paterno;
    Ap_Materno.value = data.Ap_Materno;
    CURP.value = data.CURP;
    Telefono.value = data.Telefono;
    Fecha_Nacimiento.value = data.Fecha_Nacimiento;
    Edad.value = data.Edad;
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
    zona.value = data.Zona;
    Asignado.value = (data.Asignado_a=='SD'||data.Asignado_a.trim()=='')?'':data.Asignado_a;
    if(data.Foto!='SD'){
        let ruta = `${base_url_js}public/files/Entrevistas/${data.Id_Persona_Entrevista}/${data.Foto}`;
        let ban = await imageExists(ruta)
        let div = document.getElementById('imageContentDetenido');
        if(ban==true){
            ruta = ruta+'?nocache='+getRandomInt(50);
            div.innerHTML = `<div class="d-flex justify-content-end">
                                <span onclick="deleteImageFotoDetenido()" class="deleteFile">X</span>
                            </div>
                            <img name="nor" src="${ruta}" id="imagesDetenido" width="300px" data-toggle="modal" data-target="#ModalCenterDetenidoEntrevista">
                            <input type="hidden" class="Photo"/>
                            <div class="modal fade " id="ModalCenterDetenidoEntrevista" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <img name="nor" src="${ruta}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                </div>
                            </div>`;
    
        }else{
            div.innerHTML = `<div class="d-flex justify-content-end">
                                <span onclick="deleteImageFotoDetenido()" class="deleteFile">X</span>
                            </div>
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
    await getRemisiones();
    if(data.Relevancia =='1'){
        document.getElementById('id_relevancia_1').checked = true;
        document.getElementById('id_relevancia_2').checked = false;
    }else{
        document.getElementById('id_relevancia_1').checked = false;
        document.getElementById('id_relevancia_2').checked = true;
    }
}
function getRandomInt(max) {//FUNCION QUE RETORNA UN NUMERO ENTERO RANDOM
    return Math.floor(Math.random() * max);
  }