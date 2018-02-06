<?php
/**
 * Copyright (c) 2017-present, Evosphere.
 * All rights reserved.
 */

namespace Tests\Trucy\Router;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Trucy\Router\Route;
use Trucy\Router\Router;

class RouterTest extends TestCase {
  public function testMatchingSimpleRoute() {
    $router = new Router([
      $route = new Route("/", "GET", function() { return "ok"; }),
    ]);

    $request = new Request();
    $request->server->set("REQUEST_URI", "/foo/bar/index.php/");
    $foundRoute = $router->lookup($request);

    $this->assertEquals($route, $foundRoute);
    $this->assertEquals([$request], $foundRoute->getBoundParameters());
  }
  public function testMatchingWithManyRoutes() {
    $router = new Router($routes = [
      new Route("/", "GET", function() { return "not ok"; }),
      new Route("/foo", "GET", function() { return "ok"; }),
    ]);

    $request = new Request();
    $request->server->set("REQUEST_URI", "/foo/bar/index.php/foo");
    $foundRoute = $router->lookup($request);

    $this->assertEquals($routes[1], $foundRoute);
    $this->assertEquals([$request], $foundRoute->getBoundParameters());
  }
  public function testMatchingWithParameter() {
    $router = new Router($routes = [
      new Route("/", "GET", function() { return "not ok"; }),
      new Route("/{id}/{slug}", "GET", function($request, $param, $slug) {
        return "param is " .$param. " and slug is " .$slug;
      }),
    ]);

    $request = new Request();
    $request->server->set("REQUEST_URI", "/foo/bar/index.php/1/foo");
    $foundRoute = $router->lookup($request);

    $this->assertEquals($routes[1], $foundRoute);
    $this->assertEquals([$request, 1, "foo"], $foundRoute->getBoundParameters());
  }
  public function testWithQueryParameters() {
    // The ?foo=bar part of the URL is not passed to the router
    // Because it can be fetched from the request's query bag
    $router = new Router($routes = [
      new Route("/", "GET", function() { return "not ok"; }),
      new Route("/{id}", "GET", function($request, $param) {
        return "param is " .$param;
      }),
    ]);

    $request = new Request();
    $request->server->set("REQUEST_URI", "/foo/bar/index.php/1?foo=bar");
    $foundRoute = $router->lookup($request);
    $this->assertEquals($routes[1], $foundRoute);
    $this->assertEquals([$request, 1], $foundRoute->getBoundParameters());
  }
  public function testWithRequirements() {
    $router = new Router($routes = [
      new Route("/", "GET", function() { return "not ok"; }),
      new Route("/{id}/{slug}", "GET", function($request, $id, $slug) {
        return "id : " .$id. " - slug : " .$slug;
      }, [
        "id" => "\d+",
        "slug" => "\w+",
      ]),
    ]);

    $request = new Request();
    $request->server->set("REQUEST_URI", "/foo/bar/index.php/1/hehe");

    $this->assertEquals($routes[1], $foundRoute = $router->lookup($request));
    $this->assertEquals([$request, 1, "hehe"], $foundRoute->getBoundParameters());

    $request = new Request();
    $request->server->set("REQUEST_URI", "/foo/bar/index.php/hehe/1");
    $this->assertEquals(null, $router->lookup($request));
  }
}