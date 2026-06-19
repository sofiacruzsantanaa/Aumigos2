<?php
session_start();

function carregarUsuariosCadastro(string $arquivo): array
{
    if (!file_exists($arquivo)) {
        return [];
    }

    $dados = json_decode(file_get_contents($arquivo), true);

    return is_array($dados) ? $dados : [];
}

function salvarUsuariosCadastro(string $arquivo, array $usuarios): void
{
    $pasta = dirname($arquivo);

    if (!is_dir($pasta)) {
        mkdir($pasta, 0777, true);
    }

    file_put_contents($arquivo, json_encode($usuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function emailJaExiste(array $usuarios, string $email): bool
{
    foreach ($usuarios as $u) {
        if (strtolower($u['email'] ?? '') === strtolower($email)) {
            return true;
        }
    }

    return false;
}

function cadastrarUsuario(): void
{
    $arquivo = __DIR__ . '/../data/users.json';

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $endereco = trim($_POST['endereco'] ?? '');

    if ($nome === '' || $email === '' || $senha === '' || $cpf === '' || $telefone === '' || $endereco === '') {
        header("Location: ../cadastro.php?erro=1");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../cadastro.php?erro=1");
        exit();
    }

    $usuarios = carregarUsuariosCadastro($arquivo);

    if (emailJaExiste($usuarios, $email)) {
        header("Location: ../cadastro.php?email=1");
        exit();
    }

    $novoUsuario = [
        "nome" => $nome,
        "email" => $email,
        "senha" => password_hash($senha, PASSWORD_DEFAULT),
        "cpf" => $cpf,
        "telefone" => $telefone,
        "endereco" => $endereco,
        "foto" => "",
        "curtidos" => []
    ];

    $usuarios[] = $novoUsuario;

    salvarUsuariosCadastro($arquivo, $usuarios);

    header("Location: ../login.php?cadastro=1");
    exit();
}

cadastrarUsuario();