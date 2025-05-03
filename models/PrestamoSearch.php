<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Prestamo;

/**
 * PrestamoSearch represents the model behind the search form of `app\models\Prestamo`.
 */
class PrestamoSearch extends Prestamo
{

    public $cedula_solicitante;
    public $libroTitulo;  // Atributo para el filtro de título del libro
    public $tipoSolicitante;  // Atributo para el filtro de tipo de solicitante
    public $facultad;
    public $carrera;
    public $nivel;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pc_idpc'], 'string'],
            [['id', 'biblioteca_idbiblioteca', 'pc_biblioteca_idbiblioteca', 'libro_id', ], 'integer'],
            [['cedula_solicitante', 'fecha_solicitud', 'fechaentrega', 'tipoprestamo_id', 'personaldata_Ci', 'informacionpersonal_d_CIInfPer', 'informacionpersonal_CIInfPer'], 'safe'],
            [['libroTitulo'], 'safe'], 
            [['tipoSolicitante'], 'safe'],  // Añadir regla para tipoSolicitante
            [['facultad', 'carrera', 'nivel'], 'safe'],
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
    $query = Prestamo::find();

    $query->joinWith('pcIdpc'); // Realizar un join con la tabla pc
    $query->joinWith(['libro']);
    $query->joinWith(['informacionpersonalCIInfPer']);
    $query->joinWith(['informacionpersonalDCIInfPer']);
    $query->joinWith(['personaldataCi']);

  //  $query->joinWith(['informacionpersonalCIInfPer.factura.detalleMatricula.carrera']);

    // Add conditions that should always apply here
    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination' => ['pageSize' => 3000,],
    ]);

    $this->load($params);

    if (!$this->validate()) {
        // uncomment the following line if you do not want to return any records when validation fails
        // $query->where('0=1');
        return $dataProvider;
    }

    if (!empty($this->fecha_solicitud)) {
        // Cambiamos el formato de la fecha para hacerlo compatible con la base de datos
        $fechaSolicitud = \Yii::$app->formatter->asDatetime($this->fecha_solicitud, 'php:Y-m-d H:i:s');

        // Separar la fecha en formato Y-m-d H:i:s en fecha y hora
        list($fecha, $hora) = explode(' ', $fechaSolicitud);

        // Convertir la fecha en formato Y-m-d a un rango de tiempo en ese día
        $fechaInicio = $fecha . ' 00:00:00';
        $fechaFin = $fecha . ' 23:59:59';

        // Aplicar el filtro
        $query->andFilterWhere([
            'between', 'fecha_solicitud', $fechaInicio, $fechaFin
        ]);
    }

    // grid filtering conditions
    $query->andFilterWhere([
        'id' => $this->id,
        'fechaentrega' => $this->fechaentrega,
        'prestamo.biblioteca_idbiblioteca' => $this->biblioteca_idbiblioteca,
        'pc_biblioteca_idbiblioteca' => $this->pc_biblioteca_idbiblioteca,
        'libro_id' => $this->libro_id,
    ]);

    $query->andFilterWhere(['like', 'tipoprestamo_id', $this->tipoprestamo_id])
        ->andFilterWhere(['like', 'pc.nombre', $this->pc_idpc])
        ->andFilterWhere(['like', 'personaldata_Ci', $this->cedula_solicitante])
        ->orFilterWhere(['like', 'informacionpersonal_d_CIInfPer', $this->cedula_solicitante])
        ->orFilterWhere(['like', 'informacionpersonal_CIInfPer', $this->cedula_solicitante]);

    $query->andFilterWhere(['like', 'libro.titulo', $this->libroTitulo]);

    // Filtrado por tipoSolicitante
    if ($this->tipoSolicitante == 'Estudiante') {
        $query->andFilterWhere(['not', ['informacionpersonal_d_CIInfPer' => null]]);
        
        // Filtrado por facultad, carrera y nivel solo si es estudiante
        $query->andFilterWhere(['like', 'facultad.nombre', $this->facultad])
            ->andFilterWhere(['like', 'carrera.nombre', $this->carrera])
            ->andFilterWhere(['like', 'detalle_matricula.nivel', $this->nivel]);
    } else {
        // Para otros tipos de solicitantes, no se aplican filtros para facultad, carrera y nivel
        $query->andFilterWhere(['like', 'facultad', $this->facultad])
              ->andFilterWhere(['like', 'carrera', $this->carrera])
              ->andFilterWhere(['like', 'nivel', $this->nivel]);
    }

    return $dataProvider;
}
}
