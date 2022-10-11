<?php

namespace backend\modules\stories\models;

use Yii;

class StoriesPhoto extends \yii\db\ActiveRecord
{
    public $imageFile;

    const SCENARIO_CREATE = 'create';

    const SCENARIO_UPDATE = 'update';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stories_photo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['album_id', 'duration'], 'integer'],
            [['rank'], 'integer', 'min' => 1],
            [['created_at'], 'safe'],
            [['image'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'on' => self::SCENARIO_CREATE],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'on' => self::SCENARIO_UPDATE],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'album_id' => 'Album ID',
            'image' => 'Image',
            'rank' => 'Rank',
            'duration' => 'Duration',
            'created_at' => 'Created At',
        ];
    }

    public function upload($path)
    {
        if ($this->validate()) {
            $this->imageFile->saveAs($path);
            return true;
        } else {
            return false;
        }
    }
}
