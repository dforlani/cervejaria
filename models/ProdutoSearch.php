<?php

namespace app\models;

use app\models\Produto;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ProdutoSearch represents the model behind the search form of `app\models\Produto`.
 */
class ProdutoSearch extends Produto {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['pk_produto'], 'integer'],
            [['nome', 'auxHasPromocao', 'tipo_produto', 'is_vendavel'], 'safe'],
            [['estoque_vendido'], 'number'],
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
    public function search($params, $tipos) {
        $query = Produto::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
            'sort' => ['defaultOrder' => ['nome' => SORT_ASC]],
            'pagination' => [
                'pageSize' => 40,
            ],
        ]);

        $this->load($params);
        $query->joinWith('precos');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'pk_produto' => $this->pk_produto,
            'estoque_vendido' => $this->estoque_vendido,
            'is_promocao_ativa' => $this->auxHasPromocao,
            'is_vendavel' => $this->is_vendavel,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome]);
        $query->andFilterWhere(['like', 'tipo_produto', $this->tipo_produto]);
        $query->andFilterWhere(['in', 'tipo_produto', $tipos]);

        return $dataProvider;
    }

}
