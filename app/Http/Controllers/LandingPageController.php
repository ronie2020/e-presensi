<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceSiswa; 
use App\Models\LibraryVisit; 
use App\Models\Borrowing;
use App\Models\Announcement;
use App\Models\Achievement;
use App\Models\SchoolActivity;
use App\Models\User;
use App\Models\GuestBook;
use App\Models\Extracurricular; 
use App\Models\Agenda;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\LmsMaterial;
use App\Models\LmsAssignment;
use App\Models\AlumniProfile; 
use App\Models\StudentHabit; 
use App\Models\Book; 
use App\Models\TeacherArticle;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class LandingPageController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        // ==============================================================
        // FITUR PENGHITUNG PENGUNJUNG (VISITOR COUNTER)
        // ==============================================================
        // Cek apakah pengunjung (session ini) sudah dihitung sebelumnya
        if (!session()->has('has_visited_landing_page')) {
            // PERBAIKAN: Cek apakah key cache sudah ada
            if (!Cache::has('total_landing_visitors')) {               
                Cache::forever('total_landing_visitors', 1);
            } else {                
                Cache::increment('total_landing_visitors');
            }
                        
            session()->put('has_visited_landing_page', true);
        }
        
        // Ambil total pengunjung, jika masih error/kosong setel ke 1 sebagai fallback
        $visitorCount = Cache::get('total_landing_visitors', 1);



        // --- DEFINISI STATUS (Case Insensitive handling) ---
        $statusHadir     = ['Hadir', 'hadir', 'Present', 'present', 'Tepat Waktu', 'tepat waktu'];
        $statusTerlambat = ['Terlambat', 'terlambat', 'Late', 'late', 'Telat'];
        $statusSakit     = ['Sakit', 'sakit', 'Sick'];
        $statusIzin      = ['Izin', 'izin', 'Permission'];
        $statusAlpa      = ['Alpa', 'alpa', 'Alpha', 'Absent'];

        // --- 1. STATISTIK HARIAN ---
         $stats = Cache::remember('landing_daily_attendance_' . $today->format('Ymd'), 600, function() use ($today, $statusHadir, $statusTerlambat, $statusSakit, $statusIzin, $statusAlpa) {
            $dailyQuery = AttendanceSiswa::whereDate('attendance_date', $today)
                ->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
                ->whereHas('student', function($q) {
                    $q->where('status', '!=', 'graduated');
                });

            $hadirTepat = (clone $dailyQuery)->whereIn('status', $statusHadir)->distinct('student_id')->count('student_id');
            $terlambat  = (clone $dailyQuery)->whereIn('status', $statusTerlambat)->distinct('student_id')->count('student_id');
            $tidakHadir = (clone $dailyQuery)->whereIn('status', array_merge($statusSakit, $statusIzin, $statusAlpa))->distinct('student_id')->count('student_id');

            return [
                'hadir'       => $hadirTepat + $terlambat,
                'tepat_waktu' => $hadirTepat,
                'terlambat'   => $terlambat,
                'tidak_hadir' => $tidakHadir
            ];
        });

        // --- 2. LOGIKA 7 KEBIASAAN ANAK ---
         $habitDataArray = Cache::remember('landing_habit_stats_' . $today->format('Ymd'), 600, function() use ($today) {
            $totalStudentsActive = Student::where('status', '!=', 'graduated')->count();
            $habitsToday = StudentHabit::whereDate('report_date', $today)->count();
            $habitPercentage = $totalStudentsActive > 0 ? round(($habitsToday / $totalStudentsActive) * 100) : 0;
            
            $habitStats = [
                'submitted' => $habitsToday,
                'missing'   => max(0, $totalStudentsActive - $habitsToday),
                'percentage'=> $habitPercentage
            ];

            $habitLabels = [];
            $habitData = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $habitLabels[] = $date->translatedFormat('D');
                $habitData[] = StudentHabit::whereDate('report_date', $date)->count();
            }

            return ['stats' => $habitStats, 'labels' => $habitLabels, 'data' => $habitData];
        });

        $habitStats = $habitDataArray['stats'];
        $habitLabels = $habitDataArray['labels'];
        $habitData = $habitDataArray['data'];

        // --- 3. CHART KEHADIRAN MINGGUAN ---
        $barChartData = Cache::remember('landing_weekly_attendance_chart', 3600, function() use ($statusHadir, $statusTerlambat, $statusSakit, $statusIzin, $statusAlpa) {
            $startDate = Carbon::today()->subDays(6);
            $endDate = Carbon::today();
            
            $weeklyData = AttendanceSiswa::whereBetween('attendance_date', [$startDate, $endDate])
                ->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
                ->whereHas('student', function($q) {
                    $q->where('status', '!=', 'graduated');
                })->get();

            $chartLabels = [];
            $dataHadir = [];
            $dataTerlambat = [];
            $dataAbsen = [];

            $period = $startDate->copy();
            while ($period <= $endDate) {
                $dateStr = $period->toDateString();
                $chartLabels[] = $period->format('d/m'); 

                $dailyAtt = $weeklyData->filter(function ($att) use ($dateStr) {
                    $attDate = $att->attendance_date instanceof \Carbon\Carbon 
                                ? $att->attendance_date->toDateString() 
                                : substr($att->attendance_date, 0, 10);
                    return $attDate === $dateStr;
                });

                $countStatus = function($collection, $statuses) {
                    return $collection->filter(function ($item) use ($statuses) {
                        return in_array($item->status, $statuses) || in_array(ucfirst($item->status), $statuses);
                    })->unique('student_id')->count();
                };

                $dataHadir[] = $countStatus($dailyAtt, $statusHadir);
                $dataTerlambat[] = $countStatus($dailyAtt, $statusTerlambat);
                $dataAbsen[] = $countStatus($dailyAtt, array_merge($statusSakit, $statusIzin, $statusAlpa));

                $period->addDay();
            }

            return [
                'labels' => $chartLabels,
                'datasets' => [
                    ['label' => 'Hadir Tepat', 'data' => $dataHadir, 'backgroundColor' => '#10b981', 'borderRadius' => 4],
                    ['label' => 'Terlambat', 'data' => $dataTerlambat, 'backgroundColor' => '#f59e0b', 'borderRadius' => 4],
                    ['label' => 'Tidak Hadir', 'data' => $dataAbsen, 'backgroundColor' => '#f43f5e', 'borderRadius' => 4]
                ]
            ];
        });

        // --- 4. CHART PERPUSTAKAAN ---
        $libraryDataCache = Cache::remember('landing_library_data', 1800, function() use ($today) {
            $startDate = Carbon::today()->subDays(6);
            $endDate = Carbon::today();
            $libLabels = [];
            $libData = [];
            $periodLib = $startDate->copy();

            while ($periodLib <= $endDate) {
                $libLabels[] = $periodLib->format('d/m');
                $libData[] = LibraryVisit::whereDate('date', $periodLib->toDateString())->count();
                $periodLib->addDay();
            }

            return [
                'chart' => ['labels' => $libLabels, 'data' => $libData],
                'stats' => [
                    'visitors_today' => LibraryVisit::whereDate('date', $today)->count(), 
                    'books_borrowed' => Borrowing::where('status', 'borrowed')->count()
                ]
            ];
        });
        $libraryChartData = $libraryDataCache['chart'];
        $libraryStats = $libraryDataCache['stats'];

        // --- 5. CACHE STATISTIK ---
        $schoolStats = Cache::remember('school_profile_stats_v3', 3600, function () {
            $materiCount = class_exists('App\Models\LmsMaterial') ? \App\Models\LmsMaterial::count() : 0;
            $tugasCount = class_exists('App\Models\LmsAssignment') ? \App\Models\LmsAssignment::count() : 0;
            $guruCount = User::where(function($query) {
                // SINKRONISASI ROLE ADMIN PANEL
                $roles = ['Guru', 'Wali Kelas', 'Guru Mata Pelajaran', 'Guru Piket', 'Kepala Sekolah'];
                foreach ($roles as $role) {
                    $query->orWhere('role', 'LIKE', '%' . $role . '%');
                }
            })->count();

            return [
                'siswa' => Student::where('status', '!=', 'graduated')->count(),
                'guru'  => $guruCount,
                'rombel'=> SchoolClass::count(),
                'materi'=> $materiCount,
                'tugas' => $tugasCount,
            ];
        });

        // --- 6. DATA LAINNYA ---      
        $generalData = Cache::remember('landing_general_data', 7200, function() {
            return [
                'teachers' => User::where(function($query) {
                    // SINKRONISASI ROLE ADMIN PANEL
                    $roles = ['Guru', 'Wali Kelas', 'Guru Mata Pelajaran', 'Guru Piket', 'Kepala Sekolah'];
                    foreach ($roles as $role) {
                        $query->orWhere('role', 'LIKE', '%' . $role . '%');
                    }
                })->latest()->take(8)->get(),
                'announcements' => Announcement::orderBy('created_at', 'desc')->limit(3)->get(),
                // PERBAIKAN: Tambahkan where('status', 'approved')
                'achievements' => Achievement::with('student')->where('status', 'approved')->orderBy('date', 'desc')->limit(6)->get(),
                'activities' => SchoolActivity::latest()->take(3)->get(),
                'agendas' => Agenda::where('event_date', '>=', now()->subDays(1))->orderBy('event_date', 'asc')->limit(4)->get(),
                'guestbooks' => GuestBook::latest()->take(3)->get(),
                'allGuestbooks' => GuestBook::latest()->take(50)->get(),
                'extracurriculars' => Extracurricular::withCount('members')->with(['attendances' => function($query) { $query->latest('date')->limit(1); }])->get(),
            ];
        });
        extract($generalData); 
        
        // 7. DATA ALUMNI
        $alumniData = Cache::remember('landing_alumni_data', 300, function() {
            return [
                'stats' => [
                    'total' => Student::where('status', 'graduated')->count(),
                    'sma' => AlumniProfile::where('activity_status', 'SMA')->count(),
                    'smk' => AlumniProfile::where('activity_status', 'SMK')->count(),
                    'ma' => AlumniProfile::where('activity_status', 'MA')->count(),
                    'pesantren' => AlumniProfile::where('activity_status', 'Pesantren')->count(),
                    'bekerja' => AlumniProfile::whereIn('activity_status', ['Bekerja', 'Wirausaha', 'Lainnya'])->count(),
                ],
                'testimonials' => AlumniProfile::whereNotNull('testimony')
                    ->where('testimony', '!=', '') 
                    ->with('student') 
                    ->latest()
                    ->take(6) 
                    ->get()
            ];
        });
        $alumniStats = $alumniData['stats'];
        $alumniTestimonials = $alumniData['testimonials'];

        // --- 8. KATALOG E-BOOK ---
       $literacyData = Cache::remember('landing_literacy_data', 3600, function() {
            $books = collect([]);
            if (class_exists(Book::class)) {
                try {
                    $books = Book::whereNotNull('ebook_path')->latest()->take(4)->get();
                } catch (\Exception $e) {}
            }

        // --- 9. ARTIKEL GURU TERBARU ---
        $articles = collect([]);
            if (class_exists(TeacherArticle::class)) {
                try {
                    $articles = TeacherArticle::with('user')->whereNotNull('published_at')->latest('published_at')->take(3)->get();
                } catch (\Exception $e) {}
            }

            return ['books' => $books, 'articles' => $articles];
        });
        $latestBooks = $literacyData['books'];
        $latestArticles = $literacyData['articles'];

         // --- 10. DATA JADWAL UJIAN CBT ---
        // Menggunakan pencarian langsung tanpa Cache agar saat testing data langsung muncul.
        // Menyesuaikan dengan kolom is_active di model CbtExam.
        $publicExams = \App\Models\CbtExam::where('is_active', true)
            ->where(function($query) {
                $now = \Carbon\Carbon::now();
                $query->whereNull('end_time')
                      ->orWhere('end_time', '>=', $now);
            })
            ->orderBy('start_time', 'asc')
            ->take(6)
            ->get();

        $latestVideoActivity = \App\Models\SchoolActivity::whereNotNull('video_url')
            ->where('video_url', '!=', '')
            ->latest()
            ->first();

             // TAMBAHAN: Ambil 1 Pengumuman Terbaru untuk dijadikan Pop-up
        $popupAnnouncement = \App\Models\Announcement::latest()->first();

        // --- 11. DATA JADWAL PELAJARAN (TIMETABLE) ---
        // CATATAN PERBAIKAN: dengan 18 kelas x 41 JP/minggu, jadwal per hari bisa
        // berisi ratusan baris jika ditampilkan sekaligus. Maka jadwal dikelompokkan
        // per HARI *dan* per KELAS, lalu halaman publik hanya menampilkan jadwal
        // 1 kelas terpilih pada 1 hari terpilih (bukan seluruh 18 kelas sekaligus).
        // Key cache diganti versinya (_v2) karena struktur datanya berubah.
        $scheduleCache = Cache::remember('landing_public_schedules_v2', 3600, function() {
            if (class_exists(\App\Models\Timetable::class)) {
                $schedules = \App\Models\Timetable::with(['timeslot', 'teacher', 'subject', 'studentClass'])->get();

                $formatted = [
                    'Senin' => [], 'Selasa' => [], 'Rabu' => [], 'Kamis' => [], 'Jumat' => []
                ];
                $classNames = [];

                foreach ($schedules as $sched) {
                    $day = ucfirst(strtolower($sched->day_of_week)); 
                    if (!isset($formatted[$day])) continue; 
                    
                    $time = $sched->timeslot ? substr($sched->timeslot->start_time, 0, 5) . ' - ' . substr($sched->timeslot->end_time, 0, 5) : '00:00 - 00:00';
                    $subject = $sched->subject ? $sched->subject->name : 'Mata Pelajaran';
                    $teacher = $sched->teacher ? $sched->teacher->name : 'Guru';
                    $className = $sched->studentClass ? $sched->studentClass->name : 'Kelas';

                    $classNames[$className] = true;

                    // Dikelompokkan: [hari][nama_kelas] = array item jadwal
                    if (!isset($formatted[$day][$className])) {
                        $formatted[$day][$className] = [];
                    }

                    $formatted[$day][$className][] = [
                        'time' => $time,
                        'subject' => $subject,
                        'teacher' => $teacher,
                        'class' => $className,
                        'type' => 'pelajaran'
                    ];
                }
                
                // Urutkan jadwal tiap kelas, tiap harinya berdasarkan waktu
                foreach ($formatted as $day => &$classes) {
                    foreach ($classes as $cls => &$items) {
                        usort($items, function($a, $b) {
                            return strcmp($a['time'], $b['time']);
                        });
                    }
                }

                // Daftar nama kelas terurut, untuk isi dropdown filter kelas
                $classList = collect(array_keys($classNames))
                    ->sort(SORT_NATURAL | SORT_FLAG_CASE)
                    ->values()
                    ->all();
                
                return ['data' => $formatted, 'classes' => $classList];
            }
            return ['data' => [], 'classes' => []];
        });

        $publicSchedules = $scheduleCache['data'];
        $scheduleClasses = $scheduleCache['classes'];

         return view('welcome', compact(
            'stats', 'barChartData', 'libraryStats', 'libraryChartData', 
            'announcements', 'achievements', 'activities', 'teachers',
            'guestbooks', 'allGuestbooks', 'extracurriculars', 'agendas', 'schoolStats',
            'alumniStats', 'alumniTestimonials', 'habitLabels', 'habitData', 'habitStats',
            'latestBooks', 'latestArticles',
            'visitorCount',
            'publicExams',
            'latestVideoActivity',
            'popupAnnouncement',
            'publicSchedules',
            'scheduleClasses'
        ));
    }

    public function activities()
    {
        $activities = SchoolActivity::latest()->paginate(9);
        return view('activities', compact('activities'));
    }

    // PERBAIKAN: Fungsi Achievements 
    public function achievements(Request $request)
    {
        // Tambahkan where('status', 'approved') sebagai default filter
        $query = Achievement::with('student')->where('status', 'approved')->orderBy('date', 'desc');

        if ($request->has('level') && $request->level != 'Semua') {
            $query->where('level', $request->level);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $achievements = $query->paginate(12);
        
        // Pilihan filter level juga hanya mengambil yang approved
        $levels = Achievement::where('status', 'approved')->select('level')->distinct()->pluck('level');

        return view('achievements', compact('achievements', 'levels'));
    }

    public function teachers(Request $request)
    {
        $search = $request->input('q');
        $kategori = $request->input('kategori'); // Menangkap input kategori dari dropdown form
                
        $query = User::query();

        // 1. Logika Filter Berdasarkan Kategori (Telah Disinkronkan dengan Manajemen User)
        if ($kategori === 'guru') {
            $query->where(function($q) {
                // Role untuk kelompok pengajar
                $roles = ['Guru', 'Wali Kelas', 'Guru Mata Pelajaran', 'Guru Piket', 'Kepala Sekolah'];
                foreach ($roles as $role) {               
                    $q->orWhere('role', 'LIKE', '%' . $role . '%');
                }
            });
        } elseif ($kategori === 'staf') {
            $query->where(function($q) {
                // Role untuk kelompok staf non-pengajar (Sesuai dengan form Admin Panel)
                $roles = ['TU', 'Admin'];
                foreach ($roles as $role) {               
                    $q->orWhere('role', 'LIKE', '%' . $role . '%');
                }
            });
        } else {
            // Default (Semua Peran)
            $query->where(function($q) {
                $roles = ['Guru', 'Wali Kelas', 'Guru Mata Pelajaran', 'Guru Piket', 'Kepala Sekolah', 'TU', 'Admin'];
                foreach ($roles as $role) {               
                    $q->orWhere('role', 'LIKE', '%' . $role . '%');
                }
            });
        }

        // 2. Logika Filter Berdasarkan Pencarian Teks
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        $teachers = $query->orderBy('name', 'asc')->paginate(12);
        return view('teachers', compact('teachers'));
    }

    // ==============================================================
    // FUNGSI BARU UNTUK MENANGANI DETAIL/PORTOFOLIO GURU
    // ==============================================================
    public function teacherDetail($id)
    {
        
        $teacher = User::with([
            'experiences' => function($q) { $q->orderBy('year', 'desc'); },
            'materials' => function($q) { $q->latest(); },
            'portfolios' => function($q) { $q->orderBy('year', 'desc'); },
            'articles' => function($q) { $q->latest(); }
        ])->findOrFail($id);

        $experiences = $teacher->experiences;
        $materials   = $teacher->materials;
        $portfolios  = $teacher->portfolios;
        $articles    = $teacher->articles;

        // Diarahkan ke view 'teacher-detail' (di folder resources/views/)
        return view('teacher-detail', compact(
            'teacher', 
            'experiences', 
            'materials', 
            'portfolios', 
            'articles'
        ));
    }

    // ==============================================================
    // FUNGSI UNTUK DOWNLOAD CV GURU (PDF)
    // ==============================================================
    public function downloadCv($id)
    {
        
        $teacher = User::with([
            'experiences' => function($q) { $q->orderBy('year', 'desc'); },
            'portfolios' => function($q) { $q->orderBy('year', 'desc'); },
            'articles' => function($q) { $q->latest(); }
        ])->findOrFail($id);
       
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('teacher-cv', compact('teacher'));  
        $pdf->setPaper('A4', 'portrait');        
        $fileName = 'CV_' . preg_replace('/[^A-Za-z0-9\-]/', '_', $teacher->name) . '.pdf';
       
        return $pdf->download($fileName);
    }

    public function testimonials()
    {
        $testimonials = AlumniProfile::with('student')
            ->whereNotNull('testimony')
            ->where('testimony', '!=', '') 
            ->latest('updated_at') 
            ->paginate(12);

        return view('testimonials', compact('testimonials'));
    }

    // ==============================================================
    // FUNGSI UNTUK HALAMAN SEMUA ARTIKEL (INDEX ARTIKEL)
    // ==============================================================
    public function articles(Request $request)
    {       
        if (!class_exists(TeacherArticle::class)) {
            abort(404, 'Fitur Artikel belum tersedia.');
        }
        
        $query = TeacherArticle::with('user')->whereNotNull('published_at');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qUser) use ($search) {
                      $qUser->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
       
        $articles = $query->latest('published_at')->paginate(9);

        return view('articles.index', compact('articles'));
    }
}