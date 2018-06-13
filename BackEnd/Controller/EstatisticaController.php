<?php

namespace Ciente\Controller;

use \Slim\Http\Request as Req;
use \Slim\Http\Response as Resp;
use \Exception;
use Ciente\Util\DBLayer;
use Ciente\Util\Utils;
use Ciente\Model\VO\Usuario;
use Ciente\Model\VO\Solicitacao;
use Ciente\Model\VO\Atendimento;

class EstatisticaController
{

    public static function getStatusAtendimentoSetor(Req $request, Resp $response)
    {
        try
        {
            $idSetor = $request->getAttribute('idSetor');

            DBLayer::Connect();

            $query = DBLayer::table(DBLayer::raw('atendimentos a'))
                ->from(DBLayer::raw('atendimentos a'))
                ->select(DBLayer::raw('status, count(a.id)'))
                ->where('a.idSetor',"=","$idSetor")
                ->groupBy('a.idSetor','status')
                ->orderBy('a.status');

            $data = $query->get();

            $result['data'] = $data;

            /*
            select a."idSetor", a.status,count(a.id)
            from ciente.atendimentos a
            where "idSetor"=5
            group by "idSetor", status;
                    */
        }
        catch (Exception $e)
        {
            return $response->withStatus(400)->withJson(['message' => 'Ouve um erro desconhecido.', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
        }

        return $response->withStatus(200)->withJson($result);

    }

    public static function getPrioridadeSolicitacaoSetor(Req $request, Resp $response)
    {
        try
        {
            $idSetor = $request->getAttribute('idSetor');

            DBLayer::Connect();

            $query = DBLayer::table(DBLayer::raw('atendimentos a'))
                ->from(DBLayer::raw('atendimentos a'))
                ->select(DBLayer::raw('b.prioridade, count(a.id)'))
                ->join(DBLayer::raw("solicitacoes b"),"a.idSolicitacao","=","b.id")
                //->where('a.idSetor',"=","$idSetor")
                ->whereRaw('a."idSetor"  = '."$idSetor AND status < 3")
                ->groupBy('a.idSetor','b.prioridade')
                ->orderBy('b.prioridade');

            $data = $query->get();

            $result['data'] = $data;

            /*
            select a."idSetor", b.prioridade,count(a.id)
            from ciente.atendimentos a, ciente.solicitacoes b
            where (a."idSolicitacao"=b.id)AND(a."idSetor"=4)
            group by a."idSetor", b.prioridade
            */

        }
        catch (Exception $e)
        {
            return $response->withStatus(400)->withJson(['message' => 'Ouve um erro desconhecido.', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
        }

        return $response->withStatus(200)->withJson($result);

    }

}