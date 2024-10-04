document.addEventListener('DOMContentLoaded',async() => {
    Id_Punto = getPuntoSearch();
   
    let dataPunto = await getInfoPunto(Id_Punto);
    let datosPunto = await getDatosPunto(Id_Punto);    
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
    disableForm(document.getElementById('datos_principales_puntos'))
});
const disableForm = async (theform) =>{
    if (document.all || document.getElementById) {
        for (i = 0; i < theform.length; i++) {
        var formElement = theform.elements[i];
            if (true) {
                formElement.disabled = true;
            }
        }
    }
}
const randomNum = Math.random();
const llenarVista = async (data) =>{
    document.getElementById('Id_Punto').value = data.Id_Punto;
    document.getElementById('Capturo').value = data.Capturo;
    document.getElementById('Fecha_Captura').value = data.Fecha_Captura;
    document.getElementById('cordY').value = data.CoordY;
    document.getElementById('cordX').value = data.CoordX;


    document.getElementById('Colonia').value = data.Colonia;
    document.getElementById('Calle').value = data.Calle;
    document.getElementById('Calle2').value = data.Calle2;
    document.getElementById('no_Ext').value = data.NoExt;
    document.getElementById('CP').value = data.CP;


    document.getElementById('zona').value = data.Zona;

    document.getElementById('vector').value = data.Vector;

    document.getElementById('Fuente_info').value = data.Fuente_Info;
    document.getElementById('Estatus_Punto').value = data.Estatus_Punto;
    document.getElementById('Identificador').value = data.Identificador;

   
    document.getElementById('nombre').value = data.Nombre_Detenido;
    document.getElementById('Narrativa').value = data.Narrativa;
    document.getElementById('id_remision').value = data.Remision;
    
    
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
            div.innerHTML = `
                            <img name="nor" src="${ruta}" id="imagesMaps" width="500px" data-toggle="modal" data-target="#ModalCenterMaps">
                            <input type="hidden" class="Photo"/>
                            <div class="modal fade " id="ModalCenterMaps" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <img name="nor" src="${ruta}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                </div>
                            </div>`;
        }else{
            div.innerHTML = `
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
            div.innerHTML = `
                        <img name="nor" src="${ruta}" id="imagesUbis" width="500px" data-toggle="modal" data-target="#ModalCenterUbi">
                        <input type="hidden" class="Photo"/>
                        <div class="modal fade " id="ModalCenterUbi" tabindex="-1" role="dialog" aria-labelledby="exampleModalUbi" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <img name="nor" src="${ruta}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalUbi">
                            </div>
                        </div>`;
    
        }else{
            div.innerHTML = `
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

const InsertgetDatosUbi = async({Id_Dato_Punto, Id_Punto, Tipo_Dato, Descripcion_Dato, Img_64_Dato, Path_Imagen_Dato, Capturo})=>{//FUNCION QUE INSERTA LOS DATOS DE LAS PERSONAS ASOCIADAS AL SEGUIMIENTO
    let pathImages = base_url_js+'public/files/Puntos/'+Id_Punto+'/Datos/';
    let table = document.getElementById('DatosUbiTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    if(Path_Imagen_Dato!='SD'){
        let ruta = pathImages+Path_Imagen_Dato;
        let ban = await imageExists(ruta)
        if(ban==true){
            
            ruta = ruta+'?nocache='+ randomNum;
            newRow.insertCell(0).innerHTML =`<div id="imageContentDato_row${newRow.rowIndex}">
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
                newRow.insertCell(0).innerHTML =`<div id="imageContentDato_row${newRow.rowIndex}">
                                                    <img name="nor" src="${Img_64_Dato}" id="imagesDato_row_${newRow.rowIndex}" width="400px" data-toggle="modal" data-target="#ModalCenterDatoUbi${newRow.rowIndex}">
                                                    <input type="hidden" class="${newRow.rowIndex} Photo"/>
                                                    <div class="modal fade " id="ModalCenterDatoUbi${newRow.rowIndex}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                            <img name="nor" src="${Img_64_Dato}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                                                        </div>
                                                    </div> 
                                                </div>`;

            }else{
                newRow.insertCell(0).innerHTML = `<div id="imageContentDato_row${newRow.rowIndex}"></div>`;
            }
        }
        
    }else{
        
        newRow.insertCell(0).innerHTML = `<div id="imageContentDato_row${newRow.rowIndex}"></div>`;
    }

    newRow.insertCell(1).innerHTML = Descripcion_Dato.toUpperCase();
    newRow.insertCell(2).innerHTML = Tipo_Dato;
    newRow.insertCell(3).innerHTML = Capturo;
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

