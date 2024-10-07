<?php
$input = file_get_contents('php://input');
$usuariRespostes = json_decode($input, true);

$jsonData = file_get_contents('preguntas_peliculas.json');
$preguntes = json_decode($jsonData, true)['preguntes'];

$totalPreguntas = count($preguntes);
$respuestasCorrectas = 0;

for ($i = 0; $i < $totalPreguntas; $i++) {
    if (isset($usuariRespostes[$i]) && $usuariRespostes[$i] == $preguntes[$i]['resposta_correcta']) {
        $respuestasCorrectas++;
    }
}
$resultat = [
    'totalPreguntes' => $totalPreguntas,
    'respostesCorrectes' => $respuestasCorrectas
];
echo json_encode($resultat);

?>