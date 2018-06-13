<?php

namespace Ciente\Model\VO;

class HistoricoAtendimento
{
    const TABLE_NAME = "atendimentos_historico";
    const KEY_ID = "id"; //integer
    const ID_ATENDIMENTO = "idAtendimento"; //integer
    const ID_USUARIO = "idUsuario"; //integer
    const DATA = "data"; //timestamp with time zone
    const COMENTARIO = "historico"; //text]
    const TIPO = "tipo";

    const TIPO_COMENTARIO = 'C';
    const TIPO_ACAO = 'A';
   
    const TABLE_FIELDS = [self::ID_ATENDIMENTO, self::ID_USUARIO, self::DATA, self::COMENTARIO, self::TIPO];

    public $id = null;
    public $idAtendimento = null;
    public $idUsuario = null;
    public $data = null;
    public $comentario = "";
    public $tipo = "A";

    /* MESSAGES */

    const MESSAGE_ENCAMINHAMENTO = "
        <span class='nome remetente'><a href='#' data-user='{userid1}'>{usuario1}</a></span>
        <span class='acao-mensagem'> encaminhou o atendimento para o </span> 
        <span class='nome destinatario'><a href='#' data-user='{userid2}'>{usuario2}</a></span>
    ";

    const MESSAGE_ALTERACAO_STATUS = "
        <span class='nome remetente'><a href='#' data-user='{userid1}'>{usuario1}</a></span>
        <span class='acao-mensagem'> mudou o status para </span>
        <span class='novo-status'>{novostatus}</span> 
    ";

    const MESSAGE_TRANFERIR = "
        <span class='nome remetente'><a href='#' data-user='{usuarioId}'>{usuario}</a></span>
        <span class='acao-mensagem'> transferiu o atendimento para o setor <a href='{setorId}'>{setor}</a> </span>
    ";

    const MESSAGE_ASSUMIR = "
        <span class='nome remetente'><a href='#' data-user='{usuarioId}'>{usuario}</a></span>
        <span class='acao-mensagem'> assumiu este atendimento </span>
    ";

    const MESSAGE_ADD_ANEXO = "
        <span class='nome remetente'><a href='#' data-user='{usuarioId}'>{usuario}</a></span>
        <span class='acao-mensagem'> Adicionou o anexo </span>
        <span class='anexo-name'><a href='{link-to-file}'>{file-name}</a></span>
    ";

    const MESSAGE_UPDATE_ON_REMOVED_ANEXO = "
        <span class='nome remetente'><a href='#' data-user='{usuarioId}'>{usuario}</a></span>
        <span class='acao-mensagem'> Adicionou o anexo </span>
        <span class='anexo-name'>{file-name}</span>
    ";

    const MESSAGE_REMOVE_ANEXO = "
        <span class='nome remetente'><a href='#' data-user='{usuarioId}'>{usuario}</a></span>
        <span class='acao-mensagem'> Removeu o anexo </span>
        <span class='anexo-name'>{file-name}</span>    
    ";

    /**
     * @param mixed $tipo
     * @return self
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
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
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdAtendimento()
    {
        return $this->idAtendimento;
    }

    /**
     * @param mixed $idAtendimento
     */
    public function setIdAtendimento($idAtendimento)
    {
        $this->idAtendimento = $idAtendimento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * @param mixed $idUsuario
     */
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * @param mixed $comentario
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;
        return $this;
    }

    public function toArray () {
        return [
            self::KEY_ID => $this->getId(),
            self::ID_ATENDIMENTO => $this->getIdAtendimento(),
            self::ID_USUARIO => $this->getIdUsuario(),
            self::DATA => $this->getData(),
            self::COMENTARIO => $this->getComentario(),
            self::TIPO => $this->getTipo()
        ];
    }

    /**
     * Cria um novo historico
     *
     * @param $comentario
     * @param $atendimento_id
     * @param $data_agora
     * @param $usuario_logado_id
     * @return mixed
     */
    public static function create ($comentario, Atendimento $atendimento, Pessoa $usuarioLogado, $data_agora = null, $tipo="A") {

        if (!$data_agora) {
            $data_agora = date("Y-m-d H:i:s");
        }

        if (!$usuarioLogado) {
            /* todo trocar por uma exception propria */
            throw new \Exception('E necessario a passagem do usuario logado');
        }

        if (!$comentario) {
            throw new \Exception("E obrigatorio a passagem do comentario");
        }

        $historico = new HistoricoAtendimento();
        $historico -> setComentario($comentario);
        $historico -> setData($data_agora);
        $historico -> setIdAtendimento($atendimento->getId());
        $historico -> setIdUsuario($usuarioLogado -> getId());
        $historico -> setTipo($tipo);

        return $historico;
    }


}