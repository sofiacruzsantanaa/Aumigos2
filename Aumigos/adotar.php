<?php include_once 'includes/header.php'; ?>
<link rel="stylesheet" href="assets/css/adotar.css">

<main>
<?php
if (!isset($_SESSION['logado'])) {
    header("Location: login.php");
    exit();
}

$id   = intval($_GET['id'] ?? 0);
$caes = json_decode(file_get_contents(__DIR__ . '/data/caes.json'), true);

$cao = null;
foreach ($caes as $c) {
    if ($c['id'] === $id) { $cao = $c; break; }
}

if (!$cao || !$cao['disponivel']) {
    echo '<div class="adotar-vazio"><p>Cão não encontrado ou já adotado. <a href="catalogo.php">Voltar ao catálogo</a></p></div>';
    include_once 'includes/footer.php';
    exit();
}


$erro = $_GET['erro'] ?? '';
?>

<div class="adotar-box">


  <div class="adotar-cao">
    <img src="<?php echo htmlspecialchars($cao['foto']); ?>" alt="<?php echo htmlspecialchars($cao['nome']); ?>">
    <div class="adotar-cao-info">
      <h2><?php echo htmlspecialchars($cao['nome']); ?></h2>
      <span class="<?php echo $cao['saude'] === 'Saudável' ? 'badge-saudavel' : 'badge-doenca'; ?>">
        <?php echo htmlspecialchars($cao['saude']); ?>
      </span>
      <div class="adotar-detalhes">
        <div class="detalhe-item">
          <span class="detalhe-label">Raça</span>
          <span class="detalhe-valor"><?php echo htmlspecialchars($cao['raca']); ?></span>
        </div>
        <div class="detalhe-item">
          <span class="detalhe-label">Idade</span>
          <span class="detalhe-valor"><?php echo htmlspecialchars($cao['idade']); ?></span>
        </div>
        <div class="detalhe-item">
          <span class="detalhe-label">Porte</span>
          <span class="detalhe-valor"><?php echo htmlspecialchars($cao['porte']); ?></span>
        </div>
      </div>
      <p class="adotar-desc"><?php echo htmlspecialchars($cao['descricao']); ?></p>
    </div>
  </div>

  <!-- Coluna direita: formulário -->
  <div class="adotar-form">
    <h1>Formulário de adoção</h1>
    <p class="form-subtitulo">Conte um pouco sobre você para adotar <strong><?php echo htmlspecialchars($cao['nome']); ?></strong></p>

    <?php if ($erro): ?>
      <div class="form-erro">Preencha todos os campos antes de continuar.</div>
    <?php endif; ?>

   <form action="requisitos.php" method="POST">>
      <input type="hidden" name="id" value="<?php echo $cao['id']; ?>">

      <div class="campo">
        <label>Por que você quer adotar <?php echo htmlspecialchars($cao['nome']); ?>?</label>
        <textarea name="motivo" placeholder="Conte sua motivação..." rows="3" required></textarea>
      </div>

      <div class="campo">
        <label>Quantas pessoas moram na sua casa?</label>
        <select name="pessoas" required>
          <option value="">Selecione</option>
          <option value="1">Moro sozinho(a)</option>
          <option value="2">2 pessoas</option>
          <option value="3">3 pessoas</option>
          <option value="4">4 pessoas</option>
          <option value="5+">5 ou mais pessoas</option>
        </select>
      </div>

      <div class="campo">
        <label>Tem outros animais em casa?</label>
        <div class="opcoes">
          <label class="opcao"><input type="radio" name="outros_animais" value="Não" required> Não</label>
          <label class="opcao"><input type="radio" name="outros_animais" value="Sim, cachorros"> Sim, cachorros</label>
          <label class="opcao"><input type="radio" name="outros_animais" value="Sim, gatos"> Sim, gatos</label>
          <label class="opcao"><input type="radio" name="outros_animais" value="Sim, outros"> Sim, outros</label>
        </div>
      </div>

      <div class="campo">
        <label>Sua moradia tem quintal?</label>
        <div class="opcoes">
          <label class="opcao"><input type="radio" name="quintal" value="Sim" required> Sim</label>
          <label class="opcao"><input type="radio" name="quintal" value="Não"> Não</label>
        </div>
      </div>

      <div class="campo">
        <label>Já teve cachorro antes?</label>
        <div class="opcoes">
          <label class="opcao"><input type="radio" name="experiencia" value="Sim" required> Sim</label>
          <label class="opcao"><input type="radio" name="experiencia" value="Não"> Não, mas estou preparado(a)</label>
        </div>
      </div>

      <div class="adotar-aviso">
        <strong>Atenção:</strong> ao confirmar, você assume o compromisso de cuidar com amor e responsabilidade de <?php echo htmlspecialchars($cao['nome']); ?>.
      </div>

      <button type="submit" class="btn-confirmar">Confirmar adoção</button>
      <a href="catalogo.php" class="btn-voltar">Cancelar</a>
    </form>
  </div>

</div>
</main>

<?php include_once 'includes/footer.php'; ?>