<?php
namespace Uccu\DmcatTool\Tool;

class AJAX{


    //暂时没有什么用
    static $type = 'JSON';

    //输出口
    private static function outPut($code ,$data ,$message ,$url){

        $content = new stdClass;

        $content->data = (object)$data;

        $content->code = floor($code);

        $content->url = $url.'';

        $content->message = $message.'';

        $content = json_encode($content);

        echo $content;

        while (0 < ob_get_level()) {
            ob_end_flush();
        }

        exit();

    }

    //成功
    static function success($data = null,$code = 200 ,$url = ''){

        $url = !$url && is_string($code) ? $code : $url;

        $code = is_int($code) ? $code : 200;

        if(!$data || is_string($data)){
            $data = array();
            $message = $data ? $data : '';
        }


        self::outPut($code ,$data ,$message ,$url);
        
    }

    //错误/失败
    static function error($message ,$code = 400 ,$url = ''){

        $url = !$url && is_string($code) ? $code : $url;

        $code = is_int($code) ? $code : 400;

        if(!$message)$message = 'Undefined Error';

        $data = array();

        self::outPut($code ,$data ,$message ,$url);
 
    }

    //自定义错误码
    static function  code(){




        
    }





}