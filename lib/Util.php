<?php
/*
 * (c) Anthony Benkhebbab <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trucy;

class Util {
  /**
   * Return true if the string ends with the needle
   * @param $haystack
   * @param $needle
   * @return bool
   */
  public static function endsWith($haystack, $needle) {
    $length = strlen($needle);
    return $length === 0 || (substr($haystack, -$length) === $needle);
  }
}