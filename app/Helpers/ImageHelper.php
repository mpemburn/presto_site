<?php

namespace App\Helpers;

class ImageHelper
{
    const WP_IMAGE_META = [
        'width' => 0,
        'height' =>0,
        'file' => '',
        'filesize' => 0,
        'sizes' => [],
        'image_meta' => [
            'aperture' => 0,
            'credit' => '',
            'camera' => '',
            'caption' => '',
            'created_timestamp' => 0,
            'copyright' => '',
            'focal_length' => 0,
            'iso' => 0,
            'shutter_speed' => 0,
            'title' => '',
            'orientation' => 0,
            'keywords' => [],
        ]
    ];

    const UNSUPPORTED_TYPES = ['svg', 'gif', 'bmp', 'webp'];

    const UNSUPPORTED_MIME_TYPES = [
        'svg' => 'image/svg+xm'
    ];

    public static function getMimeType($imageUrl): string {
        $imageData = getimagesize($imageUrl);

        if (! $imageData) {
            $pathParts = pathinfo($imageUrl);
            return static::UNSUPPORTED_MIME_TYPES[$pathParts['extension']] ?: '';
        }

        return $imageData['mime'];
    }

    public static function getImageMeta($imageUrl): array
    {
        $imageMeta = static::WP_IMAGE_META;

        $pathParts = pathinfo($imageUrl);
        $imageMeta['file'] = $pathParts['filename'];

        if (in_array($pathParts['extension'], static::UNSUPPORTED_TYPES)) {
            return self::getAlternateMeta($imageUrl, $pathParts['extension'], $imageMeta);
        }

        $metaData = exif_read_data($imageUrl);
        $imageMeta['width'] = $metaData['COMPUTED']['Width'];
        $imageMeta['height'] = $metaData['COMPUTED']['Height'];
        $imageMeta['filesize'] = $metaData['FileSize'];

        return $imageMeta;
    }

    protected static function getAlternateMeta(string $imageUrl, string $extension, array &$imageMeta): array
    {
        switch ($extension) {
            case 'svg':
                return self::getSvgMeta($imageUrl, $imageMeta);
            case 'gif':
            case 'bmp':
            case 'webp':
                return self::getMetaFromImagesize($imageUrl, $imageMeta);
        }

        return [];
    }

    protected static function getMetaFromImagesize(string $imageUrl, array &$imageMeta): array
    {
        $imageData = getimagesize($imageUrl);
        $contents = file_get_contents($imageUrl);

        $imageMeta['width'] = $imageData[0];
        $imageMeta['height'] = $imageData[1];
        $imageMeta['filesize'] = strlen($contents);

        return $imageMeta;
    }

    protected static function getSvgMeta(string $imageUrl, array &$imageMeta): array
    {
        $contents = file_get_contents($imageUrl);

        collect(explode("\n", $contents))->each(function ($line) use (&$imageMeta) {
            if (stripos($line, 'width="') !== false && stripos($line, 'height="') !== false) {
                $imageMeta['width'] = RegexHelper::extractAttribute('width', $line);
                $imageMeta['height'] = RegexHelper::extractAttribute('height', $line);
            }
        });

        $imageMeta['filesize'] = strlen($contents);

        return $imageMeta;
    }
}
