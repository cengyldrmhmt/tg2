<?php
// Authentication functions
function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function login($username, $password) {
    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        return true;
    }
    return false;
}

function logout() {
    session_destroy();
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}

// Database functions
function getTables() {
    $conn = getDbConnection();
    $tables = array();
    
    try {
        $result = $conn->query("SHOW TABLES");
        while ($row = $result->fetch_array(MYSQLI_NUM)) {
            $tables[] = $row[0];
        }
    } catch (Exception $e) {
        error_log($e->getMessage(), 3, ERROR_LOG);
        return false;
    }
    
    return $tables;
}

function getTableData($tableName) {
    $conn = getDbConnection();
    $data = array();
    
    try {
        // Get table columns
        $columns = array();
        $columnsResult = $conn->query("SHOW COLUMNS FROM `$tableName`");
        while ($column = $columnsResult->fetch_assoc()) {
            $columns[] = $column['Field'];
        }
        $data['columns'] = $columns;
        
        // Get table data
        $result = $conn->query("SELECT * FROM `$tableName`");
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $data['rows'] = $rows;
    } catch (Exception $e) {
        error_log($e->getMessage(), 3, ERROR_LOG);
        return false;
    }
    
    return $data;
}

// Security functions
function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function validateTableName($tableName) {
    return preg_match('/^[a-zA-Z0-9_]+$/', $tableName);
}

// Get current date for calculations
        $today = date('Y-m-d');
        $week_ago = date('Y-m-d', strtotime('-7 days'));
        $month_start = date('Y-m-01'); // First day of current month
        
        // Get all stats in a single optimized query using DATE comparison
        $query = "SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN DATE(created_at) = '$today' THEN 1 ELSE 0 END) as today,
            SUM(CASE WHEN DATE(created_at) >= '$week_ago' THEN 1 ELSE 0 END) as week,
            SUM(CASE WHEN DATE(created_at) >= '$month_start' THEN 1 ELSE 0 END) as month
            FROM forum_users";
