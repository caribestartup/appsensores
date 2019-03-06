<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Totales;

/**
 * TotalesSearch represents the model behind the search form of `app\models\Totales`.
 */
class TotalesSearch extends Totales
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'identificador', 'programa', 'camara', 'total', 'total_error', 'cliente', 'operario', 'turno', 'total_tubos', 'ampollas_tubos', 'ampollas_previstas'], 'integer'],
            [['mac', 'fecha', 'modelo', 'serie', 'hora_inicio', 'hora_fin'], 'safe'],
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
        $query = Totales::find();

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
            'identificador' => $this->identificador,
            'programa' => $this->programa,
            'fecha' => $this->fecha,
            'camara' => $this->camara,
            'hora_inicio' => $this->hora_inicio,
            'hora_fin' => $this->hora_fin,
            'total' => $this->total,
            'total_error' => $this->total_error,
            'cliente' => $this->cliente,
            'operario' => $this->operario,
            'turno' => $this->turno,
            'total_tubos' => $this->total_tubos,
            'ampollas_tubos' => $this->ampollas_tubos,
            'ampollas_previstas' => $this->ampollas_previstas,
        ]);

        $query->andFilterWhere(['like', 'mac', $this->mac])
            ->andFilterWhere(['like', 'modelo', $this->modelo])
            ->andFilterWhere(['like', 'serie', $this->serie]);

        return $dataProvider;
    }
}
