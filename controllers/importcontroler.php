<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\models\ImportForm;
use app\models\Libro;
use app\models\Record;

class ImportController extends Controller
{
    public function actionImport()
    {
        $model = new ImportForm();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->file && $model->validate()) {
                // Guardar el archivo en el servidor
                $filePath = 'uploads/' . $model->file->baseName . '.' . $model->file->extension;
                $model->file->saveAs($filePath);

                // Procesar el archivo CSV y cargar los datos en la base de datos
                $data = $this->parseCsv($filePath);

                foreach ($data as $row) {
                    $record = new Record();
                    $record->numero = $row['numero'];
                    $record->estado = $row['estado'];
                    $record->campus = $row['campus'];
                    $record->save();
                }

                Yii::$app->session->setFlash('success', 'Datos importados correctamente.');
            } else {
                Yii::$app->session->setFlash('error', 'Error al importar el archivo.');
            }
        }

        return $this->render('importModal', ['model' => $model]);
    }

    public function actionImport2()
    {
        $importModel = new ImportForm();
        
        if (Yii::$app->request->isPost) {
            $importModel->file = UploadedFile::getInstance($importModel, 'file');
            
            if ($importModel->file && $importModel->validate()) {
                $filePath = Yii::getAlias('@webroot') . '/uploads/' . $importModel->file->baseName . '.' . $importModel->file->extension;
                $importModel->file->saveAs($filePath);

                // Procesar el archivo CSV
                $data = $this->parseCsv($filePath);

                foreach ($data as $row) {
                    $libro = new Libro();
                    $libro->codigo_barras = $row['codigo_barras'] ?? $row[0] ?? '';
                    $libro->titulo = $row['titulo'] ?? $row[1] ?? '';
                    $libro->autor = $row['autor'] ?? $row[2] ?? '';
                    $libro->isbn = $row['isbn'] ?? $row[3] ?? '';
                    $libro->categoria_id = $row['categoria_id'] ?? $row[4] ?? null;
                    $libro->asignatura_IdAsig = $row['asignatura_IdAsig'] ?? $row[5] ?? null;
                    
                    if (!$libro->save()) {
                        Yii::error("Error al guardar libro: " . print_r($libro->errors, true));
                    }
                }

                Yii::$app->session->setFlash('success', 'Los libros fueron importados correctamente.');
                return $this->redirect(['libro/index']);
            } else {
                Yii::$app->session->setFlash('error', 'Hubo un error al importar el archivo: ' . print_r($importModel->errors, true));
            }
        }

        return $this->render('import', [
            'importModel' => $importModel,
        ]);
    }

    private function parseCsv($filePath)
    {
        $data = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            $header = fgetcsv($handle);
            while (($row = fgetcsv($handle)) !== false) {
                // Verificar si el archivo tiene encabezados
                if (count($header) === count($row)) {
                    $data[] = array_combine($header, $row);
                } else {
                    // Si no coinciden los encabezados con las columnas, usar índices numéricos
                    $data[] = $row;
                }
            }
            fclose($handle);
            
            // Eliminar el archivo temporal después de procesarlo
            unlink($filePath);
        }
        return $data;
    }
}