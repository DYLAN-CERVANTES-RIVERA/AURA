/*----------------ESTE ARCHIVO DE JAVASCRIPT ES PARA LA FUNCIONALIDADES DEL MAPA DE ENTREVISTAS-------------*/
var error_codigopd = document.getElementById("CP_Det_principales_error");
var error_calle_1d = document.getElementById("Calle_Det_principales_error");
document.getElementById('porDireccion_Det').addEventListener('change',seleccionar_busquedaD);
document.getElementById('porCoordenadas_Det').addEventListener('change',seleccionar_busquedaD);
/*----------------------------------------------Funcionalidades del mapa de la tab principal------------------------------------------ */
var ubicacionDetenido = {
    'porDireccion': document.getElementById('porDireccion_Det'),
    'porCoordenada': document.getElementById('porCoordenadas_Det'),
    'Colonia': document.getElementById('Colonia_Det'),
    'Calle': document.getElementById('Calle_Det'),
    'Exterior': document.getElementById('no_Ext_Det'),
    'coordenadaX': document.getElementById('cordX_Det'),
    'coordenadaY': document.getElementById('cordY_Det'),
    'codigo_postal': document.getElementById('CP_Det'),
    'Estado': '',
    'Municipio': ''
}
const offlineMapsPrincipalID =async () => {

    document.getElementById("map_mapbox_Det").innerHTML = `
        <div class="d-flex align-items-center" style="height:100%">
            <div>
                <span class="badge badge-pill badge-warning">Sin conexión a internet</span>
                <h2 class="my-4" style="color:#88072D">¡OH NO! ERROR 404</h2>
                <p>Lo sentimos, al parecer no tienes conexión a internet ó la señal es muy débil.</p>
            </div>
        </div>
    `;
}
window.addEventListener('offline',async function(e) {
    await offlineMapsPrincipalID();
});
mapboxgl.accessToken = API_MAPBOX;
const getColoniasCallesD = async(e) => {//FUNCION PARA LA BUSQUEDA DE UNA UBICACION POR COORDENADAS
    ubicacionDetenido['Calle'].value="";
    ubicacionDetenido['Exterior'].value="";
    ubicacionDetenido['codigo_postal'].value="";
    coord_x = ubicacionDetenido['coordenadaX'].value;
    coord_y =  ubicacionDetenido['coordenadaY'].value;
    cordX_principales_error = document.getElementById("cordX_Det_principales_error");
    cordY_principales_error = document.getElementById("cordY_Det_principales_error");
    (document.getElementById("CP_Det_principales_error")).textContent="";
    (document.getElementById("Calle_Det_principales_error")).textContent="";
    var FV = new FormValidator();
    if(coord_x=="" || coord_y==""){
        cordX_principales_error.textContent = FV.validate(coord_x, "required | max_length[50]");
        cordY_principales_error.textContent = FV.validate(coord_y, "required |max_length[50]");
    }
    else{
        cordX_principales_error.textContent="";
        cordY_principales_error.textContent="";
        map3.flyTo({
        center: [
            coord_x,
            coord_y
        ],
        zoom: 18,
        essential: true
        });
        marker3.setLngLat([coord_x,coord_y])
        var lngLat = {
            lng : coord_x,
            lat : coord_y,
        }
        direccion = await getDireccionD(lngLat)
        mun_="";esta_="";codi_="";calle_="";nume_="";coloni_="";esta_2="";
        for(i=0;i<(direccion.features).length;i++){
            if((direccion.features[i].id).includes("place"))
                mun_=direccion.features[i].place_name
            if((direccion.features[i].id).includes("region"))
                esta_=direccion.features[i].place_name
            if((direccion.features[i].id).includes("postcode"))
                codi_=direccion.features[i].place_name
            if((direccion.features[i].id).includes("address")){
                calle_=direccion.features[i].text
                nume_=direccion.features[i].address
            }
            if((direccion.features[i].id).includes("locality"))
                coloni_=direccion.features[i].text 
        }
        ubicacionDetenido['Municipio'].value=(mun_.split(","))[0]
        ubicacionDetenido['Estado'].value=(esta_.split(","))[0]
        if(esta_=="")
            ubicacionDetenido['Estado'].value=(codi_.split(","))[0]
        
        if(codi_!=undefined){
            ubicacionDetenido['codigo_postal'].value=(codi_.split(","))[((codi_.split(","))).length-2]
        }
        
        if(nume_!=undefined){
            ubicacionDetenido['Exterior'].value=nume_
        }
        

    }
}

