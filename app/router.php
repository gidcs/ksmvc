<?php

//The running php file is in public directory.
$config_file = '../config/router.php';
file_checks($config_file);
include_once($config_file);