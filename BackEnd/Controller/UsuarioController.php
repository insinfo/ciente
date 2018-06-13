<?php

namespace Ciente\Controller;

use \Slim\Http\Request as Req;
use \Slim\Http\Response as Resp;
use \Exception;
use Ciente\Util\DBLayer;
use Ciente\Util\Utils;
use Ciente\Model\VO\Usuario;

class UsuarioController
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
            $query = DBLayer::Connect()->table(Usuario::TABLE_NAME)->where(function ($query) use ($request, $search)
                {
                    $query->orWhere(Usuario::KEY_ID, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Usuario::ID_SETOR, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Usuario::LOGIN, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Usuario::PERFIL, DBLayer::OPERATOR_ILIKE, $search);
                    $query->orWhere(Usuario::ATIVO, DBLayer::OPERATOR_ILIKE, $search);
                })
            ;
            $totalRecords = $query->count();
            $data = $query->orderBy(Usuario::KEY_ID, 'asc')->take($limit)->offset($offset)->get();
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

    public static function save(Req $request, Resp $response)
    {

        try
        {
            $idUsuarios = $request->getAttribute('idUsuarios');
            $formData = Utils::filterArrayByArray($request->getParsedBody(), Usuario::TABLE_FIELDS);

            if ($idUsuarios)
            {
                DBLayer::Connect()->table(Usuario::TABLE_NAME)->update($formData);
            }
            else
            {
                DBLayer::Connect()->table(Usuario::TABLE_NAME)->insertGetId($formData);
            }
        }
        catch (Exception $e)
        {
            return $response->withStatus(400)->withJson((['message' => 'Ouve um erro desconhecido.', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(200)->withJson((['message' => 'Salvo com sucesso']));
    }

    public static function get(Req $request, Resp $response)
    {

        try
        {
            $idUsuarios = $request->getAttribute('Usuarios');
            $usuarios = DBLayer::Connect()->table(Usuario::TABLE_NAME)->where(Usuario::KEY_ID, DBLayer::OPERATOR_EQUAL, $idUsuarios)->first();
            return $response->withStatus(200)->withJson($usuarios);
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
                $usuarios = DBLayer::Connect()->table(Usuario::TABLE_NAME)->where(Usuario::KEY_ID, DBLayer::OPERATOR_EQUAL, $id)->first();

                if ($usuarios)
                {
                    if (DBLayer::Connect()->table(Usuario::TABLE_NAME)->delete($id))
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

    public static function getBySetor(Req $request, Resp $response)
    {
        try
        {
            $idSetor = $request->getAttribute('idSetor');

            DBLayer::Connect();

            $query = DBLayer::table(DBLayer::raw('ciente.setores_usuarios a'))
                ->from(DBLayer::raw('ciente.setores_usuarios a'))
                ->select(DBLayer::raw('a.*, b.nome, b.imagem, d.nome as cargo, count(e.id) as "atendimentos"'))
                ->leftJoin(DBLayer::raw('pmro_padrao.pessoas b'),'a.idPessoa','=','b.id')
                ->leftJoin(DBLayer::raw('portal_rh.servidores c'),'b.id','=','c.idPessoa')
                ->leftJoin(DBLayer::raw('portal_rh.cargos d'),'c.idCargo','=','d.id')
                //->leftJoin(DBLayer::raw('ciente.atendimentos e'),'b.id','=','e.idUsuario', "AND", "")
                ->leftJoin(DBLayer::raw('ciente.atendimentos e'),function($join)
                {
                    $join->on('b.id',   '=', 'e.idUsuario');
                    $join->on('e.idSetor', '=', 'a.idSetor');
                    $join->on('e.status', '<', DBLayer::raw("'3'"));
                })
                ->where('a.idSetor',"=","$idSetor")
                ->groupBy('a.idSetor', 'a.idPessoa', 'b.imagem','b.nome', 'd.nome')
                ->orderBy('b.nome');

            $data = $query->get();

            $result['data'] = $data;

            /*
            SELECT a.*, b.nome, b.imagem, "idCargo", d.nome as cargo, count(e.id)
            FROM ciente.setores_usuarios a LEFT OUTER JOIN pmro_padrao.pessoas b ON (a."idPessoa" = b.id)
                               LEFT OUTER JOIN portal_rh.servidores c ON(b.id=c."idPessoa")
                               LEFT OUTER JOIN portal_rh.cargos d ON (c."idCargo"=d.id)
                               LEFT OUTER JOIN ciente.atendimentos e ON (b.id=e."idUsuario" AND e.status < 4 and e."idSetor"=a."idSetor")

            WHERE (a."idSetor"=4)

            GROUP BY a."idSetor", a."idPessoa", b.imagem, c."idCargo",b.nome, d.nome

            */
        }
        catch (Exception $e)
        {
            return $response->withStatus(400)->withJson(['message' => 'Ouve um erro desconhecido.', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
        }

        return $response->withStatus(200)->withJson($result);

    }

}