var map3 = new mapboxgl.Map({
    container: 'map_mapbox_Det',
    style: 'mapbox://styles/mapbox/streets-v11',
    center: [-98.20868494860592, 19.040296987811555], // starting position
    zoom: 11 // starting zoom

});//INICIALIZACION DEL MAPA 

map3.addControl(new mapboxgl.NavigationControl());//controles del map
map3.addControl(new mapboxgl.FullscreenControl());

const marker3 = new mapboxgl.Marker({//punto de inicio en el mapa
    color: "#FF0000",
    draggable: true
    }).setLngLat([-98.20868494860592, 19.040296987811555])
    .addTo(map3);
// Store the marker's longitude and latitude coordinates in a variable
const lngLat3 = marker3.getLngLat();
async function onDragEndD () {
    const lngLat3 = marker3.getLngLat();
    ubicacionDetenido['coordenadaX'].value=lngLat3.lng
    ubicacionDetenido['coordenadaY'].value=lngLat3.lat
    direccion = await getDireccionD(lngLat3)
    ubicacionDetenido['codigo_postal'].value="";
    ubicacionDetenido['Exterior'].value="";
    mun_="";esta_="";codi_="";calle_="";nume_="";coloni_="";esta_2="";
    for(i=0;i<(direccion.features).length;i++){
        if((direccion.features[i].id).includes("place"))
        mun_=direccion.features[i].place_name
        if((direccion.features[i].id).includes("region"))
            esta_=direccion.features[i].place_name
        if((direccion.features[i].id).includes("postcode"))
            codi_=direccion.features[i].place_name
        if((direccion.features[i].id).includes("address")){
            calle_=direccion.features[i].text
            nume_=direccion.features[i].address
        }
        if((direccion.features[i].id).includes("locality"))
            coloni_=direccion.features[i].text
    }
    ubicacionDetenido['Municipio'].value=(mun_.split(","))[0]
    ubicacionDetenido['Estado'].value=(esta_.split(","))[0]
    if(esta_=="")
        ubicacionDetenido['Estado'].value=(codi_.split(","))[0]
    if(codi_!=""){
        ubicacionDetenido['codigo_postal'].value=((codi_.split(","))[((codi_.split(","))).length-2]).trim()
    }
    if(nume_!=undefined){
        ubicacionDetenido['Exterior'].value=nume_
    }
    if(ubicacionDetenido['Estado'].value=="Mexico City")
        ubicacionDetenido['Estado'].value="CIUDAD DE MEXICO"
    if(ubicacionDetenido['Municipio'].value=="Mexico City")
        ubicacionDetenido['Municipio'].value="CIUDAD DE MEXICO"
}    
const getDireccionD = async (lngLat) => {
    try {
        const response = await fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${lngLat.lng}, ${lngLat.lat}.json?access_token=${mapboxgl.accessToken}`, {
            method: 'GET',
            mode: 'cors', // <---
            cache: 'default'          
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}

const getLngLatD = async () => {//FUNCION PARA LA BUSQUEDA DE UNA UBICACION POR DIRECCION
    calle = document.getElementById('Calle_Det').value;
    numero = document.getElementById('no_Ext_Det').value;
    codigopostal = document.getElementById('CP_Det').value;
    var FV = new FormValidator();
    if(calle=="" || codigopostal==""){
        error_calle_1d.textContent = FV.validate(document.getElementById('Calle_Det').value, "required | max_length[50]");
        error_codigopd.textContent = FV.validate(document.getElementById('CP_Det').value, "required |max_length[50]");
    }
    else{
        error_calle_1d.textContent=""
        error_codigopd.textContent=""
        cadena = '';
        calle = calle.split(' ');
        
        for(call of calle){
            cadena+=`${call},`
        }
        cadena+=`${numero},`
        cadena+=`${codigopostal}`
        try {
            const response = await fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${cadena}.json?access_token=${mapboxgl.accessToken}&limit=1`, {
                method: 'GET',
                mode: 'cors', // <---
                cache: 'default'          
            });
            const data = await response.json();
            ubicacionDetenido['coordenadaX'].value=data.features[0].geometry.coordinates[0]
            ubicacionDetenido['coordenadaY'].value=data.features[0].geometry.coordinates[1]
            map3.flyTo({
                center: [
                data.features[0].geometry.coordinates[0],
                data.features[0].geometry.coordinates[1]
                ],
                zoom: 18,
                essential: true
                });
                marker3.setLngLat([data.features[0].geometry.coordinates[0],data.features[0].geometry.coordinates[1]])
            return data;
        } catch (error) {
            console.log(error);
        }
    }
}
marker3.on('dragend', onDragEndD);
document.getElementById('buscar_coordenadas_Det').addEventListener('click',getLngLatD)//BUSCA LAS COORDENADAS ADEMAS POSICIONA EL CURSOR CON LA CALLE Y CP
document.getElementById('buscar_direccion_Det').addEventListener('click',getColoniasCallesD);//BUSQUEDA EL NUMERO EXTERIOR Y CP ADEMAS POSICIONA EL CURSOR CON LA COORDENADAS X,Y
const inputColoniaUbi = document.getElementById('Colonia_Det');
//const myFormDataM =  new FormData();
var colonia_actual;
inputColoniaUbi.addEventListener('input', () => {//FUNCION AUTOCOMPLETE DE LA COLONIA
    myFormDataM.append('termino', inputColoniaUbi.value)
    fetch(base_url_js + 'Catalogos/getColonia', {
            method: 'POST',
            body: myFormDataM
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `${r.Tipo} ${r.Colonia}`, value: `${r.Colonia}`, tipo: r.Tipo }))
        autocomplete({
            input: Colonia_Det,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {
                Colonia_Det.value = item.label;
                colonia_actual=item.value;
                buscarCodigoPostal()
            }
        }); 
    })
    .catch(err => console.log(`Ha ocurrido un error al obtener las colonias.\nCódigo de error: ${ err }`))
});
function buscarCodigoPostal(){//BUSCA EL CODIGO POSTAL DE LA DIRECCION INGRESADO
    const myFormData_cp =  new FormData();
    myFormData_cp.append('cp', colonia_actual)
    fetch(base_url_js + 'Catalogos/getCP', {
        method: 'POST',
        body: myFormData_cp
    })
    .then(res => res.json())
    .then(data => {
        if(data.length>0){
            ubicacionDetenido['codigo_postal'].value=data[0]['Codigo_postal']
        }
    })
    .catch(err => console.log(`Ha ocurrido un error al obtener el codigo postal.\nCódigo de error: ${ err }`))
}

