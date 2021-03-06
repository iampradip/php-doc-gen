<!-- Author: Pradip Vadher --><html>
	<head>
		<link rel="stylesheet" href="style.css" type="text/css" />
	</head>
	<body>
		<h2>PHP</h2>
		<a class="extension" target="extension" href="extension.php?extension=">Everything</a>
		<form class="extension_add" target="extension" action="extension.php">
			<input type="text" name="include_files" value="file1;file2;file3" />
			<input type="submit" value="Include Files" />
		</form>
		<?php if(function_exists("dl")) { ?>
			<form class="extension_add" target="extension" action="extension.php">
				<input type="text" name="dl" value="extension.dll" />
				<input type="text" name="extension" value="name" />
				<input type="submit" value="Load Extension" />
			</form>
		<?php } else { ?>
			<small>Cannot load extensions dynamically.</small>
		<?php } ?>
		<h3>Extensions</h3>
		<?php
			$extensions = get_loaded_extensions();
			natcasesort($extensions);
			foreach($extensions as $extension){
				?>
					<a class="extension" target="extension" href="extension.php?extension=<?php echo urlencode($extension); ?>"><?php echo htmlentities($extension); ?></a><br />
				<?php 
			}
		?>
	</body>
</html>