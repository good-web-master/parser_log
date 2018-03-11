<?php
namespace App\Controllers;

class TreeBlocks {

    static public function get() {
        $file = ROOT_DIR . '/uploads/user-id/tree_blocks.json';

        if(file_exists($file)) {
            return file_get_contents($file); 
        } else {
            return '';
        }
    }
}
