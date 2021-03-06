<?php

namespace ZEDx\Utils;

use File;
use Image;
use Intervention\Image\Exception\NotReadableException;

class PhotoManager
{
    private $hash;
    private $files = [];

    public function save($imgs)
    {
        if (!is_array($imgs)) {
            $imgs = [$imgs];
        }
        $imgs = array_filter($imgs);
        foreach ($imgs as $data) {
            try {
                $img = Image::make($data);
            } catch (NotReadableException $e) {
                return [];
            }

            $this->randomPath();

            $this->createImage($img, config('zedx.images.large'));
            $this->createImage(Image::make($data), config('zedx.images.medium'));
            $this->createImage(Image::make($data), config('zedx.images.thumb'));

            $this->files[] = ['path' => $this->hash.'.jpg'];
        }

        return $this->files;
    }

    protected function createImage($img, $config)
    {
        $size = $config['size'];
        if ($config['resizeCanvas'] && ($img->width() < $size['width'] || $img->height() < $size['height'])) {
            $img->resizeCanvas($size['width'], $size['height'], 'center', false, $config['colorCanvas']);
        } else {
            $img->resize($size['width'], $size['height'], function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        if ($config['watermark']) {
            $watermarkConfig = config('zedx.watermark');
            $watermark = Image::make(public_path($watermarkConfig['path']));
            $watermark->resize($watermarkConfig['size']['width'], $watermarkConfig['size']['height']);
            $img->insert($watermark, $watermarkConfig['position']);
        }

        $img->encode('jpg', 75);
        File::put(public_path($config['path'].'/'.$this->hash.'.jpg'), $img->getEncoded());
    }

    protected function randomPath()
    {
        while (true) {
            $fileName = hash('crc32', md5(rand()));
            $this->hash = $fileName;
            $file = config('zedx.images.large.path').'/ad-'.$fileName.'.jpg';
            if (File::exists(public_path($file))) {
                continue;
            }

            return $file;
        }

        return false;
    }
}
