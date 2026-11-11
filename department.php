<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'connection.php';

// Get all departments with student count
$sql = "SELECT d.department_id, d.department_name, COUNT(s.student_id) as Total_Student 
        FROM departments d 
        LEFT JOIN students s ON d.department_id = s.department_id 
        GROUP BY d.department_id, d.department_name 
        ORDER BY d.department_name";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments - Student Management System</title>
    <link rel="stylesheet" href="loginstyle.css">
    <style>
        .container {
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
        
        .back-btn {
            padding: 10px 20px;
            background-color: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        
        /* Table Styles */
        .table-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .table-section h2 {
            margin-bottom: 20px;
            color: #333;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        table th {
            background-color: #667eea;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        
        table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        
        table tr:hover {
            background-color: #f5f5f5;
        }
        
        .count-badge {
            background-color: #4CAF50;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
        }
        
        .empty-count {
            background-color: #999;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
        }
    </style>
</head>
<body>

    <div class="container">
        
        <!-- Header -->
        <div class="header">
            <h1>🏢 Departments</h1>
            <a href="workspace.php" class="back-btn">← Back to Workspace</a>
        </div>
        
        <!-- Departments Table -->
        <div class="table-section">
            <h2>Departments and Student Count</h2>
            
            <table>
                <thead>
                    <tr>
                        <th>Department ID</th>
                        <th>Department Name</th>
                        <th>Number of Students</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $row['department_id']; ?></td>
                                <td><?php echo $row['department_name']; ?></td>
                                <td>
                                    <?php if ($row['Total_Student'] > 0): ?>
                                        <span class="count-badge"><?php echo $row['Total_Student']; ?></span>
                                    <?php else: ?>
                                        <span class="empty-count">0</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center;">No departments found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
    </div>

</body>
</html>

<?php
// Close connection
mysqli_close($conn);
?>
