/*-----------------------------------------------ESTE ARCHIVO DE JAVASCRIPT ES PARA OBTENER LOS DATOS DE LAS ENTREVISTAS DEL EVENTO------------------------------------------------------------------------*/
document.addEventListener('DOMContentLoaded', async () => {
    caso = getEventotoSearch();
    await GetStatusTareas(caso)
    Entrevistas = await getEntrevistas(caso);
    if(Entrevistas!=undefined){
        Entrevistas.forEach(entrevista => insertNewRowTablaEntrevista(entrevista));
    }
});

const getEntrevistas = async (caso) => { //Funcion que realizar peticion para obtener los datos de las entrevistas del evento
    try {
        var myFormData = new FormData();
        myFormData.append('Folio_infra',caso)
        const response = await fetch(base_url_js + 'GestorCasos/getEntrevistas', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}

const insertNewRowTablaEntrevista= ({ procedencia,entrevista,entrevistado,entrevistador,edad_entrevistado,telefono_entrevistado,fecha_entrevista,hora_entrevista,Capturo,Ultima_Actualizacion}) => {//Funcion que inserta los datos obtenidos del evento en la tabla de entrevistas 
    const table = document.getElementById('entrevistasTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    //Empieza generar la tabla de vizualizacion de entrevistas
    newRow.insertCell(0).innerHTML = procedencia.toUpperCase();
    newRow.insertCell(1).innerHTML = entrevista.toUpperCase();
    newRow.insertCell(2).innerHTML = entrevistado.toUpperCase();
    newRow.insertCell(3).innerHTML = entrevistador.toUpperCase();
    newRow.insertCell(4).innerHTML = edad_entrevistado;
    newRow.insertCell(5).innerHTML = telefono_entrevistado;
    newRow.insertCell(6).innerHTML = fecha_entrevista;
    newRow.insertCell(7).innerHTML = hora_entrevista;
    newRow.insertCell(8).innerHTML = Capturo;
    newRow.insertCell(9).innerHTML = `<button type="button" class="btn btn-add " onclick="editEntrevistas(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <div class="mt-1 px-1"></div>
                                    <button type="button" class="btn btn-ssc" value="-" onclick="deleteEntrevista(this)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
    newRow.insertCell(10).innerHTML = Ultima_Actualizacion;
    newRow.cells[10].style.display = "none";
}

const refrescarDOMEntrevistas = async()=>{
    let aux = document.getElementById('countEntrevistas').rows.length+1
    for(let i = 1; i < aux; i++){
        document.getElementById('entrevistasTable').deleteRow(1);
    }
    caso = getEventotoSearch();
    Entrevistas = await getEntrevistas(caso);
    if(Entrevistas!=undefined){
        Entrevistas.forEach(entrevista => insertNewRowTablaEntrevista(entrevista));
    }
    
}