// Tuki School Service Worker (stub for PWA installability)
const CACHE_NAME = 'tuki-v1';
const OFFLINE_URL = '/offline.html';

self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(clients.claim());
});

self.addEventListener('fetch', (event) => {
    // For now, just pass through all requests
    // Full offline caching will be added in Phase 2
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request).catch(() => {
                return caches.match(OFFLINE_URL);
            })
        );
    }
});
