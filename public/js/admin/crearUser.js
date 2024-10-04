//condiciones para permisos
var passButton  =   document.getElementById('id_pass_button')
var inputPass   =   document.getElementById('id_input_pass') 
var mySubmit    =   document.getElementById('mySubmit')
var myForm      =   document.getElementById('id_form')
document.getElementById('error_img1').style.display = "none"
function viewPassword(){
    if (inputPass.type == 'text') {
        inputPass.type = 'password'
    }
    else{
        inputPass.type = 'text'
    }
    
}

passButton.addEventListener('click',viewPassword)
function valideMultiples(evt) {//FUNCION QUE VALIDA LA INSERCION DE SOLO NUMEROS 
    var code = (evt.which) ? evt.which : evt.keyCode;
    let codigos = [8]//TECLA DELETE
    if ((code >= 65 && code <= 90)||(code >= 97 && code <= 122)||(code >= 48 && code <= 57)) { //TECLAS DE NUMEROS
        return true;
    } else { // OTRAS TECLAS.
        let codigo_valido = codigos.find(element=>element==code);
        if(codigo_valido){
            return true;
        }else{
            return false;
        }
    }
}


function disablePermisos(){
    var permisos = document.getElementsByClassName('checkPermisos');
        permisos = Array.prototype.slice.call( permisos, 0 );
    
    if (document.getElementById('Modo_Admin').checked) 
        permisos.forEach(element => {element.disabled = true});
    
    else
        permisos.forEach(element => {element.disabled = false});    
}

disablePermisos()

//procesamiento de la imagen a subir
var img_1 = document.getElementById("id_foto_file")

var p_1 = document.getElementById('preview_1')

p_1.style.display = "none"

img_1.onchange = function(e) {
    let formatosImg = 'image/png image/jpeg image/jpg'

    //console.log(img_1.files[0])
    //console.log(img_1.files[0].type)
    let reader = new FileReader();
    if (typeof img_1.files[0] !== 'undefined') {
        if (img_1.files[0].size <= 8000000) { //size max 8MB
            if(formatosImg.includes(img_1.files[0].type+"")){
                document.getElementById('error_img1').style.display = "none"
                reader.onload = function() {
                    let image = document.createElement('img');

                    document.getElementById('label_foto_file').textContent = e.target.files[0].name

                    image.src = reader.result;
                    p_1.style.display = "block"
                    p_1.innerHTML = '';
                    p_1.append(image);
                    //alert('Tamaño: ' + img_1.files[0].size)
                };

                reader.readAsDataURL(e.target.files[0]);
            }
            else{
                delete img_1.files[0];
                p_1.style.display = "none"
                document.getElementById('error_img1').style.display = "block"
                img_1.value = ""
                document.getElementById('label_foto_file').textContent = "Subir imagen"
            }

        }
    } 
    else {
        delete img_1.files[0];
        p_1.style.display = "none"
        document.getElementById('error_img1').style.display = "block"
        img_1.value = ""
        document.getElementById('label_foto_file').textContent = "Subir imagen"
    }

}

/*JS para activar todos o ninguno de los permisos marcados*/


var all_seguimientos = document.getElementById('all_seguimientos')
var all_eventos = document.getElementById('all_eventos')
var all_entrevistas = document.getElementById('all_entrevistas')
var all_redes = document.getElementById('all_redes')
var all_puntos = document.getElementById('all_puntos')


all_seguimientos.addEventListener('change',change_all);
all_eventos.addEventListener('change',change_all);
all_entrevistas.addEventListener('change',change_all);
all_redes.addEventListener('change',change_all);
all_puntos.addEventListener('change',change_all);
function change_all(e){
    switch(e.target.id){
        
        case 'all_seguimientos':
            if (all_seguimientos.value === '1') {
                document.getElementById('S_Create').checked = true
                document.getElementById('S_Read').checked = true
                document.getElementById('S_Update').checked = true
                document.getElementById('S_Delete').checked = true
                all_seguimientos.value = '0'
            }
            else{
                document.getElementById('S_Create').checked = false
                document.getElementById('S_Read').checked = false
                document.getElementById('S_Update').checked = false
                document.getElementById('S_Delete').checked = false
                all_seguimientos.value = '1'
            }
        break
        case 'all_eventos':
            if (all_eventos.value === '1') {
                document.getElementById('E_Create').checked = true
                document.getElementById('E_Read').checked = true
                document.getElementById('E_Update').checked = true
                document.getElementById('E_Delete').checked = true
                all_eventos.value = '0'
            }
            else{
                document.getElementById('E_Create').checked = false
                document.getElementById('E_Read').checked = false
                document.getElementById('E_Update').checked = false
                document.getElementById('E_Delete').checked = false
                all_eventos.value = '1'
            }
        break
        case 'all_entrevistas':
            if (all_entrevistas.value === '1') {
                document.getElementById('Entrevista_Create').checked = true
                document.getElementById('Entrevista_Read').checked = true
                document.getElementById('Entrevista_Update').checked = true
                document.getElementById('Entrevista_Delete').checked = true
                all_entrevistas.value = '0'
            }
            else{
                document.getElementById('Entrevista_Create').checked = false
                document.getElementById('Entrevista_Read').checked = false
                document.getElementById('Entrevista_Update').checked = false
                document.getElementById('Entrevista_Delete').checked = false
                all_entrevistas.value = '1'
            }
        break
        case 'all_redes':
            if (all_redes.value === '1') {
                document.getElementById('Red_Create').checked = true
                document.getElementById('Red_Read').checked = true
                document.getElementById('Red_Update').checked = true
                document.getElementById('Red_Delete').checked = true
                all_redes.value = '0'
            }
            else{
                document.getElementById('Red_Create').checked = false
                document.getElementById('Red_Read').checked = false
                document.getElementById('Red_Update').checked = false
                document.getElementById('Red_Delete').checked = false
                all_redes.value = '1'
            }
        break
        case 'all_puntos':
            if (all_puntos.value === '1') {
                document.getElementById('Punto_Create').checked = true
                document.getElementById('Punto_Read').checked = true
                document.getElementById('Punto_Update').checked = true
                document.getElementById('Punto_Delete').checked = true
                all_puntos.value = '0'
            }
            else{
                document.getElementById('Punto_Create').checked = false
                document.getElementById('Punto_Read').checked = false
                document.getElementById('Punto_Update').checked = false
                document.getElementById('Punto_Delete').checked = false
                all_puntos.value = '1'
            }
        break
    }
}