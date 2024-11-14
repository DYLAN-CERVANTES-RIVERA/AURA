/*----------------------ESTE ARCHIVO DE JAVASCRIPT ES PARA LA FUNCIONALIDAD DE LA TAB IDE IMAGENES---------------------------------------*/ 
var dataFotos = document.getElementById('datos_fotos')
var banderafotos=true;
document.addEventListener('DOMContentLoaded', async () => {
    Folio_infra = getEventotoSearch();
    await getCamaras();
});

document.getElementById('btn_principalFotos').addEventListener('click',async (e) => {//funcion del boton guardar Imagenes
    e.preventDefault()
    var myFormDataFotos = new FormData(dataFotos)
    myFormDataFotos.append('Folio_infra', Folio_infra)
    const elements = document.getElementsByClassName('uploadFileFotos');
    await readTableImagenes().then(res => {
        validateImages(res).then(resp => {
            if (resp) {
                button = document.getElementById('btn_principalFotos')
                button.innerHTML = `
                    Guardando
                    <div class="spinner-grow spinner-grow-sm" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                `;
                button.classList.add('disabled-link');//DESACTIVAMOS EL BOTON HASTA QUE EL SERVIDOR RESPONDA
                $('#ModalCenterFotosVideos').modal('show');//SE MUESTRA UNA IMAGEN AL CENTRO DE LA PAGINA 
                if(res['Fotos'].length>0){
                    myFormDataFotos.append('fotos_table', JSON.stringify(res['Fotos']));
                }
                myFormDataFotos.append('banderafotos', banderafotos);
                fetch(base_url_js + 'GestorCasos/updateFotos', {//Realiza la actualizacion de los datos
                        method: 'POST',
                        body: myFormDataFotos
                    })
                    .then(res => res.json())
                    .then(data => {
                        button.innerHTML = `Guardar Imagenes y Fotos`;
                        button.classList.remove('disabled-link');//Activa de nuevo el boton
                        $('#ModalCenterFotosVideos').modal('hide');
                        banderafotos=true;
                        if (!data.status) {
                            let messageError;
                            if ('error_message' in data) {
                                if (data.error_message != 'Render Index') {
                                    if (typeof(data.error_message) != 'string') {
                                        messageError = `<div class="alert alert-danger text-center" role="alert">Sucedio un error en el servidor: ${data.error_message.errorInfo[2]}</div>`;
                                    } else {
                                        messageError = `<div class="alert alert-danger text-center" role="alert">Sucedio un error en el servidor: ${data.error_message}</div>`;
                                    }
                                } else {
                                    messageError = `<div class="alert alert-danger text-center alert-session-create" role="alert">
                                                        <p>Sucedio un error, su sesión caduco o no tiene los permisos necesarios. Por favor vuelva a iniciar sesión.</p>
                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalLogin">
                                                            Iniciar sesión
                                                        </button>
                                                    </div>`;
                                }
                            } else {
                                messageError = '<div class="alert alert-danger text-center" role="alert">Por favor, revisa nuevamente el formulario</div>'
                            }
                            msg_fotos.innerHTML = messageError;
                            window.scroll({
                                top: 0,
                                left: 100,
                                behavior: 'smooth'
                            });
                        } else {//correcto
                            msg_fotos.innerHTML = `<div class="alert alert-success text-center" role="success">Imagenes de Videos y Fotos editados correctamente  
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>`;
                            refrescarDOMFotos();
                            window.scroll({
                                top: 0,
                                left: 100,
                                behavior: 'smooth'
                            }); 
                        }
                    })
            } else {
                window.scroll({
                    top: 0,
                    left: 100,
                    behavior: 'smooth'
                });
            }
        })
    });
})

