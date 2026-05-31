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

$valor1 = $_POST["valor1"];
$valor2 = $_POST["valor2"];
$operador = $_POST["operador"];

switch($operador){

    case "+":
        $resultado = $valor1 + $valor2;
        break;

    case "-":
        $resultado = $valor1 - $valor2;
        break;

    case "*":
        $resultado = $valor1 * $valor2;
        break;

    case "/":

        if($valor2 == 0){
            echo "<strong>Erro:</strong> Não é possível dividir por zero.";
            exit;
        }

        $resultado = $valor1 / $valor2;
        break;
}

echo "<strong>Primeiro Valor:</strong> $valor1";
echo "<br><br>";

echo "<strong>Segundo Valor:</strong> $valor2";
echo "<br><br>";

echo "<strong>Operação:</strong> $operador";
echo "<br><br>";

echo "<strong>Resultado:</strong> $resultado";

?>

</div>

</div>

</body>
</html>