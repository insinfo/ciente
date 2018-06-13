<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 23/02/2018
 * Time: 14:58
 */

namespace Ciente\Controller;


use \Slim\Http\Request;
use \Slim\Http\Response;
use \Exception;
use Ciente\Util\DBLayer;
use Ciente\Util\Utils;
use Ciente\Util\StatusMessage;
use Ciente\Util\StatusCode;
use Ciente\Model\VO\SetorHistorico;
use Ciente\Model\VO\Setor;
use Ciente\Model\VO\Token;
use Ciente\Model\VO\SetorUsuario;

use Ciente\Model\DAL\SetorDAL;


class SetorController
{

    public static function getAll(Request $request, Response $response)
    {
        try {
            //evita exiber noticia Notice: Undefined offset
            error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT ^ E_DEPRECATED);
            $parametros = $request->getParsedBody();
            $draw = $parametros['draw'];
            $limit = $parametros['length'];
            $offset = $parametros['start'];
            $search = '%' . $parametros['search'] . '%';
            $idSetor = $parametros['idSetor'];

            $orderby = isset($parametros['ordering']) ? $parametros['ordering'] : null;


            DBLayer::Connect();
            $query = DBLayer::table('"' . SetorHistorico::TABLE_NAME . '" sh')
                ->from(DBLayer::raw('"' . SetorHistorico::TABLE_NAME . '" sh'))
                ->select(DBLayer::raw(' sh.*, s."' . SetorHistorico::SIGLA . '" as "siglaPai", s."' . SetorHistorico::NOME . '" as "nomePai"'))
                ->leftjoin(DBLayer::raw('func_setores(sh."' . SetorHistorico::DATA_INICIO . '") s'), DBLayer::raw('sh."' . SetorHistorico::ID_PAI . '"'), '=', DBLayer::raw('s."' . SetorHistorico::ID_SETOR . '"'));

            //filtra por setor
            if ($idSetor) {
                $query->where('sh.' . SetorHistorico::ID_SETOR, '=', $idSetor);
            }

            $totalRecords = $query->count();

            //ordenacao
            if($orderby and count($orderby))
            foreach($orderby as $order)
            {
                $query->orderBy($order['columnKey'],$order['direction']);
            }
            else
                $query->orderBy('sh.' . SetorHistorico::DATA_INICIO, 'DESC');


            if ($limit) {
                //$data = $query->orderBy('sh.' . SetorHistorico::DATA_INICIO, 'DESC')->take($limit)->offset($offset)->get();
                $data = $query->take($limit)->offset($offset)->get();
            } else {
                $data = $query->orderBy('sh.' . SetorHistorico::DATA_INICIO, 'DESC')->get();
            }

            $result['draw'] = $draw;
            $result['recordsFiltered'] = $totalRecords;
            $result['recordsTotal'] = $totalRecords;
            $result['data'] = $data;

        } catch (Exception $e) {
            return $response->withStatus(StatusCode::BAD_REQUEST)
                ->withJson((['message' => StatusMessage::MENSAGEM_ERRO_PADRAO, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(StatusCode::SUCCESS)->withJson($result);
    }

    //Listar toda hierarquia por Data
    public static function getHierarquia(Request $request, Response $response)
    {
        try {
            $parametros = $request->getParsedBody();
            $dataInicio = isset($parametros['dataInicio']) ? $parametros['dataInicio'] : null;
            $idPai = isset($parametros['idPai']) ? $parametros['dataInicio'] : null;
            $ativo = isset($parametros['ativo']) ? $parametros['ativo'] : null;

            $setorDAL = new SetorDAL();
            $result = $setorDAL->getHierarquia($idPai, $dataInicio, $ativo);
        } catch (Exception $e) {
            return $response->withStatus(StatusCode::BAD_REQUEST)->withJson((['message' => StatusMessage::MENSAGEM_ERRO_PADRAO, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(StatusCode::SUCCESS)->withJson($result);

    }

    public static function save(Request $request, Response $response)
    {
        /*
         {
            "setor": {
                "idPai":1,
                "ativo": true
            },
            "setorHistorico":{
                "dataInicio":"2018-02-02",
                "sigla":"SEMAD",
                "nome":"Secretaria de Administração Pública"
            }
        }
          */
        try {
            $idSetor = $request->getAttribute('id');
            $dataInicio = $request->getAttribute('dataInicio');
            $formData = $request->getParsedBody();
            $setorData = $formData['setor'];
            $historicoData = $formData['setorHistorico'];
            $historicoData['dataInicio'] = Utils::isNullOrEmptyString($historicoData['dataInicio']) ? '01/01/1900' : $historicoData['dataInicio'];

            if ($idSetor != null && $dataInicio != null) {

                DBLayer::Connect()->table(Setor::TABLE_NAME)
                    ->where(Setor::KEY_ID, '=', $idSetor)
                    ->update($setorData);

                DBLayer::Connect()->table(SetorHistorico::TABLE_NAME)
                    ->where(SetorHistorico::ID_SETOR, '=', $idSetor)
                    ->where(SetorHistorico::DATA_INICIO, '=', $dataInicio)
                    ->update($historicoData);

            }
            else if ($idSetor != null && $dataInicio == null)
            {
                $historicoData[SetorHistorico::ID_SETOR] = $idSetor;
                DBLayer::Connect()->table(SetorHistorico::TABLE_NAME)
                    ->insert($historicoData);
            }
            else
            {
                $idSetor = DBLayer::Connect()->table(Setor::TABLE_NAME)
                    ->insertGetId($setorData);

                $historicoData[SetorHistorico::ID_SETOR] = $idSetor;
                DBLayer::Connect()->table(SetorHistorico::TABLE_NAME)
                    ->insert($historicoData);
            }

        } catch (Exception $e) {
            return $response->withStatus(StatusCode::BAD_REQUEST)
                ->withJson((['message' => StatusMessage::MENSAGEM_ERRO_PADRAO, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(StatusCode::SUCCESS)
            ->withJson(['message' => StatusMessage::MENSAGEM_DE_SUCESSO_PADRAO]);
    }

    public static function deleteHistorico(Request $request, Response $response)
    {
        try {

            $formData = $request->getParsedBody();

            foreach ($formData as $item) {
                $idSetor = $item['idSetor'];
                $dataInicio = $item['dataInicio'];

                DBLayer::Connect()->table(SetorHistorico::TABLE_NAME)
                    ->where(SetorHistorico::KEY_ID, '=', $idSetor)
                    ->where(SetorHistorico::DATA_INICIO, '=', $dataInicio)
                    ->delete();
            }


        } catch (Exception $e) {
            return $response->withStatus(StatusCode::BAD_REQUEST)
                ->withJson((['message' => StatusMessage::MENSAGEM_ERRO_PADRAO, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(StatusCode::SUCCESS)
            ->withJson(['message' => StatusMessage::MENSAGEM_DE_SUCESSO_PADRAO]);
    }

    public static function deleteSetor(Request $request, Response $response)
    {
        try {

            $idSetor = $request->getAttribute('id');

            DBLayer::Connect()->table(Setor::TABLE_NAME)
                ->where(Setor::KEY_ID, '=', $idSetor)
                ->delete();


        } catch (Exception $e) {
            return $response->withStatus(StatusCode::BAD_REQUEST)
                ->withJson((['message' => StatusMessage::MENSAGEM_ERRO_PADRAO, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(StatusCode::SUCCESS)
            ->withJson(['message' => StatusMessage::MENSAGEM_DE_SUCESSO_PADRAO]);
    }

    //lista setores do usuario
    public static function getOfUser(Request $request, Response $response)
    {
        try {
            $token = new Token($request);
            $param = $request->getParsedBody();
            //parametros que o DataTable envia
            $draw = isset($param['draw']) ? $param['draw'] : null;
            $limit = isset($param['length']) ? $param['length'] : null;
            $offset = isset($param['start']) ? $param['start'] : null;
            $search = isset($param['search']) ? '%' . trim($param['search']) . '%' : null;
            //pega o idPessoa do token
            $idPessoa = $token->getIdPessoa();


            DBLayer::Connect();
            $query = DBLayer::table(DBLayer::raw(SetorHistorico::FUNC_SETOR_NAME . '() a, ' . SetorUsuario::TABLE_NAME . ' b'))
                ->from(DBLayer::raw(SetorHistorico::FUNC_SETOR_NAME . '() a, ' . SetorUsuario::TABLE_NAME . ' b'))
                ->select(DBLayer::raw('a.*'))
                ->whereRaw('a."' . SetorHistorico::ID_SETOR . '"=b."' . SetorUsuario::ID_SETOR . '" ')
                ->whereRaw('b."' . SetorUsuario::ID_PESSOA . '"=' . $idPessoa)
                ->whereRaw('ativo=true');
            /*->groupBy(DBLayer::raw(''))*/
            //->orderBy('id');

            if ($search != null) {
                $query->where(function ($query2) use ($request, $search) {
                    $query2->orWhere(SetorHistorico::TABLE_NAME . "." . SetorHistorico::SIGLA, DBLayer::OPERATOR_ILIKE, $search);
                    $query2->orWhere(SetorHistorico::TABLE_NAME . "." . SetorHistorico::NOME, DBLayer::OPERATOR_ILIKE, $search);

                });
            }

            $totalRecords = $query->count();

            if ($limit != null) {
                $data = $query->take($limit)->skip($offset)->get();
            } else {

                $data = $query->get();
            }

            $result['draw'] = $draw;
            $result['recordsFiltered'] = $totalRecords;
            $result['recordsTotal'] = $totalRecords;
            $result['data'] = $data;

        } catch (Exception $e) {
            return $response->withStatus(StatusCode::BAD_REQUEST)->withJson((['message' => StatusMessage::MENSAGEM_ERRO_PADRAO, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(StatusCode::SUCCESS)->withJson($result);

    }

    //retorna a quantidade de usuarios que estão acociados a um setor
    public static function getUserCount(Request $request, Response $response)
    {
        try {
            $token = new Token($request);
            $param = $request->getParsedBody();
            //parametros que o DataTable envia
            $idSetor = isset($param['idSetor']) ? $param['idSetor'] : null;

            if ($idSetor != null) {
                DBLayer::Connect();
                $query = DBLayer::table(SetorUsuario::TABLE_NAME)
                    ->from(SetorUsuario::TABLE_NAME)
                    ->select('*')
                    ->where(SetorUsuario::ID_SETOR, '=', $idSetor);

                $result['total'] = $query->count();
            }else{
                throw new Exception(StatusMessage::REQUIRED_PARAMETERS);
            }

            return $response->withStatus(StatusCode::SUCCESS)->withJson($result);

        } catch (Exception $e)
        {
            return $response->withStatus(StatusCode::BAD_REQUEST)->withJson((['message' => StatusMessage::MENSAGEM_ERRO_PADRAO, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
    }
}