const getVehiculos = async (Id_Seguimiento) => { //Funcion que realizar peticion para obtener los datos de las entrevistas del evento
    try {
        myFormData.append('Id_Seguimiento',Id_Seguimiento)
        const response = await fetch(base_url_js + 'Seguimientos/getVehiculos', {
            method: 'POST',
            body: myFormData
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
const InsertVistaVehiculos= async({Id_Vehiculo,Id_Seguimiento,Placas,Marca,Submarca,Color,Modelo,Nombre_Propietario,Nivs,InfoPlaca,Capturo,Foto,Img_64},Vehiculo)=>{
   let pathImagesVehiculos =base_url_js+'public/files/Seguimientos/'+Id_Seguimiento+'/Vehiculos/';
    
    let scr='';
    if(Foto!='SD'){
        let ruta = pathImagesVehiculos+Foto;
        let ban = await imageExists(ruta);
        ruta = ruta+'?nocache='+getRandomInt(50)
        if(ban==true){
            scr=ruta;
        }else{
            if(Img_64!='SD'){
                scr=Img_64;
            }
        }
    }
    let main = document.getElementById('datos_Vehiculos');
    createElement('div', {id: "Vehiculo"+Vehiculo, className: 'form-group'}, [],main);
    document.getElementById("Vehiculo"+Vehiculo).innerHTML=`
        <h5 class="titulo-azul mt-3">Vehiculo ${Vehiculo}</h5>
        <div class="row">
            <div class=" row col-lg-9">
                <div class="col-lg-8">
                    <span class="span_rem">Placas: </span>
                    <span class="span_rem_ans" >${Placas}</span>
                </div>
                <div class="col-lg-4">
                    <span class="span_rem">Marca: </span>
                    <span class="span_rem_ans" >${Marca}</span>
                </div>
                <div class="col-lg-4">
                    <span class="span_rem">Submarca: </span>
                    <span class="span_rem_ans" >${Submarca}</span>
                </div>
                <div class="col-lg-4">
                    <span class="span_rem">Color: </span>
                    <span class="span_rem_ans" >${Color}</span>
                </div>
                <div class="col-lg-4">
                    <span class="span_rem">Modelo: </span>
                    <span class="span_rem_ans" >${Modelo}</span>
                </div>
                <div class="col-lg-6">
                    <span class="span_rem">Nombre_Propietario: </span>
                    <span class="span_rem_ans" >${Nombre_Propietario}</span>
                </div>
                <div class="col-lg-6">
                    <span class="span_rem">Nivs: </span>
                    <span class="span_rem_ans" >${Nivs}</span>
                </div>
                <div class="col-lg-6">
                    <span class="span_rem">InfoPlaca: </span>
                    <span class="span_rem_ans" >${InfoPlaca}</span>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="col-lg-12">
                    <span class="span_rem">Capturo: </span>
                    <span class="span_rem_ans" >${Capturo}</span>
                </div>
                <img name="nor" src="${scr}"  width="175"  data-toggle="modal" data-target="#ModalCenterVehiculo${Vehiculo}">
                <div class="modal fade " id="ModalCenterVehiculo${Vehiculo}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <img name="nor" src="${scr}"  style="width:1200px; position:relative; top:0px; left:0px; border:opx; overflow:hidden; display:block"data-toggle="modal" data-target="#exampleModalCenter">
                    </div>
                </div>  
            </div>
        </div>
        <hr>
    `;
    let contenedorhtml=document.getElementById("Vehiculo"+Vehiculo)
    let Antecedentes = await getAntecedentesOneRegister(Id_Vehiculo,'VEHICULO');
    let Domicilios = await getDomiciliosOneRegister(Id_Vehiculo,'VEHICULO');
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
            Capturo : Domicilio.Capturo
        }
        await InsertVistaDomicilio(formDataDomicilio,contenedorhtml);//Inserta todos los involucrados del evento
    }
    for await(let Antecedente of Antecedentes){
        let formDataAntecedente = {
            Id_Antecedente : Antecedente.Id_Antecedente,
            Id_Dato: Antecedente.Id_Dato,
            Tipo_Entidad : Antecedente.Tipo_Entidad,
            Descripcion_Antecedente : Antecedente.Descripcion_Antecedente,
            Fecha_Antecedente : Antecedente.Fecha_Antecedente,
            Capturo : Antecedente.Capturo
        }
        await InsertVistaAntecedente(formDataAntecedente,contenedorhtml);//Inserta todos domicilios de los vehiculos del seguimiento
    }
}