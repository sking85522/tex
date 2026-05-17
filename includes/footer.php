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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
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
                });

                swatchesContainer.appendChild(swatch);
            }

            // Load saved color
            const savedColor = localStorage.getItem('theme_color_primary');
            const savedGlow = localStorage.getItem('theme_color_glow');
            if (savedColor && savedGlow) {
                document.documentElement.style.setProperty('--primary', savedColor);
                document.documentElement.style.setProperty('--primary-glow', savedGlow);
            }
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
