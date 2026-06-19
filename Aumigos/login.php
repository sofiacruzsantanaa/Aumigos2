<?php
  $paginaCSS = 'assets/css/login.css';
  include_once 'includes/header.php';
?>

<main>
  <h2>Entre em sua conta</h2>

  <?php if (isset($_GET['erro'])): ?>
    <p style="color:red; text-align:center;">Email ou senha incorretos!</p>
  <?php endif; ?>

  <form action="controllers/con_loginnovo.php" method="POST">
    <input type="text" name="usuario" placeholder="Usuário">
    <input type="password" name="senha" placeholder="Senha">
    <input type="submit" value="Entrar">
  </form>
</main>

<?php include_once 'includes/footer.php'; ?>