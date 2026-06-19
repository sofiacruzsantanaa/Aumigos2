<?php
session_start();

function carregarUsuarios(string $arquivo): array
{
    if (!file_exists($arquivo)) {
        return [];
    }

    $dados = json_decode(file_get_contents($arquivo), true);

    return is_array($dados) ? $dados : [];
}

function senhaConfere(string $senhaDigitada, string $senhaSalva): bool
{
    if (password_verify($senhaDigitada, $senhaSalva)) {
        return true;
    }

    return $senhaDigitada === $senhaSalva;
}

function fazerLogin(): void
{
    $arquivo = __DIR__ . '/../data/users.json';

    $usuario = trim($_POST['usuario'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if ($usuario === '' || $senha === '') {
        header("Location: ../login.php?erro=1");
        exit();
    }

    $usuarios = carregarUsuarios($arquivo);

    foreach ($usuarios as $u) {
        $emailSalvo = $u['email'] ?? '';
        $senhaSalva = $u['senha'] ?? '';

        $emailConfere = strtolower($usuario) === strtolower($emailSalvo);
        $senhaOk = senhaConfere($senha, $senhaSalva);

        if ($emailConfere && $senhaOk) {
            $_SESSION['logado'] = true;
            $_SESSION['usuario'] = $emailSalvo;
            $_SESSION['nome'] = $u['nome'] ?? '';

            header("Location: ../inicio.php");
            exit();
        }
    }

    header("Location: ../login.php?erro=1");
    exit();
}

fazerLogin();