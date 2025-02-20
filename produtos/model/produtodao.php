<?php
require_once("../../parametro/configDB/connectDB.php");
require_once("../../produtos/model/produtos.php");
//and Marca = :Marca and Name = :Name and Img = :Img and Categoria = :Categoria and Sub_Categoria = :Sub_Categoria and Price = :Price
class produtodao
{
    public static function getFindById($id): produtos
    {
        try {
            $param['id'] = $id;
            $PDO = connectDB::getInstance();
            $sql = "SELECT * FROM produtos WHERE Id = :id";
            $stm = $PDO->prepare($sql);
            $stm->execute($param);
            $produto = $stm->fetchObject(produtos::class);

            return empty($produto) ? new produtos() : $produto;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public static function getFindByAll(array $input = null)
    {
        try {
            $PDO = connectDB::getInstance();
            $sql = "SELECT * FROM produtos ORDER BY Name ASC";
            $stm = $PDO->prepare($sql);
            $stm->execute();

            $results = [];

            while ($row = $stm->fetch(PDO::FETCH_OBJ)) {
                $objeto = new produtos();
                $objeto->setId($row->Id);
                $objeto->setMarca($row->Marca);
                $objeto->setName($row->Name);
                $objeto->setImg($row->Img);
                $objeto->setCategoria($row->Categoria);
                $objeto->setSub_Categoria($row->Sub_Categoria);
                $objeto->setPrice($row->Price);

                $results[] = $objeto;
            }

            return $results;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function update(produtos $produto)
    {
        try {
            $PDO = connectDB::getInstance();
            $sql = "UPDATE produtos SET 
                    Marca = :Marca, 
                    Name = :Name,
                    Categoria = :Categoria, 
                    Sub_Categoria = :Sub_Categoria, 
                    Price = :Price
                WHERE Id = :Id";

            $stm = $PDO->prepare($sql);
            $stm->bindValue(":Id", $produto->getId());
            $stm->bindValue(":Marca", $produto->getMarca());
            $stm->bindValue(":Name", $produto->getName());
            $stm->bindValue(":Categoria", $produto->getCategoria());
            $stm->bindValue(":Sub_Categoria", $produto->getSub_Categoria());
            $stm->bindValue(":Price", $produto->getPrice());
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

    public static function updateProdutoImg($id, $imgUrl)
    {
        try {
            $PDO = connectDB::getInstance();
            $sql = "UPDATE produtos SET Img = :Img WHERE Id = :Id";

            $stm = $PDO->prepare($sql);
            $stm->bindValue(":Id", $id);
            $stm->bindValue(":Img", $imgUrl);
            $stm->execute();

            return $stm->rowCount() > 0;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }


    public static function insert(produtos $produto)
    {
        try {
            $PDO = connectDB::getInstance();
            $sql = "INSERT INTO produtos (Marca, Name, Img, Categoria, Sub_Categoria, Price) 
                    VALUES (:Marca, :Name, :Img, :Categoria, :Sub_Categoria,:Price)";
            $stm = $PDO->prepare($sql);
            $stm->bindValue(":Marca", $produto->getMarca());
            $stm->bindValue(":Name", $produto->getName());
            $stm->bindValue(":Img", $produto->getImg());
            $stm->bindValue(":Categoria", $produto->getCategoria());
            $stm->bindValue(":Sub_Categoria", $produto->getSub_Categoria());
            $stm->bindValue(":Price", $produto->getPrice());
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
            $sql = "DELETE FROM produtos WHERE Id =:Id";
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
            $sql = "SELECT count(*) as register FROM produtos WHERE $param_where";
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
            $sql = "SELECT count(*) as register FROM produtos WHERE $param_where";
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
            $sql = "SELECT * FROM produtos WHERE $param_where $orderby LIMIT $start_limit, $start_final";
            $stmt = $PDO->prepare($sql);
            $stmt->execute();
            $results = array();
            while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {

                $objeto = new produtos();

                $objeto->setId($row->Id);
                $objeto->setMarca($row->Marca);
                $objeto->setName($row->Name);
                $objeto->setImg($row->Img);
                $objeto->setCategoria($row->Categoria);
                $objeto->setSub_Categoria($row->Sub_Categoria);
                $objeto->setPrice($row->Price);

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
        $busca_produto = self::getFindByAll($inputs);
        if (!empty($busca_produto)) {
            $opcoes = '<option value="" disabled selected >Selecione um produto</option>';
            foreach ($busca_produto as $produto) {
                $sel = $selected == $produto->getId() ? "selected" : "";
                $opcoes .= '<option value="' . $produto->getId() . '" ' . $sel . '>' . $produto->getName() . '</option>';
            }
        }
        return $opcoes;
    }

    public static function verifica_existe_produto($Id)
    {
        try {
            $PDO = connectDB::getInstance();
            $sql = "SELECT p.* FROM produtos p WHERE p.Id = :Id";
            $stm = $PDO->prepare($sql);
            $stm->bindValue(':Id', strtolower($Id));
            $stm->execute();
            $produto = $stm->fetchObject(produtos::class);

            return empty($produto) ? new produtos() : $produto;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function verifica_existe_produto_editar($id_produto, $name)
    {
        try {
            $PDO = connectDB::getInstance();
            $sql = "SELECT p.* FROM produtos p WHERE p.Name = :Name AND p.Id NOT IN (:id_produto)";
            $stm = $PDO->prepare($sql);
            $stm->bindValue(':id_produto', $id_produto);
            $stm->bindValue(':Name', $name);

            $stm->execute();
            $produto = $stm->fetchObject(produtos::class);

            return empty($produto) ? new produtos() : $produto;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }



    // public static function busca_produto_cliente($id_cliente)
    // {
    //     $return_data = array();
    //     if (!empty($id_cliente)) {
    //         $URL = AMBIENTE->URL_ISP_PRODUTO . '/isp/api/cliente/control/busca_produto_servico.php';
    //         $ch = curl_init($URL);
    //         $inputs = array(
    //             'documento' => $id_cliente
    //         );
    //         $postdata = json_encode($inputs);
    //         curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    //         curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //         $response = curl_exec($ch);
    //         if (!curl_errno($ch)) {
    //             $return_data = json_decode($response, true);
    //         }
    //     }
    //     return $return_data;
    // }
}
