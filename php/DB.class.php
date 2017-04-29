<?php  

/**
* 
*/
class DB {
	
	public static $conn;

	public static function getConn() {
		if (!self::$conn) {
			self::$conn = new mysqli('localhost', 'root', '', 'theschool');
		}
		return self::$conn;
	}
	private function __construct() {}

}