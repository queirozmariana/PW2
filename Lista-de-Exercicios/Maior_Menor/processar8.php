<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Resultado</title>
<link rel="stylesheet" href="../CSS/style.css">
</head>

<body>

<div class="container">

<h1>Resultado</h1>

<div class="resultado">

<?php

$num1 = $_POST["num1"];
$num2 = $_POST["num2"];
$num3 = $_POST["num3"];

$maior = max($num1, $num2, $num3);
$menor = min($num1, $num2, $num3);

echo "<strong>Números Digitados:</strong> $num1, $num2, $num3";
echo "<br><br>";

echo "<strong>Maior Número:</strong> $maior";
echo "<br><br>";

echo "<strong>Menor Número:</strong> $menor";

?>

</div>

</div>

</body>
</html>