const readTableImagenes = async() => {//lee el contenido de la tabla fotos
    const table = document.getElementById('fotosTable');
    let Fotos = [];
    if (table.rows.length > 1) {
        for (let i = 1; i < table.rows.length; i++) {
            const input = table.rows[i].cells[2].children[1].children[1];
            if (input != undefined) {
                const type = table.rows[i].cells[2].children[1].children[2].classList[1],
                    base64 = document.getElementById('images_row_' + i);
                nameImage = 'Foto_row' + i;
                if (type != 'File') {
                    isPNG = base64.src.split('.');
                    let scr=base64.src.split('?')
                    if (isPNG[1] != undefined) {
                        await toDataURL(scr[0])
                            .then(myBase64 => {
                                Fotos.push(dataImagesTable(table.rows[i].cells[0].innerHTML,table.rows[i].cells[1].innerHTML,table.rows[i].cells[3].innerHTML,table.rows[i].cells[4].innerHTML,table.rows[i].cells[5].innerHTML,table.rows[i].cells[6].innerHTML,table.rows[i].cells[7].innerHTML,table.rows[i].cells[8].innerHTML,table.rows[i].cells[9].innerHTML,table.rows[i].cells[10].innerHTML,table.rows[i].cells[11].innerHTML,table.rows[i].cells[12].innerHTML,table.rows[i].cells[13].innerHTML, type, nameImage, myBase64, myBase64,table.rows[i].cells[14].innerHTML,table.rows[i].cells[16].innerHTML));
                            })
                    } else {
                        Fotos.push(dataImagesTable(table.rows[i].cells[0].innerHTML,table.rows[i].cells[1].innerHTML,table.rows[i].cells[3].innerHTML,table.rows[i].cells[4].innerHTML,table.rows[i].cells[5].innerHTML,table.rows[i].cells[6].innerHTML,table.rows[i].cells[7].innerHTML,table.rows[i].cells[8].innerHTML,table.rows[i].cells[9].innerHTML,table.rows[i].cells[10].innerHTML,table.rows[i].cells[11].innerHTML,table.rows[i].cells[12].innerHTML,table.rows[i].cells[13].innerHTML, type, nameImage, base64.src, base64.src,table.rows[i].cells[14].innerHTML,table.rows[i].cells[16].innerHTML));
                    }
                } else {
                    //aqui solo en este caso va enviar  la funcion de file to base64
                    aux=document.getElementById('file'+nameImage)
                    if(aux.files[0]!=undefined){
                        let base64URL = await encodeFileAsBase64URL(aux.files[0]);
                        Fotos.push(dataImagesTable(table.rows[i].cells[0].innerHTML,table.rows[i].cells[1].innerHTML,table.rows[i].cells[3].innerHTML,table.rows[i].cells[4].innerHTML,table.rows[i].cells[5].innerHTML,table.rows[i].cells[6].innerHTML,table.rows[i].cells[7].innerHTML,table.rows[i].cells[8].innerHTML,table.rows[i].cells[9].innerHTML,table.rows[i].cells[10].innerHTML,table.rows[i].cells[11].innerHTML,table.rows[i].cells[12].innerHTML,table.rows[i].cells[13].innerHTML, type, nameImage,null,base64URL,table.rows[i].cells[14].innerHTML,table.rows[i].cells[16].innerHTML));     
                     
                    }  
                }
            } else {
                Fotos.push(dataImagesTable(table.rows[i].cells[0].innerHTML,table.rows[i].cells[1].innerHTML,table.rows[i].cells[3].innerHTML,table.rows[i].cells[4].innerHTML,table.rows[i].cells[5].innerHTML,table.rows[i].cells[6].innerHTML,table.rows[i].cells[7].innerHTML,table.rows[i].cells[8].innerHTML,table.rows[i].cells[9].innerHTML,table.rows[i].cells[10].innerHTML,table.rows[i].cells[11].innerHTML,table.rows[i].cells[12].innerHTML,table.rows[i].cells[13].innerHTML, null, null, null,null,table.rows[i].cells[14].innerHTML,table.rows[i].cells[16].innerHTML));
            }
        }
    }
    return {Fotos};
}

