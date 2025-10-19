import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

/**
 * Initialize theme from localStorage (runs before Alpine starts)
 */
function initTheme() {
    const storedTheme = localStorage.getItem('theme');

    if (storedTheme === 'dark') {
        document.documentElement.classList.add('dark');
    } else if (storedTheme === 'light') {
        document.documentElement.classList.remove('dark');
    } else {
        // If no explicit preference, follow system preference
        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        }
    }
}

/**
 * Expose theme methods globally (used by Navbar)
 */
window.toggleTheme = function () {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
};

// Run theme init immediately
initTheme();

// Start Alpine.js
Alpine.start();
