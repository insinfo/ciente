<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 12/03/2018
 * Time: 10:49
 */

namespace Ciente\Model\VO;

class SetorUsuario
{
    const TABLE_NAME = "setores_usuarios";
    const ID_SETOR = "idSetor";
    const ID_PESSOA = "idPessoa";

    const TABLE_FIELDS = [
        self::ID_SETOR,
        self::ID_PESSOA
    ];

    public $idSetor;
    public $idPessoa;

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



}