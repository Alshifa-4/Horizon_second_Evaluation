<?php
session_start();
include "connection.php";
include "navbar.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['return'])) {
    $book_id = $_POST['book_id'];
    $email = $_POST['email'];

    $user_res = mysqli_query($db, "SELECT id FROM users WHERE email='$email'");
    if (mysqli_num_rows($user_res) > 0) {
        $user = mysqli_fetch_assoc($user_res);
        $user_id = $user['id'];

        $borrow_res = mysqli_query($db, "SELECT * FROM borrowed_books WHERE user_id='$user_id' AND book_id='$book_id' AND return_date IS NULL");
        if (mysqli_num_rows($borrow_res) > 0) {
            $borrow = mysqli_fetch_assoc($borrow_res);
            $borrow_date = new DateTime($borrow['borrow_date']);
            $current_date = new DateTime();
            $interval = $borrow_date->diff($current_date);
            $days = $interval->days;

            $fine = 0;
            if ($days > 15) {
                $fine = ($days - 15) * 5;
            }

            $return_date = $current_date->format('Y-m-d');

            mysqli_query($db, "UPDATE borrowed_books SET return_date='$return_date', fine='$fine' WHERE id='{$borrow['id']}'");
            mysqli_query($db, "UPDATE books SET quantity=quantity+1 WHERE bid='$book_id'");

            echo "<script>alert('Book returned successfully! Fine: Rs. $fine');</script>";
        } else {
            echo "<script>alert('No such borrowed book found.');</script>";
        }
    } else {
        echo "<script>alert('Invalid email address.');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Return Book</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h2>Return a Book</h2>
    <form method="POST" action="">
        <label for="book_id">Book ID:</label>
        <input type="text" id="book_id" name="book_id" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <button type="submit" name="return">Return Book</button>
    </form>
</body>
</html>