<?php

namespace app\models;

use yii\base\Model;

class XmlImportForm extends Model
{
    public $file;
    public $filename;
    public $filePath;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {

        return [
            //max upload filesize 15Mb
            ['file','required'],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xml','maxSize' => 15* 1024 * 1024],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $filename = $this->file->baseName . '.' . $this->file->extension;
            $folder = User::getFullUploadPathByType(USER::UPLOADS_TYPE_IMPORT);

            if (!is_file($folder)) {
                \yii\helpers\FileHelper::createDirectory($folder, $mode = 0775, $recursive = true);
            }

            $path = $folder. $filename;
            $this->file->saveAs($path);
            $this->filePath = $path;
            $this->filename = $filename;
            return true;
        } else {
            return false;
        }
    }

}