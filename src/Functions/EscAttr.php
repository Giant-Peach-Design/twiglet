<?php

namespace Giantpeach\Schnapps\Twiglet\Functions;

class EscAttr extends \Twig\Extension\AbstractExtension
{
  public function getFilters(): array
  {
    return [
      new \Twig\TwigFilter('esc_attr', [$this, 'esc_attr']),
    ];
  }

  public function esc_attr($data)
  {
    return esc_attr($data);
  }
}
