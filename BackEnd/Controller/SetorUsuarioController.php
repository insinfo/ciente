<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 04/06/2018
 * Time: 17:02
 */

namespace Ciente\Controller;

use \Slim\Http\Request;
use \Slim\Http\Response;
use \Exception;
use Ciente\Util\DBLayer;
use Ciente\Util\Utils;
use Ciente\Util\StatusMessage;
use Ciente\Util\StatusCode;
use Ciente\Model\VO\SetorHistorico;
use Ciente\Model\VO\Setor;
use Ciente\Model\VO\Token;
use Ciente\Model\VO\SetorUsuario;


class SetorUsuarioController
{
    public static function getAll(Request $request, Response $response) {
        try {
            $param = $request->getParsedBody();
            //parametros que o DataTable envia
            $draw = isset($param['draw']) ? $param['draw'] : null;
            $limit = isset($param['length']) ? $param['length'] : null;
            $offset = isset($param['start']) ? $param['start'] : null;
            $search =  isset($param['search']) ? '%' . trim($param['search']) . '%' : null;
            $idPessoa = isset($param['idPessoa']) ? $param['idPessoa'] : null;

            DBLayer::Connect();
            $query = DBLayer::table(SetorUsuario::TABLE_NAME)
            ->select(SetorUsuario::ID_SETOR);
            if ($idPessoa != null) {
                $query->where(SetorUsuario::ID_PESSOA,"=",$idPessoa);
            }

            $totalRecords = $query->count();

            if ($limit != null && $offset != null) {
                $data = $query->limit($limit)->offset($offset)->get();
            } else {
                $data = $query->pluck('idSetor');
            }

            /*$resu = array();
            foreach ($data as $iten){
                array_push($resu,$iten['idSetor']);
            }*/

            $result['draw'] = $draw;
            $result['recordsFiltered'] = $totalRecords;
            $result['recordsTotal'] = $totalRecords;
            $result['data'] = $data;

        } catch (Exception $e) {
            return $response->withStatus(StatusCode::BAD_REQUEST)->withJson((['message' => StatusMessage::MENSAGEM_ERRO_PADRAO, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(StatusCode::SUCCESS)->withJson($result);
    }

    public static function updateSetores(Request $request, Response $response)
    {
        try
        {
            //apagar todos os setores e gravar os novos
            //$idOrganograma = $request->getAttribute('idOrganograma');
            //$dataInicio = $request->getAttribute('dataInicio');

            //print "$idOrganograma :: $dataInicio";

            $formData = $request->getParsedBody();
            if(isset($formData['idPessoa']))
            {
                DBLayer::Connect();

                //apagar os setores existentes
                DBLayer::table(SetorUsuario::TABLE_NAME)
                         ->where(SetorUsuario::ID_PESSOA, '=', $formData['idPessoa'])
                         ->delete();

                //grava os novos setores do usuario
                if(isset($formData['idSetores']) and count($formData['idSetores']))
                foreach($formData['idSetores'] as $setor)
                {
                    $reg[SetorUsuario::ID_PESSOA] = $formData['idPessoa'];
                    $reg[SetorUsuario::ID_SETOR] = $setor;
                    DBLayer::table(SetorUsuario::TABLE_NAME)
                             ->insert($reg);
                }
            }
        }
        catch (Exception $e)
        {
            return $response->withStatus(StatusCode::BAD_REQUEST)
                ->withJson(['message' => StatusMessage::MENSAGEM_ERRO_PADRAO, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
        }

        return $response->withStatus(StatusCode::SUCCESS)->withJson(StatusMessage::MENSAGEM_DE_SUCESSO_PADRAO);
    }
}