<?php
/*
 * (c) Anthony Benkhebbab <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trucy\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class ContainerAwareCommand extends Command {
  protected $container;

  /**
   * @param ContainerInterface $container
   */
  public function setContainer(ContainerInterface $container) {
    $this->container = $container;
  }

  /**
   * @return mixed
   */
  public function getContainer() {
    return $this->container;
  }
}