const dataImagesTable = (id_ubicacion,id_camara,descripcionFoto,ColoniaF,CalleF,Calle2F,no_ExtF,CPF,cordYF,cordXF,fecha_captura_foto,hora_captura_foto,fecha_hora_captura_sistema,typeImage, nameImage, dataImage,base64,capturo,Ultima_Actualizacion) => {//crea para la estructura para que esta se envie al modelo para la escritura en base de datos
    return {
        ['row']: {
            id_ubicacion:id_ubicacion,
            id_camara:id_camara,
            descripcion: descripcionFoto,
            ColoniaF:ColoniaF,
            CalleF:CalleF,
            Calle2F:Calle2F,
            no_ExtF:no_ExtF,
            CPF:CPF,
            cordYF:cordYF,
            cordXF:cordXF,
            fecha_captura_foto:fecha_captura_foto,
            hora_captura_foto:hora_captura_foto,
            fecha_hora_captura_sistema:fecha_hora_captura_sistema,
            typeImage: typeImage,
            nameImage: nameImage,
            image: dataImage,
            imagebase64:base64,
            capturo:capturo,
            Ultima_Actualizacion:Ultima_Actualizacion
        }
    }
}

const validateImages = async({Fotos}) => {//Si no existe foto en alguna row de la tabla fotos informa al usuario
    let band = true;
    await Fotos.forEach(element => {
        if (element.row.typeImage === null) {
            band = false;
            msg_fotos.innerHTML = `<div class="alert alert-danger text-center" role="alert">Por Favor, Ingrese Todas las Imagenes en la tabla
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                <span aria-hidden="true">&times;</span>
            </button>
        </div>`;
        window.scroll({
            top: 0,
            left: 100,
            behavior: 'smooth'
        });
        }
    });
    return band;
}

/*AUTOCOMPLETE PARA LA CAMARAS REGISTRADAS EN EL CATALOGO DE LAS CAMARAS DE LA CIUDAD */
var datosCamaras;
document.getElementById("id_puerta").addEventListener('input',async()=>{
    const arr = datosCamaras.map( r => ({ label: `CALLE:${r.Calle}, ${r.Calle2} ${r.Info_Adicional}` , value: `${r.Id_Dato}`}))
    autocomplete({
        input: id_puerta,
        fetch: function(text, update) {
            text = text.toLowerCase();
            const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
            update(suggestions);
        },
        onSelect: function(item) {
            id_puerta.value = item.value;
            onFormCamaraSubmit()
        }
    }); 
});

const getCamaras = async()=>{
    fetch(base_url_js + 'GestorCasos/getCamaras', {
            method: 'POST',
            body: myFormData
    })
    .then(res => res.json())
    .then(data => {
        datosCamaras = data;
    })
    .catch(err => alert(`Ha ocurrido un error al obtener las Camaras.\nCódigo de error: ${ err }`))
}

const onFormCamaraSubmit = async() => {//FUNCION QUE LLENA LA FORM DE IMAGENES
   dato = document.getElementById("id_puerta").value;
    for await (camara of datosCamaras) {
        if(camara.Id_Dato==dato){
            document.getElementById("id_puerta").value=""
            document.getElementById("cordYF").value = camara.CoordY;
            document.getElementById("cordXF").value = camara.CoordX;
            await getColoniasCalles2();
            document.getElementById("CalleF").value = camara.Calle;
            document.getElementById("Calle2F").value = camara.Calle2;

        }
    }
}
/* ----- ----- ----- Eventos Funcionalidades de la tabla de fotos y videos ----- ----- ----- */
let selectedRowFotos = null;//Define una actualizacion o una insercion en la tabla

const onFormFotosSubmit = async()=>{//El boton asociado dispara la funcion
    const campos = ['id_ubicacion','descripcionFoto','ColoniaF','CalleF','Calle2F','no_ExtF','CPF','cordYF','cordXF','id_camara','fecha_captura_foto','hora_captura_foto'];
    if(await validateFormFoto()){
        let formData = readFormDataFotos(campos);
        if(selectedRowFotos === null){
            insertNewRowFotos(formData);
        }else{
            updateRowFotos(formData);
        }
            
        resetFormFotos(campos);
    }
}

const readFormDataFotos = (campos)=>{
    let formData = {};
    for(let i=0; i<campos.length;i++){
            formData[campos[i]] = document.getElementById(campos[i]).value;
        }
    return formData;
}

