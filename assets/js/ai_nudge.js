// Tech Elevate X - Smart AI Behavior Nudge
// This script monitors user activity and detects "exit intent" or "high interest"
// to deploy a personalized sales nudge.

let timeSpent = 0;
let nudgeShown = false;

// Determine page interest
let currentPage = window.location.pathname.split("/").pop();
let interestCategory = 'Software Services';

if (currentPage.includes('services')) interestCategory = 'IT Service Package';
if (currentPage.includes('portfolio')) interestCategory = 'Premium Custom Solution';
if (currentPage.includes('ai-solutions')) interestCategory = 'AI & Data Science Tool';

// Track time on page
setInterval(() => {
    timeSpent += 1;
}, 1000);

// Detect Exit Intent (when mouse moves quickly to top of browser)
document.addEventListener('mouseleave', function(e) {
    if (e.clientY < 0 && timeSpent > 5 && !nudgeShown) {
        showNudgePopup();
    }
});

// If they stay on a specific page for more than 45 seconds, they are highly interested
setTimeout(() => {
    if (!nudgeShown) showNudgePopup();
}, 45000);

function showNudgePopup() {
    nudgeShown = true;

    // Create popup HTML
    const popupHtml = `
    <div id="ai_sales_nudge" style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:99999; display:flex; align-items:center; justify-content:center; opacity:0; transition:opacity 0.3s;">
        <div style="background:white; padding:40px; border-radius:15px; max-width:500px; text-align:center; box-shadow:0 10px 40px rgba(0,0,0,0.3); transform:translateY(20px); transition:transform 0.3s;" id="nudge_box">
            <h2 style="color:#4e73df; font-size:2rem; margin-top:0; margin-bottom:15px;">Wait! Don't leave empty-handed.</h2>
            <p style="font-size:1.1rem; color:#555; margin-bottom:25px;">
                We noticed your interest in our <b>${interestCategory}</b>.<br><br>
                For a limited time, our AI system has generated a personalized offer for you. Get a <b>15% discount</b> if you start your project today!
            </p>
            <div style="display:flex; gap:15px; justify-content:center;">
                <a href="contact.php?offer=15percent&interest=${encodeURIComponent(interestCategory)}" class="btn btn-primary" style="padding:15px 30px; font-size:1.1rem; border-radius:30px; background:#1cc88a; border:none;">Claim 15% Off Now</a>
                <button onclick="closeNudge()" class="btn btn-outline" style="padding:15px 30px; font-size:1.1rem; border-radius:30px;">Maybe Later</button>
            </div>
            <p style="font-size:0.8rem; color:#999; margin-top:20px;">Powered by Tech Elevate X Predictive AI Engine</p>
        </div>
    </div>
    `;

    document.body.insertAdjacentHTML('beforeend', popupHtml);

    // Trigger animation
    setTimeout(() => {
        document.getElementById('ai_sales_nudge').style.opacity = '1';
        document.getElementById('nudge_box').style.transform = 'translateY(0)';
    }, 10);
}

function closeNudge() {
    const popup = document.getElementById('ai_sales_nudge');
    popup.style.opacity = '0';
    document.getElementById('nudge_box').style.transform = 'translateY(20px)';
    setTimeout(() => popup.remove(), 300);
}
