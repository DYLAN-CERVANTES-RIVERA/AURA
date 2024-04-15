const showHabilitado = () =>{//Funcion de habilitado para la edicion del evento
    radioHabilitado = document.getElementsByName('Habilitado_question')
    if(radioHabilitado[0].checked){
        document.getElementById('form_activacion').classList.remove('mi_hide')
        if(document.getElementById('fechahora_activacion_principales').value==""||document.getElementById('fechahora_activacion_principales').value==null||document.getElementById('fechahora_activacion_principales').value=="NULL"){
            unir=getFechaActual();
            fechahora_activacion=document.getElementById('fechahora_activacion_principales') 
            fechahora_activacion.value = unir;
            fechahora_activacion.disabled = true;
            QuienHabilito=document.getElementById('quienhabilito')
            
            QuienHabilito.value=document.getElementById('actualizahabilito').value
        }
    }else if(radioHabilitado[1].checked){
        document.getElementById('form_activacion').classList.add('mi_hide');
    }
}

document.addEventListener('DOMContentLoaded', () =>{
    radioHabilitado = document.getElementsByName('Habilitado_question')
    radioHabilitado[0].addEventListener('change',showHabilitado)
    radioHabilitado[1].addEventListener('change',showHabilitado)  

})

const getFechaActual = () => { //Funcion para Obtener la fecha en el formato sql admitido
    const str = new Date().toLocaleString('es-MX', );
    auxhoy=str
    separadas = auxhoy.split(',')
    aux=separadas[1] 
    aux=aux.substring(1, 6)
    unir=getFecha();
    unir=unir+' '+aux;
    return unir;
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
    auxhoy=str
    separadas = auxhoy.split(',')
    aux=separadas[1] 
    aux=aux.substring(1, 6)
    return aux;
} 

const uploadFilePDF = (obj) => {
    const name = obj.target.files[0].name,
        content = document.getElementById('filePDF');
    content.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-file-earmark-text" viewBox="0 0 16 16">
            <path d="M4 0h5.5v1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h1V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2z"/>
            <path d="M9.5 3V0L14 4.5h-3A1.5 1.5 0 0 1 9.5 3z"/>
            <path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
        </svg>
        <p class="text-center">${name}</p>
    `;
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

document.getElementById("Compañia").addEventListener("input", filtrarAlfaNumericos);
document.getElementById("Elementos_Realizan_D").addEventListener("input", filtrarLetrasComa);
document.getElementById("Nombres_Detenidos").addEventListener("input", filtrarLetrasComa);

document.getElementById("Colonia_Det").addEventListener("input", filtrarAlfaNumericos);
document.getElementById("Calle_Det").addEventListener("input", filtrarAlfaNumericos);
document.getElementById("Calle_Det2").addEventListener("input", filtrarAlfaNumericos);

document.getElementById('CP_Det').addEventListener("input", filtrarSoloNumeros);
document.getElementById('no_Ext_Det').addEventListener("input", filtrarAlfaNumericos);
document.getElementById('no_Int_Det').addEventListener("input", filtrarAlfaNumericos);

document.getElementById("cordY_Det").addEventListener("input", filtraCoordPositiva);
document.getElementById("cordX_Det").addEventListener("input", filtraCoordNegativa);