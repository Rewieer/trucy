<?php
/*
 * (c) Anthony Benkhebbab <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trucy\Providers\Doctrine;

use Trucy\Providers\Doctrine\Command\CreateDatabaseCommand;
use Trucy\Providers\Doctrine\Command\DropDatabaseCommand;
use Trucy\Providers\Doctrine\Command\SchemaUpdateCommand;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;
use Trucy\AbstractProvider;

class DoctrineProvider extends AbstractProvider {
  public function getCommands() {
     return [
       new CreateDatabaseCommand(),
       new DropDatabaseCommand(),
       new SchemaUpdateCommand(),
     ];
  }

  public function inject(ContainerBuilder $container) {
    $config = Setup::createAnnotationMetadataConfiguration([$container->getParameter("entities_dir")], true);
    $data = Yaml::parse(file_get_contents($container->getParameter("config_dir"). "/doctrine.yml"));
    $parameters = $data["connection"];

    $params = [
      "dbname" => $parameters["name"],
      "user" => $parameters["user"],
      "password" => $parameters["password"],
      "host" => $parameters["host"],
      "driver" => "pdo_mysql"
    ];

    $manager = EntityManager::create($params, $config);
    $container->set("doctrine.orm.entity_manager", $manager);
  }
}