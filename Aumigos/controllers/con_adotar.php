<?php
session_start();

function carregarJsonAdocao(string $arquivo): array
{
    if (!file_exists($arquivo)) {
        return [];
    }

    $dados = json_decode(file_get_contents($arquivo), true);

    return is_array($dados) ? $dados : [];
}

function salvarJsonAdocao(string $arquivo, array $dados): void
{
    $pasta = dirname($arquivo);

    if (!is_dir($pasta)) {
        mkdir($pasta, 0777, true);
    }

    file_put_contents($arquivo, json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function uploadDocumento(string $campo): string
{
    if (!isset($_FILES[$campo]) || $_FILES[$campo]['error'] !== UPLOAD_ERR_OK) {
        return '';
    }

    $pasta = __DIR__ . '/../uploads/documentos/';

    if (!is_dir($pasta)) {
        mkdir($pasta, 0777, true);
    }

    $nomeOriginal = $_FILES[$campo]['name'];
    $extensao = pathinfo($nomeOriginal, PATHINFO_EXTENSION);
    $novoNome = uniqid($campo . '_', true) . '.' . $extensao;

    $destino = $pasta . $novoNome;

    if (move_uploaded_file($_FILES[$campo]['tmp_name'], $destino)) {
        return 'uploads/documentos/' . $novoNome;
    }

    return '';
}

function finalizarAdocao(): void
{
    if (!isset($_SESSION['logado']) || !isset($_SESSION['usuario'])) {
        header("Location: ../login.php");
        exit();
    }

    $id = intval($_POST['id'] ?? 0);
    $motivo = trim($_POST['motivo'] ?? '');
    $pessoas = trim($_POST['pessoas'] ?? '');
    $animais = trim($_POST['outros_animais'] ?? '');
    $quintal = trim($_POST['quintal'] ?? '');
    $experiencia = trim($_POST['experiencia'] ?? '');

    $concordancia = isset($_POST['concordancia']);
    $financeiro = isset($_POST['financeiro']);
    $taxa = isset($_POST['taxa']);
    $termo = isset($_POST['termo']);

    if (!$id || !$motivo || !$pessoas || !$animais || !$quintal || !$experiencia || !$concordancia || !$financeiro || !$taxa || !$termo) {
        header("Location: ../catalogo.php?erro=1");
        exit();
    }

    $docRg = uploadDocumento('doc_rg');
    $docCpf = uploadDocumento('doc_cpf');
    $docResidencia = uploadDocumento('doc_residencia');

    if ($docRg === '' || $docCpf === '' || $docResidencia === '') {
        header("Location: ../catalogo.php?erro=documentos");
        exit();
    }

    $arqCaes = __DIR__ . '/../data/caes.json';
    $arqAdocoes = __DIR__ . '/../data/adocoes.json';

    $caes = carregarJsonAdocao($arqCaes);
    $adocoes = carregarJsonAdocao($arqAdocoes);

    $caoEncontrado = false;

    foreach ($caes as &$cao) {
        if ((int) ($cao['id'] ?? 0) === $id) {
            if (!($cao['disponivel'] ?? false)) {
                header("Location: ../catalogo.php?erro=indisponivel");
                exit();
            }

            $cao['disponivel'] = false;
            $caoEncontrado = true;
            break;
        }
    }

    unset($cao);

    if (!$caoEncontrado) {
        header("Location: ../catalogo.php?erro=naoencontrado");
        exit();
    }

    $novaAdocao = [
        "id" => count($adocoes) + 1,
        "cao_id" => $id,
        "usuario" => $_SESSION['usuario'],
        "motivo" => $motivo,
        "pessoas" => $pessoas,
        "outros_animais" => $animais,
        "quintal" => $quintal,
        "experiencia" => $experiencia,
        "documentos" => [
            "rg" => $docRg,
            "cpf" => $docCpf,
            "residencia" => $docResidencia
        ],
        "data" => date('Y-m-d H:i:s')
    ];

    $adocoes[] = $novaAdocao;

    salvarJsonAdocao($arqCaes, $caes);
    salvarJsonAdocao($arqAdocoes, $adocoes);

    header("Location: ../catalogo.php?adotado=1");
    exit();
}

finalizarAdocao();