async function  RecargaSelectIndicativo() {//REFRESCA EL SELECTOR DEL FORENSIA CON LOS DATOS DE PERSONAS Y VEHICULOS GUARDADOS EN EL SEGUIMIENTO  
    // Obtener referencia al elemento select
    /*var select = document.getElementById("Id_persona_seguimiento");

    let Personas = await getPersonaSeguimiento();
    let cad='';
    // Generar las opciones del select
    for (var i = 0; i < Personas.length; i++) {
        option = document.createElement("option");
        cad=(Personas[i]['Alias']!='SD')?" ( "+Personas[i]['Alias']+" ) DE ":" DE "
        option.text = Personas[i]['Nombre_completo'] + cad +Personas[i]['Banda'];
        option.value = Personas[i]['Id_Persona'];
        select.add(option);
    }*/

    let select2 = document.getElementById("indicativo_entrevistador");
    let indicativos = await getIndicativos();
    // Generar las opciones del select
    for (var i = 0; i < indicativos.length; i++) {
        option = document.createElement("option");
        option.text = indicativos[i]['Indicativo'] ;
        option.value = indicativos[i]['Indicativo'] ;
        select2.add(option);
    }
    document.getElementById('hora_entrevista').value = getHora()
}
const getPersonaSeguimiento = async () => { //Funcion que realizar peticion para obtener los datos de las personas del seguimiento
    try {
        const response = await fetch(base_url_js + 'Entrevistas/getPersonaSeguimiento', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const getIndicativos = async () => { //Funcion que realizar peticion para obtener los datos de las personas del seguimiento
    try {
        const response = await fetch(base_url_js + 'Entrevistas/getIndicativos', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const getFecha= () => { //Funcion para Obtener la fecha actual en el formato para el html
    var options = {//Formato DD/MM/YYYY
        year: "numeric",
        month: "2-digit",
        day: "2-digit"
    };
    const str = new Date().toLocaleString('es-MX', options );
    invertir=str.split('/')
    invertir= invertir.reverse();
    unir=invertir.join('-')
    return unir;
}
const getHora= () => { //Funcion para Obtener la hora actual en el formato para el html
    const str = new Date().toLocaleString('es-MX', );
    auxhoy=str;
    separadas = auxhoy.split(',');
    aux=separadas[1];
    aux=aux.substring(1, 6);
    return aux;
} 

const getEntrevistas = async (Persona_Entrevista) => { //Funcion que realizar peticion para obtener los datos de las entrevistas del evento
    try {
        var myFormData = new FormData();
        myFormData.append('Id_Persona_Entrevista',Persona_Entrevista)
        const response = await fetch(base_url_js + 'Entrevistas/getEntrevistas', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}

const insertNewRowTablaEntrevista= async({id_entrevista,id_persona_entrevista,indicativo_entrevistador,alias_referidos,relevancia,entrevista,fecha_entrevista,hora_entrevista,captura_entrevistas,Foto,Img_64}) => {//Funcion que inserta los datos obtenidos del evento en la tabla de entrevistas 
    let pathImagesEntrevistas =base_url_js+'public/files/Entrevistas/'+id_persona_entrevista+'/FotosEntrevistas/';
    const table = document.getElementById('entrevistasTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    //Empieza generar la tabla de vizualizacion de entrevistas
    newRow.insertCell(0).innerHTML = id_entrevista;
    newRow.insertCell(1).innerHTML = id_persona_entrevista;
    //newRow.insertCell(2).innerHTML = (Id_persona_seguimiento==''||Id_persona_seguimiento==null)?'SD':Id_persona_seguimiento;
    newRow.insertCell(2).innerHTML = indicativo_entrevistador;
    newRow.insertCell(3).innerHTML = (alias_referidos=='SD'||alias_referidos.trim()=='')?'SD':alias_referidos;
    newRow.insertCell(4).innerHTML = relevancia;
    newRow.insertCell(5).innerHTML = entrevista;
    newRow.insertCell(6).innerHTML = fecha_entrevista;
    newRow.insertCell(7).innerHTML = hora_entrevista;
    if(Foto!='SD'){
        let ruta = pathImagesEntrevistas+Foto;
        let ban = await imageExists(ruta)
        if(ban==true){
          ruta = ruta+'?nocache='+getRandomInt(50);
          newRow.insertCell(8).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoEntrevista${newRow.rowIndex}">
                                            <div class="form-group">
                                                <input type="file" name="FotoEntrevista_row${newRow.rowIndex}" accept="image/*" id="fileFotoEntrevista_row${newRow.rowIndex}" class="inputfile uploadFileFotoEntrevista" onchange="uploadFileEntrevista(event)" data-toggle="tooltip" data-placement="bottom">
                                                <label for="fileFotoEntrevista_row${newRow.rowIndex}"></label>
                                            </div>
                                        </div>
                                        <div id="imageContentEntrevista_row${newRow.rowIndex}">
                                            <div class="d-flex justify-content-end">
                                                <span onclick="deleteImageFotoEntrevista(${newRow.rowIndex})" class="deleteFile">X</span>
                                            </div>
                                            <img name="nor" src="${ruta}" id="imagesEntrevista_row_${newRow.rowIndex}" width="400px" data-toggle="modal" data-target="#ModalCenterEntrevista${newRow.rowIndex}">
                                            <input type="hidden" class="${newRow.rowIndex} Photo"/>
                                            <div class="modal fade " id="ModalCenterEntrevista${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <img name="nor" src="${ruta}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                                </div>
                                            </div>  
                                        </div>`;

        }else{
            if(Img_64!='SD'){
                newRow.insertCell(8).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoEntrevista${newRow.rowIndex}">
                                                    <div class="form-group">
                                                        <input type="file" name="FotoEntrevista_row${newRow.rowIndex}" accept="image/*" id="fileFotoEntrevista_row${newRow.rowIndex}" class="inputfile uploadFileFotoEntrevista" onchange="uploadFileEntrevista(event)" data-toggle="tooltip" data-placement="bottom">
                                                        <label for="fileFotoEntrevista_row${newRow.rowIndex}"></label>
                                                    </div>
                                                </div>
                                                <div id="imageContentEntrevista_row${newRow.rowIndex}">
                                                    <div class="d-flex justify-content-end">
                                                        <span onclick="deleteImageFotoEntrevista(${newRow.rowIndex})" class="deleteFile">X</span>
                                                    </div>
                                                    <img name="nor" src="${Img_64}" id="imagesEntrevista_row_${newRow.rowIndex}" width="400px" data-toggle="modal" data-target="#ModalCenterEntrevista${newRow.rowIndex}">
                                                    <input type="hidden" class="${newRow.rowIndex} Photo"/>
                                                    <div class="modal fade " id="ModalCenterEntrevista${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                            <img name="nor" src="${Img_64}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                                        </div>
                                                    </div>  
                                                </div>`;

            }else{
                newRow.insertCell(8).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoEntrevista${newRow.rowIndex}">
                                                    <div class="form-group">
                                                        <input type="file" name="FotoEntrevista_row${newRow.rowIndex}" accept="image/*" id="fileFotoEntrevista_row${newRow.rowIndex}" class="inputfile uploadFileFotoEntrevista" onchange="uploadFileEntrevista(event)" data-toggle="tooltip" data-placement="bottom">
                                                        <label for="fileFotoEntrevista_row${newRow.rowIndex}"></label>
                                                    </div>
                                                </div>
                                                <div id="imageContentEntrevista_row${newRow.rowIndex}"></div>`;

            }
        }
        
    }else{
        newRow.insertCell(8).innerHTML =`<div class="d-flex justify-content-around" id="uploadFileFotoEntrevista${newRow.rowIndex}">
                                            <div class="form-group">
                                                <input type="file" name="FotoEntrevista_row${newRow.rowIndex}" accept="image/*" id="fileFotoEntrevista_row${newRow.rowIndex}" class="inputfile uploadFileFotoEntrevista" onchange="uploadFileEntrevista(event)" data-toggle="tooltip" data-placement="bottom">
                                                <label for="fileFotoEntrevista_row${newRow.rowIndex}"></label>
                                            </div>
                                        </div>
                                        <div id="imageContentEntrevista_row${newRow.rowIndex}"></div>`;

    }


    newRow.insertCell(9).innerHTML = captura_entrevistas;

    newRow.insertCell(10).innerHTML = `<button type="button" class="btn btn-add d-flex" onclick="editEntrevistas(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button><br>
                                    <button type="button" class="btn btn-ssc d-flex" value="-" onclick="deleteEntrevista(this)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
    //newRow.cells[0].style.display = "none";
    newRow.cells[1].style.display = "none";

}

const dropTablaContentEntrevistas = async () => {//VACIA EL CONTENIDO DE LA TABLA ENTREVISTAS SIN TOCAR EL ENCABEZADO
    ban= true;
    table = document.getElementById('entrevistasTable');
    aux=document.getElementById('tablaEntrevistasCount').rows.length;
    for(let i = 1; i < aux+1; i++){
        table.deleteRow(1);
    }
    return ban;
}
/*---------------------FUNCIONES DE REFRESCO--------------------------- */
const RecargaDatosEntrevistas = async()=>{//Funcion que actualiza la vista de la tabla de redes sociales cada vez que se guarden o eliminen datos
    await dropTablaContentEntrevistas();
    let Entrevistas = await getEntrevistas(Persona_Entrevista);
    if(Entrevistas!=undefined){
        for await(let Entrevista of Entrevistas){
            let formDataEntrevista = {
                id_entrevista : Entrevista.Id_Entrevista ,
                id_persona_entrevista: Entrevista.Id_Persona_Entrevista,
                indicativo_entrevistador: Entrevista.Indicativo_Entrevistador,
                alias_referidos: Entrevista.Alias_Referidos,
                relevancia: Entrevista.Relevancia,
                entrevista: Entrevista.Entrevista,
                fecha_entrevista:Entrevista.Fecha_Entrevista,
                hora_entrevista:Entrevista.Hora_Entrevista,
                captura_entrevistas:Entrevista.Capturo,
                Foto:Entrevista.Foto,
                Img_64:Entrevista.Img_64
            }
            await insertNewRowTablaEntrevista(formDataEntrevista);//Inserta todas las personas del seguimiento
        }
        await RecargaSelects();
    }
}