<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;


class ImageUpload extends Model
{

    public $image;
    public $adsimg;

    public function uploadImage(UploadedFile $file, $currentImage, $folder = 'diff')
    {

        if ($this->validate()) {

            if (file_exists(Yii::getAlias('@web') . 'images/'.$folder.'/' . $currentImage) && $currentImage) {
                unlink($this->getFolder($folder) . $currentImage); // удаление картинки текущей если она есть
            }

            if (!file_exists($this->getFolder($folder))) {
                mkdir($this->getFolder($folder), 755);
            } //Проверяем наличие каталога и создаем если его нет

            $filname = $this->generateFilename($file); //генерим уникальное имя файла

            $file->saveAs($this->getFolder($folder) . $filname); //Грузим картинку в папку с нашими файлами объвлений

        }

        return $filname; //Возвращаем обратно имя загруженного файла
    }

    private function getFolder($folder)
    {
        return Yii::getAlias('@web') . 'images/'.$folder.'/';
    }

    public function generateFilename($file)
    {
        return strtolower(uniqid(md5($file->baseName))) . '.' . $file->extension;
    }


}