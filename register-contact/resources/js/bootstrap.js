import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Auto-attach JWT for all axios requests
window.axios.interceptors.request.use((config) => {
    try {
        const token = localStorage.getItem('access_token');
        console.log("b" + token);
        if (token) {
            config.headers = config.headers || {};
            config.headers['Authorization'] = `Bearer ${token}`;
        }
        config.headers = config.headers || {};
        config.headers['Accept'] = 'application/json';
    } catch (e) {
        // ignore
    }
    return config;
});
