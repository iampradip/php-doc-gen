<!-- Author: Pradip Vadher. -->
<html>
	<head>
		<link rel="stylesheet" href="style.css" type="text/css" />
		<script src="highlight_hash.js"></script>
	</head>
	<body>
		<h2>Variables</h2>
	<?php
		if(isset($_GET['extension'])){
			$variables = 0;
			if($_GET['extension'] === ""){
				$variables = get_defined_vars();
				if(isset($_GET['include_files']) && !empty($_GET['include_files'])){
					$include_files = explode(";", $_GET['include_files']);
					foreach ($include_files as $include_file) {
						ob_start();
						require_once $include_file;
						ob_end_clean();
					}
					unset($include_files);
					unset($include_file);
					$variables = array_diff_assoc(get_defined_vars(), $variables);
					unset($variables['variables']);
					echo "<h3>Included</h3>";
				} else {
					
					echo "<h3>Everything</h3>";
				}
				ksort($variables);
				require_once "code_functions.php";
				display_variables($variables);
			}
		}
	?>
	</body>
</html>