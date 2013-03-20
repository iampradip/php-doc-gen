<!-- Author: Pradip Vadher. -->
<html>
	<head>
		<link rel="stylesheet" href="style.css" type="text/css" />
		<script src="highlight_hash.js"></script>
	</head>
	<body>
		<h2>INI Entries</h2>
	<?php
		if(isset($_GET['extension'])){
			$ini_entries = 0;
			if($_GET['extension'] === ""){
				$ini_entries = ini_get_all(NULL, FALSE);
				if(isset($_GET['include_files']) && !empty($_GET['include_files'])){
					$include_files = explode(";", $_GET['include_files']);
					foreach ($include_files as $include_file) {
						ob_start();
						require_once $include_file;
						ob_end_clean();
					}
					$ini_entries = array_diff_assoc(ini_get_all(NULL, FALSE), $ini_entries);
					echo "<h3>Included</h3>";
				} else {
					echo "<h3>Everything</h3>";
				}
			} else {
				$reflection_extension = new ReflectionExtension($_GET['extension']);
				$ini_entries = $reflection_extension->getINIEntries();
				echo "<h3>Extension: ".$reflection_extension->getName()."</h3>";
			}
			ksort($ini_entries);
			require_once "code_functions.php";
			display_ini_entries($ini_entries);
		}
	?>
	</body>
</html>