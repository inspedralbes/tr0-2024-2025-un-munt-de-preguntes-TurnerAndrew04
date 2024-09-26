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
});

let numeroPregunta = 0;
let opcions = ['A', 'B', 'C', 'D'];
let puntuacionActual = 0;

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

  if (respuestaCliente === respuestaCorrecta - 1) {

    puntuacionActual++;
    comentarioDiv.innerHTML = "<p>Respuesta Correcta!</p>";
  } else {
    comentarioDiv.innerHTML = "<p>Respuesta Incorrecta!</p>";
  }

  setTimeout(() => {
    mostrarPregunta();
  }, 2000);
}

//getPreguntas();
