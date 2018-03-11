<?php
/**
 * Класс для обработки ошибок
 * 
 * @author Igor Evstegneev <good.web-master@yandex.ru>
 */
final class ErrorHandler {

	private static $levelError;
	private static $mode;
	private static $printFormat = 'html';
	private	static $printError = false;
	
	public static function init($level_error = E_ALL, $mode = 'working') {
		
		self::$levelError = $level_error;
		self::$mode = $mode;

		set_error_handler(array(__CLASS__, 'printPhpMessage'), self::$levelError); //Метод обработки ошибок PHP
		set_exception_handler(array(__CLASS__, 'printException')); //Метод обработки исключений PHP
		register_shutdown_function(array(__CLASS__, 'printFatalError')); //Метод отлавливания фатальных ошибок PHP
		ob_start();
	}
	
	public static function setPrintFormat($format) {
		$formats = array ('html', 'json');
		
		if (in_array($format,$formats)) {
			self::$printFormat = $format;
			return true;
		} else {
			return false;
		}
	}
	
	private static function printHeader503() {
		header('HTTP/1.1 503 Service Temporarily Unavailable');
		header('Status: 503 Service Temporarily Unavailable');
		header('Retry-After: 3600');
	}
	
	
	private static function printMessage($title, $message) {
		self::printHeader503();
		
		if (self::$mode == 'working') {
			$title   = 'Ошибка';
			$message = '<p><strong>Произошла ошибка. Просим прощения за неудобства...</strong></p>
						<p>Администрация и Техподдержка уведомлены об этом. Попытайтесь повторить операцию немного позже.</p> <p>Спасибо за понимание.</p>
						<p><a href="' . BASE_URL . '">Перейти на главную страницу</a></p>';
		}
		
		ob_end_clean();
		
		switch (self::$printFormat) {
			case 'html':
				require(ROOT_DIR . '/engine/message_template.php');
				break;
			
			case 'json':
				$json = array('error' => array('title' => $title, 'message' => $message));
				echo json_encode($json);
				break;
		}
		self::$printError = true;
		exit;
	}
	
	public static function printPhpMessage($errno, $errstr, $errfile, $errline) {

		$errors = array(
			0 => 'Exception',
			1 => 'E_ERROR',
			2 => 'E_WARNING',
			4 => 'E_PARSE',
			8 => 'E_NOTICE',
			16 => 'E_CORE_ERROR',
			34 => 'E_CORE_WARNING',
			64 => 'E_COMPILE_ERROR',
			128 => 'E_COMPILE_WARNING',
			256 => 'E_USER_ERROR',
			512 => 'E_USER_WARNING',
			1024 => 'E_USER_NOTICE',
			2048 => 'E_STRICT',
			4096 => 'E_RECOVERABLE_ERROR',
			8192 => 'E_DEPRECATED',
			16384 => 'E_USER_DEPRECATED'
		);

		if (!isset($errors[$errno])) {
			$errors[$errno] = '';
		}
		
		$title='Ошибка PHP';
		$message='<p><strong>' . $errors[$errno] . '</strong> #' . $errno . '</p>
				  <p><strong>Описание:</strong> ' . $errstr . '</p>
				  <p><strong>Ошибка в файле:</strong> ' . $errfile . '</p>
				  <p><strong>Номер строки:</strong> ' . $errline . '</p>'; 
		self::printMessage($title, $message);
	}
	
	public static function printDbMessage($subtitle, $errno, $errstr, $errfile, $errline, $sql = NULL) {
		$title='Ошибка БД';
		$message = '<p><strong>' . $subtitle . '</strong></p>
				    <p><strong>#' . $errno . '</strong></p>
				    <p><strong>Описание:</strong> ' . $errstr . '</p>';
		if ($sql) {
			$message .= 
				'<p><strong>SQL:</strong> <code>' . 
				htmlspecialchars($sql, ENT_QUOTES, CONTENT_CHARSET) . 
				'</code></p>';
		}
		
		$message .= 
			'<p><strong>Ошибка в файле:</strong> ' . $errfile . '</p>
			 <p><strong>Номер строки:</strong> ' . $errline . '</p>';
				  
		self::printMessage($title, $message);
	}
	
	public static function printException($e) {
		self::printPhpMessage(0, $e->getMessage(), $e->getFile(), $e->getLine());

		/*$trace = $exception->getTrace();

		self::printPhpMessage(0, $exception->getMessage(), $trace[0]['file'], $trace[0]['line']);*/
	} 
	

	public static function printFatalError() {
		//1, 4, 16, 64, 256, 4096
		if (!self::$printError) {
			$error=error_get_last();
			
			if ($error && ($error['type'] & self::$levelError)) {
				self::printPhpMessage($error['type'], $error['message'], $error['file'], $error['line']);
			}
		}
	}
}