import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import './bootstrap';

// ✅ Make Pusher available globally
window.Pusher = Pusher;

// ✅ Initialize Laravel Echo with Pusher
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'eu',
    forceTLS: true,
});

// Debug logs (optional)
console.log('Pusher initialized with key:', import.meta.env.VITE_PUSHER_APP_KEY);
