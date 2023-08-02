<?php
session_start();

if (isset($_POST['timePassed'])) {
    $_SESSION['timePassed'] = $_POST['timePassed'];
}
?>