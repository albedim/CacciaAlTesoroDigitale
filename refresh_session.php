<?php
session_start();
$session = $_SESSION['team_id'];
$_SESSION['team_id'] = $session;
?>
