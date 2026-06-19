<?php
session_start();

if (!isset($_SESSION['logado'])) {
    header("Location: ../login.php");
    exit();
}

$id          = intval($_POST['id'] ?? 0);
$motivo      = trim($_POST['motivo'] ?? '');
$pessoas     = $_POST['pessoas'] ?? '';
$animais     = $_POST['outros_animais'] ?? '';
$quintal     = $_POST['quintal'] ?? '';
$experiencia = $_POST['experiencia'] ?? '';

// Valida campos
if (!$id || !$motivo || !$pessoas || !$animais || !$quintal || !$experiencia) {
    header("Location: ../adotar.php?id=$id&erro=campos");
    exit();
}

$arq_caes  = __DIR__ . '/../data/caes.json';
$arq_users = __DIR__ . '/../data/users.json';

// -------------------------------------------------------
// FUNÇÃO: Marca o cão como indisponível no array de cães
// Retorna true se encontrou e atualizou, false caso contrário
// -------------------------------------------------------
function marcarComoAdotado(array &$caes, int $id, string $usuario): bool {
    foreach ($caes as &$cao) {
        if ($cao['id'] === $id && $cao['disponivel']) {
            $cao['disponivel']  = false;
            $cao['adotado_por'] = $usuario;
            return true;
        }
    }
    return false;
}

// -------------------------------------------------------
// FUNÇÃO: Registra a adoção no perfil do usuário
// -------------------------------------------------------
function registrarAdocaoNoUsuario(array &$users, string $email, array $dadosAdocao): void {
    foreach ($users as &$u) {
        if ($u['email'] === $email) {
            if (!isset($u['adocoes'])) $u['adocoes'] = [];
            $u['adocoes'][] = $dadosAdocao;
            break;
        }
    }
}

// Carrega os dados
$caes  = json_decode(file_get_contents($arq_caes), true);
$users = json_decode(file_get_contents($arq_users), true);

// Tenta marcar o cão como adotado
$encontrado = marcarComoAdotado($caes, $id, $_SESSION['usuario']);

if (!$encontrado) {
    header("Location: ../catalogo.php?erro=indisponivel");
    exit();
}

file_put_contents($arq_caes, json_encode($caes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Monta os dados da adoção e salva no perfil do usuário
$dadosAdocao = [
    'id_cao'      => $id,
    'motivo'      => $motivo,
    'pessoas'     => $pessoas,
    'animais'     => $animais,
    'quintal'     => $quintal,
    'experiencia' => $experiencia,
    'data'        => date('d/m/Y')
];

registrarAdocaoNoUsuario($users, $_SESSION['usuario'], $dadosAdocao);

file_put_contents($arq_users, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

header("Location: ../perfil.php?adocao=ok");
exit();