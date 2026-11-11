<?php
session_start();

// Include database connection
include 'connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    // Prepare SQL statement to prevent SQL injection
    $sql = "SELECT admin_id, username, password, admin_name FROM admins WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "s", $username);
        
        // Execute statement
        mysqli_stmt_execute($stmt);
        
        // Get result
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            // Verify password (database has plain text password)
            if ($password === $row["password"]) {
                // Password matches - set session variables
                $_SESSION["admin_id"] = $row["admin_id"];
                $_SESSION["username"] = $row["username"];
                $_SESSION["admin_name"] = $row["admin_name"];
                
                // Redirect to workspace
                header("Location: workspace.php");
                exit();
            } else {
                // Password doesn't match
                $_SESSION["login_error"] = "Invalid username or password";
                header("Location: login.php");
                exit();
            }
        } else {
            // Username not found
            $_SESSION["login_error"] = "Invalid username or password";
            header("Location: login.php");
            exit();
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        // SQL error
        $_SESSION["login_error"] = "Database error. Please try again.";
        header("Location: login.php");
        exit();
    }
} else {
    // If not POST request, redirect to login
    header("Location: login.php");
    exit();
}

// Close connection
mysqli_close($conn);
?>
