<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Salary Form</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>


    <div class="container">

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
  die("Connection failed: " . $conn->connect_error);
}

// Handle logout request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    if (!isset($_SESSION['employee_id'])) {
        header("Location: employee_id.php");
        exit();
    }

    $employee_id = $_SESSION['employee_id']; // Retrieve employee ID from session
    date_default_timezone_set('Asia/Kolkata'); // Set correct timezone
            $today = date('Y-m-d');
            $current_time = date('H:i:s');

    // Update only the last logout time for the day
    $updateLogout = "UPDATE attendance SET logout_time = ? WHERE employee_id = ? AND date = ?";
    
    if ($stmt = $conn->prepare($updateLogout)) {
        $stmt->bind_param("sis", $current_time, $employee_id, $today);
        $stmt->execute();
        $stmt->close();
    }

    // Clear session after updating logout time
    session_unset();
    session_destroy();

    // Redirect to the login page
    header("Location: employee_id.php");
    exit();
}
// to get the employee id
$employee_id = isset($_GET['employee_id']) ? $_GET['employee_id'] : '';

// Initialize variables with empty string
$name = $department = $basic_salary = $vehicle = $grade = $experience_years = '';

if (!empty($employee_id)) {
    // Fetch employee details
    $query = "SELECT name, department, basic_salary, vehicle, grade, joining_date FROM employees WHERE employee_id = ?";

    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
        

        // Assign values to variables
        $name = $employee['name'];
        $department = $employee['department'];
        $basic_salary = $employee['basic_salary'];
        $vehicle = $employee['vehicle'];
        $grade = $employee['grade'];
        $joiningDate = $employee['joining_date'];
        $da = $basic_salary *  1.25  ;   
       // Calculate House Rent Allowance (HRA) based on Grade
if ($grade == 'A') {
    $hra = 5000;
} elseif ($grade == 'B') {
    $hra = 4000;
} elseif ($grade == 'C') {
    $hra = 3000;
} else {
    $hra = 0; 
}
$ma = $basic_salary * .10 ;
// Calculate Conveyance Allowance 
$conveyance = 0; // Default value

if ($department == 'Sales') {
    if ($vehicle == 'Four Wheeler') {
        $conveyance = 10000;
    } elseif ($vehicle == 'Two Wheeler') {
        $conveyance = 6000;
    }
}
$pf = $basic_salary * .12 ;

// Calculate Bonus
if (($department == 'Sales' || $department == 'Developer') && $experience_years > 5) {
    $bonus = $basic_salary * 2.00;  
} elseif (($department == 'Sales' || $department == 'Developer') && $experience_years <= 5) {
    $bonus = $basic_salary * 1.25; 
} else {
    $bonus = $basic_salary * 1.00; 
}
// Calculate ESI (Employee's Security Insurance)
if ($department == 'Sales' || $department == 'Developer') {
    $esi = $basic_salary * 0.10;  
} else {
    $esi = $basic_salary * 0.05;  
}
// Calculate Salary/Month
$salary_month = $basic_salary + $da + $hra + $ma + $conveyance + $bonus - $pf - $esi;

$salary_annum = $salary_month * 12;
if ($salary_annum <= 500000) {
    $salary_annum = 0; 
    $tax = 0; 
} elseif ($salary_annum > 500000 && $salary_annum <= 550000) {
    $tax = ($salary_annum - 500000) * 0.10;  
} elseif ($salary_annum > 550000 && $salary_annum <= 600000) {
    $tax = ($salary_annum - 550000) * 0.20 + 5000; 
} elseif ($salary_annum > 600000 && $salary_annum <= 650000) {
    $tax = ($salary_annum - 600000) * 0.25 + 10000 + 5000; 
} else { 
   $tax = ($salary_annum - 650000) * 0.30 + 12500+10000+5000; 
}

// Calculate Salary/Annum
$salary_annum = $salary_month * 12;
// Tax calculation based on Salary/Annum

if ($salary_annum <= 500000) {
    $tax = 0; 
} elseif ($salary_annum > 500000 && $salary_annum <= 550000) {
    $tax = ($salary_annum - 500000) * 0.10;  
} elseif ($salary_annum > 550000 && $salary_annum <= 600000) {
    $tax = ($salary_annum - 550000) * 0.20 + 5000; 
} elseif ($salary_annum > 600000 && $salary_annum <= 650000) {
    $tax = ($salary_annum - 600000) * 0.25 + 10000 + 5000; 
} else { 
   $tax = ($salary_annum - 650000) * 0.30 + 12500+10000+5000; 
}



// Calculate Net Salary (Salary/annum - Tax)
$net_salary = $salary_annum - $tax;  



if(!empty($joiningDate)) {
    // Calculate experience
    $joining_date = new DateTime($joiningDate);
    $current_date = new DateTime();
    $experience = $joining_date->diff($current_date);

    // Experience in years
    $experience_years = $experience->y;
} else {
    // Handle case where date_of_joining is null or empty
    echo "Date of joining is not available for this employee.";
    $experience_years = 0; // Set a default value
}

    } else {
        echo "No employee found with ID: " . htmlspecialchars($employee_id);
        exit;
    }
} else {
    echo "Employee ID not provided.";
    exit;
}

