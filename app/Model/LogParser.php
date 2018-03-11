<?php
namespace App\Model;

class LogParser {
    //regular expressions
    private static $regular_exp = [
        'startBlock' => '/^([0-9\-:, ]+) \[(.*)\] ([A-Z]+) \((.*)\) - /u',
        'startBlockLogin' => '/^login = ([a-zA-Z0-9\-_]+)/u',

        'Login' => '/^Login = ([a-zA-Z0-9]+)$/u',
        'NodeType' => '/^NodeType = ([a-zA-Z0-9_]+)$/u',
        'NodeCaption' => '/^NodeCaption = (.*)$/u',
        'NodeNumber' => '/^NodeNumber = (IT([0-9]+)|null)$/u',
        
        'Pageflow' => '/^Pageflow = ([a-zA-Z0-9\/\\\\]+)$/u',
        'ParentPageflow' => '/^ParentPageflow = ([a-zA-Z0-9\/\\\\]+)$/u',

        'startInputParameters' => '/^InputParameters = $/u',
        'startOutputParameters' => '/^OutputParameters = $/u',

        'Parameter' => '/^([	]+)([a-zA-Z0-9_]+):[ ]+((list|map|null)|(\'([^\']*)(\' \(([a-z]+)\)){0,1}))[ ]*(\-\> ([p|s]var) \'([a-zA-Z0-9_]+)\'){0,1}[:a-z]*$/u',
    ];

    //список регулярок по которым нужно пройтись чтобы получить сведенья о блоке
    private static $list_reg_exp = [
        'type' => 'NodeType',
        'caption' => 'NodeCaption',
        'number' => 'NodeNumber',
        'pageflow' => 'Pageflow',
        'parent_pageflow' => 'ParentPageflow' 
    ];

    public static function getStartBlock($string) {
        if (preg_match(self::$regular_exp['startBlock'], $string, $matches)) {
            $data = [
                'time' => $matches[1],
                'type' => $matches[3]
            ];

            $string = trim(substr($string, strlen($matches[0])));
            if (strlen($string) > 0 && preg_match(self::$regular_exp['startBlockLogin'], $string, $matches)) {
                $data['login'] = $matches[1];
            }
            
            return $data;
        } else {
            return false;
        }   
    }

    public static function getLogin($string) {
        if (preg_match(self::$regular_exp['Login'], $string, $matches)) {
            return $matches[1];
        } else {
            return false;
        }
    }

    public static function getNodeType($string) {
        if (preg_match(self::$regular_exp['NodeType'], $string, $matches)) {
            return $matches[1];
        } else {
            return false;
        }
    }

    public static function getNodeCaption($string) {
        if (preg_match(self::$regular_exp['NodeCaption'], $string, $matches)) {
            return $matches[1];
        } else {
            return false;
        }
    }

    public static function getNodeNumber($string) {
        if (preg_match(self::$regular_exp['NodeNumber'], $string, $matches)) {
            return $matches[2];
        } else {
            return false;
        }
    }

    public static function getPageflow($string) {
        if (preg_match(self::$regular_exp['Pageflow'], $string, $matches)) {
            return $matches[1];
        } else {
            return false;
        }
    }

    public static function getParentPageflow($string) {
        if (preg_match(self::$regular_exp['ParentPageflow'], $string, $matches)) {
            return $matches[1];
        } else {
            return false;
        }
    }

    public static function getStartInputParameters($string) {
        if (preg_match(self::$regular_exp['startInputParameters'], $string)) {
            return true;
        } else {
            return false;
        }
    }

    public static function getStartOutputParameters($string) {
        if (preg_match(self::$regular_exp['startOutputParameters'], $string)) {
            return true;
        } else {
            return false;
        }
    }

    private static function getScope($str_scope) {
        $scope = '';

        switch ($str_scope) {
            case 'pvar':
                $scope = 'PROCESS';
            break;

            case 'svar':
                $scope = 'SESSION';
            break;

            case '':
                $scope = 'CONSTANTS';
            break;
        }

        return $scope;
    }

