</div>
<footer>
	<a class='back_to_top' onClick='document.body.scrollTop = document.body.scrollTop = "0";'>Back to top</a>
</footer>
</div>

<div id="section_nav_menu">
	<div id="section_nav_menu_back" onclick="display_section_menu();">< Back</div>
		<div id="accordion-resizer">
			<div id="accordion">
			<?php
				foreach($categories as $value)
				{
					if($category == $value){$chosen = " class='chosen'";} else {$chosen = "";}
					echo "<h3>".ucfirst($value)."</h3>\n\t";
					echo "<div>\n\t";
					
					foreach($pages[$value] as $k => $v)
					{
						if($page == $v['url']){$chosen = " class='chosen'";} else {$chosen = "";}
						echo "<li><a href='/$controller/$value/{$v['url']}' title='' $chosen>{$v['menu']}</a></li>\n\t";
					}	
					echo "</div>\n\t";
				}	
			?></div>
		</div>
	</div>
	
	<script>
	var active_section = <?php echo $section_index; ?>;
</script>
</div>
</body>
</html>