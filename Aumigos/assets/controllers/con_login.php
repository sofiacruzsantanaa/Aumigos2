<?php
session_start();

$usuario = $_POST['usuario'];
$senha   = $_POST['senha'];

$arquivo = '../data/users.json';

function autenticarUsuario(array $usuarios, string $email, string $senha): ?array {
    foreach ($usuarios as $u) {
        if ($u['email'] === $email && password_verify($senha, $u['senha'])) {
            return $u;
        }
    }
    return null;
}

function iniciarSessao(array $usuario): void {
    $_SESSION['logado']  = true;
    $_SESSION['usuario'] = $usuario['email'];
}

if (!file_exists($arquivo)) {
    header("Location: ../login.php?erro=1");
    exit();
}

$usuarios      = json_decode(file_get_contents($arquivo), true);
$usuarioLogado = autenticarUsuario($usuarios, $usuario, $senha);

if ($usuarioLogado) {
    iniciarSessao($usuarioLogado);
    header("Location: ../inicio.php");
} else {
    header("Location: ../login.php?erro=1");
}
?>