    public static function getParameter($string, $handle) {
        if (preg_match(self::$regular_exp['Parameter'], $string, $matches)) {

            $data = [
                'tab_count' => strlen($matches[1]),
                'name' => $matches[2]
            ];

            //
            switch($matches[4]) {
                case 'list':
                case 'map':
                    $data['type'] = $matches[4];
                    $data['value'] = [];

                    if ($data['tab_count'] == 1) {
                        $data['name_out'] = $matches[11];
                        $data['scope'] = self::getScope($matches[10]);
                    }
                break;

                case 'null':
                    $data['type'] = 'null';
                    $data['value'] = 'null';

                    if ($data['tab_count'] == 1) {
                        $data['name_out'] = $matches[11];
                        $data['scope'] = self::getScope($matches[10]);
                    }
                break;

                default:
                    if (empty($matches[7])) { //если нету закрывающей кавычки 
                        $str_var = $string;
                        while (($str = fgets($handle)) !== false) {
                            $str_var .= $str;
                            if (strpos($str, '\'') !== false) {
                                break;
                            }
                        }

                        preg_match(self::$regular_exp['Parameter'], $str_var, $matches);
                    }

                    $data['value'] = $matches[6];
                    $data['type'] = $matches[8];
                    
                    if ($data['tab_count'] == 1) {
                        $data['name_out'] = $matches[11];
                        $data['scope'] = self::getScope($matches[10]);
                    }
                break;
            }

            return $data;
        } else {
            return false;
        }
    }

    public static function getLogByLogin($login, $file_source, $file_result) {
        if (file_exists($file_result)) {
            unlink($file_result); 
        }

        $handle = fopen($file_source, 'r');
        if (!$handle) {
            return false;
        }

        $tmp = '';
        $record = false;

        while (($string = fgets($handle)) !== false) {
            if ($start_block = self::getStartBlock($string)) {
                if (array_key_exists('login', $start_block) && $start_block['login'] == $login) {
                    $record = true;
                } else {
                    $record = false;
                }

                $tmp = $string;
            } elseif (!$record && $login == self::getLogin($string)) {
                $record = true;
                file_put_contents($file_result, $tmp, FILE_APPEND);
            }
            
            if ($record) {
                //тут записываем все строки если это наш блок
                file_put_contents($file_result, $string, FILE_APPEND);
            }
        }

        if (!feof($handle)) { //если мы не достигли конца файла
            //echo "Error: unexpected fgets() fail\n";
            return false;
        }

        fclose($handle);
    }

    public static function writeParameters($id, $type, $parameters) {
    	if (empty($parameters)) {
    		return false;
    	}

    	$id = (int) $id;

    	$file = ROOT_DIR . '/uploads/user-id/parameters/' . $id . '_' . $type . '.json';
    	file_put_contents($file, json_encode($parameters));	
    }
 

    public static function writeTreeBlocks($tree_blocks) {
    	if (empty($tree_blocks)) {
    		return false;
    	}

    	$file = ROOT_DIR . '/uploads/user-id/tree_blocks.json';
    	file_put_contents($file, json_encode($tree_blocks));	
    }


    public static function printLog($file) {
        $handle = fopen($file, 'r');
        if (!$handle) {
            return false;
        }

        $i = 0; //интератор по конторому мы задаем id для блока
        $tree_blocks = [];

        $data_block = [];
        $list_reg_exp = self::$list_reg_exp;
        $record_input_params = false;
        $record_output_params = false;
        $last_lists = []; //список листов которые мы октрыли для записи

        //для деревеа
        $pageflow_levels = [];
        $this_level = 1;
        $pageflow_levels[$this_level]['iterator_pageflow'] = 0;


        while (($string = fgets($handle)) !== false) {
            if (strlen(trim($string)) == 0) {
                continue;
            }

            if ($start_block = self::getStartBlock($string)) {

                if (!empty($data_block)) {
                	$data_block['id'] = $i;
                    $i++;

                   BlockHandler::recordData($data_block);
                }

                //устанавливаем значения по умолчанию
                $data_block = [];
                $list_reg_exp = self::$list_reg_exp;
                $record_input_params = false;
                $record_output_params = false;
                $last_lists = [];
            } else {
                $data_search = false; //false если ничего не найдено

                foreach ($list_reg_exp as $k => $name) {
                    $result = call_user_func('self::get' . $name, $string);
                    if ($result !== false) {
                        $data_block[$k] = $result; //запоминаем данные блока
                        unset($list_reg_exp[$k]);
                        $data_search = true;
                        break;
                    }
                }

                if (!$data_search) { 
                    if (self::getStartInputParameters($string)) {
                        $record_input_params = true;
                        $data_block['input_params'] = [];
                    } elseif (self::getStartOutputParameters($string)) {
                        $record_output_params = true;
                        $data_block['output_params'] = [];
                    } elseif ($record_input_params || $record_output_params) {
                        if (($param = self::getParameter($string, $handle)) !== false) {
                            if ($param['tab_count'] == 1) {
                                if ($record_input_params) {
                                    $data_block['input_params'][$param['name']] = $param;
                                    $link_param = &$data_block['input_params'][$param['name']];
                                } elseif($record_output_params) {
                                    $data_block['output_params'][$param['name']] = $param;
                                    $link_param = &$data_block['output_params'][$param['name']];
                                }
                            } else {
                                $last_lists[$param['tab_count']]['value'][$param['name']] = $param;
                                $link_param = &$last_lists[$param['tab_count']]['value'][$param['name']];
                            }

                            if (in_array($param['type'], ['list', 'map'])) {
                                if ($param['tab_count'] == 1) {
                                    $level = 3;
                                } else {
                                    $level = $param['tab_count'] + 1;
                                }

                                $last_lists[$level] = &$link_param;
                            } else {

                            }

                            unset($link_param['name']);
                            unset($link_param['tab_count']);
                        } else {
                            $record_input_params = false;
                            $record_output_params = false;
                        }
                    }
                }
            }
        }

        /*print_r(BlockHandler::$tree);
        exit;
*/

        if (!empty($data_block)) {
        	$data_block['id'] = $i;
        	BlockHandler::recordData($data_block);
        }

        self::writeTreeBlocks(BlockHandler::$tree);

        if (!feof($handle)) { //если мы не достигли конца файла
            //echo "Error: unexpected fgets() fail\n";
            return false;
        }

        fclose($handle);
    }
}


