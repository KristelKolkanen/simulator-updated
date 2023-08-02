var timerInterval;
var initialDuration = 20 * 60;

if (localStorage.getItem('timerValue')) {
  var timerValue = localStorage.getItem('timerValue');
  startTimer(timerValue);
} else {
  startTimer(20 * 60);
}

function startTimer(duration) {
  var timerDisplay = document.getElementById('timer');
  var timer = duration;
  var minutes, seconds;

  timerInterval = setInterval(function () {
    minutes = parseInt(timer / 60, 10);
    seconds = parseInt(timer % 60, 10);

    minutes = minutes < 10 ? "0" + minutes : minutes;
    seconds = seconds < 10 ? "0" + seconds : seconds;

    timerDisplay.textContent = minutes + ":" + seconds;

    localStorage.setItem('timerValue', timer);

    if (--timer < 0) {
      clearInterval(timerInterval);
      localStorage.removeItem('timerValue');
      saveTimePassedToSession();
      window.location.href = "results.php";
    }
  }, 1000);
}

function resetTimer() {
  clearInterval(timerInterval);
  localStorage.removeItem('timerValue');
  startTimer(20 * 60);
}

function calculateTimePassed() {
  var timerValue = localStorage.getItem('timerValue');
  var elapsedTime = initialDuration - parseInt(timerValue, 10);
  return elapsedTime;
}

function saveTimePassedToSession() {
  var timePassed = calculateTimePassed();
  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'save_time_passed.php');
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.onload = function() {
    if (xhr.status === 200) {
      console.log("Time passed saved to session successfully.");
    } else {
      console.log("Failed to save time passed to session.");
    }
  };
  xhr.send('timePassed=' + timePassed);
}