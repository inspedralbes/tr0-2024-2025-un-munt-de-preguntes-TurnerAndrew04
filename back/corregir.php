<?php

$data = json_decode(file_get_contents('php://input'), true);

$jsonData = file_get_contents('preguntas_peliculas.json');
$preguntes = json_decode($jsonData, true)['preguntes'];

$pregunta_id = $data['pregunta_id'];
$respuesta_cliente = $data['respuesta_cliente']; 

foreach ($preguntes as $pregunta) {
    if ($pregunta['id'] == $pregunta_id) {
        $respuesta_correcta = $pregunta['resposta_correcta'];

        echo json_encode(['correcta' => ($respuesta_cliente == $respuesta_correcta)]);
        exit;
    }
}

echo json_encode(['correcta' => false]);
?>
