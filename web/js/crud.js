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
      htmlString += `<button class="resposta-button" data-index="${j}" data-correcta="${preguntaActual.resposta_correcta}">${opcions[j]} </button> ${preguntaActual.respostes[j].etiqueta}<br>`;
    }
  
    numeroPregunta++;
    partidaDiv.innerHTML = htmlString;
    
    const buttons = partidaDiv.querySelectorAll('.resposta-button');
    buttons.forEach(button => {
      button.addEventListener('click', (event) => {
        const index = parseInt(event.target.getAttribute('data-index'));
        const respuestaCorrecta = parseInt(event.target.getAttribute('data-correcta'));
        corregirRespuesta(index, respuestaCorrecta);
      });
    });
  }


function modificarPregunta(idPregunta, nuevaPregunta, nuevasRespuestas, nuevaRespuestaCorrecta) {
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
      if (data.success) {
        console.log("Pregunta modificada con éxito");

      } else {
        console.log("Error al modificar la pregunta: ", data.error);
      }
    })
    .catch(error => {
      console.error("Error al realizar la solicitud:", error);
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
    } else {
      console.log("Error al eliminar la pregunta: ", data.error);
    }
  })
  .catch(error => {
    console.error("Error al realizar la solicitud:", error);
  });
}

function crearPregunta(titulo, contenido, categoria) {
    const datos = {
      titulo: titulo,
      contenido: contenido,
      categoria: categoria
    };
  
    fetch('../back/crearPregunta.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        console.log("Pregunta creada con éxito");
      } else {
        console.log("Error al crear la pregunta: ", data.error);
      }
    })
    .catch(error => {
      console.error("Error al realizar la solicitud:", error);
    });
  }
  
  
