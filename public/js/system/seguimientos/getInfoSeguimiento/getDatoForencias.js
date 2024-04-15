const getForencias = async (Ids_Persona) => { //Funcion que realizar peticion para obtener los datos de las forensias de las personas del seguimiento
    try {
        myFormData.append('Ids_Persona',JSON.stringify(Ids_Persona))
        const response = await fetch(base_url_js + 'Seguimientos/getForencias', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const dropTablaContentForencias = async () => {//VACIA EL CONTENIDO DE LA TABLA DE FORENSIAS SIN TOCAR EL ENCABEZADO
    ban= true;
    table = document.getElementById('ForenciasTable');
    aux=document.getElementById('contarforencias').rows.length;
    for(let i = 1; i < aux+1; i++){
        table.deleteRow(1);
    }
    return ban;
}
/*---------------------FUNCIONES DE REFRESCO--------------------------- */
const RecargaDatosForencia = async()=>{//Funcion que actualiza la vista de la tabla forensias cada vez que se guarden o eliminen datos
    await dropTablaContentForencias();
    let i=0;
    let consultaPersonas=[];
    let Personas = await getPersonas(Seguimiento);
    for await(Persona of Personas){
        consultaPersonas[i]=Persona.Id_Persona;
        i++;
    }
   
    let ForenciasP=await getForencias(consultaPersonas);
    for ( i = 0; i < ForenciasP.length; i++) {
        if(ForenciasP[i].length>0){
            let Forencias = ForenciasP[i];
            for await(let Forencia of Forencias){
                let formDataForencia = {
                    Id_Forencia : Forencia.Id_Forencia,
                    Id_Persona : Forencia.Id_Persona,
                    Id_Seguimiento: Seguimiento,
                    Descripcion_Forencia : Forencia.Descripcion_Forencia,
                    Capturo : Forencia.Capturo,
                    Foto_Nombre : Forencia.Foto_Nombre,
                    Img_64 : Forencia.Img_64
                }
               await InsertgetForencia(formDataForencia);//Inserta todos las forensias de las personas del seguimiento
            }
        }
    }

}
const InsertgetForencia= async({Id_Forencia,Id_Persona,Id_Seguimiento,Descripcion_Forencia,Capturo,Foto_Nombre,Img_64})=>{//Funcion que inserta los datos obtenidos en la tabla de forensias
    let pathImagesForencias =base_url_js+'public/files/Seguimientos/'+Id_Seguimiento+'/Forencias/';
    let table = document.getElementById('ForenciasTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    document.getElementById("PersonaSelectForencias").value= Id_Persona;  
    newRow.insertCell(0).innerHTML =  document.getElementById("PersonaSelectForencias").options[document.getElementById("PersonaSelectForencias").selectedIndex].text;
    document.getElementById("PersonaSelectForencias").value='SD';
    newRow.insertCell(1).innerHTML = Id_Forencia;
    newRow.insertCell(2).innerHTML = Id_Persona;
    newRow.insertCell(3).innerHTML = Descripcion_Forencia;
    if(Foto_Nombre!='SD'){
        let ruta = pathImagesForencias+Foto_Nombre;
        let ban = await imageExists(ruta)
        if(ban==true){
          ruta = ruta+'?nocache='+getRandomInt(50);
          newRow.insertCell(4).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoForencia${newRow.rowIndex}">
                                            <div class="form-group">
                                                <input type="file" name="FotoForencia_row${newRow.rowIndex}" accept="image/*" id="fileFotoForencia_row${newRow.rowIndex}" class="inputfile uploadFileFotoForencia" onchange="uploadFileForencia(event)" data-toggle="tooltip" data-placement="bottom">
                                                <label for="fileFotoForencia_row${newRow.rowIndex}"></label>
                                            </div>
                                        </div>
                                        <div id="imageContentForencia_row${newRow.rowIndex}">
                                            <div class="d-flex justify-content-end">
                                                <span onclick="deleteImageFotoForencia(${newRow.rowIndex})" class="deleteFile">X</span>
                                            </div>
                                            <img name="nor" src="${ruta}" id="imagesforencia_row_${newRow.rowIndex}" width="400px" data-toggle="modal" data-target="#ModalCenterForencia${newRow.rowIndex}">
                                            <input type="hidden" class="${newRow.rowIndex} Photo"/>
                                            <div class="modal fade " id="ModalCenterForencia${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <img name="nor" src="${ruta}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                                </div>
                                            </div>  
                                        </div>`;

        }else{
            if(Img_64!='SD'){
                newRow.insertCell(4).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoForencia${newRow.rowIndex}">
                                                    <div class="form-group">
                                                        <input type="file" name="FotoForencia_row${newRow.rowIndex}" accept="image/*" id="fileFotoForencia_row${newRow.rowIndex}" class="inputfile uploadFileFotoForencia" onchange="uploadFileForencia(event)" data-toggle="tooltip" data-placement="bottom">
                                                        <label for="fileFotoForencia_row${newRow.rowIndex}"></label>
                                                    </div>
                                                </div>
                                                <div id="imageContentForencia_row${newRow.rowIndex}">
                                                    <div class="d-flex justify-content-end">
                                                        <span onclick="deleteImageFotoForencia(${newRow.rowIndex})" class="deleteFile">X</span>
                                                    </div>
                                                    <img name="nor" src="${Img_64}" id="imagesforencia_row_${newRow.rowIndex}" width="400px" data-toggle="modal" data-target="#ModalCenterForencia${newRow.rowIndex}">
                                                    <input type="hidden" class="${newRow.rowIndex} Photo"/>
                                                    <div class="modal fade " id="ModalCenterForencia${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                            <img name="nor" src="${Img_64}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                                        </div>
                                                    </div>  
                                                </div>`;

            }else{
                newRow.insertCell(4).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoForencia${newRow.rowIndex}">
                                                    <div class="form-group">
                                                        <input type="file" name="FotoForencia_row${newRow.rowIndex}" accept="image/*" id="fileFotoForencia_row${newRow.rowIndex}" class="inputfile uploadFileFotoForencia" onchange="uploadFileForencia(event)" data-toggle="tooltip" data-placement="bottom">
                                                        <label for="fileFotoForencia_row${newRow.rowIndex}"></label>
                                                    </div>
                                                </div>
                                                <div id="imageContentForencia_row${newRow.rowIndex}"></div>`;

            }
        }
        
    }else{
        newRow.insertCell(4).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoForencia${newRow.rowIndex}">
                                            <div class="form-group">
                                                <input type="file" name="FotoForencia_row${newRow.rowIndex}" accept="image/*" id="fileFotoForencia_row${newRow.rowIndex}" class="inputfile uploadFileFotoForencia" onchange="uploadFileForencia(event)" data-toggle="tooltip" data-placement="bottom">
                                                <label for="fileFotoForencia_row${newRow.rowIndex}"></label>
                                            </div>
                                        </div>
                                        <div id="imageContentForencia_row${newRow.rowIndex}"></div>`;

    }
    newRow.insertCell(5).innerHTML =Capturo;
    newRow.insertCell(6).innerHTML =`<button type="button" class="btn btn-add" onclick="editForencia(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc" value="-" onclick="deleteRowForencia(this,ForenciasTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
    newRow.cells[1].style.display = "none";
    newRow.cells[2].style.display = "none";
}