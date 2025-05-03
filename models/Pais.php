<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pais".
 *
 * @property string $codigopais
 * @property string $Nombrepais
 *
 * @property Libro[] $libros
 */
class Pais extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pais';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigopais', 'Nombrepais'], 'required'],
            [['codigopais'], 'string', 'max' => 4],
            [['Nombrepais'], 'string', 'max' => 45],
            [['codigopais'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'codigopais' => Yii::t('app', 'Codigopais'),
            'Nombrepais' => Yii::t('app', 'Nombrepais'),
        ];
    }

    /**
     * Gets query for [[Libros]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLibros()
    {
        return $this->hasMany(Libro::class, ['pais_codigopais' => 'codigopais']);
    }

}
