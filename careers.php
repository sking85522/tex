<?php include 'includes/header.php';
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_submit'])) {
    header('Content-Type: application/json');
    $job_id = $_POST['job_id'] ?? '';
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $resume_url = trim($_POST['resume_url'] ?? '');

    if (!empty($job_id) && !empty($name) && !empty($email) && !empty($resume_url)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO job_applications (job_id, name, email, phone, resume_url) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$job_id, $name, $email, $phone, $resume_url]);
            echo json_encode(['success' => true, 'message' => 'Application submitted successfully! Our HR team will contact you.']);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to submit application. Database error.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Please fill out all required fields.']);
    }
    exit();
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
                    <div id="msg-box-<?php echo $job['id']; ?>" style="display:none; padding: 15px; margin-bottom: 20px; border-radius: 5px;"></div>
                    <form id="form-<?php echo $job['id']; ?>" onsubmit="submitJobForm(event, <?php echo $job['id']; ?>)">
                        <input type="hidden" id="job_id_<?php echo $job['id']; ?>" value="<?php echo $job['id']; ?>">

                        <div style="display:flex; gap: 15px; margin-bottom: 15px;">
                            <div style="flex:1;">
                                <label style="display:block; margin-bottom:5px;">Full Name *</label>
                                <input type="text" id="name_<?php echo $job['id']; ?>" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
                            </div>
                            <div style="flex:1;">
                                <label style="display:block; margin-bottom:5px;">Email *</label>
                                <input type="email" id="email_<?php echo $job['id']; ?>" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
                            </div>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="display:block; margin-bottom:5px;">Phone Number</label>
                            <input type="text" id="phone_<?php echo $job['id']; ?>" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="display:block; margin-bottom:5px;">Resume URL / LinkedIn Profile *</label>
                            <input type="url" id="resume_<?php echo $job['id']; ?>" required placeholder="https://..." style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
                        </div>
                        <button type="submit" id="btn_<?php echo $job['id']; ?>" class="btn btn-primary" style="padding: 10px 20px;">Submit Application</button>
                        <button type="button" onclick="document.getElementById('apply-form-<?php echo $job['id']; ?>').style.display='none'" class="btn btn-outline" style="border:none; margin-left:10px;">Cancel</button>
                    </form>
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


<script>
async function submitJobForm(e, jobId) {
    e.preventDefault();
    const btn = document.getElementById("btn_" + jobId);
    const msgBox = document.getElementById("msg-box-" + jobId);
    btn.disabled = true;
    btn.innerHTML = "Submitting...";
    msgBox.style.display = "none";

    let formData = new URLSearchParams();
    formData.append("ajax_submit", "1");
    formData.append("job_id", document.getElementById("job_id_" + jobId).value);
    formData.append("name", document.getElementById("name_" + jobId).value);
    formData.append("email", document.getElementById("email_" + jobId).value);
    formData.append("phone", document.getElementById("phone_" + jobId).value);
    formData.append("resume_url", document.getElementById("resume_" + jobId).value);

    try {
        const response = await fetch("careers.php", {
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
            document.getElementById("form-" + jobId).reset();
            setTimeout(() => { document.getElementById('apply-form-' + jobId).style.display = 'none'; msgBox.style.display = 'none'; }, 3000);
        } else {
            msgBox.style.background = "#f8d7da";
            msgBox.style.color = "#721c24";
        }
    } catch(error) {
        msgBox.style.display = "block";
        msgBox.style.background = "#f8d7da";
        msgBox.style.color = "#721c24";
        msgBox.innerHTML = "Network error occurred.";
    }
    btn.disabled = false;
    btn.innerHTML = "Submit Application";
}
</script>
<?php include 'includes/footer.php'; ?>
