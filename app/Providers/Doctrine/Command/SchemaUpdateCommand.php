<?php
/*
 * (c) Anthony Benkhebbab <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Providers\Doctrine\Command;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Trucy\Console\ContainerAwareCommand;
use Trucy\Kernel;

class SchemaUpdateCommand extends UpdateCommand {
  protected function configure() {
    parent::configure();
    $this
      ->setName('doctrine:schema:update');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $kernel = $this->getApplication()->getKernel();
    $manager = $kernel->getContainer()->get("doctrine.orm.entity_manager");

    $this->getApplication()->getHelperSet()->set(new ConnectionHelper($manager->getConnection()), "db");
    $this->getApplication()->getHelperSet()->set(new EntityManagerHelper($manager), "em");
    parent::execute($input, $output);
  }
}