<?php
class Page extends Models 
{
	public $table = "pages";
	
	public $columns = [
		"autoid" => '',
		"controller" => '',
		"category" => '',
		"url" => '',
		"menu" => '',
		"title" => '',
		"meta_description" => '',
		"meta_keywords" => '',
		"content" => '',
		"created" => '',
		"modified" => ''
	];

	public $validation = [
		'controller' => ['required'],
		'category' => ['required'],
		'url' => ['required'],
		'menu' => ['required'],
		'title' => ['required'],
		'meta_description' => ['required'],
		'meta_keywords' => ['required'],
		'content' => ['required']
	];	
	
	public $nicerControllerNames = [
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

	public $defaultTitle = "Welcome";
	
	public $defaultMetaKeywords = "basic, web, development, programming, code, html, css, javascript";
	
	public $defaultMetaDescription = "Basic Web Development";
	
	public $defaultView = "welcome/welcome";
		
	public function manipulateFormDataBeforeFill($data){
		return $data;
	}
	
	public function beforeInsert(){
		$this->created = date('Y-m-d H:i:s', NOW);
		$this->modified = date('Y-m-d H:i:s', NOW);
	}	
	
	public function afterInsert(){
		
		$this->afterInsertAndUpdate();
	}

	public function beforeUpdate(){
		$this->modified = date('Y-m-d H:i:s', NOW);
	}	
	
	public function afterUpdate(){
		
		$this->afterInsertAndUpdate();
	}
	
	public function afterInsertAndUpdate(){
		
	}
	
	public function afterSaveMessage(){		
	
		return "";
	}
	
	public function afterSaveUrl(){
		
		return "";
	}
	
	public function afterUpdateMessage(){
		
		return "";
	}
	
	public function afterUpdateUrl(){
		
		return "";
	}		
	
	public function getControllers(){
		return $this->dbq("SELECT DISTINCT controller FROM pages");
	}
	
	public function getCategories($controller){
		return $this->dbq("SELECT category, url, menu FROM pages WHERE controller = '$controller'");	
	}
	
	public function getPage($controller, $category, $url){
		return $this->dbq("SELECT * from pages WHERE controller = '$controller' AND category = '$category' AND url = '$url'");
	}
}