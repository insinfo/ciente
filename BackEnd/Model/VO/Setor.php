<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 09/01/2018
 * Time: 18:10
 */

namespace Ciente\Model\VO;


class Setor
{

    const TABLE_NAME = "setores";
    const KEY_ID = "id";
    const ATIVO = "ativo";

    const TABLE_FIELDS = [self::ATIVO];

    private $id;
    private $ativo;

    /**
     * Para cada setor, buscar o setor historico
     *
     * @var SetorHistorico $setorHistorico
     */
    private $setorHistorico;

    /**
     * Retorna um setor de historico
     *
     * @return SetorHistorico
     */
    public function getSetorHistorico () {
        return $this -> setorHistorico;
    }

    /**
     * Seta o historico do setor
     *
     * @param $setorHistorico
     * @return $this
     */
    public function setSetorHistorico ($setorHistorico) {
        $this->setorHistorico = $setorHistorico;
        return $this;
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