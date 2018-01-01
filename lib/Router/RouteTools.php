<?php
/*
 * (c) Anthony Benkhebbab <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trucy\Router;


class RouteTools {
  public static function buildRoutesFromArray($array) {
    return array_map(function($def) {
      $route = new Route();
      $route
        ->setPath($def["path"])
        ->setMethod($def["method"])
        ->setAction($def["action"])
        ->setRequirements($def["requirements"])
        ;

      return $route;
    }, $array);
  }
}