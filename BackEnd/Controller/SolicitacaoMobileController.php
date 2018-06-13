<?php

namespace Ciente\Controller;

use \Slim\Http\Request as Req;
use \Slim\Http\Response as Resp;
use \Exception;
use Ciente\Util\DBLayer;
use Ciente\Util\Utils;
use Ciente\Model\VO\SolicitacaoMobile;

class SolicitacaoMobileController
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
            $query = DBLayer::Connect()->table(SolicitacaoMobile::TABLE_NAME)->where(function ($query) use ($request, $search)
                {
                    $query->orWhere(SolicitacaoMobile::KEY_ID, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(SolicitacaoMobile::TOKEN_MOBILE, DBLayer::OPERATOR_ILIKE, $search);
                })
            ;
            $totalRecords = $query->count();
            $data = $query->orderBy(SolicitacaoMobile::KEY_ID, 'asc')->take($limit)->offset($offset)->get();
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
            $idSolicitacoesMobile = $request->getAttribute('idSolicitacoesMobile');
            $formData = Utils::filterArrayByArray($request->getParsedBody(), SolicitacaoMobile::TABLE_FIELDS);

            if ($idSolicitacoesMobile)
            {
                DBLayer::Connect()->table(SolicitacaoMobile::TABLE_NAME)->update($formData);
            }
            else
            {
                DBLayer::Connect()->table(SolicitacaoMobile::TABLE_NAME)->insertGetId($formData);
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
            $idSolicitacoesMobile = $request->getAttribute('SolicitacoesMobile');
            $solicitacoesMobile = DBLayer::Connect()->table(SolicitacaoMobile::TABLE_NAME)->where(SolicitacaoMobile::KEY_ID, DBLayer::OPERATOR_EQUAL, $idSolicitacoesMobile)->first();
            return $response->withStatus(200)->withJson($solicitacoesMobile);
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
                $solicitacoesMobile = DBLayer::Connect()->table(SolicitacaoMobile::TABLE_NAME)->where(SolicitacaoMobile::KEY_ID, DBLayer::OPERATOR_EQUAL, $id)->first();

                if ($solicitacoesMobile)
                {
                    if (DBLayer::Connect()->table(SolicitacaoMobile::TABLE_NAME)->delete($id))
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