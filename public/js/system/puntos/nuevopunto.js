let zona = document.getElementById('zona');
let vector = document.getElementById('vector');
var data = document.getElementById('datos_principales_puntos')
var msg_principalesError = document.getElementById('msg_principales_puntos')
var vectores;
var datosRemisiones;
const myFormData =  new FormData();
//-------------Funcion que dispara el cambio en el selector de vector cuando se cambia la opcion de la zona
zona.addEventListener('change', () => {
    vector.innerHTML="";
    if(zona.value.includes('ZONA')){
        zonaValue = zona.value.split(' ');
        vectoresFiltrados = vectores.filter((vector) => vector.Zona == zonaValue[1]);
    }else {
        zonaValue = 'CH';
        vectoresFiltrados = vectores.filter((vector) => vector.Zona == zonaValue);
    }
    if(zona.value == "NA"){
        vectoresFiltrados = [{Id_vector_Interno : 'SELECCIONE', Zona: 0, Region: 'LA ZONA'}]
    }
    vectoresFiltrados.forEach(vectorE => {
        vector.innerHTML += `<option value="${vectorE.Id_vector_Interno}">${vectorE.Id_vector_Interno} - ${vectorE.Region}</option>`
    })
});

const getAllVectores = async () => {
    try{
        const response = await fetch(base_url_js + 'Puntos/getAllVector', {
            method: 'POST',
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}

//-------------Funcion de Inicializacion para vista
document.addEventListener('DOMContentLoaded',async() => {
    vectores = await getAllVectores();
    await getRemisiones();
    fechahora_captura = document.getElementById('fechahora_captura_principales');
    fechahora_captura.value = getFechaActual();
    fechahora_captura.disabled= true;
    captura=document.getElementById('captura_principales');
    captura.disabled= true;
});

const getFechaActual = () => {
    const now = new Date();
    // Obtener la fecha en formato yyyy-mm-dd
    const fecha = now.toISOString().split('T')[0];
    // Obtener la hora en formato de 24 horas
    const options = { hour: '2-digit', minute: '2-digit', hour12: false };
    const hora = new Intl.DateTimeFormat('es-MX', options).format(now);
    // Unir fecha y hora
    const fechaHora = `${fecha} ${hora}`;
    return fechaHora;
}

const changeRemision = () =>{//FUNCION QUE HABILITA LA BUSQUEDA DEL NUMERO REMISION
    let radioHabilitado = document.getElementsByName('Remision_Si_No');
    if(radioHabilitado[0].checked){//SI TIENE REMISION
        document.getElementById('id_Remision_panel').classList.remove('mi_hide');
    }else if(radioHabilitado[1].checked){//NO TIENE REMISION
        document.getElementById('id_Remision_panel').classList.add('mi_hide');
        document.getElementById('id_Remision_panel').value = '';
    }
}

const inputRemisiones = document.getElementById('id_remision');
const error_remision= document.getElementById('error_remision');
inputRemisiones.addEventListener('input', async() => { 
    const arr = datosRemisiones.map( r => ({ label: `REMISION:${r.No_Remision} NOMBRE DETENIDO:${r.Nombre_completo} DETENIDO POR:${r.Detenido_por}` , value: `${r.No_Remision}`}))
    autocomplete({
        input: id_remision,
        fetch: function(text, update) {
            text = text.toLowerCase();
            const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
            update(suggestions);
        },
        onSelect: function(item) {
            id_remision.value = item.value;
            onFormRemisionSubmit()
        }
    }); 
});
const getRemisiones=async()=>{
    fetch(base_url_js + 'Puntos/getRemisiones', {
            method: 'POST',
            body: myFormData
    })
    .then(res => res.json())
    .then(data => {

        datosRemisiones=data;
    })
    .catch(err => alert(`Ha ocurrido un error al obtener las Remisiones.\nCódigo de error: ${ err }`))
}
const onFormRemisionSubmit = async() => {//FUNCION QUE EVALUA SI LA ENTRADA DE NUMERO DE REMISION EXISTE EN EL CATALOGO
    InfoRemision = await getInfoRemision(inputRemisiones.value)
    //console.log(InfoRemision);
    error_remision.innerHTML='';
    if(InfoRemision!=''){
        document.getElementById("nombre").value = (InfoRemision.Alias!="SD" && InfoRemision.Alias.trim()!=""&& InfoRemision.Alias.trim()!="NINGUNO")?InfoRemision.Nombre_completo+' ('+InfoRemision.Alias.trim()+')':InfoRemision.Nombre_completo;
        document.getElementById("Narrativa").value = InfoRemision.Narrativa_Hechos.trim()
    }
}
const getInfoRemision = async (No_Remision) => {//FUNCION PARA OBTENER LA INFORMACION DE LA REMISION SELECCIONADA
    try {
        myFormData.append('No_Remision',No_Remision);
        const response = await fetch(base_url_js + 'Puntos/getInfoRemision', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}

const filtrarSoloNumeros = (event) =>{
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

function filtrarAlfaNumericos(event) {/// Para el pegado
    var valor = event.target.value;
    var nuevoValor = "";
    for (var i = 0; i < valor.length; i++) {
        var char = valor[i];       
        if (/[a-zA-Z0-9-\sáéíóúÁÉÍÓÚÑñ.,]/.test(char)) {
            nuevoValor += char;
        }
    }
    event.target.value = nuevoValor;
}

const cambioSelectFuente = () =>{//FUNCIONES PARA LOS SELECTS CON Y SIN VIOLENCIA
    if(document.getElementById('Fuente_info').value=="DETENIDO"){
        document.getElementById('Info_detenido').classList.remove('mi_hide');
    }else{
        document.getElementById('Info_detenido').classList.add('mi_hide');
    }
}

const filtraCoordNegativa = (event)  =>{///Para la coordenada X
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

const filtraCoordPositiva = (event)  =>{/// Para la coordenada Y
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
const ValidaCoordY = async (valor)  =>{/// Para la coordenada Y
    let bandera =""
    if(valor.length>0){
        let contador=0;
        for (let i = 0; i < valor.length; i++) {
            if(valor[i]=='.'){
                contador++;
            }
        }

        switch(contador){
            case 1: bandera='';break;
            case 0: bandera='Falta el punto decimal';break;
            default: bandera='El Numero de puntos decimales es mayor a 1';
        }
        if(valor[2]!='.'){bandera = bandera + ' Error en el punto decimal ';}
    }else{
         bandera = 'Coordenada Y requerida'
    }

    return bandera.trim();
}

const ValidaCoordX = async (valor)  =>{/// Para la coordenada Y
    
    let bandera =""
    if(valor.length>0){
        let contador=0;
        let contadornegativo=0;
        for (let i = 0; i < valor.length; i++) {
            if(valor[i]=='.'){
                contador++;
            }
        }
        switch(contador){
            case 1: bandera='';break;
            case 0: bandera='Falta el punto decimal';break;
            default: bandera='El Numero de puntos decimales es mayor a 1';
        }
        for (let i = 0; i < valor.length; i++) {
            if(valor[i]=='-'){
                contadornegativo++;
            }
        }
        switch(contadornegativo){
            case 1: bandera = bandera+'';break;
            case 0: bandera = bandera +' Falta el simbolo de negativo';break;
            default: bandera= bandera + ' El Numero de simbolos negativos es mayor a uno';
        }
        if(valor[0]!='-'){bandera = bandera + ' Ingrese el simbolo negativo al principio de la coordenada Y';}
        if(valor[3]!='.'){bandera = bandera + ' Error en el punto decimal ';}
       
    }else{
        bandera = 'Coordenada X requerida'
    }
    return bandera.trim();
}

document.getElementById('btn_nuevo_punto').addEventListener('click', async function(e) {
    e.preventDefault()
    var myFormData = new FormData(data)
    var band = [];
    var i = 0;
  
    let coordXFuera = '',coordYFuera = '';
    if(document.getElementById('Identificador').value!='SIN PUNTO DE VENTA NI ENTREGA' && document.getElementById('Identificador').value!='RED SOCIAL'){
        
        band[i++] = document.getElementById('cordX_principales_error').innerText = await ValidaCoordX(document.getElementById('cordX').value);
        band[i++] = document.getElementById('cordY_principales_error').innerText = await ValidaCoordY(document.getElementById('cordY').value);

        if(document.getElementById('cordX').value >= -98.01 || document.getElementById('cordX').value <= -98.3){
            coordXFuera ='La Coordena X esta fuera del limite del municipio';
        }
        if(document.getElementById('cordY').value >= 19.24 || document.getElementById('cordY').value <= 18.83){
            coordYFuera ='La Coordena Y esta fuera del limite del municipio';
        } 
    
        band[i++] = document.getElementById('zona_error').innerText = (document.getElementById('zona').value!='NA'&& document.getElementById('zona').value!=''&& document.getElementById('zona').value!=null)?'':'Campo Requerido';
        band[i++] = document.getElementById('vector_error').innerText = (document.getElementById('vector').value!='NA')?'':'Campo Requerido';
    }else{
        myFormData.append('cordX','')
        myFormData.append('cordY','')
        document.getElementById('cordX_principales_error').innerText = '';
        document.getElementById('cordY_principales_error').innerText = '';
        document.getElementById('zona_error').innerText = '';
        document.getElementById('vector_error').innerText = '';
    }
    band[i++] = document.getElementById('Fuente_info_error').innerText = (document.getElementById('Fuente_info').value!='NA')?'':'Campo Requerido';
    band[i++] = document.getElementById('Identificador_error').innerText = (document.getElementById('Identificador').value!='NA')?'':'Campo Requerido';

    

    //se comprueban todas las validaciones
    var success = true
    band.forEach(element => {//recorre todas la banderas si todas son vacias procede a guardar los datos ya que no existe ninguna restriccion 
        success &= (element == '') ? true : false
    });
    if (success) { //si todo es correcto se envía form
        myFormData.append('captura_principales',document.getElementById('captura_principales').value.toUpperCase())
        myFormData.append('fechahora_captura_principales',document.getElementById('fechahora_captura_principales').value)
        let button = document.getElementById('btn_nuevo_punto')
        button.innerHTML = `
            Guardando
            <div class="spinner-grow spinner-grow-sm" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        `;
        button.classList.add('disabled-link');//se desactiva el boton de guardar hasta que halla una respuesta del controlador
        $('#ModalCenterPunto').modal('show');//Mostramos una imagen completa en pantalla hasta que halla una respuesta del controlador

        if(coordXFuera!='' || coordYFuera!=''){//Funcion para guardar preguntara si el evento esta pro confirmar
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });
            //console.log(coordXFuera + coordYFuera)
            var opcion = await confirm(coordXFuera +' '+coordYFuera+" ¿Desea guardarlo asi?");

        }else{
            opcion = true;
        }

        if(opcion == true){
            for (var pair of myFormData.entries()) {
                console.log(pair[0] + ', ' + pair[1]);
            }
            fetch(base_url_js + 'Puntos/insertPuntoFetch', {//realiza el fetch para insertar los datos
                method: 'POST',
                body: myFormData
            })
            .then(res => res.json())
            .then(data => {//obtine respuesta del controlador
                button.innerHTML = `Guardar`;
                button.classList.remove('disabled-link');//se vuelve activar la funcion del boton 
                $('#ModalCenterPunto').modal('hide');//se quita la imagen 
                if (!data.status) {//si ubo error informa que clase de error se genero 
                    let messageError;
                    if ('error_message' in data) {
                        if (data.error_message != 'Render Index') {
                            if (typeof(data.error_message) != 'string') {
                                messageError = `<div class="alert alert-danger text-center" role="alert">Sucedio un error en el servidor: ${data.error_message.errorInfo[2]}</div>`;
                            } else {
                                messageError = `<div class="alert alert-danger text-center" role="alert">Sucedio un error en el servidor: ${data.error_message}</div>`;
                            }
                        } else {
                            messageError = `<div class="alert alert-danger text-center alert-session-create" role="alert">
                                                <p>Sucedio un error, su sesión caduco o no tiene los permisos necesarios. Por favor vuelva a iniciar sesión.</p>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalLogin">
                                                    Iniciar sesión
                                                </button>
                                            </div>`;
                        }
                    } else {
                        messageError = '<div class="alert alert-danger text-center" role="alert">Por favor, revisa nuevamente el formulario ya que hubo un error </div>';
                        
                    }
                    console.log(data.error_sql)
                    msg_principalesError.innerHTML = messageError
                    window.scroll({
                        top: 0,
                        left: 100,
                        behavior: 'smooth'
                    });
    
                } else {//si todo salio bien
                    button.classList.add('disabled-link');//se desactiva el boton para que el usuario no pueda guardar la misma informacion
    
                    window.scroll({
                        top: 0,
                        left: 100,
                        behavior: 'smooth'
                    });//mueve la vista hasta arriba de la pagina
                    //document.getElementById('datos_principales').reset()
                    alertaPuntoInsert()//Si todo se valido bien y se inserto correctamente se arroja un mensaje satisfactorio y redirige a la pestaña principal del gestor de casos
                }
            })
            
        }else{
            button.innerHTML = `Guardar`;
            button.classList.remove('disabled-link');//se vuelve activar la funcion del boton 
            $('#ModalCenterPunto').modal('hide');//se quita la imagen 
        }
        
    }else { //si no, se muestran errores en pantalla

        msg_principalesError.innerHTML = `<div class="alert alert-danger text-center" role="alert">Por favor, revisa nuevamente el formulario hay campos requeridos
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>`;
        for (var pair of myFormData.entries()) {
            console.log(pair[0] + ', ' + pair[1]);
        }
        window.scroll({
            top: 0,
            left: 100,
            behavior: 'smooth'
        });
    }


});

function alertaPuntoInsert() {//FUNCION PARA AVISAR QUE TODO SALIO BIEN 

    msg_principalesError.innerHTML = `<div class="alert alert-success text-center" role="success">DATOS INSERTADOS CORRECTAMENTE. EN BREVE SERÁ REDIRIGIDO A LA PÁGINA PRINCIPAL
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>`;
    window.scroll({
        top: 0,
        left: 100,
        behavior: 'smooth'
    });
    setTimeout(function() { window.location = base_url_js+"Puntos"; }, 4000);//FUNCION PARA REDIRIGIR A LA PAGINA PRINCIPAL DE SEGUIMIENTO
}


document.getElementById('id_remision').addEventListener("input", filtrarSoloNumeros);
document.getElementById('Narrativa').addEventListener("input", filtrarAlfaNumericos);
document.getElementById('Info_Adicional').addEventListener("input", filtrarAlfaNumericos);
document.getElementById('Distribuidor').addEventListener("input", filtrarAlfaNumericos);
document.getElementById('Grupo_OP').addEventListener("input", filtrarAlfaNumericos);
document.getElementById('Atendido_Por').addEventListener("input", filtrarAlfaNumericos);

document.getElementById('cordY').addEventListener("input", filtraCoordPositiva);
document.getElementById('cordX').addEventListener("input", filtraCoordNegativa);

document.querySelector("#Fuente_info").addEventListener("change", cambioSelectFuente);
