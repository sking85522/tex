// Simple script for mobile menu toggling
document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');

    if(hamburger) {
        hamburger.addEventListener('click', () => {
            // Note: In a full app, you'd toggle a class to show/hide the menu properly
            alert('Mobile menu toggled! Add proper styling in CSS to show/hide.');
        });
    }
});

// Chatbot Functionality
document.addEventListener('DOMContentLoaded', () => {
    const chatbotToggleBtn = document.getElementById('chatbot-toggle-btn');
    const chatbotContainer = document.getElementById('chatbot-container');
    const chatbotCloseBtn = document.getElementById('chatbot-close-btn');
    const chatbotInput = document.getElementById('chatbot-input');
    const chatbotSendBtn = document.getElementById('chatbot-send-btn');
    const chatbotBody = document.getElementById('chatbot-body');

    // Toggle Chatbot
    if(chatbotToggleBtn) {
        chatbotToggleBtn.addEventListener('click', () => {
            chatbotContainer.classList.add('active');
            chatbotToggleBtn.style.display = 'none';
        });
    }

    if(chatbotCloseBtn) {
        chatbotCloseBtn.addEventListener('click', () => {
            chatbotContainer.classList.remove('active');
            chatbotToggleBtn.style.display = 'block';
        });
    }

    // Send Message Logic
    const sendMessage = async () => {
        if(!chatbotInput) return;
        const message = chatbotInput.value.trim();
        if (message === '') return;

        // Add user message to UI
        appendMessage('user', message);
        chatbotInput.value = '';

        try {
            // Fetch response from API
            const formData = new FormData();
            formData.append('message', message);

            const response = await fetch('chatbot_api.php', {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                const data = await response.json();
                appendMessage('bot', data.reply);
            } else {
                appendMessage('bot', "I'm having trouble connecting right now.");
            }
        } catch (error) {
            appendMessage('bot', "An error occurred. Please try again later.");
        }
    };

    if(chatbotSendBtn) {
        chatbotSendBtn.addEventListener('click', sendMessage);
    }

    if(chatbotInput) {
        chatbotInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    }

    // Append Message helper
    function appendMessage(sender, text) {
        if(!chatbotBody) return;
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('chat-message', sender);
        messageDiv.innerHTML = `<p>${text}</p>`;
        chatbotBody.appendChild(messageDiv);
        // Scroll to bottom
        chatbotBody.scrollTop = chatbotBody.scrollHeight;
    }
});