const inputCalleUbi = document.getElementById('Calle_Det');
//const myFormData_calle =  new FormData();
inputCalleUbi.addEventListener('input', () => {//FUNCION AUTOCOMPLETE DE LA CALLE
    myFormData_calle.append('termino', inputCalleUbi.value)
    fetch(base_url_js + 'Catalogos/getCalles', {
            method: 'POST',
            body: myFormData_calle
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `${r.Calle}`, value: `${r.Calle}` }))
        autocomplete({
            input: Calle_Det,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {
                Calle_Det.value = item.label;
            }
        }); 
    })
    .catch(err => alert(`Ha ocurrido un error al obtener las colonias.\nCódigo de error: ${ err }`))
});

const inputCalleUbi2 = document.getElementById('Calle_Det2');
//const myFormData_calle2 =  new FormData();
inputCalleUbi2.addEventListener('input', () => {//FUNCION AUTOCOMPLETE DE LA CALLE 2
    myFormData_calle2.append('termino', inputCalleUbi2.value)
    console.log(inputCalleUbi2.value)
    fetch(base_url_js + 'Catalogos/getCalles', {
            method: 'POST',
            body: myFormData_calle2
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `${r.Calle}`, value: `${r.Calle}` }))
        autocomplete({
            input: Calle_Det2,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {
                Calle_Det2.value = item.label;
            }
        }); 
    })
    .catch(err => alert(`Ha ocurrido un error al obtener las colonias.\nCódigo de error: ${ err }`))
});
function seleccionar_busquedaD(e){//FUNCION QUE REALIZA LA BUSQUEDA POR DIRECCION O COORDENADAS
    if(document.getElementById('porDireccion_Det').checked){
        cordX_principales_error.textContent="";
        cordY_principales_error.textContent="";
        document.getElementById('buscar_coordenadas_Det').style.display = "block";
        document.getElementById('buscar_direccion_Det').style.display = "none";
    }
    else{
        error_codigopd.innerText = "";   
        error_calle_1d.innerText = "";
        document.getElementById('buscar_coordenadas_Det').style.display = "none";
        document.getElementById('buscar_direccion_Det').style.display = "block";
    }
}

   // Agregar capas de tipo 'fill' a los mapas
   addFillLayerToMap(map3);
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

