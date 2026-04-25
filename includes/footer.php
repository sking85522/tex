</main>
    <!-- Main Content Area Ends -->

    <!-- HRITIK Configuration System -->
    <?php include_once __DIR__ . '/configurator.php'; ?>

    <!-- Footer -->
    <footer class="footer">
        <div class="container footer-container">
            <div class="footer-col" data-aos="fade-up">
                <h3>Tech Elevate X</h3>
                <p>Empowering businesses through cutting-edge software and autonomous AI solutions.</p>
                <div class="social-links" style="display: flex; gap: 15px; margin-top: 20px;">
                    <a href="#" style="color: var(--text-muted);"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" style="color: var(--text-muted);"><i class="fab fa-twitter"></i></a>
                    <a href="#" style="color: var(--text-muted);"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" style="color: var(--text-muted);"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="footer-col" data-aos="fade-up" data-aos-delay="100">
                <h4>Platform</h4>
                <ul style="padding: 0; list-style: none;">
                    <li><a href="<?php echo $root_prefix; ?>index.php">Home</a></li>
                    <li><a href="<?php echo $root_prefix; ?>pages/about.php">About</a></li>
                    <li><a href="<?php echo $root_prefix; ?>pages/services.php">Solutions</a></li>
                    <li><a href="<?php echo $root_prefix; ?>pages/portfolio.php">Portfolio</a></li>
                </ul>
            </div>
            <div class="footer-col" data-aos="fade-up" data-aos-delay="200">
                <h4>Neural Services</h4>
                <ul style="padding: 0; list-style: none;">
                    <li><a href="services.php">AI Integration</a></li>
                    <li><a href="services.php">Web Ecosystems</a></li>
                    <li><a href="services.php">Mobile Apps</a></li>
                    <li><a href="services.php">Cloud Architecture</a></li>
                </ul>
            </div>
            <div class="footer-col" data-aos="fade-up" data-aos-delay="300">
                <h4>Contact Neural</h4>
                <p style="color: var(--text-muted); margin-bottom: 10px;"><i class="fas fa-map-marker-alt" style="color: var(--primary);"></i> <?php echo htmlspecialchars(get_setting("contact_address", "123 Tech Street, IT Park, City")); ?></p>
                <p style="color: var(--text-muted); margin-bottom: 10px;"><i class="fas fa-phone" style="color: var(--primary);"></i> <?php echo htmlspecialchars(get_setting("contact_phone", "+1 234 567 8900")); ?></p>
                <p style="color: var(--text-muted);"><i class="fas fa-envelope" style="color: var(--primary);"></i> <?php echo htmlspecialchars(get_setting("contact_email", "info@techelevatex.com")); ?></p>
            </div>
        </div>
        <div class="container" style="margin-top: 60px; padding-top: 20px; border-top: 1px solid var(--glass-border); text-align: center; color: var(--text-muted); font-size: 0.9rem;">
            <p>&copy; <?php echo date("Y"); ?> Tech Elevate X. Powered by <span class="text-gradient">HRITIK Core</span>. All rights reserved.</p>
        </div>
    </footer>

    <!-- HRITIK Global Intelligence Chat HUD -->
    <button class="chatbot-toggle" id="chatbot-toggle-btn" title="Speak with HRITIK">
        <i class="fas fa-robot"></i>
    </button>

    <div class="chatbot-window" id="chatbot-container">
        <div class="chatbot-header">
            <h4 style="color: white; margin: 0; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-microchip"></i> 
                <span>HRITIK Neural Hub</span>
            </h4>
            <button id="chatbot-close-btn" style="background: none; border: none; color: white; cursor: pointer; font-size: 1.2rem;"><i class="fas fa-times"></i></button>
        </div>
        <div class="chatbot-body" id="chatbot-body">
            <div class="message bot">
                <p>Namaste! Main **HRITIK** hoon—aapki software company ka independent AI core. Main aapki kaise madad kar sakta hoon?</p>
            </div>
        </div>
        <div class="chatbot-footer" style="padding: 20px; border-top: 1px solid var(--glass-border); background: rgba(15, 23, 42, 0.3);">
            <div class="chatbot-input-area" style="padding: 0; border: none;">
                <input type="file" id="chatbot-file" accept="image/*" style="display:none;">
                <button id="chatbot-upload-btn" title="Vision Analysis" style="background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); color: var(--text-muted); width: 44px; height: 44px; border-radius: 12px; cursor: pointer;"><i class="fas fa-image"></i></button>
                <input type="text" id="chatbot-input" placeholder="Type a message..." style="flex: 1; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); color: white; padding: 0 15px; border-radius: 12px; outline: none;">
                <button id="chatbot-send-btn" class="btn btn-primary" style="padding: 0; width: 44px; height: 44px; border-radius: 12px;">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- AOS - Animate On Scroll -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    </script>
    
    <script src="<?php echo $root_prefix; ?>assets/js/main.js"></script>
    <script src="<?php echo $root_prefix; ?>assets/js/modules/voice_kernel.js"></script>
</body>
</html>
