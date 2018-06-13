<?php

use \Slim\Http\Request as Req;
use \Slim\Http\Response as Resp;

use \Ciente\Controller\EquipamentoTipoController;
use \Ciente\Controller\EquipamentoController;
use \Ciente\Controller\ContratoController;
use \Ciente\Controller\ServicoTipoController;
use \Ciente\Controller\ServicoController;
use \Ciente\Controller\SetorController;
use \Ciente\Controller\EquipamentoModeloController;
use \Ciente\Controller\AuthMiddleware;
use \Ciente\Controller\SolicitacaoController;
use \Ciente\Controller\UsuarioController;
use \Ciente\Controller\EstatisticaController;
use \Ciente\Controller\ProdutoController;
use \Ciente\Controller\SetorUsuarioController;

// ROTAS DE WEBSERVICE REST
$app->group('/api', function () use ($app)
{
    /**************** ROTAS TIPO DE EQUIPAMENTO ******************/
    //CRIA E ATUALIZA TIPO DE EQUIPAMENTO
    $app->put('/equipamentoTipo/[{idEquipamentoTipo}]', function (Req $request, Resp $response, $args) use ($app)
    {
        return EquipamentoTipoController::save($request,$response);
    });
    //LISTA TIPO DE EQUIPAMENTO
    $app->post('/equipamentoTipo','\Ciente\Controller\EquipamentoTipoController::getAll');
    //DELETA TIPO DE EQUIPAMENTO
    $app->delete('/equipamentoTipo', function (Req $request, Resp $response, $args) use ($app)
    {
        return EquipamentoTipoController::delete($request,$response);
    });
    //OBTEM UM TIPO DE EQUIPAMENTO
    $app->get('/equipamentoTipo/[{idEquipamentoTipo}]', function (Req $request, Resp $response, $args) use ($app)
    {
        return EquipamentoTipoController::get($request,$response);
    });

    /**************** ROTAS CONTRATO ******************/

    //CRIA E ATUALIZA CONTRATO
    $app->put('/contrato/[{id}]', function (Req $request, Resp $response, $args) use ($app)
    {
        return ContratoController::save($request,$response);
    });
    //LISTA CONTRATOS
    $app->post('/contrato','\Ciente\Controller\ContratoController::getAll');
    //LISTA CONTRATOS ATIVOS
    $app->post('/contratos/ativos','\Ciente\Controller\ContratoController::getContratosAtivos');
    //DELETA CONTRATO
    $app->delete('/contrato', function (Req $request, Resp $response, $args) use ($app)
    {
        return ContratoController::delete($request,$response);
    });
    //OBTEM UM CONTRATO
    $app->get('/contrato/[{idContrato}]', function (Req $request, Resp $response, $args) use ($app)
    {
        return ContratoController::get($request,$response);
    });
    //LISTA Pessoas do PMRO_PADRAO
    $app->put('/contratos/pessoas','\Ciente\Controller\ContratoController::getContratosPessoas');

    /**************** ROTAS TIPOS SERVICOS *******************/

    //CRIA E ATUALIZA TIPOS SERVICOS
    $app->put('/servicoTipo/[{id}]', function (Req $request, Resp $response, $args) use ($app)
    {
        return ServicoTipoController::save($request,$response);
    });

    //LISTA TIPOS SERVICOS
    $app->post('/servicoTipo',function (Req $request, Resp $response, $args) use ($app)
    {
        return ServicoTipoController::getAll($request,$response);
    });

    $app->post('/servicoTipo/usuario',function (Req $request, Resp $response, $args) use ($app)
    {
        return ServicoTipoController::getAllOfUser($request,$response);
    });


    //DELETA TIPOS SERVICOS
    $app->delete('/servicoTipo', function (Req $request, Resp $response, $args) use ($app)
    {
        return ServicoTipoController::delete($request,$response);
    });
    //OBTEM UM TIPO SERVICO
    $app->get('/servicoTipo/[{idServicoTipo}]', function (Req $request, Resp $response, $args) use ($app)
    {
        return ServicoTipoController::get($request,$response);
    });


    /**************** ROTAS SERVICOS ******************/
    //CRIA E ATUALIZA SERVICO
    $app->put('/servicos/[{id}]', function (Req $request, Resp $response, $args) use ($app)
    {
        return ServicoController::save($request,$response);
    });
    //LISTA SERVICOS
    $app->post('/servicos',function (Req $request, Resp $response, $args) use ($app)
    {
        return ServicoController::getAll($request,$response);
    });

    $app->post('/servicos/usuario',function (Req $request, Resp $response, $args) use ($app)
    {
        return ServicoController::getAllOfUser($request,$response);
    });

    //OBTEM UM SERVICO
    $app->get('/servicos/[{id}]', function (Req $request, Resp $response, $args) use ($app)
    {
        return ServicoController::get($request,$response);
    });
    //DELETA SERVICOS
    $app->delete('/servicos', function (Req $request, Resp $response, $args) use ($app)
    {
        return ServicoController::delete($request,$response);
    });

    /**************** ROTAS SETORES ******************/
    //CRIA E ATUALIZA SETORES
    $app->put('/setores/{id}/{dataInicio}', function (Req $request, Resp $response, $args) use ($app)
    {
        return SetorController::save($request,$response);
    });
    $app->put('/setores/[{id}]', function (Req $request, Resp $response, $args) use ($app)
    {
        return SetorController::save($request,$response);
    });
    //LISTA SETORES
    $app->post('/setores',function (Req $request, Resp $response, $args) use ($app)
    {
        return SetorController::getAll($request,$response);
    });
    //LISTA SETORES hierarquia
    $app->post('/setores/hierarquia',function (Req $request, Resp $response, $args) use ($app)
    {
        return SetorController::getHierarquia($request,$response);
    });

    $app->delete('/setores', function (Req $request, Resp $response, $args) use ($app)
    {
        return SetorController::deleteHistorico($request,$response);
    });

    $app->delete('/setores/{id}', function (Req $request, Resp $response, $args) use ($app)
    {
        return SetorController::deleteSetor($request,$response);
    });

    //LISTA SETORES DO USUARIO LOGADO
    $app->post('/setores/usuario',function (Req $request, Resp $response, $args) use ($app)
    {
        return SetorController::getOfUser($request,$response);
    });

    //etorna a quantidade de usuarios que estão acociados a um setor
    $app->post('/setores/usuario/quantidade',function (Req $request, Resp $response, $args) use ($app)
    {
        return SetorController::getUserCount($request,$response);
    });

   //usuario extenção
    $app->post('/setorusuario',function (Req $request, Resp $response, $args) use ($app)
    {
        return SetorUsuarioController::getAll($request,$response);
    });
    //alterar o(s) setor(es) do usuario
    $app->put('/setorusuario',function (Req $request, Resp $response, $args) use ($app)
    {
        return SetorUsuarioController::updateSetores($request,$response);
    });


    /**************** ROTAS EQUIPAMENTO MODELO ******************/
    //CRIA E ATUALIZA TIPO DE EQUIPAMENTO
    $app->put('/equipamentoModelo/[{idEquipamentoModelo}]', function (Req $request, Resp $response, $args) use ($app)
    {
        return EquipamentoModeloController::save($request,$response);
    });
    //LISTA TIPO DE EQUIPAMENTO
    $app->post('/equipamentoModelo','\Ciente\Controller\EquipamentoModeloController::getAll');

    //DELETA TIPO DE EQUIPAMENTO
    $app->delete('/equipamentoModelo', function (Req $request, Resp $response, $args) use ($app)
    {
        return EquipamentoModeloController::delete($request,$response);
    });
    //OBTEM UM TIPO DE EQUIPAMENTO
    $app->get('/equipamentoModelo/[{idEquipamentoTipo}]', function (Req $request, Resp $response, $args) use ($app)
    {
        return EquipamentoTipoController::get($request,$response);
    });

    /**************** ROTAS EQUIPAMENTO ******************/
    //CRIA E ATUALIZA EQUIPAMENTO
    $app->put('/equipamento/[{idEquipamento}]', function (Req $request, Resp $response, $args) use ($app)
    {
        return EquipamentoController::save($request,$response);
    });
    //LISTA DE EQUIPAMENTO
    $app->post('/equipamento','\Ciente\Controller\EquipamentoController::getAll');

    //DELETA TIPO DE EQUIPAMENTO
    $app->delete('/equipamento', function (Req $request, Resp $response, $args) use ($app)
    {
        return EquipamentoModeloController::delete($request,$response);
    });
    //OBTEM UM TIPO DE EQUIPAMENTO
    $app->get('/equipamento/[{idEquipamento}]', function (Req $request, Resp $response, $args) use ($app)
    {
        return EquipamentoController::get($request,$response);
    });

    //OBTEM OS EQUIPAMENTO DO SETOR DO USUARIO LOGADO
    $app->post('/equipamento/usuario', function (Req $request, Resp $response, $args) use ($app)
    {
        return EquipamentoController::getEquipamentoUsuario($request,$response);
    });

    /* ROTAS DE SOLICITAÇÔES */
    $app->put('/solicitacao', function (Req $request, Resp $response, $args) use ($app){
        return SolicitacaoController::save($request, $response);
    });

    $app->post('/solicitacoes/por/{field-name}/{id}', function (Req $request, Resp $response, $args) use ($app) {
        return \Ciente\Controller\SolicitacaoController::getSolicitacoesPor($request, $response, $args);
    });
    //minhas solicitações
    $app->post('/solicitacoes/usuario', function (Req $request, Resp $response, $args) use ($app) {
        return \Ciente\Controller\MinhaSolicitacaoController::getAll($request, $response);
    });
    //pesquisa avançada de  solicitações
    $app->post('/solicitacoes/pesquisa', function (Req $request, Resp $response, $args) use ($app) {
        return \Ciente\Controller\MinhaSolicitacaoController::advancedSearch($request, $response);
    });

    /* ROTAS PARA ATENDIMENTO */

    $app->post('/listarAtendimentos', function ($request, $response, $args) {
        return \Ciente\Controller\AtendimentoController::getAll($request, $response);
    });

    $app->get('/atendimento', function ($request, $response, $args) {
        return \Ciente\Controller\AtendimentoController::getAll($request, $response);
    });

    $app->get('/atendimento/{id}', function ($request, $response, $args) {
        return \Ciente\Controller\AtendimentoController::get($request, $response, $args);
    });

    $app->post('/atendimento/{id}/encaminhar', function ($request, $response, $args) {
        return \Ciente\Controller\AtendimentoController::encaminhar($request, $response, $args);
    });

    $app->post('/atendimento/{id}/trocarStatus', function ($request, $response, $args) {
        return \Ciente\Controller\AtendimentoController::trocarStatus($request, $response, $args);
    });

    $app->post('/atendimento/{id}/comentario', function ($request, $response, $args) {
        return \Ciente\Controller\AtendimentoController::enviarComentario($request, $response, $args);
    });

    $app->post('/atendimento/{id}/transferir/{setor_id}', function ($request, $response, $args) {
       return \Ciente\Controller\AtendimentoController::tranferir($request, $response, $args);
    });

    $app->post('/atendimento/{id}/assumir', function ($request, $response, $args) {
        return \Ciente\Controller\AtendimentoController::assumir($request, $response, $args);
    });

    $app->post('/atendimento/{id}/upload', function ($request, $response, $args) {
        return \Ciente\Controller\AtendimentoController::upload($request, $response, $args);
    });

    $app->get('/atendimento/{id}/anexos', function ($request, $response, $args) {
        return \Ciente\Controller\AtendimentoController::listarAnexos($request, $response, $args);
    });

    $app->delete('/atendimento/{id}/anexos/{anexo_id}', function ($request, $response, $args) {
        return \Ciente\Controller\AtendimentoController::deleteAnexo($request, $response, $args);
    });


    /* ROTAS PARA USUARIOS */

    //listar todos os usuarios
    $app->post('/usuarios', function (Req $request, Resp $response, $args)
    {
        return UsuarioController::getAll($request,$response);
    });

    $app->get('/usuarios/setor/{idSetor}', function (Req $request, Resp $response, $args) use ($app)
    {
        return UsuarioController::getBySetor($request,$response);
    });


    /* ROTAS PARA Estatistica das Solicitacoes e Atendimentos */

    //listar todos os usuarios
    $app->get('/estatisticas/status/atendimento/setor/{idSetor}', function (Req $request, Resp $response, $args)
    {
        return EstatisticaController::getStatusAtendimentoSetor($request,$response,$args);
    });

    $app->get('/estatisticas/prioridade/solicitacao/setor/{idSetor}', function (Req $request, Resp $response, $args)
    {
        return EstatisticaController::getPrioridadeSolicitacaoSetor($request,$response,$args);
    });


    //CRIA E ATUALIZA Produto
    $app->put('/produto/[{id}]', function (Req $request, Resp $response, $args) use ($app)
    {
        return ProdutoController::save($request,$response);
    });

    //LISTA Produtos
    $app->post('/produtos', function (Req $request, Resp $response, $args) use ($app)
    {
        return ProdutoController::getAll($request,$response);
    });

    //LISTA Produtos ATIVOS
    $app->post('/produtos/ativos','\Ciente\Controller\ProdutoController::getProdutosAtivos');

    //DELETA CONTRATO
    $app->delete('/produto', function (Req $request, Resp $response, $args) use ($app)
    {
        return ProdutoController::delete($request,$response);
    });
    //OBTEM UM CONTRATO
    $app->get('/produto/[{idProduto}]', function (Req $request, Resp $response, $args) use ($app)
    {
        return ProdutoController::get($request,$response);
    });

})->add( new AuthMiddleware() );
