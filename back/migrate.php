<?php
$servername = "localhost";
$username = "turner";
$password = "1234hola";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>



id int(11) NOT NULL,
  `pregunta` varchar(100) NOT NULL,
  `respostes` int(11) NOT NULL,
  `resposta_correcta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;