<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 28/05/2018
 * Time: 17:41
 */

namespace Ciente\Model\VO;

class ViewSolicitacao
{
    const TABLE_NAME = "vw_solicitacoes";
    const KEY_ID = "id"; //integer
    const ANO = "ano"; //integer
    const NUMERO = "numero"; //bigint
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
    const TOTAL_ATENDIMENTOS = "totalAtendimentos";
    const MIN_STATUS = "minStatus";
    const SIGLA_ORGANOGRAMA = "siglaOrganograma";
    const NOME_ORGANOGRAMA = "nomeOrganograma";
    const NOME_SOLICITANTE = "nomeSolicitante";
    const CPF_SOLICITANTE = "cpfSolicitante";
    const NOME_SETOR_RESPONSAVEL = "nomeSetorResponsavel";
    const SIGLA_SETOR_RESPONSAVEL = "siglaSetorResponsavel";
    const ID_SETOR = "idSetor";

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
        self::PRIORIDADE,
        self::NOME_SETOR_RESPONSAVEL,
        self::SIGLA_SETOR_RESPONSAVEL,
        self::ID_SETOR
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
}