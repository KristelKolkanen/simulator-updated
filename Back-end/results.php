<?php
session_start();

$new_form_id = $_SESSION['form_id'] ?? "";
$final_time = $_SESSION['time_diff'] ?? "";

/* $final_time_seconds = intval( $final_time % 60);
$final_time_minutes = intval( $final_time / 60);

$final_time_minutes = $final_time_minutes < 10 ? "0" . $final_time_minutes : $final_time_minutes;
$final_time_seconds = $final_time_seconds < 10 ? "0" . $final_time_seconds : $final_time_seconds; */

if (isset($_POST['points'])) {
    $points = $_POST['points'];
    $_SESSION['points'] = $points;
}

require_once "../config_dlg.php";

$new_form_id = $_SESSION['form_id'] ?? "";
$points = $_SESSION['points'] ?? "";
$time_diff = $_SESSION['time_diff'] ?? "";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['time_diff']) && isset($_SESSION['form_id'])) {
    $time_diff = $_SESSION['time_diff'];
    $form_id = $_SESSION['form_id'];

    $formattedTime = gmdate("H:i:s", $time_diff);

    $sql = "UPDATE User_Result SET result_time = '$formattedTime' WHERE Form_form_id = '$form_id'";
    if ($conn->query($sql) === TRUE) {
        echo "";
    } else {
        echo "Error inserting data: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Front-end/results.css">
    
    <title>DLG sisseastumisintervjuu simulaator</title>
</head>
<body>
<div class="session-value">
</div>
    <div class="container">       
        <p>Thank you for participating!</p>
        <div class="textcontainer">
            <div class="text">Your result:</div>
            <div id="results" class="text">
                <?php
                        if (isset($_POST['points'])) {
                            $points = $_POST['points'];
                            echo "$points / 100";
                        } else {
                            echo "No points.";
                        }
                    ?>
            </div>
        </div>
        <div class="btncontainer">
            <a href="edetabel.php"><button class="nosubmit">Don't share on leaderboard</button></a>
            <form action="submitname.php" method="POST">
                <input type="hidden" name="points" value="<?php echo $points; ?>">
                <button type="submit" class="submit">Share on leaderboard</button>
            </form>
        </div>
        
    </div>
</body>
</html>
