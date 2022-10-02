<?php

namespace backend\modules\stories\models;

use Yii;

/**
 * This is the model class for table "stories_photo".
 *
 * @property int $id
 * @property int|null $album_id
 * @property string $image
 * @property int $rank
 * @property int $duration
 * @property string $created_at
 */
class StoriesPhoto extends \yii\db\ActiveRecord
{
    public $imageFile;
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
            [['album_id', 'rank', 'duration'], 'integer'],
            [['created_at'], 'safe'],
            [['image'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg'],
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
