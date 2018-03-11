<?php
final class ResponseHandler {

	private static $printFormat = 'html';

	public static function setPrintFormat($format) {
		$formats = ['html', 'json'];
		
		if (in_array($format,$formats)) {
			self::$printFormat = $format;
			return true;
		} else {
			return false;
		}
	}
	
	private static function printHeader() {
		$content_types = ['html' => 'text/html', 'json' => 'application/json'];
		
		header('Content-Type: ' . $content_types[self::$printFormat] . '; charset=' . CONTENT_CHARSET);
	}
	
	
	public static function printResponse($mixed_data) {
		self::printHeader();
		
		switch (self::$printFormat) {
			case 'html':
				echo $mixed_data;
			break;
			
			case 'json':
				$json = json_encode($mixed_data);

				if ($json === false) {
					throw new Exception(json_last_error_msg());	
				} else {
					echo $json;
				}
			break;
		}
		
		exit;
	}
}