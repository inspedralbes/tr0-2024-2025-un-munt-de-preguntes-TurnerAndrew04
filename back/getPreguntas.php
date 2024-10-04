<?php
$servername = "localhost";
$username = "turner2";
$password = "123456789hola";
$dbname = "turner2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
// $numPreguntes = (int) $_GET['num'];

if (isset($_GET['num'])) {
    $num = $_GET['num'];

    $sql = "SELECT * FROM preguntas ORDER BY RAND() LIMIT ?";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $num);

    $stmt->execute();

    $result = $stmt->get_result();
    $preguntas = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($preguntas as &$pregunta) {
        $sqlRespuestas = "SELECT * FROM respostes WHERE idQuestion = ?" ;
        $stmtRespuestas = $conn->prepare($sqlRespuestas);

        $stmtRespuestas->bind_param("i", $pregunta["id"]);

        $stmtRespuestas->execute();

        $resultRespuestas = $stmtRespuestas->get_result();
        $respuestas = $resultRespuestas->fetch_all(MYSQLI_ASSOC);
        $pregunta["respostes"] = $respuestas;
    }

    echo json_encode($preguntas);

    $stmt->close();
} else {
    $sql = "SELECT * FROM preguntas";
    $stmt = $conn->prepare($sql);

    // $stmt->bind_param("i", $num);

    $stmt->execute();

    $result = $stmt->get_result();
    $preguntas = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($preguntas as &$pregunta) {
        $sqlRespuestas = "SELECT * FROM respostes WHERE idQuestion = ?" ;
        $stmtRespuestas = $conn->prepare($sqlRespuestas);

        $stmtRespuestas->bind_param("i", $pregunta["id"]);

        $stmtRespuestas->execute();

        $resultRespuestas = $stmtRespuestas->get_result();
        $respuestas = $resultRespuestas->fetch_all(MYSQLI_ASSOC);
        $pregunta["respostes"] = $respuestas;
    }
    echo json_encode($preguntas);
    $stmt->close();
}
$conn->close();

?>