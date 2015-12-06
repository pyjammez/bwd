<?php 
class Database {
	
	private $db_host = 'localhost';   
	private $db_user = 'root'; 
	private $db_pass = 'poo123'; 
	private $db_name = 'icanhasm_bwd';

	public function query($query, $data = null)
	{
		try
		{	
			$pdo = new PDO("mysql:host=".$this->db_host.";dbname=".$this->db_name,$this->db_user,$this->db_pass);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $pdo->prepare($query);
			$stmt->execute($data);
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} 
		catch(PDOException $e) 
		{
			echo 'ERROR: ' . $e->getMessage();
		}
	}
	
	public function insert($query, $data = null)
	{	
		try 
		{
			$pdo = new PDO("mysql:host=".$this->db_host.";dbname=".$this->db_name,$this->db_user,$this->db_pass);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $pdo->prepare($query);
			$stmt->execute($data);
			return $pdo->lastInsertId();
		} 
		catch(PDOException $e) 
		{
			echo 'Error: ' . $e->getMessage();
		}
	}
	
	public function dbq($sql, $data = null){
		// if(!$result) error_log($query,3,'sql_error.log');
		return $this->query($sql, $data);
	}
	
	public function dbi($table, $data){
		foreach($data as $key => $value)
		{
			$placeholders[] = ":$key";
			$values[":$key"] = $value;
			$cols[] = $key;
		}
		if (!isset($placeholders)) return false;
		$placeholders = implode(',',$placeholders);
		$cols = implode(',',$cols);
		$string = "INSERT into $table ($cols) VALUES($placeholders)";
		return $this->insert($string, $values);	
	}
	
	public function dbu($table, $data, $where = null){
		foreach($data as $key => $value)
		{
			$placeholders[] = "{$key} = :{$key}";
			$values[":{$key}"] = $value;
		}
		$string = implode(', ', $placeholders);
		$query = "UPDATE $table set $string WHERE autoid = :autoid";
		if($where) $query .= " AND ".implode(' AND ', $where);
		return $this->insert($query, $values);
	}
	
	public function dbd($id, $table){
		$executearray[":autoid"] = $id;
		$executestring = "UPDATE $table SET active = 0 WHERE autoid = :autoid";
		return $this->insert($executestring, $executearray);	
	}
}
?>