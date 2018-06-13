<?php
/**
 * Created by PhpStorm.
 * User: jose.neto
 * Date: 03/01/18
 * Time: 13:31
 */

namespace Ciente\Model\VO;


class Contrato
{
    const TABLE_NAME = "contratos";
    const KEY_ID = "id";
    const ID_PESSOA_JURIDICA = "idPessoaJuridica";
    const DESCRICAO = "descricao";
    const OBSERVACAO = "observacao";
    const ATIVO = "ativo";
    const NUMERO = "numero";
    const ANO = "ano";
    const OBJETO = "objeto";

    const TABLE_FIELDS = [
        self::ID_PESSOA_JURIDICA,
        self::DESCRICAO,
        self::OBSERVACAO,
        self::ATIVO,
        self::NUMERO,
        self::ANO,
        self::OBJETO
    ];

    public $idPessoaJuridica;
    public $descricao;
    public $observacao;
    public $ativo;
    public $numero;
    public $ano;
    public $objeto;

    /**
     * @return mixed
     */
    public function getIdPessoaJuridica()
    {
        return $this->idPessoaJuridica;
    }

    /**
     * @param mixed $idPessoaJuridica
     */
    public function setIdPessoaJuridica($idPessoaJuridica)
    {
        $this->idPessoaJuridica = $idPessoaJuridica;
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

    /**
     * @return mixed
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    /**
     * @return mixed
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * @param mixed $ano
     */
    public function setAno($ano)
    {
        $this->ano = $ano;
    }

    /**
     * @return mixed
     */
    public function getObjeto()
    {
        return $this->objeto;
    }

    /**
     * @param mixed $objeto
     */
    public function setObjeto($objeto)
    {
        $this->objeto = $objeto;
    }




}