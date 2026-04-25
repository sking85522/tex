<?php
$admin_name = $_SESSION['admin_username'] ?? 'Admin';
$admin_role = $_SESSION['admin_role'] ?? 'super_admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Elevate X | AI Powered Admin</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body { font-family: 'Nunito', sans-serif; background-color: #f4f6f9; overflow-x: hidden; }

        /* Sidebar Styling (RentalRoom style) */
        .sidebar { position: fixed; top: 0; left: 0; height: 100vh; width: 250px; background-color: #343a40; z-index: 1000; transition: 0.3s; }
        .sidebar .brand { padding: 20px; text-align: center; color: white; font-size: 1.3rem; font-weight: bold; border-bottom: 1px solid #4f5962; }
        .sidebar-menu { padding: 15px 0; overflow-y: auto; height: calc(100vh - 70px); }
        .nav-item { margin-bottom: 5px; }
        .nav-link { color: #c2c7d0; padding: 12px 20px; display: flex; align-items: center; text-decoration: none; transition: 0.2s; }
        .nav-link i { margin-right: 15px; font-size: 1.1rem; width: 20px; text-align: center; }
        .nav-link:hover, .nav-link.active { background-color: rgba(255,255,255,0.1); color: white; border-left: 4px solid #0d6efd; }

        /* Main Content */
        .main-content { margin-left: 250px; transition: 0.3s; display: flex; flex-direction: column; min-height: 100vh; }

        /* Topbar */
        .topbar { background: white; height: 60px; padding: 0 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 4px rgba(0,0,0,0.08); z-index: 999; }
        .btn-toggle { background: transparent; border: none; font-size: 1.5rem; color: #6c757d; cursor: pointer; }

        /* User Dropdown */
        .user-dropdown { display: flex; align-items: center; gap: 10px; color: #555; text-decoration: none; font-weight: 600; cursor: pointer; }
        .user-dropdown img { width: 35px; height: 35px; border-radius: 50%; }

        /* Content Area */
        .content-wrapper { padding: 25px; flex-grow: 1; }

        /* Small Box Widgets */
        .small-box { border-radius: 8px; position: relative; display: block; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,.12), 0 1px 2px rgba(0,0,0,.24); overflow: hidden; }
        .small-box > .inner { padding: 20px; color: white; position: relative; z-index: 2; }
        .small-box h3 { font-size: 2.2rem; font-weight: 700; margin: 0 0 10px 0; }
        .small-box p { font-size: 1.1rem; margin: 0; }
        .small-box .icon { position: absolute; top: -10px; right: 15px; z-index: 1; font-size: 80px; color: rgba(0,0,0,0.15); transition: 0.3s; }
        .small-box:hover .icon { transform: scale(1.1); }
        .small-box-footer { display: block; padding: 10px; text-align: center; text-decoration: none; background: rgba(0,0,0,0.1); color: rgba(255,255,255,0.8); z-index: 2; position: relative; }
        .small-box-footer:hover { background: rgba(0,0,0,0.15); color: white; }

        @media (max-width: 768px) {
            .sidebar { left: -250px; }
            .sidebar.show { left: 0; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="brand">
            <i class="bi bi-robot text-primary"></i> TEX Admin
        </div>
        <div class="sidebar-menu">
            <div class="nav-item">
                <a href="index.php" class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </div>

            <h6 class="text-muted px-3 mt-3 mb-1" style="font-size:0.75rem; text-transform:uppercase;">CLIENTS & PROJECTS</h6>
            <?php if($admin_role == 'super_admin'): ?>
            <div class="nav-item">
                <a href="projects.php" class="nav-link <?php echo ($current_page == 'projects.php') ? 'active' : ''; ?>">
                    <i class="bi bi-kanban"></i> Live Projects
                </a>
            </div>
            <div class="nav-item">
                <a href="users.php" class="nav-link <?php echo ($current_page == 'users.php') ? 'active' : ''; ?>">
                    <i class="bi bi-people"></i> Registered Users
                </a>
            </div>
            <?php endif; ?>

            <h6 class="text-muted px-3 mt-3 mb-1" style="font-size:0.75rem; text-transform:uppercase;">AI & CONTENT</h6>
            <?php if($admin_role == 'super_admin' || $admin_role == 'chat_support'): ?>
            <div class="nav-item">
                <a href="ai_hub.php" class="nav-link <?php echo ($current_page == 'ai_hub.php') ? 'active' : ''; ?>">
                    <i class="bi bi-cpu"></i> AI Engine & Sales
                </a>
            </div>
            <div class="nav-item">
                <a href="chatbot.php" class="nav-link <?php echo ($current_page == 'chatbot.php') ? 'active' : ''; ?>">
                    <i class="bi bi-robot"></i> Train NLP Bot
                </a>
            </div>
            <div class="nav-item">
                <a href="tickets.php" class="nav-link <?php echo ($current_page == 'tickets.php') ? 'active' : ''; ?>">
                    <i class="bi bi-ticket-detailed"></i> Support Tickets
                </a>
            </div>
            <div class="nav-item">
                <a href="search.php" class="nav-link <?php echo ($current_page == 'search.php') ? 'active' : ''; ?>">
                    <i class="bi bi-search"></i> Advanced Search
                </a>
            </div>
            <?php endif; ?>

            <?php if($admin_role == 'super_admin'): ?>
            <h6 class="text-muted px-3 mt-3 mb-1" style="font-size:0.75rem; text-transform:uppercase;">MANAGEMENT</h6>
            <div class="nav-item">
                <a href="blog.php" class="nav-link <?php echo ($current_page == 'blog.php') ? 'active' : ''; ?>">
                    <i class="bi bi-journal-text"></i> Blogs & SEO
                </a>
            </div>
            <div class="nav-item">
                <a href="services.php" class="nav-link <?php echo ($current_page == 'services.php') ? 'active' : ''; ?>">
                    <i class="bi bi-gear"></i> IT Services (Pricing)
                </a>
            </div>
            <div class="nav-item">
                <a href="portfolio.php" class="nav-link <?php echo ($current_page == 'portfolio.php') ? 'active' : ''; ?>">
                    <i class="bi bi-briefcase"></i> Portfolio Projects
                </a>
            </div>
            <div class="nav-item">
                <a href="settings.php" class="nav-link <?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>">
                    <i class="bi bi-sliders"></i> CMS Settings
                </a>
            </div>
            <?php endif; ?>

            <?php if($admin_role == 'super_admin' || $admin_role == 'hr'): ?>
            <h6 class="text-muted px-3 mt-3 mb-1" style="font-size:0.75rem; text-transform:uppercase;">HR & STAFF</h6>
            <div class="nav-item">
                <a href="careers.php" class="nav-link <?php echo ($current_page == 'careers.php') ? 'active' : ''; ?>">
                    <i class="bi bi-person-workspace"></i> Job Applications
                </a>
            </div>
            <?php if($admin_role == 'super_admin'): ?>
            <div class="nav-item">
                <a href="staff.php" class="nav-link <?php echo ($current_page == 'staff.php') ? 'active' : ''; ?>">
                    <i class="bi bi-person-badge"></i> Staff Management
                </a>
            </div>
            <?php endif; endif; ?>

            <div style="height: 50px;"></div> <!-- Spacer -->
        </div>
    </div>

    <!-- Main Content wrapper -->
    <div class="main-content">

        <!-- Topbar -->
        <div class="topbar">
            <button class="btn-toggle" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="bi bi-list"></i>
            </button>

            <div class="dropdown">
                <div class="user-dropdown" data-bs-toggle="dropdown">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($admin_name); ?>&background=0d6efd&color=fff" alt="Admin">
                    <span><?php echo htmlspecialchars($admin_name); ?> <i class="bi bi-chevron-down small"></i></span>
                </div>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    <li><h6 class="dropdown-header text-uppercase">Role: <?php echo htmlspecialchars($admin_role); ?></h6></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="login.php?logout=true"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                </ul>
            </div>
        </div>

        <div class="content-wrapper">
