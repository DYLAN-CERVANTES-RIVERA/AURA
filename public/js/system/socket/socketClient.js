// Conexión al servidor
console.log('cliente de sockets')
///console.log(ECMAScript.version)
const socket= io.connect('http://172.18.10.184:8181',{'forceNew ': true});
var myformSocket = new FormData()

socket.on('nueva_insercion', (data) => {
    // Aquí puedes procesar la notificación y mostrarla en tu panel
    console.log('Nueva inserción detectada:', data);
    // Por ejemplo, puedes actualizar una lista de notificaciones en la interfaz de usuario
  });