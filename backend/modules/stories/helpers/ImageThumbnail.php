<?php

namespace backend\modules\stories\helpers;

use Yii;
use yii\imagine\Image;

class ImageThumbnail
{
    static public function createThumbnail($sourceImagePath)
    {
        if (empty($sourceImagePath)) return;

        $directory = dirname($sourceImagePath);
        $basename = basename($sourceImagePath);

        $destinationImagePath = $directory . '/' . 'thumb-' . $basename;

        Image::thumbnail($sourceImagePath, 120, 120)
            ->save($destinationImagePath, ['quality' => 85])
        ;

        return $destinationImagePath;
    }

    static public function cropImage($sourceImagePath)
    {
        if (empty($sourceImagePath)) return;

        Image::thumbnail($sourceImagePath, 120, 120)
            ->save($sourceImagePath, ['quality' => 85])
        ;
    }

    static public function scaleImage($sourceImagePath)
    {
        if (empty($sourceImagePath)) return;

        $size = getimagesize($sourceImagePath);
        $imageWidth = $size[0];
        $imageHeight = $size[1];

        if ($imageWidth <= $imageHeight) {
            ImageThumbnail::scalePortraitImage($sourceImagePath);
        }

        ImageThumbnail::scaleWideImage($sourceImagePath);
    }

    static public function scalePortraitImage($sourceImagePath)
    {
        if (empty($sourceImagePath)) return;

        Image::resize($sourceImagePath, 600, null)
            ->save($sourceImagePath, ['quality' => 85]);
    }

    static public function scaleWideImage($sourceImagePath)
    {
        if (empty($sourceImagePath)) return;

        Image::resize($sourceImagePath, 1200, null)
            ->save($sourceImagePath, ['quality' => 85]);
    }
}
