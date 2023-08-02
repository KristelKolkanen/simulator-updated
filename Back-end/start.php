<?php

session_start();
// Retrieve the form_id from the URL
$new_form_id = $_SESSION['form_id'] ?? "";
$form_name = $_SESSION['form_name'] ?? "";
$form_age = $_SESSION['form_age'] ?? "";
$form_country = $_SESSION['form_country'] ?? "";
$form_education = $_SESSION['form_education'] ?? "";
$form_work = $_SESSION['form_work'] ?? "";
$form_hobby = $_SESSION['form_hobby'] ?? "";
$form_kids = $_SESSION['form_kids'] ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Front-end/begin.css">
    <script src="timer.js"></script>
    <title>Start</title>
</head>
<body>
  <div id="textcontainer">
      <p>The interview is about to begin.</p>
      <p>You have 20 minutes to answer the questions.</p>
  
    <form action="interview.php" method="POST">
        <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
        <input type="hidden" name="form_name" value="<?php echo $form_name; ?>">
        <input type="hidden" name="form_age" value="<?php echo $form_age; ?>">
        <input type="hidden" name="form_country" value="<?php echo $form_country; ?>">
        <input type="hidden" name="form_education" value="<?php echo $form_education; ?>">
        <input type="hidden" name="form_work" value="<?php echo $form_work; ?>">
        <input type="hidden" name="form_hobby" value="<?php echo $form_hobby; ?>">
        <input type="hidden" name="form_kids" value="<?php echo $form_kids; ?>">
        <input type="hidden" name="form_id" value="<?php echo $new_form_id; ?>">
        <button id="button" type="submit">START</button>
    </form>
  </div>
  <script>resetTimer();</script>
</body>
</html>
