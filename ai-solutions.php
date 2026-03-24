<?php
include 'includes/header.php';
include 'includes/db.php';
?>

<section class="page-header" style="background-color: #1c2331; color: white; text-align: center; padding: 100px 0;">
    <div class="container">
        <h1 style="font-size: 3.5rem; margin-bottom: 20px; background: -webkit-linear-gradient(#4e73df, #1cc88a); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">AI & Data Science Solutions</h1>
        <p style="font-size: 1.3rem; color: #d1d3e2;">Experience the power of SciPHP modules developed by Tech Elevate X.</p>
    </div>
</section>

<section class="ai-showcase" style="padding: 80px 0; background-color: #f8f9fc;">
    <div class="container">

        <div style="text-align:center; margin-bottom: 50px;">
            <h2 style="font-size: 2.5rem; color: #3a3b45;">Interactive AI Demos</h2>
            <p style="font-size: 1.1rem; color: #858796; max-width: 700px; margin: 0 auto;">
                We utilize advanced mathematical computation libraries (NumPHP, SciPHP, VisionPHP, SpeechPHP) to build next-generation enterprise software. Test our capabilities live below!
            </p>
        </div>

        <!-- VisionPHP Demo -->
        <div class="demo-card" style="background: white; border-radius: 15px; box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15); padding: 40px; margin-bottom: 40px; border-left: 6px solid #4e73df;">
            <div style="display:flex; align-items:center; gap:15px; margin-bottom:20px;">
                <i class="fas fa-eye" style="font-size: 2rem; color: #4e73df;"></i>
                <h3 style="margin:0; font-size:1.8rem; color:#3a3b45;">Computer Vision (Powered by VisionPHP)</h3>
            </div>
            <p style="color:#5a5c69; margin-bottom: 30px;">
                Our custom VisionPHP engine performs matrix-level image transformations, edge detection, and ML-ready pixel manipulation. Upload an image to see real-time matrix processing in action.
            </p>

            <div style="display:flex; flex-wrap:wrap; gap:30px;">
                <div style="flex:1; min-width:300px; background:#f8f9fc; padding:20px; border-radius:10px; border:2px dashed #d1d3e2; text-align:center;">
                    <i class="fas fa-cloud-upload-alt" style="font-size:3rem; color:#d1d3e2; margin-bottom:15px;"></i>
                    <p style="margin-bottom:15px;">Drag & Drop Image Here</p>
                    <input type="file" id="vision_upload" accept="image/*" style="display:none;" onchange="previewVisionImage(this)">
                    <button class="btn btn-primary" onclick="document.getElementById('vision_upload').click()">Browse Files</button>
                    <img id="vision_preview" style="display:none; max-width:100%; margin-top:20px; border-radius:8px;">
                </div>

                <div style="flex:1; min-width:300px; display:flex; flex-direction:column; justify-content:center; align-items:center; gap:15px;">
                    <div id="vision_controls" style="opacity: 0.5; pointer-events: none; text-align:center;">
                        <button class="btn btn-outline" style="margin-bottom:10px; width:200px;" onclick="applyVisionFilter('edge')"><i class="fas fa-border-style"></i> Run Edge Detection</button>
                        <button class="btn btn-outline" style="margin-bottom:10px; width:200px;" onclick="applyVisionFilter('blur')"><i class="fas fa-tint"></i> Gaussian Blur Matrix</button>
                        <button class="btn btn-outline" style="margin-bottom:10px; width:200px;" onclick="applyVisionFilter('gray')"><i class="fas fa-adjust"></i> Grayscale Tensor</button>

                        <div id="vision_loading" style="display:none; margin-top:20px; color:#4e73df;">
                            <i class="fas fa-spinner fa-spin" style="font-size:2rem;"></i><br><small>Processing Math Tensors...</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SpeechPHP Demo -->
        <div class="demo-card" style="background: white; border-radius: 15px; box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15); padding: 40px; margin-bottom: 40px; border-left: 6px solid #1cc88a;">
            <div style="display:flex; align-items:center; gap:15px; margin-bottom:20px;">
                <i class="fas fa-microphone-alt" style="font-size: 2rem; color: #1cc88a;"></i>
                <h3 style="margin:0; font-size:1.8rem; color:#3a3b45;">Audio Analytics (Powered by SpeechPHP)</h3>
            </div>
            <p style="color:#5a5c69; margin-bottom: 30px;">
                SpeechPHP performs Fast Fourier Transforms (FFT) and spectrum analysis on raw audio waveforms to enable ML voice recognition systems.
            </p>

            <div style="background:#1c2331; padding:30px; border-radius:10px; color:white; text-align:center; position:relative; overflow:hidden;">
                <!-- Simulated SpeechPHP Waveform Visualization -->
                <svg viewBox="0 0 800 100" style="width:100%; height:100px; stroke:#1cc88a; stroke-width:2; fill:none; stroke-linecap:round; stroke-linejoin:round;">
                    <path id="waveform_path" d="M0,50 Q20,30 40,50 T80,50 T120,50 T160,50 T200,50 T240,50 T280,50 T320,50 T360,50 T400,50 T440,50 T480,50 T520,50 T560,50 T600,50 T640,50 T680,50 T720,50 T760,50 T800,50"></path>
                </svg>

                <div style="margin-top:20px; display:flex; justify-content:center; gap:20px;">
                    <button class="btn btn-primary" style="background:#1cc88a; border-color:#1cc88a;" onclick="simulateSpeechFFT()"><i class="fas fa-play"></i> Generate Sine Wave & Analyze FFT</button>
                </div>
                <div id="fft_result" style="display:none; margin-top:20px; font-family:monospace; background:rgba(0,0,0,0.5); padding:15px; border-radius:5px; font-size:0.9rem; text-align:left;">
                    > Initializing SpeechPHP\WaveGenerator...<br>
                    > Performing Fast Fourier Transform...<br>
                    > Peak Frequency detected: 440Hz (A4 Note)<br>
                    > Status: Ready for ML Model Input.
                </div>
            </div>
        </div>

        <!-- Call to action block -->
        <div style="text-align:center; margin-top: 60px; background: linear-gradient(135deg, #4e73df, #2e59d9); padding: 50px; border-radius: 15px; color: white; box-shadow: 0 10px 30px rgba(78, 115, 223, 0.3);">
            <h2 style="font-size: 2.5rem; margin-bottom: 20px;">Ready to integrate AI into your business?</h2>
            <p style="font-size: 1.2rem; margin-bottom: 30px; opacity: 0.9;">Our team can build custom NLP, Vision, and Analytics software tailored to your workflow.</p>
            <a href="contact.php?service=AI Solutions" class="btn btn-outline" style="background:white; color:#4e73df; font-weight:bold; padding:15px 40px; font-size:1.1rem; border-radius:30px;">Get a Custom AI Quote</a>
        </div>

    </div>
