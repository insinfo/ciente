<?php


namespace Ciente\Model\DAL;

use Ciente\Model\VO\Atendimento;
use Ciente\Model\VO\Constants;
use Ciente\Model\VO\HistoricoAtendimento;
use Ciente\Model\VO\Pessoa;
use Ciente\Util\DBLayer;
use Ciente\Model\VO\Anexo;
use Ciente\Util\Utils;

class AnexoDAL extends AbstractDAL
{

    private static $instance = null;

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function save(Anexo $anexo) {
        $data = [
            Anexo::FIELD_KEY => $anexo->getId(),
            Anexo::FIELD_NOME => $anexo -> getFilename(),
            Anexo::FIELD_NOME_FISICO => $anexo -> getRealFilename(),
            Anexo::FIELD_EXTENSAO => $anexo -> getExtensao()
        ];

        if ($anexo -> isDeleted()) {
            $this->delete($anexo);
        } else {
            if ($anexo -> getId()) {
                // se não for possivel alterar, insere um novo
                if(!$this -> update($data)) {
                    $this -> insert($data);
                }
            }
        }

        return $anexo;
    }

    public function findAllByAtendimento ($atendimento_id) {
        $data =  $this->db()->table(Anexo::TABLE_NAME)
            -> selectRaw(Anexo::TABLE_NAME. '.*, pes.'. Pessoa::NOME_PESSOA . ' as "nomePessoa", hate."idAtendimento", pes."' . Pessoa::KEY_ID_PESSOA . '" as "idPessoa" ')
            -> leftJoin(DBLayer::raw(Utils::adAspas(HistoricoAtendimento::TABLE_NAME) . 'hate'),
                DBLayer::raw('hate.id'), '=', Anexo::FIELD_KEY)
            -> leftJoin(DBLayer::raw(Utils::adAspas(Pessoa::TABLE_NAME_PESSOA) . 'pes'),
                DBLayer::raw('pes.' . Pessoa::KEY_ID_PESSOA), '=', DBLayer::raw('hate.'. Utils::adAspas(HistoricoAtendimento::ID_USUARIO)))
            -> where('hate.'.HistoricoAtendimento::ID_ATENDIMENTO, '=', $atendimento_id)
            -> get();

        foreach ($data as &$d) {

            $filefull = Constants::CIENTE_STORAGE_PATH . '/' . $atendimento_id . '/' . $d['nomeFisico'];
            $file_size = @filesize($filefull);
            $d['fullFile'] = $filefull;
            $d['url'] = Constants::CIENTE_STORAGE_WEB_PATH . $atendimento_id . '/' . $d['nomeFisico'];
            $d['size'] = $file_size ? $file_size: -1;
        }

        return $data;
    }

    public function insert(array $data) {
        $this->db()->table(Anexo::TABLE_NAME)
            -> insert($data);
    }

    public function findOne($id) {
        $data = $this->db()->table(Anexo::TABLE_NAME)
            ->where(Anexo::FIELD_KEY, '=', $id)
            ->first();

        return $this->mount($data);
    }

    public function mount ($data) {
        $anexo = new Anexo();
        $anexo -> setId($data[Anexo::FIELD_KEY]);
        $anexo -> setFilename($data[Anexo::FIELD_NOME]);
        $anexo -> setRealFilename($data[Anexo::FIELD_NOME_FISICO]);
        $anexo -> setExtensao($data[Anexo::FIELD_EXTENSAO]);

        // get histórico $anexo
        $historico = AtendimentoHistoricoDAL::getInstance()->findOne($data[Anexo::FIELD_KEY]);
        $atendimento = AtendimentoDAL::getInstance()->findOne($historico->getIdAtendimento());

        $anexo -> setHistorico($historico);
        $anexo -> setAtendimento($atendimento);

        return $anexo;
    }

    public function delete (Anexo $anexo) {
        return $this->db()->table(Anexo::TABLE_NAME)
            -> where(Anexo::FIELD_KEY, '=', $anexo->getId())
            -> delete();
    }

    public function update (array $data) {
        return $this->db()->table(Anexo::TABLE_NAME)
            -> where(Anexo::FIELD_KEY, '=', $data[Anexo::FIELD_KEY])
            -> update($data);
    }

}