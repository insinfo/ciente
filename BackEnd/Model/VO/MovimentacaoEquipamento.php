<?php

namespace Ciente\Model\VO;

class MovimentacaoEquipamento
{
    const TABLE_NAME = "movimentacoes_equipamento";
    const KEY_ID = "id"; //integer
    const ID_EQUIPAMENTO = "idEquipamento"; //integer
    const DATA = "data"; //timestamp with time zone
    const TIPO = "tipo"; //smallint
    const MOTIVO = "motivo"; //smallint
    const OBSERVACAO = "observacao"; //text
    
    const TABLE_FIELDS = [self::ID_EQUIPAMENTO, self::DATA, self::TIPO, self::MOTIVO, self::OBSERVACAO];
    
    public $id;
    public $idEquipamento;
    public $data;
    public $tipo;
    public $motivo;
    public $observacao;

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
    public function getIdEquipamento()
    {
        return $this->idEquipamento;
    }

    /**
     * @param mixed $idEquipamento
     */
    public function setIdEquipamento($idEquipamento)
    {
        $this->idEquipamento = $idEquipamento;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * @return mixed
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * @param mixed $motivo
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;
    }

    /**
     * @return mixed
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * @param mixed $observacao
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
    }

    

}