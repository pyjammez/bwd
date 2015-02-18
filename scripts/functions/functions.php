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

?>