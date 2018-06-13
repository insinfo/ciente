<?php

namespace Ciente\Util;

trait TraitFillFromArray
{
    public function fillFromArray($objectArray)
    {
        if ($objectArray != null) {
            foreach ($objectArray as $key => $val) {
                if (property_exists(__CLASS__, $key)) {
                    $this->$key = $val;
                }
            }
        }
    }
}