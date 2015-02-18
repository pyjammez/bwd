<?php

	// Important startup stuff that every website needs.
	session_start();
	set_include_path(get_include_path().PATH_SEPARATOR.'models'.PATH_SEPARATOR.'objects'.PATH_SEPARATOR.'controllers');
	function __autoload($class){@include_once $class.'.php';}
	$root = $_SERVER['DOCUMENT_ROOT'];
	if ($root[strlen($root)-1] != "/"){$root .= "/";}
		
	// Constants
	define('ROOT', $root ); // this includes a forward slash at the end on my server, but apparently not on bluehost.
	define('VIEWS', ROOT.'views/');
	define('SCRIPTS', ROOT.'scripts/');
	define('OBJECTS', ROOT.'objects/');
	define('CONTROLLERS', ROOT.'controllers/');
	define('MODELS', ROOT.'models/');
	define('COMMON', ROOT.'common/');
	define('NOW', strtotime('now'));
	
	// Parse URI
	$uri_controls = array_fill(0,8, ''); // fill them with empty values.
	$uri = $_SERVER['REQUEST_URI']; 
	$uri_parts = explode('?',$uri);
	if(isset($uri_parts[1])) 
	{
		$uri_criteria = "/?".$uri_parts[1]; 
		$uri_query = parse_str($uri_parts[1]);
	}
	else $uri_criteria = ""; 
	$uri_controls = explode('/',$uri_parts[0]) + $uri_controls;
	
	// FUNCTIONS
	function dbq($sql)
	{
		$db = new Database();
		$result = $db->query($sql);
		if(!$result) $result = array();
		return $result;
	}
	function dbi($table, $form_data)
	{
		foreach($form_data['structure'] as $key => $value){
			if($value[0] != "script"){
				$insertpars[] = ":{$value[1]}";
				$executearray[":{$value[1]}"] = $form_data['data'][$value[1]];
			}
		}
		$insertparstring = implode(', ', $insertpars); //echo $insertparstring;
		$executestring = "INSERT into $table VALUES($insertparstring)";		//print_r($executearray);
		$db = new Database();  
		$db->insert($executestring, $executearray);	
	}
	function dbu($autoid, $table, $form_data)
	{
		foreach($form_data['structure'] as $key => $value){
			if($value[0] != "script" && $value[0] != "database"){ 		// ignore scripts and database rows
				$updatepars[] = "{$value[1]} = :{$value[1]}";
				$executearray[":{$value[1]}"] = $form_data['data'][$value[1]];
			}
		}
		$updateparstring = implode(', ', $updatepars);
		$executestring = "UPDATE $table set $updateparstring WHERE autoid = :autoid";//echo $executestring;
		$executearray[':autoid'] = $autoid;//print_r($executearray);
		$db = new Database();  
		$db->insert($executestring, $executearray);
	}
	function dbd($autoid, $table)
	{
		$executearray[":autoid"] = $autoid;
		$executestring = "UPDATE $table SET active = 0 WHERE autoid = :autoid";
		$db = new Database();  
		$db->insert($executestring, $executearray);	
	}
	function form_success($string){echo "<div class='form_success'>$string</div>";}
	function form_failure($string){echo "<div class='form_failure'>$string</div>";}
	function url_friendly($string) {return strtolower(str_replace(" ", "-", $string));}
	function className($string){return ucfirst(preg_replace_callback('/[^a-zA-Z](\w)/',function($matches){	return strtoupper($matches[1]);	},$string));}
	function user_friendly($string) 
	{	
		$string = str_replace("-", " ", $string);
		if(strlen($string)<4){$string = strtoupper($string);}
		else {$string = ucwords($string);}
		return $string;	
	}
	function showErrors($type = null)
	{
		ini_set('display_errors',1);
		error_reporting(E_ALL);
	}
	function showMemoryUsage($type = null)
	{
		$mtime = explode(" ", microtime());
		$Smtime = $mtime[1] + $mtime[0];
		$start_memory_usage = memory_get_usage();
	}	
	function user_friendly_category($string) 
	{	
		$strings = explode("-", $string);
		foreach($strings as $key => $val){
			if($val != "and"){$strings[$key] = ucfirst($val);}
		}
		$string = implode(' ', $strings);
		return $string;
	}