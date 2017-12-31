<?php
/*
 * (c) Anthony Benkhebbab <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Serializer;

use Trucy\Serializer\ClassMetadataCollection;
use Trucy\Serializer\SerializerTools;

class SerializerToolsTest extends \PHPUnit\Framework\TestCase {
  public function testCreatingCollection() {
    $collection = SerializerTools::createMetadataFromConfig([
      Dummy::class => [
        "bar" => [
          "class" => Dummy::class,
        ]
      ]
    ]);

    $this->assertInstanceOf(ClassMetadataCollection::class, $collection);
    $this->assertEquals([
      "bar" => [
        "class" => Dummy::class
      ]
    ], $collection->getOrNull(Dummy::class)->rawProperties());
  }
}