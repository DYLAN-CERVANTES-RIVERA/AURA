$(function() {
    $('[data-toggle="tooltip"]').tooltip()
})

var search = document.getElementById('id_search')
var catalogoActual = document.getElementById('catalogoActual')
var search_button = document.getElementById('search_button')

document.getElementById('id_form_catalogo').style.display = 'none'

//caracteres máximos en texarea
const MAXLENGTH = 300
var textareas = document.querySelectorAll('textarea')

textareas.forEach(function(element, index, array) {
    element.maxLength = MAXLENGTH
})

search_button.addEventListener('click', buscarUsuarioCad)

function buscarUsuarioCad(e) {
    var myform = new FormData()
    myform.append("cadena", search.value)
    myform.append("catalogoActual", catalogoActual.value)

    fetch(base_url_js + 'Catalogos/buscarPorCadena', {
            method: 'POST',
            body: myform
        })
        .then(function(response) {
            if (response.ok) {
                return response.json()
            } else {
                throw "Error en la llamada Ajax";
            }
        })
        .then(function(myJson) {
            if (!(typeof(myJson) == 'string')) {
                document.getElementById('id_tbody').innerHTML = myJson.infoTable.body
                document.getElementById('id_thead').innerHTML = myJson.infoTable.header
                document.getElementById('id_pagination').innerHTML = myJson.links
                document.getElementById('id_link_excel').href = myJson.export_links.excel
                document.getElementById('id_link_pdf').href = myJson.export_links.pdf
                document.getElementById('id_total_rows').innerHTML = myJson.total_rows
            } else {
                console.log("myJson: " + myJson)
            }

        })
        .catch(function(error) {
            console.log("Error desde Catch _  " + error)
        })

}

function checarCadena(e) {
    if (search.value == "") {
        buscarUsuarioCad()
    }
}

