<?php
/**
 * Created by PhpStorm.
 * User: Isaque
 * Date: 30/08/2017
 * Time: 17:24
 */

namespace Ciente\Util;

use PmroPadraoLib\Util\Exceptions\ArgumentNullOrEmptyException;
use PmroPadraoLib\Util\Exceptions\InvalidCredentialsException;
use PmroPadraoLib\Util\Utils;
use \Exception;

class LdapAuth
{
    private $ldapHost = 'ldap://192.168.133.10';//active airectory server ex "server.college.school.edu"
    private $ldapPort = '';
    private $ldapDomain = 'DC=dcro,DC=gov';//active directory domain name DN (base location of ldap search) ex "OU=Departments,DC=college,DC=school,DC=edu"
    private $ldapUserGroup = null;// active directory user group name ex  KAnboard_users
    private $ldapManagerGroup = null;// active directory manager group name ex Kanboard_gerente
    private $ldapUserDomain = '@dcro.gov';// domain, for purposes of constructing $user ex '@college.school.edu'
    private $ldapConnection = null;
    private $authenticatedUser = null;

    public function setHost($ldapHost)
    {
        $this->ldapHost = $ldapHost;
    }

    public function setPort($ldapPort)
    {
        $this->ldapPort = $ldapPort;
    }

    public function setDomain($ldapDomain)
    {
        $this->ldapDomain = $ldapDomain;
    }

    public function setUserGroup($ldapUserGroup)
    {
        $this->ldapUserGroup = $ldapUserGroup;
    }

    public function setManagerGroup($ldapManagerGroup)
    {
        $this->ldapManagerGroup = $ldapManagerGroup;
    }

    public function setUserDomain($ldapUserDomain)
    {
        $this->ldapManagerGroup = $ldapUserDomain;
    }

    private function connect()
    {
        $this->ldapConnection = ldap_connect($this->ldapHost);
    }

    public function authenticate($user, $password)
    {
        if (empty($user))
        {
            new ArgumentNullOrEmptyException('Usuario não pode ser nulo ou vazio!');
        }
        if (empty($password))
        {
            new ArgumentNullOrEmptyException('Senha não pode ser nula ou vazia!');
        }

        $this->connect();

        $ldaprdn = $user . $this->ldapUserDomain;

        ldap_set_option($this->ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->ldapConnection, LDAP_OPT_REFERRALS, 0);

        // verifica usuario e senha
        if ($bind = @ldap_bind($this->ldapConnection, $ldaprdn, $password))
        {
            $this->authenticatedUser = $user;
            return true;
            /*
            // válido
            // verificar presença em grupos
            $filter = "(sAMAccountName=" . $user . ")";
            $attr = array("memberof");
            $result = ldap_search($this->ldapConnection, $this->ldapDomain, $filter, $attr) or exit("Unable to search LDAP server");
            $entries = ldap_get_entries($this->ldapConnection, $result);
            ldap_unbind($this->ldapConnection);

            $access = 0;
            // Verificar se o usuario pertence a um grupo
            foreach ($entries[0]['memberof'] as $grps)
            {
                // É gerente, então interronpa o loop
                if (strpos($grps, $this->ldapManagerGroup))
                {
                    $access = 2;
                    break;
                }

                // É usuário
                if (strpos($grps, $this->ldapUserGroup))
                {
                    $access = 1;
                }
            }

            if ($access != 0)
            {
                // Estabelecer variáveis de sessão
                $_SESSION['user'] = $user;
                $_SESSION['access'] = $access;
                return true;
            }
            else
            {
                // O usuário não possui direitos
                return false;
            }*/
        }
        else
        {
            //credencial invalida
            // nome ou senha invalido
            new InvalidCredentialsException();
        }
        return false;
    }

    public function getAllUser()
    {
        if ($this->ldapConnection == null && $this->authenticatedUser == null)
        {
            new Exception('Autentique primeiro!', 400);
        }
        $lista = array();

        $base = $this->ldapDomain;
        $filter = "(sAMAccountName=*)";
        $result = ldap_search($this->ldapConnection, $base, $filter);

        $entries = ldap_get_entries($this->ldapConnection, $result);

        for ($i = 0; $i < $entries["count"]; $i++)
        {
            $item = array();
            $item['nome'] = $entries[$i]['cn'][0];
            $item['login'] = $entries[$i]['samaccountname'][0];
            array_push($lista, $item);
        }
        return $lista;
    }

    public function countRegisteredUsers()
    {
        if ($this->ldapConnection == null && $this->authenticatedUser == null)
        {
            new Exception('Autentique primeiro!', 400);
        }

        $base = $this->ldapDomain;
        $filter = "(sAMAccountName=*)";
        $result = ldap_search($this->ldapConnection, $base, $filter);
        $entries = ldap_get_entries($this->ldapConnection, $result);
        return $entries["count"];
    }

    public function countTotalNumberOfPages($recordsPerPage)
    {
        $totalRecords = $this->countRegisteredUsers();
        $totalNumberOfPages = ceil($totalRecords / $recordsPerPage);
        return $totalNumberOfPages;
    }

