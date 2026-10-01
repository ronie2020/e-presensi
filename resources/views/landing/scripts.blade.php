<script>
    // Data dari Controller -> window agar bisa diakses AlpineJS (openAnnouncementByIndex)
    window.announcementsData = @json($announcements ?? []);

    document.addEventListener('DOMContentLoaded', function () {
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // AOS: dimatikan otomatis bagi pengunjung yang memilih "kurangi gerakan"
        if (typeof AOS !== 'undefined') {
            AOS.init({ once: true, offset: 50, duration: 800, disable: () => reduceMotion });
        }

        // Chart.js gagal dimuat (CDN down / offline)? Lewati grafik, halaman tetap jalan.
        if (typeof Chart === 'undefined') return;

        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.color = '#94a3b8';
        if (reduceMotion) Chart.defaults.animation = false;

        // WARNA THEME ELEVATE (tailwind.config.js)
        const elevateAccent = '#56bbf1';
        const elevateAccentRgb = '86, 187, 241';

        // --- Helper bersama (dulu ditulis ulang di tiap grafik) ---
        const tooltip = (extra = {}) => ({
            backgroundColor: 'rgba(2, 17, 36, 0.95)',
            borderColor: 'rgba(86, 187, 241, 0.3)',
            borderWidth: 1,
            titleColor: '#fff',
            bodyColor: '#e2e8f0',
            padding: 10,
            cornerRadius: 8,
            ...extra
        });

        // Chart.js v4: garis grid putus-putus diatur lewat `border.dash`
        const yAxis = (tickExtra = {}) => ({
            beginAtZero: true,
            border: { display: false, dash: [4, 4] },
            grid: { color: 'rgba(255, 255, 255, 0.08)' },
            ticks: { color: '#94a3b8', ...tickExtra }
        });

        // Gradien mengikuti tinggi area grafik yang sebenarnya
        const areaFill = (context) => {
            const { ctx, chartArea } = context.chart;
            if (!chartArea) return 'transparent';
            const g = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
            g.addColorStop(0, `rgba(${elevateAccentRgb}, 0.25)`);
            g.addColorStop(1, `rgba(${elevateAccentRgb}, 0)`);
            return g;
        };

        // Grafik di bawah layar baru dibuat saat mendekati area pandang (hemat kerja di HP)
        const whenVisible = (el, build) => {
            if (!el) return;
            if (!('IntersectionObserver' in window)) { build(); return; }
            const io = new IntersectionObserver((entries, obs) => {
                if (entries.some(e => e.isIntersecting)) { obs.disconnect(); build(); }
            }, { rootMargin: '200px 0px' });
            io.observe(el);
        };

        // --- 0. CHART KEHADIRAN (HERO SECTION) ---
        const attCanvas = document.getElementById('publicWeeklyChart');
        if (attCanvas) {
            const chartData = @json($barChartData ?? ['labels'=>[],'datasets'=>[]]);
            const dsColors = ['#38bdf8', '#1d4ed8', '#f43f5e'];
            
            if (chartData.datasets && chartData.datasets.length > 0) {
                chartData.datasets.forEach((ds, index) => {
                    ds.backgroundColor = dsColors[index] || '#38bdf8';
                    ds.borderRadius = { topLeft: 6, topRight: 6, bottomLeft: 0, bottomRight: 0 };
                    ds.maxBarThickness = 14;
                    ds.categoryPercentage = 0.72;
                    ds.barPercentage = 0.85;
                });
            }

            new Chart(attCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: chartData.labels || [],
                    datasets: chartData.datasets || []
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: tooltip({
                            callbacks: {
                                label: function(context) {
                                    return ' ' + (context.dataset.label || '') + ': ' + context.parsed.y + ' siswa';
                                }
                            }
                        })
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                color: '#94a3b8',
                                font: { weight: '600', size: 10 },
                                autoSkip: true,
                                maxTicksLimit: 7
                            }
                        },
                        y: {
                            beginAtZero: true,
                            min: 0,
                            suggestedMax: 120,
                            border: { display: false, dash: [4, 4] },
                            grid: { color: 'rgba(255, 255, 255, 0.08)' },
                            ticks: {
                                color: '#94a3b8',
                                font: { weight: '600', size: 10 },
                                stepSize: 40,
                                precision: 0
                            }
                        }
                    }
                }
            });
        }

        // --- 1. CHART PERPUSTAKAAN ---
        const libCanvas = document.getElementById('publicLibraryChart');
        whenVisible(libCanvas, () => {
            const libData = @json($libraryChartData ?? []);
            new Chart(libCanvas.getContext('2d'), {
                type: 'line',
                data: {
                    labels: libData.labels || [],
                    datasets: [{
                        label: 'Kunjungan',
                        data: libData.data || [],
                        borderColor: elevateAccent,
                        backgroundColor: areaFill,
                        borderWidth: 3,
                        pointBackgroundColor: '#021124',
                        pointBorderColor: elevateAccent,
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: tooltip({ displayColors: false }) },
                    scales: {
                        y: yAxis({ stepSize: 1 }),
                        x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
                    }
                }
            });
        });

        // --- 2. CHART PEMBIASAAN (HABITS) ---
        const habitCanvas = document.getElementById('habitWeeklyChart');
        whenVisible(habitCanvas, () => {
            new Chart(habitCanvas.getContext('2d'), {
                type: 'line',
                data: {
                    labels: @json($habitLabels ?? []),
                    datasets: [{
                        label: 'Siswa Melapor',
                        data: @json($habitData ?? []),
                        borderColor: elevateAccent,
                        backgroundColor: areaFill,
                        borderWidth: 4,
                        pointBackgroundColor: '#021124',
                        pointBorderColor: elevateAccent,
                        pointBorderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: tooltip({ padding: 12, cornerRadius: 12, displayColors: false }) },
                    scales: {
                        y: yAxis({ font: { weight: 'bold' } }),
                        x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { weight: 'bold' } } }
                    }
                }
            });
        });
    });
</script>