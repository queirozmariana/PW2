<?php
require_once "config/conexao.php";
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$clienteId = 0;

if ($id) {
    $stmt = $conexao->prepare("SELECT cliente_id FROM compras WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_assoc();
    $clienteId = (int)($resultado['cliente_id'] ?? 0);
    $stmt->close();

    $stmt = $conexao->prepare("DELETE FROM compras WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

if ($clienteId > 0) {
    header("Location: cliente.php?id=".$clienteId);
} else {
    header("Location: compras.php");
}
exit;
?>
