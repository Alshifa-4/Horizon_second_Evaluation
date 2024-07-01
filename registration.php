<?php
  include "connection.php";  // Assuming this file includes the database connection setup
  include "navbar.php";      // Assuming this file includes the navigation bar

  // Database connection setup (assuming connection.php does this already)
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "library";
  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
?>

<!DOCTYPE html>
<html>
<head>
  <title>Student Registration</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
  <style type="text/css">
    section {
      margin-top: -20px;
    }
  </style>   
</head>
<body>

<section>
  <div class="reg_img">
    <div class="box2">
      <h1 style="text-align: center; font-size: 35px;font-family: Lucida Console;">Library Management System</h1>
      <h1 style="text-align: center; font-size: 25px;">User Registration Form</h1>

      <form name="Registration" action="" method="post">
        <div class="login">
          <input class="form-control" type="text" name="first" placeholder="first" required=""> <br>
          <input class="form-control" type="text" name="last" placeholder="last" required=""> <br>
          <input class="form-control" type="text" name="username" placeholder="Username" required=""> <br>
          <input class="form-control" type="password" name="password" placeholder="Password" required=""> <br>
          <input class="form-control" type="text" name="roll" placeholder="roll" required=""><br>
          <input class="form-control" type="text" name="email" placeholder="Email" required=""><br>
          <input class="form-control" type="text" name="contact" placeholder="contact" required=""><br>
          <input class="btn btn-default" type="submit" name="submit" value="Sign Up" style="color: black; width: 70px; height: 30px">
        </div>
      </form>
    </div>
  </div>
</section>

<?php
  if(isset($_POST['submit'])) {
    $first = $_POST['first'];
    $last = $_POST['last'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $roll = $_POST['roll'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];

    // Check if username already exists
    $stmt = $conn->prepare("SELECT * FROM student WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;
    $stmt->close();

    if($count == 0) {
      // Insert new record into database
      $stmt = $conn->prepare("INSERT INTO student (first, last, username, password, roll, email, contact) VALUES (?, ?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("sssssss", $first, $last, $username, $password, $roll, $email, $contact);
      $stmt->execute();
      $stmt->close();
      
      echo '<script type="text/javascript">alert("Registration successful");</script>';
    } else {
      echo '<script type="text/javascript">alert("The username already exists.");</script>';
    }
  }

  // Close database connection
  $conn->close();
?>

</body>
</html>
