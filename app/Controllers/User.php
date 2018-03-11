<?php

namespace App\Controllers;

class User {

    static public function get() {
        
        //пока без моделей

        $id = (int)$_REQUEST['id'];

    	$db = new \Db;
    	$result = $db->select('users', '*', '`id` = ' . $id . ' LIMIT 1');

        $user = $result->fetch_assoc();

        return $user;
    }
}
