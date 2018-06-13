<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 22/12/2017
 * Time: 13:36
 */

namespace Ciente\Model\DAL;

use Ciente\Util\DBLayer;
use Exception;
use Ciente\Model\VO\EquipamentoTipo;

class EquipamentoTipoDAL
{
    function __construct()
    {

    }

    public function selectAll($limit=10,$offset=0,$totalRecords)
    {
        $query = DBLayer::Connect()->table(EquipamentoTipo::TABLE_NAME);
        $totalRecords = $query->count();
        return $query->orderBy(EquipamentoTipo::KEY_ID, DBLayer::ORDER_DIRE_ASC)
            ->take($limit)->offset($offset)->get();

    }

    public function selectById($idEquipamentoTipo)
    {
        return DBLayer::Connect()->selectRaw('DISTINCT '.EquipamentoTipo::TABLE_NAME.'.*')
            ->where(EquipamentoTipo::KEY_ID,DBLayer::OPERATOR_EQUAL,$idEquipamentoTipo)
            ->orderBy(EquipamentoTipo::KEY_ID, DBLayer::ORDER_DIRE_ASC)->first();
    }

    public function insert($asocArrayData)
    {
        $id = DBLayer::Connect()
            ->table(EquipamentoTipo::TABLE_NAME)->insertGetId($asocArrayData);
        return $id;
    }

    public function update($asocArrayData)
    {
        return DBLayer::Connect()
            ->table(EquipamentoTipo::TABLE_NAME)
            ->update($asocArrayData);
    }

    public function delete($idEquipamentoTipo)
    {
        return DBLayer::Connect()
            ->table(EquipamentoTipo::TABLE_NAME)
            ->delete($idEquipamentoTipo);
    }

    public function find($idEquipamentoTipo)
    {
        return DBLayer::Connect()->table(EquipamentoTipo::TABLE_NAME)
            ->where(EquipamentoTipo::KEY_ID,DBLayer::OPERATOR_EQUAL,$idEquipamentoTipo)->first();

    }
}