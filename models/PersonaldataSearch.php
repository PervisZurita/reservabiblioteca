<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Personaldata;

/**
 * PersonaldataSearch represents the model behind the search form of `app\models\Personaldata`.
 */
class PersonaldataSearch extends Personaldata
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
        [['Ci', 'Apellidos', 'Nombres', 'FechaNacimiento', 'Email', 'Genero', 'Institucion', 'Nivel'/*, 'Facultad'*/], 'safe'],
            //[['Ciclo'], 'integer'],
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
        $query = Personaldata::find();

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
            'FechaNacimiento' => $this->FechaNacimiento,
            //'Ciclo' => $this->Ciclo,
        ]);

        $query->andFilterWhere(['like', 'Ci', $this->Ci])
            ->andFilterWhere(['like', 'Apellidos', $this->Apellidos])
            ->andFilterWhere(['like', 'Nombres', $this->Nombres])
            ->andFilterWhere(['like', 'Email', $this->Email])
            ->andFilterWhere(['like', 'Genero', $this->Genero])
            ->andFilterWhere(['like', 'Institucion', $this->Institucion])
            ->andFilterWhere(['like', 'Nivel', $this->Nivel]);
           // ->andFilterWhere(['like', 'Facultad', $this->Facultad]);

        return $dataProvider;
    }
}
