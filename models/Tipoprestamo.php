<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipoprestamo".
 *
 * @property string $id
 * @property string $nombre_tipo
 *
 * @property Prestamo[] $prestamos
 */
class Tipoprestamo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipoprestamo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'nombre_tipo'], 'required'],
            [['id'], 'string', 'max' => 5],
            [['nombre_tipo'], 'string', 'max' => 45],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre_tipo' => 'Tipo de Solicitud',
        ];
    }

    /**
     * Gets query for [[Prestamos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrestamos()
    {
        return $this->hasMany(Prestamo::class, ['tipoprestamo_id' => 'id']);
    }
}
