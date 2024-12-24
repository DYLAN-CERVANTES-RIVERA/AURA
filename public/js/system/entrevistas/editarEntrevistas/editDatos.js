
const changeDatoEspecifico = async()=>{//SELECT DEL DATO QUE ANALIZA 
    let select = document.getElementById("Dato_select_especifico");
    select.disabled = false;
    while (select.options.length > 0) {//ACTUALIZACION DE SELECT POR SI HAY MODIFICACION EN LAS TABLAS
        select.remove(0);
    }
    let Forensias = await getForensias(Persona_Entrevista);
    option = document.createElement("option");
    option.text = "SELECCIONE EL ID ASOCIADO";
    option.value = -1;
    select.add(option);

    for (let i = 0; i < Forensias.length; i++) {
        option = document.createElement("option");
        option.text = "ID: "+Forensias[i]['Id_Forensia_Entrevista']+" EXTRACTO DATO: "+Forensias[i]['Descripcion_Forensia'].substr(0, 55)+"... ";
        option.value = Forensias[i]['Id_Forensia_Entrevista'];
        select.add(option);
    }
}

const hideTablaPanel = async() =>{//CAMBIA LA TABLA QUE ES DE ACUERDO AL SELECT
    document.getElementById('msg_datos').innerHTML=``;
    let option = document.getElementById('Tipo_Dato_Especifico').value;

    switch(option){
        case '1':
            document.getElementById('panelTabla').innerHTML=` 
                    <div class="form-row mt-3">
                        <div class="form-group col-md-3">
                            <label for="telefono" class="subtitulo-rosa">Teléfono</label>
                            <input type="text" maxlength="10" class="form-control" id="telefonoTab" placeholder="Ingrese su número telefónico">
                            <span class="span_error" id="telefonoTab_error"></span>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="nombre" class="subtitulo-rosa">Nombre del Contacto</label>
                            <input type="text" class="form-control" id="nombreTel" placeholder="Ingrese nombre del Contacto">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="relacion" class="subtitulo-rosa">Relacion Con el Detenido</label>
                            <select class="custom-select custom-select-sm" id="Select_Relacion"> 
                                <option value="RELACION FAMILIAR/AMISTAD">RELACION FAMILIAR/AMISTAD</option> 
                                <option value="COOPARTICIPE/LIDER">COOPARTICIPE/LIDER</option> 
                            </select>
                        </div>
                        <div class="form-group col-md-10"></div>
                        <div class="form-group col-md-2 mt-3">
                            <input type="text" class="mi_hide" id="Id_Tel" value="-1">
                            <button type="submit" class="btn btn-ssc" id="BotonTel">Guardar Dato</button>
                        </div>
                    </div>
                    <div class="form-row row mt-3"> 
                        <div class="form-group col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="Telefono_Table" style="text-align:center">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Id Telefono</th>
                                            <th scope="col">Id Dato relacionado</th>
                                            <th scope="col">Telefono</th>
                                            <th scope="col">Nombre o Alias del contacto</th>
                                            <th scope="col">Relacion</th>
                                            <th scope="col">Capturo</th>
                                            <th scope="col">Editar/Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="Telefono_TableCount">
                                    </tbody>
                                </table>
                            </div><br>
                        </div>
                    </div>`;
                    await RecargaDatosTelefono();
        break;
        case '2':
            document.getElementById('panelTabla').innerHTML=`
                    <div class="form-row mt-3">
                        <div class="form-group col-md-4">
                            <label for="curp" class="subtitulo-rosa">CURP / RFC</label>
                            <input type="text" maxlength="19" class="form-control" id="curpTab" placeholder="Ingrese Curp /RFC">
                            <span class="span_error" id="curpTab_error"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nombreCurp" class="subtitulo-rosa">Nombre</label>
                            <input type="text" class="form-control" id="nombreCurp" placeholder="Ingrese nombre">
                        </div>
                        <div class="form-group col-md-2 mt-3">
                            <br>
                            <input type="text" class="mi_hide" id="Id_CURP" value="-1">
                            <button type="submit" class="btn btn-ssc" id="BotonCurp">Guardar Dato</button>
                        </div>
                    </div>

                    <div class="form-row row mt-3"> 
                        <div class="form-group col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="Curp_Table" style="text-align:center">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Id CURP</th>
                                            <th scope="col">Id Dato relacionado</th>
                                            <th scope="col">CURP o RFC</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Capturo</th>
                                            <th scope="col">Editar/Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="Curp_TableCount">
                                    </tbody>
                                </table>
                            </div><br>
                            
                        </div>
                    </div>`;
            RecargaDatosCURP();
        break;
        case '3':
            document.getElementById('panelTabla').innerHTML=`
                    <div class="form-row mt-3">
                        <div class="form-group col-md-4">
                            <label for="tarjeta" class="subtitulo-rosa">Tarjeta de Credito</label>
                            <input type="text" maxlength="20" class="form-control" id="tarjetaTab" placeholder="Ingrese tarjeta">
                            <span class="span_error" id="tarjetaTab_error"></span>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="nombreTarjeta" class="subtitulo-rosa">Nombre</label>
                            <input type="text" class="form-control" id="nombreTarjeta" placeholder="Ingrese su nombre">
                        </div>

                        <div class="form-group col-md-2 mt-3">
                            <input type="text" class="mi_hide" id="Id_Tarjeta" value="-1">
                            <br>
                            <button type="submit" class="btn btn-ssc" id="BotonTarjeta">Guardar Dato</button>
                        </div>
                    </div>
                    <div class="form-row row mt-3"> 
                        <div class="form-group col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="Num_tarjeta_Table" style="text-align:center">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Id Tarjeta</th>
                                            <th scope="col">Id Dato relacionado</th>
                                            <th scope="col">Numero de Tarjeta</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Capturo</th>
                                            <th scope="col">Editar/Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="Num_tarjeta_Count">
                                    </tbody>
                                </table>
                            </div><br>
                        </div>
                    </div>`;
                    RecargaDatosTarjeta();
        break;
        case '4':
            document.getElementById('panelTabla').innerHTML=`
                    <div class="form-row mt-3">
                        <div class="form-group col-md-10">
                            <label for="Otro" class="subtitulo-rosa">Otro</label>
                            <input type="text" class="form-control" id="OtroTab" placeholder="Ingrese Otro">
                            <span class="span_error" id="OtroTab_error"></span>
                        </div>
                        <div class="form-group col-md-2 mt-3">
                            <input type="text" class="mi_hide" id="Id_Otro" value="-1">
                            <br>
                            <button type="submit" class="btn btn-ssc" id="BotonOtro">Guardar Dato</button>
                        </div>
                    </div>
                    <div class="form-row row mt-3"> 
                        <div class="form-group col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="Otro_Table" style="text-align:center">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Id Otro</th>
                                            <th scope="col">Id Dato relacionado</th>
                                            <th scope="col">Descripcion Otro</th>
                                            <th scope="col">Capturo</th>
                                            <th scope="col">Editar/Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="Otro_Count">
                                    </tbody>
                                </table>
                            </div><br>
                        </div>
                    </div>`;
                    RecargaDatosOtros();
        break;
        case '5':
            document.getElementById('panelTabla').innerHTML=` 
                    <div class="form-row mt-3">
                        <div class="form-group col-md-5">
                            <label for="placa" class="subtitulo-rosa">Placa</label>
                            <input type="text" class="form-control" id="placaTab" placeholder="Ingrese placa">
                            <span class="span_error" id="placaTab_error"></span>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="NivTab" class="subtitulo-rosa">Niv</label>
                            <input type="text" class="form-control" id="NivTab" placeholder="Ingrese Niv">
                            <span class="span_error" id="NivTab_error"></span>
                        </div>
                        <div class="form-group col-md-2 mt-3">
                            <input type="text" class="mi_hide" id="Id_PlacaNiv" value="-1">
                            <br>
                            <button type="submit" class="btn btn-ssc" id="BotonPlacaNiv">Guardar Dato</button>
                        </div>
                    </div>
                    <div class="form-row row mt-3"> 
                        <div class="form-group col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="Placa_Niv_Table" style="text-align:center">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Id Placa/Niv</th>
                                            <th scope="col">Id Dato relacionado</th>
                                            <th scope="col">Placa</th>
                                            <th scope="col">Niv</th>
                                            <th scope="col">Capturo</th>
                                            <th scope="col">Editar/Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="Placa_Niv_Count">
                                    </tbody>
                                </table>
                            </div><br>
                        </div>
                    </div>`;
                RecargaDatosPlacaNiv();
        break;
        case '6':
            document.getElementById('panelTabla').innerHTML=`
                <div class="form-row mt-3">
                    <div class="form-group col-md-10">
                        <label for="Zona" class="subtitulo-rosa">Zona de Operacion</label>
                        <input type="text" class="form-control" id="ZonaTab" placeholder="Ingrese Zona de Operacion">
                        <span class="span_error" id="ZonaTab_error"></span>
                    </div>
                    <div class="form-group col-md-2 mt-3">
                        <input type="text" class="mi_hide" id="Id_Zona" value="-1">
                        <br>
                        <button type="submit" class="btn btn-ssc" id="BotonZonaTab">Guardar Dato</button>
                    </div>
                </div>
                <div class="form-row row mt-3"> 
                    <div class="form-group col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="Zona_Table" style="text-align:center">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Id Zona</th>
                                        <th scope="col">Id Dato relacionado</th>
                                        <th scope="col">Zona Operacion</th>
                                        <th scope="col">Capturo</th>
                                        <th scope="col">Editar/Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody id="Zona_Count">
                                </tbody>
                            </table>
                        </div><br>
                    </div>
                </div>`;
                RecargaDatosZona();
        break;
        case '7':
            document.getElementById('panelTabla').innerHTML=`
                <div class="form-row mt-3">
                    <div class="form-group col-md-10">
                        <label for="Banda" class="subtitulo-rosa">Banda de Asociada</label>
                        <input type="text" class="form-control" id="BandaTab" placeholder="Ingrese Banda de Asociada">
                        <span class="span_error" id="BandaTab_error"></span>
                    </div>
                    <div class="form-group col-md-2 mt-3">
                        <input type="text" class="mi_hide" id="Id_Banda" value="-1">
                        <br>
                        <button type="submit" class="btn btn-ssc" id="BotonBandaTab">Guardar Dato</button>
                    </div>
                </div>
                <div class="form-row row mt-3"> 
                    <div class="form-group col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="Banda_Table" style="text-align:center">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Id Banda</th>
                                        <th scope="col">Id Dato relacionado</th>
                                        <th scope="col">Banda Asociada</th>
                                        <th scope="col">Capturo</th>
                                        <th scope="col">Editar/Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody id="Banda_Count">
                                </tbody>
                            </table>
                        </div><br>
                    </div>
                </div>`;
                RecargaDatosBanda();
        break;
        case '8':
            document.getElementById('panelTabla').innerHTML=`
                <div class="form-row mt-3">
                    <div class="form-group col-md-6">
                        <label for="NombreTab" class="subtitulo-rosa">Nombre</label>
                        <input type="text" class="form-control" id="NombreTab" placeholder="Ingrese Nombre">
                        <span class="span_error" id="NombreTab_error"></span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="ApPaternoTab" class="subtitulo-rosa">Apellido Paterno</label>
                        <input type="text" class="form-control" id="ApPaternoTab" placeholder="Ingrese Apellido Paterno">
                        <span class="span_error" id="ApPaternoTab_error"></span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="ApMaternoTab" class="subtitulo-rosa">Apellido Materno</label>
                        <input type="text" class="form-control" id="ApMaternoTab" placeholder="Ingrese Apellido Materno">
                        <span class="span_error" id="ApMaternoTab_error"></span>
                    </div>
                    <div class="form-group col-md-4"> 
                    </div>
                    <div class="form-group col-md-2 mt-3">
                        <input type="text" class="mi_hide" id="Id_Nombre" value="-1">
                        <br>
                        <button type="submit" class="btn btn-ssc" id="BotonNombreTab">Guardar Dato</button>
                    </div>
                </div>
                <div class="form-row row mt-3"> 
                    <div class="form-group col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="Nombre_Table" style="text-align:center">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Id Nombre</th>
                                        <th scope="col">Id Dato relacionado</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Apellido Paterno</th>
                                        <th scope="col">Apellido Materno</th>
                                        <th scope="col">Capturo</th>
                                        <th scope="col">Editar/Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody id="Nombre_Count">
                                </tbody>
                            </table>
                        </div><br>
                    </div>
                </div>`;
                RecargaDatosNombre();
        break;
        default:
            document.getElementById('panelTabla').innerHTML=``
        break;
    }
}

