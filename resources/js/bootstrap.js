window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
//for Echo
import Echo from 'laravel-echo';
window.io = require('socket.io-client');

// 접속정보
window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: 'http://localhost:6001',
    // encrypted: false,
    // disableStats: false
});

// redis채널설정
window.Echo.channel('laravel_database_ccit')
    .listen('chartEvent', (e) => {
        console.log(e);
        
    });
