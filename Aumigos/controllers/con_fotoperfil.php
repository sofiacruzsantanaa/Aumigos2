<?php
session_start();

function carregarUsuariosFoto(string $arquivo): array
{
    if (!file_exists($arquivo)) {
        return [];
    }

    $dados = json_decode(file_get_contents($arquivo), true);

    return is_array($dados) ? $dados : [];
}

function salvarUsuariosFoto(string $arquivo, array $usuarios): void
{
    file_put_contents($arquivo, json_encode($usuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function salvarFotoPerfil(): void
{
    if (!isset($_SESSION['logado']) || !isset($_SESSION['usuario'])) {
        header("Location: ../login.php");
        exit();
    }

    if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
        header("Location: ../perfil.php?erro=foto");
        exit();
    }

    $pasta = __DIR__ . '/../uploads/perfil/';

    if (!is_dir($pasta)) {
        mkdir($pasta, 0777, true);
    }

    $nomeOriginal = $_FILES['foto']['name'];
    $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));

    $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($extensao, $extensoesPermitidas)) {
        header("Location: ../perfil.php?erro=tipo");
        exit();
    }

    $novoNome = uniqid('perfil_', true) . '.' . $extensao;
    $destino = $pasta . $novoNome;

    if (!move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
        header("Location: ../perfil.php?erro=upload");
        exit();
    }

    $caminhoRelativo = 'uploads/perfil/' . $novoNome;

    $arquivo = __DIR__ . '/../data/users.json';
    $usuarios = carregarUsuariosFoto($arquivo);

    foreach ($usuarios as &$u) {
        if (($u['email'] ?? '') === $_SESSION['usuario']) {
            $u['foto'] = $caminhoRelativo;
            break;
        }
    }

    unset($u);

    salvarUsuariosFoto($arquivo, $usuarios);

    header("Location: ../perfil.php");
    exit();
}

salvarFotoPerfil();