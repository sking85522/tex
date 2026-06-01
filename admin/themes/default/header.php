<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h(APP_NAME) ?> - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                    }
                }
            }
        }
    </script>
</head>
<!-- Defaulting to Dark Mode for SaaS AI Vibe -->
<body class="bg-gray-900 font-sans leading-normal tracking-normal flex transition-colors duration-300 dark" x-data="{ darkMode: localStorage.getItem('darkMode') !== 'false' }" :class="{ 'dark': darkMode }">
    <style>
        /* Modern SaaS Dark Mode Overrides */
        body { background-color: #000000 !important; color: #f3f4f6; }
        .dark .bg-white { background-color: rgba(15, 15, 15, 0.7) !important; backdrop-filter: blur(12px); border-color: rgba(255,255,255,0.1); color: #f3f4f6; box-shadow: 0 4px 30px rgba(0,0,0,0.5); }
        .dark .bg-gray-100 { background-color: #0a0a0a !important; }
        .dark .bg-gray-200 { background-color: #0a0a0a !important; }
        .dark .text-gray-900 { color: #ffffff !important; }
        .dark .text-gray-700 { color: #d1d5db !important; }
        .dark .text-gray-600 { color: #9ca3af !important; }
        .dark .text-gray-500 { color: #6b7280 !important; }
        .dark input, .dark textarea, .dark select { background-color: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: white; border-radius: 8px;}
        .dark input:focus, .dark textarea:focus { border-color: #3b82f6; box-shadow: 0 0 0 1px #3b82f6; }
        .dark table td, .dark table th { border-color: rgba(255,255,255,0.05); }
        .dark .bg-gray-50 { background-color: rgba(255,255,255,0.02) !important; }
        .dark .divide-gray-200 > :not([hidden]) ~ :not([hidden]) { border-color: rgba(255,255,255,0.05); }
        .dark .shadow { box-shadow: 0 10px 40px -10px rgba(0,0,0,0.8); }

        /* Glassmorphism Sidebar & Navbar */
        .dark #sidebar { background-color: rgba(0,0,0,0.9) !important; border-right: 1px solid rgba(255,255,255,0.1); }
        .dark .navbar-glass { background-color: rgba(0,0,0,0.8) !important; border-bottom: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(16px); }
    </style>

    <?php if (Auth::check()): ?>
        <?php include __DIR__ . '/sidebar.php'; ?>
    <?php endif; ?>

    <div class="main-content flex-1 flex flex-col h-screen overflow-hidden">

        <?php if (Auth::check()): ?>
            <?php include __DIR__ . '/navbar.php'; ?>
        <?php endif; ?>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
            <div class="container mx-auto px-6 py-8">
                <?php
                $flash = Session::getFlash('success');
                if ($flash):
                ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?= h($flash) ?></span>
                    </div>
                <?php endif; ?>

                <?php
                $flashError = Session::getFlash('error');
                if ($flashError):
                ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?= h($flashError) ?></span>
                    </div>
                <?php endif; ?>
