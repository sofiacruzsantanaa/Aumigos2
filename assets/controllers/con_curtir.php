<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id'])) {
    header("Location: ../catalogo.php");
    exit();
}

$id       = (int) $_POST['id'];
$redirect = isset($_POST['redirect']) ? '../' . $_POST['redirect'] : '../catalogo.php';

// -------------------------------------------------------
// FUNÇÃO: Alterna o favorito de um cão para o usuário
// Se já curtido, remove. Se não curtido, adiciona.
// Retorna o array de curtidos atualizado.
// -------------------------------------------------------
function alternarFavorito(array $curtidos, int $id): array {
    if (in_array($id, $curtidos)) {
        return array_values(array_filter($curtidos, fn($c) => $c !== $id));
    } else {
        $curtidos[] = $id;
        return $curtidos;
    }
}

// -------------------------------------------------------
// FUNÇÃO: Atualiza os curtidos do usuário no array geral
// Retorna o array de usuários atualizado
// -------------------------------------------------------
function atualizarCurtidosDoUsuario(array $users, string $email, int $id): array {
    foreach ($users as &$u) {
        if ($u['email'] === $email) {
            $u['curtidos'] = alternarFavorito($u['curtidos'] ?? [], $id);
            break;
        }
    }
    unset($u);
    return $users;
}

// Carrega usuários e atualiza favoritos
$arq_users = __DIR__ . '/../data/users.json';
$users     = json_decode(file_get_contents($arq_users), true);

$users = atualizarCurtidosDoUsuario($users, $_SESSION['usuario'], $id);

file_put_contents($arq_users, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

header("Location: " . $redirect);
exit();