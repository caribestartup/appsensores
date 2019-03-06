<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Parciales;

/**
 * ParcialesSearch represents the model behind the search form of `app\models\Parciales`.
 */
class ParcialesSearch extends Parciales
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_totales', 'total', 'total_error'], 'integer'],
            [['ventana', 'nombre_ventana'], 'safe'],
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
        $query = Parciales::find();

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
            'id' => $this->id,
            'id_totales' => $this->id_totales,
            'total' => $this->total,
            'total_error' => $this->total_error,
        ]);

        $query->andFilterWhere(['like', 'ventana', $this->ventana])
            ->andFilterWhere(['like', 'nombre_ventana', $this->nombre_ventana]);

        return $dataProvider;
    }
}
