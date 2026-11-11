<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'connection.php';

$message = "";
$search_id = "";
$result = null;

// SEARCH STUDENT BY ID
if (isset($_POST["search_student"])) {
    $search_id = $_POST["search_id"];
    
    if (!empty($search_id)) {
        $sql = "SELECT s.*, d.department_name 
                FROM students s 
                JOIN departments d ON s.department_id = d.department_id 
                WHERE s.student_id LIKE '%$search_id%' 
                ORDER BY s.student_id";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            $message = "Found " . mysqli_num_rows($result) . " student(s)";
        } else {
            $message = "No student found with ID: " . $search_id;
        }
    } else {
        $message = "Please enter a student ID to search";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Students - Student Management System</title>
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
        
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
        
        .success {
            background-color: #4CAF50;
            color: white;
        }
        
        .error {
            background-color: #ff4444;
            color: white;
        }
        
        .search-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .search-section h2 {
            margin-bottom: 20px;
            color: #333;
        }
        
        .search-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .search-form input {
            flex: 1;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            color: white;
        }
        
        .btn-primary {
            background-color: #4CAF50;
        }
        
        .btn-secondary {
            background-color: #667eea;
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
        
        .action-btns {
            display: flex;
            gap: 5px;
        }
        
        .action-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: none;
            color: white;
        }
        
        .edit-btn {
            background-color: #ff9800;
        }
        
        .view-btn {
            background-color: #2196F3;
        }
    </style>
</head>
<body>

    <div class="container">
        
        <!-- Header -->
        <div class="header">
            <h1>🔍 Search Students</h1>
            <a href="workspace.php" class="back-btn">← Back to Workspace</a>
        </div>
        
        <!-- Message -->
        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'No student') !== false || strpos($message, 'Please enter') !== false ? 'error' : 'success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <!-- Search Form -->
        <div class="search-section">
            <h2>Search by Student ID</h2>
            
            <form method="POST" action="">
                <div class="search-form">
                    <input type="text" 
                           name="search_id" 
                           placeholder="Enter Student ID (e.g., 011231001)" 
                           value="<?php echo $search_id; ?>">
                    <button type="submit" name="search_student" class="btn btn-primary">Search</button>
                    <a href="search.php" class="btn btn-secondary">Clear</a>
                </div>
            </form>
        </div>
        
        <!-- Search Results Table -->
        <?php if ($result): ?>
            <div class="table-section">
                <h2>Search Results</h2>
                
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Semester</th>
                            <th>CGPA</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $row['student_id']; ?></td>
                                    <td><?php echo $row['student_name']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['department_name']; ?></td>
                                    <td><?php echo $row['semester']; ?></td>
                                    <td><?php echo $row['cgpa']; ?></td>
                                    <td>
                                        <div class="action-btns">
                                            <a href="student.php?edit=<?php echo $row['student_id']; ?>" 
                                               class="action-btn edit-btn">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center;">No results found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        
    </div>

</body>
</html>

<?php
// Close connection
mysqli_close($conn);
?>
