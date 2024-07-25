/*----------------ESTE ARCHIVO DE JAVASCRIPT ES PARA LA FUNCIONALIDADES DE LAS GRAFICAS------------*/
const convertHexToRGBA = (hexCode, opacity) => {//convertir color hexadecimal a rgb con opacidad
    opacity = (opacity >= 0.0 && opacity <= 1.0) ? opacity : 1.0;

    let hex = hexCode.replace('#', '');

    if (hex.length === 3) {
        hex = `${hex[0]}${hex[0]}${hex[1]}${hex[1]}${hex[2]}${hex[2]}`;
    }

    const r = parseInt(hex.substring(0, 2), 16);
    const g = parseInt(hex.substring(2, 4), 16);
    const b = parseInt(hex.substring(4, 6), 16);

    return `rgba(${r},${g},${b},${opacity})`;
};

/**---------------MIS COLORES (para mantener un perfil)----------------------- */
const mis_colores = [
    // '#e57373',
    '#f06292',
    // '#ba68c8',
    '#9575cd',
    // '#7986cb',
    '#64b5f6',
    // '#4fc3f7',
    '#4dd0e1',
    // '#4db6ac',
    '#81c784',
    // '#aed581',
    '#dce775',
    // '#66bb6a',
    '#9ccc65',
    // '#d4e157',
    '#fff176',
    // '#ffd54f',
    '#ffb74d',
    // '#ff8a65',
    '#a1887f',
    // '#e0e0e0',
    '#90a4ae'
];
/**---------------MIS COLORES (para mantener un perfil)----------------------- */
const mis_colores2 = [
    '#64b5f6',
    // '#4fc3f7',
    '#81c784',
    // '#aed581',
    // '#e0e0e0',
    '#90a4ae',
    // '#ff8a65',
    '#a1887f',
    // '#7986cb',
    '#dce775',
    // '#e57373',
    '#f06292',
    // '#66bb6a',
    '#9ccc65',
    // '#d4e157',
    '#fff176',
    // '#ffd54f',
    '#4dd0e1',
    // '#4db6ac',
    '#ffb74d',
    // '#ba68c8',
    '#9575cd'
];
//------------------------VARIABLES DE CANVAS Y CHARTS---------------------------
let ctx1 = document.getElementById('id_grafica_1').getContext('2d');
let ctx2 = document.getElementById('id_grafica_2').getContext('2d');
let ctx3 = document.getElementById('id_grafica_3').getContext('2d');
let ctx4 = document.getElementById('id_grafica_4').getContext('2d');
let ctx5 = document.getElementById('id_grafica_5').getContext('2d');
let ctx6 = document.getElementById('id_grafica_6').getContext('2d');
let chart1 = new Chart(ctx1, {});
let chart2 = new Chart(ctx2, {});
let chart3 = new Chart(ctx3, {});
let chart4 = new Chart(ctx4, {});
let chart5 = new Chart(ctx5, {});
let chart6 = new Chart(ctx6, {});
//------------------------GENERACIÓN DE GRÁFICAS---------------------------

const mostrarGrafica = async(results) => {
    let mis_labels = [];
    let backColors = [];
    let borderColors = [];
    let miData = [];
    let totalZona = 0;

    chart1.destroy() //se destruye el anterior canvas para poner el nuevo
    results.forEach((element, index) => {
        mis_labels.push(element.Zona);
        miData.push(element.ZonaTotal);
        backColors.push(convertHexToRGBA(mis_colores[(index % mis_colores.length)], 0.3));
        borderColors.push(convertHexToRGBA(mis_colores[(index % mis_colores.length)], 1.0));
        totalZona += parseInt(element.ZonaTotal);

        /*setData.push({
            label: element.Zona,
            data: element.ZonaTotal,
            backgroundColor: convertHexToRGBA(mis_colores[(index % mis_colores.length)], 0.3),
            borderColor: convertHexToRGBA(mis_colores[(index % mis_colores.length)], 1.0),
            borderWidth: 2
        });*/
    });
    document.getElementById('id_total_grafica').innerHTML = totalZona;

    if (results.length > 0) {
        document.getElementById('id_sin_results_grafica').classList.add('mi_hide')
        chart1 = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: mis_labels,
                datasets: [
                    {
                    label: 'Eventos',
                    data: miData,
                    backgroundColor: backColors,
                    borderColor: borderColors,
                    borderWidth: 2
                    }
                ]
            },
            options: {
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'EVENTOS POR ZONAS'
                    } 
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }

                }
            }
        });
    } else {
        document.getElementById('id_sin_results_grafica').classList.remove('mi_hide')
    }
}

