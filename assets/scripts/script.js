function yesPublish() {
    document.getElementById("nameAndPublishDiv").style.display = "block";
    document.getElementById("publishQuestion").style.display = "none";
}

function noPublish() {
    document.getElementById("nameAndPublishDiv").style.display = "none";
    document.getElementById("publishQuestion").style.display = "none";
}

function publish() {
    document.getElementById("name")
}

document.getElementById("yesPublish").addEventListener("click", yesPublish);
document.getElementById("noPublish").addEventListener("click", noPublish);


// Función para establecer la fecha actual en el campo de fecha actual
function setCurrentDate() {
    var currentDateInput = document.getElementById("currentDate");
    var currentDate = new Date();
    currentDateInput.value = currentDate.toISOString();
}


document.addEventListener("load", setCurrentDate);
