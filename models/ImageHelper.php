<?php

namespace app\models;

/**
 * Class ImageHelper
 *
 * Simple class to manage image load, save and resize
 *
 * Usage:
 *
 * $image = new ImageHelper;
 * $image->load('image.jpg');
 * $image->resize(400, 200);
 * $image->save('image1.jpg');
 *
 */
class ImageHelper {


    private $image;
    private $image_type;

    /**
     * Load image from file
     *
     * @param $filename
     */
    public function load($filename)
    {
        $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];
        if( $this->image_type == IMAGETYPE_JPEG ) {
            $this->image = imagecreatefromjpeg($filename);
        } elseif( $this->image_type == IMAGETYPE_GIF ) {
            $this->image = imagecreatefromgif($filename);
        } elseif( $this->image_type == IMAGETYPE_PNG ) {
            $this->image = imagecreatefrompng($filename);
        }
    }

    public function putImage($imagePath,$x,$y)
    {
        if (!is_file($imagePath) || !list(,,$type) = @getimagesize($imagePath)) return false;
        switch ($type)
        {
            case 1:  $image = @imagecreatefromgif($imagePath);$params=getimagesize($imagePath);  break;
            case 2:  $image = @imagecreatefromjpeg($imagePath);$params=getimagesize($imagePath);  break;
            case 3:  $image = @imagecreatefrompng($imagePath);$params=getimagesize($imagePath);  break;
            default: $image = false;
        }
        if ($image)
            imagecopy($this->image,$image,$x,$y,0,0,$params[0],$params[1]);
    }

    /**
     * Save image to file
     *
     * @param $filename
     * @param int $image_type
     * @param int $compression
     * @param null $permissions
     */
    public function save($filename, $image_type=IMAGETYPE_JPEG, $compression=100, $permissions=null, $delitecurrentimage = true)
    {
        if( $image_type == IMAGETYPE_JPEG ) {
            imagejpeg($this->image,$filename,$compression);
        } elseif( $image_type == IMAGETYPE_GIF ) {
            imagegif($this->image,$filename);
        } elseif( $image_type == IMAGETYPE_PNG ) {
            imagepng($this->image,$filename);
        }
        if( $permissions != null) {
            chmod($filename,$permissions);
        }
        ($delitecurrentimage) ? imagedestroy($this->image) : false;
    }

    public function output($image_type=IMAGETYPE_JPEG)
    {
        if( $image_type == IMAGETYPE_JPEG ) {
            imagejpeg($this->image);
        } elseif( $image_type == IMAGETYPE_GIF ) {
            imagegif($this->image);
        } elseif( $image_type == IMAGETYPE_PNG ) {
            imagepng($this->image);
        }
        imagedestroy($this->image);
    }


    public function getWidth()
    {
        return imagesx($this->image);
    }

    public function getHeight()
    {
        return imagesy($this->image);
    }

    public function resizeToHeight($height)
    {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width,$height);
    }

    public function resizeToWidth($width)
    {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width,$height);
    }

    public function scale($scale)
    {
        $width = $this->getWidth() * $scale/100;
        $height = $this->getheight() * $scale/100;
        $this->resize($width,$height);
    }

    public function resize($width,$height)
    {
        $new_image = imagecreatetruecolor($width, $height);
        imagealphablending($new_image, false);
        imagesavealpha($new_image, true);
        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
    }

    public function smartCrop($width,$height)
    {
        $w_ratio = $this->getWidth() / $width;
        $h_ratio = $this->getHeight() / $height;
        if ($w_ratio>$h_ratio){
            $new_width = $h_ratio * $width;
            $new_height = $this->getHeight();
            $this->crop(($this->getWidth() - $new_width)/2,0,$new_width,$new_height);
        }
        if ($h_ratio>$w_ratio){
            $new_height = $w_ratio * $height;
            $new_width = $this->getWidth();
            $y = ($this->getHeight() - $new_height)/2;
            $this->crop(0,$y,$new_width,$new_height);
        }

    }

    private function crop($x0, $y0, $w, $h)
    {
        $newImage = imagecreatetruecolor($w, $h);
        imagecopyresampled($newImage, $this->image, 0, 0, $x0, $y0, $w, $h, $w, $h);
        $this->image = $newImage;
    }
}
