/*----------------FUNCION PARA TRAER EL CATALOGO DE MUNICIPIOS------------------------*/
const Estado = document.getElementById('Estado');
Estado.addEventListener('change', () => { 
    document.getElementById('Municipio').value=''
});
const Municipio=document.getElementById('Municipio');
Municipio.addEventListener('input', () => { 
        input_elegido=document.getElementById('Municipio')
        termino=(document.getElementById('Municipio')).value
        estado=(document.getElementById('Estado')).value
   
    const myFormData_muni =  new FormData();
    myFormData_muni.append('termino', termino)
    myFormData_muni.append('estado', estado)
    fetch(base_url_js + 'Seguimientos/getMunicipios', {
            method: 'POST',
            body: myFormData_muni
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `${r.Municipio}`, value: `${r.Municipio}` }))
        autocomplete({
            input: input_elegido,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {
                input_elegido.value = item.label;
            }
        }); 
    })
    .catch(err => alert(`Ha ocurrido un error al obtener las colonias.\nCódigo de error: ${ err }`))  
});
const getAllEstados = async () => {//FUNCION QUE OBTIENE LOS ESATDOS DE MEXICO
    try {
        const response = await fetch(base_url_js + 'Entrevistas/getEstadosMexico', {
            method: 'POST'
        });
        const data = await response.json();
        return data;
        
    } catch (error) {
        console.log(error);
    }
}

const getAllColonias = async () => {//FUNCION QUE OBTIENE TODAS COLONIAS
    try {
        const response = await fetch(base_url_js + 'Catalogos/getColonias', {
            method: 'POST'
        });
        const data = await response.json();
        return data;
        
    } catch (error) {
        console.log(error);
    }
}
const createObjectColonia = (colonia) => {//FUNCION QUE CONVIERTE LA CADENA INGRESADA EN EL CAMPO COLONIA A DOS ELEMENTOS PARA LA COMPARACION EN EL CATALOGO
    separado = colonia.split(' ');
    objetoColonia = {
        Tipo: '',
        Colonia: ''
    }
    if(separado){
        objetoColonia.Tipo = separado[0];
        for(let i = 1; i<separado.length; i++){
            objetoColonia.Colonia += separado[i]+' ';
        }
    }
    objetoColonia.Colonia = objetoColonia.Colonia.trim();
    return objetoColonia
}
const getAllCalles = async () => {//FUNCION QUE OBTIENE TODAS LAS CALLES
    try {
        const response = await fetch(base_url_js + 'Catalogos/getAllCalles', {
            method: 'POST'
        });
        const data = await response.json();
        return data;
        
    } catch (error) {
        console.log(error);
    }
}

