<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DataSurveiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // karena sudah dilindungi middleware auth.admin
    }

    public function rules(): array
    {
        return [
            'judul'          => ['required', 'string', 'max:255'],
            'ketua_tim'      => ['required', 'string', 'max:100'], // Tambahkan validasi
            'tahun'          => ['required', 'integer', 'min:1900', 'max:9999'],
            'tipe'            => ['required', 'string', Rule::in(['2D', '3D', 'HR', 'Lainnya'])],
            'wilayah'        => ['required', 'string', 'max:255'],
            'deskripsi'      => ['nullable', 'string'],
            'gambar_pratinjau' => [
                'nullable', 
                'file', 
                'image',  // This validates it's actually an image
                'mimes:jpeg,png,jpg', 
                'mimetypes:image/jpeg,image/png,image/jpg', 
                'max:5120',
                'dimensions:min_width=100,min_height=100,max_width=5000,max_height=5000'
            ],
            // File scan asli (optional - untuk pegawai internal)
            'file_scan_asli' => [
                'nullable',
                'file',
                'mimes:pdf,tiff,tif,png,jpeg,jpg,zip,rar', // Format yang didukung
                'max:614400', // 600MB = 614400 KB (naik dari 5MB)
            ],
            'tautan_file'    => ['nullable', 'string', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required'    => 'Judul survei wajib diisi.',
            'ketua_tim.required' => 'Ketua tim wajib diisi.', // Tambahkan pesan
            'ketua_tim.max'     => 'Ketua tim maksimal 100 karakter.',
            'tahun.required'    => 'Tahun wajib diisi.',
            'tahun.integer'     => 'Tahun harus berupa angka.',
            'tipe.required'     => 'Tipe survei wajib dipilih.',
            'tipe.in'           => 'Tipe hanya boleh 2D, 3D, HR, atau Lainnya.',
            'wilayah.required'  => 'Wilayah / blok wajib diisi.',
            'gambar_pratinjau.image' => 'File harus berupa gambar yang valid (JPEG atau PNG).',
            'gambar_pratinjau.mimes' => 'Format file tidak didukung. Hanya JPEG, PNG, atau JPG yang diperbolehkan.',
            'gambar_pratinjau.mimetypes' => 'Tipe konten file tidak valid. File harus berupa gambar asli.',
            'gambar_pratinjau.max' => 'Ukuran gambar maksimal 5MB.',
            'gambar_pratinjau.dimensions' => 'Dimensi gambar harus minimal 100x100 piksel dan maksimal 5000x5000 piksel.',
            'file_scan_asli.file' => 'File scan asli harus berupa file yang valid.',
            'file_scan_asli.mimes' => 'Format file scan asli hanya mendukung: PDF, TIFF, PNG, JPEG, JPG, ZIP, RAR.',
            'file_scan_asli.max' => 'Ukuran file scan asli maksimal 600MB.',
        ];
    }
}