<?php

namespace Ciente\Model\VO;

class Solicitacao
{
    const TABLE_NAME = "solicitacoes";
    const KEY_ID = "id"; //integer
    const ANO = "ano"; //integer
    const NUMERO = "numero"; //bigint
//    const ID_SETOR = "idSetor"; //integer
    const ID_ORGANOGRAMA = 'idOrganograma';
    const ID_SOLICITANTE = "idSolicitante"; //integer
    const ID_SOLICITANTESEC = "idSolicitantesec"; //integer
    const ID_EQUIPAMENTO = "idEquipamento"; //integer
    const ID_OPERADOR = "idOperador"; //integer
    const DESCRICAO = "descricao"; //text
    const OBSERVACAO = "observacao"; //text
    const DATA_ABERTURA = "dataAbertura"; //timestamp with time zone
    const DATA_FECHAMENTO = "dataFechamento"; //timestamp with time zone
    const PRIORIDADE = "prioridade"; //smallint
    const STATUS = "status"; //smallint

    const ABERTO = 0, EM_ANADAMENTO = 1, PENDENTE = 2, CONCLUIDO = 3, CANCELADO = 4, DUPLICADO = 5, SEM_SOLUCAO = 6;

    const LIST_STATUS = [
        "Aberto",
        "Em andamento",
        "Pendente",
        "Concluido",
        "Cancelado",
        "Duplicado",
        "Sem solução"
    ];

    const LIST_PRIORIDADES = [
        "Urgente",
        "Alta",
        "Normal",
        "Baixa"
    ];

    const TABLE_FIELDS = [
        self::ANO,
        self::NUMERO,
        self::ID_ORGANOGRAMA,
        self::ID_SOLICITANTE,
        self::ID_SOLICITANTESEC,
        self::ID_EQUIPAMENTO,
        self::ID_OPERADOR,
        self::DESCRICAO,
        self::OBSERVACAO,
        self::DATA_ABERTURA,
        self::DATA_FECHAMENTO,
        self::PRIORIDADE
    ];

    const TABLE_FILTER = [
        self::ID_SOLICITANTE,
        self::ID_ORGANOGRAMA,
        'idServico'
    ];

    public $id;
    public $ano;
    public $numero;
    public $idSetor;
    public $idSolicitante;
    public $idSolicitantesec;
    public $idEquipamento;
    public $idUsuario;
    public $descricao;
    public $observacao;
    public $dataAbertura;
    public $dataFechamento;
    public $prioridade;

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
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * @param mixed $ano
     */
    public function setAno($ano)
    {
        $this->ano = $ano;
    }

    /**
     * @return mixed
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    /**
     * @return mixed
     */
    public function getIdSetor()
    {
        return $this->idSetor;
    }

    /**
     * @param mixed $idSetor
     */
    public function setIdSetor($idSetor)
    {
        $this->idSetor = $idSetor;
    }

    /**
     * @return mixed
     */
    public function getIdSolicitante()
    {
        return $this->idSolicitante;
    }

    /**
     * @param mixed $idSolicitante
     */
    public function setIdSolicitante($idSolicitante)
    {
        $this->idSolicitante = $idSolicitante;
    }

    /**
     * @return mixed
     */
    public function getIdSolicitantesec()
    {
        return $this->idSolicitantesec;
    }

    /**
     * @param mixed $idSolicitantesec
     */
    public function setIdSolicitantesec($idSolicitantesec)
    {
        $this->idSolicitantesec = $idSolicitantesec;
    }

    /**
     * @return mixed
     */
    public function getIdEquipamento()
    {
        return $this->idEquipamento;
    }

    /**
     * @param mixed $idEquipamento
     */
    public function setIdEquipamento($idEquipamento)
    {
        $this->idEquipamento = $idEquipamento;
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
    }

    /**
     * @return mixed
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param mixed $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * @return mixed
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * @param mixed $observacao
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
    }

    /**
     * @return mixed
     */
    public function getDataAbertura()
    {
        return $this->dataAbertura;
    }

    /**
     * @param mixed $dataAbertura
     */
    public function setDataAbertura($dataAbertura)
    {
        $this->dataAbertura = $dataAbertura;
    }

    /**
     * @return mixed
     */
    public function getDataFechamento()
    {
        return $this->dataFechamento;
    }

    /**
     * @param mixed $dataFechamento
     */
    public function setDataFechamento($dataFechamento)
    {
        $this->dataFechamento = $dataFechamento;
    }

    /**
     * @return mixed
     */
    public function getPrioridade()
    {
        return $this->prioridade;
    }

    /**
     * @param mixed $prioridade
     */
    public function setPrioridade($prioridade)
    {
        $this->prioridade = $prioridade;
    }


}