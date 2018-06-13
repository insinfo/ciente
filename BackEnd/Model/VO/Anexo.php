<?php

namespace Ciente\Model\VO;

use Ciente\Model\VO\Exception\File\AnexoException;
use Ciente\Util\Exceptions\NotFoundException;
use Ciente\Util\TraitFillFromArray;
use Ciente\Util\Utils;
use PHPMailer\PHPMailer\Exception;
use function PHPSTORM_META\type;

class Anexo {

    use TraitFillFromArray;

    const TABLE_NAME = "anexos";
    const FIELD_KEY = "idAtendimentoHistorico";
    const FIELD_NOME = 'nome';
    const FIELD_EXTENSAO = 'extensao';
    const FIELD_NOME_FISICO = 'nomeFisico';

    /**
     * @var id Id do anexo, mesmo id utilizado de maneira 1 para 1 na tabela histórico
     */
    private $id;

    /**
     * @var string Caminho real utilizado para acesso ao arquivo
     */
    private $caminho;

    /**
     * @var $filename Nome do arquivo guardado no banco de dados
     */
    private $filename;

    /**
     * @var $realFilename Nome que do arquivo físico, guardada na pasta do servidor
     */
    private $realFilename;

    /**
     * Esta variável vai armazenar ou um array contendo as informações do arquivo enviado
     * normalmente a variável de ambientes $_FILES['key']. Ou então, uma string do com o conteúdo do arquivo
     * normalizado na base64.
     *
     * @var $content Conteúdo do arquivo
     */
    private $content;

    /**
     * Para cada anexo é obrigatório um item de histórico de atendimento.
     *
     * @var HistoricoAtendimento $historico
     */
    private $historico;

    /**
     * Estensão do arquivo
     *
     * @var String $extensao
     */
    private $extensao;

    /**
     * Atendimento ao qual o anexo está incluído, este atendimento é inserido automáticamente ao model quando
     * este é buscado via DAO, por que para se formar o caminho completo para o arquivo físico é necessário
     * que o ID do atendimento em questão, esteja carregado.
     *
     * @var Atendimento
     */
    private $atendimento;

    /**
     * Marcado como true quando um anexo é removido. Utilizado no salvamento do model pelo DAO
     *
     * @var bool
     */
    private $deleted = false;

    /**
     * Anexo constructor.
     * @param $content
     */
    public function __construct ($content = null) {
        $this->setContent($content);

        if (is_array($content)) {
            $this->setFilename($content['name']);

            // create a new filename
            $better_token = md5(uniqid(rand(), true));
            $extension = explode(".", $content['name']);

            if (is_array($extension)) {
                $extension = end($extension);
            }

            $newRealFilename = $better_token .'.'. $extension;
            $this->setRealFilename($newRealFilename);
        }
    }

    /**
     * Verifica se o anexo foi marcado como removido
     *
     * @return bool
     */
    public function isDeleted () {
        return $this->deleted;
    }

    /**
     * Remove o arquivo físico e marca o model como removido
     *
     * @return bool
     */
    public function remove () {
        // informa ao DAO que foi removido
        $this->deleted = true;

        $file = $this->getRealFullName();

        if (file_exists($file)) {
            if (@unlink($file) != true) {
                throw new \Exception("Não foi possível apagar o arquivo " . $file . " Error: " . error_get_last());
            };
        }
    }

    /**
     * Seta Atendimento
     *
     * @param Atendimento $atendimento
     */
    public function setAtendimento (Atendimento $atendimento) {
        $this->atendimento = $atendimento;
    }

    /**
     * Retorna o atendimento
     *
     * @return Atendimento
     */
    public function getAtendimento () {
        return $this->atendimento;
    }

    /**
     * @return HistoricoAtendimento
     */
    public function getHistorico () {
        return $this->historico;
    }

    /**
     * @param HistoricoAtendimento $historico
     * @return $this
     */
    public function setHistorico (HistoricoAtendimento $historico) {
        $this->historico = $historico;
        return $this;
    }

    /**
     * Informa se o arquivo é ou não válido
     *
     * @return bool
     */
    public function isValid () {
        return true;
    }

