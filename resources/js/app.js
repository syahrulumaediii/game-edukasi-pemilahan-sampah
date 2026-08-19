import * as Turbo from '@hotwired/turbo';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Initialize Alpine.js
Alpine.start();

// Handle Alpine re-initialization if required by Turbo navigation
document.addEventListener('turbo:load', () => {
    // Turbo navigation finished
});
