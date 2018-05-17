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
use yii\web\UploadedFile;

class UploadProject extends Model
{
    public $file;
    public $filename;

    public function uploadZip(UploadedFile $file, $currentImage, $folder = 'projects')
    {
        $filename = $file;
        //Yii::$app->session->setFlash('info', 'Тест ошибки');
        //dump($filename->type);
        Yii::$app->session->setFlash('info', 'Тест ошибки');
        if ($this->validate()) {

            //dump($folder);
            if (file_exists(Yii::getAlias('@web') .$folder.'/' . $currentImage) && $currentImage) {
                unlink($this->getFolder($folder) . $currentImage); // удаление картинки текущей если она есть
            }

            if (!file_exists($this->getFolder($folder))) {
                mkdir($this->getFolder($folder), 755);
            } //Проверяем наличие каталога и создаем если его нет

            $filename = $this->generateFilename($file); //генерим уникальное имя файла

            $file->saveAs($this->getFolder($folder) . $filename); //Грузим картинку в папку с нашими файлами

        }

        return $filename; //Возвращаем обратно имя загруженного файла
    }

    private function getFolder($folder)
    {
        return Yii::getAlias('@web') . $folder.'/';
    }

    public function generateFilename($file)
    {
        return strtolower(uniqid(md5($file->baseName))) . '.' . $file->extension;
    }



}