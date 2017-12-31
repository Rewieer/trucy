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
  private $configuration = [];

  public function __construct($configuration = null) {
    if ($configuration) {
      $this->configuration = $configuration;
    }
  }

  /**
   * Initialize the router
   * @param array $configuration
   */
  public function init(array $configuration) {
    $this->configuration = $configuration;
  }

  /**
   * @param $callable
   * @param array $data
   * @return mixed
   * @throws \Exception
   */
  private function exec($callable, array $data) {
    return call_user_func_array($callable, $data);
  }

  /**
   * Tries to match the current path with the configuration
   * and calls the controller if it does
   * @param string $path
   * @param array $conf
   * @param Request $request
   * @throws \Exception
   * @return bool|mixed
   */
  private function match(string $path, array $conf, Request $request) {
    $requirements = array_key_exists("requirements", $conf) && is_array($conf["requirements"])
      ? $conf["requirements"]
      : [];

    $regex = preg_replace_callback("/{(\w+)}/", function($matches) use ($requirements) {
      if (array_key_exists($matches[1], $requirements)) {
        return "(" .$requirements[$matches[1]]. ")";
      }

      return "(.+)";
    }, $conf["path"]);

    $regex = str_replace("/", "\/", $regex);
    $regex = "/^" .$regex. "$/";
    $data = [];

    if (preg_match($regex, $path, $data)) {
      $parameters = $data;
      $parameters[0] = $request;
      return $this->exec($conf["callable"], $parameters);
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

    // Removing everything after the "?"
    $argsMark = strpos($path, "?");
    if ($argsMark !== false) {
      $path = substr($path,0, $argsMark);
    }

    foreach ($this->configuration as $conf) {
      $value = $this->match($path, $conf, $request);
      if ($value !== null) {
        return $value;
      }
    }

    return null;
  }
}