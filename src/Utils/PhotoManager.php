<?php

namespace ZEDx\Utils;

use Image;
use Intervention\Image\Exception\NotReadableException;
use Storage;

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
            $img->fit($size['width'], $size['height']);
        }
        if ($config['watermark']) {
            $watermark = config('zedx.watermark');
            $img->insert(Storage::get($watermark['path']), $watermark['position']);
        }

        $img->encode('jpg', 75);
        Storage::put($config['path'].'/'.$this->hash.'.jpg', $img->getEncoded());
    }

    protected function randomPath()
    {
        while (true) {
            $fileName = hash('crc32', md5(rand()));
            $this->hash = $fileName;
            $file = config('zedx.images.large.path').'/ad-'.$fileName.'.jpg';
            if (Storage::exists($file)) {
                continue;
            }

            return $file;
        }

        return false;
    }
}
