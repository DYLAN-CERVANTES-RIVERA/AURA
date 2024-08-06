/*-----------------------------------------------ESTE ARCHIVO DE JAVASCRIPT REALIZA VALIDACIONES EN LOS DATOS DEL MAPA PRINCIPAL-------------------------------------------------------------------------*/
const validateColonia = async (colonia_buscar)=> {//ESTA FUNCION ES PARA MANEJAR LA VALIDACION DE TODAS LAS COLONIAS EN DONDE HALLA MAPAS EN EL GESTOR
    console.log(colonia_buscar);
    coloniaValida=""
    if(colonia_buscar.length > 0){
    
        coloniasCatalogo = await getAllColonias();
        let inputColoniaValue = createObjectColonia (colonia_buscar);
        const result = coloniasCatalogo.find( colonia => (colonia.Tipo == inputColoniaValue.Tipo && colonia.Colonia == inputColoniaValue.Colonia) ) // SI ESTO ME REGRESA EL MISMO OBJETO QUIERE DECIR QUE SI LO ENCONTRO 
        if (result){
            coloniaValida = true
        }
        if(coloniaValida==false)
            coloniaValida="Ingrese una colonia valida"
        else
            coloniaValida=""

    }
    return coloniaValida;
}
const getAllColonias = async () => {//OBTINE TODAS LAS COLONIAS EN EL CATALOGO
    try {
        const response = await fetch(base_url_js + 'Catalogos/getColonias', {//REALIZA UN FETCH PARA OBTENER TODAS LAS COLONIAS DEL CATALOGO
            method: 'POST'
        });
        const data = await response.json();
        return data;
        
    } catch (error) {
        console.log(error);
    }
} 
const createObjectColonia = (colonia) => {//SIRVE PARA SEPARAR LA CADENA QUE SE INGRESO EN DOS PARTES TIPO Y EL NOMBRE DE LA COLONIA PARA BUSCARLA EN EL CATALOGO
    separado = colonia.split(' ');
    objetoColonia = {
        Tipo: '',
        Colonia: ''
    }
    if(separado){
        objetoColonia.Tipo = separado[0];
        for(let i = 1; i<separado.length; i++){
            objetoColonia.Colonia += separado[i]+' ';
        }
    }
    objetoColonia.Colonia = objetoColonia.Colonia.trim();
    return objetoColonia
}
/*Esta funcion es para manejar la validacion de todas las calles en donde haya mapas en el gestor*/
const validateCalle = async (calle_buscar)=> {
    var calleValida = "";
    if(calle_buscar.length > 0){
    
        callesCatalogo = await getAllCalles();
        const result = callesCatalogo.find(element => element.Calle == calle_buscar);
        if (result){
            calleValida = true
        }
        if(calleValida==false)
            calleValida="Ingrese una calle valida"
        else
            calleValida=""
    
    }
    return calleValida;
}
const getAllCalles = async () => {//OBTINE TODAS LAS CALLES EN EL CATALOGO
    try {
        const response = await fetch(base_url_js + 'Catalogos/getAllCalles', {//REALIZA UN FETCH PARA OBTENER TODAS LAS CALLES DEL CATALOGO
            method: 'POST'
        });
        const data = await response.json();
        return data;
        
    } catch (error) {
        console.log(error);
    }
}
function valideMultiples(evt) {//FUNCION QUE VALIDA LA INSERCION DE DATOS VALIDOS EN EL FORMULARIO
        var code = (evt.which) ? evt.which : evt.keyCode;
        let codigos = [8,32,193,201,205,211,218,225,233,237,243,250,209,241]//TECLAS DELETE,ESPACIO Y LETRAS ACENTUADAS
        if ((code >= 65 && code <= 90)||(code >= 97 && code <= 122)||(code >= 48 && code <= 57)) { //TECLAS DE NUMEROS
            return true;
        } else { // OTRAS TECLAS.
            let codigo_valido = codigos.find(element=>element==code);
            if(codigo_valido){
                return true;
            }else{
                return false;
            }
        }
}
function valideKey(evt) {//FUNCION QUE VALIDA LA INSERCION DE SOLO NUMEROS 
    var code = (evt.which) ? evt.which : evt.keyCode;
    let codigos = [8]//TECLA DELETE
    if ((code >= 65 && code <= 90)||(code >= 97 && code <= 122)||(code >= 48 && code <= 57)) { //TECLAS DE NUMEROS
        return true;
    } else { // OTRAS TECLAS.
        let codigo_valido = codigos.find(element=>element==code);
        if(codigo_valido){
            return true;
        }else{
            return false;
        }
    }
}

function filtraCoordNegativa(event) {/// Para el pegado
    var valor = event.target.value;
    var nuevoValor = "";
    for (var i = 0; i < valor.length; i++) {
        var char = valor[i];       
        if (/[0-9-.]/.test(char)) {
            nuevoValor += char;
        }
    }
    event.target.value = nuevoValor;
}
function filtraCoordPositiva(event) {/// Para el pegado
    var valor = event.target.value;
    var nuevoValor = "";
    for (var i = 0; i < valor.length; i++) {
        var char = valor[i];       
        if (/[0-9.]/.test(char)) {
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
function filtrarSoloNumeros(event) {
    var valor = event.target.value;
    var nuevoValor = "";
    for (var i = 0; i < valor.length; i++) {
        var char = valor[i];       
        if (/[0-9]/.test(char)) {
            nuevoValor += char;
        }
    }
    event.target.value = nuevoValor;
}


//Filtrado de parametros de entrada
document.getElementById("911_principales").addEventListener("input", filtrarAlfaNumericos);
document.getElementById("Colonia").addEventListener("input", filtrarAlfaNumericos);
document.getElementById("Calle").addEventListener("input", filtrarAlfaNumericos);
document.getElementById("Calle2").addEventListener("input", filtrarAlfaNumericos);

document.getElementById('CP').addEventListener("input", filtrarSoloNumeros);
document.getElementById('no_Ext').addEventListener("input", filtrarAlfaNumericos);

document.getElementById("cordY").addEventListener("input", filtraCoordPositiva);
document.getElementById("cordX").addEventListener("input", filtraCoordNegativa);

document.getElementById("Unidad_Primer_R").addEventListener("input", filtrarAlfaNumericos);
document.getElementById("Semana").addEventListener("input", filtrarSoloNumeros);

document.getElementById("Marca").addEventListener("input", filtrarAlfaNumericos);
document.getElementById("Submarca").addEventListener("input", filtrarAlfaNumericos);
document.getElementById("Placa_Vehiculo").addEventListener("input", filtrarAlfaNumericos);
document.getElementById("Color").addEventListener("input", filtrarSoloLetras);