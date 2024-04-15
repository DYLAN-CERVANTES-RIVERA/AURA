/*-----------------------------------------------ESTE ARCHIVO DE JAVASCRIPT ES PARA LA FUNCIONALIDADES DE LA TAB DE LAS ENTREVISTAS-------------------------------------------------------------------------*/
var dataEntrevistas = document.getElementById('datos_Entrevistas')

document.addEventListener('DOMContentLoaded', async () => {
    Folio_infra= getEventotoSearch();
});

document.getElementById('btn_principalEntrevistas').addEventListener('click', (e) => {// funcion se activa en dar guardar entrevistas tab
    e.preventDefault()
    var myFormDataEntrevistas = new FormData(dataEntrevistas)
    myFormDataEntrevistas.append('Folio_infra', Folio_infra)
    button = document.getElementById('btn_principalEntrevistas')
    button.innerHTML = `
        Guardando
        <div class="spinner-grow spinner-grow-sm" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    `;
    button.classList.add('disabled-link');//se desactiva el boton de guardar hasta que halla una respuesta del controlador
    $('#ModalCenterEntrevistas').modal('show');//SE MUESTRA UNA IMAGEN AL CENTRO DE LA PAGINA 
               
    myFormDataEntrevistas.append('entrevistas_table', JSON.stringify(readTableEntrevistas()));
    fetch(base_url_js + 'GestorCasos/updateEntrevistas', {//REALIZA FETCH PARA LA ACTUALIZACION DE LA TAB DE LAS ENTREVISTAS
            method: 'POST',
            body: myFormDataEntrevistas
    })
    .then(res => res.json())
    .then(data => {//Espera una respuesta he informa el usuario el estado de la transaccion
        button.innerHTML = `Guardar entrevistas`;
        button.classList.remove('disabled-link');//se vuelve activar la funcion del boton 
        $('#ModalCenterEntrevistas').modal('hide');
        if (!data.status) {
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
                messageError = '<div class="alert alert-danger text-center" role="alert">Por favor, revisa nuevamente el formulario</div>'
            }
            msg_principales_entrevistas.innerHTML= messageError
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });
        } else {//todo correcto
            msg_principales_entrevistas.innerHTML = `<div class="alert alert-success text-center" role="success">Entrevistas editadas correctamente
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">    
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>`
            window.scroll({
                top: 0,
                left: 100,
                behavior: 'smooth'
            }); 
            refrescarDOMEntrevistas();
        }
        /*for (var pair of myFormDataEntrevistas.entries()) {
            console.log(pair[0] + ', ' + pair[1]);
        }*/
    })
})

const readTableEntrevistas = () => {//lee el contenido de la tabla de entrevistas
    const table = document.getElementById('entrevistasTable');
    let entrevistas = [];

    for (let i = 1; i < table.rows.length; i++) {
        entrevistas.push({
            ['row']: {
                procedencia: table.rows[i].cells[0].innerHTML,
                entrevista: table.rows[i].cells[1].innerHTML,
                nombre_entrevistado: table.rows[i].cells[2].innerHTML,
                clave_entrevistador: table.rows[i].cells[3].innerHTML,
                edad_entrevistado: table.rows[i].cells[4].innerHTML,
                telefono_entrevistado: table.rows[i].cells[5].innerHTML,
                fecha_entrevista: table.rows[i].cells[6].innerHTML,
                hora_entrevista: table.rows[i].cells[7].innerHTML,
                Capturo: table.rows[i].cells[8].innerHTML,
                Ultima_Actualizacion: table.rows[i].cells[10].innerHTML
            }
        });
    }
    console.log(entrevistas);
    return entrevistas;
}
/* ----- ----- ----- Funcionalidades de tabla de Entrevistas ----- ----- ----- */
let selectedRowEntrevistas = null;

const onFormEntrevistasSubmit = ()=>{

    const campos = ['procedencia','entrevista','nombre_Entrevistado','clave_entrevistador','edad_Entrevistado','Telefono_Entrevistado','fecha_entrevista','hora_entrevista'];
    
    if(validateFormEntrevista()){
        let formData = readFormDataEntrevistas(campos);
        if(selectedRowEntrevistas === null)
            insertNewRowEntrevistas(formData);
        else
            updateRowEntrevistas(formData);
        resetFormEntrevistas(campos);
    }
}

