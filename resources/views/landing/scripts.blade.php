<script>
    AOS.init({ once: true, offset: 50, duration: 800 });
    // Pass data dari Controller ke Window object agar bisa diakses AlpineJS
    window.announcementsData = @json($announcements ?? []);

    document.addEventListener('DOMContentLoaded', function() {
        // Chart Default Styling
        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.color = '#94a3b8';
        
        // WARNA THEME ELEVATE (tailwind.config.js)
        const elevateAccent = '#56bbf1';      // Biru Muda / Highlight (#56bbf1)
        const elevatePrimary = '#0d52a1';     // Biru Pekat / Primary (#0d52a1)
        const elevateDark = '#2c3f61';        // Biru Navy (#2c3f61)
        const elevatePeach = '#f9a282';       // Peach Highlight (#f9a282)
        const elevateAccentRgb = '86, 187, 241';
        const elevatePeachRgb = '249, 162, 130';
        
        // --- 1. CHART ATTENDANCE (HERO SECTION) ---
        const ctx = document.getElementById('publicWeeklyChart');
        if(ctx) {
            const chartData = @json($barChartData ?? ['labels'=>[],'datasets'=>[]]); 
            
            if(chartData.datasets && chartData.datasets.length > 0) {
                chartData.datasets.forEach((ds, index) => { 
                    if(index === 0) ds.backgroundColor = elevateAccent; 
                    if(index === 1) ds.backgroundColor = elevatePrimary; 
                    ds.borderRadius = 6;
                    ds.maxBarThickness = 24;
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
                    plugins: { 
                        legend: { 
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                padding: 15,
                                color: '#cbd5e1',
                                font: { size: 11, family: "'Plus Jakarta Sans', sans-serif" }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(2, 17, 36, 0.95)',
                            borderColor: 'rgba(86, 187, 241, 0.3)',
                            borderWidth: 1,
                            titleColor: '#fff',
                            bodyColor: '#e2e8f0',
                            padding: 10,
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        x: { 
                            grid: { display: false }, 
                            ticks: { 
                                color: '#94a3b8', 
                                font: { weight: '600', size: 10 },
                                autoSkip: true,
                                maxTicksLimit: 5,
                                maxRotation: 0
                            } 
                        },
                        y: { 
                            grid: { color: 'rgba(255, 255, 255, 0.08)', borderDash: [4, 4] }, 
                            border: { display: false }, 
                            ticks: { 
                                color: '#94a3b8', 
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
                        borderColor: elevateAccent,
                        backgroundColor: (context) => {
                            const ctx = context.chart.ctx;
                            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                            gradient.addColorStop(0, `rgba(${elevateAccentRgb}, 0.25)`);
                            gradient.addColorStop(1, `rgba(${elevateAccentRgb}, 0)`);
                            return gradient;
                        },
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
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(2, 17, 36, 0.95)',
                            borderColor: 'rgba(86, 187, 241, 0.3)',
                            borderWidth: 1,
                            titleColor: '#fff',
                            bodyColor: '#e2e8f0',
                            padding: 10,
                            cornerRadius: 8,
                            displayColors: false
                        }
                    },
                    scales: { 
                        y: { 
                            beginAtZero: true, 
                            border: { display: false }, 
                            grid: { color: 'rgba(255, 255, 255, 0.08)', borderDash: [4, 4] },
                            ticks: { stepSize: 1, color: '#94a3b8' }
                        }, 
                        x: { 
                            grid: { display: false }, 
                            ticks: { color: '#94a3b8' }
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
                    labels: @json($habitLabels ?? []),
                    datasets: [{
                        label: 'Siswa Melapor',
                        data: @json($habitData ?? []),
                        borderColor: elevateAccent,
                        backgroundColor: (context) => {
                            const ctx = context.chart.ctx;
                            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                            gradient.addColorStop(0, `rgba(${elevateAccentRgb}, 0.25)`);
                            gradient.addColorStop(1, `rgba(${elevateAccentRgb}, 0)`);
                            return gradient;
                        },
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
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(2, 17, 36, 0.95)',
                            borderColor: 'rgba(86, 187, 241, 0.3)',
                            borderWidth: 1,
                            titleColor: '#fff',
                            bodyColor: '#e2e8f0',
                            padding: 12,
                            cornerRadius: 12,
                            displayColors: false
                        }
                    },
                    scales: { 
                        y: { 
                            beginAtZero: true, 
                            grid: { color: 'rgba(255, 255, 255, 0.08)', borderDash: [4, 4] },
                            ticks: { color: '#94a3b8', font: { weight: 'bold' } }
                        }, 
                        x: { 
                            grid: { display: false }, 
                            ticks: { color: '#94a3b8', font: { weight: 'bold' } }
                        } 
                    }
                }
            });
        }
    });
</script>