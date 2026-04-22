const ADMIN_CACHE = 'invita-plus-admin-assets-v1';
const ADMIN_ASSETS = [
    '/manifest-admin.json',
    '/admin-offline.html',
    '/admin-pwa/icon-192.png',
    '/admin-pwa/icon-512.png',
    '/admin-pwa/maskable-icon-512.png',
    '/admin-pwa/apple-touch-icon.png'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(ADMIN_CACHE)
            .then((cache) => cache.addAll(ADMIN_ASSETS))
            .then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((keys) => Promise.all(
                keys
                    .filter((key) => key.startsWith('invita-plus-admin-') && key !== ADMIN_CACHE)
                    .map((key) => caches.delete(key))
            ))
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    const { request } = event;

    if (request.method !== 'GET') {
        return;
    }

    const url = new URL(request.url);

    if (url.origin !== self.location.origin) {
        return;
    }

    if (request.mode === 'navigate' && url.pathname.startsWith('/admin')) {
        event.respondWith(networkFirstAdminNavigation(request));
        return;
    }

    if (
        url.pathname.startsWith('/build/') ||
        url.pathname.startsWith('/admin-pwa/') ||
        url.pathname === '/manifest-admin.json' ||
        url.pathname === '/admin-offline.html'
    ) {
        event.respondWith(cacheFirst(request));
    }
});

async function networkFirstAdminNavigation(request) {
    try {
        return await fetch(request);
    } catch (error) {
        const cached = await caches.match('/admin-offline.html');
        return cached || Response.error();
    }
}

async function cacheFirst(request) {
    const cached = await caches.match(request);

    if (cached) {
        return cached;
    }

    const response = await fetch(request);

    if (response.ok) {
        const cache = await caches.open(ADMIN_CACHE);
        cache.put(request, response.clone());
    }

    return response;
}
