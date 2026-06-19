<?php
$paginaCSS = 'assets/css/catalogo.css';
include_once 'includes/header.php';

$arqCaes = __DIR__ . '/data/caes.json';
$arqUsers = __DIR__ . '/data/users.json';

$caes = [];
$users = [];
$curtidos = [];

if (file_exists($arqCaes)) {
    $dadosCaes = json_decode(file_get_contents($arqCaes), true);
    if (is_array($dadosCaes)) {
        $caes = $dadosCaes;
    }
}

if (file_exists($arqUsers)) {
    $dadosUsers = json_decode(file_get_contents($arqUsers), true);
    if (is_array($dadosUsers)) {
        $users = $dadosUsers;
    }
}

if (isset($_SESSION['usuario'])) {
    foreach ($users as $u) {
        if (($u['email'] ?? '') === $_SESSION['usuario']) {
            $curtidos = $u['curtidos'] ?? [];
            break;
        }
    }
}
?>

<main>

  <section class="catalogo-hero">
    <h1>AUmigos disponíveis</h1>
    <p>Conheça os cães que estão esperando por um lar cheio de amor.</p>
  </section>

  <?php if (isset($_GET['adotado'])): ?>
    <p style="text-align:center; color:green; font-weight:bold;">Adoção finalizada com sucesso.</p>
  <?php endif; ?>

  <?php if (empty($caes)): ?>
    <div style="text-align:center; padding:40px;">
      <p>Nenhum cão cadastrado no momento.</p>
    </div>
  <?php else: ?>

  <section class="catalogo">
  <?php foreach ($caes as $cao): 
    $id = (int) ($cao['id'] ?? 0);
    $disponivel = $cao['disponivel'] ?? false;
    $saudavel = ($cao['saude'] ?? '') === 'Saudável';
    $favoritado = in_array($id, $curtidos);
  ?>

    <div class="card-cachorro <?php echo !$disponivel ? 'indisponivel' : ''; ?>">
      <div class="card-img">
  <img src="<?php echo htmlspecialchars(resolve_asset_path($cao['foto'] ?? '')); ?>" alt="<?php echo htmlspecialchars($cao['nome'] ?? 'Cão'); ?>">

        <?php if (!$disponivel): ?>
          <div class="badge-indisponivel">Adotado 🐾</div>
        <?php endif; ?>

        <?php if (isset($_SESSION['logado'])): ?>
          <form method="POST" action="controllers/con_curtir.php">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="redirect" value="../catalogo.php">
            <button type="submit" class="btn-favorito <?php echo $favoritado ? 'favoritado' : ''; ?>" title="Favoritar">
              <?php echo $favoritado ? '♥' : '♡'; ?>
            </button>
          </form>
        <?php endif; ?>
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

        <?php if ($disponivel): ?>
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