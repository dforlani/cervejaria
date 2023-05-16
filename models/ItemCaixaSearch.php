<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ItemCaixa;

/**
 * CaixaSearch represents the model behind the search form of `app\models\Caixa`.
 */
class ItemCaixaSearch extends ItemCaixa {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['fk_caixa', 'pk_item_caixa', 'fk_venda'], 'integer'],
            [['valor_dinheiro', 'valor_debito', 'valor_credito', 'valor_pix'], 'number'],
            [['tipo'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = ItemCaixa::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
            'sort' => ['defaultOrder' => ['dt_movimento' => SORT_ASC]],
            'pagination' => ['pageSize' => false],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'fk_caixa' => $this->fk_caixa,
            'pk_item_caixa' => $this->pk_item_caixa,
            'fk_venda' => $this->fk_venda,
            'valor_debito' => $this->valor_debito,
            'valor_credito' => $this->valor_credito,
            'valor_pix' => $this->valor_pix,
            'valor_dinheiro' => $this->valor_dinheiro,
        ]);


        $query->andFilterWhere(['like', 'tipo', $this->tipo]);

        return $dataProvider;
    }

}

