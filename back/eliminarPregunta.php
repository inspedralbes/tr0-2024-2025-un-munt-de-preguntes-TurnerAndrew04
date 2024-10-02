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
?>