/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
import router from './router';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
window.axios.defaults.headers.common['Content-Type'] = 'application/json';
window.axios.defaults.headers.common['Accept'] = 'application/json';
// window.axios.defaults.headers.common['Access-Control-Allow-Origin'] = '*1';
// window.axios.defaults.timeout = 45 * 1000;
window.axios.interceptors.response.use(
    function (config) {
        // Do something before request is sent
        const token = window.localStorage.getItem('crmState.token') //do not store token on localstorage!!!
        config.headers.Authorization = 'Bearer '+token
        return config
    },
    function (error) {
        // Do something with request error
        // Requests.errorHandling(error)
        if (error?.response?.status === 401) {
            router.push('/login')
            return false
        }
        return Promise.reject(error)
    }
)

import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

import Requests from './requests'

const echoOptions = {
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    wsHost: window.location.hostname,
    wsPort: 6001,
    wssPort: 6001,
    forceTLS: import.meta.env.VITE_PUSHER_SCHEME === 'https',
    disableStats: import.meta.env.VITE_PUSHER_SCHEME === 'https',
    authEndpoint: '/api/broadcasting/auth',
    enabledTransports: [import.meta.env.VITE_PUSHER_SCHEME === 'https' ? 'wss' : 'ws'],
    encrypted: import.meta.env.VITE_PUSHER_SCHEME === 'https',
    auth: {
        headers: {
            Authorization: 'Bearer ' + Requests.token(),
            'X-CSRF-TOKEN': window.csrfToken
        },
    },
}

console.log(echoOptions)

window.Echo = new Echo(echoOptions);
