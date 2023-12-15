<?php
$servername = "localhost";
$username = "root";
$password = "11025";
$dbname = "cacciaaltesorodigitale";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $teamName = $_POST['team_name'];
        $secretWord = $_POST['secret_word'];

        $query = "SELECT * FROM teams WHERE team_name = :teamName AND secret_word = :secretWord";
        $stmt = $conn->prepare($query);
        $stmt->execute([':teamName' => $teamName, ':secretWord' => $secretWord]);
        $team = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($team) {
            session_start();
            $_SESSION['team_id'] = $team['team_id'];
            echo '<script type="text/javascript">';
            echo 'const searchParams = new URLSearchParams();';
            echo "const page = searchParams.get('page') != null ? searchParams.get('page') : 'index.html';";
            echo 'window.location.href = page;';
            echo '</script>';
        } else {
            echo "Credenziali non valide";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    // Close the connection
    $conn = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
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
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px;
            border: none;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>

</head>
<body>
    <div>
        <h2>Login</h2>
        <form action="signin.php" method="post">
            <label for="team_name">Nome team</label>
            <input type="text" name="team_name" required>

            <label for="secret_word">Parola segreta</label>
            <input type="password" name="secret_word" required>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
