<?php
session_start();
$res = [
    'res' => !empty($_SESSION['team_id'])
];
echo json_encode($res)
?>
