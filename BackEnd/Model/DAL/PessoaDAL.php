<?php


namespace Ciente\Model\DAL;

use Ciente\Util\DBLayer;
use Ciente\Util\Utils;

use Ciente\Model\VO\Pessoa;

class PessoaDAL extends AbstractDAL
{

    private static $instance = null;

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new PessoaDAL();
        }

        return self::$instance;
    }

    /**
     * Obtem um objeto do tipo Pessoa.
     *
     * @param  integer  $id
     * @return  \Ciente\Model\VO\Pessoa
     */
    public function getById($id)
    {
        $data = $this->db()->table(Pessoa::TABLE_NAME_PESSOA)
            ->where(Pessoa::KEY_ID_PESSOA, $id)->first();

        if (!$data['id']) {
            throw new \Exception("Pessoa com id ". $id . " nao encontrada");
        }

        return new Pessoa($data);
    }
}