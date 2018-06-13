<?php
/**
 * Created by PhpStorm.
 * User: jose.neto
 * Date: 16/01/2018
 * Time: 11:40
 */

namespace Ciente\Model\DAL;

use Ciente\Util\DBLayer;
use Exception;
use Ciente\Model\VO\ServicoTipo;

class ServicoTipoDAL
{
    function __construct()
    {

    }

    public function selectAll($limit=10,$offset=0,$totalRecords)
    {
        $query = DBLayer::Connect()->table(ServicoTipo::TABLE_NAME);
        $totalRecords = $query->count();
        return $query->orderBy(ServicoTipo::KEY_ID, DBLayer::ORDER_DIRE_ASC)
            ->take($limit)->offset($offset)->get();

    }

    public function selectById($id)
    {
        return DBLayer::Connect()->selectRaw(ServicoTipo::TABLE_NAME.'.*')
            ->where(ServicoTipo::KEY_ID,DBLayer::OPERATOR_EQUAL,$id)
            ->orderBy(ServicoTipo::KEY_ID, DBLayer::ORDER_DIRE_ASC)->first();
    }

    public function insert($asocArrayData)
    {
        $id = DBLayer::Connect()
            ->table(ServicoTipo::TABLE_NAME)->insertGetId($asocArrayData);
        return $id;
    }

    public function update($asocArrayData)
    {
        return DBLayer::Connect()
            ->table(ServicoTipo::TABLE_NAME)
            ->update($asocArrayData);
    }

    public function delete($id)
    {
        return DBLayer::Connect()
            ->table(ServicoTipo::TABLE_NAME)
            ->delete($id);
    }

    public function find($id)
    {
        return DBLayer::Connect()->table(ServicoTipo::TABLE_NAME)
            ->where(ServicoTipo::KEY_ID,DBLayer::OPERATOR_EQUAL,$id)->first();

    }
}