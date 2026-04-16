<script>
    AOS.init({ once: true, offset: 50, duration: 800 });
    // Pass data dari Controller ke Window object agar bisa diakses AlpineJS
    window.announcementsData = @json($announcements ?? []);

    document.addEventListener('DOMContentLoaded', function() {
        // Chart Default Styling
        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.color = '#64748b'; // Teks disesuaikan ke abu-abu medium agar terbaca di background putih
        
        // --- 1. CHART ATTENDANCE (HERO SECTION) ---
        const ctx = document.getElementById('publicWeeklyChart');
        if(ctx) {
            const chartData = @json($barChartData ?? ['labels'=>[],'datasets'=>[]]); 
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
            const libData = @json($libraryChartData ?? ['labels'=>[],'data'=>[]]);
            new Chart(libCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: libData.labels,
                    datasets: [{
                        label: 'Kunjungan',
                        data: libData.data,
                        borderColor: '#0ea5e9', // Diubah ke warna cyan/sky blue
                        backgroundColor: (context) => {
                            const ctx = context.chart.ctx;
                            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                            gradient.addColorStop(0, 'rgba(14, 165, 233, 0.15)');
                            gradient.addColorStop(1, 'rgba(14, 165, 233, 0)');
                            return gradient;
                        },
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#0ea5e9',
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
                            backgroundColor: '#0ea5e9',
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
    });

    document.addEventListener('DOMContentLoaded', function() {
    const habitCtx = document.getElementById('habitWeeklyChart');
    if (habitCtx) {
        new Chart(habitCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($habitLabels ?? []),
                datasets: [{
                    label: 'Siswa Melapor',
                    data: @json($habitData ?? []),
                    borderColor: '#06b6d4', // Cyan
                    backgroundColor: (context) => {
                        const ctx = context.chart.ctx;
                        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                        gradient.addColorStop(0, 'rgba(6, 182, 212, 0.2)');
                        gradient.addColorStop(1, 'rgba(6, 182, 212, 0)');
                        return gradient;
                    },
                    borderWidth: 4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#06b6d4',
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
                        backgroundColor: '#1e293b',
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
                        grid: { color: '#e2e8f0', drawBorder: false }, // Grid disesuaikan ke light
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
</script>