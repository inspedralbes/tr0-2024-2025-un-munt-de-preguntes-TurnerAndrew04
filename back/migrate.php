<?php
$servername = "localhost";
$username = "turner";
$password = "1234hola";
$dbname = "preguntas_peliculas";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

$sqlDrop = "DROP TABLE IF EXISTS preguntas;";

if ($conn->query($sqlDrop) === TRUE) {
  echo "Table eliminada";
} else {
  echo "Error al eliminar la tabla" . $conn->error;
}
$sqlCreate = "CREATE TABLE `preguntas`(

  `id` int(11) NOT NULL,
  `pregunta` varchar(100) NOT NULL,
  `respostes` int(11) NOT NULL,
  `resposta_correcta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
COMMIT;
";

if ($conn->query($sqlCreate) === TRUE) {
  echo "Table creada";
} else {
  echo "Error al crear la tabla" . $conn->error;
}

$json = file_get_contents('back/preguntas_peliculas.json');
$json = json_decode($json, true);

foreach ($data['preguntes'] as $row) {
  $id = $row['id'];
  $pregunta = $row['pregunta'];
  $respostes = $row['respostes'];
  $resposta_correcta = $row['resposta_correcta'];

  $sql = "INSERT INTO preguntas ('id', 'pregunta', 'respostes', 'resposta_correcta')
  VALUES ('$id', '$pregunta', '$respostes', '$resposta_correcta');";

}
$conn->close();

?>