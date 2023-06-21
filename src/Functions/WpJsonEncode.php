<?php

namespace Giantpeach\Schnapps\Twiglet\Functions;

class WpJsonEncode extends \Twig\Extension\AbstractExtension
{
  public function getFilters(): array
  {
    return [
      new \Twig\TwigFilter('wp_json_encode', [$this, 'wp_json_encode']),
    ];
  }

  public function wp_json_encode($data, $options = 0, $depth = 512)
  {
    return wp_json_encode($data, $options, $depth);
  }
}
