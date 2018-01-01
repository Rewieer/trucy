<?php
/*
 * (c) Anthony Benkhebbab <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trucy\Providers\Framework;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;
use Trucy\AbstractProvider;

/**
 * Class FrameworkProvider
 * Provide the framework of the project.
 *
 * @package App\Providers\Framework
 */
class FrameworkProvider extends AbstractProvider {
  public function inject(ContainerBuilder $container) {
    $rootDir = $container->getParameter("root_dir");
    $env = $container->getParameter("env");

    $container->setParameter("controller_dir", $rootDir. "/controller");
    $container->setParameter("config_dir", $rootDir. "/config");
    $container->setParameter("entities_dir", $rootDir. "/model/Entities");
    $container->setParameter("repositories_dir", $rootDir. "/model/Repositories");
    $container->setParameter("cache_dir", $rootDir. "/var/cache/" .$env);
    $container->setParameter("logs_dir", $rootDir. "/var/logs/" .$env);

    $data = Yaml::parse(file_get_contents($container->getParameter("config_dir"). "/parameters.yml"));
    foreach($data["parameters"] as $name => $value) {
      $container->setParameter($name, $value);
    }
  }
}