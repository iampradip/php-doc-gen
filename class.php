<!-- Author: Pradip Vadher --><html>
	<head>
		<link rel="stylesheet" href="style.css" type="text/css" />
		<script src="highlight_hash.js"></script>
		<script src="visibility.js"></script>
	</head>
	<body>
		Show: 
		<input id="show_comments" type="checkbox" checked="checked" onchange="change_visibility('comment', this.checked)" />
		<label for="show_comments">Comments</label>
		
		<input id="show_inaccessible" type="checkbox" checked="checked" onchange="change_visibility('inaccessible', this.checked)" />
		<label for="show_inaccessible">Inaccessible Methods and Properties</label>
	<?php
		require "code_functions.php";
		if(isset($_GET['extension'], $_GET['class'])){
			if(isset($_GET['dl'])){
				dl($_GET['dl']);
			}
			add_included_files();
			if($_GET['extension'] === ""){
				$class = new ReflectionClass($_GET['class']);
				echo "<h2>".(($class->isInterface() ? "Interface" : "Class").": ".$class->getName())."</h2>";
				display_class($class);
				
			} else {
				$reflection_extension = new ReflectionExtension($_GET['extension']);
				$reflection_classes = $reflection_extension->getClasses();
				foreach ($reflection_classes as $reflection_class) {
					if($reflection_class->getName() === $_GET['class']){
						echo "<h2>".(($reflection_class->isInterface() ? "Interface" : "Class").": ".$reflection_class->getName())."</h2>";
						echo "<h3>Extension: ".$reflection_extension->getName()."</h3>";
						display_class($reflection_class);
					}
				}
			}
		}
	?>
	</body>
</html>