<?php
/*********************************************************************************
 * Nome : ServicoTipoController.php
 * Descricao : Controller para as requisicoes e processamento dos dados dos Tipos de Servicos
 * Projeto : CIENTE
 * Data de Criacao : 15/01/2018
 * Data das ultimas modificacoes : 09/03
 * Versao : 2.0
 * Autor : José Amaro da Costa Neto (jcosta@riodasostras.rj.gov.br)
 * Ultimo Modificador : José Amaro da Costa Neto (jcosta@riodasostras.rj.gov.br)
 **********************************************************************************/

namespace Ciente\Controller;

use \Slim\Http\Request as Req;
use \Slim\Http\Response as Resp;
use Ciente\Util\DBLayer;
use Ciente\Model\DAL\ServicoTipoDAL;
use Ciente\Model\VO\ServicoTipo;
use Ciente\Model\VO\SetorHistorico;
use Ciente\Model\VO\SetorUsuario;
use Ciente\Util\Utils;
use Exception;
use Ciente\Util\StatusCode;
use Ciente\Util\StatusMessage;
use Ciente\Model\VO\Token;

class ServicoTipoController
{
    /**************** TIPO DE Servico ******************/
    // lista todos os tipos de serviço existentes
    public static function getAll(Req $request, Resp $response) {
        try {
            $param = $request->getParsedBody();
            //parametros que o DataTable envia
            $draw = isset($param['draw']) ? $param['draw'] : null;
            $limit = isset($param['length']) ? $param['length'] : null;
            $offset = isset($param['start']) ? $param['start'] : null;
            $search =  isset($param['search']) ? '%' . trim($param['search']) . '%' : null;
            $idSetor = isset($param['idSetor']) ? $param['idSetor'] : null;

            /*
            SELECT fs.sigla as "siglaSetor", fs.nome as "nomeSetor", st.*
            FROM servicos_tipos st, func_setores() fs
            WHERE st."idSetor" = fs."idSetor"
                AND st."idSetor" = 4
            */

            DBLayer::Connect();
            $query = DBLayer::table('servicos_tipos st, func_setores() fs')
                ->from(DBLayer::raw('servicos_tipos st, func_setores() fs'))
                ->select(DBLayer::raw('fs.sigla as "siglaSetor", fs.nome as "nomeSetor", st.*'))
                ->whereRaw('st."idSetor" = fs."idSetor"');
            if ($idSetor != null) {
                $query->whereRaw('st."idSetor" = ' . $idSetor);
            }

            if ($search != null) {
                $query->where(function ($query2) use ($request, $search) {
                    $query2->orWhere("st." . ServicoTipo::DESCRICAO, DBLayer::OPERATOR_ILIKE, $search);
                    $query2->orWhere("st." . ServicoTipo::NOME, DBLayer::OPERATOR_ILIKE, $search);
                    $query2->orWhere("fs.sigla" , DBLayer::OPERATOR_ILIKE, $search);
                    $query2->orWhere("fs.nome" , DBLayer::OPERATOR_ILIKE, $search);
                });
            }

            $totalRecords = $query->count();
            $query->orderBy(DBLayer::raw('st.'.ServicoTipo::NOME));

            if ($limit != null) {
                $data = $query->limit($limit)->offset($offset)->get();
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

    //lista tipo de Servico associados ao usuário/setor
    public static function getAllOfUser(Req $request, Resp $response)
    {
        try {
            $token = new Token($request);
            $param = $request->getParsedBody();
            //parametros que o DataTable envia
            $draw = isset($param['draw']) ? $param['draw'] : null;
            $limit = isset($param['length']) ? $param['length'] : null;
            $offset = isset($param['start']) ? $param['start'] : null;
            $search =  isset($param['search']) ? '%' . trim($param['search']) . '%' : null;
            $idSetor = isset($param['idSetor']) ? $param['idSetor'] : null;
            $ordering = isset($param['ordering']) ?  $param['ordering'] : null;
            /*
            SELECT fs.sigla as "siglaSetor", fs.nome as "nomeSetor", st.*
            FROM servicos_tipos st, setores_usuarios su, func_setores() fs
            WHERE st."idSetor" = su."idSetor"
              AND st."idSetor" = fs."idSetor"
              AND su."idPessoa" = 1
            */

            DBLayer::Connect();
            $query = DBLayer::table('servicos_tipos st, setores_usuarios su, func_setores() fs')
                ->from(DBLayer::raw('servicos_tipos st, setores_usuarios su, func_setores() fs'))
                ->select(DBLayer::raw('fs.sigla as "siglaSetor", fs.nome as "nomeSetor", st.*'))
                ->whereRaw(DBLayer::raw('st."idSetor"= su."idSetor"'))
                ->whereRaw(DBLayer::raw('st."idSetor"= fs."idSetor"'))
                ->whereRaw(DBLayer::raw('su."idPessoa"='.$token->getIdPessoa()));

            if($idSetor != null) {
                $query->whereRaw('su."idSetor" = ' . $idSetor);
            }

            if ($search != null) {
                $query->where(function ($query2) use ($request, $search) {
                    $query2->orWhere("st." . ServicoTipo::DESCRICAO, 'ilike', $search);
                    $query2->orWhere("st." . ServicoTipo::NOME, 'ilike', $search);
                    $query2->orWhere("fs.sigla" , 'ilike', $search);
                    $query2->orWhere("fs.nome" , 'ilike', $search);
                });
            }

            $query->whereRaw('su."'.SetorUsuario::ID_PESSOA.'" = ' . $token->getIdPessoa());

            $totalRecords = $query->count();

            //implementação da ordenação do ModernDataTable
            if($ordering != null && count($ordering) > 0){
                foreach ($ordering as $item){
                    $query->orderBy($item['columnKey'],$item['direction']);
                }
            }else{
                $query->orderBy(DBLayer::raw('st.'.ServicoTipo::NOME));
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

        } catch (Exception $e) {
            return $response->withStatus(StatusCode::BAD_REQUEST)
                ->withJson((['message' => StatusMessage::MENSAGEM_ERRO_PADRAO, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(StatusCode::SUCCESS)->withJson($result);
    }

    //cria ou atualiza tipo de equipamento
    public static function save(Req $request, Resp $response)
    {
        try {
            $id = $request->getAttribute('id'); //id do contrato
            $formData = Utils::filterArrayByArray($request->getParsedBody(), ServicoTipo::TABLE_FIELDS);

            if ($id) {
                DBLayer::Connect()->table(ServicoTipo::TABLE_NAME)->where(ServicoTipo::KEY_ID, "=", $id)->update($formData);
            } else {
                DBLayer::Connect()->table(ServicoTipo::TABLE_NAME)->insertGetId($formData);
            }

        } catch (Exception $e) {
            return $response->withStatus(StatusCode::BAD_REQUEST)->withJson((['message' => StatusMessage::MENSAGEM_ERRO_PADRAO, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(StatusCode::SUCCESS)->withJson($request->getParsedBody());
    }

    //Deleta tipo de equipamento atravez de um array de ids
    public static function delete(Req $request, Resp $response)
    {
        try {
            $formData = $request->getParsedBody();
            $ids = $formData['ids'];
            $idsCount = count($ids);
            $itensDeletadosCount = 0;
            foreach ($ids as $id) {
                $tipo_servicoDAL = new ServicoTipoDAL();
                if ($tipo_servicoDAL->find($id)) {
                    if ($tipo_servicoDAL->delete($id)) {
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

    //obter tipo de equipamento por id usando DAL
    public static function get(Req $request, Resp $response)
    {
        try {
            $idServicoTipo = $request->getAttribute('id');
            $ServicoTipoDAL = new ServicoTipoDAL();
            $ServicoTipo = $ServicoTipoDAL->find($idServicoTipo);
            return $response->withStatus(StatusCode::SUCCESS)->withJson($ServicoTipo);
        } catch (Exception $e) {
            return $response->withStatus(StatusCode::BAD_REQUEST)->withJson(['message' => StatusMessage::MENSAGEM_ERRO_PADRAO, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
        }
    }

    public static function getAutoCompleteSetores(Req $request, Resp $response)
    {
        try {
            $parametros = $request->getParsedBody();
            //parametros que o DataTable envia
            $draw = $parametros['draw'];
            $limit = $parametros['length'];
            $offset = $parametros['start'];
            $search = '%' . $parametros['search'] . '%';

            $query = DBLayer::Connect()->table("setores_historico")
                ->selectRaw('DISTINCT ON ("idSetor") *')
                //->from("historico_setores")
                //->join('historico_setores','historico_setores.id','=',ServicoTipo::ID_SETOR)
                ->where(function ($query) use ($request, $search) //->whereRaw("nome ilike (%$search%) and descricao ilike()")
                {
                    $query->orWhere('nome', DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere('sigla', DBLayer::OPERATOR_ILIKE, $search);
                })
                ->groupBy('idSetor', 'dataInicio')
                //->where("ativo",DBLayer::OPERATOR_EQUAL,1)
                ->orderBy("idSetor", DBLayer::ORDER_DIRE_ASC)
                ->orderBy("dataInicio", DBLayer::ORDER_DIRE_DESC);

            $totalRecords = $query->count();
            $data = $query->orderBy("nome", DBLayer::ORDER_DIRE_ASC)->take($limit)->skip($offset)->get();

            /*
            //tranz uma relação com pessoa juridica
            $indice = 0;
            foreach($data as $item){
                $data[$indice]['pessoaJuridica'] = DBLayer::Connect()
                    ->table('pessoas')->where("id",'=',$item['idPessoaJuridica'])
                    ->first();
                $indice++;
            }
            */
            $result['draw'] = $draw;
            $result['recordsFiltered'] = $totalRecords;
            $result['recordsTotal'] = $totalRecords;
            $result['data'] = $data;
        } catch (Exception $e) {
            return $response->withStatus(StatusCode::BAD_REQUEST)->withJson((['message' => StatusMessage::MENSAGEM_ERRO_PADRAO, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(StatusCode::SUCCESS)->withJson($result);

    }

}