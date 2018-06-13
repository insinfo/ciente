<?php
/**
 * Created by PhpStorm.
 * User: neto
 * Date: 12/03/2018
 * Time: 13:36
 */

namespace Ciente\Model\DAL;

use Ciente\Util\DBLayer;
use Exception;
use Ciente\Model\VO\EquipamentoModelo;

class EquipamentoModeloDAL
{
    function __construct()
    {

    }

    public function selectAll($limit=10,$offset=0,$totalRecords)
    {
        $query = DBLayer::Connect()->table(EquipamentoModelo::TABLE_NAME);
        $totalRecords = $query->count();
        return $query->orderBy(EquipamentoModelo::KEY_ID, DBLayer::ORDER_DIRE_ASC)
            ->take($limit)->offset($offset)->get();

    }

    public function selectById($idEquipamentoModelo)
    {
        return DBLayer::Connect()->selectRaw('DISTINCT '.EquipamentoModelo::TABLE_NAME.'.*')
            ->where(EquipamentoModelo::KEY_ID,DBLayer::OPERATOR_EQUAL,$idEquipamentoModelo)
            ->orderBy(EquipamentoModelo::KEY_ID, DBLayer::ORDER_DIRE_ASC)->first();
    }

    public function insert($asocArrayData)
    {
        $id = DBLayer::Connect()
            ->table(EquipamentoModelo::TABLE_NAME)->insertGetId($asocArrayData);
        return $id;
    }

    public function update($asocArrayData)
    {
        return DBLayer::Connect()
            ->table(EquipamentoModelo::TABLE_NAME)
            ->update($asocArrayData);
    }

    public function delete($idEquipamentoModelo)
    {
        return DBLayer::Connect()
            ->table(EquipamentoModelo::TABLE_NAME)
            ->delete($idEquipamentoModelo);
    }

    public function find($idEquipamentoModelo)
    {
        return DBLayer::Connect()->table(EquipamentoModelo::TABLE_NAME)
            ->where(EquipamentoModelo::KEY_ID,DBLayer::OPERATOR_EQUAL,$idEquipamentoModelo)->first();

    }
}