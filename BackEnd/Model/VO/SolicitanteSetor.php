<?php

namespace Ciente\Model\VO;

class SolicitanteSetor
{
    const TABLE_NAME = "solicitantes_setor";
    const KEY_ID = "idPessoa"; //integer
    const ID_SETOR = "idSetor"; //integer

    const TABLE_FIELDS = [self::ID_SETOR];

    public $idPessoa;
    public $idSetor;

    /**
     * @return mixed
     */
    public function getIdPessoa()
    {
        return $this->idPessoa;
    }

    /**
     * @param mixed $idPessoa
     */
    public function setIdPessoa($idPessoa)
    {
        $this->idPessoa = $idPessoa;
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