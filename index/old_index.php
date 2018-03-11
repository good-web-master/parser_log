<?php
require('./../engine/engine.php');


//https://stackoverflow.com/questions/14050231/php-function-ssh2-connect-is-not-working
//set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');

//include('Net/SFTP.php');

//$sftp = new Net_SFTP('vebcashwl');
//if (!$sftp->login('weblogic', 'weblogic')) {
//    exit('Login Failed');
//}

// outputs the contents of filename.remote to the screen
//echo $sftp->get('filename.remote');
// copies filename.remote to filename.local from the SFTP server

//$sftp->get($load_file['remote'], $load_file['local']);


//print_r($sftp->nlist('/'));

//======================================

//все события которые происходят когда пользователь работает с формой отображать паралельно или вообще не отображать

require('./LogParser.php');

$login = 'iev';
$tmp = '';
$record = false;
$data = []; //сюда собираем всю инфу о блоке



//LogParser::getLogByLogin($login, $load_file['local'], $load_file['local'] . '_fn');


LogParser::printLog($load_file['local'] . '_fn');

exit;