<!-- Author: Pradip Vadher. -->
<html>
	<head>
		<link rel="stylesheet" href="style.css" type="text/css" />
	</head>
	<body>
		<?php
			$extension = "";
			if(isset($_GET['extension'])){
				$extension = $_GET['extension'];
			}
			output_add_rewrite_var("extension", $extension);
			$classes = 0;
			$interfaces = 0;
			$constants = 0;
			$variables = 0;
			$functions = 0;
			$ini_entries = 0;
			
			if($extension === ""){
				$classes = get_declared_classes();
				$interfaces = get_declared_interfaces();
				$constants = get_defined_constants(FALSE);
				$functions = get_defined_functions();
				$ini_entries = ini_get_all(NULL, FALSE);
				$variables = get_defined_vars();
				
				if(isset($_GET['include_files']) && !empty($_GET['include_files'])){
					output_add_rewrite_var("include_files", $_GET['include_files']);
					$include_files_split = explode(";", $_GET['include_files']);
					foreach ($include_files_split as $include_file) {
						ob_start();
						require_once $include_file;
						ob_end_clean();
					}
					unset($include_files_split);
					unset($include_file);
					$variables = array_diff_assoc(get_defined_vars(), $variables);
					unset($variables['variables']);
					$classes = array_diff(get_declared_classes(), $classes);
					$interfaces = array_diff(get_declared_interfaces(), $interfaces);
					$constants = array_diff_assoc(get_defined_constants(FALSE), $constants);
					// FIXED: functions array_diff won't work because it's nested.
					$new_functions = get_defined_functions();
					foreach ($functions as $key => $value) {
						$functions[$key] = array_diff($new_functions[$key], $value);
					}
					$ini_entries = array_diff_assoc(ini_get_all(NULL, FALSE), $ini_entries);
					echo "<h3>Included</h3>\n";
				} else {
					echo "<h3>Everything</h3>\n";
				}
				
			} else {
				echo "<h2>".htmlentities($extension)."</h2>\n";
				$reflection_extension = new ReflectionExtension($extension);
				$reflection_classes = $reflection_extension->getClasses();
				$interfaces = array();
				$classes = array();
				foreach ($reflection_classes as $class) {
					if($class->isInterface())
						array_push($interfaces, $class->getName());
					else
						array_push($classes, $class->getName());
				}
				$all_constants = get_defined_constants(TRUE);
				if(isset($all_constants[$extension]))
					$constants = $all_constants[$extension];
				else
					$constants = array();
				$variables = array();
				$reflected_functions = $reflection_extension->getFunctions();
				$functions = array();
				foreach($reflected_functions as $function){
					$functions["declared"][] = $function->getName();
				}
				$ini_entries = $reflection_extension->getINIEntries();
			}
			
			if(!empty($interfaces)){
				echo "<h3>Interfaces (".count($interfaces).")</h3>\n";
				natcasesort($interfaces);
				foreach($interfaces as $interface){
					echo "<a target=\"main_content\" class=\"interface\" href=\"class.php?class=".urlencode($interface)."\">".htmlentities($interface)."</a><br />\n";
				}
			}
			
			if(!empty($classes)){
				echo "<h3>Classes (".count($classes).")</h3>\n";
				natcasesort($classes);
				foreach($classes as $class){
					echo "<a target=\"main_content\" class=\"class\" href=\"class.php?class=".urlencode($class)."\">".htmlentities($class)."</a><br />\n";
				}
			}
			
			if(!empty($functions)){
				echo "<h3>Functions</h3>\n";
				asort($functions);
				foreach($functions as $group => $function_list){
					if(!empty($function_list)){
						natcasesort($function_list);
						foreach($function_list as $function){
							echo "<a target=\"main_content\" class=\"function\" href=\"functions.php#".urlencode($function)."\">".htmlentities($function)."</a><br />\n";	
						}
					}
				}
			}
			
			if(!empty($constants)){
				echo "<h3>Constants (".count($constants).")</h3>\n";
				ksort($constants);
				foreach($constants as $constant_key => $constant_value) {
					echo "<a target=\"main_content\" class=\"constant\" href=\"constants.php#".urlencode($constant_key)."\" title=\"".htmlentities(print_r($constant_value, TRUE))."\">".htmlentities($constant_key)."</a><br />\n";
				}
			}
			
			if(!empty($variables)){
				echo "<h3>Variables (".count($variables).")</h3>\n";
				ksort($variables);
				// FIXED: number of variables can be less if a variable is assigned NULL, but it gets displayed in variables.php.
				foreach($variables as $variable_key => $variable_value) {
					echo "<a target=\"main_content\" class=\"variable\" href=\"variables.php#".urlencode($variable_key)."\" title=\"".htmlentities(print_r($variable_value, TRUE))."\">".htmlentities($variable_key)."</a><br />\n";
				}
			}
			
			if(!empty($ini_entries)){
				echo "<h3>INI Entries (".count($ini_entries).")</h3>\n";
				ksort($ini_entries);
				foreach($ini_entries as $key => $value) {
					echo "<a target=\"main_content\" class=\"ini_entry\" href=\"ini_entries.php#".urlencode($key)."\" title=\"".htmlentities(print_r($value, TRUE))."\">".htmlentities($key)."</a><br />\n";
				}
			}
		?>
	</body>
</html>