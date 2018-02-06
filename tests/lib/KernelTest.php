<?php
/**
 * Copyright (c) 2017-present, Evosphere.
 * All rights reserved.
 */

namespace Tests\Trucy;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Trucy\ContainerSingleton;
use Trucy\Kernel;
use Trucy\Providers\Doctrine\DoctrineProvider;
use Trucy\Providers\Framework\FrameworkProvider;

class DummyKernel extends Kernel {
  public function getRootDir() {
    return __DIR__. "/../mock";
  }

  public function registerProviders() {
    return [
      new FrameworkProvider(),
      new DoctrineProvider()
    ];
  }
}

class KernelTest extends TestCase {
  protected function setUp() {
    ContainerSingleton::reset();
  }

  public function testInstantiating() {
    $kernel = new DummyKernel("prod");
    $this->assertEquals("prod", $kernel->getEnvironment());
  }

  public function testSettingUp() {
    $kernel = new DummyKernel("prod");
    $kernel->boot();
    $container = $kernel->getContainer();
    $this->assertEquals("127.0.0.1", $container->getParameter("database_host"));
    $this->assertEquals(null, $container->getParameter("database_port"));
    $this->assertEquals("trucy", $container->getParameter("database_name"));
    $this->assertEquals("rewieer", $container->getParameter("database_user"));
    $this->assertEquals("azerty", $container->getParameter("database_password"));
  }

  public function testHandlingRequest() {
    $kernel = new DummyKernel("prod");
    $request = new Request(
      [],
      [],
      [],
      [],
      [],
      [
        "REQUEST_URI" => "foo/bar/index.php/user"
      ],
      null
    );

    /** @var Response $response */
    $response = $kernel->handle($request);
    $this->assertInstanceOf(Response::class, $response);
    $this->assertEquals("This is the list of users", $response->getContent());
  }
}