const insertNewRowFotos = ({id_ubicacion,descripcionFoto,ColoniaF,CalleF,Calle2F,no_ExtF,CPF,cordYF,cordXF,id_camara,fecha_captura_foto,hora_captura_foto})=>{//Funcion para insertar una nueva foto a la tabla
    const table = document.getElementById('fotosTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    newRow.insertCell(0).innerHTML = id_ubicacion.toUpperCase();
    newRow.insertCell(1).innerHTML = id_camara;
    newRow.insertCell(2).innerHTML = `<div class="d-flex justify-content-around" id="uploadContent_row${newRow.rowIndex}">
                                        <div class="form-group">
                                            <input type="file" name="Foto_row${newRow.rowIndex}" accept="image/*" id="fileFoto_row${newRow.rowIndex}" class="inputfile uploadFileFotos" onchange="uploadFile(event)" data-toggle="tooltip" data-placement="bottom">
                                            <label for="fileFoto_row${newRow.rowIndex}" ></label>
                                            <h3 class="uploadFileFotosCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                        </div>
                                    </div>
                                    <div id="imageContent_row${newRow.rowIndex}"></div>`;
    
    newRow.insertCell(3).innerHTML = descripcionFoto.toUpperCase();
    newRow.insertCell(4).innerHTML = ColoniaF.toUpperCase();
    newRow.insertCell(5).innerHTML = CalleF.toUpperCase();
    newRow.insertCell(6).innerHTML = (Calle2F.trim()=='')?'SD':Calle2F.toUpperCase();
    newRow.insertCell(7).innerHTML = (no_ExtF.trim()=='')?'0':no_ExtF;
    newRow.insertCell(8).innerHTML = (CPF.trim()=='')?'0':CPF;
    newRow.insertCell(9).innerHTML = (cordYF.trim()=='')?'0':cordYF;
    newRow.insertCell(10).innerHTML = (cordXF.trim()=='')?'0':cordXF;

    newRow.insertCell(11).innerHTML= fecha_captura_foto;
    newRow.insertCell(12).innerHTML= hora_captura_foto;
    newRow.insertCell(13).innerHTML= getFechaActual();
    newRow.insertCell(14).innerHTML= document.getElementById('actualizaFotos').value.toUpperCase();
    newRow.insertCell(15).innerHTML =`<button type="button" class="btn btn-add" onclick="editFotos(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc" value="-" onclick="deleteFoto(this)">
                                        <i class="material-icons">delete</i>
                                    </button>`; 
    document.getElementById('descripcionFoto').value = '';
    newRow.insertCell(16).innerHTML = document.getElementById('actualizaVP').value.toUpperCase();
    newRow.cells[16].style.display = "none";
}

const editFotos = async(obj)=>{//Funcion para editar la informacion de la tabla fotos
    document.getElementById('alertEditFoto').style.display = 'block';
    selectedRowFotos = obj.parentElement.parentElement;

    if(selectedRowFotos.cells[9].innerHTML!='SD' && selectedRowFotos.cells[10].innerHTML!='SD'){
        document.getElementById('cordYF').value = (selectedRowFotos.cells[9].innerHTML=='0')?'':selectedRowFotos.cells[9].innerHTML;
        document.getElementById('cordXF').value = (selectedRowFotos.cells[10].innerHTML=='0')?'':selectedRowFotos.cells[10].innerHTML;
        let validaX = await ValidaCoordX(document.getElementById('cordXF').value);
        let validaY = await ValidaCoordY(document.getElementById('cordYF').value);
    
        if(validaX == "" && validaY == ""){
            await getColoniasCalles2();
        }
        
        document.getElementById('CPF').value = "";
        document.getElementById('no_ExtF').value = "";
    }
    document.getElementById('id_ubicacion').value = selectedRowFotos.cells[0].innerHTML;
    document.getElementById('id_camara').value = selectedRowFotos.cells[1].innerHTML;
    document.getElementById('descripcionFoto').value = selectedRowFotos.cells[3].innerHTML;
    document.getElementById('ColoniaF').value = selectedRowFotos.cells[4].innerHTML;
    document.getElementById('CalleF').value = selectedRowFotos.cells[5].innerHTML;

    document.getElementById('Calle2F').value = (selectedRowFotos.cells[6].innerHTML=='SD')?'':selectedRowFotos.cells[6].innerHTML;
    document.getElementById('no_ExtF').value = (selectedRowFotos.cells[7].innerHTML=='0')?'':selectedRowFotos.cells[7].innerHTML;
    document.getElementById('CPF').value = (selectedRowFotos.cells[8].innerHTML=='0')?'':selectedRowFotos.cells[8].innerHTML;
  

    document.getElementById('fecha_captura_foto').value = selectedRowFotos.cells[11].innerHTML;
    document.getElementById('hora_captura_foto').value = selectedRowFotos.cells[12].innerHTML;
    window.scroll({
        top: 0,
        left: 100,
        behavior: 'smooth'
    });

}

const updateRowFotos = ({id_ubicacion,descripcionFoto,ColoniaF,CalleF,Calle2F,no_ExtF,CPF,cordYF,cordXF,id_camara,fecha_captura_foto,hora_captura_foto})=>{//Funcion para actualizar la informacion de la tabla fotos
    
    selectedRowFotos.cells[0].innerHTML = id_ubicacion;
    selectedRowFotos.cells[1].innerHTML = id_camara;
    selectedRowFotos.cells[3].innerHTML = descripcionFoto.toUpperCase();
    selectedRowFotos.cells[4].innerHTML = ColoniaF.toUpperCase();
    selectedRowFotos.cells[5].innerHTML = CalleF.toUpperCase();
    selectedRowFotos.cells[6].innerHTML = (Calle2F.trim()=='')?'SD':Calle2F.toUpperCase();
    selectedRowFotos.cells[7].innerHTML = (no_ExtF=='')?'0':no_ExtF;
    selectedRowFotos.cells[8].innerHTML = (CPF=='')?'0':CPF;
    selectedRowFotos.cells[9].innerHTML = (cordYF=='')?'0':cordYF;
    selectedRowFotos.cells[10].innerHTML = (cordXF=='')?'0':cordXF;

    selectedRowFotos.cells[11].innerHTML = fecha_captura_foto;
    selectedRowFotos.cells[12].innerHTML = hora_captura_foto;
    selectedRowFotos.cells[16].innerHTML = document.getElementById('actualizaVP').value.toUpperCase();
    document.getElementById('alertEditFoto').style.display = 'none';
}

const resetFormFotos = (campos)=>{
    if(confirm('¿Desea conservar la ubicacion Capturada?')){
        document.getElementById('descripcionFoto').value='';
        
    }else{
        document.getElementById('id_ubicacion').value='NA';
        
        for(let i=1;i<campos.length;i++){
            if(campos[i]!='fecha_captura_foto' && campos[i]!='hora_captura_foto'){

                document.getElementById(campos[i]).value='';
            }

        }
        document.getElementById('id_camara').value='NA';
        map2.flyTo({
            center: [-98.20868494860592, 19.040296987811555],
            zoom: 11,
            essential: true
            });
            marker2.setLngLat([-98.20868494860592, 19.040296987811555])
    }
    selectedRowFotos = null;
}

const deleteFoto = (obj)=>{//Funcion especial para eliminar fila de la tabla fotos
    if(confirm('¿Desea eliminar este elemento?')){
        const row = obj.parentElement.parentElement;
        aux=row.rowIndex;
        table = document.getElementById('fotosTable');
        table.deleteRow(row.rowIndex);
        j=aux+1;
        for(let i=aux;i<table.rows.length;i++){
            let contenedorImg = table.rows[i].cells[2].childNodes[2];
            //console.log(contenedorImg)
            contenedorImg.setAttribute('id', 'imageContent_row'+i);
            
            if(contenedorImg.childNodes.length>1){
                if(contenedorImg.childNodes[1].childNodes[1]!=undefined){

                    contenedorImg.childNodes[1].childNodes[1].setAttribute('onclick', "deleteImageFoto("+i+")");
                    contenedorImg.childNodes[3].setAttribute('id', 'images_row_'+i);
                    contenedorImg.childNodes[3].setAttribute('data-target', '#ModalCenterFotos2'+i);
                    console.log(contenedorImg.childNodes[5])
                    if(contenedorImg.childNodes[5].getAttribute('class')==j+' Photo'){
                        contenedorImg.childNodes[5].setAttribute('class', i+' Photo');
                        console.log("foto")
                    }else{
                        contenedorImg.childNodes[5].setAttribute('class', i+' File');
                        console.log("file")
                    }
                    contenedorImg.childNodes[7].setAttribute('id', 'ModalCenterFotos2'+i);
                }else{

                    contenedorImg.childNodes[0].childNodes[1].setAttribute('onclick', "deleteImageFoto("+i+")");
                    contenedorImg.childNodes[2].setAttribute('id', 'images_row_'+i);
                    contenedorImg.childNodes[2].setAttribute('data-target', '#ModalCenterFotos2'+i);
                    console.log(contenedorImg.childNodes[4])

                    if(contenedorImg.childNodes[4].getAttribute('class')==j+' Photo'){
                        contenedorImg.childNodes[4].setAttribute('class', i+' Photo');
                        console.log("foto1")
                    }else{
                        contenedorImg.childNodes[4].setAttribute('class', i+' File');
                        console.log("file1")
                    }
                    contenedorImg.childNodes[6].setAttribute('id', 'ModalCenterFotos2'+i);
                }
            }
            let contenedorInput = table.rows[i].cells[2].childNodes[0];
            contenedorInput.setAttribute('id', 'uploadContent_row'+i);
            contenedorInput.childNodes[1].childNodes[1].setAttribute('id', 'fileFoto_row'+i);
            contenedorInput.childNodes[1].childNodes[1].setAttribute('name', 'Foto_row'+i);
            contenedorInput.childNodes[1].childNodes[3].setAttribute('for', 'fileFoto_row'+i);
            j++;//este es para que compare el elemento en el que estaba
        }
    }
}

const validateFormFoto = async() =>{//funcion para validar los campos antes de ser agregados  la tablas
    let isValid = true;
    let band = []
    let i=0;
    band[i++] = document.getElementById('descripcionFoto_error').innerText =(document.getElementById('descripcionFoto').value.trim() == "")?"Ingrese la Descripcion de la Imagen":"";
    band[i++] = document.getElementById('Colonia_fotos_error').innerText =(document.getElementById('ColoniaF').value.trim() == "")?"Ingrese la Colonia":"";
    band[i++] = document.getElementById('Calle_fotos_error').innerText =(document.getElementById('CalleF').value.trim() == "")?"Ingrese la Calle":"";
    band[i++] = document.getElementById('id_ubicacion_error').innerText =(document.getElementById('id_ubicacion').value == "NA")?"Seleccione un id de ubicacion":"";
    band[i++] = document.getElementById('id_camara_error').innerText =(document.getElementById('id_camara').value == "NA")?"Seleccione un id de camara":"";

    band[i++] = document.getElementById('cordY_fotos_error').innerText = await ValidaCoordY(document.getElementById('cordYF').value);
    band[i++] = document.getElementById('cordX_fotos_error').innerText = await ValidaCoordX(document.getElementById('cordXF').value);
    band.forEach(element => {
        isValid &= (element == '') ? true : false
    })
    return isValid;
}

//Filtrado de parametros de entrada
document.getElementById("id_puerta").addEventListener("input", filtrarAlfaNumericos);
document.getElementById("ColoniaF").addEventListener("input", filtrarAlfaNumericos);
document.getElementById("CalleF").addEventListener("input", filtrarAlfaNumericos);
document.getElementById("Calle2F").addEventListener("input", filtrarAlfaNumericos);

document.getElementById('CPF').addEventListener("input", filtrarSoloNumeros);
document.getElementById('no_ExtF').addEventListener("input", filtrarAlfaNumericos);

document.getElementById("cordYF").addEventListener("input", filtraCoordPositiva);
document.getElementById("cordXF").addEventListener("input", filtraCoordNegativa);

