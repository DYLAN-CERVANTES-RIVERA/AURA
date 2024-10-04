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

document.getElementById('btn_edit_punto').addEventListener('click', async function(e) {
    e.preventDefault();
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
        myFormData.append('cordX','');
        myFormData.append('cordY','');
        myFormData.append('zona','NA');
        myFormData.append('vector','NA');
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

    if(document.getElementById('imageContentMaps').children[1]==undefined){
        myFormData.append('Path_Img_Google','SD')
        myFormData.append('Img_64_Google','SD')
    }else{
        if(document.getElementById('imageContentMaps').children[2].classList[0]=='File'){
            let base64URL = await encodeFileAsBase64URL(document.getElementById('fileFotoMaps').files[0]);
            myFormData.append('Path_Img_Google','FotoMaps'+document.getElementById('Id_Punto').value+'.png');
            myFormData.append('Img_64_Google',base64URL);
            let type = document.getElementById('imageContentMaps').children[2].classList[0];
            myFormData.append('typeImageGoogle',type);
        }else{
            let base64 = document.getElementById('imagesMaps');
            await toDataURL(base64.src)//esto es para tipo photo osea que ya se encuentran en el servidor un archivo fisico con extension diferente a la png
            .then(myBase64 => {
                myFormData.append('Img_64_Google',myBase64);
                myFormData.append('Path_Img_Google','FotoMaps'+document.getElementById('Id_Punto').value+'.png');
                let type = document.getElementById('imageContentMaps').children[2].classList[0];
                myFormData.append('typeImageGoogle',type);
            })
        }
    }


    if(document.getElementById('imageContentUbi').children[1]==undefined){
        myFormData.append('Path_Img','SD')
        myFormData.append('Img_64','SD')
    }else{
        if(document.getElementById('imageContentUbi').children[2].classList[0]=='File'){
            let base64URL = await encodeFileAsBase64URL(document.getElementById('fileFotoUbi').files[0]);
            myFormData.append('Path_Img','FotoUbi'+document.getElementById('Id_Punto').value+'.png');
            myFormData.append('Img_64',base64URL);
            let type = document.getElementById('imageContentUbi').children[2].classList[0];
            myFormData.append('typeImage',type);
        }else{
            let base64 = document.getElementById('imagesUbis');
            await toDataURL(base64.src)//esto es para tipo photo osea que ya se encuentran en el servidor un archivo fisico con extension diferente a la png
            .then(myBase64 => {
                myFormData.append('Img_64',myBase64);
                myFormData.append('Path_Img','FotoUbi'+document.getElementById('Id_Punto').value+'.png');
                let type = document.getElementById('imageContentUbi').children[2].classList[0];
                myFormData.append('typeImage',type);
            })
        }
    }
    TableDatosUbicacion = await readTablePuntos();

    myFormData.append('DatosUbicacion_table', JSON.stringify(TableDatosUbicacion));
    myFormData.append('Id_Punto',document.getElementById("Id_Punto").value)
    
    if (success) { 

        let button = document.getElementById('btn_edit_punto')
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
            /*for (var pair of myFormData.entries()) {
                console.log(pair[0] + ', ' + pair[1]);
            }*/
            fetch(base_url_js + 'Puntos/UpdatePuntoFetch', {//realiza el fetch para insertar los datos
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
                    alertaPuntoUpdate()//Si todo se valido bien y se inserto correctamente se arroja un mensaje satisfactorio y redirige a la pestaña principal del gestor de casos
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
        /*for (var pair of myFormData.entries()) {
            console.log(pair[0] + ', ' + pair[1]);
        }*/
        window.scroll({
            top: 0,
            left: 100,
            behavior: 'smooth'
        });
    }


});

const alertaPuntoUpdate = async () =>{//FUNCION PARA AVISAR QUE TODO SALIO BIEN 

    msg_principalesError.innerHTML = `<div class="alert alert-success text-center" role="success">Datos Actualizados Correctamente.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>`;
    window.scroll({
        top: 0,
        left: 100,
        behavior: 'smooth'
    });
    await RecargaDom()
}
/*-----------------------------------------FUNCIONES DE TABLA DE DATOS------------------------------------- */
let selectedRowDatosUbi = null
const onFormDatosUbisubmit = async() => {
    if(await ValidaDatosTabla()){
        if (selectedRowDatosUbi === null){
            InsertDatoUbi();
        }else{
            updateRowDatoUbi();
        }
        resetFormDatos();
    }
}
const InsertDatoUbi = async()=>{//FUNCION QUE INSERTA LOS DATOS EN LA TABLA 
    let table = document.getElementById('DatosUbiTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);

    newRow.insertCell(0).innerHTML = `<div class="d-flex justify-content-around" id="uploadFileFotoDato${newRow.rowIndex}">
                                        <div class="form-group">
                                            <input type="file" name="FotoDato_row${newRow.rowIndex}" accept="image/*" id="fileFotoDato_row${newRow.rowIndex}" class="inputfile uploadFileFotoDato" onchange="uploadFileDato(event)" data-toggle="tooltip" data-placement="bottom">
                                            <label for="fileFotoDato_row${newRow.rowIndex}"></label>
                                             <h3 class="uploadFotoDatoCtrolV" style=" border-style: dotted; border-color: red;">Para pegar imagen da click aqui y presiona control+v</h3>
                                        </div>
                                    </div>
                                    <div id="imageContentDato_row${newRow.rowIndex}"></div>`;
    newRow.insertCell(1).innerHTML = document.getElementById('Descripcion_Dato').value.toUpperCase();
    newRow.insertCell(2).innerHTML = document.getElementById('tipo_dato').value;
    newRow.insertCell(3).innerHTML = document.getElementById('captura_dato_persona').value.toUpperCase();
    newRow.insertCell(4).innerHTML = `<button type="button" class="btn btn-add" onclick="editDatoUbi(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc" value="-" onclick="deleteRowDatoUbi(this,DatosUbiTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
    newRow.insertCell(5).innerHTML = 'SD';
    newRow.insertCell(6).innerHTML = await getFechaActual();
    newRow.cells[5].style.display = "none";
    newRow.cells[6].style.display = "none";
}
const resetFormDatos = async()=>{//FUNCION QUE LIMPA LOS DATOS DEL FORMULARIO 
    document.getElementById('Id_Dato_Punto').value ='SD';
    document.getElementById('Descripcion_Dato').value ='';
    document.getElementById('tipo_dato').value = 'NA';
    selectedRowDatosUbi = null;
}
const editDatoUbi = (obj) => {
    document.getElementById('alertaEditDato').style.display = 'block';
    selectedRowDatosUbi = obj.parentElement.parentElement;
    document.getElementById('Descripcion_Dato').value = selectedRowDatosUbi.cells[1].innerHTML;
    document.getElementById('tipo_dato').value = selectedRowDatosUbi.cells[2].innerHTML;
    document.getElementById('Id_Dato_Punto').value = selectedRowDatosUbi.cells[5].innerHTML;
}
const ValidaDatosTabla = async() => {//FUNCION QUE VALIDA LAS ENTRADAS DEL FORMULARIO  PARA QUE SE INGRESE EN LA TABLA
    let band = [];
    let i = 0;
    let respuesta = true;
    band[i++] = document.getElementById('Descripcion_Dato_error').innerText = (document.getElementById('Descripcion_Dato').value.trim()!='')?'':'Campo Requerido';
    band[i++] = document.getElementById('tipo_dato_error').innerText = (document.getElementById('tipo_dato').value!='NA')?'':'Campo Requerido';

    band.forEach(element => {//recorre todas la banderas si todas son vacias procede a guardar los datos ya que no existe ninguna restriccion 
        respuesta &= (element == '') ? true : false
    });
    return respuesta;
}

const updateRowDatoUbi = async()=>{//FUNCION QUE ACTUALIZA LOS DATOS EN LA TABLA
    selectedRowDatosUbi.cells[1].innerHTML = document.getElementById('Descripcion_Dato').value.toUpperCase();
    selectedRowDatosUbi.cells[2].innerHTML = document.getElementById('tipo_dato').value;
    selectedRowDatosUbi.cells[5].innerHTML = document.getElementById('Id_Dato_Punto').value;
    document.getElementById('alertaEditDato').style.display = 'none';
}

//FUNCIONES PARA GUARDAR INFORMACION EN LA BASE DE DATOS Y REFRESCAR LA INFORMACION DE LA VISTA DE LA TABLA
const readTablePuntos = async() => {//lee los datos de la tabla personas y genera una estructura deacuerdo a los datos contenido es la tabla
    let table = document.getElementById('DatosUbiTable');
    let objetos = [];
    for (let i = 1; i < table.rows.length; i++) {
        let input = table.rows[i].cells[0].children[1].children[1];
        if (input != undefined) {//si en el contenedor existe alguna imagen verifica el tipo
            let type = table.rows[i].cells[0].children[1].children[2].classList[1]
            let base64 = document.getElementById('imagesDato_row_' + i);
            nameImage = 'FotoDato_row' + i;
            isPNG = base64.src.split('.');
            if (type != 'File') {//funcion para leer las fotos
                if (isPNG[1] != undefined) {
                    await toDataURL(base64.src)//esto es para tipo photo osea que ya se encuentran en el servidor un archivo fisico con extension diferente a la png
                        .then(myBase64 => {
                            objetos.push({
                                ['row']: {
                                    Descripcion_Dato : table.rows[i].cells[1].innerHTML,
                                    Tipo_Dato: table.rows[i].cells[2].innerHTML,
                                    Capturo: table.rows[i].cells[3].innerHTML,
                                    Id_Dato_Punto : table.rows[i].cells[5].innerHTML,
                                    Fecha_Captura: table.rows[i].cells[6].innerHTML,
                                    Id_Punto: document.getElementById("Id_Punto").value,
                                    typeImage: type,
                                    nameImage: nameImage,
                                    image: myBase64,
                                    imagebase64:myBase64
                                }
                            });
                        })
                } else {//esto es para tipo photo osea que ya se encuentran en el servidor un archivo fisico con extension png
                    objetos.push({
                        ['row']: {
                            Descripcion_Dato : table.rows[i].cells[1].innerHTML,
                            Tipo_Dato: table.rows[i].cells[2].innerHTML,
                            Capturo: table.rows[i].cells[3].innerHTML,
                            Id_Dato_Punto : table.rows[i].cells[5].innerHTML,
                            Fecha_Captura: table.rows[i].cells[6].innerHTML,
                            Id_Punto: document.getElementById("Id_Punto").value,
                            typeImage: type,
                            nameImage: nameImage,
                            image:  base64.src,
                            imagebase64:base64.src
                        }
                    });
                }
            }else{// si es tipo file guarda los datos de la imagen
                let aux=document.getElementById('fileFotoDato_row'+i)
                let base64URL = await encodeFileAsBase64URL(aux.files[0]);
                objetos.push({
                    ['row']: {
                        Descripcion_Dato : table.rows[i].cells[1].innerHTML,
                        Tipo_Dato: table.rows[i].cells[2].innerHTML,
                        Capturo: table.rows[i].cells[3].innerHTML,
                        Id_Dato_Punto : table.rows[i].cells[5].innerHTML,
                        Fecha_Captura: table.rows[i].cells[6].innerHTML,
                        Id_Punto: document.getElementById("Id_Punto").value,
                        typeImage: type,
                        nameImage: nameImage,
                        image: "null",
                        imagebase64:base64URL
                    }
                });
            }
        } else {//si no hay imagen solo almacena los datos el texto 
            objetos.push({
                ['row']: {
                    Descripcion_Dato : table.rows[i].cells[1].innerHTML,
                    Tipo_Dato: table.rows[i].cells[2].innerHTML,
                    Capturo: table.rows[i].cells[3].innerHTML,
                    Id_Dato_Punto : table.rows[i].cells[5].innerHTML,
                    Fecha_Captura: table.rows[i].cells[6].innerHTML,
                    Id_Punto: document.getElementById("Id_Punto").value,
                    typeImage: "null",
                    nameImage: "null",
                    image: "null",
                    imagebase64:"null"
                }
            });
        }
    }
    return objetos;
}

const getFechaActual = async() => {
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

document.getElementById('id_remision').addEventListener("input", filtrarSoloNumeros);
document.getElementById('Narrativa').addEventListener("input", filtrarAlfaNumericos);
document.getElementById('Info_Adicional').addEventListener("input", filtrarAlfaNumericos);
document.getElementById('Distribuidor').addEventListener("input", filtrarAlfaNumericos);
document.getElementById('Grupo_OP').addEventListener("input", filtrarAlfaNumericos);
document.getElementById('Atendido_Por').addEventListener("input", filtrarAlfaNumericos);
document.getElementById('Descripcion_Dato').addEventListener("input", filtrarAlfaNumericos);

document.getElementById('cordY').addEventListener("input", filtraCoordPositiva);
document.getElementById('cordX').addEventListener("input", filtraCoordNegativa);

document.querySelector("#Fuente_info").addEventListener("change", cambioSelectFuente);
