<!-- Author: Pradip Vadher --><html>
	<head>
		<link rel="stylesheet" href="style.css" type="text/css" />
		<script src="highlight_hash.js"></script>
	</head>
	<body>
		<h2>Constants</h2>
	<?php
		if(isset($_GET['extension'])){
			if(isset($_GET['dl'])){
				dl($_GET['dl']);
			}
			$constants = 0;
			if($_GET['extension'] === ""){
				$constants = get_defined_constants(FALSE);
				if(isset($_GET['include_files']) && !empty($_GET['include_files'])){
					$include_files = explode(";", $_GET['include_files']);
					foreach ($include_files as $include_file) {
						ob_start();
						require_once $include_file;
						ob_end_clean();
					}
					$constants = array_diff_assoc(get_defined_constants(FALSE), $constants);
					echo "<h3>Included</h3>";
				} else {
					echo "<h3>Everything</h3>";
				}
			} else {
				$extension = $_GET['extension'];
				$all_constants = get_defined_constants(TRUE);
				if(isset($all_constants[$extension]))
					$constants = $all_constants[$extension];
				else
					$constants = array();
				echo "<h3>Extension: ".htmlentities($extension)."</h3>";
			}
			ksort($constants);
			require_once "code_functions.php";
			display_constants($constants);
		}
	?>
	</body>
</html>