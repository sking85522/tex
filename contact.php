<?php include 'includes/header.php';
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_submit'])) {
    header('Content-Type: application/json');
    $name = trim($_POST['name'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $subject, $message]);
            echo json_encode(['success' => true, 'message' => 'Your message has been sent successfully!']);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to send message. Database error.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Please fill out all required fields.']);
    }
    exit();
}
?>


<section class="page-header" style="background-color: var(--primary-color); color: white; text-align: center; padding: 100px 0;">
    <div class="container">
        <h1 style="font-size: 3rem; margin-bottom: 20px;">Contact Us</h1>
        <p style="font-size: 1.2rem;">Have a project in mind? We'd love to hear from you.</p>
    </div>
</section>

<section class="contact-page">
    <div class="container">
        <div class="contact-wrapper" style="display: flex; gap: 50px; flex-wrap: wrap;">

            <div class="contact-info" style="flex: 1; min-width: 300px;">
                <h2 style="font-size: 2rem; margin-bottom: 20px; color: var(--dark-color);">Get In Touch</h2>
                <p style="margin-bottom: 30px; font-size: 1.1rem; color: #666;">Fill out the form and our team will get back to you within 24 hours.</p>

                <div style="margin-bottom: 20px; display: flex; align-items: center;">
                    <div style="width: 50px; height: 50px; background: rgba(13, 110, 253, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; color: var(--primary-color); font-size: 1.2rem;">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h4 style="margin-bottom: 5px; color: var(--dark-color);">Location</h4>
                        <p style="color: #666;"><?php echo htmlspecialchars(get_setting("contact_address", "123 Tech Street, IT Park, City")); ?></p>
                    </div>
                </div>

                <div style="margin-bottom: 20px; display: flex; align-items: center;">
                    <div style="width: 50px; height: 50px; background: rgba(13, 110, 253, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; color: var(--primary-color); font-size: 1.2rem;">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <h4 style="margin-bottom: 5px; color: var(--dark-color);">Phone</h4>
                        <p style="color: #666;"><?php echo htmlspecialchars(get_setting("contact_phone", "+1 234 567 8900")); ?></p>
                    </div>
                </div>

                <div style="margin-bottom: 20px; display: flex; align-items: center;">
                    <div style="width: 50px; height: 50px; background: rgba(13, 110, 253, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; color: var(--primary-color); font-size: 1.2rem;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h4 style="margin-bottom: 5px; color: var(--dark-color);">Email</h4>
                        <p style="color: #666;"><?php echo htmlspecialchars(get_setting("contact_email", "info@techelevatex.com")); ?></p>
                    </div>
                </div>
            </div>


            <div class="contact-form-container" style="flex: 1.5; min-width: 300px; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
                <div id="form-msg-box" style="display:none; padding: 15px; margin-bottom: 20px; border-radius: 5px;"></div>
                <form id="contactForm" onsubmit="submitContactForm(event)">
                    <input type="hidden" name="ajax_submit" value="1">
                    <div style="display: flex; gap: 20px; margin-bottom: 20px; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 200px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Your Name</label>
                            <input type="text" name="name" id="c_name" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-family: inherit;">
                        </div>
                        <div style="flex: 1; min-width: 200px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Your Email</label>
                            <input type="email" name="email" id="c_email" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-family: inherit;">
                        </div>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">Subject</label>
                        <input type="text" name="subject" id="c_subject" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-family: inherit;">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">Message</label>
                        <textarea name="message" id="c_message" rows="5" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-family: inherit; resize: vertical;"></textarea>
                    </div>
                    <button type="submit" id="c_submit_btn" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 1.1rem;">Send Message</button>
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
                        msgBox.style.background = "#d4edda";
                        msgBox.style.color = "#155724";
                        document.getElementById("contactForm").reset();
                    } else {
                        msgBox.style.background = "#f8d7da";
                        msgBox.style.color = "#721c24";
                    }
                } catch(error) {
                    msgBox.style.display = "block";
                    msgBox.style.background = "#f8d7da";
                    msgBox.style.color = "#721c24";
                    msgBox.innerHTML = "Failed to send message. Network error.";
                }
                btn.disabled = false;
                btn.innerHTML = "Send Message";
            }
            </script>

        </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">Subject</label>
                        <input type="text" name="subject" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-family: inherit;">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">Message</label>
                        <textarea name="message" rows="5" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-family: inherit; resize: vertical;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 1.1rem;">Send Message</button>
                </form>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
