<?php

namespace Giantpeach\Schnapps\Twiglet\Functions;

class Image extends \Twig\Extension\AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new \Twig\TwigFilter('image', [$this, 'handleImage']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new \Twig\TwigFunction('image', [$this, 'getImage']),
            new \Twig\TwigFunction('image_tag', [$this, 'imageTag']),
            new \Twig\TwigFunction('picture_tag', [$this, 'pictureTag']),
        ];
    }

    public function getImage(array|int|string $image, string $size)
    {
        if (class_exists('\Giantpeach\Schnapps\Images\Facades\Images')) {
            if (is_array($image)) {
                $image = $image['id'];
            }

            return \Giantpeach\Schnapps\Images\Facades\Images::getImageUrlForSize($image, $size);
            //return \Giantpeach\Schnapps\Images\Facades\Images::get(image: $image, imageSize: $size);
        }

        return null;
    }

    public function imageTag(array|int|string|null $image, string $sizes = '100vw', array $widths = [375, 750, 1100, 1500, 2200], array $attributes = [], array $glideParams = [])
    {
        if (class_exists('\Giantpeach\Schnapps\Images\Facades\Images')) {
            if ($image === null) {
                return new \Twig\Markup('', 'UTF-8');
            }
            
            if (is_array($image)) {
                $image = $image['id'] ?? null;
            }
            
            if ($image === null) {
                return new \Twig\Markup('', 'UTF-8');
            }

            $html = \Giantpeach\Schnapps\Images\Facades\Images::createImageTag($image, $sizes, $widths, $attributes, $glideParams);
            return new \Twig\Markup($html, 'UTF-8');
        }

        return new \Twig\Markup('', 'UTF-8');
    }

    public function pictureTag(
        array|int|string|null $mobileImage, 
        array|int|string|null $desktopImage, 
        string $breakpoint = '640px',
        array $mobileWidths = [375, 750],
        array $desktopWidths = [1100, 1500, 2200],
        array $attributes = [],
        array $mobileGlideParams = [],
        array $desktopGlideParams = []
    )
    {
        if (class_exists('\Giantpeach\Schnapps\Images\Facades\Images')) {
            if (is_array($mobileImage)) {
                $mobileImage = $mobileImage['id'] ?? null;
            }
            if (is_array($desktopImage)) {
                $desktopImage = $desktopImage['id'] ?? null;
            }

            $html = \Giantpeach\Schnapps\Images\Facades\Images::createPictureTag(
                $mobileImage, 
                $desktopImage, 
                $breakpoint, 
                $mobileWidths, 
                $desktopWidths, 
                $attributes,
                $mobileGlideParams,
                $desktopGlideParams
            );
            return new \Twig\Markup($html, 'UTF-8');
        }

        return new \Twig\Markup('', 'UTF-8');
    }

    public function handleImage($data, $w = 500, $h = 500, $crop = true, $webp = false)
    {
        if (class_exists('\Giantpeach\Schnapps\Images\Images')) {
            $opts = [
                'w' => $w,
                'h' => $h,
                'crop' => $crop,
            ];

            if ($webp) {
                $opts['fm'] = 'webp';
            }
            return $imgUrl = \Giantpeach\Schnapps\Images\Images::getInstance()->getUrl($data, $opts);
        }

        return $data;
    }
}
