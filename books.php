<?php
  session_start();
  include "connection.php";
  include "navbar.php";

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['borrow'])) {
      $book_id = $_POST['book_id'];
      $email = $_POST['email'];

      $user_res = mysqli_query($db, "SELECT id FROM users WHERE email='$email'");
      if (mysqli_num_rows($user_res) > 0) {
          $user = mysqli_fetch_assoc($user_res);
          $user_id = $user['id'];

          $book_res = mysqli_query($db, "SELECT * FROM books WHERE bid='$book_id' AND quantity > 0");
          if (mysqli_num_rows($book_res) > 0) {
              $book = mysqli_fetch_assoc($book_res);
              $quantity = $book['quantity'];

              $borrow_date = date('Y-m-d');
              $return_date = null; // Set this when the book is returned

              mysqli_query($db, "INSERT INTO borrowed_books (user_id, book_id, borrow_date, return_date) VALUES ('$user_id', '$book_id', '$borrow_date', '$return_date')");
              mysqli_query($db, "UPDATE books SET quantity=quantity-1 WHERE bid='$book_id'");

              echo "<script>alert('Book borrowed successfully!');</script>";
          } else {
              echo "<script>alert('Book is out of service.');</script>";
          }
      } else {
          echo "<script>alert('Invalid email address.');</script>";
      }
  }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Books</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h2>List Of Books</h2>
    <form method="GET" action="">
        <input type="text" name="filter_author" placeholder="Filter by Author">
        <input type="text" name="filter_title" placeholder="Filter by Title">
        <input type="text" name="filter_id" placeholder="Filter by Book ID">
        <button type="submit">Apply Filters</button>
    </form>
    <?php
        $filter_author = isset($_GET['filter_author']) ? $_GET['filter_author'] : '';
        $filter_title = isset($_GET['filter_title']) ? $_GET['filter_title'] : '';
        $filter_id = isset($_GET['filter_id']) ? $_GET['filter_id'] : '';

        $query = "SELECT * FROM books WHERE authors LIKE '%$filter_author%' AND name LIKE '%$filter_title%' AND bid LIKE '%$filter_id%' ORDER BY name ASC";
        $res = mysqli_query($db, $query);

        echo "<table class='table table-bordered table-hover'>";
        echo "<tr style='background-color: white;'>";
        echo "<th>ID</th>";
        echo "<th>Book-Name</th>";
        echo "<th>Authors Name</th>";
        echo "<th>Edition</th>";
        echo "<th>Status</th>";
        echo "<th>Quantity</th>";
        echo "<th>Department</th>";
        echo "<th>Action</th>";
        echo "</tr>";

        while ($row = mysqli_fetch_assoc($res)) {
            echo "<tr>";
            echo "<td>{$row['bid']}</td>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['authors']}</td>";
            echo "<td>{$row['edition']}</td>";
            echo "<td>{$row['status']}</td>";
            echo "<td>{$row['quantity']}</td>";
            echo "<td>{$row['department']}</td>";
            echo "<td>
                    <form method='POST' action=''>
                        <input type='hidden' name='book_id' value='{$row['bid']}'>
                        <input type='email' name='email' placeholder='Enter your email' required>
                        <button type='submit' name='borrow'>Borrow</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
        echo "</table>";
    ?>
</body>
</html>