const getUbicaciones = async (Id_Persona_Entrevista) => { //Funcion que realizar peticion para obtener los datos de las forensias de las personas del seguimiento
    try {
        myFormData.append('Id_Persona_Entrevista',Id_Persona_Entrevista)
        const response = await fetch(base_url_js + 'Entrevistas/getUbicaciones', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const dropTablaContentUbicacionesEntrevistas = async () => {//VACIA EL CONTENIDO DE LA TABLA DE FORENSIAS SIN TOCAR EL ENCABEZADO
    ban= true;
    table = document.getElementById('UbicacionTable');
    aux=document.getElementById('contarUbicacion').rows.length;
    for(let i = 1; i < aux+1; i++){
        table.deleteRow(1);
    }
    return ban;
}
/*---------------------FUNCIONES DE REFRESCO--------------------------- */
const RecargaDatosUbicacion = async()=>{//Funcion que actualiza la vista de la tabla forensias cada vez que se guarden o eliminen datos
    await dropTablaContentUbicacionesEntrevistas();
    let Ubicaciones=await getUbicaciones(Persona_Entrevista);
    for await(let Ubicacion of Ubicaciones){
        let formDataUbicacion = {
            Id_Ubicaciones_Entrevista: Ubicacion.Id_Ubicaciones_Entrevista ,
            Id_Persona_Entrevista: Ubicacion.Id_Persona_Entrevista,
            Id_Dato: Ubicacion.Id_Dato,
            Colonia: Ubicacion.Colonia,
            Calle: Ubicacion.Calle,
            Calle2: Ubicacion.Calle2,
            NumExt: Ubicacion.NumExt,
            NumInt: Ubicacion.NumInt,
            CP: Ubicacion.CP,
            CoordX: Ubicacion.CoordX,
            CoordY: Ubicacion.CoordY,
            Observaciones_Ubicacion: Ubicacion.Observaciones_Ubicacion,
            Link_Ubicacion: Ubicacion.Link_Ubicacion,
            Estado: Ubicacion.Estado,
            Municipio: Ubicacion.Municipio,
            Foraneo: Ubicacion.Foraneo,
            Capturo : Ubicacion.Capturo,
            Foto : Ubicacion.Foto,
            Img_64 : Ubicacion.Img_64,
            Tipo_Relacion: Ubicacion.Tipo_Relacion
        }
       await InsertgetUbicacion(formDataUbicacion);//Inserta todos las Ubicaciones de las personas del seguimiento
    }
}

const InsertgetUbicacion= async({Id_Ubicaciones_Entrevista ,Id_Persona_Entrevista,Id_Dato,Colonia,Calle,Calle2,NumExt,NumInt,CP,CoordX,CoordY,Observaciones_Ubicacion,Link_Ubicacion,Estado,Municipio,Foraneo,Capturo,Foto,Img_64,Tipo_Relacion})=>{//Funcion que inserta los datos obtenidos en la tabla de forensias
    let pathImagesUbicaciones =base_url_js+'public/files/Entrevistas/'+Id_Persona_Entrevista+'/UbicacionesRelevantes/';
    let table = document.getElementById('UbicacionTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length); 
   // newRow.insertCell(0).innerHTML =  Id_Ubicaciones_Entrevista
    newRow.insertCell(0).innerHTML = Id_Dato;
    newRow.insertCell(1).innerHTML = Tipo_Relacion;
    
    
    newRow.insertCell(2).innerHTML = Colonia;
    newRow.insertCell(3).innerHTML = Calle;
    newRow.insertCell(4).innerHTML = Calle2;
    newRow.insertCell(5).innerHTML = NumExt;
    newRow.insertCell(6).innerHTML = NumInt;
    newRow.insertCell(7).innerHTML = CP;
    newRow.insertCell(8).innerHTML = CoordY;
    newRow.insertCell(9).innerHTML = CoordX;
    newRow.insertCell(10).innerHTML = Estado;
    newRow.insertCell(11).innerHTML = Municipio;
    newRow.insertCell(12).innerHTML = Foraneo;
    newRow.insertCell(13).innerHTML = Observaciones_Ubicacion;
    newRow.insertCell(14).innerHTML = Link_Ubicacion;
    if(Foto!='SD'){
        let ruta = pathImagesUbicaciones+Foto;
        let ban = await imageExists(ruta)
        if(ban==true){
          ruta = ruta+'?nocache='+getRandomInt(50);
          newRow.insertCell(15).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoUbicacion${newRow.rowIndex}">
                                            <div class="form-group">
                                                <input type="file" name="FotoUbicacion_row${newRow.rowIndex}" accept="image/*" id="fileFotoUbicacion_row${newRow.rowIndex}" class="inputfile uploadFileFotoUbicacion" onchange="uploadFileUbicacion(event)" data-toggle="tooltip" data-placement="bottom">
                                                <label for="fileFotoUbicacion_row${newRow.rowIndex}"></label>
                                                <h3 class="uploadFotoUbicacionCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                            </div>
                                        </div>
                                        <div id="imageContentUbicacion_row${newRow.rowIndex}">
                                            <div class="d-flex justify-content-end">
                                                <span onclick="deleteImageFotoUbicacion(${newRow.rowIndex})" class="deleteFile">X</span>
                                            </div>
                                            <img name="nor" src="${ruta}" id="imagesUbicacion_row_${newRow.rowIndex}" width="250px" data-toggle="modal" data-target="#ModalCenterUbicacion${newRow.rowIndex}">
                                            <input type="hidden" class="${newRow.rowIndex} Photo"/>
                                            <div class="modal fade " id="ModalCenterUbicacion${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <img name="nor" src="${ruta}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                                </div>
                                            </div>  
                                        </div>`;

        }else{
            if(Img_64!='SD'){
                newRow.insertCell(15).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoUbicacion${newRow.rowIndex}">
                                                    <div class="form-group">
                                                        <input type="file" name="FotoUbicacion_row${newRow.rowIndex}" accept="image/*" id="fileFotoUbicacion_row${newRow.rowIndex}" class="inputfile uploadFileFotoUbicacion" onchange="uploadFileUbicacion(event)" data-toggle="tooltip" data-placement="bottom">
                                                        <label for="fileFotoUbicacion_row${newRow.rowIndex}"></label>
                                                        <h3 class="uploadFotoUbicacionCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                                    </div>
                                                </div>
                                                <div id="imageContentUbicacion_row${newRow.rowIndex}">
                                                    <div class="d-flex justify-content-end">
                                                        <span onclick="deleteImageFotoUbicacion(${newRow.rowIndex})" class="deleteFile">X</span>
                                                    </div>
                                                    <img name="nor" src="${Img_64}" id="imagesUbicacion_row_${newRow.rowIndex}" width="250px" data-toggle="modal" data-target="#ModalCenterUbicacion${newRow.rowIndex}">
                                                    <input type="hidden" class="${newRow.rowIndex} Photo"/>
                                                    <div class="modal fade " id="ModalCenterUbicacion${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                            <img name="nor" src="${Img_64}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                                        </div>
                                                    </div>  
                                                </div>`;

            }else{
                newRow.insertCell(15).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoUbicacion${newRow.rowIndex}">
                                                    <div class="form-group">
                                                        <input type="file" name="FotoUbicacion_row${newRow.rowIndex}" accept="image/*" id="fileFotoUbicacion_row${newRow.rowIndex}" class="inputfile uploadFileFotoUbicacion" onchange="uploadFileUbicacion(event)" data-toggle="tooltip" data-placement="bottom">
                                                        <label for="fileFotoUbicacion_row${newRow.rowIndex}"></label>
                                                        <h3 class="uploadFotoUbicacionCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                                    </div>
                                                </div>
                                                <div id="imageContentUbicacion_row${newRow.rowIndex}"></div>`;

            }
        }
        
    }else{
        newRow.insertCell(15).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoUbicacion${newRow.rowIndex}">
                                            <div class="form-group">
                                                <input type="file" name="FotoUbicacion_row${newRow.rowIndex}" accept="image/*" id="fileFotoUbicacion_row${newRow.rowIndex}" class="inputfile uploadFileFotoUbicacion" onchange="uploadFileUbicacion(event)" data-toggle="tooltip" data-placement="bottom">
                                                <label for="fileFotoUbicacion_row${newRow.rowIndex}"></label>
                                                <h3 class="uploadFotoUbicacionCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                            </div>
                                        </div>
                                        <div id="imageContentUbicacion_row${newRow.rowIndex}"></div>`;

    }
    newRow.insertCell(16).innerHTML = Id_Ubicaciones_Entrevista;
    newRow.insertCell(17).innerHTML = Capturo;
    newRow.insertCell(18).innerHTML  =`<button type="button" class="btn btn-add" onclick="editUbicacion(this)"> 
                                            <i class="material-icons">edit</i>
                                        </button>
                                        <button type="button" class="btn btn-ssc" value="-" onclick="deleteRowDomicilio(this,UbicacionTable)">
                                            <i class="material-icons">delete</i>
                                        </button>`;
    
    //newRow.cells[0].style.display = "none";
    //newRow.cells[1].style.display = "none";
}