//function para precargar la información del registro seleccionado y visualizar formulario de edición
function editAction(catalogo, id_reg) {
    console.log("catalogo: " + catalogo + "\n Id: " + id_reg)
    document.getElementById('id_form_catalogo').style.display = 'block'
    document.getElementById('send_button').innerHTML = 'Guardar'
    /*Se comento esta asignacion ya que pone el boton con fondo blanco*/
   // document.getElementById('send_button').style.backgroundColor = 'var(--blue-darken-2)'
    window.scrollTo(0, 50);

    switch (catalogo) {
        case 1:
            var t_row = document.getElementById('tr' + id_reg)
            document.getElementById('id_dato_d').value = id_reg
            document.getElementById('id_delito').value = t_row.getElementsByTagName('td')[1].innerHTML
            document.getElementById('id_delito').focus()
            document.getElementById('id_descripcion').value = t_row.getElementsByTagName('td')[2].innerHTML
            document.getElementById('id_actividad').value = t_row.getElementsByTagName('td')[3].innerHTML
            document.getElementById('Tipo_actividad').value = t_row.getElementsByTagName('td')[4].innerHTML
            break
        case 2:
            var t_row = document.getElementById('tr' + id_reg)
            document.getElementById('id_tipo_arma').value = id_reg
            document.getElementById('id_tipo_arma_nombre').value = t_row.getElementsByTagName('td')[1].innerHTML
            document.getElementById('id_tipo_arma_nombre').focus()
            break
        case 3:
            var t_row = document.getElementById('tr' + id_reg)
            document.getElementById('id_tipo_violencia').value = id_reg
            document.getElementById('id_tipo_violencia_valor').value = t_row.getElementsByTagName('td')[1].innerHTML
            document.getElementById('id_tipo_violencia_valor').focus()
            break
        case 4:
            var t_row = document.getElementById('tr' + id_reg)
            document.getElementById('id_zona_sector').value = id_reg
            document.getElementById('id_tipo_grupo').value = t_row.getElementsByTagName('td')[1].innerHTML
            document.getElementById('id_tipo_grupo').focus()
            document.getElementById('id_zona_sector_valor').value = t_row.getElementsByTagName('td')[2].innerHTML
            break
        case 5:
            var t_row = document.getElementById('tr' + id_reg)
            document.getElementById('id_vector').value = id_reg
            document.getElementById('id_vector_i').value = t_row.getElementsByTagName('td')[1].innerHTML
            document.getElementById('id_vector_i').focus()
            document.getElementById('id_zona').value = t_row.getElementsByTagName('td')[2].innerHTML
            document.getElementById('id_vector_numero').value = t_row.getElementsByTagName('td')[3].innerHTML
            document.getElementById('id_region').value = t_row.getElementsByTagName('td')[4].innerHTML
            break
        case 6:
            var t_row = document.getElementById('tr' + id_reg)
            document.getElementById('id_marca_vehiculo').value = id_reg
            document.getElementById('id_marca').value = t_row.getElementsByTagName('td')[1].innerHTML
            document.getElementById('id_marca').focus()
            break
        case 7:
            var t_row = document.getElementById('tr' + id_reg)
            document.getElementById('id_tipo_veh').value = id_reg
            document.getElementById('id_tipo_veh_desc').value = t_row.getElementsByTagName('td')[1].innerHTML
            document.getElementById('id_tipo_veh_desc').focus()
            break
        case 8:
            var t_row = document.getElementById('tr' + id_reg)
            document.getElementById('id_submarca_veh').value = id_reg
            document.getElementById('id_submarca_desc').value = t_row.getElementsByTagName('td')[1].innerHTML
            document.getElementById('id_submarca_desc').focus()
            break
        case 9:
            var t_row = document.getElementById('tr' + id_reg)
            document.getElementById('Id_colonia').value = id_reg
            document.getElementById('tipo').value = t_row.getElementsByTagName('td')[1].innerHTML
            document.getElementById('colonia').value = t_row.getElementsByTagName('td')[2].innerHTML
            break
        case 10:
            var t_row = document.getElementById('tr' + id_reg)
            document.getElementById('Id_calle').value = id_reg
            document.getElementById('Id_calle_desc').value = t_row.getElementsByTagName('td')[1].innerHTML
            document.getElementById('Id_calle_desc').focus()
            break
        case 11:
            var t_row = document.getElementById('tr' + id_reg)
            document.getElementById('Id_cp').value = id_reg
            document.getElementById('Codigo_postal').value = t_row.getElementsByTagName('td')[1].innerHTML
            document.getElementById('Nombre').value = t_row.getElementsByTagName('td')[2].innerHTML
            break
        case 12:
            var t_row = document.getElementById('tr' + id_reg)
            document.getElementById('id_clave').value = t_row.getElementsByTagName('td')[0].innerHTML
            document.getElementById('nombre_clave').value = t_row.getElementsByTagName('td')[1].innerHTML
            //document.getElementById('nombre_clave').focus()
            document.getElementById('ap_paterno_clave').value = t_row.getElementsByTagName('td')[2].innerHTML
            document.getElementById('ap_materno_clave').value =t_row.getElementsByTagName('td')[3].innerHTML
            document.getElementById('clave').value = t_row.getElementsByTagName('td')[4].innerHTML
            break
        case 13:
            var t_row = document.getElementById('tr' + id_reg)
            document.getElementById('id_fuente').value = t_row.getElementsByTagName('td')[0].innerHTML
            document.getElementById('fuente').value = t_row.getElementsByTagName('td')[1].innerHTML
            break
        case 14:
            var t_row = document.getElementById('tr' + id_reg)
            document.getElementById('id_area').value = t_row.getElementsByTagName('td')[0].innerHTML
            document.getElementById('area').value = t_row.getElementsByTagName('td')[1].innerHTML
            break
        case 15:
            var t_row = document.getElementById('tr' + id_reg)
            document.getElementById('id_tipo_violencia').value = id_reg
            document.getElementById('id_tipo_violencia_valor').value = t_row.getElementsByTagName('td')[1].innerHTML
            document.getElementById('id_tipo_violencia_valor').focus()
            break
        case 16:
            var t_row = document.getElementById('tr' + id_reg)
            document.getElementById('Id_Dato_Indicativo').value = id_reg
            document.getElementById('Indicativo').value = t_row.getElementsByTagName('td')[1].innerHTML
            document.getElementById('Indicativo').focus()
            break
        case 17:
            var t_row = document.getElementById('tr' + id_reg)
            document.getElementById('Id_Dato_Tipo').value = id_reg
            document.getElementById('Tipo').value = t_row.getElementsByTagName('td')[1].innerHTML
            document.getElementById('Tipo').focus()
            break
        case 18:
            var t_row = document.getElementById('tr' + id_reg)
            document.getElementById('Id_Dato').value = t_row.getElementsByTagName('td')[0].innerHTML
            document.getElementById('Calle').value = t_row.getElementsByTagName('td')[1].innerHTML
            //document.getElementById('nombre_clave').focus()
            document.getElementById('Calle2').value = t_row.getElementsByTagName('td')[2].innerHTML
            document.getElementById('Info_Adicional').value =t_row.getElementsByTagName('td')[3].innerHTML
            document.getElementById('CoordX').value = t_row.getElementsByTagName('td')[4].innerHTML
            document.getElementById('CoordY').value =t_row.getElementsByTagName('td')[5].innerHTML
    }

}