const readFormDataEntrevistas = (campos)=>{
    let formData = {};
    for(let i=0; i<campos.length;i++){ 
        formData[campos[i]] = document.getElementById(campos[i]).value;
    }
    return formData;
}

const insertNewRowEntrevistas = ({procedencia,entrevista,nombre_Entrevistado,clave_entrevistador,edad_Entrevistado,Telefono_Entrevistado,fecha_entrevista,hora_entrevista})=>{//Funcion para insertar una nueva entrevista a la tabla
    const table = document.getElementById('entrevistasTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    newRow.insertCell(0).innerHTML = procedencia.toUpperCase();
    newRow.insertCell(1).innerHTML = entrevista.toUpperCase();
    newRow.insertCell(2).innerHTML = nombre_Entrevistado.toUpperCase();
    newRow.insertCell(3).innerHTML = clave_entrevistador.toUpperCase();
    newRow.insertCell(4).innerHTML = (edad_Entrevistado.trim()=='')?'SD':edad_Entrevistado;
    newRow.insertCell(5).innerHTML = (Telefono_Entrevistado.trim()=='')?'SD':Telefono_Entrevistado;
    newRow.insertCell(6).innerHTML = fecha_entrevista;
    newRow.insertCell(7).innerHTML = hora_entrevista;
    newRow.insertCell(8).innerHTML = document.getElementById('actualizaVP').value.toUpperCase();
    newRow.insertCell(9).innerHTML = `<button type="button" class="btn btn-add " onclick="editEntrevistas(this)"> 
                                            <i class="material-icons">edit</i>
                                        </button>
                                        <div class="mt-1 px-1"></div>
                                        <button type="button" class="btn btn-ssc" value="-" onclick="deleteEntrevista(this)">
                                            <i class="material-icons">delete</i>
                                        </button>`;
    newRow.insertCell(10).innerHTML = document.getElementById('actualizaVP').value.toUpperCase();
    newRow.cells[10].style.display = "none";
}

const editEntrevistas = (obj)=>{//Funcion para editar entrevista de la tabla
    selectedRowEntrevistas = obj.parentElement.parentElement;
    document.getElementById('alertEditEntrevista').style.display = 'block';
    document.getElementById('procedencia').value = selectedRowEntrevistas.cells[0].innerHTML;
    document.getElementById('entrevista').value = selectedRowEntrevistas.cells[1].innerHTML;
    document.getElementById('nombre_Entrevistado').value = selectedRowEntrevistas.cells[2].innerHTML;
    document.getElementById('clave_entrevistador').value = selectedRowEntrevistas.cells[3].innerHTML;
    document.getElementById('edad_Entrevistado').value = (selectedRowEntrevistas.cells[4].innerHTML == 'SD')?'':selectedRowEntrevistas.cells[4].innerHTML;
    document.getElementById('Telefono_Entrevistado').value = (selectedRowEntrevistas.cells[5].innerHTML == 'SD')?'':selectedRowEntrevistas.cells[5].innerHTML;
    document.getElementById('fecha_entrevista').value = selectedRowEntrevistas.cells[6].innerHTML;
    document.getElementById('hora_entrevista').value = selectedRowEntrevistas.cells[7].innerHTML;
    window.scroll({
        top: 0,
        left: 100,
        behavior: 'smooth'
    }); 
    
}

const updateRowEntrevistas = ({procedencia,entrevista,nombre_Entrevistado,clave_entrevistador,edad_Entrevistado,Telefono_Entrevistado,fecha_entrevista,hora_entrevista})=>{//Funcion para actualizar una entrevista de la tabla
    selectedRowEntrevistas.cells[0].innerHTML = procedencia.toUpperCase();
    selectedRowEntrevistas.cells[1].innerHTML = entrevista.toUpperCase();
    selectedRowEntrevistas.cells[2].innerHTML = nombre_Entrevistado.toUpperCase();
    selectedRowEntrevistas.cells[3].innerHTML = clave_entrevistador.toUpperCase();
    selectedRowEntrevistas.cells[4].innerHTML = (edad_Entrevistado.trim()=='')?'SD':edad_Entrevistado;
    selectedRowEntrevistas.cells[5].innerHTML = (Telefono_Entrevistado.trim()=='')?'SD':Telefono_Entrevistado;
    selectedRowEntrevistas.cells[6].innerHTML = fecha_entrevista;
    selectedRowEntrevistas.cells[7].innerHTML = hora_entrevista;
    selectedRowEntrevistas.cells[10].innerHTML = document.getElementById('actualizaVP').value.toUpperCase();
    document.getElementById('alertEditEntrevista').style.display = 'none';
}

const resetFormEntrevistas = (campos)=>{//Funcion para resetear contenedor vista
    for(let i=0;i<campos.length;i++){
        if((campos[i]!=fecha_entrevista))
        document.getElementById(campos[i]).value='';
    }
    document.getElementById('procedencia').value = 'NA';
    document.getElementById('clave_entrevistador').value = 'NA';
    document.getElementById('fecha_entrevista').value = getFecha();
    document.getElementById('hora_entrevista').value = getHora();
    selectedRowEntrevistas = null;
}

const deleteEntrevista = (obj)=>{//Funcion para eliminar una entrevista
    if(confirm('¿Desea eliminar este elemento?')){
        const row = obj.parentElement.parentElement;
        table = document.getElementById('entrevistasTable');
        table.deleteRow(row.rowIndex);

    }
}

const validateFormEntrevista = () =>{//Funcion para validar la entrevista antes que se meta a la tabla 
    let isValid = true;
    let band = []
    let i=0;
    band[i++] = document.getElementById('procedencia_error').innerText = (document.getElementById('procedencia').value == "NA")?"Debe de especificar la procedencia":"";
    band[i++] = document.getElementById('clave_entrevistador_error').innerText = (document.getElementById('clave_entrevistador').value === "NA")?"Debe de especificar la clave del entrevistador":"";
    band[i++] = document.getElementById('entrevista_error').innerText = (document.getElementById('entrevista').value.trim() == "")?"Ingrese la entrevista":"";
    band[i++] = document.getElementById('nombre_Entrevistado_error').innerText = (document.getElementById('nombre_Entrevistado').value.trim() == "")?"Ingrese el nombre del entrevistado":"";
    
    band.forEach(element => {
        isValid &= (element == '') ? true : false
    })
    return isValid;
}
/*Funciones para terminar el seguimiento */
var EstatusSeguimientoError = document.getElementById('msg_principales_entrevistas')
var datosfot = document.getElementById('datos_fotos')
const onFormSeguimientoTermSubmit = async()=>{
    var myFormData1 = new FormData(datosfot);
    evento = getEventotoSearch();
    fotos =  await getFotos(evento);
    if(fotos.length<1){
        alert('NO PUEDES DESACTIVAR EL SEGUIMIENTO DEL EVENTO HASTA QUE AGREGUES POR LO MENOS UNA IMAGEN DE VIDEO O FOTO AL EVENTO')
    }else{

    
        var opcion = confirm("¿Desea Terminar Seguimiento?");
        if (opcion == true) {
            myFormData1.append('FolioInfra', document.getElementById('folio_infra_principales').value)
            myFormData1.append('SeguimientoTerminado', 1)

            fetch(base_url_js + 'GestorCasos/UpdateSeguimientoTerminado', {
                method: 'POST',
                body: myFormData1
            })

            .then(res => res.json())
            .then(data => {

                console.log(data)
                if (!data.status) {
                    EstatusSeguimientoError.innerHTML = '<div class="alert alert-danger text-center" role="alert">Ubo un error al actualizar el Termino del seguimiento</div>';
                    window.scroll({
                        top: 0,
                        left: 100,
                        behavior: 'smooth'
                    });

                }else{
                    EstatusSeguimientoError.innerHTML = `<div class="alert alert-success text-center" role="success">Termino del seguimiento Actualizado
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
            })

        }
    }
}

//Filtrado de parametros de entrada
document.getElementById("Telefono_Entrevistado").addEventListener("input", filtrarSoloNumeros);
document.getElementById("edad_Entrevistado").addEventListener("input", filtrarSoloNumeros);
document.getElementById("nombre_Entrevistado").addEventListener("input", filtrarSoloLetras);