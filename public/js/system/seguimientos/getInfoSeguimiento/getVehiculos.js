const getVehiculos = async (Id_Seguimiento) => { //Funcion que realizar peticion para obtener los datos de las entrevistas del evento
    try {
        myFormData.append('Id_Seguimiento',Id_Seguimiento)
        const response = await fetch(base_url_js + 'Seguimientos/getVehiculos', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const InsertgetVehiculos= async({Id_Vehiculo,Id_Seguimiento,Placas,Marca,Submarca,Color,Modelo,Nombre_Propietario,Nivs,InfoPlaca,Capturo,Foto,Img_64})=>{
    let pathImagesVehiculos =base_url_js+'public/files/Seguimientos/'+Id_Seguimiento+'/Vehiculos/';
    let table = document.getElementById('VehiculoTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    newRow.insertCell(0).innerHTML = Id_Vehiculo;
    newRow.insertCell(1).innerHTML = Placas;
    newRow.insertCell(2).innerHTML = Marca;
    newRow.insertCell(3).innerHTML = Submarca;
    newRow.insertCell(4).innerHTML = Color;
    newRow.insertCell(5).innerHTML = Modelo;
    newRow.insertCell(6).innerHTML = Nombre_Propietario
    newRow.insertCell(7).innerHTML = Nivs
    newRow.insertCell(8).innerHTML = InfoPlaca;
    if(Foto!='SD'){
        let ruta = pathImagesVehiculos+Foto;
        let ban = await imageExists(ruta)
        if(ban==true){
          ruta = ruta+'?nocache='+getRandomInt(50);
          newRow.insertCell(9).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoV${newRow.rowIndex}">
                                                    <div class="form-group">
                                                        <input type="file" name="FotoVehiculo_row${newRow.rowIndex}" accept="image/*" id="fileFotoVehiculo_row${newRow.rowIndex}" class="inputfile uploadFileFotoV" onchange="uploadFileV(event)" data-toggle="tooltip" data-placement="bottom">
                                                        <label for="fileFotoVehiculo_row${newRow.rowIndex}"></label>
                                                        <h3 class="uploadFotoVehiculoCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                                    </div>
                                                </div>
                                                <div id="imageContentV_row${newRow.rowIndex}">
                                                    <div class="d-flex justify-content-end">
                                                        <span onclick="deleteImageFotoV(${newRow.rowIndex})" class="deleteFile">X</span>
                                                    </div>
                                                    <img name="nor" src="${ruta}" id="imagesV_row_${newRow.rowIndex}" width="400px" data-toggle="modal" data-target="#ModalCenterVehiculo${newRow.rowIndex}">
                                                    <input type="hidden" class="${newRow.rowIndex} Photo"/>
                                                    <div class="modal fade " id="ModalCenterVehiculo${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                            <img name="nor" src="${ruta}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                                        </div>
                                                    </div>
                                                </div>`;

        }else{
            if(Img_64!='SD'){
                newRow.insertCell(9).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoV${newRow.rowIndex}">
                                                    <div class="form-group">
                                                        <input type="file" name="FotoVehiculo_row${newRow.rowIndex}" accept="image/*" id="fileFotoVehiculo_row${newRow.rowIndex}" class="inputfile uploadFileFotoV" onchange="uploadFileV(event)" data-toggle="tooltip" data-placement="bottom">
                                                        <label for="fileFotoVehiculo_row${newRow.rowIndex}"></label>
                                                        <h3 class="uploadFotoVehiculoCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                                    </div>
                                                </div>
                                                <div id="imageContentV_row${newRow.rowIndex}">
                                                    <div class="d-flex justify-content-end">
                                                        <span onclick="deleteImageFotoV(${newRow.rowIndex})" class="deleteFile">X</span>
                                                    </div>
                                                    <img name="nor" src="${Img_64}" id="imagesV_row_${newRow.rowIndex}" width="400px" data-toggle="modal" data-target="#ModalCenterVehiculo${newRow.rowIndex}">
                                                    <input type="hidden" class="${newRow.rowIndex} Photo"/>
                                                    <div class="modal fade " id="ModalCenterVehiculo${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                            <img name="nor" src="${Img_64}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                                        </div>
                                                    </div>
                                                </div>`;

            }else{
                newRow.insertCell(9).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoV${newRow.rowIndex}">
                                                    <div class="form-group">
                                                        <input type="file" name="FotoVehiculo_row${newRow.rowIndex}" accept="image/*" id="fileFotoVehiculo_row${newRow.rowIndex}" class="inputfile uploadFileFotoV" onchange="uploadFileV(event)" data-toggle="tooltip" data-placement="bottom">
                                                        <label for="fileFotoVehiculo_row${newRow.rowIndex}"></label>
                                                        <h3 class="uploadFotoVehiculoCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                                    </div>
                                                </div>
                                                <div id="imageContentV_row${newRow.rowIndex}"></div>`;
            }
        }
        
    }else{
        newRow.insertCell(9).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoV${newRow.rowIndex}">
                                            <div class="form-group">
                                                <input type="file" name="FotoVehiculo_row${newRow.rowIndex}" accept="image/*" id="fileFotoVehiculo_row${newRow.rowIndex}" class="inputfile uploadFileFotoV" onchange="uploadFileV(event)" data-toggle="tooltip" data-placement="bottom">
                                                <label for="fileFotoVehiculo_row${newRow.rowIndex}"></label>
                                                <h3 class="uploadFotoVehiculoCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                            </div>
                                        </div>
                                        <div id="imageContentV_row${newRow.rowIndex}"></div>`;
    }

    newRow.insertCell(10).innerHTML = Capturo;
    newRow.insertCell(11).innerHTML = `<button type="button" class="btn btn-add" onclick="editVehiculo(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc" value="-" onclick="deleteRowVehiculo(this,VehiculoTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
    newRow.cells[0].style.display = "none";
}
/*---------------------FUNCIONES DE REFRESCO--------------------------- */
const RecargaDatosVehiculos = async()=>{//Funcion que actualiza la vista de la tabla vehiculos cada vez que se guarden o eliminen datos
    await dropTablaContentVehiculos();
    let Vehiculos = await getVehiculos(Seguimiento);
    for await(Vehiculo of Vehiculos){
        let formDataVehiculo = {
            Id_Vehiculo : Vehiculo.Id_Vehiculo,
            Id_Seguimiento: Seguimiento,
            Placas : Vehiculo.Placas,
            Marca : Vehiculo.Marca,
            Submarca : Vehiculo.Submarca,
            Color : Vehiculo.Color,
            Modelo : Vehiculo.Modelo,
            Nombre_Propietario : Vehiculo.Nombre_Propietario,
            Nivs : Vehiculo.Nivs,
            InfoPlaca : Vehiculo.InfoPlaca,
            Capturo : Vehiculo.Capturo,
            Foto : Vehiculo.Foto,
            Img_64 : Vehiculo.Img_64
        }
       await InsertgetVehiculos(formDataVehiculo);//Inserta todos los vehiculos del seguimiento
    }
    await MostrarTabDomicilio();
    await MostrarTabAntecedentes();
}
const dropTablaContentVehiculos = async () => {//VACIA EL CONTENIDO DE LA TABLA 
    ban= true;
    table = document.getElementById('VehiculoTable');
    aux=document.getElementById('contarVeh').rows.length;
    for(let i = 1; i < aux+1; i++){
        table.deleteRow(1);
    }
    return ban;
}