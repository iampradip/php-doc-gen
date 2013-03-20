// Author: Pradip Vadher.
var change_visibility = function (className, value){
	var elements = document.getElementsByClassName(className);
	for(var i = 0; i < elements.length; i++){
		elements[i].style.display = (value ? "inline" : "none");
		if(elements[i].previousSibling && elements[i].previousSibling.className && elements[i].previousSibling.className == "indent"){
			elements[i].previousSibling.style.display = elements[i].style.display;
			elements[i] = elements[i].previousSibling;
		}
		if(elements[i].previousSibling && elements[i].previousSibling.nodeName && elements[i].previousSibling.nodeName.toUpperCase() == "BR"){
			elements[i].previousSibling.style.display = elements[i].style.display;
		}
	}
};