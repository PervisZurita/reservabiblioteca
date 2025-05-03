<?php
namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class ImportFormLibro extends Model
{
    /**
     * @var UploadedFile
     */
    public $archivo;

    public function rules()
    {
        return [
            [['archivo'], 'file', 'extensions' => 'csv', 'skipOnEmpty' => false],
        ];
    }
}
