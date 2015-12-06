<?php

	// Start Session
	session_start();
		
	// Include common helper functions
	include "helpers/functions.php";
	
	// Setup debugging
	showErrors('all');
	showMemoryUsage('all');
	
	// Set Paths
	set_include_path(
		get_include_path()
		.PATH_SEPARATOR.'models'
		.PATH_SEPARATOR.'helpers'
	);
	
	// Override path autoloader
	function __autoload($class){@include_once $class.'.php';}
	
	// Define useful constants
	$root = $_SERVER['DOCUMENT_ROOT']; // includes a forward slash at the end on my server, but apparently not on bluehost.
	if ($root[strlen($root)-1] != "/") $root .= "/";
	define('ROOT', $root );
	define('VIEWS', ROOT.'views/');
	define('HELPERS', ROOT.'helpers/');
	define('CONTROLLERS', ROOT.'controllers/');
	define('MODELS', ROOT.'models/');
	define('COMMON', ROOT.'common/');
	define('NOW', strtotime('now'));
	define('URI', $_SERVER['REQUEST_URI']);

	// Parse URI to get the paths
	$uri_controls = array_fill(0,8, '');
	$uri_criteria = "";
	$uri_parts = explode('?', $_SERVER['REQUEST_URI']);
	if(isset($uri_parts[1])) 
	{
		$uri_criteria = "/?".$uri_parts[1]; 
		$uri_query = parse_str($uri_parts[1]);
	}
	$uri_controls = explode('/', $uri_parts[0]) + $uri_controls;
	
	list($nothing, $controller, $category, $page) = $uri_controls;
	
	if(!$category)   $category = "about";
	if(!$controller) $controller = (isset($_COOKIE['controller'])) ? $_COOKIE['controller'] : "welcome";
	
	// Check for admin mode
	if(isset($_GET['admin']))
	{
		if(isset($_SESSION['admin'])) $_SESSION['admin'] = $_GET['admin'];
		if($_GET['admin'] == "password123") $_SESSION['admin'] = "edit";
		if($_GET['admin'] == "logout") unset($_SESSION['admin']);
	}
	
	$pageModel = new Page;
	
	// Get POST content
	if(isset($_POST['controller']))
	{
		$pageModel->fillFromFormSubmit($_POST);
		$pageModel->save();
		$_SESSION['admin'] = "view";
	}
	
	// Query the controllers for the header menu
	$controllers = $pageModel->getControllers();
	
	// Prepare output
	$edit_autoid = $edit_menu = $title = $meta_description = $meta_keywords = $edit_content = $edit_created = $edit_modified = "";
  	$categories = [];
	
	if($controller == "welcome") 
	{
		$pages['about'][] = ['url' => 'about', 'menu' => 'No pages yet'];
			
		$title = $pageModel->defaultTitle;
		$meta_keywords = $pageModel->defaultMetaKeywords;
		$meta_description = $pageModel->defaultMetaDescription;		
		$view = $pageModel->defaultView;
	} 
	else
	{
		// Get the categories for the menu.
		$menu_result = $pageModel->getCategories($controller);

		// If no content for this controller yet
		if (!$menu_result)
		{
			$pages['about'][] = ['url' => 'about', 'menu' => 'No pages yet'];
		}
		
		foreach($menu_result as $key => $value)
		{
			$categories[$value['category']] = $value['category'];
			$pages[$value['category']][] = ['url' => $value['url'], 'menu' => htmlentities($value['menu'])];
		}
		
		// Set default page if not specified
		if(!$page) $page = $pages[$category][0]['url'];
		
		// Query content
		$result = $pageModel->getPage($controller, $category, $page);
		
		if($result) 
		{			
			$title 				= $result[0]['title'];
			$meta_description 	= $result[0]['meta_description'];
			$meta_keywords 		= $result[0]['meta_keywords'];
			$edit_autoid 		= $result[0]['autoid'];
			$edit_menu 			= $result[0]['menu'];
			$edit_content 		= $result[0]['content'];
			$edit_created 		= $result[0]['created'];
			$edit_modified 		= $result[0]['modified'];
		}
		else
		{
			$title = "No content yet";
			$meta_keywords = $meta_description = "";
		}
		
		// All of them use layout/default
		$view = "layout/default";
	}
	
	// Use nicer looking names for the controllers. Used in the header. Should be a separate table.
	$controller_array = $pageModel->nicerControllerNames;
	
	// For the accordion menu
	$section_index = 0;
	
	// Build the page
	include VIEWS."common/header.php";
	include VIEWS."$view.php";
	include VIEWS."common/footer.php";
	
?>