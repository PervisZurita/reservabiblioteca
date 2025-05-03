<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tesis".
 *
 * @property int $id
 * @property string|null $numero_estanteria
 * @property string|null $facultad
 * @property string|null $carrera
 * @property string|null $tema
 * @property string|null $autor
 * @property string|null $tutor
 * @property string|null $anio_publicacion
 * @property string|null $codigo
 */
class Tesis extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tesis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero_estanteria', 'facultad', 'carrera', 'tema', 'autor', 'tutor', 'anio_publicacion', 'codigo'], 'default', 'value' => null],
            [['tema'], 'string'],
            [['anio_publicacion'], 'safe'],
            [['numero_estanteria', 'codigo'], 'string', 'max' => 50],
            [['facultad', 'carrera', 'autor', 'tutor'], 'string', 'max' => 100],
            [['codigo'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'numero_estanteria' => Yii::t('app', 'Numero Estanteria'),
            'facultad' => Yii::t('app', 'Facultad'),
            'carrera' => Yii::t('app', 'Carrera'),
            'tema' => Yii::t('app', 'Tema'),
            'autor' => Yii::t('app', 'Autor'),
            'tutor' => Yii::t('app', 'Tutor'),
            'anio_publicacion' => Yii::t('app', 'Año Publicacion'),
            'codigo' => Yii::t('app', 'Codigo'),
        ];
    }
    public function actionExport()
{
    $tesis = Tesis::find()->all();
    $filename = 'tesis_export.csv';

    $csvData = fopen('php://output', 'w');
    header('Content-Type: text/csv');
    header("Content-Disposition: attachment; filename={$filename}");

    // Encabezados
    fputcsv($csvData, ['ID', 'Número Estantería', 'Facultad', 'Carrera', 'Tema', 'Autor', 'Tutor', 'Año Publicación', 'Código']);

    // Datos
    foreach ($tesis as $item) {
        fputcsv($csvData, [
            $item->numero_estanteria,
            $item->facultad,
            $item->carrera,
            $item->tema,
            $item->autor,
            $item->tutor,
            $item->anio_publicacion,
            $item->codigo
        ]);
    }

    fclose($csvData);
    exit;
}

public function actionImport()
{
    if (Yii::$app->request->isPost) {
        $file = UploadedFile::getInstanceByName('importFile');
        if ($file && $file->tempName) {
            $handle = fopen($file->tempName, 'r');
            $isFirst = true;
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                if ($isFirst) {
                    $isFirst = false;
                    continue; // omitir encabezado
                }

                $tesis = new Tesis();
                $tesis->numero_estanteria = $data[1];
                $tesis->facultad = $data[2];
                $tesis->carrera = $data[3];
                $tesis->tema = $data[4];
                $tesis->autor = $data[5];
                $tesis->tutor = $data[6];
                $tesis->anio_publicacion = $data[7];
                $tesis->codigo = $data[8];
                $tesis->save(false);
            }
            fclose($handle);
            Yii::$app->session->setFlash('success', 'Importación completada.');
        }
    }

    return $this->redirect(['index']);
}


}
