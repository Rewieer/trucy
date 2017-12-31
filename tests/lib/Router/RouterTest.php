<?php
/**
 * Copyright (c) 2017-present, Evosphere.
 * All rights reserved.
 */

namespace Tests\Trucy;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Trucy\Router\Router;

class RouterTest extends TestCase {
  public function testMatchingSimpleRoute() {
    $router = new Router([
      [
        "path" => "/",
        "method" => "GET",
        "callable" => function() {
          return "ok";
        }
      ]
    ]);

    $request = new Request();
    $request->server->set("REQUEST_URI", "/foo/bar/index.php/");
    $response = $router->lookup($request);

    $this->assertEquals("ok", $response);
  }
  public function testMatchingWithManyRoutes() {
    $router = new Router([
      [
        "path" => "/",
        "method" => "GET",
        "callable" => function() {
          return "not ok";
        }
      ],
      [
        "path" => "/foo",
        "method" => "GET",
        "callable" => function() {
          return "ok";
        }
      ]
    ]);

    $request = new Request();
    $request->server->set("REQUEST_URI", "/foo/bar/index.php/foo");
    $response = $router->lookup($request);

    $this->assertEquals("ok", $response);
  }
  public function testMatchingWithParameter() {
    $router = new Router([
      [
        "path" => "/",
        "method" => "GET",
        "callable" => function() {
          return "not ok";
        }
      ],
      [
        "path" => "/{id}/{slug}",
        "method" => "GET",
        "callable" => function($request, $param, $slug) {
          return "param is " .$param. " and slug is " .$slug;
        }
      ]
    ]);

    $request = new Request();
    $request->server->set("REQUEST_URI", "/foo/bar/index.php/1/foo");
    $response = $router->lookup($request);

    $this->assertEquals("param is 1 and slug is foo", $response);
  }
  public function testWithQueryParameters() {
    // The ?foo=bar part of the URL is not passed to the router
    // Because it can be fetched from the request's query bag
    $router = new Router([
      [
        "path" => "/",
        "method" => "GET",
        "callable" => function() {
          return "not ok";
        }
      ],
      [
        "path" => "/{id}",
        "method" => "GET",
        "callable" => function($request, $param) {
          return "param is " .$param;
        }
      ]
    ]);

    $request = new Request();
    $request->server->set("REQUEST_URI", "/foo/bar/index.php/1?foo=bar");
    $response = $router->lookup($request);

    $this->assertEquals("param is 1", $response);
  }
  public function testWithRequirements() {
    $router = new Router();
    $router->init([
      [
        "path" => "/",
        "method" => "GET",
        "callable" => function() {
          return "not ok";
        }
      ],
      [
        "path" => "/{id}/{slug}",
        "requirements" => [
          "id" => "\d+",
          "slug" => "\w+",
        ],
        "method" => "GET",
        "callable" => function($request, $id, $slug) {
          return "id : " .$id. " - slug : " .$slug;
        }
      ]
    ]);

    $request = new Request();
    $request->server->set("REQUEST_URI", "/foo/bar/index.php/1/hehe");

    $response = $router->lookup($request);

    $this->assertEquals("id : 1 - slug : hehe", $response);

    $request = new Request();
    $request->server->set("REQUEST_URI", "/foo/bar/index.php/hehe/1");

    $response = $router->lookup($request);
    $this->assertEquals(null, $response);
  }
}