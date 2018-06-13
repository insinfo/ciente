<?php

namespace Ciente\Model\VO;

class SolicitacaoMobile
{
    const TABLE_NAME = "solicitacoes_mobile";

    const KEY_ID = "idSolicitacao"; //integer
    const TOKEN_MOBILE = "tokenMobile"; //text

    const TABLE_FIELDS = [self::TOKEN_MOBILE];

    public $idSolicitacao;
    public $tokenMobile;

    /**
     * @return mixed
     */
    public function getIdSolicitacao()
    {
        return $this->idSolicitacao;
    }

    /**
     * @param mixed $idSolicitacao
     */
    public function setIdSolicitacao($idSolicitacao)
    {
        $this->idSolicitacao = $idSolicitacao;
    }

    /**
     * @return mixed
     */
    public function getTokenMobile()
    {
        return $this->tokenMobile;
    }

    /**
     * @param mixed $tokenMobile
     */
    public function setTokenMobile($tokenMobile)
    {
        $this->tokenMobile = $tokenMobile;
    }



}