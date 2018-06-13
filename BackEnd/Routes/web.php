<?php

use \Slim\Http\Request as Req;
use \Slim\Http\Response as Resp;


// ROTAS DE WEBPAGES

$app->get('/', function ( $request,  $response, $args) use ($app)
{
    return  $this->view->render($response, 'MainView.php');
});

$app->group('', function () use ($app)
{
    //pagina
    $app->get('/grid', function ($request, $response, $args)
    {
        return $this->view->render($response, 'GerenciarAtendimentosView.php');
    });

    $app->get('/detalhe', function ($request, $response, $args)
    {
        return $this->view->render($response, 'DetalheAtendimentoView.php');
    });
    $app->get('/novaSolicitacao', function ($request, $response, $args)
    {
        return $this->view->render($response, 'NovaSolicitacaoView.php');
    });
    $app->get('/minhasSolicitacoes', function ($request, $response, $args)
    {
        return $this->view->render($response, 'MinhasSolicitacoesView.php');
    });
    $app->get('/pesquisaAvancada', function ($request, $response, $args)
    {
        return $this->view->render($response, 'PesquisaAvancadaView.php');
    });
    //pagina gerencia tipo equipamento
    $app->get('/equipamentoTipo', function ($request, $response, $args)
    {
        return $this->view->render($response, 'GerenciarEquipamentoTipoView.php');
    });

    //pagina gerencia contrato
    $app->get('/contrato', function ($request, $response, $args)
    {
        return $this->view->render($response, 'GerenciarContratoView.php');
    });

    //pagina gerencia servicoTipo
    $app->get('/servicoTipo', function ($request, $response, $args)
    {
        return $this->view->render($response, 'GerenciarServicoTipoView.php');
    });

    // pagina gerencia servico
    $app->get('/servicos', function ($request, $response, $args)
    {
        return $this->view->render($response, 'GerenciarServicosView.php');
    });

    $app->get('/gerenciarSetores', function ($request, $response, $args)
    {
        return $this->view->render($response, 'GerenciarSetoresView.php');
    });

    //equipamento_modelo
    $app->get('/equipamentoModelo', function ($request, $response, $args)
    {
        return $this->view->render($response, 'GerenciarEquipamentoModeloView.php');
    });

    //equipamento
    $app->get('/equipamento', function ($request, $response, $args)
    {
        return $this->view->render($response, 'GerenciarEquipamentoView.php');
    });


    //equipamentos_movimentacoes
    $app->get('/equipamentoMovimentacao', function ($request, $response, $args)
    {
        return $this->view->render($response, 'GerenciarEquipamentoMovimentacaoView.php');
    });

    $app->get('/teste', function (Req $request, Resp $response, $args) use ($app)
    {
        return $this->view->render($response, 'teste.php');
    });

    //produtos
    $app->get('/produtos', function (Req $request, Resp $response, $args) use ($app)
    {
        return $this->view->render($response, 'GerenciarProdutoView.php');
    });

});
