<?php
/*
 * (c) Anthony Benkhebbab <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Trucy\Providers\Framework;

use PHPUnit\Framework\TestCase;
use Rewieer\Serializer\ClassMetadata;
use Rewieer\Serializer\ClassMetadataCollection;
use Rewieer\Serializer\Serializer;
use Trucy\Container;
use Trucy\Providers\Doctrine\DoctrineProvider;
use Trucy\Providers\Framework\FrameworkProvider;
use Trucy\Providers\Restful\RestfulProvider;

class RestfulProviderTest extends TestCase {
  /**
   * @var Container
   */
  private $container;

  public function initContainer() {
    $this->container = new Container();
    $this->container->setParameter("root_dir", __DIR__. "/../../../mock");
    $this->container->setParameter("env", "test");
    (new FrameworkProvider())->inject($this->container);
    (new DoctrineProvider())->inject($this->container);
  }

  public static function metadataCollectionToArray(ClassMetadataCollection $collection) {
    return array_map(function(ClassMetadata $metadata) {
      return [
        "properties" => $metadata->rawProperties(),
        "views" => $metadata->rawViews(),
      ];
    }, $collection->raw());
  }

  public function testInstantiating() {
    $this->initContainer();
    $provider = new RestfulProvider();
    $provider->inject($this->container);

    /** @var Serializer $serializer */
    $serializer = $this->container->get("serializer");

    $this->assertEquals([
      'Model\User' => [
        "properties" => [
          "id" => [
            "getter" => "getId",
          ]
        ],
        "views" => [
          "general" => [
            "id", "email",
          ]
        ]
      ]
    ], self::metadataCollectionToArray($serializer->getClassMetadataCollection()));
  }

}