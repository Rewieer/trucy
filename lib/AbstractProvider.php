<?php
/*
 * (c) Anthony Benkhebbab <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trucy;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class AbstractProvider
 * A provider is the basic way to extend Trucy's capabilities
 * It allows to incorporate third parties libraries
 *
 * @package Trucy
 */
abstract class AbstractProvider {
  private $path;
  private $namespace;
  private $name;

  /**
   * Init the provider
   */
  public function init() {
    $this->loadAnnotations();
  }

  /**
   * Return the path of the current ile
   * @return string
   */
  public function getPath() {
    if ($this->path === null) {
      $reflected = new \ReflectionObject($this);
      $this->path = dirname($reflected->getFileName());
    }

    return $this->path;
  }

  /**
   * Load annotations
   */
  public function loadAnnotations() {
    $annotationsDirectory = $this->getPath(). "/Annotation";
    $this->parseClassName();

    if (is_dir($annotationsDirectory)) {
      $files = scandir($annotationsDirectory);
      foreach ($files as $file) {
        if (Util::endsWith($file, ".php") === false)
          continue;

        AnnotationRegistry::registerFile($annotationsDirectory. "/" .$file);
      }
    }
  }

  /**
   * Parse the classname and the namespace
   */
  private function parseClassName() {
    $pos = strrpos(static::class, '\\');
    $this->namespace = false === $pos ? '' : substr(static::class, 0, $pos);
    if ($this->name === null) {
      $this->name = false === $pos ? static::class : substr(static::class, $pos + 1);
    }
  }

  /**
   * Return a list of commands
   * @return array
   */
  public function getCommands () {
    return [];
  }

  public function inject(ContainerBuilder $container) {

  }
}