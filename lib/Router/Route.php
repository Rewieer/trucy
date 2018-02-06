<?php
/*
 * (c) Anthony Benkhebbab <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trucy\Router;


class Route {
  /**
   * @var string
   */
  private $path;

  /**
   * @var array
   */
  private $requirements;

  /**
   * @var string
   */
  private $method;

  /**
   * @var
   */
  private $action;

  /**
   * @var array
   */
  private $boundParameters = [];

  /**
   * Route constructor.
   * @param string $path
   * @param array $requirements
   * @param string $method
   * @param $action
   */
  public function __construct(string $path = "", string $method = "", $action = null, array $requirements = []) {
    $this->path = $path;
    $this->requirements = $requirements;
    $this->method = $method;
    $this->action = $action;
  }


  /**
   * Execute the action linked to the route
   * Beware that a route returning null is considered by the router as an HTTP 404 Response.
   *
   * @param array $params
   * @return mixed
   */
  public function execute(array $params = []) {
    $result = call_user_func_array($this->action, array_merge($params, $this->boundParameters));
    $this->boundParameters = [];
    return $result;
  }

  /**
   * @param array $params
   */
  public function bindParameters(array $params) {
    $this->boundParameters = $params;
  }

  /**
   * @return array
   */
  public function getBoundParameters(): array {
    return $this->boundParameters;
  }

  /**
   * @return mixed|null|string|string[]
   */
  public function getRegex() {
    $regex = preg_replace_callback("/{(\w+)}/", function($matches) {
      if (array_key_exists($matches[1], $this->requirements)) {
        return "(" .$this->requirements[$matches[1]]. ")";
      }

      return "(.+)";
    }, $this->path);

    $regex = str_replace("/", "\/", $regex);
    $regex = "/^" .$regex. "$/";
    return $regex;
  }

  /**
   * @return string
   */
  public function getPath(): string {
    return $this->path;
  }

  /**
   * @param string $path
   * @return Route
   */
  public function setPath(string $path): Route {
    $this->path = $path;
    return $this;
  }

  /**
   * @return array
   */
  public function getRequirements(): array {
    return $this->requirements;
  }

  /**
   * @param array $requirements
   * @return Route
   */
  public function setRequirements(array $requirements): Route {
    $this->requirements = $requirements;
    return $this;
  }

  /**
   * @return string
   */
  public function getMethod(): string {
    return $this->method;
  }

  /**
   * @param string $method
   * @return Route
   */
  public function setMethod(string $method): Route {
    $this->method = $method;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getAction() {
    return $this->action;
  }

  /**
   * @param mixed $action
   * @return Route
   */
  public function setAction($action) {
    $this->action = $action;
    return $this;
  }
}