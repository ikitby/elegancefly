<?php
/**
 * Created by PhpStorm.
 * User: Grey
 * Date: 16.05.2018
 * Time: 13:24
 */

namespace app\models;

use Imagick;
//use PHPUnit\Framework\Constraint\IsJson;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use Yii;

use yii\base\Model;
//use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
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
        //Yii::$app->session->setFlash('info', 'Текст ошибки');
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
        $image = new ImageHelper();
        $watermark = new ImageHelper();
        //займемся распаковкой архива и генерацией всех вариантов размеров картинок
        $catfolder = $this->getCatFolder('cat'); //Определяем генерацию папок и подпапок для галлерей проектов
        $file->saveAs($catfolder . '/' . $file->name, false); //Грузим картинку в папку с нашими файлами
        //$goodarchive = false;
        $archive = new PclZip();
        $archive->PclZip($catfolder . '/' . $file->name); //получили
        $resultlist = $archive->listContent();
        $result = $archive->extract(PCLZIP_OPT_PATH, $catfolder); //распаковали
        unlink($catfolder . '/' . $file->name); // удаление Архива исходника
        $photos = array();
        $i = 0;
        $limit = 10;

/*          ================= Сохраняет в кталоге тектовй файлик с массивом файлов содержимного архива */
        //предварительно даляем все папки с содержимым из архива

        foreach ($resultlist as $item) {
            if ($item['folder'] == true) { //Если видим папку - удаляем со всей требухой
                FileHelper::removeDirectory($catfolder.'/'.$item['filename']);
            }
        }

        foreach ($resultlist as $item) {
            //$item = serialize($item);
            $item_ext = pathinfo($item['filename'], PATHINFO_EXTENSION); //расширение файла
            $item_name = basename($item['filename'], '.'.$item_ext); //Имя файла

            if ($item['folder'] == true ) { //Если видим папку - удаляем со всей требухой
                FileHelper::removeDirectory($catfolder.'/'.$item['filename']);
                //continue;
            } elseif (!preg_match("/^preview_\d\.png|jpe?g$/i", $item['filename']) || $item['size'] > 1024*1024*3){ //Проверяем файл. И если он не нужен - удаляем

                if (file_exists($catfolder.'/'.$item['filename'])) {
                    unlink($catfolder.'/'.$item['filename']);
                    //continue;
                }

            } elseif (preg_match("/^preview_\d\.png|jpe?g$/i", $item['filename'])) { //Если все соответствует маске превьюшки и размер файл

                $photos[$i] = [
                    'number'   => $i,
                    'foolpath' => $catfolder.'/'.$item['filename'],
                    'filepath' => $catfolder.'/',
                    'filename' => $item['filename']
                ];
                $i++;
            }

        }

        //Смотрим сколько превьюшек нашел скрипт и если ни одной - сообщаем
        if ($i == 0){
            //Если нет ни одной превьюшки - прерываем загрузку и сощаем что там все плохо
            Yii::$app->session->setFlash('warning', 'Ваш пакет не соответствует требованиям и не может быть принят.');
            Yii::$app->getResponse()->redirect( '/profile/myprojects'); # Укажите ссылку
            Yii::$app->end();
            Return false;
        }
        //Если все уложилось - создаем галлерею
        sort($photos);
