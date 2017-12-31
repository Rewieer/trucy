<?php
/*
 * (c) Anthony Benkhebbab <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Providers\Twig;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Trucy\AbstractProvider;

class TwigProvider extends AbstractProvider {
  public function inject(ContainerBuilder $container) {
    $loader = new XmlFileLoader($container, new FileLocator(__DIR__. "/Config"));
    $loader->load("services.xml");
  }
}