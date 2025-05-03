<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pc".
 *
 * @property int $idpc
 * @property string $nombre
 * @property string $estado
 * @property int $biblioteca_idbiblioteca
 *
 * @property Biblioteca $bibliotecaIdbiblioteca
 * @property Prestamo[] $prestamos
 */
class Pc extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'estado', 'biblioteca_idbiblioteca'], 'required'],
            [['biblioteca_idbiblioteca'], 'integer'],
            [['nombre'], 'string', 'max' => 45],
            [['estado'], 'string', 'max' => 10],
            [['biblioteca_idbiblioteca'], 'exist', 'skipOnError' => true, 'targetClass' => Biblioteca::class, 'targetAttribute' => ['biblioteca_idbiblioteca' => 'idbiblioteca']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idpc' => Yii::t('app', 'Idpc'),
            'nombre' => Yii::t('app', 'Nombre'),
            'estado' => Yii::t('app', 'Estado'),
            'biblioteca_idbiblioteca' => Yii::t('app', 'Campus'),
        ];
    }

    /**
     * Gets query for [[BibliotecaIdbiblioteca]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBibliotecaIdbiblioteca()
    {
        return $this->hasOne(Biblioteca::class, ['idbiblioteca' => 'biblioteca_idbiblioteca']);
    }

    /**
     * Gets query for [[Prestamos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrestamos()
    {
        return $this->hasMany(Prestamo::class, ['pc_idpc' => 'idpc', 'pc_biblioteca_idbiblioteca' => 'biblioteca_idbiblioteca']);
    }
    public function actionPrestarModal($id)
{
    $model = Pc::findOne($id);
    if (!$model) {
        throw new \yii\web\NotFoundHttpException('PC no encontrada');
    }

    return $this->renderAjax('_modal_prestar', [
        'model' => $model,
    ]);
}


}
