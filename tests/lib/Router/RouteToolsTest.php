<?php
/**
 * Copyright (c) 2017-present, Evosphere.
 * All rights reserved.
 */

namespace Tests\Trucy\Router;

use PHPUnit\Framework\TestCase;
use Trucy\Router\Route;
use Trucy\Router\RouteTools;

class RouteToolsTest extends TestCase {
  public function testMatchingSimpleRoute() {
    $routes = RouteTools::buildRoutesFromArray([
      [
        "path" => "/",
        "method" => "GET",
        "requirements" => [],
        "action" => function() {
          return "ok";
        }
      ]
    ]);

    $route = $routes[0];
    $this->assertInstanceOf(Route::class, $route);
    $this->assertEquals("/", $route->getPath());
    $this->assertEquals("GET", $route->getMethod());
    $this->assertTrue(is_callable($route->getAction()));
    $this->assertEquals("ok", $route->execute());
  }
}