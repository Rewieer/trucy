<?php
/*
 * (c) Anthony Benkhebbab <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trucy\Providers\Framework;

use Trucy\Container;

abstract class AbstractController {
  /**
   * @var Container
   */
  private $container;

  /**
   * @param Container $container
   */
  public function setContainer(Container $container) {
    $this->container = $container;
  }

  /**
   * @return Container
   */
  public function getContainer() {
    return $this->container;
  }
}