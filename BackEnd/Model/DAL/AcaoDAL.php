<?php


namespace Ciente\Model\DAL;

use Ciente\Util\Utils;

class ComentarioDAL extends AtendimentoHistoricoDAL
{

    /**
     * @param $mensagem
     * @param $atendimento_id
     * @param $usuario_logado_id
     * @return mixed
     */

    public function create ($mensagem, $atendimento_id, $usuario_logado_id) {
        return $this->create($mensagem, $atendimento_id, $usuario_logado_id, Utils::getDateTimeNow('en'), 'A');
    }
}