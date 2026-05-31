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

$num1 = $_POST["num1"];
$num2 = $_POST["num2"];
$num3 = $_POST["num3"];

$somaQuadrados = ($num1 * $num1) +
                 ($num2 * $num2) +
                 ($num3 * $num3);

echo "<strong>1º Número:</strong> $num1";
echo "<br><br>";

echo "<strong>2º Número:</strong> $num2";
echo "<br><br>";

echo "<strong>3º Número:</strong> $num3";
echo "<br><br>";

echo "<strong>Soma dos Quadrados:</strong> $somaQuadrados";

?>

</div>

</div>

</body>
</html>