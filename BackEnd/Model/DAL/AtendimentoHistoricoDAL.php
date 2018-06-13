<?php

namespace Ciente\Model\DAL;

use Ciente\Model\VO\Atendimento;
use Ciente\Model\VO\HistoricoAtendimento;
use Ciente\Util\DBLayer;
use PHPMailer\PHPMailer\Exception;

class AtendimentoHistoricoDAL extends AbstractDAL
{

    private static $instance = null;

    /**
     * Retorna uma instancia da classe
     *
     * @return AtendimentoHistoricoDAL
     */
    public static function getInstance () {
        if (self::$instance === null) {
            self::$instance = new AtendimentoHistoricoDAL();
        }
        return self::$instance;
    }


    /**
     * Retorna um historico
     *
     * @param $historico_id
     * @return HistoricoAtendimento $historico
     */
    public function findOne ($historico_id) {
        return $this->mountObject($this->db()->table(HistoricoAtendimento::TABLE_NAME)
            -> find($historico_id));
    }

    /**
     * Altera um historico
     *
     * @param HistoricoAtendimento $historico
     * @return mixed
     */
    public function update (HistoricoAtendimento $historico) {
        if ($historico -> getId()) {
            $data = $historico->toArray();

            $this->db()->table(HistoricoAtendimento::TABLE_NAME)
                -> where (HistoricoAtendimento::KEY_ID, '=', $historico -> getId())
                -> update($data);
        }
    }

    /**
     * @param HistoricoAtendimento $historico
     * @return HistoricoAtendimento
     */

    public function insert(HistoricoAtendimento $historico) {
        $data = $historico -> toArray();
        unset($data[HistoricoAtendimento::KEY_ID]);


        $return_id = $this->db()->table(HistoricoAtendimento::TABLE_NAME)
            ->insertGetId($data);

        $historico -> setId($return_id);

        return $historico;
    }

    /**
     * @param HistoricoAtendimento $historico
     */

    public function save (HistoricoAtendimento $historico) {

        if ($historico -> getId()) {
            $this->update($historico);
        } else {
            // insert
            $this->insert($historico);
        }
    }


    /**
     * Remove um historico
     *
     * @param $historico_id
     * @throws \Exception
     */

    public function delete ($historico_id) {

        if (is_object($historico_id) && $historico_id instanceof HistoricoAtendimento) {
            $historico_id = $historico_id -> getId();
        }

        if (!is_int($historico_id) ) {
            throw new \Exception("paramter invalid, expeted int");
        }

        $this->db()->table(HistoricoAtendimento::TABLE_NAME)
            ->delete($historico_id);

    }

    /**
     * @param $solicitacao_id
     * @return int
     */

    public function contaAtendimentosNaoConcluidosPorSolicitacao ($solicitacao_id) {
        return $this->db()->table(Atendimento::TABLE_NAME)
            -> where(Atendimento::ID_SOLICITACAO, "=", $solicitacao_id)
            -> where(Atendimento::STATUS, "<", Atendimento::CONCLUIDO)
            -> count();
    }

    /**
     * Monta objeto
     *
     * @param $data
     * @return HistoricoAtendimento
     */

    private function mountObject ($data) {
        return ((new HistoricoAtendimento())
            -> setId($data[HistoricoAtendimento::KEY_ID])
            -> setComentario($data[HistoricoAtendimento::COMENTARIO])
            -> setData($data[HistoricoAtendimento::DATA])
            -> setIdAtendimento($data[HistoricoAtendimento::ID_ATENDIMENTO])
            -> setIdUsuario($data[HistoricoAtendimento::ID_USUARIO])
            -> setTipo($data[HistoricoAtendimento::TIPO]));
    }

}