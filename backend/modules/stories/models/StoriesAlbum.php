<?php

namespace backend\modules\stories\models;

use Yii;

/**
 * This is the model class for table "stories_album".
 *
 * @property int $id
 * @property string $name
 * @property string $image
 * @property int $rank
 * @property string $created_at
 */
class StoriesAlbum extends \yii\db\ActiveRecord
{
    public $albumImage;

    public $photoImages;

    public static function tableName()
    {
        return 'stories_album';
    }

    public function rules()
    {
        return [
            [['rank'], 'integer'],
            [['created_at'], 'safe'],
            [['name', 'image'], 'string', 'max' => 255],
            [['albumImage'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg'],
            [['photoImages'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 40],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'image' => 'Image',
            'rank' => 'Rank',
            'created_at' => 'Created At',
        ];
    }

    public function getPhotos()
    {
        return $this->hasMany(StoriesPhoto::class, ['album_id' => 'id'])->orderBy('id asc');
    }
    
    public function upload($path)
    {
        if ($this->validate()) {
            \Yii::debug($path);
            $this->albumImage->saveAs($path);
            return true;
        } else {
            return false;
        }
    }
}
