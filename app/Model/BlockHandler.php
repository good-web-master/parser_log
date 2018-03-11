<?php
namespace App\Model;

class BlockHandler {

    public static $tree = []; //дерево блоков

    //вспомогательный элемент для построения дерева
    /*
        $pageflow_levels = [
            $level => [ //где $level - это уровень вложености
                'link_parent' => , // хранит ссылку на родительский узел дерева
                'link_last_block' => , //хранит ссылку на узел дерева, ссылка на последний блок на текущем уровне
                'iterator_pageflow' => , //счетик пэджфлоу, служит для создания уникального ключа в массиве, так как записи у нас отображаются по временной шкале, имена ключей пэджфлоу могут совпадать это интератор это исправляет
                'form_open' => , //флаг который обозначет то что у нас открыта форма 
            ]
        ]
    */
    private static $this_level = 1;

    private static $pageflow_levels = [
        1 => [
            'link_parent' => null,
            'link_last_block' => null,
            'iterator_pageflow' => 0,
            'form_open' => false,
            'sub_pageflow_open' => false
        ]    
    ];


    private static function writeParameters($id, $type, $parameters) {
        if (empty($parameters)) {
            return false;
        }

        $id = (int) $id;

        $file = ROOT_DIR . '/uploads/user-id/parameters/' . $id . '_' . $type . '.json';
        file_put_contents($file, json_encode($parameters)); 
    }

    public static function recordData(array $data_block) {
        if (isset($data_block['input_params'])) {
            self::writeParameters($data_block['id'], 'in', $data_block['input_params']);
            $data_block['count_input_params'] = count($data_block['input_params']);
            unset($data_block['input_params']);
        }

        if (isset($data_block['output_params'])) {

            // сделать совмещение блоков для формы и подпроцесса
            switch ($data_block['type']) {
                case 'SUB_PAGEFLOW':
                case 'UI_FORM':
                    $output_params = $data_block['output_params'];
                    $data_block = &self::$pageflow_levels[self::$this_level - 1]['link_last_block'];
                    self::writeParameters($data_block['id'], 'out', $output_params);
                    $data_block['count_output_params'] = count($output_params);

                    self::$pageflow_levels[self::$this_level -1]['sub_pageflow_open'] = false;
                    self::$pageflow_levels[self::$this_level - 1]['form_open'] = false;
                    self::$this_level--;
                    return;
                break;
            }

            self::writeParameters($data_block['id'], 'out', $data_block['output_params']);
            $data_block['count_output_params'] = count($data_block['output_params']);
            unset($data_block['output_params']);
        }


        //прежде чем добавлять блок в дерево надо проверить действительно ли даный блок является ребенком даного узла
        //эта проверка нужна так как пользователь может работать сразу в нескольких вкладках
        if (
            $data_block['type'] != 'SUB_PAGEFLOW' && $data_block['type'] != 'UI_FORM'
            &&
            self::$pageflow_levels[self::$this_level - 1]['link_last_block']['pageflow'] != $data_block['parent_pageflow']
        ) {
            self::$this_level = 1;
        }


        $data_level = &self::$pageflow_levels[self::$this_level];

        if (isset($data_level['link_last_block']) && $data_level['link_last_block']['pageflow'] != $data_block['pageflow']) {
            $data_level['iterator_pageflow']++;
        }


        if (!isset($data_level['link_parent'])) {
            $prev_level = &self::$pageflow_levels[self::$this_level - 1];

            if (isset($prev_level) && isset($prev_level['link_last_block'])) {
                $data_level['link_parent'] = &$prev_level['link_last_block'];
            } else {
                $data_level['link_parent'] = &self::$tree;
            }

        }



        $id_pageflow = $data_level['iterator_pageflow'] . $data_block['pageflow'];
        $parent_node = &$data_level['link_parent'];

        if (self::$pageflow_levels[self::$this_level - 1]['sub_pageflow_open']) {
            $parent_node[] = [
                'node_type' => 'block',
                'block' => $data_block
            ];

            //записываем ссылку на последний блок
            $key = count($parent_node) - 1;
            $data_level['link_last_block'] = &$parent_node[$key]['block'];

            $this_node = &$parent_node[$key];
        } else {
            if (!array_key_exists($id_pageflow, $parent_node)) {
                $parent_node[$id_pageflow] = [
                    'node_type' => 'pageflow',
                    'pageflow' => ['name' => $data_block['pageflow']]
                ];
            }
            
            $parent_node[$id_pageflow]['childs'][] = [
                'node_type' => 'block',
                'block' => $data_block
            ];

            //записываем ссылку на последний блок
            $key = count($parent_node[$id_pageflow]['childs']) - 1;
            $data_level['link_last_block'] = &$parent_node[$id_pageflow]['childs'][$key]['block'];

            $this_node = &$parent_node[$id_pageflow]['childs'][$key];
        }





        switch ($data_block['type']) {
            case 'SUB_PAGEFLOW':
                if (array_key_exists('count_input_params', $data_block)) {
                    self::$pageflow_levels[self::$this_level]['sub_pageflow_open'] = true;
                    
                    self::$this_level++;

                    $this_node['childs'] = [];
                    self::$pageflow_levels[self::$this_level]['link_parent'] = &$this_node['childs'];
                } elseif (array_key_exists('count_output_params', $data_block)) {

                    self::$pageflow_levels[self::$this_level -1]['sub_pageflow_open'] = false;
                    self::$this_level--;
                }
            break;

            case 'UI_FORM':
                if (array_key_exists('count_input_params', $data_block)) {
                    self::$pageflow_levels[self::$this_level]['form_open'] = true;
                    
                    self::$this_level++;

                    $this_node['childs'] = [];
                    self::$pageflow_levels[self::$this_level]['link_parent'] = &$this_node['childs'];
                } elseif (array_key_exists('output_params', $data_block)) {
                    self::$pageflow_levels[self::$this_level - 1]['form_open'] = false;
                    self::$this_level--;
                }
            break;
            
            default:
            break;
        }
    }
}

