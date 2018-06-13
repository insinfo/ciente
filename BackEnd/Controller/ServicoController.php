<?php

namespace Ciente\Controller;

use \Slim\Http\Request as Req;
use \Slim\Http\Response as Resp;
use \Exception;
use Ciente\Util\DBLayer;
use Ciente\Util\Utils;
use Ciente\Model\VO\Servico;
use Ciente\Model\VO\ServicoTipo;
use Ciente\Model\VO\Contrato;
use Ciente\Model\VO\SetorUsuario;
use Ciente\Util\StatusCode;
use Ciente\Util\StatusMessage;
use Ciente\Model\VO\Token;

class ServicoController
{
    public static function getAll(Req $request, Resp $response)
    {
        try {

            $token = new Token($request);
            $param = $request->getParsedBody();
            //parametros que o DataTable envia
            $draw = isset($param['draw']) ? $param['draw'] : null;
            $limit = isset($param['length']) ? $param['length'] : null;
            $offset = isset($param['start']) ? $param['start'] : null;
            $search = isset($param['search']) ? '%' . trim($param['search']) . '%' : null;
            $ordering = isset($param['ordering']) ? $param['ordering'] : null;
            //parametros extras
            $ativo = isset($param['ativo']) ? $param['ativo'] : null;
            $idSetor = isset($param['idSetor']) ? $param['idSetor'] : null;
            $idTipoServico = isset($param['idTipoServico']) ? $param['idTipoServico'] : null;

            DBLayer::Connect();
            $query = DBLayer::table(DBLayer::raw(Servico::TABLE_NAME.' s'))
                ->from(DBLayer::raw(Servico::TABLE_NAME.' s'))
                ->select(DBLayer::raw('s.*, c."'.Contrato::NUMERO.'", c."'.Contrato::ANO.'"' . ', st."' . ServicoTipo::NOME . '" as "servicoTipoNome" '))
                ->leftJoin(DBLayer::raw(Contrato::TABLE_NAME.' c'),DBLayer::raw('s."'.Servico::ID_CONTRATO.'"'),'=',DBLayer::raw('c."'.Contrato::KEY_ID.'"'))
                ->join(DBLayer::raw(ServicoTipo::TABLE_NAME.' st'),DBLayer::raw('s."idTipoServico"'),'=',DBLayer::raw('st."'.ServicoTipo::KEY_ID.'"'));


            if($idTipoServico){
                $query->whereRaw('s."'.Servico::ID_TIPO_SERVICO.'"='.$idTipoServico);
            }

            if ($search != null) {
                $query->where(function ($query2) use ($request, $search) {
                    $query2->orWhere('s.' . Servico::NOME, DBLayer::OPERATOR_ILIKE, $search);
                    $query2->orWhere('s.' . Servico::DESCRICAO, DBLayer::OPERATOR_ILIKE, $search);
                });
            }

            if ($ativo) {
                $query->where(Servico::TABLE_NAME . '.' . Servico::ATIVO, '=', $ativo);
            }
            //$result['sql']= $query->toSql();

            $totalRecords = $query->count();

            //implementação da ordenação do ModernDataTable
            if($ordering != null && count($ordering) > 0){
                foreach ($ordering as $item){
                    $query->orderBy($item['columnKey'],$item['direction']);
                }
            }else{
                $query->orderBy(DBLayer::raw('st.'. Servico::NOME));
                $query->orderBy(DBLayer::raw('s.'.Servico::NOME));
            }

            if ($limit != null) {

                $data = $query->limit($limit)->offset($offset)->get();

            } else {
                $data = $query->get();
            }

            DBLayer::getRelation($data, ServicoTipo::TABLE_NAME, ServicoTipo::KEY_ID, Servico::ID_TIPO_SERVICO, 'tipoServico');
            DBLayer::getRelation($data, Contrato::TABLE_NAME, Contrato::KEY_ID, Servico::ID_CONTRATO, 'contrato');

            $result['draw'] = $draw;
            $result['recordsFiltered'] = $totalRecords;
            $result['recordsTotal'] = $totalRecords;
            $result['data'] = $data;


        } catch (Exception $e) {
            return $response->withStatus(StatusCode::BAD_REQUEST)->withJson((['message' => StatusMessage::MENSAGEM_ERRO_PADRAO, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(StatusCode::SUCCESS)->withJson($result);
    }

    public static function getAllOfUser(Req $request, Resp $response)
    {
        try {

            $token = new Token($request);
            $param = $request->getParsedBody();
            //parametros que o DataTable envia
            $draw = isset($param['draw']) ? $param['draw'] : null;
            $limit = isset($param['length']) ? $param['length'] : null;
            $offset = isset($param['start']) ? $param['start'] : null;
            $search = isset($param['search']) ? '%' . trim($param['search']) . '%' : null;
            $ordering = isset($param['ordering']) ?  $param['ordering'] : null;

            //parametros extras
            $ativo = isset($param['ativo']) ? $param['ativo'] : null;
            $idSetor = isset($param['idSetor']) ? $param['idSetor'] : null;
            $idTipoServico = isset($param['idTipoServico']) ? $param['idTipoServico'] : null;

            DBLayer::Connect();
            $query = DBLayer::table(DBLayer::raw(Servico::TABLE_NAME.' s'))
                ->from(DBLayer::raw(Servico::TABLE_NAME.' s'))
                ->select(DBLayer::raw('s.*, c."'.Contrato::NUMERO.'", c."'.Contrato::ANO.'"' . ', st."' . ServicoTipo::NOME . '" as "servicoTipoNome" '))
                ->leftJoin(DBLayer::raw(Contrato::TABLE_NAME.' c'),DBLayer::raw('s."'.Servico::ID_CONTRATO.'"'),'=',DBLayer::raw('c."'.Contrato::KEY_ID.'"'))
                ->join(DBLayer::raw(ServicoTipo::TABLE_NAME.' st'),DBLayer::raw('s."idTipoServico"'),'=',DBLayer::raw('st."'.ServicoTipo::KEY_ID.'"'))
                ->join(DBLayer::raw(SetorUsuario::TABLE_NAME.' su'),DBLayer::raw('st."'.ServicoTipo::ID_SETOR.'"'),'=',DBLayer::raw('su."'.SetorUsuario::ID_SETOR.'"'))
                ->whereRaw('su."'.SetorUsuario::ID_PESSOA.'" = ' . $token->getIdPessoa());

            //filtra pelo setor do usuario
            if ($idSetor) {
                $query->whereRaw('su."'.SetorUsuario::ID_SETOR.'"='.$idSetor);
            }

            if($idTipoServico != null){
                $query->whereRaw('s."'.Servico::ID_TIPO_SERVICO.'"='.$idTipoServico);
            }

            if ($search != null) {
                $query->where(function ($query2) use ($request, $search) {
                    $query2->orWhere('s.' . Servico::NOME, DBLayer::OPERATOR_ILIKE, $search);
                    $query2->orWhere('s.' . Servico::DESCRICAO, DBLayer::OPERATOR_ILIKE, $search);
                });
            }

            if ($ativo != null) {
                $query->where(Servico::TABLE_NAME . '.' . Servico::ATIVO, '=', $ativo);
            }
            //$result['sql']= $query->toSql();

            $totalRecords = $query->count();

            //implementação da ordenação do ModernDataTable
            if($ordering != null && count($ordering) > 0){
                foreach ($ordering as $item){
                    $query->orderBy($item['columnKey'],$item['direction']);
                }
            }else{
                $query->orderBy(DBLayer::raw('st.'. Servico::NOME));
                $query->orderBy(DBLayer::raw('s.'.Servico::NOME));
            }

            if ($limit != null && $offset != null) {

                $data = $query->limit($limit)->offset($offset)->get();

            } else {
                $data = $query->get();
            }

            //DBLayer::getRelation($data, ServicoTipo::TABLE_NAME, ServicoTipo::KEY_ID, Servico::ID_TIPO_SERVICO, 'tipoServico');
            //DBLayer::getRelation($data, Contrato::TABLE_NAME, Contrato::KEY_ID, Servico::ID_CONTRATO, 'contrato');

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

    public static function save(Req $request, Resp $response)
    {
        try {
            $id = $request->getAttribute('id');
            $formData = Utils::filterArrayByArray($request->getParsedBody(), Servico::TABLE_FIELDS);

            if ($id) {
                DBLayer::Connect()->table(Servico::TABLE_NAME)->where(Servico::KEY_ID, '=', $id)->update($formData);
            } else {
                DBLayer::Connect()->table(Servico::TABLE_NAME)->insert($formData);
            }
        } catch (Exception $e) {
            return $response->withStatus(StatusCode::BAD_REQUEST)->withJson((['message' => StatusMessage::MENSAGEM_ERRO_PADRAO, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(StatusCode::SUCCESS)->withJson((['message' => StatusMessage::MENSAGEM_DE_SUCESSO_PADRAO]));
    }

    public static function get(Req $request, Resp $response)
    {
        try {
            $id = $request->getAttribute('id');
            $servicos = DBLayer::Connect()->table(Servico::TABLE_NAME)->where(Servico::KEY_ID, DBLayer::OPERATOR_EQUAL, $id)->first();
            return $response->withStatus(StatusCode::SUCCESS)->withJson($servicos);
        } catch (Exception $e) {
            return $response->withStatus(StatusCode::BAD_REQUEST)->withJson(['message' => StatusMessage::MENSAGEM_ERRO_PADRAO, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
        }
    }

    public static function delete(Req $request, Resp $response)
    {
        try {
            $formData = $request->getParsedBody();
            $ids = $formData['ids'];
            $idsCount = count($ids);
            $itensDeletadosCount = 0;
            foreach ($ids as $id) {
                $servicos = DBLayer::Connect()->table(Servico::TABLE_NAME)->where(Servico::KEY_ID, DBLayer::OPERATOR_EQUAL, $id)->first();

                if ($servicos) {
                    if (DBLayer::Connect()->table(Servico::TABLE_NAME)->delete($id)) {
                        $itensDeletadosCount++;
                    }
                }
            }
            if ($itensDeletadosCount == $idsCount) {
                return $response->withStatus(StatusCode::SUCCESS)->withJson(['message' => StatusMessage::TODOS_ITENS_DELETADOS]);
            } else if ($itensDeletadosCount > 0) {
                return $response->withStatus(StatusCode::SUCCESS)->withJson(['message' => StatusMessage::NEM_TODOS_ITENS_DELETADOS]);
            } else {
                return $response->withStatus(StatusCode::SUCCESS)->withJson((['message' => StatusMessage::NENHUM_ITEM_DELETADO]));
            }
        } catch (Exception $e) {
            return $response->withStatus(StatusCode::BAD_REQUEST)->withJson(['message' => StatusMessage::MENSAGEM_ERRO_PADRAO, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
        }
    }

}