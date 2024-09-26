let data;

/*function getPreguntas() {
  fetch('http://localhost/proyecto_0_turner/js/getPreguntas.php?num=10')
    .then(response => response.json())
    .then(info => {
      data = info;
      mostrarPregunta();
    })
}*/


fetch('http://localhost:8000/proyecto 0_turner/js/getPreguntas.php?num=10')
.then(response => response.json())
.then(info => {
  data = info; 
  console.log(data);
  mostrarPregunta();
  actualizarMarcador();
});

let numeroPregunta = 0;
let opcions = ['A', 'B', 'C', 'D'];
let puntuacionActual = 0;
let estadoPartida = {
  contadorPreguntas: 0,
  preguntes: [
    { id: 1, hecha: false, resposta: 0 },
    { id: 2, hecha: false, resposta: 0 },
    { id: 3, hecha: false, resposta: 0 },
    { id: 4, hecha: false, resposta: 0 },
    { id: 5, hecha: false, resposta: 0 },
    { id: 6, hecha: false, resposta: 0 },
    { id: 7, hecha: false, resposta: 0 },
    { id: 8, hecha: false, resposta: 0 },
    { id: 9, hecha: false, resposta: 0 },
    { id: 10, hecha: false, resposta: 0 }
  ]
};

function actualizarMarcador() {
  let htmlString = '';
  htmlString = `<h2>Preguntas Respondidas: ${estadoPartida.contadorPreguntas}/10</h2>`;
  htmlString += `<table>`;

  for (let index = 0; index < estadoPartida.preguntes.length; index++) {
    htmlString += `<tr><td>Pregunta ${index + 1}</td><td>`;

    if (estadoPartida.preguntes[index].hecha) {
      htmlString += "Hecha";
    } else {
      htmlString += "Pendiente";
    }
    htmlString += `</td></tr>`;
  }

  htmlString += `</table>`;

  document.getElementById("marcador").innerHTML = htmlString;
}

function mostrarPregunta() {
  const partidaDiv = document.getElementById('partida');
  const comentarioDiv = document.getElementById('comentario');
  comentarioDiv.innerHTML = '';

  if (numeroPregunta >= data.length) {
    partidaDiv.innerHTML = `<h2>Tu puntuación es: ${puntuacionActual} de ${numeroPregunta}</h2>`;
    return;
  }

  let preguntaActual = data[numeroPregunta];
  let htmlString = `<br><h2>${preguntaActual.pregunta}</h2>`;

  for (let j = 0; j < preguntaActual.respostes.length; j++) {
    htmlString += `<button onclick="corregirRespuesta(${j}, ${preguntaActual.resposta_correcta})">${opcions[j]} </button> ${preguntaActual.respostes[j].etiqueta}<br>`;

  }
  numeroPregunta++;

  partidaDiv.innerHTML = htmlString;
}


function corregirRespuesta(respuestaCliente, respuestaCorrecta) {
  const comentarioDiv = document.getElementById('comentario');

  estadoPartida.preguntes[numeroPregunta-1].hecha=true;
  estadoPartida.contadorPreguntas++;
  estadoPartida.preguntes[numeroPregunta-1].resposta=respuestaCliente;

  if (respuestaCliente === respuestaCorrecta - 1 ) {

    puntuacionActual++;
    comentarioDiv.innerHTML = "<p>Respuesta Correcta!</p>";
  } else {
    comentarioDiv.innerHTML = "<p>Respuesta Incorrecta!</p>";
  }

  actualizarMarcador();

  setTimeout(() => {
    mostrarPregunta();
  }, 2000);
}

//getPreguntas();
