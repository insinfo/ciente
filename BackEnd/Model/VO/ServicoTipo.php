<?php

namespace Ciente\Model\VO;

class ServicoTipo
{
    const TABLE_NAME = "servicos_tipos";
    const KEY_ID = "id"; //integer
    const ID_SETOR = "idSetor"; //integer
    const NOME = "nome"; //character varying
    const DESCRICAO = "descricao"; //text
    const PRAZO_ATENDIMENTO_INICIAL = "prazoAtendimentoInicial"; //integer
    const ATIVO = "ativo"; //boolean

    const TABLE_FIELDS = [self::ID_SETOR, self::NOME, self::DESCRICAO, self::PRAZO_ATENDIMENTO_INICIAL, self::ATIVO];

    public $id;
    public $idSetor;
    public $nome;
    public $descricao;
    public $prazoAtendimentoInicial;
    public $ativo;

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
    }

    /**
     * @return mixed
     */
    public function getIdSetor()
    {
        return $this->idSetor;
    }

    /**
     * @param mixed $idSetor
     */
    public function setIdSetor($idSetor)
    {
        $this->idSetor = $idSetor;
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
    }

    /**
     * @return mixed
     */
    public function getPrazoAtendimentoInicial()
    {
        return $this->prazoAtendimentoInicial;
    }

    /**
     * @param mixed $prazoAtendimentoInicial
     */
    public function setPrazoAtendimentoInicial($prazoAtendimentoInicial)
    {
        $this->prazoAtendimentoInicial = $prazoAtendimentoInicial;
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
    }


}