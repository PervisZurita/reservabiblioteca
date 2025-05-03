<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use app\models\User; // Modelo de usuarios

class ImportFormUsuario extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;

    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'csv'],
        ];
    }

    public function uploadAndImport()
    {
        if (!$this->validate()) {
            return false;
        }

        $handle = fopen($this->file->tempName, 'r');

        if ($handle === false) {
            return false;
        }

        $header = fgetcsv($handle); // Leer encabezado

        while (($data = fgetcsv($handle)) !== false) {
            // Asegurarse que tiene al menos 6 columnas
            if (count($data) < 6) {
                continue;
            }

            [$id, $username, $status, $tipo_usuario, $created_at, $updated_at] = $data;

            // Si ya existe, actualiza; si no, crea uno nuevo
            $user = User::findOne($id);
            if (!$user) {
                $user = new User();
                $user->id = $id;
            }

            $user->username = $username;
            $user->Status = strtolower($status) == 'activo' ? 1 : 0;
            $user->tipo_usuario = $tipo_usuario;
            $user->Created_at = $created_at;
            $user->Updated_at = $updated_at;

            if (!$user->save(false)) { // false = no validar (asumiendo CSV confiable)
                continue;
            }
        }

        fclose($handle);
        return true;
    }
}
