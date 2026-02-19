const CACHE_NAME = 'netila-pwa-cache-v1';
const urlsToCache = [
    '/offline.html',
    '/images/logo.png', // Logo sekolah untuk halaman offline
];

// 1. Install Service Worker & Simpan Cache
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Cache berhasil dibuka');
                return cache.addAll(urlsToCache);
            })
    );
});

// 2. Aktivasi Service Worker & Bersihkan Cache Lama
self.addEventListener('activate', event => {
    const cacheWhitelist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheWhitelist.indexOf(cacheName) === -1) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

// 3. Strategi Fetch (Network First, fallback to Cache/Offline)
self.addEventListener('fetch', event => {
    // Abaikan request API, request POST, atau ekstensi chrome
    if (event.request.method !== 'GET' || event.request.url.includes('/api/') || event.request.url.startsWith('chrome-extension')) {
        return;
    }

    event.respondWith(
        fetch(event.request).catch(() => {
            return caches.match(event.request).then(response => {
                // Jika file ada di cache, gunakan file tersebut
                if (response) {
                    return response;
                }
                // Jika offline dan yang direquest adalah halaman web (navigate)
                // Tampilkan halaman offline fallback
                if (event.request.mode === 'navigate') {
                    return caches.match('/offline.html');
                }
            });
        })
    );
});