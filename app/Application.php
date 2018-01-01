<?php

namespace App;

use Trucy\Kernel;
use Trucy\Providers\Doctrine\DoctrineProvider;
use Trucy\Providers\Framework\FrameworkProvider;

class Application extends Kernel {
  public function registerProviders() {
    return [
      new FrameworkProvider(),
      new DoctrineProvider(),
    ];
  }
}