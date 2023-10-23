function soundBadQuestion(){
	var sonido = document.createElement("iframe");
	sonido.setAttribute("src","./music/error_sound.mp3");
	document.body.appendChild(sonido);
}
function soundSuccessQuuestion(){
	var sonido = document.createElement("iframe");
	sonido.setAttribute("src","./music/success_sound.mp3");
	document.body.appendChild(sonido);
}
function soundHelp(){
	var sonido = document.createElement("iframe");
	sonido.setAttribute("src","./music/help_sound.mp3");
	document.body.appendChild(sonido);
}   
function soundWin(){
	var sonido = document.createElement("iframe");
	sonido.setAttribute("src","./music/win_sound.mp3");
	document.body.appendChild(sonido);
}   
function soundLose(){
	var sonido = document.createElement("iframe");
	sonido.setAttribute("src","./music/lose_sound.mp3");
	document.body.appendChild(sonido);
}