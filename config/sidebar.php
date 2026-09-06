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
                'roles' => ['*']              
            ],  
            [
                'name' => 'Dashboard Wali Kelas',
                'route' => 'homeroom.dashboard',
                'active_check' => 'homeroom.*',
                'icon' => 'ph-chalkboard-teacher',
                // Hanya muncul untuk Wali Kelas dan Admin
                'roles' => ['Wali Kelas', 'Admin', 'Kepala Sekolah'] 
            ],
            [
                'name' => 'Profil Saya',
                'route' => 'profile.edit',
                'active_check' => 'profile.*',
                'icon' => 'ph-user-circle',  
                'roles' => ['*'] 
            ], 
            [
                'name' => 'Kelola Portofolio',
                'route' => 'portfolio.index', 
                'active_check' => 'portfolio.*',
                'icon' => 'ph-medal',
                'roles' => ['Admin', 'Kepala Sekolah', 'Wali Kelas', 'Guru Mata Pelajaran', 'Guru', 'Guru Piket']
            ],
        ],

        'Menu Absensi Siswa' => [
            [
                'name' => 'Scan Aktifitas',
                'route' => 'scan.show',
                'active_check' => 'scan.*',
                'icon' => 'ph-scan',
                'roles' => ['*']     
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
                'name' => 'Analitik Izin', 
                'route' => 'permit.analytics',
                'active_check' => 'permit.analytics',
                'icon' => 'ph-chart-polar',
                'roles' => ['Admin', 'Guru Piket', 'Wali Kelas', 'Kepala Sekolah']
            ],
            [
                'name' => 'Rekap Harian',
                'route' => 'reports.daily',
                'active_check' => 'reports.daily',
                'icon' => 'ph-chart-bar',
                'roles' => ['Admin', 'Kepala Sekolah', 'Wali Kelas', 'TU']
            ],
            [
                'name' => 'Rekap Keagamaan',
                'route' => 'reports.religious',
                'active_check' => 'reports.religious',
                'icon' => 'ph-star',
                'roles' => ['Admin', 'Kepala Sekolah', 'Wali Kelas', 'Guru Mata Pelajaran']
            ],
            [
                'name' => 'Rekap Kelas',
                'route' => 'reports.class',
                'active_check' => 'reports.class',
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
                'roles' => ['Admin', 'Kepala Sekolah', 'Wali Kelas', 'Guru Mata Pelajaran']
            ],
        ],

       'Penerimaan Siswa (PPDB)' => [
            [
                'name' => 'Data Pendaftar',
                'route' => 'admin.ppdb.index',                
                'active_check' => ['admin.ppdb.index', 'admin.ppdb.show', 'admin.ppdb.edit', 'admin.ppdb.create'],
                'icon' => 'ph-users-four',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah', 'Guru Mata Pelajaran']
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
                'name' => 'Pokok Bahasan (Bab)',
                'route' => 'lms.topics.index',
                'active_check' => 'lms.topics.*',
                'icon' => 'ph-list-dashes',
                'roles' => ['Admin', 'Guru', 'Guru Mata Pelajaran', 'Wali Kelas']
            ],
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
                'name' => 'Susun Jadwal Saya',
                'route' => 'teacher.timetable.index',
                'active_check' => 'teacher.timetable.*',
                'icon' => 'ph-calendar-plus',
                'roles' => ['Guru', 'Guru Mata Pelajaran', 'Wali Kelas'] 
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
                'roles' => ['Admin', 'Guru', 'Guru Mata Pelajaran', 'Wali Kelas', 'Kepala Sekolah']
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
                'active_check' => ['discipline.index', 'discipline.create', 'discipline.edit', 'discipline.show'],
                'icon' => 'ph-warning-circle',
                'roles' => ['Admin', 'Guru Piket', 'Wali Kelas', 'Kepala Sekolah', 'Guru']
            ],
            [
                'name' => 'Analitik Disiplin', 
                'route' => 'discipline.analytics',
                'active_check' => 'discipline.analytics',
                'icon' => 'ph-chart-pie-slice',
                'roles' => ['Admin', 'Kepala Sekolah', 'Wali Kelas']
            ],
            [
                'name' => 'Pemulihan Poin (Amnesti)',
                'route' => 'recovery.index',
                'active_check' => 'recovery.*',
                'icon' => 'ph-leaf',
                'roles' => ['Admin', 'Kepala Sekolah', 'Wali Kelas', 'Guru']
            ],
            [
                'name' => 'Tutup Buku Poin',
                'route' => 'admin.points_reset.index',
                'active_check' => 'admin.points_reset.*',
                'icon' => 'ph-archive',
                'roles' => ['Admin', 'Kepala Sekolah'] 
            ],
            [
                'name' => 'Data Siswa Aktif', 
                'route' => 'students.index',
                'active_check' => 'students.*',
                'icon' => 'ph-student',               
                'roles' => ['Admin', 'TU', 'Kepala Sekolah', 'Wali Kelas', 'Guru', 'Guru Mata Pelajaran', 'Guru Piket']
            ],
             [
                'name' => 'Mutasi & Kenaikan', 
                'route' => 'promotions.index',
                'active_check' => 'promotions.*',
                'icon' => 'ph-arrows-left-right',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah'] 
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
                'active_check' => ['extracurriculars.index', 'extracurriculars.create', 'extracurriculars.edit'],
                'icon' => 'ph-calendar-check',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah', 'Guru']
            ],
            [
                'name' => 'Peserta Ekskul',
                'route' => 'extracurriculars.members',
                'active_check' => 'extracurriculars.members',
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
                'active_check' => ['admin.bk.index', 'admin.bk.show', 'admin.bk.create', 'admin.bk.edit'],
                'icon' => 'ph-chat-centered-text',
                'roles' => ['Admin', 'Kepala Sekolah', 'Guru'] 
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
                'name' => 'Surat Keluar',
                'route' => 'letters.outgoing.index',
                'active_check' => 'letters.outgoing.*',
                'icon' => 'ph-paper-plane-tilt',
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
                'route' => 'school-activities.index',
                'active_check' => 'school-activities.*',
                'icon' => 'ph-image',
                'roles' => ['Admin', 'TU']
            ],
        ],
        'Perpustakaan' => [
            [
                'name' => 'Dashboard Pustaka',
                'route' => 'library.dashboard',
                'active_check' => 'library.dashboard',
                'icon' => 'ph-books',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah', 'Guru']
            ],
            [
                'name' => 'Sirkulasi',
                'route' => 'library.circulation.index',
                'active_check' => ['library.circulation.index', 'library.circulation.create', 'library.circulation.edit'],
                'icon' => 'ph-arrows-left-right',
                'roles' => ['Admin', 'TU'] 
            ],
            [
                'name' => 'Distribusi Paket',
                'route' => 'library.circulation.bulk_borrow',
                'active_check' => 'library.circulation.bulk_borrow',
                'icon' => 'ph-stack',
                'roles' => ['Admin', 'TU'] 
            ],
                [
                'name' => 'Pinjam Individu',
                'route' => 'library.circulation.student_borrow',
                'active_check' => 'library.circulation.student_borrow',
                'icon' => 'ph-user-focus', 
                'roles' => ['Admin', 'TU'] 
            ],
             [
                'name' => 'Data Buku',
                'route' => 'library.books.index',                
                'active_check' => ['library.books.index', 'library.books.edit', 'library.books.show'],
                'icon' => 'ph-book-bookmark',
                'roles' => ['Admin', 'TU']
            ],
            [
                'name' => 'Input Buku Baru',
                'route' => 'library.books.create',
                'active_check' => 'library.books.create',
                'icon' => 'ph-plus-circle',
                'roles' => ['Admin', 'TU']
            ],
            [
                'name' => 'Bebas Pustaka',
                'route' => 'library.tools.bebas_pustaka',
                'active_check' => 'library.tools.bebas_pustaka',
                'icon' => 'ph-certificate',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah']
            ],
            [
                'name' => 'Alat Admin',
                'route' => 'library.tools.index',
                'active_check' => 'library.tools.index',
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
                'name' => 'Kalender Pendidikan',
                'route' => 'admin.academic-calendar.index',
                'active_check' => 'admin.academic-calendar.*',
                'icon' => 'ph-calendar-star',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah']
            ],
            [
                'name' => 'Mata Pelajaran',
                'route' => 'subjects.index',
                'active_check' => 'subjects.*',
                'icon' => 'ph-notebook',
                'roles' => ['Admin', 'TU'] 
            ],
            [
                'name' => 'Slot Waktu',
                'route' => 'timeslots.index',
                'active_check' => 'timeslots.*',
                'icon' => 'ph-clock-user',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah']
            ],
            [
                'name' => 'Beban Mengajar',
                'route' => 'teaching-loads.index',
                'active_check' => 'teaching-loads.*',
                'icon' => 'ph-chalkboard-teacher',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah']
            ],
            [
                'name' => 'Jadwal Pelajaran',
                'route' => 'timetable.index',
                'active_check' => 'timetable.*',
                'icon' => 'ph-magic-wand',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah']
            ],
            [
                'name' => 'Input Jadwal Manual',
                'route' => 'admin.timetable_manual.index',
                'active_check' => 'admin.timetable_manual.*',
                'icon' => 'ph-keyboard',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah']
            ],
            [
                'name' => 'Jam Mesin Absen',
                'route' => 'schedules.index',  
                'active_check' => 'schedules.*',
                'icon' => 'ph-timer',
                'roles' => ['Admin', 'Kepala Sekolah'] 
            ],
//----------------------------------------------------------------------//
           // -- di alihkan ke timetable -- // [
               //    'name' => 'Atur Jadwal',
               //   'route' => 'schedules.index',
               //  'active_check' => 'schedules.*',
               //  'icon' => 'ph-clock',
               // 'roles' => ['Admin', 'TU']
           // ],   -- //   
//----------------------------------------------------------------------//
            [
                'name' => 'Data Kelas',
                'route' => 'classes.index',
                'active_check' => 'classes.*',
                'icon' => 'ph-chalkboard',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah']
            ],
            [
                'name' => 'Data Pengguna',
                'route' => 'users.index',
                'active_check' => 'users.*',
                'icon' => 'ph-users',
                'roles' => ['Admin'] 
            ],
            [
                'name' => 'Jenis Pelanggaran',
                'route' => 'discipline-types.index',
                'active_check' => 'discipline-types.*',
                'icon' => 'ph-warning',
                'roles' => ['Admin', 'TU', 'Kepala Sekolah']
            ],
            [
                'name' => 'Radar Deteksi BK',
                'route' => 'admin.bk.early_warning',
                'active_check' => 'admin.bk.early_warning',
                'icon' => 'ph-siren',
                'roles' => ['Admin', 'Guru Piket', 'Kepala Sekolah']
            ]
        ]
    ],
];