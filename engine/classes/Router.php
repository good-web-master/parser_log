<?php 
final class Router {

	static private $config;

	static private $routes = [];

	static private $routes_names = [];
	

	static public function init() {
		self::$config = get_config('routes');

		require(ROOT_DIR . '/routes/web.php');

		$mod = $_REQUEST['mod'];
		$go = $_REQUEST['go'];

		if (empty($mod) && empty($go)) {
			$str_controller = self::$config['default_controller'];
		} elseif (!isset(self::$routes[$mod][$go])) {
			throw new Exception('Не найден маршрут');
		} else {
			$str_controller = self::$routes[$mod][$go];
		}

		return self::callController($str_controller);
	}

	static public function setRoute($mod, $go, $str_controller, $name = null) {
		self::$routes[$mod][$go] = $str_controller;

		self::$routes_names[$name] = $str_controller;

		return true;
	}

	static private function callController($str_controller) {
		list($name_controller, $method) = explode('@', $str_controller, 2);
		
		return call_user_func(['App\\Controllers\\' . $name_controller, $method]);
	}
}