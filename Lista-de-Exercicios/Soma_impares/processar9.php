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

$inicio = $_POST["inicio"];
$fim = $_POST["fim"];

$soma = 0;

for($i = $inicio; $i <= $fim; $i++){

    if($i % 2 != 0){
        $soma += $i;
    }

}

echo "<strong>Valor Inicial:</strong> $inicio";
echo "<br><br>";

echo "<strong>Valor Final:</strong> $fim";
echo "<br><br>";

echo "<strong>Soma dos Ímpares:</strong> $soma";

?>

</div>

</div>

</body>
</html>