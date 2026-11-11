<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'connection.php';

// Handle form submissions
$message = "";

// ADD NEW STUDENT
if (isset($_POST["add_student"])) {
    $student_id = $_POST["student_id"];
    $student_name = $_POST["student_name"];
    $email = $_POST["email"];
    $department_id = $_POST["department_id"];
    $semester = $_POST["semester"];
    $cgpa = $_POST["cgpa"];
    
    // Check if all fields are filled
    if (empty($student_id) || empty($student_name) || empty($email) || empty($department_id) || empty($semester) || empty($cgpa)) {
        $message = "Please fill all fields";
    } else {
        $sql = "INSERT INTO students (student_id, student_name, email, department_id, semester, cgpa) 
                VALUES ('$student_id', '$student_name', '$email', '$department_id', '$semester', '$cgpa')";
        
        if (mysqli_query($conn, $sql)) {
            $message = "Student added successfully!";
        } else {
            $message = "Error adding student: " . mysqli_error($conn);
        }
    }
}

// DELETE STUDENT
if (isset($_GET["delete"])) {
    $student_id = $_GET["delete"];
    
    $sql = "DELETE FROM students WHERE student_id = '$student_id'";
    
    if (mysqli_query($conn, $sql)) {
        $message = "Student deleted successfully!";
    } else {
        $message = "Error deleting student: " . mysqli_error($conn);
    }
}

// UPDATE STUDENT - Load data for editing
$edit_mode = false;
$edit_student = null;

if (isset($_GET["edit"])) {
    $edit_mode = true;
    $student_id = $_GET["edit"];
    
    $sql = "SELECT * FROM students WHERE student_id = '$student_id'";
    $result = mysqli_query($conn, $sql);
    $edit_student = mysqli_fetch_assoc($result);
}

// UPDATE STUDENT - Save changes
if (isset($_POST["update_student"])) {
    $student_id = $_POST["student_id"];
    $student_name = $_POST["student_name"];
    $email = $_POST["email"];
    $department_id = $_POST["department_id"];
    $semester = $_POST["semester"];
    $cgpa = $_POST["cgpa"];
    
    $sql = "UPDATE students 
            SET student_name = '$student_name', 
                email = '$email', 
                department_id = '$department_id', 
                semester = '$semester', 
                cgpa = '$cgpa' 
            WHERE student_id = '$student_id'";
    
    if (mysqli_query($conn, $sql)) {
        $message = "Student updated successfully!";
        $edit_mode = false;
    } else {
        $message = "Error updating student: " . mysqli_error($conn);
    }
}

// SEARCH STUDENT BY ID
$search_id = "";
if (isset($_POST["search_student"])) {
    $search_id = $_POST["search_id"];
    $sql = "SELECT s.*, d.department_name 
            FROM students s 
            JOIN departments d ON s.department_id = d.department_id 
            WHERE s.student_id LIKE '%$search_id%' 
            ORDER BY s.student_id";
    $result = mysqli_query($conn, $sql);
} else {
    // Get all students for display
    $sql = "SELECT s.*, d.department_name 
            FROM students s 
            JOIN departments d ON s.department_id = d.department_id 
            ORDER BY s.student_id";
    $result = mysqli_query($conn, $sql);
}

// Get all departments for dropdown
$dept_sql = "SELECT * FROM departments";
$dept_result = mysqli_query($conn, $dept_sql);

// Check for department query error
if (!$dept_result) {
    $message = "Error loading departments: " . mysqli_error($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students - Student Management System</title>
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
        
        .form-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .form-section h2 {
            margin-bottom: 20px;
            color: #333;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
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
            margin-right: 10px;
        }
        
        .btn-primary {
            background-color: #4CAF50;
            color: white;
        }
        
        .btn-secondary {
            background-color: #667eea;
            color: white;
        }
        
        .btn-danger {
            background-color: #ff4444;
            color: white;
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
        
        .delete-btn {
            background-color: #ff4444;
        }
    </style>
</head>
<body>

    <div class="container">
        
        <!-- Header -->
        <div class="header">
            <h1>👨‍🎓 Manage Students</h1>
            <a href="workspace.php" class="back-btn">← Back to Workspace</a>
        </div>
        
        <!-- Message -->
        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'Error') === false ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <!-- Add/Edit Student Form -->
        <div class="form-section">
            <h2><?php echo $edit_mode ? 'Edit Student' : 'Add New Student'; ?></h2>
            
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label>Student ID</label>
                        <input type="text" 
                               name="student_id" 
                               value="<?php echo $edit_mode ? $edit_student['student_id'] : ''; ?>" 
                               <?php echo $edit_mode ? 'readonly' : 'required'; ?>>
                    </div>
                    
                    <div class="form-group">
                        <label>Student Name</label>
                        <input type="text" 
                               name="student_name" 
                               value="<?php echo $edit_mode ? $edit_student['student_name'] : ''; ?>" 
                               required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" 
                               name="email" 
                               value="<?php echo $edit_mode ? $edit_student['email'] : ''; ?>" 
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label>Department</label>
                        <select name="department_id" required>
                            <option value="">Select Department</option>
                            <?php while ($dept = mysqli_fetch_assoc($dept_result)): ?>
                                <option value="<?php echo $dept['department_id']; ?>" 
                                        <?php echo $edit_mode && $edit_student['department_id'] == $dept['department_id'] ? 'selected' : ''; ?>>
                                    <?php echo $dept['department_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Semester</label>
                        <input type="number" 
                               name="semester" 
                               value="<?php echo $edit_mode ? $edit_student['semester'] : ''; ?>" 
                               min="1" required>
                    </div>
                    
                    <div class="form-group">
                        <label>CGPA</label>
                        <input type="number" 
                               name="cgpa" 
                               value="<?php echo $edit_mode ? $edit_student['cgpa'] : ''; ?>" 
                               min="0" max="4" step="0.01" required>
                    </div>
                </div>
                
                <?php if ($edit_mode): ?>
                    <button type="submit" name="update_student" class="btn btn-primary">Update Student</button>
                    <a href="student.php" class="btn btn-secondary">Cancel</a>
                <?php else: ?>
                    <button type="submit" name="add_student" class="btn btn-primary">Add Student</button>
                <?php endif; ?>
            </form>
        </div>
        
        <!-- Students Table -->
        <div class="table-section">
            <h2>All Students</h2>
            
            <!-- Search Form -->
            <form method="POST" action="" style="margin-bottom: 20px;">
                <div style="display: flex; gap: 10px; align-items: center;">
                    <input type="text" 
                           name="search_id" 
                           placeholder="Search by Student ID" 
                           value="<?php echo $search_id; ?>"
                           style="flex: 1; padding: 10px; border: 2px solid #ddd; border-radius: 5px;">
                    <button type="submit" name="search_student" class="btn btn-primary">Search</button>
                    <a href="student.php" class="btn btn-secondary">Show All</a>
                </div>
            </form>
            
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
                                        <a href="student.php?delete=<?php echo $row['student_id']; ?>" 
                                           class="action-btn delete-btn" 
                                           onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">No students found</td>
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
