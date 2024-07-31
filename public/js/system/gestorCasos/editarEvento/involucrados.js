function changeIdentificacionI(){
    let radioHabilitado = document.getElementsByName('Identificacion_I');
    if(radioHabilitado[0].checked){//si tiene involucrados
        document.getElementById('div_responsables').classList.remove('mi_hide');
    }else if(radioHabilitado[1].checked){//no tiene involucrados
        document.getElementById('div_responsables').classList.add('mi_hide');
    }
    
}

const readTableRes = async() => {//lee los datos de la tabla personas y genera una estructura deacuerdo a los datos contenido es la tabla
    let table = document.getElementById('PersonaTable');
    let objetos = [];
    for (let i = 1; i < table.rows.length; i++) {
        let input = table.rows[i].cells[5].children[1].children[1];
        if (input != undefined) {//si en el contenedor existe alguna imagen verifica el tipo
            let type = table.rows[i].cells[5].children[1].children[2].classList[1]
            let base64 = document.getElementById('imagesP_row_' + i);
            nameImage = 'FotoInvolucrado_row' + i;
            isPNG = base64.src.split('.');
            if (type != 'File') {//funcion para leer las fotos
                if (isPNG[1] != undefined) {
                    await toDataURL(base64.src)//esto es para tipo photo osea que ya se encuentran en el servidor un archivo fisico con extension diferente a la png
                        .then(myBase64 => {
                            objetos.push({
                                ['row']: {
                                    Id_Responsable: table.rows[i].cells[0].innerHTML,
                                    sexo: table.rows[i].cells[1].innerHTML,
                                    rango_edad: table.rows[i].cells[2].innerHTML,
                                    complexion: table.rows[i].cells[3].innerHTML,
                                    descripcionR: table.rows[i].cells[4].innerHTML,
                                    tipo_arma: table.rows[i].cells[6].innerHTML,
                                    estado_res: table.rows[i].cells[7].innerHTML,
                                    capturo: table.rows[i].cells[8].innerHTML,
                                    Ultima_Actualizacion: table.rows[i].cells[10].innerHTML,
                                    typeImage: type,
                                    nameImage: nameImage,
                                    image: myBase64,
                                    imagebase64:myBase64
                                }
                            });
                        })
                } else {//esto es para tipo photo osea que ya se encuentran en el servidor un archivo fisico con extension png
                    objetos.push({
                        ['row']: {
                            Id_Responsable: table.rows[i].cells[0].innerHTML,
                            sexo: table.rows[i].cells[1].innerHTML,
                            rango_edad: table.rows[i].cells[2].innerHTML,
                            complexion: table.rows[i].cells[3].innerHTML,
                            descripcionR: table.rows[i].cells[4].innerHTML,
                            tipo_arma: table.rows[i].cells[6].innerHTML,
                            estado_res: table.rows[i].cells[7].innerHTML,
                            capturo: table.rows[i].cells[8].innerHTML,
                            Ultima_Actualizacion: table.rows[i].cells[10].innerHTML,
                            typeImage: type,
                            nameImage: nameImage,
                            image:  base64.src,
                            imagebase64:base64.src
                        }
                    });
                }
            }else{// si es tipo file guarda los datos de la imagen
                let aux=document.getElementById('fileFotoP_row'+i)
                let base64URL = await encodeFileAsBase64URL(aux.files[0]);
                objetos.push({
                    ['row']: {
                        Id_Responsable: table.rows[i].cells[0].innerHTML,
                        sexo: table.rows[i].cells[1].innerHTML,
                        rango_edad: table.rows[i].cells[2].innerHTML,
                        complexion: table.rows[i].cells[3].innerHTML,
                        descripcionR: table.rows[i].cells[4].innerHTML,
                        tipo_arma: table.rows[i].cells[6].innerHTML,
                        estado_res: table.rows[i].cells[7].innerHTML,
                        capturo: table.rows[i].cells[8].innerHTML,
                        Ultima_Actualizacion: table.rows[i].cells[10].innerHTML,
                        typeImage: type,
                        nameImage: nameImage,
                        image: "null",
                        imagebase64:base64URL
                    }
                });

            }
 
        } else {//si no hay imagen solo almacena los datos el texto 
            objetos.push({
                ['row']: {
                    Id_Responsable: table.rows[i].cells[0].innerHTML,
                    sexo: table.rows[i].cells[1].innerHTML,
                    rango_edad: table.rows[i].cells[2].innerHTML,
                    complexion: table.rows[i].cells[3].innerHTML,
                    descripcionR: table.rows[i].cells[4].innerHTML,
                    tipo_arma: table.rows[i].cells[6].innerHTML,
                    estado_res: table.rows[i].cells[7].innerHTML,
                    capturo: table.rows[i].cells[8].innerHTML,
                    Ultima_Actualizacion: table.rows[i].cells[10].innerHTML,
                    typeImage: "null",
                    nameImage: "null",
                    image: "null",
                    imagebase64:"null"
                }
            });
        }

    }
    return objetos;
}
//FUNCIONALIDADES DE LA TABLA DE RESPONSABLES
const insertNewRowResponsable = async({Folio_infra,Id_Responsable,sexoResponsable,complexionResponsable,rangoEdad,descripcionResponsable,Path_Imagen,tipo_arma,img_64,Estado_Res,Capturo,Ultima_Actualizacion}) => {//Funcion para llenar los datos de la tabla personas
    let pathImagesFotos = `${base_url_js}public/files/GestorCasos/${Folio_infra}/Evento/`;
    const table = document.getElementById('PersonaTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    newRow.insertCell(0).innerHTML = Id_Responsable;
    newRow.insertCell(1).innerHTML = sexoResponsable;
    newRow.insertCell(2).innerHTML = rangoEdad;
    newRow.insertCell(3).innerHTML = complexionResponsable;
    newRow.insertCell(4).innerHTML = descripcionResponsable;
    if((Path_Imagen!='') && (Path_Imagen!=null)){
        srcImage = Path_Imagen;
        srcImage = srcImage.split('?')
        let ruta2=pathImagesFotos + srcImage[0]
        ban =await imageExists(ruta2)
        if( ban==true){
            ruta2 =ruta2+'?nocache='+getRandomInt(50);
            newRow.insertCell(5).innerHTML = `<div class="d-flex justify-content-around" id="uploadFileFotoP${newRow.rowIndex}">
                                                <div class="form-group">
                                                    <input type="file" name="FotoInvolucrado_row${newRow.rowIndex}" accept="image/*" id="fileFotoP_row${newRow.rowIndex}" class="inputfile uploadFileFotoP" onchange="uploadFile(event)" data-toggle="tooltip" data-placement="bottom">
                                                    <label for="fileFotoP_row${newRow.rowIndex}"></label>
                                                    <h3 class="uploadInvolucradoCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                                </div>
                                            </div>
                                            <div id="imageContentP_row${newRow.rowIndex}">
                                                <div class="d-flex justify-content-end">
                                                    <span onclick="deleteImageFotoP(${newRow.rowIndex})" class="deleteFile">X</span>
                                                </div>
                                                <img name="nor" src="${ruta2}" id="imagesP_row_${newRow.rowIndex}" width="200px" data-toggle="modal" data-target="#ModalCenterInvolucrado${newRow.rowIndex}">
                                                <input type="hidden" class="${newRow.rowIndex} Photo"/>
                                                <div class="modal fade " id="ModalCenterInvolucrado${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                        <img name="nor" src="${ruta2}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                                    </div>
                                                </div>
                                            </div>`; 
        }else{
            if(img_64!='SD'&&img_64!=' '){
                newRow.insertCell(5).innerHTML = `<div class="d-flex justify-content-around" id="uploadFileFotoP${newRow.rowIndex}">
                                                    <div class="form-group">
                                                        <input type="file" name="FotoInvolucrado_row${newRow.rowIndex}" accept="image/*" id="fileFotoP_row${newRow.rowIndex}" class="inputfile uploadFileFotoP" onchange="uploadFile(event)" data-toggle="tooltip" data-placement="bottom">
                                                        <label for="fileFotoP_row${newRow.rowIndex}"></label>
                                                        <h3 class="uploadInvolucradoCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                                    </div>
                                                </div>
                                                <div id="imageContentP_row${newRow.rowIndex}">
                                                    <div class="d-flex justify-content-end">
                                                        <span onclick="deleteImageFotoP(${newRow.rowIndex})" class="deleteFile">X</span>
                                                    </div>
                                                    <img name="nor" src="${img_64}" id="imagesP_row_${newRow.rowIndex}" width="200px" data-toggle="modal" data-target="#ModalCenterInvolucrado${newRow.rowIndex}">
                                                    <input type="hidden" class="${newRow.rowIndex} Photo"/>
                                                    <div class="modal fade " id="ModalCenterInvolucrado${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                            <img name="nor" src="${img_64}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                                        </div>
                                                    </div>
                                                </div>`; 
                banderafotosVP=false;
            }else{
                console.log("NO EXISTE FOTO PERSONA VERIFICAR RESPALDO")
                newRow.insertCell(5).innerHTML = `<div class="d-flex justify-content-around" id="uploadFileFotoP${newRow.rowIndex}">
                                                    <div class="form-group">
                                                        <input type="file" name="FotoInvolucrado_row${newRow.rowIndex}" accept="image/*" id="fileFotoP_row${newRow.rowIndex}" class="inputfile uploadFileFotoP" onchange="uploadFile(event)" data-toggle="tooltip" data-placement="bottom">
                                                        <label for="fileFotoP_row${newRow.rowIndex}"></label>
                                                        <h3 class="uploadInvolucradoCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                                    </div>
                                                </div>
                                                <div id="imageContentP_row${newRow.rowIndex}">
                                                <label>NO EXISTE FOTO PERSONA VERIFICAR RESPALDO</label>
                                                </div>`
            }
        }
    }else{
        newRow.insertCell(5).innerHTML = `<div class="d-flex justify-content-around" id="uploadFileFotoP${newRow.rowIndex}">
                                            <div class="form-group">
                                                <input type="file" name="FotoInvolucrado_row${newRow.rowIndex}" accept="image/*" id="fileFotoP_row${newRow.rowIndex}" class="inputfile uploadFileFotoP" onchange="uploadFile(event)" data-toggle="tooltip" data-placement="bottom">
                                                <label for="fileFotoP_row${newRow.rowIndex}"></label>
                                                <h3 class="uploadInvolucradoCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                            </div>
                                        </div>
                                        <div id="imageContentP_row${newRow.rowIndex}"></div>`;
    }
    newRow.insertCell(6).innerHTML = tipo_arma;
    newRow.insertCell(7).innerHTML = Estado_Res;
    newRow.insertCell(8).innerHTML = Capturo;
    newRow.insertCell(9).innerHTML = `<button type="button" class="btn btn-add" onclick="editPR(this)"> 
                                        <i class="material-icons">edit</i>
                                        </button>
                                        <button type="button" class="btn btn-ssc" value="-" onclick="deleteRow(this,PersonaTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;

    newRow.insertCell(10).innerHTML = Ultima_Actualizacion;
    newRow.cells[10].style.display = "none";
}
let selectedRowPR = null
const onFormPRSubmit = () => {//Funcion para validar que por lo menos ponga el sexo del involucrado
    if(document.getElementById('Sexo').value!="SD"){
        document.getElementById('Sexo_principales_error').innerText =''
        if (selectedRowPR === null){
            InsertPR();
            resetFormPR();
        }else{
            updateRowPR();
            resetFormPR();
        }
    }else{
        document.getElementById('Sexo_principales_error').innerText = 'Debe de especificar por lo menos el sexo'
    }
}

const InsertPR = () => {//INSERTA LOS DATOS CAPTURADOS EN LA VISTA EN LA TABLA DE PERSONAS
    const table = document.getElementById('PersonaTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    newRow.insertCell(0).innerHTML = document.getElementById('Id_Responsable').value;
    newRow.insertCell(1).innerHTML = document.getElementById('Sexo').value;
    newRow.insertCell(2).innerHTML = document.getElementById('Rango_Edad').value;
    newRow.insertCell(3).innerHTML = document.getElementById('Complexion').value;
    
    if(document.getElementById('Descripcion_gral_per').value!=""){
        let emojis = /(?:[\u2700-\u27bf]|(?:\ud83c[\udde6-\uddff]){2}|[\ud800-\udbff][\udc00-\udfff]|[\u0023-\u0039]\ufe0f?\u20e3|\u3299|\u3297|\u303d|\u3030|\u24c2|\ud83c[\udd70-\udd71]|\ud83c[\udd7e-\udd7f]|\ud83c\udd8e|\ud83c[\udd91-\udd9a]|\ud83c[\udde6-\uddff]|\ud83c[\ude01-\ude02]|\ud83c\ude1a|\ud83c\ude2f|\ud83c[\ude32-\ude3a]|\ud83c[\ude50-\ude51]|\u203c|\u2049|[\u25aa-\u25ab]|\u25b6|\u25c0|[\u25fb-\u25fe]|\u00a9|\u00ae|\u2122|\u2139|\ud83c\udc04|[\u2600-\u26FF]|\u2b05|\u2b06|\u2b07|\u2b1b|\u2b1c|\u2b50|\u2b55|\u231a|\u231b|\u2328|\u23cf|[\u23e9-\u23f3]|[\u23f8-\u23fa]|\ud83c\udccf|\u2934|\u2935|[\u2190-\u21ff])/g;
        let limpia= document.getElementById('Descripcion_gral_per').value.toUpperCase();
        limpia=limpia.replace(emojis , '');
        newRow.insertCell(4).innerHTML =limpia;
    }else{

        newRow.insertCell(4).innerHTML = "SD"
    }
    newRow.insertCell(5).innerHTML =  `<div class="d-flex justify-content-around" id="uploadFileFotoP${newRow.rowIndex}">
                                        <div class="form-group">
                                            <input type="file" name="FotoInvolucrado_row${newRow.rowIndex}" accept="image/*" id="fileFotoP_row${newRow.rowIndex}" class="inputfile uploadFileFotoP" onchange="uploadFile(event)" data-toggle="tooltip" data-placement="bottom">
                                            <label for="fileFotoP_row${newRow.rowIndex}"></label>
                                            <h3 class="uploadInvolucradoCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                        </div>
                                    </div>
                                    <div id="imageContentP_row${newRow.rowIndex}"></div>`;
    newRow.insertCell(6).innerHTML = document.getElementById('arma_principales_per').value;
    newRow.insertCell(7).innerHTML = document.getElementById('Estado_Res').value.toUpperCase();
    newRow.insertCell(8).innerHTML = document.getElementById('actualizaVP').value.toUpperCase();                
    newRow.insertCell(9).innerHTML = `<button type="button" class="btn btn-add" onclick="editPR(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc" value="-" onclick="deleteRow(this,PersonaTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
    newRow.insertCell(10).innerHTML = document.getElementById('actualizaVP').value.toUpperCase();
    newRow.cells[10].style.display = "none";
}

const editPR = (obj) => {//FUNCION QUE EDITA LA TABLA DE PERSONAS TOMANDO LOS DATOS DE LA ROW SELECCIONADA Y ACOMODANDO LOS DATOS EN LA VISTA PARA SU EDICION
    document.getElementById('alertaEditProbales').style.display = 'block';
    selectedRowPR = obj.parentElement.parentElement;

    document.getElementById('Id_Responsable').value = selectedRowPR.cells[0].innerHTML;
    document.getElementById('Sexo').value = selectedRowPR.cells[1].innerHTML;
    document.getElementById('Rango_Edad').value=selectedRowPR.cells[2].innerHTML;
    document.getElementById('Complexion').value=selectedRowPR.cells[3].innerHTML;
    document.getElementById('Descripcion_gral_per').value=selectedRowPR.cells[4].innerHTML;
    document.getElementById('arma_principales_per').value=selectedRowPR.cells[6].innerHTML;
    document.getElementById('Estado_Res').value=selectedRowPR.cells[7].innerHTML;

}
const updateRowPR = () => {//FUNCION PARA ACTUALIZAR LOS DATOS DE LA TABLA DE PERSONAS
    selectedRowPR.cells[0].innerHTML = document.getElementById('Id_Responsable').value;
    selectedRowPR.cells[1].innerHTML = document.getElementById('Sexo').value;
    selectedRowPR.cells[2].innerHTML = document.getElementById('Rango_Edad').value;
    selectedRowPR.cells[3].innerHTML = document.getElementById('Complexion').value;
    if(document.getElementById('Descripcion_gral_per').value!=""){
        let emojis = /(?:[\u2700-\u27bf]|(?:\ud83c[\udde6-\uddff]){2}|[\ud800-\udbff][\udc00-\udfff]|[\u0023-\u0039]\ufe0f?\u20e3|\u3299|\u3297|\u303d|\u3030|\u24c2|\ud83c[\udd70-\udd71]|\ud83c[\udd7e-\udd7f]|\ud83c\udd8e|\ud83c[\udd91-\udd9a]|\ud83c[\udde6-\uddff]|\ud83c[\ude01-\ude02]|\ud83c\ude1a|\ud83c\ude2f|\ud83c[\ude32-\ude3a]|\ud83c[\ude50-\ude51]|\u203c|\u2049|[\u25aa-\u25ab]|\u25b6|\u25c0|[\u25fb-\u25fe]|\u00a9|\u00ae|\u2122|\u2139|\ud83c\udc04|[\u2600-\u26FF]|\u2b05|\u2b06|\u2b07|\u2b1b|\u2b1c|\u2b50|\u2b55|\u231a|\u231b|\u2328|\u23cf|[\u23e9-\u23f3]|[\u23f8-\u23fa]|\ud83c\udccf|\u2934|\u2935|[\u2190-\u21ff])/g;
        let limpia= document.getElementById('Descripcion_gral_per').value.toUpperCase();
        limpia=limpia.replace(emojis , '');
        selectedRowPR.cells[4].innerHTML =limpia;

    }else{

        selectedRowPR.cells[4].innerHTML = "SD"
    }
    selectedRowPR.cells[6].innerHTML = document.getElementById('arma_principales_per').value;
    selectedRowPR.cells[7].innerHTML = document.getElementById('Estado_Res').value.toUpperCase();
    selectedRowPR.cells[10].innerHTML = document.getElementById('actualizaVP').value.toUpperCase(); 
    document.getElementById('alertaEditProbales').style.display = 'none';
    selectedRowPR= null;
}

const resetFormPR = () => {//FUNCION QUE LIMPIA LOS CAMPOS ASOCIADOS A LA TABLA DE PERSONAS
    document.getElementById('Id_Responsable').value="SD";
    document.getElementById('Sexo').value="SD";
    document.getElementById('Rango_Edad').value="SD";
    document.getElementById('Complexion').value="SD";
    document.getElementById('Descripcion_gral_per').value="";
    if(document.getElementById('violencia_principales').value=="ARMA DE FUEGO"){
        document.getElementById('cons').value="SD";
    }else{
        document.getElementById('cons').value="NA";
    }
    document.getElementById('Estado_Res').value="NO CORROBORADO";
}

const DesasociaInvolucrado = async (Id_Registro) =>{
    try {
        myFormData.append('Id_Registro',Id_Registro)
        myFormData.append('Folio_infra',document.getElementById('folio_infra_principales').value)
        const response = await fetch(base_url_js + 'GestorCasos/DesasociaInvolucrado', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}