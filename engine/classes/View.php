<?php
final class View {
	static public function generate($template, $vars = []) {
		extract($vars, EXTR_SKIP);
		ob_start();
		require(ROOT_DIR . '/app/Views/' . $template . '.php');
		return ob_get_clean();
	}
}