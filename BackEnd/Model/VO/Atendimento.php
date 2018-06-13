<?php

namespace Ciente\Model\VO;

use Ciente\Model\VO\Exception\Atendimento\AssumirException;
use Ciente\Model\VO\Exception\Atendimento\FecharException;
use Ciente\Model\VO\Exception\Atendimento\TransferirException;
use Ciente\Model\VO\Exception\Atendimento\ComentarException;
use Ciente\Model\VO\Exception\Atendimento\EncaminharException;
use Ciente\Util\Utils;
use Ciente\Model\DAL\PendenciaDAL;


class Atendimento
{
    const TABLE_NAME = "atendimentos";
    const KEY_ID = "id"; //integer
    const ID_SOLICITACAO = "idSolicitacao"; //integer
    const ID_SETOR = "idSetor"; //integer
    const ID_SERVICO = "idServico"; //integer
    const ID_USUARIO = "idUsuario"; //integer
    const ID_PRODUTO = 'idProduto';
    const DATA_ENCAMINHAMENTO = "dataEncaminhamento"; //timestamp with time zone
    const DATA_INICIO_ATENDIMENTO = "dataInicioAtendimento"; //timestamp with time zone
    const DATA_FIM_ATENDIMENTO = "dataFimAtendimento"; //timestamp with time zone
    const STATUS = "status"; //smallint

    const ALIAS = 'ate';

    const TABLE_FIELDS = [
        self::ID_SOLICITACAO,
        self::ID_SETOR,
        self::ID_SERVICO,
        self::ID_USUARIO,
        self::DATA_ENCAMINHAMENTO,
        self::DATA_INICIO_ATENDIMENTO,
        self::DATA_FIM_ATENDIMENTO,
        self::STATUS,
        self::ID_PRODUTO
    ];

    const TABLE_FIELD = [
        self::ID_SERVICO
    ];

    private $id;
    private $idSolicitacao;
    private $idSetor;
    private $idServico;
    private $idUsuario;
    private $idProduto;
    private $dataEncaminhamento;
    private $dataInicioAtendimento;
    private $dataFimAtendimento;
    private $status = 0;
    private $oldStatus = null;


    /**
     * STATUS
     *
     */
    const ABERTO = 0, EM_ANDAMENTO = 1, PENDENTE = 2, CONCLUIDO = 3, CANCELADO = 4, DUPLICADO = 5, SEM_SOLUCAO = 6;

    const LIST_STATUS = [
        "Aberto",
        "Em andamento",
        "Pendente",
        "Concluido",
        "Cancelado",
        "Duplicado",
        "Sem solução"
    ];

    /**
     * @var Historico de mensagems | Comentarios ou Açoes
     */
    private $historics = [];
    private $pendencia = null; // pendencia atual
    private $files = [];


    /**
     * Retorna lista de arquivos
     *
     * @return array
     */
    public function getAnexos() {
        return $this->files;
    }

    /**
     * Seta lista de arquivos
     *
     * @param array $files
     * @return self
     */
    public function setAnexos($files) {
        $this->files = $files;
        return $this;
    }


    public function addAnexo (Anexo $anexo) {
        array_push($this->files, $anexo);
        return $this;
    }

    /**
     * Adiciona um arquivo ao atendimento
     *
     * @param mixed $content
     * @param Pessoa $usuarioLogado
     * @return Anexo
     */

    public function criarAnexo($content, Pessoa $usuarioLogado) {

        $anexo = new Anexo($content);
        $anexo -> setAtendimento($this);

        /* cria mensagem */
        $message = UTILS::messageTemplate(HistoricoAtendimento::MESSAGE_ADD_ANEXO, [
            "usuarioId" => $usuarioLogado -> getId(),
            "usuario" => $usuarioLogado -> getFullName(),
            "link-to-file" => "#" . $anexo -> getRealFilename(),
            "file-name" => $anexo -> getFilename()
        ]);

        $this->acionar($message, $usuarioLogado);
        $anexo -> setHistorico($this->getUltimoHistorico());

        if ($anexo -> isValid()) {
            // verifica se o arquivo é válido
            try{
                // solicita que o upload seja realizado
                $anexo -> upload();
                $this->addAnexo($anexo);

            } catch (\Exception $e) {
                throw $e;
            }
        }

        return $anexo;
    }

