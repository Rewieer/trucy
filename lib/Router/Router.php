<?php
/*
 * (c) Anthony Benkhebbab <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trucy\Router;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Router implements RouterInterface {
  private $routes = [];

  public function __construct($routes = null) {
    if ($routes) {
      $this->routes = $routes;
    }
  }

  /**
   * Tries to match the current path with the configuration
   * and calls the controller if it does
   * @param string $path
   * @param Route $route
   * @param Request $request
   * @throws \Exception
   * @return mixed if we didn't match, we return null.
   */
  private function match(string $path, Route $route, Request $request) {
    if (preg_match($route->getRegex(), $path, $parameters)) {
      $parameters[0] = $request;
      return $route->execute($parameters);
    }

    return null;
  }

  /**
   * Lookup controllers and return a response
   * @param Request $request
   * @throws \Exception
   * @return bool|mixed|Response
   */
  public function lookup(Request $request) {
    $uri = $request->server->get("REQUEST_URI");
    $path = substr($uri, strpos($uri, "php") + 3);

    // Removing everything after the question mark
    $argsMark = strpos($path, "?");
    if ($argsMark !== false) {
      $path = substr($path,0, $argsMark);
    }

    foreach ($this->routes as $route) {
      $value = $this->match($path, $route, $request);
      if ($value !== null) {
        return $value;
      }
    }

    return null;
  }
}