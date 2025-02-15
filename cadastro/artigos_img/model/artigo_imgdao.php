<?php
require_once("../../../parametro/configDB/connectDB.php");
require_once("../../../cadastro/artigos_img/model/artigos_img.php");
//and Marca = :Marca and Name = :Name and Img = :Img and Categoria = :Categoria and Sub_Categoria = :Sub_Categoria and Price = :Price
class artigo_imgdao
{
    public static function getFindById($id): artigo_images
    {
        try {
            $param['id'] = $id;
            $PDO = connectDB::getInstance();
            $sql = "SELECT * FROM imagens_artigos WHERE Id = :id";
            $stm = $PDO->prepare($sql);
            $stm->execute($param);
            $artg = $stm->fetchObject(artigo_images::class);

            return empty($artg) ? new artigo_images() : $artg;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public static function getFindIdArtigo($id_artigo)
    {
        try {
            $PDO = connectDB::getInstance();
            $sql = "SELECT * FROM imagens_artigos WHERE Artigo_id = :id_artigo";
            $stm = $PDO->prepare($sql);

            $stm->bindValue(":id_artigo", $id_artigo);

            $stm->execute();

            $results = [];

            while ($row = $stm->fetch(PDO::FETCH_OBJ)) {
                $objeto = new artigo_images();
                $objeto->setId($row->Id);
                $objeto->setArtigo_id($row->Artigo_id);
                $objeto->setCaminho($row->Caminho);
                $objeto->setDescricao($row->Descricao);
                $objeto->setEnviado_em($row->Enviado_em);

                $results[] = $objeto;
            }

            return $results;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function update(artigo_images $artg)
    {
        try {
            $PDO = connectDB::getInstance();
            $sql = "UPDATE imagens_artigos SET 
                    Artigo_id = :Artigo_id, 
                    Caminho = :Caminho,
                    Descricao = :Descricao,
                    Enviado_em = :Enviado_em
                WHERE Id = :Id";

            $stm = $PDO->prepare($sql);
            $stm->bindValue(":Id", $artg->getId());
            $stm->bindValue(":Artigo_id", $artg->getArtigo_id());
            $stm->bindValue(":Caminho", $artg->getCaminho());
            $stm->bindValue(":Descricao", $artg->getDescricao());
            $stm->bindValue(":Enviado_em", $artg->getEnviado_em());
            $stm->execute();

            if ($stm->rowCount() > 0) {
                return $stm->rowCount();
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // public static function updateArtigoImg(artigo_images $artg, $imgUrl)
    // {
    //     try {
    //         $PDO = connectDB::getInstance();
    //         $sql = "UPDATE imagens_artigos SET 
    //                 Artigo_id = :Artigo_id, 
    //                 Caminho = :Caminho,
    //                 Descricao = :Descricao,
    //                 Enviado_em = :Enviado_em
    //             WHERE Id = :Id";

    //         $stm = $PDO->prepare($sql);
    //         $stm->bindValue(":Id", $artg->getId());
    //         $stm->bindValue(":Artigo_id", $artg->getArtigo_id());
    //         $stm->bindValue(":Caminho", $imgUrl->getCaminho());
    //         $stm->bindValue(":Descricao", $artg->getDescricao());
    //         $stm->bindValue(":Enviado_em", $artg->getEnviado_em());
    //         $stm->execute();
    //         return $stm->rowCount() > 0;
    //     } catch (Exception $e) {
    //         throw new Exception($e->getMessage());
    //     }
    // }


    public static function insert(artigo_images $artg)
    {
        try {
            $PDO = connectDB::getInstance();
            $sql = "INSERT INTO imagens_artigos (Artigo_id, Caminho, Descricao, Enviado_em) 
                    VALUES (:Artigo_id, :Caminho, :Descricao, :Enviado_em)";
            $stm = $PDO->prepare($sql);
            $stm->bindValue(":Artigo_id", $artg->getArtigo_id());
            $stm->bindValue(":Caminho", $artg->getCaminho());
            $stm->bindValue(":Descricao", $artg->getDescricao());
            $stm->bindValue(":Enviado_em", $artg->getEnviado_em());
            $stm->execute();

            return true;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public static function delete($Id)
    {
        try {
            $PDO = connectDB::getInstance();
            $sql = "DELETE FROM imagens_artigos WHERE Id =:Id";
            $stm = $PDO->prepare($sql);
            $stm->bindParam(":Id", $Id);
            $stm->execute();

            return true;
        } catch (Exception $e) {
            return $e;
        }
    }

    public static function getCountPage($page_length, array $input = null)
    {
        $param_where = '1=1';
        if (!empty($input['name_pesquisar'])) {
            $name_pesquisar = $input['name_pesquisar'];
            $param_where .= " AND (Name LIKE '%$name_pesquisar%' OR Id LIKE '%$name_pesquisar%')";
        }
        try {
            $PDO = connectDB::getInstance();
            $sql = "SELECT count(*) as register FROM imagens_artigos WHERE $param_where";
            $stm = $PDO->prepare($sql);
            $stm->execute();

            $row = $stm->fetch(PDO::FETCH_OBJ);

            $pages = 0;
            $total_count = 0;
            if (!empty($row)) {
                $total_count = $row->register;
                $pages = ceil($total_count / $page_length);
            }
            return $pages;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function getCountPagination(array $input = null)
    {
        $param_where = '1=1';
        if (!empty($input['name_pesquisar'])) {
            $name_pesquisar = $input['name_pesquisar'];
            $param_where .= " AND (Name LIKE '%$name_pesquisar%' OR Id LIKE '%$name_pesquisar%')";
        }
        try {
            $PDO = connectDB::getInstance();
            $sql = "SELECT count(*) as register FROM imagens_artigos WHERE $param_where";
            $stm = $PDO->prepare($sql);
            $stm->execute();

            $row = $stm->fetch(PDO::FETCH_OBJ);

            $total_count = 0;
            if (!empty($row)) {
                $total_count = $row->register;
            }
            return $total_count;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function getFindByAllPagination(array $input = null)
    {
        $orderby = '';
        $dorder = '';
        $orderb = '';

        $start_limit = $input['start_limit'];
        $start_final = $input['final_limit'];

        if (!empty($input['dorder'])) {
            $dorder = $input['dorder'];
        }
        if (!empty($input['orderb'])) {
            $orderb = $input['orderb'];
        }
        if (!empty($orderb) && !empty($dorder)) {
            $orderby = "ORDER BY $orderb $dorder";
        }
        $param_where = '1=1';
        if (!empty($input['name_pesquisar'])) {
            $name_pesquisar = $input['name_pesquisar'];
            $param_where .= " AND (Name LIKE '%$name_pesquisar%' OR Id LIKE '%$name_pesquisar%')";
        }
        try {
            $PDO = connectDB::getInstance();
            $sql = "SELECT * FROM imagens_artigos WHERE $param_where $orderby LIMIT $start_limit, $start_final";
            $stmt = $PDO->prepare($sql);
            $stmt->execute();
            $results = array();
            while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {

                $objeto = new artigo_images();

                $objeto->setId($row->Id);
                $objeto->setArtigo_id($row->Artigo_id);
                $objeto->setCaminho($row->Caminho);
                $objeto->setDescricao($row->Descricao);
                $objeto->setEnviado_em($row->Enviado_em);

                $results[] = $objeto;
            }
            return $results;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public static function get_options(array $inputs = null)
    {
        $opcoes = '<option value="" disabled selected>Nenhum produto disponível</option>';
        $selected = "";
        if (!empty($inputs['selected'])) {
            $selected = $inputs['selected'];
        }
        $busca_artigo = self::getFindIdArtigo($inputs);
        if (!empty($busca_artigo)) {
            $opcoes = '<option value="" disabled selected >Selecione um produto</option>';
            foreach ($busca_artigo as $artg) {
                $sel = $selected == $artg->getId() ? "selected" : "";
                $opcoes .= '<option value="' . $artg->getId() . '" ' . $sel . '>' . $artg->getArtigo_id() . '</option>';
            }
        }
        return $opcoes;
    }

    public static function verifica_existe_imagem($Id)
    {
        try {
            $PDO = connectDB::getInstance();
            $sql = "SELECT p.* FROM imagens_artigos p WHERE p.Id = :Id";
            $stm = $PDO->prepare($sql);
            $stm->bindValue(':Id', strtolower($Id));
            $stm->execute();
            $artg = $stm->fetchObject(artigo_images::class);

            return empty($artg) ? new artigo_images() : $artg;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function verifica_existe_imagem_editar($id, $caminho)
    {
        try {
            $PDO = connectDB::getInstance();
            $sql = "SELECT p.* FROM imagens_artigos p WHERE p.caminho = :caminho AND p.Id NOT IN (:id_img)";
            $stm = $PDO->prepare($sql);
            $stm->bindValue(':id_img', $id);
            $stm->bindValue(':caminho', $caminho);

            $stm->execute();
            $artg = $stm->fetchObject(artigo_images::class);

            return empty($artg) ? new artigo_images() : $artg;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}