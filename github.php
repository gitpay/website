<?php
session_start();

if (!isset($cache)) {
  $cache = './data/github-api-cache';
}

$client = new \Github\Client(
    new \Github\HttpClient\CachedHttpClient(array('cache_dir' => $cache))
);

@include('api.php');

?>
