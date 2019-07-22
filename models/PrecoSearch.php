<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Preco;

/**
 * PrecoSearch represents the model behind the search form of `app\models\Preco`.
 */
class PrecoSearch extends Preco
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pk_preco', 'fk_produto'], 'integer'],
            [['denominacao'], 'safe'],
            [['preco', 'quantidade'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = Preco::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'pk_preco' => $this->pk_preco,
            'fk_produto' => $this->fk_produto,
            'preco' => $this->preco,
            'quantidade' => $this->quantidade,
        ]);

        $query->andFilterWhere(['like', 'denominacao', $this->denominacao]);

        return $dataProvider;
    }
}
