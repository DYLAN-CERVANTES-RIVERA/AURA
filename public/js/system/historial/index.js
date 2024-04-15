/*-----------------------------------------------ESTE ARCHIVO DE JAVASCRIPT ES PARA LA FUNCIONALIDADES DE LA VISTA DEL HISTORIAL -------------------------------------------------------------------------*/
$(function() {
    $('[data-toggle="tooltip"]').tooltip();
})
$(function() {
    $('[data-toggle="popover"]').popover({
        html: true,
    })
})

/*------------- FUNCIONALIDAD PARA LA BUSQUEDA POR TECLADO------*/
var search = document.getElementById('id_search'),
   filtroActual = document.getElementById('filtroActual'),
    search_button = document.getElementById('search_button');

search_button.addEventListener('click',buscarHistorialesCad);

function buscarHistorialesCad(e){
    let  myform = new FormData();
    myform.append('cadena', search.value);
    myform.append('filtroActual', filtroActual.value);

    fetch(base_url_js+'Historiales/buscarPorCadena',{
        method: 'POST',
        body: myform
    })
    .then(res=>res.json())
    .then(data=>{
        if(!(typeof(data) == 'string')){
            document.getElementById('id_tbody').innerHTML = data.infoTable.body;
            document.getElementById('id_thead').innerHTML = data.infoTable.header;
            document.getElementById('id_pagination').innerHTML = data.links;
            document.getElementById('id_link_excel').href = data.export_links.excel;
            document.getElementById('id_link_pdf').href = data.export_links.pdf;
            document.getElementById('id_total_rows').innerHTML = data.total_rows;
            document.getElementById('id_dropdownColumns').innerHTML = data.dropdownColumns;
            const columnsNames3 = document.querySelectorAll('th');
            columnsNames3.forEach((element,index,array)=>{
                if(element.className.match(/column.*/)){
                    hideShowColumn(element.className);
                }
            })
            $('[data-toggle="tooltip"]').tooltip();
            $('[data-toggle="popover"]').popover();
        }else{
            console.log(data);
        }
    })	
    .catch(function(error) {
		console.log("Error desde Catch _  " + error)
	})
}
function checarCadena(e){
    if (search.value == "") {
        buscarHistorialesCad()
    }
}
/*----------------------Función para aplicar rangos de fechas-------------------------*/
function aplicarRangos(){
    var rango_inicio = document.getElementById('id_date_1').value,//obtener cada valor de la fecha
        rango_fin = document.getElementById('id_date_2').value;
    
    if (rango_inicio != '' && rango_fin != '') {//comprobar si ya seleccionó una fecha
        let fecha1 = new Date(rango_inicio);
        let fecha2 = new Date(rango_fin)

        let resta = fecha2.getTime() - fecha1.getTime()
        if(resta >= 0){//comprobar si los rangos de fechas son correctos
            document.getElementById('form_rangos').submit()
        }
        else{//caso de elegir rangos erroneos
            alert("Elige intervalos correctos")
        } 
    }
    else {//caso de no ingresar aún nada
        alert("Selecciona primero los rangos")
    }
}
/*--------------------Mostrar u ocultar columnas-------------------*/
function hideShowColumn (col_name){
    let myform = new FormData();//form para actualizar la session variable
    myform.append('columnName',col_name);//se asigna el nombre de la columna a cambiar

    var checkbox_val = document.getElementById(col_name).value;
    if(checkbox_val == 'hide'){
        var all_col = document.getElementsByClassName(col_name);
        for(let i=0;i<all_col.length;i++){
            all_col[i].style.display="none";
        }

        document.getElementById(col_name).value = 'show';
        myform.append('valueColumn','hide');//se asigna la acción (hide or show)
    }else{
        var all_col = document.getElementsByClassName(col_name);
        for(let i=0;i<all_col.length;i++){
            all_col[i].style.display='table-cell';
        }

        document.getElementById(col_name).value="hide";
        myform.append('valueColumn','show');//se asigna la acción (hide or show)
    }
    //se actualiza la session var para las columnas cambiadas
    fetch(base_url_js+'Historiales/setColumnFetch',{
        method: 'POST',
        body: myform
    })
    .then((res)=>{
        if(res.ok){
            return res.json();
        }else{
            throw 'Error en fetch'
        }
    })
    
}

function hideShowAll(){
    const valueCheckAll = document.getElementById('checkAll').value;//valor actual del check todos
    var checkBoxes = document.querySelectorAll('.checkColumns');//se obtiene los checks de las columnas del filtro actual
	//se convierte todo a hide o todo a show ademas de desmarcar o marcar todos los checked

    console.log(valueCheckAll);
    if (valueCheckAll === 'hide') {
        checkBoxes.forEach(function(element,index,array){
            if (element.value = 'show') {
                element.value = 'hide'
                element.checked = false
            }
        })
        document.getElementById('checkAll').value = 'show'
    }
    else{
        checkBoxes.forEach(function(element,index,array){
            if (element.value = 'hide') {
                element.value = 'show'
                element.checked = true
            }
        })
        document.getElementById('checkAll').value = 'hide'
    }
    
   	//se procede a mostrar u ocultar todo
	var columnsNames = document.querySelectorAll('th')
	columnsNames.forEach(function(element, index, array){
		if (element.className.match(/column.*/))
			hideShowColumn(element.className)
	  });
}
var columnsNames2 = document.querySelectorAll('th')
	columnsNames2.forEach(function(element, index, array){
		if (element.className.match(/column.*/))
			hideShowColumn(element.className)
	  });
