<?php

namespace Ciente\Controller;

use \Slim\Http\Request as Req;
use \Slim\Http\Response as Resp;
use \Exception;
use Ciente\Util\DBLayer;
use Ciente\Util\Utils;
use Ciente\Model\VO\HistoricoAtendimento;

class HistoricoAtendimentoController
{

    public static function getAll(Req $request, Resp $response)
    {

        try
        {
            $parametros = $request->getParsedBody();
            $draw = $parametros['draw'];
            $limit = $parametros['length'];
            $offset = $parametros['start'];
            $search = '%' . $parametros['search'] . '%';
            $query = DBLayer::Connect()->table(HistoricoAtendimento::TABLE_NAME)->where(function ($query) use ($request, $search)
                {
                    $query->orWhere(HistoricoAtendimento::KEY_ID, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(HistoricoAtendimento::ID_ATENDIMENTO, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(HistoricoAtendimento::ID_USUARIO, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(HistoricoAtendimento::DATA, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(HistoricoAtendimento::COMENTARIO, DBLayer::OPERATOR_ILIKE, $search);
                })
            ;
            $totalRecords = $query->count();
            $data = $query->orderBy(HistoricoAtendimento::KEY_ID, 'asc')->take($limit)->offset($offset)->get();
            $result['draw'] = $draw;
            $result['recordsFiltered'] = $totalRecords;
            $result['recordsTotal'] = $totalRecords;
            $result['data'] = $data;
        }
        catch (Exception $e)
        {
            return $response->withStatus(400)->withJson((['message' => 'Ouve um erro desconhecido.', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(200)->withJson($result);
    }

    public static function save(Req $request, Resp $response)
    {

        try
        {
            $idHistoricoAtendimentos = $request->getAttribute('idHistoricoAtendimentos');
            $formData = Utils::filterArrayByArray($request->getParsedBody(), HistoricoAtendimento::TABLE_FIELDS);

            if ($idHistoricoAtendimentos)
            {
                DBLayer::Connect()->table(HistoricoAtendimento::TABLE_NAME)->update($formData);
            }
            else
            {
                DBLayer::Connect()->table(HistoricoAtendimento::TABLE_NAME)->insertGetId($formData);
            }
        }
        catch (Exception $e)
        {
            return $response->withStatus(400)->withJson((['message' => 'Ouve um erro desconhecido.', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(200)->withJson((['message' => 'Salvo com sucesso']));
    }

    public static function get(Req $request, Resp $response)
    {

        try
        {
            $idHistoricoAtendimentos = $request->getAttribute('HistoricoAtendimentos');
            $historicoAtendimentos = DBLayer::Connect()->table(HistoricoAtendimento::TABLE_NAME)->where(HistoricoAtendimento::KEY_ID, DBLayer::OPERATOR_EQUAL, $idHistoricoAtendimentos)->first();
            return $response->withStatus(200)->withJson($historicoAtendimentos);
        }
        catch (Exception $e)
        {
            return $response->withStatus(400)->withJson(['message' => 'Ouve um erro desconhecido.', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
        }
    }

    public static function delete(Req $request, Resp $response)
    {

        try
        {
            $formData = $request->getParsedBody();
            $ids = $formData['ids'];
            $idsCount = count($ids);
            $itensDeletadosCount = 0;
            foreach ($ids as $id)
            {
                $historicoAtendimentos = DBLayer::Connect()->table(HistoricoAtendimento::TABLE_NAME)->where(HistoricoAtendimento::KEY_ID, DBLayer::OPERATOR_EQUAL, $id)->first();

                if ($historicoAtendimentos)
                {
                    if (DBLayer::Connect()->table(HistoricoAtendimento::TABLE_NAME)->delete($id))
                    {
                        $itensDeletadosCount++;
                    }
                }
            }
            if ($itensDeletadosCount == $idsCount)
            {
                return $response->withStatus(200)->withJson(['message' => 'Todos os itens foram deletados com sucesso']);
            }
            else if ($itensDeletadosCount > 0)
            {
                return $response->withStatus(200)->withJson(['message' => 'Nem todos os itens foram deletados com sucesso']);
            }
            else
            {
                return $response->withStatus(200)->withJson((['message' => 'Nenhum dos itens foram deletados']));
            }
        }
        catch (Exception $e)
        {
            return $response->withStatus(400)->withJson(['message' => 'Ouve um erro desconhecido.', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
        }
    }

}