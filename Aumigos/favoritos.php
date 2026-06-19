<?php
$paginaCSS = 'assets/css/favoritos.css';
include_once 'includes/header.php';
?>
<main>
<?php
if (!isset($_SESSION['logado'])) {
    header("Location: login.php");
    exit();
}

$arq_users = __DIR__ . '/data/users.json';
$arq_caes  = __DIR__ . '/data/caes.json';

$users = json_decode(file_get_contents($arq_users), true);
$caes  = json_decode(file_get_contents($arq_caes), true);

$curtidos = [];
foreach ($users as $u) {
    if ($u['email'] === $_SESSION['usuario']) {
        $curtidos = $u['curtidos'] ?? [];
        break;
    }
}

// Desfavoritar via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    foreach ($users as &$u) {
        if ($u['email'] === $_SESSION['usuario']) {
            $u['curtidos'] = array_values(array_filter($u['curtidos'] ?? [], fn($c) => $c !== $id));
            $curtidos = $u['curtidos'];
            break;
        }
    }
    file_put_contents($arq_users, json_encode($users, JSON_PRETTY_PRINT));
    header("Location: favoritos.php");
    exit();
}

// Filtra só os cães curtidos
$caesCurtidos = array_filter($caes, fn($c) => in_array($c['id'], $curtidos));
?>

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
      $saudavel = $cao['saude'] === 'Saudável';
    ?>
    <div class="card-cachorro">
      <div class="card-img">
        <img src="<?php echo htmlspecialchars($cao['foto']); ?>" alt="<?php echo htmlspecialchars($cao['nome']); ?>">
        <form method="POST">
          <input type="hidden" name="id" value="<?php echo $cao['id']; ?>">
          <button type="submit" class="btn-favorito favoritado" title="Remover dos favoritos">♥</button>
        </form>
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
        <?php if ($cao['disponivel']): ?>
          <a href="adotar.php?id=<?php echo $cao['id']; ?>" class="btn-adotar">Quero adotar</a>
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