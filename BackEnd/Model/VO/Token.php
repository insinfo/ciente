<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 07/03/2018
 * Time: 16:09
 */

namespace Ciente\Model\VO;

class Token
{
    public $idPessoa;
    public $idOrganograma;
    public $userName;
    public $idPerfil;
    public $domain;
    public $tokenType = 'bearer';
    public $pws;
    public $ipClientPrivate;
    public $ipClientPublic;
    public $ipClientVisibleByServer;
    //JWT
    public $iat;
    public $iss;
    public $exp;
    public $nbf;
    public $data;

    function __construct($request)
    {
        $jwt = $request->getAttribute('jwt');
        $jwtArray = array();
        $this->objToArray($jwt, $jwtArray);

        $this->iat = $jwtArray['iat'];
        $this->iss = $jwtArray['iss'];
        $this->exp = $jwtArray['exp'];
        $this->nbf = $jwtArray['nbf'];
        $this->data = $jwtArray['data'];
        $this->domain = $this->iss;

        $this->idOrganograma = $this->data['idOrganograma'];
        $this->idSistema = $this->data['idSistema'];
        $this->userName = $this->data['loginName'];
        $this->idPerfil = $this->data['idPerfil'];
        $this->idPessoa = $this->data['idPessoa'];
        $this->pws = $this->data['pws'];
        $this->ipClientPrivate = $this->data['ipClientPrivate'];
        $this->ipClientPublic = $this->data['ipClientPublic'];
        $this->ipClientVisibleByServer = $this->data['ipClientVisibleByServer'];

    }

    function fillFromJwt($jwt)
    {
        $jwtArray = array();
        $this->objToArray($jwt, $jwtArray);

        $this->iat = $jwtArray['iat'];
        $this->iss = $jwtArray['iss'];
        $this->exp = $jwtArray['exp'];
        $this->nbf = $jwtArray['nbf'];
        $this->data = $jwtArray['data'];

        $this->idOrganograma = $this->data['idOrganograma'];
        $this->userName = $this->data['userName'];
        $this->idPerfil = $this->data['idPerfil'];
        $this->idPessoa = $this->data['idPessoa'];
        $this->domain = $this->iss;
    }

    public function objToArray($obj, &$arr)
    {
        if (!is_object($obj) && !is_array($obj))
        {
            $arr = $obj;
            return $arr;
        }

        foreach ($obj as $key => $value)
        {
            if (!empty($value))
            {
                $arr[$key] = array();
                $this->objToArray($value, $arr[$key]);
            }
            else
            {
                $arr[$key] = $value;
            }
        }
        return $arr;
    }

    /**
     * @return mixed
     */
    public function getIdSistema()
    {
        return $this->idSistema;
    }

    /**
     * @param mixed $idSistema
     * @return self
     */
    public function setIdSistema($idSistema)
    {
        $this->idSistema = $idSistema;
        return $this;
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

    /**
     * @return mixed
     */
    public function getIdOrganograma()
    {
        return $this->idOrganograma;
    }

    /**
     * @param mixed $idOrganograma
     */
    public function setIdOrganograma($idOrganograma)
    {
        $this->idOrganograma = $idOrganograma;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param mixed $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return mixed
     */
    public function getIdPerfil()
    {
        return $this->idPerfil;
    }

    /**
     * @param mixed $idPerfil
     */
    public function setIdPerfil($idPerfil)
    {
        $this->idPerfil = $idPerfil;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param mixed $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getTokenType()
    {
        return $this->tokenType;
    }

    /**
     * @param string $tokenType
     */
    public function setTokenType($tokenType)
    {
        $this->tokenType = $tokenType;
    }

    /**
     * @return mixed
     */
    public function getPws()
    {
        return $this->pws;
    }

    /**
     * @param mixed $pws
     */
    public function setPws($pws)
    {
        $this->pws = $pws;
    }

    /**
     * @return mixed
     */
    public function getIpClientPrivate()
    {
        return $this->ipClientPrivate;
    }

    /**
     * @param mixed $ipClientPrivate
     */
    public function setIpClientPrivate($ipClientPrivate)
    {
        $this->ipClientPrivate = $ipClientPrivate;
    }

    /**
     * @return mixed
     */
    public function getIpClientPublic()
    {
        return $this->ipClientPublic;
    }

    /**
     * @param mixed $ipClientPublic
     */
    public function setIpClientPublic($ipClientPublic)
    {
        $this->ipClientPublic = $ipClientPublic;
    }

    /**
     * @return mixed
     */
    public function getIpClientVisibleByServer()
    {
        return $this->ipClientVisibleByServer;
    }

    /**
     * @param mixed $ipClientVisibleByServer
     */
    public function setIpClientVisibleByServer($ipClientVisibleByServer)
    {
        $this->ipClientVisibleByServer = $ipClientVisibleByServer;
    }

    /**
     * @return mixed
     */
    public function getIat()
    {
        return $this->iat;
    }

    /**
     * @param mixed $iat
     */
    public function setIat($iat)
    {
        $this->iat = $iat;
    }

    /**
     * @return mixed
     */
    public function getIss()
    {
        return $this->iss;
    }

    /**
     * @param mixed $iss
     */
    public function setIss($iss)
    {
        $this->iss = $iss;
    }

    /**
     * @return mixed
     */
    public function getExp()
    {
        return $this->exp;
    }

    /**
     * @param mixed $exp
     */
    public function setExp($exp)
    {
        $this->exp = $exp;
    }

    /**
     * @return mixed
     */
    public function getNbf()
    {
        return $this->nbf;
    }

    /**
     * @param mixed $nbf
     */
    public function setNbf($nbf)
    {
        $this->nbf = $nbf;
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

}