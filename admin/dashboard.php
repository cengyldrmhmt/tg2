<?php
session_start();
require_once '../config/config.php';
require_once '../includes/functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: ../login.php');
    exit();
}

// Get all tables from database
$tables = getTables();
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="sidebar">
        <div class="logo-details">
            <i class='bx bx-code-alt'></i>
            <span class="logo_name">Admin Panel</span>
        </div>
        <ul class="nav-links">
            <li>
                <a href="dashboard.php" class="active">
                    <i class='bx bx-grid-alt'></i>
                    <span class="link_name">Dashboard</span>
                </a>
            </li>
            <?php if ($tables): ?>
                <?php foreach ($tables as $table): ?>
                    <li>
                        <a href="view_table.php?table=<?php echo urlencode($table); ?>">
                            <i class='bx bx-table'></i>
                            <span class="link_name"><?php echo htmlspecialchars($table); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>

            <li>
                <a href="logout.php">
                    <i class='bx bx-log-out'></i>
                    <span class="link_name">Logout</span>
                </a>
            </li>
        </ul>
    </div>

    <section class="home-section">
        <div class="home-content">
            <i class='bx bx-menu'></i>
            <span class="text">Dashboard</span>
            <div class="theme-switch-container">
                <label class="theme-switch">
                    <input type="checkbox" id="theme-toggle">
                    <span class="slider"></span>
                </label>
            </div>
        </div>

        <div class="container-fluid px-4">
            <?php
            require_once '../includes/stats.php';
            $userStats = getTableStats('forum_users');
            $premiumStats = getTableStats('premium_users');
            $tgStats = getTableStats('tg_users');
            ?>

            <div class="row g-4 mt-2">
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-card-info">
                            <h4>Forum Kullanıcıları</h4>
                            <p><?php echo $userStats['total']; ?></p>
                        </div>
                        <i class='bx bx-user-circle'></i>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-card-info">
                            <h4>Premium Kullanıcılar</h4>
                            <p><?php echo $premiumStats['total']; ?></p>
                        </div>
                        <i class='bx bx-diamond'></i>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-card-info">
                            <h4>Telegram Kullanıcıları</h4>
                            <p><?php echo $tgStats['total']; ?></p>
                        </div>
                        <i class='bx bx-send'></i>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-2">
                <div class="col-12 col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">VIP Kullanıcı İstatistikleri</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-crown me-2'></i>
                                    <span>Bugün VIP Olan</span>
                                </div>
                                <h3><?php echo $userStats['today_vip']; ?></h3>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-calendar-week me-2'></i>
                                    <span>Bu Hafta VIP Olan</span>
                                </div>
                                <h3><?php echo $userStats['weekly_vip']; ?></h3>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-calendar me-2'></i>
                                    <span>Bu Ay VIP Olan</span>
                                </div>
                                <h3><?php echo $userStats['monthly_vip']; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Premium Kullanıcı İstatistikleri</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-diamond me-2'></i>
                                    <span>Bugün Premium Olan</span>
                                </div>
                                <h3><?php echo $premiumStats['today_premium']; ?></h3>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-calendar-week me-2'></i>
                                    <span>Bu Hafta Premium Olan</span>
                                </div>
                                <h3><?php echo $premiumStats['weekly_premium']; ?></h3>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-calendar me-2'></i>
                                    <span>Bu Ay Premium Olan</span>
                                </div>
                                <h3><?php echo $premiumStats['monthly_premium']; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Telegram Kullanıcı İstatistikleri</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-user-plus me-2'></i>
                                    <span>Bugün Katılan</span>
                                </div>
                                <h3><?php echo $tgStats['today_tg']; ?></h3>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-calendar-week me-2'></i>
                                    <span>Bu Hafta Katılan</span>
                                </div>
                                <h3><?php echo $tgStats['weekly_tg']; ?></h3>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-calendar me-2'></i>
                                    <span>Bu Ay Katılan</span>
                                </div>
                                <h3><?php echo $tgStats['monthly_tg']; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<style>
:root[data-theme="light"] {
    --bg-color: #f7f7f7;
    --text-color: #333333;
    --card-bg: #ffffff;
    --card-text: #333333;
    --border-color: #e0e0e0;
    --sidebar-bg: #ffffff;
    --sidebar-text: #333333;
    --sidebar-hover: #f0f0f0;
    --link-color: #0d6efd;
    --link-hover: #0a58ca;
    --stat-card-bg: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
    --stat-card-text: #ffffff;
    --table-bg: #ffffff;
    --table-text: #333333;
    --table-border: #dee2e6;
    --table-hover: #f8f9fa;
    --table-stripe: #f2f2f2;
}

