<!DOCTYPE html>
<html>

	<title><?php echo $title; ?></title>

	<meta name="keywords" content="<?php echo $meta_keywords; ?>" />
	<meta name="description" content="<?php echo $meta_description; ?>" />
	<meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1' />

	<link rel="icon" type="image/x-icon" href="/favico.ico" />
	<link rel="stylesheet" href="/common/css/default.css" />

	<link href='http://fonts.googleapis.com/css?family=Yellowtail' rel='stylesheet' type='text/css'>
	<script src="/common/js/jquery-1.8.0.min.js"></script>
	<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
	<script src="/common/js/js.js"></script>	

<body class="<?php echo $controller; ?>_body">

<div class="header_wrap">
<header>

	<a href='/welcome' id='logo' title="Web Developer Logo">Basic Web Development</a>
	<span id='main_nav_chosen' onClick='display_menu();'><?php echo $controller_array[$controller]; ?></span>
	
	<nav id='main_nav'>
	<?php
	foreach($controller_array as $key => $value)
	{
		if($controller == $key){$chosen = " class='chosen'";} else {$chosen = "";}
		echo "<a href='/$key' title='How To Learn $value'$chosen><span />$value</a>\n\t";
	}
	?></nav>
	
</header>
</div>

<div class="content_wrap">
<div class="content">
