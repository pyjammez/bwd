<?php

	function display_side_nav()
	{	
		$uri2 = $GLOBALS['uri_controls'][2];
		foreach($GLOBALS['menu_array'][$GLOBALS['view']] as $key => $value)
		{
			if($GLOBALS['uri_controls'][2] == $key){$chosen = " class='chosen'";} else {$chosen = "";}
			echo "<li><a href='/{$GLOBALS['controller']}/$key' title='{$value[1]}' $chosen>".htmlentities($value[0])."</a></li>";
		}	
	}
	
	// $menu_array[section][url]['link', 'title', 'meta_description', 'meta_keywords']
	function prev_page()
	{
		global $controller, $category, $pages, $view_index;
		$values = array_values($pages[$category]);
		if(isset($values[$view_index-1]))
		{
			$newvalues = $values[$view_index-1];
			$keys = array_keys($pages[$category]);
			$url = "/$controller/$category/{$newvalues['url']}";
			echo "<a id='previous_page' href='$url'><< {$newvalues['menu']}</a>";
		}
	}
	
	function next_page()
	{
		global $controller, $category, $pages, $view_index;
		// $pages['about'][0]['url'] = 'what_is_css';
		$values = array_values($pages[$category]);
		if(isset($values[$view_index+1]))
		{
			$newvalues = $values[$view_index+1];
			$keys = array_keys($pages[$category]);
			$url = "/$controller/$category/{$newvalues['url']}";
			echo "<a id='next_page' href='$url'>{$newvalues['menu']} >></a>";
		}
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
	
?>