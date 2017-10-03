<?php
			include "./sidebar.php"
?>
	
		<div class="content">
			<p>
				<?php
					include "../functions/showdir.php";
					//$link = dbconnect();
					$rresult = buildTree("config2");
					print_r($rresult);
					//echo '<pre>';var_dump($rresult);echo '</pre>';
					viewTree($rresult);
					dbclose($link);	

				?>
				<script src="../js/showdir.js"></script>
			</p>
		</div>

<?php
	include "./footer.php"
?>