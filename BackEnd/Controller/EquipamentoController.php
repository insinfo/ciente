<?php

namespace Ciente\Controller;

use \Slim\Http\Request as Req;
use \Slim\Http\Response as Resp;
use \Exception;
use Ciente\Util\DBLayer;
use Ciente\Util\Utils;
use Ciente\Model\VO\Equipamento;
use Ciente\Model\VO\EquipamentoModelo;
use Ciente\Model\VO\Contrato;

class EquipamentoController
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
            //$query = DBLayer::Connect()->table(Equipamento::TABLE_NAME)->where(function ($query) use ($request, $search)

            $query = DBLayer::Connect()->table(Equipamento::TABLE_NAME)
                        ->select(DBLayer::raw('"' . Equipamento::TABLE_NAME . '".*'))

            ;
            $totalRecords = $query->count();

            $data = $query->orderBy(Equipamento::TABLE_NAME.".".Equipamento::KEY_ID, 'asc')->take($limit)->skip($offset)->get();

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
            $idEquipamentos = $request->getAttribute('idEquipamentos');
            $formData = Utils::filterArrayByArray($request->getParsedBody(), Equipamento::TABLE_FIELDS);

            if ($idEquipamentos)
            {
                DBLayer::Connect()->table(Equipamento::TABLE_NAME)->update($formData);
            }
            else
            {
                DBLayer::Connect()->table(Equipamento::TABLE_NAME)->insertGetId($formData);
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
            $idEquipamentos = $request->getAttribute('Equipamentos');
            $equipamentos = DBLayer::Connect()->table(Equipamento::TABLE_NAME)->where(Equipamento::KEY_ID, DBLayer::OPERATOR_EQUAL, $idEquipamentos)->first();
            return $response->withStatus(200)->withJson($equipamentos);
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
                $equipamentos = DBLayer::Connect()->table(Equipamento::TABLE_NAME)->where(Equipamento::KEY_ID, DBLayer::OPERATOR_EQUAL, $id)->first();

                if ($equipamentos)
                {
                    if (DBLayer::Connect()->table(Equipamento::TABLE_NAME)->delete($id))
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

    //retorna os equipamentos do setor do usuario logado
    public static function getEquipamentoUsuario(Req $request, Resp $response)
    {

        try
        {
            $parametros = $request->getParsedBody();
            $draw = $parametros['draw'];
            $limit = $parametros['length'];
            $offset = $parametros['start'];
            $search = '%' . $parametros['search'] . '%';

            //$query = DBLayer::Connect()->table(Equipamento::TABLE_NAME)->where(function ($query) use ($request, $search)
            $query = DBLayer::Connect()->table(Equipamento::TABLE_NAME)
                        ->join(Contrato::TABLE_NAME,Contrato::TABLE_NAME.".id","=","idContrato")
                        ->join(EquipamentoModelo::TABLE_NAME,Equipamento::TABLE_NAME.".".EquipamentoModelo::KEY_ID,"=","idModeloEquipamento")
                        ->join("equipamentos_tipos",EquipamentoModelo::TABLE_NAME.".".EquipamentoModelo::ID_TIPO_EQUIP,"=","equipamentos_tipos.id");

                        if(isset($parametros['idSetor']))
                            $query->where("idSetor",DBLayer::OPERATOR_EQUAL,$parametros['idSetor']);

                $query->where(function ($query) use ($request, $search)
                {

                    $query->orWhere(Equipamento::TABLE_NAME.".".Equipamento::KEY_ID, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Equipamento::ID_MODELO_EQUIPAMENTO, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Equipamento::ID_CONTRATO, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Equipamento::PATRIMONIO_PMRO, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Equipamento::PATRIMONIO_INTERNO, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Equipamento::PATRIMONIO_EMPRESA, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Equipamento::TABLE_NAME.".".Equipamento::OBSERVACAO, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Equipamento::SITUACAO, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Equipamento::STATUS, DBLayer::OPERATOR_ILIKE, $search);
                })
            ;
            $totalRecords = $query->count();
            $data = $query->orderBy(Equipamento::TABLE_NAME.".".Equipamento::KEY_ID, 'asc')->take($limit)->offset($offset)->get();
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

}