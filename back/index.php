<?php

session_start();

$jsonString = file_get_contents('preguntas_peliculas.json');
$preguntes = json_decode($jsonString, true)['preguntes'];

if (!isset($_SESSION['numeroPregunta'])) {
    $_SESSION['numeroPregunta'] = 0;
    $_SESSION['puntuacio'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $respostaClient = intval($_POST['resposta']);
    $respostaCorrecta = $preguntes[$_SESSION['numeroPregunta']]['resposta_correcta'];

    if ($respostaClient === $respostaCorrecta) {
        $_SESSION['puntuacio']++;
        $comentari = "Resposta Correcta!";
    } else {
        $comentari = "Resposta Incorrecta!";
    }

    $_SESSION['numeroPregunta']++;

    if ($_SESSION['numeroPregunta'] >= count($preguntes)) {
        header('Location: resultat.php');
        exit;
    }
} else {
    $comentari = "";
}


$preguntaActual = $preguntes[$_SESSION['numeroPregunta']];
?>

 <!DOCTYPE html> 
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adivina el año de la película</title>
</head>
<body>
    <h1>Adivina el año de la película</h1>

    <div id="partida">
        <h2><?php echo $preguntaActual['pregunta']; ?></h2>
        <form method="POST" action="">
            <?php foreach ($preguntaActual['respostes'] as $index => $resposta): ?>
                <input type="radio" id="resposta_<?php echo $index; ?>" name="resposta" value="<?php echo $resposta['id']; ?>">
                <label for="resposta_<?php echo $index; ?>"><?php echo $resposta['etiqueta']; ?></label><br>
            <?php endforeach; ?>
            <br>
            <button type="submit">Enviar respuesta</button>
        </form>
    </div>

    <div id="comentario">
        <p><?php echo $comentari; ?></p>
    </div>

    <div id="puntuacio">
        <p>Puntuació actual: <?php echo $_SESSION['puntuacio']; ?></p>
    </div>
</body>
</html>
