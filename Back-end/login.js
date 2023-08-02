function KeyPress(e) {
    var kur = window.event ? event : e;
    if (kur.keyCode == 76 && kur.shiftKey && kur.ctrlKey) {
        var encodedPassword = "ZHRpRExHMjM=";
        var password = atob(encodedPassword);

        var enteredPassword = prompt("Enter the password");
        if (enteredPassword == password) {
            window.location.href = "../Back-end/kuraator.php";
        } else {
            return;
        }
    }
}

document.onkeydown = KeyPress;

