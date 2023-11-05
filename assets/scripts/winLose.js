function playSound(soundFile) {
    var audio = new Audio(soundFile);
    audio.play();
}

function soundLoseQuestion() {
    playSound('../music/lose_sound.mp3');
}

function soundBicho() {
    playSound('../music/bicho.mp3');
}

function soundWinQuestion() {
    playSound('../music/win_sound.mp3');
}