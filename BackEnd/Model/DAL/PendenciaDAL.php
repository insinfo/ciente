<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 09/01/2018
 * Time: 18:48
 */

namespace Ciente\Model\DAL;

use Ciente\Model\VO\Atendimento;
use Ciente\Model\VO\Pendencia;
use Ciente\Util\DBLayer;

class PendenciaDAL extends AbstractDAL
{

    private static $instance = null;

    public static function getInstance () {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Retorna ultima pendencia aberta de um atendimento
     *
     * @param int $atendimentoId
     * @return Pendencia $pendencia
     */

    public function findForAtendimentoIdAndIsOpen($atendimentoId) {
        $data = $this->db()->table(Pendencia::TABLE_NAME)
            -> where(Pendencia::ID_ATENDIMENTO, "=", $atendimentoId)
            -> whereNull(Pendencia::DATA_FIM)
            -> first();

        return $this->mount($data);
    }

    /**
     * Salva uma pendencia. Insere nova se nao existir
     *
     * @param Pendencia $pendencia
     */

    public function save(Pendencia $pendencia) {
        if ($pendencia -> getId()) {
            return $this->update($pendencia);
        }

        return $this->insert($pendencia);
    }


    public function update(Pendencia $pendencia) {
        $data = $pendencia -> toArray();

        $this->db()->table(Pendencia::TABLE_NAME)
            -> where(Pendencia::KEY_ID, "=", $pendencia->getId())
            -> update($data);

        return $pendencia;
    }

    public function insert(Pendencia $pendencia) {
        $data = $pendencia -> toArray();
        unset ($data['id']);

        $this->db()->table(Pendencia::TABLE_NAME)
            ->insertGetId($data);
    }

    public function mount($data) {
        $pendencia = new Pendencia();
        $pendencia -> setId($data[Pendencia::KEY_ID])
            -> setIdAtendimento($data[Pendencia::ID_ATENDIMENTO])
            -> setDataInicio($data[Pendencia::DATA_INICIO])
            -> setDataFim($data[Pendencia::DATA_FIM])
            -> setIdHistoricoAtendimento($data[Pendencia::ID_HISTORICO_ATENDIMENTO]);

        return $pendencia;
    }



    /**
     * Retorna um setor atraves do seu id
     *
     * @param $setorId
     */
    public function findOne ($setorId) {

    }


}