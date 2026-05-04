<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LetterOutgoing;
use App\Models\LetterSpt;
use App\Models\Sppd;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class LetterOutgoingController extends Controller
{
    public function index(Request $request)
    {
        $query = LetterOutgoing::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('tujuan_surat', 'like', "%{$search}%");
            });
        }

        if ($request->has('sifat_surat') && $request->sifat_surat != '') {
            $query->where('sifat_surat', $request->sifat_surat);
        }
        
        $letters = $query->latest()->paginate(10)->withQueryString();
        return view('letters.outgoing.index', compact('letters'));
    }

    public function create()
    {
        $lastLetter = LetterOutgoing::latest('id')->first();
        $nextAgendaKeluar = $lastLetter ? str_pad(intval($lastLetter->nomor_agenda) + 1, 4, '0', STR_PAD_LEFT) : '0001';
        $users = User::orderBy('name', 'asc')->get();

        return view('letters.outgoing.create', compact('nextAgendaKeluar', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_agenda'  => 'required|string|unique:letter_outgoings,nomor_agenda',
            'nomor_surat'   => 'required|string|max:255',
            'tujuan_surat'  => 'required|string|max:255',
            'sifat_surat'   => 'required|string',
            'tgl_surat'     => 'required|date',
            'perihal'       => 'required|string',
            'file_surat'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'guru_ditugaskan' => 'required_if:is_penugasan,on|array',
            'tgl_berangkat'   => 'required_if:is_penugasan,on|date|nullable',
            'tgl_kembali'     => 'required_if:is_penugasan,on|date|after_or_equal:tgl_berangkat|nullable',
        ]);

        $dataSurat = $request->only(['nomor_agenda', 'nomor_surat', 'tujuan_surat', 'sifat_surat', 'tgl_surat', 'perihal']);
        
        if ($request->hasFile('file_surat')) {
            $dataSurat['file_path'] = $request->file('file_surat')->store('surat-keluar', 'public');
        }

        $suratKeluar = LetterOutgoing::create($dataSurat);

        if ($request->has('is_penugasan') && $request->is_penugasan === 'on') {
            $this->syncPenugasan($suratKeluar, $request);
            return redirect()->route('sppd.index')
                ->with('success', 'Surat Keluar disimpan. Draft SPT dan SPPD telah otomatis dibuat!');
        }

        return redirect()->route('letters.outgoing.index')
            ->with('success', 'Surat Keluar berhasil disimpan!');
    }

    public function edit($id)
    {
        $letter = LetterOutgoing::with('spt.users')->findOrFail($id);
        $users = User::orderBy('name', 'asc')->get();
        return view('letters.outgoing.edit', compact('letter', 'users'));
    }

    public function update(Request $request, $id)
    {
        $letter = LetterOutgoing::findOrFail($id);

        $request->validate([
            'nomor_agenda'  => 'required|string|unique:letter_outgoings,nomor_agenda,' . $id,
            'nomor_surat'   => 'required|string|max:255',
            'tujuan_surat'  => 'required|string|max:255',
            'sifat_surat'   => 'required|string',
            'tgl_surat'     => 'required|date',
            'perihal'       => 'required|string',
            'file_surat'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'guru_ditugaskan' => 'required_if:is_penugasan,on|array',
            'tgl_berangkat'   => 'required_if:is_penugasan,on|date|nullable',
            'tgl_kembali'     => 'required_if:is_penugasan,on|date|after_or_equal:tgl_berangkat|nullable',
        ]);

        $data = $request->except(['file_surat', '_token', '_method', 'is_penugasan', 'guru_ditugaskan', 'tgl_berangkat', 'tgl_kembali']);

        if ($request->hasFile('file_surat')) {
            if ($letter->file_path && Storage::disk('public')->exists($letter->file_path)) {
                Storage::disk('public')->delete($letter->file_path);
            }
            $data['file_path'] = $request->file('file_surat')->store('surat-keluar', 'public');
        }

        $letter->update($data);

        if ($request->has('is_penugasan') && $request->is_penugasan === 'on') {
            $this->syncPenugasan($letter, $request);
            return redirect()->route('letters.outgoing.index')
                ->with('success', 'Data Surat dan Penugasan berhasil diperbarui!');
        }

        return redirect()->route('letters.outgoing.index')
            ->with('success', 'Data Surat Keluar berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $letter = LetterOutgoing::findOrFail($id);
        if ($letter->file_path && Storage::disk('public')->exists($letter->file_path)) {
            Storage::disk('public')->delete($letter->file_path);
        }
        $letter->delete();
        return redirect()->route('letters.outgoing.index')->with('success', 'Surat Keluar berhasil dihapus!');
    }

    /**
     * HELPER: Sinkronisasi SPT & SPPD untuk Surat Keluar
     */
    private function syncPenugasan($letter, $request)
    {
        $start = Carbon::parse($request->tgl_berangkat);
        $end = Carbon::parse($request->tgl_kembali);
        $lama_hari = $start->diffInDays($end) + 1;
        $bulan_romawi = $this->getRomawi(date('n'));
        $tahun = date('Y');

        $spt = LetterSpt::where('surat_keluar_id', $letter->id)->first();

        if (!$spt) {
            $last_spt_count = LetterSpt::whereYear('created_at', $tahun)->count() + 1;
            $nomor_spt = sprintf("094/%03d/SMP.03/Disdik/%s/%s", $last_spt_count, $bulan_romawi, $tahun);
            
            $spt = LetterSpt::create([
                'surat_keluar_id' => $letter->id,
                'nomor_spt'       => $nomor_spt,
                'untuk'           => $request->perihal,
                'tempat_tujuan'   => $request->tujuan_surat,
                'tgl_berangkat'   => $request->tgl_berangkat,
                'tgl_kembali'     => $request->tgl_kembali,
                'lama_hari'       => $lama_hari,
                'pejabat_nama'    => 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.',
                'pejabat_nip'     => '19820928 201101 1 002',
            ]);
        } else {
            $spt->update([
                'untuk'         => $request->perihal,
                'tempat_tujuan' => $request->tujuan_surat,
                'tgl_berangkat' => $request->tgl_berangkat,
                'tgl_kembali'   => $request->tgl_kembali,
                'lama_hari'     => $lama_hari,
            ]);
            Sppd::where('spt_id', $spt->id)->delete();
            $spt->users()->detach();
        }

        if ($request->has('guru_ditugaskan') && is_array($request->guru_ditugaskan)) {
            foreach ($request->guru_ditugaskan as $guru_id) {
                $spt->users()->attach($guru_id);
                $last_sppd_count = Sppd::whereYear('created_at', $tahun)->count() + 1;
                $nomor_sppd = sprintf("090/%03d/SMP.03/Disdik/%s/%s", $last_sppd_count, $bulan_romawi, $tahun);

                Sppd::create([
                    'spt_id'            => $spt->id,
                    'nomor_sppd'        => $nomor_sppd,
                    'user_id'           => $guru_id,
                    'maksud_perjalanan' => $request->perihal,
                    'tempat_berangkat'  => 'SMP Negeri 3 Lakbok',
                    'tempat_tujuan'     => $request->tujuan_surat,
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