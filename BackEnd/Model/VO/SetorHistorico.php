<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 09/01/2018
 * Time: 16:16
 */

namespace Ciente\Model\VO;

class SetorHistorico
{
    const TABLE_NAME = "setores_historico";
    const KEY_ID = "idSetor";
    const DATA_INICIO = "dataInicio";
    const SIGLA = "sigla";
    const NOME = "nome";
    const ID_SETOR = "idSetor";
    const ID_PAI = "idPai";

    const FUNC_SETOR_NAME = "func_setores";

    const TABLE_FIELDS = [self::DATA_INICIO, self::SIGLA, self::NOME,self::ID_SETOR,self::ID_PAI];

    public $idSetor;
    public $idHierarquia;
    public $dataInicio;
    public $sigla;
    public $nome;
    public $idPai;
    public $ativo;

    /**
     * Seta ativo
     *
     * @param mixed $ativo
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
        return $this;
    }

    /**
     * get ativo
     *
     * @return mixed
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Seta id do setor
     *
     * @param $setorId
     * @return $this
     */
    public function setIdSetor ($setorId) {
        $this->idSetor = $setorId;
        return $this;
    }

    /**
     * Retorna id do setor
     *
     * @return mixed
     */
    public function getIdSetor () {
        return $this->idSetor;
    }

    /**
     * @return mixed
     */
    public function getIdHierarquia()
    {
        return $this->idHierarquia;
    }

    /**
     * @param mixed $idHierarquia
     */
    public function setIdHierarquia($idHierarquia)
    {
        $this->idHierarquia = $idHierarquia;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataInicio()
    {
        return $this->dataInicio;
    }

    /**
     * @param mixed $dataInicio
     */
    public function setDataInicio($dataInicio)
    {
        $this->dataInicio = $dataInicio;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSigla()
    {
        return $this->sigla;
    }

    /**
     * @param mixed $sigla
     */
    public function setSigla($sigla)
    {
        $this->sigla = $sigla;
        return $this;
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
        return $this;
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
        return $this;
    }




}