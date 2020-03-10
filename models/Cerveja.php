<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "produto".
 *
 * @property int $pk_produto
 * @property string $nome
 * @property double $estoque_vendido
 * @property int $fk_unidade_medida
 * @property int $is_vendavel
 * @property string $custo_compra_producao
 * @property Entrada[] $entradas
 * @property Preco[] $precos
 */
class Cerveja extends Produto {

    public static $TIPO = "Cerveja";
    public static $URL = "produto/update-cerveja";

    /**
     * Busca produtos que vão vencer em alguns dias
     * @return type
     */
    public static function getProdutosPraVencer($tipo_produto = 'Outros') {
        return parent::getProdutosPraVencer(Cerveja::$TIPO);
    }

    /**
     * return produtos que estão vencidos
     * @return type
     */
    public static function getProdutosVencidos($tipo_produto = 'Outros') {
        return parent::getProdutosVencidos(Cerveja::$TIPO);
    }

    public static function getProdutosComPromocaoAtiva($tipo_produto = 'Cerveja') {
        return parent::getProdutosComPromocaoAtiva(Cerveja::$TIPO);
    }

}