//funcion para ocultar formulario de edición o creación
function hideForm() {
    document.getElementById('id_form_catalogo').style.display = 'none'
}

//funcion para vaciar los campos correspondientes (si no lo estan) y mostrar el form de cada catálogo
function addAction(catalogo) {
    console.log("catalogo: " + catalogo)
    document.getElementById('id_form_catalogo').style.display = 'block'
    document.getElementById('send_button').innerHTML = 'Crear'
    /*Se comento esta asignacion ya que pone el boton con fondo blanco*/
   // document.getElementById('send_button').style.backgroundColor = 'var(--green-darken-2)'
    window.scrollTo(0, 50);

    switch (catalogo) {
        case 1:
            document.getElementById('id_dato_d').value = ''
            document.getElementById('id_delito').value = ''
            document.getElementById('id_descripcion').value = ''
            document.getElementById('id_actividad').value = ''
            document.getElementById('Tipo_actividad').value = ''
           
            break
        case 2:
            document.getElementById('id_tipo_arma').value = ''
            document.getElementById('id_tipo_arma_nombre').value = ''
            document.getElementById('id_tipo_arma_nombre').focus()
            break
        case 3:
            document.getElementById('id_tipo_violencia').value = ''
            document.getElementById('id_tipo_violencia_valor').value = ''
            document.getElementById('id_tipo_violencia_valor').focus()
            break
        case 4:
            document.getElementById('id_zona_sector').value = ''
            document.getElementById('id_tipo_grupo').value = ''
            document.getElementById('id_tipo_grupo').focus()
            document.getElementById('id_zona_sector_valor').value = ''
            break
        case 5:
            document.getElementById('id_vector').value = ''
            document.getElementById('id_vector_i').value = ''
            document.getElementById('id_zona').value = ''
            document.getElementById('id_vector_numero').value = ''
            document.getElementById('id_region').value = ''
            document.getElementById('id_vector_i').focus()
            break
        case 6:
            document.getElementById('id_marca_vehiculo').value = ''
            document.getElementById('id_marca').value = ''
            document.getElementById('id_marca').focus()
            break
        case 7:
            document.getElementById('id_tipo_veh').value = ''
            document.getElementById('id_tipo_veh_desc').value = ''
            document.getElementById('id_tipo_veh_desc').focus()
            break
        case 8:
            document.getElementById('id_submarca_veh').value = ''
            document.getElementById('id_submarca_desc').value = ''
            document.getElementById('id_submarca_desc').focus()
            break
        case 9:
            document.getElementById('Id_colonia').value = ''
            document.getElementById('tipo').value = ''
            document.getElementById('colonia').value = ''
            document.getElementById('colonia').focus()
            break    
        case 10:
            document.getElementById('Id_calle').value = ''
            document.getElementById('Id_calle_desc').value = ''
            document.getElementById('Id_calle_desc').focus()
            break   
        case 11:
            document.getElementById('Id_cp').value = ''
            document.getElementById('Codigo_postal').value = ''
            document.getElementById('Nombre').value = ''
            document.getElementById('Codigo_postal').focus()
            break
        case 12:
            document.getElementById('id_clave').value = ''
            document.getElementById('nombre_clave').value = ''
            document.getElementById('nombre_clave').focus()
            document.getElementById('ap_paterno_clave').value = ''
            document.getElementById('ap_materno_clave').value =''
            document.getElementById('clave').value = ''
            break
        case 13:
            document.getElementById('id_fuente').value = ''
            document.getElementById('fuente').value = ''
            document.getElementById('fuente').focus()
            break
        case 14:
            document.getElementById('id_area').value = ''
            document.getElementById('area').value = ''
            document.getElementById('area').focus()
            break
        case 15:
            document.getElementById('id_tipo_violencia').value = ''
            document.getElementById('id_tipo_violencia_valor').value = ''
            document.getElementById('id_tipo_violencia_valor').focus()
            break
        case 16:
            document.getElementById('Id_Dato_Indicativo').value = ''
            document.getElementById('Indicativo').value = ''
            document.getElementById('Indicativo').focus()
            break
        case 17:
            document.getElementById('Id_Dato_Tipo').value = ''
            document.getElementById('Tipo').value = ''
            document.getElementById('Tipo').focus()
            break
        case 18:
            document.getElementById('Id_Dato').value = ''
            document.getElementById('Calle').value = ''
            document.getElementById('Calle2').value = ''
            document.getElementById('Info_Adicional').value = ''
            document.getElementById('CoordX').value = ''
            document.getElementById('CoordY').value = ''
            document.getElementById('Calle').focus()
            break
    }
}

