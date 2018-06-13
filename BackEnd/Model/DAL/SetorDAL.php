<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 09/01/2018
 * Time: 18:48
 */

namespace Ciente\Model\DAL;

use Ciente\Util\DBLayer;
use Ciente\Util\Utils;

use Ciente\Model\VO\SetorHistorico;
use Ciente\Model\VO\Setor;

class SetorDAL extends AbstractDAL
{

    private static $instance = null;

    public static function getInstance () {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Retorna um setor atraves do seu id
     *
     * @param $setorId
     */
    public function findOne ($setorId) {

    }

    //Listar Todos os setores
    public function getHierarquia($idSetorPai = null, $date = null, $filter = null)
    {
        if ($date == null)
        {
            $date = Utils::getDateNow();
        }

        $idPai = 'is NULL';

        if ($idSetorPai != null && $idSetorPai != "")
        {
            $idPai = ' = ' . $idSetorPai;
        }

        $queryString = '';
        $queryString .= ' "' . Setor::TABLE_NAME . '" s, "' . SetorHistorico::TABLE_NAME . '" sh ';
        $queryString .= ' WHERE s."' . Setor::KEY_ID . '" = sh."' . SetorHistorico::ID_SETOR . '" AND sh."' . SetorHistorico::ID_PAI . '" ' . $idPai . ' ';

        if($filter != null)
        {
            $filter = $filter ? "'t'" : "'f'";
            $queryString .= ' AND  s.' . Setor::ATIVO . ' = '.$filter;
        }

        $queryString .= ' AND sh."' . SetorHistorico::DATA_INICIO . '" = (SELECT max("' . SetorHistorico::DATA_INICIO . '") FROM "' . SetorHistorico::TABLE_NAME . '" ';
        $queryString .= 'WHERE "' . SetorHistorico::ID_SETOR . '" = s."' . Setor::KEY_ID . '" AND "' . SetorHistorico::DATA_INICIO . '" <= ' . "'" . $date . "')";

        $query = $this->db()->table(DBLayer::raw($queryString));

        $data = $query->orderBy(SetorHistorico::SIGLA)->get();
        //$data['sql'] = $query->toSql();

        $result = array();

        if (count($data) < 1)
        {
            $data = null;
        }

        if ($data != null)
        {
            for ($i = 0; $i < count($data); $i++)
            {
                $item = $data[$i];
                $idSetor = $item[SetorHistorico::ID_SETOR];
                if ($idSetor != "")
                {
                    $setor = array();
                    $setor[SetorHistorico::ID_SETOR] = $idSetor;
                    $setor[SetorHistorico::ID_PAI] = $item[SetorHistorico::ID_PAI];
                    $setor[Setor::ATIVO] = $item[Setor::ATIVO];
                    $setor[SetorHistorico::DATA_INICIO] = $item[SetorHistorico::DATA_INICIO];
                    $setor[SetorHistorico::SIGLA] = $item[SetorHistorico::SIGLA];
                    $setor[SetorHistorico::NOME] = $item[SetorHistorico::NOME];
                    $sigla = $item[SetorHistorico::SIGLA] ? $item[SetorHistorico::SIGLA] . ' - ' : "";
                    $setor['text'] = $sigla . $item[SetorHistorico::NOME];

                    $filhos = $this->getHierarquia($idSetor, $date,$filter);

                    if ($filhos != null)
                    {
                        $setor['nodes'] = $filhos;
                    }
                    array_push($result, $setor);
                }
            }
        }

        /*if (count($result) < 1)
        {
            return null;
        }*/

        return $result;
    }

}