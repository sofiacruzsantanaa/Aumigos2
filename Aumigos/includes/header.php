<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AUmas Gêmeas</title>

    <!-- CSS Global -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- CSS específico da página -->
    <?php if (isset($paginaCSS)): ?>
        <link rel="stylesheet" href="<?= htmlspecialchars($paginaCSS) ?>">
    <?php endif; ?>
</head>

<body>

<h1>AUmas Gêmeas</h1>

<header>
    <div class="nav-links">
        <a href="inicio.php">Início</a>
        <a href="catalogo.php">AUmigos</a>

        <?php if (isset($_SESSION['logado'])): ?>
            <a href="favoritos.php">Favoritos</a>
        <?php endif; ?>
    </div>

    <div class="nav-right">
        <?php if (isset($_SESSION['logado'])): ?>
            <a href="perfil.php" class="btn-castrar">Perfil</a>
            <a href="sair.php" class="btn-entrar">Sair</a>
        <?php else: ?>
            <a href="cadastro.php" class="btn-castrar">Cadastrar</a>
            <a href="login.php" class="btn-entrar">Entrar</a>
        <?php endif; ?>
    </div>
</header>