    /**
     * Defina aqui a quantidade máxima de registros por página
     * e a pagina atual
     **/
    public function getAllUserByLimit($correntPage = 1, $recordsPerPage = 50)
    {
        if ($this->ldapConnection == null && $this->authenticatedUser == null)
        {
            new Exception('Autentique primeiro!', 400);
        }
        $lista = array();

        $base = $this->ldapDomain;
        $filter = "(sAMAccountName=*)";
        $result = ldap_search($this->ldapConnection, $base, $filter);
        $entries = ldap_get_entries($this->ldapConnection, $result);

        if ($correntPage < 0)
        {
            $correntPage = 0;
        }
        if ($correntPage == 0)
        {
            $correntPage = $correntPage + 1;
        }
        $totalRecords = $entries["count"];
        $totalNumberOfPages = ceil($totalRecords / $recordsPerPage);
        // calculando início e fim da seleção
        $qtdrow = $recordsPerPage;
        $pagfim = ($correntPage * $qtdrow);//72*10 =720
        $pagini = ($pagfim - $qtdrow) + 1;//720-10+1=711

        if ($correntPage > $totalNumberOfPages)
        {
            return null;
        }

        for ($i = $pagini - 1; $i < $pagfim; $i++)
        {
            if ($i == $totalRecords)
            {
                break;
            }
            $item = array();
            $item['nome'] = $entries[$i]['cn'][0];
            $item['login'] = $entries[$i]['samaccountname'][0];
            array_push($lista, $item);
        }
        return $lista;
    }

    public function getUserByLoginName($accountName)
    {
        if ($this->ldapConnection == null && $this->authenticatedUser == null)
        {
            new Exception('Autentique primeiro!', 400);
        }

        $base = $this->ldapDomain;
        $filter = "(sAMAccountName=$accountName)";
        $result = ldap_search($this->ldapConnection, $base, $filter);

        $entries = ldap_get_entries($this->ldapConnection, $result);
        $toralRecords = $entries["count"];
        if ($toralRecords == 0)
        {
            return null;
        }
        $lista = array();
        for ($i = 0; $i < $toralRecords; $i++)
        {
            $item = array();
            $item['nome'] = $entries[$i]['cn'][0];
            $item['login'] = $entries[$i]['samaccountname'][0];
            $item['menbroDe'] = $entries[$i]['memberof'];

            array_push($lista, $item);
        }
        return $lista;
    }

    /** procura usuario pelo nome **/
    public function findUserByName($name, $limit = null)
    {
        if ($this->ldapConnection == null && $this->authenticatedUser == null)
        {
            new Exception('Autentique primeiro!', 400);
        }

        $lista = array();

        $base = $this->ldapDomain;
        $filter = "(sAMAccountName=*)";
        $result = ldap_search($this->ldapConnection, $base, $filter);

        $entries = ldap_get_entries($this->ldapConnection, $result);
        $name = strtolower(Utils::RemoveAccents($name));

        for ($i = 0; $i < $entries["count"]; $i++)
        {
            $cn = $entries[$i]['cn'][0];
            if (strpos(strtolower($cn), $name) !== false)
            {
                $item = array();
                $item['nome'] = $cn;
                $item['login'] = $entries[$i]['samaccountname'][0];
                //$item['menbroDe'] = $entries[$i]['member'];
                array_push($lista, $item);
            }
            if ($limit != null)
            {
                if ($i == $limit)
                {
                    break;
                }
            }
        }
        return $lista;
    }

    /**alteracao de senha no ldap**/
    public function changePassword($newPassword)
    {
        if ($this->ldapConnection == null && $this->authenticatedUser == null)
        {
            new Exception('Autentique primeiro!', 400);
        }
        $ldaprdn = $this->authenticatedUser . $this->ldapUserDomain;

        $info["userPassword"] = $newPassword;

        $rs = ldap_mod_replace($this->ldapConnection, $ldaprdn, $info);

        if ($rs)
        {
            return true;
        }
        return false;
    }

    public function createNewUser($conpleteName, $username, $password, $email = '')
    {
        if ($this->ldapConnection == null && $this->authenticatedUser == null)
        {
            new Exception('Autentique primeiro!', 400);
        }

        // Prepare data
        $info['cn'] = $conpleteName;
        $info['sn'] = " ";
        $info['mail'] = $email;
        $info['objectclass'] = "inetOrgPerson";
        $info['userpassword'] = $password;
        $info['samaccountname'] = $username;

        // Add data to directory
        $rs = ldap_add($this->ldapConnection, "cn=$conpleteName,".$this->ldapDomain, $info);
        ldap_close($this->ldapConnection);
        if ($rs)
        {
            return true;
        }
        return false;
    }

    public function deleteUser($userName)
    {
        if ($this->ldapConnection == null && $this->authenticatedUser == null)
        {
            new Exception('Autentique primeiro!', 400);
        }

        $base = $this->ldapDomain;
        $filter = "(sAMAccountName=$userName)";
        $result = ldap_search($this->ldapConnection, $base, $filter);
        $info = ldap_get_entries($this->ldapConnection,$result);
        $dn = $info[0]["dn"];
        $rs= ldap_delete($this->ldapConnection,$dn);
        ldap_close($this->ldapConnection);

        if ($rs)
        {
            return true;
        }
        return false;
    }


}