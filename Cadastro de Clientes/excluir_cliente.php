<?php
require_once "config/conexao.php";
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id) {
    $stmt = $conexao->prepare("DELETE FROM clientes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}
header("Location: clientes.php?sucesso=excluido");
exit;
?>
