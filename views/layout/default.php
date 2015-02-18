	
	<!-- Mobile only -->
	<span id="content_nav_chosen" onclick="display_section_menu();"><?php echo "$category : ".htmlentities($title); ?></span>
	
	<!-- Categories tab menu-->
	<section class='top'>
	
		<nav>
		<?php
		$i=0;
		if(!$categories){$categories[] = "about";}
		foreach($categories as $value)
		{
			if($category == $value)
			{
				$section_index = $i;
				$chosen = " class='chosen'";
			} 
			else $chosen = "";
			echo "<li><a href='/$controller/$value' title='' $chosen>".ucfirst($value)."</a></li>\n\t\t";
			$i++;
		}	
		if(isset($_SESSION['admin']))
		{
			if($_SESSION['admin'] == "edit")$mode = "view";else $mode = "edit";
			echo "<a id='toggle_mode' href='?admin=$mode'>toggle mode</a>\n";
		}
		?>
		</nav>
		
	</section>
	
	<!-- Pages menu -->
	<section class='left'>
	
		<nav>
		<?php
		$i = 0;
		foreach($pages[$category] as $value)
		{
			if($page == $value['url']){$chosen = " class='chosen'";$view_index=$i;} else {$chosen = "";}
			echo "<a href='/$controller/$category/{$value['url']}' title='' $chosen>".$value['menu']."</a>\n\t\t";
			$i++;
		}	
		?></nav>
		
	</section>
	
	<!-- Page content --->
	<section class='middle'>

		<?php
		if(isset($_SESSION['admin']) && $_SESSION['admin'] == "edit"):?>
		
			<form name='edit_form' id="edit_form" method='post'>
				<div class="edit_details">
				<label>Autoid:<input name="autoid" title="autoid" value="<?php echo $edit_autoid; ?>" /></label>
				<label>Controller:<input name="controller" value="<?php echo $controller; ?>" /></label>
				<label>Category:<input name="category" value="<?php echo $category; ?>" /></label>
				<label>URL:<input name="url" value="<?php echo $page; ?>" /></label>
				<label>Menu:<input name="menu" value="<?php echo $edit_menu; ?>" /></label>
				<label>MetaDesc:<input name="meta_description" value='<?php echo $meta_description; ?>' /></label>
				<label>MetaKey:<input name="meta_keywords" value='<?php echo $meta_keywords; ?>' /></label>
				<button>Save</button>
				</div>
				<input name="title" value='<?php echo $title; ?>' placeholder="Title" />
				<textarea name="content"><?php echo htmlspecialchars($edit_content, ENT_COMPAT, "iso-8859-1"); ?></textarea>
				<button>Save</button>
			</form>
			
		<?php else: ?> 
		
			<h1><?php echo htmlentities($title);?></h1> 
			<?php echo $edit_content; ?>
			
		<?php endif;?>
		
		<div id='pagination'>
			<?php prev_page(); ?>
			<?php next_page(); ?>
		</div>
		
	</section>