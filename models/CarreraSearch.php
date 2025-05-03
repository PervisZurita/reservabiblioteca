<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Carrera;

/**
 * CarreraSearch represents the model behind the search form of `app\models\Carrera`.
 */
class CarreraSearch extends Carrera
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idCarr', 'NombCarr', 'nivelCarr', 'codCarr_senescyt', 'sau_id', 'inst_cod', 'idcarr_utelvt', 'carreracol', 'tituloh', 'titulom', 'fechaaprobacion', 'resolucion', 'titulo', 'director'], 'safe'],
            [['StatusCarr', 'mod_id', 'id_tc', 'idsede', 'idfacultad', 'culminacion', 'optativa', 'habilitada', 'folio', 'cantidadestudiante', 'cantidadporpagina', 'cantidadlibro', 'duracion'], 'integer'],
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
        $query = Carrera::find();

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
            'StatusCarr' => $this->StatusCarr,
            'mod_id' => $this->mod_id,
            'id_tc' => $this->id_tc,
            'idsede' => $this->idsede,
            'idfacultad' => $this->idfacultad,
            'culminacion' => $this->culminacion,
            'optativa' => $this->optativa,
            'habilitada' => $this->habilitada,
            'folio' => $this->folio,
            'cantidadestudiante' => $this->cantidadestudiante,
            'cantidadporpagina' => $this->cantidadporpagina,
            'cantidadlibro' => $this->cantidadlibro,
            'fechaaprobacion' => $this->fechaaprobacion,
            'duracion' => $this->duracion,
        ]);

        $query->andFilterWhere(['like', 'idCarr', $this->idCarr])
            ->andFilterWhere(['like', 'NombCarr', $this->NombCarr])
            ->andFilterWhere(['like', 'nivelCarr', $this->nivelCarr])
            ->andFilterWhere(['like', 'codCarr_senescyt', $this->codCarr_senescyt])
            ->andFilterWhere(['like', 'sau_id', $this->sau_id])
            ->andFilterWhere(['like', 'inst_cod', $this->inst_cod])
            ->andFilterWhere(['like', 'idcarr_utelvt', $this->idcarr_utelvt])
            ->andFilterWhere(['like', 'carreracol', $this->carreracol])
            ->andFilterWhere(['like', 'tituloh', $this->tituloh])
            ->andFilterWhere(['like', 'titulom', $this->titulom])
            ->andFilterWhere(['like', 'resolucion', $this->resolucion])
            ->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'director', $this->director]);

        return $dataProvider;
    }
}
