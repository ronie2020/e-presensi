<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sidebar Navigation Menu dengan Role Permission
    |--------------------------------------------------------------------------
    | Format Role: 'Admin', 'Kepala Sekolah', 'Wali Kelas', 'Guru Mata Pelajaran', 'Guru', 'Guru Piket', 'TU'
    */

    'menus' => [
        'Menu Utama' => [
            [
                'name' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'ph-squares-four',
                // Tidak ada key 'roles' berarti bisa diakses semua user yang login
            ],
            [
                'name' => 'Scan Aktifitas',
                'route' => 'scan.show',
                'icon' => 'ph-scan',
                'roles' => ['Admin', 'Kepala Sekolah', 'Wali Kelas', 'Guru Mata Pelajaran', 'TU']
            ],
            [
                'name' => 'Izin Keluar Siswa', 
                'route' => 'permit.index',
                'active_check' => 'permit.index', 
                'icon' => 'ph-door-open',
                'roles' => ['Admin', 'Guru Piket', 'Guru Mata Pelajaran', 'Wali Kelas'] 
            ],
            [
                'name' => 'Riwayat Izin', 
                'route' => 'permit.history',
                'active_check' => 'permit.history',
                'icon' => 'ph-scroll',
                'roles' => ['Admin', 'Guru Piket', 'Wali Kelas', 'Kepala Sekolah']
            ],
            [
                'name' => 'Rekap Harian',
                'route' => 'reports.daily',
                'icon' => 'ph-chart-bar',
                'roles' => ['Admin', 'Kepala Sekolah', 'Wali Kelas', 'TU']
            ],
            [
                'name' => 'Rekap Keagamaan',
                'route' => 'reports.religious',
                'icon' => 'ph-star',
                'roles' => ['Admin', 'Kepala Sekolah', 'Wali Kelas', 'Guru Mata Pelajaran'] // Guru Agama biasanya masuk Guru Mapel
            ],
            [
                'name' => 'Rekap Kelas',
                'route' => 'reports.class',
                'icon' => 'ph-chalkboard-teacher',
                'roles' => ['Admin', 'Kepala Sekolah', 'Wali Kelas', 'TU']
            ],
        ],
        'Spesial Ramadhan' => [
            [
                'name' => 'Rekap Mutabaah',
                'route' => 'admin.ramadan.reports',
                'active_check' => 'admin.ramadan.reports',
                'icon' => 'ph-book-open',
                'roles' => ['Admin', 'Kepala Sekolah', 'Wali Kelas']
            ],
        ],

       'Penerimaan Siswa (PPDB)' => [
            [
                'name' => 'Data Pendaftar',
                'route' => 'admin.ppdb.index',                
                'active_check' => ['admin.ppdb.index', 'admin.ppdb.show', 'admin.ppdb.edit', 'admin.ppdb.create'],
                'icon' => 'ph-users-four',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah']
            ],
            [
                'name' => 'Laporan PPDB',
                'route' => 'admin.ppdb.reports',             
                'active_check' => 'admin.ppdb.reports',
                'icon' => 'ph-printer',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah']
            ],
        ],
        'E-Learning (LMS)' => [
            [
                'name' => 'Materi Pelajaran',
                'route' => 'lms.materials.index',
                'active_check' => 'lms.materials.*',
                'icon' => 'ph-book-open-text',
                'roles' => ['Admin', 'Guru', 'Guru Mata Pelajaran', 'Wali Kelas']
            ],
            [
                'name' => 'Tugas & PR',
                'route' => 'lms.assignments.index',
                'active_check' => 'lms.assignments.*',
                'icon' => 'ph-pencil-simple',
                'roles' => ['Admin', 'Guru', 'Guru Mata Pelajaran', 'Wali Kelas']
            ],
            [
                'name' => 'Rekap Nilai', 
                'route' => 'lms.grades.index',
                'active_check' => 'lms.grades.*',
                'icon' => 'ph-clipboard-text',
                'roles' => ['Admin', 'Guru', 'Guru Mata Pelajaran', 'Wali Kelas', 'Kepala Sekolah']
            ],
        ],
        'Akademik' => [
            [
                'name' => 'Manajemen Kelulusan',
                'route' => 'admin.graduation.index',
                'active_check' => 'admin.graduation.*',
                'icon' => 'ph-graduation-cap',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah']
            ],
            [
                'name' => 'Jurnal Mengajar',
                'route' => 'teaching.index',
                'active_check' => ['teaching.index', 'teaching.show', 'teaching.start'], 
                'icon' => 'ph-chalkboard-teacher',
                'roles' => ['Admin', 'Guru', 'Guru Mata Pelajaran', 'Wali Kelas']
            ],
            [
                'name' => 'Riwayat Mengajar',
                'route' => 'teaching.history',
                'active_check' => 'teaching.history',
                'icon' => 'ph-clock-counter-clockwise',
                'roles' => ['Admin', 'Guru', 'Guru Mata Pelajaran', 'Wali Kelas', 'Kepala Sekolah']
            ],
            [
                'name' => 'Monitoring Jurnal',
                'route' => 'reports.teaching_journal',
                'active_check' => 'reports.teaching_journal',
                'icon' => 'ph-monitor',
                'roles' => ['Admin', 'Kepala Sekolah', 'TU']
            ],
            [
                'name' => 'Input Nilai & Rapor',
                'route' => 'grades.index',
                'active_check' => 'grades.*',
                'icon' => 'ph-pencil-line',
                'roles' => ['Admin', 'Guru', 'Guru Mata Pelajaran', 'Wali Kelas']
            ],   
            [
                'name' => 'Bank Soal',
                'route' => 'bank.index',
                'active_check' => 'bank.*',
                'icon' => 'ph-stack',
                'roles' => ['Admin', 'Guru', 'Guru Mata Pelajaran', 'Wali Kelas']
            ],
            [
                'name' => 'CBT / Ujian Online',
                'route' => 'cbt.index',
                'active_check' => [
                    'cbt.index', 
                    'cbt.create', 
                    'cbt.edit', 
                    'cbt.questions.*', 
                    'cbt.monitoring', 
                    'cbt.recap', 
                    'cbt.analysis',
                    'cbt.result.*'
                ],
                'icon' => 'ph-desktop',
                'roles' => ['Admin', 'Guru', 'Guru Mata Pelajaran', 'Wali Kelas', 'Kepala Sekolah']
            ],
            [
                'name' => 'Cetak Kartu Ujian',
                'route' => 'cbt.cards.index',
                'active_check' => 'cbt.cards.*', 
                'icon' => 'ph-identification-card',
                'roles' => ['Admin', 'TU', 'Wali Kelas']
            ]
        ],
        'Kesiswaan' => [
            [
                'name' => 'Catatan Disiplin',
                'route' => 'discipline.index',
                'icon' => 'ph-warning-circle',
                'roles' => ['Admin', 'Guru Piket', 'Wali Kelas', 'Kepala Sekolah', 'Guru']
            ],
            [
                'name' => 'Data Siswa Aktif', 
                'route' => 'students.index',
                'icon' => 'ph-student',
                // Semua staff biasanya butuh lihat data siswa, tapi bisa dibatasi
                'roles' => ['Admin', 'TU', 'Kepala Sekolah', 'Wali Kelas', 'Guru', 'Guru Mata Pelajaran', 'Guru Piket']
            ],
            [
                'name' => 'Prestasi',
                'route' => 'achievements.index',
                'active_check' => 'achievements.*',
                'icon' => 'ph-trophy',
                'roles' => ['Admin', 'Kepala Sekolah', 'Wali Kelas', 'TU']
            ],
            [
                'name' => 'Data Alumni',
                'route' => 'admin.alumni.index', 
                'active_check' => 'admin.alumni.*',
                'icon' => 'ph-users-three',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah']
            ],
        ],
        'Ekstrakurikuler' => [
            [
                'name' => 'Data & Jadwal',
                'route' => 'extracurriculars.index',
                'icon' => 'ph-calendar-check',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah', 'Guru']
            ],
            [
                'name' => 'Peserta Ekskul',
                'route' => 'extracurriculars.members',
                'icon' => 'ph-users-three',
                'roles' => ['Admin', 'TU', 'Guru']
            ],
            [
                'name' => 'Laporan Absensi',
                'route' => 'extracurriculars.reports',
                'active_check' => 'extracurriculars.reports*',
                'icon' => 'ph-file-text',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah']
            ],
        ],
        'Pusat Layanan Siswa' => [
            [
                'name' => 'Buku Penghubung',
                'route' => 'liaison.index', 
                'active_check' => 'liaison.*',
                'icon' => 'ph-book-open-user',
                'roles' => ['Admin', 'Wali Kelas', 'Kepala Sekolah']
            ],
            [
                'name' => 'Kotak Pengaduan',
                'route' => 'complaints.index', 
                'active_check' => 'complaints.*',
                'icon' => 'ph-mailbox',
                'roles' => ['Admin', 'Kepala Sekolah', 'Guru Piket']
            ],
            [
                'name' => 'Monitoring Kebiasaan',
                'route' => 'teacher.habits.index',
                'active_check' => 'teacher.habits.*',
                'icon' => 'ph-check-square-offset', 
                'roles' => ['Admin', 'Wali Kelas', 'Guru']
            ],           
            [
                'name' => 'Monitoring Literasi', 
                'route' => 'admin.literacy.index', 
                'active_check' => 'admin.literacy.*',
                'icon' => 'ph-read-cv-logo',
                'roles' => ['Admin', 'Wali Kelas', 'Guru Mata Pelajaran', 'Kepala Sekolah']
            ],
            [
                'name' => 'E-Counseling (BK)',
                'route' => 'admin.bk.index',
                'active_check' => 'admin.bk.*',
                'icon' => 'ph-chat-centered-text',
                'roles' => ['Admin', 'Kepala Sekolah', 'Guru'] // Asumsi Guru BK role-nya Guru atau Admin
            ],
        ],
        'Persuratan & Dinas' => [
            [
                'name' => 'Surat Masuk',
                'route' => 'letters.incoming.index',
                'active_check' => 'letters.incoming.*',
                'icon' => 'ph-envelope-simple-open',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah']
            ],
            [
                'name' => 'Surat Tugas (SPT)',
                'route' => 'letters.spt.index',
                'active_check' => 'letters.spt.*',
                'icon' => 'ph-file-text',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah']
            ],
            [
                'name' => 'Input SPPD',
                'route' => 'sppd.index',
                'active_check' => 'sppd.*',
                'icon' => 'ph-airplane-tilt',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah']
            ],
        ],
        'Informasi Sekolah' => [
            [
                'name' => 'Papan Pengumuman',
                'route' => 'announcements.index',
                'active_check' => 'announcements.*',
                'icon' => 'ph-megaphone',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah']
            ],
            [
                'name' => 'Agenda Kegiatan',
                'route' => 'agendas.index',
                'active_check' => 'agendas.*',
                'icon' => 'ph-calendar',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah']
            ],
            [
                'name' => 'Galeri Aktifitas',
                'route' => 'activities.index',
                'active_check' => 'activities.*',
                'icon' => 'ph-image',
                'roles' => ['Admin', 'TU']
            ],
        ],
        'Perpustakaan' => [
            [
                'name' => 'Dashboard Pustaka',
                'route' => 'library.dashboard',
                'icon' => 'ph-books',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah', 'Guru']
            ],
            
            [
                'name' => 'Sirkulasi',
                'route' => 'library.circulation.index',
                'icon' => 'ph-arrows-left-right',
                'roles' => ['Admin', 'TU'] // Atau petugas perpus jika ada
            ],
            [
                'name' => 'Data Buku',
                'route' => 'library.books.index',
                'active_check' => 'library.books.*',
                'icon' => 'ph-book-bookmark',
                'roles' => ['Admin', 'TU']
            ],
            [
                'name' => 'Alat Admin',
                'route' => 'library.tools.index',
                'active_check' => 'library.tools.*',
                'icon' => 'ph-printer',
                'roles' => ['Admin', 'TU']
            ]
        ],
        'Administrasi' => [
            [
                'name' => 'Tahun Ajaran',
                'route' => 'settings.academic.index',
                'active_check' => 'settings.academic.*',
                'icon' => 'ph-calendar-blank',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah']
            ],
            [
                'name' => 'Mata Pelajaran',
                'route' => 'subjects.index',
                'active_check' => 'subjects.*',
                'icon' => 'ph-notebook',
                'roles' => ['Admin', 'TU'] // Kepsek hanya perlu lihat laporan, bukan edit master data
            ],
            [
                'name' => 'Atur Jadwal',
                'route' => 'schedules.index',
                'icon' => 'ph-clock',
                'roles' => ['Admin', 'TU']
            ],
            [
                'name' => 'Data Kelas',
                'route' => 'classes.index',
                'icon' => 'ph-chalkboard',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah']
            ],
            [
                'name' => 'Data Pengguna',
                'route' => 'users.index',
                'icon' => 'ph-users',
                'roles' => ['Admin', 'TU'] // Sangat Penting: Hanya Admin yang boleh akses ini
            ],
            [
                'name' => 'Jenis Pelanggaran',
                'route' => 'discipline-types.index',
                'active_check' => 'discipline-types.*',
                'icon' => 'ph-warning',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah']
            ]
        ]
    ],
];