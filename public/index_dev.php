<?php
use Symfony\Component\HttpFoundation\Request;

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

$kernel = new \App\Application("dev");
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
