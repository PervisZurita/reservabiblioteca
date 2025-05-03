<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "personaldata".
 *
 * @property string $Ci
 * @property string $Apellidos
 * @property string $Nombres
 * @property string|null $FechaNacimiento
 * @property string $Email
 * @property string $Genero
 * @property string|null $Institucion
 * @property string|null $Nivel
 *
 * @property Prestamo[] $prestamos
 * @property User $user
 */
class Personaldata extends \yii\db\ActiveRecord
{
    public $niveles = [
        'B'=>'Bachillerato'  ,
        'BT'=>'Bachillerato Técnico' ,
        'U'=> 'Tercer Nivel (Universidad)' ,
        'T'=>'Tercer Nivel (Tecnológico)' ,
        'PS'=> 'Posgrado' ,
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'personaldata';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Ci', 'Apellidos', 'Nombres', 'Email', 'Genero'], 'required'],
            [['FechaNacimiento'], 'safe'],
            [['Genero'], 'string'],
           // [['Ciclo'], 'integer'],
            [['Ci'], 'string', 'max' => 15],
            [['Apellidos'], 'string', 'max' => 40],
            [['Nombres', 'Email', 'Institucion', 'Nivel'], 'string', 'max' => 45],
          //  [['Facultad'], 'string', 'max' => 60],
            [['Ci'], 'unique'],
            [['Email'], 'email'],
            [['FechaNacimiento'], 'validateFechaNacimiento'],       
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Ci' => 'Cédula',
            'Apellidos' => 'Apellidos',
            'Nombres' => 'Nombres',
            'FechaNacimiento' => 'Fecha de Nacimiento',
            'Email' => 'Email',
            'Genero' => 'Género',
            'Institucion' => 'Afiliación',
            'Nivel' => 'Nivel Académico',
           // 'Facultad' => 'Facultad',
           // 'Ciclo' => 'Ciclo',
        ];
    }

    /**
     * Gets query for [[Prestamos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrestamos()
    {
        return $this->hasMany(Prestamo::class, ['personaldata_Ci' => 'Ci']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['username' => 'Ci']);
    }


    public function getNombre()
    {
		$nombre = '';
		$nombre = $this->Apellidos .' '. $this->Nombres;
		return $nombre;
    }
    public function validateFechaNacimiento($attribute, $params)
    {
        if ($this->$attribute > date('Y-m-d')) {
            $this->addError($attribute, 'Ingrese una fecha válida.');
        }
    }
}
