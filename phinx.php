<?php

$conf = 'config/config.php';

if(!file_exists($conf)){
  $f = fopen('php://stderr','a');
  fwrite($f,"\nPlease Configure $conf to make this work!\n");
  fclose($f);
  return;
}

require_once($conf);

$output = [
  'paths' => [
    'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
    'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
  ],
  'environments' => array_merge(
    ['default_migration_table' => 'phinxlog'],
    $db
  )
];



return $output;
