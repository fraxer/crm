<?php

namespace backend\modules\stories\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;


class StoriesForm extends Model
{
    public $id;
    public $name;
    public $rank;
    public $imageFile;

    public function rules()
    {
        return [
            [['rank'], 'integer'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg'],
        ];
    }
    
    public function upload()
    {
        if ($this->validate()) {
            $this->imageFile->saveAs($this->getFilePath());
            return true;
        } else {
            return false;
        }
    }

    public function getFilePath()
    {
        return 'uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension;
    }
}
