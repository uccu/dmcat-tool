<?php

require_once __DIR__."/../vendor/autoload.php";


use Uccu\DmcatTool\Tool\LocalConfig;

LocalConfig::$_CONFIG_ROOT = dirname(dirname(__FILE__)).'/Conf/';


echo $f = LocalConfig::get('E');

;
echo "\n";