<?php include 'includes/header.php'; ?>

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
                        <p style="color: #666;">123 Tech Street, IT Park, City</p>
                    </div>
                </div>

                <div style="margin-bottom: 20px; display: flex; align-items: center;">
                    <div style="width: 50px; height: 50px; background: rgba(13, 110, 253, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; color: var(--primary-color); font-size: 1.2rem;">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <h4 style="margin-bottom: 5px; color: var(--dark-color);">Phone</h4>
                        <p style="color: #666;">+1 234 567 8900</p>
                    </div>
                </div>

                <div style="margin-bottom: 20px; display: flex; align-items: center;">
                    <div style="width: 50px; height: 50px; background: rgba(13, 110, 253, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; color: var(--primary-color); font-size: 1.2rem;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h4 style="margin-bottom: 5px; color: var(--dark-color);">Email</h4>
                        <p style="color: #666;">info@techelevatex.com</p>
                    </div>
                </div>
            </div>

            <div class="contact-form-container" style="flex: 1.5; min-width: 300px; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
                <form action="#" method="POST">
                    <div style="display: flex; gap: 20px; margin-bottom: 20px; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 200px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Your Name</label>
                            <input type="text" name="name" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-family: inherit;">
                        </div>
                        <div style="flex: 1; min-width: 200px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Your Email</label>
                            <input type="email" name="email" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-family: inherit;">
                        </div>
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
