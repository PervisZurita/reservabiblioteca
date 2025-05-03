<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class ImportFormLibros extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;

    public function rules()
    {
        return [
            [['file'], 'required'],
            [['file'], 'file', 'extensions' => 'csv, xlsx, xls'],
        ];
    }

    public function uploadAndImport()
    {
        if ($this->validate()) {
            $filePath = $this->file->tempName;
            $fileContent = file_get_contents($filePath);
            
            // Convertir a UTF-8 si es necesario
            if (!mb_check_encoding($fileContent, 'UTF-8')) {
                $fileContent = mb_convert_encoding($fileContent, 'UTF-8', 'auto');
                file_put_contents($filePath, $fileContent);
            }
            
            $handle = fopen($filePath, 'r');
            if ($handle === false) {
                throw new \Exception('No se pudo abrir el archivo.');
            }

            $transaction = Yii::$app->db->beginTransaction();
            try {
                $imported = 0;
                $errors = [];
                $rowNumber = 0;
                
                while (($row = fgetcsv($handle, 0, ',')) !== false) {
                    $rowNumber++;
                    
                    // Saltar encabezados
                    if ($rowNumber === 1) continue;
                    
                    // Saltar filas vacías
                    if (empty(array_filter($row))) continue;
                    
                    $model = new Libros();
                    $model->attributes = [
                        'ubicacion' => $row[1] ?? null,
                        'numero' => $row[2] ?? null,
                        'campus' => $row[3] ?? null,
                        'clasificacion' => $row[4] ?? null,
                        'titulo_libro' => $row[5] ?? null,
                        'autor' => $row[6] ?? null,
                        'editorial' => $row[7] ?? null,
                        'pais' => $row[8] ?? null,
                        'year' => $row[9] ?? null,
                        'codigo_barras' => $row[10] ?? null,
                    ];
                    
                    if ($model->save()) {
                        $imported++;
                    } else {
                        $errors[] = "Fila {$rowNumber}: " . implode("; ", $model->getFirstErrors());
                    }
                }
                
                fclose($handle);
                $transaction->commit();
                
                if (!empty($errors)) {
                    Yii::$app->session->set('importErrors', $errors);
                    return false;
                }
                
                return true;
            } catch (\Exception $e) {
                if (isset($handle)) fclose($handle);
                $transaction->rollBack();
                Yii::error("Error al importar libros: " . $e->getMessage());
                return false;
            }
        }
        return false;
    }
}