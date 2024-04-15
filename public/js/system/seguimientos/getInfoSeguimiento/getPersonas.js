const getPersonas = async (Id_Seguimiento) => { //Funcion que realizar peticion para obtener los datos de las personas del seguimiento
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

const InsertgetPersona= async({	Id_Persona,Id_Seguimiento,Nombre,Ap_Paterno,Ap_Materno,Genero,Edad,Fecha_Nacimiento,Telefono,Alias,Curp,Remisiones,Rol,Capturo,Foto,Img_64})=>{//FUNCION QUE INSERTA LOS DATOS DE LAS PERSONAS ASOCIADAS AL SEGUIMIENTO
    let pathImagesPersonas =base_url_js+'public/files/Seguimientos/'+Id_Seguimiento+'/Personas/';
    let table = document.getElementById('PersonaTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    newRow.insertCell(0).innerHTML = Id_Persona;
    newRow.insertCell(1).innerHTML = Nombre;
    newRow.insertCell(2).innerHTML = Ap_Paterno;
    newRow.insertCell(3).innerHTML = Ap_Materno;
    newRow.insertCell(4).innerHTML = Curp;
    newRow.insertCell(5).innerHTML = Fecha_Nacimiento;
    newRow.insertCell(6).innerHTML = Edad
    newRow.insertCell(7).innerHTML = Genero
    newRow.insertCell(8).innerHTML = Telefono;
    newRow.insertCell(9).innerHTML = Alias;
    newRow.insertCell(10).innerHTML = Remisiones;
    if(Foto!='SD'){
        let ruta = pathImagesPersonas+Foto;
        let ban = await imageExists(ruta)
        if(ban==true){
          ruta = ruta+'?nocache='+getRandomInt(50);
          newRow.insertCell(11).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoP${newRow.rowIndex}">
                                            <div class="form-group">
                                                <input type="file" name="FotoPersona_row${newRow.rowIndex}" accept="image/*" id="fileFotoPersona_row${newRow.rowIndex}" class="inputfile uploadFileFotoP" onchange="uploadFileP(event)" data-toggle="tooltip" data-placement="bottom">
                                                <label for="fileFotoPersona_row${newRow.rowIndex}"></label>
                                            </div>
                                        </div>
                                        <div id="imageContentP_row${newRow.rowIndex}">
                                            <div class="d-flex justify-content-end">
                                                <span onclick="deleteImageFotoP(${newRow.rowIndex})" class="deleteFile">X</span>
                                            </div>
                                            <img name="nor" src="${ruta}" id="imagesP_row_${newRow.rowIndex}" width="400px" data-toggle="modal" data-target="#ModalCenterInvolucrado${newRow.rowIndex}">
                                            <input type="hidden" class="${newRow.rowIndex} Photo"/>
                                            <div class="modal fade " id="ModalCenterInvolucrado${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <img name="nor" src="${ruta}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                                </div>
                                            </div>  
                                        </div>`;

        }else{
            if(Img_64!='SD'){
                newRow.insertCell(11).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoP${newRow.rowIndex}">
                                                    <div class="form-group">
                                                        <input type="file" name="FotoPersona_row${newRow.rowIndex}" accept="image/*" id="fileFotoPersona_row${newRow.rowIndex}" class="inputfile uploadFileFotoP" onchange="uploadFileP(event)" data-toggle="tooltip" data-placement="bottom">
                                                        <label for="fileFotoPersona_row${newRow.rowIndex}"></label>
                                                    </div>
                                                </div>
                                                <div id="imageContentP_row${newRow.rowIndex}">
                                                    <div class="d-flex justify-content-end">
                                                        <span onclick="deleteImageFotoP(${newRow.rowIndex})" class="deleteFile">X</span>
                                                    </div>
                                                    <img name="nor" src="${Img_64}" id="imagesP_row_${newRow.rowIndex}" width="400px" data-toggle="modal" data-target="#ModalCenterInvolucrado${newRow.rowIndex}">
                                                    <input type="hidden" class="${newRow.rowIndex} Photo"/>
                                                    <div class="modal fade " id="ModalCenterInvolucrado${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                            <img name="nor" src="${Img_64}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                                        </div>
                                                    </div>  
                                                </div>`;

            }else{
                newRow.insertCell(11).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoP${newRow.rowIndex}">
                                                    <div class="form-group">
                                                        <input type="file" name="FotoPersona_row${newRow.rowIndex}" accept="image/*" id="fileFotoPersona_row${newRow.rowIndex}" class="inputfile uploadFileFotoP" onchange="uploadFileP(event)" data-toggle="tooltip" data-placement="bottom">
                                                        <label for="fileFotoPersona_row${newRow.rowIndex}"></label>
                                                    </div>
                                                </div>
                                                <div id="imageContentP_row${newRow.rowIndex}"></div>`;
            }
        }
        
    }else{
        newRow.insertCell(11).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoP${newRow.rowIndex}">
            <div class="form-group">
                <input type="file" name="FotoPersona_row${newRow.rowIndex}" accept="image/*" id="fileFotoPersona_row${newRow.rowIndex}" class="inputfile uploadFileFotoP" onchange="uploadFileP(event)" data-toggle="tooltip" data-placement="bottom">
                <label for="fileFotoPersona_row${newRow.rowIndex}"></label>
            </div>
        </div>
        <div id="imageContentP_row${newRow.rowIndex}"></div>`;
    }
    newRow.insertCell(12).innerHTML = Rol;
    newRow.insertCell(13).innerHTML = Capturo;
    newRow.insertCell(14).innerHTML = `<button type="button" class="btn btn-add" onclick="editPersona(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc" value="-" onclick="deleteRowPersona(this,PersonaTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
    newRow.cells[0].style.display = "none";
}
/*---------------------FUNCIONES DE REFRESCO--------------------------- */
const RecargaDatosPersonas = async()=>{//Funcion que actualiza la vista de la tabla personas cada vez que se guarden o eliminen datos
    await dropTablaContentPersonas();
    let Personas = await getPersonas(Seguimiento);
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
        console.log(formDataPersona);
       await InsertgetPersona(formDataPersona);//Inserta todas las personas del seguimiento
    }
    await MostrarTabDomicilio();//ES NECESARIO REFRESCAR LOS DATOS DEL SELECT DE LA TAB POR LO QUE AL INVOCAR ESTA FUNCION REALIZA UN REFRESH DE LOS DATOS DEL SELECT
    await MostrarTabAntecedentes();
    MostrarTabForencias();
    MostrarTabRedesSociales();
}
const dropTablaContentPersonas = async () => {//VACIA EL CONTENIDO DE LA TABLA FOTOS SIN TOCAR EL ENCABEZADO
    ban= true;
    table = document.getElementById('PersonaTable');
    aux=document.getElementById('contarRes').rows.length;
    for(let i = 1; i < aux+1; i++){
        table.deleteRow(1);
    }
    return ban;
}

function getRandomInt(max) {//FUNCION QUE RETORNA UN NUMERO ENTERO RANDOM
    return Math.floor(Math.random() * max);
}