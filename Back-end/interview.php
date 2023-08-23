<?php
session_start();
require_once "../config_dlg.php";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$start_time = time();
$_SESSION['start_time'] = $start_time;

$new_form_id = $_SESSION['form_id'] ?? "";
$form_name = $_SESSION['form_name'] ?? "";
$form_age = $_SESSION['form_age'] ?? "";
$form_country = $_SESSION['form_country'] ?? "";
$form_education = $_SESSION['form_education'] ?? "";
$form_work = $_SESSION['form_work'] ?? "";
$form_hobby = $_SESSION['form_hobby'] ?? "";
$form_kids = $_SESSION['form_kids'] ?? "";

if (isset($_GET['form_id'])) {
    $form_id = $_GET['form_id'];
} else {
    $form_id = 0; // Default form_id
}

function getQuestion($questionId){
    global $conn;
    $sql = "SELECT `question_text` FROM `Question` WHERE `question_id` = $questionId";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row["question_text"];
    } else {
        return "Question not found.";
    }
}

function getAnswers($questionId){
    global $conn;
    $answers_list = [];
    $stmt = $conn->prepare("SELECT answer_text, next_question_id, answer_score , answer_end FROM Answer WHERE Question_question_id = ?");
    echo $conn->error;
    $stmt->bind_param("i", $questionId);
    $stmt->bind_result($answer_text_from_db, $next_question_id_from_db, $answer_score_from_db, $answer_end_from_db);
    $stmt->execute();
    echo $stmt->error;
    while($stmt->fetch()){
         $answers_list[] = [
                "answer_text" => $answer_text_from_db,
                "next_question_id" => $next_question_id_from_db,
                "answer_score" => $answer_score_from_db,
                "answer_end" => $answer_end_from_db
            ];
    }
    $stmt->close();
	
    return $answers_list;
}

// Check if a question ID is provided in the URL
if (isset($_POST['questionId'])) {
    $questionId = $_POST['questionId'];
} elseif(isset($_SESSION['form_name']) && $_SESSION['form_name'] !== ''){
    $questionId = 1;
} elseif(isset($_SESSION['form_name']) && $_SESSION['form_name'] === ''){
    $questionId = 101;
}
else {
    $questionId = 101; // Default starting question ID
}

$question = getQuestion($questionId);
$answers = getAnswers($questionId);

function roundScore(&$score) {
    if ($score > 100) {
        $score = 100;
    }
    if ($score < 0) {
        $score = 0;
    }
    $finalScore = $score;
}

// Retrieve the points from the URL parameter
if (isset($_POST['points'])) {
    $points = $_POST['points'];
    //echo $_POST['points'];
} else {
    $points = 80; // Default starting points
}

roundScore($points);

