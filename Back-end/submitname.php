<?php
session_start();
require_once "../config_dlg.php";

$new_form_id = $_SESSION['form_id'] ?? "";
$points = $_SESSION['points'] ?? "";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (isset($_POST['name_input'], $_POST['points'])) {
    $name = $_POST['name_input'];

    $stmt = $conn->prepare("SELECT * FROM User_Result WHERE Form_form_id = ?");
    $stmt->bind_param("s", $new_form_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === false) {
        echo "Error executing query: " . $conn->error;
        exit;
    }

    if ($result->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE User_Result SET result_name = ?, result_score = ? WHERE Form_form_id = ?");
        $stmt->bind_param("sss", $name, $points, $new_form_id);

        if ($stmt->execute()) {
            echo "Name updated successfully.";
        } else {
            echo "Error updating name: " . $stmt->error;
        }
    } else {
        $stmt = $conn->prepare("INSERT INTO User_Result (result_name, Form_form_id, result_score) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $name, $new_form_id, $points);

        if ($stmt->execute()) {
            echo "Name inserted successfully.";
        } else {
            echo "Error inserting name: " . $stmt->error;
        }
    }

    $stmt->close();
    header("Location: edetabel.php");
    exit;
} else {
    echo ""; //"Name and points not set."
}

$results_query = "SELECT result_id, result_name FROM User_Result WHERE deleted = 0 ";
$results_result = $conn->query($results_query);

if($results_result->num_rows > 0){
    while ($row = $results_result->fetch_assoc()) {
        $previous_name = $row["result_name"];
}
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DLG sisseastumisintervjuu simulaator</title>
    <link rel="stylesheet" href="../Front-end/submitname.css">
</head>
<body>
    <div id="textcontainer">
        <p>Submit your name for the leaderboard</p>
        <form method="POST" id="myName" action="submitname.php">
            <label for="name_input"></label>
            <input type="text" id="name_input" name="name_input" placeholder="Your name" value="<?php echo htmlspecialchars($previous_name); ?>"required>
            <input type="hidden" name="points" value="<?php echo $points; ?>">
            <button form="myName" type="submit" id="name_result" name="form_submit">SUBMIT</button>
            <a href="results.php"><button id="skip">BACK</button></a>
            
        </form>
    </div>  
</body>
</html>
