<?php
/*
 * (c) Anthony Benkhebbab <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trucy\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Trucy\Kernel;
use Trucy\AbstractProvider;

class Application extends BaseApplication {
  private $kernel;

  public function __construct(Kernel $kernel) {
    parent::__construct();
    $this->kernel = $kernel;
  }

  /**
   * @param InputInterface $input
   * @param OutputInterface $output
   * @return int
   * @throws \Throwable
   */
  public function doRun(InputInterface $input, OutputInterface $output) {
    $this->kernel->boot();
    $this->registerCommands();
    return parent::doRun($input, $output);
  }

  /**
   * Loads the commands
   */
  public function registerCommands() {
    $providers = $this->kernel->getProviders();
    foreach ($providers as $provider) {
      if ($provider instanceof AbstractProvider) {
        foreach ($provider->getCommands() as $command) {
          if ($command instanceof ContainerAwareCommand) {
            $command->setContainer($this->kernel->getContainer());
          }

          $this->add($command);
        }
      }
    }
  }

  /**
   * @return Kernel
   */
  public function getKernel() {
    return $this->kernel;
  }
}