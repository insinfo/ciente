<?php

namespace Ciente\Controller;

use \Slim\Http\Request as Req;
use \Slim\Http\Response as Resp;
use \Exception;
use Ciente\Util\DBLayer;
use Ciente\Util\Utils;
use Ciente\Model\VO\Pendencia;

class PendenciaController
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
            $query = DBLayer::Connect()->table(Pendencia::TABLE_NAME)->where(function ($query) use ($request, $search)
                {
                    $query->orWhere(Pendencia::KEY_ID, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Pendencia::ID_ATENDIMENTO, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Pendencia::ID_HISTORICO_ATENDIMENTO, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Pendencia::DATA_INICIO, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Pendencia::DATA_FIM, DBLayer::OPERATOR_ILIKE, $search);
                })
            ;
            $totalRecords = $query->count();
            $data = $query->orderBy(Pendencia::KEY_ID, 'asc')->take($limit)->offset($offset)->get();
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
            $idPendencias = $request->getAttribute('idPendencias');
            $formData = Utils::filterArrayByArray($request->getParsedBody(), Pendencia::TABLE_FIELDS);

            if ($idPendencias)
            {
                DBLayer::Connect()->table(Pendencia::TABLE_NAME)->update($formData);
            }
            else
            {
                DBLayer::Connect()->table(Pendencia::TABLE_NAME)->insertGetId($formData);
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
            $idPendencias = $request->getAttribute('Pendencias');
            $pendencias = DBLayer::Connect()->table(Pendencia::TABLE_NAME)->where(Pendencia::KEY_ID, DBLayer::OPERATOR_EQUAL, $idPendencias)->first();
            return $response->withStatus(200)->withJson($pendencias);
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
                $pendencias = DBLayer::Connect()->table(Pendencia::TABLE_NAME)->where(Pendencia::KEY_ID, DBLayer::OPERATOR_EQUAL, $id)->first();

                if ($pendencias)
                {
                    if (DBLayer::Connect()->table(Pendencia::TABLE_NAME)->delete($id))
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