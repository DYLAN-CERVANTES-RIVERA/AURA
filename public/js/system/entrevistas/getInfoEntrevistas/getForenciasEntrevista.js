async function  RecargaSelectPersonaForensias() {//REFRESCA EL SELECTOR DEL FORENSIA CON LOS DATOS DE PERSONAS Y VEHICULOS GUARDADOS EN EL SEGUIMIENTO  
    // Obtener referencia al elemento select
    var select = document.getElementById("PersonaSelectForencias");
    let Personas = await getPersonaSeguimiento();
    let cad='';
    // Generar las opciones del select
    for (var i = 0; i < Personas.length; i++) {
        option = document.createElement("option");
        cad=(Personas[i]['Alias']!='SD')?" ( "+Personas[i]['Alias']+" ) DE ":" DE "
        option.text = Personas[i]['Nombre_completo'] + cad +Personas[i]['Banda'];
        option.value = Personas[i]['Id_Persona'];
        select.add(option);
    }
}
const MostrarTabForensias=async()=>{//FUNCION QUE OCULTA O MUESTRA LA TAB DE FORENSIA
    if(document.getElementById('visual').value==0){//EN CASO DE NINGUN DATO CARGADO
        document.getElementById('li-Forensia').classList.add('mi_hide');
        document.getElementById('Forensia0').classList.add('mi_hide');
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
const getForensiasSelect = async (Id_Persona_Entrevista) => { //Funcion que realizar peticion para obtener los datos de las forensias de las personas del seguimiento
    try {
        myFormData.append('Id_Persona_Entrevista',Id_Persona_Entrevista)
        const response = await fetch(base_url_js + 'Entrevistas/getForensiasSelect', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const getCatalogoTipo = async () => { //Funcion que realizar peticion para obtener los datos de las forensias de las personas del seguimiento
    try {
        const response = await fetch(base_url_js + 'Catalogos/getTipoDatos', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const dropTablaContentForensiasEntrevistas = async () => {//VACIA EL CONTENIDO DE LA TABLA DE FORENSIAS SIN TOCAR EL ENCABEZADO
    ban= true;
    table = document.getElementById('ForenciasTable');
    aux=document.getElementById('contarforencias').rows.length;
    for(let i = 1; i < aux+1; i++){
        table.deleteRow(1);
    }
    return ban;
}
/*---------------------FUNCIONES DE REFRESCO--------------------------- */
const RecargaDatosForensia = async()=>{//Funcion que actualiza la vista de la tabla forensias cada vez que se guarden o eliminen datos
    await dropTablaContentForensiasEntrevistas();
    let Forensias=await getForensias(Persona_Entrevista);
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
    await changeTipoUbicacion();
    await changeTipoRedSocial();
    await changeDatoEspecifico();
}
const InsertgetForensia = async({Id_Forensia_Entrevista,Id_Persona_Entrevista,Id_Dato,Tipo_Relacion,Descripcion_Forensia,Tipo_Dato,Dato_Relevante,Capturo,Foto,Img_64})=>{//Funcion que inserta los datos obtenidos en la tabla de forensias
    let pathImagesForencias =base_url_js+'public/files/Entrevistas/'+Id_Persona_Entrevista+'/ForensiasRelevantes/';
    let table = document.getElementById('ForenciasTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length); 
    newRow.insertCell(0).innerHTML = Id_Forensia_Entrevista
    newRow.insertCell(1).innerHTML = Id_Dato;
    newRow.insertCell(2).innerHTML = Tipo_Relacion;
    if(Foto!='SD'){
        let ruta = pathImagesForencias+Foto;
        let ban = await imageExists(ruta)
        if(ban==true){
          ruta = ruta+'?nocache='+getRandomInt(50);
          newRow.insertCell(3).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoForencia${newRow.rowIndex}">
                                            <div class="form-group">
                                                <input type="file" name="FotoForencia_row${newRow.rowIndex}" accept="image/*" id="fileFotoForencia_row${newRow.rowIndex}" class="inputfile uploadFileFotoForencia" onchange="uploadFileForencia(event)" data-toggle="tooltip" data-placement="bottom">
                                                <label for="fileFotoForencia_row${newRow.rowIndex}"></label>
                                                <h3 class="uploadFotoForenciaCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                            </div>
                                        </div>
                                        <div id="imageContentForencia_row${newRow.rowIndex}">
                                            <div class="d-flex justify-content-end">
                                                <span onclick="deleteImageFotoForencia(${newRow.rowIndex})" class="deleteFile">X</span>
                                            </div>
                                            <img name="nor" src="${ruta}" id="imagesforencia_row_${newRow.rowIndex}" width="200px" data-toggle="modal" data-target="#ModalCenterForencia${newRow.rowIndex}">
                                            <input type="hidden" class="${newRow.rowIndex} Photo"/>
                                            <div class="modal fade " id="ModalCenterForencia${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <img name="nor" src="${ruta}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                                </div>
                                            </div>  
                                        </div>`;

        }else{
            if(Img_64!='SD'){
                newRow.insertCell(3).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoForencia${newRow.rowIndex}">
                                                    <div class="form-group">
                                                        <input type="file" name="FotoForencia_row${newRow.rowIndex}" accept="image/*" id="fileFotoForencia_row${newRow.rowIndex}" class="inputfile uploadFileFotoForencia" onchange="uploadFileForencia(event)" data-toggle="tooltip" data-placement="bottom">
                                                        <label for="fileFotoForencia_row${newRow.rowIndex}"></label>
                                                        <h3 class="uploadFotoForenciaCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                                    </div>
                                                </div>
                                                <div id="imageContentForencia_row${newRow.rowIndex}">
                                                    <div class="d-flex justify-content-end">
                                                        <span onclick="deleteImageFotoForencia(${newRow.rowIndex})" class="deleteFile">X</span>
                                                    </div>
                                                    <img name="nor" src="${Img_64}" id="imagesforencia_row_${newRow.rowIndex}" width="200px" data-toggle="modal" data-target="#ModalCenterForencia${newRow.rowIndex}">
                                                    <input type="hidden" class="${newRow.rowIndex} Photo"/>
                                                    <div class="modal fade " id="ModalCenterForencia${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                            <img name="nor" src="${Img_64}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                                        </div>
                                                    </div>  
                                                </div>`;

            }else{
                newRow.insertCell(3).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoForencia${newRow.rowIndex}">
                                                    <div class="form-group">
                                                        <input type="file" name="FotoForencia_row${newRow.rowIndex}" accept="image/*" id="fileFotoForencia_row${newRow.rowIndex}" class="inputfile uploadFileFotoForencia" onchange="uploadFileForencia(event)" data-toggle="tooltip" data-placement="bottom">
                                                        <label for="fileFotoForencia_row${newRow.rowIndex}"></label>
                                                        <h3 class="uploadFotoForenciaCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                                    </div>
                                                </div>
                                                <div id="imageContentForencia_row${newRow.rowIndex}"></div>`;

            }
        }
        
    }else{
        newRow.insertCell(3).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoForencia${newRow.rowIndex}">
                                            <div class="form-group">
                                                <input type="file" name="FotoForencia_row${newRow.rowIndex}" accept="image/*" id="fileFotoForencia_row${newRow.rowIndex}" class="inputfile uploadFileFotoForencia" onchange="uploadFileForencia(event)" data-toggle="tooltip" data-placement="bottom">
                                                <label for="fileFotoForencia_row${newRow.rowIndex}"></label>
                                                <h3 class="uploadFotoForenciaCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                            </div>
                                        </div>
                                        <div id="imageContentForencia_row${newRow.rowIndex}"></div>`;

    }

    newRow.insertCell(4).innerHTML = Descripcion_Forensia;
    newRow.insertCell(5).innerHTML = Tipo_Dato;
    newRow.insertCell(6).innerHTML = Dato_Relevante;
    newRow.insertCell(7).innerHTML =Capturo;
    newRow.insertCell(8).innerHTML =`<button type="button" class="btn btn-add mt-1" onclick="editForencia(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc mt-1" value="-" onclick="deleteRowForencia(this,ForenciasTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
    newRow.cells[5].style.display = "none";
    newRow.cells[6].style.display = "none";
}