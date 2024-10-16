document.addEventListener('DOMContentLoaded',async() => {
    Id_Punto = getPuntoSearch();
   
    let dataPunto = await getInfoPunto(Id_Punto);
    let datosPunto = await getDatosPunto(Id_Punto);
    vectores = await getAllVectores();
    await getRemisiones();
    
    llenarVista(dataPunto);
    
    if(datosPunto.length > 0){
        for await(let datoPunto of datosPunto){
            let formDataPunto = {
                Id_Dato_Punto : datoPunto.Id_Dato_Punto,
                Id_Punto: datoPunto.Id_Punto,
                Tipo_Dato : datoPunto.Tipo_Dato,
                Descripcion_Dato : datoPunto.Descripcion_Dato,
                Img_64_Dato : datoPunto.Img_64_Dato,
                Path_Imagen_Dato : datoPunto.Path_Imagen_Dato,
                Capturo : datoPunto.Capturo
            }
            await InsertgetDatosUbi(formDataPunto);//Inserta todas las personas del seguimiento
        }
    }
});
const randomNum = Math.random();
const llenarVista = async (data) =>{
    document.getElementById('Id_Punto').value = data.Id_Punto;
    document.getElementById('Capturo').value = data.Capturo;
    document.getElementById('Fecha_Captura').value = data.Fecha_Captura;

    if(data.CoordY!='' && data.CoordX!=''){
        let validaX = await ValidaCoordX(data.CoordX);
        let validaY = await ValidaCoordY(data.CoordY);

        if(validaX == "" && validaY == ""){
            document.getElementById('cordY').value = data.CoordY;
            document.getElementById('cordX').value = data.CoordX;
            await getColoniasCalles();
        }else{
            document.getElementById('cordY').value = data.CoordY;
            document.getElementById('cordX').value = data.CoordX;
        }
    }

    document.getElementById('Colonia').value = data.Colonia;
    document.getElementById('Calle').value = data.Calle;
    document.getElementById('Calle2').value = data.Calle2;
    document.getElementById('no_Ext').value = data.NoExt;
    document.getElementById('CP').value = data.CP;


    document.getElementById('zona').value = data.Zona;
    zonaValue = document.getElementById('zona').value.split(' ');
    if(document.getElementById('zona').value.includes('ZONA')){
        zonaValue = document.getElementById('zona').value.split(' ');
        vectoresFiltrados = vectores.filter((vector) => vector.Zona == zonaValue[1]);
        //console.log(vectoresFiltrados);
    }else {
        zonaValue = 'CH';
        vectoresFiltrados = vectores.filter((vector) => vector.Zona == zonaValue);
    }
    if(zona.value == "NA"){
        vectoresFiltrados = [{Id_vector_Interno : 'SELECCIONE', Zona: 0, Region: 'LA ZONA'}]
    }
    vectoresFiltrados.forEach(vectorE => {
        vector.innerHTML += `<option value="${vectorE.Id_vector_Interno}">${vectorE.Id_vector_Interno} - ${vectorE.Region}</option>`
    })
    vector.value = data.Vector
    document.getElementById('vector').value = data.Vector;

    document.getElementById('Fuente_info').value = data.Fuente_Info;
    document.getElementById('Estatus_Punto').value = data.Estatus_Punto;
    document.getElementById('Identificador').value = data.Identificador;

    if(data.Fuente_Info =='DETENIDO'){
        document.getElementById('Info_detenido').classList.remove('mi_hide');
        document.getElementById('nombre').value = data.Nombre_Detenido;
        document.getElementById('Narrativa').value = data.Narrativa;
        if(data.Remision.trim() != '' && data.Remision > 0 && data.Remision != null){
            
            document.getElementById('id_rem_1').checked = true;
            document.getElementById('id_Remision_panel').classList.remove('mi_hide');
            document.getElementById('id_remision').value = data.Remision;
        }
    }
    document.getElementById('fecha_obtencion').value = data.Fecha_Punto;
    document.getElementById('Info_Adicional').value = data.Descripcion_Adicional;
    document.getElementById('Distribuidor').value = data.Distribuidor;
    document.getElementById('Grupo_OP').value = data.Grupo_OP;
    document.getElementById('Atendido_Por').value = data.Atendido_Por;
    document.getElementById('Enlace_Google').value = data.Enlace_Google;
    
    if(data.Path_Img_Google!='SD' && data.Path_Img_Google != null){
        let ruta = `${base_url_js}public/files/Puntos/${data.Id_Punto}/${data.Path_Img_Google}`;
        let ban = await imageExists(ruta)
        let div = document.getElementById('imageContentMaps');
        if(ban==true){
            ruta = ruta+'?nocache='+randomNum;
            div.innerHTML = `<div class="d-flex justify-content-end">
                                <span onclick="deleteImageFotoMaps()" class="deleteFile">X</span>
                            </div>
                            <img name="nor" src="${ruta}" id="imagesMaps" width="500px" data-toggle="modal" data-target="#ModalCenterMaps">
                            <input type="hidden" class="Photo"/>
                            <div class="modal fade " id="ModalCenterMaps" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <img name="nor" src="${ruta}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                </div>
                            </div>`;
        }else{
            div.innerHTML = `<div class="d-flex justify-content-end">
                                <span onclick="deleteImageFotoMaps()" class="deleteFile">X</span>
                            </div>
                            <img name="nor" src="${data.Img_64_Google}" id="imagesMaps" width="500px" data-toggle="modal" data-target="#ModalCenterMaps">
                            <input type="hidden" class="Photo"/>
                            <div class="modal fade " id="ModalCenterMaps" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <img name="nor" src="${data.Img_64_Google}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                </div>
                            </div>`;
        }
    }

    if(data.Path_Img!='SD' && data.Path_Img != null){
        let ruta = `${base_url_js}public/files/Puntos/${data.Id_Punto}/${data.Path_Img}`;
        let ban = await imageExists(ruta)
        let div = document.getElementById('imageContentUbi');
        if(ban==true){
            ruta = ruta+'?nocache='+ randomNum;
            div.innerHTML = `<div class="d-flex justify-content-end">
                            <span onclick="deleteImageFotoUbi()" class="deleteFile">X</span>
                        </div>
                        <img name="nor" src="${ruta}" id="imagesUbis" width="500px" data-toggle="modal" data-target="#ModalCenterUbi">
                        <input type="hidden" class="Photo"/>
                        <div class="modal fade " id="ModalCenterUbi" tabindex="-1" role="dialog" aria-labelledby="exampleModalUbi" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <img name="nor" src="${ruta}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalUbi">
                            </div>
                        </div>`;
    
        }else{
            div.innerHTML = `<div class="d-flex justify-content-end">
                                <span onclick="deleteImageFotoUbi()" class="deleteFile">X</span>
                            </div>
                            <img name="nor" src="${data.Img_64}" id="imagesUbis" width="500px" data-toggle="modal" data-target="#ModalCenterUbi">
                            <input type="hidden" class="Photo"/>
                            <div class="modal fade " id="ModalCenterUbi" tabindex="-1" role="dialog" aria-labelledby="exampleModalUbi" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <img name="nor" src="${data.Img_64}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalUbi">
                                </div>
                            </div>`;
        }
    }
}
const RecargaPrincipales = async (data) =>{

    if(data.CoordY!='' && data.CoordX!=''){
        let validaX = await ValidaCoordX(data.CoordX);
        let validaY = await ValidaCoordY(data.CoordY);

        if(validaX == "" && validaY == ""){
            document.getElementById('cordY').value = data.CoordY;
            document.getElementById('cordX').value = data.CoordX;
            await getColoniasCalles();
        }else{
            document.getElementById('cordY').value = data.CoordY;
            document.getElementById('cordX').value = data.CoordX;
        }
    }

    document.getElementById('Colonia').value = data.Colonia;
    document.getElementById('Calle').value = data.Calle;
    document.getElementById('Calle2').value = data.Calle2;
    document.getElementById('no_Ext').value = data.NoExt;
    document.getElementById('CP').value = data.CP;


    document.getElementById('zona').value = data.Zona;
        zonaValue = document.getElementById('zona').value.split(' ');
        if(document.getElementById('zona').value.includes('ZONA')){
            zonaValue = document.getElementById('zona').value.split(' ');
            vectoresFiltrados = vectores.filter((vector) => vector.Zona == zonaValue[1]);
            //console.log(vectoresFiltrados);
        }else {
            zonaValue = 'CH';
            vectoresFiltrados = vectores.filter((vector) => vector.Zona == zonaValue);
        }
        if(zona.value == "NA"){
            vectoresFiltrados = [{Id_vector_Interno : 'SELECCIONE', Zona: 0, Region: 'LA ZONA'}]
        }
        vectoresFiltrados.forEach(vectorE => {
            vector.innerHTML += `<option value="${vectorE.Id_vector_Interno}">${vectorE.Id_vector_Interno} - ${vectorE.Region}</option>`
        })
        vector.value = data.Vector
    document.getElementById('vector').value = data.Vector;

    document.getElementById('Fuente_info').value = data.Fuente_Info;
    document.getElementById('Estatus_Punto').value = data.Estatus_Punto;
    document.getElementById('Identificador').value = data.Identificador;

    if(data.Fuente_Info =='DETENIDO'){
        document.getElementById('Info_detenido').classList.remove('mi_hide');
        document.getElementById('nombre').value = data.Nombre_Detenido;
        document.getElementById('Narrativa').value = data.Narrativa;
        if(data.Remision.trim() != '' && data.Remision > 0 && data.Remision != null){
            
            document.getElementById('id_rem_1').checked = true;
            document.getElementById('id_Remision_panel').classList.remove('mi_hide');
            document.getElementById('id_remision').value = data.Remision;
        }
    }
    document.getElementById('fecha_obtencion').value = data.Fecha_Punto;
    document.getElementById('Info_Adicional').value = data.Descripcion_Adicional;
    document.getElementById('Distribuidor').value = data.Distribuidor;
    document.getElementById('Grupo_OP').value = data.Grupo_OP;
    document.getElementById('Atendido_Por').value = data.Atendido_Por;
    document.getElementById('Enlace_Google').value = data.Enlace_Google;
    
    if(data.Path_Img_Google!='SD'){
        let ruta = `${base_url_js}public/files/Puntos/${data.Id_Punto}/${data.Path_Img_Google}`;
        let ban = await imageExists(ruta)
        let div = document.getElementById('imageContentMaps');
        if(ban==true){
            ruta = ruta+'?nocache='+randomNum;
            div.innerHTML = `<div class="d-flex justify-content-end">
                                <span onclick="deleteImageFotoMaps()" class="deleteFile">X</span>
                            </div>
                            <img name="nor" src="${ruta}" id="imagesMaps" width="500px" data-toggle="modal" data-target="#ModalCenterMaps">
                            <input type="hidden" class="Photo"/>
                            <div class="modal fade " id="ModalCenterMaps" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <img name="nor" src="${ruta}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                </div>
                            </div>`;
        }else{
            div.innerHTML = `<div class="d-flex justify-content-end">
                                <span onclick="deleteImageFotoMaps()" class="deleteFile">X</span>
                            </div>
                            <img name="nor" src="${data.Img_64_Google}" id="imagesMaps" width="500px" data-toggle="modal" data-target="#ModalCenterMaps">
                            <input type="hidden" class="Photo"/>
                            <div class="modal fade " id="ModalCenterMaps" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <img name="nor" src="${data.Img_64_Google}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                </div>
                            </div>`;
        }
    }

    if(data.Path_Img!='SD'){
        let ruta = `${base_url_js}public/files/Puntos/${data.Id_Punto}/${data.Path_Img}`;
        let ban = await imageExists(ruta)
        let div = document.getElementById('imageContentUbi');
        if(ban==true){
            ruta = ruta+'?nocache='+ randomNum;
            div.innerHTML = `<div class="d-flex justify-content-end">
                            <span onclick="deleteImageFotoUbi()" class="deleteFile">X</span>
                        </div>
                        <img name="nor" src="${ruta}" id="imagesUbis" width="500px" data-toggle="modal" data-target="#ModalCenterUbi">
                        <input type="hidden" class="Photo"/>
                        <div class="modal fade " id="ModalCenterUbi" tabindex="-1" role="dialog" aria-labelledby="exampleModalUbi" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <img name="nor" src="${ruta}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalUbi">
                            </div>
                        </div>`;
    
        }else{
            div.innerHTML = `<div class="d-flex justify-content-end">
                                <span onclick="deleteImageFotoUbi()" class="deleteFile">X</span>
                            </div>
                            <img name="nor" src="${data.Img_64}" id="imagesUbis" width="500px" data-toggle="modal" data-target="#ModalCenterUbi">
                            <input type="hidden" class="Photo"/>
                            <div class="modal fade " id="ModalCenterUbi" tabindex="-1" role="dialog" aria-labelledby="exampleModalUbi" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <img name="nor" src="${data.Img_64}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalUbi">
                                </div>
                            </div>`;
        }
    }
}

