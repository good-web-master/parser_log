<?php
namespace App\Controllers;

class Parameters {

    static public function get() {
        $id = (int)$_POST['id'];


        switch ($_POST['type']) {
            case 'input':
                $type = 'in';
            break;
            
            case 'output':
                $type = 'out';
            break;
        }

       


        $file = ROOT_DIR . '/uploads/user-id/parameters/' . $id . '_' . $type . '.json';

        if(file_exists($file)) {
            return file_get_contents($file); 
        } else {
            return '';
        }

    }
}
