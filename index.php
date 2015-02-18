<?php

	include "framework/index.php";
	include SCRIPTS."functions/functions.php";
	
	showErrors('all');
	showMemoryUsage('all');
	
	// Parse URI
	list($nothing, $controller, $category, $page) = $uri_controls;

	// If no controller, check if cookie exists, and if not, default to /welcome
	if(!$controller)
	{
		if(isset($_COOKIE['controller'])) $controller = $_COOKIE['controller'];
		else $controller = "welcome";
	}
	
	// Admin mode
	if(isset($_GET['admin']))
	{
		if(isset($_SESSION['admin'])) $_SESSION['admin'] = $_GET['admin'];
		
		if($_GET['admin'] == "password123")
		{
			$_SESSION['admin'] = "edit";
		}
		if($_GET['admin'] == "logout")
		{
			unset($_SESSION['admin']);
		}
	}
	
	// POST content
	if(isset($_POST['controller']))
	{

		$form_data = [
			'structure' => [
				["auto","autoid"],
				["input","controller"],
				["input","category"],
				["input","url"],
				["input","menu"],
				["input","title"],
				["input","meta_description"],
				["input","meta_keywords"],
				["textarea","content"],
				["input","created"],
				["input","modified"]
			],
			'data' => [
				"autoid"=>"",
				"controller"=>$_POST['controller'],
				"category"=>$_POST['category'],
				"url"=>$_POST['url'],
				"menu"=>$_POST['menu'],
				"title"=>$_POST['title'],
				"meta_description"=>$_POST['meta_description'],
				"meta_keywords"=>$_POST['meta_keywords'],
				"content"=>$_POST['content'],
				"created"=>date('Y-m-d H:i:s',NOW),
				"modified"=>date('Y-m-d H:i:s',NOW)
			],
		];
		
		if(!empty($_POST['autoid']))
		{ 
			// Update page by removing the ID and created date value.
			unset($form_data['data']['autoid']);
			unset($form_data['data']['created']);
			unset($form_data['structure'][0]);
			unset($form_data['structure'][9]);
			dbu($_POST['autoid'], "pages", $form_data);
		}
		else
		{
			// Insert page.
			dbi("pages",$form_data);
		}
		$_SESSION['admin'] = "view";

	}
	
	
	
	// Query the controllers
	$controllers = dbq("SELECT DISTINCT controller FROM pages");
	
	// Nicer looking names for the controllers
	$controller_array = [
		'welcome' => 'Welcome',
		'html' => 'HTML',
		'css' => 'CSS',
		'javascript' => 'JavaScript',
		'php' => 'PHP',
		'mysql' => 'MySQL',
		'node' => 'Node',
		'angular' => 'Angular',
		'backbone' => 'Backbone',
		'mongo' => 'MongoDb',
		'regexp' => 'RegExp'
	];
	
	// Query pages
	if(!$category) $category = "about";
	if($controller == "welcome")$menu_result = array();
	else $menu_result = dbq("SELECT category, url, menu FROM pages WHERE controller = '$controller'");

	// Get distinct categories
	$categories = [];
	if(!$menu_result)$pages['about'][] = ['url' => 'asdf', 'menu' => 'No pages yet'];
	foreach($menu_result as $key => $value)
	{
		$pages[$value['category']][] = ['url' => $value['url'], 'menu' => htmlentities($value['menu'])];
		$categories[$value['category']] = $value['category'];
	}

	// Page layout. 
	if($controller == "welcome")
	{
		$title = "Welcome";
		$meta_keywords = "basic, web, development, programming, code, html, css, javascript";
		$meta_description = "Basic Web Development";
		$layout = "welcome";
	}
	else 
	{
		// Query content
		if(!$page) $page = $pages[$category][0]['url'];
		$result = dbq("SELECT * from pages WHERE controller = '$controller' AND category = '$category' AND url = '$page'");
		if($result)
		{
			$edit_autoid = $result[0]['autoid'];
			$edit_menu = $result[0]['menu'];
			$title = $result[0]['title'];
			$meta_description = $result[0]['meta_description'];
			$meta_keywords = $result[0]['meta_keywords'];
			$edit_content = $result[0]['content'];
			$edit_created = $result[0]['created'];
			$edit_modified = $result[0]['modified'];
		}
		else
		{
			$edit_autoid = $edit_menu = $title = $meta_description = $meta_keywords = $edit_content = $edit_created = $edit_modified = "";
		}
		$layout = "default";
	}
	
	// Build the page
	include COMMON."html/header.php";
	include VIEWS."layout/$layout.php";
	include COMMON."html/footer.php";
	
?>