<?php
/*********************************************************************************
Nome : contratoController.php
Descricao : Controller para as requisicoes e processamento dos dados dos Contrato
Projeto : CIENTE
Data de Criacao : 02/01/2018
Data das ultimas modificacoes : 11/01/2018
Versao : 2.0
Autor : José Amaro da Costa Neto (jcosta@riodasostras.rj.gov.br)
Ultimo Modificador : José Amaro da Costa Neto (jcosta@riodasostras.rj.gov.br)
**********************************************************************************/

namespace Ciente\Controller;

use \Slim\Http\Request as Req;
use \Slim\Http\Response as Resp;

use Ciente\Util\DBLayer;

use Ciente\Model\DAL\ContratoDAL;
use Ciente\Model\VO\Contrato;
use Ciente\Util\Utils;

use Exception;

class ContratoController
{
    /**************** TIPO DE EQUIPAMENTO ******************/
    //lista tipo de equipamento
    public static function getAll(Req $request, Resp $response)
    {
        try
        {
            $parametros = $request->getParsedBody();
            //parametros que o DataTable envia
            $draw = isset($paramametros['draw']) ? $parametros['draw'] : null ;
            $limit = isset($parametros['length'])? $parametros['length'] : null;
            $offset = isset($parametros['start'])?$parametros['start'] : null;
            $search = isset($parametros['search']) ? '%'.trim($parametros['search']) . '%' : '%%';
            $orderby = isset($parametros['ordering']) ? $parametros['ordering'] : null;


            $query = DBLayer::Connect()->table(Contrato::TABLE_NAME)
            ->selectRaw(Contrato::TABLE_NAME.".*, pessoas.nome")
            ->join('pessoas','pessoas.id','=',Contrato::ID_PESSOA_JURIDICA)
            ->where(function ($query) use ($request, $search)
            {
                //$query->orWhere(TipoEquipamento::KEY_ID, DBLayer::OPERATOR_ILIKE, $search);

                $query->orWhere('nome', DBLayer::OPERATOR_ILIKE, $search);
                $query->orWhere(Contrato::DESCRICAO, DBLayer::OPERATOR_ILIKE, $search);
                $query->orWhere(Contrato::OBSERVACAO, DBLayer::OPERATOR_ILIKE, $search);
                //$query->orWhere(Contrato::ATIVO, DBLayer::OPERATOR_ILIKE, $search);
            })
            ;

            $totalRecords = $query->count();

            //ordenacao
            if($orderby and count($orderby))
            foreach($orderby as $order)
            {
                $query->orderBy($order['columnKey'],$order['direction']);
            }
            else
                $query->orderBy("descricao");

            //$data = $query->orderBy(Contrato::TABLE_NAME.'.'.Contrato::KEY_ID, DBLayer::ORDER_DIRE_ASC)->take($limit)->skip($offset)->get();
            $data = $query->take($limit)->skip($offset)->get();

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
        }
        catch (Exception $e)
        {
            return $response->withStatus(400)->withJson((['message' => 'Ouve um erro', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(200)->withJson($result);

    }

    //lista pessoas do pmro_padrao
    public static function getContratosPessoas(Req $request, Resp $response)
    {
        try
        {
            $parametros = $request->getParsedBody();
            //parametros que o DataTable envia
            $draw = $parametros['draw'];
            $limit = $parametros['length'];
            $offset = $parametros['start'];
            $search = '%' . $parametros['search'] . '%';

            $query = DBLayer::Connect()->table("pmro_padrao.pessoas_juridicas")
            //->selectRaw("idPessoa, cnpj, nomeFantasia")
            //->join('pessoas','pessoas.id','=',Contrato::ID_PESSOA_JURIDICA)
            ->where(function ($query) use ($request, $search)
            {
                //$query->orWhere(TipoEquipamento::KEY_ID, DBLayer::OPERATOR_ILIKE, $search);

                $query->orWhere("nomeFantasia", DBLayer::OPERATOR_ILIKE, $search);
                $query->orWhere("cnpj", DBLayer::OPERATOR_ILIKE, $search);
                //$query->orWhere(Contrato::ATIVO, DBLayer::OPERATOR_ILIKE, $search);
            })
            ;
            $totalRecords = $query->count();
            $data = $query->orderBy("nomeFantasia", DBLayer::ORDER_DIRE_ASC)->take($limit)->skip($offset)->get();
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
        }
        catch (Exception $e)
        {
            return $response->withStatus(400)->withJson((['message' => 'Ouve um erro', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(200)->withJson($result);

    }

    public static function getContratosAtivos(Req $request, Resp $response)
    {
        try
        {
            $parametros = $request->getParsedBody();
            //parametros que o DataTable envia
            $draw = $parametros['draw'];
            $limit = $parametros['length'];
            $offset = $parametros['start'];
            $search = '%' . $parametros['search'] . '%';

            $query = DBLayer::Connect()->table(Contrato::TABLE_NAME)
            ->selectRaw(Contrato::TABLE_NAME.".*, pessoas.nome")
            ->join('pessoas','pessoas.id','=',Contrato::ID_PESSOA_JURIDICA)
            ->where(function ($query) use ($request, $search)
            {
                //$query->orWhere(TipoEquipamento::KEY_ID, DBLayer::OPERATOR_ILIKE, $search);

                $query->orWhere('nome', DBLayer::OPERATOR_ILIKE, $search);
                $query->orWhere(Contrato::DESCRICAO, DBLayer::OPERATOR_ILIKE, $search);
                $query->orWhere(Contrato::OBSERVACAO, DBLayer::OPERATOR_ILIKE, $search);
            })
            ->where(Contrato::ATIVO, DBLayer::OPERATOR_EQUAL, 'true');
            ;
            $totalRecords = $query->count();
            $data = $query->orderBy(Contrato::TABLE_NAME.'.'.Contrato::KEY_ID, DBLayer::ORDER_DIRE_ASC)->take($limit)->skip($offset)->get();
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
        }
        catch (Exception $e)
        {
            return $response->withStatus(400)->withJson((['message' => 'Ouve um erro', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(200)->withJson($result);

    }

    //cria ou atualiza tipo de equipamento
    public static function save(Req $request, Resp $response)
    {
        try
        {
            $id = $request->getAttribute('id'); //id do contrato
            $formData = Utils::filterArrayByArray($request->getParsedBody(), Contrato::TABLE_FIELDS);

            if ($id)
            {
                DBLayer::Connect()->table(Contrato::TABLE_NAME)->where(Contrato::KEY_ID,"=",$id)->update($formData);
            }
            else
            {
                DBLayer::Connect()->table(Contrato::TABLE_NAME)->insertGetId($formData);
            }

        }
        catch (Exception $e)
        {
            return $response->withStatus(400)->withJson((['message' => 'Ouve um erro', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(200)->withJson($request->getParsedBody());
    }

    //Deleta tipo de equipamento travez de um array de ids
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
                $contratoDAL = new ContratoDAL();
                if ($contratoDAL->find($id))
                {
                    if ($contratoDAL->delete($id))
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
            return $response->withStatus(400)->withJson(['message' => 'Houve um erro', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
        }
    }

    //obter tipo de equipamento por id usando DAL
    public static function get(Req $request, Resp $response)
    {
        try
        {
            $idTipoEquipamento = $request->getAttribute('idTipoEquipamento');
            $tipoEquipamentoDAL = new TipoEquipamentoDAL();
            $tipoEquipamento = $tipoEquipamentoDAL->find($idTipoEquipamento);
            return $response->withStatus(200)->withJson($tipoEquipamento);
        }
        catch (Exception $e)
        {
            return $response->withStatus(400)->withJson(['message' => 'Houve um erro', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
        }
    }

    //EXEMPLO: obter tipo de equipamento por id sem usar DAL
    public static function get2(Req $request, Resp $response)
    {
        try
        {
            $idTipoEquipamento = $request->getAttribute('idTipoEquipamento');
            $tipoEquipamento = DBLayer::Connect()->table(TipoEquipamento::TABLE_NAME)->where(TipoEquipamento::KEY_ID, DBLayer::OPERATOR_EQUAL, $idTipoEquipamento)->first();
            return $response->withStatus(200)->withJson($tipoEquipamento);
        }
        catch (Exception $e)
        {
            return $response->withStatus(400)->withJson(['message' => 'Houve um erro', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
        }
    }

    //EXEMPLO: Deleta tipo de equipamento travez de um array de ids sem usar DAL
    public static function delete2(Req $request, Resp $response)
    {
        try
        {
            $formData = $request->getParsedBody();
            $ids = $formData['ids'];
            $idsCount = count($ids);
            $itensDeletadosCount = 0;
            foreach ($ids as $id)
            {
                $tipoEquipamento = DBLayer::Connect()->table(TipoEquipamento::TABLE_NAME)->where(TipoEquipamento::KEY_ID, DBLayer::OPERATOR_EQUAL, $id)->first();

                if ($tipoEquipamento)
                {
                    if (DBLayer::Connect()->table(TipoEquipamento::TABLE_NAME)->delete($id))
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
            return $response->withStatus(400)->withJson(['message' => 'Houve um erro', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
        }
    }

}