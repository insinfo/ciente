<?php
/**
 * Created by PhpStorm.
 * User: jose.neto
 * Date: 03/01/18
 * Time: 13:31
 */

namespace Ciente\Model\VO;


class Produto
{
    const TABLE_NAME = "produto";
    const KEY_ID = "id";
    const NOME_REDUZIDO = "nomeReduzido";
    const DESCRICAO = "descricao";
    const ATIVO = "ativo";
    const ORIGEM = "origem";
    const ID_SETOR = "idSetor";

    const TABLE_FIELDS = [
        self::KEY_ID,
        self::NOME_REDUZIDO,
        self::DESCRICAO,
        self::ATIVO,
        self::ORIGEM,
        self::ID_SETOR

    ];

    public $id;
    public $descricao;
    public $nomeReduzido;
    public $ativo;
    public $origem;
    public $idSetor;

    public function getId () {
        return $this->id;
    }

    public function setId ($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdProduto()
    {
        return $this->id;
    }

    /**
     * @param mixed $idProduto
     */
    public function setIdProduto($idProduto)
    {
        $this->id = $idProduto;
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
    public function getOrigem()
    {
        return $this->origem;
    }

    /**
     * @param mixed $observacao
     */
    public function setOrigem($origem)
    {
        $this->origem = $origem;
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
    public function getNomeReduzido()
    {
        return $this->nomeReduzido;
    }

    /**
     * @param mixed $numero
     */
    public function setnomeReduzido($nomeReduzido)
    {
        $this->nomeReduzido = $nomeReduzido;
        return $this;
    }

}