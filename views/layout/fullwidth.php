<div class='container'>
<div class='padding'>
	<section class='fullwidth'>
		<?php 
		foreach($pages as $key=>$value){
			echo "<article id='anchor_$key'>";
			include MODELS."$controller/$key.php";
			echo "</article>";
		}
		?>
	</section>	
</div>
</div>