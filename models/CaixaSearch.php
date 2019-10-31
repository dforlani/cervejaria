<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Caixa;

/**
 * CaixaSearch represents the model behind the search form of `app\models\Caixa`.
 */
class CaixaSearch extends Caixa
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pk_caixa'], 'integer'],
            [['dt_abertura', 'dt_fechamento', 'estado'], 'safe'],
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
        $query = Caixa::find();

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
            'pk_caixa' => $this->pk_caixa,
            'dt_abertura' => $this->dt_abertura,
            'dt_fechamento' => $this->dt_fechamento,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'estado', $this->estado]);

        return $dataProvider;
    }
}
