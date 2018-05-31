<?php
/**
 * Created by PhpStorm.
 * User: Grey
 * Date: 31.05.2018
 * Time: 15:38
 */

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;


class ImageUpload extends Model
{

    public $image;

    public function uploadImage(UploadedFile $file, $currentImage, $folder = 'diff')
    {
        $filename = "";

        //dump($file);
        //die();

        if ($this->validate()) {

            if (file_exists(Yii::getAlias('@web') . 'images/'.$folder.'/' . $currentImage) && $currentImage) {
                unlink($this->getFolder($folder) . $currentImage); // удаление картинки текущей если она есть
            }

            if (!file_exists($this->getFolder($folder))) {
                mkdir($this->getFolder($folder), 755); //Проверяем наличие базового каталога и создаем если его нет
            }

            if (!file_exists($this->getUserFolder($this->getFolder($folder)))) {
                mkdir($this->getUserFolder($this->getFolder($folder)), 755); //Проверяем наличие каталога пользователя и создаем если нет
            }

            $filename = $this->generateFilename($file); //генерим уникальное имя файла

            $file->saveAs($this->getUserFolder($this->getFolder($folder)) . $filename); //Грузим картинку в папку с нашими файлами

        }

        return $filename; //Возвращаем обратно имя загруженного файла
    }

    private function getFolder($folder)
    {
        return Yii::getAlias('@web') . 'images/'.$folder.'/';
    }

    public function generateFilename($file)
    {
        return strtolower(uniqid(md5($file->baseName))) . '.' . $file->extension;
    }

    private function getUserFolder($userfolder)
    {
        return $userfolder.'/user_'.Yii::$app->user->id.'/';
    }


}