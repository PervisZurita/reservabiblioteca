<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "facultad".
 *
 * @property int $idfacultad
 * @property int $idsede
 * @property string $facultad
 * @property string $decano
 * @property string $cargodecano
 * @property string $secretario
 * @property string $cargosecretario
 * @property string $fechacreacion
 * @property string $siglas
 *
 * @property Carrera[] $carreras
 */
class Facultad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'facultad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idfacultad'], 'required'],
            [['idfacultad', 'idsede'], 'integer'],
            [['facultad', 'cargodecano', 'cargosecretario'], 'string', 'max' => 45],
            [['decano', 'secretario'], 'string', 'max' => 249],
            [['fechacreacion'], 'string', 'max' => 11],
            [['siglas'], 'string', 'max' => 10],
            [['idfacultad'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idfacultad' => 'Idfacultad',
            'idsede' => 'Idsede',
            'facultad' => 'Facultad',
            'decano' => 'Decano',
            'cargodecano' => 'Cargodecano',
            'secretario' => 'Secretario',
            'cargosecretario' => 'Cargosecretario',
            'fechacreacion' => 'Fechacreacion',
            'siglas' => 'Siglas',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarreras()
    {
        return $this->hasMany(Carrera::className(), ['idfacultad' => 'idfacultad']);
    }
}
