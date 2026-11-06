<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Library FullCalendar v6 -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<script>
    // Logic untuk Chat Buku Penghubung (Original)
    function studentLiaisonHandler() {
        return {
            mode: 'note',
            messages: [],
            newMessage: '',
            loading: false,
            fetchMessages() {
                const url = "{{ route('student.liaison.chat.messages') }}?student_id={{ $student->id }}";
                fetch(url).then(res => res.json()).then(data => { this.messages = data; this.scrollToBottom(); });
            },
            sendMessage() {
                if (!this.newMessage.trim()) return;
                const payload = { message: this.newMessage, student_id: "{{ $student->id }}" };
                this.messages.push({ message: this.newMessage, sender_type: 'student' });
                this.newMessage = '';
                fetch("{{ route('student.liaison.chat.send') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    body: JSON.stringify(payload)
                });
            },
            scrollToBottom() { setTimeout(() => { const b = this.$refs.chatBox; if(b) b.scrollTop = b.scrollHeight; }, 100); }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. INISIALISASI FULLCALENDAR ---
        let calendarEl = document.getElementById('calendar');
        let calendar = null;

        if (calendarEl) {
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: window.innerWidth < 768 ? 'listMonth' : 'dayGridMonth',
                locale: 'id',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listMonth'
                },
                buttonText: {
                    today: 'Hari Ini',
                    month: 'Bulan',
                    list: 'Agenda'
                },
                // Data dikirim dari Controller
                events: @json($calendarEvents ?? []),
                eventClick: function(info) {
                    // Logika sederhana saat agenda diklik
                    console.log('Event: ' + info.event.title);
                }
            });
            calendar.render();
        }

        // --- 2. LOGIKA FIX UNTUK TAB ALPINE.JS ---
        // Kalender perlu di-render ulang saat tab 'jadwal' aktif
        document.addEventListener('tab-changed', (e) => {
            if(e.detail.tab === 'jadwal' && calendar) {
                setTimeout(() => { 
                    calendar.render();
                    calendar.updateSize(); 
                }, 100);
            }
        });

        // Window resize handler agar kalender tetap responsif
        window.addEventListener('resize', () => {
            if(calendar) calendar.render();
        });

        // --- 3. CHART AKADEMIK (Penyelarasan Warna Tema Microsoft Elevate) ---
        const academicCanvas = document.getElementById('academicChart');
        const academicData = @json($chartData ?? null);
        
        if (academicCanvas && academicData && academicData.labels) {
            new Chart(academicCanvas, {
                type: 'bar',
                data: {
                    labels: academicData.labels,
                    datasets: [{
                        label: 'Nilai',
                        data: academicData.scores,
                        backgroundColor: 'rgba(56, 189, 248, 0.25)',
                        borderColor: 'rgba(56, 189, 248, 1)',
                        borderWidth: 2,
                        borderRadius: 6
                    }]
                },
                options: { 
                    indexAxis: 'y', 
                    responsive: true, 
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            ticks: { color: '#94a3b8', font: { weight: 'bold' } },
                            grid: { color: 'rgba(255, 255, 255, 0.06)' }
                        },
                        y: {
                            ticks: { color: '#e2e8f0', font: { weight: 'bold' } },
                            grid: { color: 'rgba(255, 255, 255, 0.06)' }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }

        // --- 4. CHART KEHADIRAN (Penyelarasan Warna Tema Microsoft Elevate) ---
        const attendanceCanvas = document.getElementById('attendanceChart');
        const attData = @json($attendanceChart ?? null);
        
        if (attendanceCanvas && attData) {
            new Chart(attendanceCanvas, {
                type: 'doughnut',
                data: {
                    labels: ['Hadir', 'Sakit', 'Izin', 'Alpa'],
                    datasets: [{
                        data: [attData.hadir, attData.sakit, attData.izin, attData.alpa],
                        backgroundColor: ['#10b981', '#38bdf8', '#f59e0b', '#f43f5e'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    cutout: '75%',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: '#cbd5e1', font: { weight: 'bold', size: 11 } }
                        }
                    }
                }
            });
        }
    });
</script>