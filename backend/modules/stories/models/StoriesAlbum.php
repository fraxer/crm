<?php

namespace backend\modules\stories\models;

use Yii;

class StoriesAlbum extends \yii\db\ActiveRecord
{
    public $albumImage;

    public $photoImages;

    const SCENARIO_CREATE = 'create';

    const SCENARIO_UPDATE = 'update';

    public static function tableName()
    {
        return 'stories_album';
    }

    public function rules()
    {
        return [
            [['salon_id', 'rank'], 'integer'],
            [['salon_id'], 'required'],
            [['created_at'], 'safe'],
            [['name', 'image'], 'string', 'max' => 255],
            [['albumImage'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'on' => self::SCENARIO_CREATE],
            [['albumImage'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'on' => self::SCENARIO_UPDATE],
            [['photoImages'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 40, 'on' => self::SCENARIO_CREATE],
            [['photoImages'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 40, 'on' => self::SCENARIO_UPDATE],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'salon_id' => 'Salon ID',
            'name' => 'Name',
            'image' => 'Image',
            'rank' => 'Rank',
            'created_at' => 'Created At',
        ];
    }

    public function getPhotos()
    {
        return $this->hasMany(StoriesPhoto::class, ['album_id' => 'id'])->orderBy('rank asc');
    }
    
    public function upload($path)
    {
        if ($this->validate()) {
            $this->albumImage->saveAs($path);
            return true;
        } else {
            return false;
        }
    }
}
