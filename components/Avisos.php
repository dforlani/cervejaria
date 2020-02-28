<?php

namespace app\components;

use app\models\Cerveja;
use app\models\Produto;
use Yii;
use yii\helpers\Url;

/**
 * Esta classe foi criada para somar um campo ou um array de campor de um array de models
 */
class Avisos {
    
    public static $KEY = 'cache_avisos';

    public static function getAvisos() {
        //Cervejas pra vencer
        $produtos = Cerveja::getProdutosPraVencer();
        $avisos = "";
        if (!empty($produtos)) {
            $avisos = "<b>Cervejas pra Vencer em 15 dias</b><br>";
            foreach ($produtos as $aux) {
                $avisos .= '<a href="' . Avisos::criar_link("produto/update-cerveja", "id", $aux->pk_produto) . '">' . $aux->nome . '</a> (' . Yii::$app->formatter->asDate($aux->dt_vencimento) . ')<br>';
            }
        }
        
        //Cervejas vencidas
        $produtos = Cerveja::getProdutosVencidos();
      
        if (!empty($produtos)) {
            $avisos .= "<br><b>Cervejas Vencidas</b><br>";
            foreach ($produtos as $aux) {
                $avisos .= '<a href="' . Avisos::criar_link("produto/update-cerveja", "id", $aux->pk_produto) . '">' . $aux->nome . '</a> (' . Yii::$app->formatter->asDate($aux->dt_vencimento) . ')<br>';
            }
        }
        
         //Produtos pra vencer
        $produtos = Produto::getProdutosPraVencer();
    
        if (!empty($produtos)) {
            $avisos .= "<br><b>Produtos pra Vencer em 15 dias</b><br>";
            foreach ($produtos as $aux) {
                $avisos .= '<a href="' . Avisos::criar_link("produto/update-cerveja", "id", $aux->pk_produto) . '">' . $aux->nome . '</a> (' . Yii::$app->formatter->asDate($aux->dt_vencimento) . ')<br>';
            }
        }
        
        //Produtos vencidos
        $produtos = Produto::getProdutosVencidos();
      
        if (!empty($produtos)) {
            $avisos .= "<br><b>Produtos Vencidos</b><br>";
            foreach ($produtos as $aux) {
                $avisos .= '<a href="' . Avisos::criar_link("produto/update-cerveja", "id", $aux->pk_produto) . '">' . $aux->nome . '</a> (' . Yii::$app->formatter->asDate($aux->dt_vencimento) . ')<br>';
            }
        }
        
        

        return $avisos;
    }

    public static function criar_link($url_base, $parametro, $cod) {
        return Url::to([$url_base, $parametro => $cod]);
    }

}
