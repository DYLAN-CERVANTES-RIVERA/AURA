//Modulo de edicion de gestor de casos obtener fotos
/*----------------------ESTE ARCHIVO DE JAVASCRIPT ES PARA CARGAR LOS DATOS DE LA TAB DE EDICION DE FOTOS---------------------------------------*/ 
document.addEventListener('DOMContentLoaded', async () => { //Funcion que Recupera el id del registro que se visualizara
    caso = await getEventotoSearch();
    //Genera la tabla de visualizacion fotos
    fotos =  await getFotos(caso);
    //Genera la tabla de visualizacion videos
    if(fotos!=undefined && fotos!=[]){
        for await (foto of fotos) {//Forma correcta de ir renglon por renglo en la insercion de datos existentes
      
            let formDataFotos = {
                Folio_infra: foto.Folio_infra,
                Descripcion: foto.Descripcion,
                Path_Imagen: foto.Path_Imagen,
                id_ubicacion: foto.id_ubicacion,
                ColoniaF: foto.ColoniaF,
                CalleF: foto.CalleF,
                Calle2F: foto.Calle2F,
                no_ExtF: foto.no_ExtF,
                CPF: foto.CPF,
                cordYF: foto.cordYF,
                cordXF: foto.cordXF,
                id_camara: foto.id_camara,
                fecha_captura_foto: foto.fecha_captura_foto,
                hora_captura_foto: foto.hora_captura_foto,
                fecha_hora_captura_sistema: foto.fecha_hora_captura_sistema,
                img_64: foto.img_64,
                Capturo:foto.Capturo,
                Ultima_Actualizacion:foto.Ultima_Actualizacion
            }
          insertNewRowTablaFotos(formDataFotos);//Inserta todos los vehiculos del evento
        }
    }
});
const refrescarDOMFotos= async()=>{
    let aux = document.getElementById('contarImagenes').rows.length+1
    for(let i = 1; i < aux; i++){
        document.getElementById('fotosTable').deleteRow(1);
    }
    caso = await getEventotoSearch();
    //Genera la tabla de visualizacion fotos
    fotos =  await getFotos(caso);
    //Genera la tabla de visualizacion videos
    if(fotos!=undefined && fotos!=[]){
        for await (foto of fotos) {//Forma correcta de ir renglon por renglo en la insercion de datos existentes
      
            let formDataFotos = {
                Folio_infra: foto.Folio_infra,
                Descripcion: foto.Descripcion,
                Path_Imagen: foto.Path_Imagen,
                id_ubicacion: foto.id_ubicacion,
                ColoniaF: foto.ColoniaF,
                CalleF: foto.CalleF,
                Calle2F: foto.Calle2F,
                no_ExtF: foto.no_ExtF,
                CPF: foto.CPF,
                cordYF: foto.cordYF,
                cordXF: foto.cordXF,
                id_camara: foto.id_camara,
                fecha_captura_foto: foto.fecha_captura_foto,
                hora_captura_foto: foto.hora_captura_foto,
                fecha_hora_captura_sistema: foto.fecha_hora_captura_sistema,
                img_64: foto.img_64,
                Capturo:foto.Capturo,
                Ultima_Actualizacion:foto.Ultima_Actualizacion
            }
          insertNewRowTablaFotos(formDataFotos);//Inserta todos los vehiculos del evento
        }
    }
}

