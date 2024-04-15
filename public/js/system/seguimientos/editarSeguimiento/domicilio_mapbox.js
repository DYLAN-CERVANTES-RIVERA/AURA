/*----------------ESTE ARCHIVO DE JAVASCRIPT ES PARA LA FUNCIONALIDADES DEL MAPA DE LA TAB PRINCIPAL-------------*/
var error_codigop = document.getElementById("CP_principales_error");
var error_calle_1 = document.getElementById("Calle_principales_error");
document.getElementById('porDireccion_alertas').addEventListener('change',seleccionar_busqueda);
document.getElementById('porCoordenadas_alertas').addEventListener('change',seleccionar_busqueda);
/*----------------------------------------------Funcionalidades del mapa de la tab principal------------------------------------------ */
var ubicacionEventoPrincipal = {
    'porDireccion': document.getElementById('por_direccion_ins'),
    'porCoordenada': document.getElementById('por_coordenadas_ins'),
    'Colonia': document.getElementById('Colonia'),
    'Calle': document.getElementById('Calle'),
    'Exterior': document.getElementById('no_Ext'),
    'coordenadaX': document.getElementById('cordX'),
    'coordenadaY': document.getElementById('cordY'),
    'codigo_postal': document.getElementById('CP'),
    'Estado': '',
    'Municipio': ''
}
const offlineMapsPrincipalI =async () => {

    document.getElementById("map_mapbox").innerHTML = `
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
    await offlineMapsPrincipalI();
});
mapboxgl.accessToken = API_MAPBOX;
const getColoniasCalles = async(e) => {//FUNCION PARA LA BUSQUEDA DE UNA UBICACION POR COORDENADAS
    ubicacionEventoPrincipal['Calle'].value="";
    ubicacionEventoPrincipal['Exterior'].value="";
    ubicacionEventoPrincipal['codigo_postal'].value="";
    coord_x = ubicacionEventoPrincipal['coordenadaX'].value;
    coord_y =  ubicacionEventoPrincipal['coordenadaY'].value;
    cordX_principales_error = document.getElementById("cordX_principales_error");
    cordY_principales_error = document.getElementById("cordY_principales_error");
    (document.getElementById("CP_principales_error")).textContent="";
    (document.getElementById("Calle_principales_error")).textContent="";
    var FV = new FormValidator();
    if(coord_x=="" || coord_y==""){
        cordX_principales_error.textContent = FV.validate(coord_x, "required | max_length[50]");
        cordY_principales_error.textContent = FV.validate(coord_y, "required |max_length[50]");
    }
    else{
        cordX_principales_error.textContent="";
        cordY_principales_error.textContent="";
        map.flyTo({
        center: [
            coord_x,
            coord_y
        ],
        zoom: 18,
        essential: true
        });
        marker.setLngLat([coord_x,coord_y])
        var lngLat = {
            lng : coord_x,
            lat : coord_y,
        }
        direccion = await getDireccion(lngLat)
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
        ubicacionEventoPrincipal['Municipio'].value=(mun_.split(","))[0]
        ubicacionEventoPrincipal['Estado'].value=(esta_.split(","))[0]
        if(esta_=="")
            ubicacionEventoPrincipal['Estado'].value=(codi_.split(","))[0]
        
        if(codi_!=undefined){
            ubicacionEventoPrincipal['codigo_postal'].value=(codi_.split(","))[((codi_.split(","))).length-2]
        }
        
        if(nume_!=undefined){
            ubicacionEventoPrincipal['Exterior'].value=nume_
        }
        

    }
}

var map = new mapboxgl.Map({
    container: 'map_mapbox',
    style: 'mapbox://styles/mapbox/streets-v11',
    center: [-98.20868494860592, 19.040296987811555], // starting position
    zoom: 11 // starting zoom

});//INICIALIZACION DEL MAPA 

map.addControl(new mapboxgl.NavigationControl());//controles del map
map.addControl(new mapboxgl.FullscreenControl());

const marker = new mapboxgl.Marker({//punto de inicio en el mapa
    color: "#FF0000",
    draggable: true
    }).setLngLat([-98.20868494860592, 19.040296987811555])
    .addTo(map);
// Store the marker's longitude and latitude coordinates in a variable
const lngLat = marker.getLngLat();
async function onDragEnd () {
    const lngLat = marker.getLngLat();
    ubicacionEventoPrincipal['coordenadaX'].value=lngLat.lng
    ubicacionEventoPrincipal['coordenadaY'].value=lngLat.lat
    direccion = await getDireccion(lngLat)
    ubicacionEventoPrincipal['codigo_postal'].value="";
    ubicacionEventoPrincipal['Exterior'].value="";
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
    ubicacionEventoPrincipal['Municipio'].value=(mun_.split(","))[0]
    ubicacionEventoPrincipal['Estado'].value=(esta_.split(","))[0]
    if(esta_=="")
        ubicacionEventoPrincipal['Estado'].value=(codi_.split(","))[0]
    if(codi_!=""){
        ubicacionEventoPrincipal['codigo_postal'].value=((codi_.split(","))[((codi_.split(","))).length-2]).trim()
    }
    if(nume_!=undefined){
        ubicacionEventoPrincipal['Exterior'].value=nume_
    }
    if(ubicacionEventoPrincipal['Estado'].value=="Mexico City")
        ubicacionEventoPrincipal['Estado'].value="CIUDAD DE MEXICO"
    if(ubicacionEventoPrincipal['Municipio'].value=="Mexico City")
        ubicacionEventoPrincipal['Municipio'].value="CIUDAD DE MEXICO"
}    
const getDireccion = async (lngLat) => {
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

const getLngLat = async () => {//FUNCION PARA LA BUSQUEDA DE UNA UBICACION POR DIRECCION
    calle = document.getElementById('Calle').value;
    numero = document.getElementById('no_Ext').value;
    codigopostal = document.getElementById('CP').value;
    var FV = new FormValidator();
    if(calle=="" || codigopostal==""){
        error_calle_1.textContent = FV.validate(document.getElementById('Calle').value, "required | max_length[50]");
        error_codigop.textContent = FV.validate(document.getElementById('CP').value, "required |max_length[50]");
    }
    else{
        error_calle_1.textContent=""
        error_codigop.textContent=""
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
            ubicacionEventoPrincipal['coordenadaX'].value=data.features[0].geometry.coordinates[0]
            ubicacionEventoPrincipal['coordenadaY'].value=data.features[0].geometry.coordinates[1]
            map.flyTo({
                center: [
                data.features[0].geometry.coordinates[0],
                data.features[0].geometry.coordinates[1]
                ],
                zoom: 18,
                essential: true
                });
                marker.setLngLat([data.features[0].geometry.coordinates[0],data.features[0].geometry.coordinates[1]])
            return data;
        } catch (error) {
            console.log(error);
        }
    }
}
marker.on('dragend', onDragEnd);
document.getElementById('buscar_coordenadas_ins').addEventListener('click',getLngLat)//BUSCA LAS COORDENADAS ADEMAS POSICIONA EL CURSOR CON LA CALLE Y CP
document.getElementById('buscar_direccion_ins').addEventListener('click',getColoniasCalles);//BUSQUEDA EL NUMERO EXTERIOR Y CP ADEMAS POSICIONA EL CURSOR CON LA COORDENADAS X,Y
const inputColonia = document.getElementById('Colonia');
const myFormDataM =  new FormData();
var colonia_actual;
inputColonia.addEventListener('input', () => {//FUNCION AUTOCOMPLETE DE LA COLONIA
    myFormDataM.append('termino', inputColonia.value)
    fetch(base_url_js + 'Catalogos/getColonia', {
            method: 'POST',
            body: myFormDataM
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `${r.Tipo} ${r.Colonia}`, value: `${r.Colonia}`, tipo: r.Tipo }))
        autocomplete({
            input: Colonia,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {
                Colonia.value = item.label;
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
            ubicacionEventoPrincipal['codigo_postal'].value=data[0]['Codigo_postal']
        }
    })
    .catch(err => console.log(`Ha ocurrido un error al obtener el codigo postal.\nCódigo de error: ${ err }`))
}

const inputCalle = document.getElementById('Calle');
const myFormData_calle =  new FormData();
inputCalle.addEventListener('input', () => {//FUNCION AUTOCOMPLETE DE LA CALLE
    myFormData_calle.append('termino', inputCalle.value)
    fetch(base_url_js + 'Catalogos/getCalles', {
            method: 'POST',
            body: myFormData_calle
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `${r.Calle}`, value: `${r.Calle}` }))
        autocomplete({
            input: Calle,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {
                Calle.value = item.label;
            }
        }); 
    })
    .catch(err => alert(`Ha ocurrido un error al obtener las colonias.\nCódigo de error: ${ err }`))
});

const inputCalle2 = document.getElementById('Calle2');
const myFormData_calle2 =  new FormData();
inputCalle2.addEventListener('input', () => {//FUNCION AUTOCOMPLETE DE LA CALLE 2
    myFormData_calle2.append('termino', inputCalle2.value)
    fetch(base_url_js + 'Catalogos/getCalles', {
            method: 'POST',
            body: myFormData_calle2
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `${r.Calle}`, value: `${r.Calle}` }))
        autocomplete({
            input: Calle2,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {
                Calle2.value = item.label;
            }
        }); 
    })
    .catch(err => alert(`Ha ocurrido un error al obtener las colonias.\nCódigo de error: ${ err }`))
});
function seleccionar_busqueda(e){//FUNCION QUE REALIZA LA BUSQUEDA POR DIRECCION O COORDENADAS
    if(document.getElementById('porDireccion_alertas').checked){
        cordX_principales_error.textContent="";
        cordY_principales_error.textContent="";
        document.getElementById('buscar_coordenadas_ins').style.display = "block";
        document.getElementById('buscar_direccion_ins').style.display = "none";
    }
    else{
        error_codigop.innerText = "";   
        error_calle_1.innerText = "";
        document.getElementById('buscar_coordenadas_ins').style.display = "none";
        document.getElementById('buscar_direccion_ins').style.display = "block";
    }
}
function addFillLayerToMap(map) {
    map.on('load', function () {
    map.addSource('sourceVectores', {
    type: 'geojson',
    data: base_url_js + 'public/media/capas/195_VECTORES.geojson',
    });

    map.addLayer({
    id: 'vectores-layer',
    type: 'fill',
    source: 'sourceVectores',
    layout: {},
    paint: {
    "fill-color": "#00ffff",
    'fill-opacity': 0.1,
    },
    });

    });

    // When a click event occurs on a feature in the places layer, open a popup at the
    // location of the feature, with description HTML from its properties.
    map.on('click', 'vectores-layer', (e) => {
    // Copy coordinates array.
    const coordinates = e.features[0].geometry.coordinates.slice();
    const description = e.features[0].properties.Name + ' ' + e.features[0].properties.ZONA ;
    console.log('EVENTO : ', e)
    // Ensure that if the map is zoomed out such that multiple
    // copies of the feature are visible, the popup appears
    // over the copy being pointed to.


    new mapboxgl.Popup()
    .setLngLat(e.lngLat)
    .setHTML(description)
    .addTo(map);
    });

    // Change the cursor to a pointer when the mouse is over the places layer.
    map.on('mouseenter', 'places', () => {
    map.getCanvas().style.cursor = 'pointer';
    });

    // Change it back to a pointer when it leaves.
    map.on('mouseleave', 'places', () => {
    map.getCanvas().style.cursor = '';
    });
}
   // Agregar capas de tipo 'fill' a los mapas
   addFillLayerToMap(map);