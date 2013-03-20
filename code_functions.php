<?php
// Author: Pradip Vadher
function add_included_files(){
	if(isset($_GET['include_files']) && !empty($_GET['include_files'])){
		$include_files = explode(";", $_GET['include_files']);
		foreach ($include_files as $include_file) {
			ob_start();
			require_once $include_file;
			ob_end_clean();
		}
	}
}
function type($what, $class, $add_space = TRUE){
	echo "<span class=\"{$class}\">".str_replace("\n", "<br />", htmlentities($what))."</span>";
	if($add_space)
		echo " ";
}
function comma(){
	echo ", ";
}
function operator($operator, $add_space = TRUE){
	type($operator, "operator", $add_space);
}
function identifier($identifier, $add_dollar = FALSE, $add_space = TRUE){
	if($add_dollar)
		operator("$", FALSE);
	type($identifier, "identifier", $add_space);
	if($add_space)
		echo " ";
}
function value($value, $add_space = TRUE){
	echo "<span class=\"value\">";
	//ob_start();
	//var_dump($value);
	//echo trim(ob_get_clean());
	echo htmlentities(var_export($value, TRUE));
	echo "</span>";
}
function ini_value($value, $add_space = TRUE){
	echo "<span class=\"ini value\">";
	$value = "\"".str_replace("\"", "\"\"", $value)."\"";
	echo htmlentities($value);
	echo "</span>";
}
function classname($identifier){
	type($identifier, "identifier classname");
}
function keyword($keyword){
	type($keyword, "keyword");
}
function bracket($which, $add_space = TRUE){
	type($which, "bracket", $add_space);
}
function semicolon(){
	type(";", "semicolon", FALSE);
}
function comment($comment, $add = TRUE){
	if($add)
		type("/* {$comment} */", "comment");
	else
		type($comment, "comment");
}
function doc_comment($comment){
	type($comment, "doc comment");
}
function section($section, $add_space = TRUE){
	type($section, "section", $add_space);
}
function indent($value = 1){
	echo "<span class=\"indent\">";
	for($i = 0; $i < $value; $i++)
		for($j = 0; $j < 3; $j++)
			echo "&nbsp;";
	echo "</span>";
}
function start_line(){
	echo "";
}
function end_line(){
	echo "<br />";
}
function add_extra_line(){
	start_line();
	end_line();
}
function display_property($property, $indent = 1){
	$doc_comment = $property->getDocComment();
	if($doc_comment !== FALSE){
		start_line();
		doc_comment($doc_comment);
		end_line();
	}
	echo "<span class=\"property";
	if($property->isStatic()){
		echo " static";
		try {
			$value = $property->getValue();	
		}catch(ReflectionException $e){
			echo " inaccessible";
		}
	} else {
		echo " non-static";
		if(!$property->isPublic())
			echo " inaccessible";
	}
	echo "\">";
	start_line();
	indent($indent);
	$value = NULL;
	if($property->isPrivate()) keyword("private");
	if($property->isProtected()) keyword("protected");
	if($property->isPublic()) keyword("public");
	if($property->isStatic()) keyword("static");
	identifier($property->getName(), TRUE);
	if(!is_null($value)){
		operator("=");
		value($value);
	}
	semicolon();
	end_line();
	echo "</span>";
}
function display_function($method, $indent = 1){
	$doc_comment = $method->getDocComment();
	if($doc_comment !== FALSE){
		start_line();
		doc_comment($doc_comment);
		end_line();
	}
	echo "<span class=\"";
	if($method instanceof ReflectionMethod){
		echo "method";
		if($method->isStatic()){
			echo " static";
		} else {
			echo " non-static";
		}
		if(!$method->isPublic())
			echo " inaccessible";
	} else if($method instanceof ReflectionFunction){
		echo "function";
		if($method->isDisabled()){
			echo " inaccessible";
		}
	}
	if($method->isDeprecated()){
		echo " deprecated";
	}
	echo "\">";
	start_line();
	indent($indent);
	$abstract = FALSE;
	if($method instanceof ReflectionMethod){
		if($method->isAbstract()) {
			keyword("abstract");
			$abstract = TRUE;
		}
		if($method->isPrivate()) keyword("private");
		if($method->isProtected()) keyword("protected");
		if($method->isPublic()) keyword("public");
		if($method->isStatic()) keyword("static");
	}
	keyword("function");
	if($method->returnsReference()){
		operator("&", FALSE);
	}
	echo "<a name=\"".$method->getName()."\" id=\"".$method->getName()."\">";
	identifier($method->getName(), FALSE, FALSE);
	echo "</a>";

	$parameters = $method->getParameters();
	$count = count($parameters);
	if($count == 0){
		bracket("(", FALSE);
		bracket(")");
	} else {
		bracket("(", FALSE);
	}
	$required_arguments = $method->getNumberOfRequiredParameters();
	for($i = 0; $i < $count; $i++){
		$parameter = $parameters[$i];
		if($parameter->isPassedByReference()){
			operator("&", FALSE);
		}
		$is_optional = $parameter->isDefaultValueAvailable() || $i >= $required_arguments;
		identifier($parameter->getName(), TRUE, $is_optional);
		if($is_optional){
			operator("=");
			try{
				value($parameter->getDefaultValue(), FALSE);
			}catch(ReflectionException $e){
				if(strpos($e->getMessage(), "Cannot determine default value for internal functions") !== FALSE){
					value("<internal-value>", FALSE);
				} else {
					value("<?>", FALSE);
				}
			}
		}
		if($i !== $count - 1)
			comma();
	}
	if($count !== 0){
		bracket(")", !$abstract);
	}
	if($abstract) {
		semicolon();
	} else {
		if($method instanceof ReflectionMethod && ($method->isConstructor() || $method->isDestructor())){
			bracket("{", FALSE);
			end_line();
			start_line();
			indent($indent + 1);
			if($method->isConstructor()){
				comment("Constructor Implementation");
			} else if($method->isDestructor()){
				comment("Destructor Implementation");
			}
			end_line();
			start_line();
			indent($indent);
		} else {
			bracket("{", FALSE);
		}
		bracket("}", FALSE);
	}

	end_line();
	echo "</span>";
}
function display_ini_entries($ini_entries){
	if(!empty($ini_entries)){
		echo "<div class=\"code\">";
		$last_section = FALSE;
		foreach ($ini_entries as $key => $value) {
			start_line();
			echo "<a name=\"".$key."\" id=\"".$key."\">";
			$dot = strpos($key, ".");
			if($dot !== FALSE){
				$section = substr($key, 0, $dot);
				$key = substr($key, $dot + 1);
				if($last_section !== $section){
					add_extra_line();
					$last_section = $section;
				}
				section($section, FALSE);
				operator(".", FALSE);
			} else {
				if($last_section !== FALSE){
					add_extra_line();
					$last_section = FALSE;
				}
			}
			identifier($key);
			operator("=");
			echo ini_value($value, FALSE);
			echo "</a>";
			end_line();
		}
		echo "</div>";
	}
}
function display_variables($variables){
	if(!empty($variables)){
		echo "<div class=\"code\">";
		foreach ($variables as $key => $value) {
			start_line();
			echo "<a name=\"".$key."\" id=\"".$key."\">";
			identifier($key, TRUE);
			echo "</a>";
			operator("=");
			value($value, FALSE);
			semicolon();
			end_line();
		}
		echo "</div>";
	}
}
function display_constants($constants){
	if(!empty($constants)){
		echo "<div class=\"code\">";
		foreach ($constants as $key => $value) {
			start_line();
			identifier("define", FALSE, FALSE);
			bracket("(", FALSE);
			echo "<a name=\"".$key."\" id=\"".$key."\">";
			value($key, FALSE);
			echo "</a>";
			comma();
			value($value, FALSE);
			bracket(")", FALSE);
			semicolon();
			end_line();
		}
		echo "</div>";
	}
}
function display_functions($functions_with_group){
	foreach($functions_with_group as $group => $functions){
		if(!empty($functions)){
			echo "<h4>".htmlentities($group)."</h4>";
			echo "<div class=\"code\">";
			asort($functions);
			foreach($functions as $function_name){
				$function = new ReflectionFunction($function_name);
				display_function($function, 0);
			}
			echo "</div>";
		}
	}
}
function display_class($class){
	echo "<div class=\"code\">";
		// class doc
		$doc_comment = $class->getDocComment();
		if($doc_comment !== FALSE){
			start_line();
			doc_comment($doc_comment);
			end_line();
		}

		// class declaration
		start_line();
		if($class->isFinal()){
			keyword("final");
		}
		if($class->isAbstract()){
			keyword("abstract");
		}
		keyword($class->isInterface() ? "interface" : "class");
		classname($class->getName());
		$parent_class = $class->getParentClass();
		if($parent_class !== FALSE){
			// class inherited from class
			keyword("extends");
			identifier($parent_class->getName());
		}
		$parent_interfaces = $class->getInterfaceNames();
		if(!empty($parent_interfaces)){
			// class inherited from interfaces
			keyword("implements");
			$count = count($parent_interfaces);
			for($i = 0; $i < $count; $i++) {
				identifier($parent_interfaces[$i], FALSE, $i === $count - 1);
				if($i != $count - 1)
					comma();
			}
		}
		bracket("{", FALSE);
		end_line();


			// constants
			$constants = $class->getConstants();
			if(!empty($constants)){
				add_extra_line();
				start_line();
				indent();
				comment("Constants");
				end_line();
				foreach ($constants as $key => $value) {
					start_line();
					indent();
					keyword("const");
					identifier($key);
					operator("=");
					value($value);
					semicolon();
					end_line();
				}
			}

			// properties stuff
			$properties = $class->getProperties();
			if(!empty($properties)){
				add_extra_line();
				start_line();
				indent();
				comment("Properties");
				end_line();
				foreach($properties as $property){
					display_property($property);
				}
			}

			// methods
			$methods = $class->getMethods();
			if(!empty($methods)){
				add_extra_line();
				start_line();
				indent();
				comment("Methods");
				end_line();
				foreach($methods as $method){
					display_function($method);
				}
			}

			add_extra_line();

		// class end
		start_line();
		bracket("}");
		end_line();
	echo "</div>";
}