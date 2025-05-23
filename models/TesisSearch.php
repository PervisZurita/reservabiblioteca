<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tesis;

/**
 * TesisSearch represents the model behind the search form of `app\models\Tesis`.
 */
class TesisSearch extends Tesis
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['numero_estanteria', 'facultad', 'carrera', 'tema', 'autor', 'tutor', 'anio_publicacion', 'codigo'], 'safe'],
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = Tesis::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'anio_publicacion' => $this->anio_publicacion,
        ]);

        $query->andFilterWhere(['like', 'numero_estanteria', $this->numero_estanteria])
            ->andFilterWhere(['like', 'facultad', $this->facultad])
            ->andFilterWhere(['like', 'carrera', $this->carrera])
            ->andFilterWhere(['like', 'tema', $this->tema])
            ->andFilterWhere(['like', 'autor', $this->autor])
            ->andFilterWhere(['like', 'tutor', $this->tutor])
            ->andFilterWhere(['like', 'codigo', $this->codigo]);

        return $dataProvider;
    }
}
