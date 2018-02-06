<?php
/*
 * (c) Anthony Benkhebbab <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trucy\Controller;


abstract class AbstractController implements ControllerInterface {
  public $method = ""; // HTTP Method
  public $path = "";
  public $requirements = [];

  public function getMethod(): string {
    return $this->method;
  }

  public function getPath(): string {
    return $this->path;
  }

  public function getRequirements(): array {
    return $this->requirements;
  }
}