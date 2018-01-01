<?php
/**
 * Copyright (c) 2017-present, Evosphere.
 * All rights reserved.
 */

namespace Tests\Trucy\Router;

use PHPUnit\Framework\TestCase;
use Trucy\Router\Route;
use Trucy\Router\RouteTools;

class Dummy {
  public function getFoo() {
    return "foo";
  }
}

class RouteTest extends TestCase {
  public function testExecutingCallable() {
    $route = new Route();
    $route->setAction(function() { return "ok"; });
    $this->assertEquals("ok", $route->execute());
  }

  public function testExecuting() {
    $route = new Route();
    $dummy = new Dummy();

    $route->setAction([$dummy, "getFoo"]);
    $this->assertEquals("foo", $route->execute());
  }

  public function testGetRegex() {
    $route = new Route("/foo");
    $expected = "/^\\/foo$/";
    $this->assertEquals($expected, $route->getRegex());
  }

  public function testGetRegexWithVariables() {
    $route = new Route("/foo/{id}");
    $expected = "/^\\/foo\/(.+)$/";
    $this->assertEquals($expected, $route->getRegex());
  }

  public function testGetRegexWithVariablesAndRequirements() {
    $route = new Route("/foo/{id}", "GET", null, [
      "id" => "\d+",
    ]);
    $expected = "/^\\/foo\/(\d+)$/";
    $this->assertEquals($expected, $route->getRegex());
  }
}