function deleteAction(catalogo, id_reg) {
    console.log("catalogo: " + catalogo + "\n Id: " + id_reg)
    const confirmaDelete = confirm("¿Estás seguro de borrar este registro permanéntemente?")

    if (confirmaDelete) {
        var myForm = new FormData()
            //catálogo que será afectado por medio del form
        myForm.append('catalogo', catalogo)
            //acción del fetch (insertar o actualizar)
        myForm.append('Id_Reg', id_reg)
            //catálogo que será afectado por medio del form
        myForm.append('deletePostForm', 1)

        fetch(base_url_js + 'Catalogos/deleteFormFetch', {
                method: 'POST',
                body: myForm
            })
            .then(function(response) {
                if (response.ok) {
                    return response.json()
                } else {
                    throw "Error en la llamada Ajax";
                }
            })
            .then(function(myJson) {
                console.log(myJson)
                if (myJson == 'Success') {
                    alert("El registro ha sido borrado!")
                    document.location.reload()
                } else {
                    alert(myJson)
                }
            })
            .catch(function(error) {
                console.log("Error desde Catch _  " + error)
            })
    }
}

//función para enviar el formulario, comprobar si se trata de insert or update y comprobar todo el llenado correcto del mismo
async function sendFormAction(catalogo) {
    switch (catalogo) {
        case 1:
            var id_dato_d = document.getElementById('id_dato_d')
            var id_delito = document.getElementById('id_delito')
            var descripcion = document.getElementById('id_descripcion')
            var id_actividad = document.getElementById('id_actividad')
            var Tipo_actividad = document.getElementById('Tipo_actividad')
            if (id_dato_d.value == '') { // se trata de insert
                //validaciones
                if (id_delito.value.trim() != ''  && descripcion.value.trim() != '' && id_actividad.value.trim() != ''&& descripcion.value.length <= MAXLENGTH && Tipo_actividad.value.trim() != '' && Tipo_actividad.value.length <= MAXLENGTH) {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '1')
                }
            } else { // se trata de update
                if (id_dato_d.value.trim() != '' && id_delito.value.trim() != '' && id_actividad.value.trim() != '' &&  descripcion.value.trim() != '' && descripcion.value.length <= MAXLENGTH && Tipo_actividad.value.trim() != '' && Tipo_actividad.value.length <= MAXLENGTH) {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '2')
                }
            }
            break
        case 2:
            var id_tipo_arma = document.getElementById('id_tipo_arma')
            var tipo_arma = document.getElementById('id_tipo_arma_nombre')
            if (id_tipo_arma.value == '') { // se trata de insert
                //validaciones
                if (tipo_arma.value.trim() != '') {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '1')
                }
            } else { // se trata de update
                if (id_tipo_arma.value.trim() != '' && tipo_arma.value.trim() != '') {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '2')
                }
            }
            break
        case 3:
            var id_tipo_violencia = document.getElementById('id_tipo_violencia')
            var tipo_violencia = document.getElementById('id_tipo_violencia_valor')
            if (id_tipo_violencia.value == '') { // se trata de insert
                //validaciones
                if (tipo_violencia.value.trim() != '') {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '1')
                }
            } else { // se trata de update
                if (id_tipo_violencia.value.trim() != '' && tipo_violencia.value.trim() != '') {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '2')
                } else console.log("Error en el form amix")
            }
            break
        case 4:
            //form inputs charge
            var id_zona_sector = document.getElementById('id_zona_sector')
            var tipo_grupo = document.getElementById('id_tipo_grupo')
            var zona_sector_valor = document.getElementById('id_zona_sector_valor')

            if (id_zona_sector.value == '') { // se trata de insert
                //validaciones
                if (tipo_grupo.value.trim() != '' && zona_sector_valor.value.trim() != '' && zona_sector_valor.value.length <= MAXLENGTH) {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '1')
                }
            } else { // se trata de update
                if (id_zona_sector.value.trim() != '' && tipo_grupo.value.trim() != '' && zona_sector_valor.value.trim() != '' && zona_sector_valor.value.length <= MAXLENGTH) {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '2')
                }
            }
            break
        case 5:
            var id_vector = document.getElementById('id_vector')
            var id_vector_in = document.getElementById('id_vector_i')
            var id_zona = document.getElementById('id_zona')
            var id_vector_numero = document.getElementById('id_vector_numero')
            var id_region = document.getElementById('id_region')
            if (id_vector.value == '') { // se trata de insert
                //validaciones
                if (id_vector_in.value.trim() != '' && id_zona.value.trim() != '' && id_vector_numero.value.trim() != '' &&
                    id_region.value.trim() != '' && id_region.value.length <= MAXLENGTH) {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '1')
                }
            } else { // se trata de update
                if (id_vector.value.trim() != '' && id_vector_in.value.trim() != '' && id_zona.value.trim() != '' && id_vector_numero.value.trim() != '' &&
                    id_region.value.trim() != '' && id_region.value.length <= MAXLENGTH) {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '2')
                }
            }
            break
        case 6:
            var id_marca_vehiculo = document.getElementById('id_marca_vehiculo')
            var marca = document.getElementById('id_marca')
            if (id_marca_vehiculo.value == '') { // se trata de insert
                //validaciones
                if (marca.value.trim() != '') {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '1')
                }
            } else { // se trata de update
                if (id_marca_vehiculo.value.trim() != '' && marca.value.trim() != '') {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '2')
                } else console.log("Error en el form mano(a)")
            }
            break
        case 7:
            var id_tipo_veh = document.getElementById('id_tipo_veh')
            var id_tipo_veh_desc = document.getElementById('id_tipo_veh_desc')
            if (id_tipo_veh.value == '') { // se trata de insert
                //validaciones
                if (id_tipo_veh_desc.value.trim() != '') {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '1')
                }
            } else { // se trata de update
                if (id_tipo_veh.value.trim() != '' && id_tipo_veh_desc.value.trim() != '') {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '2')
                } else console.log("Error en el form amix")
            }
            break
        case 8:
            var id_submarca_veh = document.getElementById('id_submarca_veh')
            var id_submarca_desc = document.getElementById('id_submarca_desc')
            if (id_submarca_veh.value == '') { // se trata de insert
                //validaciones
                if (id_submarca_desc.value.trim() != '') {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '1')
                }
            } else { // se trata de update
                if (id_submarca_veh.value.trim() != '' && id_submarca_desc.value.trim() != '') {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '2')
                } else console.log("Error en el form amix")
            }
            break
        case 9:
            var Id_colonia = document.getElementById('Id_colonia')
            var tipo = document.getElementById('tipo')
            var colonia = document.getElementById('colonia')
            if (Id_colonia.value == '') { // se trata de insert
                //validaciones
                if (tipo.value.trim() != '' && colonia.value.trim() != '') {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '1')
                }
            } else { // se trata de update
                if (Id_colonia.value.trim() != '' && tipo.value.trim() != '' && colonia.value.trim() != '') {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '2')
                } else console.log("Error en el form amix")
            }
            break
        case 10:
            var Id_calle = document.getElementById('Id_calle')
            var Id_calle_desc = document.getElementById('Id_calle_desc')
            if (Id_calle.value == '') { // se trata de insert
                //validaciones
                if (Id_calle_desc.value.trim() != '') {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '1')
                }
            } else { // se trata de update
                if (Id_calle.value.trim() != '' && Id_calle_desc.value.trim() != '') {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '2')
                } else console.log("Error en el form amix")
            }
            break
        case 11:
            var Id_cp = document.getElementById('Id_cp')
            var Codigo_postal = document.getElementById('Codigo_postal')
            var Nombre = document.getElementById('Nombre')           
            if (Id_cp.value == '') { // se trata de insert
                //validaciones
                if (Codigo_postal.value.trim() != '' && Nombre.value.trim() != '') {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '1')
                }
            } else { // se trata de update
                if (Id_cp.value.trim() != '' && Codigo_postal.value.trim() != '' && Nombre.value.trim() != '' ) {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '2')
                } else console.log("Error en el form amix")
            }
            break
        case 12:
            var id_clave = document.getElementById('id_clave')
            var nombre_clave = document.getElementById('nombre_clave')
            var ap_paterno_clave = document.getElementById('ap_paterno_clave')
            var ap_materno_clave = document.getElementById('ap_materno_clave')
            var clave = document.getElementById('clave')
            console.log(id_clave.value)
            console.log(nombre_clave.value)
            console.log(ap_paterno_clave.value)
            console.log(ap_materno_clave.value)
            console.log(clave.value)
            if (id_clave.value == '') { // se trata de insert
                //validaciones
                if ((nombre_clave.value.trim() != '')&&(ap_paterno_clave.value.trim() != '')&&(ap_materno_clave.value.trim() != '')&&(clave.value.trim() != '')){
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '1')
                }
            } else { // se trata de update
                if ((id_clave.value.trim() != '') && (nombre_clave.value.trim() != '')&&(ap_paterno_clave.value.trim() != '')&&(ap_materno_clave.value.trim() != '')&&(clave.value.trim() != '')) {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '2')
                } else console.log("Error en el form amix")
            }
            break
        case 13:
            var id_fuente = document.getElementById('id_fuente')
            var fuente = document.getElementById('fuente')

            if (id_fuente.value == '') { // se trata de insert
                //validaciones
                if ((fuente.value.trim() != '')){
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '1')
                }
            } else { // se trata de update
                if ((id_fuente.value.trim() != '') && (fuente.value.trim() != '')) {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '2')
                } else console.log("Error en el form amix")
            }
            break
        case 14:
            var id_area = document.getElementById('id_area')
            var area = document.getElementById('area')

            if (id_area.value == '') { // se trata de insert
                //validaciones
                if ((area.value.trim() != '')){
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '1')
                }
            } else { // se trata de update
                if ((id_area.value.trim() != '') && (area.value.trim() != '')) {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '2')
                } else console.log("Error en el form amix")
            }
            break
        case 15:
            var id_tipo_violencia = document.getElementById('id_tipo_violencia')
            var tipo_violencia = document.getElementById('id_tipo_violencia_valor')
            if (id_tipo_violencia.value == '') { // se trata de insert
                //validaciones
                if (tipo_violencia.value.trim() != '') {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '1')
                }
            } else { // se trata de update
                if (id_tipo_violencia.value.trim() != '' && tipo_violencia.value.trim() != '') {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '2')
                } else console.log("Error en el form amix")
            }
            break
        case 16:
            var Id_Dato_Indicativo = document.getElementById('Id_Dato_Indicativo')
            var Indicativo = document.getElementById('Indicativo')
            if (Id_Dato_Indicativo.value == '') { // se trata de insert
                //validaciones
                if (Indicativo.value.trim() != '') {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '1')
                }
            } else { // se trata de update
                if (Id_Dato_Indicativo.value.trim() != '' && Indicativo.value.trim() != '') {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '2')
                } else console.log("Error en el form amix")
            }
            break
        case 17:
            var Id_Dato_Tipo = document.getElementById('Id_Dato_Tipo')
            var Tipo = document.getElementById('Tipo')
            if (Id_Dato_Tipo.value == '') { // se trata de insert
                //validaciones
                if (Tipo.value.trim() != '') {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '1')
                }
            } else { // se trata de update
                if (Id_Dato_Tipo.value.trim() != '' && Tipo.value.trim() != '') {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '2')
                } else console.log("Error en el form amix")
            }
            break
        case 18:
            var id_dato = document.getElementById('Id_Dato')
            var Calle = document.getElementById('Calle')
            var CoordX = document.getElementById('CoordX')
            var CoordY = document.getElementById('CoordY')

            if (id_dato.value == '') { // se trata de insert
                //validaciones
                if (Calle.value.trim() != '' && CoordX.value.trim() != ''&& CoordY.value.trim() != '') {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '1')
                }
            } else { // se trata de update
                if (id_dato.value.trim() != '' && Calle.value.trim() != '' && CoordX.value.trim() != ''&& CoordY.value.trim() != '') {
                    console.log("form valido, se envia form por fetch")
                    fetchFormCatalogo(catalogo, '2')
                } else console.log("Error en el form")
            }
            break
    }
}

