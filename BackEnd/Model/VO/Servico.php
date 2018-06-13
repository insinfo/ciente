<?php

namespace Ciente\Model\VO;

class Servico
{
    const TABLE_NAME = "servicos";
    const KEY_ID = "id"; //integer
    const ID_TIPO_SERVICO = "idTipoServico"; //integer
    const ID_CONTRATO = "idContrato"; //integer
    const CODIGO_REFERENCIA = "codigoReferencia"; //integer
    const NOME = "nome"; //character varying
    const DESCRICAO = "descricao"; //text
    const PRAZO_BAIXA = "prazoBaixa"; //integer
    const PRAZO_NORMAL = "prazoNormal"; //integer
    const PRAZO_ALTA = "prazoAlta"; //integer
    const PRAZO_URGENTE = "prazoUrgente"; //integer
    const IN_PRODUTO = "inProduto"; //boolean
    const ATIVO = "ativo"; //boolean

    const TABLE_FIELDS = [
        self::ID_TIPO_SERVICO,
        self::ID_CONTRATO,
        self::CODIGO_REFERENCIA,
        self::NOME,
        self::DESCRICAO,
        self::PRAZO_BAIXA,
        self::PRAZO_NORMAL,
        self::PRAZO_ALTA,
        self::PRAZO_URGENTE,
        self::IN_PRODUTO,
        self::ATIVO
    ];

    public $id;
    public $idTipoServico;
    public $idContrato;
    public $codigoReferencia;
    public $nome;
    public $descricao;
    public $prazoBaixa;
    public $prazoNormal;
    public $prazoAlta;
    public $prazoUrgente;
    public $ativo;
    public $inProduto;
    public $ordem;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdTipoServico()
    {
        return $this->idTipoServico;
    }

    /**
     * @param mixed $idTipoServico
     */
    public function setIdTipoServico($idTipoServico)
    {
        $this->idTipoServico = $idTipoServico;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdContrato()
    {
        return $this->idContrato;
    }

    /**
     * @param mixed $idContrato
     */
    public function setIdContrato($idContrato)
    {
        $this->idContrato = $idContrato;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoReferencia()
    {
        return $this->codigoReferencia;
    }

    /**
     * @param mixed $codigoReferencia
     */
    public function setCodigoReferencia($codigoReferencia)
    {
        $this->codigoReferencia = $codigoReferencia;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param mixed $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrazoBaixa()
    {
        return $this->prazoBaixa;
    }

    /**
     * @param mixed $prazoBaixa
     */
    public function setPrazoBaixa($prazoBaixa)
    {
        $this->prazoBaixa = $prazoBaixa;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrazoNormal()
    {
        return $this->prazoNormal;
    }

    /**
     * @param mixed $prazoNormal
     */
    public function setPrazoNormal($prazoNormal)
    {
        $this->prazoNormal = $prazoNormal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrazoAlta()
    {
        return $this->prazoAlta;
    }

    /**
     * @param mixed $prazoAlta
     */
    public function setPrazoAlta($prazoAlta)
    {
        $this->prazoAlta = $prazoAlta;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrazoUrgente()
    {
        return $this->prazoUrgente;
    }

    /**
     * @param mixed $prazoUrgente
     */
    public function setPrazoUrgente($prazoUrgente)
    {
        $this->prazoUrgente = $prazoUrgente;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * @param mixed $ativo
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInProduto()
    {
        return $this->inProduto;
    }

    /**
     * @param mixed $ativo
     */
    public function setInProduto($inProduto)
    {
        $this->inProduto = $inProduto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrdem()
    {
        return $this->ordem;
    }

    /**
     * @param mixed $ordem
     */
    public function setOrdem($ordem)
    {
        $this->ordem = $ordem;
        return $this;
    }

}