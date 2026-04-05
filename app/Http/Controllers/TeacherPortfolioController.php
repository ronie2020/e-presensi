<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class TeacherPortfolioController extends Controller
{
    /**
     * Helper Privasi: Menentukan siapa pemilik portofolio yang sedang dikelola.     
     */
    private function getTargetUser(Request $request)
    {
        $currentUser = Auth::user();
        
        // Jika terdapat request user_id (ingin melihat/mengubah porto orang lain)
        // Pastikan HANYA Admin yang diizinkan melakukannya.
        if ($request->filled('user_id') && $currentUser->hasRole('Admin')) {
            return User::findOrFail($request->user_id);
        }
        
        // Jika bukan admin atau tidak ada request user_id, 
        // paksa kembalikan profil user yang sedang login saat ini.
        return $currentUser;
    }

    /**
     * Tampilkan halaman dashboard manajemen portofolio
     */
    public function index(Request $request)
    {
        $targetUser = $this->getTargetUser($request);

        $experiences = $targetUser->experiences()->orderBy('year', 'desc')->get();
        $materials = $targetUser->materials()->latest()->get();
        $portfolios = $targetUser->portfolios()->orderBy('year', 'desc')->get();
        $articles = $targetUser->articles()->latest()->get();
        $educations = $targetUser->educations()->orderBy('start_year', 'desc')->get();

        return view('portfolio.index', compact('experiences', 'materials', 'portfolios', 'articles', 'educations', 'targetUser'));
    }

    // ==========================================
    // CRUD PENDIDIKAN (EDUCATION)
    // ==========================================
    public function storeEducation(Request $request)
    {
        $request->validate([
            'institution' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'start_year' => 'nullable|string|max:4',
            'end_year' => 'nullable|string|max:4',
        ]);

        $targetUser = $this->getTargetUser($request);
        $targetUser->educations()->create($request->only('institution', 'degree', 'start_year', 'end_year'));
        
        return back()->with('success', 'Riwayat Pendidikan berhasil ditambahkan.');
    }

    public function destroyEducation(Request $request, $id)
    {
        $targetUser = $this->getTargetUser($request);
        $edu = $targetUser->educations()->findOrFail($id);
        $edu->delete();
        
        return back()->with('success', 'Riwayat Pendidikan berhasil dihapus.');
    }
    
    public function updateEducation(Request $request, $id)
    {
        $request->validate([
            'institution' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'start_year' => 'nullable|string|max:4',
            'end_year' => 'nullable|string|max:4',
        ]);

        $targetUser = $this->getTargetUser($request);
        $edu = $targetUser->educations()->findOrFail($id);
        $edu->update($request->only('institution', 'degree', 'start_year', 'end_year'));
        
        return back()->with('success', 'Riwayat Pendidikan berhasil diperbarui.');
    }

    // ==========================================
    // CRUD PENGALAMAN & PELATIHAN
    // ==========================================
    public function storeExperience(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'nullable|string|max:10',
            'organizer' => 'nullable|string|max:255',
            'certificate' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only('title', 'year', 'organizer');

        if ($request->hasFile('certificate')) {
            $data['certificate_path'] = $request->file('certificate')->store('teacher_certificates', 'public');
        }

        $targetUser = $this->getTargetUser($request);
        $targetUser->experiences()->create($data);
        
        return back()->with('success', 'Pengalaman/Pelatihan berhasil ditambahkan.');
    }

    public function destroyExperience(Request $request, $id)
    {
        $targetUser = $this->getTargetUser($request);
        $exp = $targetUser->experiences()->findOrFail($id);
        
        if ($exp->certificate_path) {
            Storage::disk('public')->delete($exp->certificate_path);
        }
        
        $exp->delete();
        
        return back()->with('success', 'Data berhasil dihapus.');
    }

    public function updateExperience(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'nullable|string|max:10',
            'organizer' => 'nullable|string|max:255',
            'certificate' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);

        $targetUser = $this->getTargetUser($request);
        $exp = $targetUser->experiences()->findOrFail($id);
        
        $data = $request->only('title', 'year', 'organizer');

        if ($request->hasFile('certificate')) {
            if ($exp->certificate_path) {
                Storage::disk('public')->delete($exp->certificate_path);
            }
            $data['certificate_path'] = $request->file('certificate')->store('teacher_certificates', 'public');
        }

        $exp->update($data);
        
        return back()->with('success', 'Pengalaman/Pelatihan berhasil diperbarui.');
    }

    // ==========================================
    // CRUD MATERI & MEDIA
    // ==========================================
    public function storeMaterial(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'nullable|string|max:100',
            'file' => 'nullable|file|mimes:pdf,ppt,pptx,doc,docx,zip|max:5120',
            'file_url' => 'nullable|url',
        ]);

        $data = $request->only('title', 'type', 'file_url');
        
        $type = strtolower($request->type);
        if (str_contains($type, 'pdf')) $data['icon'] = 'ph-file-pdf text-red-500';
        elseif (str_contains($type, 'ppt') || str_contains($type, 'slide')) $data['icon'] = 'ph-file-slides text-orange-500';
        elseif (str_contains($type, 'video')) $data['icon'] = 'ph-file-video text-blue-500';
        else $data['icon'] = 'ph-file-text text-slate-500';

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('teacher_materials', 'public');
            $data['file_url'] = asset('storage/' . $data['file_path']);
        }

        $targetUser = $this->getTargetUser($request);
        $targetUser->materials()->create($data);
        
        return back()->with('success', 'Materi/Media berhasil ditambahkan.');
    }

    public function destroyMaterial(Request $request, $id)
    {
        $targetUser = $this->getTargetUser($request);
        $mat = $targetUser->materials()->findOrFail($id);
        
        if ($mat->file_path) Storage::disk('public')->delete($mat->file_path);
        $mat->delete();
        
        return back()->with('success', 'Materi berhasil dihapus.');
    }

     public function updateMaterial(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'nullable|string|max:100',
            'file' => 'nullable|file|mimes:pdf,ppt,pptx,doc,docx,zip|max:5120',
            'file_url' => 'nullable|url',
        ]);

        $targetUser = $this->getTargetUser($request);
        $mat = $targetUser->materials()->findOrFail($id);

        $data = $request->only('title', 'type', 'file_url');
        
        $type = strtolower($request->type ?? '');
        if (str_contains($type, 'pdf')) $data['icon'] = 'ph-file-pdf text-red-500';
        elseif (str_contains($type, 'ppt') || str_contains($type, 'slide')) $data['icon'] = 'ph-file-slides text-orange-500';
        elseif (str_contains($type, 'video')) $data['icon'] = 'ph-file-video text-blue-500';
        else $data['icon'] = 'ph-file-text text-slate-500';

        if ($request->hasFile('file')) {
            if ($mat->file_path) Storage::disk('public')->delete($mat->file_path);
            
            $data['file_path'] = $request->file('file')->store('teacher_materials', 'public');
            $data['file_url'] = asset('storage/' . $data['file_path']);
        }

        $mat->update($data);
        
        return back()->with('success', 'Materi/Media berhasil diperbarui.');
    }

    // ==========================================
    // CRUD PORTOFOLIO GURU
    // ==========================================
    public function storePortfolio(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'nullable|string|max:10',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only('title', 'year');
        $data['image_path'] = $request->file('image')->store('teacher_portfolios', 'public');

        $targetUser = $this->getTargetUser($request);
        $targetUser->portfolios()->create($data);
        
        return back()->with('success', 'Portofolio/Pencapaian berhasil ditambahkan.');
    }

    public function destroyPortfolio(Request $request, $id)
    {
        $targetUser = $this->getTargetUser($request);
        $port = $targetUser->portfolios()->findOrFail($id);
        
        if ($port->image_path) Storage::disk('public')->delete($port->image_path);
        $port->delete();
        
        return back()->with('success', 'Portofolio berhasil dihapus.');
    }

    public function updatePortfolio(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'nullable|string|max:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $targetUser = $this->getTargetUser($request);
        $port = $targetUser->portfolios()->findOrFail($id);

        $data = $request->only('title', 'year');
        
        if ($request->hasFile('image')) {
            if ($port->image_path) Storage::disk('public')->delete($port->image_path);
            $data['image_path'] = $request->file('image')->store('teacher_portfolios', 'public');
        }

        $port->update($data);
        
        return back()->with('success', 'Portofolio/Pencapaian berhasil diperbarui.');
    }


    // ==========================================
    // CRUD ARTIKEL TERPUBLIKASI
    // ==========================================
    public function storeArticle(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'excerpt' => 'nullable|string|max:500',
            'url' => 'nullable|url',
            'published_at' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only('title', 'category', 'excerpt', 'url', 'published_at');
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('teacher_articles', 'public');
        }

        $targetUser = $this->getTargetUser($request);
        $targetUser->articles()->create($data);
        
        return back()->with('success', 'Artikel berhasil ditambahkan.');
    }

     public function destroyArticle(Request $request, $id)
    {
        $targetUser = $this->getTargetUser($request);
        $art = $targetUser->articles()->findOrFail($id);
        
        if ($art->image_path) Storage::disk('public')->delete($art->image_path);
        $art->delete();
        
        return back()->with('success', 'Artikel berhasil dihapus.');
    }

    public function updateArticle(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'excerpt' => 'nullable|string|max:500',
            'url' => 'nullable|url',
            'published_at' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $targetUser = $this->getTargetUser($request);
        $art = $targetUser->articles()->findOrFail($id);

        $data = $request->only('title', 'category', 'excerpt', 'url', 'published_at');
        
        if ($request->hasFile('image')) {
            if ($art->image_path) Storage::disk('public')->delete($art->image_path);
            $data['image_path'] = $request->file('image')->store('teacher_articles', 'public');
        }

        $art->update($data);
        
        return back()->with('success', 'Artikel berhasil diperbarui.');
    }
}