//Insert data into User_Result table if answer_end is 1
if (isset($_SESSION['form_id']) && isset($_POST['points']) && $answers[0]['answer_end'] == 1) {
    $form_id = $_SESSION['form_id'];
    $points = $_POST['points'];
    $sql = "INSERT INTO `User_Result` (`Form_form_id`, `result_score`) VALUES ('$form_id', '$points')";
    if ($conn->query($sql) === TRUE) {
        echo "";
    } else {
        echo "Error inserting data: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../Front-end/kysimus.css">
	  <!-- <script src="timer.js" defer></script> -->
</head>
<body>
    <div class="pagecontainer">
<!--         <div class="container1">
            <div class="time" id="timer"></div>
        </div> -->
        <img src="../pics/unmute_50.png" alt="mute" class="mute" id="mute" onclick="toggleMute()">
        <div class="container2">
            <div class="kysimused">
                <h2 id="question_text"><?php echo $question; ?></h2>
            </div>
        </div>
        <div class="figures">
    <?php
    $figure_1 = "../pics/ms_neutral1.0.png";
    $figure_2 = "../pics/pc_neutral1.0.png";

    // Determine the figure based on the change in points
    if (empty($_SESSION['previous_points'])){
        $figure_1 = "../pics/ms_neutral1.0.png";
        $figure_2 = "../pics/pc_neutral1.0.png";
        $_SESSION['previous_points'] = 80;
    }
    elseif ($points > $_SESSION['previous_points']) {
        $figure_1 = "../pics/ms_happy1.0.png";
        $figure_2 = "../pics/pc_happy1.0.png";
    } elseif ($points < $_SESSION['previous_points']) {
        $figure_1 = "../pics/ms_negative1.0.png";
        $figure_2 = "../pics/pc_negative1.0.png";
    }
    $_SESSION['previous_points'] = $points; // Store the current points for future comparison
    ?>

    <img src="<?php echo $figure_1; ?>" alt="figures">
    <img src="<?php echo $figure_2; ?>" alt="figures">
</div>
    <div class="table">
        <div class="button-group">
            <?php foreach ($answers as $answer): ?>
                <?php
                    $next_question_id = $answer['next_question_id'];
                    $answer_score = $answer['answer_score'];
                    $next_points = $points + $answer_score; // Increase points by answer score
                    $answer_text = $answer['answer_text'];
                    roundScore($next_points);

                    if ($_SESSION['form_name'] !== '') {
                        $answer_text = str_replace('[name]', $_SESSION['form_name'], $answer_text);
                    }
                    if ($_SESSION['form_age'] !== '') {
                        $answer_text = str_replace('[age]', $_SESSION['form_age'], $answer_text);
                    }
                    if ($_SESSION['form_country'] !== '') {
                        $answer_text = str_replace('[country]', $_SESSION['form_country'], $answer_text);
                    }
                    if ($_SESSION['form_education'] !== '') {
                        $answer_text = str_replace('[education]', $_SESSION['form_education'], $answer_text);
                    }
                    if ($_SESSION['form_work'] !== '') {
                        $answer_text = str_replace('[work]', $_SESSION['form_work'], $answer_text);
                    }
                    if ($_SESSION['form_hobby'] !== '') {
                        $answer_text = str_replace('[hobby]', $_SESSION['form_hobby'], $answer_text);
                    }
                    if ($_SESSION['form_kids'] !== '') {
                        $answer_text = str_replace('[X]', $_SESSION['form_kids'], $answer_text);
                    }
                    if ($_SESSION['form_kids'] > 1) {
                        $answer_text = str_replace('[s]', 's', $answer_text);
                    }
                    if ($_SESSION['form_kids'] == 1) {
                        $answer_text = str_replace('[s]', '', $answer_text);
                    }
                    $_SESSION['previous_points'] = $points;
                ?>

                <?php if ($answer['answer_end'] == 1 && !strpos($answer_text, '[name]') && !strpos($answer_text, '[age]') && !strpos($answer_text, '[country]') && !strpos($answer_text, '[education]') && !strpos($answer_text, '[work]') && !strpos($answer_text, '[hobby]') && !strpos($answer_text, '[X]')): ?>
                    <form action="results.php" method="POST">
                        <input type="hidden" name="points" value="<?php echo $next_points; ?>">
                        <button class="answer-button" type="submit"><?php echo $answer_text; ?></button>
                    </form>
                <?php elseif (!strpos($answer_text, '[name]') && !strpos($answer_text, '[age]') && !strpos($answer_text, '[country]') && !strpos($answer_text, '[education]') && !strpos($answer_text, '[work]') && !strpos($answer_text, '[hobby]') && !strpos($answer_text, '[X]')): ?>
                    <form action="interview.php" method="POST">
                        <input type="hidden" name="questionId" value="<?php echo $next_question_id; ?>">
                        <input type="hidden" name="points" value="<?php echo $next_points; ?>">
                        <button class="answer-button" type="submit"><?php echo $answer_text; ?></button>
                    </form>
                <?php $end_time = time();
                    $_SESSION['end_time'] = $end_time;?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</body>
	<?php
        /* $start_time = $_SESSION['start_time'] ?? 0; // Retrieve the start time from session*/
        $end_time = $_SESSION['end_time'] ?? 0;
         // Retrieve the end time from session 

        if ($start_time && $end_time) {
            $time_diff = $end_time - $start_time;
            $time_diff = $time_diff * (-1);
            // Calculate the difference in minutes
            $_SESSION['time_diff'] = $time_diff;

            $time_diff_minutes = floor($time_diff / 60);
            $_SESSION['time_diff_minutes'] = $time_diff_minutes;

            //echo "Time difference in seconds: " . $time_diff . " seconds<br>";
            //echo "Time difference in minutes: " . $time_diff_minutes . " minutes<br>";
        } else {
            //echo "Start and end times are not set.";
        }
    ?>
<script>
var isMuted = false;
var muteBtn = document.getElementById("mute");
var muted = "../pics/mute_50_2.png";
var unmuted = "../pics/unmute_50.png";

function toggleMute() {
    const synth = window.speechSynthesis;
    const voices = synth.getVoices();

    if (isMuted) {
        window.speechSynthesis.cancel();
        muteBtn.textContent = 'Unmute';
        muteBtn.src = muted;
        isMuted = false;
    } else {
        var text = document.getElementById('question_text').textContent;
        var utterance = new SpeechSynthesisUtterance(text);
        utterance.voice = voices["Fred"];
        utterance.volume = 0.5;
        window.speechSynthesis.speak(utterance);
        muteBtn.textContent = 'Mute';
        muteBtn.src = unmuted;
        isMuted = true;
    }
}
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>
</html>
