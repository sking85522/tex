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
