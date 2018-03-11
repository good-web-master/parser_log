<?php

namespace App\Controllers;

use App\Model\LogParser;

class LogByLogin {

    static public function get() {
        $login = 'iev';

        $load_file = [
            'remote' => '/opt/diasoft/Oracle/Middleware/Oracle_Home/user_projects/domains/flex01/logs/pageflow_controller.log',
            'local' => __DIR__ . '/../../uploads/pageflow_controller.log'
        ];

        LogParser::getLogByLogin($login, $load_file['local'], $load_file['local'] . '_fn');

        //return \View::generate('index', []);
    }
}
