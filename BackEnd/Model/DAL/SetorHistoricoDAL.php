<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 09/01/2018
 * Time: 18:48
 */

namespace Ciente\Model\DAL;

use Ciente\Model\VO\Pessoa;
use Ciente\Model\VO\Setor;
use Ciente\Model\VO\SetorUsuario;
use Ciente\Util\DBLayer;
use Ciente\Util\Utils;

use Ciente\Model\VO\SetorHistorico;
use ParagonIE\Halite\Util;

class SetorHistoricoDAL extends AbstractDAL
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
     * @return SetorHistorico
     */
    public function findOne ($setorId, $date = null) {

        if ($date === null) {
            $date = Utils::getDateTimeNow("EN");
        }

        $data = $this->db()->table(DBLayer::raw("func_setores('".$date."')"))
            ->where(SetorHistorico::KEY_ID, "=", $setorId)
            ->first();

        return $this -> mount($data);
    }

    /**
     * Monta o model com o seus dados
     *
     * @param array $data
     * @return SetorHistorico
     */

    public function mount($data) {

        $setorHistorico = new SetorHistorico();
        $setorHistorico -> setIdSetor($data['idSetor'])
            -> setDataInicio($data['dataInicio'])
            -> setSigla($data['sigla'])
            -> setNome($data['nome'])
            -> setIdPai($data['idPai'])
            -> setAtivo($data['ativo']);
        return $setorHistorico;
    }

    /**
     * Retorna todos os setores onde a pessoa se encontra
     *
     * @param $pessoa_id
     * @param null $date
     * @return array SetorHistorico
     */

    public function getPorPessoaId ($pessoa_id, $date = null) {

        if ($date === null) {
            $date = Utils::getDateTimeNow("EN");
        }

        $data = $this->db()->table(DBLayer::raw("func_setores('".$date."') fs"))
            ->leftJoin(DBLayer::raw(Utils::adAspas(SetorUsuario::TABLE_NAME) . " su"),
                DBLayer::raw("su.". Utils::adAspas(SetorUsuario::ID_SETOR)),
                "=",
                DBLayer::raw("fs." . Utils::adAspas(SetorUsuario::ID_SETOR))
                )
            ->where(DBLayer::raw("su." . Utils::adAspas(SetorUsuario::ID_PESSOA)), "=", $pessoa_id)
            ->get();

        $setores = [];

        foreach ($data as $d) {
            $setores[] = $this->mount($d);
        }

        return $setores;
    }


}