<?php

define("AUTH_USERNAME", 'info@sts.com');
define("AUTH_PASSWORD", '123456');

$database_path='https://sts-app-31b9c-default-rtdb.firebaseio.com/';
$dpath='Users/TlfrjreYtxbApaz0Jo5rlNt6jAx1/';
$database_secret = 'bWpOSXWI6LiymQe6iI4bBFebwmGig5cgZ5mQg6gv';

require __DIR__ . '/vendor/autoload.php';

use Kreait\Firebase\Factory;

$factory = (new Factory)->withServiceAccount('key.json')->withDatabaseUri($database_path);

$database = $factory->createDatabase();

return  $database;
return  $dpath;
?>
