<?php
/**
 * Based on Joseph Crawford
 * http://www.weberdev.com/PHP-Code-Examples-by-Joseph-Crawford.html
 */
class Logger {
	private static $_instance;
	private function __construct() { }

	static function instance() {
		if (!isset(self::$_instance)) {
    			$c = __CLASS__;
    			self::$_instance = new $c;
		}

		return self::$_instance;
	}

	public function __clone() {
	    throw new Exception('Cannot clone the logger object.');
	}
   
	public function log($message = '', $indent = 0) {
		$filename = '/opt/TwitBot/logs/bot.txt';
		$file = fopen($filename, 'a+');
		fwrite($file, sprintf("[%s]: %{$indent}s %s\r\n", date("d/m/y H:i:s"), "", $message));
		fclose($file);
	}
}
?>
