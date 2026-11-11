<?php
session_start();

if (isset($_SESSION["admin_id"])) {
    header("Location: workspace.php");
    exit();
}

$error = $_SESSION["login_error"] ?? "";
unset($_SESSION["login_error"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Student Management System</title>
    <link rel="stylesheet" href="loginstyle.css?v=<?php echo time(); ?>">
</head>
<body>

    <!-- Page Title -->
    <div class="page-title">
        <h1>Student Management System</h1>
        <h2>Manges By @Esa </h2>
    </div>

    <!-- Login Form Container -->
    <div class="login-container">
        
        <div class="login-box">
            <h2>Admin Login</h2>
            
            <?php if ($error): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form action="authenticate.php" method="POST">
                
                <div class="form-group">
                    <label for="username">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="Enter username" 
                        required
                    >
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Enter password" 
                        required
                    >
                </div>
                
                <button type="submit" class="login-btn">Login</button>
                
            </form>
        </div>
        
    </div>

</body>
</html>
