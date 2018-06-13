<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 22/05/2018
 * Time: 17:29
 */

namespace Ciente\Controller;

use \Slim\Http\Request;
use \Slim\Http\Response;
use \Exception;
use Ciente\Util\StatusMessage;
use Ciente\Util\StatusCode;
use Ciente\Model\VO\ViewSolicitacao;
use Ciente\Util\DBLayer;
use Ciente\Model\VO\Token;

class MinhaSolicitacaoController
{
    public static function getAll(Request $request, Response $response)
    {
        try {
            $token = new Token($request);
            $params = $request->getParsedBody();
            $draw = isset($params['draw']) ? $params['length'] : null;
            $limit = isset($params['length']) ? $params['length'] : null;
            $offset = isset($params['start']) ? $params['start'] : null;
            $search = isset($params['search']) ? '%' . $params['search'] . '%' : null;
            $ordering = isset($params['ordering']) ? $params['ordering'] : null;

            //estras
            $prioridade = isset($params['prioridade']) ? $params['prioridade'] : null;
            $status = isset($params['status']) ? $params['status'] : null;
            $setorResponsavel = isset($params['setorResponsavel']) ? $params['setorResponsavel'] : null;

            $query = DBLayer::Connect()->table(ViewSolicitacao::TABLE_NAME);
            $query->where(ViewSolicitacao::ID_SOLICITANTE,'=',$token->getIdPessoa());

            if ($search != null) {
                $query->where(function ($query) use ($request, $search) {
                    $query->orWhere(ViewSolicitacao::CPF_SOLICITANTE, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(ViewSolicitacao::NOME_ORGANOGRAMA, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(ViewSolicitacao::NOME_SOLICITANTE, DBLayer::OPERATOR_ILIKE, $search);
                    $query->whereRaw(" ano||numero ilike '$search'");
                    $query->orWhere(ViewSolicitacao::ANO, DBLayer::OPERATOR_ILIKE, $search);
                });
            }

            //filtra por prioridade
            if($prioridade != null && count($prioridade) > 0){
                $query->whereIn(ViewSolicitacao::PRIORIDADE,$prioridade);
            }

            //filtra por status
            if($status != null && count($status) > 0){
                $query->whereIn(ViewSolicitacao::MIN_STATUS,$status);
            }

            //filtra por setor Responsavel
            if($setorResponsavel != null){
                $query->where(ViewSolicitacao::ID_SETOR,"=",$setorResponsavel);
            }

            $totalRecords = $query->count();

            //implementação da ordenação do ModernDataTable
            if ($ordering != null && count($ordering) > 0) {
                foreach ($ordering as $item) {
                    $query->orderBy($item['columnKey'], $item['direction']);
                }
            } else {
                $query->orderBy(ViewSolicitacao::KEY_ID);
            }

            if ($limit != null) {

                $data = $query->limit($limit)->offset($offset)->get();

            } else {
                $data = $query->get();
            }

            $result['draw'] = $draw;
            $result['recordsFiltered'] = $totalRecords;
            $result['recordsTotal'] = $totalRecords;
            $result['data'] = $data;
            return $response->withStatus(StatusCode::SUCCESS)->withJson($result);

        } catch (Exception $e) {
            return $response->withStatus(StatusCode::BAD_REQUEST)
                ->withJson((['message' => StatusMessage::MENSAGEM_ERRO_PADRAO, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
    }

    public static function advancedSearch(Request $request, Response $response)
    {
        try
        {
            $token = new Token($request);
            $params = $request->getParsedBody();
            $draw = isset($params['draw']) ? $params['length'] : null;
            $limit = isset($params['length']) ? $params['length'] : null;
            $offset = isset($params['start']) ? $params['start'] : null;
            $search = isset($params['search']) ? $params['search'] : null;
            $ordering = isset($params['ordering']) ? $params['ordering'] : null;
            $prioridade = isset($params['prioridade']) ? $params['prioridade'] : null;
            $status = isset($params['status']) ? $params['status'] : null;
            $idOrganograma = isset($params['idOrganograma']) ? $params['idOrganograma'] : null;
            $dataInicio = isset($params['dataInicio']) ? $params['dataInicio'] : null;
            $dataFim = isset($params['dataFim']) ? $params['dataFim'] : null;

            $query = DBLayer::Connect()->table(ViewSolicitacao::TABLE_NAME);

            if ($search != null) {
                $query->where(function ($query) use ($request, $search) {
                    foreach ($search as $value) {
                        $val = '%'.$value.'%';
                        $query->orWhere(ViewSolicitacao::CPF_SOLICITANTE, "ilike", $val);
                        $query->orWhere(ViewSolicitacao::NOME_ORGANOGRAMA, "ilike", $val);
                        $query->orWhere(ViewSolicitacao::NOME_SOLICITANTE, "ilike", $val);
                        $query->orWhere(ViewSolicitacao::DESCRICAO, "ilike", $val);
                        $query->orWhereRaw(" ano||numero ilike '$val'");
                    }
                });
            }

            //filtra por data de abertura
            if($dataInicio != null && $dataFim != null){
                $query->whereBetween(ViewSolicitacao::DATA_ABERTURA,[$dataInicio,$dataFim]);
            }

            //filtra por prioridade
            if($prioridade != null && count($prioridade) > 0){
                $query->whereIn(ViewSolicitacao::PRIORIDADE,$prioridade);
            }

            //filtra por status
            if($status != null && count($status) > 0){
               $query->whereIn(ViewSolicitacao::MIN_STATUS,$status);
            }

            //filtra por organograma
            if($idOrganograma != null){
                $query->where(ViewSolicitacao::ID_ORGANOGRAMA,"=",$idOrganograma);
            }

            $totalRecords = $query->count();

            //implementação da ordenação do ModernDataTable
            if ($ordering != null && count($ordering) > 0) {
                foreach ($ordering as $item) {
                    $query->orderBy($item['columnKey'], $item['direction']);
                }
            } else {
                $query->orderBy(ViewSolicitacao::KEY_ID);
            }

            if ($limit != null) {

                $data = $query->limit($limit)->offset($offset)->get();

            } else {
                $data = $query->get();
            }

            $result['draw'] = $draw;
            $result['recordsFiltered'] = $totalRecords;
            $result['recordsTotal'] = $totalRecords;
            $result['data'] = $data;
            return $response->withStatus(StatusCode::SUCCESS)->withJson($result);

        } catch (Exception $e)
        {
            return $response->withStatus(StatusCode::BAD_REQUEST)
                ->withJson((['message' => StatusMessage::MENSAGEM_ERRO_PADRAO, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
    }
}