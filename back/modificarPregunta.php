<?php
include ("connexion.php");

$servername = "localhost";
$username = "turner2";
$password = "123456789hola";
$dbname = "turner2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);

$idPregunta = $data['id'];
$nuevaPregunta = $data['pregunta'];
$nuevasRespuestas = $data['respostes'];
$nuevaRespuestaCorrecta = $data['resposta_correcta'];

$sql = "UPDATE preguntas SET pregunta = ?, respostes = ?, resposta_correcta = ? WHERE id = ?";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(["Pregunta Modificada" => false, "Error al modificar la pregunta" => $conn->error]);
    exit;
}

$stmt->bind_param("siii", $nuevaPregunta, $nuevasRespuestas, $nuevaRespuestaCorrecta, $idPregunta);

if ($stmt->execute()) {
    echo json_encode(["Pregunta Modificada" => true]);
} else {
    echo json_encode(["Pregunta Modificada" => false, "Error al modificar la pregunta" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
