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

if (!isset($data['id'])) {
    echo json_encode(["Pregunta_eliminada" => false, "error" => "ID de la pregunta no especificado"]);
    exit;
}

$idPregunta = $data['id'];
$obj = new stdClass();
$obj->id = $idPregunta;

$sql = "DELETE FROM respostes WHERE idQuestion = ?"; 
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idPregunta);
$stmt->execute();

$sql = "DELETE FROM preguntas WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idPregunta);
$stmt->execute();
$obj ->filas = $stmt->num_rows();


echo json_encode($obj);
$conn->close();
?>
