<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sidebar Navigation Menu
    |--------------------------------------------------------------------------
    */

    'menus' => [
        'Menu Utama' => [
            [
                'name' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'ph-squares-four'
            ],
            [
                'name' => 'Scan Aktifitas',
                'route' => 'scan.show',
                'icon' => 'ph-scan'
            ],
            [
                'name' => 'Izin Keluar Siswa', 
                'route' => 'permit.index',
                'active_check' => 'permit.index', 
                'icon' => 'ph-door-open' 
            ],
            [
                'name' => 'Riwayat Izin', 
                'route' => 'permit.history',
                'active_check' => 'permit.history',
                'icon' => 'ph-scroll' 
            ],
            [
                'name' => 'Rekap Harian',
                'route' => 'reports.daily',
                'icon' => 'ph-chart-bar'
            ],
            [
                'name' => 'Rekap Keagamaan',
                'route' => 'reports.religious',
                'icon' => 'ph-star'
            ],
            [
                'name' => 'Rekap Kelas',
                'route' => 'reports.classReport',
                'icon' => 'ph-chalkboard-teacher'
            ],
        ],
        'Spesial Ramadhan' => [
           
            [
                'name' => 'Rekap Mutabaah',
                'route' => 'admin.ramadan.reports',
                'active_check' => 'admin.ramadan.reports',
                'icon' => 'ph-book-open'
            ],
        ],

       'Penerimaan Siswa (PPDB)' => [
            [
                'name' => 'Data Pendaftar',
                'route' => 'admin.ppdb.index',                
                'active_check' => ['admin.ppdb.index', 'admin.ppdb.show', 'admin.ppdb.edit', 'admin.ppdb.create'],
                'icon' => 'ph-users-four'
            ],
            [
                'name' => 'Laporan PPDB',
                'route' => 'admin.ppdb.reports',             
                'active_check' => 'admin.ppdb.reports',
                'icon' => 'ph-printer'
            ],
        ],
        'E-Learning (LMS)' => [
            [
                'name' => 'Materi Pelajaran',
                'route' => 'lms.materials.index',
                'active_check' => 'lms.materials.*',
                'icon' => 'ph-book-open-text'
            ],
            [
                'name' => 'Tugas & PR',
                'route' => 'lms.assignments.index',
                'active_check' => 'lms.assignments.*',
                'icon' => 'ph-pencil-simple'
            ],
            [
                'name' => 'Rekap Nilai', 
                'route' => 'lms.grades.index',
                'active_check' => 'lms.grades.*',
                'icon' => 'ph-clipboard-text'
            ],
        ],
        'Akademik' => [
            [
                'name' => 'Manajemen Kelulusan',
                'route' => 'admin.graduation.index',
                'active_check' => 'admin.graduation.*',
                'icon' => 'ph-graduation-cap'
            ],
            [
                'name' => 'Jurnal Mengajar',
                'route' => 'teaching.index',
                'active_check' => ['teaching.index', 'teaching.show', 'teaching.start'], 
                'icon' => 'ph-chalkboard-teacher'
            ],
            [
                'name' => 'Riwayat Mengajar',
                'route' => 'teaching.history',
                'active_check' => 'teaching.history',
                'icon' => 'ph-clock-counter-clockwise'
            ],
            [
                'name' => 'Monitoring Jurnal',
                'route' => 'reports.teaching_journal',
                'active_check' => 'reports.teaching_journal',
                'icon' => 'ph-monitor'
            ],
            [
                'name' => 'Input Nilai & Rapor',
                'route' => 'grades.index',
                'active_check' => 'grades.*',
                'icon' => 'ph-pencil-line'
            ],   
            [
                'name' => 'Bank Soal',
                'route' => 'bank.index',
                'active_check' => 'bank.*',
                'icon' => 'ph-stack'
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
                'icon' => 'ph-desktop'
            ],
            [
                'name' => 'Cetak Kartu Ujian',
                'route' => 'cbt.cards.index',
                'active_check' => 'cbt.cards.*', 
                'icon' => 'ph-identification-card'
            ]
        ],
        'Kesiswaan' => [
            [
                'name' => 'Catatan Disiplin',
                'route' => 'discipline.index',
                'icon' => 'ph-warning-circle'
            ],
            [
                'name' => 'Data Siswa Aktif', 
                'route' => 'students.index',
                'icon' => 'ph-student'
            ],
            [
                'name' => 'Prestasi',
                'route' => 'achievements.index',
                'active_check' => 'achievements.*',
                'icon' => 'ph-trophy'
            ],
            [
                'name' => 'Data Alumni',
                'route' => 'admin.alumni.index', 
                'active_check' => 'admin.alumni.*',
                'icon' => 'ph-users-three', 
            ],
        ],
        'Ekstrakurikuler' => [
            [
                'name' => 'Data & Jadwal',
                'route' => 'extracurriculars.index',
                'icon' => 'ph-calendar-check'
            ],
            [
                'name' => 'Peserta Ekskul',
                'route' => 'extracurriculars.members',
                'icon' => 'ph-users-three'
            ],
            [
                'name' => 'Laporan Absensi',
                'route' => 'extracurriculars.reports',
                'active_check' => 'extracurriculars.reports*',
                'icon' => 'ph-file-text'
            ],
        ],
        'Pusat Layanan Siswa' => [
            [
                'name' => 'Buku Penghubung',
                'route' => 'liaison.index', 
                'active_check' => 'liaison.*',
                'icon' => 'ph-book-open-user', 
            ],
            [
                'name' => 'Kotak Pengaduan',
                'route' => 'complaints.index', 
                'active_check' => 'complaints.*',
                'icon' => 'ph-mailbox', 
            ],
            [
                'name' => 'Monitoring Kebiasaan',
                'route' => 'teacher.habits.index',
                'active_check' => 'teacher.habits.*',
                'icon' => 'ph-check-square-offset', 
            ],           
            [
                'name' => 'Monitoring Literasi', 
                'route' => 'admin.literacy.index', 
                'active_check' => 'admin.literacy.*',
                'icon' => 'ph-read-cv-logo'
            ],
            [
                'name' => 'E-Counseling (BK)',
                'route' => 'admin.bk.index',
                'active_check' => 'admin.bk.*',
                'icon' => 'ph-chat-centered-text', 
            ],
        ],
        'Persuratan & Dinas' => [
            [
                'name' => 'Surat Masuk',
                'route' => 'letters.incoming.index',
                'active_check' => 'letters.incoming.*',
                'icon' => 'ph-envelope-simple-open'
            ],
            [
                'name' => 'Surat Tugas (SPT)',
                'route' => 'letters.spt.index',
                'active_check' => 'letters.spt.*',
                'icon' => 'ph-file-text'
            ],
            [
                'name' => 'Input SPPD',
                'route' => 'sppd.index',
                'active_check' => 'sppd.*',
                'icon' => 'ph-airplane-tilt'
            ],
        ],
        'Informasi Sekolah' => [
            [
                'name' => 'Papan Pengumuman',
                'route' => 'announcements.index',
                'active_check' => 'announcements.*',
                'icon' => 'ph-megaphone'
            ],
            [
                'name' => 'Agenda Kegiatan',
                'route' => 'agendas.index',
                'active_check' => 'agendas.*',
                'icon' => 'ph-calendar'
            ],
            [
                'name' => 'Galeri Aktifitas',
                'route' => 'activities.index',
                'active_check' => 'activities.*',
                'icon' => 'ph-image'
            ],
        ],
        'Perpustakaan' => [
            [
                'name' => 'Dashboard Pustaka',
                'route' => 'library.dashboard',
                'icon' => 'ph-books'
            ],
            
            [
                'name' => 'Sirkulasi',
                'route' => 'library.circulation.index',
                'icon' => 'ph-arrows-left-right'
            ],
            [
                'name' => 'Data Buku',
                'route' => 'library.books.index',
                'active_check' => 'library.books.*',
                'icon' => 'ph-book-bookmark'
            ],
            [
                'name' => 'Alat Admin',
                'route' => 'library.tools.index',
                'active_check' => 'library.tools.*',
                'icon' => 'ph-printer'
            ]
        ],
        'Administrasi' => [
            [
                'name' => 'Tahun Ajaran',
                'route' => 'settings.academic.index',
                'active_check' => 'settings.academic.*',
                'icon' => 'ph-calendar-blank'
            ],
            [
                'name' => 'Mata Pelajaran',
                'route' => 'subjects.index',
                'active_check' => 'subjects.*',
                'icon' => 'ph-notebook'
            ],
            [
                'name' => 'Atur Jadwal',
                'route' => 'schedules.index',
                'icon' => 'ph-clock'
            ],
            [
                'name' => 'Data Kelas',
                'route' => 'classes.index',
                'icon' => 'ph-chalkboard'
            ],
            [
                'name' => 'Data Pengguna',
                'route' => 'users.index',
                'icon' => 'ph-users'
            ],
            [
                'name' => 'Jenis Pelanggaran',
                'route' => 'discipline-types.index',
                'active_check' => 'discipline-types.*',
                'icon' => 'ph-warning'
            ]
        ]
    ],
];