<?php
require_once "../config_dlg.php";
    $comment_error = null;

    if(isset($_POST["deleting"])){
        if(isset($_POST["to_delete"]) && !empty($_POST["to_delete"])){
             $conn = new mysqli($servername, $username, $password, $dbname);
                  // Check for connection errors
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $stmt=$conn->prepare("UPDATE User_Result SET deleted = 1 WHERE result_id = ?");
            echo $conn->error;
            $stmt->bind_param("i", $_POST["to_delete"]);
            $stmt->execute();
            $stmt->close();
            $conn->close();
        }
    }

    //analytics, average result, average time
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $stmt = $conn->prepare("SELECT AVG(result_score), AVG(result_time) FROM User_Result");
	echo $conn->error;
    $stmt->bind_result($avg_score_db, $avg_time_db);
    $stmt->execute();
	echo $stmt->error;

    $analytics_score = null; //average score
    $analytics_time = null; //average time

    if($stmt->fetch()){
        $analytics_score = $avg_score_db;
        $analytics_time = $avg_time_db;
    }
    $stmt->close();
    $conn->close();

    $analytics_time = round($analytics_time / 60);

    //analytics, amount of participants
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $stmt = $conn->prepare("SELECT count(*) FROM User_Result");
	echo $conn->error;
    $stmt->bind_result($participants_db);
    $stmt->execute();
	echo $stmt->error;

    $analytics_participants = null; //participants in total

    if($stmt->fetch()){
        $analytics_participants = $participants_db;
    }
    $stmt->close();
    $conn->close();

    //analytics, average age
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $stmt = $conn->prepare("SELECT AVG(form_age) FROM Form");
	echo $conn->error;
    $stmt->bind_result($avg_age_db);
    $stmt->execute();
	echo $stmt->error;

    $analytics_age = null; //participants in total

    if($stmt->fetch()){
        $analytics_age = $avg_age_db;
    }
    $stmt->close();
    $conn->close();

?>

<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../Front-end/edetabel.css">
    <link rel="stylesheet" href="../Front-end/tabel.css">    
</head>
<body>
    <div class="pagecontainer">
        <div class="maincontent">
            <div class="header"><h1>LEADERBOARD</h1></div>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Name</th>
                        <th>Score</th>
                        <th>Time</th>
                        <!--<th>ID</th>-->
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $servername = "localhost";
                        $username = "if22";
                        $password = "if22pass";
                        $dbname = "if22_DLGsimulaator";

                        // Connect to the database
                        $conn = new mysqli($servername, $username, $password, $dbname);

                        // Check for connection errors
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $stmt = $conn->prepare("SELECT result_id, result_score, result_time, result_name, deleted FROM User_Result WHERE deleted = 0 ORDER BY result_score DESC, result_time ASC LIMIT 10");
                        $stmt->bind_result($result_id, $result_score, $result_time, $result_name, $deleted);

                        // Display the results
                        if ($stmt->execute()) {
                            $rank = 1;
                            while ($stmt->fetch()) {
                                echo "<tr> \n";
                                echo "<td>" . $rank . "</td>";
                                echo "<td>" . $result_name . "</td>";
                                echo "<td>" . $result_score . "</td>";
                                echo "<td>" . $result_time . "</td>";
                                //echo "<td>" . $result_id . "</td>";
                                echo '<td id="btn_group"><form method="POST"><input type="hidden" name="to_delete" value="' .$result_id  .'"><input type="submit" id="selected_id_' .$result_id .'" name="deleting" value="Delete"></form></td>' ."\n";
                                echo "</tr> \n";
                                $rank++;
                            }
                        } else {
                            echo "<tr><td colspan='4'>No results found.</td></tr>";
                        }

                        // Close the database connection
                        $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
        <div class="sidecontent">
            <div class="keskmine_vanus container"><p>Osalejate keskmine vanus:</p><br><?php echo floor($analytics_age); ?></div>
            <div class="osalejad_kokku container"><p>Osalejaid kokku:</p><br><?php echo $analytics_participants; ?></div>
            <div class="keskmine_tulemus container"><p>Keskmine tulemus:</p><br><?php echo floor($analytics_score); ?></div>
            <div class="keskmine_aeg container"><p>Keskmine aeg:</p><br><?php echo $analytics_time; ?><p>minutit</p></div>
        </div>    
    </div>
</body>
</html>
