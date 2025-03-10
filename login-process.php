<?php
// Start session
session_start();

// Include configuration and database connection
require_once 'includes/config.php';
require_once 'includes/db-connection.php';
require_once 'includes/functions.php';

// Error logging function
function customErrorLog($message) {
    error_log("[LOGIN PROCESS] " . $message);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate and sanitize email input
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        customErrorLog("Invalid email format: $email");
        $_SESSION['login_error'] = "Invalid email format";
        header("Location: login.php");
        exit();
    }
    
    // Get password
    $password = $_POST['password'];
    
    try {
        // Get database connection
        $pdo = getDbConnection();
        
        if (!$pdo) {
            customErrorLog("Database connection failed");
            throw new Exception("Database connection failed");
        }
        
        // Prepare SQL statement to prevent SQL injection
        $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, password, role, student_id, department, year_of_study FROM users WHERE email = ? AND role = 'student'");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        // Log user details for debugging
        if ($user) {
            customErrorLog("User found: " . json_encode($user));
        } else {
            customErrorLog("No user found with email: $email");
        }
        
        // Check if user exists and password is correct
        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct, start a new session
                session_regenerate_id(true);
                
                // Store data in session variables
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $user['id'];  
                $_SESSION['email'] = $user['email'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['role'] = $user['role'];

                
                customErrorLog("Login successful for user ID: " . $user['id']);
                
                // Redirect to student dashboard
                header("Location: student/dashboard.php");
                exit();
            } else {
                customErrorLog("Incorrect password for email: $email");
                $_SESSION['login_error'] = "Incorrect email or password";
                header("Location: login.php");
                exit();
            }
        } else {
            customErrorLog("No student found with email: $email");
            $_SESSION['login_error'] = "Incorrect email or password";
            header("Location: login.php");
            exit();
        }
    } catch (Exception $e) {
        // Handle database errors
        customErrorLog("Login error: " . $e->getMessage());
        $_SESSION['login_error'] = "A system error occurred. Please try again later.";
        header("Location: login.php");
        exit();
    }
} else {
    // If someone tries to access this file directly
    customErrorLog("Direct access attempt to login-process.php");
    header("Location: login.php");
    exit();
}
?>