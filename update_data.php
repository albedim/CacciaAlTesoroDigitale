<?php
session_start();
// Connect to MySQL server (change these credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_cacciaaltesorodigitale";

if (!is_null($_SESSION['team_id'])) {
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $level = $_POST['level']; // Assuming 'level' is the name of the input field
        $teamId = $_SESSION['team_id'];

        $sql = "UPDATE teams SET level=:level WHERE team_id=:team_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':level', $level, PDO::PARAM_INT);
        $stmt->bindParam(':team_id', $teamId, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Error execute query: " . $e->getMessage();
    }
}

$query = "SELECT * FROM teams";
$result = $conn->query($query);

$teamsData = [];

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $teamsData[] = [
        'name' => $row['name'],
        'level' => $row['level'],
    ];
}

$conn = null;
header('Content-Type: application/json');
echo json_encode($teamsData);
?>
