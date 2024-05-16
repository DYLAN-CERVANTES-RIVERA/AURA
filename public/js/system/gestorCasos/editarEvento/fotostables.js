/*----------------------ESTE ARCHIVO DE JAVASCRIPT ES PARA FUNCIONALIDADES DE LA TAB DE EDICION DE FOTOS---------------------------------------*/ 
function uploadFile(event, type) {//Funcion para actualizar las imagenes de las tablas
    let file;
    if (type) {
        file = 'Photo';
    } else {
        file = 'File';
    }
    console.log(file)
    if (event.currentTarget.classList.contains('uploadFileFotos')) {//TABLA DE FOTOS
        if (validateImage(event.target)) {
            const src = URL.createObjectURL(event.target.files[0]);
            const row = event.currentTarget;
            const index = row.parentNode.parentNode.parentNode.parentNode.rowIndex;
            createElementFoto(src, index, 'File');
        } else {
            document.getElementById('msg_fotosParticulares').innerHTML = '<div class="alert alert-warning text-center" role="alert">Verificar el archivo cargado.<br>Posibles errores: <br> - Archivo muy pesado (Máximo 8 megas). <br> -Extensión no aceptada (Extensiones aceptadas: jpeg, png, jpg, PNG).</div>';
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });
        }
    }
    if (event.currentTarget.classList.contains('uploadFileFotosV')) {//TABLA DE VEHICULOS
        if (validateImage(event.target)) {
            const src = URL.createObjectURL(event.target.files[0]);
            const row = event.currentTarget;
            const index = row.parentNode.parentNode.parentNode.parentNode.rowIndex;
            createElementFotoVehiculo(src, index, 'File');
        } else {
            document.getElementById('msg_fotosParticulares').innerHTML = '<div class="alert alert-warning text-center" role="alert">Verificar el archivo cargado.<br>Posibles errores: <br> - Archivo muy pesado (Máximo 8 megas). <br> -Extensión no aceptada (Extensiones aceptadas: jpeg, png, jpg, PNG).</div>';
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });
        }
    }
    if (event.currentTarget.classList.contains('uploadFileFotoP')) {//TABLA DE PERSONAS
        if (validateImage(event.target)) {
            const src = URL.createObjectURL(event.target.files[0]);
            const row = event.currentTarget;
            const index = row.parentNode.parentNode.parentNode.parentNode.rowIndex;
            createElementFotoInvolucrado(src, index, 'File');
        } else {
            document.getElementById('msg_fotosParticulares').innerHTML = '<div class="alert alert-warning text-center" role="alert">Verificar el archivo cargado.<br>Posibles errores: <br> - Archivo muy pesado (Máximo 8 megas). <br> -Extensión no aceptada (Extensiones aceptadas: jpeg, png, jpg, PNG).</div>';
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });
        }
    }
}

const validateImage = (image) => {//Valida la nueva imagen cargada con una extension y peso indicado  
    const size = image.files[0].size,
        allowedExtensions = /(.jpg|.jpeg|.png|.PNG)$/i;
    if (!allowedExtensions.exec(image.value)) {
        return false;
    }
    return true;
}

/*  FUNCIONALIDADES DE LA TABLA DE FOTOS */

function createElementFoto(src, index, type, view) {
    const div = document.getElementById('imageContent_row' + index);
    if (view === undefined) {
        div.innerHTML = `<div class="d-flex justify-content-end">
                            <span onclick="deleteImageFoto(${index})" class="deleteFile">X</span>
                        </div>
                        <img name="nor" src="${src}" id="images_row_${index}" width="250px" data-toggle="modal" data-target="#ModalCenterFotos2${index}">
                        <input type="hidden" class="${index} ${type}"/>
                        <div class="modal fade " id="ModalCenterFotos2${index}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <img name="nor" src="${src}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                            </div>
                        </div>`;
    } else {
        div.innerHTML = `<div>
                            <img2 src="${src}">
                            <input type="hidden" class="${index} ${type}"/>
                        </div>`;
    }
}

function deleteImageFoto(index) {
    const div = document.getElementById('imageContent_row' + index);
    document.getElementById('fileFoto_row' + index).value = '';
    div.innerHTML = '';
}

/*  FUNCIONALIDADES DE LAS IMAGENES DE LA TABLA DE VEHICULOS */

function createElementFotoVehiculo(src, index, type, view) {
    const div = document.getElementById('imageContentV_row' + index);
    if (view === undefined) {
        div.innerHTML = `<div class="d-flex justify-content-end">
                            <span onclick="deleteImageFotoV(${index})" class="deleteFile">X</span>
                        </div>
                        <img name="nor" src="${src}" id="imagesV_row_${index}" width="250px" data-toggle="modal" data-target="#ModalCenterVehiculo${index}">
                        <input type="hidden" class="${index} ${type}"/>
                        <div class="modal fade " id="ModalCenterVehiculo${index}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <img name="nor" src="${src}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                            </div>
                        </div>`;
    } else {
        div.innerHTML = `<div>
                            <img2 src="${src}">
                            <input type="hidden" class="${index} ${type}"/>
                        </div>`;
    }
}
function deleteImageFotoV(index) {
    const div = document.getElementById('imageContentV_row' + index);
    document.getElementById('fileFotoV_row' + index).value = '';
    div.innerHTML = '';
}

/*  FUNCIONALIDADES DE LA TABLA DE INVOLUCRADOS  */

function createElementFotoInvolucrado(src, index, type, view) {
    const div = document.getElementById('imageContentP_row' + index);
    if (view === undefined) {
        div.innerHTML = `<div class="d-flex justify-content-end">
                            <span onclick="deleteImageFotoP(${index})" class="deleteFile">X</span>
                        </div>
                        <img name="nor" src="${src}" id="imagesP_row_${index}" width="200px" data-toggle="modal" data-target="#ModalCenterInvolucrado${index}">
                        <input type="hidden" class="${index} ${type}"/>
                        <div class="modal fade " id="ModalCenterInvolucrado${index}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <img name="nor" src="${src}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                            </div>
                        </div>`;
    } else {
        div.innerHTML = `<div>
                            <img2 src="${src}">
                            <input type="hidden" class="${index} ${type}"/>
                        </div>`;
    }
}

function deleteImageFotoP(index) {
    const div = document.getElementById('imageContentP_row' + index);
    document.getElementById('fileFotoP_row' + index).value = '';
    div.innerHTML = '';
}


document.addEventListener('paste', async function(event) {
    var target = event.target;
    var index = target.parentNode.parentNode.parentNode.parentNode.rowIndex
    //console.log(target.parentNode.parentNode.parentNode.parentNode.rowIndex)
    // Verificar si el elemento en el que se pegará la imagen es un área de carga de imágenes
    if (target.classList.contains('uploadFileFotosCtrolV')) {
       
        var items = (event.clipboardData || event.originalEvent.clipboardData).items;
        for (var i = 0; i < items.length; i++) {
            
            if (items[i].type.indexOf('image') !== -1) {
                
                var blob = items[i].getAsFile();
                //console.log(blob)
                const src = await encodeFileAsBase64URL(blob);
                createElementFoto(src, index, 'Photo');
                console.log("Cambio de imagen fila "+ index)
               
                //console.log(base64URL)
            }
        }
    }
});