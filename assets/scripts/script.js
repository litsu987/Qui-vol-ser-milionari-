function yesPublish() {
    document.getElementById("nameAndPublishDiv").style.display = "block";
    document.getElementById("publishQuestion").style.display = "none";
}

function noPublish() {
    document.getElementById("nameAndPublishDiv").style.display = "none";
    document.getElementById("publishQuestion").style.display = "none";
}

document.getElementById("yesPublish").addEventListener("click", yesPublish);
document.getElementById("noPublish").addEventListener("click", noPublish);
