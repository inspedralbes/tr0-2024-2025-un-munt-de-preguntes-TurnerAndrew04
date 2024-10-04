let data = [];

async function cargarPreguntas() {
  await fetch('../back/getPreguntas.php')
    .then(response => response.json())
    .then(dades => {
      console.log(dades)
      data = dades;
      mostrarPregunta();
    })
    .catch(error => console.error('Error al cargar las preguntas:', error));
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

    // Evento para el botón "Eliminar"
    preguntaDiv.querySelector('.eliminar').addEventListener('click', () => {
      const idPregunta = pregunta.id;
      eliminarPregunta(idPregunta);
    });
  });
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

function guardarCambios(idPregunta) {
  console.log("change")
  const nuevaPregunta = document.getElementById('modificarPregunta').value;
  const nuevasRespuestas = [];

  for (let i = 0; i < 4; i++) {
    const respuesta = document.getElementById(`modificarResposta${i}`);
    const respuestaId = document.getElementById(`modificarResposta${i}`).dataset.idRes;

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
      console.log(data);

      if (data.Pregunta_Modificada) {
        console.log("Pregunta modificada con éxito");
        cargarPreguntas(); // Carga las preguntas actualizadas
      }

    });
}

function eliminarPregunta(idPregunta) {
  const datos = {
    id: idPregunta
  };

  fetch('../back/eliminarPregunta.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(datos)
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        console.log("Pregunta eliminada con éxito");
        cargarPreguntas(); // Carga las preguntas actualizadas
      } else {
        console.log("Error al eliminar la pregunta: ", data.error);
      }
    })
    .catch(error => {
      console.error("Error al realizar la solicitud:", error);
    });
}

document.addEventListener('DOMContentLoaded', cargarPreguntas);
