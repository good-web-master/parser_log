<?php 
require(__DIR__ . '/configs/config.php');

require(__DIR__ . '/functions.php');

require(__DIR__ . '/classes/ErrorHandler.php');

require(__DIR__ . '/classes/AutoLoader.php');

ErrorHandler::init(LEVEL_ERROR_HANDLER, MODE);