function fetchFormCatalogo(catalogo, action) { //action: 1 - insertar,  2 - actualizar
    var myForm = new FormData()
        //acción del fetch (insertar o actualizar)
    myForm.append('action', action)
        //catálogo que será afectado por medio del form
    myForm.append('catalogo', catalogo)
        //catálogo que será afectado por medio del form
    myForm.append('postForm', 1)

    switch (catalogo) { //apendizar todos los campos correspondientes conforme al cátolo afectado
        case 1:
            myForm.append('Id_dato', document.getElementById('id_dato_d').value)
            myForm.append('Id_delito', document.getElementById('id_delito').value)
            myForm.append('Descripcion', document.getElementById('id_descripcion').value)
            myForm.append('Id_actividad', document.getElementById('id_actividad').value)
            myForm.append('Tipo_actividad', document.getElementById('Tipo_actividad').value)
            break
        case 2:
            myForm.append('Id_Tipo_Arma', document.getElementById('id_tipo_arma').value)
            myForm.append('Tipo_Arma', document.getElementById('id_tipo_arma_nombre').value)
            break
        case 3:
            myForm.append('Id_Tipo_Violencia', document.getElementById('id_tipo_violencia').value)
            myForm.append('Tipo_Violencia', document.getElementById('id_tipo_violencia_valor').value)
            break
        case 4:
            myForm.append('Id_Zona_Sector', document.getElementById('id_zona_sector').value)
            myForm.append('Tipo_Grupo', document.getElementById('id_tipo_grupo').value)
            myForm.append('Zona_Sector', document.getElementById('id_zona_sector_valor').value)
            break
        case 5:
            myForm.append('Id_Vector', document.getElementById('id_vector').value)
            myForm.append('Id_Vector_Interno', document.getElementById('id_vector_i').value)
            myForm.append('Zona', document.getElementById('id_zona').value)
            myForm.append('Vector', document.getElementById('id_vector_numero').value)
            myForm.append('Region', document.getElementById('id_region').value)
            break
        case 6:
            myForm.append('Id_Marca_Io', document.getElementById('id_marca_vehiculo').value)
            myForm.append('Marca', document.getElementById('id_marca').value)
            break
        case 7:
            myForm.append('Id_Tipo_Vehiculo', document.getElementById('id_tipo_veh').value)
            myForm.append('Valor_Tipo', document.getElementById('id_tipo_veh_desc').value)
            break
        case 8:
            myForm.append('Id_Submarca_Vehiculo', document.getElementById('id_submarca_veh').value)
            myForm.append('Valor_Submarca', document.getElementById('id_submarca_desc').value)
            break
        case 9:
            myForm.append('Id_colonia', document.getElementById('Id_colonia').value)
            myForm.append('tipo', document.getElementById('tipo').value)
            myForm.append('colonia', document.getElementById('colonia').value)
            break
        case 10:
            myForm.append('Id_calle', document.getElementById('Id_calle').value)
            myForm.append('Id_calle_desc', document.getElementById('Id_calle_desc').value)
            break
        case 11:
            myForm.append('Id_cp', document.getElementById('Id_cp').value.toUpperCase())
            myForm.append('Codigo_postal', document.getElementById('Codigo_postal').value.toUpperCase())
            myForm.append('Nombre', document.getElementById('Nombre').value.toUpperCase())
            break
        case 12:
            myForm.append('Id_clave', document.getElementById('id_clave').value.toUpperCase())
            myForm.append('Nombre_clave', document.getElementById('nombre_clave').value.toUpperCase())
            myForm.append('Ap_paterno_clave', document.getElementById('ap_paterno_clave').value.toUpperCase())
            myForm.append('Ap_materno_clave', document.getElementById('ap_materno_clave').value.toUpperCase())
            myForm.append('Clave', document.getElementById('clave').value.toUpperCase())
            break
        case 13:
            myForm.append('id_fuente', document.getElementById('id_fuente').value.toUpperCase())
            myForm.append('fuente', document.getElementById('fuente').value.toUpperCase())
            break
        case 14:
            myForm.append('id_area', document.getElementById('id_area').value.toUpperCase())
            myForm.append('area', document.getElementById('area').value.toUpperCase())
            break
        case 15:
            myForm.append('Id_Tipo_Violencia', document.getElementById('id_tipo_violencia').value)
            myForm.append('Tipo_Violencia', document.getElementById('id_tipo_violencia_valor').value)
            break
        case 16:
            myForm.append('Id_Dato', document.getElementById('Id_Dato_Indicativo').value)
            myForm.append('Indicativo', document.getElementById('Indicativo').value)
            break
        case 17:
            myForm.append('Id_Dato_Tipo', document.getElementById('Id_Dato_Tipo').value)
            myForm.append('Tipo', document.getElementById('Tipo').value)
            break
        case 18:
            myForm.append('Id_Dato', document.getElementById('Id_Dato').value)
            myForm.append('Calle', document.getElementById('Calle').value)
            myForm.append('Calle2', document.getElementById('Calle2').value)
            myForm.append('Info_Adicional', document.getElementById('Info_Adicional').value)
            myForm.append('CoordX', document.getElementById('CoordX').value)
            myForm.append('CoordY', document.getElementById('CoordY').value)
            break
    }
    fetch(base_url_js + 'Catalogos/sendFormFetch', {
            method: 'POST',
            body: myForm
        })
        .then(function(response) {
            if (response.ok) {
                return response.json()
            } else {
                throw "Error en la llamada Ajax";
            }
        })
        .then(function(myJson) {
            console.log(myJson)
            if (myJson == 'Success') {
                alert("Consulta realizada correctamente")
                document.location.reload()
            } else {
                alert(myJson)
            }
        })
        .catch(function(error) {
            console.log("Error desde Catch _  " + error)
        })
}
const catalogocuervov =  async () => {
    //console.log('entro a sacar catalogo')
    try {
        const response = await fetch(base_url_js + 'Catalogos/getCatalogoPlacaNip', {
            method: 'POST',
            mode: 'cors' ,
        });
        const data = await response.json();
        //console.log('entro a sacar catalogo con data')
        return data;
    } catch (error) {
        console.log(error);
    }
}

