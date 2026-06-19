<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header("Location: /CODIGOSWELISON/Aumigos/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id'])) {
    header("Location: /CODIGOSWELISON/Aumigos/catalogo.php");
    exit();
}

$id       = (int) $_POST['id'];
$redirect = isset($_POST['redirect']) ? '/CODIGOSWELISON/Aumigos/' . $_POST['redirect'] : '/CODIGOSWELISON/Aumigos/catalogo.php';

function alternarFavorito(array $curtidos, int $id): array {
    if (in_array($id, $curtidos)) {
        return array_values(array_filter($curtidos, fn($c) => $c !== $id));
    } else {
        $curtidos[] = $id;
        return $curtidos;
    }
}

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

$arq_users = __DIR__ . '/../data/users.json';
$users     = json_decode(file_get_contents($arq_users), true);
$users     = atualizarCurtidosDoUsuario($users, $_SESSION['usuario'], $id);

file_put_contents($arq_users, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

header("Location: " . $redirect);
exit();
?>