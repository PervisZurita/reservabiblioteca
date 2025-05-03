<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "informacionpersonal".
 *
 * @property string $CIInfPer
 * @property string $ApellInfPer
 * @property string $ApellMatInfPer
 * @property string $NombInfPer
 * @property string|null $codigo_dactilar
 * 
 * @property Prestamo[] $prestamos
 * @property User $user
 */

class Informacionpersonal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'informacionpersonal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['CIInfPer', 'ApellInfPer', 'ApellMatInfPer', 'NombInfPer'], 'required'],
            [['CIInfPer'], 'string', 'max' => 15],
            [['ApellInfPer', 'ApellMatInfPer', 'NombInfPer', 'codigo_dactilar'], 'string', 'max' => 45],
            [['CIInfPer'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'CIInfPer' => 'Cédula',
            'ApellInfPer' => 'Apellido Paterno',
            'ApellMatInfPer' => 'Apellido Materno',
            'NombInfPer' => 'Nombre',
            'codigo_dactilar' => 'Código Dactilar',
        ];
    }

    /**
     * Encuentra un modelo basado en el número de cédula.
     * @param string $cedula El número de cédula para buscar.
     * @return static|null El modelo encontrado o null si no se encuentra.
     */
    public static function findByCedula($cedula)
    {
        return static::findOne(['CIInfPer' => $cedula]);
    }

    public function getFactura()
    {
        return $this->hasOne(Factura::class, ['cedula' => 'CIInfPer'])
                ->orderBy(['idper' => SORT_DESC]);
    }
    public function getNombre()
    {
		$nombre = '';
		$nombre = $this->ApellInfPer .' '. $this->ApellMatInfPer . ' ' . $this->NombInfPer;
		return $nombre;
    }
    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['username' => 'CIInfPer']);
    }


    /**
     * Gets query for [[Prestamos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrestamos()
    {
        return $this->hasMany(Prestamo::class, ['informacionpersonal_CIInfPer' => 'CIInfPer']);
    }
}
