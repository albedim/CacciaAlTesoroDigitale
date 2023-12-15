<?php
// Connect to MySQL server (change these credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_cacciaaltesorodigitale";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE TABLE IF NOT EXISTS teams (
        team_id INT PRIMARY KEY AUTO_INCREMENT,
        team_name VARCHAR(50) UNIQUE NOT NULL,
        secret_word VARCHAR(50) NOT NULL,
        level INT DEFAULT 1
    ); CREATE TABLE IF NOT EXISTS classes (
        class_id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(50) UNIQUE NOT NULL
    ); CREATE TABLE IF NOT EXISTS users (
        user_id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(50) NOT NULL,
        surname VARCHAR(50) NOT NULL,
        class_id INT NOT NULL,
        team_id INT NOT NULL,
        FOREIGN KEY (class_id) REFERENCES classes(class_id),
        FOREIGN KEY (team_id) REFERENCES teams(team_id)
    )";
    
    
    $conn->exec($sql);
    
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}

$teamId = $_GET['team_id'];
$query = "SELECT * FROM users WHERE team_id = '$teamId'";
$result = $conn->query($query);

$teamsData = [];

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $teamsData[] = [
        'name' => $row['name'],
        'surname' => $row['surname'],
        'user_id' => $row['user_id'],
        'team_id' => $row['team_id'],
        'class_id' => $row['class_id'],
    ];
}

$conn = null;
header('Content-Type: application/json');
echo json_encode($teamsData);
?>
