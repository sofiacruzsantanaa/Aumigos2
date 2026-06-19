<?php include_once 'includes/header.php'; ?>
<link rel="stylesheet" href="assets/css/cadastro.css">

    <h2>Crie sua conta!</h2>

    <?php if (isset($_GET['erro'])): ?>
        <?php if ($_GET['erro'] == 'senha'): ?>
            <p style="color:red;">As senhas não coincidem!</p>
        <?php else: ?>
            <p style="color:red;">Preencha todos os campos corretamente!</p>
        <?php endif; ?>
    <?php endif; ?>

    <form action="controllers/con_cadastro.php" method="POST">

        <input type="text" name="nome" placeholder="Nome completo">
        <input type="email" name="email" placeholder="E-mail">
        <input type="text" name="cpf" placeholder="CPF" maxlength="14">
        <input type="text" name="telefone" placeholder="Telefone" maxlength="15">
        <input type="text" name="endereco" placeholder="Endereço">
        <input type="password" name="senha" placeholder="Senha">
        <input type="password" name="senha_confirm" placeholder="Confirmar senha">
        <input type="submit" value="Cadastrar">

    </form>



    <p style="margin-top: 16px; font-size: 14px;">
        Já tem conta? <a href="login.php">Entrar</a>
    </p>
</main>


</main>

<?php include_once 'includes/footer.php'; ?>