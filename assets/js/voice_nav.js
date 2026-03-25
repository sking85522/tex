// Tech Elevate X - Voice Navigation (Speech Recognition API)
const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

if (SpeechRecognition) {
    const recognition = new SpeechRecognition();
    recognition.continuous = false;
    recognition.lang = 'en-US'; // Can understand Hinglish reasonably well
    recognition.interimResults = false;
    recognition.maxAlternatives = 1;

    let isListening = false;
    const micBtn = document.getElementById('voice-nav-btn');
    const micStatus = document.getElementById('voice-nav-status');

    if (micBtn) {
        micBtn.addEventListener('click', () => {
            if (isListening) {
                recognition.stop();
            } else {
                try {
                    recognition.start();
                    micBtn.style.animation = 'pulseMic 1.5s infinite';
                    micBtn.style.backgroundColor = '#e74a3b'; // Red when recording
                    micStatus.textContent = 'Listening...';
                    micStatus.style.opacity = '1';
                } catch(e) {
                    console.log("Mic already started or blocked.");
                }
            }
        });
    }

    recognition.onstart = function() {
        isListening = true;
    };

    recognition.onspeechend = function() {
        recognition.stop();
    };

    recognition.onerror = function(event) {
        console.error('Speech recognition error:', event.error);
        stopMicUI();
        micStatus.textContent = 'Mic Error. Try again.';
        setTimeout(() => micStatus.style.opacity = '0', 2000);
    };

    recognition.onresult = function(event) {
        const speechResult = event.results[0][0].transcript.toLowerCase();
        console.log('Voice Command Received: ', speechResult);
        micStatus.textContent = `Command: "${speechResult}"`;

        // Let the NLP routing function handle the logic
        setTimeout(() => {
            handleVoiceRouting(speechResult);
            stopMicUI();
        }, 1000);
    };

    function stopMicUI() {
        isListening = false;
        micBtn.style.animation = 'none';
        micBtn.style.backgroundColor = '#1cc88a'; // Back to green
        setTimeout(() => { micStatus.style.opacity = '0'; }, 2000);
    }
} else {
    console.warn("Speech Recognition API not supported in this browser.");
    // Hide the mic button if not supported
    window.addEventListener('load', () => {
        const btn = document.getElementById('voice-nav-wrapper');
        if(btn) btn.style.display = 'none';
    });
}

function handleVoiceRouting(phrase) {
    const p = phrase.toLowerCase();
    let url = null;

    // Home / Dashboard
    if (p.includes('home') || p.includes('wapas') || p.includes('start') || p.includes('dashboard')) {
        url = 'index.php';
    }
    // Services / Features
    else if (p.includes('service') || p.includes('features') || p.includes('kya kya') || p.includes('offer') || p.includes('mobile app') || p.includes('web dev')) {
        url = 'services.php';
    }
    // Portfolio / Work
    else if (p.includes('portfolio') || p.includes('work') || p.includes('demo') || p.includes('projects') || p.includes('dekhao') || p.includes('dikhaiye')) {
        url = 'portfolio.php';
    }
    // About Us
    else if (p.includes('about') || p.includes('kon ho') || p.includes('company') || p.includes('info')) {
        url = 'about.php';
    }
    // Contact / Deal
    else if (p.includes('contact') || p.includes('baat karni hai') || p.includes('call') || p.includes('help') || p.includes('message')) {
        url = 'contact.php';
    }
    // Careers / Jobs
    else if (p.includes('career') || p.includes('job') || p.includes('hire') || p.includes('apply') || p.includes('vacancy')) {
        url = 'careers.php';
    }
    // Login / Client Area
    else if (p.includes('login') || p.includes('sign in') || p.includes('mera account') || p.includes('panel')) {
        url = 'user/login.php';
    }
    // AI Solutions Demo
    else if (p.includes('ai') || p.includes('artificial intelligence') || p.includes('smart') || p.includes('demo') || p.includes('machine learning')) {
        url = 'ai-solutions.php';
    }

    if (url) {
        document.getElementById('voice-nav-status').textContent = 'Navigating...';
        document.getElementById('voice-nav-status').style.opacity = '1';
        setTimeout(() => {
            window.location.href = url;
        }, 1000);
    } else {
        document.getElementById('voice-nav-status').textContent = 'Command not understood.';
        document.getElementById('voice-nav-status').style.opacity = '1';
        setTimeout(() => {
            document.getElementById('voice-nav-status').style.opacity = '0';
        }, 2000);
    }
}

// Add animation css dynamically