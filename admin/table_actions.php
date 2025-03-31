<?php
session_start();
require_once '../config/config.php';
require_once '../includes/functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Get request data
$data = json_decode(file_get_contents('php://input'), true);
$action = isset($_GET['action']) ? sanitizeInput($_GET['action']) : '';
$tableName = isset($_GET['table']) ? sanitizeInput($_GET['table']) : '';

if (!$tableName || !validateTableName($tableName)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid table name']);
    exit();
}

$conn = getDbConnection();

switch ($action) {
    case 'update':
        if (!isset($data['id']) || !isset($data['data'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            exit();
        }

        $id = (int)$data['id'];
        $updateData = $data['data'];
        
        // Build update query
        $setClauses = [];
        $types = '';
        $values = [];
        
        foreach ($updateData as $column => $value) {
            $column = sanitizeInput($column);
            $setClauses[] = "`$column` = ?";
            $types .= 's';
            $values[] = sanitizeInput($value);
        }
        
        // Add id to values and types
        $types .= 'i';
        $values[] = $id;
        
        $setClause = implode(', ', $setClauses);
        $sql = "UPDATE `$tableName` SET $setClause WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...array_values($values));

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Update failed']);
        }
        break;

    case 'delete':
        if (!isset($data['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing ID']);
            exit();
        }

        $id = (int)$data['id'];
        $stmt = $conn->prepare("DELETE FROM `$tableName` WHERE id = ?");
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Delete failed']);
        }
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
        break;
}