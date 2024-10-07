let data = [];

async function cargarPreguntas() {
  await fetch('../back/getPreguntas.php')
    .then(response => response.json())
    .then(dades => {
      //console.log(dades);
      data = dades;
      mostrarPregunta();
    })
    .catch(error => console.error('Error al cargar las preguntas:', error));

  mostrarFormularioCrear();
}

function mostrarPregunta() {
  const partidaDiv = document.getElementById('partida');
  partidaDiv.innerHTML = '';

  data.forEach((pregunta, index) => {
    const preguntaDiv = document.createElement('div');
    preguntaDiv.classList.add('pregunta');

    preguntaDiv.innerHTML = `
      <h2>Pregunta ${index + 1}: ${pregunta.pregunta}</h2>
      <ul>
          ${pregunta.respostes.map(resposta => `
              <li>${resposta.id}. ${resposta.etiqueta}</li>
          `).join('')}
      </ul>
      <button class="modificar" 
              data-id="${pregunta.id}" 
              data-pregunta="${pregunta.pregunta}" 
              data-respostes='${JSON.stringify(pregunta.respostes)}' 
              data-respuesta-correcta="${pregunta.resposta_correcta}">
          Modificar
      </button>
      <button class="eliminar" data-id="${pregunta.id}">Eliminar</button>
    `;

    partidaDiv.appendChild(preguntaDiv);
    
    preguntaDiv.querySelector('.modificar').addEventListener('click', () => {
      const idPregunta = pregunta.id;
      const nuevaPregunta = pregunta.pregunta;
      const nuevasRespuestas = pregunta.respostes;
      const nuevaRespuestaCorrecta = pregunta.resposta_correcta;

      mostrarFormularioModificar(idPregunta, nuevaPregunta, nuevasRespuestas, nuevaRespuestaCorrecta);
    });

    preguntaDiv.querySelector('.eliminar').addEventListener('click', () => {
      const idPregunta = pregunta.id;
      eliminarPregunta(idPregunta);
    });
  });

  const crearPreguntaDiv = document.createElement('div');
  crearPreguntaDiv.innerHTML = `<button id="crearPreguntaBtn">Crear Nueva Pregunta</button>`;
  partidaDiv.appendChild(crearPreguntaDiv);

  document.getElementById('crearPreguntaBtn').addEventListener('click', mostrarFormularioCrear);
}

function mostrarFormularioModificar(idPregunta, nuevaPregunta, nuevasRespuestas, nuevaRespuestaCorrecta) {
  const adminDiv = document.getElementById('formularioPregunta');
  adminDiv.innerHTML = `
      <h3>Modificar Pregunta</h3>
      <input type="text" id="modificarPregunta" value="${nuevaPregunta}" placeholder="Pregunta"><br>
      <label>Respostes:</label><br>
      ${nuevasRespuestas.map((respuesta, index) => `
          <input type="text" id="modificarResposta${index}" data-id-res="${respuesta.id}" value="${respuesta.etiqueta}" placeholder="Resposta ${index + 1}"><br>
      `).join('')}
      <br>
      <label>Resposta Correcta (0-${nuevasRespuestas.length - 1}):</label>
      <input type="number" id="modificarRespuestaCorrecta" min="0" max="${nuevasRespuestas.length - 1}" value="${nuevaRespuestaCorrecta}"><br>
      <button onclick="guardarCambios('${idPregunta}')">Guardar Cambios</button>
  `;
}

function mostrarFormularioCrear() {
  const formularioCrearDiv = document.getElementById('formularioPregunta');
  formularioCrearDiv.innerHTML = `
      <h3>Crear Nueva Pregunta</h3>
      <input type="text" id="nuevaPregunta" placeholder="Pregunta"><br>
      <label>Respuestas:</label><br>
      <input type="text" id="nuevaRespuesta0" placeholder="Respuesta 1"><br>
      <input type="text" id="nuevaRespuesta1" placeholder="Respuesta 2"><br>
      <input type="text" id="nuevaRespuesta2" placeholder="Respuesta 3"><br>
      <input type="text" id="nuevaRespuesta3" placeholder="Respuesta 4"><br>
      <br>
      <label>Respuesta Correcta (0-3):</label>
      <input type="number" id="nuevaRespuestaCorrecta" min="0" max="3" placeholder="Índice de la respuesta correcta"><br>
      <button onclick="guardarNuevaPregunta()">Guardar Nueva Pregunta</button>
  `;
}

function guardarNuevaPregunta() {
  const nuevaPregunta = document.getElementById('nuevaPregunta').value;
  const nuevasRespuestas = [];

  for (let i = 0; i < 4; i++) {
    const respuesta = document.getElementById(`nuevaRespuesta${i}`).value;
    nuevasRespuestas.push({ etiqueta: respuesta });
  }

  const nuevaRespuestaCorrecta = document.getElementById('nuevaRespuestaCorrecta').value;

  if (nuevaPregunta && nuevasRespuestas.length === 4 && nuevaRespuestaCorrecta !== "") {
    crearPregunta(null, nuevaPregunta, nuevasRespuestas, nuevaRespuestaCorrecta);
    cargarPreguntas();
  } else {
    alert('Completa todos los campos.');
  }
}

function guardarCambios(idPregunta) {
  const nuevaPregunta = document.getElementById('modificarPregunta').value;
  const nuevasRespuestas = [];

  for (let i = 0; i < 4; i++) {
    const respuesta = document.getElementById(`modificarResposta${i}`);
    const respuestaId = respuesta.dataset.idRes;

    if (respuesta) {
      nuevasRespuestas.push({ id: respuestaId, etiqueta: respuesta.value });
    }
  }

  const nuevaRespuestaCorrecta = document.getElementById('modificarRespuestaCorrecta').value;

  const datosModificados = {
    id: idPregunta,
    pregunta: nuevaPregunta,
    respostes: nuevasRespuestas,
    resposta_correcta: nuevaRespuestaCorrecta
  };

  fetch('../back/modificarPregunta.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(datosModificados)
  })
    .then(response => response.json())
    .then(data => {
      if (data.Pregunta_Modificada) {
        console.log("Pregunta modificada con éxito");
        cargarPreguntas();
      }
    });
}

function eliminarPregunta(idPregunta) {
  const confirmacion = confirm("¿Estás seguro de que quieres eliminar esta pregunta?");

  if (confirmacion) {
    const datos = {
      id: idPregunta
    };
    cargarPreguntas();

    fetch('../back/eliminarPregunta.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
      if (data.Pregunta_eliminada) {
        console.log("Pregunta eliminada con éxito");
        cargarPreguntas(); 
      } else {
        console.log("Error al eliminar la pregunta: ", data.error);
      }
    })
    .catch(error => {
      console.error("Error al realizar la solicitud:", error);
    });
  } else {
    console.log("Acción cancelada.");
  }
}

function crearPregunta(idPregunta, nuevaPreguntaC, nuevasRespuestasC, nuevaRespuestaCorrectaC) {
  const datosModificados = {
      id: idPregunta,
      pregunta: nuevaPreguntaC,
      respostes: nuevasRespuestasC,
      resposta_correcta: nuevaRespuestaCorrectaC
  };

  fetch('../back/crearPregunta.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json'
      },
      body: JSON.stringify(datosModificados)
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          console.log("Pregunta creada con éxito");
          cargarPreguntas(); 
      } else {
          console.log("Error al crear la pregunta: ", data.error);
      }
  })
  .catch(error => {
      console.error("Error al realizar la solicitud:", error);
  });
}

document.addEventListener('DOMContentLoaded', cargarPreguntas);
