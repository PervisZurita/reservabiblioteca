<?php
namespace app\models;

use yii\db\ActiveRecord;

class Record extends ActiveRecord
{
    public static function tableName()
    {
        return 'records'; // Nombre de la tabla donde guardarás los datos
    }

    public function rules()
    {
        return [
            [['numero', 'estado', 'campus'], 'required'],
            [['numero'], 'integer'],
            [['estado', 'campus'], 'string', 'max' => 255],
        ];
    }
}
