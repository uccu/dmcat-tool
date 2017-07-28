<?php
namespace uccu\Tool\Traits;

Trait InstanceTrait{


    public static function getSingleInstance(){

        static $object;
		if(empty($object)){

            $params = func_get_args();
            $object = new static(...$params);
        }
		return $object;
    }


    public static function getMutiInstance(string $type){

        static $object;
		if(empty($object) && !isset($object[$type])){

            $params = func_get_args();
            $object[$type] = new static(...$params);
        }
		return $object[$type];
    }


    public static function copyMutiInstance(string $type){

        $params = func_get_args();
        $obj = clone static::getMutiInstance(...$params);
		return $obj;
    }

}