const mostrarGrafica2 = async(results) => {
    let mis_labels = [];
    let backColors = [];
    let borderColors = [];
    let miData = [];
    let total = 0;

    chart2.destroy() //se destruye el anterior canvas para poner el nuevo
    results.forEach((element, index) => {
        mis_labels.push(element.CSviolencia);
        miData.push(element.CSviolenciaTotal);
        backColors.push(convertHexToRGBA(mis_colores[index+1], 0.3));
        borderColors.push(convertHexToRGBA(mis_colores[index+1], 1.0));
        total += parseInt(element.CSviolenciaTotal);
    });
    document.getElementById('id_total_grafica').innerHTML = total;

    if (results.length > 0) {
        document.getElementById('id_sin_results_grafica').classList.add('mi_hide')
        chart2 = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: mis_labels,
                datasets: [{
                    label: 'Eventos',
                    data: miData,
                    backgroundColor: backColors,
                    borderColor: borderColors,
                    borderWidth: 2
                }]
            },
            options: {
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'EVENTOS POR TIPO DE VIOLENCIA'
                    } 
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    } else {
        document.getElementById('id_sin_results_grafica').classList.remove('mi_hide')
    }
}
const mostrarGrafica3 = async(results) => {
    let mis_labels = [];
    let backColors = [];
    let borderColors = [];
    let miData = [];
    let totalDelitos = 0;

    chart3.destroy() //se destruye el anterior canvas para poner el nuevo
    results.forEach((element, index) => {
        mis_labels.push(element.Delito);
        miData.push(element.DelitosTotal);
        backColors.push(convertHexToRGBA(mis_colores2[(index % mis_colores2.length)], 0.3));
        borderColors.push(convertHexToRGBA(mis_colores2[(index % mis_colores2.length)], 1.0));
        totalDelitos += parseInt(element.DelitosTotal);
    });
    document.getElementById('id_total_grafica').innerHTML = totalDelitos;

    if (results.length > 0) {
        document.getElementById('id_sin_results_grafica').classList.add('mi_hide')
        chart3 = new Chart(ctx3, {
            type: 'pie',
            data: {
                labels: mis_labels,
                datasets: [{
                    label: 'Eventos',
                    data: miData,
                    backgroundColor: backColors,
                    borderColor: borderColors,
                    borderWidth: 2
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'EVENTOS POR DELITO'
                    },
                    legend: {
                        display: true,
                        position: 'right', // Coloca la leyenda a la derecha del gráfico
                        labels: {
                            font: {
                                size: 7 // Tamaño de la fuente de las leyendas
                            }
                        } 
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    } else {
        document.getElementById('id_sin_results_grafica').classList.remove('mi_hide')
    }
}

const mostrarGrafica4 = async(results) => {
    let mis_labels = [];
    let backColors = [];
    let borderColors = [];
    let miData = [];
    let totalDias = 0;

    chart4.destroy() //se destruye el anterior canvas para poner el nuevo
    results.forEach((element, index) => {
        mis_labels.push(element.Dia_semana);
        miData.push(element.DiaTotal);
        backColors.push(convertHexToRGBA(mis_colores2[index+1], 0.3));
        borderColors.push(convertHexToRGBA(mis_colores2[index+1], 1.0));
        totalDias += parseInt(element.DiaTotal);
    });
    document.getElementById('id_total_grafica').innerHTML = totalDias;

    if (results.length > 0) {
        document.getElementById('id_sin_results_grafica').classList.add('mi_hide')
        chart4 = new Chart(ctx4, {
            type: 'bar',
            data: {
                labels: mis_labels,
                datasets: [{
                    label: 'Eventos',
                    data: miData,
                    backgroundColor: backColors,
                    borderColor: borderColors,
                    borderWidth: 2
                }]
            },
            options: {
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'EVENTOS POR DE DIA DE LA SEMANA'
                    } 
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    } else {
        document.getElementById('id_sin_results_grafica').classList.remove('mi_hide')
    }
}
const mostrarGrafica5 = async(results) => {
    let mis_labels = [];
    let backColors = [];
    let borderColors = [];
    let miData = [];
    let totalDias = 0;

    chart5.destroy() //se destruye el anterior canvas para poner el nuevo
    results.forEach((element, index) => {
        mis_labels.push(element.Hora_trunca);
        miData.push(element.HoraTotal);
        backColors.push(convertHexToRGBA(mis_colores[(index % mis_colores.length)], 0.3));
        borderColors.push(convertHexToRGBA(mis_colores[(index % mis_colores.length)], 1.0));
        totalDias += parseInt(element.HoraTotal);
    });
    document.getElementById('id_total_grafica').innerHTML = totalDias;

    if (results.length > 0) {
        document.getElementById('id_sin_results_grafica').classList.add('mi_hide')
        chart5 = new Chart(ctx5, {
            type: 'line',
            data: {
                labels: mis_labels,
                datasets: [{
                    label: 'Eventos',
                    data: miData,
                    backgroundColor: backColors,
                    borderColor: borderColors,
                    borderWidth: 2
                }]
            },
            options: {
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'EVENTOS POR HORA DEL DIA'
                    } 
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    } else {
        document.getElementById('id_sin_results_grafica').classList.remove('mi_hide')
    }
}

const mostrarGrafica6 = async(results) => {
    let mis_labels = [];
    let backColors = [];
    let borderColors = [];
    let miData = [];
    let totalVectores = 0;

    chart6.destroy() //se destruye el anterior canvas para poner el nuevo
    results.forEach((element, index) => {
        mis_labels.push(element.Vector);
        miData.push(element.VectorTotal);
        backColors.push(convertHexToRGBA(mis_colores2[(index % mis_colores2.length)], 0.3));
        borderColors.push(convertHexToRGBA(mis_colores2[(index % mis_colores2.length)], 1.0));
        totalVectores += parseInt(element.VectorTotal);
    });
    document.getElementById('id_total_grafica').innerHTML = totalVectores;

    if (results.length > 0) {
        document.getElementById('id_sin_results_grafica').classList.add('mi_hide')
        chart6 = new Chart(ctx6, {
            type: 'bar',
            data: {
                labels: mis_labels,
                datasets: [{
                    label: 'Eventos',
                    data: miData,
                    backgroundColor: backColors,
                    borderColor: borderColors,
                    borderWidth: 2
                }]
            },
            options: {
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'EVENTOS POR VECTORES'
                    } 
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    } else {
        document.getElementById('id_sin_results_grafica').classList.remove('mi_hide')
    }
}


//Funcion actualiza las graficas conforme a las consultas
const getDataGraficas = () => {
    let formAux = new FormData();
    formAux.append('cadena', document.getElementById('id_search').value);
   //console.log( document.getElementById('exacta').checked)
   if(document.getElementById('exacta').checked){
        formAux.append('exacta', 1);
   }else{
        formAux.append('exacta', 0);
   }
   $('#ModalCenterFoto').modal('show');//Mostramos una imagen completa en pantalla hasta que halla una respuesta del controlador
    fetch(base_url_js + 'Estadisticas/getDatagraficas', {//REALIZA UN FETCH PARA GENERAR LAS GRAFICAS CON LOS FILTROS PUESTOS EN LA INTERFAZ
            method: 'POST',
            body: formAux
        })
        .then(resp => resp.json())
        .then(data => {
             mostrarGrafica(data.Zonas);
             mostrarGrafica6(data.Vectores);
             mostrarGrafica2(data.CSviolencias);
             mostrarGrafica3(data.Delitos);
             mostrarGrafica4(data.Dias);
             mostrarGrafica5(data.Horas);
             $('#ModalCenterFoto').modal('hide');//se quita la imagen 
        })
        .catch(err => console.log(err))

}
//funcion para el inicio de la generacion de graficas
window.onload = function() {
    $('#myModal').on('shown.bs.modal', function() {
        $('#myInput').trigger('focus')
    });

    monitorizarTablas();
    //getDataGraficas();
};