<?php
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

$sql = "DELETE FROM preguntas WHERE id = ?";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(["Pregunta eliminada" => false, "Error al eliminar la pregunta" => $conn->error]);
    exit;
}

$stmt->bind_param("i", $idPregunta);

if ($stmt->execute()) {
    echo json_encode(["Pregunta eliminad" => true]);
} else {
    echo json_encode(["Pregunta eliminad" => false, "Error al eliminar la pregunta" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
