<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 07/03/2018
 * Time: 16:12
 */

namespace Ciente\Controller;//Middleware;

use \Slim\Http\Request;

use \Slim\Http\Response;

use \Firebase\JWT\ExpiredException;
use Ciente\Util\JWTWrapper;
use \Exception;

use Ciente\Util\StatusCode;
use Ciente\Util\StatusMessage;

class AuthMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        try
        {
            //if ($request->hasHeader('Accept')) {
            $bearer = $request->getHeader('Authorization');

            if ($bearer)
            {
                list($token) = sscanf($bearer[0], 'Bearer %s');
                $isDecode = JWTWrapper::decode($token);
                $request = $request->withAttribute('jwt',$isDecode);
            }
            else
            {
                // nao foi possivel extrair token do header Authorization
                return $response->withStatus(200)->withJson(['message' => 'Acesso não Autorizado!', 'exception' => 'Header sem Token']);
            }
        }
        catch (ExpiredException $e)
        {  //  token espirou
            return $response->withStatus(StatusCode::UNAUTHORIZED)->withJson(['message' => 'Acesso não Autorizado!', 'exception' => $e->getMessage()]);
        }
        catch (Exception $e)
        {  // nao foi possivel decodificar o token jwt
            return $response->withStatus(StatusCode::UNAUTHORIZED)->withJson(['message' => 'Acesso não Autorizado!', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
        }

        return $next($request, $response);
    }
}
