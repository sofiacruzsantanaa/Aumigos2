<?php
session_start();

$arquivo = __DIR__ . '/../data/users.json';

$usuario = trim($_POST['usuario'] ?? '');
$senha   = trim($_POST['senha'] ?? '');

if ($usuario === '' || $senha === '') {
    header("Location: ../login.php?erro=1");
    exit();
}

if (!file_exists($arquivo)) {
    header("Location: ../login.php?erro=1");
    exit();
}

$usuarios = json_decode(file_get_contents($arquivo), true);

if (!is_array($usuarios)) {
    header("Location: ../login.php?erro=1");
    exit();
}

foreach ($usuarios as $u) {
    $emailSalvo = $u['email'] ?? '';
    $senhaSalva = $u['senha'] ?? '';

    $loginCorreto = strtolower($usuario) === strtolower($emailSalvo);

    // Aceita senha normal ou senha criptografada com password_hash
    $senhaCorreta = $senha === $senhaSalva || password_verify($senha, $senhaSalva);

    if ($loginCorreto && $senhaCorreta) {
        $_SESSION['logado'] = true;
        $_SESSION['usuario'] = $emailSalvo;
        $_SESSION['nome'] = $u['nome'] ?? '';

        header("Location: ../inicio.php");
        exit();
    }
}

header("Location: ../login.php?erro=1");
exit();