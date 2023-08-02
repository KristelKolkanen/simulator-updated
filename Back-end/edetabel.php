<!DOCTYPE html>
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
                    </tr>
                </thead>
                <tbody>
                    <?php
			require_once "../config_dlg.php";

                    // Connect to the database
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Check for connection errors
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $results_query = "SELECT result_id, result_score, result_time, result_name FROM User_Result WHERE deleted = 0 ORDER BY result_score DESC, result_time ASC LIMIT 10";
                    $results_result = $conn->query($results_query);

                    // Display the results
                    if ($results_result->num_rows > 0) {
                        $rank = 1;
                        while ($row = $results_result->fetch_assoc()) {
                           		echo "<tr>";
                            		echo "<td>" . $rank . "</td>";
                            		echo "<td>" . $row["result_name"] . "</td>";
                            		echo "<td>" . $row["result_score"] . "</td>";
                            		echo "<td>" . $row["result_time"] . "</td>";
                            		echo "</tr>";
                            		$rank++;
                        }
                    } else {
                        echo "<tr><td colspan='4'>No results found.</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
        <div class="sidebar">
            <div class="button-group">
                <a href="../Front-end/share.html"><button class="answer-button" id="option1">Share</button></a>
                <a href="../Front-end/index.html"><button class="answer-button" id="option2">New interview?</button></a>
            </div>
        </div>
    </div>
</body>
</html>
