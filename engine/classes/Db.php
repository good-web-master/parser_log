<?php
final class Db extends mysqli{
	
	private $prefix = '';
	
	public function __construct(array $config = array()) {
		
		if (!$config) {
			$config_name = 'database';
			$config = get_config($config_name);
		}
		
		mysqli_report(MYSQLI_REPORT_STRICT);

		try {
			parent::__construct($config['hostname'], $config['username'], $config['password'], $config['database']);
		} catch (Exception $e) {
			ErrorHandler::printDbMessage(
				'Не удалось соединиться с БД, проверте файл конфигурации: ' . $config_name . '.',
				$this->connect_errno,
				(!strncasecmp(PHP_OS, 'WIN', 3)) ? iconv('windows-1251', CONTENT_CHARSET, $e->getMessage()) : $e->getMessage(), 
				$config_name, 
				1
			);
		}

		if (!parent::set_charset($config['charset'])) {
			ErrorHandler::printDbMessage('Не удается установить кодировку: ' . $config['charset'] . '.', $this->errno, $this->error, $config_name, 1);
		}
		
		$this->setTimeZone(date('P'));
		
		$this->prefix = $config['prefix'];
	} 

	public function __set($a,$b) {
	}
	
	public function setPrefix($prefix) {
		$this->prefix = $prefix;
	}
	
	public function getPrefix() {
		return $this->prefix;
	}
	
	public function query($sql) {
		
		$result = parent::query($sql);
		
		if (!$result) {
			
			$debug_backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
			
			if ($debug_backtrace[0]['class'] == __CLASS__) {
				$i=1;
			} else {
				$i=0;
			}
			
			ErrorHandler::printDbMessage('Не удается выполнить запрос к БД.', $this->errno, $this->error, $debug_backtrace[$i]['file'], $debug_backtrace[$i]['line'], $sql);
		} else {
			return $result;
		}
	}
	
	public function setTimeZone($time_zone) {
		return $this->query('SET time_zone=\'' . $this->escape_string($time_zone) . '\'');
	}
	
	public function inc($table, $data, $where = '', $inc = 1) {
		
		$table = $this->prefix . $table;
		
		if ($where != '') {
			$where = ' WHERE ' . $where;
		}
		
		$inc = (int)$inc;
		$query = '`' . $data . '`=`' . $data . '`+' . $inc;
		$query = 'UPDATE `' . $table . '` SET ' . $query.' ' . $where;
		
		$this->query($query); 
		return $this->isSuccess();
	}
 
	public function dec($table, $data, $where = '', $dec = 1) {
		
		$table = $this->prefix . $table;
		
		if ($where != '') {
			$where = ' WHERE ' . $where;
		}
		
		$dec=(int)$dec;
		$query = '`' . $data . '`=`' . $data . '`-' . $dec;
		$query = 'UPDATE `' . $table . '` SET ' . $query.' ' . $where;
		
		$this->query($query); 
		return $this->isSuccess();
	}

	public function select($table, $data, $where = '') {
		$table = $this->prefix . $table;
		
		if ($where != '') {
			$where = ' WHERE ' . $where;
		}
		return $this->query('SELECT ' . $data . ' FROM `' . $table . '`' . $where); 
	}
	
	public function insert($table, array $data) {
		
		$table = $this->prefix . $table;
		
		$fields = '`' . implode('`,`',array_keys($data)) . '`';
		$data = array_map(array($this, 'escape_string'), $data);
		$values = "'" . implode("','", $data) . "'" ;
		
		$this->query('INSERT INTO `' . $table.'` (' . $fields . ') VALUE (' . $values . ')');
		return $this->isSuccess();
	}
 
	public function update($table, array $data, $where = '') { //Метод обновления данных в таблице.
		
		$table = $this->prefix . $table;
		
		if ($where) {
			$where = ' WHERE ' . $where;
		}
		
		$query=array();
		
		foreach ($data as $field=>$val) {
			$query[]='`'.$field.'`=\''.$this->escape_string($val).'\'';
		}
		
		$query=implode(',',$query);
		$query='UPDATE `'.$table.'` SET '.$query.' '.$where;
		
		if ($this->query($query)===false) {
			return false;
		} else {
			return true;
		}
	}
 
	public function delete($table, $where = '') { //Метод удаления данных в таблице.
		
		$table = $this->prefix . $table;
		
		if ($where) {
			$where = ' WHERE ' . $where;
		}
		
		$this->query('DELETE FROM `'.$table.'`'.$where);
		
		return $this->isSuccess();
	}
	
	public function countRows($table, $where = '') {
		
		$table = $this->prefix . $table;
		
		if ($where) {
			$where = ' WHERE ' . $where;
		}
		
		$result = $this->query('SELECT 1 FROM `'.$table.'` '.$where);
		
		return $result->num_rows;
	}
	
	public function isSuccess() {
		if ($this->affected_rows) {
			return true;
		} else {
			return false; 
		}
	}
}