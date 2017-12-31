<?php
/*
 * (c) Anthony Benkhebbab <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Providers\Twig\Service;

class Twig extends \Twig_Environment {
  public function __construct(string $cache, string $views, string $env) {
    $debug = $env !== "prod";
    $loader = new \Twig_Loader_Filesystem($views);

    parent::__construct($loader, [
      "cache" => $cache. "/twig",
      "debug" => $debug,
      "auto_reload" => $debug,
    ]);
  }
}