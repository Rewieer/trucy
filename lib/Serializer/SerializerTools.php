<?php
/*
 * (c) Anthony Benkhebbab <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trucy\Serializer;

/**
 * Class SerializerTools
 * @package Serializer
 * Provide tools for the serializer
 */
class SerializerTools {
  /**
   * Create a ClassMetadataCollection from an array
   * @param array $config
   * @return ClassMetadataCollection
   */
  public static function createMetadataFromConfig(array $config) {
    $collection = new ClassMetadataCollection();
    foreach ($config as $class => $metadataConfig) {
      $metadata = new ClassMetadata();
      foreach($metadataConfig as $propertyName => $propertyConfig) {
        $metadata->configureProperty($propertyName, $propertyConfig);
      }

      $collection->add($class, $metadata);
    }
    return $collection;
  }

  /**
   * Get a portion of the data.
   * @param array $data
   * an associative arrays usually with nested values
   *
   * @param array $path looks like ["foo"]["bar"]
   * an array of items to fetch
   *
   * if the $data is equal to ["foo" => ["bar" => 1]] and $path is equal to ["foo"]["bar"]
   * then this function returns 1 (or $data["foo"]["bar"])
   *
   * @return array
   */
  public static function getPortion($data, array $path) {
    if (count($path) === 0)
      return $data;

    $first = array_shift($path);
    if (array_key_exists($first, $data)) {
      return self::getPortion($data[$first], $path);
    }

    return [];
  }
}