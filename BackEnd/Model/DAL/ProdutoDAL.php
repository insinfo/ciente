<?php
/**
 * User: jose.neto
 * Date: 07/05/2018
 * Time: 13:36
 */

namespace Ciente\Model\DAL;

use Ciente\Util\DBLayer;
use Exception;
use Ciente\Model\VO\Produto;

class ProdutoDAL
{
    function __construct()
    {

    }

    public function selectAll($limit=10,$offset=0,$totalRecords)
    {
        $query = DBLayer::Connect()->table(Produto::TABLE_NAME);
        $totalRecords = $query->count();
        return $query->orderBy(Produto::KEY_ID, DBLayer::ORDER_DIRE_ASC)
            ->take($limit)->offset($offset)->get();

    }

    public function selectById($idProduto)
    {
        return DBLayer::Connect()->selectRaw('DISTINCT '.Produto::TABLE_NAME.'.*')
            ->where(Produto::KEY_ID,DBLayer::OPERATOR_EQUAL,$idProduto)
            ->orderBy(Produto::KEY_ID, DBLayer::ORDER_DIRE_ASC)->first();
    }

    public function insert($assocArrayData)
    {
        $id = DBLayer::Connect()
            ->table(Produto::TABLE_NAME)->insertGetId($assocArrayData);
        return $id;
    }

    public function update($asocArrayData)
    {
        return DBLayer::Connect()
            ->table(Produto::TABLE_NAME)
            ->update($asocArrayData);
    }

    public function delete($idProduto)
    {
        return DBLayer::Connect()
            ->table(Produto::TABLE_NAME)
            ->delete($idProduto);
    }

    public function find($idProduto)
    {
        return DBLayer::Connect()->table(Produto::TABLE_NAME)
            ->where(Produto::KEY_ID,DBLayer::OPERATOR_EQUAL,$idProduto)->first();

    }

    /**
     * Retorna um produto
     *
     * @param $idProduto
     * @return Produto
     */

    public function findOne($idProduto) {
        return $this->mount($this->find($idProduto));
    }

    /**
     * Monta e retorna um produto
     *
     * @param array $data
     * @return Produto
     */

    public function mount($data) {
        $produto = new Produto();
        $produto -> setAtivo($data['ativo'])
            ->setDescricao($data['descricao'])
            ->setIdProduto(array_key_exists('idProduto', $data)? $data['idProduto']:null)
            ->setnomeReduzido($data['nomeReduzido'])
            ->setOrigem($data['origem']);

        return $produto;
    }

    private static $instance = null;

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


}