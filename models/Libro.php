<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "libro".
 *
 * @property int $id
 * @property string|null $ubicacion
 * @property int|null $numer
 * @property int $biblioteca_idbiblioteca
 * @property string|null $clasificacion
 * @property string $asignatura_id
 * @property string $titulo
 * @property string $autor
 * @property string $editorial
 * @property string $pais_codigopais
 * @property string|null $anio_publicacion
 * @property string|null $codigo_barras
 *
 * @property Asignatura $asignatura
 * @property Biblioteca $bibliotecaIdbiblioteca
 * @property Pais $paisCodigopais
 * @property Prestamo[] $prestamos
 */
class Libro extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'libro';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ubicacion', 'numer', 'clasificacion', 'anio_publicacion', 'codigo_barras'], 'default', 'value' => null],
            [['numer', 'biblioteca_idbiblioteca'], 'integer'],
            [['biblioteca_idbiblioteca', 'asignatura_id', 'titulo', 'autor', 'editorial', 'pais_codigopais'], 'required'],
            [['anio_publicacion'], 'safe'],
            [['ubicacion', 'clasificacion', 'titulo', 'autor', 'editorial', 'codigo_barras'], 'string', 'max' => 100],
            [['asignatura_id', 'pais_codigopais'], 'string', 'max' => 4],
            [['asignatura_id'], 'exist', 'skipOnError' => true, 'targetClass' => Asignatura::class, 'targetAttribute' => ['asignatura_id' => 'id']],
            [['biblioteca_idbiblioteca'], 'exist', 'skipOnError' => true, 'targetClass' => Biblioteca::class, 'targetAttribute' => ['biblioteca_idbiblioteca' => 'idbiblioteca']],
            [['pais_codigopais'], 'exist', 'skipOnError' => true, 'targetClass' => Pais::class, 'targetAttribute' => ['pais_codigopais' => 'codigopais']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'ubicacion' => Yii::t('app', 'Ubicacion'),
            'numer' => Yii::t('app', 'Numero'),
            'biblioteca_idbiblioteca' => Yii::t('app', 'biblioteca'),
            'clasificacion' => Yii::t('app', 'Clasificacion'),
            'asignatura_id' => Yii::t('app', 'Asignatura'),
            'titulo' => Yii::t('app', 'Titulo del libro'),
            'autor' => Yii::t('app', 'Autor'),
            'editorial' => Yii::t('app', 'Editorial'),
            'pais_codigopais' => Yii::t('app', 'Pais '),
            'anio_publicacion' => Yii::t('app', 'Año de Publicacion'),
            'codigo_barras' => Yii::t('app', 'Codigo de Barras'),
        ];
    }

    /**
     * Gets query for [[Asignatura]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAsignatura()
    {
        return $this->hasOne(Asignatura::class, ['id' => 'asignatura_id']);
    }

    /**
     * Gets query for [[BibliotecaIdbiblioteca]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBiblioteca()
    {
        return $this->hasOne(Biblioteca::class, ['idbiblioteca' => 'biblioteca_idbiblioteca']);
    }

    /**
     * Gets query for [[PaisCodigopais]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPais()
{
    return $this->hasOne(Pais::class, ['codigopais' => 'pais_codigopais']);
}

    /**
     * Gets query for [[Prestamos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrestamos()
    {
        return $this->hasMany(Prestamo::class, ['libro_id' => 'id', 'libro_biblioteca_idbiblioteca' => 'biblioteca_idbiblioteca']);
    }

}
