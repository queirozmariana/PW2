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

$nota1 = $_POST["nota1"];
$nota2 = $_POST["nota2"];
$nota3 = $_POST["nota3"];
$nota4 = $_POST["nota4"];

$media = ($nota1 + $nota2 + $nota3 + $nota4) / 4;

echo "<strong>Média:</strong> " . number_format($media, 1, ",", ".");
echo "<br><br>";

if($media >= 6){
    echo "<strong>Situação:</strong> APROVADO";
}
elseif($media < 3){
    echo "<strong>Situação:</strong> RETIDO";
}
else{
    echo "<strong>Situação:</strong> EXAME";
}

?>

</div>

</div>

</body>
</html>