<?php
/*
 * (c) Anthony Benkhebbab <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trucy;

/**
 * Class ContainerSingleton
 * Provide the unique container for the whole app
 * @package Trucy
 */
class ContainerSingleton {
  /**
   * @var Container
   */
  private static $container = null;

  static function getInstance() {
    if (self::$container === null) {
      self::$container = new Container();
    }

    return self::$container;
  }
}