$GLOBALS['blocks'] = [
    'START' => [
    	'title' => 'Начало процесса',
        'class' => 'start'
    ],
    
    'END' => [
    	'title' => 'Завершение процесса',
        'class' => 'end'
    ],
    
    'UI_FORM' => [
    	'title' => 'Типовая форма',
        'class' => 'ui_form'
    ],
    
    'IF' => [
    	'title' => 'Простое условие',
        'class' => 'if'
    ],
    
    'JAVASCRIPT_ACTION' => [
    	'title' => 'Вызов скрипта',
        'class' => 'javascript_action'
    ],
    
    'EXTERNAL_ACTION' => [
    	'title' => 'Удаленный вызов',
        'class' => 'external_action'
    ],
    
    'INTERNAL_ACTION' => [
    	'title' => 'Локальный вызов',
        'class' => 'internal_action'
    ],
    
    'SUB_PAGEFLOW' => [
    	'title' => 'Вызов подпроцесса',
        'class' => 'sub_pageflow'
    ],
];

$GLOBALS['html'] = '';

function print_params($params) {
    if (empty($params)) {
        return false;
    }


    echo '
    <table border="1">
        <tr style="font-weight:bold;">
            <td>
                name
            </td>
            <td>
                type
            </td>
            <td>
                value
            </td>
            <td>
                name_out
            </td>
            <td>
                scope
            </td>
        </tr>

    ';


    foreach($params as $param) {


        echo '
        <tr>
            <td valign="top">'.$param['name'].'</td>
            <td valign="top">'.$param['type'].'</td>
            <td>
            ';

        if (is_array($param['value'])) {
            print_params($param['value']);
        } else {
            echo '<pre>'.htmlspecialchars($param['value']) . '</pre>';
        }   


        echo '
            </td>
            <td valign="top">'.$param['name_out'].'</td>
            <td valign="top">'.$param['scope'].'</td>
        </tr>
        ';


    }
    echo '</table>';
}

function print_block($data) { 
	global $blocks;

	if (array_key_exists($data['type'], $blocks)) {
		$data['title'] = $blocks[$data['type']]['title'];
		$data['class'] = $blocks[$data['type']]['class'];
	} else {
		$data['title'] = $data['type'];
		$data['class'] = 'default';
	}

	if (empty($data['input_params'])) {
		$data['input_params'] = [];
	}
	
	if (empty($data['output_params'])) {
		$data['output_params'] = [];
	}

	$GLOBALS['html'] .= \View::generate('block', $data);


    //echo '<h3>Input params:</h3>';
    //print_params($data['input_params']);
    //echo '<h3>Output params:</h3>';
    //print_params($data['output_params']);
}


/*
switch ($data['type']) {
    case 'DEBUG':
        //значит это то что нам нужно обрабатываем лог дальше 
    break;
    
    case 'INFO':
        //значит вся следующая инфа надо запомнить как едиое целое потомучто это наше сообщение которое мы вывели 
    break;
    
    case 'ERROR':
        //пока таольо заметил для блока "Удаленного вызова"
        //внутри блока ошибка
        
        ищем в начале строки                    
        An Error Occurred: 
        
        остальные две строки  это текст с описанем ошибки
        
        Error occured while calling service http://vebcashwl:8004/cashws/cashws?dsCashOperMassUpdate!
        FaultCode=500, FaultMessage="Command execution 'dsCashOperMassUpdate' failed: 'null'"
    break;
    
    
    default:
        //какаято неизвестная заись делаем вывод ошибки с номером строки где находится эта запись.. 
    break;
}*/