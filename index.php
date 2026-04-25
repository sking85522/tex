<?php 
require_once 'includes/header.php';

if (class_exists('Core\AIEngine')) {
    $ai = new \Core\AIEngine($pdo);
}
?>

<!-- Premium Hero Section -->
<main id="ai-dynamic-root">
    <section class="hero">
        <div class="container">
            <div class="hero-grid">
                <div class="hero-text" data-aos="fade-right">
                    <div class="hero-tag">
                        <i class="fas fa-sparkles"></i> 
                        <span>Independent HRITIK AI Core</span>
                    </div>
                    <h1 class="hero-title">
                        Enterprise Software <br>
                        <span class="text-gradient">Driven by Intelligence</span>
                    </h1>
                    <p class="hero-desc">
                        Tech Elevate X builds self-evolving software ecosystems and local-first AI models that automate business scaling without external API dependencies.
                    </p>
                    <div class="hero-actions">
                        <a href="javascript:void(0)" onclick="toggleConfigurator()" class="btn btn-primary">Start Project</a>
                        <a href="pages/services.php" class="btn btn-outline">Explore Solutions</a>
                    </div>
                </div>
                
                <div class="hero-visual" data-aos="zoom-in">
                    <!-- Neural Mesh Canvas -->
                    <div id="three-canvas-container"></div>
                    <div class="glass-card" style="position: absolute; bottom: 20px; left: 20px; padding: 20px; border-radius: 20px; max-width: 250px;">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                            <div style="width: 10px; height: 10px; background: var(--secondary); border-radius: 50%; box-shadow: 0 0 10px var(--secondary);"></div>
                            <span style="font-size: 0.75rem; font-weight: 700; color: var(--secondary);">HRITIK ONLINE</span>
                        </div>
                        <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4;">Neural pathways optimized for local inference.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Capabilities Discovery -->
    <section class="services" style="padding: 100px 0;">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2 class="font-heading">Neural Capabilities</h2>
                <p class="text-muted">Explore our suite of autonomous AI modules designed to integrate seamlessly into your enterprise grid.</p>
            </div>
            
            <div class="services-grid">
                <?php 
                try {
                    $stmt = $pdo->query("SELECT * FROM services LIMIT 6");
                    while($service = $stmt->fetch()): 
                ?>
                <div class="glass-card service-card" data-aos="fade-up">
                    <div class="service-icon">
                        <i class="<?php echo $service['icon_class']; ?>"></i>
                    </div>
                    <h3><?php echo $service['title']; ?></h3>
                    <p class="text-muted" style="margin: 16px 0 24px; font-size: 0.95rem;"><?php echo $service['description']; ?></p>
                    <a href="pages/services.php" style="color: var(--primary); font-weight: 600; display: flex; align-items: center; gap: 8px;">
                        Analyze Architecture <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <?php endwhile; } catch(Exception $e) {
                    // Fallback if DB is empty
                    echo '<p class="text-muted">Database sync in progress... System initializing.</p>';
                } ?>
            </div>
        </div>
    </section>

    <!-- Strategic Trust Grid -->
    <section class="trust-grid" style="padding: 80px 0; border-top: 1px solid var(--glass-border); background: rgba(15, 23, 42, 0.4);">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 40px; flex-wrap: wrap;">
                <div style="opacity: 0.6;">
                    <h4 style="font-size: 0.8rem; letter-spacing: 0.2em; color: var(--text-muted); margin-bottom: 16px;">AUTONOMOUS STACK</h4>
                    <div style="display: flex; gap: 32px; font-size: 1.2rem;">
                        <i class="fab fa-php"></i>
                        <i class="fab fa-js"></i>
                        <i class="fas fa-database"></i>
                        <i class="fas fa-network-wired"></i>
                    </div>
                </div>
                <div class="glass-card" style="padding: 16px 32px; border-radius: 100px; display: flex; gap: 32px; font-size: 0.85rem; font-weight: 600;">
                    <span><i class="fas fa-fingerprint" style="color: var(--primary);"></i> Zero API Leakage</span>
                    <span><i class="fas fa-bolt" style="color: var(--secondary);"></i> Sub-10ms Latency</span>
                    <span><i class="fas fa-shield-check" style="color: var(--accent);"></i> HRITIK Encrypted</span>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Three.js Visual Engine -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('three-canvas-container');
    if (!container || typeof THREE === 'undefined') return;

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
    
    renderer.setSize(container.clientWidth, container.clientHeight);
    container.appendChild(renderer.domElement);

    const geometry = new THREE.BufferGeometry();
    const particles = 1800;
    const positions = new Float32Array(particles * 3);
    for (let i = 0; i < particles * 3; i++) {
        positions[i] = (Math.random() - 0.5) * 12;
    }
    geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    const material = new THREE.PointsMaterial({ color: 0x6366f1, size: 0.04, transparent: true, opacity: 0.6 });
    const points = new THREE.Points(geometry, material);
    scene.add(points);

    camera.position.z = 5;

    function animate() {
        requestAnimationFrame(animate);
        points.rotation.y += 0.0012;
        points.rotation.x += 0.0006;
        renderer.render(scene, camera);
    }
    animate();

    window.addEventListener('resize', () => {
        camera.aspect = container.clientWidth / container.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(container.clientWidth, container.clientHeight);
    });
});
</script>

<?php include 'includes/footer.php'; ?>
