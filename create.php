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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teamName = $_POST['team_name'];
    $secretWord = $_POST['secret_word'];
    $classId = $_POST['class_id']; // Assuming 'class' is the name of the select element

    try {
        // Insert data into teams table
        $query = "INSERT INTO teams (team_name, secret_word) VALUES ('$teamName', '$secretWord')";
        $conn->exec($query);
        $teamId = $conn->lastInsertId();

        if (isset($_POST['students'])) {
            foreach ($_POST['students'] as $student) {
                $name = $student['name'];
                $surname = $student['surname'];
                $query = "INSERT INTO users VALUES (NULL, '$name', '$surname', '$classId', '$teamId')";
                $conn->exec($query);
            }
        }

        echo "Team creato con successo";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $conn = null; // Close the connection
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crea un nuovo team</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            background-color: #f4f4f4;
        }

        form {
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        button {
            color: #fff;
            padding: 10px;
            border: none;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        
        .green {
            background-color: #4caf50;
         }
         
        .gray {
            background-color: #05050554;
         }


        .student-container {
            display: flex;
            flex-direction: column;
        }

        .student-input {
            display: flex;
            margin-bottom: 10px;
        }

        .remove-student {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            margin-left: 5px;
        }
    </style>

</head>
<body>

    <h2>Crea un team</h2>
    <form action="create.php" method="post">

        <label for="team_name">Nome del team</label>
        <input type="text" name="team_name" required>

        <label for="team_name">Classe</label>
        <select id="classSelect" type="text" name="class_id">
            <!-- Options will be dynamically added here using JavaScript -->
        </select>

        <label for="secret_word">Parola segreta</label>
        <input type="password" name="secret_word" required>

        <label for="students">Studenti</label>
        <div class="student-container" id="studentsContainer">
            <div class="student-input">
                <input type="text" name="students[][name]" placeholder="Nome" required>
                <input type="text" name="students[][surname]" placeholder="Cognome" required>
                <button type="button" class="remove-student" onclick="removeStudent(this)">Rimuovi</button>
            </div>
        </div>
        <button type="button" class="gray" onclick="addStudent()">Aggiungi Studente</button>

        <button type="submit" class="green">Crea</button>
    </form>

    <script>
        // Fetch classes data using AJAX
        fetch('get_classes.php')
            .then(response => response.json())
            .then(classes => {
                const classSelect = document.getElementById('classSelect');
                classes.forEach(classItem => {
                    const option = document.createElement('option');
                    option.value = classItem.class_id;
                    option.text = classItem.name;
                    classSelect.add(option);
                });
            })
            .catch(error => console.error('Error fetching classes:', error));

        function addStudent() {
            const studentsContainer = document.getElementById('studentsContainer');
            const studentInput = document.createElement('div');
            studentInput.classList.add('student-input');
            studentInput.innerHTML = `
                <input type="text" name="students[][name]" placeholder="Nome" required>
                <input type="text" name="students[][surname]" placeholder="Cognome" required>
                <button type="button"  class="remove-student" onclick="removeStudent(this)">Rimuovi</button>
            `;
            studentsContainer.appendChild(studentInput);
        }

        function removeStudent(button) {
            const studentInput = button.parentNode;
            const studentsContainer = document.getElementById('studentsContainer');
            studentsContainer.removeChild(studentInput);
        }
    </script>

</body>
</html>
