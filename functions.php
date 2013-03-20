<!-- Author: Pradip Vadher. -->
<html>
	<head>
		<link rel="stylesheet" href="style.css" type="text/css" />
		<script src="highlight_hash.js"></script>
		<script src="visibility.js"></script>
	</head>
	<body>
		Show: 
		<input id="show_comments" type="checkbox" checked="checked" onchange="change_visibility('comment', this.checked)" />
		<label for="show_comments">Comments</label>
		
		<input id="show_deprecated" type="checkbox" checked="checked" onchange="change_visibility('deprecated', this.checked)" />
		<label for="show_deprecated">Deprecated Functions</label>
		<h2>Functions</h2>
	<?php
		if(isset($_GET['extension'])){
			$functions = 0;
			if($_GET['extension'] === ""){
				$functions = get_defined_functions();
				if(isset($_GET['include_files']) && !empty($_GET['include_files'])){
					$include_files = explode(";", $_GET['include_files']);
					foreach ($include_files as $include_file) {
						ob_start();
						require_once $include_file;
						ob_end_clean();
					}
					$new_functions = get_defined_functions();
					foreach ($functions as $key => $value) {
						$functions[$key] = array_diff($new_functions[$key], $value);
					}
					echo "<h3>Included</h3>";
				} else {
					echo "<h3>Everything</h3>";
				}
			} else {
				$reflection_extension = new ReflectionExtension($_GET['extension']);
				$reflected_functions = $reflection_extension->getFunctions();
				$functions = array();
				foreach($reflected_functions as $function){
					$functions["declared"][] = $function->getName();
				}
				echo "<h3>Extension: ".$reflection_extension->getName()."</h3>";
			}
			require_once "code_functions.php";
			display_functions($functions);
		}
	?>
	</body>
</html>