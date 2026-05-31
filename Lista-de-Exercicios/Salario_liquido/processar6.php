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

$salario = $_POST["salario"];

$gratificacao = $salario * 0.10;
$imposto = $salario * 0.20;

$salarioLiquido = $salario + $gratificacao - $imposto;

echo "<strong>Salário Bruto:</strong> R$ " . number_format($salario, 2, ",", ".");
echo "<br><br>";

echo "<strong>Gratificação (10%):</strong> R$ " . number_format($gratificacao, 2, ",", ".");
echo "<br><br>";

echo "<strong>Imposto de Renda (20%):</strong> R$ " . number_format($imposto, 2, ",", ".");
echo "<br><br>";

echo "<strong>Salário Líquido:</strong> R$ " . number_format($salarioLiquido, 2, ",", ".");

?>

</div>

</div>

</body>
</html>