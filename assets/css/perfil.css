<?php include_once 'includes/header.php'; ?>
<link rel="stylesheet" href="assets/css/perfil.css">

<main>
<?php
$arquivo = 'data/users.json';

$usuarioLogado = null;
if (file_exists($arquivo)) {
    $usuarios = json_decode(file_get_contents($arquivo), true);
    foreach ($usuarios as $u) {
        if ($u['email'] === $_SESSION['usuario']) {
            $usuarioLogado = $u;
            break;
        }
    }
}
?>

<?php if ($usuarioLogado): ?>

<div class="container">
  <div class="perfil">

    
    <div class="banner"></div>

    <div class="dados">

      
      <?php if (!empty($usuarioLogado['foto'])): ?>
        <img src="<?php echo htmlspecialchars($usuarioLogado['foto']); ?>" class="foto" alt="Foto de perfil">
      <?php else: ?>
        <div class="foto" style="display:flex; align-items:center; justify-content:center; font-size:56px; background:#d6f0dc;">
          🐾
        </div>
      <?php endif; ?>

      <h1><?php echo htmlspecialchars($usuarioLogado['nome']); ?></h1>
      <p class="bio"><?php echo htmlspecialchars($usuarioLogado['email']); ?></p>

      <!-- Upload de foto -->
      <form action="controllers/con_foto.php" method="POST" enctype="multipart/form-data" style="margin-top: 14px;">
        <label class="btn-upload">
          Alterar foto
          <input type="file" name="foto" accept="image/*" style="display:none;" onchange="this.form.submit()">
        </label>
      </form>
    </div>

    <div class="estatisticas">
      <div class="card">
        <h2>0</h2>
        <p>Adoções</p>
      </div>
      <div class="card">
        <h2>0</h2>
        <p>Favoritos</p>
      </div>
      <div class="card">
        <h2>⭐</h2>
        <p>Adotante</p>
      </div>
    </div>

    <!-- Informações pessoais -->
    <div class="secao">
      <h3>Informações pessoais</h3>
      <div class="info-grid">

        <div class="info-item">
          <span class="info-label">Nome completo</span>
          <span class="info-valor"><?php echo htmlspecialchars($usuarioLogado['nome']); ?></span>
        </div>

        <div class="info-item">
          <span class="info-label">E-mail</span>
          <span class="info-valor"><?php echo htmlspecialchars($usuarioLogado['email']); ?></span>
        </div>

        <div class="info-item">
          <span class="info-label">CPF</span>
          <span class="info-valor"><?php echo htmlspecialchars($usuarioLogado['cpf']); ?></span>
        </div>

        <div class="info-item">
          <span class="info-label">Telefone</span>
          <span class="info-valor"><?php echo htmlspecialchars($usuarioLogado['telefone']); ?></span>
        </div>

        <div class="info-item" style="grid-column: 1 / -1;">
          <span class="info-label">Endereço</span>
          <span class="info-valor"><?php echo htmlspecialchars($usuarioLogado['endereco']); ?></span>
        </div>

      </div>
<a href="favoritos.php" class="btn-sair" style="text-align:center; margin-top:10px; margin-bottom:8px; background-color:#B9E4A5; color:#3a6b2a;">♡ Meus favoritos</a>
<a href="sair.php" class="btn-sair" style="text-align:center; margin-top:10px;">Sair da conta</a>
     
    </div>

  </div>
</div>

<?php else: ?>
  <div style="text-align:center; padding: 60px 20px;">
    <p style="font-size:16px; color:#5f7262;">Usuário não encontrado. <a href="login.php" style="color:#43a047; font-weight:700;">Faça login</a></p>
  </div>
<?php endif; ?>

</main>

<?php include_once 'includes/footer.php'; ?>