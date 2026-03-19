<?php include 'includes/header.php'; ?>

<section class="page-header" style="background-color: var(--primary-color); color: white; text-align: center; padding: 100px 0;">
    <div class="container">
        <h1 style="font-size: 3rem; margin-bottom: 20px;">Our Portfolio</h1>
        <p style="font-size: 1.2rem;">Take a look at some of our recent successful projects.</p>
    </div>
</section>

<section class="portfolio-page">
    <div class="container">
        <div class="portfolio-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">

            <div class="portfolio-item" style="border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <img src="https://via.placeholder.com/400x300" alt="E-commerce App" style="width: 100%; height: auto; display: block;">
                <div class="portfolio-info" style="padding: 20px; background: white;">
                    <h3 style="margin-bottom: 10px; color: var(--dark-color);">E-Commerce Platform</h3>
                    <p style="color: #666; margin-bottom: 15px;">A full-featured online store with payment gateway integration and inventory management.</p>
                    <span style="display: inline-block; padding: 5px 10px; background: #eee; border-radius: 5px; font-size: 0.8rem; margin-right: 5px;">Web Dev</span>
                    <span style="display: inline-block; padding: 5px 10px; background: #eee; border-radius: 5px; font-size: 0.8rem;">PHP/MySQL</span>
                </div>
            </div>

            <div class="portfolio-item" style="border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <img src="https://via.placeholder.com/400x300" alt="Health App" style="width: 100%; height: auto; display: block;">
                <div class="portfolio-info" style="padding: 20px; background: white;">
                    <h3 style="margin-bottom: 10px; color: var(--dark-color);">Fitness Tracking App</h3>
                    <p style="color: #666; margin-bottom: 15px;">A mobile application for tracking workouts, nutrition, and connecting with personal trainers.</p>
                    <span style="display: inline-block; padding: 5px 10px; background: #eee; border-radius: 5px; font-size: 0.8rem; margin-right: 5px;">App Dev</span>
                    <span style="display: inline-block; padding: 5px 10px; background: #eee; border-radius: 5px; font-size: 0.8rem;">React Native</span>
                </div>
            </div>

            <div class="portfolio-item" style="border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <img src="https://via.placeholder.com/400x300" alt="Corporate Website" style="width: 100%; height: auto; display: block;">
                <div class="portfolio-info" style="padding: 20px; background: white;">
                    <h3 style="margin-bottom: 10px; color: var(--dark-color);">Corporate SaaS Dashboard</h3>
                    <p style="color: #666; margin-bottom: 15px;">An analytics dashboard for a financial firm to track real-time market data and client portfolios.</p>
                    <span style="display: inline-block; padding: 5px 10px; background: #eee; border-radius: 5px; font-size: 0.8rem; margin-right: 5px;">Software Dev</span>
                    <span style="display: inline-block; padding: 5px 10px; background: #eee; border-radius: 5px; font-size: 0.8rem;">Vue.js/Node.js</span>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
