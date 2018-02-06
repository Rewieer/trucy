<?php
/*
 * (c) Anthony Benkhebbab <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Trucy\Providers\Framework;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Trucy\Container;
use Trucy\Kernel;
use Trucy\Providers\Doctrine\DoctrineProvider;
use Trucy\Providers\Framework\FrameworkProvider;

class FrameworkProviderTest extends TestCase {
  public function testInstantiating() {
    $container = new Container();
    $container->setParameter("root_dir", __DIR__. "/../../../mock");
    $container->setParameter("env", "test");

    $provider = new FrameworkProvider();
    $provider->inject($container);

    $this->assertEquals(__DIR__. "/../../../mock/controller", $container->getParameter("controller_dir"));
    $this->assertEquals(__DIR__ . "/../../../mock/config", $container->getParameter("config_dir"));
    $this->assertEquals(__DIR__ . "/../../../mock/model/Entities", $container->getParameter("entities_dir"));
    $this->assertEquals(__DIR__ . "/../../../mock/model/Repositories", $container->getParameter("repositories_dir"));
    $this->assertEquals(__DIR__. "/../../../mock/var/cache/test", $container->getParameter("cache_dir"));
    $this->assertEquals(__DIR__. "/../../../mock/var/logs/test", $container->getParameter("logs_dir"));
    $this->assertEquals("127.0.0.1", $container->getParameter("database_host"));
    $this->assertEquals(null, $container->getParameter("database_port"));
    $this->assertEquals("trucy", $container->getParameter("database_name"));
    $this->assertEquals("rewieer", $container->getParameter("database_user"));
    $this->assertEquals("azerty", $container->getParameter("database_password"));
  }

}