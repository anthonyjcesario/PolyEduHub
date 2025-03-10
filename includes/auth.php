<?php
/**
 * Authentication helper functions
 * Place this file in: polyeduhub/includes/auth.php
 */

// Enable error logging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Check if student is logged in, redirect to login page if not
 * @param string $redirect_path Optional custom redirect path
 * @return void
 */
function checkStudentLogin($redirect_path = null) {
    // Ensure session is started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Detailed error logging function
    function logAuthError($message) {
        error_log("[PolyEduHub Auth Error] " . $message);
        error_log("Current Session: " . print_r($_SESSION, true));
    }
    
    // Determine base path for redirect
    $base_path = '../';
    
    // Default redirect path
    if ($redirect_path === null) {
        $redirect_path = $base_path . 'login.php';
    }
    
    // Comprehensive authentication checks
    $auth_failed = false;
    $fail_reason = '';
    
    // Check if user is logged in
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        $auth_failed = true;
        $fail_reason = "Not logged in";
    }
    
    // Check user role
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
        $auth_failed = true;
        $fail_reason = "Invalid user role: " . ($_SESSION['role'] ?? 'No role');
    }
    
    // Check if essential session variables exist
    $required_vars = ['id', 'email', 'first_name', 'last_name'];
    foreach ($required_vars as $var) {
        if (!isset($_SESSION[$var])) {
            $auth_failed = true;
            $fail_reason = "Missing session variable: $var";
            break;
        }
    }
    
    // If authentication fails, log error and redirect
    if ($auth_failed) {
        logAuthError("Authentication failed. Reason: " . $fail_reason);
        
        // Destroy session to clean up
        session_unset();
        session_destroy();
        
        // Start a new session for error messaging
        session_start();
        $_SESSION['login_error'] = "Please log in to access this page.";
        
        // Redirect to login
        header("Location: " . $redirect_path);
        exit();
    }
    
    // Optional: Session timeout check
    $inactive = 3600; // 1 hour
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive)) {
        logAuthError("Session expired due to inactivity");
        
        // Destroy session
        session_unset();
        session_destroy();
        
        // Start a new session for error messaging
        session_start();
        $_SESSION['login_error'] = "Your session has expired. Please log in again.";
        
        // Redirect to login
        header("Location: " . $redirect_path);
        exit();
    }
    
    // Update last activity time
    $_SESSION['last_activity'] = time();
}

/**
 * Check if admin is logged in, redirect to admin login page if not
 * @param string $redirect_path Optional custom redirect path
 * @return void
 */
function checkAdminLogin($redirect_path = null) {
    // Similar implementation to checkStudentLogin, 
    // but with admin-specific checks and redirect
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    $base_path = '../';
    
    if ($redirect_path === null) {
        $redirect_path = $base_path . 'admin-login.php';
    }
    
    // Check if user is logged in as admin
    if (!isset($_SESSION['loggedin']) || 
        !isset($_SESSION['role']) || 
        $_SESSION['role'] !== 'admin') {
        
        // Destroy session
        session_unset();
        session_destroy();
        
        // Start a new session for error messaging
        session_start();
        $_SESSION['login_error'] = "Please log in to access the admin area.";
        
        // Redirect to admin login
        header("Location: " . $redirect_path);
        exit();
    }
}

/**
 * Check if user is logged in
 * @return bool True if logged in, false otherwise
 */
function isLoggedIn() {
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
}

/**
 * Check if current session is for an admin user
 * @return bool True if admin, false otherwise
 */
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
?>