    public function removeAnexo (Anexo $anexo, Pessoa $usuarioLogado) {
        // só quem pode remover o anexo é quem o criou
        if ($usuarioLogado -> getId() !== $anexo -> getHistorico() -> getIdUsuario()) {
            throw new \Exception("Este usuário não pode remover este anexo.");
        }

        // verifica se este anexo é realmente deste atendimento
        if ($anexo -> getAtendimento() -> getId() !== $this->getId()) {
            throw new \Exception('Este anexo não pertence a este atendimento');
        }

        /* cria mensagem de update para o historico do anexo*/
        $message = UTILS::messageTemplate(HistoricoAtendimento::MESSAGE_UPDATE_ON_REMOVED_ANEXO, [
            "usuarioId" => $usuarioLogado -> getId(),
            "usuario" => $usuarioLogado -> getFullName(),
            "file-name" => $anexo -> getFilename()
        ]);
        $historico_existente = $anexo -> getHistorico();
        $historico_existente->setComentario($message);

        // adiciona historico ao atendimento para ser atualizado
        $this->addHistorico($historico_existente);

        /* cria novo histórico avisando da remoção do arquivo */

        /* cria mensagem */
        $message = UTILS::messageTemplate(HistoricoAtendimento::MESSAGE_REMOVE_ANEXO, [
            "usuarioId" => $usuarioLogado -> getId(),
            "usuario" => $usuarioLogado -> getFullName(),
            "link-to-file" => "#" . $anexo -> getRealFilename(),
            "file-name" => $anexo -> getFilename()
        ]);

        $this->acionar($message, $usuarioLogado);

        // remove anexo real
        $anexo -> remove();

        $this->addAnexo($anexo);
    }

    /**
     * Retorna o último histórico adicionado ao atendimento.
     *
     * @return HistoricoAtendimento
     */
    public function getUltimoHistorico () {
        $historicos = $this->getHistoricos();
        return end($historicos);
    }


