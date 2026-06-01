/**
 * Tech Elevate X -   Frontend Kernel
 * Handles: UI Interactions, Chat Logic, and  Flow
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Chatbot Logic
    const chatbotToggle = document.getElementById('chatbot-toggle-btn');
    const chatbotWindow = document.getElementById('chatbot-container');
    const chatbotClose = document.getElementById('chatbot-close-btn');
    const chatbotInput = document.getElementById('chatbot-input');
    const chatbotSendBtn = document.getElementById('chatbot-send-btn');
    const chatbotBody = document.getElementById('chatbot-body');

    if (chatbotToggle && chatbotWindow) {
        chatbotToggle.addEventListener('click', () => {
            chatbotWindow.classList.toggle('active');
        });
    }

    if (chatbotClose) {
        chatbotClose.addEventListener('click', () => {
            chatbotWindow.classList.remove('active');
        });
    }

    const sendMessage = async () => {
        const message = chatbotInput.value.trim();
        if (!message) return;

        appendMessage('user', message);
        chatbotInput.value = '';

        try {
            const formData = new FormData();
            formData.append('action', 'chat');
            formData.append('message', message);

            const apiPath = (window.rootPrefix || '') + '_api.php';
            const response = await fetch(apiPath, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            if (data.success) {
                appendMessage('bot', data.message);
            } else {
                appendMessage('bot', "Maaf kijiye, main abhi respond nahi kar paa raha hoon.");
            }
        } catch (error) {
            console.error(' Error:', error);
            appendMessage('bot', "System Error: Connectivity lost.");
        }
    };

    if (chatbotSendBtn) chatbotSendBtn.addEventListener('click', sendMessage);
    if (chatbotInput) {
        chatbotInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') sendMessage();
        });
    }

    function appendMessage(sender, text) {
        const msgDiv = document.createElement('div');
        msgDiv.classList.add('chat-message', sender);
        msgDiv.innerHTML = `<p>${text}</p>`;
        chatbotBody.appendChild(msgDiv);
        chatbotBody.scrollTop = chatbotBody.scrollHeight;
    }

    // 2. Mobile Menu
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');
    if (hamburger && navLinks) {
        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    }
});
