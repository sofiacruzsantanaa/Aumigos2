<?php include_once 'includes/header.php'; ?>
<link rel="stylesheet" href="assets/css/requisitos.css">
<main>
<?php
if (!isset($_SESSION['logado'])) {
    header("Location: login.php");
    exit();
}

// Recebe os dados do formulário anterior
$id          = intval($_POST['id'] ?? 0);
$motivo      = $_POST['motivo'] ?? '';
$pessoas     = $_POST['pessoas'] ?? '';
$animais     = $_POST['outros_animais'] ?? '';
$quintal     = $_POST['quintal'] ?? '';
$experiencia = $_POST['experiencia'] ?? '';

if (!$id || !$motivo || !$pessoas || !$animais || !$quintal || !$experiencia) {
    header("Location: catalogo.php");
    exit();
}

$caes = json_decode(file_get_contents(__DIR__ . '/data/caes.json'), true);
$cao  = null;
foreach ($caes as $c) {
    if ($c['id'] === $id) { $cao = $c; break; }
}

if (!$cao || !$cao['disponivel']) {
    header("Location: catalogo.php?erro=indisponivel");
    exit();
}
?>

<div class="req-wrap">

  <div class="req-header">
    <div class="req-step concluido">1 Perfil</div>
    <div class="req-linha concluido"></div>
    <div class="req-step ativo">2 Requisitos</div>
    <div class="req-linha"></div>
    <div class="req-step">3 Confirmação</div>
  </div>

  <h1>Requisitos para adoção</h1>
  <p class="req-sub">Você está quase lá! Leia com atenção e preencha os campos abaixo para adotar <strong><?php echo htmlspecialchars($cao['nome']); ?></strong>.</p>

  <?php if (isset($_GET['erro'])): ?>
    <div class="req-erro">Preencha todos os campos e aceite os termos antes de continuar.</div>
  <?php endif; ?>

  <form action="controllers/con_adotar.php" method="POST" enctype="multipart/form-data">

    <!-- Passa dados do formulário anterior -->
    <input type="hidden" name="id"             value="<?php echo $id; ?>">
    <input type="hidden" name="motivo"         value="<?php echo htmlspecialchars($motivo); ?>">
    <input type="hidden" name="pessoas"        value="<?php echo htmlspecialchars($pessoas); ?>">
    <input type="hidden" name="outros_animais" value="<?php echo htmlspecialchars($animais); ?>">
    <input type="hidden" name="quintal"        value="<?php echo htmlspecialchars($quintal); ?>">
    <input type="hidden" name="experiencia"    value="<?php echo htmlspecialchars($experiencia); ?>">

    <!-- Bloco 1: Documentação -->
    <div class="req-bloco">
      <div class="req-bloco-titulo">
        <span class="req-num">1</span>
        Documentação
      </div>
      <p class="req-bloco-desc">Envie os documentos necessários para realizar a adoção. Todos os arquivos devem estar legíveis.</p>

      <div class="campo-doc">
        <label>RG (frente e verso)</label>
        <input type="file" name="doc_rg" accept="image/*,.pdf" required>
      </div>

      <div class="campo-doc">
        <label>CPF</label>
        <input type="file" name="doc_cpf" accept="image/*,.pdf" required>
      </div>

      <div class="campo-doc">
        <label>Comprovante de residência (atualizado)</label>
        <input type="file" name="doc_residencia" accept="image/*,.pdf" required>
      </div>
    </div>

    <!-- Bloco 2: Concordância familiar -->
    <div class="req-bloco">
      <div class="req-bloco-titulo">
        <span class="req-num">2</span>
        Concordância familiar
      </div>
      <p class="req-bloco-desc">Todos os moradores da sua casa devem estar cientes e de acordo com a adoção.</p>

      <label class="check-label">
        <input type="checkbox" name="concordancia" required>
        <span>Confirmo que todos que moram comigo estão de acordo com a chegada de <?php echo htmlspecialchars($cao['nome']); ?>.</span>
      </label>
    </div>

    <!-- Bloco 3: Condições financeiras -->
    <div class="req-bloco">
      <div class="req-bloco-titulo">
        <span class="req-num">3</span>
        Posse responsável
      </div>
      <p class="req-bloco-desc">Ter um cachorro envolve custos mensais com alimentação, veterinário, vacinas e emergências.</p>

      <label class="check-label">
        <input type="checkbox" name="financeiro" required>
        <span>Declaro que tenho condições financeiras de arcar com os cuidados necessários para o bem-estar do animal.</span>
      </label>
    </div>

    <!-- Bloco 4: Taxa de adoção -->
    <div class="req-bloco">
      <div class="req-bloco-titulo">
        <span class="req-num">4</span>
        Taxa de adoção
      </div>
      <p class="req-bloco-desc">A taxa cobre custos operacionais como vacinas, vermifugação e cuidados pré-adoção. <strong>Não é a compra do animal.</strong></p>

      <div class="taxa-box">
        <div class="taxa-valor">R$ 30,00</div>
        <div class="taxa-desc">Taxa única de adoção</div>
      </div>

      <label class="check-label">
        <input type="checkbox" name="taxa" required>
        <span>Estou ciente da taxa de adoção no valor de R$ 150,00 e concordo com o pagamento.</span>
      </label>
    </div>

    <!-- Bloco 5: Termo de responsabilidade -->
    <div class="req-bloco">
      <div class="req-bloco-titulo">
        <span class="req-num">5</span>
        Termo de responsabilidade
      </div>

      <div class="termo-texto">
        <p>Eu, abaixo identificado, declaro que estou adotando o animal <strong><?php echo htmlspecialchars($cao['nome']); ?></strong> de forma consciente e responsável, comprometendo-me a:</p>
        <ul>
          <li>Garantir alimentação adequada, água limpa e abrigo seguro;</li>
          <li>Manter as vacinas e consultas veterinárias em dia;</li>
          <li>Não submeter o animal a maus-tratos ou abandono;</li>
          <li>Comunicar a plataforma em caso de impossibilidade de continuar com a tutoria;</li>
          <li>Zelar pelo bem-estar físico e emocional do animal.</li>
        </ul>
        <p style="margin-top:10px;">O descumprimento deste termo pode acarretar em penalidades previstas na Lei de Crimes Ambientais (Lei nº 9.605/98) e na Lei do Bem-Estar Animal (Lei nº 14.064/20).</p>
      </div>

      <label class="check-label">
        <input type="checkbox" name="termo" required>
        <span>Li e aceito o termo de responsabilidade acima.</span>
      </label>
    </div>

    <div class="req-btns">
      <button type="submit" class="btn-confirmar">Finalizar adoção</button>
      <a href="adotar.php?id=<?php echo $id; ?>" class="btn-voltar">Voltar</a>
    </div>

  </form>
</div>
</main>

<?php include_once 'includes/footer.php'; ?>