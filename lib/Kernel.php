<?php
/*
 * (c) Anthony Benkhebbab <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trucy;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\AutowirePass;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Trucy\Controller\ControllerInterface;
use Trucy\Router\Route;
use Trucy\Router\Router;

abstract class Kernel implements KernelInterface {
  private $environment;

  /**
   * @var \Trucy\Container
   */
  private $container;

  /**
   * @var AbstractProvider[]
   */
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
    $this->container->addCompilerPass(new AutowirePass());

    $this->container->set(get_class($this->container), $this->container);
    $this->container->setParameter("root_dir", $this->getRootDir());
    $this->container->setParameter("env", $this->getEnvironment());

    $loader = new YamlFileLoader($this->container, new FileLocator($this->getRootDir(). '/config'));
    $loader->load("services.yml");

    // Registering providers
    $providers = $this->registerProviders();
    foreach ($providers as $provider) {
      if ($provider instanceof AbstractProvider) {
        $this->providers[] = $provider;
        $provider->init();
        $provider->inject($this->container);
      }
    }

    $this->container->compile();
  }

  /**
   *
   */
  public function initializeRouter() {
    if ($this->container->has("router") === true)
      return;

    $routes = [];
    foreach ($this->container->findTaggedServiceIds("controller") as $class => $data) {
      $object = $this->container->get($class);
      if (!$object instanceof ControllerInterface) {
        throw new \Exception(
          sprintf("The class %s must implements the ControllerInterface.", $class)
        );
      }

      $routes[] = new Route(
        $object->getPath(),
        $object->getMethod(),
        [$object, "handle"],
        $object->getRequirements()
      );
    }

    $this->container->set("router", new Router($routes));
  }

  /**
   * Boot the kernel
   */
  public function boot() {
    if ($this->hasBoot === true)
      return;

    $start = microtime(1);
    $this->initializeContainer();
    $this->initializeRouter();
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

    $route = $this->container->get("router")->lookup($request);
    $response = $route->execute();
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

  /**
   * Return the root directory
   * @return string
   */
  public function getRootDir() {
    return dirname($this->getAppDirectory());
  }

  /**
   * Register a list of providers
   * @return array
   */
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