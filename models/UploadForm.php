<?php

namespace app\models;

use app\components\DBHelper;
use app\components\Helper;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class UploadForm extends Model
{

    public $inputFile;
    private $fieldName = 'inputFile';
    private $fileName;
    public $oldFileName;
    public $extensions = 'png, jpg, jpeg';
    public $folder = 'logo';
    private $path;
    private $pathAbsolute;

    public function __construct($folder = 'logo')
    {
        parent::__construct();
        $user = Helper::identity();
        $this->pathAbsolute = 'uploads/' . $user->branch->id_client . '/' . $folder;
        $this->path = Helper::getBaseUrl($this->pathAbsolute);
    }

    public function rules()
    {
        return [
            [['oldFileName'], 'safe'],
            [['inputFile'], 'file', 'skipOnEmpty' => true, 'extensions' => $this->extensions],
        ];
    }

    public function getPath($name = '')
    {
        return $this->path . '/' . $name;
    }

    public function getPathAbsolute($name = '')
    {
        return $this->pathAbsolute . ($name ? '/' . $name : '');
    }

    public function upload()
    {
        if ($this->inputFile) {
            $this->removeFile();

            FileHelper::createDirectory($this->pathAbsolute);
            $this->inputFile->saveAs($this->pathAbsolute . '/' . $this->fileName);
            return true;
        }
        return false;
    }

    public function removeFile()
    {
        if ($this->inputFile) {
            $path = getcwd() . '/' . $this->pathAbsolute . '/' . $this->oldFileName;

            if ($this->oldFileName && file_exists($path)) {
                unlink($path);
            }
            return true;
        }
        return false;
    }

    public function loadImage($model, $title)
    {
        $this->inputFile = UploadedFile::getInstance(UploadForm::instance(), $this->fieldName);

        if ($this->inputFile) {
            if ($model->isNewRecord) {
                $name = DBHelper::now();
            } else {
                $this->oldFileName = $model->oldAttributes[$title];
                $name = $model->getPrimaryKey();
            }
            $filename = $name . '.' . $this->inputFile->extension;
        } else {
            $filename = $model->isNewRecord ? null : $model->oldAttributes[$title];
        }
        $this->fileName = $filename;
        return $filename;
    }
}
