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

$preco = $_POST["preco"];
$desconto = $_POST["desconto"];

$valorDesconto = ($preco * $desconto) / 100;
$valorFinal = $preco - $valorDesconto;

echo "<strong>Preço Original:</strong> R$ " . number_format($preco, 2, ",", ".");
echo "<br><br>";

echo "<strong>Desconto:</strong> " . $desconto . "%";
echo "<br><br>";

echo "<strong>Preço Final:</strong> R$ " . number_format($valorFinal, 2, ",", ".");

?>

</div>

</div>

</body>
</html>