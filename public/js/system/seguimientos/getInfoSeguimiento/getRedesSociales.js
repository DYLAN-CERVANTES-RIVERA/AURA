const getRedesSociales = async (Ids_Persona) => { //Funcion que realizar peticion para obtener los datos de las redes sociales de las personas del seguimiento
    try {
        myFormData.append('Ids_Persona',JSON.stringify(Ids_Persona))
        const response = await fetch(base_url_js + 'Seguimientos/getRedesSociales', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const dropTablaContentRedesSociales = async () => {//VACIA EL CONTENIDO DE LA TABLA FOTOS SIN TOCAR EL ENCABEZADO
    ban= true;
    table = document.getElementById('RedsocialTable');
    aux=document.getElementById('contarRedsocial').rows.length;
    for(let i = 1; i < aux+1; i++){
        table.deleteRow(1);
    }
    return ban;
}
/*---------------------FUNCIONES DE REFRESCO--------------------------- */
const RecargaDatosRedesSociales = async()=>{//Funcion que actualiza la vista de la tabla de redes sociales cada vez que se guarden o eliminen datos
    await dropTablaContentRedesSociales();
    let i=0;
    let consultaPersonas=[];
    let Personas = await getPersonas(Seguimiento);
    for await(Persona of Personas){
        consultaPersonas[i]=Persona.Id_Persona;
        i++;
    }
   
    let RedesSociales=await getRedesSociales(consultaPersonas);
    for ( i = 0; i < RedesSociales.length; i++) {
        if(RedesSociales[i].length>0){
            let RedesSocial = RedesSociales[i];
            for await(let RedSocial of RedesSocial){
                let formDataRedSocial = {
                    Id_Registro: RedSocial.Id_Registro ,
                    Id_Seguimiento: Seguimiento,
                    Id_Persona : RedSocial.Id_Persona ,
                    Usuario:     RedSocial.Usuario,
                    Enlace:      RedSocial.Enlace,
                    Tipo_Enlace: RedSocial.Tipo_Enlace,
                    Observacion_Enlace: RedSocial.Observacion_Enlace,
                    Capturo : RedSocial.Capturo,
                    Foto_Nombre : RedSocial.Foto_Nombre,
                    Img_64 : RedSocial.Img_64
                }
               
               await InsertgetRedSocial(formDataRedSocial);//Inserta todos los datos de redes sociales de las personas del seguimiento
            }
        }
    }
}

const InsertgetRedSocial = async({Id_Registro,Id_Seguimiento,Id_Persona,Usuario,Enlace,Tipo_Enlace,Observacion_Enlace,Capturo,Foto_Nombre,Img_64})=>{//Funcion que inserta los datos de redes sociales de las personas del seguimiento
    let pathImagesRedesSociales =base_url_js+'public/files/Seguimientos/'+Id_Seguimiento+'/Redes_Sociales/';
    let table = document.getElementById('RedsocialTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    document.getElementById("PersonaSelectRedsocial").value= Id_Persona;  
    newRow.insertCell(0).innerHTML =  document.getElementById("PersonaSelectRedsocial").options[document.getElementById("PersonaSelectRedsocial").selectedIndex].text;
    document.getElementById("PersonaSelectRedsocial").value='SD';
    newRow.insertCell(1).innerHTML =Id_Registro;
    newRow.insertCell(2).innerHTML =Id_Persona;
    newRow.insertCell(3).innerHTML =Usuario;
    newRow.insertCell(4).innerHTML =Enlace;
    newRow.insertCell(5).innerHTML =Tipo_Enlace;
    newRow.insertCell(6).innerHTML =Observacion_Enlace;
    if(Foto_Nombre!='SD'){
        let ruta = pathImagesRedesSociales+Foto_Nombre;
        let ban = await imageExists(ruta)
        if(ban==true){
          ruta = ruta+'?nocache='+getRandomInt(50);
          newRow.insertCell(7).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoRedsocial${newRow.rowIndex}">
                                            <div class="form-group">
                                                <input type="file" name="FotoRedsocial_row${newRow.rowIndex}" accept="image/*" id="fileFotoRedsocial_row${newRow.rowIndex}" class="inputfile uploadFileFotoRedsocial" onchange="uploadFileRedsocial(event)" data-toggle="tooltip" data-placement="bottom">
                                                <label for="fileFotoRedsocial_row${newRow.rowIndex}"></label>
                                            </div>
                                        </div>
                                        <div id="imageContentRedsocial_row${newRow.rowIndex}">
                                            <div class="d-flex justify-content-end">
                                                <span onclick="deleteImageFotoRedSocial(${newRow.rowIndex})" class="deleteFile">X</span>
                                            </div>
                                            <img name="nor" src="${ruta}" id="imagesRedSocial_row_${newRow.rowIndex}" width="400px" data-toggle="modal" data-target="#ModalCenterRedSocial${newRow.rowIndex}">
                                            <input type="hidden" class="${newRow.rowIndex} Photo"/>
                                            <div class="modal fade " id="ModalCenterRedSocial${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <img name="nor" src="${ruta}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                                </div>
                                            </div>
                                        </div>`;

        }else{
            if(Img_64!='SD'){
                newRow.insertCell(7).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoRedsocial${newRow.rowIndex}">
                                                        <div class="form-group">
                                                            <input type="file" name="FotoRedsocial_row${newRow.rowIndex}" accept="image/*" id="fileFotoRedsocial_row${newRow.rowIndex}" class="inputfile uploadFileFotoRedsocial" onchange="uploadFileRedsocial(event)" data-toggle="tooltip" data-placement="bottom">
                                                            <label for="fileFotoRedsocial_row${newRow.rowIndex}"></label>
                                                        </div>
                                                    </div>
                                                    <div id="imageContentRedsocial_row${newRow.rowIndex}">
                                                        <div class="d-flex justify-content-end">
                                                            <span onclick="deleteImageFotoRedSocial(${newRow.rowIndex})" class="deleteFile">X</span>
                                                        </div>
                                                        <img name="nor" src="${Img_64}" id="imagesRedSocial_row_${newRow.rowIndex}" width="400px" data-toggle="modal" data-target="#ModalCenterRedSocial${newRow.rowIndex}">
                                                        <input type="hidden" class="${newRow.rowIndex} Photo"/>
                                                        <div class="modal fade " id="ModalCenterRedSocial${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                                <img name="nor" src="${Img_64}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                                            </div>
                                                        </div>
                                                    </div>`;

            }else{
                newRow.insertCell(7).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoRedsocial${newRow.rowIndex}">
                        <div class="form-group">
                            <input type="file" name="FotoRedsocial_row${newRow.rowIndex}" accept="image/*" id="fileFotoRedsocial_row${newRow.rowIndex}" class="inputfile uploadFileFotoRedsocial" onchange="uploadFileRedsocial(event)" data-toggle="tooltip" data-placement="bottom">
                            <label for="fileFotoRedsocial_row${newRow.rowIndex}"></label>
                        </div>
                    </div>
                    <div id="imageContentRedsocial_row${newRow.rowIndex}"></div>`;

            }
        }
        
    }else{
        newRow.insertCell(7).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoRedsocial${newRow.rowIndex}">
                                            <div class="form-group">
                                                <input type="file" name="FotoRedsocial_row${newRow.rowIndex}" accept="image/*" id="fileFotoRedsocial_row${newRow.rowIndex}" class="inputfile uploadFileFotoRedsocial" onchange="uploadFileRedsocial(event)" data-toggle="tooltip" data-placement="bottom">
                                                <label for="fileFotoRedsocial_row${newRow.rowIndex}"></label>
                                            </div>
                                        </div>
                                        <div id="imageContentRedsocial_row${newRow.rowIndex}"></div>`;
    }

    newRow.insertCell(8).innerHTML =Capturo;
    newRow.insertCell(9).innerHTML =`<button type="button" class="btn btn-add" onclick="editRedsocial(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc" value="-" onclick="deleteRowRedsocial(this,RedsocialTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
    newRow.cells[1].style.display = "none";
    newRow.cells[2].style.display = "none";
    
}