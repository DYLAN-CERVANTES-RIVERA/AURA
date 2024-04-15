/*----------------------ESTE ARCHIVO DE JAVASCRIPT ES PARA OBTENER LOS DATOS DEL EVENTO PARA LA TAB DE ENTREVISTAS Y FOTOS PARA SOLO LA VISUALIZACION---------------------------------------*/ 
let id_ubicacion_contenedor = document.getElementById('id_ubicacion');
let id_camara_contenedor = document.getElementById('id_camara');
document.addEventListener('DOMContentLoaded', async () => {
    evento = getEventotoSearch();
    Entrevistas = await getEntrevistas(evento);
    fotos =  await getFotos(evento);
    if(Entrevistas!=undefined){
        Entrevistas.forEach(entrevista => insertNewRowTablaEntrevista(entrevista));
    }
    if(fotos!=undefined){
        for (let i = 0; i < fotos.length; i++) {//Forma correcta de ir renglon por rengloen en la insercion de datos existentes
            let formData = {
                Folio_infra: fotos[i].Folio_infra,
                Descripcion: fotos[i].Descripcion,
                Path_Imagen: fotos[i].Path_Imagen,
                id_ubicacion: fotos[i].id_ubicacion,
                ColoniaF: fotos[i].ColoniaF,
                CalleF: fotos[i].CalleF,
                Calle2F: fotos[i].Calle2F,
                no_ExtF: fotos[i].no_ExtF,
                CPF: fotos[i].CPF,
                cordYF: fotos[i].cordYF,
                cordXF: fotos[i].cordXF,
                id_camara: fotos[i].id_camara,
                fecha_captura_foto: fotos[i].fecha_captura_foto,
                hora_captura_foto: fotos[i].hora_captura_foto,
                fecha_hora_captura_sistema: fotos[i].fecha_hora_captura_sistema,
                img_64: fotos[i].img_64
            }
           await insertNewRowTablaFotos(formData);//Inserta todos los vehiculos del evento
        }
    }
});
const insertNewRowTablaEntrevista= ({ procedencia,entrevista,entrevistado,entrevistador,edad_entrevistado,telefono_entrevistado,fecha_entrevista,hora_entrevista}) => {//Funcion que inserta los datos obtenidos del evento en la tabla de entrevistas 
    const table = document.getElementById('entrevistasTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    //Empieza generar la tabla de vizualizacion de entrevistas
    newRow.insertCell(0).innerHTML = procedencia.toUpperCase();
    newRow.insertCell(1).innerHTML = entrevista.toUpperCase();
    newRow.insertCell(2).innerHTML = entrevistado.toUpperCase();
    newRow.insertCell(3).innerHTML = entrevistador.toUpperCase();
    newRow.insertCell(4).innerHTML = edad_entrevistado;
    newRow.insertCell(5).innerHTML = telefono_entrevistado;
    newRow.insertCell(6).innerHTML = fecha_entrevista;
    newRow.insertCell(7).innerHTML = hora_entrevista;
}
const insertNewRowTablaFotos = async ({Folio_infra,Descripcion,Path_Imagen,id_ubicacion,ColoniaF,CalleF,Calle2F,no_ExtF,CPF,cordYF,cordXF,id_camara,fecha_captura_foto,hora_captura_foto,fecha_hora_captura_sistema,img_64}) => {//FUNCION QUE INSERTA LOS DATOS OBETENIDOS DEL EVENTO EN LA TABLA DE FOTO
    pathImagesFotos = `${base_url_js}public/files/GestorCasos/${Folio_infra}/Seguimiento/`;
    const table = document.getElementById('fotosTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    srcImage = Path_Imagen;
    srcImage = srcImage.split('?')
    //Empieza generar la tabla de vizualizacion de fotos
    newRow.insertCell(0).innerHTML = id_ubicacion;
    ruta=pathImagesFotos + srcImage[0]
    ban = await imageExists(ruta)
    if(ban==true){

        newRow.insertCell(1).innerHTML =`<div id="imageContent_row${newRow.rowIndex}">
                <img name="nor" src="${pathImagesFotos + srcImage[0]}" id="images_row_${newRow.rowIndex}" width="250px" data-toggle="modal" data-target="#ModalCenterFotos2${newRow.rowIndex}">
                <input type="hidden" class="${newRow.rowIndex} Photo"/>
                <div class="modal fade " id="ModalCenterFotos2${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <img name="nor" src="${pathImagesFotos + srcImage[0]}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                    </div>
                </div>
            </div>`;
    }else{
        if(img_64!='SD'&&img_64!=' '&&img_64!=''){
            newRow.insertCell(1).innerHTML =`<div id="imageContent_row${newRow.rowIndex}">
                    <img name="nor" src="${img_64}" id="images_row_${newRow.rowIndex}" width="250px" data-toggle="modal" data-target="#ModalCenterFotos2${newRow.rowIndex}">
                    <input type="hidden" class="${newRow.rowIndex} Photo"/>
                    <div class="modal fade " id="ModalCenterFotos2${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <img name="nor" src="${img_64}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                        </div>
                    </div>
                </div>`;

        }else{
            newRow.insertCell(1).innerHTML =`<div id="imageContent_row${newRow.rowIndex}">
                                                <label>NO EXISTE FOTO VERIFICAR RESPALDO</label>
                                            </div>`;
        }
    }

    newRow.insertCell(2).innerHTML = id_camara;
    newRow.insertCell(3).innerHTML = Descripcion.toUpperCase();
    newRow.insertCell(4).innerHTML =ColoniaF;
    newRow.insertCell(5).innerHTML =CalleF;
    newRow.insertCell(6).innerHTML =Calle2F;
    newRow.insertCell(7).innerHTML =no_ExtF;
    newRow.insertCell(8).innerHTML =CPF;
    newRow.insertCell(9).innerHTML =cordYF;
    newRow.insertCell(10).innerHTML =cordXF; 
    newRow.insertCell(11).innerHTML =fecha_captura_foto; 
    newRow.insertCell(12).innerHTML =hora_captura_foto; 
    newRow.insertCell(13).innerHTML =fecha_hora_captura_sistema;  
}
const getEntrevistas = async (caso) => { //Funcion para realizar una peticion para la consulta a la tabla  
    try {
        var myFormData = new FormData();
        myFormData.append('Folio_infra',caso)
        const response = await fetch(base_url_js + 'GestorCasos/getEntrevistas', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
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
id_ubicacion_contenedor.addEventListener('change', async() => {//FUNCION DE VISTA PARA FILTRO DE TABLA FOTOS
    aux= await dropTablaContentFotos();
    evento = getEventotoSearch();
    fotos =  await getFotos(evento);
    if(aux== true){
        if(id_ubicacion_contenedor.value=='NA'){
            fotos.forEach( foto => insertNewRowTablaFotos(foto))//ACTUALIZA LOS DATOS DE ACUERDO AL FILTRO DE UBICACION
        }else{
            for (let i = 0; i < fotos.length; i++) {
                if(id_ubicacion_contenedor.value==fotos[i].id_ubicacion){
                    let formData = {
                        Folio_infra:fotos[i].Folio_infra,
                        Descripcion:fotos[i].Descripcion,
                        Path_Imagen:fotos[i].Path_Imagen,
                        id_ubicacion:fotos[i].id_ubicacion,
                        ColoniaF:fotos[i].ColoniaF,
                        CalleF:fotos[i].CalleF,
                        Calle2F:fotos[i].Calle2F,
                        no_ExtF:fotos[i].no_ExtF,
                        CPF:fotos[i].CPF,
                        cordYF:fotos[i].cordYF,
                        cordXF:fotos[i].cordXF,
                        id_camara:fotos[i].id_camara,
                        fecha_captura_foto:fotos[i].fecha_captura_foto,
                        hora_captura_foto:fotos[i].hora_captura_foto,
                        fecha_hora_captura_sistema:fotos[i].fecha_hora_captura_sistema
                    }
                    insertNewRowTablaFotos(formData);
                }
            }
        }
        id_camara_contenedor.value='NA';
    }
}); 
id_camara_contenedor.addEventListener('change', async() => {//FUNCION DE VISTA PARA FILTRO DE TABLA FOTOS
    aux= await dropTablaContentFotos();
    evento = getEventotoSearch();
    fotos =  await getFotos(evento);
    if(aux== true){
        if(id_ubicacion_contenedor.value=='NA'&& id_camara_contenedor.value=='NA'){
            fotos.forEach( foto => insertNewRowTablaFotos(foto))//ACTUALIZA LOS DATOS DE ACUERDO AL FILTRO DE CAMARA
        }else{
            if(id_camara_contenedor.value=='NA'){
                for (let i = 0; i < fotos.length; i++) {
                    if(id_ubicacion_contenedor.value==fotos[i].id_ubicacion){
                        let formData = {
                            Folio_infra:fotos[i].Folio_infra,
                            Descripcion:fotos[i].Descripcion,
                            Path_Imagen:fotos[i].Path_Imagen,
                            id_ubicacion:fotos[i].id_ubicacion,
                            ColoniaF:fotos[i].ColoniaF,
                            CalleF:fotos[i].CalleF,
                            Calle2F:fotos[i].Calle2F,
                            no_ExtF:fotos[i].no_ExtF,
                            CPF:fotos[i].CPF,
                            cordYF:fotos[i].cordYF,
                            cordXF:fotos[i].cordXF,
                            id_camara:fotos[i].id_camara,
                            fecha_captura_foto:fotos[i].fecha_captura_foto,
                            hora_captura_foto:fotos[i].hora_captura_foto,
                            fecha_hora_captura_sistema:fotos[i].fecha_hora_captura_sistema
                        }
                        insertNewRowTablaFotos(formData);
                    }
                }

            }else{
                for (let i = 0; i < fotos.length; i++) {
                    if(id_ubicacion_contenedor.value==fotos[i].id_ubicacion && id_camara_contenedor.value==fotos[i].id_camara){
                        let formData = {
                            Folio_infra:fotos[i].Folio_infra,
                            Descripcion:fotos[i].Descripcion,
                            Path_Imagen:fotos[i].Path_Imagen,
                            id_ubicacion:fotos[i].id_ubicacion,
                            ColoniaF:fotos[i].ColoniaF,
                            CalleF:fotos[i].CalleF,
                            Calle2F:fotos[i].Calle2F,
                            no_ExtF:fotos[i].no_ExtF,
                            CPF:fotos[i].CPF,
                            cordYF:fotos[i].cordYF,
                            cordXF:fotos[i].cordXF,
                            id_camara:fotos[i].id_camara,
                            fecha_captura_foto:fotos[i].fecha_captura_foto,
                            hora_captura_foto:fotos[i].hora_captura_foto,
                            fecha_hora_captura_sistema:fotos[i].fecha_hora_captura_sistema
                        }
                        insertNewRowTablaFotos(formData);
                    }
                }

            }

        }
    }
}); 
const dropTablaContentFotos = async () => {//VACIA EL CONTENIDO DE LA TABLA FOTOS SIN TOCAR EL ENCABEZADO
    ban= true;
    table = document.getElementById('fotosTable');
    aux=document.getElementById('tablaEntrevistas-B').rows.length;
    for(let i = 1; i < aux+1; i++){
        table.deleteRow(1);
    }
    return ban;
}