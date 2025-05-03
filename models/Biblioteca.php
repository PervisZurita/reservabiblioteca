<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "biblioteca".
 *
 * @property int $idbiblioteca
 * @property string $campus
 * @property string|null $apertura
 * @property string|null $cierre
 * @property string|null $email
 * @property string|null $telefono
 *
 * @property Libros2[] $libros
 * @property Pc[] $pcs
 * @property Prestamo[] $prestamos
 */
class Biblioteca extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'biblioteca';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campus'], 'required'],
            [['apertura', 'cierre'], 'safe'],
            [['campus', 'email', 'telefono'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idbiblioteca' => 'ID',
            'campus' => 'Campus',
            'apertura' => 'Apertura',
            'cierre' => 'Cierre',
            'email' => 'Email',
            'telefono' => 'Teléfono',
        ];
    }

    /**
     * Gets query for [[Libros]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLibros()
    {
        return $this->hasMany(Libros2::class, ['biblioteca_idbiblioteca' => 'idbiblioteca']);
    }

    /**
     * Gets query for [[Pcs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPcs()
    {
        return $this->hasMany(Pc::class, ['biblioteca_idbiblioteca' => 'idbiblioteca']);
    }

    /**
     * Gets query for [[Prestamos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrestamos()
    {
        return $this->hasMany(Prestamo::class, ['biblioteca_idbiblioteca' => 'idbiblioteca']);
    }
}
