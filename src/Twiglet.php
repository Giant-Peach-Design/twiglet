<?php

namespace Giantpeach\Schnapps\Twiglet;

use Giantpeach\Schnapps\Twiglet\Functions\WpJsonEncode;
use Giantpeach\Schnapps\Twiglet\Functions\EscAttr;
use Giantpeach\Schnapps\Twiglet\Functions\Image;
use Performing\TwigComponents\Configuration;

use function Env\env;

class Twiglet
{
  private static $instance;
  private static $themeDir;
  private static $env;

  public static function init()
  {
    self::$env = env('WP_ENV');
    self::$themeDir = \get_template_directory();
    $options = [
      'debug' => true,
      'cache' => false,
    ];

    if (self::$env === 'production') {
      $options['cache'] = self::$themeDir . '/twig-cache';
      $options['debug'] = false;
    }

    $twig = new \Twig\Environment(
      new \Twig\Loader\FilesystemLoader([self::$themeDir, self::$themeDir . '/src/Blocks', self::$themeDir . '/src/Components', self::$themeDir . '/src/Templates']),
      [
        'cache' => false,
        'debug' => true,
      ]
    );

    self::$instance = $twig;

    self::$instance->addExtension(new WpJsonEncode());
    self::$instance->addExtension(new EscAttr());
    self::$instance->addExtension(new Image());

    Configuration::make($twig)
      ->setTemplatesPath(self::$themeDir . '/src/Components')
      ->setTemplatesExtension('twig')
      ->useCustomTags()
      ->setup();
  }

  public static function getInstance()
  {
    if (self::$instance === null) {
      self::init();
    }

    return self::$instance;
  }
}
