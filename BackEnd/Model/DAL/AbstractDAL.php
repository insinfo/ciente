<?php

namespace Ciente\Model\DAL;

use Ciente\Util\DBLayer;

abstract class AbstractDAL {

    private static $db = null;

    public function db () {
        if (self::$db === null) {
            self::$db = DBLayer::connect();
        }
        return self::$db;
    }
}