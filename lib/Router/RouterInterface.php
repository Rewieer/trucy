<?php
/*
 * (c) Anthony Benkhebbab <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trucy\Router;

use Symfony\Component\HttpFoundation\Request;

interface RouterInterface {
  /**
   * A router is a simple tool that match an URI - and his parameters - to
   * a callable.
   * The callable can return any type of response.
   *
   * @param Request $request
   * @return mixed
   */
  public function lookup(Request $request);
}