<?php

namespace app\components;

/**
 * Esta classe foi criada para burlar um erro de conversão de datas do Yii2
 */
class Somatorio {

    public static function getTotal($provider, $fieldName) {
        $total = 0;

        foreach ($provider as $item) {
            $total += $item[$fieldName];
        }

        return $total;
    }

}
