<?php
function print_message($title, $message) {
	ob_end_clean();
	require(ROOT_DIR . '/engine/message_template.php');
	exit;
}

function get_config($name) {
	return include(ROOT_DIR . '/engine/configs/' . $name . '.php');
}