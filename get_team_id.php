<?php
session_start();
$teamsData['id_team']="";
if(!is_null($_SESSION['team_id'])){
    $teamsData['id_team'] = $_SESSION['team_id'];
}
header('Content-Type: application/json');
echo json_encode($teamsData);
?>
