/*-----------------------------------------------ESTE ARCHIVO DE JAVASCRIPT ES PARA LA FUNCIONALIDADES DE LA VISTA DEL GESTOR DE CASOS -------------------------------------------------------------------------*/
$(function() {
    $('[data-toggle="tooltip"]').tooltip()
})

$(function() {
    $('[data-toggle="popover"]').popover({
        html: true,
    })
})

/*------------- FUNCIONALIDAD PARA LA BUSQUEDA POR TECLADO------*/
var search = document.getElementById('id_search')
var filtroActual = document.getElementById('filtroActual')
var search_button = document.getElementById('search_button')


search_button.addEventListener('click', buscarEventoCad)

function buscarEventoCad(e) {
	getDataGraficas();
}

function checarCadena(e) {
	if (search.value == "") {
		buscarEventoCad()
	}
}

/*-------------------Función para aplicar rangos de fechas---------------*/
function aplicarRangos(){
	//obtener cada valor de la fecha
	var rango_inicio = document.getElementById('id_date_1').value
	var rango_fin = document.getElementById('id_date_2').value
	//comprobar si ya seleccionó una fecha
	if (rango_inicio != '' && rango_fin != '') {
		let fecha1 = new Date(rango_inicio);
		let fecha2 = new Date(rango_fin)

		let resta = fecha2.getTime() - fecha1.getTime()
		if(resta >= 0){	//comprobar si los rangos de fechas son correctos
			///console.log('correcto')
			document.getElementById('form_rangos').submit()
		}
		else{
		//caso de elegir rangos erroneos
			alert("Elige intervalos correctos")
		} 
	}
	else {
	//caso de no ingresar aún nada
		alert("Selecciona primero los rangos")
	}
}

