    </main>
    <!-- Main Content Area Ends -->

    <!-- Footer -->
    <footer class="footer">
        <div class="container footer-container">
            <div class="footer-col">
                <h3>Tech Elevate X</h3>
                <p>Empowering businesses through cutting-edge software and web development solutions.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="services.php">Services</a></li>
                    <li><a href="portfolio.php">Portfolio</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Our Services</h4>
                <ul>
                    <li><a href="services.php">Web Development</a></li>
                    <li><a href="services.php">App Development</a></li>
                    <li><a href="services.php">Software Development</a></li>
                    <li><a href="services.php">UI/UX Design</a></li>
                    <li><a href="services.php">SEO & Marketing</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contact Info</h4>
                <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars(get_setting("contact_address", "123 Tech Street, IT Park, City")); ?>, Country</p>
                <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars(get_setting("contact_phone", "+1 234 567 8900")); ?></p>
                <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars(get_setting("contact_email", "info@techelevatex.com")); ?></p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date("Y"); ?> Tech Elevate X. All rights reserved.</p>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>

    <!-- Voice Navigation UI -->
    <div id="voice-nav-wrapper" style="position: fixed; bottom: 90px; right: 25px; z-index: 9999; display: flex; flex-direction: column; align-items: flex-end;">
        <div id="voice-nav-status" style="background: #333; color: white; padding: 5px 15px; border-radius: 20px; font-size: 0.8rem; margin-bottom: 10px; opacity: 0; transition: opacity 0.3s; font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">Listening...</div>
        <button id="voice-nav-btn" style="width: 60px; height: 60px; border-radius: 50%; background: #1cc88a; color: white; border: none; font-size: 1.5rem; cursor: pointer; box-shadow: 0 5px 15px rgba(28, 200, 138, 0.4); display: flex; align-items: center; justify-content: center; transition: 0.3s;" title="Voice Command Navigation">
            <i class="fas fa-microphone"></i>
        </button>
    </div>

    <script src="assets/js/voice_nav.js"></script>

<script src="assets/js/ai_nudge.js"></script>
</body>
</html>

    <!-- Chatbot Widget -->
    <div class="chatbot-container" id="chatbot-container">
        <div class="chatbot-header">
            <h4><i class="fas fa-robot"></i> Support Bot</h4>
            <button id="chatbot-close-btn"><i class="fas fa-times"></i></button>
        </div>
        <div class="chatbot-body" id="chatbot-body">
            <div class="chat-message bot">
                <p>Hello! I am the Tech Elevate X assistant. How can I help you today?</p>
            </div>
        </div>
        <div class="chatbot-footer">
            <input type="text" id="chatbot-input" placeholder="Type your message...">
            <button id="chatbot-send-btn"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>

    <button class="chatbot-toggle" id="chatbot-toggle-btn">
        <i class="fas fa-comments"></i>
    </button>
