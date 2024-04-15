var checkbox = document.getElementById('check_pass');
var contrasena = document.getElementById('contrasena')
checkbox.addEventListener('change', function() {
    if (this.checked) {
        contrasena.type = 'text'
    } else {
        contrasena.type = 'password'
    }

});