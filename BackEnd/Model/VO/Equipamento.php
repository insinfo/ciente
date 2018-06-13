<?php

namespace Ciente\Model\VO;

class Equipamento
{
    const TABLE_NAME = "equipamentos";
    const KEY_ID = "id"; //integer
    const ID_MODELO_EQUIPAMENTO = "idModeloEquipamento"; //integer
    const ID_CONTRATO = "idContrato"; //integer
    const PATRIMONIO_PMRO = "patrimonioPmro"; //character varying
    const PATRIMONIO_INTERNO = "patrimonioInterno"; //character varying
    const PATRIMONIO_EMPRESA = "patrimonioEmpresa"; //character varying
    const OBSERVACAO = "observacao"; //text
    const SITUACAO = "situacao"; //smallint
    const STATUS = "status"; //smallint
   
    const TABLE_FIELDS = [self::ID_MODELO_EQUIPAMENTO, self::ID_CONTRATO, self::PATRIMONIO_PMRO, self::PATRIMONIO_INTERNO, self::PATRIMONIO_EMPRESA, self::OBSERVACAO, self::SITUACAO, self::STATUS];
   
    public $id;
    public $idModeloEquipamento;
    public $idContrato;
    public $patrimonioPmro;
    public $patrimonioInterno;
    public $patrimonioEmpresa;
    public $observacao;
    public $situacao;
    public $status;

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
    public function getIdModeloEquipamento()
    {
        return $this->idModeloEquipamento;
    }

    /**
     * @param mixed $idModeloEquipamento
     */
    public function setIdModeloEquipamento($idModeloEquipamento)
    {
        $this->idModeloEquipamento = $idModeloEquipamento;
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
    }

    /**
     * @return mixed
     */
    public function getPatrimonioPmro()
    {
        return $this->patrimonioPmro;
    }

    /**
     * @param mixed $patrimonioPmro
     */
    public function setPatrimonioPmro($patrimonioPmro)
    {
        $this->patrimonioPmro = $patrimonioPmro;
    }

    /**
     * @return mixed
     */
    public function getPatrimonioInterno()
    {
        return $this->patrimonioInterno;
    }

    /**
     * @param mixed $patrimonioInterno
     */
    public function setPatrimonioInterno($patrimonioInterno)
    {
        $this->patrimonioInterno = $patrimonioInterno;
    }

    /**
     * @return mixed
     */
    public function getPatrimonioEmpresa()
    {
        return $this->patrimonioEmpresa;
    }

    /**
     * @param mixed $patrimonioEmpresa
     */
    public function setPatrimonioEmpresa($patrimonioEmpresa)
    {
        $this->patrimonioEmpresa = $patrimonioEmpresa;
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

    /**
     * @return mixed
     */
    public function getSituacao()
    {
        return $this->situacao;
    }

    /**
     * @param mixed $situacao
     */
    public function setSituacao($situacao)
    {
        $this->situacao = $situacao;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    

}