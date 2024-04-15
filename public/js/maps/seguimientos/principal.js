const principales = {
    'porDireccion'      : document.getElementById('por_direccion'),
    'porCoordenada'     : document.getElementById('por_coordenadas'),
    'searchDireccion'   : document.getElementById('dir'),
    'searchCoordenadaY' : document.getElementById('search_cy'),
    'searchCoordenadaX' : document.getElementById('search_cx'),
    'buscar'            : document.getElementById('buscar'),
    'colonia'           : document.getElementById('spdomicilio_colonia'),
    'calle'             : document.getElementById('spdomicilio_calle'),
    'noInterior'        : document.getElementById('spdomicilio_numint'),
    'noExterior'        : document.getElementById('spdomicilio_numext'),
    'cp'                : document.getElementById('spdomicilio_cp'),
    'errorMap'          : document.getElementById('errorMap')
}

principales['porDireccion'].style.display  = 'none';
principales['porCoordenada'].style.display = 'none';
document.getElementById('select-coordenadas').style.display = 'none';

const offlineMapsSeguimientos = ()=>{

    document.getElementById("map").innerHTML =`
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
    offlineMapsSeguimientos();
});

window.addEventListener('online', function(e) {
    initMap();
});

const radios = document.getElementsByName('busqueda');

let markers = [],
    type = 0;

function initMap(){

    const option = {
        zoom: 10,
        center: {
            lat: 19.0437227,
            lng: -98.1984744
        }
    }

    if(typeof google === "undefined"){
        document.getElementById("map").innerHTML =`
            <div class="d-flex align-items-center" style="height:100%">
                <div>
                    <span class="badge badge-pill badge-success">Volvio la conexión a internet</span>
                    <h2 class="my-4" style="color:#88072D">¡CON INTERNET!</h2>
                    <p>Para volver a visualizar los mapas se requiere de actualizar la página.</p>
                    <p class="text-danger">NOTA: recuerda guardar tus cambios antes de actualizar la página.</p>
                </div>
            </div>
        `;
    }

    const primer_map = new google.maps.Map(document.getElementById("map"), option);

    const geocoder = new google.maps.Geocoder();

    for(let radio in radios){
        radios[radio].onclick = (e)=>{
		let select,data_id;
		if(e.path===undefined)
		{
			 select = e.originalTarget;
			 data_id = select.getAttribute('data-id');
		}

		else
		{
            		select = e.path[0];
            		data_id = select.getAttribute('data-id');
		}
	    let apartado;
            let map;
            switch(data_id){
                case 'principales':
                    apartado = principales;
                    map = primer_map
                break;
                case 'peticionario':
                    apartado = peticionario;
                    map = segundo_map;
                break;
                case 'hechos':
                    apartado = hechos;
                    map = tercer_map
                break;
                case 'detencion':
                    apartado = detencion;
                    map = cuarto_map;
                break;
            }
            if (select.value == 0) {
                apartado['porDireccion'].style.display = 'block';
                apartado['porCoordenada'].style.display = 'none';

                clear_fields(apartado);
                clean_marks();
                markers = [];

                placesMap(map, apartado, geocoder)

            } else {

                apartado['porDireccion'].style.display = 'none';
                apartado['porCoordenada'].style.display = 'block';

                clear_fields(apartado);
                clean_marks();
                markers = [];
                type = 1;

                apartado['buscar'].addEventListener('click',(e)=>{
                    e.preventDefault();
                    if (apartado['searchCoordenadaY'].value != '' && apartado['searchCoordenadaX'].value != '') {
                        geocodeLatLng(geocoder, map, apartado['searchCoordenadaY'].value, apartado['searchCoordenadaX'].value, apartado)
                    } else {
                        alert("Por favor ingresa unas coordenadas validas")
                    }
                })

            }
        }
    }
}

function placesMap(map, apartado, geocoder){

    const direccion = apartado['searchDireccion'],
        searchValue = new google.maps.places.SearchBox(direccion);

    map.addListener('bounds_changed', function() { 
        searchValue.setBounds(map.getBounds());
    });

    searchValue.addListener('places_changed', function() { 
        const places = searchValue.getPlaces();

        if (places.length === 0) {
            return;
        }

        const bounds = new google.maps.LatLngBounds();

        places.forEach(function(p) { 
            if (!p.geometry) {
                return;
            }

            clean_marks();
            markers = [];

            markers.push(new google.maps.Marker({ 
                map: map,
                position: p.geometry.location,
                draggable: true
            }));

            for(let i=0;i<markers.length;i++){
                markers[i].addListener('dragend', function(event) { 
    
                    /* apartado['coordenadaY'].value = this.getPosition().lat(); 
                    apartado['coordenadaX'].value = this.getPosition().lng(); */
    
                    const lati = this.getPosition().lat();
                    const lon = this.getPosition().lng();
    
                    geocodeLatLng(geocoder, map, lati, lon, apartado);

                });
            }
            
            
            if (p.geometry.viewport) {
                bounds.union(p.geometry.viewport);
            } else {
                bounds.extend(p.geometry.location);
            }

            getAdress(p,apartado);

        });
        map.fitBounds(bounds);
    });
}

function getAdress(e,apartado) {
    for (let i = 0; i < e.address_components.length; i++) {
        if (e.address_components[i]['types'] == 'street_number')
            apartado['noInterior'].value = e.address_components[i]['short_name']

        if (e.address_components[i]['types'] == 'route')
            apartado['calle'].value = e.address_components[i]['short_name']

        if (e.address_components[i]['types'][0] == 'sublocality_level_1' || e.address_components[i]['types'][0] == 'political')
            apartado['colonia'].value = e.address_components[i]['short_name']

        if (e.address_components[i]['types'][0] == 'locality' && apartado['municipio'] != undefined)
            apartado['municipio'].value = e.address_components[i]['short_name']

        if (e.address_components[i]['types'][0] == 'administrative_area_level_1' && apartado['estado'] != undefined)
            apartado['estado'].value = e.address_components[i]['long_name']

        if (e.address_components[i]['types'][0] == 'postal_code')
            apartado['cp'].value = e.address_components[i]['long_name']
    }
    
    /* apartado['coordenadaX'].value = Object.values(e.geometry.viewport)[1].i;
    apartado['coordenadaY'].value = Object.values(e.geometry.viewport)[0].i; */

    if (type == 0)
        apartado['searchDireccion'].value = e.formatted_address
}

function geocodeLatLng(geocoder, map, latitud, longitud, apartado) {

    const lat = latitud;
    const lng = longitud;

    const latlng = {
        lat: parseFloat(lat),
        lng: parseFloat(lng)
    };

    geocoder.geocode({ location: latlng }, (results, status) => {
        if (status === "OK") {
            if (results[0]) {
                switch (type) {
                    case 0:
                        getAdress(results[0], apartado)
                    break;
                    case 1:
                        clean_marks();
                        markers = [];

                        markers.push(new google.maps.Marker({
                            map: map,
                            position: latlng,
                            draggable: true
                        }));

                        for(let i=0;i<markers.length;i++){
                            markers[i].addListener('dragend', function(event) {
                                apartado['coordenadaY'].value = this.getPosition().lat();
                                apartado['coordenadaX'].value = this.getPosition().lng();
                                
                                apartado['searchCoordenadaY'].value = this.getPosition().lat();
                                apartado['searchCoordenadaX'].value = this.getPosition().lng();
                                
                                var lati1 = this.getPosition().lat();
                                var lon1 = this.getPosition().lng();
                                
                                geocodeLatLng(geocoder, map, lati1, lon1, apartado)
                                
                            });
                        }

                        const bounds = new google.maps.LatLngBounds();

                        if (results[0].geometry.viewport) {
                            bounds.union(results[0].geometry.viewport);
                        } else {
                            bounds.extend(results[0].geometry.location);
                        }

                        getAdress(results[0], apartado);
                        map.fitBounds(bounds);
                    break;
                }
            } else {
                apartado['errorMap'].innerHTML = '<span class="text-danger">Resultados no encontrados</span>'
            }
        } else {
            apartado['errorMap'].innerHTML = '<span class="text-danger" style="font-size: 11px">Solicitud invalida</span>'
        }
    });
}

function clear_fields(input) {
    for (var nombre in input) {
        if (input[nombre] != '')
            input[nombre].value = ''
    }
}

function clean_marks() {
    markers.forEach(function(m) { m.setMap(null); });
}
