<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 06/04/2018
 * Time: 14:49
 */

namespace Ciente\Model\VO;

use Ciente\Model\DAL\SetorHistoricoDAL;
use Ciente\Model\DAL\UsuarioDAL;
use PHPMailer\PHPMailer\Exception;

class Pessoa
{
    const SCHEMA = "pmro_padrao";
    const TABLE_NAME_PESSOA = "pessoas";
    const KEY_ID_PESSOA = "id";
    const CGM_PESSOA = "cgm";
    const NOME_PESSOA = "nome";
    const EMAIL_PRINCIPAL = "emailPrincipal";
    const EMAIL_ADICIONAL = "emailAdicional";
    const TIPO_PESSOA = "tipo";
    const DATA_INCLUSAO = "dataInclusao";
    const DATA_ALTERACAO = "dataAlteracao";
    const IMAGEM = "imagem";

    const TABLE_FIELDS = [
        self::CGM_PESSOA,
        self::NOME_PESSOA,
        self::EMAIL_PRINCIPAL,
        self::EMAIL_ADICIONAL,
        self::TIPO_PESSOA,
        self::DATA_INCLUSAO,
        self::DATA_ALTERACAO
    ];

    const DISPLAY_NAMES =
        [
            'id' => 'ID',
            'cgm' => 'CGM',
            'nome' => 'Nome',
            'emailPrincipal' => 'E-mail Principal',
            'emailAdicional' => 'E-mail Adicional',
            'tipo' => 'Tipo',
            'dataInclusao' => 'Data de Inclusão',
            'dataAlteracao' => 'Data de Alteracão'
        ];

    protected $id;
    protected $cgm;
    protected $nome;
    protected $emailPrincipal;
    protected $emailAdicional;
    protected $tipo;
    protected $imagem;
    protected $dataInclusao;
    protected $dataAlteracao;


    function __construct(array $data = array())
    {
        foreach ($data as $key => $val) {
            if (property_exists(__CLASS__, $key)) {
                $this->$key = $val;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getImagem()
    {
        return $this->imagem;
    }

    /**
     * @param mixed $imagem
     */
    public function setImagem($imagem)
    {
        $this->imagem = $imagem;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataInclusao()
    {
        return $this->dataInclusao;
    }

    /**
     * @param mixed $dataInclusao
     */
    public function setDataInclusao($dataInclusao)
    {
        $this->dataInclusao = $dataInclusao;
    }

    /**
     * @return mixed
     */
    public function getDataAlteracao()
    {
        return $this->dataAlteracao;
    }

    /**
     * @param mixed $dataAlteracao
     */
    public function setDataAlteracao($dataAlteracao)
    {
        $this->dataAlteracao = $dataAlteracao;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCgm()
    {
        return $this->cgm;
    }

    /**
     * @param mixed $cgm
     */
    public function setCgm($cgm)
    {
        $this->cgm = $cgm;
    }

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getFirstName () {
        if ($this->nome) {
            return explode(' ', $this->nome)[0];
        }
    }

    public function getLastName () {
        if ($this->nome) {
            $nomes = explode(' ', $this->nome);
            $size = count($nomes);

            return $nomes[$size - 1];
        }
    }

    public function getFullName () {
        return $this->getFirstName() . " " . $this->getLastName();
    }

    /**
     * @return mixed
     */
    public function getEmailPrincipal()
    {
        return $this->emailPrincipal;
    }

    /**
     * @param mixed $emailPrincipal
     */
    public function setEmailPrincipal($emailPrincipal)
    {
        $this->emailPrincipal = $emailPrincipal;
    }

    /**
     * @return mixed
     */
    public function getEmailAdicional()
    {
        return $this->emailAdicional;
    }

    /**
     * @param mixed $emailAdicional
     */
    public function setEmailAdicional($emailAdicional)
    {
        $this->emailAdicional = $emailAdicional;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    public static function getClassName()
    {
        return get_called_class();
    }

    /**
     * Retorna setores onde uma pessoa esta incluida
     *
     * @return array
     * @throws \Exception
     */

    public function getSetoresHistoricos () {
        if ($this->getId()) {
            return SetorHistoricoDAL::getInstance() -> getPorPessoaId($this->getId());
        } else {
            throw new \Exception("Id da pessoa nao esta presente");
        }
    }

    /**
     * Verifica se pessoa esta no setor passado
     *
     * @param int $setor
     */
    public function estaNoSetor($_setor) {
        $setores = $this->getSetoresHistoricos();

        foreach($setores as $setor) {
            if ($setor -> getIdSetor() == $_setor) {
                return true;
            }
        }

        return false;
    }

    public function getPerfis () {

    }

}