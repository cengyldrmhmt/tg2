<?php

function getTableStats($tableName) {
    $conn = getDbConnection();
    $stats = array('total' => 0, 'today_vip' => 0, 'today_premium' => 0, 'weekly_vip' => 0, 'weekly_premium' => 0, 'monthly_vip' => 0, 'monthly_premium' => 0, 'today_tg' => 0, 'weekly_tg' => 0, 'monthly_tg' => 0);
    
    try {
        // Get total record count
        $result = $conn->query("SELECT COUNT(*) as total FROM `$tableName`");
        if ($result === false) {
            throw new Exception("Error executing total count query: " . $conn->error);
        }
        $stats['total'] = $result->fetch_assoc()['total'];
        
        // Get today's VIP/Premium users count
        if ($tableName === 'forum_users' || $tableName === 'premium_users' || $tableName === 'tg_users') {
            $today = date('Y-m-d');
            $week_ago = date('Y-m-d', strtotime('-7 days'));
            $dateField = $tableName === 'tg_users' ? 'tg_registration_date' : 'vip_start_date';
            $countField = $tableName === 'forum_users' ? 'today_vip' : ($tableName === 'premium_users' ? 'today_premium' : 'today_tg');
            $weeklyField = $tableName === 'forum_users' ? 'weekly_vip' : ($tableName === 'premium_users' ? 'weekly_premium' : 'weekly_tg');
            
            // Get today's count
            $result = $conn->query("SELECT COUNT(*) as today_count FROM `$tableName` WHERE DATE($dateField) = '$today'");
            if ($result === false) {
                throw new Exception("Error executing today count query: " . $conn->error);
            }
            $stats[$countField] = $result->fetch_assoc()['today_count'];
            
            // Get weekly count
            $result = $conn->query("SELECT COUNT(*) as weekly_count FROM `$tableName` WHERE DATE($dateField) BETWEEN '$week_ago' AND '$today'");
            if ($result === false) {
                throw new Exception("Error executing weekly count query: " . $conn->error);
            }
            $stats[$weeklyField] = $result->fetch_assoc()['weekly_count'];
            
            // Get monthly count
            $month_ago = date('Y-m-d', strtotime('-30 days'));
            $monthlyField = $tableName === 'forum_users' ? 'monthly_vip' : ($tableName === 'premium_users' ? 'monthly_premium' : 'monthly_tg');
            $result = $conn->query("SELECT COUNT(*) as monthly_count FROM `$tableName` WHERE DATE($dateField) BETWEEN '$month_ago' AND '$today'");
            if ($result === false) {
                throw new Exception("Error executing monthly count query: " . $conn->error);
            }
            $stats[$monthlyField] = $result->fetch_assoc()['monthly_count'];
        }
        
    } catch (Exception $e) {
        error_log($e->getMessage(), 3, ERROR_LOG);
        return $stats; // Return default values instead of false
    }
    
    return $stats;
}