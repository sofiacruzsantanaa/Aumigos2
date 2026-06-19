<?php
session_start();

$nome          = $_POST['nome'];
$email         = $_POST['email'];
$cpf           = $_POST['cpf'];
$telefone      = $_POST['telefone'];
$endereco      = $_POST['endereco'];
$senha         = $_POST['senha'];
$senha_confirm = $_POST['senha_confirm'];

// -------------------------------------------------------
// FUNÇÃO: Verifica se o e-mail já está cadastrado
// Retorna true se já existe, false se está livre
// -------------------------------------------------------
function emailJaCadastrado(array $usuarios, string $email): bool {
    foreach ($usuarios as $u) {
        if ($u['email'] === $email) {
            return true;
        }
    }
    return false;
}

// -------------------------------------------------------
// FUNÇÃO: Cria e adiciona o novo usuário no array
// Retorna o array atualizado com o novo usuário
// -------------------------------------------------------
function cadastrarUsuario(array $usuarios, string $nome, string $email, string $cpf, string $telefone, string $endereco, string $senha): array {
    $senha_hash  = password_hash($senha, PASSWORD_DEFAULT);
    $usuarios[] = [
        'nome'     => $nome,
        'email'    => $email,
        'cpf'      => $cpf,
        'telefone' => $telefone,
        'endereco' => $endereco,
        'senha'    => $senha_hash
    ];
    return $usuarios;
}

// Valida se as senhas coincidem
if ($senha !== $senha_confirm) {
    header("Location: ../cadastro.php?erro=senha");
    exit();
}

// Caminho do arquivo JSON
$arquivo = '../data/users.json';

// Lê os usuários existentes
if (file_exists($arquivo)) {
    $usuarios = json_decode(file_get_contents($arquivo), true);
} else {
    $usuarios = [];
}

// Verifica se e-mail já existe
if (emailJaCadastrado($usuarios, $email)) {
    header("Location: ../cadastro.php?erro=email");
    exit();
}

// Cadastra o novo usuário e salva no JSON
$usuarios = cadastrarUsuario($usuarios, $nome, $email, $cpf, $telefone, $endereco, $senha);

file_put_contents($arquivo, json_encode($usuarios, JSON_PRETTY_PRINT));

header("Location: ../login.php");
exit();
?>