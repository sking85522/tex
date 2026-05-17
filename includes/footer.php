</main>
    <!-- Main Content Area Ends -->

    <!-- Footer -->
    <footer class="footer">
        <div class="container footer-container">
            <div class="footer-col" data-aos="fade-up">
                <h3>Tech Elevate X</h3>
                <p>Empowering businesses through cutting-edge software and reliable IT solutions.</p>
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
                <h4>Our Services</h4>
                <ul style="padding: 0; list-style: none;">
                    <li><a href="services.php">Enterprise Solutions</a></li>
                    <li><a href="services.php">Web Ecosystems</a></li>
                    <li><a href="services.php">Mobile Apps</a></li>
                    <li><a href="services.php">Cloud Architecture</a></li>
                </ul>
            </div>
            <div class="footer-col" data-aos="fade-up" data-aos-delay="300">
                <h4>Contact Us</h4>
                <p style="color: var(--text-muted); margin-bottom: 10px;"><i class="fas fa-map-marker-alt" style="color: var(--primary);"></i> <?php echo htmlspecialchars(get_setting("contact_address", "123 Tech Street, IT Park, City")); ?></p>
                <p style="color: var(--text-muted); margin-bottom: 10px;"><i class="fas fa-phone" style="color: var(--primary);"></i> <?php echo htmlspecialchars(get_setting("contact_phone", "+1 234 567 8900")); ?></p>
                <p style="color: var(--text-muted);"><i class="fas fa-envelope" style="color: var(--primary);"></i> <?php echo htmlspecialchars(get_setting("contact_email", "info@techelevatex.com")); ?></p>
            </div>
        </div>
        <div class="container" style="margin-top: 60px; padding-top: 20px; border-top: 1px solid var(--glass-border); text-align: center; color: var(--text-muted); font-size: 0.9rem;">
            <p>&copy; <?php echo date("Y"); ?> Tech Elevate X. All rights reserved.</p>
        </div>
    </footer>

    <!-- Theme Switcher UI -->
    <div id="theme-switcher-panel" style="position: fixed; bottom: 80px; right: 20px; background: var(--bg-card); border: 1px solid var(--glass-border); padding: 20px; border-radius: 12px; box-shadow: var(--glass-shadow); z-index: 2000; display: none; width: 300px;">
        <h4 style="margin-top: 0; color: var(--text-main);">Theme Settings</h4>

        <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <span style="color: var(--text-muted); font-size: 0.9rem;">Mode</span>
            <button id="toggle-dark-mode" style="background: var(--bg-deep); border: 1px solid var(--glass-border); padding: 5px 10px; border-radius: 6px; cursor: pointer; color: var(--text-main);">Toggle Light/Dark</button>
        </div>

        <span style="color: var(--text-muted); font-size: 0.9rem; display: block; margin-bottom: 10px;">Select Color Theme (100+)</span>
        <div id="color-swatches" style="display: flex; flex-wrap: wrap; gap: 5px; max-height: 200px; overflow-y: auto;">
            <!-- Generated via JS -->
        </div>
    </div>
    <button id="theme-switcher-toggle" style="position: fixed; bottom: 20px; right: 20px; width: 50px; height: 50px; border-radius: 50%; background: var(--primary); color: #fff; border: none; cursor: pointer; box-shadow: var(--glass-shadow); z-index: 2001; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
        <i class="fas fa-palette"></i>
    </button>

    <!-- Support Chatbot -->
    <button id="chatbot-toggle" style="position: fixed; bottom: 80px; right: 20px; width: 50px; height: 50px; border-radius: 50%; background: var(--text-main); color: var(--bg-main); border: none; cursor: pointer; box-shadow: var(--glass-shadow); z-index: 2001; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
        <i class="fas fa-comments"></i>
    </button>

    <div id="chatbot-panel" style="position: fixed; bottom: 140px; right: 20px; width: 320px; height: 400px; background: var(--bg-card); border: 1px solid var(--glass-border); border-radius: 12px; box-shadow: var(--glass-shadow); z-index: 2000; display: none; flex-direction: column;">
        <div style="background: var(--primary); color: #fff; padding: 15px; border-radius: 12px 12px 0 0; font-weight: bold; display: flex; justify-content: space-between; align-items: center;">
            <span>Support Chatbot</span>
            <button id="chatbot-close" style="background: none; border: none; color: #fff; cursor: pointer;"><i class="fas fa-times"></i></button>
        </div>
        <div id="chatbot-messages" style="flex: 1; padding: 15px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px;">
            <div style="background: var(--bg-main); color: var(--text-main); padding: 10px 15px; border-radius: 12px 12px 12px 0; align-self: flex-start; max-width: 80%; font-size: 0.9rem;">
                Hello! / नमस्ते! I can help you with pricing, services, or careers.
            </div>
        </div>
        <div style="padding: 10px; border-top: 1px solid var(--glass-border); display: flex; gap: 5px;">
            <input type="text" id="chatbot-input" placeholder="Type a message..." style="flex: 1; padding: 8px 12px; border: 1px solid var(--glass-border); border-radius: 20px; outline: none; background: var(--bg-main); color: var(--text-main);">
            <button id="chatbot-send" style="background: var(--primary); color: #fff; border: none; border-radius: 50%; width: 36px; height: 36px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Theme Switcher Logic
            const panel = document.getElementById('theme-switcher-panel');
            const toggleBtn = document.getElementById('theme-switcher-toggle');
            const darkModeBtn = document.getElementById('toggle-dark-mode');
            const swatchesContainer = document.getElementById('color-swatches');

            // Toggle Panel
            toggleBtn.addEventListener('click', () => {
                panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
            });

            // Dark Mode Toggle
            darkModeBtn.addEventListener('click', () => {
                const currentTheme = document.documentElement.getAttribute('data-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                document.documentElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme_preference', newTheme);
            });

            // Generate 100 Themes (Hues)
            for (let i = 0; i < 100; i++) {
                const hue = Math.floor((i / 100) * 360);
                const color = `hsl(${hue}, 80%, 50%)`;
                const swatch = document.createElement('div');
                swatch.style.width = '20px';
                swatch.style.height = '20px';
                swatch.style.background = color;
                swatch.style.borderRadius = '4px';
                swatch.style.cursor = 'pointer';
                swatch.title = `Theme ${i+1}`;

                swatch.addEventListener('click', () => {
                    document.documentElement.style.setProperty('--primary', color);
                    document.documentElement.style.setProperty('--primary-glow', `hsla(${hue}, 80%, 50%, 0.5)`);
                    localStorage.setItem('theme_color_primary', color);
                    localStorage.setItem('theme_color_glow', `hsla(${hue}, 80%, 50%, 0.5)`);
                    sessionStorage.setItem('manual_theme_active', 'true');
                });

                swatchesContainer.appendChild(swatch);
            }

            // Dynamic flow: apply a random theme on EVERY load
            const randomHue = Math.floor(Math.random() * 360);
            const randomColor = `hsl(${randomHue}, 80%, 50%)`;
            const randomGlow = `hsla(${randomHue}, 80%, 50%, 0.5)`;

            // Allow manual override via theme panel, but if not set just now, use random
            if(sessionStorage.getItem('manual_theme_active') === 'true') {
                 document.documentElement.style.setProperty('--primary', localStorage.getItem('theme_color_primary'));
                 document.documentElement.style.setProperty('--primary-glow', localStorage.getItem('theme_color_glow'));
            } else {
                 document.documentElement.style.setProperty('--primary', randomColor);
                 document.documentElement.style.setProperty('--primary-glow', randomGlow);
            }

            // Chatbot Logic
            const chatToggle = document.getElementById('chatbot-toggle');
            const chatPanel = document.getElementById('chatbot-panel');
            const chatClose = document.getElementById('chatbot-close');
            const chatInput = document.getElementById('chatbot-input');
            const chatSend = document.getElementById('chatbot-send');
            const chatMessages = document.getElementById('chatbot-messages');

            chatToggle.addEventListener('click', () => chatPanel.style.display = 'flex');
            chatClose.addEventListener('click', () => chatPanel.style.display = 'none');

            async function sendMessage() {
                const text = chatInput.value.trim();
                if (!text) return;

                // Add user message
                const userMsg = document.createElement('div');
                userMsg.style = `background: var(--primary); color: #fff; padding: 10px 15px; border-radius: 12px 12px 0 12px; align-self: flex-end; max-width: 80%; font-size: 0.9rem;`;
                userMsg.textContent = text;
                chatMessages.appendChild(userMsg);
                chatInput.value = '';
                chatMessages.scrollTop = chatMessages.scrollHeight;

                try {
                    const response = await fetch('<?php echo $root_prefix; ?>api/chat.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ message: text })
                    });
                    const data = await response.json();

                    const botMsg = document.createElement('div');
                    botMsg.style = `background: var(--bg-main); color: var(--text-main); padding: 10px 15px; border-radius: 12px 12px 12px 0; align-self: flex-start; max-width: 80%; font-size: 0.9rem;`;

                    // Split the bilingual response by '/'
                    const parts = data.reply.split(' / ');
                    botMsg.innerHTML = parts[0] + (parts[1] ? `<br><span style="font-size: 0.8rem; color: var(--text-muted);">${parts[1]}</span>` : '');

                    chatMessages.appendChild(botMsg);
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                } catch (error) {
                    console.error('Chat error:', error);
                }
            }

            chatSend.addEventListener('click', sendMessage);
            chatInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') sendMessage();
            });
        });
    </script>

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
</body>
</html>
