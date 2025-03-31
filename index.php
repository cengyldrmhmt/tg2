<?php
session_start();
require_once 'config/config.php';
require_once 'includes/functions.php';

// Check if user is not logged in
if (!isLoggedIn()) {
    // If the current page is not login.php, redirect to login
    if (basename($_SERVER['PHP_SELF']) !== 'login.php') {
        header('Location: login.php');
        exit();
    }
}

// If user is logged in and trying to access login page, redirect to dashboard
if (isLoggedIn() && basename($_SERVER['PHP_SELF']) === 'login.php') {
    header('Location: admin/dashboard.php');
    exit();
}

// Redirect to login page by default
header('Location: login.php');
exit();