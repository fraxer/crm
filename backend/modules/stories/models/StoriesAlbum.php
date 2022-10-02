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
    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stories_album';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rank'], 'integer'],
            [['created_at'], 'safe'],
            [['name', 'image'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
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
}
