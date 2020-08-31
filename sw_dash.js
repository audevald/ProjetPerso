const STATIC_CACHE_DASH = [
    'offline_dash.html',
    'https://use.fontawesome.com/releases/v5.8.2/css/all.css',
    'css/bootstrap.min.css',
    'css/dashboard.css',
    'img/favicon-dash.ico',
    'js/jquery.min.js',
    'js/popper.min.js',
    'https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js',
    'js/bootstrap.min.js'

];

self.addEventListener("install", (event) => {
    console.log("service worker installed");
    self.skipWaiting();
    event.waitUntil(caches.open('dash_cache')
            .then(cache => cache.addAll(STATIC_CACHE_DASH))
            );
});

self.addEventListener('activate', (event) => {
    console.log('sw activé.');
});

self.addEventListener('fetch', function (event) {

    event.respondWith(
            caches.match(event.request).then(function (response) {
        return response || fetch(event.request);
    })
            .catch(function () {
                return caches.match('offline_dash.html');
            })
            );

});