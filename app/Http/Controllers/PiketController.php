<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PiketSchedule;
use App\Models\PiketJournal;
use App\Models\PiketInval;
use App\Models\PiketGuest;
use App\Models\StudentPermit;
use App\Models\AcademicYear;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PiketController extends Controller
{
    /**
     * Halaman Pengaturan Master Jadwal Piket Mingguan (Senin - Sabtu)
     */
    public function schedulesIndex()
    {
        $activeYear = AcademicYear::active();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        
        $schedules = PiketSchedule::with('teacher')
            ->when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))
            ->orderBy('order_number')
            ->get()
            ->groupBy('day_name');

        $teachers = User::orderBy('name')->get();

        return view('permit.schedules', compact('schedules', 'days', 'teachers', 'activeYear'));
    }

    /**
     * Simpan / Tambah Guru ke Jadwal Piket Mingguan
     */
    public function schedulesStore(Request $request)
    {
        $request->validate([
            'user_id'       => 'required|exists:users,id',
            'day_name'      => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'post_location' => 'nullable|string|max:100',
            'is_coordinator'=> 'nullable|boolean',
        ]);

        $activeYear = AcademicYear::active();

        // Cek apakah guru sudah dijadwalkan pada hari yang sama
        $exists = PiketSchedule::where('user_id', $request->user_id)
            ->where('day_name', $request->day_name)
            ->when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))
            ->exists();

        if ($exists) {
            return back()->with('error', 'Guru tersebut sudah terjadwal piket pada hari ' . $request->day_name . '!');
        }

        // Hitung urutan terakhir
        $lastOrder = PiketSchedule::where('day_name', $request->day_name)->max('order_number') ?? 0;

        PiketSchedule::create([
            'academic_year_id' => $activeYear->id ?? null,
            'user_id'          => $request->user_id,
            'day_name'         => $request->day_name,
            'post_location'    => $request->post_location ?: 'Lobi Utama',
            'is_coordinator'   => $request->boolean('is_coordinator'),
            'order_number'     => $lastOrder + 1,
        ]);

        return back()->with('success', 'Petugas piket berhasil ditambahkan ke jadwal ' . $request->day_name . '.');
    }

    /**
     * Hapus Guru dari Jadwal Piket
     */
    public function schedulesDestroy($id)
    {
        $schedule = PiketSchedule::findOrFail($id);
        $schedule->delete();

        return back()->with('success', 'Petugas piket berhasil dihapus dari jadwal.');
    }

    /**
     * Simpan / Update Catatan Umum & Cuaca Jurnal Piket
     */
    public function saveJournal(Request $request)
    {
        $request->validate([
            'date'           => 'required|date',
            'coordinator_id' => 'nullable|exists:users,id',
            'weather'        => 'nullable|string|max:50',
            'general_notes'  => 'nullable|string',
            'status'         => 'nullable|in:draft,submitted,verified',
        ]);

        $activeYear = AcademicYear::active();

        $journal = PiketJournal::updateOrCreate(
            ['date' => $request->date],
            [
                'academic_year_id' => $activeYear->id ?? null,
                'coordinator_id'   => $request->coordinator_id,
                'weather'          => $request->weather ?: 'Cerah',
                'general_notes'    => $request->general_notes,
                'status'           => $request->status ?: 'draft',
            ]
        );

        if ($request->wantsJson()) {
            return response()->json(['status' => 'success', 'message' => 'Catatan jurnal piket berhasil disimpan.', 'journal' => $journal]);
        }

        return back()->with('success', 'Catatan jurnal piket berhasil disimpan.');
    }

    /**
     * Tambah Catatan Guru Absen & Penugasan Inval (Mendukung Multi-Kelas / Lebih dari 1 Kelas)
     */
    public function storeInval(Request $request)
    {
        $request->validate([
            'piket_journal_id'          => 'required|exists:piket_journals,id',
            'absent_teacher_id'         => 'required|exists:users,id',
            'reason'                    => 'required|string|max:50',
            'slots'                     => 'nullable|array',
            'slots.*.class_id'          => 'nullable|exists:classes,id',
            'slots.*.subject_id'        => 'nullable|exists:subjects,id',
            'slots.*.timeslot_info'     => 'nullable|string|max:100',
            'slots.*.inval_teacher_id'  => 'nullable|exists:users,id',
            'slots.*.assignment_notes'  => 'nullable|string',
            // Fallback single row
            'class_id'                  => 'nullable|exists:classes,id',
            'subject_id'                => 'nullable|exists:subjects,id',
            'timeslot_info'             => 'nullable|string|max:100',
            'inval_teacher_id'          => 'nullable|exists:users,id',
            'assignment_notes'          => 'nullable|string',
        ]);

        $createdCount = 0;

        // 1. Jika dikirim dalam bentuk array multi-kelas (slots)
        if ($request->has('slots') && is_array($request->slots) && count($request->slots) > 0) {
            foreach ($request->slots as $slot) {
                // Simpan jika setidaknya ada kelas, mapel, atau info jam yang diisi
                if (!empty($slot['class_id']) || !empty($slot['subject_id']) || !empty($slot['timeslot_info'])) {
                    PiketInval::create([
                        'piket_journal_id'  => $request->piket_journal_id,
                        'absent_teacher_id' => $request->absent_teacher_id,
                        'reason'            => $request->reason,
                        'class_id'          => !empty($slot['class_id']) ? $slot['class_id'] : null,
                        'subject_id'        => !empty($slot['subject_id']) ? $slot['subject_id'] : null,
                        'timeslot_info'     => !empty($slot['timeslot_info']) ? $slot['timeslot_info'] : null,
                        'inval_teacher_id'  => !empty($slot['inval_teacher_id']) ? $slot['inval_teacher_id'] : null,
                        'assignment_notes'  => !empty($slot['assignment_notes']) ? $slot['assignment_notes'] : null,
                        'status'            => !empty($slot['inval_teacher_id']) ? 'Terisi' : 'Menunggu Pengganti',
                    ]);
                    $createdCount++;
                }
            }
        }

        // 2. Fallback jika dikirim format single row lama
        if ($createdCount === 0) {
            PiketInval::create([
                'piket_journal_id'  => $request->piket_journal_id,
                'absent_teacher_id' => $request->absent_teacher_id,
                'reason'            => $request->reason,
                'class_id'          => $request->class_id,
                'subject_id'        => $request->subject_id,
                'timeslot_info'     => $request->timeslot_info,
                'inval_teacher_id'  => $request->inval_teacher_id,
                'assignment_notes'  => $request->assignment_notes,
                'status'            => $request->inval_teacher_id ? 'Terisi' : 'Menunggu Pengganti',
            ]);
            $createdCount = 1;
        }

        return back()->with('success', "Berhasil mencatat {$createdCount} jadwal mengajar & inval untuk guru tersebut.");
    }

    /**
     * Update Data Inval / Assign Guru Pengganti
     */
    public function updateInval(Request $request, $id)
    {
        $request->validate([
            'absent_teacher_id' => 'sometimes|required|exists:users,id',
            'reason'            => 'sometimes|required|string|max:50',
            'class_id'          => 'sometimes|required|exists:classes,id',
            'subject_id'        => 'sometimes|required|exists:subjects,id',
            'timeslot_info'     => 'nullable|string|max:100',
            'inval_teacher_id'  => 'nullable|exists:users,id',
            'assignment_notes'  => 'nullable|string',
            'status'            => 'nullable|string|max:50',
        ]);

        $inval = PiketInval::findOrFail($id);

        $updateData = [];

        if ($request->has('absent_teacher_id')) {
            $updateData['absent_teacher_id'] = $request->absent_teacher_id;
        }
        if ($request->has('reason')) {
            $updateData['reason'] = $request->reason;
        }
        if ($request->has('class_id')) {
            $updateData['class_id'] = $request->class_id;
        }
        if ($request->has('subject_id')) {
            $updateData['subject_id'] = $request->subject_id;
        }
        if ($request->has('timeslot_info')) {
            $updateData['timeslot_info'] = $request->timeslot_info;
        }
        if ($request->has('inval_teacher_id')) {
            $updateData['inval_teacher_id'] = $request->inval_teacher_id ?: null;
        }
        if ($request->has('assignment_notes')) {
            $updateData['assignment_notes'] = $request->assignment_notes;
        }

        if ($request->filled('status')) {
            $updateData['status'] = $request->status;
        } else {
            $invalTeacher = array_key_exists('inval_teacher_id', $updateData) ? $updateData['inval_teacher_id'] : $inval->inval_teacher_id;
            $updateData['status'] = $invalTeacher ? 'Terisi' : 'Menunggu Pengganti';
        }

        $inval->update($updateData);

        return back()->with('success', 'Data inval & guru berhalangan hadir berhasil diperbarui.');
    }

    /**
     * Hapus Catatan Inval
     */
    public function destroyInval($id)
    {
        $inval = PiketInval::findOrFail($id);
        $inval->delete();

        return back()->with('success', 'Catatan inval berhasil dihapus.');
    }

    /**
     * Tambah Tamu Meja Piket
     */
    public function storeGuest(Request $request)
    {
        $request->validate([
            'piket_journal_id' => 'required|exists:piket_journals,id',
            'guest_name'       => 'required|string|max:150',
            'institution'      => 'nullable|string|max:150',
            'phone'            => 'nullable|string|max:50',
            'meet_with'        => 'nullable|string|max:100',
            'purpose'          => 'required|string',
            'time_in'          => 'nullable|date_format:H:i',
            'is_vip'           => 'nullable|boolean',
        ]);

        $timeIn = $request->time_in ?: Carbon::now(config('app.timezone', 'Asia/Jakarta'))->format('H:i');

        PiketGuest::create([
            'piket_journal_id' => $request->piket_journal_id,
            'guest_name'       => $request->guest_name,
            'institution'      => $request->institution,
            'phone'            => $request->phone,
            'meet_with'        => $request->meet_with,
            'purpose'          => $request->purpose,
            'time_in'          => $timeIn,
            'is_vip'           => $request->boolean('is_vip'),
        ]);

        return back()->with('success', 'Buku tamu piket berhasil ditambahkan.');
    }

    /**
     * Tamu Check-Out / Pulang
     */
    public function checkoutGuest($id)
    {
        $guest = PiketGuest::findOrFail($id);
        $guest->update([
            'time_out' => Carbon::now(config('app.timezone', 'Asia/Jakarta'))->format('H:i')
        ]);

        return back()->with('success', 'Tamu ' . $guest->guest_name . ' berhasil check-out.');
    }

    /**
     * Hapus Tamu
     */
    public function destroyGuest($id)
    {
        $guest = PiketGuest::findOrFail($id);
        $guest->delete();

        return back()->with('success', 'Data tamu piket berhasil dihapus.');
    }

    /**
     * Cetak Lembar Buku Piket Harian Resmi (PDF Ready)
     */
    public function printJournal(Request $request)
    {
        $date = $request->date ?: Carbon::now(config('app.timezone', 'Asia/Jakarta'))->toDateString();
        $dateObj = Carbon::parse($date)->locale('id');
        $dayNames = [0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'];
        $dayName = $dayNames[$dateObj->dayOfWeek];

        $journal = PiketJournal::with([
            'coordinator',
            'invals.absentTeacher',
            'invals.invalTeacher',
            'invals.schoolClass',
            'invals.subject',
            'guests'
        ])->where('date', $date)->first();

        // Ambil Petugas Piket Terjadwal
        $dutyTeachers = PiketSchedule::with('teacher')
            ->where('day_name', $dayName)
            ->orderBy('order_number')
            ->get();

        // Ambil Siswa Izin Keluar pada Hari Tersebut
        $permits = StudentPermit::with(['student.schoolClass'])
            ->whereDate('time_out', $date)
            ->orderBy('time_out', 'asc')
            ->get();

        $title = 'Buku Jurnal Piket Harian - ' . $dateObj->translatedFormat('l, d F Y');

        return view('permit.print_journal', compact('journal', 'date', 'dateObj', 'dayName', 'dutyTeachers', 'permits', 'title'));
    }
}
