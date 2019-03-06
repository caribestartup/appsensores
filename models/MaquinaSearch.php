<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Maquina;

/**
 * MaquinaSearch represents the model behind the search form of `app\models\Maquina`.
 */
class MaquinaSearch extends Maquina
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['maquina_id', 'local', 'estado'], 'integer'],
            [['nombre', 'modelo', 'numero', 'imagen', 'fecha'], 'safe'],
            [['posx', 'posy', 'ancho', 'largo'], 'number'],
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
        $query = Maquina::find();

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
            'maquina_id' => $this->maquina_id,
            'local' => $this->local,
            'posx' => $this->posx,
            'posy' => $this->posy,
            'ancho' => $this->ancho,
            'largo' => $this->largo,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'modelo', $this->modelo])
            ->andFilterWhere(['like', 'numero', $this->numero])
            ->andFilterWhere(['like', 'imagen', $this->imagen]);

        return $dataProvider;
    }
}
