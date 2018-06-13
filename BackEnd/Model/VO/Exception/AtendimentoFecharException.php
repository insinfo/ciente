<?php

namespace Ciente\Model\VO\Exception;

use Throwable;

class AtendimentoFecharException extends \Exception {

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}