<?php

namespace Ciente\Model\DAL;

use Ciente\Util\DBLayer;
use Exception;
use Ciente\Model\VO\EquipamentoTipoSetor;

class EquipamentoTipoSetorDAL
{
    function __construct()
    {

    }

    public function selectAll($limit=10,$offset=0,$totalRecords)
    {
        $query = DBLayer::Connect()->table(EquipamentoTipoSetor::TABLE_NAME);
        $totalRecords = $query->count();
        return $query->orderBy(EquipamentoTipoSetor::ID_TIPO_EQUIPAMENTO, DBLayer::ORDER_DIRE_ASC)
            ->take($limit)->offset($offset)->get();

    }

    public function selectById($idEquipamentoTipo)
    {
        return DBLayer::Connect()->selectRaw('DISTINCT '.EquipamentoTipoSetor::TABLE_NAME.'.*')
            ->where(EquipamentoTipoSetor::ID_TIPO_EQUIPAMENTO,DBLayer::OPERATOR_EQUAL,$idEquipamentoTipo)
            ->orderBy(EquipamentoTipoSetor::ID_TIPO_EQUIPAMENTO, DBLayer::ORDER_DIRE_ASC)->first();
    }

    public function insert($assocArrayData)
    {
        $id = DBLayer::Connect()
            ->table(EquipamentoTipoSetor::TABLE_NAME)->insertGetId($assocArrayData);
        return $id;
    }

    public function update($assocArrayData)
    {
        return DBLayer::Connect()
            ->table(EquipamentoTipoSetor::TABLE_NAME)
            ->where(EquipamentoTipoSetor::ID_TIPO_EQUIPAMENTO, DBLayer::OPERATOR_EQUAL,$assocArrayData['idTipoEquipamento'])
            ->where(EquipamentoTipoSetor::ID_SETOR, DBLayer::OPERATOR_EQUAL,$assocArrayData['idSetor'])
            ->update($assocArrayData);
    }

    public function delete($idEquipamentoTipo, $idSetor)
    {
        return DBLayer::Connect()
            ->table(EquipamentoTipoSetor::TABLE_NAME)
            ->where(EquipamentoTipoSetor::ID_TIPO_EQUIPAMENTO, DBLayer::OPERATOR_EQUAL,$idEquipamentoTipo)
            ->where(EquipamentoTipoSetor::ID_SETOR, DBLayer::OPERATOR_EQUAL,$idSetor)
            ->delete();
    }

    public function find($idEquipamentoTipo)
    {
        return DBLayer::Connect()->table(EquipamentoTipoSetor::TABLE_NAME)
            ->where(EquipamentoTipoSetor::ID_TIPO_EQUIPAMENTO,DBLayer::OPERATOR_EQUAL,$idEquipamentoTipo)->first();

    }
}