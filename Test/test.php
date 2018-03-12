<?php

require_once __DIR__."/../vendor/autoload.php";

//错误机制
use Uccu\DmcatTool\Tool\LocalConfig as Config;
use Uccu\DmcatTool\Tool\E;
set_exception_handler([E::class,'handleException']);
set_error_handler([E::class,'handleError']);
register_shutdown_function([E::class, 'handleShutdown']);







// Config设置


Config::$_CONFIG_ROOT = dirname(__FILE__).'/Conf/';
E::$_BASE_ROOT = dirname(__FILE__).'/';
E::$_LOG_ROOT = dirname(__FILE__).'/Log/';



// echo $f = Config::get('E');


class A{


}


class B{

    public function tt(A $a){

        echo $a;
    }

}

$b = new B;
echo $b->tt();
echo "\n";