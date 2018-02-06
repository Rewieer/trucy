<?php
/*
 * (c) Anthony Benkhebbab <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trucy\Providers\Doctrine\Services;

use Doctrine\ORM\EntityManager;

class EntityManagerFactory {
  /**
   * @var array
   */
  private $params;

  /**
   * @var
   */
  private $config;

  public function __construct($params, $config) {
    $this->params = $params;
    $this->config = $config;
  }

  public function create() {
    return EntityManager::create($this->params, $this->config);
  }
}