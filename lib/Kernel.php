<?php
/*
 * (c) Anthony Benkhebbab <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trucy;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Trucy\Controller\AbstractController;
use Trucy\Controller\RouteAnnotation;
use Trucy\Router\Router;

abstract class Kernel implements KernelInterface {
  private $environment;

  /**
   * @var \Trucy\Container
   */
  private $container;
  private $providers;

  private $path;
  private $bootTime;
  private $handleTime;
  private $hasBoot = false;

  public function __construct($env = "dev") {
    $this->environment = $env;
  }

  /**
   * Initialize the service container
   */
  public function initializeContainer() {
    if ($this->container !== null)
      return;

    $this->container = ContainerSingleton::getInstance();
    $this->container->setParameter("service_container", $this->container);
    $this->container->setParameter("root_dir", $this->getRootDir());
    $this->container->setParameter("env", $this->getEnvironment());
    $this->container->set("router", new Router());

    // Registering providers
    $providers = $this->registerProviders();
    foreach ($providers as $provider) {
      if ($provider instanceof AbstractProvider) {
        $this->providers[] = $provider;
        $provider->init();
        $provider->inject($this->container);
      }
    }
  }

  /**
   * Boot the kernel
   */
  public function boot() {
    if ($this->hasBoot === true)
      return;

    $start = microtime(1);
    $this->initializeContainer();
    $this->bootTime = microtime(1) - $start;
    $this->hasBoot = true;
  }

  /**
   * Handle the request
   * @param Request $request
   * @throws \Exception
   * @return mixed
   */
  public function handle(Request $request) {
    $this->boot();

    $start = microtime(1);
    if ($this->container->has("router") === false) {
      throw new \Exception("You must provide a router service in order to handle a kernel request");
    }

    $response = $this->container->get("router")->lookup($request);
    $this->handleTime = microtime(1) - $start;

    if ($response === null) {
      return new Response("Not found", 404);
    }

    return $response;
  }

  /**
   * Terminate the request
   * @param Request $request
   * @param Response $response
   */
  public function terminate(Request $request, Response $response) {

  }

  /**
   * Return the app folder
   * @return mixed
   */
  public function getAppDirectory() {
    if ($this->path === null) {
      $reflected = new \ReflectionObject($this);
      $this->path = dirname($reflected->getFileName());
    }

    return $this->path;
  }

  public function getRootDir() {
    return dirname($this->getAppDirectory());
  }

  public function getDirectories() {
    return [];
  }

  public function registerProviders() {
    return [];
  }

  /**
   * @return string
   */
  public function getEnvironment() {
    return $this->environment;
  }

  /**
   * @return Container
   */
  public function getContainer() {
    return $this->container;
  }

  /**
   * @return mixed
   */
  public function getProviders() {
    return $this->providers;
  }
}