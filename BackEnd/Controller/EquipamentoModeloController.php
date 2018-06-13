<?php

namespace Ciente\Controller;

use \Slim\Http\Request as Req;
use \Slim\Http\Response as Resp;
use \Exception;
use Ciente\Util\DBLayer;
use Ciente\Util\Utils;
use Ciente\Model\VO\EquipamentoModelo;
use Ciente\Model\VO\EquipamentoTipo;

class EquipamentoModeloController
{

    public static function getAll(Req $request, Resp $response)
    {
        $search = '';
        try
        {
            //lista os modelos de equipamentos do usuario logado
            $param = $request->getParsedBody();
            $draw = isset($param['draw']) ? $param['draw'] : null ;
            $limit = isset($param['length'])? $param['length'] : null;
            $offset = isset($param['start'])?$param['start'] : null;
            $search = isset($param['search']) ? '%'.trim($param['search']) . '%' : '%%';

            $idSetor = isset($param['idSetor']) ? trim($param['idSetor']) : null;

            DBLayer::Connect();
            $query = DBLayer::table(DBLayer::raw(EquipamentoModelo::TABLE_NAME." a, ".EquipamentoTipo::TABLE_NAME." b, func_setores() c"))
                    ->select(DBLayer::raw('a.*, b.nome as nomeEquipTipo, c."idSetor", c.sigla,c.*'))
                    ->from(DBLayer::raw(EquipamentoModelo::TABLE_NAME." a, ".EquipamentoTipo::TABLE_NAME." b, func_setores() c"))
                    ->whereRaw('a."'.EquipamentoModelo::ID_TIPO_EQUIP.'"=b.'.EquipamentoTipo::KEY_ID)
                    ->whereRaw('b."'.EquipamentoTipo::ID_SETOR.'"=c."idSetor"');
                    if(isset($idSetor))
                        $query->whereRaw('b."'.EquipamentoTipo::ID_SETOR.'"='.$idSetor);

            $query->where(function ($query) use ($request, $search)
                {
                    $query->orWhere('a.'.EquipamentoModelo::KEY_ID, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(EquipamentoModelo::ID_TIPO_EQUIP, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere('a.'.EquipamentoModelo::DESCRICAO, DBLayer::OPERATOR_ILIKE, $search);
                    //$query->orWhere(EquipamentoModelo::ATIVO, DBLayer::OPERATOR_ILIKE, $search);
                })
            ;

            $totalRecords = $query->count();
            $data = $query->orderBy(EquipamentoModelo::KEY_ID, 'asc')->take($limit)->offset($offset)->get();
            $result['draw'] = $draw;
            $result['recordsFiltered'] = $totalRecords;
            $result['recordsTotal'] = $totalRecords;
            $result['data'] = $data;
        }
        catch (Exception $e)
        {
            return $response->withStatus(400)->withJson((['message' => '::Ouve um erro desconhecido.', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(200)->withJson($result);
    }

    public static function save(Req $request, Resp $response)
    {

        try
        {
            $id = $request->getAttribute('id');
            $formData = Utils::filterArrayByArray($request->getParsedBody(), EquipamentoModelo::TABLE_FIELDS);

            if ($id)
            {
                DBLayer::Connect()->table(EquipamentoModelo::TABLE_NAME)
                    ->where(EquipamentoModelo::KEY_ID,'=',$id)
                    ->update($formData);
            }
            else
            {
                DBLayer::Connect()->table(EquipamentoModelo::TABLE_NAME)->insertGetId($formData);
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
            $idModelosEquipamento = $request->getAttribute('ModelosEquipamento');
            $modelosEquipamento = DBLayer::Connect()->table(EquipamentoModelo::TABLE_NAME)->where(EquipamentoModelo::KEY_ID, DBLayer::OPERATOR_EQUAL, $idModelosEquipamento)->first();
            return $response->withStatus(200)->withJson($modelosEquipamento);
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
                $modelosEquipamento = DBLayer::Connect()->table(EquipamentoModelo::TABLE_NAME)->where(EquipamentoModelo::KEY_ID, DBLayer::OPERATOR_EQUAL, $id)->first();

                if ($modelosEquipamento)
                {
                    if (DBLayer::Connect()->table(EquipamentoModelo::TABLE_NAME)->delete($id))
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