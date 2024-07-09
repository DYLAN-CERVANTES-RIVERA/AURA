const fecha = new Date();
const hoy_map_box = fecha.getDate();
const API_MAPBOX_PAR='pk.eyJ1Ijoic3NjZGlwYyIsImEiOiJjbHllczRpNDkwNW9xMnFwbW53MTBxY2ZkIn0.HBTJqZOzZoCh8IzNIFcY4A';
const API_MAPBOX_IMPAR='pk.eyJ1Ijoic3NjZGlwYyIsImEiOiJjbHllczRpNDkwNW9xMnFwbW53MTBxY2ZkIn0.HBTJqZOzZoCh8IzNIFcY4A';
var API_MAPBOX="";
if(hoy_map_box%2===0){
    var API_MAPBOX=API_MAPBOX_PAR;
}
else{
    var API_MAPBOX=API_MAPBOX_IMPAR;
}