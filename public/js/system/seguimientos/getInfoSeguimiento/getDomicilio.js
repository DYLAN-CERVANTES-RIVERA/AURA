const getDomicilios = async (Ids_Datos,Tipo_Entidad) => { //Funcion que realizar peticion para obtener los datos de los domicilios de las personas o vehiculos del seguimiento
    try {
        myFormData.append('Ids_Datos',JSON.stringify(Ids_Datos))
        myFormData.append('Tipo_Entidad',Tipo_Entidad)
        const response = await fetch(base_url_js + 'Seguimientos/getDomicilios', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const InsertgetDomicilio= async({Id_Domicilio,Id_Dato,Tipo_Entidad,Estatus,Colonia,Calle,Calle2,NumExt,NumInt,CP,CoordY,CoordX,Capturo,Observaciones_Ubicacion,Estado,Municipio,Foraneo})=>{//Funcion que inserta los datos obtenidos en la tabla de domicilios
    let table = document.getElementById('DomiciliosTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    if(Tipo_Entidad=='PERSONA'){
        if(Id_Dato!=null){
            document.getElementById("PersonaSelect").value= Id_Dato;  
            newRow.insertCell(0).innerHTML =  document.getElementById("PersonaSelect").options[document.getElementById("PersonaSelect").selectedIndex].text;
            document.getElementById("PersonaSelect").value='SD';
        }

    }else{
        if(Id_Dato!=null){
            document.getElementsByName('tipo_dato')[1].checked=true;
            await RecargaSelect() 
            document.getElementById("VehiculoSelect").value= Id_Dato;
            newRow.insertCell(0).innerHTML =  document.getElementById("VehiculoSelect").options[document.getElementById("VehiculoSelect").selectedIndex].text;
            document.getElementById("VehiculoSelect").value='SD';
            document.getElementsByName('tipo_dato')[0].checked=true;
            document.getElementById('Persona_Select').classList.remove('mi_hide');
            document.getElementById('Vehiculo_Select').classList.add('mi_hide');    
        }
    }
    newRow.insertCell(1).innerHTML = Id_Domicilio;
    if(Tipo_Entidad=='PERSONA'){
        newRow.insertCell(2).innerHTML =Id_Dato;
        newRow.insertCell(3).innerHTML ='PERSONA';
         
    }else{
        newRow.insertCell(2).innerHTML =Id_Dato
        newRow.insertCell(3).innerHTML ='VEHICULO';
    }

    newRow.insertCell(4).innerHTML =Estatus;
    newRow.insertCell(5).innerHTML =Colonia;
    newRow.insertCell(6).innerHTML =Calle;
    newRow.insertCell(7).innerHTML =Calle2;
    newRow.insertCell(8).innerHTML =NumExt;
    newRow.insertCell(9).innerHTML =NumInt;
    newRow.insertCell(10).innerHTML =CP;
    newRow.insertCell(11).innerHTML =CoordY;
    newRow.insertCell(12).innerHTML =CoordX;
    newRow.insertCell(13).innerHTML =Estado;
    newRow.insertCell(14).innerHTML =Municipio;
    newRow.insertCell(15).innerHTML =Foraneo;
    newRow.insertCell(16).innerHTML =Observaciones_Ubicacion;
    newRow.insertCell(17).innerHTML =Capturo;
    newRow.insertCell(18).innerHTML =`<button type="button" class="btn btn-add" onclick="editDomicilio(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc" value="-" onclick="deleteRowDomicilio(this,DomiciliosTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
    newRow.cells[1].style.display = "none";
    newRow.cells[2].style.display = "none";

}
/*---------------------FUNCIONES DE REFRESCO--------------------------- */
const RecargaDatosDomicilios = async()=>{//Funcion que actualiza la vista de la tabla domicilios cada vez que se guarden o eliminen datos
    await dropTablaContentDomicilios();
    let i=0,j=0;
    let consultaPersonas=[];
    let consultaVehiculos=[];
    let Personas = await getPersonas(Seguimiento);
    let Vehiculos = await getVehiculos(Seguimiento);
    for await(Persona of Personas){
        consultaPersonas[i]=Persona.Id_Persona;
        i++;
    }
    for await(Vehiculo of Vehiculos){
        consultaVehiculos[j]=Vehiculo.Id_Vehiculo;
        j++;
    }
    let DomiciliosP=await getDomicilios(consultaPersonas,'PERSONA');
    let DomiciliosV=await getDomicilios(consultaVehiculos,'VEHICULO');
    for ( i = 0; i < DomiciliosP.length; i++) {
        if(DomiciliosP[i].length>0){
            let Domicilios = DomiciliosP[i];
            for await(Domicilio of Domicilios){
                let formDataDomicilio = {
                    Id_Domicilio : Domicilio.Id_Domicilio,
                    Id_Dato: Domicilio.Id_Dato,
                    Tipo_Entidad : Domicilio.Tipo_Entidad,
                    Estatus : Domicilio.Estatus,
                    Colonia : Domicilio.Colonia,
                    Calle : Domicilio.Calle,
                    Calle2 : Domicilio.Calle2,
                    NumExt : Domicilio.NumExt,
                    NumInt : Domicilio.NumInt,
                    CP : Domicilio.CP,
                    CoordY : Domicilio.CoordY,
                    CoordX : Domicilio.CoordX,
                    Capturo : Domicilio.Capturo,
                    Observaciones_Ubicacion : Domicilio.Observaciones_Ubicacion,
                    Estado : Domicilio.Estado,
                    Municipio : Domicilio.Municipio,
                    Foraneo : Domicilio.Foraneo
                }
                //console.log(formDataDomicilio)
               await InsertgetDomicilio(formDataDomicilio);//Inserta todos los domicilios de las personas
            }
        }
    }
    for ( j = 0; j < DomiciliosV.length; j++) {
        if(DomiciliosV[j].length>0){
            let Domicilios = DomiciliosV[j];
            for await(Domicilio of Domicilios){
                let formDataDomicilio = {
                    Id_Domicilio : Domicilio.Id_Domicilio,
                    Id_Dato: Domicilio.Id_Dato,
                    Tipo_Entidad : Domicilio.Tipo_Entidad,
                    Estatus : Domicilio.Estatus,
                    Colonia : Domicilio.Colonia,
                    Calle : Domicilio.Calle,
                    Calle2 : Domicilio.Calle2,
                    NumExt : Domicilio.NumExt,
                    NumInt : Domicilio.NumInt,
                    CP : Domicilio.CP,
                    CoordY : Domicilio.CoordY,
                    CoordX : Domicilio.CoordX,
                    Capturo : Domicilio.Capturo,
                    Observaciones_Ubicacion : Domicilio.Observaciones_Ubicacion,
                    Estado : Domicilio.Estado,
                    Municipio : Domicilio.Municipio,
                    Foraneo : Domicilio.Foraneo
                }
                //console.log(formDataDomicilio)
                await InsertgetDomicilio(formDataDomicilio);//Inserta todos los domicilios de los vehiculos
            }
        }
    }
}
const dropTablaContentDomicilios = async () => {//VACIA EL CONTENIDO DE LA TABLA DOMICILIOS SIN TOCAR EL ENCABEZADO
    ban= true;
    table = document.getElementById('DomiciliosTable');
    aux=document.getElementById('contarDomicilios').rows.length;
    for(let i = 1; i < aux+1; i++){
        table.deleteRow(1);
    }
    return ban;
}
/*----------------FUNCION PARA TRAER EL CATALOGO DE MUNICIPIOS------------------------*/
const Estado = document.getElementById('Estado');
Estado.addEventListener('change', () => { 
    document.getElementById('Municipio').value=''
});
const Municipio=document.getElementById('Municipio');
Municipio.addEventListener('input', () => { 
        input_elegido=document.getElementById('Municipio')
        termino=(document.getElementById('Municipio')).value
        estado=(document.getElementById('Estado')).value
   
    const myFormData_muni =  new FormData();
    myFormData_muni.append('termino', termino)
    myFormData_muni.append('estado', estado)
    fetch(base_url_js + 'Seguimientos/getMunicipios', {
            method: 'POST',
            body: myFormData_muni
    })
    .then(res => res.json())
    .then(data => {
        const arr = data.map( r => ({ label: `${r.Municipio}`, value: `${r.Municipio}` }))
        autocomplete({
            input: input_elegido,
            fetch: function(text, update) {
                text = text.toLowerCase();
                const suggestions = arr.filter(n => n.label.toLowerCase().includes(text))
                update(suggestions);
            },
            onSelect: function(item) {
                input_elegido.value = item.label;
            }
        }); 
    })
    .catch(err => alert(`Ha ocurrido un error al obtener las colonias.\nCódigo de error: ${ err }`))  
});