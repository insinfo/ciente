<?php


namespace Ciente\Model\DAL;

use Ciente\Model\VO\Pessoa;
use Ciente\Model\VO\Usuario;

class UsuarioDAL extends AbstractDAL
{

    private static $instance = null;

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Obtem um objeto do tipo Pessoa.
     *
     * @param  integer  $id
     * @return  \Ciente\Model\VO\Usuario
     */

    public function getById($id)
    {
        $data = $this->db()->table(Usuario::TABLE_NAME)
            ->where(Usuario::KEY_ID, $id)->first();


        return Usuario::create($data);
    }
}