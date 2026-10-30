<?php

date_default_timezone_set("America/Sao_Paulo");

// Função para limpar os dados

function limpar($valor){
    return htmlspecialchars(trim(strip_tags($valor)), ENT_QUOTES, "UTF-8");
}

$resultado = null;

// Só aceita envio por POST

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $nome = limpar($_POST["nome"] ?? "");
    $email = limpar($_POST["email"] ?? "");
    $telefone = limpar($_POST["telefone"] ?? "");
    $instituicao = limpar($_POST["instituicao"] ?? "");
    $assunto = limpar($_POST["assunto"] ?? "");
    $mensagem = limpar($_POST["mensagem"] ?? "");
    $newsletter = isset($_POST["newsletter"]);

    $assuntosPermitidos = [
        "Cursos",
        "Vestibulinho",
        "Eventos",
        "Documentos",
        "Secretaria",
        "Outros"
    ];

    $erros = [];

    if(strlen($nome) < 3){
        $erros[] = "O nome deve possuir pelo menos 3 caracteres.";
    }

    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $erros[] = "Digite um e-mail válido.";
    }

    $numeros = preg_replace('/\D/','',$telefone);

    if(strlen($numeros) != 11){
        $erros[] = "Telefone inválido.";
    }

    if(strlen($instituicao) < 2){
        $erros[] = "Informe uma instituição válida.";
    }

    if(!in_array($assunto,$assuntosPermitidos)){
        $erros[] = "Selecione um assunto válido.";
    }

    if(strlen($mensagem) < 15){
        $erros[] = "A mensagem deve possuir pelo menos 15 caracteres.";
    }

    if(strlen($mensagem) > 500){
        $erros[] = "A mensagem pode possuir no máximo 500 caracteres.";
    }

    if(empty($erros)){

        if(!is_dir(__DIR__."/logs")){
            mkdir(__DIR__."/logs",0755,true);
        }

        $linha = "=============================\n";
        $linha .= "Data: " . date("d/m/Y") . " às " . date("H:i") . "\n";
        $linha .= "Nome: ".$nome."\n";
        $linha .= "Email: ".$email."\n";
        $linha .= "Telefone: ".$telefone."\n";
        $linha .= "Instituição: ".$instituicao."\n";
        $linha .= "Assunto: ".$assunto."\n";
        $linha .= "Mensagem: ".$mensagem."\n";
        $linha .= "Newsletter: ".($newsletter ? "Sim" : "Não")."\n";
        $linha .= "=============================\n\n";

        file_put_contents(__DIR__."/logs/contatos.log",$linha,FILE_APPEND | LOCK_EX);

    }

    $resultado = [
        "sucesso" => empty($erros),
        "erros" => $erros,
        "campos" => compact(
            "nome",
            "email",
            "telefone",
            "instituicao",
            "assunto",
            "mensagem"
        ),
        "newsletter" => $newsletter,
        "data" => date("d/m/Y") . " às " . date("H:i")
    ];

}

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Contato</title>

<link rel="preconnect" href="https://fonts.googleapis.com">

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
}

body{

font-family:'Raleway',sans-serif;
background:#f5f5f5;
padding:40px;

}

.card{

max-width:750px;
margin:auto;
background:white;
border-radius:12px;
overflow:hidden;
box-shadow:0 5px 18px rgba(0,0,0,.15);

}

.topo{

padding:30px;

}

.topo.sucesso{

background:#eaf8ef;
border-bottom:5px solid #AD0000;

}

.topo.erro{

background:#fdecec;
border-bottom:5px solid #AD0000;

}

.topo h1{

font-size:30px;

}

.topo p{

margin-top:10px;
color:#555;

}

.conteudo{

padding:35px;

}

table{

width:100%;
border-collapse:collapse;
margin-bottom:30px;

}

th,td{

padding:14px;
border-bottom:1px solid #ddd;
text-align:left;

}

th{

width:35%;
background:#fafafa;

}

.mensagem{

background:#f8f8f8;
padding:18px;
border-left:5px solid #AD0000;
margin-top:15px;
margin-bottom:25px;
white-space:pre-wrap;

}

.erros{

margin-bottom:25px;

}

.erros li{

margin-bottom:10px;
color:#AD0000;

}

.botao{

display:inline-block;
background:#AD0000;
color:white;
padding:14px 24px;
text-decoration:none;
border-radius:8px;

}

.botao:hover{

background:#880000;

}

</style>

</head>

<body>

<div class="card">

<?php if($resultado === null): ?>

<div class="topo erro">

<h1>Página de processamento</h1>

<p>Esta página recebe os dados enviados pelo formulário de contato.</p>

</div>

<div class="conteudo">

<p>Para visualizar o resultado, acesse a página de contato e envie o formulário.</p>

<br>

<a href="contato.html" class="botao">Voltar ao formulário</a>

</div>

<?php elseif($resultado["sucesso"]): ?>

<div class="topo sucesso">

<h1>Mensagem enviada com sucesso!</h1>

<p>Recebemos seus dados em <?php echo $resultado["data"]; ?>.</p>

</div>

<div class="conteudo">

<table>

<tr>
<th>Nome</th>
<td><?php echo $resultado["campos"]["nome"]; ?></td>
</tr>

<tr>
<th>Email</th>
<td><?php echo $resultado["campos"]["email"]; ?></td>
</tr>

<tr>
<th>Telefone</th>
<td><?php echo $resultado["campos"]["telefone"]; ?></td>
</tr>

<tr>
<th>Instituição</th>
<td><?php echo $resultado["campos"]["instituicao"]; ?></td>
</tr>

<tr>
<th>Assunto</th>
<td><?php echo $resultado["campos"]["assunto"]; ?></td>
</tr>

<tr>
<th>Receber novidades</th>
<td>
<?php
echo $resultado["newsletter"] ? "Sim" : "Não";
?>
</td>
</tr>

</table>

<h3>Mensagem</h3>

<div class="mensagem">

<?php echo nl2br($resultado["campos"]["mensagem"]); ?>

</div>

<a href="contato.html" class="botao">
Voltar
</a>

</div>

<?php else: ?>

<div class="topo erro">

<h1>Não foi possível enviar a mensagem</h1>

<p>Corrija os erros abaixo e tente novamente.</p>

</div>

<div class="conteudo">

<ul class="erros">

<?php

foreach($resultado["erros"] as $erro){

echo "<li>".$erro."</li>";

}

?>

</ul>

<a href="javascript:history.back()" class="botao">

Voltar ao formulário

</a>

</div>

<?php endif; ?>

</div>

</body>

</html>