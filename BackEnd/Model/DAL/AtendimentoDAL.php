<?php

namespace Ciente\Model\DAL;


use Ciente\Model\VO\Atendimento;
use Ciente\Model\VO\Pendencia;
use Ciente\Model\DAL\PendenciaDAL;
use Ciente\Util\DBLayer;
use Ciente\Util\Utils;
use Symfony\Component\Routing\Exception\InvalidParameterException;

class AtendimentoDAL extends AbstractDAL
{
    private static $instance = null;

    /**
     * Retorna uma instancia da classe
     *
     * @return AtendimentoDAL
     */
    public static function getInstance () {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    /**
     * Remove um atendimento
     *
     * @param $atendimento
     */
    public function delete ($atendimento) {}

    /**
     * Busca um atendimento
     *
     * @param $atendimento_id
     * @return Atendimento
     */
    public function findOne($atendimento_id) {

        if (!$atendimento_id) {
            throw new InvalidParameterException("Esperado um inteiro como id do atendimento");
        }

        return $this->mountObject($this->db()->table(Atendimento::TABLE_NAME)
            -> find($atendimento_id));
    }

    /**
     * Monta atendimento
     *
     * @param $data
     * @return Atendimento
     */
    private function mountObject ($data) {
        return ((new Atendimento())
            -> setId($data[Atendimento::KEY_ID])
            -> setIdServico($data[Atendimento::ID_SERVICO])
            -> setIdSolicitacao($data[Atendimento::ID_SOLICITACAO])
            -> setIdSetor($data[Atendimento::ID_SETOR])
            -> setIdUsuario($data[Atendimento::ID_USUARIO])
            -> setDataEncaminhamento($data[Atendimento::DATA_ENCAMINHAMENTO])
            -> setDataFimAtendimento($data[Atendimento::DATA_FIM_ATENDIMENTO])
            -> setDataInicioAtendimento($data[Atendimento::DATA_INICIO_ATENDIMENTO])
            -> setIdProduto($data[Atendimento::ID_PRODUTO])
            -> setStatus($data[Atendimento::STATUS]));

    }

    /**
     * @param Atendimento $atendimento
     * @return Atendimento|null
     */

    public function save(Atendimento $atendimento) {
        $result = null;

        if ($atendimento -> getId()) {
            $result = $this->update($atendimento);
        } else {
            $result = $this->insert($atendimento);
        }

        if ($result) {
            // salva historicos
            $historicos = $atendimento->getHistoricos();

            // realiza update do history
            foreach ($historicos as $historico) {
                AtendimentoHistoricoDAL::getInstance()->save($historico);
            }

            // salva pendencia se houver
            $pendencia = $atendimento->getPendencia();

            // salva pendencia
            if ($pendencia) {
                PendenciaDAL::getInstance()->save($pendencia);
            }

            // arquivos
            $anexos = $atendimento->getAnexos();
            if ($anexos) {
                foreach ($anexos as $anexo) {
                    AnexoDAL::getInstance()->save($anexo);
                }
            }
        }

        return $result;
    }

    /**
     * Atualiza os dados de um atendimento
     *
     * @param Atendimento $atendimento
     * @return mixed
     */
    public function update (Atendimento $atendimento) {

        $result = $this->db()->table(Atendimento::TABLE_NAME)
            -> where(Atendimento::KEY_ID, '=', $atendimento->getId())
            -> update($atendimento->toArray());

        if ($result) {
            return $atendimento;
        } else {
            return null;
        }
    }

    /**
     * @param Atendimento $atendimento
     * @return Atendimento
     */

    public function insert(Atendimento $atendimento) {
        $data = $atendimento->toArray();

        unset($data[Atendimento::KEY_ID]);

        $id = $this->db()->table(Atendimento::TABLE_NAME)
            -> insertGetId($data);

        return ($atendimento->setId($id));
    }

}