const catalogocuervop =  async () => {
    //console.log('entro a sacar catalogo')
    try {
        const response = await fetch(base_url_js + 'Catalogos/getCatalogoPersonas', {
            method: 'POST',
            mode: 'cors' ,
        });
        const data = await response.json();
        //console.log('entro a sacar catalogo con data')
        return data;
    } catch (error) {
        console.log(error);
    }
}

const validarPlacaNip = async (placainput, nipinput) => {
    // console.log('entro a validar placa nip')
    // console.log(placainput,nipinput)
    let noEncontro = {id_dato: 0, valor: true };
    let catalogocuervovdata;
    catalogocuervovdata = await catalogocuervov()
    console.log('DESDE FUNCION VALIDACION',catalogocuervovdata);
    for(let i =0; i<catalogocuervovdata.length; i++){
        if(catalogocuervovdata[i].placa.toLowerCase() === placainput.toLowerCase() && catalogocuervovdata[i].nip.toLowerCase() === nipinput.toLowerCase()){
            noEncontro.valor = false;
            noEncontro.id_dato = catalogocuervovdata[i].id_dato;
           break;
        }
    }
    // console.log('salgo de validad placa nip')
    return noEncontro;
}
const validarPersona = async (nombreinput, appaternoinput, apmaternoinput) => {
    // console.log('entro a validar placa nip')
    // console.log(placainput,nipinput)
    let noEncontro = {id_dato: 0, valor: true };
    let catalogocuervopdata;
    catalogocuervopdata = await catalogocuervop()
    console.log('DESDE FUNCION VALIDACION',catalogocuervopdata);
    for(let i =0; i<catalogocuervopdata.length; i++){
        if(catalogocuervopdata[i].Nombre.toLowerCase() === nombreinput.toLowerCase() && catalogocuervopdata[i].Ap_Paterno.toLowerCase() === appaternoinput.toLowerCase() && catalogocuervopdata[i].Ap_Materno.toLowerCase() === apmaternoinput.toLowerCase()){
            noEncontro.valor = false;
            noEncontro.id_dato = catalogocuervopdata[i].id_dato;
           break;
        }
    }
    // console.log('salgo de validad placa nip')
    return noEncontro;
}