const getFotos = async (caso) => { //Funcion para realizar una peticion para la consulta a la tabla  
    try {
        var myFormData = new FormData();
        myFormData.append('Folio_infra',caso)
        const response = await fetch(base_url_js + 'GestorCasos/getFotos', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
} 
const insertNewRowTablaFotos = async({ Folio_infra,Descripcion,Path_Imagen,id_ubicacion,ColoniaF,CalleF,Calle2F,no_ExtF,CPF,cordYF,cordXF,id_camara,fecha_captura_foto,hora_captura_foto,fecha_hora_captura_sistema,img_64,Capturo,Ultima_Actualizacion}) => {//FUNCION QUE INSERTA LOS DATOS DEL EVENTO EN LA TABLA DE FOTO
    pathImagesFotos = `${base_url_js}public/files/GestorCasos/${Folio_infra}/Seguimiento/`;
    const table = document.getElementById('fotosTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    srcImage = Path_Imagen;
    srcImage = srcImage.split('?')
    //Empieza generar la tabla de vizualizacion de fotos
    newRow.insertCell(0).innerHTML =id_ubicacion;
    newRow.insertCell(1).innerHTML = id_camara;
    let ruta=pathImagesFotos + srcImage[0]///que sean variables declaradas de tipo let hace que no te sobrescriba la ultima foto en todas las rows
    ban = await imageExists(ruta)
    if(ban==true){
        ruta = ruta+'?nocache='+ getRandomInt(100);
        newRow.insertCell(2).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotos${newRow.rowIndex}">
            <div class="form-group">
                <input type="file" name="Foto_row${newRow.rowIndex}" accept="image/*" id="fileFoto_row${newRow.rowIndex}" class="inputfile uploadFileFotos" onchange="uploadFile(event)" data-toggle="tooltip" data-placement="bottom">
                <label for="fileFoto_row${newRow.rowIndex}"></label>
            </div>
        </div>
        <div id="imageContent_row${newRow.rowIndex}">
            <div class="d-flex justify-content-end">
                <span onclick="deleteImageFoto(${newRow.rowIndex})" class="deleteFile">X</span>
            </div>
            <img name="nor" src="${ruta}" id="images_row_${newRow.rowIndex}" width="250px" data-toggle="modal" data-target="#ModalCenterFotos2${newRow.rowIndex}">
            <input type="hidden" class="${newRow.rowIndex} Photo"/>
            <div class="modal fade " id="ModalCenterFotos2${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <img name="nor" src="${ruta}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                </div>
            </div>
        </div>`;
    }else{
        
        if(img_64!='SD'&&img_64!=' '&&img_64!=''){
            newRow.insertCell(2).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotos${newRow.rowIndex}">
                                                <div class="form-group">
                                                    <input type="file" name="Foto_row${newRow.rowIndex}" accept="image/*" id="fileFoto_row${newRow.rowIndex}" class="inputfile uploadFileFotos" onchange="uploadFile(event)" data-toggle="tooltip" data-placement="bottom">
                                                    <label for="fileFoto_row${newRow.rowIndex}"></label>
                                                </div>
                                            </div>
                                            <div id="imageContent_row${newRow.rowIndex}">
                                                <div class="d-flex justify-content-end">
                                                    <span onclick="deleteImageFoto(${newRow.rowIndex})" class="deleteFile">X</span>
                                                </div>
                                                <img name="nor" src="${img_64}" id="images_row_${newRow.rowIndex}" width="250px" data-toggle="modal" data-target="#ModalCenterFotos2${newRow.rowIndex}">
                                                <input type="hidden" class="${newRow.rowIndex} Photo"/>
                                                <div class="modal fade " id="ModalCenterFotos2${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                        <img name="nor" src="${img_64}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                                    </div>
                                                </div>
                                            </div>`;

            banderafotos=false;//para que haga un respaldo de todas las fotos ya que no existen fisicamente si le da guardar 
        }else{

            newRow.insertCell(2).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotos${newRow.rowIndex}">
                    <div class="form-group">
                        <input type="file" name="Foto_row${newRow.rowIndex}" accept="image/*" id="fileFoto_row${newRow.rowIndex}" class="inputfile uploadFileFotos" onchange="uploadFile(event)" data-toggle="tooltip" data-placement="bottom">
                        <label for="fileFoto_row${newRow.rowIndex}"></label>
                    </div>
                </div>
                <div id="imageContent_row${newRow.rowIndex}">
                <label>NO EXISTE FOTO VERIFICAR RESPALDO</label>
                </div>`;//esto se debe a que existe un nombre asociado al registro pero no existe fisicamente ni logicamente revisar la carpeta de respaldo en todo caso
        }
    }
   
    newRow.insertCell(3).innerHTML = Descripcion.toUpperCase();
    newRow.insertCell(4).innerHTML = ColoniaF;
    newRow.insertCell(5).innerHTML = CalleF;
    newRow.insertCell(6).innerHTML = Calle2F;
    newRow.insertCell(7).innerHTML = no_ExtF;
    newRow.insertCell(8).innerHTML = CPF;
    newRow.insertCell(9).innerHTML = cordYF;
    newRow.insertCell(10).innerHTML = cordXF; 
    newRow.insertCell(11).innerHTML = fecha_captura_foto; 
    newRow.insertCell(12).innerHTML = hora_captura_foto; 
    newRow.insertCell(13).innerHTML = fecha_hora_captura_sistema;
    newRow.insertCell(14).innerHTML = Capturo;                                      
    newRow.insertCell(15).innerHTML = `<button type="button" class="btn btn-add" onclick="editFotos(this)"> 
                                    <i class="material-icons">edit</i>
                                </button>
                                <button type="button" class="btn btn-ssc" value="-" onclick="deleteFoto(this)">
                                    <i class="material-icons">delete</i>
                                </button>`;
    newRow.insertCell(16).innerHTML = Ultima_Actualizacion;
    newRow.cells[16].style.display = "none";
}