<?php

require_once('../app/init.php');

$app = App::instance();
$app->setup($application);
$app->run();