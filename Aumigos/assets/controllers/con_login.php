<?php
session_start();

$usuario = $_POST['usuario'];
$senha   = $_POST['senha'];

$arquivo = '../data/users.json';

// -------------------------------------------------------
// FUNÇÃO: Busca o usuário pelo e-mail e valida a senha
// Retorna o usuário encontrado ou null
// -------------------------------------------------------
function autenticarUsuario(array $usuarios, string $email, string $senha): ?array {
    foreach ($usuarios as $u) {
        if ($u['email'] === $email && password_verify($senha, $u['senha'])) {
            return $u;
        }
    }
    return null;
}

// -------------------------------------------------------
// FUNÇÃO: Inicia a sessão do usuário logado
// -------------------------------------------------------
function iniciarSessao(array $usuario): void {
    $_SESSION['logado']  = true;
    $_SESSION['usuario'] = $usuario['email'];
}

// Verifica se o arquivo de usuários existe
if (!file_exists($arquivo)) {
    header("Location: ../login.php?erro=1");
    exit();
}

// Carrega os usuários e tenta autenticar
$usuarios      = json_decode(file_get_contents($arquivo), true);
$usuarioLogado = autenticarUsuario($usuarios, $usuario, $senha);

if ($usuarioLogado) {
    iniciarSessao($usuarioLogado);
    header("Location: ../perfil.php");
} else {
    header("Location: ../login.php?erro=1");
}
?>