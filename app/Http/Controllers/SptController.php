<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LetterSpt;      
use App\Models\LetterIncoming; 
use App\Models\User;           
use Carbon\Carbon;

class SptController extends Controller
{
    public function index(Request $request)
    {
        $query = LetterSpt::with(['users', 'letterIncoming']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_spt', 'like', "%{$search}%")
                  ->orWhere('tempat_tujuan', 'like', "%{$search}%")
                  ->orWhere('untuk', 'like', "%{$search}%")
                  ->orWhereHas('users', function($qUser) use ($search) {
                      $qUser->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $spts = $query->latest()->paginate(10);
        return view('letters.spt.index', compact('spts'));
    }

    public function create(Request $request)
    {
        $users = User::orderBy('name', 'asc')->get();
        $incoming_letters = LetterIncoming::latest()->get();
        $selected_letter_id = $request->get('from_letter');

        $bulan_romawi = $this->getRomawi(date('n'));
        $tahun = date('Y');
        $last_count = LetterSpt::whereYear('created_at', $tahun)->count() + 1;
        $nomor_otomatis = sprintf("094/%03d/SMP.03/Disdik/%s/%s", $last_count, $bulan_romawi, $tahun);

        return view('letters.spt.create', compact('users', 'incoming_letters', 'selected_letter_id', 'nomor_otomatis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_spt' => 'required',
            'pegawai_ids' => 'required|array|min:1', 
            'untuk' => 'required',
            'tempat' => 'required',
            'tgl_berangkat' => 'required|date',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_berangkat',
        ]);

        $start = Carbon::parse($request->tgl_berangkat);
        $end = Carbon::parse($request->tgl_kembali);
        $lama_hari = $start->diffInDays($end) + 1;

        $spt = LetterSpt::create([
            'letter_incoming_id' => $request->letter_incoming_id,
            'nomor_spt' => $request->nomor_spt,
            'untuk' => $request->untuk,
            'tempat_tujuan' => $request->tempat,
            'tgl_berangkat' => $request->tgl_berangkat,
            'tgl_kembali' => $request->tgl_kembali,
            'lama_hari' => $lama_hari,
            'pejabat_nama' => 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.',
            'pejabat_nip' => '19820928 201101 1 002',
        ]);

        $spt->users()->attach($request->pegawai_ids);

        return redirect()->route('letters.spt.index')
            ->with('success', 'Surat Perintah Tugas berhasil dibuat!');
    }

    // --- FITUR EDIT & UPDATE ---

    public function edit($id)
    {
        $spt = LetterSpt::with('users')->findOrFail($id);
        $users = User::orderBy('name', 'asc')->get();
        $incoming_letters = LetterIncoming::latest()->get();

        return view('letters.spt.edit', compact('spt', 'users', 'incoming_letters'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor_spt' => 'required',
            'pegawai_ids' => 'required|array|min:1', 
            'untuk' => 'required',
            'tempat' => 'required',
            'tgl_berangkat' => 'required|date',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_berangkat',
        ]);

        $spt = LetterSpt::findOrFail($id);

        $start = Carbon::parse($request->tgl_berangkat);
        $end = Carbon::parse($request->tgl_kembali);
        $lama_hari = $start->diffInDays($end) + 1;

        $spt->update([
            'letter_incoming_id' => $request->letter_incoming_id,
            'nomor_spt' => $request->nomor_spt,
            'untuk' => $request->untuk,
            'tempat_tujuan' => $request->tempat,
            'tgl_berangkat' => $request->tgl_berangkat,
            'tgl_kembali' => $request->tgl_kembali,
            'lama_hari' => $lama_hari,
        ]);

        // Sync untuk memperbarui daftar pegawai (menghapus yang lama, memasukkan yang baru dipilih)
        $spt->users()->sync($request->pegawai_ids);

        return redirect()->route('letters.spt.index')
            ->with('success', 'Perubahan SPT berhasil disimpan!');
    }

    public function destroy($id)
    {
        $spt = LetterSpt::findOrFail($id);
        $spt->users()->detach();
        $spt->delete();

        return redirect()->route('letters.spt.index')
            ->with('success', 'Surat Perintah Tugas berhasil dihapus!');
    }

    public function print($id)
    {
        $spt = LetterSpt::with(['users', 'letterIncoming'])->findOrFail($id);
        return view('letters.spt.print', compact('spt'));
    }

    private function getRomawi($bulan)
    {
        $map = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
        return $map[$bulan];
    }
}