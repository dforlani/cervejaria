<?php

namespace app\components;

use app\models\Produto;

/**
 * Esta classe foi criada para somar um campo ou um array de campor de um array de models
 */
class Avisos {

    public static function getAvisos() {
        $avisos = '';
        $produtos = Produto::getProdutoPraVencer();
        foreach ($produtos as $produto){
            $avisos = $avisos .'<br>'.$produto->nome;
        }
        return $avisos;
    }

}
