<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'connection.php';

// Get admin info from session
$admin_name = $_SESSION["admin_name"];
$username = $_SESSION["username"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workspace - Student Management System</title>
    <link rel="stylesheet" href="loginstyle.css">
    <style>
        .workspace-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .welcome-text h2 {
            color: #333;
            margin-bottom: 5px;
        }
        
        .welcome-text p {
            color: #666;
        }
        
        .logout-btn {
            padding: 10px 20px;
            background-color: #ff4444;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        
        .logout-btn:hover {
            background-color: #cc0000;
        }
        
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .menu-item {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.3s;
        }
        
        .menu-item:hover {
            transform: translateY(-5px);
        }
        
        .menu-item h3 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .menu-item p {
            color: #666;
        }
        
        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .stat-card h3 {
            font-size: 2rem;
            color: #4CAF50;
            margin-bottom: 5px;
        }
        
        .stat-card p {
            color: #666;
        }
    </style>
</head>
<body>

    <div class="workspace-container">
        
        <!-- Header Section -->
        <div class="header">
            <div class="welcome-text">
                <h2>Welcome, <?php echo htmlspecialchars($admin_name); ?>!</h2>
                <p>Username: <?php echo htmlspecialchars($username); ?></p>
            </div>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
        
        <!-- Statistics Section -->
        <div class="stats-section">
            <?php
            // Get total students count
            $student_count_query = "SELECT COUNT(*) as total FROM students";
            $student_result = mysqli_query($conn, $student_count_query);
            $student_count = mysqli_fetch_assoc($student_result)['total'];
            
            // Get total departments count
            $dept_count_query = "SELECT COUNT(*) as total FROM departments";
            $dept_result = mysqli_query($conn, $dept_count_query);
            $dept_count = mysqli_fetch_assoc($dept_result)['total'];
            
            // Get average CGPA
            $avg_cgpa_query = "SELECT AVG(cgpa) as avg_cgpa FROM students";
            $avg_result = mysqli_query($conn, $avg_cgpa_query);
            $avg_cgpa = mysqli_fetch_assoc($avg_result)['avg_cgpa'];
            $avg_cgpa = round($avg_cgpa, 2);
            
            // Get highest CGPA
            $max_cgpa_query = "SELECT MAX(cgpa) as max_cgpa FROM students";
            $max_result = mysqli_query($conn, $max_cgpa_query);
            $max_cgpa = mysqli_fetch_assoc($max_result)['max_cgpa'];
            $max_cgpa = round($max_cgpa, 2);
            
            // Get lowest CGPA
            $min_cgpa_query = "SELECT MIN(cgpa) as min_cgpa FROM students";
            $min_result = mysqli_query($conn, $min_cgpa_query);
            $min_cgpa = mysqli_fetch_assoc($min_result)['min_cgpa'];
            $min_cgpa = round($min_cgpa, 2);
            ?>
            
            <div class="stat-card">
                <h3><?php echo $student_count; ?></h3>
                <p>Total Students</p>
            </div>
            
            <div class="stat-card">
                <h3><?php echo $avg_cgpa; ?></h3>
                <p>Average CGPA</p>
            </div>
            
            <div class="stat-card">
                <h3><?php echo $max_cgpa; ?></h3>
                <p>Highest CGPA</p>
            </div>
            
            <div class="stat-card">
                <h3><?php echo $min_cgpa; ?></h3>
                <p>Lowest CGPA</p>
            </div>
        </div>
        
        <!-- Menu Section -->
        <div class="menu-grid">
            <a href="student.php" class="menu-item">
                <h3>👨‍🎓 Students</h3>
                <p>Manage student records</p>
            </a>
            
            <a href="department.php" class="menu-item">
                <h3>🏢 Departments</h3>
                <p>Manage departments</p>
            </a>
            
            <a href="search.php" class="menu-item">
                <h3>🔍 Search</h3>
                <p>Search students</p>
            </a>
            
            <div class="menu-item">
                <h3>⚙️ Settings</h3>
                <p>System settings</p>
            </div>
        </div>
        
    </div>

</body>
</html>

<?php
// Close connection
mysqli_close($conn);
?>
