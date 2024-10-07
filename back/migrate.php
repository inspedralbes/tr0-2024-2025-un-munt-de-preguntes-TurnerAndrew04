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

$sqlDrop = "DROP TABLE IF EXISTS respostes;";
if ($conn->query($sqlDrop) === TRUE) {
  echo "Tabla 'respostes' eliminada<br>";
} else {
  echo "Error al eliminar la tabla respostes: " . $conn->error . "<br>";
}

$sqlDrop = "DROP TABLE IF EXISTS preguntas;";
if ($conn->query($sqlDrop) === TRUE) {
  echo "Tabla 'preguntas' eliminada<br>";
} else {
  echo "Error al eliminar la tabla preguntas: " . $conn->error . "<br>";
}

$sqlCreate = "CREATE TABLE `preguntas`(
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `pregunta` varchar(100) NOT NULL,
  `respostes` int(11) DEFAULT NULL,
  `resposta_correcta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
if ($conn->query($sqlCreate) === TRUE) {
  echo "Tabla 'preguntas' creada<br>";
} else {
  echo "Error al crear la tabla 'preguntas': " . $conn->error . "<br>";
}

$sqlCreate = "CREATE TABLE `respostes` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `idQuestion` int(11) NOT NULL,
  `etiqueta` varchar(200) NOT NULL,
  FOREIGN KEY (idQuestion) REFERENCES preguntas(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
if ($conn->query($sqlCreate) === TRUE) {
  echo "Tabla 'respostes' creada<br>";
} else {
  echo "Error al crear la tabla 'respostes': " . $conn->error . "<br>";
}

$json = file_get_contents('preguntas_peliculas.json');
$data = json_decode($json, true);

$stmtPreguntas = $conn->prepare("INSERT INTO preguntas (pregunta, respostes, resposta_correcta) VALUES (?, ?, ?)");
$stmtPreguntas->bind_param("sii", $pregunta, $respostes, $resposta_correcta);

foreach ($data['preguntes'] as $row) {
  $pregunta = $row['pregunta'];
  $respostes = count($row['respostes']); 
  $resposta_correcta = $row['resposta_correcta'];

  if ($stmtPreguntas->execute()) {
    echo "Pregunta insertada correctamente: $pregunta<br>";
    $idPreguntaInsertada = $conn->insert_id; 
  } else {
    echo "Error al insertar la pregunta: " . $stmtPreguntas->error . "<br>";
  }

  $stmtRespostes = $conn->prepare("INSERT INTO respostes (idQuestion, etiqueta) VALUES (?, ?)");
  $stmtRespostes->bind_param("is", $idQuestion, $etiqueta);

  foreach ($row['respostes'] as $resposta) {
    $idQuestion = $idPreguntaInsertada; 
    $etiqueta = $resposta['etiqueta'];

    if ($stmtRespostes->execute()) {
      echo "Respuesta insertada correctamente: $etiqueta para la pregunta $idQuestion<br>";
    } else {
      echo "Error al insertar la respuesta: " . $stmtRespostes->error . "<br>";
    }
  }
}

$stmtPreguntas->close();
$stmtRespostes->close();
$conn->close();
?>
