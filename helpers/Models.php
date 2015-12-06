<?php
class Models extends Database
{
	// All models will use these methods...
	
	public $data = array(); // holds all the form data.
	public $success_message = '';
	public $success_url = '';
	public $errors = [];
		
	function addDbqToModel($sql){
		// Adds the database results into the class. eg: $this->name = "$results[0]['name'];
		$result = $this->dbq($sql);
		if($result) foreach($result[0] as $k => $v) $this->$k = $v;
	}
	
	function addSessionToModel($session, $name){	
		// Take the org profile session and add it to the model for use in the page.
		foreach($_SESSION[$session][$name] as $k => $v)	$this->$k = $v;
	}
	
	public function required($value){
		return $value; // do nothing. let it run throuh
	}
	
	public function hashPassword($password){		
		return SHA1($password);
	}
		
	public function __get($name){
        if(isset($this->$name)) return $this->$name;
        else return '';

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }
	
	public function save()
	{	
		if(isset($this->data['autoid']))
		{
			$this->beforeUpdate();
			if(!empty($this->errors)) return;
			
			$this->prepareData();
			
			$this->dbu($this->table, $this->data);
			
			$this->afterUpdate();
			
			$this->success_message = $this->afterUpdateMessage();
			$this->success_url = $this->afterUpdateUrl();
		}
		else
		{
			$this->beforeInsert();
			if(!empty($this->errors)) return;
			
			$this->prepareData();

			$this->autoid = $this->dbi($this->table, $this->data);
			
			$this->afterInsert();
			
			$this->success_message = $this->afterSaveMessage();
			$this->success_url = $this->afterSaveUrl();
		}
		
	}
	
	public function prepareData() {
		// Put values into data array for database layer insert
		foreach($this->columns as $key => $value) {
			if(isset($this->$key)) $this->data[$key] = $this->$key;
			else $this->data[$key] = $this->data[$key] ?: $value; // Set default
		}
	}

	public function fillFromFormSubmit($data)
	{	
		// Any manipulation to data before filling the model? 
		$data = $this->manipulateFormDataBeforeFill($data);
		
		if(!$data) exit("No data");
		
		foreach($this->columns as $key => $value)
		{
			// If data exists, run it through validation before creating variable. Some validations are in this class, some are in the table class.
			if(isset($data[$key]))
			{
				if(isset($this->validation[$key])) // yes hashPassword or duplicateEmailCheck
				{
					foreach($this->validation[$key] as $validation) // hashPassword
					{
						$data[$key] = $this->$validation($data[$key]); //hashPassword('poo123')
					}
				}
				$this->data[$key] = $data[$key];
			}
			else
			{
				if(isset($this->validation[$key])) 
				{
					if(in_array('required',$this->validation[$key])) exit("$key is required.");
				}
				$this->data[$key] = $value; // default value.
			}
		}
	}
	
	public function delete($id){
		$this->dbd($id, $this->table);
		$return = "/logout";	
		return $return;
	}
}
?>