const RecargaDom = async () =>{
    let dataPunto = await getInfoPunto(document.getElementById('Id_Punto').value);
    await RecargaPrincipales(dataPunto);
    await RecargaTablaDatos();
}
const RecargaTablaDatos = async () =>{
    let datosPunto = await getDatosPunto(document.getElementById('Id_Punto').value);
    await dropTablaContentDatos();
    //eliminar y acomodar
    if(datosPunto.length > 0){
        for await(let datoPunto of datosPunto){
            let formDataPunto = {
                Id_Dato_Punto : datoPunto.Id_Dato_Punto,
                Id_Punto: datoPunto.Id_Punto,
                Tipo_Dato : datoPunto.Tipo_Dato,
                Descripcion_Dato : datoPunto.Descripcion_Dato,
                Img_64_Dato : datoPunto.Img_64_Dato,
                Path_Imagen_Dato : datoPunto.Path_Imagen_Dato,
                Capturo : datoPunto.Capturo
            }
            await InsertgetDatosUbi(formDataPunto);//Inserta todas las personas del seguimiento
        }
    }

}

const dropTablaContentDatos= async () => {//VACIA EL CONTENIDO DE LA TABLA DE FORENSIAS SIN TOCAR EL ENCABEZADO
    ban= true;
    table = document.getElementById('DatosUbiTable');
    aux=document.getElementById('contardatosUbi').rows.length;
    for(let i = 1; i < aux+1; i++){
        table.deleteRow(1);
    }
    return ban;
}

