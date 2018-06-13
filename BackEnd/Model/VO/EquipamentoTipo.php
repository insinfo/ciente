<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 22/12/2017
 * Time: 13:31
 */

namespace Ciente\Model\VO;


class EquipamentoTipo
{
    const TABLE_NAME = "equipamentos_tipos";
    const KEY_ID = "id";
    const ID_SETOR = "idSetor";
    const NOME = "nome";
    const DESCRICAO = "descricao";
    const ATIVO = "ativo";

    const TABLE_FIELDS = [self::ID_SETOR, self::NOME, self::DESCRICAO, self::ATIVO];

    private $id;
    private $idSetor;
    private $nome;
    private $descricao;
    private $ativo;


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