<?php

namespace Ciente\Model\VO;

class EquipamentoTipoSetor
{
    const TABLE_NAME = "equipamentos_tipos_setores";
    //const KEY_ID = "id";
    const ID_TIPO_EQUIPAMENTO = "idTipoEquipamento";
    const ID_SETOR = "idSetor";

    const TABLE_FIELDS = [self::ID_TIPO_EQUIPAMENTO, self::ID_SETOR];

    private $idTipoEquipamento;
    private $idSetor;


    /**
     * @return mixed
     */
    public function getIdTipoEquipamento()
    {
        return $this->idTipoEquipamento;
    }

    /**
     * @param mixed $id
     */
    public function setidTipoEquipamento($id)
    {
        $this->idTipoEquipamento = $id;
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

}