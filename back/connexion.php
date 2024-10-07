<?php
$servername = "localhost";
$username = "a23andturmat_turner2";
$password = "Catan2004";
$dbname = "a23andturmat_turner2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Conexión fallida: " . $conn->connect_error);
} else {
  // echo "Conectado con éxito";
}

$conn->close();
?>
