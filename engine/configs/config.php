<?php
// Отображение ошибок
error_reporting(E_ALL ^ E_NOTICE); 

define('LEVEL_ERROR_HANDLER', E_ALL ^ E_NOTICE); // уровень обрабатываемых обрботчком ошибок

define('CONTENT_CHARSET', 'utf-8'); // Кодировка контента сайта


/*define('PROTOCOL', 'https');
define('BASE_URL', PROTOCOL .'://payment.oteneriferent.com/');
*/

//date_default_timezone_get();
define('DEFAULT_TIMEZONE', 'Europe/Moscow');
date_default_timezone_set(DEFAULT_TIMEZONE);

define('MODE','debugging'); //working

define('ROOT_DIR', realpath(__DIR__ . '/../../')); 
define('TEMPLATE_DIR', ROOT_DIR . '/templates');

mb_internal_encoding(CONTENT_CHARSET);