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
        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        }
    }
}

window.toggleTheme = function () {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
};

initTheme();
Alpine.start();