const InsertgetDatosUbi = async({Id_Dato_Punto, Id_Punto, Tipo_Dato, Descripcion_Dato, Img_64_Dato, Path_Imagen_Dato, Capturo})=>{//FUNCION QUE INSERTA LOS DATOS DE LAS PERSONAS ASOCIADAS AL SEGUIMIENTO
    let pathImages = base_url_js+'public/files/Puntos/'+Id_Punto+'/Datos/';
    let table = document.getElementById('DatosUbiTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    if(Path_Imagen_Dato!='SD'){
        let ruta = pathImages+Path_Imagen_Dato;
        let ban = await imageExists(ruta)
        if(ban==true){
            
            ruta = ruta+'?nocache='+ randomNum;
            newRow.insertCell(0).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoDato${newRow.rowIndex}">
                                                    <div class="form-group">
                                                        <input type="file" name="FotoDato_row${newRow.rowIndex}" accept="image/*" id="fileFotoDato_row${newRow.rowIndex}" class="inputfile uploadFileFotoDato" onchange="uploadFileDato(event)" data-toggle="tooltip" data-placement="bottom">
                                                        <label for="fileFotoDato_row${newRow.rowIndex}"></label>
                                                        <h3 class="uploadFotoDatoCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                                    </div>
                                                </div>
                                                <div id="imageContentDato_row${newRow.rowIndex}">
                                                    <div class="d-flex justify-content-end">
                                                        <span onclick="deleteImageFotoDato(${newRow.rowIndex})" class="deleteFile">X</span>
                                                    </div>
                                                    <img name="nor" src="${ruta}" id="imagesDato_row_${newRow.rowIndex}" width="400px" data-toggle="modal" data-target="#ModalCenterDatoUbi${newRow.rowIndex}">
                                                    <input type="hidden" class="${newRow.rowIndex} Photo"/>
                                                    <div class="modal fade " id="ModalCenterDatoUbi${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                            <img name="nor" src="${ruta}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                                        </div>
                                                    </div> 
                                                </div>`;

        }else{
            if(Img_64_Dato!='SD' && Img_64_Dato!=null){
                newRow.insertCell(0).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoDato${newRow.rowIndex}">
                                                    <div class="form-group">
                                                        <input type="file" name="FotoDato_row${newRow.rowIndex}" accept="image/*" id="fileFotoDato_row${newRow.rowIndex}" class="inputfile uploadFileFotoDato" onchange="uploadFileDato(event)" data-toggle="tooltip" data-placement="bottom">
                                                        <label for="fileFotoDato_row${newRow.rowIndex}"></label>
                                                        <h3 class="uploadFotoDatoCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                                    </div>
                                                </div>
                                                <div id="imageContentDato_row${newRow.rowIndex}">
                                                    <div class="d-flex justify-content-end">
                                                        <span onclick="deleteImageFotoDato(${newRow.rowIndex})" class="deleteFile">X</span>
                                                    </div>
                                                    <img name="nor" src="${Img_64_Dato}" id="imagesDato_row_${newRow.rowIndex}" width="400px" data-toggle="modal" data-target="#ModalCenterDatoUbi${newRow.rowIndex}">
                                                    <input type="hidden" class="${newRow.rowIndex} Photo"/>
                                                    <div class="modal fade " id="ModalCenterDatoUbi${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                            <img name="nor" src="${Img_64_Dato}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                                        </div>
                                                    </div> 
                                                </div>`;

            }else{
                newRow.insertCell(0).innerHTML = `<div class="d-flex justify-content-around" id="uploadFileFotoDato${newRow.rowIndex}">
                                                    <div class="form-group">
                                                        <input type="file" name="FotoDato_row${newRow.rowIndex}" accept="image/*" id="fileFotoDato_row${newRow.rowIndex}" class="inputfile uploadFileFotoDato" onchange="uploadFileDato(event)" data-toggle="tooltip" data-placement="bottom">
                                                        <label for="fileFotoDato_row${newRow.rowIndex}"></label>
                                                        <h3 class="uploadFotoDatoCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                                    </div>
                                                </div>
                                                <div id="imageContentDato_row${newRow.rowIndex}"></div>`;
            }
        }
        
    }else{
        
        newRow.insertCell(0).innerHTML = `<div class="d-flex justify-content-around" id="uploadFileFotoDato${newRow.rowIndex}">
                                            <div class="form-group">
                                                <input type="file" name="FotoDato_row${newRow.rowIndex}" accept="image/*" id="fileFotoDato_row${newRow.rowIndex}" class="inputfile uploadFileFotoDato" onchange="uploadFileDato(event)" data-toggle="tooltip" data-placement="bottom">
                                                <label for="fileFotoDato_row${newRow.rowIndex}"></label>
                                                <h3 class="uploadFotoDatoCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                            </div>
                                        </div>
                                        <div id="imageContentDato_row${newRow.rowIndex}"></div>`;
    }





    newRow.insertCell(1).innerHTML = Descripcion_Dato.toUpperCase();
    newRow.insertCell(2).innerHTML = Tipo_Dato;
    newRow.insertCell(3).innerHTML = Capturo;
    newRow.insertCell(4).innerHTML = `<button type="button" class="btn btn-add" onclick="editDatoUbi(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc" value="-" onclick="deleteRowDatoUbi(this,DatosUbiTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
    newRow.insertCell(5).innerHTML = Id_Dato_Punto;
    newRow.insertCell(6).innerHTML = await getFechaActual();
    newRow.cells[5].style.display = "none";
    newRow.cells[6].style.display = "none";
}

const getAllVectores = async () => {
    try{
        const response = await fetch(base_url_js + 'Puntos/getAllVector', {
            method: 'POST',
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}

/*---------------------------FUNCIONES PARA LA OBTENCION DE LA INFORMACION DEL PUNTO PRINCIPAL------------------- */
const getPuntoSearch = () => {//OBTIENE EL ID DEL PUNTO QUE SE ESTA EDITARA
    const params = new Proxy(new URLSearchParams(window.location.search),
     {
        get: (searchParams,
             prop) => searchParams.get(prop),
      });
      let value = params.Id_Punto; 
      return value;
}

const getInfoPunto = async (Id_Punto) => {//FUNCION QUE OBTINE LOS DATOS PRINCIPALES DEL PUNTO
    try {
        var myFormData = new FormData();
        myFormData.append('Id_Punto',Id_Punto);
        const response = await fetch(base_url_js + 'Puntos/getInfoPunto', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
} 
const getDatosPunto = async (Id_Punto) => {//FUNCION QUE OBTINE LOS DATOS PRINCIPALES DEL PUNTO
    try {
        var myFormData = new FormData();
        myFormData.append('Id_Punto',Id_Punto);
        const response = await fetch(base_url_js + 'Puntos/getDatosPunto', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
} 

/*----------------------------FUNCIONES DE ACTUALIZACION DE FOTOS -------------------------------------*/
function uploadFileMaps(event) {//FUNCION PARA ACTUALIZAR LA IMAGEN 
    document.getElementById('msg_principales_puntos').innerHTML = '';
    if (event.currentTarget.classList.contains('uploadFileFotoMaps')) {//FOTO DEL GRUPO DELICTIVO
        if (validateImage(event.target)) {
            const src = URL.createObjectURL(event.target.files[0]);
            createElementMaps(src, 'File');
        } else {
            document.getElementById('msg_principales_puntos').innerHTML = '<div class="alert alert-warning text-center" role="alert">Verificar el archivo cargado.<br>Posibles errores: <br> - Archivo muy pesado (Máximo 8 megas). <br> -Extensión no aceptada (Extensiones aceptadas: jpeg, png, jpg, PNG).</div>';
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });
        }
    }
}

function uploadFileUbi(event) {//FUNCION PARA ACTUALIZAR LA IMAGEN 
    document.getElementById('msg_principales_puntos').innerHTML = '';
    if (event.currentTarget.classList.contains('uploadFileFotoUbi')) {//FOTO DEL GRUPO DELICTIVO
        if (validateImage(event.target)) {
            const src = URL.createObjectURL(event.target.files[0]);
            createElementUbi(src, 'File');
        } else {
            document.getElementById('msg_principales_puntos').innerHTML = '<div class="alert alert-warning text-center" role="alert">Verificar el archivo cargado.<br>Posibles errores: <br> - Archivo muy pesado (Máximo 8 megas). <br> -Extensión no aceptada (Extensiones aceptadas: jpeg, png, jpg, PNG).</div>';
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });
        }
    }
}

function uploadFileDato(event) {//FUNCION PARA ACTUALIZAR LA IMAGEN 
    document.getElementById('msg_principales_puntos').innerHTML = '';
    if (event.currentTarget.classList.contains('uploadFileFotoDato')) {//TABLA DE PERSONAS
        if (validateImage(event.target)) {
            const src = URL.createObjectURL(event.target.files[0]);
            const row = event.currentTarget;
            const index = row.parentNode.parentNode.parentNode.parentNode.rowIndex;
            createElementDatoUbicacion(src, index, 'File');
        } else {
            document.getElementById('msg_principales_puntos').innerHTML = '<div class="alert alert-warning text-center" role="alert">Verificar el archivo cargado.<br>Posibles errores: <br> - Archivo muy pesado (Máximo 8 megas). <br> -Extensión no aceptada (Extensiones aceptadas: jpeg, png, jpg, PNG).</div>';
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });
        }
    }
}
function createElementDatoUbicacion(src, index, type) {//FUNCION PARA LA VISUALIZACION DE IMAGENES EN LA TABAL DE PERSONAS
    console.log(index);
    const div = document.getElementById('imageContentDato_row' + index);
   
        div.innerHTML = `<div class="d-flex justify-content-end">
                            <span onclick="deleteImageFotoDato(${index})" class="deleteFile">X</span>
                        </div>
                        <img name="nor" src="${src}" id="imagesDato_row_${index}" width="400px" data-toggle="modal" data-target="#ModalCenterDatoUbi${index}">
                        <input type="hidden" class="${index} ${type}"/>
                        <div class="modal fade " id="ModalCenterDatoUbi${index}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <img name="nor" src="${src}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                            </div>
                        </div>`;
 
}
function deleteImageFotoDato(index) {//FUNCION QUE ELIMINA LA VISUALIZACION Y EL CONTENIDO DE LAS IMAGENES
    const div = document.getElementById('imageContentDato_row' + index);
    document.getElementById('fileFotoDato_row' + index).value = '';
    div.innerHTML = '';
}

function createElementMaps(src, type) {//FUNCION PARA VIZUALIZAR Y CONTENER LA IMAGEN CARGADA
    let div = document.getElementById('imageContentMaps');
    div.innerHTML = `<div class="d-flex justify-content-end">
                        <span onclick="deleteImageFotoMaps()" class="deleteFile">X</span>
                    </div>
                    <img name="nor" src="${src}" id="imagesMaps" width="500px" data-toggle="modal" data-target="#ModalCenterMaps">
                    <input type="hidden" class=" ${type}"/>
                    <div class="modal fade " id="ModalCenterMaps" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <img name="nor" src="${src}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                        </div>
                    </div>`;
}
function deleteImageFotoMaps() {//FUNCION PARA ELIMINAR LA IMAGEN DEL GRUPO DELICTIVO
    let div = document.getElementById('imageContentMaps');
    document.getElementById('fileFotoMaps').value = '';
    div.innerHTML = '';
}
function createElementUbi(src, type) {//FUNCION PARA VIZUALIZAR Y CONTENER LA IMAGEN CARGADA
    let div = document.getElementById('imageContentUbi');
    div.innerHTML = `<div class="d-flex justify-content-end">
                        <span onclick="deleteImageFotoUbi()" class="deleteFile">X</span>
                    </div>
                    <img name="nor" src="${src}" id="imagesUbis" width="500px" data-toggle="modal" data-target="#ModalCenterUbi">
                    <input type="hidden" class=" ${type}"/>
                    <div class="modal fade " id="ModalCenterUbi" tabindex="-1" role="dialog" aria-labelledby="exampleModalUbi" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <img name="nor" src="${src}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalUbi">
                        </div>
                    </div>`;
}
function deleteImageFotoUbi() {//FUNCION PARA ELIMINAR LA IMAGEN DEL GRUPO DELICTIVO
    let div = document.getElementById('imageContentUbi');
    document.getElementById('fileFotoUbi').value = '';
    div.innerHTML = '';
}

const validateImage = (image) => {//Valida la nueva imagen cargada con una extension y peso indicado  
    const size = image.files[0].size,
        allowedExtensions = /(.jpg|.jpeg|.png|.PNG)$/i;
    if (!allowedExtensions.exec(image.value)) {
        return false;
    }
    return true;
}
async function encodeFileAsBase64URL(file) {//FUNCION PARA CODIFICAR EN BASE 64 LA IMAGEN CARGADA 
    if (file.size > 8 * 1024 * 1024) { // 8 MB en bytes
        throw new Error('El archivo excede el tamaño máximo de 8 MB.');
    }
    return new Promise((resolve1) => {
        let reader2 = new FileReader();
        reader2.addEventListener('loadend', () => {
            resolve1(reader2.result);
        });
        reader2.readAsDataURL(file);
    });
};
//funciones para leer las fotos en base64 en las tablas
const toDataURL =async url => fetch(url)
    .then(res => res.blob())
    .then(blob => new Promise((resolve) => {
        let reader = new FileReader();
        reader.addEventListener('loadend', async() => {
            resolve(reader.result);
        });
        reader.readAsDataURL(blob);
    }))
    
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
document.addEventListener('paste', async function(event) {
    var target = event.target;
    try{
        if (target.classList.contains('uploadFotoMapsCtrolV')) {
            var items = (event.clipboardData || event.originalEvent.clipboardData).items;
            for (var i = 0; i < items.length; i++) {
                if (items[i].type.indexOf('image') !== -1) {
                    var blob = items[i].getAsFile();
                    const src = await encodeFileAsBase64URL(blob);
                    createElementMaps(src, 'Photo');
                }
            }
        }

        if (target.classList.contains('uploadFotoUbiCtrolV')) {
            var items = (event.clipboardData || event.originalEvent.clipboardData).items;
            for (var i = 0; i < items.length; i++) {
                if (items[i].type.indexOf('image') !== -1) {
                    var blob = items[i].getAsFile();
                    const src = await encodeFileAsBase64URL(blob);
                    createElementUbi(src, 'Photo');
                }
            }
        }

        if (target.classList.contains('uploadFotoDatoCtrolV')) {
            var index = target.parentNode.parentNode.parentNode.parentNode.rowIndex
            var items = (event.clipboardData || event.originalEvent.clipboardData).items;
            for (var i = 0; i < items.length; i++) {
                if (items[i].type.indexOf('image') !== -1) {
                    var blob = items[i].getAsFile();
                    const src = await encodeFileAsBase64URL(blob);
                    createElementDatoUbicacion(src, index, 'Photo');
                }
            }
        }
    } catch (error) {
        Swal.fire({
            title: "ERROR AL PEGAR IMAGEN VERIFICA EL TAMAÑO MAXIMO 8MB",
            icon: 'info',
            confirmButtonText: 'OK',
            customClass: {
                confirmButton: 'custom-confirm-btn'  // Clase CSS personalizada para el botón de confirmación
            },
            buttonsStyling: false
        });
        console.error(error.message);
    }
});

const deleteRowDatoUbi = async(obj, tableId) => {//funcion para eliminar una fila en tablas ademas de funcion especial de eliminacion para las tablas personas
    if (confirm('¿Desea eliminar este elemento?')) {
        let row = obj.parentElement.parentElement;
        aux=row.rowIndex;
        if(row.cells[5].innerHTML!='SD'){
            await DesasociaDato(row.cells[5].innerHTML,document.getElementById('Id_Punto').value);
        }
        document.getElementById(tableId.id).deleteRow(row.rowIndex);
        j=aux+1;
        if(tableId.id=='DatosUbiTable'){//Si es la tabla de personas hace una eliminacion especial debido a las fotos
            let table = document.getElementById('DatosUbiTable')
            j=aux+1;
            for(let i=aux;i<table.rows.length;i++){
                let contenedorImg =table.rows[i].cells[0].children[1];
                contenedorImg.setAttribute('id', 'imageContentDato_row'+i);
                if(contenedorImg.childNodes.length>1){
                    if(contenedorImg.childNodes[1].childNodes[1]!=undefined){
                        contenedorImg.childNodes[1].childNodes[1].setAttribute('onclick', "deleteImageFotoDato("+i+")");
                        contenedorImg.childNodes[3].setAttribute('id', 'imagesDato_row_'+i);
                        contenedorImg.childNodes[3].setAttribute('data-target', '#ModalCenterUbi'+i);
                        if(contenedorImg.childNodes[5].getAttribute('class')==j+' Photo'){
                            contenedorImg.childNodes[5].setAttribute('class', i+' Photo');
                        }else{
                            contenedorImg.childNodes[5].setAttribute('class', i+' File');
                        }
                        contenedorImg.childNodes[7].setAttribute('id', 'ModalCenterUbi'+i);
                    }else{
                        contenedorImg.childNodes[0].childNodes[1].setAttribute('onclick', "deleteImageFotoDato("+i+")");
                        contenedorImg.childNodes[2].setAttribute('id', 'imagesDato_row_'+i);
                        contenedorImg.childNodes[2].setAttribute('data-target', '#ModalCenterUbi'+i);
    
                        if(contenedorImg.childNodes[4].getAttribute('class')==j+' Photo'){
                            contenedorImg.childNodes[4].setAttribute('class', i+' Photo');
                        }else{
                            contenedorImg.childNodes[4].setAttribute('class', i+' File');
                        }
                        contenedorImg.childNodes[6].setAttribute('id', 'ModalCenterUbi'+i);
                    }  
                }
                let contenedorInput =table.rows[i].cells[0].children[0];
                contenedorInput.setAttribute('id', 'uploadFileFotoDato'+i);
                contenedorInput.childNodes[1].childNodes[1].setAttribute('id', 'fileFotoDato_row'+i);
                contenedorInput.childNodes[1].childNodes[1].setAttribute('name', 'FotoDato_row'+i);
                contenedorInput.childNodes[1].childNodes[3].setAttribute('for', 'fileFotoDato_row'+i);
                j++;
            }
        }
        await RecargaTablaDatos();
    }
}
const DesasociaDato = async(Id_Dato_Punto,Id_Punto)=>{//FUNCION QUE ELIMINA LOS DATOS DE LAS TABLAS DE FORENSIA
    try {
        myFormData.append('Id_Dato_Punto',Id_Dato_Punto)
        myFormData.append('Id_Punto',Id_Punto)
        const response = await fetch(base_url_js + 'Puntos/DesasociaDato', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}