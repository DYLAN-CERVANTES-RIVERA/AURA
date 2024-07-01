let eventoError = document.getElementById('evento_error');
var datosEventos;
/*-------------FUNCIONES DE INICIALIZACION PARA VISTA---------*/
document.addEventListener('DOMContentLoaded',async() => {
    await getInfoEventos()
    fechahora_captura = document.getElementById('fechahora_captura_principales');
    fechahora_captura.value = await getFechaActual();
    fechahora_captura.disabled= true;
    captura=document.getElementById('captura_principales');
    captura.disabled= true;
});

const getFechaActual =async() => { //Funcion para Obtener la fecha en el formato sql admitido
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
/*------------- FUNCION AUTOCOMPLETE DE PROBABLE DELITO O FALTA ----------------------- */
const inputDelitos = document.getElementById('principal_actividad');
const myFormData =  new FormData();
inputDelitos.addEventListener('input', () => { 
    myFormData.append('termino', inputDelitos.value)//REALIZA UN FETCH PARA TRAER EL CATALOGO DELITOS PARA QUE SERA COMPARADO CON LO QUE SE ESTA INGRESANDO ADEMAS DE SE OCUPARA PARA LA FUNCION DEL AUTOCOMPLETE
    fetch(base_url_js + 'Seguimientos/getDelitos', {
            method: 'POST',
            body: myFormData
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `${r.Descripcion}`, value: `${r.Descripcion}`}))
        autocomplete({
            input: principal_actividad,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {
                principal_actividad.value = item.label;
            }
        }); 
    })
    .catch(err => alert(`Ha ocurrido un error al obtener los Delitos.\nCódigo de error: ${ err }`))
});

