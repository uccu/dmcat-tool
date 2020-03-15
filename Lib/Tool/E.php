<?php
namespace Uccu\DmcatTool\Tool;

class E extends \Exception{

	static public $_BASE_ROOT = __DIR__;
	static public $_LOG_ROOT = __DIR__;

	protected $_trace        = null;

	public function __construct($message = '',$code = 0,$file = NULL,$line = NULL,array $trace = []) {
		
		$file 	&& $this->file   = $file;
        $line 	&& $this->line   = $line;
        $trace 	&& $this->_trace = $trace;

        parent::__construct($message, (int)$code);

	}
	

	public function getBacktrace(){
        if (null === $this->_trace) {
            $this->_trace = $this->getTrace();
        }

        return $this->_trace;
    }
    
    # 处理异常
	final public static function handleException($exception){

		if ($exception instanceof self) {
			self::output($exception);
        }

		$sql = isset($exception->sql) ? $exception->sql : '';
		
		$str = $exception->getMessage();
		$no = $exception->getCode();
		$file = $exception->getFile();
		$line = $exception->getLine();
		$trace = $exception->getTrace();


		$exception = new self('Exception '.$str, $no, $file, $line, $trace);
		$exception->sql = $sql;
		self::output($exception);
	}


	# 处理错误
	final public static function handleError($no, $str, $file = null, $line = null) {

        if (0 === ($no & error_reporting())) {
            return;
		}elseif($no == 8){
			return;
		}
        $trace = debug_backtrace();


        throw new self('Error '.$str, $no, $file, $line, $trace);
            
	}


	# 输出给前端
	final public static function output(E $exception,$no = 0,$fileName = ''){

		$message = $exception->getMessage();
		$message = preg_replace('#, *called in.*#','',$message);
		
		$line = $exception->getLine();
		!$line && $line = 'NULL';
		$file = $exception->getFile();
		!$file && $file = '{core}';

		$file = str_replace(str_replace('\\','/',self::$_BASE_ROOT),'',str_replace('\\','/',$file));
		$file = preg_replace('#\..*$#i','',$file);

		$trace = $exception->getBacktrace();
		

		$message .= ' in '.$file.':'.$line;
		
		$raise = '['.date('Y-m-d H:i:s')."]\n".$message."\n";
		if($exception->sql) $raise .= $exception->sql . "\n";
		
		foreach($trace as $t){

			if(isset($t['file']) && isset($t['line'])){
				$raise .= $t['file'] . ':' . $t['line'] . "\n";
			}

		}

		# 记录日志
		!$fileName && $fileName = date('Ymd');
		$enableLog = LocalConfig::get('ERROR_LOG');
		$filePath = self::$_LOG_ROOT . $fileName . '.log';

		# 判断文件写入权限
		if(
			$enableLog && 
			is_writable(self::$_LOG_ROOT) && 
			(
				!file_exists($filePath) || is_writable($filePath)
			)
		){
			
			$file = fopen($filePath,"a");
			fwrite($file,$raise);
			fclose($file);

		}else{

			$enableLog && $message = 'LogError|' . $message;

		}


		# 输出类型
		$type = LocalConfig::get('EXCEPTION_OUTPUT_TYPE');
		switch($type){

			case 'string':
				echo $message;

				while (0 < ob_get_level()) {
					ob_end_flush();
				}
				break;
			default:
				AJAX::error($message ,999 );
				break;

		}

		exit();

	}

	

	final public static function handleShutdown(){
		$error = error_get_last();
		if($error && isset($error['type']) && $error['type']){
			$message = isset($error['message'])?$error['message']:'';
			$file = isset($error['file'])?$error['file']:'';
			$line = isset($error['line'])?$error['line']:'';
			self::output(new self('Shutdown '.$message,0,$file,$line));
		}
	}


	public static function throwEx($message = 'Uncaught',$no = 0){

		$trace = debug_backtrace();

		if (!empty($trace)) {

			$t    = $trace[$no];
			$file = '';
			$line = '';

            if ($t && isset($t['file'])) {
                $file = $t['file'] ;
            }

			if ($t && isset($t['line'])) {
                $line = $t['line'] ;
            }
		}

		$exception = new self('Exception '.$message, 0, $file, $line, $trace);
		self::output($exception);
	}

	
}



?>