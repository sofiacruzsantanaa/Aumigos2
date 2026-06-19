<?php
session_start();

function carregarUsuariosCurtir(string $arquivo): array
{
    if (!file_exists($arquivo)) {
        return [];
    }

    $dados = json_decode(file_get_contents($arquivo), true);

    return is_array($dados) ? $dados : [];
}

function salvarUsuariosCurtir(string $arquivo, array $usuarios): void
{
    file_put_contents($arquivo, json_encode($usuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function alternarCurtida(): void
{
    if (!isset($_SESSION['logado']) || !isset($_SESSION['usuario'])) {
        header("Location: ../login.php");
        exit();
    }

    $arquivo = __DIR__ . '/../data/users.json';

    $id = intval($_POST['id'] ?? 0);
    $redirect = $_POST['redirect'] ?? '../catalogo.php';

    if ($id <= 0) {
        header("Location: " . $redirect);
        exit();
    }

    $usuarios = carregarUsuariosCurtir($arquivo);

    foreach ($usuarios as &$u) {
        if (($u['email'] ?? '') === $_SESSION['usuario']) {
            if (!isset($u['curtidos']) || !is_array($u['curtidos'])) {
                $u['curtidos'] = [];
            }

            if (in_array($id, $u['curtidos'])) {
                $u['curtidos'] = array_values(array_filter($u['curtidos'], function ($item) use ($id) {
                    return (int) $item !== $id;
                }));
            } else {
                $u['curtidos'][] = $id;
            }

            break;
        }
    }

    unset($u);

    salvarUsuariosCurtir($arquivo, $usuarios);

    header("Location: " . $redirect);
    exit();
}

alternarCurtida();