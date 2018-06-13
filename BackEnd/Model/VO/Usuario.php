<?php

namespace Ciente\Model\VO;

class Usuario
{
    const TABLE_NAME = "usuarios";
    const KEY_ID = "idPessoa"; //integer
    const ID_SETOR = "idSetor"; //integer
    const LOGIN = "login"; //character varying
    const PERFIL = "perfil"; //smallint
    const ATIVO = "ativo"; //boolean
    const SISTEMA = 'idSistema';
    
    const TABLE_FIELDS = [self::ID_SETOR, self::LOGIN, self::PERFIL, self::ATIVO];
    
    public $idPessoa;
    public $idSetor;
    public $idSistema;
    public $login;
    public $perfil;
    public $ativo;


    public static function create ($data) {
        $usuario = new self();
        return $usuario -> setIdPessoa($data['idPessoa'])
            ->setIdSetor($data['idSetor'])
            ->setAtivo($data['ativo'])
            ->setLogin($data['login'])
            ->setIdSistema($data['idSistema'])
            ->setPerfil($data['idPerfil']);
    }

    /**
     * @param mixed $idSistema
     * @return self;
     */
    public function setIdSistema($idSistema)
    {
        $this->idSistema = $idSistema;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdSistema()
    {
        return $this->idSistema;
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
     * @return self
     */
    public function setIdPessoa($idPessoa)
    {
        $this->idPessoa = $idPessoa;
        return $this;
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
     * @return self
     */
    public function setIdSetor($idSetor)
    {
        $this->idSetor = $idSetor;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     * @return self
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPerfil()
    {
        return $this->perfil;
    }

    /**
     * @param mixed $perfil
     * @return self
     */
    public function setPerfil($perfil)
    {
        $this->perfil = $perfil;
        return $this;
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
     * @return self
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
        return $this;
    }

    

}