/*------------- FUNCION AUTOCOMPLETE PARA EL CAMPO DE EVENTOS ----------------------- */
const inputEventos = document.getElementById('id_evento');
inputEventos.addEventListener('input', () => { 
        const arr = datosEventos.map( r => ({ label: `FOLIO INFRA:${r.Folio_infra} FOLIO 911:${r.Folio_911} DELITOS:${r.delitos_concat}` , value: `${r.Folio_infra}`}))
        autocomplete({
            input: id_evento,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {
                id_evento.value = item.label;
                document.getElementById('id_evento_value').value=item.value;
            }
        }); 
});
function valideKey(evt) {//FUNCION QUE VALIDA LA INSERCION DE SOLO NUMEROS 
    var code = (evt.which) ? evt.which : evt.keyCode;
    if (code == 8) { //TECLA DELETE
        return true;
    } else if (code >= 48 && code <= 57) { //TECLAS DE NUMEROS
        return true;
    } else { // OTRAS TECLAS.
        return false;
    }
}
function valideMultiples(evt) {//FUNCION QUE VALIDA LA INSERCION DE SOLO NUMEROS 
    var code = (evt.which) ? evt.which : evt.keyCode;
    let codigos = [8,32,34,39,40,41,44,45,46,47,58,59,63,193,201,205,211,218,225,233,237,243,250,209,241]//TECLA DELETE
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
function validaConAcentos(evt) {//FUNCION QUE VALIDA LA INSERCION DE SOLO LETRAS Y VOCALES CON ASCENTOS
    var code = (evt.which) ? evt.which : evt.keyCode;
    let codigos = [8,32,193,201,205,211,218,225,233,237,243,250,209,241]//TECLAS DELETE,ESPACIO Y LETRAS ACENTUADAS
    if ((code >= 65 && code <= 90)||(code >= 97 && code <= 122)) { //TECLAS DE NUMEROS
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
/*------------------FUNCIONES DE LA TABLA DE EVENTOS-------------- */
const onFormEventoSubmit = async() => {//SE LANZA LA FUNCION CON EL BOTON DE + DE LA TABLA EVENTOS
    let inputEventos = document.getElementById('id_evento_value');
    eventoError.innerText='';
    if(inputEventos.value!=''){
        let eventoValido= await ValidaEvento(inputEventos.value);
        if(eventoValido==true){
            eventoValido2= await ValidareadTable(inputEventos.value);
            if(eventoValido2==true){
                InfoEvento= await getInfoEvento(inputEventos.value)
                insertEventoTabla(InfoEvento);
                inputEventos.value='';
                document.getElementById('id_evento').value='';
            }else{
                alert("EL FOLIO YA SE INGRESO A LA TABLA");
            }
        }else{
            eventoError.innerText="Ingrese un evento valido";
        }
    }else{
        eventoError.innerText="Ingrese un evento";
    }
}
const insertEventoTabla = ({ Folio_infra, Folio_911,delitos_concat,Ubicacion}, type) => {//Funcion para cuando ingrese "delito" 
    const table = document.getElementById('EventoTable').getElementsByTagName('tbody')[0];//SELECCIONA LA TABLA
    let newRow = table.insertRow(table.length);//INSERTA EL NUEVO ROW 
    newRow.insertCell(0).innerHTML = Folio_infra;
    newRow.insertCell(1).innerHTML = Folio_911;
    newRow.insertCell(2).innerHTML = Ubicacion.toUpperCase();
    newRow.insertCell(3).innerHTML = delitos_concat;
    if (type === undefined) {//AL NO TENER ESPECICACION ACERCA DEL TIPO DE VISTA SE INFIERE QUE ES UNA TABA EN LA QUE SE PUEDE OCUPAR FUNCIONES DE ACTUALIZACION Y ELIMINACION POR LO QUE SE DA PASO A MOSTRAR LOS BOTONES
        newRow.insertCell(4).innerHTML = `<button type="button" class="btn btn-ssc" value="-" onclick="deleteRow(this,EventoTable)">
                                            <i class="material-icons">delete</i>
                                        </button>`;
    }
}
const ValidaEvento = async(BuscarEvento) => {//FUNCION QUE VALIDA ANTES DE INSERTAR QUE EL EVENTO SE ENCUENTRE EN EL CATALOGO DE EVENTOS SIN SEGUIMIENTO 
    var EventoValido = false;
    if(BuscarEvento.length > 0){
        TodosFolios = datosEventos;
        const result = TodosFolios.find(element => element.Folio_infra == BuscarEvento);
        if (result){
            EventoValido=true;
        }else{
            EventoValido=false;
        }
    }
    return EventoValido;
}
const getInfoEventos = async()=>{
    fetch(base_url_js + 'Seguimientos/getEventos', {
        method: 'POST',
        body: myFormData
    })
    .then(res => res.json())
    .then(data => {
        datosEventos = data;
    })
    .catch(err => alert(`Ha ocurrido un error al obtener los Eventos.\nCódigo de error: ${ err }`))
}
const getInfoEvento = async (Folio) => {//FUNCION PARA OBTENER LA INFORMACION DEL EVENTO 
    try {
        myFormData.append('Folio_infra',Folio);
        const response = await fetch(base_url_js + 'Seguimientos/getInfoEvento', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}

const deleteRow = (obj, tableId) => {//FUNCION DE LA TABLA DE EVENTOS PARA ELIMINAR REGISTRO
    if (confirm('¿Desea eliminar este elemento?')) {
        const row = obj.parentElement.parentElement;
        document.getElementById(tableId.id).deleteRow(row.rowIndex);
    }
}
const ValidareadTable = (Evento) => {//FUNCION PARA QUE NO INGRESE DOS VECES EN LA TABLA EL MISMO EVENTO
    const table = document.getElementById('EventoTable');
    let Banderita = true;

    for (let i = 1; i < table.rows.length; i++) {
        if(table.rows[i].cells[0].innerHTML==Evento){
            Banderita = false;
        }
    }
    return Banderita;
}

/*--------------- FUNCIONES PARA VALIDAR Y DE GUARDAR EL SEGUIMIENTO NUEVO -------------------------*/
var data = document.getElementById('datos_principales')
var GrupoError = document.getElementById('nombre_grupo_error')
var PeligrosidadError = document.getElementById('peligrosidad_error')
var PrincipalActividadError = document.getElementById('principal_actividad_error')
var TablaError=document.getElementById('tabla_eventos_error')
var msg_principalesError = document.getElementById('msg_principales')
document.getElementById('btn_principal').addEventListener('click', async function(e) {//Funcion que valida lo ingresado 
    e.preventDefault()
    var myFormData = new FormData(data)
    var band = []
    var FV = new FormValidator()
    var i = 0
    await ResetLetreros();
    band[i++] = GrupoError.innerText = FV.validate(myFormData.get('nombre_grupo'), 'required ')
    if(document.getElementById('peligrosidad').value=='SD'){
        band[i++] = PeligrosidadError.innerText = FV.validate('', 'required ')
    }

    rowsTableEventos = document.getElementById('contarEventos').rows.length;//ALMACENA EL NUMERO DE RENGLONES DE LA TABLA DELITOS
    
    if(rowsTableEventos >= 1){
        band[i++] = TablaError.innerText = '';
        var EventosForm = readTableEventos();
        myFormData.append('Eventos', JSON.stringify(EventosForm));
    }
    //se comprueban todas las validaciones
    var success = true
    band.forEach(element => {//recorre todas la banderas si todas son vacias procede a guardar los datos ya que no existe ninguna restriccion 
        success &= (element == '') ? true : false
    })
    if (success) { //si todo es correcto se envía form
        let emojis = /(?:[\u2700-\u27bf]|(?:\ud83c[\udde6-\uddff]){2}|[\ud800-\udbff][\udc00-\udfff]|[\u0023-\u0039]\ufe0f?\u20e3|\u3299|\u3297|\u303d|\u3030|\u24c2|\ud83c[\udd70-\udd71]|\ud83c[\udd7e-\udd7f]|\ud83c\udd8e|\ud83c[\udd91-\udd9a]|\ud83c[\udde6-\uddff]|\ud83c[\ude01-\ude02]|\ud83c\ude1a|\ud83c\ude2f|\ud83c[\ude32-\ude3a]|\ud83c[\ude50-\ude51]|\u203c|\u2049|[\u25aa-\u25ab]|\u25b6|\u25c0|[\u25fb-\u25fe]|\u00a9|\u00ae|\u2122|\u2139|\ud83c\udc04|[\u2600-\u26FF]|\u2b05|\u2b06|\u2b07|\u2b1b|\u2b1c|\u2b50|\u2b55|\u231a|\u231b|\u2328|\u23cf|[\u23e9-\u23f3]|[\u23f8-\u23fa]|\ud83c\udccf|\u2934|\u2935|[\u2190-\u21ff])/g;

        let delitos=readTableDelitos();
        myFormData.append('TableDelitos', JSON.stringify(delitos));
        myFormData.append('nombre_grupo',document.getElementById('nombre_grupo').value.toUpperCase())
        myFormData.append('principal_actividad',document.getElementById('principal_actividad').value.toUpperCase())
        myFormData.append('peligrosidad',document.getElementById('peligrosidad').value.toUpperCase())
        let MO= document.getElementById('MO').value.toUpperCase();
        MO=MO.replace(emojis , '');
        myFormData.append('MO',MO)
        let observaciones= document.getElementById('observaciones').value.toUpperCase();
        observaciones=observaciones.replace(emojis , '');
        myFormData.append('observaciones',observaciones)
        myFormData.append('captura_principales',document.getElementById('captura_principales').value.toUpperCase())
        myFormData.append('fechahora_captura_principales',document.getElementById('fechahora_captura_principales').value)

        button2 = document.getElementById('btn_principal')
        button2.innerHTML = `Guardando
                            <div class="spinner-grow spinner-grow-sm" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>`;
        button2.classList.add('disabled-link');//se desactiva el boton de guardar hasta que halla una respuesta del controlador
        $('#ModalCenterFoto').modal('show');//Mostramos una imagen completa en pantalla hasta que halla una respuesta del controlador
        //Agregamos los campos proporcionados  que se almacenaran

        fetch(base_url_js + 'Seguimientos/insertSeguimientoFetch', {//realiza el fetch para insertar los datos
            method: 'POST',
            body: myFormData
        })
        .then(res => res.json())

        .then(data => {//obtine respuesta del controlador
            button2.innerHTML = `Guardar`;
            button2.classList.remove('disabled-link');//se vuelve activar la funcion del boton 
            $('#ModalCenterFoto').modal('hide');//se quita la imagen 
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
                button2.classList.add('disabled-link');//se desactiva el boton para que el usuario no pueda guardar la misma informacion

                window.scroll({
                    top: 0,
                    left: 100,
                    behavior: 'smooth'
                });//mueve la vista hasta arriba de la pagina
                document.getElementById('datos_principales').reset()
                alerta()//Si todo se valido bien y se inserto correctamente se arroja un mensaje satisfactorio y redirige a la pestaña principal del gestor de casos
            }
        })
        

    } else { //si no, se muestran errores en pantalla
        msg_principalesError.innerHTML = `<div class="alert alert-danger text-center" role="alert">POR FAVOR, REVISA NUEVAMENTE EL FORMULARIO HAY CAMPOS REQUERIDOS
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>`;
        window.scroll({
            top: 0,
            left: 100,
            behavior: 'smooth'
        });
    }

    for (var pair of myFormData.entries()) {
        console.log(pair[0] + ', ' + pair[1]);
    }
})

function alerta() {//FUNCION PARA AVISAR QUE TODO SALIO BIEN 
    const BtGuardar =document.getElementById("btn_principal");
    BtGuardar.setAttribute('disabled', '');
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
    setTimeout(function() { window.location = base_url_js+"Seguimientos"; }, 4000);//FUNCION PARA REDIRIGIR A LA PAGINA PRINCIPAL DE SEGUIMIENTO
}

const validateDelito = async (delito_buscar)=> {//FUNCION QUE VALIDA SI EL DELITO INGRESADO ESTA EN EL CATALOGO
    var DelitoValido = "";
    if(delito_buscar.length > 0){
        Delitos = await getAllDelito();
        const result = Delitos.find(element => element.Descripcion.toUpperCase() == delito_buscar);
        if (result){
            DelitoValido = true
        }
        if(DelitoValido==false){
            DelitoValido="Ingrese un Delito valido"
        }else{
            DelitoValido=""
        }  
    }
    return DelitoValido;
}
const getAllDelito = async () => {//FUNCION PIDE TODOS LOS DELITOS DEL CATALOGO
    try {
        const response = await fetch(base_url_js + 'Seguimientos/getDelitos', {//REALIZA UN FETCH DE PETICION DE DATOS EN ESTE CASO LOS DELITOS
            method: 'POST'
        });
        const data = await response.json();
        return data;   
    } catch (error) {
        console.log(error);
    }
}
const ResetLetreros = async () =>{//FUNCION PARA RESETEAR LOS LETREROS DE LA VISTA
    PeligrosidadError.innerText ="";
    GrupoError.innerText ="";
}
/*-----------------------------------FUNCIONES PARA LEER LOS DATOS DE LAS TABLAS-----------------------------------*/
const readTableEventos = () => {//FUNCION QUE LEE LOS DATOS DE LA TABLA DE EVENTOS Y GENERA UNA ESTRUCTURA DEACUERDO A LOS DATOS CONTENIDO EN LA TABLA
    const table = document.getElementById('EventoTable');
    let objetos = [];
    for (let i = 1; i < table.rows.length; i++) {
        objetos.push({
            ['row']: {
                Folio_infra: table.rows[i].cells[0].innerHTML
            }
        });
    }
    return objetos;
}

/*---------------------------FUNCIONES DE LA TABLA DELITOS DEL SEGUIMIENTO------------------------*/

const onFormDelitoSubmit = async() => {
    if(document.getElementById('principal_actividad').value!=''){
         PrincipalActividadError.innerHTML= await validateDelito(document.getElementById('principal_actividad').value); 
    }else{
        PrincipalActividadError.innerHTML="Ingrese un delito primero"
    }

    if(PrincipalActividadError.innerHTML==''){
        InsertDelito(document.getElementById('principal_actividad').value);
        document.getElementById('principal_actividad').value='';
    }
}
const InsertDelito = async(delito) =>{
    let table = document.getElementById('DelitosTable').getElementsByTagName('tbody')[0];//SELECCIONA LA TABLA
    let newRow = table.insertRow(table.length);//INSERTA EL NUEVO ROW 
    newRow.insertCell(0).innerHTML = delito;
    newRow.insertCell(1).innerHTML = `<button type="button" class="btn btn-ssc" value="-" onclick="deleteRow(this,DelitosTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;

}
const readTableDelitos = () => {//FUNCION QUE LEE LOS DATOS DE LA TABLA DE EVENTOS Y GENERA UNA ESTRUCTURA DEACUERDO A LOS DATOS CONTENIDO EN LA TABLA
    let table = document.getElementById('DelitosTable');
    let objetos = [];
    for (let i = 1; i < table.rows.length; i++) {
        objetos.push({
            ['row']: {
                Delito: table.rows[i].cells[0].innerHTML
            }
        });
    }
    return objetos;
}