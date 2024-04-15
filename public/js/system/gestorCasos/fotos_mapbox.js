/*----------------ESTE ARCHIVO DE JAVASCRIPT ES PARA LA FUNCIONALIDADES DEL MAPA DE LA TAB FOTOS-------------*/
var error_codigopf = document.getElementById("CP_fotos_error");
var error_calle_1f = document.getElementById("Calle_fotos_error");
document.getElementById('porDireccion_fotos').addEventListener('change',seleccionar_busqueda);
document.getElementById('porCoordenadas_fotos').addEventListener('change',seleccionar_busqueda);
/*----------------------------------------------Funcionalidades del mapa de la tab de fotos------------------------------------------ */
var ubicacionEventoFotos = {
    'porDireccion': document.getElementById('por_direccion_fotos'),
    'porCoordenada': document.getElementById('por_coordenadas_fotos'),
    'Colonia': document.getElementById('ColoniaF'),
    'Calle': document.getElementById('CalleF'),
    'Exterior': document.getElementById('no_ExtF'),
    'coordenadaX': document.getElementById('cordXF'),
    'coordenadaY': document.getElementById('cordYF'),
    'codigo_postal': document.getElementById('CPF'),
    'Estado': '',
    'Municipio': ''
}
const offlineMapsPrincipalI2 = () => {

    document.getElementById("map_mapbox2").innerHTML = `
        <div class="d-flex align-items-center" style="height:100%">
            <div>
                <span class="badge badge-pill badge-warning">Sin conexión a internet</span>
                <h2 class="my-4" style="color:#88072D">¡OH NO! ERROR 404</h2>
                <p>Lo sentimos, al parecer no tienes conexión a internet ó la señal es muy débil.</p>
            </div>
        </div>
    `;
}
window.addEventListener('offline', function(e) {
    offlineMapsPrincipalI2();
});
mapboxgl.accessToken = API_MAPBOX;
const getColoniasCalles2 = async(e) => {//FUNCION PARA LA BUSQUEDA DE UNA UBICACION POR COORDENADAS
    ubicacionEventoFotos['Calle'].value="";
    ubicacionEventoFotos['Exterior'].value="";
    ubicacionEventoFotos['codigo_postal'].value="";
    coord_x = ubicacionEventoFotos['coordenadaX'].value;
    coord_y =  ubicacionEventoFotos['coordenadaY'].value;
    cordX_principales_error = document.getElementById("cordX_fotos_error");
    cordY_principales_error = document.getElementById("cordY_fotos_error");
    (document.getElementById("CP_fotos_error")).textContent="";
    (document.getElementById("Calle_fotos_error")).textContent="";
    var FV = new FormValidator();
    if(coord_x=="" || coord_y==""){
        cordX_principales_error.textContent = FV.validate(coord_x, "required | max_length[50]");
        cordY_principales_error.textContent = FV.validate(coord_y, "required |max_length[50]");
    }
    else{
        cordX_principales_error.textContent="";
        cordY_principales_error.textContent="";
        
        map2.flyTo({
            center: [
            ubicacionEventoFotos['coordenadaX'].value,
            ubicacionEventoFotos['coordenadaY'].value
            ],
            zoom: 18,
            essential: true
            });
            marker2.setLngLat([ubicacionEventoFotos['coordenadaX'].value,ubicacionEventoFotos['coordenadaY'].value])
        var lngLat = {
            lng : coord_x,
            lat : coord_y,
        }
        direccion = await getDireccionFotos(lngLat)
        console.log(direccion)
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
        ubicacionEventoFotos['Municipio'].value=(mun_.split(","))[0]
        ubicacionEventoFotos['Estado'].value=(esta_.split(","))[0]
        if(esta_=="")
            ubicacionEventoFotos['Estado'].value=(codi_.split(","))[0]
       
        if(codi_!=undefined){
            ubicacionEventoFotos['codigo_postal'].value=(codi_.split(","))[((codi_.split(","))).length-2]
        }
        
        if(nume_!=undefined){
            ubicacionEventoFotos['Exterior'].value=nume_
        }

    }
}
var map2 = new mapboxgl.Map({
    container: 'map_mapbox2',
    style: 'mapbox://styles/mapbox/streets-v11',
    center: [-98.20868494860592, 19.040296987811555], // starting position
    zoom: 11 // starting zoom

});//INICIALIZACION DEL MAPA 
const marker2 = new mapboxgl.Marker({//punto de inicio en el mapa
    color: "#FF0000",
    draggable: true
    }).setLngLat([-98.20868494860592, 19.040296987811555])
    .addTo(map2);
map2.addControl(new mapboxgl.NavigationControl());//controles del map
map2.addControl(new mapboxgl.FullscreenControl());

