<?php
$servername = "localhost";
$username = "turner2";
$password = "123456789hola";
$dbname = "turner2";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos enviados en la solicitud POST
$data = json_decode(file_get_contents('php://input'), true);

$titulo = $data['titulo'];
$contenido = $data['contenido'];
$categoria = $data['categoria'];

// Preparar la consulta SQL para insertar la nueva pregunta
$sql = "INSERT INTO preguntas (titulo, contenido, categoria) VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(["success" => false, "error" => $conn->error]);
    exit;
}

// Asignar los parámetros a la consulta
$stmt->bind_param("sss", $titulo, $contenido, $categoria);

// Ejecutar la consulta y verificar si se creó correctamente
if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

// Cerrar la declaración y la conexión
$stmt->close();
$conn->close();
?>