function filtrarSoloLetras(event) {/// Para el pegado
    var valor = event.target.value;
    var nuevoValor = "";
    for (var i = 0; i < valor.length; i++) {
        var char = valor[i];       
        if (/[a-zA-Z\sáéíóúÁÉÍÓÚÑñ]/.test(char)) {
            nuevoValor += char;
        }
    }
    event.target.value = nuevoValor;
}

function filtrarAlfaNumericos(event) {/// Para el pegado
    var valor = event.target.value;
    var nuevoValor = "";
    for (var i = 0; i < valor.length; i++) {
        var char = valor[i];       
        if (/[a-zA-Z0-9-\sáéíóúÁÉÍÓÚÑñ.]/.test(char)) {
            nuevoValor += char;
        }
    }
    event.target.value = nuevoValor;
}

document.getElementById("Tipo_Dato_Especifico").addEventListener("change", hideTablaPanel);

document.body.addEventListener("input", function(event) {
    if (event.target) {
        let input = event.target.id;
        switch (input){
            case "telefonoTab":
            case "tarjetaTab":
                filtrarNumerosTel(event);break;
            case "nombreTel":
            case "nombreCurp":
            case "nombreTarjeta":
            case "OtroTab":
            case "NombreTab":
            case "ApPaternoTab":
            case "ApMaternoTab": 
                filtrarSoloLetras(event);break;
            case "curpTab":
            case "placaTab":
            case "NivTab":
            case "ZonaTab":
            case "BandaTab":
                filtrarAlfaNumericos(event);break;
        }
    }
});

document.body.addEventListener("click", function(event) {
    if (event.target.tagName.toLowerCase() === "button") {
       // console.log("Botón clickeado:", event.target.id);  // Muestra el id del botón

        let input = event.target.id;
        switch (input){
            case "BotonTel":GuardaTel();break;
            case "BotonCurp":GuardaCurp();break;
            case "BotonTarjeta":GuardaTarjeta();break;
            case "BotonOtro":GuardaOtro();break;
            case "BotonPlacaNiv":GuardaPlacaNiv();break;
            case "BotonZonaTab":GuardaZona();break;
            case "BotonBandaTab":GuardaBanda();break;
            case "BotonNombreTab":GuardaNombre();break;
        }
    }
});
 
