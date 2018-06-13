<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 21/02/2018
 * Time: 14:33
 */

namespace Ciente\Model\VO;

class Organograma
{
    const TABLE_NAME = "organograma";
    const KEY_ID = "id";
    const ID_PAI = "idPai";
    const COR = "cor";
    const ATIVO = "ativo";

    public $id;
    public $idPai;
    public $cor;
    public $ativo;
    public $setoresFilhos;

    /**
     * @return mixed
     */
    public function getSetoresFilhos()
    {
        return $this->setoresFilhos;
    }

    /**
     * @param mixed $setoresFilhos
     */
    public function setSetoresFilhos($setoresFilhos)
    {
        $this->setoresFilhos = $setoresFilhos;
    }

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
    public function getIdPai()
    {
        return $this->idPai;
    }

    /**
     * @param mixed $idPai
     */
    public function setIdPai($idPai)
    {
        $this->idPai = $idPai;
    }

    /**
     * @return mixed
     */
    public function getCor()
    {
        return $this->cor;
    }

    /**
     * @param mixed $cor
     */
    public function setCor($cor)
    {
        $this->cor = $cor;
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


    public static function getClassName()
    {
        return get_called_class();
    }

}