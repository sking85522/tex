<?php include 'includes/header.php';
include 'includes/db.php';

$msg = '';
$msg_class = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_job'])) {
    $job_id = $_POST['job_id'];
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $resume_url = $_POST['resume_url'] ?? ''; // Simulating file upload with a URL input for simplicity

    if (!empty($job_id) && !empty($name) && !empty($email) && !empty($resume_url)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO job_applications (job_id, name, email, phone, resume_url) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$job_id, $name, $email, $phone, $resume_url]);
            $msg = 'Application submitted successfully! Our HR team will contact you.';
            $msg_class = 'success';
        } catch(PDOException $e) {
            $msg = 'Failed to submit application. Please try again.';
            $msg_class = 'error';
        }
    } else {
        $msg = 'Please fill out all required fields.';
        $msg_class = 'error';
    }
}

$jobs = [];
try {
    $stmt = $pdo->query("SELECT * FROM jobs WHERE status = 'open' ORDER BY id DESC");
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) { }

?>

<section class="page-header" style="background-color: var(--primary-color); color: white; text-align: center; padding: 100px 0;">
    <div class="container">
        <h1 style="font-size: 3rem; margin-bottom: 20px;">Careers at Tech Elevate X</h1>
        <p style="font-size: 1.2rem;">Join our team of innovators and builders.</p>
    </div>
</section>

<section class="careers-page" style="padding: 60px 0; background-color: #f9f9f9;">
    <div class="container">
        <?php if($msg): ?>
        <div style="padding: 15px; margin-bottom: 20px; border-radius: 5px; background: <?php echo $msg_class === 'success' ? '#d4edda' : '#f8d7da'; ?>; color: <?php echo $msg_class === 'success' ? '#155724' : '#721c24'; ?>;">
            <?php echo htmlspecialchars($msg); ?>
        </div>
        <?php endif; ?>

        <h2 style="text-align: center; margin-bottom: 40px; font-size: 2.5rem;">Open Positions</h2>

        <div class="jobs-list" style="display: flex; flex-direction: column; gap: 20px; max-width: 800px; margin: 0 auto;">
            <?php if(empty($jobs)): ?>
                <p style="text-align:center; color:#666;">No open positions at the moment. Please check back later.</p>
            <?php else: foreach($jobs as $job): ?>
            <div class="job-card" style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border-left: 5px solid var(--primary-color);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; flex-wrap: wrap;">
                    <h3 style="margin: 0; font-size: 1.5rem; color: var(--dark-color);"><?php echo htmlspecialchars($job['title']); ?></h3>
                    <div style="display: flex; gap: 10px;">
                        <span style="background: #e9ecef; color: #495057; padding: 5px 10px; border-radius: 4px; font-size: 0.85rem; font-weight: bold;"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($job['location']); ?></span>
                        <span style="background: #e9ecef; color: #495057; padding: 5px 10px; border-radius: 4px; font-size: 0.85rem; font-weight: bold;"><i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($job['job_type']); ?></span>
                    </div>
                </div>
                <p style="color: #555; margin-bottom: 15px; line-height: 1.6;"><?php echo htmlspecialchars($job['description']); ?></p>
                <p style="color: #555; margin-bottom: 20px; line-height: 1.6;"><strong>Requirements:</strong> <?php echo htmlspecialchars($job['requirements']); ?></p>

                <button class="btn btn-outline" onclick="document.getElementById('apply-form-<?php echo $job['id']; ?>').style.display='block'" style="padding: 8px 15px; font-size: 0.9rem;">Apply Now</button>

                <!-- Hidden Apply Form -->
                <div id="apply-form-<?php echo $job['id']; ?>" style="display:none; margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                    <form action="careers.php" method="POST">
                        <input type="hidden" name="apply_job" value="1">
                        <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">

                        <div style="display:flex; gap: 15px; margin-bottom: 15px;">
                            <div style="flex:1;">
                                <label style="display:block; margin-bottom:5px;">Full Name *</label>
                                <input type="text" name="name" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
                            </div>
                            <div style="flex:1;">
                                <label style="display:block; margin-bottom:5px;">Email *</label>
                                <input type="email" name="email" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
                            </div>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="display:block; margin-bottom:5px;">Phone Number</label>
                            <input type="text" name="phone" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="display:block; margin-bottom:5px;">Resume URL / LinkedIn Profile *</label>
                            <input type="url" name="resume_url" required placeholder="https://..." style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
                        </div>
                        <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">Submit Application</button>
                        <button type="button" onclick="document.getElementById('apply-form-<?php echo $job['id']; ?>').style.display='none'" class="btn btn-outline" style="border:none; margin-left:10px;">Cancel</button>
                    </form>
                </div>

            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
