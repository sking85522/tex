<?php include '../includes/header.php'; ?>

<section class="page-header" style="background: var(--bg-main); border-bottom: 1px solid var(--glass-border); padding: 100px 0 60px; text-align: center;">
    <div class="container">
        <h1 style="font-size: 2.5rem; margin-bottom: 24px;">Search  Archives</h1>
        <div class="glass-card" style="max-width: 600px; margin: 0 auto; padding: 10px; display: flex; gap: 10px;">
            <input type="text" placeholder="Search for projects, services, or  docs..." style="flex: 1; background: transparent; border: none; color: white; padding: 0 15px; outline: none;">
            <button class="btn btn-primary" style="padding: 12px 24px;">Search</button>
        </div>
    </div>
</section>

<section style="padding: 80px 0; min-height: 400px;">
    <div class="container text-center">
        <i class="fas fa-search" style="font-size: 4rem; color: var(--glass-border); margin-bottom: 24px;"></i>
        <h3 class="text-muted">No active search queries found.</h3>
        <p class="text-muted">Enter a keyword above to scan the Tech Elevate X intelligence grid.</p>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