// Store the marker's longitude and latitude coordinates in a variable
const lngLat2 = marker2.getLngLat();///getlnglat es una funcion propia de marker
async function onDragEnd2 () {
    const lngLat2 = marker2.getLngLat();
    ubicacionEventoFotos['coordenadaX'].value=lngLat2.lng
    ubicacionEventoFotos['coordenadaY'].value=lngLat2.lat
    direccion = await getDireccionFotos(lngLat2)
    console.log(direccion);
    ubicacionEventoFotos['codigo_postal'].value="";
    ubicacionEventoFotos['Exterior'].value="";
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
    ubicacionEventoFotos['Municipio'].value=(mun_.split(","))[0]
    ubicacionEventoFotos['Estado'].value=(esta_.split(","))[0]
    if(esta_=="")
        ubicacionEventoFotos['Estado'].value=(codi_.split(","))[0]
    if(codi_!=undefined && codi_!=""){
        ubicacionEventoFotos['codigo_postal'].value=((codi_.split(","))[((codi_.split(","))).length-2]).trim()
    }
    if(nume_!=undefined){
        ubicacionEventoFotos['Exterior'].value=nume_
    }
    if(ubicacionEventoFotos['Estado'].value=="Mexico City")
        ubicacionEventoFotos['Estado'].value="CIUDAD DE MEXICO"
    if(ubicacionEventoFotos['Municipio'].value=="Mexico City")
        ubicacionEventoFotos['Municipio'].value="CIUDAD DE MEXICO"
}
const getDireccionFotos = async (lngLat) => {
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
    
const getLngLat2 = async () => {//FUNCION PARA LA BUSQUEDA DE UNA UBICACION POR DIRECCION
    calle = document.getElementById('CalleF').value;
    numero = document.getElementById('no_ExtF').value;
    codigopostal = document.getElementById('CPF').value;
    var FV = new FormValidator();
    if(calle=="" || codigopostal==""){
        error_calle_1f.textContent = FV.validate(document.getElementById('CalleF').value, "required | max_length[50]");
        error_codigopf.textContent = FV.validate(document.getElementById('CPF').value, "required |max_length[50]");
    }
    else{
        error_calle_1f.textContent=""
        error_codigopf.textContent=""
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
            ubicacionEventoFotos['coordenadaX'].value=data.features[0].geometry.coordinates[0]
            ubicacionEventoFotos['coordenadaY'].value=data.features[0].geometry.coordinates[1]
            map2.flyTo({
                center: [
                data.features[0].geometry.coordinates[0],
                data.features[0].geometry.coordinates[1]
                ],
                zoom: 18,
                essential: true
                });
                marker2.setLngLat([data.features[0].geometry.coordinates[0],data.features[0].geometry.coordinates[1]])
            return data;
        } catch (error) {
            console.log(error);
        }
    }
}
marker2.on('dragend', onDragEnd2);
document.getElementById('buscar_coordenadas_fotos').addEventListener('click',getLngLat2)//BUSCA LAS COORDENADAS ADEMAS POSICIONA EL CURSOR CON LA CALLE Y CP
document.getElementById('buscar_direccion_fotos').addEventListener('click',getColoniasCalles2);//BUSQUEDA EL NUMERO EXTERIOR Y CP ADEMAS POSICIONA EL CURSOR CON LA COORDENADAS X,Y
const inputColonia2 = document.getElementById('ColoniaF');
const myFormDataM2 =  new FormData();
var colonia_actual;
inputColonia2.addEventListener('input', () => {//FUNCION AUTOCOMPLETE DE LA COLONIA
    myFormDataM2.append('termino', inputColonia2.value)
    fetch(base_url_js + 'Catalogos/getColonia', {
            method: 'POST',
            body: myFormDataM2
    })
    .then(res => res.json())
    .then(data => {
        const arr2 = data.map( r => ({ label: `${r.Tipo} ${r.Colonia}`, value: `${r.Colonia}`, tipo: r.Tipo }))
        autocomplete({
            input: ColoniaF,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions1 = arr2.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions1);
            },
            onSelect: function(item) {
                ColoniaF.value = item.label;
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
            ubicacionEventoFotos['codigo_postal'].value=data[0]['Codigo_postal']
        }
    })
    .catch(err => console.log(`Ha ocurrido un error al obtener el codigo postal.\nCódigo de error: ${ err }`))
}

const inputCalle21 = document.getElementById('CalleF');
const myFormData_calle21 =  new FormData();
inputCalle21.addEventListener('input', () => {//FUNCION AUTOCOMPLETE DE LA CALLE
    myFormData_calle21.append('termino', inputCalle21.value)
    fetch(base_url_js + 'Catalogos/getCalles', {
            method: 'POST',
            body: myFormData_calle21
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `${r.Calle}`, value: `${r.Calle}` }))
        autocomplete({
            input: CalleF,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {
                CalleF.value = item.label;
            }
        }); 
    })
    .catch(err => alert(`Ha ocurrido un error al obtener las colonias.\nCódigo de error: ${ err }`))
});

const inputCalle22 = document.getElementById('Calle2F');
const myFormData_calle22 =  new FormData();
inputCalle22.addEventListener('input', () => {//FUNCION AUTOCOMPLETE DE LA CALLE 2
    myFormData_calle22.append('termino', inputCalle22.value)
    fetch(base_url_js + 'Catalogos/getCalles', {
            method: 'POST',
            body: myFormData_calle22
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `${r.Calle}`, value: `${r.Calle}` }))
        autocomplete({
            input: Calle2F,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {
                Calle2F.value = item.label;
            }
        }); 
    })
    .catch(err => alert(`Ha ocurrido un error al obtener las colonias.\nCódigo de error: ${ err }`))
});
function seleccionar_busqueda(e){//FUNCION QUE REALIZA LA BUSQUEDA POR DIRECCION O COORDENADAS
    if(document.getElementById('porDireccion_fotos').checked){
        cordX_principales_error.textContent="";
        cordY_principales_error.textContent="";
        document.getElementById('buscar_direccion_fotos').classList.add('mi_hide')
        document.getElementById('buscar_coordenadas_fotos').classList.remove('mi_hide')
    }
    else{ 
        error_codigopf.innerText = "";
        error_calle_1f.innerText = "";
        document.getElementById('buscar_coordenadas_fotos').classList.add('mi_hide')
        document.getElementById('buscar_direccion_fotos').classList.remove('mi_hide')
    }
}
   // Agregar capas de tipo 'fill' a los mapas
   addFillLayerToMap(map2);