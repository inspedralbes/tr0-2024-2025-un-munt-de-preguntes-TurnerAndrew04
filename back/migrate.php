<?php
$servername = "localhost";
$username = "turner2";
$password = "123456789hola";
$dbname = "turner2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Conexión fallida:<br>" . $conn->connect_error);
} else {
  echo "Conectado con éxito<br>";
}

$sqlDrop = "DROP TABLE IF EXISTS preguntas;";

if ($conn->query($sqlDrop) === TRUE) {
  echo "Tabla eliminada<br>";
} else {
  echo "Error al eliminar la tabla<br>" . $conn->error;
}

$sqlCreate = "CREATE TABLE `preguntas`(
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `pregunta` varchar(100) NOT NULL,
  `respostes` int(11) NOT NULL,
  `resposta_correcta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

if ($conn->query($sqlCreate) === TRUE) {
  echo "Table creada";
} else {
  echo "Error al crear la tabla" . $conn->error;
}

$json = file_get_contents('preguntas_peliculas.json');
$data = json_decode($json, true);

$stmt = $conn->prepare("INSERT INTO preguntas (id, pregunta, respostes, resposta_correcta) VALUES (?, ?, ?, ?)");

foreach ($data['preguntes'] as $row) {
  $id = $row['id'];
  $pregunta = $row['pregunta'];
  $respostes = $row['respostes'];
  $resposta_correcta = $row['resposta_correcta'];

  $stmt->bind_param("isii", $id, $pregunta, $respostes, $resposta_correcta);

  if ($stmt->execute()) {
    echo "Datos inseridos correctamente<br>";
  }else{
    echo "Error<br>". $stmt->error;
  }
}

$stmt->close();
$conn->close();


?>