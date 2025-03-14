<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee ID</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="container">
        <h2 class="title">Enter Your Employee ID</h2>
        <br>
        <form action="employee_password.php" method="POST">
            <div class="form-group">
                <label for="employee_id">Employee ID:</label>
                <input type="text" name="employee_id" id="employee_id" required>
            </div>
            <br>
            <div class="form-group">
                <button type="submit" class="btn">Continue</button>
            </div>
        </form>
    </div>

</body>
</html>

