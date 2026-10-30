<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Resultado</title>

    <!-- IMPORTANTE -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="resultado">

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $horas = $_POST["txthoras"];
        $valor = $_POST["txtvalor"];

        $salario = $horas * $valor;

        echo "<p>O salário é: <strong>R$ " . number_format($salario, 2, ',', '.') . "</strong></p>";
    }
    ?>

    <a href="index.html" class="btn-voltar">Voltar</a>

</div>

</body>
</html>