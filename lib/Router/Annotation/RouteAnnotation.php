<?php
/*
 * (c) Anthony Benkhebbab <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trucy\Router\Annotation;

/** @Annotation */
class RouteAnnotation {
  public $path;
  public $method;
  public $requirements;

  public function __construct(array $values) {
    $this->path = $values["value"];
    $this->method = array_key_exists("method", $values) ? $values["method"] : "GET";
    $this->requirements = array_key_exists("requirements", $values) ? $values["requirements"] : [];
  }
}