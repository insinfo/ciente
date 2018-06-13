<?php

namespace Ciente\Controller;

use Ciente\Model\DAL\AnexoDAL;
use Ciente\Model\DAL\AtendimentoDAL;
use Ciente\Model\DAL\AtendimentoHistoricoDAL;
use Ciente\Model\DAL\PessoaDAL;

use Ciente\Model\DAL\ProdutoDAL;
use Ciente\Model\DAL\ServicoDAL;
use Ciente\Model\DAL\SetorHistoricoDAL;
use Ciente\Model\VO\Atendimento;
use Ciente\Model\VO\Constants;
use Ciente\Model\VO\Equipamento;
use Ciente\Model\VO\HistoricoAtendimento;
use Ciente\Model\VO\Pessoa;
use Ciente\Model\VO\Solicitacao;
use Ciente\Model\VO\Token;

use Ciente\Util\DBLayer;
use Ciente\Util\StatusCode;
use Ciente\Util\StatusMessage;
use Ciente\Util\Utils;

use ParagonIE\Halite\Util;
use \Slim\Http\Request as Req;
use \Slim\Http\Response as Resp;

use \Exception;
use Slim\Http\Response;


class AtendimentoController
{

    public static function deleteAnexo ($request, $response, $args) {
        try {
            $atendimento_id = $args['id'];
            $anexo_id = $args['anexo_id'];

            $atendimento = AtendimentoDAL::getInstance()->findOne($atendimento_id);
            $anexo = AnexoDAL::getInstance()->findOne($anexo_id);

            $usuarioLogado = self::getUserLogged($request);
            $atendimento -> removeAnexo ($anexo, $usuarioLogado);

            AtendimentoDAL::getInstance()->save($atendimento);

            return $response->withStatus(200)->withJson([
                'message' => 'Anexo removido com sucesso',
                'anexo' => $anexo ->toArray(),
                'historicos' => self::mountHistorico([$atendimento->getUltimoHistorico()])
            ]);

        } catch (\Exception $e) {
            return $response->withStatus(400)->withJson((['message' => 'Ouve um erro desconhecido.', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }


    }

    public static function listarAnexos($request, $response, $args) {

        try{

            $atendimento_id = $args['id'];

            if (!$atendimento_id) {
                throw new \Exception("O atendimento passado não é válido");
            }
            $atendimento = AtendimentoDAL::getInstance() -> findOne($atendimento_id);

            $anexos = AnexoDAL::getInstance()->findAllByAtendimento($atendimento->getId());
            // {"draw": 1, "start": offset, "length": self._itemsPerPage, "search":""}

            foreach ($anexos as &$anexo) {
                $anexo['deletable'] = false;
                if ($anexo['idPessoa'] == self::getUserLogged($request) ->getId()) {
                    $anexo['deletable'] = true;
                }
            }

            return $response->withStatus(200)->withJson([
                "data" => $anexos
            ]);

        } catch (\Exception $e) {
            return $response->withStatus(400)->withJson((['message' => 'Ouve um erro desconhecido.', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }

    }

    public static function upload($request, $response, $args) {

        try{

            $atendimento_id = $args['id'];

            if (!$atendimento_id) {
                throw new \Exception("O atendimento passado não é válido");
            }
            $atendimento = AtendimentoDAL::getInstance() -> findOne($atendimento_id);
            $userLogged = self::getUserLogged($request);

            $filedata = isset($_FILES['arquivo']) ? $_FILES['arquivo']: null;

            $anexo = $atendimento -> criarAnexo($filedata, $userLogged);

            AtendimentoDAL::getInstance()->save($atendimento);

            return $response->withStatus(200)->withJson([
                "message" => "Anexo criado com sucesso!",
                "file" => $anexo->toArray(),
                "historicos" => self::mountHistorico($atendimento -> getHistoricos())
            ]);

        } catch (\Exception $e) {
            return $response->withStatus(400)->withJson((['message' => 'Ouve um erro desconhecido.', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }

    }

    /**
     * Metodo criado para evitar replicar codigo
     *
     * todo Adicionar esse metodo em uma classe acessivel a todo o sistema
     *
     * @param $request
     * @return Pessoa
     * @throws \Exception
     */
    private static function getUserLogged($request) {
        $token = new Token($request);
        return (new PessoaDAL) -> getById($token->getIdPessoa());
    }

    /**
     * Retorna todos os atendimentos
     *
     * @param Req $request
     * @param Resp $response
     * @return static
     */
    public static function getAll(Req $request, Resp $response){
        try
        {
            $parametros = $request->getParsedBody();

            if (!is_array($parametros)) {
                $parametros = [];
            }

            $draw = array_key_exists('draw', $parametros)? $parametros['draw']: 0;
            $limit = array_key_exists( 'length', $parametros)? $parametros['length']: 20;
            $offset = array_key_exists( 'start', $parametros)? $parametros['start']: 0;
            $order = array_key_exists('order', $parametros)? $parametros['order']: null;
            $direction = array_key_exists('direction', $parametros)? $parametros['direction']: 'asc';
            $filters = array_key_exists('filters', $parametros)? $parametros['filters']: null;

            $search = array_key_exists( 'search', $parametros) && !Utils::isNullOrEmptyString($parametros['search'])? $parametros['search']: null;

            DBLayer::Connect();

            $filter_fields = [
                'status' => 'status',
                'dataAbertura' => '"dataAbertura"',
                'dataEncaminhamento' => '"dataEncaminhamento"',
                'prioridade' => 'prioridade',
                'idResponsavel' => '"idResponsavel"',
                'idSetor' => '"idSetor"',
            ];

            $orders_fields = [
                'nomeOrganograma' => '"nomeOrganograma"',
                'dataAbertura' => '"dataAbertura"',
                'nomeSolicitante' => '"nomeSolicitante"',
                'siglaOrganograma' => '"siglaOrganograma"',
                'siglaSetor' => '"siglaSetor"',
                'nomeSetor' => '"nomeSetor"',
                'prioridade' => '"prioridade"',
                'status' => 'status',
                'idResponsavel' => '"idResponsavel"',
                'nomeResponsavel' => '"nomeResponsavel"'
            ];

            $search_fields = [
                'cpfSolicitante',
                'nomeSolicitante',
                'nomeOrganograma',
                'siglaOrganograma',
                'descricao',
                'ano',
                'numero',
                'descricao'
            ];

            $query = DBLayer::table("vw_atendimentos_json")
                -> select('json');

            $totalRecords = $query->count(); // conta total de atendimentos

            if ($filters) {
                $query -> where(function ($query) use ($filters, $filter_fields) {
                    $filters_type = gettype($filters);

                    if ($filters_type == 'array') {
                        foreach ($filters as $filter) {
                            $filter_key = key($filter);

                            if (array_key_exists($filter_key, $filter_fields)) {
                                $filter_field = $filter_fields[$filter_key];
                                $filter_value = $filter[$filter_key];


                                if (is_string($filter_value) || is_int($filter_value)) {

                                    if ($filter_key == 'idResponsavel') { // filtro especifico para o campo usuário

                                        $query -> where(function ($query) use ($filter_field, $filter_value) {
                                            $query->whereRaw(
                                                $filter_field . '=' . "'" . $filter_value . "'"
                                            )->orWhereRaw(
                                                $filter_field . ' is ' . "null"
                                            );
                                        });

                                    } else {
                                        $query->whereRaw(
                                            $filter_field . '=' . "'" . $filter_value . "'"
                                        );
                                    }

                                } else if(is_array($filter_value)){
                                    $operator = '=';
                                    $value = '';

                                    if (array_key_exists('operator', $filter_value)) {
                                        $operator = $filter_value['operator'];
                                    }

                                    if (array_key_exists('value', $filter_value)) {
                                        $value = $filter_value['value'];
                                    }

                                    $query->whereRaw($filter_field ." ". $operator . " '" . $value . "'");
                                }
                            }
                        }
                    }

                });
            }

            if ($search) {
                $query -> where(function ($query) use ($search, $search_fields) {
                    $search_str = "'%".$search."%'";

                    foreach ($search_fields as $field) {
                        $query -> orWhereRaw(Utils::adAspas($field) . " ilike " . $search_str);
                    }

                    // $query -> orWhereRaw('ate."' . Atendimento::DATA_ENCAMINHAMENTO . '" ilike '. $search_str);

                });
            }

            $totalFiltered = $query->count();

            if ($order) {
                $order_type = gettype($order);

                if ($order_type == "array") {
                    foreach ($order as $option) {
                        $field = key($option);

                        if (array_key_exists($field, $orders_fields)) {
                            $query->orderBy(
                                DBLayer::raw($orders_fields[$field]),
                                $option[$field]
                            );
                        }
                    }
                }
            } else {
                $query->orderBy("dataAbertura", 'desc');
            }

            if ($limit) {
                $query->take($limit);
            }

            if ($offset) {
                $query->offset($offset);
            }

            $data = $query->get();

            // transform json
            $new_data = [];
            foreach ($data as &$value) {
                $json_object = json_decode($value['json']);
                $json_object -> descricao = html_entity_decode(strip_tags($json_object -> descricao));
                array_push($new_data, $json_object);
            }

            $result['draw'] = 1;
            $result['recordsFiltered'] = $totalFiltered;
            $result['recordsTotal'] = $totalRecords;
            $result['data'] = $new_data;
            $result['atendimentoStatus'] = Atendimento::LIST_STATUS;
            $result['atendimentoPrioridades'] = Solicitacao::LIST_PRIORIDADES;

            return $response->withStatus(200)->withJson($result);
        }
        catch (Exception $e)
        {
            return $response->withStatus(400)->withJson((['message' => 'Ouve um erro desconhecido.', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
    }

    /**
     * Salva os dados de um atendimento
     *
     * @param Req $request
     * @param Resp $response
     * @return static
     */
    public static function save(Req $request, Resp $response){

        try
        {
            $idAtendimentos = $request->getAttribute('idAtendimentos');
            $formData = Utils::filterArrayByArray($request->getParsedBody(), Atendimento::TABLE_FIELDS);

            if ($idAtendimentos)
            {
                DBLayer::Connect()->table(Atendimento::TABLE_NAME)->update($formData);
            }
            else
            {
                DBLayer::Connect()->table(Atendimento::TABLE_NAME)->insertGetId($formData);
            }
        }
        catch (Exception $e)
        {
            return $response->withStatus(400)->withJson((['message' => 'Ouve um erro desconhecido.', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(200)->withJson((['message' => 'Salvo com sucesso']));
    }

    /**
     * Retorna um atendimento especifico
     *
     * @param Req $request
     * @param Resp $response
     * @param $args
     * @return static
     */
    public static function get(Req $request, Resp $response, $args){
        try
        {

            $atendimento_id = $args['id'];

            if (!$atendimento_id) {
                throw new \Exception('É obrigatório a passagem do id do atendimento');
            }

            $query = DBLayer::Connect() -> table(
                DBLayer::raw(
                    Utils::adAspas(Atendimento::TABLE_NAME) . ' ate'
                )
            );

            $pessoa_select = [
                'pes.' . Pessoa::NOME_PESSOA . ' as nomeOperador'
            ];

            $solicitacao_select = [
                'so.' . Solicitacao::DESCRICAO,
                'so.' . Solicitacao::OBSERVACAO,
                'so.' . Solicitacao::ANO,
                'so.' . Solicitacao::DATA_ABERTURA,
                'so.' . Solicitacao::NUMERO,
                'so.' . Solicitacao::PRIORIDADE
            ];

            $fields = array_merge(['ate.*'],
                $solicitacao_select,
                $pessoa_select,
                ['sol.nome as nomeSolicitante'],
                ['sol_sec.nome as nomeSolicitanteSecundario'],
                ['res.nome as nomeResponsavel'],
                ['equi.patrimonioPmro as equipamento']
            );

            $query -> select($fields);

            // Join solicitacoes
            $query -> leftJoin(DBLayer::raw(Utils::adAspas(Solicitacao::TABLE_NAME) . ' so'),
                DBLayer::raw('so.'.Utils::adAspas(Solicitacao::KEY_ID)),
                "=",
                DBLayer::raw('ate.' . Utils::adAspas(Atendimento::ID_SOLICITACAO)));

            // pega operador
            $query -> leftJoin(
                DBLayer::raw(Utils::adAspas(Pessoa::SCHEMA). '.' . Utils::adAspas(Pessoa::TABLE_NAME_PESSOA) . ' pes'),
                DBLayer::raw('so.' . Utils::adAspas(Solicitacao::ID_OPERADOR)),
                '=',
                DBLayer::raw('pes.' . Utils::adAspas(Pessoa::KEY_ID_PESSOA))
            );

            // pega nome solicitante
            $query -> leftJoin(
                DBLayer::raw(Utils::adAspas(Pessoa::SCHEMA). '.' . Utils::adAspas(Pessoa::TABLE_NAME_PESSOA) . ' sol'),
                DBLayer::raw('so.' . Utils::adAspas(Solicitacao::ID_SOLICITANTE)),
                '=',
                DBLayer::raw('sol.' . Utils::adAspas(Pessoa::KEY_ID_PESSOA))
            );

            // pegar nome responsável
            $query -> leftJoin(
                DBLayer::raw(Utils::adAspas(Pessoa::SCHEMA). '.' . Utils::adAspas(Pessoa::TABLE_NAME_PESSOA) . ' res'),
                DBLayer::raw('ate.' . Utils::adAspas(Atendimento::ID_USUARIO)),
                '=',
                DBLayer::raw('res.' . Utils::adAspas(Pessoa::KEY_ID_PESSOA))
            );

            // pega nome solicitante secundário
            $query -> leftJoin(
                DBLayer::raw(Utils::adAspas(Pessoa::SCHEMA). '.' . Utils::adAspas(Pessoa::TABLE_NAME_PESSOA) . ' sol_sec'),
                DBLayer::raw('so.' . Utils::adAspas(Solicitacao::ID_SOLICITANTESEC)),
                '=',
                DBLayer::raw('sol_sec.' . Utils::adAspas(Pessoa::KEY_ID_PESSOA))
            );

            // pegar equipamento
            $query -> leftJoin(
                DBLayer::raw(Utils::adAspas(Equipamento::TABLE_NAME) . ' equi'),
                DBLayer::raw('so.' . Utils::adAspas(Solicitacao::ID_EQUIPAMENTO)),
                '=',
                DBLayer::raw('equi.'. Utils::adAspas(Equipamento::KEY_ID))
            );

            // pegar o solicitante secundario
            $query -> whereRaw(
                DBLayer::raw('ate.'. Utils::adAspas(Atendimento::KEY_ID) . '=' . $atendimento_id)
            );

            $query -> orderBy('ate.id', "asc");

            $data = $query -> get();

            // define joins para historico
            $historico_join_callback = function ($query) {
                $query -> select([
                    DBLayer::raw(Utils::adAspas(HistoricoAtendimento::TABLE_NAME).'.*'),
                    DBLayer::raw('pes.nome as "nomePessoa"'),
                    DBLayer::raw('pes.imagem as "imagemPessoa"'),
                ]);

                $query -> leftJoin(
                    DBLayer::raw(Utils::adAspas(Pessoa::SCHEMA) . '.' . Utils::adAspas(Pessoa::TABLE_NAME_PESSOA) . ' pes'),
                    DBLayer::raw('pes.'. Utils::adAspas(Pessoa::KEY_ID_PESSOA)),
                    '=',
                    DBLayer::raw(Utils::adAspas(HistoricoAtendimento::TABLE_NAME) . '.' . Utils::adAspas(HistoricoAtendimento::ID_USUARIO)));

                $query -> orderBy("id", "asc");
            };

            DBLayer::getRelationAll($data,
                HistoricoAtendimento::TABLE_NAME,
                HistoricoAtendimento::ID_ATENDIMENTO,
                Atendimento::KEY_ID,
                'historicos', $defaultNull=[],
                null,
                $historico_join_callback);

            DBLayer::getRelationAll($data,
                Atendimento::TABLE_NAME,
                Atendimento::ID_SOLICITACAO,
                Atendimento::ID_SOLICITACAO,
                'solicitacaoAtendimentos', $defaultNull=[],
                null,
                function ($query) use ($solicitacao_select, $pessoa_select) {

                    $query -> select(array_merge([
                        Atendimento::TABLE_NAME . '.*',
                        'set.nome as nomeSetor',
                        'set.sigla as siglaSetor',
                        'res.nome as nomeResponsavel'
                    ],
                        $solicitacao_select, $pessoa_select));

                    $query -> leftJoin(DBLayer::raw(Utils::adAspas(Solicitacao::TABLE_NAME) . ' so'),
                        DBLayer::raw('so.' . Utils::adAspas(Solicitacao::KEY_ID)),
                        "=",
                        DBLayer::raw(Utils::adAspas(Atendimento::TABLE_NAME) . '.' . Utils::adAspas(Atendimento::ID_SOLICITACAO )));

                    // retorna nome do operador
                    $query -> leftJoin(
                        DBLayer::raw(Utils::adAspas(Pessoa::SCHEMA). '.' . Utils::adAspas(Pessoa::TABLE_NAME_PESSOA) . ' pes'),
                        DBLayer::raw('so.' . Utils::adAspas(Solicitacao::ID_OPERADOR)),
                        '=',
                        DBLayer::raw('pes.' . Utils::adAspas(Pessoa::KEY_ID_PESSOA))
                    );

                    // retornando responsavel atendimento
                    $query -> leftJoin(
                        DBLayer::raw(Utils::adAspas(Pessoa::SCHEMA). '.' . Utils::adAspas(Pessoa::TABLE_NAME_PESSOA) . ' res'),
                        DBLayer::raw(Utils::adAspas(Atendimento::TABLE_NAME) . '.' . Utils::adAspas(Atendimento::ID_USUARIO)),
                        '=',
                        DBLayer::raw('res.' . Utils::adAspas(Pessoa::KEY_ID_PESSOA))
                    );

                    $query -> leftJoin(
                        DBLayer::raw('func_setores(' . Utils::adAspas(Atendimento::DATA_ENCAMINHAMENTO) . ') set'),
                        DBLayer::raw(Utils::adAspas(Atendimento::TABLE_NAME) . '.' . Utils::adAspas(Atendimento::ID_SETOR)),
                        '=',
                        DBLayer::raw('set."idSetor"')
                    );

                    $query -> orderBy('id', 'asc');

                });

            DBLayer::getRelationAll($data[0]['solicitacaoAtendimentos'],
                HistoricoAtendimento::TABLE_NAME,
                HistoricoAtendimento::ID_ATENDIMENTO,
                Atendimento::KEY_ID,
                'historicos', $defaultNull=[],
                null,
                $historico_join_callback);

            $data[0]['lista_status'] = Atendimento::LIST_STATUS;
            $data[0]['date_now'] = date("Y-m-d H:i:s");

            return $response->withStatus(200)
                -> withJson($data[0]);
        }
        catch (Exception $e)
        {
            return $response->withStatus(400)
                -> withJson(['message' => 'Ouve um erro desconhecido.', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
        }
    }

    /**
     * Remove um atendimento
     *
     * @param Req $request
     * @param Resp $response
     * @return static
     */
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
                $atendimentos = DBLayer::Connect()->table(Atendimento::TABLE_NAME)->where(Atendimento::KEY_ID, DBLayer::OPERATOR_EQUAL, $id)->first();

                if ($atendimentos)
                {
                    if (DBLayer::Connect()->table(Atendimento::TABLE_NAME)->delete($id))
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

    /**
     * Encaminhar atendimento para outro usuario
     *
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public static function encaminhar($request, $response, $args) {
        try {
            $atendimento_id = $args['id'];

            if (!$atendimento_id) {
                throw new \Exception("Id do atendimento é obrigatório!");
            }

            $params = $request->getParsedBody();
            $usuario_id = $params['idUsuario'];
            $mensagem = $params['mensagem'];

            if (!$mensagem) {
                throw new \Exception('O campo mensagem é obrigatório');
            }

            // busca o atendimento a ser encaminhado
            $atendimento = AtendimentoDAL::getInstance() -> findOne($atendimento_id);

            // busca informaçoes do token

            $usuario_logado = self::getUserLogged($request);
            $destinatario = (new PessoaDAL) -> getById($usuario_id);

            $data_agora = Utils::getDateTimeNow('en');

            try {

                $atendimento -> encaminhar($usuario_logado, $destinatario, $mensagem, $data_agora);
                AtendimentoDAL::getInstance()->save($atendimento);

            } catch (\Exception $e) {
                throw $e;
            }

            return $response->withStatus(StatusCode::SUCCESS)->withJson([
                'message' => StatusMessage::SUCCESS,
                "historicos" => self::mountHistorico($atendimento -> getHistoricos()),
                "destinatario" => [
                    "nome" => $destinatario -> getFullName()
                ]
            ]);

        } catch (\Exception $e) {
            return $response->withStatus(StatusCode::BAD_REQUEST)
                -> withJson((['message' => StatusMessage::ERROR, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
    }

    /**
     * Troca status do atendimento
     *
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public static function trocarStatus($request, $response, $args) {
        try {
            $atendimento_id = $args['id'];


            if (!$atendimento_id) {
                throw new \Exception("Id do atendimento é obrigatório!");
            }

            $params = $request->getParsedBody();
            $status = $params['status']? $params['status']: 0;
            $mensagem = $params['mensagem'];

            $usuarioLogado = self::getUserLogged($request);
            $atendimento = AtendimentoDAL::getInstance()->findOne($atendimento_id);

            try {

                $atendimento -> alterarStatus($status, $usuarioLogado, $mensagem);
                AtendimentoDAL::getInstance()->save($atendimento);

            } catch (\Exception $e) {
                throw $e;
            }

            $totalAtendimentosNaoConcluidos = AtendimentoHistoricoDAL::getInstance()
                -> contaAtendimentosNaoConcluidosPorSolicitacao($atendimento->getIdSolicitacao());

            if ($totalAtendimentosNaoConcluidos === 0 && $atendimento -> getStatus() >= Atendimento::CONCLUIDO) {

                DBLayer::table(Solicitacao::TABLE_NAME)
                    -> where(Solicitacao::KEY_ID, "=", $atendimento -> getIdSolicitacao())
                    -> update(['dataFechamento' => Utils::getDateTimeNow()]);

            }

            return $response->withStatus(StatusCode::SUCCESS)->withJson([
                'message' => StatusMessage::SUCCESS,
                "historicos" => self::mountHistorico($atendimento -> getHistoricos())
            ]);

        } catch (\Exception $e) {
            return $response->withStatus(StatusCode::BAD_REQUEST)
                -> withJson((['message' => StatusMessage::ERROR, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
    }

    /**
     * Envia comentario para atendimento
     *
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public static function enviarComentario ($request, $response, $args) {
        try {
            $atendimento_id = $args['id'];

            if (!$atendimento_id) {
                throw new \Exception('E necessario passar o id do atendimento');
            }

            $atendimento = AtendimentoDAL::getInstance() -> findOne($atendimento_id);
            $body = $request->getParsedBody();

            $usuarioLogado = self::getUserLogged($request);

            $comentario = array_key_exists('mensagem', $body)? $body['mensagem']: '';
            if (!$comentario) {
                throw new \Exception("O campo 'comentario' e obrigatorio!");
            }

            $novo_responsavel_id = array_key_exists('responsavel_id', $body)? $body['responsavel_id'] : null;
            if (!$novo_responsavel_id) {
                throw new \Exception("Informe o id do novo responsavel para o atendimento ou o responsavel atual");
            }

            // mudança da regra, adiciona um comentário quando o responsável enviado for diferente do responsável atual
            if ($atendimento -> getIdUsuario() == $novo_responsavel_id) {
                $atendimento -> comentar($comentario, $usuarioLogado);
                AtendimentoDAL::getInstance()->save($atendimento);

            } else {
                $novo_responsavel = PessoaDAL::getInstance() -> getById($novo_responsavel_id);

                $atendimento -> encaminhar($usuarioLogado, $novo_responsavel, $comentario, Utils::getDateTimeNow('en'));
                AtendimentoDAL::getInstance()->save($atendimento);

            }

            // recupera historicos

            return $response->withStatus(StatusCode::SUCCESS)->withJson([
                'message' => StatusMessage::SUCCESS,
                "historicos" => self::mountHistorico($atendimento -> getHistoricos())
            ]);

        } catch (\Exception $e) {
            return $response->withStatus(StatusCode::BAD_REQUEST)
                -> withJson((['message' => StatusMessage::ERROR, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
    }

    /**
     * Transfere atendimento para outro setor
     *
     * Quando um atendimento for tranferido para um outro setor, o que acontece
     * e que o atendimento atual e fechado e e criado um novo atendimento para o setor de destino.
     *
     * @param $request
     * @param $response
     * @param $args
     *
     * @return Response
     */
    public static function tranferir ($request, $response, $args) {

        try {
            $atendimento_id = $args['id'];
            $setor_id = $args['setor_id'];

            $data_body = $request->getParsedBody();

            $usuario_logado = self::getUserLogged($request);

            $setorHistorico = SetorHistoricoDAL::getInstance()->findOne($setor_id);
            $atendimento = AtendimentoDAL::getInstance()->findOne($atendimento_id);
            $servico = ServicoDAL::getInstance()->findOne($data_body['idServico']);

            $mensagem = isset($data_body['mensagem'])? $data_body['mensagem']: "";
            $manter_aberto = isset($data_body['manter_aberto'])? $data_body['manter_aberto']: false;
            $status = isset($data_body['status'])? $data_body['status']: null;

            $_array_key = 'idProduto';
            $novoAtendimento = null;

            if (array_key_exists($_array_key, $data_body)) {
                $produto = ProdutoDAL::getInstance()->findOne($data_body[$_array_key]);
                $novoAtendimento = $atendimento -> transferir($usuario_logado, $setorHistorico, $servico, $produto, $mensagem, $status, !$manter_aberto);
            } else {
                $novoAtendimento = $atendimento -> transferir($usuario_logado, $setorHistorico, $servico, null, $mensagem, $status, !$manter_aberto);
            }

            try {
                // salva novo atendimento
                $result = AtendimentoDAL::getInstance()->save($novoAtendimento);

                if ($result && $result -> getId()) {
                    // salva informaçoes do atendimento atual
                    AtendimentoDAL::getInstance()->save($atendimento);
                }
            } catch (\Exception $e) {
                throw $e;
            }

            return $response->withStatus(StatusCode::SUCCESS)->withJson([
                'message' => StatusMessage::SUCCESS,
                "historicos" => self::mountHistorico($atendimento->getHistoricos()),
                "novoAtendimento" => $novoAtendimento
            ]);

        } catch (\Exception $e) {
            return $response->withStatus(StatusCode::BAD_REQUEST)
                -> withJson((['message' => StatusMessage::ERROR, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));

        }
    }

    /**
     * Asumir um atendimento
     *
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public static function assumir ($request, $response, $args) {
        try{
            $atendimento_id = $args['id'];
            $atendimentoDao = AtendimentoDAL::getInstance();

            $atendimento = $atendimentoDao -> findOne($atendimento_id);
            $pessoa = self::getUserLogged($request);

            $atendimento -> assumir($pessoa);

            AtendimentoDAL::getInstance()->save($atendimento);

            return $response->withStatus(StatusCode::SUCCESS)->withJson([
                'message' => StatusMessage::SUCCESS,
                "historicos" => self::mountHistorico($atendimento->getHistoricos()),
                "responsavel" => [
                    'nome' => $pessoa -> getFullName()
                ]
            ]);

        } catch (\Exception $e) {
            return $response->withStatus(StatusCode::BAD_REQUEST)
                -> withJson((['message' => StatusMessage::ERROR, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
    }

    /**
     * Utilizada internamente no controle para montar os históricos conforme é 
     * necessário no json
     * 
     * @param Array $historicos Um array de objetos do tipo historico 
     **/
    private static function mountHistorico(Array $historicos) {
        $historico_return = [];

        foreach ($historicos as $historico) {

            $pessoa = PessoaDAL::getInstance()->getById($historico -> getIdUsuario());

            array_push($historico_return, [
                'data' => $historico -> getData(),
                'historico' => $historico -> getComentario(),
                "id" => $historico -> getId(),
                "idAtendimento" => $historico -> getIdAtendimento(),
                "idUsuario" => $historico -> getIdUsuario(),
                "nomePessoa" => $pessoa -> getNome(),
                "tipo" => $historico -> getTipo(),
                "imagemPessoa" => $pessoa -> getImagem()
            ]);
        }

        return $historico_return;
    }
}