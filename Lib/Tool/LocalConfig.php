<?php
namespace Uccu\DmcatTool\Tool;

use Uccu\DmcatTool\Traits\InstanceTrait;
use stdClass;

class LocalConfig{
    
    use InstanceTrait;

    static public $_CONFIG_ROOT = __DIR__;
    static public $_configs = [];

    function __construct($name){

		$path = self::$_CONFIG_ROOT.$name.'.conf';
		$file = @fopen($path, "r");

		$config = new stdClass;
		if(!$file)return $config;
		
		while(!feof($file)) {

			$line = fgets($file);
			$line = trim( preg_replace('/#.*$/','',$line) );
			if(!$line)continue;
			if(!preg_match('#^[a-z_]#i',$line))continue;
			if(!preg_match('#^([a-z_][a-z_0-9]*)[ \t]*=[ \t]*(.*)$#i',$line,$match))continue;

			list(,$key,$value) = $match;
			$key = strtoupper($key);
			if(!empty($config->$key)){
				$con = &$config->$key;
				if(!is_array($con))$con = array($con);
				$con[] = $value;
				
			}else{
				$config->$key = $value;
			}

		}

        foreach($config as $k=>$v){

            $this->$k = $v;
        }

		fclose($file);
		
		return $config;

	}

	public function __get($name){
		return null;
	}

    public static function __callStatic($name, $arguments) 
    {
        
        return self::getMutiInstance(ucfirst($name));

    }

	public static function get($name){
		return self::config()->$name;
	}

}