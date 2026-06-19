<?php
session_start();

function obterDadosLogin(): array {
    return [
        'usuario' => $_POST['usuario'] ?? '',
        'senha'   => $_POST['senha']   ?? '',
    ];
}

function carregarUsuarios(string $arquivo): ?array {
    if (!file_exists($arquivo)) return null;
    return json_decode(file_get_contents($arquivo), true);
}

function autenticarUsuario(array $usuarios, string $email, string $senha): ?array {
    foreach ($usuarios as $u) {
        if (!empty($u['email']) && $u['email'] === $email && password_verify($senha, $u['senha'])) {
            return $u;
        }
    }
    return null;
}

function iniciarSessao(array $usuario): void {
    $_SESSION['logado']  = true;
    $_SESSION['usuario'] = $usuario['email'];
}

function redirecionar(string $destino): void {
    header("Location: " . $destino);
    exit();
}

function processarLogin(): void {
    $dados    = obterDadosLogin();
    $usuarios = carregarUsuarios(__DIR__ . '/../data/users.json');

    if ($usuarios === null) {
        redirecionar('/CODIGOSWELISON/Aumigos/login.php?erro=1');
    }

    $usuarioLogado = autenticarUsuario($usuarios, $dados['usuario'], $dados['senha']);

    if ($usuarioLogado) {
        iniciarSessao($usuarioLogado);
        redirecionar('/CODIGOSWELISON/Aumigos/inicio.php');
    } else {
        redirecionar('/CODIGOSWELISON/Aumigos/login.php?erro=1');
    }
}

processarLogin();
?>