//FUNCIONES DE LA TABLA DE DATOS TELEFONOS
const GuardaTel = async()=> {
    let band = [];
    let i = 0;

    band[i++] = document.getElementById('telefonoTab_error').innerText = (document.getElementById('telefonoTab').value.trim()!='')?'':'Campo Requerido';
    band[i++] = document.getElementById('Dato_select_especifico_error').innerText = (document.getElementById('Dato_select_especifico').value!='-1')?'':'Campo Requerido';

    let success = true
    band.forEach(element => {//recorre todas la banderas si todas son vacias procede a guardar los datos ya que no existe ninguna restriccion 
        success &= (element == '') ? true : false
    });

    if(success){
        let myFormData = new FormData()
        myFormData.append('Id_Persona_Entrevista',document.getElementById('id_persona_entrevista').value);
        myFormData.append('Id_Dato_Entrevista',document.getElementById("Dato_select_especifico").value);
        myFormData.append('Id_Tel',document.getElementById("Id_Tel").value);
        myFormData.append('Telefono',document.getElementById("telefonoTab").value);
        myFormData.append('Nombre',(document.getElementById("nombreTel").value.trim()!='')?document.getElementById("nombreTel").value.toUpperCase():'SD');
        myFormData.append('Relacion',document.getElementById("Select_Relacion").value);
        myFormData.append('Capturo',document.getElementById('captura_dato_forencias').value.toUpperCase());
        //MANDAMOS TAMBIEN EL ID PROPIO SI ES -1 ES INSERT SI NO UPDATE

        /*for (var pair of myFormData.entries()) {
            console.log(pair[0] + ', ' + pair[1]);
        }*/
        //MOSTRAMOS MODAL , GUARDAMOS Y QUITAMOS MODAL
        $('#ModalCenterPrincipalforencias').modal('show');

        fetch(base_url_js + 'Entrevistas/UpdateTelefonoTab', {//realiza el fetch para actualizar los datos
            method: 'POST',
            body: myFormData
        })
    
        .then(res => res.json())
    
        .then(data => {//obtine  respuesta del modelo
            setTimeout(function() {
                $('#ModalCenterPrincipalforencias').modal('hide');
            }, 500);

            if (!data.status) {
                document.getElementById('msg_datos').innerHTML =`<div class="alert alert-danger text-center" role="alert">Sucedio un error en el servidor: ${data.error_message} ${data.error_sql}</div>`;
            } else {//si todo salio bien
                document.getElementById('msg_datos').innerHTML =`<div class="alert alert-success text-center" role="success">Datos de Telefono Actualizados Correctamente.
                                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>`;
                document.getElementById("Id_Tel").value = "-1";
                document.getElementById("Dato_select_especifico").value = "-1";   
                document.getElementById("telefonoTab").value = "";
                document.getElementById("nombreTel").value = "";
                document.getElementById("Select_Relacion").value = "RELACION FAMILIAR/AMISTAD"; 
                //REFRESH CUANDO TODO HALLA QUEDADO
                RecargaDatosTelefono();
            }
        })   
    }
}

const RecargaDatosTelefono = async()=>{
    let table = document.getElementById('Telefono_Table');
    let aux = document.getElementById('Telefono_TableCount').rows.length;
    for(let i = 1; i < aux+1; i++){
        table.deleteRow(1);
    }
    datos = await getDatosTelefonos();
    for await(let dato of datos){
        let formData = {
            Id_Tel : dato.Id_Tel ,
            Id_Dato_Entrevista : dato.Id_Dato_Entrevista,
            Telefono: dato.Telefono,
            Nombre: dato.Nombre,
            Relacion : dato.Relacion ,
            Capturo : dato.Capturo 
        }
        await InsertDatosTelefono(formData);//Inserta todos las forensias de las personas del seguimiento
    }
}

const InsertDatosTelefono = async({Id_Tel, Id_Dato_Entrevista, Telefono, Nombre, Relacion, Capturo})=>{
    let table = document.getElementById('Telefono_Table').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length); 
    newRow.insertCell(0).innerHTML = Id_Tel
    newRow.insertCell(1).innerHTML = Id_Dato_Entrevista;
    newRow.insertCell(2).innerHTML = Telefono;
    newRow.insertCell(3).innerHTML = Nombre;
    newRow.insertCell(4).innerHTML = Relacion;
    newRow.insertCell(5).innerHTML = Capturo;
    newRow.insertCell(6).innerHTML =`<button type="button" class="btn btn-add mt-1" onclick="editTelefono(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc mt-1" value="-" onclick="deleteRowTelefono(this)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
}

const editTelefono = async (obj)=>{
    document.getElementById('msg_datos').innerHTML =`<div class="alert  alert-primary text-center" role="alert">Editando Dato.
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                                                                <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>`;
    let selectedRow = obj.parentElement.parentElement;
    document.getElementById("Id_Tel").value = selectedRow.cells[0].innerHTML;
    document.getElementById("Dato_select_especifico").value = selectedRow.cells[1].innerHTML;  
    document.getElementById("telefonoTab").value = selectedRow.cells[2].innerHTML;
    document.getElementById("nombreTel").value = selectedRow.cells[3].innerHTML;
    document.getElementById("Select_Relacion").value = selectedRow.cells[4].innerHTML; 
}
const deleteRowTelefono = async(obj)=>{//FUNCION QUE ELIMINA LOS DATOS DE LAS TABLAS DE FORENSIA    
    try {
        if (confirm('¿Desea eliminar este elemento?')) {
            let selectedRow = obj.parentElement.parentElement;
            let myFormData = new FormData()

            myFormData.append('Id_Tel',selectedRow.cells[0].innerHTML);
            const response = await fetch(base_url_js + 'Entrevistas/deleteRowTelefono', {
                method: 'POST',
                body: myFormData
            });
            const data = await response.json();
            if (!data.status) {
                Swal.fire({
                    title: "ERROR AL ELIMINAR DATO DE TELEFONO",
                    icon: 'error',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'custom-confirm-btn'  // Clase CSS personalizada para el botón de confirmación
                    },
                    buttonsStyling: false
                })
            } else {//si todo salio bien
                Swal.fire({
                    title: "DATO DE TELEFONO ELIMINADO",
                    icon: 'error',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'custom-confirm-btn'  // Clase CSS personalizada para el botón de confirmación
                    },
                    buttonsStyling: false
                })
                RecargaDatosTelefono();
            }
        }
    } catch (error) {
        console.log(error);
    }
}

const getDatosTelefonos = async()=>{
    let myFormData = new FormData()
    myFormData.append('Id_Persona_Entrevista',document.getElementById('id_persona_entrevista').value);
    try {
        const response = await fetch(base_url_js + 'Entrevistas/getDatosTelefono', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
//Funciones tabla Curp

const GuardaCurp = async()=> {
    let band = [];
    let i = 0;

    band[i++] = document.getElementById('curpTab_error').innerText = (document.getElementById('curpTab').value.trim()!='')?'':'Campo Requerido';
    band[i++] = document.getElementById('Dato_select_especifico_error').innerText = (document.getElementById('Dato_select_especifico').value!='-1')?'':'Campo Requerido';

    let success = true
    band.forEach(element => {//recorre todas la banderas si todas son vacias procede a guardar los datos ya que no existe ninguna restriccion 
        success &= (element == '') ? true : false
    });

    if(success){
        let myFormData = new FormData()
        myFormData.append('Id_Persona_Entrevista',document.getElementById('id_persona_entrevista').value);
        myFormData.append('Id_Dato_Entrevista',document.getElementById("Dato_select_especifico").value);
        myFormData.append('Id_CURP',document.getElementById("Id_CURP").value);
        myFormData.append('CURP',document.getElementById("curpTab").value.toUpperCase());
        myFormData.append('Nombre',(document.getElementById("nombreCurp").value.trim()!='')?document.getElementById("nombreCurp").value.toUpperCase():'SD');
        myFormData.append('Capturo',document.getElementById('captura_dato_forencias').value.toUpperCase());
        //MANDAMOS TAMBIEN EL ID PROPIO SI ES -1 ES INSERT SI NO UPDATE

        /*for (var pair of myFormData.entries()) {
            console.log(pair[0] + ', ' + pair[1]);
        }*/
        //MOSTRAMOS MODAL , GUARDAMOS Y QUITAMOS MODAL
        $('#ModalCenterPrincipalforencias').modal('show');

        fetch(base_url_js + 'Entrevistas/UpdateCURPTab', {//realiza el fetch para actualizar los datos
            method: 'POST',
            body: myFormData
        })
    
        .then(res => res.json())
    
        .then(data => {//obtine  respuesta del modelo
            setTimeout(function() {
                $('#ModalCenterPrincipalforencias').modal('hide');
            }, 500);

            if (!data.status) {
                document.getElementById('msg_datos').innerHTML =`<div class="alert alert-danger text-center" role="alert">Sucedio un error en el servidor: ${data.error_message} ${data.error_sql}</div>`;
            } else {//si todo salio bien
                document.getElementById('msg_datos').innerHTML =`<div class="alert alert-success text-center" role="success">Datos de CURP Actualizados Correctamente.
                                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>`;
                document.getElementById("Id_CURP").value = "-1";
                document.getElementById("Dato_select_especifico").value = "-1"; 
                document.getElementById("curpTab").value = "";
                document.getElementById("nombreCurp").value = "";
                //REFRESH CUANDO TODO HALLA QUEDADO
                RecargaDatosCURP();
            }
        })   
    }
}

const RecargaDatosCURP = async()=>{
    let table = document.getElementById('Curp_Table');
    let aux = document.getElementById('Curp_TableCount').rows.length;
    for(let i = 1; i < aux+1; i++){
        table.deleteRow(1);
    }
    datos = await getDatosCURP();
    for await(let dato of datos){
        let formData = {
            Id_CURP  : dato.Id_CURP  ,
            Id_Dato_Entrevista : dato.Id_Dato_Entrevista,
            CURP: dato.CURP,
            Nombre: dato.Nombre,
            Capturo : dato.Capturo 
        }
        await InsertDatosCURP(formData);//Inserta todos las forensias de las personas del seguimiento
    }
}
const getDatosCURP = async()=>{
    let myFormData = new FormData()
    myFormData.append('Id_Persona_Entrevista',document.getElementById('id_persona_entrevista').value);
    try {
        const response = await fetch(base_url_js + 'Entrevistas/getDatosCURP', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const InsertDatosCURP = async({Id_CURP, Id_Dato_Entrevista, CURP, Nombre, Capturo})=>{
    let table = document.getElementById('Curp_Table').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length); 
    newRow.insertCell(0).innerHTML = Id_CURP
    newRow.insertCell(1).innerHTML = Id_Dato_Entrevista;
    newRow.insertCell(2).innerHTML = CURP;
    newRow.insertCell(3).innerHTML = Nombre;
    newRow.insertCell(4).innerHTML = Capturo;
    newRow.insertCell(5).innerHTML =`<button type="button" class="btn btn-add mt-1" onclick="editCURP(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc mt-1" value="-" onclick="deleteRowCURP(this)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
}
const editCURP = async (obj)=>{
    document.getElementById('msg_datos').innerHTML =`<div class="alert  alert-primary text-center" role="alert">Editando Dato.
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                                                                <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>`;
    let selectedRow = obj.parentElement.parentElement;
    document.getElementById("Id_CURP").value = selectedRow.cells[0].innerHTML;
    document.getElementById("Dato_select_especifico").value = selectedRow.cells[1].innerHTML;  
    document.getElementById("curpTab").value = selectedRow.cells[2].innerHTML;
    document.getElementById("nombreCurp").value = selectedRow.cells[3].innerHTML;
}

