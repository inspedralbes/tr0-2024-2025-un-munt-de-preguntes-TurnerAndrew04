<?php

$numPreguntes = (int) $_GET['num'];

$jsonData = file_get_contents('preguntas_peliculas.json');
$preguntes = json_decode($jsonData, true)['preguntes']; 

shuffle($preguntes);
$preguntasSeleccionadas = array_slice($preguntes, 0, $numPreguntes);

foreach ($preguntasSeleccionadas as &$pregunta) {
    unset($pregunta['resposta_correcta']);  
}

//echo js_encode($preguntasSeleccionadas);
echo json_encode($preguntasSeleccionadas);

?>