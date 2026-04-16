<script>
    AOS.init({ once: true, offset: 50, duration: 800 });
    // Pass data dari Controller ke Window object agar bisa diakses AlpineJS
    window.announcementsData = <?php echo json_encode($announcements ?? [], 15, 512) ?>;

    document.addEventListener('DOMContentLoaded', function() {
        // Chart Default Styling
        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.color = '#64748b'; // Teks disesuaikan ke abu-abu medium agar terbaca di background putih
        
        // --- 1. CHART ATTENDANCE (HERO SECTION) ---
        const ctx = document.getElementById('publicWeeklyChart');
        if(ctx) {
            const chartData = <?php echo json_encode($barChartData ?? ['labels'=>[], 'datasets'=>[]], 512) ?>; 
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
                    barThickness: 24,
                    plugins: { legend: { position: 'bottom' } },
                    scales: {
                        x: { 
                            grid: { display: false }, 
                            ticks: { color: '#64748b', font: { weight: '600' } } // Warna font gelap untuk background terang
                        },
                        y: { 
                            grid: { color: '#f1f5f9', borderDash: [4, 4] }, // Grid lebih halus
                            border: { display: false }, 
                            ticks: { color: '#64748b', font: { weight: '600' } } 
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
                labels: <?php echo json_encode($habitLabels ?? [], 15, 512) ?>,
                datasets: [{
                    label: 'Siswa Melapor',
                    data: <?php echo json_encode($habitData ?? [], 15, 512) ?>,
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
</script><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/landing/scripts.blade.php ENDPATH**/ ?>