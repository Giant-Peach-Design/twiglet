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
