<?php

namespace Ciente\Controller;

use Ciente\Model\DAL\SetorDAL;
use Ciente\Model\DAL\SetorHistoricoDAL;
use function GuzzleHttp\Psr7\str;
use Illuminate\Support\Facades\DB;
use \Slim\Http\Request as Req;
use \Slim\Http\Response as Resp;
use \Exception;
use Ciente\Util\DBLayer;
use Ciente\Util\Utils;
use Ciente\Model\VO\Solicitacao;
use Ciente\Model\VO\Atendimento;
use Ciente\Util\StatusCode;
use Ciente\Util\StatusMessage;

class SolicitacaoController
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
            $query = DBLayer::Connect()->table(Solicitacao::TABLE_NAME)->where(function ($query) use ($request, $search)
                {
                    $query->orWhere(Solicitacao::KEY_ID, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Solicitacao::ANO, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Solicitacao::NUMERO, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Solicitacao::ID_SETOR, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Solicitacao::ID_SOLICITANTE, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Solicitacao::ID_SOLICITANTESEC, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Solicitacao::ID_EQUIPAMENTO, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Solicitacao::ID_USUARIO, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Solicitacao::DESCRICAO, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Solicitacao::OBSERVACAO, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Solicitacao::DATA_ABERTURA, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Solicitacao::DATA_FECHAMENTO, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Solicitacao::PRIORIDADE, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Solicitacao::STATUS, DBLayer::OPERATOR_ILIKE, $search);
                })
            ;
            $totalRecords = $query->count();
            $data = $query->orderBy(Solicitacao::KEY_ID, 'asc')->take($limit)->offset($offset)->get();
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

    public static function save(Req $request, Resp $response){
        try {

            $mensagem = null;

            DBLayer::connect();

            $solicitacao_id = 0;
            $atendimento_id = 0;

            DBLayer::transaction(function () use ($request, $response, &$mensagem, &$solicitacao_id, &$atendimento_id) {

                $idSolicitacoes = $request->getAttribute('id');
                $formDataSolicitacao = Utils::filterArrayByArray(
                    $request->getParsedBody(),
                    Solicitacao::TABLE_FIELDS);

                $formDataSolicitacao[Solicitacao::ID_EQUIPAMENTO] = null;

                $timeNow = explode('.',number_format(microtime(true),4, '.', ''));

                $dateNow = date('Y-m-d H:i:s', $timeNow[0]);

                $formDataAtendimento = Utils::filterArrayByArray(
                    $request->getParsedBody(),
                    Atendimento::TABLE_FIELDS
                );

                // Adicionar data de encaminhamento
                $formDataAtendimento[Atendimento::DATA_ENCAMINHAMENTO] = $dateNow;
                $formDataAtendimento[Atendimento::STATUS] = 0;
                $formDataSolicitacao[Solicitacao::DATA_ABERTURA] = $dateNow;

                $formDataSolicitacao[Solicitacao::ANO] = date('Y', $timeNow[0]);
                $formDataSolicitacao[Solicitacao::NUMERO] = date('mdhis', $timeNow[0]) .''. $timeNow[1];


                if ($idSolicitacoes) {
                    DBLayer::table(Solicitacao::TABLE_NAME)
                        ->where(Solicitacao::KEY_ID, $idSolicitacoes)
                        ->update($formDataSolicitacao);
                }
                else {
                    $solicitacao_id = DBLayer::table(Solicitacao::TABLE_NAME)
                        ->insertGetId($formDataSolicitacao);

                    if ($solicitacao_id) {
                        $formDataAtendimento[Atendimento::ID_SOLICITACAO] = $solicitacao_id;
                        $atendimento_id = DBLayer::table(Atendimento::TABLE_NAME)
                            ->insertGetId($formDataAtendimento);
                    }
                }

                $numero_solicitcao = "#" . $formDataSolicitacao[Solicitacao::ANO] . "." . $formDataSolicitacao[Solicitacao::NUMERO];

                $setor = SetorHistoricoDAL::getInstance()->findOne($formDataAtendimento[Atendimento::ID_SETOR]);

                $mensagem = "Foi criada uma nova solicitação com o número <strong> $numero_solicitcao </strong> para o setor <strong>" . $setor->getNome() . "</strong>";

            });

            return $response->withStatus(StatusCode::SUCCESS)
                ->withJson([
                    'message' => $mensagem,
                    'solicitacaoId' => $solicitacao_id,
                    'atendimentoId' => $atendimento_id
                ]);

        }
        catch (Exception $e) {
            return $response->withStatus(StatusCode::BAD_REQUEST)
                ->withJson(['message' => StatusMessage::MENSAGEM_ERRO_PADRAO,
                    'exception' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile()]);
        }

    }

    public static function getSolicitacoesPor($request, $response, array $args) {
        $field_value_id = $args['id'];
        $field_id_name = $args['field-name'];

        if (!$field_value_id ||
            !$field_id_name ||
            array_search($field_id_name, Solicitacao::TABLE_FILTER) === false
        ) {
            return $response -> withStatus(StatusCode::BAD_REQUEST)
                ->withJson([
                    'message' => StatusMessage::MENSAGEM_ERRO_PADRAO
                ]);
        }

        try {
            DBLayer::Connect();

            $param = $request->getParams();

            $limit = isset($param['length'])? $param['length'] : 10;
            $offset = isset($param['start'])?$param['start'] : 0;
            $search = isset($param['search']) ? '%'.trim($param['search']) . '%' : '';
            $order = isset($param['order'])? explode('|', $param['order']): ['id', 'desc'];
            $fields_filter = isset($param['fields_filter'])? $param['fields_filter']: null;
            $fields = isset($param['fields'])? $param['fields']: '*';

            $query = DBLayer::table(Solicitacao::TABLE_NAME);

            if ($field_id_name == "idServico") {
                // une com table atendimento
                if (is_string($fields)) {
                    $query -> select(DBLayer::raw(Solicitacao::TABLE_NAME . ".*"));
                } else {
                    $fields_str = [];
                    foreach ($fields as $f) {
                        $fields_str[] = Solicitacao::TABLE_NAME . '.' . $f;
                    }
                    $query -> select(DBLayer::raw(implode(',',$fields_str)) );
                }


                $query -> leftJoin(DBLayer::raw(Atendimento::TABLE_NAME . ' as atd'),
                    DBLayer::raw('atd."' . Atendimento::ID_SOLICITACAO.'"'),
                    '=',
                    DBLayer::raw(Solicitacao::TABLE_NAME . "." . Solicitacao::KEY_ID)
                    );

                $query -> where(DBLayer::raw('atd."'. $field_id_name . '"'), '=', $field_value_id);

                if ($search) {
                    $query -> where (function ($query) use ($search) {
                        $query -> where(DBLayer::raw(Solicitacao::TABLE_NAME . '.' . Solicitacao::DESCRICAO), 'like', $search)
                            -> orWhere(DBLayer::raw(Solicitacao::TABLE_NAME . '.' . Solicitacao::OBSERVACAO), 'like', $search);
                    });
                }

                if ($order) {
                    $query -> orderBy(DBLayer::raw(Solicitacao::TABLE_NAME . "." . $order[0]), $order[1]);
                }

            } else {
                $query -> select($fields);

                $query -> where($field_id_name, '=', $field_value_id);

                if ($search) {
                    $query -> where (function ($query) use ($search) {
                        $query -> where(Solicitacao::DESCRICAO, 'like', $search)
                            -> orWhere(Solicitacao::OBSERVACAO, 'like', $search);
                    });
                }

                if ($order) {
                    $query -> orderBy($order[0], $order[1]);
                }
            }


            if ($limit) {
                $query -> limit($limit);
            }

            if ($offset) {
                $query -> offset($offset);
            }

            $filters = [
                "filter_html" => function($data) {
                    return html_entity_decode(strip_tags($data));
                }
            ];

            $result = [];
            $result['data'] = $query -> get();



            if ($fields_filter) {
                foreach ($fields_filter as $field) {
                    $field_name = $field[0];
                    $filter_func_name = "filter_" . $field[1];

                    // filtra campos
                    $new_data = [];
                    foreach ($result['data'] as $obj) {
                        if (is_array($obj)) {
                            if (array_key_exists($field_name, $obj)) {
                                if (array_key_exists($filter_func_name, $filters)) {
                                    $obj[$field_name] = $filters[$filter_func_name]($obj[$field_name]);
                                }
                            }
                        }
                        array_push($new_data, $obj);
                    }
                    $result['data'] = $new_data;
                }
            }


            return $response -> withStatus(StatusCode::SUCCESS, StatusMessage::SUCCESS)
                -> withJson($result);

        } catch (\Exception $e) {
            return $response -> withStatus(StatusCode::BAD_REQUEST)
                ->withJson([
                    'message' => StatusMessage::MENSAGEM_ERRO_PADRAO,
                    'exception' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile()
                ]);
        }
    }

    public static function get(Req $request, Resp $response)
    {

        try
        {
            $idSolicitacoes = $request->getAttribute('Solicitacoes');
            $solicitacoes = DBLayer::Connect()->table(Solicitacao::TABLE_NAME)->where(Solicitacao::KEY_ID, DBLayer::OPERATOR_EQUAL, $idSolicitacoes)->first();
            return $response->withStatus(200)->withJson($solicitacoes);
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
                $solicitacoes = DBLayer::Connect()->table(Solicitacao::TABLE_NAME)->where(Solicitacao::KEY_ID, DBLayer::OPERATOR_EQUAL, $id)->first();

                if ($solicitacoes)
                {
                    if (DBLayer::Connect()->table(Solicitacao::TABLE_NAME)->delete($id))
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