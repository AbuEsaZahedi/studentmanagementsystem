<?php

session_start();

include("connectioncopy.php");

// Get login information
$email = $_POST['email'];
$password = $_POST['password'];


// Find customer
$sql = "SELECT * FROM users
        WHERE email = ? AND password = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ss",
    $email,
    $password
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);


// Check customer
if (mysqli_num_rows($result) == 1) {

    // Get customer information
    $user = mysqli_fetch_assoc($result);

    // Save customer ID in session
    $_SESSION['user_id'] = $user['user_id'];

    // Save customer name
    $_SESSION['user_name'] = $user['name'];

    // Go to home page
    header("Location: index.php");
    exit();

} else {

    echo "Invalid email or password.";

    echo "<br><br>";

    echo "<a href='login.php'>Try Again</a>";
}

?>