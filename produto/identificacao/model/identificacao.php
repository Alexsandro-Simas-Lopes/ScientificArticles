<?php

class identificacao
{
    private $Id;
    private $Descricao;
    private $DataCadastro;

    /**
     * Get the value of Id
     */
    public function getId()
    {
        return $this->Id;
    }

    /**
     * Set the value of Id
     *
     * @return  self
     */
    public function setId($Id)
    {
        $this->Id = $Id;

        return $this;
    }

    /**
     * Get the value of Descricao
     */
    public function getDescricao()
    {
        return $this->Descricao;
    }

    /**
     * Set the value of Descricao
     *
     * @return  self
     */
    public function setDescricao($Descricao)
    {
        $this->Descricao = $Descricao;

        return $this;
    }

    /**
     * Get the value of DataCadastro
     */
    public function getDataCadastro()
    {
        return $this->DataCadastro;
    }

    /**
     * Set the value of DataCadastro
     *
     * @return  self
     */
    public function setDataCadastro($DataCadastro)
    {
        $this->DataCadastro = $DataCadastro;

        return $this;
    }
    public function getDataCadastroFormatada()
    {
        $data = $this->DataCadastro;
        $data1 = DateTime::createFromFormat("Y-m-d H:i:s", $data);
        if (date_get_last_errors()) {
            $erro = date_get_last_errors()['errors'];
            $new_data = '';
        } else {
            $new_data = $data1->format("d/m/Y");
        }

        return $new_data;
    }
}
