<?php
header("Content-Type: application/json");

// Configurações de limite de memória e upload
ini_set('memory_limit', '256M');
ini_set('upload_max_filesize', '50M');
ini_set('post_max_size', '55M');

require_once '../../artigos/model/artigodao.php';

date_default_timezone_set('America/Manaus');

header('Content-Type: application/json'); // Garante que a resposta seja JSON

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Método inválido"]);
    exit;
}

$id_artigo = $_POST['id'] ?? null;

if (empty($id_artigo) || !isset($_FILES['img'])) {
    echo json_encode(["error" => "ID do produto ou imagem não foram enviados"]);
    exit;
}

// Verifica se o produto existe no banco de dados
$artg = produtodao::getFindById($id_artigo);

if (!$artg) {
    echo json_encode(["error" => "Produto não encontrado"]);
    exit;
}

// Diretório de upload
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/Scientific_articles/public/assets/uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Verifica se o produto já tem uma imagem cadastrada
$imgAtualUrl = $artg->getImg();
if (!empty($imgAtualUrl)) {
    $imgAtualPath = str_replace("http://" . $_SERVER['HTTP_HOST'], $_SERVER['DOCUMENT_ROOT'], $imgAtualUrl);
    if (file_exists($imgAtualPath)) {
        unlink($imgAtualPath); // Remove a imagem antiga
    }
}

// Upload da nova imagem
$img = $_FILES['img'];
if ($img['error'] === 0) {
    $fileName = time() . "_" . basename($img["name"]);
    $targetFilePath = $uploadDir . $fileName;

    if (move_uploaded_file($img["tmp_name"], $targetFilePath)) {
        chmod($targetFilePath, 0777);

        // Constrói a URL correta da imagem
        $imgUrl = "http://" . $_SERVER['HTTP_HOST'] . "/Scientific_articles/public/assets/uploads/" . $fileName;
        $imgUrl = str_replace("\\", "/", $imgUrl);

        // Atualiza a URL da imagem no banco de dados
        $atualizado = produtodao::update($id_artigo, $imgUrl);

        if ($atualizado) {
            echo json_encode(["message" => "Imagem atualizada com sucesso!", "imgUrl" => $imgUrl]);
        } else {
            echo json_encode(["error" => "Erro ao atualizar o banco de dados"]);
        }
    } else {
        echo json_encode(["error" => "Erro ao salvar a nova imagem"]);
    }
} else {
    echo json_encode(["error" => "Erro no upload da imagem"]);
}