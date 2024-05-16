// Conexión al servidor
console.log('cliente de sockets')
///console.log(ECMAScript.version)
const socket = io('http://172.18.10.227:3333');
var myformSocket = new FormData()
socket.on("connect", async() => {
    console.log({msg: 'conectado con el socket server'}) 
});


socket.on('GetInfo_Folio',function(data){
    ///console.log(data)
    
    if(Number(data)){
        myformSocket.append('Folio_infra',data) 
        fetch(base_url_js+'GestorCasos/GetInfo_Evento', {
            method: 'POST',
            body: myformSocket
        })
        .then(function(response){
            if (response.ok) {
                console.log(response);
                socket.emit('Infopeticion',response);//emitimos al sever los datos que requiere
            }
            else{
                throw "Error en fetch"
            }
        })
        .catch(function(error){
            console.log("catch: "+error)
        })
    }else{
        ///console.log('Ingrese de manera correcta el folio');
        socket.emit('InfopeticionMal','Ingrese de manera correcta el folio');//emitimos a el socket un error en la entrada
    }

});