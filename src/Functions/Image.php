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

  public function handleImage($data, $options = ['w' => 500, 'h' => 500, 'crop' => true],)
  {
    if (class_exists('\Giantpeach\Schnapps\Images\Images')) {
      return $imgUrl = \Giantpeach\Schnapps\Images\Images::getInstance()->getGlideImageUrl($data, $options);
    }

    return false;
  }
}