/*
        $fp = fopen($catfolder.'/'.'log.txt', 'a+');
        fwrite($fp, serialize($photos) . PHP_EOL);
        fclose($fp);
*/
        $photonumber = 0;
        foreach ($photos as $photo) {

            //if (file_exists($photo['foolpath']))
            //dump($photo['number']);
            if (!file_exists($photo['foolpath'])) continue;

            $photo['number'] = $photonumber;
            $image->load($photo['foolpath']); //грузим текущую картинку

            $newname = $photo['number'].'_'.md5(uniqid()).'.jpg';
            $newphoto = $photo['filepath'].$newname; //генерим новое рандомное имя для картинки а формате jpg
            $image->resize(600, 600); //ресайзим картинку
            $image->save($newphoto, IMAGETYPE_JPEG, 80, null, false);//сохраняем картинку нового размера
            //тут будем накладывать вотермарк
            $this->putWotermark($newphoto);

            $image->resize(400, 400); //ресайзим картинку 400/400
            $image->save($photo['filepath'].'400_400_'.$newname, IMAGETYPE_JPEG, 80, null, false);

            $image->resize(200, 200); //ресайзим картинку 200/200
            $image->save($photo['filepath'].'200_200_'.$newname, IMAGETYPE_JPEG, 80, null, false);

            $image->resize(100, 100); //ресайзим картинку 100/100
            $image->save($photo['filepath'].'100_100_'.$newname, IMAGETYPE_JPEG, 80); //последний ресайз удаляет за собой временную картинку

            //сохраняем измененную картинку и, если сохранили, удаляем оригинал и меняем имя в массиве
            unlink($photo['foolpath']);
            $photos[$photo['number']]['foolpath'] = $newphoto; //подменяем полный пть к картинке
            $photos[$photo['number']]['filename'] = $newname; //подменяем имя картинки

            $photonumber++;
        }

        $photos = Json::encode($photos); //в этом месте имеем готовый массив фотографий для галлереи

        return $photos;
    }


    /*
        public function makeGalery($file)
        {
            $image = new ImageHelper();
            $watermark = new ImageHelper();
            //займемся распаковкой архива и генерацией всех вариантов размеров картинок
            $catfolder = $this->getCatFolder('cat'); //Определяем генерацию папок и подпапок для галлерей проектов
            $file->saveAs($catfolder . '/' . $file->name, false); //Грузим картинку в папку с нашими файлами
            //$goodarchive = false;
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
                    preg_match("/^.*\/(.*?)$/i", $file['filename'], $filename); //Ищем имя файла
                    $photos[$i] = [
                        'number'   => $i,
                        'foolpath' => $file['filename'],
                        'filepath' => $matches[1].'/',
                        'filename' => $filename[1]
                    ];
                   $i++;
                } else {
                    unlink($file['filename']);
                }
                sort($photos);
            }

            foreach ($photos as $photo) {
                $image->load($photo['foolpath']); //грузим текущую картинку

                $newname = $photo['number'].'_'.md5(uniqid()).'.jpg';
                $newphoto = $photo['filepath'].$newname; //генерим новое рандомное имя для картинки а формате jpg
                $image->resize(600, 600); //ресайзим картинку
                $image->save($newphoto, IMAGETYPE_JPEG, 80, null, false);//сохраняем картинку нового размера
                    //тут будем накладывать вотермарк
                $this->putWotermark($newphoto);

                $image->resize(400, 400); //ресайзим картинку 400/400
                $image->save($photo['filepath'].'400_400_'.$newname, IMAGETYPE_JPEG, 80, null, false);

                $image->resize(200, 200); //ресайзим картинку 200/200
                $image->save($photo['filepath'].'200_200_'.$newname, IMAGETYPE_JPEG, 80, null, false);

                $image->resize(100, 100); //ресайзим картинку 100/100
                $image->save($photo['filepath'].'100_100_'.$newname, IMAGETYPE_JPEG, 80); //последний ресайз удаляет за собой временную картинку

                    //сохраняем измененную картинку и, если сохранили, удаляем оригинал и меняем имя в массиве
                    unlink($photo['foolpath']);
                    $photos[$photo['number']]['foolpath'] = $newphoto; //подменяем полный пть к картинке
                    $photos[$photo['number']]['filename'] = $newname; //подменяем имя картинки

            }

            $photos = Json::encode($photos); //в этом месте имеем готовый массив фотографий для галлереи

            return $photos;
        }
    */

    private function putWotermark($pathtoimage){

        // Загружаем оригинальное изображение
        $image = new Imagick();
        $image->readImage(__DIR__.'/../web/'.$pathtoimage);

        $w = $image->getImageWidth();
        $h = $image->getImageHeight();

        $imageWatermark = new Imagick();
        $imageWatermark->readImage(__DIR__.'/../web/res/watermark.png');

        $ww = $imageWatermark->getImageWidth();
        $wh = $imageWatermark->getImageHeight();

        //Отступ снизу
        $paddingBottom = 20;

        //Отступ справа
        $paddingRight = 20;

        //Это позволяет поставить изображение в нижний правый угол (учитывая отступы)
        $x = ($w - $ww) - $paddingRight;
        $y = ($h - $wh) - $paddingBottom;

        $image->compositeImage($imageWatermark, imagick::COMPOSITE_OVER, $x, $y);
        $image->writeImage(__DIR__.'/../web/'.$pathtoimage);
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
        //$this->setEmpteHtml($catfolder);
        if (!file_exists($catfolder)) mkdir($catfolder, 755);
        return $catfolder;
    }

    private function setEmpteHtml($catfolder = '/web/cat'){
        $fp = fopen($catfolder.'/'.'index.html', 'a+');
        fwrite($fp, '' . PHP_EOL);
        fclose($fp);
        return true;
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