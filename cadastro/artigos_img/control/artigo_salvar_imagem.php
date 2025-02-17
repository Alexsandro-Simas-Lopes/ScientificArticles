<?php

// Configurações de limite de memória e upload
ini_set('memory_limit', '256M');
ini_set('upload_max_filesize', '50M');
ini_set('post_max_size', '55M');

require_once '../../artigos_img/model/artigo_imgdao.php';

date_default_timezone_set('America/Manaus');

header('Content-Type: application/json'); // Garante que a resposta seja JSON

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Método inválido"]);
    exit;
}

$id_artigo = $_POST['id_artigo'] ?? null; // Nome correto vindo do JavaScript

if (empty($id_artigo) || !isset($_FILES['img'])) {
    echo json_encode(["error" => "ID do artigo ou imagem não foram enviados"]);
    exit;
}

// Verifica se o artigo existe no banco de dados
$artg = artigo_imgdao::getFindById($id_artigo);

if (!$artg) {
    echo json_encode(["error" => "Artigo não encontrado"]);
    exit;
}

// Diretório de upload do artigo
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/Scientific_articles/public/assets/uploads/artigos/$id_artigo/";

// Verifica se o diretório não existe e cria
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        echo json_encode(["error" => "Erro ao criar o diretório"]);
        exit;
    }
}

// Obtém todas as imagens do diretório para numerar corretamente
$imagensExistentes = glob($uploadDir . "imagem*.jpg");
$descricao = count($imagensExistentes) + 1; // Define o próximo número da imagem

// Upload da nova imagem
$img = $_FILES['img'];

if ($img['error'] === 0) {
    $fileName = "imagem$descricao.jpg"; // Nome fixo e numerado
    $targetFilePath = $uploadDir . $fileName;

    // Tenta mover a imagem para o diretório
    if (move_uploaded_file($img["tmp_name"], $targetFilePath)) {
        chmod($targetFilePath, 0777);
    
        // Constrói a URL correta da imagem
        $imgUrl = "/Scientific_articles/public/assets/uploads/artigos/$id_artigo/$fileName"; 
    
        // Criando objeto para inserir no banco de dados
        $novaImagem = new artigo_images();
        $novaImagem->setArtigo_id($id_artigo);
        $novaImagem->setCaminho($imgUrl); // Caminho salvo no banco
        $novaImagem->setDescricao("Imagem do artigo $id_artigo - $descricao"); // Descrição clara
        $novaImagem->setEnviado_em(date("Y-m-d H:i:s")); // Define a data atual
    
        // Insere a nova imagem na tabela `imagens_artigos`
        $inserido = artigo_imgdao::insert($novaImagem);
    
        if ($inserido) {
            echo json_encode(["message" => "Imagem salva com sucesso!", "imgUrl" => $imgUrl]);
        } else {
            echo json_encode(["error" => "Erro ao salvar no banco de dados"]);
        }
    } else {
        echo json_encode(["error" => "Erro ao mover o arquivo para o servidor"]);
    }
} else {
    echo json_encode(["error" => "Erro no upload da imagem"]);
}

