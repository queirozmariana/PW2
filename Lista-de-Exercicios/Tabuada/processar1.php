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

for($i=1;$i<=10;$i++){

    echo "$numero x $i = ".($numero*$i)."<br>";

}

?>

</div>

</div>

</body>
</html>