document.getElementById('puebla_ubicacion').addEventListener('change',()=>{
    showUbicacionForanea();
});
document.getElementById('foraneo_ubicacion').addEventListener('change',()=>{
    showUbicacionForanea();
});
const showUbicacionForanea = () =>{//Funcion de habilitado para la edicion del estado
    radio = document.getElementsByName('ubicacion_puebla')
    if(radio[0].checked){
        document.getElementById('Estado').setAttribute('disabled', '');
        document.getElementById('Estado').value='PUEBLA';
        document.getElementById('Municipio').value='PUEBLA';
        document.getElementById('Municipio').setAttribute('disabled', '');
        document.getElementById('Es_Foraneo').classList.add('mi_hide');
        document.getElementById('Estado_error').innerHTML='';
    }else if(radio[1].checked){
        document.getElementById('Estado').removeAttribute("disabled");
        document.getElementById('Estado').value='SD';
        document.getElementById('Municipio').value='';
        document.getElementById('Municipio').removeAttribute("disabled");
        document.getElementById('Es_Foraneo').classList.remove('mi_hide');
        document.getElementById('Estado_error').innerHTML='';
    }
}

function changeDetencion(){
    let radioHabilitado = document.getElementsByName('Detencion');
    if(radioHabilitado[0].checked){//si tiene involucrados
        document.getElementById('div_detencion').classList.remove('mi_hide');
    }else if(radioHabilitado[1].checked){//no tiene involucrados
        document.getElementById('div_detencion').classList.add('mi_hide');
    }
    
}
document.getElementById("Elementos_Realizan_D").addEventListener("input", function(event) {/// Para el pegado
    var valor = event.target.value;
    var nuevoValor = "";
    for (var i = 0; i < valor.length; i++) {
        var char = valor[i];       
        if (/[a-zA-Z,áéíóúÁÉÍÓÚÑñ\s]/.test(char)) {
            nuevoValor += char;
        }
    }
    event.target.value = nuevoValor;
});
document.getElementById("Nombres_Detenidos").addEventListener("input", function(event) {/// Para el pegado
    var valor = event.target.value;
    var nuevoValor = "";
    for (var i = 0; i < valor.length; i++) {
        var char = valor[i];       
        if (/[a-zA-Z,áéíóúÁÉÍÓÚÑñ\s]/.test(char)) {
            nuevoValor += char;
        }
    }
    event.target.value = nuevoValor;
});