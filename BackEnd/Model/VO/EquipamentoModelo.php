<?php

namespace Ciente\Model\VO;

class EquipamentoModelo
{
    const TABLE_NAME = "equipamentos_modelos";
    const KEY_ID = "id"; //integer
    const ID_TIPO_EQUIP = "idTipoEquipamento"; //integer
    const DESCRICAO = "descricao"; //text
    const ATIVO = "ativo"; //boolean

    const TABLE_FIELDS = [self::ID_TIPO_EQUIP, self::DESCRICAO, self::ATIVO];

    public $id;
    public $idTipoEquip;
    public $descricao;
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
    public function getIdTipoEquip()
    {
        return $this->idTipoEquip;
    }

    /**
     * @param mixed $idTipoEquip
     */
    public function setIdTipoEquip($idTipoEquip)
    {
        $this->idTipoEquip = $idTipoEquip;
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