const deleteRowCURP = async(obj)=>{//FUNCION QUE ELIMINA LOS DATOS DE LAS TABLAS DE FORENSIA    
    try {
        if (confirm('¿Desea eliminar este elemento?')) {
            let selectedRow = obj.parentElement.parentElement;
            let myFormData = new FormData()

            myFormData.append('Id_CURP',selectedRow.cells[0].innerHTML);
            const response = await fetch(base_url_js + 'Entrevistas/deleteRowCURP', {
                method: 'POST',
                body: myFormData
            });
            const data = await response.json();
            if (!data.status) {
                Swal.fire({
                    title: "ERROR AL ELIMINAR DATO DE CURP",
                    icon: 'error',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'custom-confirm-btn'  // Clase CSS personalizada para el botón de confirmación
                    },
                    buttonsStyling: false
                })
            } else {//si todo salio bien
                Swal.fire({
                    title: "DATO DE CURP ELIMINADO",
                    icon: 'error',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'custom-confirm-btn'  // Clase CSS personalizada para el botón de confirmación
                    },
                    buttonsStyling: false
                })
                RecargaDatosCURP();
            }
        }
    } catch (error) {
        console.log(error);
    }
}
//Funciones de Tabla Telefono
const GuardaTarjeta = async()=> {
    let band = [];
    let i = 0;

    band[i++] = document.getElementById('tarjetaTab_error').innerText = (document.getElementById('tarjetaTab').value.trim()!='')?'':'Campo Requerido';
    band[i++] = document.getElementById('Dato_select_especifico_error').innerText = (document.getElementById('Dato_select_especifico').value!='-1')?'':'Campo Requerido';

    let success = true
    band.forEach(element => {//recorre todas la banderas si todas son vacias procede a guardar los datos ya que no existe ninguna restriccion 
        success &= (element == '') ? true : false
    });

    if(success){
        let myFormData = new FormData()
        myFormData.append('Id_Persona_Entrevista',document.getElementById('id_persona_entrevista').value);
        myFormData.append('Id_Dato_Entrevista',document.getElementById("Dato_select_especifico").value);
        myFormData.append('Id_Tarjeta',document.getElementById("Id_Tarjeta").value);
        myFormData.append('Tarjeta',document.getElementById("tarjetaTab").value);
        myFormData.append('Nombre',(document.getElementById("nombreTarjeta").value.trim()!='')?document.getElementById("nombreTarjeta").value.toUpperCase():'SD');
        myFormData.append('Capturo',document.getElementById('captura_dato_forencias').value.toUpperCase());
        //MANDAMOS TAMBIEN EL ID PROPIO SI ES -1 ES INSERT SI NO UPDATE

        /*for (var pair of myFormData.entries()) {
            console.log(pair[0] + ', ' + pair[1]);
        }*/
        //MOSTRAMOS MODAL , GUARDAMOS Y QUITAMOS MODAL
        $('#ModalCenterPrincipalforencias').modal('show');

        fetch(base_url_js + 'Entrevistas/UpdateTarjetaTab', {//realiza el fetch para actualizar los datos
            method: 'POST',
            body: myFormData
        })
    
        .then(res => res.json())
    
        .then(data => {//obtine  respuesta del modelo
            setTimeout(function() {
                $('#ModalCenterPrincipalforencias').modal('hide');
            }, 500);

            if (!data.status) {
                document.getElementById('msg_datos').innerHTML =`<div class="alert alert-danger text-center" role="alert">Sucedio un error en el servidor: ${data.error_message} ${data.error_sql}</div>`;
            } else {//si todo salio bien
                document.getElementById('msg_datos').innerHTML =`<div class="alert alert-success text-center" role="success">Datos de Tarjeta Actualizados Correctamente.
                                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>`;
                document.getElementById("Id_Tarjeta").value = "-1";
                document.getElementById("Dato_select_especifico").value = "-1"; 
                document.getElementById("tarjetaTab").value = "";
                document.getElementById("nombreTarjeta").value = "";
                //REFRESH CUANDO TODO HALLA QUEDADO
                RecargaDatosTarjeta();
            }
        })   
    }
}

