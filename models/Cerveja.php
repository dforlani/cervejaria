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

    public static $CERVEJA = "Cerveja";

    /**
     * Busca produtos que vão vencer em alguns dias
     * @return type
     */
    public static function getProdutosPraVencer($tipo_produto = "Cerveja") {
        return parent::getProdutosPraVencer(Cerveja::$CERVEJA);
    }
    
    /**
     * return produtos que estão vencidos
     * @return type
     */
    public static function getProdutosVencidos($tipo_produto = "Cerveja") {
       return parent::getProdutosVencidos(Cerveja::$CERVEJA);
    }

}
