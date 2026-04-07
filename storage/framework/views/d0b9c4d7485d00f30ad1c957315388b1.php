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
                const url = "<?php echo e(route('student.liaison.chat.messages')); ?>?student_id=<?php echo e($student->id); ?>";
                fetch(url).then(res => res.json()).then(data => { this.messages = data; this.scrollToBottom(); });
            },
            sendMessage() {
                if (!this.newMessage.trim()) return;
                const payload = { message: this.newMessage, student_id: "<?php echo e($student->id); ?>" };
                this.messages.push({ message: this.newMessage, sender_type: 'student' });
                this.newMessage = '';
                fetch("<?php echo e(route('student.liaison.chat.send')); ?>", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>" },
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
                events: <?php echo json_encode($calendarEvents ?? [], 15, 512) ?>,
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

        // --- 3. CHART AKADEMIK (Original) ---
        const academicCanvas = document.getElementById('academicChart');
        const academicData = <?php echo json_encode($chartData ?? null, 15, 512) ?>;
        
        if (academicCanvas && academicData && academicData.labels) {
            new Chart(academicCanvas, {
                type: 'bar',
                data: {
                    labels: academicData.labels,
                    datasets: [{
                        label: 'Nilai',
                        data: academicData.scores,
                        backgroundColor: 'rgba(37, 99, 235, 0.2)',
                        borderColor: 'rgba(37, 99, 235, 1)',
                        borderWidth: 2
                    }]
                },
                options: { 
                    indexAxis: 'y', 
                    responsive: true, 
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }

        // --- 4. CHART KEHADIRAN (Placeholder Implementasi) ---
        const attendanceCanvas = document.getElementById('attendanceChart');
        const attData = <?php echo json_encode($attendanceChart ?? null, 15, 512) ?>;
        
        if (attendanceCanvas && attData) {
            new Chart(attendanceCanvas, {
                type: 'doughnut',
                data: {
                    labels: ['Hadir', 'Sakit', 'Izin', 'Alpa'],
                    datasets: [{
                        data: [attData.hadir, attData.sakit, attData.izin, attData.alpa],
                        backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444'],
                        borderWidth: 0
                    }]
                },
                options: {
                    cutout: '70%',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }
    });
</script><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/partials/scripts.blade.php ENDPATH**/ ?>