:root[data-theme="dark"] {
    --bg-color: #1a1a1a;
    --text-color: #e0e0e0;
    --card-bg: #2d2d2d;
    --card-text: #e0e0e0;
    --border-color: #404040;
    --sidebar-bg: #2d2d2d;
    --sidebar-text: #e0e0e0;
    --sidebar-hover: #3d3d3d;
    --link-color: #6ea8fe;
    --link-hover: #8bb9fe;
    --stat-card-bg: linear-gradient(to right, #434343 0%, #000000 100%);
    --stat-card-text: #ffffff;
    --table-bg: #2d2d2d;
    --table-text: #e0e0e0;
    --table-border: #404040;
    --table-hover: #3d3d3d;
    --table-stripe: #363636;
}

body {
    background-color: var(--bg-color);
    color: var(--text-color);
    position: relative;
    min-height: 100vh;
    width: 100%;
}

.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 260px;
    background: var(--sidebar-bg);
    z-index: 100;
    transition: all 0.5s ease;
    padding: 6px 14px;
}

.sidebar.close {
    width: 78px;
}

.sidebar .logo-details {
    height: 60px;
    width: 100%;
    display: flex;
    align-items: center;
}

.sidebar .logo-details i {
    font-size: 30px;
    color: var(--text-color);
    height: 50px;
    min-width: 50px;
    text-align: center;
    line-height: 50px;
}

.sidebar .logo-details .logo_name {
    font-size: 22px;
    color: var(--text-color);
    font-weight: 600;
    transition: 0.3s ease;
    transition-delay: 0.1s;
}

.sidebar.close .logo-details .logo_name {
    transition-delay: 0s;
    opacity: 0;
    pointer-events: none;
}

.sidebar .nav-links {
    height: 100%;
    padding: 30px 0 150px 0;
    overflow: auto;
    list-style: none;
    margin: 0;
}

.sidebar .nav-links::-webkit-scrollbar {
    display: none;
}

.sidebar .nav-links li {
    position: relative;
    list-style: none;
    transition: all 0.4s ease;
}

.sidebar .nav-links li:hover {
    background: var(--sidebar-hover);
}

.sidebar .nav-links li i {
    height: 50px;
    min-width: 50px;
    text-align: center;
    line-height: 50px;
    color: var(--text-color);
    font-size: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.sidebar .nav-links li a {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: var(--text-color);
}

.sidebar .nav-links li a .link_name {
    font-size: 16px;
    font-weight: 400;
    color: var(--text-color);
    transition: all 0.4s ease;
}

.sidebar.close .nav-links li a .link_name {
    opacity: 0;
    pointer-events: none;
}

.home-section {
    position: relative;
    left: 260px;
    width: calc(100% - 260px);
    transition: all 0.5s ease;
}

.sidebar.close ~ .home-section {
    left: 78px;
    width: calc(100% - 78px);
}

.home-section .home-content {
    height: 60px;
    display: flex;
    align-items: center;
    padding: 0 20px;
}

.home-section .home-content i,
.home-section .home-content .text {
    color: var(--text-color);
    font-size: 24px;
}

.home-section .home-content i {
    margin: 0 15px;
    cursor: pointer;
}

.home-section .home-content .text {
    font-size: 26px;
    font-weight: 600;
}

.stat-card {
    padding: 20px;
    background: var(--stat-card-bg);
    border-radius: 10px;
    color: var(--stat-card-text);
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-card i {
    font-size: 3rem;
    opacity: 0.8;
}

.stat-card-info h4 {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.stat-card-info p {
    font-size: 1.8rem;
    font-weight: bold;
    margin: 0;
}

.card {
    background-color: var(--card-bg);
    border-color: var(--border-color);
    color: var(--card-text);
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.card-title {
    color: var(--card-text);
    margin-bottom: 0;
}

.theme-switch-container {
    padding: 10px;
    margin-top: auto;
}

@media (max-width: 768px) {
    .sidebar {
        width: 78px;
    }
    .sidebar.close {
        width: 0;
    }
    .home-section {
        left: 78px;
        width: calc(100% - 78px);
    }
    .sidebar.close ~ .home-section {
        left: 0;
        width: 100%;
    }
}
</style>
<script>
const themeToggle = document.getElementById('theme-toggle');
const html = document.documentElement;
const sidebar = document.querySelector('.sidebar');
const menuBtn = document.querySelector('.bx-menu');

// Check for saved theme preference
const savedTheme = localStorage.getItem('theme') || 'light';
html.setAttribute('data-theme', savedTheme);
themeToggle.checked = savedTheme === 'dark';

// Theme toggle handler
themeToggle.addEventListener('change', () => {
    const newTheme = themeToggle.checked ? 'dark' : 'light';
    html.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
});

// Sidebar toggle
menuBtn.addEventListener('click', () => {
    sidebar.classList.toggle('close');
    localStorage.setItem('sidebarState', sidebar.classList.contains('close') ? 'closed' : 'open');
});

// Check for saved sidebar state
const savedSidebarState = localStorage.getItem('sidebarState');
if (savedSidebarState === 'closed') {
    sidebar.classList.add('close');
}

// Handle responsive sidebar on page load and resize
function handleResponsiveSidebar() {
    if (window.innerWidth <= 768) {
        sidebar.classList.add('close');
    } else {
        if (savedSidebarState !== 'closed') {
            sidebar.classList.remove('close');
        }
    }
}

window.addEventListener('load', handleResponsiveSidebar);
window.addEventListener('resize', handleResponsiveSidebar);
</script>
</body>
</html>