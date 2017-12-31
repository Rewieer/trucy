<?php
/*
 * (c) Anthony Benkhebbab <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Providers\Doctrine\Command;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Trucy\Console\ContainerAwareCommand;

class DropDatabaseCommand extends ContainerAwareCommand {
  protected function configure() {
    $this
      ->setName('doctrine:database:drop')
      ->setDescription(
        'Creates the database'
      );
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    /** @var EntityManager $manager */
    $manager = $this->getContainer()->get("doctrine.orm.entity_manager");
    $connection = $manager->getConnection();
    $params = $connection->getParams();
    $name = $params["dbname"];
    unset($params["dbname"]);
    $tmpConnection = DriverManager::getConnection($params);

    $db = $tmpConnection->getSchemaManager()->listDatabases();
    if (in_array($name, $db) === false) {
      $output->writeln(sprintf("<info>Database <comment>%s</comment> does not exist. Deletion skipped</info>", $name));
      return;
    }

    $tmpConnection->getSchemaManager()->dropDatabase($name);
    $output->writeln(sprintf("<info>Database <comment>%s</comment> just got dropped !</info>", $name));
  }
}