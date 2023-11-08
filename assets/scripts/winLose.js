function playSound(soundFile) {
    var audio = new Audio(soundFile);
    audio.play();
}

function soundLoseQuestion() {
    playSound('../../assets/music/lose_sound.mp3');
}

function soundBicho() {
    playSound('../../assets/music/bicho.mp3');
}

function soundWinQuestion() {
    playSound('../../assets/music/win_sound.mp3');
}

function yesPublish() {
    document.getElementById("nameAndPublishDiv").style.display = "block";
    document.getElementById("publishQuestion").style.display = "none";
    document.getElementById("publishScoreQuestion").style.display = "none";
}

function noPublish() {
    document.getElementById("nameAndPublishDiv").style.display = "none";
    document.getElementById("publishQuestion").style.display = "none";
}

document.getElementById("yesPublish").addEventListener("click", yesPublish);
document.getElementById("noPublish").addEventListener("click", noPublish);


function publish() {
    document.getElementById("publishConfirmedMessage").style.display = "block";
}

document.getElementById("publishButton").addEventListener("click", publish);


const questionsOK = document.getElementById("preguntasAcertadas").getAttribute("preguntasAcertadas");
console.log(questionsOK);


document.getElementById("preguntasAcertadas").innerHTML = questionsOK;