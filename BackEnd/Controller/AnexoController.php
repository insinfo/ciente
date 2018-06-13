<?php

namespace Ciente\Controller;

use \Slim\Http\Request as Req;
use \Slim\Http\Response as Resp;
use \Exception;
use Ciente\Util\DBLayer;
use Ciente\Util\Utils;
use Ciente\Model\VO\Anexo;

class AnexoController
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
            $query = DBLayer::Connect()->table(Anexo::TABLE_NAME)->where(function ($query) use ($request, $search)
                {
                    $query->orWhere(Anexo::KEY_ID, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Anexo::ID_SOLICITACAO, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Anexo::ID_ATENDIMENTO, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Anexo::ID_COMUNICACAO_INTERNA, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Anexo::CAMINHO, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Anexo::DATA, DBLayer::OPERATOR_ILIKE, $search);
                })
            ;
            $totalRecords = $query->count();
            $data = $query->orderBy(Anexo::KEY_ID, DBLayer::ORDER_DIRE_ASC)->take($limit)->offset($offset)->get();
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
            $idAnexos = $request->getAttribute('idAnexos');
            $formData = Utils::filterArrayByArray($request->getParsedBody(), Anexo::TABLE_FIELDS);

            if ($idAnexos)
            {
                DBLayer::Connect()->table(Anexo::TABLE_NAME)->update($formData);
            }
            else
            {
                DBLayer::Connect()->table(Anexo::TABLE_NAME)->insertGetId($formData);
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
            $idAnexos = $request->getAttribute('Anexos');
            $anexos = DBLayer::Connect()->table(Anexo::TABLE_NAME)->where(Anexo::KEY_ID, DBLayer::OPERATOR_EQUAL, $idAnexos)->first();
            return $response->withStatus(200)->withJson($anexos);
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
                $anexos = DBLayer::Connect()->table(Anexo::TABLE_NAME)->where(Anexo::KEY_ID, DBLayer::OPERATOR_EQUAL, $id)->first();

                if ($anexos)
                {
                    if (DBLayer::Connect()->table(Anexo::TABLE_NAME)->delete($id))
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