const RecargaDatosTarjeta = async()=>{
    let table = document.getElementById('Num_tarjeta_Table');
    let aux = document.getElementById('Num_tarjeta_Count').rows.length;
    for(let i = 1; i < aux+1; i++){
        table.deleteRow(1);
    }
    datos = await getDatosTarjeta();
    for await(let dato of datos){
        let formData = {
            Id_Tarjeta: dato.Id_Tarjeta,
            Id_Dato_Entrevista : dato.Id_Dato_Entrevista,
            Tarjeta: dato.Tarjeta,
            Nombre: dato.Nombre,
            Capturo : dato.Capturo 
        }
        await InsertDatosTarjeta(formData);//Inserta todos las forensias de las personas del seguimiento
    }
}
const getDatosTarjeta = async()=>{
    let myFormData = new FormData()
    myFormData.append('Id_Persona_Entrevista',document.getElementById('id_persona_entrevista').value);
    try {
        const response = await fetch(base_url_js + 'Entrevistas/getDatosTarjeta', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const InsertDatosTarjeta = async({Id_Tarjeta, Id_Dato_Entrevista, Tarjeta, Nombre, Capturo})=>{
    let table = document.getElementById('Num_tarjeta_Table').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length); 
    newRow.insertCell(0).innerHTML = Id_Tarjeta
    newRow.insertCell(1).innerHTML = Id_Dato_Entrevista;
    newRow.insertCell(2).innerHTML = Tarjeta;
    newRow.insertCell(3).innerHTML = Nombre;
    newRow.insertCell(4).innerHTML = Capturo;
    newRow.insertCell(5).innerHTML =`<button type="button" class="btn btn-add mt-1" onclick="editTarjeta(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc mt-1" value="-" onclick="deleteRowTarjeta(this)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
}

const editTarjeta = async (obj)=>{
    document.getElementById('msg_datos').innerHTML =`<div class="alert  alert-primary text-center" role="alert">Editando Dato.
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                                                                <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>`;
    let selectedRow = obj.parentElement.parentElement;
    document.getElementById("Id_Tarjeta").value = selectedRow.cells[0].innerHTML;
    document.getElementById("Dato_select_especifico").value = selectedRow.cells[1].innerHTML;  
    document.getElementById("tarjetaTab").value = selectedRow.cells[2].innerHTML;
    document.getElementById("nombreTarjeta").value = selectedRow.cells[3].innerHTML;
}

const deleteRowTarjeta = async(obj)=>{//FUNCION QUE ELIMINA LOS DATOS DE LAS TABLAS DE FORENSIA    
    try {
        if (confirm('¿Desea eliminar este elemento?')) {
            let selectedRow = obj.parentElement.parentElement;
            let myFormData = new FormData()

            myFormData.append('Id_Tarjeta',selectedRow.cells[0].innerHTML);
            const response = await fetch(base_url_js + 'Entrevistas/deleteRowTarjeta', {
                method: 'POST',
                body: myFormData
            });
            const data = await response.json();
            if (!data.status) {
                Swal.fire({
                    title: "ERROR AL ELIMINAR DATO DE TARJETA",
                    icon: 'error',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'custom-confirm-btn'  // Clase CSS personalizada para el botón de confirmación
                    },
                    buttonsStyling: false
                })
            } else {//si todo salio bien
                Swal.fire({
                    title: "DATO DE TARJETA ELIMINADO",
                    icon: 'error',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'custom-confirm-btn'  // Clase CSS personalizada para el botón de confirmación
                    },
                    buttonsStyling: false
                })
                RecargaDatosTarjeta();
            }
        }
    } catch (error) {
        console.log(error);
    }
}
//Funciones tabla otro
const GuardaOtro = async()=> {
    let band = [];
    let i = 0;

    band[i++] = document.getElementById('OtroTab_error').innerText = (document.getElementById('OtroTab').value.trim()!='')?'':'Campo Requerido';
    band[i++] = document.getElementById('Dato_select_especifico_error').innerText = (document.getElementById('Dato_select_especifico').value!='-1')?'':'Campo Requerido';

    let success = true
    band.forEach(element => {//recorre todas la banderas si todas son vacias procede a guardar los datos ya que no existe ninguna restriccion 
        success &= (element == '') ? true : false
    });

    if(success){
        let myFormData = new FormData()
        myFormData.append('Id_Persona_Entrevista',document.getElementById('id_persona_entrevista').value);
        myFormData.append('Id_Dato_Entrevista',document.getElementById("Dato_select_especifico").value);
        myFormData.append('Id_Otro',document.getElementById("Id_Otro").value);
        myFormData.append('Otro',document.getElementById("OtroTab").value.toUpperCase());
        myFormData.append('Capturo',document.getElementById('captura_dato_forencias').value.toUpperCase());
        //MANDAMOS TAMBIEN EL ID PROPIO SI ES -1 ES INSERT SI NO UPDATE

        /*for (var pair of myFormData.entries()) {
            console.log(pair[0] + ', ' + pair[1]);
        }*/
        //MOSTRAMOS MODAL , GUARDAMOS Y QUITAMOS MODAL
        $('#ModalCenterPrincipalforencias').modal('show');

        fetch(base_url_js + 'Entrevistas/UpdateOtroTab', {//realiza el fetch para actualizar los datos
            method: 'POST',
            body: myFormData
        })
    
        .then(res => res.json())
    
        .then(data => {//obtine  respuesta del modelo
            setTimeout(function() {
                $('#ModalCenterPrincipalforencias').modal('hide');
            }, 500);

            if (!data.status) {
                document.getElementById('msg_datos').innerHTML =`<div class="alert alert-danger text-center" role="alert">Sucedio un error en el servidor: ${data.error_message} ${data.error_sql}</div>`;
            } else {//si todo salio bien
                document.getElementById('msg_datos').innerHTML =`<div class="alert alert-success text-center" role="success">Datos de Tarjeta Actualizados Correctamente.
                                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>`;
                document.getElementById("Id_Otro").value = "-1";
                document.getElementById("Dato_select_especifico").value = "-1"; 
                document.getElementById("OtroTab").value = "";
                //REFRESH CUANDO TODO HALLA QUEDADO
                RecargaDatosOtros();
            }
        })   
    }
}
const RecargaDatosOtros = async()=>{
    let table = document.getElementById('Otro_Table');
    let aux = document.getElementById('Otro_Count').rows.length;
    for(let i = 1; i < aux+1; i++){
        table.deleteRow(1);
    }
    datos = await getDatosOtro();
    for await(let dato of datos){
        let formData = {
            Id_Otro: dato.Id_Otro,
            Id_Dato_Entrevista : dato.Id_Dato_Entrevista,
            Otro: dato.Otro,
            Capturo : dato.Capturo 
        }
        await InsertDatosOtro(formData);//Inserta todos las forensias de las personas del seguimiento
    }
}
const getDatosOtro = async()=>{
    let myFormData = new FormData()
    myFormData.append('Id_Persona_Entrevista',document.getElementById('id_persona_entrevista').value);
    try {
        const response = await fetch(base_url_js + 'Entrevistas/getDatosOtro', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const InsertDatosOtro = async({Id_Otro, Id_Dato_Entrevista, Otro, Capturo})=>{
    let table = document.getElementById('Otro_Table').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length); 
    newRow.insertCell(0).innerHTML = Id_Otro
    newRow.insertCell(1).innerHTML = Id_Dato_Entrevista;
    newRow.insertCell(2).innerHTML = Otro;
    newRow.insertCell(3).innerHTML = Capturo;
    newRow.insertCell(4).innerHTML =`<button type="button" class="btn btn-add mt-1" onclick="editOtro(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc mt-1" value="-" onclick="deleteRowOtro(this)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
}

const editOtro = async (obj)=>{
    document.getElementById('msg_datos').innerHTML =`<div class="alert  alert-primary text-center" role="alert">Editando Dato.
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                                                                <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>`;
    let selectedRow = obj.parentElement.parentElement;
    document.getElementById("Id_Otro").value = selectedRow.cells[0].innerHTML;
    document.getElementById("Dato_select_especifico").value = selectedRow.cells[1].innerHTML;  
    document.getElementById("OtroTab").value = selectedRow.cells[2].innerHTML;
}

const deleteRowOtro = async(obj)=>{//FUNCION QUE ELIMINA LOS DATOS DE LAS TABLAS DE FORENSIA    
    try {
        if (confirm('¿Desea eliminar este elemento?')) {
            let selectedRow = obj.parentElement.parentElement;
            let myFormData = new FormData()

            myFormData.append('Id_Otro',selectedRow.cells[0].innerHTML);
            const response = await fetch(base_url_js + 'Entrevistas/deleteRowOtro', {
                method: 'POST',
                body: myFormData
            });
            const data = await response.json();
            if (!data.status) {
                Swal.fire({
                    title: "ERROR AL ELIMINAR DATO DE OTRO TIPO",
                    icon: 'error',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'custom-confirm-btn'  // Clase CSS personalizada para el botón de confirmación
                    },
                    buttonsStyling: false
                })
            } else {//si todo salio bien
                Swal.fire({
                    title: "DATO DE OTRO TIPO ELIMINADO",
                    icon: 'error',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'custom-confirm-btn'  // Clase CSS personalizada para el botón de confirmación
                    },
                    buttonsStyling: false
                })
                RecargaDatosOtros();
            }
        }
    } catch (error) {
        console.log(error);
    }
}
//FUNCIONES DE TABLA PLACA/NIV
const GuardaPlacaNiv = async()=> {
    let band = [];
    let i = 0;

    
    band[i++] = document.getElementById('Dato_select_especifico_error').innerText = (document.getElementById('Dato_select_especifico').value!='-1')?'':'Campo Requerido';
    if(document.getElementById('placaTab').value.trim()=='' && document.getElementById('NivTab').value.trim()==''){
        band[i++] = document.getElementById('placaTab_error').innerText = 'Campo Requerido';
        band[i++] = document.getElementById('NivTab_error').innerText = 'Campo Requerido';
    }
    let success = true
    band.forEach(element => {//recorre todas la banderas si todas son vacias procede a guardar los datos ya que no existe ninguna restriccion 
        success &= (element == '') ? true : false
    });

    if(success){
        let myFormData = new FormData()
        myFormData.append('Id_Persona_Entrevista',document.getElementById('id_persona_entrevista').value);
        myFormData.append('Id_Dato_Entrevista',document.getElementById("Dato_select_especifico").value);
        myFormData.append('Id_PlacaNiv',document.getElementById("Id_PlacaNiv").value);
        myFormData.append('Placa',(document.getElementById("placaTab").value.trim()!='')?document.getElementById("placaTab").value.toUpperCase():'SD');
        myFormData.append('NIV',(document.getElementById("NivTab").value.trim()!='')?document.getElementById("NivTab").value.toUpperCase():'SD');
        myFormData.append('Capturo',document.getElementById('captura_dato_forencias').value.toUpperCase());
        //MANDAMOS TAMBIEN EL ID PROPIO SI ES -1 ES INSERT SI NO UPDATE

        /*for (var pair of myFormData.entries()) {
            console.log(pair[0] + ', ' + pair[1]);
        }*/
        //MOSTRAMOS MODAL , GUARDAMOS Y QUITAMOS MODAL
        $('#ModalCenterPrincipalforencias').modal('show');

        fetch(base_url_js + 'Entrevistas/UpdatePlacaNivTab', {//realiza el fetch para actualizar los datos
            method: 'POST',
            body: myFormData
        })
    
        .then(res => res.json())
    
        .then(data => {//obtine  respuesta del modelo
            setTimeout(function() {
                $('#ModalCenterPrincipalforencias').modal('hide');
            }, 500);

            if (!data.status) {
                document.getElementById('msg_datos').innerHTML =`<div class="alert alert-danger text-center" role="alert">Sucedio un error en el servidor: ${data.error_message} ${data.error_sql}</div>`;
            } else {//si todo salio bien
                document.getElementById('msg_datos').innerHTML =`<div class="alert alert-success text-center" role="success">Datos de Placa-Niv Actualizados Correctamente.
                                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>`;
                document.getElementById("Id_PlacaNiv").value = "-1";
                document.getElementById("Dato_select_especifico").value = "-1"; 
                document.getElementById("placaTab").value = "";
                document.getElementById("NivTab").value = "";
                document.getElementById('placaTab_error').innerText = '';
                document.getElementById('NivTab_error').innerText = '';
                //REFRESH CUANDO TODO HALLA QUEDADO
                RecargaDatosPlacaNiv();
            }
        })   
    }
}
const RecargaDatosPlacaNiv = async()=>{
    let table = document.getElementById('Placa_Niv_Table');
    let aux = document.getElementById('Placa_Niv_Count').rows.length;
    for(let i = 1; i < aux+1; i++){
        table.deleteRow(1);
    }
    datos = await getDatosPlacaNiv();
    for await(let dato of datos){
        let formData = {
            Id_PlacaNiv  : dato.Id_PlacaNiv  ,
            Id_Dato_Entrevista : dato.Id_Dato_Entrevista,
            Placa: dato.Placa,
            NIV: dato.NIV,
            Capturo : dato.Capturo 
        }
        await InsertDatosPlacaNiv(formData);//Inserta todos las forensias de las personas del seguimiento
    }
}
const getDatosPlacaNiv = async()=>{
    let myFormData = new FormData()
    myFormData.append('Id_Persona_Entrevista',document.getElementById('id_persona_entrevista').value);
    try {
        const response = await fetch(base_url_js + 'Entrevistas/getDatosPlacaNiv', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const InsertDatosPlacaNiv = async({Id_PlacaNiv, Id_Dato_Entrevista, Placa, NIV, Capturo})=>{
    let table = document.getElementById('Placa_Niv_Table').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length); 
    newRow.insertCell(0).innerHTML = Id_PlacaNiv
    newRow.insertCell(1).innerHTML = Id_Dato_Entrevista;
    newRow.insertCell(2).innerHTML = Placa;
    newRow.insertCell(3).innerHTML = NIV;
    newRow.insertCell(4).innerHTML = Capturo;
    newRow.insertCell(5).innerHTML =`<button type="button" class="btn btn-add mt-1" onclick="editPlacaNiv(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc mt-1" value="-" onclick="deleteRowPlacaNiv(this)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
}
const editPlacaNiv = async (obj)=>{
    document.getElementById('msg_datos').innerHTML =`<div class="alert  alert-primary text-center" role="alert">Editando Dato.
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                                                                <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>`;
    let selectedRow = obj.parentElement.parentElement;
    document.getElementById("Id_PlacaNiv").value = selectedRow.cells[0].innerHTML;
    document.getElementById("Dato_select_especifico").value = selectedRow.cells[1].innerHTML;  
    document.getElementById("placaTab").value = selectedRow.cells[2].innerHTML;
    document.getElementById("NivTab").value = selectedRow.cells[3].innerHTML;
}

const deleteRowPlacaNiv = async(obj)=>{//FUNCION QUE ELIMINA LOS DATOS DE LAS TABLAS DE FORENSIA    
    try {
        if (confirm('¿Desea eliminar este elemento?')) {
            let selectedRow = obj.parentElement.parentElement;
            let myFormData = new FormData()

            myFormData.append('Id_PlacaNiv',selectedRow.cells[0].innerHTML);
            const response = await fetch(base_url_js + 'Entrevistas/deleteRowPlacaNiv', {
                method: 'POST',
                body: myFormData
            });
            const data = await response.json();
            if (!data.status) {
                Swal.fire({
                    title: "ERROR AL ELIMINAR DATO DE PLACA - NIV",
                    icon: 'error',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'custom-confirm-btn'  // Clase CSS personalizada para el botón de confirmación
                    },
                    buttonsStyling: false
                })
            } else {//si todo salio bien
                Swal.fire({
                    title: "DATO DE PLACA - NIV ELIMINADO",
                    icon: 'error',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'custom-confirm-btn'  // Clase CSS personalizada para el botón de confirmación
                    },
                    buttonsStyling: false
                })
                RecargaDatosPlacaNiv();
            }
        }
    } catch (error) {
        console.log(error);
    }
}
//Funciones tabla ZONA DE OPERACION
const GuardaZona = async()=> {
    let band = [];
    let i = 0;

    band[i++] = document.getElementById('ZonaTab_error').innerText = (document.getElementById('ZonaTab').value.trim()!='')?'':'Campo Requerido';
    band[i++] = document.getElementById('Dato_select_especifico_error').innerText = (document.getElementById('Dato_select_especifico').value!='-1')?'':'Campo Requerido';

    let success = true
    band.forEach(element => {//recorre todas la banderas si todas son vacias procede a guardar los datos ya que no existe ninguna restriccion 
        success &= (element == '') ? true : false
    });

    if(success){
        let myFormData = new FormData()
        myFormData.append('Id_Persona_Entrevista',document.getElementById('id_persona_entrevista').value);
        myFormData.append('Id_Dato_Entrevista',document.getElementById("Dato_select_especifico").value);
        myFormData.append('Id_Zona',document.getElementById("Id_Zona").value);
        myFormData.append('Zona',document.getElementById("ZonaTab").value.toUpperCase());
        myFormData.append('Capturo',document.getElementById('captura_dato_forencias').value.toUpperCase());
        //MANDAMOS TAMBIEN EL ID PROPIO SI ES -1 ES INSERT SI NO UPDATE

        /*for (var pair of myFormData.entries()) {
            console.log(pair[0] + ', ' + pair[1]);
        }*/
        //MOSTRAMOS MODAL , GUARDAMOS Y QUITAMOS MODAL
        $('#ModalCenterPrincipalforencias').modal('show');

        fetch(base_url_js + 'Entrevistas/UpdateZonaTab', {//realiza el fetch para actualizar los datos
            method: 'POST',
            body: myFormData
        })
    
        .then(res => res.json())
    
        .then(data => {//obtine  respuesta del modelo
            setTimeout(function() {
                $('#ModalCenterPrincipalforencias').modal('hide');
            }, 500);

            if (!data.status) {
                document.getElementById('msg_datos').innerHTML =`<div class="alert alert-danger text-center" role="alert">Sucedio un error en el servidor: ${data.error_message} ${data.error_sql}</div>`;
            } else {//si todo salio bien
                document.getElementById('msg_datos').innerHTML =`<div class="alert alert-success text-center" role="success">Datos de Zona Operacion Actualizadas Correctamente.
                                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>`;
                document.getElementById("Id_Zona").value = "-1";
                document.getElementById("Dato_select_especifico").value = "-1"; 
                document.getElementById("ZonaTab").value = "";
                //REFRESH CUANDO TODO HALLA QUEDADO
                RecargaDatosZona();
            }
        })   
    }
}
const RecargaDatosZona = async()=>{
    let table = document.getElementById('Zona_Table');
    let aux = document.getElementById('Zona_Count').rows.length;
    for(let i = 1; i < aux+1; i++){
        table.deleteRow(1);
    }
    datos = await getDatosZona();
    for await(let dato of datos){
        let formData = {
            Id_Zona : dato.Id_Zona ,
            Id_Dato_Entrevista : dato.Id_Dato_Entrevista,
            Zona: dato.Zona,
            Capturo : dato.Capturo 
        }
        await InsertDatosZona(formData);//Inserta todos las forensias de las personas del seguimiento
    }
}
const getDatosZona = async()=>{
    let myFormData = new FormData()
    myFormData.append('Id_Persona_Entrevista',document.getElementById('id_persona_entrevista').value);
    try {
        const response = await fetch(base_url_js + 'Entrevistas/getDatosZona', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const InsertDatosZona = async({Id_Zona, Id_Dato_Entrevista, Zona, Capturo})=>{
    let table = document.getElementById('Zona_Table').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length); 
    newRow.insertCell(0).innerHTML = Id_Zona
    newRow.insertCell(1).innerHTML = Id_Dato_Entrevista;
    newRow.insertCell(2).innerHTML = Zona;
    newRow.insertCell(3).innerHTML = Capturo;
    newRow.insertCell(4).innerHTML =`<button type="button" class="btn btn-add mt-1" onclick="editZona(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc mt-1" value="-" onclick="deleteRowZona(this)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
}
const editZona = async (obj)=>{
    document.getElementById('msg_datos').innerHTML =`<div class="alert  alert-primary text-center" role="alert">Editando Dato.
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                                                                <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>`;
    let selectedRow = obj.parentElement.parentElement;
    document.getElementById("Id_Zona").value = selectedRow.cells[0].innerHTML;
    document.getElementById("Dato_select_especifico").value = selectedRow.cells[1].innerHTML;  
    document.getElementById("ZonaTab").value = selectedRow.cells[2].innerHTML;
}
const deleteRowZona = async(obj)=>{//FUNCION QUE ELIMINA LOS DATOS DE LAS TABLAS DE FORENSIA    
    try {
        if (confirm('¿Desea eliminar este elemento?')) {
            let selectedRow = obj.parentElement.parentElement;
            let myFormData = new FormData()

            myFormData.append('Id_Zona',selectedRow.cells[0].innerHTML);
            const response = await fetch(base_url_js + 'Entrevistas/deleteRowZona', {
                method: 'POST',
                body: myFormData
            });
            const data = await response.json();
            if (!data.status) {
                Swal.fire({
                    title: "ERROR AL ELIMINAR DATO DE ZONA DE OPERACION TIPO",
                    icon: 'error',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'custom-confirm-btn'  // Clase CSS personalizada para el botón de confirmación
                    },
                    buttonsStyling: false
                })
            } else {//si todo salio bien
                Swal.fire({
                    title: "DATO DE ZONA DE OPERACION TIPO ELIMINADO",
                    icon: 'error',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'custom-confirm-btn'  // Clase CSS personalizada para el botón de confirmación
                    },
                    buttonsStyling: false
                })
                RecargaDatosZona();
            }
        }
    } catch (error) {
        console.log(error);
    }
}
//Funciones tabla Banda
const GuardaBanda = async()=> {
    let band = [];
    let i = 0;

    band[i++] = document.getElementById('BandaTab_error').innerText = (document.getElementById('BandaTab').value.trim()!='')?'':'Campo Requerido';
    band[i++] = document.getElementById('Dato_select_especifico_error').innerText = (document.getElementById('Dato_select_especifico').value!='-1')?'':'Campo Requerido';

    let success = true
    band.forEach(element => {//recorre todas la banderas si todas son vacias procede a guardar los datos ya que no existe ninguna restriccion 
        success &= (element == '') ? true : false
    });

    if(success){
        let myFormData = new FormData()
        myFormData.append('Id_Persona_Entrevista',document.getElementById('id_persona_entrevista').value);
        myFormData.append('Id_Dato_Entrevista',document.getElementById("Dato_select_especifico").value);
        myFormData.append('Id_Banda',document.getElementById("Id_Banda").value);
        myFormData.append('Banda',document.getElementById("BandaTab").value.toUpperCase());
        myFormData.append('Capturo',document.getElementById('captura_dato_forencias').value.toUpperCase());
        //MANDAMOS TAMBIEN EL ID PROPIO SI ES -1 ES INSERT SI NO UPDATE

        /*for (var pair of myFormData.entries()) {
            console.log(pair[0] + ', ' + pair[1]);
        }*/
        //MOSTRAMOS MODAL , GUARDAMOS Y QUITAMOS MODAL
        $('#ModalCenterPrincipalforencias').modal('show');

        fetch(base_url_js + 'Entrevistas/UpdateBandaTab', {//realiza el fetch para actualizar los datos
            method: 'POST',
            body: myFormData
        })
    
        .then(res => res.json())
    
        .then(data => {//obtine  respuesta del modelo
            setTimeout(function() {
                $('#ModalCenterPrincipalforencias').modal('hide');
            }, 500);

            if (!data.status) {
                document.getElementById('msg_datos').innerHTML =`<div class="alert alert-danger text-center" role="alert">Sucedio un error en el servidor: ${data.error_message} ${data.error_sql}</div>`;
            } else {//si todo salio bien
                document.getElementById('msg_datos').innerHTML =`<div class="alert alert-success text-center" role="success">Datos de Banda Actualizadas Correctamente.
                                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>`;
                document.getElementById("Id_Banda").value = "-1";
                document.getElementById("Dato_select_especifico").value = "-1"; 
                document.getElementById("BandaTab").value = "";
                //REFRESH CUANDO TODO HALLA QUEDADO
                RecargaDatosBanda();
            }
        })   
    }
}
const RecargaDatosBanda = async()=>{
    let table = document.getElementById('Banda_Table');
    let aux = document.getElementById('Banda_Count').rows.length;
    for(let i = 1; i < aux+1; i++){
        table.deleteRow(1);
    }
    datos = await getDatosBanda();
    for await(let dato of datos){
        let formData = {
            Id_Banda : dato.Id_Banda ,
            Id_Dato_Entrevista : dato.Id_Dato_Entrevista,
            Banda: dato.Banda,
            Capturo : dato.Capturo 
        }
        await InsertDatosBanda(formData);//Inserta todos las forensias de las personas del seguimiento
    }
}
const getDatosBanda = async()=>{
    let myFormData = new FormData()
    myFormData.append('Id_Persona_Entrevista',document.getElementById('id_persona_entrevista').value);
    try {
        const response = await fetch(base_url_js + 'Entrevistas/getDatosBanda', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const InsertDatosBanda = async({Id_Banda, Id_Dato_Entrevista, Banda, Capturo})=>{
    let table = document.getElementById('Banda_Table').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length); 
    newRow.insertCell(0).innerHTML = Id_Banda
    newRow.insertCell(1).innerHTML = Id_Dato_Entrevista;
    newRow.insertCell(2).innerHTML = Banda;
    newRow.insertCell(3).innerHTML = Capturo;
    newRow.insertCell(4).innerHTML =`<button type="button" class="btn btn-add mt-1" onclick="editDatoBanda(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc mt-1" value="-" onclick="deleteRowBanda(this)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
}
const editDatoBanda = async (obj)=>{
    document.getElementById('msg_datos').innerHTML =`<div class="alert  alert-primary text-center" role="alert">Editando Dato.
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                                                                <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>`;
    let selectedRow = obj.parentElement.parentElement;
    document.getElementById("Id_Banda").value = selectedRow.cells[0].innerHTML;
    document.getElementById("Dato_select_especifico").value = selectedRow.cells[1].innerHTML;  
    document.getElementById("BandaTab").value = selectedRow.cells[2].innerHTML;
}
const deleteRowBanda = async(obj)=>{//FUNCION QUE ELIMINA LOS DATOS DE LAS TABLAS DE FORENSIA    
    try {
        if (confirm('¿Desea eliminar este elemento?')) {
            let selectedRow = obj.parentElement.parentElement;
            let myFormData = new FormData()

            myFormData.append('Id_Banda',selectedRow.cells[0].innerHTML);
            const response = await fetch(base_url_js + 'Entrevistas/deleteRowBanda', {
                method: 'POST',
                body: myFormData
            });
            const data = await response.json();
            if (!data.status) {
                Swal.fire({
                    title: "ERROR AL ELIMINAR DATO DE BANDA RELACIONADA",
                    icon: 'error',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'custom-confirm-btn'  // Clase CSS personalizada para el botón de confirmación
                    },
                    buttonsStyling: false
                })
            } else {//si todo salio bien
                Swal.fire({
                    title: "DATO DE BANDA RELACIONADA ELIMINADO",
                    icon: 'error',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'custom-confirm-btn'  // Clase CSS personalizada para el botón de confirmación
                    },
                    buttonsStyling: false
                })
                RecargaDatosBanda();
            }
        }
    } catch (error) {
        console.log(error);
    }
}
//Funciones tabla Nombre
const GuardaNombre = async()=> {
    let band = [];
    let i = 0;

    band[i++] = document.getElementById('NombreTab_error').innerText = (document.getElementById('NombreTab').value.trim()!='')?'':'Campo Requerido';
    band[i++] = document.getElementById('ApPaternoTab_error').innerText = (document.getElementById('ApPaternoTab').value.trim()!='')?'':'Campo Requerido';
    band[i++] = document.getElementById('ApMaternoTab_error').innerText = (document.getElementById('ApMaternoTab').value.trim()!='')?'':'Campo Requerido';
    band[i++] = document.getElementById('Dato_select_especifico_error').innerText = (document.getElementById('Dato_select_especifico').value!='-1')?'':'Campo Requerido';

    let success = true
    band.forEach(element => {//recorre todas la banderas si todas son vacias procede a guardar los datos ya que no existe ninguna restriccion 
        success &= (element == '') ? true : false
    });

    if(success){
        let myFormData = new FormData()
        myFormData.append('Id_Persona_Entrevista',document.getElementById('id_persona_entrevista').value);
        myFormData.append('Id_Dato_Entrevista',document.getElementById("Dato_select_especifico").value);
        myFormData.append('Id_Nombre',document.getElementById("Id_Nombre").value);
        myFormData.append('Nombre',document.getElementById("NombreTab").value.toUpperCase());
        myFormData.append('Apellido_Paterno',document.getElementById("ApPaternoTab").value.toUpperCase());
        myFormData.append('Apellido_Materno',document.getElementById("ApMaternoTab").value.toUpperCase());
        myFormData.append('Capturo',document.getElementById('captura_dato_forencias').value.toUpperCase());
        //MANDAMOS TAMBIEN EL ID PROPIO SI ES -1 ES INSERT SI NO UPDATE

        for (var pair of myFormData.entries()) {
            console.log(pair[0] + ', ' + pair[1]);
        }
        //MOSTRAMOS MODAL , GUARDAMOS Y QUITAMOS MODAL
        $('#ModalCenterPrincipalforencias').modal('show');

        fetch(base_url_js + 'Entrevistas/UpdateNombreTab', {//realiza el fetch para actualizar los datos
            method: 'POST',
            body: myFormData
        })
    
        .then(res => res.json())
    
        .then(data => {//obtine  respuesta del modelo
            setTimeout(function() {
                $('#ModalCenterPrincipalforencias').modal('hide');
            }, 500);

            if (!data.status) {
                document.getElementById('msg_datos').innerHTML =`<div class="alert alert-danger text-center" role="alert">Sucedio un error en el servidor: ${data.error_message} ${data.error_sql}</div>`;
            } else {//si todo salio bien
                document.getElementById('msg_datos').innerHTML =`<div class="alert alert-success text-center" role="success">Datos de Nombre Actualizadas Correctamente.
                                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>`;
                document.getElementById("Id_Nombre").value = "-1";
                document.getElementById("Dato_select_especifico").value = "-1"; 
                document.getElementById("NombreTab").value = "";
                document.getElementById("ApPaternoTab").value = "";
                document.getElementById("ApMaternoTab").value = "";
                //REFRESH CUANDO TODO HALLA QUEDADO
                RecargaDatosNombre();
            }
        })   
    }
}
const RecargaDatosNombre = async()=>{
    let table = document.getElementById('Nombre_Table');
    let aux = document.getElementById('Nombre_Count').rows.length;
    for(let i = 1; i < aux+1; i++){
        table.deleteRow(1);
    }
    datos = await getDatosNombre();
    for await(let dato of datos){
        let formData = {
            Id_Nombre : dato.Id_Nombre ,
            Id_Dato_Entrevista : dato.Id_Dato_Entrevista,
            Nombre: dato.Nombre,
            Apellido_Paterno: dato.Apellido_Paterno,
            Apellido_Materno: dato.Apellido_Materno,
            Capturo : dato.Capturo 
        }
        await InsertDatosNombre(formData);//Inserta todos las forensias de las personas del seguimiento
    }
}
const getDatosNombre = async()=>{
    let myFormData = new FormData()
    myFormData.append('Id_Persona_Entrevista',document.getElementById('id_persona_entrevista').value);
    try {
        const response = await fetch(base_url_js + 'Entrevistas/getDatosNombre', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const InsertDatosNombre = async({Id_Nombre, Id_Dato_Entrevista, Nombre, Apellido_Paterno, Apellido_Materno, Capturo})=>{
    let table = document.getElementById('Nombre_Table').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length); 
    newRow.insertCell(0).innerHTML = Id_Nombre
    newRow.insertCell(1).innerHTML = Id_Dato_Entrevista;
    newRow.insertCell(2).innerHTML = Nombre;
    newRow.insertCell(3).innerHTML = Apellido_Paterno;
    newRow.insertCell(4).innerHTML = Apellido_Materno;
    newRow.insertCell(5).innerHTML = Capturo;
    newRow.insertCell(6).innerHTML =`<button type="button" class="btn btn-add mt-1" onclick="editNombre(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc mt-1" value="-" onclick="deleteRowNombre(this)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
}
const editNombre = async (obj)=>{
    document.getElementById('msg_datos').innerHTML =`<div class="alert  alert-primary text-center" role="alert">Editando Dato.
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                                                                <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>`;
    let selectedRow = obj.parentElement.parentElement;
    document.getElementById("Id_Nombre").value = selectedRow.cells[0].innerHTML;
    document.getElementById("Dato_select_especifico").value = selectedRow.cells[1].innerHTML;  
    document.getElementById("NombreTab").value = selectedRow.cells[2].innerHTML;
    document.getElementById("ApPaternoTab").value = selectedRow.cells[3].innerHTML;
    document.getElementById("ApMaternoTab").value = selectedRow.cells[4].innerHTML;
}
const deleteRowNombre = async(obj)=>{//FUNCION QUE ELIMINA LOS DATOS DE LAS TABLAS DE FORENSIA    
    try {
        if (confirm('¿Desea eliminar este elemento?')) {
            let selectedRow = obj.parentElement.parentElement;
            let myFormData = new FormData()

            myFormData.append('Id_Nombre',selectedRow.cells[0].innerHTML);
            const response = await fetch(base_url_js + 'Entrevistas/deleteRowNombre', {
                method: 'POST',
                body: myFormData
            });
            const data = await response.json();
            if (!data.status) {
                Swal.fire({
                    title: "ERROR AL ELIMINAR DATO DE NOMBRE RELACIONADO",
                    icon: 'error',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'custom-confirm-btn'  // Clase CSS personalizada para el botón de confirmación
                    },
                    buttonsStyling: false
                })
            } else {//si todo salio bien
                Swal.fire({
                    title: "DATO DE NOMBRE RELACIONADO ELIMINADO",
                    icon: 'error',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'custom-confirm-btn'  // Clase CSS personalizada para el botón de confirmación
                    },
                    buttonsStyling: false
                })
                RecargaDatosNombre();
            }
        }
    } catch (error) {
        console.log(error);
    }
}