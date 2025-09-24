import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Laravel Echo + Pusher setup
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,        // from .env
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER, // from .env
    forceTLS: true,   // hosted Pusher requires TLS
    encrypted: true
});
