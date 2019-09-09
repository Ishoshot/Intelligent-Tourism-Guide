<?php
class DB {
	private static $_instance = null;
	private $_pdo,
	$_query,
	$_error = false,
	$_results,
	$_count = 0;

	private function __construct() {
		try{
			$this->_pdo = new PDO('mysql:host='. Config::get('mysql/host'). ';dbname=' .Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));

		}catch(PDOException $e){
			die($e->getMessage());
		}
	}

	 public static function getInstance() {
	 	if(!isset(self::$_instance)) {
	 		self::$_instance=new DB();
	 	}
	 	return self::$_instance;
	 }

	 public function query($sql, $params = array()){
	 	$this->_error=false;
	 	if($this->_query = $this->_pdo->prepare($sql)){
	 			$x = 1;
	 			if(count($params)) {
	 				foreach($params  as $param) {
	 					$this->_query->bindValue($x, $param);
	 					$x++;
	 				}
	 			}
	 	if($this->_query->execute()){
	 		
	 	$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ); 
	 	$this->_count=$this->_query->rowCount();
	 	}else{
	 		$this->_error = true;
	 	}
	  }
	  return $this;
	}
		
	// THE ACTION METHOD SPECIFIES THE ACTION WE WANT TO PERFRM IN A DB UISNG FIELD OPERATOR VALUE E.G SELECT FROM TBALE WHERE USER = USERNAME, THE SELECT IF THE ACTION


	public function action($action,$table,$where =array()){
			
			if (count($where) === 3) {
				$operators =array('=', '>', '<', '>=', '<=');

				 $field      = $where[0];
				 $operator   = $where[1];
				 $value      = $where[2];

				 if (in_array($operator, $operators)){
				 	$sql = "{$action}  FROM {$table} WHERE {$field} {$operator} ?";

				 		if (!$this->query($sql, array($value))->error()) {

				 				return $this;
				 		}
				 }
			}
			return false;
	}



	// THE GET METHOD ENABLES US TO QUERY A DB TABLE WITHOUT WRITING _query
	public function get($table,$where){
		return $this->action('SELECT *', $table, $where);
	}
// THE GET METHOD ENABLES US TO QUERY A DB TABLE WITHOUT WRITING QUERY



// FUNCTION DELETE FROM TABLE
	public function delete($table,$where){
		return $this->action('DELETE',$table, $where);
	}
// FUNCTION DELETE FROM TABLE


// FUNCTION INSERT INTO
public function insert($table, $fields =array()){
		$keys = array_keys($fields);
		$values = '';
		$x = 1;

		foreach($fields as $field) {
			$values .= '?';
			if ($x < count($fields)) {
				$values .= ', ';
			}
			$x++;
		}

		$sql = "INSERT INTO {$table} (`" . implode('`, `', $keys)  .  "`) VALUES ({$values})";

		if (!$this->query($sql, $fields)->error()) {
			return true;
			}

	return false;
	}
// FUNCTION INSERT INTO


// FUNCTION UPDATE DATA
	public function update($table,  $id, $fields){
		$set ='';
		$x = 1;

		foreach($fields as $name => $value) {
			$set .= "{$name} = ?";
		if($x < count($fields)) {
			$set .= ', ';
		}
		$x++;
	}

		$sql = "UPDATE {$table} SET {$set} WHERE users_id = {$id}";
		
	if (!$this->query($sql, $fields)->error()) {
			return true;
			}

	return false;
	}
// FUNCTION UPDATE DATA



// FUNCTION RESULTS FROM TABLE
	public function results(){
		return $this->_results;
	}
// FUNCTION RESULTS FROM TABLE



// FUNCTION FIRST
	public function first(){
		return $this->results()[0];
	}
// FUNCTION FIRST


// FUNCTION ERROR
	public function error(){
		return $this->_error;
	}
// FUNCTION ERROR


// FUNCTION COUNT
	public function count(){
	return $this->_count;
	}
// FUNCTION COUNT
 
}

?>