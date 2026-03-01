<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Logic untuk Chat Buku Penghubung
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
        // Chart Akademik
        const academicCanvas = document.getElementById('academicChart');
        // Validasi data untuk mencegah error jika data null
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
                options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false }
            });
        }

        // Chart Kehadiran (Jika data tersedia)
        // Anda perlu memastikan backend mengirim $attendanceChart yang sesuai
        // atau gunakan data dari view yang sudah ada.
    });
</script><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/partials/scripts.blade.php ENDPATH**/ ?>