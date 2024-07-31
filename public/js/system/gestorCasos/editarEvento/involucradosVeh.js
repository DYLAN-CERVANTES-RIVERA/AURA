function changeIdentificacionVI(){
    let radioHabilitado = document.getElementsByName('Identificacion_VI');
    if(radioHabilitado[0].checked){//si tiene involucrados
        document.getElementById('div_vehInvolucrados').classList.remove('mi_hide');
    }else if(radioHabilitado[1].checked){//no tiene involucrados
        document.getElementById('div_vehInvolucrados').classList.add('mi_hide');
    }
}
const readTableVehiculos = async() => {//lee los datos de la tabla vehiculos y genera una estructura deacuerdo a los datos contenido es la tabla
    const table = document.getElementById('VehiculoTable');
    let objetos = [];
    for (let i = 1; i < table.rows.length; i++) {
        const input = table.rows[i].cells[8].children[1].children[2];
      
        if (input != undefined) {//si en el contenedor existe alguna imagen verifica el tipo 
            const type = table.rows[i].cells[8].children[1].children[2].classList[1]
            base64 = document.getElementById('imagesV_row_' + i);
            nameImage = 'FotoVehiculo_row' + i;
            isPNG = base64.src.split('.');
            if (type != 'File') {//funcion para leer las fotos
                if (isPNG[1] != undefined) {
                    await toDataURL(base64.src)//esto es para tipo photo osea que ya se encuentran en el servidor un archivo fisico con extension diferente a la png
                        .then(myBase64 => {
                            objetos.push({
                                ['row']: {
                                    Id_Vehiculo: table.rows[i].cells[0].innerHTML,
                                    tipo_vehiculo: table.rows[i].cells[1].innerHTML,
                                    marca: table.rows[i].cells[2].innerHTML,
                                    submarca: table.rows[i].cells[3].innerHTML,
                                    modelo: table.rows[i].cells[4].innerHTML,
                                    placas: table.rows[i].cells[5].innerHTML,
                                    color: table.rows[i].cells[6].innerHTML,
                                    descripcionV: table.rows[i].cells[7].innerHTML,
                                    tipo_vehiculo_involucrado: table.rows[i].cells[9].innerHTML,
                                    estado_veh: table.rows[i].cells[10].innerHTML,
                                    capturo: table.rows[i].cells[11].innerHTML,
                                    Ultima_Actualizacion: table.rows[i].cells[13].innerHTML,
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
                            Id_Vehiculo: table.rows[i].cells[0].innerHTML,
                            tipo_vehiculo: table.rows[i].cells[1].innerHTML,
                            marca: table.rows[i].cells[2].innerHTML,
                            submarca: table.rows[i].cells[3].innerHTML,
                            modelo: table.rows[i].cells[4].innerHTML,
                            placas: table.rows[i].cells[5].innerHTML,
                            color: table.rows[i].cells[6].innerHTML,
                            descripcionV: table.rows[i].cells[7].innerHTML,
                            tipo_vehiculo_involucrado: table.rows[i].cells[9].innerHTML,
                            estado_veh: table.rows[i].cells[10].innerHTML,
                            capturo: table.rows[i].cells[11].innerHTML,
                            Ultima_Actualizacion: table.rows[i].cells[13].innerHTML,
                            typeImage: type,
                            nameImage: nameImage,
                            image:  base64.src,
                            imagebase64:base64.src
                        }
                    });
                }
            }else{// si es tipo file guarda los datos de la imagen
                let aux=document.getElementById('fileFotoV_row'+i)
                let base64URL = await encodeFileAsBase64URL(aux.files[0]);
                objetos.push({
                    ['row']: {
                        Id_Vehiculo: table.rows[i].cells[0].innerHTML,
                        tipo_vehiculo: table.rows[i].cells[1].innerHTML,
                        marca: table.rows[i].cells[2].innerHTML,
                        submarca: table.rows[i].cells[3].innerHTML,
                        modelo: table.rows[i].cells[4].innerHTML,
                        placas: table.rows[i].cells[5].innerHTML,
                        color: table.rows[i].cells[6].innerHTML,
                        descripcionV: table.rows[i].cells[7].innerHTML,
                        tipo_vehiculo_involucrado: table.rows[i].cells[9].innerHTML,
                        estado_veh: table.rows[i].cells[10].innerHTML,
                        capturo: table.rows[i].cells[11].innerHTML,
                        Ultima_Actualizacion: table.rows[i].cells[13].innerHTML,
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
                    Id_Vehiculo: table.rows[i].cells[0].innerHTML,
                    tipo_vehiculo: table.rows[i].cells[1].innerHTML,
                    marca: table.rows[i].cells[2].innerHTML,
                    submarca: table.rows[i].cells[3].innerHTML,
                    modelo: table.rows[i].cells[4].innerHTML,
                    placas: table.rows[i].cells[5].innerHTML,
                    color: table.rows[i].cells[6].innerHTML,
                    descripcionV: table.rows[i].cells[7].innerHTML,
                    tipo_vehiculo_involucrado: table.rows[i].cells[9].innerHTML,
                    estado_veh: table.rows[i].cells[10].innerHTML,
                    capturo: table.rows[i].cells[11].innerHTML,
                    Ultima_Actualizacion: table.rows[i].cells[13].innerHTML,
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
/*--------------Funciones de la tabla de vehiculos involucrados muestra todos los vehiculos */
const insertNewRowVehiculo= async({Id_Vehiculo,Folio_infra,tipoVehiculo,marcaVehiculo,submarcaVehiculo,modeloVehiculo,placasVehiculo,colorVehiculo,descripcionVehiculo,Path_Imagen,Tipo_veh_invo,img_64,Estado_Veh,Capturo,Ultima_Actualizacion}) => {//Funcion para llenar los datos de la tabla vehiculo
    let pathImagesFotos = `${base_url_js}public/files/GestorCasos/${Folio_infra}/Evento/`;
    const table = document.getElementById('VehiculoTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    newRow.insertCell(0).innerHTML = Id_Vehiculo;
    newRow.insertCell(1).innerHTML = tipoVehiculo;
    newRow.insertCell(2).innerHTML = marcaVehiculo;
    newRow.insertCell(3).innerHTML = submarcaVehiculo;
    newRow.insertCell(4).innerHTML = modeloVehiculo;
    newRow.insertCell(5).innerHTML = placasVehiculo;
    newRow.insertCell(6).innerHTML = colorVehiculo;
    newRow.insertCell(7).innerHTML = descripcionVehiculo;
    if((Path_Imagen!='') && (Path_Imagen!=null)){

        srcImage = Path_Imagen;
        srcImage = srcImage.split('?')
        let ruta=pathImagesFotos + srcImage[0]
        ban =await imageExists(ruta)
        if( ban==true){
            ruta =ruta+'?nocache='+getRandomInt(50);
            newRow.insertCell(8).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotos${newRow.rowIndex}">
                                                <div class="form-group">
                                                    <input type="file" name="FotoVehiculo_row${newRow.rowIndex}" accept="image/*" id="fileFotoV_row${newRow.rowIndex}" class="inputfile uploadFileFotosV" onchange="uploadFile(event)" data-toggle="tooltip" data-placement="bottom">
                                                    <label for="fileFotoV_row${newRow.rowIndex}"></label>
                                                    <h3 class="uploadInvolucradoVHCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                                </div>
                                            </div>
                                            <div id="imageContentV_row${newRow.rowIndex}">
                                                <div class="d-flex justify-content-end">
                                                    <span onclick="deleteImageFotoV(${newRow.rowIndex})" class="deleteFile">X</span>
                                                </div>
                                                <img name="nor" src="${ruta}" id="imagesV_row_${newRow.rowIndex}" width="250px" data-toggle="modal" data-target="#ModalCenterVehiculo${newRow.rowIndex}">
                                                <input type="hidden" class="${newRow.rowIndex} Photo"/>
                                                <div class="modal fade " id="ModalCenterVehiculo${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                        <img name="nor" src="${ruta}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                                    </div>
                                                </div>
                                            </div>`;
        }else{
            if(img_64!='SD'&&img_64!=' '){
                    newRow.insertCell(8).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotos${newRow.rowIndex}">
                                                        <div class="form-group">
                                                            <input type="file" name="FotoVehiculo_row${newRow.rowIndex}" accept="image/*" id="fileFotoV_row${newRow.rowIndex}" class="inputfile uploadFileFotosV" onchange="uploadFile(event)" data-toggle="tooltip" data-placement="bottom">
                                                            <label for="fileFotoV_row${newRow.rowIndex}"></label>
                                                            <h3 class="uploadInvolucradoVHCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                                        </div>
                                                    </div>
                                                    <div id="imageContentV_row${newRow.rowIndex}">
                                                        <div class="d-flex justify-content-end">
                                                            <span onclick="deleteImageFotoV(${newRow.rowIndex})" class="deleteFile">X</span>
                                                        </div>
                                                        <img name="nor" src="${img_64}" id="imagesV_row_${newRow.rowIndex}" width="250px" data-toggle="modal" data-target="#ModalCenterVehiculo${newRow.rowIndex}">
                                                        <input type="hidden" class="${newRow.rowIndex} Photo"/>
                                                        <div class="modal fade " id="ModalCenterVehiculo${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                                <img name="nor" src="${img_64}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                                            </div>
                                                        </div>
                                                    </div>`;
                    banderafotosVP=false;
            }else{
                console.log("NO EXISTE FOTO VEHICULO VERIFICAR RESPALDO")
                newRow.insertCell(8).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotos${newRow.rowIndex}">
                        <div class="form-group">
                            <input type="file" name="FotoVehiculo_row${newRow.rowIndex}" accept="image/*" id="fileFotoV_row${newRow.rowIndex}" class="inputfile uploadFileFotosV" onchange="uploadFile(event)" data-toggle="tooltip" data-placement="bottom">
                            <label for="fileFotoV_row${newRow.rowIndex}"></label>
                            <h3 class="uploadInvolucradoVHCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                        </div>
                    </div>
                    <div id="imageContentV_row${newRow.rowIndex}">
                    <label>NO EXISTE FOTO VEHICULO VERIFICAR RESPALDO</label>
                    </div>`;
            }
        }
    }else{
        newRow.insertCell(8).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotos${newRow.rowIndex}">
                                            <div class="form-group">
                                                <input type="file" name="FotoVehiculo_row${newRow.rowIndex}" accept="image/*" id="fileFotoV_row${newRow.rowIndex}" class="inputfile uploadFileFotosV" onchange="uploadFile(event)" data-toggle="tooltip" data-placement="bottom">
                                                <label for="fileFotoV_row${newRow.rowIndex}"></label>
                                                <h3 class="uploadInvolucradoVHCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                            </div>
                                        </div>
                                        <div id="imageContentV_row${newRow.rowIndex}">
                                        </div>`;

    }
    newRow.insertCell(9).innerHTML = Tipo_veh_invo;
    newRow.insertCell(10).innerHTML = Estado_Veh;
    newRow.insertCell(11).innerHTML = Capturo;
    newRow.insertCell(12).innerHTML =`<button type="button" class="btn btn-add" onclick="editVehiculos(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc" value="-" onclick="deleteRow(this,VehiculoTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
    newRow.insertCell(13).innerHTML = Ultima_Actualizacion;
    newRow.cells[13].style.display = "none";
}

let selectedRowVehiculos = null
const onFormVehiculoSubmit = async () => {//Funcion para insertar un nuevo elemento vehiculo a la tabla
    let DatosValidos = await ValidaDatosTablaVeh();
    if(DatosValidos){
        if (selectedRowVehiculos === null){
            InsertVehiculos();//INSERTA NUEVA FILA EN LA TABLA DE VEHICULOS
            resetFormVehiculos();//LIMPIA LA VISTA DE LOS CAMPOS ASOCIADOS DE LA TABLA DE VEHICULOS
        }else{
            updateRowVehiculos();//ACTUALIZA LA FILA SELECCIONADA EN LA TABLA DE VEHICULOS
            resetFormVehiculos();//LIMPIA LA VISTA DE LOS CAMPOS ASOCIADOS DE LA TABLA DE VEHICULOS
        }
    }
}
const ValidaDatosTablaVeh = async () =>{

    let valido = true
    document.getElementById('Tipo_Vehiculo_principales_error').innerText = (document.getElementById('Tipo_Vehiculo').value=="NA")?'Debe de especificar el tipo de vehiculo':'';
    if(document.getElementById('Tipo_Vehiculo').value=="NA"){ 
        valido = false;
    }
      
    document.getElementById('Tipo_Veh_Involucrado_error').innerText = (document.getElementById('Tipo_Veh_Involucrado').value=="SD")?'Debe de especificar el tipo de vehiculo involucrado':'';
    if(document.getElementById('Tipo_Veh_Involucrado').value=="SD"){
        valido = false;
    }

    if(document.getElementById('Marca').value.trim()!=""){//VALIDACION QUE LA MARCA SI ES VACIA NO EVALUAR CON EL CATALOGO
        marcaValida = await validateMarca(document.getElementById('Marca').value.toUpperCase());
        if(marcaValida != ""){
            document.getElementById('Marca_principales_error').innerText = marcaValida;
            valido = false;
        }else{
            document.getElementById('Marca_principales_error').innerText ="";
        }
    }else{
        document.getElementById('Marca_principales_error').innerText ="";
    }

    if(document.getElementById('Submarca').value.trim()!=""){//VALIDACION QUE LA MARCA SI ES VACIA NO EVALUAR CON EL CATALOGO
        submarcaValida = await validateSubmarca(document.getElementById('Submarca').value.toUpperCase());
        if(submarcaValida != ""){
            valido = false;
            document.getElementById('Submarca_principales_error').innerText = submarcaValida;
        }else{
            document.getElementById('Submarca_principales_error').innerText = "";
        }
    }else{
        document.getElementById('Submarca_principales_error').innerText = "";
    }

    return valido
}

const InsertVehiculos = () => {//INSERTA LOS DATOS CAPTURADOS EN LA VISTA EN LA TABLA DE VEHICULOS
    const table = document.getElementById('VehiculoTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    newRow.insertCell(0).innerHTML = document.getElementById('Id_Vehiculo').value;
    newRow.insertCell(1).innerHTML = document.getElementById('Tipo_Vehiculo').value;
    newRow.insertCell(2).innerHTML = (document.getElementById('Marca').value.trim()!="")?document.getElementById('Marca').value.toUpperCase():"SD";
    newRow.insertCell(3).innerHTML = (document.getElementById('Submarca').value.trim()!="")?document.getElementById('Submarca').value.toUpperCase():"SD";
    newRow.insertCell(4).innerHTML = document.getElementById('Modelo').value;
    if(document.getElementById('Placa_Vehiculo').value!=""){//FUNCION ESPECIAL PARA EL CAMPO PLACA QUE SOLO SE INSERTEN DATOS DE A-Z,a-z Y 0-9
        str=document.getElementById('Placa_Vehiculo').value;
        str=str.replace(/[^a-zA-Z0-9 ]+/g,'');
        newRow.insertCell(5).innerHTML = str.toUpperCase();  

    }else{
        newRow.insertCell(5).innerHTML = "SD"
    }
    if(document.getElementById('Color').value!=""){//FUNCION ESPECIAL PARA QUE EL CAMPO COLOR QUE SOLO SE INSERTEN DATOS DE A-Z Y a-z
        Color=document.getElementById('Color').value;
        Color=Color.replace(/[^a-zA-Z ]+/g,'');
        newRow.insertCell(6).innerHTML = Color.toUpperCase();
    }else{
        newRow.insertCell(6).innerHTML = "SD"
    }
    if(document.getElementById('Descripcion_gral').value!=""){
        let emojis = /(?:[\u2700-\u27bf]|(?:\ud83c[\udde6-\uddff]){2}|[\ud800-\udbff][\udc00-\udfff]|[\u0023-\u0039]\ufe0f?\u20e3|\u3299|\u3297|\u303d|\u3030|\u24c2|\ud83c[\udd70-\udd71]|\ud83c[\udd7e-\udd7f]|\ud83c\udd8e|\ud83c[\udd91-\udd9a]|\ud83c[\udde6-\uddff]|\ud83c[\ude01-\ude02]|\ud83c\ude1a|\ud83c\ude2f|\ud83c[\ude32-\ude3a]|\ud83c[\ude50-\ude51]|\u203c|\u2049|[\u25aa-\u25ab]|\u25b6|\u25c0|[\u25fb-\u25fe]|\u00a9|\u00ae|\u2122|\u2139|\ud83c\udc04|[\u2600-\u26FF]|\u2b05|\u2b06|\u2b07|\u2b1b|\u2b1c|\u2b50|\u2b55|\u231a|\u231b|\u2328|\u23cf|[\u23e9-\u23f3]|[\u23f8-\u23fa]|\ud83c\udccf|\u2934|\u2935|[\u2190-\u21ff])/g;
        let limpia= document.getElementById('Descripcion_gral').value.toUpperCase();
        limpia=limpia.replace(emojis , '');
        newRow.insertCell(7).innerHTML = limpia; 
    }else{
        newRow.insertCell(7).innerHTML = "SD"
    }
    newRow.insertCell(8).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotos${newRow.rowIndex}">
                                        <div class="form-group">
                                            <input type="file" name="FotoVehiculo_row${newRow.rowIndex}" accept="image/*" id="fileFotoV_row${newRow.rowIndex}" class="inputfile uploadFileFotosV" onchange="uploadFile(event)" data-toggle="tooltip" data-placement="bottom">
                                            <label for="fileFotoV_row${newRow.rowIndex}"></label>
                                            <h3 class="uploadInvolucradoVHCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                        </div>
                                    </div>
                                    <div id="imageContentV_row${newRow.rowIndex}"></div>`;
    newRow.insertCell(9).innerHTML = document.getElementById('Tipo_Veh_Involucrado').value.toUpperCase();
    newRow.insertCell(10).innerHTML = document.getElementById('Estado_Veh').value.toUpperCase();
    newRow.insertCell(11).innerHTML = document.getElementById('actualizaVP').value.toUpperCase();
    newRow.insertCell(12).innerHTML = `<button type="button" class="btn btn-add" onclick="editVehiculos(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc" value="-" onclick="deleteRow(this,VehiculoTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
    newRow.insertCell(13).innerHTML = document.getElementById('actualizaVP').value.toUpperCase();
    newRow.cells[13].style.display = "none";
}

const editVehiculos = (obj) => {//FUNCION QUE EDITA LA TABLA DE VEHICULOS TOMANDO LOS DATOS DE LA ROW SELECCIONADA Y ACOMODANDO LOS DATOS EN LA VISTA PARA SU EDICION
    document.getElementById('alertaEditVehiculos').style.display = 'block';
    selectedRowVehiculos = obj.parentElement.parentElement;
    document.getElementById('Id_Vehiculo').value = selectedRowVehiculos.cells[0].innerHTML;
    document.getElementById('Tipo_Vehiculo').value = selectedRowVehiculos.cells[1].innerHTML;
    document.getElementById('Marca').value = (selectedRowVehiculos.cells[2].innerHTML !='SD')?selectedRowVehiculos.cells[2].innerHTML:"";
    document.getElementById('Submarca').value = (selectedRowVehiculos.cells[3].innerHTML !='SD')?selectedRowVehiculos.cells[3].innerHTML:"";
    document.getElementById('Modelo').value = selectedRowVehiculos.cells[4].innerHTML;
    document.getElementById('Placa_Vehiculo').value = selectedRowVehiculos.cells[5].innerHTML;    
    document.getElementById('Color').value = selectedRowVehiculos.cells[6].innerHTML;
    document.getElementById('Descripcion_gral').value = selectedRowVehiculos.cells[7].innerHTML;

    document.getElementById('Tipo_Veh_Involucrado').value = selectedRowVehiculos.cells[9].innerHTML;
    document.getElementById('Estado_Veh').value = selectedRowVehiculos.cells[10].innerHTML;
}

const updateRowVehiculos = async() => {//FUNCION PARA ACTUALIZAR LOS DATOS DE LA TABLA DE VEHICULOS
    selectedRowVehiculos.cells[0].innerHTML = document.getElementById('Id_Vehiculo').value;
    selectedRowVehiculos.cells[1].innerHTML = document.getElementById('Tipo_Vehiculo').value;
    selectedRowVehiculos.cells[2].innerHTML = (document.getElementById('Marca').value.trim()!="")?document.getElementById('Marca').value.toUpperCase():"SD";
    selectedRowVehiculos.cells[3].innerHTML = (document.getElementById('Submarca').value.trim()!="")?document.getElementById('Submarca').value.toUpperCase():"SD";
    selectedRowVehiculos.cells[4].innerHTML = document.getElementById('Modelo').value;
    if(document.getElementById('Placa_Vehiculo').value!=""){
        str=document.getElementById('Placa_Vehiculo').value;
        str=str.replace(/[^a-zA-Z0-9 ]+/g,'');
        selectedRowVehiculos.cells[5].innerHTML = str.toUpperCase();
        
    }else{
        selectedRowVehiculos.cells[5].innerHTML ='SD';
    }
    if(document.getElementById('Color').value!=""){//FUNCION ESPECIAL PARA QUE EL CAMPO COLOR QUE SOLO SE INSERTEN DATOS DE A-Z Y a-z
        Color=document.getElementById('Color').value;
        Color=Color.replace(/[^a-zA-Z ]+/g,'');
        selectedRowVehiculos.cells[6].innerHTML = Color.toUpperCase();
    }else{
        selectedRowVehiculos.cells[6].innerHTML ='SD';
    }
    if(document.getElementById('Descripcion_gral').value!=""){ 
        let emojis = /(?:[\u2700-\u27bf]|(?:\ud83c[\udde6-\uddff]){2}|[\ud800-\udbff][\udc00-\udfff]|[\u0023-\u0039]\ufe0f?\u20e3|\u3299|\u3297|\u303d|\u3030|\u24c2|\ud83c[\udd70-\udd71]|\ud83c[\udd7e-\udd7f]|\ud83c\udd8e|\ud83c[\udd91-\udd9a]|\ud83c[\udde6-\uddff]|\ud83c[\ude01-\ude02]|\ud83c\ude1a|\ud83c\ude2f|\ud83c[\ude32-\ude3a]|\ud83c[\ude50-\ude51]|\u203c|\u2049|[\u25aa-\u25ab]|\u25b6|\u25c0|[\u25fb-\u25fe]|\u00a9|\u00ae|\u2122|\u2139|\ud83c\udc04|[\u2600-\u26FF]|\u2b05|\u2b06|\u2b07|\u2b1b|\u2b1c|\u2b50|\u2b55|\u231a|\u231b|\u2328|\u23cf|[\u23e9-\u23f3]|[\u23f8-\u23fa]|\ud83c\udccf|\u2934|\u2935|[\u2190-\u21ff])/g;
        let limpia= document.getElementById('Descripcion_gral').value.toUpperCase();
        limpia=limpia.replace(emojis , '');
        selectedRowVehiculos.cells[7].innerHTML =  limpia;
    }else{
        selectedRowVehiculos.cells[7].innerHTML = 'SD';
    }

    selectedRowVehiculos.cells[9].innerHTML =  document.getElementById('Tipo_Veh_Involucrado').value.toUpperCase();
    selectedRowVehiculos.cells[10].innerHTML =  document.getElementById('Estado_Veh').value.toUpperCase();
    selectedRowVehiculos.cells[13].innerHTML =  document.getElementById('actualizaVP').value.toUpperCase();
    document.getElementById('alertaEditVehiculos').style.display = 'none';
    selectedRowVehiculos= null;
}

const resetFormVehiculos = () => {//FUNCION QUE LIMPIA LOS CAMPOS ASOCIADOS A LA TABLA DE VEHICULO
    document.getElementById('Id_Vehiculo').value="SD";
    document.getElementById('Tipo_Vehiculo').value="NA";
    document.getElementById('Marca').value="";
    document.getElementById('Submarca').value="";
    document.getElementById('Modelo').value="SD";
    document.getElementById('Placa_Vehiculo').value="";    
    document.getElementById('Color').value="";
    document.getElementById('Descripcion_gral').value="";
    document.getElementById('Tipo_Veh_Involucrado').value="SD";
    document.getElementById('Estado_Veh').value="NO CORROBORADO";
}
/*--------------Funciones de autocomplete marca */
const inputMarca = document.getElementById('Marca');
inputMarca.addEventListener('input', () => {
    myFormData.append('termino', inputMarca.value)
    fetch(base_url_js + 'GestorCasos/getMarcas', {
            method: 'POST',
            body: myFormData
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `${r.Marca}`, value: `${r.Marca}`}))
        autocomplete({
            input: Marca,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {
                Marca.value = item.label.toUpperCase();
            }
        }); 
    })
    .catch(err => alert(`Ha ocurrido un error al obtener las Marcas.\nCódigo de error: ${ err }`))
});

const validateMarca = async (marca_buscar)=> {
    var MarcaValida = "";
    if(marca_buscar.length > 0){
        Marcas = await getAllMarca();
        const result = Marcas.find(element => element.Marca.toUpperCase() == marca_buscar);
        if (result){
            MarcaValida = true
        }
        if(MarcaValida==false){
            MarcaValida="Ingrese una Marca valida"
        }else{
            MarcaValida=""
        }  
    }
    return MarcaValida;
}
const getAllMarca = async () => {
    try {
        const response = await fetch(base_url_js + 'GestorCasos/getMarcas', {
            method: 'POST'
        });
        const data = await response.json();
        return data;
        
    } catch (error) {
        console.log(error);
    }
}
/*--------------Funciones de autocomplete submarca */
const inputSubmarca = document.getElementById('Submarca');
inputSubmarca.addEventListener('input', () => {
    myFormData.append('termino', inputSubmarca.value)
    fetch(base_url_js + 'GestorCasos/getSubmarcas', {
            method: 'POST',
            body: myFormData
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `${r.Submarca}`, value: `${r.Submarca}`}))
        autocomplete({
            input: Submarca,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {
                Submarca.value = item.label.toUpperCase();
            }
        }); 
    })
    .catch(err => alert(`Ha ocurrido un error al obtener las Submarca.\nCódigo de error: ${ err }`))
});

const validateSubmarca = async (submarca_buscar)=> {
    var SubmarcaValida = "";
    if(submarca_buscar.length > 0){
        Submarcas = await getAllSubmarca();
        const result = Submarcas.find(element => element.Submarca.toUpperCase() == submarca_buscar);
        if (result){
            SubmarcaValida = true
        }
        if(SubmarcaValida==false){
            SubmarcaValida="Ingrese una Submarca valida"
        }else{
            SubmarcaValida=""
        }  
    }
    return SubmarcaValida;
}
const getAllSubmarca = async () => {
    try {
        const response = await fetch(base_url_js + 'GestorCasos/getSubmarcas', {
            method: 'POST'
        });
        const data = await response.json();
        return data; 
    } catch (error) {
        console.log(error);
    }
}

const DesasociaVehInvolucrado = async (Id_Registro) =>{
    try {
        myFormData.append('Id_Registro',Id_Registro)
        myFormData.append('Folio_infra',document.getElementById('folio_infra_principales').value)
        const response = await fetch(base_url_js + 'GestorCasos/DesasociaVehInvolucrado', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
//Filtrado de parametros de entrada

document.getElementById("Marca").addEventListener("input", filtrarAlfaNumericos);
document.getElementById("Submarca").addEventListener("input", filtrarAlfaNumericos);
document.getElementById("Placa_Vehiculo").addEventListener("input", filtrarAlfaNumericos);
document.getElementById("Color").addEventListener("input", filtrarSoloLetras);