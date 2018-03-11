<?php

namespace App\Controllers;

class BankBooks {

    static public function get() {
        
        //пока без моделей

        $user_id = (int)$_REQUEST['user_id'];

    	$db = new \Db;
    	$result = $db->select('bank_books', '*', '`user_id` = ' . $user_id);

        $bank_books = [];
    	while ($bank_book = $result->fetch_assoc()) {
    		$bank_books[] = $bank_book;
    	}

    	$data = [
    		'bank_books' => $bank_books
    	];

        return $data;
    }
}
