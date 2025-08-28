import './bootstrap';

import Alpine from 'alpinejs';

// Handle Alpine initialization properly for both dev and production
if (typeof window.Alpine === 'undefined') {
    window.Alpine = Alpine;
    Alpine.start();
} else {
    // In development mode with HMR, we might need to restart Alpine
    if (import.meta.hot) {
        // This is Vite's way to detect development mode
        console.log('Alpine already loaded, skipping initialization');
    }
}
