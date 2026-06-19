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

    <?php
        // Fixed base path for this project in MAMP. Adjust if your project path changes.
        // Using an explicit absolute base avoids path resolution issues in includes.
        $base = '/CODIGOSWELISON/Aumigos';

        function asset_href($path) {
            global $base;
            $p = str_replace('\\', '/', $path);
            return $base . '/' . ltrim($p, '/');
        }
        
        /**
         * Resolve a path that may come from data (JSON) to a public URL.
         * If it's already an absolute URL (starts with http or /) returns as-is.
         * If it starts with assets/ or uploads/ or is a relative path, convert to absolute using asset_href().
         */
        function resolve_asset_path($path) {
            if (empty($path)) return '';
            $p = trim($path);
            // already absolute URL
            if (preg_match('#^(https?:)?//#i', $p) || strpos($p, '/') === 0) {
                return $p;
            }
            // likely relative to project (assets or uploads)
            if (strpos($p, 'assets/') === 0 || strpos($p, 'uploads/') === 0) {
                return asset_href($p);
            }
            // default: treat as relative asset
            return asset_href($p);
        }
    ?>

    <link rel="stylesheet" href="<?php echo htmlspecialchars(asset_href('assets/css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(asset_href('assets/css/footer.css')); ?>">

    <?php if (isset($paginaCSS)): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars(asset_href($paginaCSS)); ?>">
    <?php endif; ?>
    <?php
        // If a page provided inline CSS content in $paginaCSS_INLINE, print it inside <style>
        if (isset($paginaCSS_INLINE) && is_string($paginaCSS_INLINE) && trim($paginaCSS_INLINE) !== ''): ?>
            <style>
                <?php echo $paginaCSS_INLINE; ?>
            </style>
    <?php endif; ?>
</head>

<body>

<h1>AUmas Gêmeas</h1>

<header>
    <div class="nav-links">
        <a href="<?php echo htmlspecialchars(asset_href('inicio.php')); ?>">Início</a>
        <a href="<?php echo htmlspecialchars(asset_href('catalogo.php')); ?>">AUmigos</a>

        <?php if (isset($_SESSION['logado'])): ?>
            <a href="<?php echo htmlspecialchars(asset_href('favoritos.php')); ?>">Favoritos</a>
        <?php endif; ?>
    </div>

    <div class="nav-right">
        <?php if (isset($_SESSION['logado'])): ?>
            <a href="<?php echo htmlspecialchars(asset_href('perfil.php')); ?>" class="btn-castrar">Perfil</a>
            <a href="<?php echo htmlspecialchars(asset_href('sair.php')); ?>" class="btn-entrar">Sair</a>
        <?php else: ?>
            <a href="<?php echo htmlspecialchars(asset_href('cadastro.php')); ?>" class="btn-castrar">Cadastrar</a>
            <a href="<?php echo htmlspecialchars(asset_href('login.php')); ?>" class="btn-entrar">Entrar</a>
        <?php endif; ?>
    </div>
</header>