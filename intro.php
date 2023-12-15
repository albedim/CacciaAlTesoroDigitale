<?php
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

$query = "SELECT * FROM teams";
$result = $conn->query($query);

$teamsData = [];

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $teamsData[] = [
        'team_name' => $row['team_name'],
        'level' => $row['level'],
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caccia al tesoro</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>Caccia al tesoro</h2>
    <canvas class="canvas" id="teamChart" width="400" height="200"></canvas>

    <script>

        function generateRandomColor() {
            const randomColors = [
                '#FF5733',
                '#33FF57',
                '#5733FF',
                '#FF33A1',
                '#33A1FF',
                '#A1FF33',
                '#FF3366',
                '#3366FF',
                '#66FF33',
                '#FFAA33'
            ];

            return randomColors[Math.random() * randomColors.length - 1]

        }


        var teamsData = <?php echo json_encode($teamsData); ?>;
        var teamNames = teamsData.map(function(team) {
            return team.team_name;
        });

        var teamLevels = teamsData.map(function(team) {
            return team.level;
        });

        var ctx = document.getElementById('teamChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: teamNames,
                datasets: [{
                    label: 'LIvello del team',
                    data: teamLevels,
                    backgroundColor: generateRandomColor(),
                    borderRadius: '5110px',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        function updateChart() {
        fetch('get_data.php')
            .then(response => response.json())
            .then(data => {
                myChart.data.labels = data.map(team => team.team_name);
                myChart.data.datasets[0].data = data.map(team => team.level);
                myChart.update();
            })
            .catch(error => console.error('Error fetching data:', error));
        }
        updateChart()
        setInterval(updateChart, 4000);
    </script>
</body>
</html>