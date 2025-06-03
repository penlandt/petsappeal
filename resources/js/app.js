import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Read session lifetime (in minutes) from backend — default to 120 if not passed
const sessionLifetimeMinutes = parseInt(document.body.dataset.sessionLifetime || 120);
const sessionLifetimeMs = sessionLifetimeMinutes * 60 * 1000;

// Start auto-logout timer
setTimeout(() => {
    console.warn('Session has likely expired. Redirecting to login.');
    window.location.href = '/login';
}, sessionLifetimeMs);