?>

    <!-- html section -->
  
    <header class="header">
    <h2 class="title"> HRMS </h2>

    <!-- Attendance Dropdown -->
    <div class="dropdown">
        <button class="dropbtn">Attendance</button>
        <div class="dropdown-content">
            <?php
            $attendanceQuery = "SELECT date, login_time, logout_time, status FROM attendance WHERE employee_id = ? ORDER BY date DESC";
            $stmt = $conn->prepare($attendanceQuery);
            $stmt->bind_param("i", $employee_id);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                echo "<p><strong>Date:</strong> " . $row['date'] . " | <strong>Status:</strong> " . $row['status'] .
                     " | <strong>Login:</strong> " . ($row['login_time'] ?? 'N/A') .
                     " | <strong>Logout:</strong> " . ($row['logout_time'] ?? 'N/A') . "</p>";
            }
            ?>
        </div>
    </div>

    <form action="calculate_salary.php" method="POST">
        <button type="submit" name="logout" value="logout" class="logout-btn">Logout</button>
    </form>
</header>

<div class="employee-id-container">

        <h1>Employee Salary Details</h1>
        <form action="calculate_salary.php" method="POST" id="salaryForm">
            <div class="form-row">
            <label>Employee ID:</label>
<input type="text" name="employee_id" value="<?php echo htmlspecialchars($employee_id); ?>" readonly>
</div>

<div class="form-row">
            <label>Employee Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" readonly>
        </div>

        <div class="form-row">
            <label>Department:</label>
            <input type="text" name="department" value="<?php echo htmlspecialchars($department); ?>" readonly>
        </div>

        <div class="form-row">
            <label>Basic Salary:</label>
            <input type="text" name="basic_salary" id="basic_salary" value="<?php echo htmlspecialchars($basic_salary); ?>" readonly>
        </div>

        <div class="form-row">
            <label>Vehicle:</label>
            <input type="text" name="vehicle" value="<?php echo htmlspecialchars($vehicle); ?>" readonly>
        </div>

        <div class="form-row">
            <label>Grade:</label>
            <input type="text" name="grade" value="<?php echo htmlspecialchars($grade); ?>" readonly>
        </div>

        <div class="form-row">
            <label>Experience (Years):</label>
            <input type="text" name="experience" value="<?php echo $experience_years; ?>" readonly>
        </div>


            <div class="form-row">

            <label>Dearness Allowance:</label>
            <input type="text" name="da" value="<?php if($da) echo $da ?>"> </div>

            <div class="form-row">
    <label>House Rent Allowance (HRA):</label>
    <input type="text" name="hra" value="<?php echo htmlspecialchars($hra); ?>" readonly>
</div>


            <div class="form-row">
            <label>Medical Allowance:</label>
            <input type="text" name="ma" value="<?php if($ma) echo $ma ?>"> </div>

            <div class="form-row">
    <label>Conveyance Allowance (C/A):</label>
    <input type="text" name="conveyance" value="<?php echo htmlspecialchars($conveyance); ?>" readonly>
</div>


           
            <div class="form-row">
            <label>Provident Fund:</label>
            <input type="text" name="pf" value="<?php if($pf) echo $pf ?>"> </div>

            <div class="form-row">
    <label>Employee's Security Insurance (ESI):</label>
    <input type="text" name="esi" value="<?php echo htmlspecialchars($esi); ?>" readonly>
</div>

            <div class="form-row">
    <label>Bonus:</label>
    <input type="text" name="bonus" value="<?php echo htmlspecialchars($bonus); ?>" readonly>
</div>



<div class="form-row">
    <label>Salary/Month:</label>
    <input type="text" name="salary_month" value="<?php echo htmlspecialchars($salary_month); ?>" readonly>
</div>

<div class="form-row">
    <label>Salary/Annum:</label>
    <input type="text" name="salary_annum" value="<?php echo htmlspecialchars($salary_annum); ?>" readonly>
</div>
<div class="form-row">
    <label>Net Salary:</label>
    <input type="text" name="net_salary" value="<?php echo htmlspecialchars($net_salary); ?>" readonly>
</div>


<div class="form-row">
    <label>Income Tax:</label>
    <input type="text" name="tax" value="<?php echo htmlspecialchars($tax); ?>" readonly>
</div>
</div>
 
    </div>
    </div>
    
</body>
</html>

