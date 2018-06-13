<?php
/**
 * ARQUIVO DE CONFIGURAÇÃO DE ROTAS
 **/

//ini_set('eaccelerator.enable', 0);


require_once '../start.php';

use \Slim\Http\Request as Req;
use \Slim\Http\Response as Resp;

//use Psr\Http\Message\ServerRequestInterface as Req;
//use Psr\Http\Message\ResponseInterface as Resp;

use \Slim\App;

function echoResponse($response, $jsonContent, $statusCode)
{
    //return $response->withStatus($statusCode)->withJson($content);
    /*return $response->withStatus($statusCode)
        ->write($jsonContent)
        ->withHeader('Content-type', 'application/json');*/

    $fh = fopen('php://memory', 'rw');
    $stream = new Slim\Http\Stream($fh);
    $stream->write($jsonContent);

    return $response->withStatus($statusCode)->withBody($stream)->withHeader('Content-type', 'application/json');
}

//instancia o slim
$app = new App;

// obtem um container
$container = $app->getContainer();

// Registra componente no container para abilitar o html render
$container['view'] = function ($container)
{
    $view = new \Slim\Views\Twig('/var/www/html/ciente/FrontEnd/View', ['cache' => false]);
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

$app->options('/{routes:.+}', function (Req $request,  Resp $response, $args) {
    return $response->withStatus(200);
});
$app->add(function ($req, $res, $next) {

    $response = $next($req, $res);
    $origin = $req->getHeader('Origin') ? $req->getHeader('Origin') : 'http://192.168.133.12';


    /*
        todo Adicionar headers em lugar mais apropriado para evitar que o navegador guarde cache
    */

    header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', FALSE);
    header('Pragma: no-cache');



    return $response
        ->withHeader('Access-Control-Allow-Credentials', 'true')
        ->withHeader('Access-Control-Allow-Origin', $origin)
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');


});



// ROTAS DE WEBPAGES
require_once '../BackEnd/Routes/web.php';

// ROTAS DE WEBSERVICE REST
require_once '../BackEnd/Routes/webservice.php';

$app->run();