    /**
     * Realiza o upload do arquivo para o servidor
     */
    public function upload () {

        $content = $this->getContent();
        $caminho = $this->getCaminho();

        /* cria caminho se não existe */
        if (!is_dir($caminho)) {
            if (!mkdir($caminho)) {
                throw new \Exception("Não foi possível criar a pasta " . $caminho);
            }
        }

        if(gettype($content) == "array") {
            if (array_key_exists('tmp_name', $content)) {

                if (!move_uploaded_file($content['tmp_name'], $this->getRealFullName()) ){
                    throw new AnexoException("Não foi possível efetuar o upload do arquivo");
                }
            }
        } else if (gettype($content) == "string") {
            // todo implementar envio usando base64
        }

        return $this;
    }

    /**
     * Retorna a extensão do arquivo
     *
     * @return mixed
     */
    public function getExtensao () {
        if (!$this->extensao) {
            $data = explode('.', $this->getFilename());
            $this->extensao = end($data);
        }

        return $this->extensao;
    }

    /**
     * Define extensão do arquivo
     *
     * @param $extensao
     * @return $this
     */
    public function setExtensao ($extensao) {
        $this->extensao = $extensao;
        return $this;
    }

    /**
     * Retorna a url para o anexo
     *
     * @return String
     */
    public function getUrl () {
        return Constants::CIENTE_STORAGE_WEB_PATH . $this->getAtendimento() -> getId() . '/' . $this->getRealFilename();
    }

    /**
     * Retorna o nome do arquivo físico salvo
     *
     * @return mixed
     */
    public function getRealFilename () {
        return $this->realFilename;
    }

    /**
     * @param mixed $realFilename
     * @return self
     */
    public function setRealFilename ($realFilename) {
        $this->realFilename = $realFilename;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFilename () {
        return $this->filename;
    }

    /**
     * @param mixed $filename
     * @return self
     */
    public function setFilename ($filename) {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent () {
        return $this->content;
    }

    /**
     * @param mixed $content
     * @return self
     */
    public function setContent ($content) {
        $this->content = $content;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId () {

        if (!$this->id) {
            $this->id = $this->getHistorico() -> getId();
        }

        return $this->id;
    }

    /**
     * @param mixed $id
     * @return self
     */
    public function setId ($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCaminho () {
        if (!$this->getAtendimento()) {
            throw new \Exception("É necessário setar o atendimento para definir o caminho do arquivo");
        }

        if (!$this->caminho) {
            $this->caminho = Constants::CIENTE_STORAGE_PATH . '/' . $this->getAtendimento() -> getId();
        }
        return $this->caminho;
    }

    /**
     * @param mixed $caminho
     * @return self
     */
    public function setCaminho ($caminho) {
        if (!is_dir($caminho)) {
            throw new NotFoundException("Pasta $caminho não existe!");
        }

        $this->caminho = $caminho;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return self
     */
    public function setData ($data) {
        $this->data = $data;
        return $this;
    }

    /**
     * Traz o arquivo como um array
     *
     * @return array
     * @throws \Exception
     */
    public function toArray () {
        return [
            'id' => $this->getId(),
            'nome' => $this->getFilename(),
            'data' => $this->getHistorico()->getData(),
            'caminho' => $this->getCaminho(),
            'url' => $this->getUrl()
        ];
    }

    /**
     * Retorna o nome do arquivo físico com o caminho completo
     *
     * @return string
     */
    public function getRealFullName () {
        return $this->getCaminho() . '/' . $this->getRealFilename();
    }

    /**
     * Realiza o renomeamento do arquivo em questão
     *
     * @param $new_name Passa um novo nome para o arquivo
     * @return $this Retorna o próprio objeto
     * @throws NotFoundException Gera uma excessão caso o arquivo não seja encontrado
     * @throws \Exception Gera uma excessão caso o arquivo não seja renomeado
     */
    public function rename ($new_name) {
        $oldName = $this->getRealFullName();

        if (file_exists($oldName)) {
            $this->setRealFilename($new_name);

            if (!rename($oldName, $this->getRealFullName())) {
                throw new \Exception("Não foi possível renomear o arquivo");
            }
        } else {
            throw new NotFoundException("Arquivo não existe");
        }

        return $this;
    }
}

