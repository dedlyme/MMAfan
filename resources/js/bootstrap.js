import _ from 'lodash';
import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window._ = _;
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// ✅ Pusher konfigurācija no .env caur Vite
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'eu',
    forceTLS: true,
    encrypted: true,
    // 🚫 No debug logs, no console leaks
    disableStats: true,
    authorizer: (channel, options) => ({
        authorize: (socketId, callback) => {
            axios.post('/broadcasting/auth', {
                socket_id: socketId,
                channel_name: channel.name
            })
            .then(response => callback(false, response.data))
            .catch(error => callback(true, error));
        }
    }),
});

// 🔒 Remove public key logging
// console.log('Pusher initialized with key:', import.meta.env.VITE_PUSHER_APP_KEY);
