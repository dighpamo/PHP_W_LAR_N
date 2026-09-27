<?php

namespace Downloader;

define('UPLOAD_PATH', BASE_PATH . '/public/uploads');

class ImageUploader {

    private const WHITE_LIST = [
        'image/jpeg', 'image/png',
        'image/gif', 'image/webp'
    ];

    public static function verify_image(array $file): bool {
        if ($file['error']) {
            throw new \Exception("Ошибка файла");
        }
        
        if ($file['size'] > 5 * 1024 * 1024) {
            throw new \Exception("Изображение слишком много весит");
        }
        $tmp_path = $file['tmp_name'];
        $imageInfo = getimagesize($tmp_path);

        if (!$imageInfo) {
            throw new \Exception("Файл не является изображением");
        }

        $mime = $imageInfo['mime'];
        if (!in_array($mime, self::WHITE_LIST)) {
            throw new \Exception("Формат не поддерживается");
        }

        return true;

    }

    public static function save_file(array $file): string {
        $source = $file['tmp_name'];
        $name = uniqid() . ".webp";
        $destination = UPLOAD_PATH . '/' . $name;

        $imageInfo = getimagesize($source);

        switch ($imageInfo['mime']) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($source);
                break;
            case'image/png':
                $image = imagecreatefrompng($source);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($source);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($source);
                break;
        }

        imagealphablending($image, true);
        imagesavealpha($image, true);

        imagewebp($image, $destination, 80);

        return '/uploads/' . $name;
    }
}
