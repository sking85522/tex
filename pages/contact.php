<?php include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_submit'])) {
    header('Content-Type: application/json');
    $name = trim($_POST['name'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        $messagesFile = __DIR__ . '/../data/messages.json';
        $messages = file_exists($messagesFile) ? json_decode(file_get_contents($messagesFile), true) : [];
        if (!is_array($messages)) $messages = [];

        $messages[] = [
            'date' => date('Y-m-d H:i:s'),
            'name' => $name,
            'email' => $email,
            'subject' => $subject,
            'message' => $message
        ];

        file_put_contents($messagesFile, json_encode($messages, JSON_PRETTY_PRINT));
        echo json_encode(['success' => true, 'message' => 'Your message has been sent successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Please fill out all required fields.']);
    }
    exit();
}
?>

<section class="page-header hero" style="padding: 160px 0 80px; text-align: center; border-bottom: 1px solid var(--glass-border);">
    <div class="container hero-content">
        <h1 class="hero-title">Establish <span class="text-gradient-primary">Connection</span></h1>
        <p class="hero-desc">Initialize a secure channel with our engineering team to architect your next venture.</p>
    </div>
</section>

<section class="contact-page" style="padding: 100px 0;">
    <div class="container">
        <div class="contact-wrapper" style="display: flex; gap: 60px; flex-wrap: wrap;">

            <div class="contact-info" style="flex: 1; min-width: 300px;" data-aos="fade-right">
                <h2 class="font-heading" style="font-size: 2.5rem; margin-bottom: 24px; color: var(--text-main);">Communication Portal</h2>
                <p style="margin-bottom: 40px; font-size: 1.1rem; color: var(--text-muted);">Transmit your requirements and our technical architects will respond within 24 hours.</p>

                <div class="glass-card" style="margin-bottom: 24px; display: flex; align-items: center; padding: 20px;">
                    <div style="width: 48px; height: 48px; background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 20px; color: var(--primary); font-size: 1.2rem;">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h4 style="margin-bottom: 4px; color: var(--text-main); font-weight: 600;">Headquarters</h4>
                        <p style="color: var(--text-muted); font-size: 0.95rem;"><?php echo htmlspecialchars(get_setting("contact_address", "123 Tech Street, IT Park, City")); ?></p>
                    </div>
                </div>

                <div class="glass-card" style="margin-bottom: 24px; display: flex; align-items: center; padding: 20px;">
                    <div style="width: 48px; height: 48px; background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 20px; color: var(--primary); font-size: 1.2rem;">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div>
                        <h4 style="margin-bottom: 4px; color: var(--text-main); font-weight: 600;">Voice Channel</h4>
                        <p style="color: var(--text-muted); font-size: 0.95rem;"><?php echo htmlspecialchars(get_setting("contact_phone", "+1 234 567 8900")); ?></p>
                    </div>
                </div>

                <div class="glass-card" style="margin-bottom: 24px; display: flex; align-items: center; padding: 20px;">
                    <div style="width: 48px; height: 48px; background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 20px; color: var(--primary); font-size: 1.2rem;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h4 style="margin-bottom: 4px; color: var(--text-main); font-weight: 600;">Data Channel</h4>
                        <p style="color: var(--text-muted); font-size: 0.95rem;"><?php echo htmlspecialchars(get_setting("contact_email", "info@techelevatex.com")); ?></p>
                    </div>
                </div>
            </div>


            <div class="contact-form-container glass-card" style="flex: 1.5; min-width: 300px; padding: 40px;" data-aos="fade-left">
                <div id="form-msg-box" style="display:none; padding: 16px; margin-bottom: 24px; border-radius: 8px;"></div>
                <form id="contactForm" onsubmit="submitContactForm(event)">
                    <input type="hidden" name="ajax_submit" value="1">
                    <div style="display: flex; gap: 24px; margin-bottom: 24px; flex-wrap: wrap;">
                        <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
                            <label for="c_name">Identifier</label>
                            <input type="text" name="name" id="c_name" required>
                        </div>
                        <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
                            <label for="c_email">Return Address</label>
                            <input type="email" name="email" id="c_email" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="c_subject">Transmission Subject</label>
                        <input type="text" name="subject" id="c_subject" required>
                    </div>
                    <div class="form-group">
                        <label for="c_message">Payload Data</label>
                        <textarea name="message" id="c_message" rows="5" required></textarea>
                    </div>
                    <button type="submit" id="c_submit_btn" class="btn btn-primary" style="width: 100%;"><i class="fas fa-paper-plane" style="margin-right: 8px;"></i> Transmit Data</button>
                </form>
            </div>

            <script>
            async function submitContactForm(e) {
                e.preventDefault();
                const btn = document.getElementById("c_submit_btn");
                const msgBox = document.getElementById("form-msg-box");
                btn.disabled = true;
                btn.innerHTML = "Sending...";
                msgBox.style.display = "none";

                let formData = new URLSearchParams();
                formData.append("ajax_submit", "1");
                formData.append("name", document.getElementById("c_name").value);
                formData.append("email", document.getElementById("c_email").value);
                formData.append("subject", document.getElementById("c_subject").value);
                formData.append("message", document.getElementById("c_message").value);

                try {
                    const response = await fetch("contact.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: formData.toString()
                    });
                    const result = await response.json();

                    msgBox.style.display = "block";
                    msgBox.innerHTML = result.message;
                    if(result.success) {
                        msgBox.style.background = "rgba(16, 185, 129, 0.2)";
                        msgBox.style.color = "#10b981";
                        msgBox.style.border = "1px solid rgba(16, 185, 129, 0.3)";
                        document.getElementById("contactForm").reset();
                    } else {
                        msgBox.style.background = "rgba(239, 68, 68, 0.2)";
                        msgBox.style.color = "#ef4444";
                        msgBox.style.border = "1px solid rgba(239, 68, 68, 0.3)";
                    }
                    msgBox.style.display = "block";
                    msgBox.style.background = "rgba(239, 68, 68, 0.2)";
                    msgBox.style.color = "#ef4444";
                    msgBox.innerHTML = "Failed to send message. Network error.";
                }
                btn.disabled = false;
                btn.innerHTML = "Send Message";
            }
            </script>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
