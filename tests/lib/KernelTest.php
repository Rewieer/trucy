<?php
/**
 * Copyright (c) 2017-present, Evosphere.
 * All rights reserved.
 */

namespace Tests\Trucy;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Trucy\Kernel;

class DummyKernel extends Kernel {
  public function getParameters() {

  }
}

class KernelTest extends TestCase {
  public function testInstantiating() {
    $kernel = new DummyKernel("prod");
    $this->assertEquals("prod", $kernel->getEnvironment());
  }

  public function testHandlingRequest() {
    $kernel = new DummyKernel("prod");
    $kernel->boot();
    $request = new Request(
      [],
      [],
      [],
      [],
      [],
      [],
      null
    );

    $kernel->handle($request);
  }
}