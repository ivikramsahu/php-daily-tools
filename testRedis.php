<?php
require 'redis/vendor/predis/predis/autoload.php';

Predis\Autoloader::register();

// Parameters passed using a named array:
$client = new Predis\Client([
    'scheme' => 'tcp',
    'host'   => 'localhost',
    'port'   => 6379,
]);
$client->set('foo', 'bar');
$value = $client->get('foo');
print $value;
?>
