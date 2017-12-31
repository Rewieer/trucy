<?php

namespace App;

use App\Providers\Doctrine\DoctrineProvider;
use App\Providers\Framework\FrameworkProvider;
use App\Providers\Router\RouterProvider;
use Trucy\Kernel;

class Application extends Kernel {
  public function registerProviders() {
    return [
      new FrameworkProvider(),
      new RouterProvider(),
      new DoctrineProvider(),
    ];
  }
}