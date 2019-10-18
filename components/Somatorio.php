<?php

namespace app\components;

/**
 * Esta classe foi criada para somar um campo ou um array de campor de um array de models
 */
class Somatorio {

    public static function getTotal($provider, $fieldName) {
        $total = 0;

        foreach ($provider as $item) {
            if (is_array($fieldName)) {
                foreach ($fieldName as $field) {
                    $total += $item[$field];
                }
            } else {
                $total += $item[$fieldName];
            }
        }

        return $total;
    }

}
