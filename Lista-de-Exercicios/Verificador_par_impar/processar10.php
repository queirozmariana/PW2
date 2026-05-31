<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Resultado</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

<h1>Resultado</h1>

<div class="resultado">

<?php

$numero = $_POST["numero"];

if($numero % 2 == 0){

    echo "<strong>Número Digitado:</strong> $numero";
    echo "<br><br>";

    echo "<strong>Este número é:</strong> PAR";

}
else{

    echo "<strong>Número Digitado:</strong> $numero";
    echo "<br><br>";

    echo "<strong>Este número é:</strong> ÍMPAR";

}

?>

</div>

</div>

</body>
</html>