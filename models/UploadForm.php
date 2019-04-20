<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model {

    /**
     * @var UploadedFile[]
     */
    public $files;

    public function rules() {
        return [
            [['files'], 'file', 'skipOnEmpty' => false, 'maxFiles' => 60],
        ];
    }

    public function upload() {
        if ($this->validate()) {
            foreach ($this->files as $file) {
                $file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
            }
            return true;
        } else {
            print_r($this->errors);
            exit();
            return false;
        }
    }

    public function listFiles() {
        $dir = './uploads';
        $arquivos = scandir($dir);
        $arquivoProcessados = [];
        if (!empty($arquivos)) {
            foreach ($arquivos as $arquivo) {
                if ($arquivo != '.' && $arquivo != '..')
                    $arquivoProcessados[]['arquivo'] = $arquivo;
            }
        }

        return $arquivoProcessados;
    }

}
