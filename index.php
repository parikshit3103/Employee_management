<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee Management</title>
  <link rel="stylesheet" href="styles.css">

</head>

<body>
  <div class="container">

  <?php

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



//initial record 

$result = $conn->query("SELECT * 
FROM employees 
ORDER BY employee_id ASC 
LIMIT 1;");
if ($result->num_rows > 0) {
  $employee_data = $result->fetch_assoc();
}


//first button 
if (isset($_POST['firstRecord'])) {
  // echo 'button clicked';
  $result = $conn->query("SELECT * 
  FROM employees 
  ORDER BY employee_id ASC 
  LIMIT 1;");
  if ($result->num_rows > 0) {
    $employee_data = $result->fetch_assoc();
  }
}

//last button 
if (isset($_POST['lastRecord'])) {
  //  echo 'button clicked';
  $result = $conn->query("SELECT * 
  FROM employees 
  ORDER BY employee_id DESC 
  LIMIT 1;");
  if ($result->num_rows > 0) {
    $employee_data = $result->fetch_assoc();
  }
}

//previous button 
if (isset($_POST['previousRecord'])) {
  //  echo 'button clicked';
  $imp_id = intval($_POST['employee_id']) - 1;
  //echo''. $imp_id .'';
  $result = $conn->query("SELECT * 
  FROM employees 
  WHERE employee_id = " . $imp_id . ";");
  if ($result->num_rows > 0) {
    $employee_data = $result->fetch_assoc();
  }
}
//next record 
if (isset($_POST['nextRecord'])) {
  //print_r($_POST);
  //  echo 'button clicked';
  $imp_id = intval($_POST['employee_id']) + 1;
  //echo''. $imp_id .'';
  $result = $conn->query("SELECT * 
    FROM employees 
    WHERE employee_id = " . $imp_id . ";");
  if ($result->num_rows > 0) {
    $employee_data = $result->fetch_assoc();
  }/* Center the login page content properly */
}

//new button 

if (isset($_POST["new"])) {

  $result = $conn->query("SELECT MAX(employee_id) AS max_id FROM employees");
  $row = $result->fetch_assoc();
  $next_id = $row ? $row['max_id'] + 1 : 1;
   $employee_data['employee_id'] = $next_id;
   $employee_data['name'] = '';
   $employee_data['department'] = '';
   $employee_data['dob'] = '';
   $employee_data['gender'] = '';
   $employee_data['grade'] = '';
   $employee_data['vehicle'] = '';
   $employee_data['joining_date'] = '';
   $employee_data['basic_salary'] = '';
}

// save button
if (isset($_POST["save"])) {

  $employee_id = $_POST['employee_id'];
  $name = $_POST['name'];
  $department = $_POST['department'];
  $dob = $_POST['dob'];
  $gender = $_POST['gender'];
  $grade = $_POST['grade'];
  $vehicle = $_POST['vehicle'];
  $joining_date = $_POST['joining_date'];
  $basic_salary = $_POST['basic_salary'];
  $stmt = $conn->prepare("INSERT INTO employees (employee_id, name, department, dob, gender, grade, vehicle, joining_date, basic_salary) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssssssss", $employee_id, $name, $department, $dob, $gender, $grade, $vehicle, $joining_date, $basic_salary);

  if ($stmt->execute()) {
      echo "New record created successfully";
  } else {
      echo "Error: " . $stmt->error;
  }
  
}

           
// modify button

if (isset($_POST["modify"])) {
  $employee_id = $_POST['employee_id'];
  $name = $_POST['name'];
  $department = $_POST['department'];
  $dob = $_POST['dob'];
  $gender = $_POST['gender'];
  $grade = $_POST['grade'];
  $vehicle = $_POST['vehicle'];
  $joining_date = $_POST['joining_date'];
  $basic_salary = $_POST['basic_salary'];

  // Update existing record in the database
  $stmt = $conn->prepare("UPDATE employees SET name = ?, department = ?, dob = ?, gender = ?, grade = ?, vehicle = ?, joining_date = ?, basic_salary = ? WHERE employee_id = ?");
  $stmt->bind_param("sssssssss",  $name, $department, $dob, $gender, $grade, $vehicle, $joining_date, $basic_salary, $employee_id);

  if ($stmt->execute()) {
      echo "Record updated successfully";
  } else {
      echo "Error: " . $stmt->error;
  }
}

// calculate salary 

if (isset($_POST['calculateSalary'])) {
    // Redirect to the calculate_salary.php page and pass the employee ID
    header("Location: calculate_salary.php?employee_id=" . $_POST['employee_id']);
    exit;
}


// connection close 
$conn->close();

?>
    <!-- this is my html  section -->

    <div class="employee-id-container">
      <h1>Employee Data </h1>
      <form action="index.php" method="POST" id="employeeForm">
        <div class="form-row">
          <label for="employee_id">Employee ID:</label>
          <input type="text" id="employee_id" name="employee_id" value="<?php echo htmlspecialchars($employee_data['employee_id']); ?>" required>

        </div>

        <div class="form-row">
          <label for="name">Employee Name:</label>
          <input type="text" id="name" name="name"
            value="<?php echo isset($employee_data) ? $employee_data['name'] : ''; ?>" required>
        </div>

        <div class="form-row">
          <label for="department">Department:</label>
          <select id="department" name="department" required>
            <option value="">Select Department</option>
            <option value="Admin" <?php echo isset($employee_data) && $employee_data['department'] == 'Admin' ? 'selected' : ''; ?>>Admin</option>
            <option value="Sales" <?php echo isset($employee_data) && $employee_data['department'] == 'Sales' ? 'selected' : ''; ?>>Sales</option>
            <option value="Developer" <?php echo isset($employee_data) && $employee_data['department'] == 'Developer' ? 'selected' : ''; ?>>Developer</option>
          </select>
        </div>

        <div class="form-row">
          <label for="dob">Date of Birth:</label>
          <input type="date" id="dob" name="dob"
            value="<?php echo isset($employee_data) ? $employee_data['dob'] : ''; ?>" required>
        </div>

        <div class="form-row">
          <label>Gender:</label>
          <div class="radio-group">
            <input type="radio" id="male" name="gender" value="Male" <?php echo isset($employee_data) && $employee_data['gender'] == 'Male' ? 'checked' : ''; ?> required>
            <label for="male">Male</label>
            <input type="radio" id="female" name="gender" value="Female" <?php echo isset($employee_data) && $employee_data['gender'] == 'Female' ? 'checked' : ''; ?> required>
            <label for="female">Female</label>
          </div>
        </div>

        <div class="form-row">
          <label for="grade">Grade:</label>
          <select id="grade" name="grade" required>
            <option value="">Select Grade</option>
            <option value="A" <?php echo isset($employee_data) && $employee_data['grade'] == 'A' ? 'selected' : ''; ?>>A
            </option>
            <option value="B" <?php echo isset($employee_data) && $employee_data['grade'] == 'B' ? 'selected' : ''; ?>>B
            </option>
            <option value="C" <?php echo isset($employee_data) && $employee_data['grade'] == 'C' ? 'selected' : ''; ?>>C
            </option>
          </select>
        </div>

        <div class="form-row">
          <label for="vehicle">Vehicle:</label>
          <select id="vehicle" name="vehicle" required>
            <option value="">Select Vehicle</option>
            <option value="Two Wheeler" <?php echo isset($employee_data) && $employee_data['vehicle'] == 'Two Wheeler' ? 'selected' : ''; ?>>Two Wheeler</option>
            <option value="Four Wheeler" <?php echo isset($employee_data) && $employee_data['vehicle'] == 'Four Wheeler' ? 'selected' : ''; ?>>Four Wheeler</option>
          </select>
        </div>

        <div class="form-row">
          <label for="joining_date">Joining Date:</label>
          <input type="date" id="joining_date" name="joining_date"
            value="<?php echo isset($employee_data) ? $employee_data['joining_date'] : ''; ?>" required>
        </div>

        <div class="form-row">
          <label for="basic_salary">Basic Salary:</label>
          <input type="number" id="basic_salary" name="basic_salary"
            value="<?php echo isset($employee_data) ? $employee_data['basic_salary'] : ''; ?>" required>
        </div>

        <!-- Buttons -->
        <div class="button-group">
          <button class="first-record" name="firstRecord">
            <|< </button>
              <button class="previous-record" name="previousRecord">
                <<< </button>
                  <button class="next-record" name="nextRecord">>>></button>
                  <button class="last-record" name="lastRecord">>|></button>

                  <button type="submit"  name="new" value="new">New</button>
                  <button type="submit" name="save" value="save">Save</button>
                  <button type="submit" name="modify" value="modify">Modify</button>
                  <button type="submit" name="calculateSalary" value="calculate">Calculate Salary</button>
                 

        </div>
      </form>
    </div>
  </div>
</body>
</html>