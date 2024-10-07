<?php
include("connexion.php");

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

$sql = "UPDATE preguntas SET pregunta = ? WHERE id = ?";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(["Pregunta Modificada" => false, "Error al modificar la pregunta" => $conn->error]);
    exit;
}

$stmt->bind_param("siii", $nuevaPregunta, $idPregunta);

if ($stmt->execute()) {
    // echo json_encode(["Pregunta_Modificada" => true]);
} else {
    // echo json_encode(["Pregunta_Modificada" => false, "error" => $stmt->error]);
}
foreach ($nuevasRespuestas as $key => $value) {

    $sqlRespuesta = "UPDATE respostes SET resposta = '?' WHERE id = ?";

    $stmtsqlRespuesta = $conn->prepare($sqlRespuesta);

    if ($stmtsqlRespuesta === false) {
        echo json_encode(["Pregunta Modificada" => false, "Error al modificar la pregunta" => $conn->error]);
        exit;
    }

    $stmtsqlRespuesta->bind_param("si", $value["etiqueta"], $value["id"]);

    if ($stmtsqlRespuesta->execute()) {
        echo json_encode(["Pregunta_Modificada" => true]);
    } else {
        echo json_encode(["Pregunta_Modificada" => false, "error" => $stmt->error]);
    }

}


$stmtsqlRespuesta->close();

$conn->close();
?>