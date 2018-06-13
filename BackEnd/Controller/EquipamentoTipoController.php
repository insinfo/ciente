<?php
/*********************************************************************************
Nome : contratoController.php
Descricao : Controller para as requisicoes e processamento dos dados dos Contrato
Projeto : CIENTE
Data de Criacao : 02/01/2018
Data das ultimas modificacoes : 17/01/2018, 09/03/2018
Versao : 2.0
Autor : Isaque Neves
Ultimo Modificador : José Amaro da Costa Neto (jcosta@riodasostras.rj.gov.br)
**********************************************************************************/

namespace Ciente\Controller;

use \Slim\Http\Request as Req;
use \Slim\Http\Response as Resp;

use Ciente\Util\DBLayer;

use Ciente\Model\DAL\EquipamentoTipoDAL;
use Ciente\Model\VO\EquipamentoTipo;
use Ciente\Util\Utils;
use Ciente\Model\VO\Token;
use Ciente\Model\VO\SetorHistorico;
use Ciente\Model\VO\SetorUsuario;
use Exception;

class EquipamentoTipoController
{
    /**************** TIPO DE EQUIPAMENTO ******************/
    //lista tipo de equipamento
    public static function getAll(Req $request, Resp $response)
    {
        try
        {
            $token = new Token($request);
            $param = $request->getParsedBody();
            //parametros que o DataTable envia
            $draw = isset($param['draw']) ? $param['draw'] : null ;
            $limit = isset($param['length'])? $param['length'] : null;
            $offset = isset($param['start'])?$param['start'] : null;
            $search = isset($param['search']) ? '%'.trim($param['search']) . '%' : '%%';

            $idPessoa = $token->getIdPessoa();

            $idSetor = isset($param['idSetor']) ? $param['idSetor'] : null;

            /*$query = DBLayer::Connect()
                ->table(DBLayer::connection()->raw("func_setores() a, equipamentos_tipos b, setores_usuarios c  WHERE a.\"idSetor\"=b.\"idSetor\" AND a.\"idSetor\"=c.\"idSetor\" AND c.\"idPessoa\"=$idPessoa order by \"idPai\",sigla"));
            */

            DBLayer::Connect();
            $query = DBLayer::table(DBLayer::raw(SetorHistorico::FUNC_SETOR_NAME.'() a, "'.EquipamentoTipo::TABLE_NAME.'" b, '.SetorUsuario::TABLE_NAME.' c'))
                ->from(DBLayer::raw(SetorHistorico::FUNC_SETOR_NAME.'() a, "'.EquipamentoTipo::TABLE_NAME.'" b, '.SetorUsuario::TABLE_NAME.' c'))
                ->select(DBLayer::raw('a."'.SetorHistorico::NOME.'" as "nomeSetor",a."'.SetorHistorico::SIGLA.'" as "siglaSetor", c."'.SetorUsuario::ID_PESSOA.'",b.*'))
                ->whereRaw('a."'.SetorHistorico::ID_SETOR.'"=b."'.EquipamentoTipo::ID_SETOR.'" ' )
                ->whereRaw('a."'.SetorHistorico::ID_SETOR.'"=c."'.SetorUsuario::ID_SETOR.'"')
                ->whereRaw('c."'.SetorUsuario::ID_PESSOA.'"='.$idPessoa)
                ->groupBy(DBLayer::raw('b.'.EquipamentoTipo::KEY_ID.',a.'.SetorHistorico::NOME.',a.'.SetorHistorico::SIGLA.',c."'.SetorUsuario::ID_PESSOA.'"'))
                ->orderBy('id');

            if($idSetor != null) {
                $query->whereRaw('c."'.SetorUsuario::ID_SETOR.'" = ' . $idSetor);
            }

            if ($search != null) {
                $query->where(function ($query2) use ($request, $search) {
                    $query2->orWhere("b." . EquipamentoTipo::DESCRICAO, DBLayer::OPERATOR_ILIKE, $search);
                    $query2->orWhere("b." . EquipamentoTipo::NOME, DBLayer::OPERATOR_ILIKE, $search);

                });
            }

            $totalRecords = $query->count();

            if ($limit != null) {

                $data = $query->limit($limit)->offset($offset)->get();

            } else {
                $data = $query->get();
            }

            $result['draw'] = $draw;
            $result['recordsFiltered'] = $totalRecords;
            $result['recordsTotal'] = $totalRecords;
            $result['data'] = $data;

        }
        catch (Exception $e)
        {
            return $response->withStatus(400)->withJson((['message' => 'Ouve um erro', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(200)->withJson($result);

    }

    //cria ou atualiza tipo de equipamento
    public static function save(Req $request, Resp $response)
    {
        try
        {
            //$idEquipamentoTipo = $request->getAttribute('idEquipamentoTipo');
            $id = $request->getAttribute('id');
            $formData = Utils::filterArrayByArray($request->getParsedBody(), EquipamentoTipo::TABLE_FIELDS);

            if ($id)
            {
                DBLayer::Connect()->table(EquipamentoTipo::TABLE_NAME)
                    ->where(EquipamentoTipo::KEY_ID,DBLayer::OPERATOR_EQUAL,$id)
                    ->update($formData);
            }
            else
            {
                $idTipoEquip = DBLayer::Connect()->table(EquipamentoTipo::TABLE_NAME)->insertGetId($formData);
                DBLayer::Connect()->table(EquipamentoTipoSetor::TABLE_NAME)->insertGetId(array('idTipoEquipamento'=>$idTipoEquip,'idSetor'=>$form['idSetor']);
            }
        }
        catch (Exception $e)
        {
            return $response->withStatus(400)->withJson((['message' => 'Ouve um erro'.print_r($formData,true), 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(200)->withJson((['message' => 'Salvo com sucesso']));
    }

    //Deleta tipo de equipamento travez de um array de ids
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
                $EquipamentoTipoDAL = new EquipamentoTipoDAL();
                $EquipamentoTipo = $EquipamentoTipoDAL->find($id);
                if ($EquipamentoTipo)
                {
                    if ($EquipamentoTipoDAL->delete($id))
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
            return $response->withStatus(400)->withJson(['message' => 'Houve um erro', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
        }
    }

    //obter tipo de equipamento por id usando DAL
    public static function get(Req $request, Resp $response)
    {
        try
        {
            $idEquipamentoTipo = $request->getAttribute('idEquipamentoTipo');
            $EquipamentoTipoDAL = new EquipamentoTipoDAL();
            $EquipamentoTipo = $EquipamentoTipoDAL->find($idEquipamentoTipo);
            return $response->withStatus(200)->withJson($EquipamentoTipo);
        }
        catch (Exception $e)
        {
            return $response->withStatus(400)->withJson(['message' => 'Houve um erro', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
        }
    }

    //EXEMPLO: obter tipo de equipamento por id sem usar DAL
    public static function get2(Req $request, Resp $response)
    {
        try
        {
            $idEquipamentoTipo = $request->getAttribute('idEquipamentoTipo');
            $EquipamentoTipo = DBLayer::Connect()->table(EquipamentoTipo::TABLE_NAME)->where(EquipamentoTipo::KEY_ID, DBLayer::OPERATOR_EQUAL, $idEquipamentoTipo)->first();
            return $response->withStatus(200)->withJson($EquipamentoTipo);
        }
        catch (Exception $e)
        {
            return $response->withStatus(400)->withJson(['message' => 'Houve um erro', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
        }
    }

    //EXEMPLO: Deleta tipo de equipamento travez de um array de ids sem usar DAL
    public static function delete2(Req $request, Resp $response)
    {
        try
        {
            $formData = $request->getParsedBody();
            $ids = $formData['ids'];
            $idsCount = count($ids);
            $itensDeletadosCount = 0;
            foreach ($ids as $id)
            {
                $EquipamentoTipo = DBLayer::Connect()->table(EquipamentoTipo::TABLE_NAME)->where(EquipamentoTipo::KEY_ID, DBLayer::OPERATOR_EQUAL, $id)->first();

                if ($EquipamentoTipo)
                {
                    if (DBLayer::Connect()->table(EquipamentoTipo::TABLE_NAME)->delete($id))
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
            return $response->withStatus(400)->withJson(['message' => 'Houve um erro', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
        }
    }
}