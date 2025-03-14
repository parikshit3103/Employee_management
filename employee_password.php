
<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "parikshit";
$password = "dws@1234";
$database = "employee_managementdb";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Ensure Employee ID is set in session
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['employee_id'])) {
    $_SESSION['employee_id'] = $_POST['employee_id'];
} elseif (!isset($_SESSION['employee_id'])) {
    header("Location: employee_id.php");
    exit();
}

$error = ""; // Error message variable

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['password'])) {
    $employee_id = $_SESSION['employee_id'];
    $employee_name = trim($_POST['password']);

    if (empty($employee_name)) {
        $error = "Please enter your password.";
    } else {
        // Query to verify credentials
        $query = "SELECT * FROM employees WHERE employee_id = ? AND name = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $employee_id, $employee_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Login successful
            $_SESSION['employee_name'] = $employee_name;

            // Attendance Tracking
            date_default_timezone_set('Asia/Kolkata'); // Set correct timezone
            $today = date('Y-m-d');
            $current_time = date('H:i:s');
            
            $checkAttendance = "SELECT 1 FROM attendance WHERE employee_id = ? AND date = ? LIMIT 1";
            $stmt = $conn->prepare($checkAttendance);
            $stmt->bind_param("is", $employee_id, $today);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows == 0) {
                $stmt->close();
            
                $insertAttendance = "INSERT INTO attendance (employee_id, date, login_time, status) VALUES (?, ?, ?, 'Present')";
                $stmt = $conn->prepare($insertAttendance);
                $stmt->bind_param("iss", $employee_id, $today, $current_time);
                $stmt->execute();
            }
            
            // Redirect to salary calculation page
            header("Location: calculate_salary.php?employee_id=" . $employee_id);
            exit();
        } else {
            $error = "Invalid Password!";
        }
        $stmt->close();
    }
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="container">
        <h2>Enter your password </h2>

       
        <?php if (!empty($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>

        <form action="employee_password.php" method="POST">
            <div class="form-row">
                <label for="password">Password:</label>
                <input type="password" name="password" required>
            </div>
            <br>
            <div class="form-group">
                <button type="submit" class="btn">Login</button>
            </div>
        </form>
    </div>

</body>
</html>
