<?php include_once 'includes/header.php'; ?>
<link rel="stylesheet" href="assets/css/catalogo.css">

<main>

  <section class="catalogo-hero">
    <h1>AUmigos disponíveis</h1>
    <p>Conheça os cães que estão esperando por um lar cheio de amor.</p>
  </section>

  <section class="catalogo">
  <?php
    $caes = json_decode(file_get_contents(__DIR__ . '/data/caes.json'), true);
    foreach ($caes as $cao):
      $disponivel = $cao['disponivel'];
      $saudavel   = $cao['saude'] === 'Saudável';
  ?>

    <div class="card-cachorro <?php echo !$disponivel ? 'indisponivel' : ''; ?>">
      <div class="card-img">
        <img src="<?php echo htmlspecialchars($cao['foto']); ?>" alt="<?php echo htmlspecialchars($cao['nome']); ?>">
        <?php if (!$disponivel): ?>
          <div class="badge-indisponivel">Adotado 🐾</div>
        <?php endif; ?>
        <button class="btn-favorito" title="Favoritar">♡</button>
      </div>
      <div class="card-body">
        <div class="card-header">
          <h2><?php echo htmlspecialchars($cao['nome']); ?></h2>
          <span class="<?php echo $saudavel ? 'badge-saudavel' : 'badge-doenca'; ?>">
            <?php echo htmlspecialchars($cao['saude']); ?>
          </span>
        </div>
        <div class="card-infos">
          <span class="info-item"><strong>Raça</strong> <?php echo htmlspecialchars($cao['raca']); ?></span>
          <span class="info-item"><strong>Idade</strong> <?php echo htmlspecialchars($cao['idade']); ?></span>
          <span class="info-item"><strong>Porte</strong> <?php echo htmlspecialchars($cao['porte']); ?></span>
        </div>
        <p class="card-desc"><?php echo htmlspecialchars($cao['descricao']); ?></p>

        <?php if ($disponivel): ?>
          <a href="adotar.php?id=<?php echo $cao['id']; ?>" class="btn-adotar">Quero adotar</a>
        <?php else: ?>
          <button class="btn-adotar btn-indisponivel" disabled>Indisponível</button>
        <?php endif; ?>
      </div>
    </div>

  <?php endforeach; ?>
  </section>

</main>

<script>
  
<?php
$users = json_decode(file_get_contents(__DIR__ . '/data/users.json'), true);
$curtidos = [];
if (isset($_SESSION['usuario'])) {
    foreach ($users as $u) {
        if ($u['email'] === $_SESSION['usuario']) {
            $curtidos = $u['curtidos'] ?? [];
            break;
        }
    }
}
?>