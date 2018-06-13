<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 23/02/2018
 * Time: 14:58
 */

namespace Ciente\Controller;

use Ciente\Model\VO\Organograma;
use Ciente\Model\VO\Solicitacao;
use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;
use \Exception;
use Ciente\Util\DBLayer;
use Ciente\Util\Utils;
use Ciente\Util\StatusMessage;
use Ciente\Util\StatusCode;
use Ciente\Model\VO\OrganogramaHistorico;

class OrganogramaController
{
    public static function save(Request $request, Response $response)
    {
        /*
         {
            "setores": {
                "idPai":1,
                "ativo": true
            },
            "setoresHistorico":{
                "dataInicio":"2018-02-02",
                "sigla":"SEMAD",
                "nome":"Secretaria de Administração Pública"
            }
        }
          */
        try
        {
            $permicao = $request->getParsedBody();
            $query = DBLayer::Connect()->table(Organograma::TABLE_NAME);


        }
        catch (Exception $e)
        {
            return $response->withStatus(StatusCode::BAD_REQUEST)->withJson((['message' => StatusMessage::MENSAGEM_ERRO_PADRAO, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(StatusCode::SUCCESS)->withJson(['message' => StatusMessage::MENSAGEM_DE_SUCESSO_PADRAO]);
    }


}