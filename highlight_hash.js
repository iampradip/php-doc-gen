// Author: Pradip Vadher
var last_hash = "";
setInterval(function (){
	var hash = location.hash;
	if(hash == last_hash)
		return;
	last_hash = hash;
	
	if(hash.length <= 1){
		return;
	}
	
	// FIXME: will not work with functions with namespaces as name includes \ character
	var element = document.getElementById(hash.substr(1));
	if(!element)
		return;
	
	var highlight_level = 100;
	var interval_for_animation = setInterval(function (){
		highlight_level--;
		if(highlight_level <= 0){
			element.style.backgroundColor = "transparent";
			clearInterval(interval_for_animation);
		} else {
			var color = Math.floor(255 - (highlight_level * 128 / 100));
			element.style.backgroundColor = "rgb(255, " + color + ", " + color + ")";
		}
	}, 15);
}, 100);