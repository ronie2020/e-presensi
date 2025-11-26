<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom-kolom Buku Induk ke tabel students sesuai referensi.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // A. KETERANGAN PRIBADI
            $table->string('nickname')->nullable()->after('name'); // Nama Panggilan
            $table->enum('gender', ['L', 'P'])->nullable()->after('nickname'); // Jenis Kelamin
            $table->string('pob')->nullable()->after('gender'); // Tempat Lahir
            $table->date('dob')->nullable()->after('pob'); // Tanggal Lahir
            $table->string('religion')->nullable(); // Agama
            $table->string('citizenship')->default('WNI'); // Kewarganegaraan
            $table->integer('birth_order')->nullable(); // Anak ke-
            $table->integer('siblings_count')->nullable(); // Jumlah Saudara Kandung
            $table->integer('step_siblings_count')->nullable(); // Jumlah Saudara Tiri
            $table->integer('adoptive_siblings_count')->nullable(); // Jumlah Saudara Angkat
            $table->enum('orphan_status', ['Yatim', 'Piatu', 'Yatim Piatu', 'Lengkap'])->default('Lengkap'); // Status Yatim
            $table->string('daily_language')->nullable(); // Bahasa Sehari-hari

            // B. TEMPAT TINGGAL
            $table->text('address')->nullable(); // Alamat
            $table->string('phone')->nullable(); // No Telp Rumah/HP
            $table->string('living_with')->nullable(); // Tinggal dengan (Ortu/Wali/Kost)
            $table->string('distance_to_school')->nullable(); // Jarak ke sekolah (km)
            $table->string('transport_mode')->nullable(); // Transportasi (Jalan kaki/Motor/dll)

            // C. KESEHATAN
            $table->decimal('weight', 5, 2)->nullable(); // Berat Badan (kg)
            $table->decimal('height', 5, 2)->nullable(); // Tinggi Badan (cm)
            $table->string('blood_type', 3)->nullable(); // Golongan Darah
            $table->text('history_disease')->nullable(); // Penyakit yg pernah diderita
            $table->text('physical_abnormalities')->nullable(); // Kelainan jasmani

            // D. PENDIDIKAN SEBELUMNYA
            $table->string('prev_school_name')->nullable(); // Asal Sekolah Dasar
            $table->string('prev_diploma_no')->nullable(); // No Ijazah SD
            $table->date('prev_exam_date')->nullable(); // Tanggal Ijazah
            $table->date('accepted_date')->nullable(); // Diterima tanggal
            $table->string('transfer_from_school')->nullable(); // Pindahan dari sekolah (jika ada)

            // E. ORANG TUA (AYAH)
            $table->string('father_name')->nullable();
            $table->string('father_pob')->nullable();
            $table->date('father_birth_year')->nullable(); // Tahun lahir saja atau full date
            $table->string('father_education')->nullable();
            $table->string('father_job')->nullable();
            $table->string('father_income')->nullable();
            
            // E. ORANG TUA (IBU)
            $table->string('mother_name')->nullable();
            $table->string('mother_pob')->nullable();
            $table->date('mother_birth_year')->nullable();
            $table->string('mother_education')->nullable();
            $table->string('mother_job')->nullable();
            $table->string('mother_income')->nullable();

            // F. WALI (Jika ada)
            $table->string('guardian_name')->nullable();
            $table->string('guardian_relationship')->nullable(); // Hubungan keluarga
            $table->string('guardian_education')->nullable();
            $table->string('guardian_job')->nullable();
            $table->string('guardian_income')->nullable();
            $table->text('guardian_address')->nullable();

            // G. INTELEGENSI & PRESTASI
            $table->string('iq_score')->nullable();
            $table->text('achievements')->nullable(); // Prestasi

            // LAINNYA
            $table->string('photo_path')->nullable(); // Foto Siswa
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Hapus kolom jika rollback (praktik yang baik)
            $table->dropColumn([
                'nickname', 'gender', 'pob', 'dob', 'religion', 'citizenship', 
                'birth_order', 'siblings_count', 'step_siblings_count', 'adoptive_siblings_count', 
                'orphan_status', 'daily_language', 'address', 'phone', 'living_with', 
                'distance_to_school', 'transport_mode', 'weight', 'height', 'blood_type', 
                'history_disease', 'physical_abnormalities', 'prev_school_name', 
                'prev_diploma_no', 'prev_exam_date', 'accepted_date', 'transfer_from_school',
                'father_name', 'father_pob', 'father_birth_year', 'father_education', 'father_job', 'father_income',
                'mother_name', 'mother_pob', 'mother_birth_year', 'mother_education', 'mother_job', 'mother_income',
                'guardian_name', 'guardian_relationship', 'guardian_education', 'guardian_job', 'guardian_income', 'guardian_address',
                'iq_score', 'achievements', 'photo_path'
            ]);
        });
    }
};