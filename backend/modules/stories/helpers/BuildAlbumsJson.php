<?php

namespace backend\modules\stories\helpers;

use Yii;

class BuildAlbumsJson
{
    private $albums;

    function __construct($albums)
    {
        $this->albums = $albums;
    }

    public function getAlbumsJson()
    {
        $albums = [];

        foreach ($this->albums as $album) {
            $albums[] = $this->getAlbumStruct($album);
        }

        return json_encode($albums);
    }

    private function getAlbumStruct($album)
    {
        $albumStruct = [
            'id' => $album->id,
            'name' => $album->name,
            'image' => $album->image,
            'photos' => [],
        ];

        foreach ($album->photos as $photo) {
            $albumStruct['photos'][] = $this->getPhotoStruct($photo);
        }

        return $albumStruct;
    }

    private function getPhotoStruct($photo)
    {
        return [
            'image' => $photo->image,
            'duration' => $photo->duration,
        ];
    }
}