</section>

<script>
// Mock VisionPHP processing logic for frontend demo interactivity
function previewVisionImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('vision_preview').src = e.target.result;
            document.getElementById('vision_preview').style.display = 'block';
            document.getElementById('vision_controls').style.opacity = '1';
            document.getElementById('vision_controls').style.pointerEvents = 'auto';
            // Reset filters
            document.getElementById('vision_preview').style.filter = 'none';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function applyVisionFilter(type) {
    const img = document.getElementById('vision_preview');
    const loading = document.getElementById('vision_loading');

    // Show "Processing"
    loading.style.display = 'block';
    img.style.opacity = '0.3';

    // Simulate backend SciPHP matrix operations taking time
    setTimeout(() => {
        loading.style.display = 'none';
        img.style.opacity = '1';

        if (type === 'edge') {
            img.style.filter = 'contrast(200%) grayscale(100%) invert(100%)';
        } else if (type === 'blur') {
            img.style.filter = 'blur(5px)';
        } else if (type === 'gray') {
            img.style.filter = 'grayscale(100%)';
        }
    }, 1500);
}

function simulateSpeechFFT() {
    const path = document.getElementById('waveform_path');
    const result = document.getElementById('fft_result');

    // Animate SVG path to look like an audio wave
    let animatedD = "M0,50 ";
    for(let i=20; i<=800; i+=20) {
        let randY = Math.floor(Math.random() * 80) + 10;
        animatedD += `T${i},${randY} `;
    }
    path.setAttribute('d', animatedD);

    setTimeout(() => {
        result.style.display = 'block';
    }, 1000);
}
</script>

<?php include 'includes/footer.php'; ?>
