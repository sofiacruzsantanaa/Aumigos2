<?php

session_start();

if (!isset($_SESSION['logado'])) {
    header("Location: login.php");
    exit();
}

$paginaCSS = 'assets/css/favoritos.css';

// Fallback: if the CSS file exists, we can optionally inline it into the head
$cssPath = __DIR__ . '/assets/css/favoritos.css';
if (file_exists($cssPath)) {
  $paginaCSS_INLINE = file_get_contents($cssPath);
}

$arqUsers = __DIR__ . '/data/users.json';
$arqCaes = __DIR__ . '/data/caes.json';

$users = [];
$caes = [];
$curtidos = [];

if (file_exists($arqUsers)) {
    $dadosUsers = json_decode(file_get_contents($arqUsers), true);
    if (is_array($dadosUsers)) {
        $users = $dadosUsers;
    }
}

if (file_exists($arqCaes)) {
    $dadosCaes = json_decode(file_get_contents($arqCaes), true);
    if (is_array($dadosCaes)) {
        $caes = $dadosCaes;
    }
}

foreach ($users as $u) {
    if (($u['email'] ?? '') === $_SESSION['usuario']) {
        $curtidos = $u['curtidos'] ?? [];
        break;
    }
}

$caesCurtidos = array_filter($caes, function ($c) use ($curtidos) {
    return in_array((int) ($c['id'] ?? 0), $curtidos);
});

include_once 'includes/header.php';

?>

<main>

<section class="catalogo-hero">
  <h1>Meus favoritos</h1>
  <p>Os cães que você curtiu estão aqui.</p>
</section>

<?php if (empty($caesCurtidos)): ?>

  <div class="favoritos-vazio">
    <div class="favoritos-vazio-icone">♡</div>
    <h2>Nenhum favorito ainda</h2>
    <p>Explore os cães disponíveis e curta os que você mais gostar!</p>
    <a href="catalogo.php" class="btn-ver-caes">Ver cães disponíveis</a>
  </div>

<?php else: ?>

  <section class="catalogo">
    <?php foreach ($caesCurtidos as $cao): 
      $id = (int) ($cao['id'] ?? 0);
      $saudavel = ($cao['saude'] ?? '') === 'Saudável';
    ?>

    <div class="card-cachorro">
      <div class="card-img">
  <img src="<?php echo htmlspecialchars(resolve_asset_path($cao['foto'] ?? '')); ?>" alt="<?php echo htmlspecialchars($cao['nome'] ?? 'Cão'); ?>">

        <form method="POST" action="controllers/con_curtir.php">
          <input type="hidden" name="id" value="<?php echo $id; ?>">
          <input type="hidden" name="redirect" value="../favoritos.php">
          <button type="submit" class="btn-favorito favoritado" title="Remover dos favoritos">♥</button>
        </form>
      </div>

      <div class="card-body">
        <div class="card-header">
          <h2><?php echo htmlspecialchars($cao['nome'] ?? 'Sem nome'); ?></h2>
          <span class="<?php echo $saudavel ? 'badge-saudavel' : 'badge-doenca'; ?>">
            <?php echo htmlspecialchars($cao['saude'] ?? 'Não informado'); ?>
          </span>
        </div>

        <div class="card-infos">
          <span class="info-item"><strong>Raça</strong> <?php echo htmlspecialchars($cao['raca'] ?? 'Não informado'); ?></span>
          <span class="info-item"><strong>Idade</strong> <?php echo htmlspecialchars($cao['idade'] ?? 'Não informado'); ?></span>
          <span class="info-item"><strong>Porte</strong> <?php echo htmlspecialchars($cao['porte'] ?? 'Não informado'); ?></span>
        </div>

        <p class="card-desc"><?php echo htmlspecialchars($cao['descricao'] ?? ''); ?></p>

        <?php if ($cao['disponivel'] ?? false): ?>
          <a href="adotar.php?id=<?php echo $id; ?>" class="btn-adotar">Quero adotar</a>
        <?php else: ?>
          <button class="btn-adotar btn-indisponivel" disabled>Indisponível</button>
        <?php endif; ?>
      </div>
    </div>

    <?php endforeach; ?>
  </section>

<?php endif; ?>

</main>

<?php include_once 'includes/footer.php'; ?>