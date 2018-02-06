<?php
/*
 * (c) Anthony Benkhebbab <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trucy\Providers\Restful;

use Rewieer\Serializer\ClassMetadata;
use Rewieer\Serializer\ClassMetadataCollection;
use Rewieer\Serializer\Serializer;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;
use Trucy\AbstractProvider;

class RestfulProvider extends AbstractProvider {
  public function inject(ContainerBuilder $container) {
    $data = Yaml::parse(file_get_contents($container->getParameter("config_dir"). "/entities.yml"));
    $metaDataCollection = new ClassMetadataCollection();

    if (isset($data["entities"])) {
      foreach ($data["entities"] as $class => $values) {
        $classMetadata = new ClassMetadata();
        if (isset($values["views"])) {
          foreach ($values["views"] as $viewName => $viewData) {
            $classMetadata->configureView($viewName, $viewData);
          }
        }

        if (isset($values["attributes"])) {
          foreach ($values["attributes"] as $attribute => $conf) {
            $classMetadata->configureAttribute($attribute, $conf);
          }
        }

        $metaDataCollection->add($class, $classMetadata);
      }
    }

    $container->set("serializer", new Serializer($metaDataCollection));
  }
}