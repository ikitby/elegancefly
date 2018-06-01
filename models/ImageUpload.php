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
        $image = new ImageHelper();

        if ($this->validate()) {

            $filepath = $this->getUserFolder($this->getFolder($folder));

            if (file_exists(Yii::getAlias('@web') . $filepath . $currentImage) && $currentImage) {
                unlink($filepath . $currentImage); // удаление картинки текущей если она есть
            }
            //тупая проверка и уничтожение всех вариация превьюшек
            if (file_exists($filepath . '200_200_'.$currentImage)) {unlink($filepath . '200_200_'.$currentImage);}
            if (file_exists($filepath . '100_100_'.$currentImage)) {unlink($filepath . '100_100_'.$currentImage);}
            if (file_exists($filepath . '50_50_'.$currentImage)) {unlink($filepath . '50_50_'.$currentImage);}
            //тупая проверка и уничтожение всех вариация превьюшек

            if (!file_exists($this->getFolder($folder))) {
                mkdir($this->getFolder($folder), 755); //Проверяем наличие базового каталога и создаем если его нет
            }

            if (!file_exists($filepath)) {
                mkdir($filepath, 755); //Проверяем наличие каталога пользователя и создаем если нет
            }

            $filename = $this->generateFilename($file); //генерим уникальное имя файла

            $file->saveAs($filepath . $filename); //Грузим картинку в папку с нашими файлами

            $image->load($filepath.$filename);

            $image->resize(400, 400); //ресайзим картинку
            $image->save($filepath . $filename, IMAGETYPE_JPEG, 80, null, false);

            $image->resize(200, 200); //ресайзим картинку
            $image->save($filepath . '200_200_'.$filename, IMAGETYPE_JPEG, 80, null, false);

            $image->resize(100, 100); //ресайзим картинку
            $image->save($filepath . '100_100_'.$filename, IMAGETYPE_JPEG, 80, null, false);

            $image->resize(50, 50); //ресайзим картинку
            $image->save($filepath . '50_50_'.$filename, IMAGETYPE_JPEG, 80, null, true);


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
        return $userfolder.'user_'.Yii::$app->user->id.'/';
    }


}