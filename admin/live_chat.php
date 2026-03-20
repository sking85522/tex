<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['admin_role'] ?? 'super_admin';
if (!in_array($role, ['super_admin', 'chat_support'])) {
    die("Access Denied. Only Chat Support staff or Super Admins can manage live chats.");
}

$sessions = [];
try {
    $stmt = $pdo->query("SELECT * FROM chat_sessions WHERE status = 'open' ORDER BY id DESC");
    $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) { }

$active_session = $_GET['session_id'] ?? ($sessions[0]['id'] ?? null);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['close_session'])) {
    $stmt = $pdo->prepare("UPDATE chat_sessions SET status = 'closed' WHERE id = ?");
    $stmt->execute([$_POST['session_id']]);
    header("Location: live_chat.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Live Chat - Tech Elevate X Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fc; color: #5a5c69; display: flex; height: 100vh; overflow: hidden; }
        .sidebar { width: 250px; background-color: #4e73df; color: white; display: flex; flex-direction: column; }
        .sidebar-brand { padding: 20px; text-align: center; font-size: 1.2rem; font-weight: bold; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-nav { flex-grow: 1; padding: 20px 0; overflow-y: auto; }
        .sidebar-nav a { display: block; padding: 15px 20px; color: rgba(255,255,255,0.8); text-decoration: none; transition: background 0.2s; }
        .sidebar-nav a:hover, .sidebar-nav a.active { background-color: rgba(255,255,255,0.1); color: white; border-left: 4px solid white; }
        .main-content { flex-grow: 1; display: flex; flex-direction: column; }
        .topbar { background-color: white; height: 70px; display: flex; justify-content: space-between; align-items: center; padding: 0 20px; box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15); }
        .chat-container { display: flex; height: calc(100vh - 70px); }
        .chat-sidebar { width: 300px; background: white; border-right: 1px solid #ddd; overflow-y: auto; }
        .session-item { padding: 15px; border-bottom: 1px solid #eee; cursor: pointer; transition: background 0.2s; }
        .session-item:hover, .session-item.active { background: #f8f9fc; border-left: 4px solid #4e73df; }
        .session-item strong { display: block; color: #333; }
        .session-item span { font-size: 0.8rem; color: #999; }

        .chat-main { flex-grow: 1; display: flex; flex-direction: column; background: #f8f9fc; }
        .chat-header { padding: 20px; background: white; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center; }
        .chat-messages { flex-grow: 1; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; }
        .message { max-width: 70%; padding: 12px 16px; border-radius: 20px; margin-bottom: 15px; line-height: 1.4; word-wrap: break-word; }
        .message.user { background: white; color: #333; align-self: flex-start; border-bottom-left-radius: 0; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
        .message.staff { background: #4e73df; color: white; align-self: flex-end; border-bottom-right-radius: 0; }

        .chat-input-area { padding: 20px; background: white; border-top: 1px solid #ddd; display: flex; gap: 10px; }
        .chat-input-area input { flex-grow: 1; padding: 12px 20px; border: 1px solid #ddd; border-radius: 30px; outline: none; }
        .chat-input-area button { background: #4e73df; color: white; border: none; width: 45px; height: 45px; border-radius: 50%; cursor: pointer; display: flex; justify-content: center; align-items: center; }
        .chat-input-area button:hover { background: #2e59d9; }

        /* Translate Widget styling inside admin */
        #google_translate_element { margin-left: 15px; }
    </style>
</head>
<body>

    <script type="text/javascript">
    function googleTranslateElementInit() {
      new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
    }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>


    <div class="sidebar">
        <div class="sidebar-brand"><i class="fas fa-rocket"></i> Admin Panel</div>
        <div class="sidebar-nav">
            <a href="index.php"><i class="fas fa-fw fa-tachometer-alt"></i> Dashboard</a>
            <a href="live_chat.php" class="active"><i class="fas fa-fw fa-comments"></i> Live Chat Support</a>
            <?php if($role == 'super_admin'): ?>
            <a href="settings.php"><i class="fas fa-fw fa-cogs"></i> CMS Settings</a>
            <a href="portfolio.php"><i class="fas fa-fw fa-briefcase"></i> Portfolio</a>
            <a href="careers.php"><i class="fas fa-fw fa-user-tie"></i> Careers / HR</a>
            <a href="staff.php"><i class="fas fa-fw fa-users-cog"></i> Staff Mgmt</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <div style="display:flex; align-items:center;">
                <i class="fas fa-bars"></i>
                <div id="google_translate_element"></div> <!-- Built-in translation for staff -->
            </div>
            <div><a href="index.php?logout=true" style="color:#e74a3b; text-decoration:none;"><i class="fas fa-sign-out-alt"></i> Logout</a></div>
        </div>

        <div class="chat-container">
            <!-- Active Sessions List -->
            <div class="chat-sidebar">
                <?php if(empty($sessions)): ?>
                    <p style="padding: 20px; color: #999; text-align: center;">No active chat sessions.</p>
                <?php else: foreach($sessions as $sess): ?>
                    <div class="session-item <?php echo ($sess['id'] == $active_session) ? 'active' : ''; ?>" onclick="window.location='live_chat.php?session_id=<?php echo $sess['id']; ?>'">
                        <strong><?php echo htmlspecialchars($sess['user_name']); ?> #<?php echo $sess['id']; ?></strong>
                        <span>Started: <?php echo date('H:i', strtotime($sess['created_at'])); ?></span>
                    </div>
                <?php endforeach; endif; ?>
            </div>

            <!-- Chat Area -->
            <div class="chat-main">
                <?php if($active_session): ?>
                    <div class="chat-header">
                        <h3 style="margin:0;">Chatting with Visitor #<?php echo $active_session; ?></h3>
                        <form action="live_chat.php" method="POST" onsubmit="return confirm('Close this chat session?');">
                            <input type="hidden" name="close_session" value="1">
                            <input type="hidden" name="session_id" value="<?php echo $active_session; ?>">
                            <button type="submit" style="background:#e74a3b; color:white; border:none; padding:8px 15px; border-radius:4px; cursor:pointer;">End Chat</button>
                        </form>
                    </div>

                    <div class="chat-messages" id="admin-chat-box">
                        <!-- Messages loaded via JS -->
                    </div>

                    <div class="chat-input-area">
                        <input type="text" id="admin-chat-input" placeholder="Type your message here..." onkeypress="if(event.key==='Enter') sendAdminMsg()">
                        <button onclick="sendAdminMsg()"><i class="fas fa-paper-plane"></i></button>
                    </div>
                <?php else: ?>
                    <div style="flex-grow: 1; display:flex; justify-content:center; align-items:center; color:#999; flex-direction:column;">
                        <i class="fas fa-comments" style="font-size:4rem; margin-bottom:20px; color:#ddd;"></i>
                        <p>Select a chat session from the left to start responding.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
    const activeSessionId = <?php echo $active_session ? $active_session : 'null'; ?>;
    let lastAdminMsgId = 0;

    function pollAdminChat() {
        if(!activeSessionId) return;
        fetch('../live_chat_api.php?action=poll&last_id=' + lastAdminMsgId + '&session_id=' + activeSessionId)
        .then(r => r.json())
        .then(data => {
            if(data.success && data.messages.length > 0) {
                const box = document.getElementById('admin-chat-box');
                data.messages.forEach(m => {
                    if(m.id > lastAdminMsgId) lastAdminMsgId = m.id;

                    const div = document.createElement('div');
                    div.className = 'message ' + m.sender_type;
                    div.textContent = m.message;
                    box.appendChild(div);
                });
                box.scrollTop = box.scrollHeight;
            }
        });
    }

    function sendAdminMsg() {
        const input = document.getElementById('admin-chat-input');
        const msg = input.value.trim();
        if(!msg || !activeSessionId) return;

        // Optimistic UI update
        const box = document.getElementById('admin-chat-box');
        const div = document.createElement('div');
        div.className = 'message staff';
        div.textContent = msg;
        box.appendChild(div);
        box.scrollTop = box.scrollHeight;
        input.value = '';

        fetch('../live_chat_api.php?action=send', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'message=' + encodeURIComponent(msg) + '&session_id=' + activeSessionId
        });
    }

    if(activeSessionId) {
        pollAdminChat();
        setInterval(pollAdminChat, 2000);
    }
    </script>
</body>
</html>
