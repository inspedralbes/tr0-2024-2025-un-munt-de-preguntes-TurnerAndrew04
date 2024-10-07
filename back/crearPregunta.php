<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "turner2";
$password = "123456789hola";
$dbname = "turner2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => "Conexión fallida: " . $conn->connect_error]));
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['pregunta']) || !isset($data['respostes']) || !isset($data['resposta_correcta'])) {
    echo json_encode(["success" => false, "error" => "Datos incompletos"]);
    exit;
}

$pregunta = $data['pregunta'];
$respostes = $data['respostes'];
$resposta_correcta = $data['resposta_correcta'];

$stmtPregunta = $conn->prepare("INSERT INTO preguntas (pregunta, respostes, resposta_correcta) VALUES (?, ?, ?)");
$numRespostes = count($respostes);
$stmtPregunta->bind_param("sii", $pregunta, $numRespostes, $resposta_correcta);

if ($stmtPregunta->execute()) {
    $idPreguntaInsertada = $conn->insert_id;
    
    foreach ($respostes as $etiqueta) {
        $stmtRespostes = $conn->prepare("INSERT INTO respostes (idQuestion, etiqueta) VALUES (?, ?)");
        $stmtRespostes->bind_param("is", $idPreguntaInsertada, $etiqueta['etiqueta']);
        if (!$stmtRespostes->execute()) {
            echo json_encode(["success" => false, "error" => "Error al insertar respuestas: " . $stmtRespostes->error]);
            exit;
        }
    }

    echo json_encode(["success" => true]);

} else {
    echo json_encode(["success" => false, "error" => "Error al insertar la pregunta: " . $stmtPregunta->error]);
}

$stmtPregunta->close();
$stmtRespostes->close();
$conn->close();
?>
