<?php
session_start();

// -------------------------------------------------------
// FUNÇÃO: Gera o caminho do arquivo de foto do usuário
// Retorna o caminho com o nome baseado no e-mail
// -------------------------------------------------------
function gerarCaminhoFoto(string $email, string $nomeArquivo): string {
    $extensao = pathinfo($nomeArquivo, PATHINFO_EXTENSION);
    return 'assets/img/perfil_' . md5($email) . '.' . $extensao;
}

// -------------------------------------------------------
// FUNÇÃO: Salva a foto e atualiza o perfil do usuário
// Retorna o array de usuários atualizado
// -------------------------------------------------------
function atualizarFotoPerfil(array $usuarios, string $email, array $arquivo): array {
    foreach ($usuarios as $i => $u) {
        if ($u['email'] === $email) {
            $caminho = gerarCaminhoFoto($email, $arquivo['name']);
            move_uploaded_file($arquivo['tmp_name'], '../' . $caminho);
            $usuarios[$i]['foto'] = $caminho;
            break;
        }
    }
    return $usuarios;
}

// Carrega usuários e atualiza a foto
$arq_users = '../users.json';
$usuarios  = json_decode(file_get_contents($arq_users), true);

$usuarios = atualizarFotoPerfil($usuarios, $_SESSION['usuario'], $_FILES['foto']);

file_put_contents($arq_users, json_encode($usuarios, JSON_PRETTY_PRINT));

header("Location: ../perfil.php");
exit();
?>