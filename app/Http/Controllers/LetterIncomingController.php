<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LetterIncoming;
use App\Models\LetterSpt;
use App\Models\Sppd;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class LetterIncomingController extends Controller
{
    // MENAMPILKAN DATA (Logika Pencarian & Filter Asli Anda)
    public function index(Request $request)
    {
        $query = LetterIncoming::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('asal_surat', 'like', "%{$search}%");
            });
        }

        if ($request->has('sifat_surat') && $request->sifat_surat != '') {
            $query->where('sifat_surat', $request->sifat_surat);
        }
        
        $letters = $query->latest()->paginate(10)->withQueryString();

        return view('letters.incoming.index', compact('letters'));
    }

    // FORM TAMBAH (Logika Agenda Otomatis Asli Anda)
    public function create()
    {
        $lastLetter = LetterIncoming::latest('id')->first();
        
        if (!$lastLetter) {
            $nextAgenda = '0001';
        } else {
            $lastAgendaNumber = intval($lastLetter->nomor_agenda);
            $nextAgenda = str_pad($lastAgendaNumber + 1, 4, '0', STR_PAD_LEFT);
        }

        $users = User::orderBy('name', 'asc')->get();

        return view('letters.incoming.create', compact('nextAgenda', 'users'));
    }
    
    // SIMPAN DATA BARU
    public function store(Request $request)
    {
        $request->validate([
            'nomor_agenda' => 'required|string|unique:letter_incomings,nomor_agenda',
            'nomor_surat'  => 'required|string|max:255',
            'sifat_surat'  => 'required|string',
            'asal_surat'   => 'required|string|max:255',
            'tgl_surat'    => 'required|date',
            'tgl_diterima' => 'required|date',
            'perihal'      => 'required|string',
            'file_surat'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',

            // Validasi Integrasi SPT
            'guru_ditugaskan' => 'required_if:is_penugasan,on|array',
            'tgl_berangkat'   => 'required_if:is_penugasan,on|date|nullable',
            'tgl_kembali'     => 'required_if:is_penugasan,on|date|after_or_equal:tgl_berangkat|nullable',
        ]);

        $data = $request->except(['file_surat', 'is_penugasan', 'guru_ditugaskan', 'tgl_berangkat', 'tgl_kembali']);

        if ($request->hasFile('file_surat')) {
            $path = $request->file('file_surat')->store('surat-masuk', 'public');
            $data['file_path'] = $path;
        }

        $suratMasuk = LetterIncoming::create($data);

        // Integrasi Magic (Dipindahkan ke helper agar rapi)
        if ($request->has('is_penugasan') && $request->is_penugasan === 'on') {
            $this->syncPenugasan($suratMasuk, $request);
            return redirect()->route('sppd.index')
                ->with('success', 'Surat Masuk disimpan. Draft SPT dan SPPD telah otomatis dibuat!');
        }

        return redirect()->route('letters.incoming.index')
            ->with('success', 'Surat Masuk berhasil disimpan!');
    }

    // FORM EDIT (Menambahkan $users agar dropdown muncul)
    public function edit($id)
    {
        $letter = LetterIncoming::with('spt.users')->findOrFail($id);
        $users = User::orderBy('name', 'asc')->get();
        return view('letters.incoming.edit', compact('letter', 'users'));
    }

    // UPDATE DATA (Logika Update File Asli + Integrasi SPT)
    public function update(Request $request, $id)
    {
        $letter = LetterIncoming::findOrFail($id);

        $request->validate([
            'nomor_agenda' => 'required|string|unique:letter_incomings,nomor_agenda,' . $id, 
            'sifat_surat'  => 'required|string',
            'nomor_surat'  => 'required|string|max:255',
            'asal_surat'   => 'required|string|max:255',
            'tgl_surat'    => 'required|date',
            'tgl_diterima' => 'required|date',
            'perihal'      => 'required|string',
            'file_surat'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            
            // Tambahan validasi untuk edit integrasi
            'guru_ditugaskan' => 'required_if:is_penugasan,on|array',
            'tgl_berangkat'   => 'required_if:is_penugasan,on|date|nullable',
            'tgl_kembali'     => 'required_if:is_penugasan,on|date|after_or_equal:tgl_berangkat|nullable',
        ]);

        $data = $request->except(['file_surat', '_token', '_method', 'is_penugasan', 'guru_ditugaskan', 'tgl_berangkat', 'tgl_kembali']);

        if ($request->hasFile('file_surat')) {
            if ($letter->file_path && Storage::disk('public')->exists($letter->file_path)) {
                Storage::disk('public')->delete($letter->file_path);
            }
            $path = $request->file('file_surat')->store('surat-masuk', 'public');
            $data['file_path'] = $path;
        }

        $letter->update($data);

        // LOGIKA INTEGRASI PADA UPDATE
        if ($request->has('is_penugasan') && $request->is_penugasan === 'on') {
            $this->syncPenugasan($letter, $request);
            return redirect()->route('letters.incoming.index')
                ->with('success', 'Data Surat dan Penugasan berhasil diperbarui!');
        }

        return redirect()->route('letters.incoming.index')
            ->with('success', 'Data Surat berhasil diperbarui!');
    }

    // HAPUS DATA (Logika Hapus File Asli)
    public function destroy($id)
    {
        $letter = LetterIncoming::findOrFail($id);

        if ($letter->file_path && Storage::disk('public')->exists($letter->file_path)) {
            Storage::disk('public')->delete($letter->file_path);
        }

        $letter->delete();

        return redirect()->route('letters.incoming.index')
            ->with('success', 'Surat berhasil dihapus!');
    }

    // =========================================================================
    // HELPER: Sinkronisasi SPT & SPPD (Gabungan Logika Magic store & update)
    // =========================================================================
    private function syncPenugasan($letter, $request)
    {
        $start = Carbon::parse($request->tgl_berangkat);
        $end = Carbon::parse($request->tgl_kembali);
        $lama_hari = $start->diffInDays($end) + 1;
        $bulan_romawi = $this->getRomawi(date('n'));
        $tahun = date('Y');

        // 1. Cek apakah SPT sudah ada atau perlu buat baru
        $spt = LetterSpt::where('letter_incoming_id', $letter->id)->first();

        if (!$spt) {
            $last_spt_count = LetterSpt::whereYear('created_at', $tahun)->count() + 1;
            $nomor_spt = sprintf("094/%03d/SMP.03/Disdik/%s/%s", $last_spt_count, $bulan_romawi, $tahun);
            
            $spt = LetterSpt::create([
                'letter_incoming_id' => $letter->id,
                'nomor_spt'          => $nomor_spt,
                'untuk'              => 'Memenuhi Undangan/Panggilan: ' . $request->perihal,
                'tempat_tujuan'      => $request->asal_surat,
                'tgl_berangkat'      => $request->tgl_berangkat,
                'tgl_kembali'        => $request->tgl_kembali,
                'lama_hari'          => $lama_hari,
                'pejabat_nama'       => 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.',
                'pejabat_nip'        => '19820928 201101 1 002',
            ]);
        } else {
            // Update SPT yang sudah ada jika ada perubahan perihal/asal/tanggal
            $spt->update([
                'untuk'         => 'Memenuhi Undangan/Panggilan: ' . $request->perihal,
                'tempat_tujuan' => $request->asal_surat,
                'tgl_berangkat' => $request->tgl_berangkat,
                'tgl_kembali'   => $request->tgl_kembali,
                'lama_hari'     => $lama_hari,
            ]);
            // Reset SPPD & Relasi Pegawai agar bisa sinkron ulang dengan pilihan terbaru
            Sppd::where('spt_id', $spt->id)->delete();
            $spt->users()->detach();
        }

        // 2. Buat ulang relasi User & Data SPPD
        if ($request->has('guru_ditugaskan') && is_array($request->guru_ditugaskan)) {
            foreach ($request->guru_ditugaskan as $guru_id) {
                $spt->users()->attach($guru_id);
                
                $last_sppd_count = Sppd::whereYear('created_at', $tahun)->count() + 1;
                $nomor_sppd = sprintf("090/%03d/SMP.03/Disdik/%s/%s", $last_sppd_count, $bulan_romawi, $tahun);

                Sppd::create([
                    'spt_id'            => $spt->id,
                    'nomor_sppd'        => $nomor_sppd,
                    'user_id'           => $guru_id,
                    'maksud_perjalanan' => 'Memenuhi Undangan/Panggilan: ' . $request->perihal,
                    'tempat_berangkat'  => 'SMP Negeri 3 Lakbok',
                    'tempat_tujuan'     => $request->asal_surat,
                    'tgl_berangkat'     => $request->tgl_berangkat,
                    'tgl_kembali'       => $request->tgl_kembali,
                    'lama_hari'         => $lama_hari,
                    'instansi_pembayar' => 'SMP Negeri 3 Lakbok',
                    'pejabat_nama'      => 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.',
                    'pejabat_nip'       => '19820928 201101 1 002',
                    'pejabat_pangkat'   => 'Penata, III/c',
                    'pejabat_jabatan'   => 'Kepala Sekolah',
                ]);
            }
        }
    }

    private function getRomawi($bulan)
    {
        $map = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
        return $map[$bulan];
    }
}