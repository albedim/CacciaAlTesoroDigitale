<?php
// Connect to MySQL server (change these credentials)
$servername = "localhost";
$username = "root";
$password = "11025";
$dbname = "cacciaaltesorodigitale";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}

$query = "SELECT * FROM teams ORDER BY level DESC";
$result = $conn->query($query);

$teamsData = [];

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $teamsData[] = [
        'team_name' => $row['team_name'],
        'level' => $row['level'],
    ];
}

$conn = null;
header('Content-Type: application/json');
echo json_encode($teamsData);
?>
