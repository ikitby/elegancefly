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
use dastanaron\translit\Translit;
//use app\models\PclZip;

class UploadProject extends Model
{
    public $file;
    public $filename;

    public function uploadZip(UploadedFile $file, $userfolder = 'user_00', $projectpatf = '')
    {
        $filename = '';

        $folder = $userfolder.'/'.$projectpatf;
        //Yii::$app->session->setFlash('info', 'Тест ошибки');
        //dump($filename->type);
        //Yii::$app->session->setFlash('info', 'Тест ошибки');
        if ($this->validate()) {

            if (!file_exists($this->getFolder($folder))) {
                mkdir($this->getFolder($folder), 755);
            } //Проверяем наличие каталога и создаем если его нет

            $filename = $this->generateFilename($file); //генерим уникальное имя файла

            $file->saveAs($this->getFolder($folder) . '/' . $filename); //Грузим картинку в папку с нашими файлами
            //займемся распаковкой архива

            $catfolder = $this->getCatFolder('catalog');

            $archive = new PclZip();
            $archive->PclZip($this->getFolder($folder) . '/' . $filename);
            $result = $archive->extract(PCLZIP_OPT_PATH, $this->getFolder($folder));
            dump($result);

            if($result == 0) echo $archive->errorInfo(true);

            dump($catfolder);
            die();

        }

        return $filename; //Возвращаем обратно имя загруженного файла
    }

    private function getFolder($folder)
    {
        return Yii::getAlias('@web') . $folder;
    }

    public function generateFilename($file)
    {
        return $this->translite($file->baseName) . '.' . $file->extension;
    }

    public function translite($text)
    {
        $translit = new Translit();
        return $text = strtolower($translit->translit($text, true, 'ru-en'));
    }

    private function getCatFolder($basefolder = 'catalog')
    {
        $cat = $basefolder;
        if (!file_exists($cat)) mkdir($cat, 755);
        $catfoldery = $cat.'/'.date("Y");
        if (!file_exists($catfoldery)) mkdir($catfoldery, 755);
        $catfolderm = $catfoldery.'/'.date("m");
        if (!file_exists($catfolderm)) mkdir($catfolderm, 755);
        $catfolder = $catfolderm.'/'.date("d");
        if (!file_exists($catfolder)) mkdir($catfolder, 755);
        return $catfolder;
    }

}