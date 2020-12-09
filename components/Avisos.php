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
        $avisos = "";
        //Cervejas pra vencer
        $produtos = Cerveja::getProdutosPraVencer();
        $avisos .= Avisos::criaAvisosVencimento(Cerveja::$URL, $produtos, 'Cervejas pra Vencer em 15 dias');


        //Cervejas vencidas
        $produtos = Cerveja::getProdutosVencidos();
        $avisos .= Avisos::criaAvisosVencimento(Cerveja::$URL, $produtos, 'Cervejas Vencidas');


        //Produtos pra vencer
        $produtos = Produto::getProdutosPraVencer();
        $avisos .= Avisos::criaAvisosVencimento(Produto::$URL, $produtos, 'Produtos pra Vencer em 15 dias');


        //Produtos vencidos
        $produtos = Produto::getProdutosVencidos();
        $avisos .= Avisos::criaAvisosVencimento(Produto::$URL, $produtos, 'Produtos Vencidos');


        //Cervejas com promoção ativa
        $produtos = Cerveja::getProdutosComPromocaoAtiva();
        $avisos .= Avisos::criaAvisosPromocao(Cerveja::$URL, $produtos, 'Cervejas com Promoção Ativa');


        //Produtos com promoção ativa
        $produtos = Produto::getProdutosComPromocaoAtiva();
        $avisos .= Avisos::criaAvisosPromocao(Produto::$URL,$produtos, 'Produtos com Promoção Ativa');



        return $avisos;
    }

     public static function criaAvisosVencimento($url, $produtos, $titulo) {
        $avisos = "";
        if (!empty($produtos)) {
            $avisos .= "<br><b>$titulo</b><br>";
            foreach ($produtos as $aux) {
                $avisos .= '<a href="' . Avisos::criar_link($url, "id", $aux->pk_produto) . '">' . \yii\helpers\Html::encode($aux->nome) . '</a> (' . Yii::$app->formatter->asDate($aux->dt_vencimento) . ')<br>';
            }
        }
        return $avisos;
    }
    
    public static function criaAvisosPromocao($url, $produtos, $titulo) {
        $avisos = "";
        if (!empty($produtos)) {
            $avisos .= "<br><b>$titulo</b><br>";
            foreach ($produtos as $aux) {
                $avisos .= '<a href="' . Avisos::criar_link($url, "id", $aux->pk_produto) . '">' . \yii\helpers\Html::encode($aux->nome) . '</a><br>';
            }
        }
        return $avisos;
    }

    public static function criar_link($url_base, $parametro, $cod) {
        return Url::to([$url_base, $parametro => $cod]);
    }

}
