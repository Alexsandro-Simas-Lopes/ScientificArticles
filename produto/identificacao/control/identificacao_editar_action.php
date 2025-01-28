<?php
require_once("../../../produto/identificacao/model/identificacaodao.php");
class identificacao_editar
{
    public function __construct()
    {
        $error_code = 404;
        $registered = 400;
        $success_code = 200;
        $receivedData = file_get_contents("php://input");
        $received = json_decode($receivedData, true);

        if (!empty($received['id_identificacao']) && !empty($received['descricao'])) {
            $verifica_existe = identificacaodao::verifica_existe_identificacao_editar($received['id_identificacao'], $received['descricao']);
            if (!empty($verifica_existe->getId())) {
                echo $registered;
            } else {
                $identificacao = new identificacao();

                $identificacao->setId($received['id_identificacao']);
                $descricao_limpa = preg_replace('/[^\x20-\x7E]/', '', $received['descricao']);
                $descricao_limpa = str_replace(array("'", '"', '`'), '', $descricao_limpa);
                
                $identificacao->setDescricao($descricao_limpa);

                $exec = identificacaodao::update($identificacao); 
                if ($exec) {
                    echo $success_code;
                }
            }   
        } else {
            echo $error_code;
        }
    }
}
new identificacao_editar();