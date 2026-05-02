<script>
    AOS.init({ once: true, offset: 50, duration: 800 });
    // Pass data dari Controller ke Window object agar bisa diakses AlpineJS
    window.announcementsData = <?php echo json_encode($announcements ?? [], 15, 512) ?>;

    document.addEventListener('DOMContentLoaded', function() {
        // Chart Default Styling
        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.color = '#64748b'; // Teks disesuaikan ke abu-abu medium agar terbaca di background putih
        
        // WARNA ELEVATE DISINKRONKAN DENGAN tailwind.config.js
        const elevateAccent = '#56bbf1'; // Hex elevate-accent
        const elevatePrimary = '#0d52a1'; // Hex elevate-primary
        const elevateDark = '#2c3f61'; // Hex elevate-dark
        
        // RGB referensi untuk gradient Elevate Accent (#56bbf1)
        const elevateAccentRgb = '86, 187, 241';
        
        // --- 1. CHART ATTENDANCE (HERO SECTION) ---
        const ctx = document.getElementById('publicWeeklyChart');
        if(ctx) {
            const chartData = <?php echo json_encode($barChartData ?? ['labels'=>[], 'datasets'=>[]], 512) ?>; 
            
            // Menimpa warna dari backend (opsional) agar seragam dengan tema Elevate
            if(chartData.datasets && chartData.datasets.length > 0) {
                chartData.datasets.forEach((ds, index) => { 
                    if(index === 0) ds.backgroundColor = elevateAccent; 
                    if(index === 1) ds.backgroundColor = elevatePrimary; 
                });
            }

            new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: chartData.datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    borderRadius: 6,
                    // PERBAIKAN 1: Gunakan maxBarThickness agar ukuran bar bisa fleksibel mengecil di HP
                    maxBarThickness: 24, 
                    plugins: { 
                        legend: { 
                            position: 'bottom',
                            // PERBAIKAN 2: Perkecil indikator warna legend agar tidak memakan tempat ke bawah
                            labels: {
                                boxWidth: 10,
                                padding: 15,
                                font: { size: 11, family: "'Plus Jakarta Sans', sans-serif" }
                            }
                        } 
                    },
                    scales: {
                        x: { 
                            grid: { display: false }, 
                            ticks: { 
                                color: '#64748b', 
                                font: { weight: '600', size: 10 },
                                // PERBAIKAN 3: Cegah teks tanggal bertumpuk
                                autoSkip: true,
                                maxTicksLimit: 5, // Batasi jumlah label tanggal yang muncul di HP
                                maxRotation: 0 // Pastikan teks tetap mendatar (tidak miring yang memakan tinggi)
                            } 
                        },
                        y: { 
                            grid: { color: '#f1f5f9', borderDash: [4, 4] }, 
                            border: { display: false }, 
                            ticks: { 
                                color: '#64748b', 
                                font: { weight: '600', size: 10 } 
                            } 
                        }
                    }
                }
            });
        }

        // --- 2. CHART LIBRARY ---
        const libCtx = document.getElementById('publicLibraryChart');
        if (libCtx) {
            const libData = <?php echo json_encode($libraryChartData ?? ['labels'=>[], 'data'=>[]], 512) ?>;
            new Chart(libCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: libData.labels,
                    datasets: [{
                        label: 'Kunjungan',
                        data: libData.data,
                        borderColor: elevateAccent, // Menggunakan Elevate Accent
                        backgroundColor: (context) => {
                            const ctx = context.chart.ctx;
                            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                            gradient.addColorStop(0, `rgba(${elevateAccentRgb}, 0.15)`); // Sinkron RGB Elevate
                            gradient.addColorStop(1, `rgba(${elevateAccentRgb}, 0)`);
                            return gradient;
                        },
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
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
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: elevatePrimary,
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            padding: 10,
                            cornerRadius: 8,
                            displayColors: false
                        }
                    },
                    scales: { 
                        y: { 
                            beginAtZero: true, 
                            border: { display: false }, 
                            grid: { color: '#f1f5f9' },
                            ticks: { stepSize: 1, color: '#64748b' }
                        }, 
                        x: { 
                            grid: { display: false },
                            ticks: { color: '#64748b' }
                        } 
                    }
                }
            });
        }

        // --- 3. CHART HABITS ---
        const habitCtx = document.getElementById('habitWeeklyChart');
        if (habitCtx) {
            new Chart(habitCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($habitLabels ?? [], 15, 512) ?>,
                    datasets: [{
                        label: 'Siswa Melapor',
                        data: <?php echo json_encode($habitData ?? [], 15, 512) ?>,
                        borderColor: elevateAccent, // Menggunakan Elevate Accent
                        backgroundColor: (context) => {
                            const ctx = context.chart.ctx;
                            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                            gradient.addColorStop(0, `rgba(${elevateAccentRgb}, 0.2)`); // Sinkron RGB Elevate
                            gradient.addColorStop(1, `rgba(${elevateAccentRgb}, 0)`);
                            return gradient;
                        },
                        borderWidth: 4,
                        pointBackgroundColor: '#ffffff',
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
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: elevateDark,
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            padding: 12,
                            cornerRadius: 12,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#e2e8f0', drawBorder: false },
                            ticks: { color: '#64748b', font: { weight: 'bold' } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#64748b', font: { weight: 'bold' } }
                        }
                    }
                }
            });
        }
    });
</script><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/landing/scripts.blade.php ENDPATH**/ ?>