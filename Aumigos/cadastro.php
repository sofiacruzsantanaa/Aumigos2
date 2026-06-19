<?php
$paginaCSS = 'assets/css/login.css';
include_once 'includes/header.php';
?>

<main>
  <h2>Crie sua conta</h2>

  <?php if (isset($_GET['erro'])): ?>
    <p style="color:red; text-align:center;">Não foi possível realizar o cadastro. Verifique os dados.</p>
  <?php endif; ?>

  <?php if (isset($_GET['email'])): ?>
    <p style="color:red; text-align:center;">Este e-mail já está cadastrado.</p>
  <?php endif; ?>

  <form action="controllers/con_cadastro.php" method="POST">
    <input type="text" name="nome" placeholder="Nome completo" required>
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="password" name="senha" placeholder="Senha" required>
    <input type="text" name="cpf" placeholder="CPF" required>
    <input type="text" name="telefone" placeholder="Telefone" required>
    <input type="text" name="endereco" placeholder="Endereço" required>
    <input type="submit" value="Cadastrar">
  </form>

  <p style="text-align:center; margin-top:15px;">
    Já tem conta? <a href="login.php">Entrar</a>
  </p>
</main>

<?php include_once 'includes/footer.php'; ?>