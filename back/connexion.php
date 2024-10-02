<?php
$servername = "localhost";
$username = "turner";
$password = "1234hola";
$dbname = "turner";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Conexión fallida: " . $conn->connect_error);
} else {
  echo "Conectado con éxito";
}

$conn->close();
?>
