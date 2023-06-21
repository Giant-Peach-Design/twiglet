<?php

namespace Giantpeach\Schnapps\Twiglet;

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

    self::$instance = new \Twig\Environment(
      new \Twig\Loader\FilesystemLoader(self::$themeDir),
      [
        'cache' => false,
        'debug' => true,
      ]
    );
  }

  public static function getInstance()
  {
    if (self::$instance === null) {
      self::init();
    }

    return self::$instance;
  }
}