    /**
     * @param mixed $idProduct
     */
    public function setIdProduto($idProduto)
    {
        $this->idProduto = $idProduto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdProduto()
    {
        return $this->idProduto;
    }

    /**
     * Adiciona uma lista de historicos
     *
     * @param Historico $historics
     */
    public function setHistoricos(array $historics)
    {
        $this->historics = $historics;
        return $this;
    }

    /**
     * Retorna todos os historicos
     *
     * @return Historico
     */
    public function getHistoricos()
    {
        return $this->historics;
    }

    /**
     * Adicionar historico
     *
     * @param HistoricoAtendimento $historico
     * @return $this
     */
    public function addHistorico (HistoricoAtendimento $historico) {
        array_push($this->historics, $historico);
        return $this;
    }

    /**
     * Retorna dados do objeto no formato array
     *
     * @return array
     */
    public function toArray () {
        return [
          self::KEY_ID => $this->getId(),
          self::ID_SOLICITACAO => $this->getIdSolicitacao(),
          self::ID_SETOR => $this->getIdSetor(),
          self::ID_SERVICO => $this->getIdServico(),
          self::ID_USUARIO => $this->getIdUsuario(),
          self::ID_PRODUTO => $this->getIdProduto(),
          self::STATUS => $this->getStatus(),
          self::DATA_ENCAMINHAMENTO => $this->getDataEncaminhamento(),
          self::DATA_INICIO_ATENDIMENTO => $this->getDataInicioAtendimento(),
          self::DATA_FIM_ATENDIMENTO => $this->getDataFimAtendimento()
        ];
    }

    /**
     * Retorna o id
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Seta id
     *
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Retorna id da solicitaçao
     *
     * @return mixed
     */
    public function getIdSolicitacao()
    {
        return $this->idSolicitacao;
    }

    /**
     * Seta id da solicitaçao
     *
     * @param mixed $idSolicitacao
     */
    public function setIdSolicitacao($idSolicitacao)
    {
        $this->idSolicitacao = $idSolicitacao;
        return $this;
    }

    /**
     * Retorna id do setor
     *
     * @return mixed
     */
    public function getIdSetor()
    {
        return $this->idSetor;
    }

    /**
     * Seta o id do setor
     *
     * @param mixed $idSetor
     */
    public function setIdSetor($idSetor)
    {
        $this->idSetor = $idSetor;
        return $this;
    }

    /**
     * retorna o id do setor
     *
     * @return mixed
     */
    public function getIdServico()
    {
        return $this->idServico;
    }

    /**
     * @param mixed $idServico
     */
    public function setIdServico($idServico)
    {
        $this->idServico = $idServico;
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
    public function getDataEncaminhamento()
    {
        return $this->dataEncaminhamento;
    }

    /**
     * @param mixed $dataEncaminhamento
     */
    public function setDataEncaminhamento($dataEncaminhamento)
    {
        $this->dataEncaminhamento = $dataEncaminhamento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataInicioAtendimento()
    {
        return $this->dataInicioAtendimento;
    }

    /**
     * @param mixed $dataInicioAtendimento
     */
    public function setDataInicioAtendimento($dataInicioAtendimento)
    {
        $this->dataInicioAtendimento = $dataInicioAtendimento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataFimAtendimento()
    {
        return $this->dataFimAtendimento;
    }

    /**
     * @param mixed $dataFimAtendimento
     */
    public function setDataFimAtendimento($dataFimAtendimento)
    {
        $this->dataFimAtendimento = $dataFimAtendimento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->oldStatus = $this->status;
        $this->status = $status;
        return $this;
    }

    public function getOldStatus () {
        return $this->oldStatus;
    }

    /**
     * Regras de bloqueio de acesso geral para todos os metodos de açao
     *
     * @param Pessoa $pessoa
     * @param Message
     * @throws \Exception
     */

    private function _blockAccess(Pessoa $pessoa) {
        // verifica se a pessoa esta no mesmo setor do atendimento
        if (!$pessoa -> estaNoSetor($this->getIdSetor())) {
            throw new \Exception("Este usuario nao e do mesmo setor do atendimento.");
        }
    }

    /**
     * Retorna o nome do status
     *
     * @param null $status_id
     * @return mixed
     */
    public function getStatusName($status_id = null) {
        if (!$status_id) {
            $status_id = $this->getStatus();
        }
        return self::LIST_STATUS[$status_id];
    }

    /**
     * Criar historico de comentario
     *
     * @param $mensagem
     * @param Pessoa $usuarioLogado
     * @param null $data_agora
     * @throws \Exception
     */
    public function comentar ($mensagem, Pessoa $usuarioLogado, $data_agora = null, $tipo = null) {

        $this->_blockAccess($usuarioLogado);

        if (!$tipo) {
            $tipo = HistoricoAtendimento::TIPO_COMENTARIO;
        }

        if (!$data_agora) {
            $data_agora = Utils::getDateTimeNow("EN");
        }

        if (!$mensagem) {
            throw new ComentarException("O parametro mensagem e obrigatorio.");
        }

        if ($this->status >= self::CONCLUIDO && $tipo == HistoricoAtendimento::TIPO_COMENTARIO) {
            throw new ComentarException("Nao e possivel enviar um comentario para o atendimento com status " . $this->getStatusName());
        }

        // nao ha nenhum usuario responsavel, assumir a tarefa
        //if (!$this->foiIniciado()) {
            // se o usuario ja nao estiver definido, definir usuario como responsavel
            //$this->iniciarAtendimento($usuarioLogado);
        //}

        // so permite o comentario se o usuario for do mesmo setor.

        $historico_movimentacao = HistoricoAtendimento::create(
            $mensagem,
            $this,
            $usuarioLogado,
            $data_agora,
            $tipo);

        $this->addHistorico($historico_movimentacao);
    }

    /**
     * Criar historico de açao
     *
     * @param $mensagem
     * @param Pessoa $usuarioLogado
     * @param null $data_agora
     * @throws Exception
     */
    public function acionar ($mensagem, Pessoa $usuarioLogado, $data_agora = null) {
        $this->_blockAccess($usuarioLogado);

        $this->comentar($mensagem, $usuarioLogado, $data_agora, HistoricoAtendimento::TIPO_ACAO);
    }

    /**
     * Verifica se o atendimento foi ou nao iniciado
     *
     * @return bool
     */
    public function foiIniciado () {
        if ($this->getIdUsuario() && $this->getDataInicioAtendimento()) {
            return true;
        }
        return false;
    }

    /**
     * Inicia atendimento
     *
     * @param $data_agora
     * @param Pessoa $usuario
     * @return Atendimento
     */
    public function iniciarAtendimento (Pessoa $usuario, $data_agora = null) {
        $this->_blockAccess($usuario);

        if (!$this->foiIniciado()) { // responsavel is null
            if($this->getStatus() == self::ABERTO) {
                $this->setStatus(self::EM_ANDAMENTO);

                if ($data_agora === null) {
                    $data_agora = Utils::getDateTimeNow("EN");
                }

                $this->setDataInicioAtendimento($data_agora);
                if ($usuario !== null) {
                    $this->setIdUsuario($usuario -> getId());
                }
            }
        }
        return $this;
    }

    /**
     * Permite a um usuario assumir o atendimento
     *
     * @param Pessoa $usuario usuario que ira asumir o atendimento
     * @return Atendimento $this
     * @throws \Exception
     */

    public function assumir (Pessoa $usuario) {
        $this->_blockAccess($usuario);

        if (!$usuario -> getId()) {
            throw new AssumirException("Este nao e um usuario valido, por isso nao e possivel assumir o atendimento");
        }

        if ($this->getStatus() >= self::CONCLUIDO) {
            throw new AssumirException("Nao e possivel assumir um atendimento com o status " . $this->getStatusName());
        }

        if ($this->getIdUsuario() == $usuario -> getId()) {
            throw new AssumirException("Voce ja e o responsavel por este atendimento");
        }

        $mensagem = Utils::messageTemplate(HistoricoAtendimento::MESSAGE_ASSUMIR, [
            'usuarioId' => $usuario -> getId(),
            'usuario' => $usuario -> getFullName()
        ]);

        // criar açao informaçao que foi assumido o atendimento.
        $this->acionar($mensagem, $usuario);

        // caso atendimento nao tenha sido iniciado
        if (!$this->foiIniciado()) {
            $this->iniciarAtendimento($usuario);
        }

        $this->setIdUsuario($usuario->getId());

        return $this;
    }

    /**
     * Altera o status do atendimento
     *
     * @param $status
     * @return $this
     * @throws \Exception
     */
    public function alterarStatus ($status, Pessoa $usuarioLogado, $mensagem="", $data_agora = null) {

        /* aplica regras de bloqueio geral para açao alterar status */
        $this->_blockAccess($usuarioLogado);

        if (!array_key_exists($status, self::LIST_STATUS)) {
            throw new AlterarStatusException("Este status não está na lista de status aceitos para atendimentos");
        }

        // nao e possivel voltar o status para aberto depois de em andamento
        if ($this->getStatus() > 0 && $status == 0) {
            throw new AlterarStatusException("Nao e possivel modificar o status deste atendimento para " .
                $this->getStatusName($status));
        }

        // Se o status >= concluido, nao e possivel alterar o status do atendimento
        if ($this->getStatus() >= self::CONCLUIDO) {
            throw new AlterarStatusException("Nao e possivel alterar o status do atendimento para '" .
                $this->getStatusName($status) .
                "' pois seu status atual e '" . $this->getStatusName($status) . "'") ;
        }

        if ($status == $this->getStatus()) {
            throw new AlterarStatusException('Atendimento ja esta com o status ' . $this -> getStatusName());
        }

        /* So muda o status do atendimento para os casos abaixo, se adicionar uma mensagem */
        if ($status >= self::PENDENTE && $mensagem == "") {
            throw new AlterarStatusException("Para mudar o status para " . $this->getStatusName($status) . " e obrigatorio adicionar uma mensagem");
        }

        if (!$this->foiIniciado()) {
            // se o usuario ja nao estiver definido, definir usuario como responsavel
            $this->iniciarAtendimento($usuarioLogado);
        }

        if ($status >= self::CONCLUIDO) {
            $this->fechar($status);
        } else {
            $this->setStatus($status);
        }

        if (!$data_agora) {
            $data_agora = Utils::getDateTimeNow("EN");
        }

        $historico_comentario = null;
        if ($mensagem) {
            $historico_comentario = HistoricoAtendimento::create(
                $mensagem,
                $this,
                $usuarioLogado,
                $data_agora,
                HistoricoAtendimento::TIPO_COMENTARIO);

            $this->addHistorico($historico_comentario);
        }

        if ($this -> getOldStatus() != null &&
            $this -> getStatus() != $this -> getOldStatus() &&
            ($this -> getStatus() == self::PENDENTE || $this -> getOldStatus() == self::PENDENTE) ) {

            if ($this -> getStatus() == self::PENDENTE) {
                // sava informaçoes na tabela de pendente
                $this->pendencia = new Pendencia();
                $this->pendencia -> setDataInicio(Utils::getDateTimeNow("EN"))
                    ->setIdAtendimento($this->getId());

            } elseif($this -> getOldStatus() == self::PENDENTE) {
                // altera informaçoes na tabela de pendente para finalizar pendencia
                $this->pendencia = PendenciaDAL::getInstance()->findForAtendimentoIdAndIsOpen($this->getId())
                    -> setDataFim(Utils::getDateTimeNow("EN"));
            }

            if ($this->pendencia && $historico_comentario) {
                $this->pendencia -> setHistoricoAtendimento($historico_comentario);
            }
        }

        $historico_mensagem = Utils::messageTemplate(HistoricoAtendimento::MESSAGE_ALTERACAO_STATUS, [
            "usuario1" => $usuarioLogado -> getFullName(),
            "userid1" => $usuarioLogado -> getId(),
            "novostatus" => $this->getStatusName($status)
        ]);

        $historico_movimentacao = HistoricoAtendimento::create(
            $historico_mensagem,
            $this,
            $usuarioLogado,
            $data_agora,
            HistoricoAtendimento::TIPO_ACAO);

        $this->addHistorico($historico_movimentacao);

        return $this;
    }


    /**
     * Fechar atendimento
     *
     * @return $this
     */

    public function fechar ($novo_status = null) {

        if ($novo_status === null) {
            $novo_status = self::CONCLUIDO;
        }

        if ($this->status == self::PENDENTE) {
            throw new Exception\Atendimento\FecharException("O status do atendimento atual e pendente, modifique o status antes de fechar o atendimento");
        }

        if ($novo_status < self::CONCLUIDO) {
            throw new Exception\Atendimento\FecharException("Nao e permitido fechar o atendimento com status " . $this->getStatusName($novo_status));
        }

        $this->setStatus($novo_status);
        $this->setDataFimAtendimento(Utils::getDateTimeNow("EN"));

        return $this;
    }

    /**
     * Encaminha este atendimentoAtendimentoHistoricoDAL para um outro responsavel
     *
     * @param $usuario_id
     * @param $mensagem
     * @throws \Exception
     */
    public function encaminhar (Pessoa $usuarioLogado, Pessoa $destinatario, $mensagem = "", $data_agora = null) {

        $this->_blockAccess($usuarioLogado);

        if (!$destinatario) {
            throw new EncaminharException("O novo usuário responsável é obrigatório");
        }

        if ($destinatario -> getId() == $this->getIdUsuario()) {
            throw new EncaminharException("Este usuário já é o responsável pelo atendimento");
        }


        // gera data atual para ser utilizada em tudo o codigo
        if ($data_agora === null) {
            $data_agora = Utils::getDateTimeNow("EN");
        }

        // se o atendimento nao foi iniciado
        if (!$this->foiIniciado()) {
            $this->iniciarAtendimento($usuarioLogado, $data_agora);
        }

        $this->setIdUsuario($destinatario->getId());

        // cria historico com a mensagem da pessoa que transferiu
        $this->comentar($mensagem, $usuarioLogado, $data_agora);

        // cria log de modificaçao
        $mensagem = Utils::messageTemplate(HistoricoAtendimento::MESSAGE_ENCAMINHAMENTO, [
            "usuario1" => $usuarioLogado->getFullName(),
            "usuario2" => $destinatario -> getFullName(),
            "userid1" => $usuarioLogado -> getId(),
            "userid2" => $destinatario -> getId()
        ]);

        $this->acionar($mensagem, $usuarioLogado, $data_agora);
    }

    /**
     * Transferir atendimento de setor
     *
     * @param $setor
     * @return Atendimento Retorna um novo atendimento para ser salvo
     */
    public function transferir (Pessoa $usuario_logado, SetorHistorico $setor, Servico $servico, Produto $produto = null, $mensagem = "", $novo_status = null, $fechar_atual = true) {
        $this->_blockAccess($usuario_logado);

        if (!$this->foiIniciado()) {
            // se o usuario ja nao estiver definido, definir usuario como responsavel
            $this->iniciarAtendimento($usuario_logado);
        }

        // verifica se ja nao esta neste setor
        if ($this->idSetor === $setor -> getIdSetor()) {
            throw new Exception\Atendimento\TransferirException("Atendimento ja se encontra neste setor");
        }

        // enviar comentário do usuário
        if ($mensagem) {
            $this->comentar($mensagem, $usuario_logado);
        }

        if ($fechar_atual) {
            // fecha o atendimento atual
            $this->fechar($novo_status);
        }

        $mensagem = Utils::messageTemplate(HistoricoAtendimento::MESSAGE_TRANFERIR, [
            "usuarioId" => $usuario_logado -> getId(),
            "usuario" => $usuario_logado -> getFullName(),
            "setorId" => $setor -> getIdSetor(),
            "setor" => $setor -> getNome()
        ]);

        $this->acionar($mensagem, $usuario_logado);

        // cria novo atendimento
        $novo_atendimento = new self();
        $novo_atendimento -> setIdSolicitacao($this->getIdSolicitacao())    // mesmo id de solicitaçao
            -> setDataEncaminhamento(Utils::getDateTimeNow("EN"))   // nova data de encaminhamento
            -> setStatus(0)                                                 // seta status como aberto
            -> setIdServico($servico -> getId())
            -> setIdSetor($setor -> getIdSetor());                                       // seta novo setor

        if ($produto !== null) {
            $novo_atendimento -> setIdProduto($produto -> getId());
        }

        return $novo_atendimento;                                           // retorna novo atendimento
    }

    /**
     * Retorna pendencia se houver
     *
     * @return Pendencia|null
     */
    public function getPendencia () {
        return $this->pendencia;
    }

    /**
     * Create a new atendimento
     */
    public static function create($data) {

    }

}