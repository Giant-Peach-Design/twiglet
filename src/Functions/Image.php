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
      return $imgUrl = \Giantpeach\Schnapps\Images\Images::getInstance()->getGlideImageUrl($data, $opts);
    }

    return $data;
  }
}
