<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 09/01/2018
 * Time: 18:48
 */

namespace Ciente\Model\DAL;

use Ciente\Model\VO\Servico;
use Ciente\Util\DBLayer;
use Ciente\Util\Utils;

class ServicoDAL extends AbstractDAL
{


    private static $instance = null;

    public static function getInstance () {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Retorna um determinado serviço
     *
     * @param $id
     * @return Servico
     */

    public function findOne ($id) {
        $data = $this->db()->table(Servico::TABLE_NAME)
            -> find($id);

        return $this->mount($data);
    }


    public function mount (array $data) {
        $servico = new Servico();
        $servico -> setId($data['id'])
            -> setIdTipoServico($data['idTipoServico'])
            -> setIdContrato($data['idContrato'])
            -> setCodigoReferencia($data['codigoReferencia'])
            -> setNome($data['nome'])
            -> setDescricao($data['descricao'])
            -> setPrazoAlta($data['prazoAlta'])
            -> setPrazoBaixa($data['prazoBaixa'])
            -> setPrazoUrgente($data['prazoUrgente'])
            -> setPrazoNormal($data['prazoNormal'])
            -> setInProduto($data['inProduto'])
            -> setAtivo($data['ativo'])
            -> setOrdem($data['ordem']);

        return $servico;
    }

}