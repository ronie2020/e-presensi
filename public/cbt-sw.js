/**
 * Zero-Panic CBT Service Worker
 * SMP Negeri 3 Lakbok - CBT Engine
 * 
 * Fitur:
 * 1. Network-first dengan automatic cache fallback untuk halaman ujian (Mencegah tampilan "No Internet / Dino" jika siswa tak sengaja refresh saat offline).
 * 2. Cache-first untuk pustaka krusial (MathJax, Phosphor Icons, SweetAlert).
 * 3. Tidak mengintervensi request POST (autosave & pengumpulan dikontrol penuh oleh local storage engine).
 */

const CBT_CACHE_NAME = 'cbt-zero-panic-v1.1';
const CRITICAL_ASSETS = [
    'https://unpkg.com/@phosphor-icons/web',
    'https://cdn.jsdelivr.net/npm/sweetalert2@11',
    'https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js'
];

// 1. Install & Cache Critical CDN Libraries
self.addEventListener('install', event => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(CBT_CACHE_NAME).then(cache => {
            return cache.addAll(CRITICAL_ASSETS).catch(err => {
                console.warn('[CBT-SW] Gagal pre-cache sebagian CDN:', err);
            });
        })
    );
});

// 2. Activate & Clean Old Caches
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys => {
            return Promise.all(
                keys.filter(key => key.startsWith('cbt-') && key !== CBT_CACHE_NAME)
                    .map(key => caches.delete(key))
            );
        }).then(() => self.clients.claim())
    );
});

// 3. Fetch Strategy
self.addEventListener('fetch', event => {
    const request = event.request;

    // Abaikan method selain GET (POST autosave/submit tidak boleh dicache oleh SW)
    if (request.method !== 'GET') return;

    // Abaikan ekstensi chrome dan telemetry
    if (request.url.startsWith('chrome-extension') || request.url.includes('/heartbeat')) return;

    const url = new URL(request.url);

    // Strategi 1: Untuk Halaman Ujian Siswa (HTML Navigation)
    if (request.mode === 'navigate' || request.destination === 'document') {
        event.respondWith(
            fetch(request)
                .then(response => {
                    if (response.status === 200) {
                        const copy = response.clone();
                        caches.open(CBT_CACHE_NAME).then(cache => cache.put(request, copy));
                    }
                    return response;
                })
                .catch(async () => {
                    // KONEKSI OFFLINE: Kembalikan halaman ujian yang tersimpan di cache
                    console.log('[CBT-SW] Offline mode: Menyajikan halaman ujian dari cache lokal.');
                    const cachedResponse = await caches.match(request);
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    // Jika belum pernah dicache sama sekali, kembalikan response informatif
                    return new Response(
                        `<!DOCTYPE html>
                        <html lang="id">
                        <head>
                            <meta charset="utf-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1">
                            <title>Mode Offline - CBT</title>
                            <style>
                                body { background: #020b18; color: #f1f5f9; font-family: sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; padding: 20px; text-align: center; }
                                .card { max-width: 480px; background: #031d3d; border: 1px solid rgba(255,255,255,0.1); border-radius: 2rem; padding: 2.5rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); }
                                h2 { color: #38bdf8; margin-top: 0; }
                                p { color: #cbd5e1; font-size: 0.95rem; line-height: 1.6; }
                                .btn { display: inline-block; background: #0284c7; color: white; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: bold; margin-top: 15px; cursor: pointer; }
                            </style>
                        </head>
                        <body>
                            <div class="card">
                                <h2>Koneksi Terputus</h2>
                                <p>Perangkat Anda sedang tidak terhubung ke jaringan sekolah/internet. Jawaban Anda tetap aman di browser ini. Mohon periksa sinyal Wi-Fi/kabel LAN Anda, lalu klik tombol muat ulang.</p>
                                <button class="btn" onclick="window.location.reload()">Muat Ulang Halaman</button>
                            </div>
                        </body>
                        </html>`,
                        { headers: { 'Content-Type': 'text/html; charset=utf-8' } }
                    );
                })
        );
        return;
    }

    // Strategi 2: Untuk Static Assets CDN & CSS/JS (Stale-While-Revalidate / Cache First)
    if (CRITICAL_ASSETS.some(asset => request.url.includes(asset)) || request.destination === 'style' || request.destination === 'script' || request.destination === 'font') {
        event.respondWith(
            caches.match(request).then(cached => {
                if (cached) return cached;
                return fetch(request).then(response => {
                    if (response.status === 200) {
                        const copy = response.clone();
                        caches.open(CBT_CACHE_NAME).then(cache => cache.put(request, copy));
                    }
                    return response;
                }).catch(() => cached);
            })
        );
        return;
    }
});
