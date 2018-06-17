<?php
/**
 * Created by PhpStorm.
 * User: Grey
 * Date: 16.05.2018
 * Time: 13:24
 */

namespace app\models;

use Yii;

use yii\base\Model;
use yii\helpers\Json;
use yii\web\UploadedFile;


class DownloadProject extends Model
{

    public static function getFileSize( $path )
    {
        $fileSize   = filesize($path);
        return Yii::$app->formatter->asShortSize( $fileSize );
    }

}