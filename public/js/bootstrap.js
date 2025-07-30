// Axios now loaded via CDN - see layout files
// Global axios configuration
document.addEventListener('DOMContentLoaded', function() {
    if (window.axios) {
        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    }
});
