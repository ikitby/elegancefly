<?php
/**
 * Created by PhpStorm.
 * User: Grey
 * Date: 16.05.2018
 * Time: 13:24
 */

namespace app\models;

use PHPUnit\Framework\Constraint\IsJson;
use Yii;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\UploadedFile;
use dastanaron\translit\Translit;
//use app\models\PclZip;

class UploadProject extends Model
{
    public $file;
    public $filename;

    public function uploadZip(UploadedFile $file, $userfolder = 'user_00', $projectpatf = '', $model)
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


        }

        return $filename; //Возвращаем обратно имя загруженного файла
    }

    public function makeGalery($file)
    {
        //займемся распаковкой архива
        $catfolder = $this->getCatFolder('cat'); //Определяем генерацию папок и подпапок для галлерей проектов
        $file->saveAs($catfolder . '/' . $file->name); //Грузим картинку в папку с нашими файлами
        $goodarchive = false;
        $archive = new PclZip();
        $archive->PclZip($catfolder . '/' . $file->name); //получили
        $result = $archive->extract(PCLZIP_OPT_PATH, $catfolder); //распаковали
        unlink($catfolder . '/' . $file->name); // удаление Архива исходника
        $photos = array();
        $i = 0;
        $limit = 10;
        foreach ($result as $file) //пересобираем массив результата распаковки и удаляем не нужные файлы
        {
            if (preg_match("/^.*?\.psd$/i", $file['filename']) || !preg_match("/^.*?_\d\.\w{3}$/i", $file['filename'])) {unlink($file['filename']); //Проверяем что за файд и если это psd или не содержит в имени _**
            } elseif ($i < $limit && preg_match("/^.*?_\d\.\w{3}$/i", $file['filename'])) {
                //Если файл соответствует маске файла для галереи и не больше лимита по количеству дополняем массив и инкременируем счетчик
                preg_match("/^(.*?)\/\w*_\d\.\w{3}$/i", $file['filename'], $matches); //Ищем путь к файлу
                preg_match("/^.*\/(.*?)$/i", $file['filename'], $filenam); //Ищем имя файла
                $photos[$i] = [
                    'foolpath' => $file['filename'],
                    'filepath' => $matches[1].'/',
                    'filename' => $filenam[1]
                ];
               $i++;
            } else {
                unlink($file['filename']);
            }
            sort($photos);
        }

        $photos = Json::encode($photos);
        return $photos;

        $galery = ArrayHelper::getColumn($result, 'filename'); //Получаем список файлов распакованных из архива
        dump($result);

        $image = new ImageHelper();
        $i = 0;
        while ($i < 10) {
            if ($galery[$i] == null) {break;}
            ($galery[$i] == $catfolder .'/' . $file->baseName. '.psd') ? false : $photogalery[$i] = $galery[$i];
            $i++;
        }
        sort($photogalery);
        $photogalery = implode(',', $photogalery); //Список картинок в строку
        /*
        foreach ($photogalery as $photo) {

            $image->load($photo); //грузим картинку текущую
            dump($image);
            die();

            $image->resize(100, 100); //ресайзим
            $image->save($file->baseName);

        }
*/
        if($result == 0) echo $archive->errorInfo(true);
        return $photogalery;
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

}