<?php

namespace Ciente\Model\VO;

use Ciente\Model\DAL\PendenciaDAL;

class Pendencia
{
    const TABLE_NAME = "pendencias";
    const KEY_ID = "id"; //integer
    const ID_ATENDIMENTO = "idAtendimento"; //integer
    const ID_HISTORICO_ATENDIMENTO = "idHistoricoAtendimento"; //integer
    const DATA_INICIO = "dataInicio"; //timestamp with time zone
    const DATA_FIM = "dataFim"; //timestamp with time zone

    const TABLE_FIELDS = [self::ID_ATENDIMENTO, self::ID_HISTORICO_ATENDIMENTO, self::DATA_INICIO, self::DATA_FIM];

    private $id;
    private $idAtendimento;
    private $idHistoricoAtendimento;
    private $historicoAtendimento;
    private $dataInicio;
    private $dataFim;


    /**
     * Retorna o historico do atendimento
     * @return HistoricoAtendimento mixed
     */
    public function getHistoricoAtendimento()
    {
        return $this->historicoAtendimento;
    }

    /**
     * Adicionar o objeto historico e retorna a pendencia
     *
     * @param mixed $historicoAtendimento
     * @return Pendencia
     */
    public function setHistoricoAtendimento(HistoricoAtendimento $historicoAtendimento)
    {
        $this->historicoAtendimento = $historicoAtendimento;
        return $this;
    }

    /**
     * Retorna o id do historico do atendimento relacionado a pendencia
     *
     * @return mixed
     */
    public function getIdHistoricoAtendimento()
    {
        if ($this->historicoAtendimento) {
            return $this->getHistoricoAtendimento() -> getId();
        }

        return $this->idHistoricoAtendimento;
    }

    /**
     * Adiciona um novo historico de atendimento
     *
     * @param mixed $idHistoricoAtendimento
     * @return Pendencia
     */
    public function setIdHistoricoAtendimento($idHistoricoAtendimento)
    {
        if ($this->historicoAtendimento) {
            $this->getHistoricoAtendimento()->setId($idHistoricoAtendimento);
        }

        $this->idHistoricoAtendimento = $idHistoricoAtendimento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId () {
        return $this->id;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId ($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdAtendimento () {
        return $this->idAtendimento;
    }

    /**
     * @param $idAtendimento
     * @return $this
     */
    public function setIdAtendimento ($idAtendimento) {
        $this->idAtendimento = $idAtendimento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataInicio () {
        return $this->dataInicio;
    }

    /**
     * @param $dataInicio
     * @return $this
     */
    public function setDataInicio ($dataInicio) {
        $this->dataInicio = $dataInicio;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataFim () {
        return $this->dataFim;
    }

    /**
     * @param $dataFim
     * @return $this
     */
    public function setDataFim ($dataFim) {
        $this->dataFim = $dataFim;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray () {
        $data = [
            self::KEY_ID => $this->getId(),
            self::ID_ATENDIMENTO => $this->getIdAtendimento(),
            self::DATA_FIM => $this -> getDataFim(),
            self::DATA_INICIO => $this -> getDataInicio(),
            self::ID_HISTORICO_ATENDIMENTO => $this->getIdHistoricoAtendimento()
        ];

        return $data;
    }


}