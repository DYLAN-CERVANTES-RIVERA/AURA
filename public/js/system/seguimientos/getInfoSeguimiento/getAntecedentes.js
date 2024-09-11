const getAntecedentes = async (Ids_Datos,Tipo_Entidad) => { //Funcion que realizar peticion para obtener los datos de los antecedentes
    try {
        myFormData.append('Ids_Datos',JSON.stringify(Ids_Datos))
        myFormData.append('Tipo_Entidad',Tipo_Entidad)
        const response = await fetch(base_url_js + 'Seguimientos/getAntecedentes', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const dropTablaContentAntecedentes = async () => {//VACIA EL CONTENIDO DE LA TABLA ANTECEDENTES SIN TOCAR EL ENCABEZADO
    ban= true;
    table = document.getElementById('AntecendentesTable');
    aux=document.getElementById('contarAntecedentes').rows.length;
    for(let i = 1; i < aux+1; i++){
        table.deleteRow(1);
    }
    return ban;
}
/*---------------------FUNCIONES DE REFRESCO--------------------------- */
const RecargaDatosAntecedentes = async()=>{//Funcion que actualiza la vista de la tabla antecedentes cada vez que se guarden o eliminen datos
    await dropTablaContentAntecedentes();
    let i=0,j=0;
    let consultaPersonas=[];
    let consultaVehiculos=[];
    let Personas = await getPersonas(Seguimiento);
    let Vehiculos = await getVehiculos(Seguimiento);
    if(document.getElementById('Question2').checked){
        let hijos = await buscaHijos(Seguimiento); 
        for await(let hijo of hijos){
            
            let PersonasJH = await getPersonas(hijo.Id_Seguimiento);
            let VehiculosJH = await getVehiculos(hijo.Id_Seguimiento);
            if(PersonasJH.length>0){
                for await(let PersonaJH of PersonasJH){
                    Personas.push(PersonaJH); 
                }
            }
            if(VehiculosJH.length>0){
                for await(let VehiculoJH of VehiculosJH){
                    Vehiculos.push(VehiculoJH); 
                }
            }
        }
        //console.log(Personas)   
        //console.log(Vehiculos)       
    }  
    for await(Persona of Personas){
        consultaPersonas[i]=Persona.Id_Persona;
        i++;
    }
    for await(Vehiculo of Vehiculos){
        consultaVehiculos[j]=Vehiculo.Id_Vehiculo;
        j++;
    }
    let AntecedentesP=await getAntecedentes(consultaPersonas,'PERSONA');
    let AntecedentesV=await getAntecedentes(consultaVehiculos,'VEHICULO');
    for ( i = 0; i < AntecedentesP.length; i++) {
        if(AntecedentesP[i].length>0){
            let Antecedentes = AntecedentesP[i];
            for await(let Antecedente of Antecedentes){
                let formDataAntecedente = {
                    Id_Antecedente : Antecedente.Id_Antecedente,
                    Id_Dato: Antecedente.Id_Dato,
                    Tipo_Entidad : Antecedente.Tipo_Entidad,
                    Descripcion_Antecedente : Antecedente.Descripcion_Antecedente,
                    Fecha_Antecedente : Antecedente.Fecha_Antecedente,
                    Capturo : Antecedente.Capturo
                }
               await InsertgetAntecedente(formDataAntecedente);//Inserta todos los antecedentes de las personas del seguimiento
            }
        }
    }
    for ( j = 0; j < AntecedentesV.length; j++) {
        if(AntecedentesV[j].length>0){
            let Antecedentes = AntecedentesV[j];
            for await(let Antecedente of Antecedentes){
                let formDataAntecedente = {
                    Id_Antecedente : Antecedente.Id_Antecedente,
                    Id_Dato: Antecedente.Id_Dato,
                    Tipo_Entidad : Antecedente.Tipo_Entidad,
                    Descripcion_Antecedente : Antecedente.Descripcion_Antecedente,
                    Fecha_Antecedente : Antecedente.Fecha_Antecedente,
                    Capturo : Antecedente.Capturo
                }
                await InsertgetAntecedente(formDataAntecedente);//Inserta todos antecedentes de los vehiculos del seguimiento
            }
        }
    }

}
const InsertgetAntecedente= async({Id_Antecedente,Id_Dato,Tipo_Entidad,Descripcion_Antecedente,Fecha_Antecedente,Capturo})=>{//Funcion que inserta los datos obtenidos en la tabla de antecedentes
    let table = document.getElementById('AntecendentesTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(table.length);
    if(Tipo_Entidad=='PERSONA'){
        if(Id_Dato!=null){
            document.getElementById("PersonaSelectAntecedente").value= Id_Dato;  
            newRow.insertCell(0).innerHTML =  document.getElementById("PersonaSelectAntecedente").options[document.getElementById("PersonaSelectAntecedente").selectedIndex].text;
            document.getElementById("PersonaSelectAntecedente").value='SD';
        }

    }else{
        if(Id_Dato!=null){
            document.getElementsByName('tipo_dato_antecendente')[1].checked=true;
            await RecargaSelectAntecedente() 
            document.getElementById("VehiculoSelectAntecedente").value= Id_Dato;
            newRow.insertCell(0).innerHTML =  document.getElementById("VehiculoSelectAntecedente").options[document.getElementById("VehiculoSelectAntecedente").selectedIndex].text;
            document.getElementById("VehiculoSelectAntecedente").value='SD';
            document.getElementsByName('tipo_dato_antecendente')[0].checked=true;
            document.getElementById('Persona_Select_Antecedente').classList.remove('mi_hide');
            document.getElementById('Vehiculo_Select_Antecedente').classList.add('mi_hide');
        }
    }
    newRow.insertCell(1).innerHTML = Id_Antecedente;
    if(Tipo_Entidad=='PERSONA'){
        newRow.insertCell(2).innerHTML =Id_Dato;
        newRow.insertCell(3).innerHTML ='PERSONA';
    }else{
        newRow.insertCell(2).innerHTML =Id_Dato
        newRow.insertCell(3).innerHTML ='VEHICULO';
    }
    newRow.insertCell(4).innerHTML =Descripcion_Antecedente;
    newRow.insertCell(5).innerHTML =Fecha_Antecedente;
    newRow.insertCell(6).innerHTML =Capturo;
    newRow.insertCell(7).innerHTML =`<button type="button" class="btn btn-add" onclick="editAntecedente(this)"> 
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" class="btn btn-ssc" value="-" onclick="deleteRowAntecedente(this,AntecendentesTable)">
                                        <i class="material-icons">delete</i>
                                    </button>`;
    newRow.cells[1].style.display = "none";
    newRow.cells[2].style.display = "none";
}