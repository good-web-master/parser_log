<?php

namespace App\Controllers;


use App\Model\LogParser;


class HomeController {

    static public function index() {
        
        $load_file = [
            'remote' => '/opt/diasoft/Oracle/Middleware/Oracle_Home/user_projects/domains/flex01/logs/pageflow_controller.log',
            'local' => __DIR__ . '/../../uploads/pageflow_controller.log'
        ];


        //LogParser::getLogByLogin('nsilaeva', $load_file['local'], $load_file['local'] . '_fn');

        //LogParser::printLog($load_file['local'] . '_fn');
        


        return \View::generate('index', []);

        //пока без моделей

    	/*$db = new \Db;
    	$result = $db->select('users', '*');

    	$users = [];
    	while ($user = $result->fetch_assoc()) {
    		$users[] = $user;
    	}

    	$data = [
    		'users' => $users
    	];

        return \View::generate('index', $data);*/

        //return view('main.home', ['properties' => $properties]);
    	/*
    	$users = file(ROOT_DIR . '/users.txt');

    	foreach ($users as $user) {
    		
    		наполнение базу пользователями 
    		list($surname, $name, $patronymic) = explode(' ', trim($user), 3);

    		$data = [
    			'surname' => $surname,
    			'name' => $name,
    			'patronymic' => $patronymic,
    			'birthday' => rand(1960, 1999) . '.' . rand(1, 12) . '.' . rand(1, 28),
    			'passport_series' => rand(10, 99) . rand(10, 99),
    			'passport_id' => rand(10, 99) . rand(10, 99) . rand(10, 99),
    			'passport_date_issue' => rand(2000, 2017) . '.' . rand(1, 12) . '.' . rand(1, 28),
    			'passport_department_name' => 'Отделом УФМС России по Чувашской республике в Ленинском районе гор. Чебоксары',
    			'passport_department_id' => '210-025',
    		];

    		$db = new \Db;
    		$db->insert('users', $data);*/

    		/*паспортные данные 
    		серия паспорта = 97 12
    		номер паспорта = 997331
    		дата выдачи = rand(1, 28) . '.' . rand(1, 12) . '.' . rand(1960, 1999);
    		кто выдал

    		КОД подразделениЯ

    	}

		*/

    	/* заполняем таблицу счета у для пользователей 
    	for ($i = 0; $i < 1000 ; $i++) {
    		$data = [
    			'user_id' => rand(1, 63),
    			'opening_date' => rand(2016, 2017) . '-' . rand(1, 8) . '-' . rand(1, 28),
    			'down_payment' => rand(1000, 10000),
    			'percent_rate' => rand(3, 6)
    		];

    		$db = new \Db;
    		$db->insert('bank_books', $data);
    	}*/
			

			/*id - номер счета
			user_id - кому пренадлежит данный счет
    		opening_date - дата открытия 
    		down_payment - первоначальный взнос
    		percent_rate - годовая процентная ставка 

    		бутстреп? скорей всего да
    		пангинация? не буду заморачиваться 

    		при нажати на одного из пользователей в списке появлеятся модальное окно с карточкой этого пользователя

    		список полььзователей с лева (кнопка обновить списко пользователей ajax)
    		карточку подгружать ajax карточка справа 


    		счета клиента 

    		расчитать капитализацию каждого счета на текущую дату
    		- номер счета
    		- дата открытия 
    		- первоначальный взнос
    		- годовая процентная ставка 

    		- капитализация расчтывается js 

    		ставка рассчитывается как сумма первоначального взноса и всех причисленных процентов по счету, с учетом причисления предыдущих процентов к первоначальному взносу перед начислением новых. 

    		Начисление и причисление процентов производится 1-го числа каждого месяца. 

    		Ежемесячная процентная ставка - 1/12 от годовой процентной ставки. */


        return ;
    }
}
