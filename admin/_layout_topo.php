<?php
/**
 * Layout do painel admin. Cada página do admin faz:
 *   require 'includes/db.php';
 *   $pagina_admin = 'produtos'; // pra destacar o menu certo
 *   require 'admin/_layout_topo.php';
 *   ... conteúdo da página ...
 *   require 'admin/_layout_rodape.php';
 */
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin — Heartbeasties</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600;700&family=Nunito:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="d-flex">
    <nav class="admin-sidebar p-3">
        <div class="brand mb-4"><i class="bi bi-heart-fill"></i> Heartbeasties</div>
        <a href="dashboard.php" class="<?php echo ($pagina_admin ?? '') === 'dashboard' ? 'active' : ''; ?>">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>
        <a href="categorias.php" class="<?php echo ($pagina_admin ?? '') === 'categorias' ? 'active' : ''; ?>">
            <i class="bi bi-tags-fill me-2"></i> Categorias
        </a>
        <a href="produtos.php" class="<?php echo ($pagina_admin ?? '') === 'produtos' ? 'active' : ''; ?>">
            <i class="bi bi-box-seam-fill me-2"></i> Produtos
        </a>
        <a href="clientes.php" class="<?php echo ($pagina_admin ?? '') === 'clientes' ? 'active' : ''; ?>">
            <i class="bi bi-people-fill me-2"></i> Clientes
        </a>
        <a href="relatorio.php" class="<?php echo ($pagina_admin ?? '') === 'relatorio' ? 'active' : ''; ?>">
            <i class="bi bi-bar-chart-fill me-2"></i> Relatório
        </a>
        <hr class="text-white-50">
        <a href="../index.php"><i class="bi bi-box-arrow-left me-2"></i> Voltar ao site</a>
    </nav>
    <main class="flex-fill p-4">