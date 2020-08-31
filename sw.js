const STATIC_CACHE_FORM = [
    'offline.html',
    'https://use.fontawesome.com/releases/v5.8.2/css/all.css',
    'css/formulaire.css',
    'css/style.css',
    'img/favicon-form.ico',
    'js/jquery.min.js',
    'js/popper.min.js',
    'https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js',
    'js/bootstrap.min.js'

];

self.addEventListener("install", (event) => {
    console.log("service worker installed");
    self.skipWaiting();
    event.waitUntil(caches.open('dash_cache')
            .then(cache => cache.addAll(STATIC_CACHE_FORM))
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
                return caches.match('offline.html');
            })
            );

});
