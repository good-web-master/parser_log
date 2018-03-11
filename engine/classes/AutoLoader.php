<?php
final class AutoLoader {
	private static $lastLoaded;

	public static function load($className) {
		self::$lastLoaded = $className;

		if (0 === strpos($className, 'App\\')) {
            $fileName = ROOT_DIR . '/app' . strtr(substr($className, 3), '\\', '/') . '.php';
        } else {
        	$fileName = ROOT_DIR . '/engine/classes/' . $className . '.php';
        }

		if (file_exists($fileName)) {
			require($fileName);
		} else {
			return false;
		}

	}

	public static function log($className) {
		self::loadPackages($className);
		printf("Class %s was loaded from %sn", $className, self::$lastLoaded);
	}
}

spl_autoload_register(array('AutoLoader', 'load'));