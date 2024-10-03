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

$pregunta = $data['pregunta'];
$respostes = $data['respostes'];
$resposta_correcta = $data['resposta_correcta'];

$sql = "INSERT INTO preguntas (pregunta, respostes, resposta_correcta) VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(["success" => false, "error" => $conn->error]);
    exit;
}

$stmt->bind_param("sss", $pregunta, $respostes, $resposta_correcta);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
